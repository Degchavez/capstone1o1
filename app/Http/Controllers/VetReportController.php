<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Animal;
use App\Models\Barangay;
use App\Models\Category;
use App\Models\Designation;
use App\Models\Owner;
use App\Models\Report;
use App\Models\Species;
use App\Models\Transaction;
use App\Models\TransactionType;
use App\Models\User;
use App\Models\Vaccine;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\TransactionSubtype;
use PDF;


class VetReportController extends Controller
{
    public function index()
    {
        // Get data needed for report forms
        $recentReports = Report::where('user_id', auth()->user()->user_id)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        $transactionTypes = TransactionType::with('subtypes')->get();
        $species = Species::with('breeds')->get();
        $vaccines = Vaccine::all();
        $barangays = Barangay::all();
        $categories = Category::all();
        $designations = Designation::all();

        return view('receptionist.reportEngine', compact(
            'recentReports', 
            'transactionTypes', 
            'species', 
            'vaccines', 
            'barangays', 
            'categories', 
            'designations'
        ));
    }

    public function transactionReportView(Request $request)
    {
        // Optional filters from request
        $filters = $request->only([
            'transaction_type_id',
            'transaction_subtype_id',
            'status',
            'date_from',
            'date_to'
        ]);
    
        // Default to last year to now if dates are not provided
        $dateFrom = \Carbon\Carbon::parse($filters['date_from'] ?? now()->subYear());
        $dateTo = \Carbon\Carbon::parse($filters['date_to'] ?? now());
    
        // Fetch transactions (no filter requirement)
        $transactions = Transaction::with('transactionType', 'transactionSubtype', 'owner', 'animal.species', 'animal.breed')
            ->whereBetween('created_at', [$dateFrom, $dateTo])
            ->when($request->filled('transaction_type_id'), function ($query) use ($filters) {
                return $query->where('transaction_type_id', $filters['transaction_type_id']);
            })
            ->when($request->filled('transaction_subtype_id'), function ($query) use ($filters) {
                return $query->where('transaction_subtype_id', $filters['transaction_subtype_id']);
            })
            ->when($request->filled('status'), function ($query) use ($filters) {
                return $query->where('status', $filters['status']);
            })
            ->get();
    
        // Generate summary
        $summary = $this->generateSummaryStatistics($transactions);
    
        // Create report record
        $report = Report::create([
            'user_id' => auth()->user()->user_id,
            'report_type' => 'transactions',
            'date_from' => $dateFrom->toDateString(),
            'date_to' => $dateTo->toDateString(),
            'parameters' => $filters,
            'generated_by' => auth()->user()->user_id,
            'status' => 'completed',
            'file_path' => '',
        ]);
    
        // Load the PDF view
        $pdf = PDF::loadView('reports.pdf.receptionist.transactions', [
            'veterinarian' => auth()->user(),
            'dateFrom' => $dateFrom,
            'dateTo' => $dateTo,
            'filters' => $filters,
            'summary' => $summary,
            'transactions' => $transactions,
        ]);
    
        // Save PDF file
        $fileName = 'transaction_report_' . now()->format('Y_m_d_H_i_s') . '.pdf';
        $filePath = 'reports/' . $fileName;
    
        if (!Storage::disk('public')->put($filePath, $pdf->output())) {
            throw new \Exception('Failed to save the PDF file.');
        }
    
        // Update report with file path
        $report->update([
            'file_path' => $filePath
        ]);
    
        return $pdf->download($fileName);
    }
    

     // New method to download the report
    public function download($id)
    {
        // Find the report by ID
        $report = Report::findOrFail($id);

        // Check if file exists in storage
        if (Storage::disk('public')->exists($report->file_path)) {
            // Return the file for download
            return response()->download(storage_path("app/public/{$report->file_path}"));
        }

        // If file doesn't exist, return an error
        return back()->with('error', 'The requested report file does not exist.');
    }

    // New method to delete the report
    public function delete($id)
    {
        // Find the report by ID
        $report = Report::findOrFail($id);

        // Check if the file exists in storage and delete it
        if (Storage::disk('public')->exists($report->file_path)) {
            Storage::disk('public')->delete($report->file_path);
        }

        // Delete the report record from the database
        $report->delete();

        // Redirect back with a success message
        return back()->with('success', 'Report deleted successfully.');
    }

