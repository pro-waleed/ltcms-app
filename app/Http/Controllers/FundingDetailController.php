<?php

namespace App\Http\Controllers;

use App\Models\FundingDetail;
use App\Models\Opportunity;
use Illuminate\Http\Request;

class FundingDetailController extends Controller
{
    public function index()
    {
        $fundings = FundingDetail::orderByDesc('id')->paginate(25);

        return view('funding.index', compact('fundings'));
    }

    public function create()
    {
        $opportunities = Opportunity::orderByDesc('id')->get();

        return view('funding.create', compact('opportunities'));
    }

    public function store(Request $request)
    {
        $data = $this->validateFunding($request);

        $funding = FundingDetail::create($data);

        if ($request->filled('opportunity_id')) {
            Opportunity::whereKey($request->input('opportunity_id'))
                ->update(['funding_detail_id' => $funding->id]);
        }

        return redirect()->route('funding.index')
            ->with('status', 'تم إلغاء التمويل (تعيين غير ممول)');
    }

    public function edit(FundingDetail $funding)
    {
        $opportunities = Opportunity::orderByDesc('id')->get();

        return view('funding.edit', compact('funding', 'opportunities'));
    }

    public function update(Request $request, FundingDetail $funding)
    {
        $data = $this->validateFunding($request);

        $funding->update($data);

        if ($request->filled('opportunity_id')) {
            Opportunity::whereKey($request->input('opportunity_id'))
                ->update(['funding_detail_id' => $funding->id]);
        }

        return redirect()->route('funding.index')
            ->with('status', 'تم إلغاء التمويل (تعيين غير ممول)');
    }

    public function destroy(FundingDetail $funding)
    {
        $funding->update(['funding_type' => 'not_funded']);

        return redirect()->route('funding.index')
            ->with('status', 'تم إلغاء التمويل (تعيين غير ممول)');
    }

    private function validateFunding(Request $request): array
    {
        return $request->validate([
            'funding_type' => ['required', 'in:fully_funded,partially_funded,not_funded,co_funded'],
            'training_fees' => ['required', 'in:included,excluded,unspecified'],
            'international_tickets' => ['required', 'in:included,excluded,unspecified'],
            'domestic_tickets' => ['required', 'in:included,excluded,unspecified'],
            'accommodation' => ['required', 'in:included,excluded,unspecified'],
            'meals' => ['required', 'in:included,excluded,unspecified'],
            'local_transport' => ['required', 'in:included,excluded,unspecified'],
            'health_insurance' => ['required', 'in:included,excluded,unspecified'],
            'visa_fees' => ['required', 'in:included,excluded,unspecified'],
            'per_diem' => ['required', 'in:included,excluded,unspecified'],
            'training_materials' => ['required', 'in:included,excluded,unspecified'],
            'tech_support' => ['required', 'in:included,excluded,unspecified'],
            'ministry_obligations' => ['nullable', 'string'],
            'notes' => ['nullable', 'string'],
        ]);
    }
}
