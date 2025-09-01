<?php

namespace App\Http\Controllers;

use App\Models\Hospital;
use Illuminate\Http\Request;

class HospitalController extends Controller
{
    public function index(Request $request)
    {
        $hospitals = Hospital::search($request->input('search'))
                              ->orderBy('name')
                              ->paginate(10)
                              ->withQueryString();

        return view('pages.hospital.index', [
            'title' => 'Hospital Dashboard',
            'hospitals' => $hospitals
        ]);
    }
}