    private function generateSummaryStatistics($transactions)
    {
        $summary = [
            'total' => $transactions->count(),
            'byStatus' => [
                0 => 0,  // Pending
                1 => 0,  // Completed
                2 => 0,  // Cancelled
            ],
            'byType' => [],
        ];
    
        foreach ($transactions as $transaction) {
            $status = $transaction->status;
            $typeName = $transaction->transactionType->name ?? 'Unknown';
    
            // Count by status using the correct numeric values (0, 1, 2)
            if (isset($summary['byStatus'][$status])) {
                $summary['byStatus'][$status]++;
            }
    
            // Count by type
            if (!isset($summary['byType'][$typeName])) {
                $summary['byType'][$typeName] = [
                    'count' => 0,
                    'completed' => 0,
                    'pending' => 0,
                    'cancelled' => 0,
                ];
            }
    
            $summary['byType'][$typeName]['count']++;
    
            // Count status-specific by type
            if (isset($summary['byType'][$typeName][$status])) {
                $summary['byType'][$typeName][$status]++;
            }
        }
    
        return $summary;
    }
    
    
    public function preview(Request $request)
    {
        try {
            // Determine which type of preview to generate based on the request type
            $previewType = $request->preview_type;
            
            switch ($previewType) {
                case 'transactions':
                    return $this->previewTransactions($request);
                case 'owners':
                    return $this->previewOwners($request);
                case 'animals':
                    return $this->previewAnimals($request);
                case 'vaccinations':
                    return $this->previewVaccinations($request);
                case 'users':
                    return $this->previewUsers($request);
                default:
                    return response()->json(['error' => 'Invalid preview type'], 400);
            }
        } catch (\Exception $e) {
            \Log::error('Report preview error: ' . $e->getMessage());
            return response()->json([
                'error' => 'Failed to generate preview: ' . $e->getMessage()
            ], 500);
        }
    }

    private function previewTransactions(Request $request)
    {
        $query = Transaction::query()
            ->whereBetween('created_at', [
                Carbon::parse($request->date_from)->startOfDay(),
                Carbon::parse($request->date_to)->endOfDay()
            ]);

        // Apply filters
        if ($request->transaction_type_id) {
            $query->where('transaction_type_id', $request->transaction_type_id);
        }
        
        if ($request->transaction_subtype_id) {
            $query->where('transaction_subtype_id', $request->transaction_subtype_id);
        }
        
        if ($request->status !== '' && $request->status !== null) {
            $query->where('status', $request->status);
        }

        // For receptionist specific report
        if ($request->receptionist_id) {
            $query->where('receptionist_id', $request->receptionist_id);
        }

        // Clone query for counts to avoid modifying the original query
        $totalQuery = clone $query;
        $completedQuery = clone $query;
        $pendingQuery = clone $query;
        $cancelledQuery = clone $query;

        // Get summary
        $summary = [
            'total' => $totalQuery->count(),
            'completed' => $completedQuery->where('status', 1)->count(),
            'pending' => $pendingQuery->where('status', 0)->count(),
            'cancelled' => $cancelledQuery->where('status', 2)->count()
        ];

        // Get sample data (latest 5 transactions)
        $samples = $query->latest()
            ->take(5)
            ->with(['transactionType', 'transactionSubtype', 'owner.user', 'animal'])
            ->get()
            ->map(function ($transaction) {
                return [
                    'created_at' => $transaction->created_at,
                    'type' => $transaction->transactionType->type_name . 
                        ($transaction->transactionSubtype ? ' - ' . $transaction->transactionSubtype->subtype_name : ''),
                    'status' => $transaction->status === 0 ? 'Pending' : 
                               ($transaction->status === 1 ? 'Completed' : 'Cancelled'),
                    'owner' => optional($transaction->owner)->user->complete_name ?? 'N/A',
                    'animal' => optional($transaction->animal)->name ?? 'N/A'
                ];
            });

        return response()->json([
            'summary' => $summary,
            'samples' => $samples
        ]);
    }
    

