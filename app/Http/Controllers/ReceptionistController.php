<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ReceptionistController extends Controller
{
    public function loadReceptionistDashboard()
    {
         return view('receptionist.dashboard');
    } 
}
