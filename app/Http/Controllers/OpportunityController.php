<?php

namespace App\Http\Controllers;

use App\Models\Opportunity;
use App\Models\OpportunityType;
use App\Models\Partner;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class OpportunityController extends Controller
{
    public function index()
    {
        $opportunities = Opportunity::with(['type', 'partner'])
            ->orderByDesc('id')
            ->paginate(25);

        return view('opportunities.index', compact('opportunities'));
    }

    public function create()
    {
        $types = OpportunityType::orderBy('name')->get();
        $partners = Partner::orderBy('name')->get();

        return view('opportunities.create', compact('types', 'partners'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:200'],
            'opportunity_type_id' => ['required', 'exists:opportunity_types,id'],
            'delivery_mode' => ['required', 'in:onsite,online,hybrid'],
            'partner_id' => ['nullable', 'exists:partners,id'],
            'language' => ['nullable', 'string', 'max:50'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date'],
            'nomination_deadline' => ['nullable', 'date'],
            'status' => ['required', 'in:draft,received,under_review,open_for_nomination,closed,nominated,executed,closed_no_benefit,referred,cancelled'],
            'summary' => ['nullable', 'string'],
            'target_group' => ['nullable', 'string', 'max:200'],
        ]);

        $data['reference_no'] = $this->nextReference();

        Opportunity::create($data);

        return redirect()->route('opportunities.index')
            ->with('status', 'تم إلغاء الفرصة');
    }

    public function edit(Opportunity $opportunity)
    {
        $types = OpportunityType::orderBy('name')->get();
        $partners = Partner::orderBy('name')->get();

        return view('opportunities.edit', compact('opportunity', 'types', 'partners'));
    }

    public function update(Request $request, Opportunity $opportunity)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:200'],
            'opportunity_type_id' => ['required', 'exists:opportunity_types,id'],
            'delivery_mode' => ['required', 'in:onsite,online,hybrid'],
            'partner_id' => ['nullable', 'exists:partners,id'],
            'language' => ['nullable', 'string', 'max:50'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date'],
            'nomination_deadline' => ['nullable', 'date'],
            'status' => ['required', 'in:draft,received,under_review,open_for_nomination,closed,nominated,executed,closed_no_benefit,referred,cancelled'],
            'summary' => ['nullable', 'string'],
            'target_group' => ['nullable', 'string', 'max:200'],
        ]);

        $opportunity->update($data);

        return redirect()->route('opportunities.index')
            ->with('status', 'تم إلغاء الفرصة');
    }

    public function destroy(Opportunity $opportunity)
    {
        $opportunity->update(['status' => 'cancelled']);

        return redirect()->route('opportunities.index')
            ->with('status', 'تم إلغاء الفرصة');
    }

    private function nextReference(): string
    {
        $year = date('Y');
        $prefix = "TR-$year-";
        $last = Opportunity::where('reference_no', 'like', $prefix . '%')
            ->orderByDesc('reference_no')
            ->first();

        $nextNumber = 1;
        if ($last) {
            $suffix = Str::after($last->reference_no, $prefix);
            $nextNumber = max(1, intval($suffix) + 1);
        }

        return $prefix . str_pad((string) $nextNumber, 3, '0', STR_PAD_LEFT);
    }
}