    private function previewOwners(Request $request)
    {
        $query = Owner::query()
            ->with('user', 'user.address.barangay')
            ->whereBetween('created_at', [
                Carbon::parse($request->date_from)->startOfDay(),
                Carbon::parse($request->date_to)->endOfDay()
            ]);

        if ($request->owner_category) {
            $query->where('category', $request->owner_category);
        }

        if ($request->barangay_id) {
            $query->whereHas('user.address', function ($q) use ($request) {
                $q->where('barangay_id', $request->barangay_id);
            });
        }

        // Execute the query once and store the results
        $ownersData = $query->get();
        
        // Create summary data
        $summary = [
            'total' => $ownersData->count(),
            'by_category' => $ownersData->groupBy('category')
                ->map(function ($item) {
                    return count($item);
                }),
            'by_barangay' => $ownersData->groupBy(function($owner) {
                return optional(optional($owner->user)->address)->barangay->barangay_name ?? 'Unknown';
            })
            ->map(function ($item) {
                return count($item);
            })
        ];

        // Get sample data
        $samples = $ownersData->take(5)
            ->map(function ($owner) {
                return [
                    'created_at' => $owner->created_at,
                    'name' => optional($owner->user)->complete_name ?? 'N/A',
                    'category' => $owner->category,
                    'barangay' => optional(optional($owner->user)->address)->barangay->barangay_name ?? 'N/A'
                ];
            });

        return response()->json([
            'summary' => $summary,
            'samples' => $samples
        ]);
    }

    private function previewAnimals(Request $request)
    {
        $query = Animal::query()
            ->with('species', 'breed', 'owner.user')
            ->whereBetween('created_at', [
                Carbon::parse($request->date_from)->startOfDay(),
                Carbon::parse($request->date_to)->endOfDay()
            ]);

        if ($request->species_id) {
            $query->where('species_id', $request->species_id);
        }

        if ($request->breed_id) {
            $query->where('breed_id', $request->breed_id);
        }

        if ($request->is_vaccinated !== '' && $request->is_vaccinated !== null) {
            $query->where('is_vaccinated', $request->is_vaccinated);
        }

        // Clone query for counts
        $totalQuery = clone $query;
        $vaccinatedQuery = clone $query;

        // Get summary
        $summary = [
            'total' => $totalQuery->count(),
            'vaccinated' => $vaccinatedQuery->where('is_vaccinated', 1)->count(),
            'not_vaccinated' => $totalQuery->count() - $vaccinatedQuery->where('is_vaccinated', 1)->count(),
            'by_species' => $query->get()->groupBy('species.name')
                ->map(function ($item) {
                    return count($item);
                })
        ];

        // Get sample data
        $samples = $query->latest()->take(5)
            ->get()
            ->map(function ($animal) {
                return [
                    'created_at' => $animal->created_at,
                    'name' => $animal->name,
                    'species' => $animal->species->name,
                    'breed' => $animal->breed->name,
                    'owner' => $animal->owner->user->complete_name,
                    'is_vaccinated' => $animal->is_vaccinated ? 'Yes' : 'No'
                ];
            });

        return response()->json([
            'summary' => $summary,
            'samples' => $samples
        ]);
    }

    private function previewVaccinations(Request $request)
    {
        $query = Transaction::query()
            ->whereNotNull('vaccine_id')
            ->with('animal.species', 'animal.breed', 'vaccine', 'owner.user')
            ->whereBetween('created_at', [
                Carbon::parse($request->date_from)->startOfDay(),
                Carbon::parse($request->date_to)->endOfDay()
            ]);

        if ($request->vaccine_id) {
            $query->where('vaccine_id', $request->vaccine_id);
        }

        if ($request->species_id) {
            $query->whereHas('animal', function ($q) use ($request) {
                $q->where('species_id', $request->species_id);
            });
        }

        if ($request->status !== '' && $request->status !== null) {
            $query->where('status', $request->status);
        }

        // Clone query for counts
        $totalQuery = clone $query;
        $completedQuery = clone $query;
        $pendingQuery = clone $query;

        // Get summary
        $summary = [
            'total' => $totalQuery->count(),
            'completed' => $completedQuery->where('status', 1)->count(),
            'pending' => $pendingQuery->where('status', 0)->count(),
            'by_vaccine' => $query->get()->groupBy('vaccine.vaccine_name')
                ->map(function ($item) {
                    return count($item);
                })
        ];

        // Get sample data
        $samples = $query->latest()->take(5)
            ->get()
            ->map(function ($transaction) {
                return [
                    'created_at' => $transaction->created_at,
                    'animal' => $transaction->animal->name,
                    'species' => $transaction->animal->species->name,
                    'vaccine' => $transaction->vaccine->vaccine_name,
                    'owner' => $transaction->owner->user->complete_name,
                    'status' => $transaction->status === 0 ? 'Pending' : 
                               ($transaction->status === 1 ? 'Completed' : 'Cancelled')
                ];
            });

        return response()->json([
            'summary' => $summary,
            'samples' => $samples
        ]);
    }

