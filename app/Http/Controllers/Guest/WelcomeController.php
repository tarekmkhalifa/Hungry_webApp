<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class WelcomeController extends Controller
{
    public function index()
    {
        $specials = Category::where('name','specials')->first();
        return view('welcome' , compact('specials')) ;
    }

    public function thankYou()
    {
        return view('thank_you');
    }
}
