<?php

namespace App\Livewire;

use App\Models\Address;
use App\Models\Barangay;
use App\Models\User;
use Livewire\Component;

class NewUsersList extends Component
{
    public $perPage = 50;  // Default items per page
    public $barangay = null;  // Filter by barangay
    public $search = ''; // Optional search query

    // Listen for changes in the perPage and barangay
    protected $queryString = ['perPage', 'barangay'];

    public function render()
    {   
        // Start building the query
        $query = User::with(['address.barangay'])->orderBy('created_at', 'desc');

        // Apply barangay filter if selected
        if ($this->barangay) {
            $query->whereHas('address.barangay', function ($q) {
                $q->where('id', $this->barangay);
            });
        }

        // Optionally apply search query
        if ($this->search) {
            $query->where(function($q) {
                $q->where('name', 'like', '%'.$this->search.'%')
                  ->orWhere('email', 'like', '%'.$this->search.'%');
            });
        }

        // Paginate the results based on perPage
        $users = $query->paginate($this->perPage);

        // Fetch all barangays for the filter
        $barangays = Barangay::all();

        return view('livewire.new-users-list', [
            'users' => $users,
            'barangays' => $barangays  // Pass barangays to the view
        ]);
    }
}