    private function previewUsers(Request $request)
    {
        try {
            // Query only veterinarians (role = 2)
            $query = User::query()
                ->where('role', 2) // Only veterinarians
                ->with('designation')
                ->whereBetween('created_at', [
                    Carbon::parse($request->date_from)->startOfDay(),
                    Carbon::parse($request->date_to)->endOfDay()
                ]);

            // Apply designation filter if provided
            if ($request->designation_id) {
                $query->where('designation_id', $request->designation_id);
            }

            // Get the veterinarians
            $vets = $query->get();
            
            // Create summary data
            $summary = [
                'total' => $vets->count(),
                'by_designation' => $vets->groupBy(function($vet) {
                    return $vet->designation ? $vet->designation->name : 'No Designation';
                })
                ->map(function ($group) {
                    return count($group);
                })
            ];

            // Prepare sample data
            $samples = $vets->take(5)
                ->map(function ($vet) {
                    return [
                        'created_at' => $vet->created_at,
                        'name' => $vet->complete_name,
                        'designation' => $vet->designation ? $vet->designation->name : 'No Designation',
                        'email' => $vet->email
                    ];
                });

            return response()->json([
                'summary' => $summary,
                'samples' => $samples
            ]);
        } catch (\Exception $e) {
            \Log::error('Error in previewUsers: ' . $e->getMessage());
            throw $e; // Re-throw to be caught by the main preview method
        }
    }

    public function generateRecTransactionReport(Request $request)
    {
        try {
            $validated = $request->validate([
                'date_from' => 'required|date',
                'date_to' => 'required|date|after_or_equal:date_from',
                'transaction_type_id' => 'nullable|exists:transaction_types,id',
                'transaction_subtype_id' => 'nullable|exists:transaction_subtypes,id',
                'status' => 'nullable|in:0,1,2',
                'format' => 'nullable|in:pdf,excel'
            ]);

            // Get transactions
            $query = Transaction::query()
                ->whereBetween('created_at', [
                    Carbon::parse($validated['date_from']),
                    Carbon::parse($validated['date_to'])->endOfDay()
                ])
                ->with([
                    'owner.user',
                    'animal.species',
                    'animal.breed',
                    'vet',
                    'receptionist',
                    'transactionType',
                    'transactionSubtype'
                ]);

            // Filter by transaction type if selected
            if (!empty($validated['transaction_type_id'])) {
                $query->where('transaction_type_id', $validated['transaction_type_id']);
            }

            // Filter by transaction subtype if selected
            if (!empty($validated['transaction_subtype_id'])) {
                $query->where('transaction_subtype_id', $validated['transaction_subtype_id']);
            }

            // Filter by status if selected
            if (isset($validated['status'])) {
                $query->where('status', $validated['status']);
            }

            $transactions = $query->get();

            $data = [
                'transactions' => $transactions,
                'dateFrom' => Carbon::parse($validated['date_from']),
                'dateTo' => Carbon::parse($validated['date_to']),
                'summary' => [
                    'total' => $transactions->count(),
                    'byStatus' => [
                        'pending' => $transactions->where('status', 0)->count(),
                        'completed' => $transactions->where('status', 1)->count(),
                        'cancelled' => $transactions->where('status', 2)->count(),
                    ],
                    'byType' => $transactions->groupBy('transactionType.type_name')
                        ->map(function ($group) {
                            return [
                                'count' => $group->count(),
                                'completed' => $group->where('status', 1)->count(),
                                'pending' => $group->where('status', 0)->count(),
                                'cancelled' => $group->where('status', 2)->count(),
                                'bySubtype' => $group->groupBy('transactionSubtype.subtype_name')
                                    ->map(function ($subgroup) {
                                        return [
                                            'count' => $subgroup->count(),
                                            'completed' => $subgroup->where('status', 1)->count(),
                                            'pending' => $subgroup->where('status', 0)->count(),
                                            'cancelled' => $subgroup->where('status', 2)->count(),
                                        ];
                                    })
                            ];
                        }),
                ],
                'filters' => [
                    'type' => !empty($validated['transaction_type_id']) ? 
                        TransactionType::find($validated['transaction_type_id'])->type_name : 'All Types',
                    'subtype' => !empty($validated['transaction_subtype_id']) ? 
                        TransactionType::find($validated['transaction_subtype_id'])->subtype_name : 'All Subtypes',
                    'status' => isset($validated['status']) ? 
                        ['Pending', 'Completed', 'Cancelled'][$validated['status']] : 'All Statuses'
                ],
                'receptionist' => auth()->user()
            ];

            // Generate PDF
            $pdf = PDF::loadView('reports.pdf.receptionist.transactions', $data);
            
            // Create filename
            $fileName = "transaction-report-" . now()->format('Y-m-d-His') . '.pdf';
            $filePath = "reports/{$fileName}";
            
            // Save report record first
            $report = Report::create([
                'user_id' => auth()->user()->user_id,
                'report_type' => 'transactions',
                'date_from' => $validated['date_from'],
                'date_to' => $validated['date_to'],
                'parameters' => $validated,
                'generated_by' => auth()->user()->user_id,
                'status' => 'completed',
                'file_path' => $filePath // Save file path immediately
            ]);

            // Save the PDF file
            Storage::disk('public')->put($filePath, $pdf->output());

            // Redirect to the download route
            return redirect()->route('reports.download', $report);

        } catch (\Exception $e) {
            \Log::error('Transaction Report generation failed: ' . $e->getMessage());
            return back()->with('error', 'Failed to generate report: ' . $e->getMessage());
        }
    }

