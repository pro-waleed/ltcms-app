<?php

namespace App\Http\Controllers;

use App\Models\PartnerOption;
use Illuminate\Http\Request;

class PartnerOptionController extends Controller
{
    public function index()
    {
        $options = PartnerOption::orderBy('category')->orderBy('label')->get()->groupBy('category');
        $labels = [
            'partner_type' => 'نوع الجهة',
            'geographic_level' => 'المستوى الجغرافي',
            'strategic_importance' => 'الأهمية الاستراتيجية',
            'sector' => 'القطاع',
            'partnership_nature' => 'طبيعة الشراكة',
            'typical_funding' => 'طبيعة التمويل',
        ];

        return view('partners.options', compact('options', 'labels'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'category' => ['required', 'in:partner_type,geographic_level,strategic_importance,sector,partnership_nature,typical_funding'],
            'label' => ['required', 'string', 'max:120'],
        ]);

        PartnerOption::updateOrCreate(
            ['category' => $data['category'], 'label' => $data['label']],
            ['is_active' => true]
        );

        return redirect()->route('partner-options.index')->with('status', 'تمت إضافة الخيار');
    }

    public function update(Request $request, PartnerOption $partnerOption)
    {
        $data = $request->validate([
            'label' => ['required', 'string', 'max:120'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $partnerOption->update([
            'label' => $data['label'],
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()->route('partner-options.index')->with('status', 'تم تحديث الخيار');
    }

    public function destroy(PartnerOption $partnerOption)
    {
        $partnerOption->delete();

        return redirect()->route('partner-options.index')->with('status', 'تم حذف الخيار');
    }
}
