<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class CompteController extends Controller
{
    public function show(Request $request): View
    {
        return view('account.show', [
            'user' => $request->user(),
            'reservations' => $request->user()->reservations()->with('terrain')->latest()->get(),
        ]);
    }
}