    public function generateOwnerReport(Request $request)
    {
        try {
            $validated = $request->validate([
                'date_from' => 'required|date',
                'date_to' => 'required|date|after_or_equal:date_from',
                'owner_category' => 'nullable|string',
                'barangay_id' => 'nullable|exists:barangays,id',
                'format' => 'nullable|in:pdf,excel'
            ]);

            // Query owners
            $query = Owner::query()
                ->with(['user', 'user.address.barangay', 'animals'])
                ->whereBetween('created_at', [
                    Carbon::parse($validated['date_from']),
                    Carbon::parse($validated['date_to'])->endOfDay()
                ]);

            // Apply filters
            if (!empty($validated['owner_category'])) {
                $query->where('category', $validated['owner_category']);
            }

            if (!empty($validated['barangay_id'])) {
                $query->whereHas('user.address', function($q) use ($validated) {
                    $q->where('barangay_id', $validated['barangay_id']);
                });
            }

            $owners = $query->get();

            // Prepare data for PDF generation
            $data = [
                'owners' => $owners,
                'dateFrom' => Carbon::parse($validated['date_from']),
                'dateTo' => Carbon::parse($validated['date_to']),
                'summary' => [
                    'total' => $owners->count(),
                    'byCategory' => $owners->groupBy('category')
                        ->map(function ($group) {
                            return [
                                'count' => $group->count(),
                                'animalCount' => $group->flatMap(function ($owner) {
                                    return $owner->animals;
                                })->count(),
                            ];
                        }),
                    'byBarangay' => $owners->groupBy('user.address.barangay.barangay_name')
                        ->map(function ($group) {
                            return [
                                'count' => $group->count(),
                                'animalCount' => $group->flatMap(function ($owner) {
                                    return $owner->animals;
                                })->count(),
                            ];
                        }),
                ],
                'filters' => [
                    'category' => !empty($validated['owner_category']) ? 
                        $validated['owner_category'] : 'All Categories',
                    'barangay' => !empty($validated['barangay_id']) ? 
                        Barangay::find($validated['barangay_id'])->barangay_name : 'All Barangays',
                ],
                'receptionist' => auth()->user()
            ];

            // Generate PDF
            $pdf = PDF::loadView('reports.pdf.receptionist.owners', $data);
            
            // Create filename
            $fileName = "owners-report-" . now()->format('Y-m-d-His') . '.pdf';
            $filePath = "reports/{$fileName}";
            
            // Save report record
            $report = Report::create([
                'user_id' => auth()->user()->user_id,
                'report_type' => 'owners',
                'date_from' => $validated['date_from'],
                'date_to' => $validated['date_to'],
                'parameters' => $validated,
                'generated_by' => auth()->user()->user_id,
                'status' => 'completed',
                'file_path' => $filePath
            ]);

            // Save the PDF file
            Storage::disk('public')->put($filePath, $pdf->output());

            // Redirect to the download route
            return redirect()->route('reports.download', $report);

        } catch (\Exception $e) {
            \Log::error('Owner Report generation failed: ' . $e->getMessage());
            return back()->with('error', 'Failed to generate report: ' . $e->getMessage());
        }
    }

