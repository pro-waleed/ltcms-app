<?php

use App\Models\Employee;

require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$rows = Employee::query()->orderBy('id')->limit(30)->get(['id','full_name','employee_no']);
foreach ($rows as $row) {
    $name = $row->full_name;
    $flag = (strpos($name, 'Ø') !== false || strpos($name, 'Ù') !== false) ? 'MOJI' : 'OK';
    echo $row->id . " | " . $flag . " | " . $name . "\n";
}
