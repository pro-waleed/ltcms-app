<?php

declare(strict_types=1);

use App\Models\Employee;
use App\Models\Mission;

require __DIR__ . '/../vendor/autoload.php';

$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

function fixMojibake(string $value): string
{
    if ($value === '') {
        return $value;
    }
    if (strpos($value, 'Ø') === false && strpos($value, 'Ù') === false) {
        return $value;
    }
    $latin1 = mb_convert_encoding($value, 'ISO-8859-1', 'UTF-8');
    return mb_convert_encoding($latin1, 'UTF-8', 'ISO-8859-1');
}

$fixedEmployees = 0;
$fixedMissions = 0;

Employee::query()->chunkById(200, function ($employees) use (&$fixedEmployees) {
    foreach ($employees as $employee) {
        $fixed = fixMojibake($employee->full_name ?? '');
        if ($fixed !== '' && $fixed !== $employee->full_name) {
            $employee->update(['full_name' => $fixed]);
            $fixedEmployees++;
        }
    }
});

Mission::query()->chunkById(200, function ($missions) use (&$fixedMissions) {
    foreach ($missions as $mission) {
        $fixed = fixMojibake($mission->name ?? '');
        if ($fixed !== '' && $fixed !== $mission->name) {
            $mission->update(['name' => $fixed]);
            $fixedMissions++;
        }
    }
});

echo "Fixed employees: {$fixedEmployees}, missions: {$fixedMissions}\n";