    public function generateAnimalReport(Request $request)
    {
        try {
            $validated = $request->validate([
                'date_from' => 'required|date',
                'date_to' => 'required|date|after_or_equal:date_from',
                'species_id' => 'nullable|exists:species,id',
                'breed_id' => 'nullable|exists:breeds,id',
                'is_vaccinated' => 'nullable|in:0,1',
                'format' => 'nullable|in:pdf,excel'
            ]);

            // Query animals
            $query = Animal::query()
                ->with(['owner.user', 'species', 'breed'])
                ->whereBetween('created_at', [
                    Carbon::parse($validated['date_from']),
                    Carbon::parse($validated['date_to'])->endOfDay()
                ]);

            // Apply filters
            if (!empty($validated['species_id'])) {
                $query->where('species_id', $validated['species_id']);
            }

            if (!empty($validated['breed_id'])) {
                $query->where('breed_id', $validated['breed_id']);
            }

            if (isset($validated['is_vaccinated'])) {
                $query->where('is_vaccinated', $validated['is_vaccinated']);
            }

            $animals = $query->get();

            // Prepare data for PDF generation
            $data = [
                'animals' => $animals,
                'dateFrom' => Carbon::parse($validated['date_from']),
                'dateTo' => Carbon::parse($validated['date_to']),
                'summary' => [
                    'total' => $animals->count(),
                    'vaccinated' => $animals->where('is_vaccinated', 1)->count(),
                    'nonVaccinated' => $animals->where('is_vaccinated', 0)->count(),
                    'bySpecies' => $animals->groupBy('species.name')
                        ->map(function ($group) {
                            return [
                                'count' => $group->count(),
                                'vaccinated' => $group->where('is_vaccinated', 1)->count(),
                                'nonVaccinated' => $group->where('is_vaccinated', 0)->count(),
                            ];
                        }),
                    'byBreed' => $animals->groupBy('breed.name')
                        ->map(function ($group) {
                            return [
                                'count' => $group->count(),
                                'vaccinated' => $group->where('is_vaccinated', 1)->count(),
                                'nonVaccinated' => $group->where('is_vaccinated', 0)->count(),
                            ];
                        }),
                ],
                'filters' => [
                    'species' => !empty($validated['species_id']) ? 
                        Species::find($validated['species_id'])->name : 'All Species',
                    'breed' => !empty($validated['breed_id']) ? 
                        Species::find($validated['breed_id'])->name : 'All Breeds',
                    'vaccination' => isset($validated['is_vaccinated']) ? 
                        ($validated['is_vaccinated'] ? 'Vaccinated Only' : 'Non-Vaccinated Only') : 'All Animals',
                ],
                'receptionist' => auth()->user()
            ];

            // Generate PDF
            $pdf = PDF::loadView('reports.pdf.receptionist.animals', $data);
            
            // Create filename
            $fileName = "animals-report-" . now()->format('Y-m-d-His') . '.pdf';
            $filePath = "reports/{$fileName}";
            
            // Save report record
            $report = Report::create([
                'user_id' => auth()->user()->user_id,
                'report_type' => 'animals',
                'date_from' => $validated['date_from'],
                'date_to' => $validated['date_to'],
                'parameters' => $validated,
                'generated_by' => auth()->user()->user_id,
                'status' => 'completed',
                'file_path' => $filePath
            ]);

            // Save the PDF file
            Storage::disk('public')->put($filePath, $pdf->output());

            // Redirect to the download route
            return redirect()->route('reports.download', $report);

        } catch (\Exception $e) {
            \Log::error('Animal Report generation failed: ' . $e->getMessage());
            return back()->with('error', 'Failed to generate report: ' . $e->getMessage());
        }
    }

