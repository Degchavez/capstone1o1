<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Owner;
use App\Models\Animal;
use Illuminate\Pagination\LengthAwarePaginator;


class ReceptionistController extends Controller
{
    public function loadReceptionistDashboard(Request $request)
    {
        $today = now()->format('Y-m-d');
    
        // Apply date range filter if provided
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
    
        // Fetch recent transactions, clients, and animals with filters
        $transactions = Transaction::latest()
            ->when($startDate, function ($query) use ($startDate) {
                return $query->whereDate('created_at', '>=', $startDate);
            })
            ->when($endDate, function ($query) use ($endDate) {
                return $query->whereDate('created_at', '<=', $endDate);
            })
            ->take(5)
            ->get()
            ->map(function ($transaction) {
                $animalName = $transaction->animal->name ?? 'Unknown';
                $speciesName = $transaction->animal->species->name ?? 'Unknown';
                return [
                    'description' => "Handled transaction for <a href='" . route('rec.profile', ['animal_id' => $transaction->animal->animal_id]) . "' class='text-teal-600'>$animalName ($speciesName)</a>.",
                    'created_at' => $transaction->created_at,
                    'url' => route('rec.profile', ['animal_id' => $transaction->animal->animal_id]),
                ];
            });
    
            $clients = Owner::latest()
            ->when($startDate, function ($query) use ($startDate) {
                return $query->whereDate('created_at', '>=', $startDate);
            })
            ->when($endDate, function ($query) use ($endDate) {
                return $query->whereDate('created_at', '<=', $endDate);
            })
            ->take(5)
            ->get()
            ->map(function ($owner) {
                $ownerName = $owner->user->complete_name ?? 'Unknown';
                return [
                    'description' => "Added a new client: <a href='" . route('rec.profile-owner', ['owner_id' => $owner->owner_id]) . "' class='text-blue-600'>$ownerName</a>.",
                    'created_at' => $owner->created_at,
                    'url' => route('rec.profile-owner', ['owner_id' => $owner->owner_id]),  // Correct URL for owner profile
                ];
            });
        
    
        $animals = Animal::latest()
            ->when($startDate, function ($query) use ($startDate) {
                return $query->whereDate('created_at', '>=', $startDate);
            })
            ->when($endDate, function ($query) use ($endDate) {
                return $query->whereDate('created_at', '<=', $endDate);
            })
            ->take(5)
            ->get()
            ->map(function ($animal) {
                $animalName = $animal->name ?? 'Unknown';
                $speciesName = $animal->species->name ?? 'Unknown';
                return [
                    'description' => "Registered a new pet: <a href='" . route('rec.profile', ['animal_id' => $animal->animal_id]) . "' class='text-yellow-600'>$animalName ($speciesName)</a>.",
                    'created_at' => $animal->created_at,
                    'url' => route('rec.profile', ['animal_id' => $animal->animal_id]),
                ];
            });
    
        // Combine all activities and sort by creation date
        $recent_activities = collect()
            ->merge($transactions)
            ->merge($clients)
            ->merge($animals)
            ->sortByDesc('created_at');
    
        $data = [
            'appointments_count' => Transaction::whereDate('created_at', $today)->count(),
            'new_clients_count' => Owner::whereDate('created_at', $today)->count(),
            'pets_registered_count' => Animal::whereDate('created_at', $today)->count(),
            'recent_activities' => $recent_activities->values()->all(),
        ];
    
        return view('receptionist.dashboard', $data);
    }
    
    
    
    
    
private function getRecentActivities()
{
    // Fetch recent transactions
    $recentTransactions = Transaction::latest()
        ->take(5)
        ->get()
        ->map(function ($transaction) {
            $animalName = $transaction->animal->name ?? 'Unknown';
            $speciesName = $transaction->animal->species->name ?? 'Unknown';
            return [
                'description' => "Handled transaction for $animalName ($speciesName).",
                'time_ago' => $transaction->created_at->diffForHumans(),
            ];
        });

    // Fetch recently added clients
    $recentClients = Owner::latest()
        ->take(5)
        ->get()
        ->map(function ($owner) {
            $ownerName = $owner->user->complete_name ?? 'Unknown';
            return [
                'description' => "Added a new client: $ownerName.",
                'time_ago' => $owner->created_at->diffForHumans(),
            ];
        });

    // Fetch recently registered animals
    $recentAnimals = Animal::latest()
        ->take(5)
        ->get()
        ->map(function ($animal) {
            $animalName = $animal->name ?? 'Unknown';
            $speciesName = $animal->species->name ?? 'Unknown';
            return [
                'description' => "Registered a new pet: $animalName ($speciesName).",
                'time_ago' => $animal->created_at->diffForHumans(),
            ];
        });

    // Combine and sort activities by creation time
    $recentActivities = collect()
        ->merge($recentTransactions)
        ->merge($recentClients)
        ->merge($recentAnimals)
        ->sortByDesc(fn ($activity) => $activity['time_ago']);

    return $recentActivities->values()->all();
}

}
