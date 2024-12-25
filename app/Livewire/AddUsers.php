<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Designation; // Import the Designation model

class AddUsers extends Component
{   
    public $designations; // Declare the $designations property

    // The mount method to load designations from the database
    public function mount()
    {
        $this->designations = Designation::all();  // Fetch the list of designations from the database
    }

    // Render the Livewire component view
    public function render()
    {
        return view('livewire.components_admin.add-users');
    }
}
