<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // nanti bisa tarik data dari database
        return view('index');
    }
}
