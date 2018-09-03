<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Rental;

class RentController extends Controller
{
    public function setRental (Request $req)
    {
        Rental::create([
            'title' => $req->title,
            'description' => $req->description,
            'room' => $req->room,
            'period' => $req->period
        ]);
    }

    public function getRental ()
    {
        return Rental::get();        
    }
}
