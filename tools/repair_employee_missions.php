<?php

use App\Models\Employee;
use App\Models\Mission;

require __DIR__ . '/../vendor/autoload.php';

$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$csvPath = $argv[1] ?? null;
if (!$csvPath || !is_file($csvPath)) {
    fwrite(STDERR, "Usage: php tools/repair_employee_missions.php <csv_path>" . PHP_EOL);
    exit(1);
}

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

$rows = 0;
$updated = 0;

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

    $mission = Mission::firstOrCreate(
        ['name' => $missionName],
        ['country' => null, 'city' => $missionName]
    );

    if ($employee->mission_id !== $mission->id) {
        $employee->mission_id = $mission->id;
        $employee->save();
        $updated++;
    }
}

fclose($handle);

echo "Updated missions for {$updated} employees (rows: {$rows})." . PHP_EOL;