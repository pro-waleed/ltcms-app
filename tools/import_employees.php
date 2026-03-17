<?php

declare(strict_types=1);

use App\Models\Employee;
use App\Models\Mission;

require __DIR__ . '/../vendor/autoload.php';

$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$path = $argv[1] ?? null;
if (!$path || !is_file($path)) {
    fwrite(STDERR, "Usage: php tools/import_employees.php <csv_path>\n");
    exit(1);
}

$handle = fopen($path, 'r');
if (!$handle) {
    fwrite(STDERR, "Cannot open CSV file.\n");
    exit(1);
}

$header = fgetcsv($handle);
if (!$header) {
    fclose($handle);
    fwrite(STDERR, "Empty CSV.\n");
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
$created = 0;
$updated = 0;

while (($row = fgetcsv($handle)) !== false) {
    $rows++;
    $payload = [];
    foreach ($header as $index => $key) {
        $payload[$key] = $row[$index] ?? null;
    }

    $fullName = trim((string) ($payload['full_name'] ?? ''));
    if ($fullName === '') {
        continue;
    }

    $employeeNo = trim((string) ($payload['employee_no'] ?? ''));
    $jobTitle = trim((string) ($payload['job_title'] ?? ''));
    $missionName = trim((string) ($payload['mission'] ?? ''));
    $education = trim((string) ($payload['education_level'] ?? ''));

    $mission = null;
    if ($missionName !== '') {
        $mission = Mission::firstOrCreate(['name' => $missionName], ['country' => null, 'city' => $missionName]);
    }

    $employee = null;
    if ($employeeNo !== '') {
        $employee = Employee::where('employee_no', $employeeNo)->first();
    }
    if (!$employee) {
        $employee = Employee::where('full_name', $fullName)->first();
    }

    if (!$employee) {
        $employee = Employee::create([
            'employee_no' => $employeeNo !== '' ? $employeeNo : generateEmployeeNo(),
            'full_name' => $fullName,
            'job_title' => $jobTitle !== '' ? $jobTitle : null,
            'mission_id' => $mission?->id,
            'education_level' => $education !== '' ? $education : null,
        ]);
        $created++;
    } else {
        $updates = [];
        if ($employeeNo !== '' && $employee->employee_no !== $employeeNo) {
            $updates['employee_no'] = $employeeNo;
        }
        if ($jobTitle !== '' && (empty($employee->job_title) || isMojibake((string) $employee->job_title))) {
            $updates['job_title'] = $jobTitle;
        }
        if ($education !== '' && (empty($employee->education_level) || isMojibake((string) $employee->education_level))) {
            $updates['education_level'] = $education;
        }
        if ($mission && !$employee->mission_id) {
            $updates['mission_id'] = $mission->id;
        }
        if ($fullName !== '' && (isMojibake($employee->full_name ?? '') || $employee->full_name === '')) {
            $updates['full_name'] = $fullName;
        }
        if ($updates) {
            $employee->update($updates);
            $updated++;
        }
    }
}

fclose($handle);

echo "Imported employees. Created: {$created}, Updated: {$updated}, Rows: {$rows}\n";

function generateEmployeeNo(): string
{
    do {
        $candidate = 'EMP-' . date('Ymd') . '-' . str_pad((string) rand(1, 9999), 4, '0', STR_PAD_LEFT);
    } while (Employee::where('employee_no', $candidate)->exists());

    return $candidate;
}

function isMojibake(string $value): bool
{
    return $value !== '' && (strpos($value, 'ÃƒËœ') !== false || strpos($value, 'Ãƒâ„¢') !== false || strpos($value, 'ÃƒÆ’') !== false);
}

