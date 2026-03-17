<?php

use App\Models\Employee;
use App\Models\Mission;

require __DIR__ . '/../vendor/autoload.php';

$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$csvPath = $argv[1] ?? null;
if (!$csvPath || !is_file($csvPath)) {
    fwrite(STDERR, "Usage: php tools/repair_missions_from_csv.php <csv_path>" . PHP_EOL);
    exit(1);
}

$missionMap = [
    'الرياض' => ['country' => 'السعودية', 'city' => 'الرياض'],
    'أبوظبي' => ['country' => 'الإمارات', 'city' => 'أبوظبي'],
    'الدوحة' => ['country' => 'قطر', 'city' => 'الدوحة'],
    'المنامة' => ['country' => 'البحرين', 'city' => 'المنامة'],
    'القاهرة' => ['country' => 'مصر', 'city' => 'القاهرة'],
    'مسقط' => ['country' => 'عُمان', 'city' => 'مسقط'],
    'الجزائر' => ['country' => 'الجزائر', 'city' => 'الجزائر'],
    'بيروت' => ['country' => 'لبنان', 'city' => 'بيروت'],
    'تونس' => ['country' => 'تونس', 'city' => 'تونس'],
    'الكويت' => ['country' => 'الكويت', 'city' => 'الكويت'],
    'الرباط' => ['country' => 'المغرب', 'city' => 'الرباط'],
    'جيبوتي' => ['country' => 'جيبوتي', 'city' => 'جيبوتي'],
    'الخرطوم' => ['country' => 'السودان', 'city' => 'الخرطوم'],
    'عمّان' => ['country' => 'الأردن', 'city' => 'عمّان'],
    'عمان' => ['country' => 'الأردن', 'city' => 'عمّان'],
    'نواكشوط' => ['country' => 'موريتانيا', 'city' => 'نواكشوط'],
    'ليبيا' => ['country' => 'ليبيا', 'city' => 'طرابلس'],
    'بغداد' => ['country' => 'العراق', 'city' => 'بغداد'],
    'مقديشو' => ['country' => 'الصومال', 'city' => 'مقديشو'],
    'دمشق' => ['country' => 'سوريا', 'city' => 'دمشق'],
    'فيينا' => ['country' => 'النمسا', 'city' => 'فيينا'],
    'موسكو' => ['country' => 'روسيا', 'city' => 'موسكو'],
    'روما' => ['country' => 'إيطاليا', 'city' => 'روما'],
    'مدريد' => ['country' => 'إسبانيا', 'city' => 'مدريد'],
    'باريس' => ['country' => 'فرنسا', 'city' => 'باريس'],
    'بودابست' => ['country' => 'المجر', 'city' => 'بودابست'],
    'براغ' => ['country' => 'التشيك', 'city' => 'براغ'],
    'بروكسل' => ['country' => 'بلجيكا', 'city' => 'بروكسل'],
    'صوفيا' => ['country' => 'بلغاريا', 'city' => 'صوفيا'],
    'برلين' => ['country' => 'ألمانيا', 'city' => 'برلين'],
    'وارسو' => ['country' => 'بولندا', 'city' => 'وارسو'],
    'أنقرة' => ['country' => 'تركيا', 'city' => 'أنقرة'],
    'بكين' => ['country' => 'الصين', 'city' => 'بكين'],
    'اسلام اباد' => ['country' => 'باكستان', 'city' => 'اسلام اباد'],
    'إسلام آباد' => ['country' => 'باكستان', 'city' => 'إسلام آباد'],
    'كوالالمبور' => ['country' => 'ماليزيا', 'city' => 'كوالالمبور'],
    'جاكرتا' => ['country' => 'إندونيسيا', 'city' => 'جاكرتا'],
    'طوكيو' => ['country' => 'اليابان', 'city' => 'طوكيو'],
    'أوتاوا' => ['country' => 'كندا', 'city' => 'أوتاوا'],
    'هافانا' => ['country' => 'كوبا', 'city' => 'هافانا'],
    'أديس أبابا' => ['country' => 'إثيوبيا', 'city' => 'أديس أبابا'],
    'دار السلام' => ['country' => 'تنزانيا', 'city' => 'دار السلام'],
    'نيروبي' => ['country' => 'كينيا', 'city' => 'نيروبي'],
    'نيودلهي' => ['country' => 'الهند', 'city' => 'نيودلهي'],
    'جنيف' => ['country' => 'سويسرا', 'city' => 'جنيف'],
    'واشنطن' => ['country' => 'الولايات المتحدة', 'city' => 'واشنطن'],
    'لندن' => ['country' => 'المملكة المتحدة', 'city' => 'لندن'],
    'لاهاي' => ['country' => 'هولندا', 'city' => 'لاهاي'],
    'أسمرا' => ['country' => 'إريتريا', 'city' => 'أسمرا'],
    'بريتوريا' => ['country' => 'جنوب أفريقيا', 'city' => 'بريتوريا'],
    'طهران' => ['country' => 'إيران', 'city' => 'طهران'],
];

$handle = fopen($csvPath, 'r');
if (!$handle) {
    fwrite(STDERR, "Cannot open CSV." . PHP_EOL);
    exit(1);
}

$header = fgetcsv($handle);
if (!$header) {
    fclose($handle);
    fwrite(STDERR, "Empty CSV." . PHP_EOL);
    exit(1);
}

$header = array_map(function ($v) {
    $value = (string) $v;
    if (str_starts_with($value, "\xEF\xBB\xBF")) {
        $value = substr($value, 3);
    }
    return strtolower(trim($value));
}, $header);

$updated = 0;
$rows = 0;

while (($row = fgetcsv($handle)) !== false) {
    $rows++;
    $payload = [];
    foreach ($header as $index => $key) {
        $payload[$key] = $row[$index] ?? null;
    }

    $missionName = trim((string) ($payload['mission'] ?? ''));
    if ($missionName === '') {
        continue;
    }

    $employeeNo = trim((string) ($payload['employee_no'] ?? ''));
    $fullName = trim((string) ($payload['full_name'] ?? ''));

    $employee = null;
    if ($employeeNo !== '') {
        $employee = Employee::where('employee_no', $employeeNo)->first();
    }
    if (!$employee && $fullName !== '') {
        $employee = Employee::where('full_name', $fullName)->first();
    }
    if (!$employee) {
        continue;
    }

    $mission = Mission::find($employee->mission_id);
    if (!$mission) {
        continue;
    }

    $country = $missionMap[$missionName]['country'] ?? null;
    $city = $missionMap[$missionName]['city'] ?? $missionName;

    $mission->name = $missionName;
    $mission->country = $country;
    $mission->city = $city;
    $mission->save();
    $updated++;
}

fclose($handle);

echo "Updated mission rows: {$updated} (rows: {$rows})." . PHP_EOL;