<?php

namespace App\Http\Controllers;

use App\Models\Opportunity;

class LandingController extends Controller
{
    public function index()
    {
        $opportunities = Opportunity::query()
            ->where('status', 'open_for_nomination')
            ->where(function ($query) {
                $query->whereNull('nomination_deadline')
                    ->orWhereDate('nomination_deadline', '>=', now()->toDateString());
            })
            ->with(['partner', 'type'])
            ->orderBy('nomination_deadline')
            ->orderByDesc('id')
            ->take(6)
            ->get();

        $heroStats = [
            'open_opportunities' => Opportunity::where('status', 'open_for_nomination')
                ->where(function ($query) {
                    $query->whereNull('nomination_deadline')
                        ->orWhereDate('nomination_deadline', '>=', now()->toDateString());
                })
                ->count(),
            'online_opportunities' => Opportunity::where('status', 'open_for_nomination')
                ->where('delivery_mode', 'online')
                ->where(function ($query) {
                    $query->whereNull('nomination_deadline')
                        ->orWhereDate('nomination_deadline', '>=', now()->toDateString());
                })
                ->count(),
            'partners_count' => Opportunity::where('status', 'open_for_nomination')
                ->where(function ($query) {
                    $query->whereNull('nomination_deadline')
                        ->orWhereDate('nomination_deadline', '>=', now()->toDateString());
                })
                ->distinct('partner_id')
                ->count('partner_id'),
            'nearest_deadline' => Opportunity::where('status', 'open_for_nomination')
                ->whereNotNull('nomination_deadline')
                ->whereDate('nomination_deadline', '>=', now()->toDateString())
                ->min('nomination_deadline'),
        ];

        return view('home', compact('opportunities', 'heroStats'));
    }
}
