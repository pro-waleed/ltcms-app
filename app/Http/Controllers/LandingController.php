<?php

namespace App\Http\Controllers;

use App\Models\Opportunity;

class LandingController extends Controller
{
    public function index()
    {
        $opportunities = Opportunity::query()
            ->where('status', 'open_for_nomination')
            ->with(['partner', 'type'])
            ->orderBy('nomination_deadline')
            ->orderByDesc('id')
            ->take(6)
            ->get();

        return view('home', compact('opportunities'));
    }
}
