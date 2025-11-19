<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TopAttraction;

class HomeController extends Controller
{
    public function index()
    {
        $topAttractions = TopAttraction::active()->ordered()->get();

        return view('index', compact('topAttractions'));
    }
}
