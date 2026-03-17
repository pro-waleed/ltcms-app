<?php

use App\Models\Department;
use App\Models\Employee;
use App\Models\Mission;

require __DIR__ . '/../vendor/autoload.php';

$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$updated = [
    'departments' => 0,
    'missions' => 0,
    'employees' => 0,
];

Department::query()->orderBy('id')->chunk(200, function ($departments) use (&$updated) {
    foreach ($departments as $department) {
        $name = (string) ($department->name ?? '');
        if ($name !== '' && isMojibake($name)) {
            $fixed = fixMojibake($name);
            if ($fixed !== '' && $fixed !== $name) {
                $department->name = $fixed;
                $department->save();
                $updated['departments']++;
            }
        }
    }
});

Mission::query()->orderBy('id')->chunk(200, function ($missions) use (&$updated) {
    foreach ($missions as $mission) {
        $changed = false;
        $fields = ['name', 'country', 'city'];
        foreach ($fields as $field) {
            $value = (string) ($mission->{$field} ?? '');
            if ($value !== '' && isMojibake($value)) {
                $fixed = fixMojibake($value);
                if ($fixed !== '' && $fixed !== $value) {
                    $mission->{$field} = $fixed;
                    $changed = true;
                }
            }
        }
        if ($changed) {
            $mission->save();
            $updated['missions']++;
        }
    }
});

Employee::query()->orderBy('id')->chunk(200, function ($employees) use (&$updated) {
    foreach ($employees as $employee) {
        $changed = false;
        $fields = ['full_name', 'job_title', 'education_level', 'specialization', 'languages', 'work_location', 'employment_status', 'notes'];
        foreach ($fields as $field) {
            $value = (string) ($employee->{$field} ?? '');
            if ($value !== '' && isMojibake($value)) {
                $fixed = fixMojibake($value);
                if ($fixed !== '' && $fixed !== $value) {
                    $employee->{$field} = $fixed;
                    $changed = true;
                }
            }
        }
        if ($changed) {
            $employee->save();
            $updated['employees']++;
        }
    }
});

echo "Repair complete. Departments: {$updated['departments']}, Missions: {$updated['missions']}, Employees: {$updated['employees']}" . PHP_EOL;

function isMojibake(string $value): bool
{
    return $value !== '' && (strpos($value, 'Ãƒ') !== false || strpos($value, 'Ã˜') !== false || strpos($value, 'Ã™') !== false);
}

function fixMojibake(string $value): string
{
    $fixed = $value;
    for ($i = 0; $i < 4; $i++) {
        if (!isMojibake($fixed)) {
            break;
        }
        $converted = @iconv('ISO-8859-1', 'UTF-8//IGNORE', $fixed);
        if ($converted === false || $converted === $fixed) {
            break;
        }
        $fixed = $converted;
    }
    return $fixed;
}