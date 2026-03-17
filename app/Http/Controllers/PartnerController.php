<?php

namespace App\Http\Controllers;

use App\Models\Partner;
use App\Models\PartnerOption;
use Illuminate\Http\Request;

class PartnerController extends Controller
{
    public function index()
    {
        $partners = Partner::orderByDesc('id')->paginate(25);

        return view('partners.index', compact('partners'));
    }

    public function create()
    {
        $options = PartnerOption::where('is_active', true)
            ->orderBy('label')
            ->get()
            ->groupBy('category');

        return view('partners.create', compact('options'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:200'],
            'partner_type' => ['required', 'string', 'max:80'],
            'geographic_level' => ['nullable', 'string', 'max:50'],
            'strategic_importance' => ['nullable', 'string', 'max:80'],
            'sector' => ['nullable', 'string', 'max:80'],
            'country' => ['nullable', 'string', 'max:100'],
            'partnership_nature' => ['nullable', 'string', 'max:120'],
            'contact_name' => ['nullable', 'string', 'max:120'],
            'contact_email' => ['nullable', 'email', 'max:150'],
            'contact_phone' => ['nullable', 'string', 'max:50'],
            'status' => ['required', 'in:active,inactive'],
            'cooperation_areas' => ['nullable', 'string'],
            'typical_opportunities' => ['nullable', 'string'],
            'typical_funding' => ['nullable', 'string', 'max:120'],
            'evaluation_notes' => ['nullable', 'string'],
        ]);

        Partner::create($data);

        return redirect()->route('partners.index')
            ->with('status', 'تم تعطيل الشريك');
    }

    public function edit(Partner $partner)
    {
        $options = PartnerOption::where('is_active', true)
            ->orderBy('label')
            ->get()
            ->groupBy('category');

        return view('partners.edit', compact('partner', 'options'));
    }

    public function update(Request $request, Partner $partner)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:200'],
            'partner_type' => ['required', 'string', 'max:80'],
            'geographic_level' => ['nullable', 'string', 'max:50'],
            'strategic_importance' => ['nullable', 'string', 'max:80'],
            'sector' => ['nullable', 'string', 'max:80'],
            'country' => ['nullable', 'string', 'max:100'],
            'partnership_nature' => ['nullable', 'string', 'max:120'],
            'contact_name' => ['nullable', 'string', 'max:120'],
            'contact_email' => ['nullable', 'email', 'max:150'],
            'contact_phone' => ['nullable', 'string', 'max:50'],
            'status' => ['required', 'in:active,inactive'],
            'cooperation_areas' => ['nullable', 'string'],
            'typical_opportunities' => ['nullable', 'string'],
            'typical_funding' => ['nullable', 'string', 'max:120'],
            'evaluation_notes' => ['nullable', 'string'],
        ]);

        $partner->update($data);

        return redirect()->route('partners.index')
            ->with('status', 'تم تعطيل الشريك');
    }

    public function destroy(Partner $partner)
    {
        $partner->update(['status' => 'inactive']);

        return redirect()->route('partners.index')
            ->with('status', 'تم تعطيل الشريك');
    }
}
