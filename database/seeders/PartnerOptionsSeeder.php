<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PartnerOptionsSeeder extends Seeder
{
    public function run(): void
    {
        $rows = [
            ['category' => 'partner_type', 'label' => 'حكومي'],
            ['category' => 'partner_type', 'label' => 'منظمة دولية'],
            ['category' => 'partner_type', 'label' => 'قطاع خاص'],
            ['category' => 'partner_type', 'label' => 'منظمة غير ربحية'],
            ['category' => 'geographic_level', 'label' => 'محلي'],
            ['category' => 'geographic_level', 'label' => 'إقليمي'],
            ['category' => 'geographic_level', 'label' => 'دولي'],
            ['category' => 'strategic_importance', 'label' => 'شريك استراتيجي'],
            ['category' => 'strategic_importance', 'label' => 'شريك تشغيلي'],
            ['category' => 'strategic_importance', 'label' => 'شريك لمرة واحدة'],
            ['category' => 'sector', 'label' => 'دبلوماسي'],
            ['category' => 'sector', 'label' => 'إغاثي'],
            ['category' => 'sector', 'label' => 'تعليمي'],
            ['category' => 'sector', 'label' => 'تقني'],
            ['category' => 'partnership_nature', 'label' => 'تعاون مؤسسي'],
            ['category' => 'partnership_nature', 'label' => 'مذكرة تفاهم'],
            ['category' => 'partnership_nature', 'label' => 'برنامج مشترك'],
            ['category' => 'typical_funding', 'label' => 'ممول بالكامل'],
            ['category' => 'typical_funding', 'label' => 'ممول جزئيًا'],
            ['category' => 'typical_funding', 'label' => 'غير ممول'],
            ['category' => 'typical_funding', 'label' => 'تمويل مشترك'],
        ];

        foreach ($rows as $row) {
            DB::table('partner_options')->updateOrInsert(
                ['category' => $row['category'], 'label' => $row['label']],
                ['is_active' => true]
            );
        }
    }
}
