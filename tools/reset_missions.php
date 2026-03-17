<?php

use App\Models\Employee;
use App\Models\Mission;

require __DIR__ . '/../vendor/autoload.php';

$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$missions = [
    ['name' => 'الرياض', 'country' => 'السعودية', 'city' => 'الرياض'],
    ['name' => 'أبوظبي', 'country' => 'الإمارات', 'city' => 'أبوظبي'],
    ['name' => 'الدوحة', 'country' => 'قطر', 'city' => 'الدوحة'],
    ['name' => 'المنامة', 'country' => 'البحرين', 'city' => 'المنامة'],
    ['name' => 'القاهرة', 'country' => 'مصر', 'city' => 'القاهرة'],
    ['name' => 'مسقط', 'country' => 'عُمان', 'city' => 'مسقط'],
    ['name' => 'الجزائر', 'country' => 'الجزائر', 'city' => 'الجزائر'],
    ['name' => 'بيروت', 'country' => 'لبنان', 'city' => 'بيروت'],
    ['name' => 'تونس', 'country' => 'تونس', 'city' => 'تونس'],
    ['name' => 'الكويت', 'country' => 'الكويت', 'city' => 'الكويت'],
    ['name' => 'الرباط', 'country' => 'المغرب', 'city' => 'الرباط'],
    ['name' => 'جيبوتي', 'country' => 'جيبوتي', 'city' => 'جيبوتي'],
    ['name' => 'الخرطوم', 'country' => 'السودان', 'city' => 'الخرطوم'],
    ['name' => 'عمّان', 'country' => 'الأردن', 'city' => 'عمّان'],
    ['name' => 'نواكشوط', 'country' => 'موريتانيا', 'city' => 'نواكشوط'],
    ['name' => 'ليبيا', 'country' => 'ليبيا', 'city' => 'طرابلس'],
    ['name' => 'بغداد', 'country' => 'العراق', 'city' => 'بغداد'],
    ['name' => 'مقديشو', 'country' => 'الصومال', 'city' => 'مقديشو'],
    ['name' => 'دمشق', 'country' => 'سوريا', 'city' => 'دمشق'],
    ['name' => 'فيينا', 'country' => 'النمسا', 'city' => 'فيينا'],
    ['name' => 'موسكو', 'country' => 'روسيا', 'city' => 'موسكو'],
    ['name' => 'روما', 'country' => 'إيطاليا', 'city' => 'روما'],
    ['name' => 'مدريد', 'country' => 'إسبانيا', 'city' => 'مدريد'],
    ['name' => 'باريس', 'country' => 'فرنسا', 'city' => 'باريس'],
    ['name' => 'بودابست', 'country' => 'المجر', 'city' => 'بودابست'],
    ['name' => 'براغ', 'country' => 'التشيك', 'city' => 'براغ'],
    ['name' => 'بروكسل', 'country' => 'بلجيكا', 'city' => 'بروكسل'],
    ['name' => 'صوفيا', 'country' => 'بلغاريا', 'city' => 'صوفيا'],
    ['name' => 'برلين', 'country' => 'ألمانيا', 'city' => 'برلين'],
    ['name' => 'وارسو', 'country' => 'بولندا', 'city' => 'وارسو'],
    ['name' => 'أنقرة', 'country' => 'تركيا', 'city' => 'أنقرة'],
    ['name' => 'بكين', 'country' => 'الصين', 'city' => 'بكين'],
    ['name' => 'إسلام آباد', 'country' => 'باكستان', 'city' => 'إسلام آباد'],
    ['name' => 'كوالالمبور', 'country' => 'ماليزيا', 'city' => 'كوالالمبور'],
    ['name' => 'جاكرتا', 'country' => 'إندونيسيا', 'city' => 'جاكرتا'],
    ['name' => 'طوكيو', 'country' => 'اليابان', 'city' => 'طوكيو'],
    ['name' => 'أوتاوا', 'country' => 'كندا', 'city' => 'أوتاوا'],
    ['name' => 'هافانا', 'country' => 'كوبا', 'city' => 'هافانا'],
    ['name' => 'أديس أبابا', 'country' => 'إثيوبيا', 'city' => 'أديس أبابا'],
    ['name' => 'دار السلام', 'country' => 'تنزانيا', 'city' => 'دار السلام'],
    ['name' => 'نيروبي', 'country' => 'كينيا', 'city' => 'نيروبي'],
    ['name' => 'نيودلهي', 'country' => 'الهند', 'city' => 'نيودلهي'],
    ['name' => 'جنيف', 'country' => 'سويسرا', 'city' => 'جنيف'],
    ['name' => 'واشنطن', 'country' => 'الولايات المتحدة', 'city' => 'واشنطن'],
    ['name' => 'لندن', 'country' => 'المملكة المتحدة', 'city' => 'لندن'],
    ['name' => 'لاهاي', 'country' => 'هولندا', 'city' => 'لاهاي'],
    ['name' => 'أسمرا', 'country' => 'إريتريا', 'city' => 'أسمرا'],
    ['name' => 'بريتوريا', 'country' => 'جنوب أفريقيا', 'city' => 'بريتوريا'],
    ['name' => 'طهران', 'country' => 'إيران', 'city' => 'طهران'],
];

Employee::query()->update(['mission_id' => null]);
Mission::query()->delete();

foreach ($missions as $mission) {
    Mission::create($mission);
}

echo "Missions reset: " . count($missions) . PHP_EOL;