    public function generateVaccinationReport(Request $request)
    {
        try {
            $validated = $request->validate([
                'date_from' => 'required|date',
                'date_to' => 'required|date|after_or_equal:date_from',
                'vaccine_id' => 'nullable|exists:vaccines,id',
                'species_id' => 'nullable|exists:species,id',
                'status' => 'nullable|in:0,1,2',
                'format' => 'nullable|in:pdf,excel'
            ]);

            // Query vaccination transactions
            $query = Transaction::query()
                ->whereNotNull('vaccine_id')
                ->with(['owner.user', 'animal.species', 'animal.breed', 'vaccine', 'vet', 'receptionist'])
                ->whereBetween('created_at', [
                    Carbon::parse($validated['date_from']),
                    Carbon::parse($validated['date_to'])->endOfDay()
                ]);

            // Apply filters
            if (!empty($validated['vaccine_id'])) {
                $query->where('vaccine_id', $validated['vaccine_id']);
            }

            if (!empty($validated['species_id'])) {
                $query->whereHas('animal', function($q) use ($validated) {
                    $q->where('species_id', $validated['species_id']);
                });
            }

            if (isset($validated['status'])) {
                $query->where('status', $validated['status']);
            }

            $vaccinations = $query->get();

            // Prepare data for PDF generation
            $data = [
                'vaccinations' => $vaccinations,
                'dateFrom' => Carbon::parse($validated['date_from']),
                'dateTo' => Carbon::parse($validated['date_to']),
                'summary' => [
                    'total' => $vaccinations->count(),
                    'completed' => $vaccinations->where('status', 1)->count(),
                    'pending' => $vaccinations->where('status', 0)->count(),
                    'cancelled' => $vaccinations->where('status', 2)->count(),
                    'byVaccine' => $vaccinations->groupBy('vaccine.vaccine_name')
                        ->map(function ($group) {
                            return [
                                'count' => $group->count(),
                                'completed' => $group->where('status', 1)->count(),
                                'pending' => $group->where('status', 0)->count(),
                                'cancelled' => $group->where('status', 2)->count(),
                            ];
                        }),
                    'bySpecies' => $vaccinations->groupBy('animal.species.name')
                        ->map(function ($group) {
                            return [
                                'count' => $group->count(),
                                'completed' => $group->where('status', 1)->count(),
                                'pending' => $group->where('status', 0)->count(),
                                'cancelled' => $group->where('status', 2)->count(),
                            ];
                        }),
                ],
                'filters' => [
                    'vaccine' => !empty($validated['vaccine_id']) ? 
                        Vaccine::find($validated['vaccine_id'])->vaccine_name : 'All Vaccines',
                    'species' => !empty($validated['species_id']) ? 
                        Species::find($validated['species_id'])->name : 'All Species',
                    'status' => isset($validated['status']) ? 
                        ['Pending', 'Completed', 'Cancelled'][$validated['status']] : 'All Statuses',
                ],
                'receptionist' => auth()->user()
            ];

            // Generate PDF
            $pdf = PDF::loadView('reports.pdf.receptionist.vaccinations', $data);
            
            // Create filename
            $fileName = "vaccinations-report-" . now()->format('Y-m-d-His') . '.pdf';
            $filePath = "reports/{$fileName}";
            
            //            // Save report record
            $report = Report::create([
                'user_id' => auth()->user()->user_id,
                'report_type' => 'vaccinations',
                'date_from' => $validated['date_from'],
                'date_to' => $validated['date_to'],
                'parameters' => $validated,
                'generated_by' => auth()->user()->user_id,
                'status' => 'completed',
                'file_path' => $filePath
            ]);

            // Save the PDF file
            Storage::disk('public')->put($filePath, $pdf->output());

            // Redirect to the download route
            return redirect()->route('reports.download', $report);

        } catch (\Exception $e) {
            \Log::error('Vaccination Report generation failed: ' . $e->getMessage());
            return back()->with('error', 'Failed to generate report: ' . $e->getMessage());
        }
    }
}