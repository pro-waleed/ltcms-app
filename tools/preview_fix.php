<?php

use App\Models\Employee;

require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$rows = Employee::query()->orderBy('id')->limit(5)->get(['full_name']);
foreach ($rows as $row) {
    $value = $row->full_name;
    echo "RAW: $value\n";
    $a = @iconv('Windows-1252', 'UTF-8', $value);
    $b = @iconv('ISO-8859-1', 'UTF-8', $value);
    $c = @utf8_encode(utf8_decode($value));
    echo "W1252: $a\n";
    echo "Latin1: $b\n";
    echo "utf8_decode/encode: $c\n";
    echo "---\n";
}
