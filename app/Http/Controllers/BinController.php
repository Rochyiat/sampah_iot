<?php

namespace App\Http\Controllers;

use App\Models\Bin;
use Illuminate\Http\Request;

class BinController extends Controller
{
    public function index()
    {
        $data = Bin::all();
        return view('bins.index', compact('data'));
    }
    

}
