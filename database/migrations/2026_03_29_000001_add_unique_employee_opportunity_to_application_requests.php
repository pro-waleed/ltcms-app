<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('application_requests', function (Blueprint $table) {
            $table->unique(['opportunity_id', 'employee_id'], 'application_requests_opportunity_employee_unique');
        });
    }

    public function down(): void
    {
        Schema::table('application_requests', function (Blueprint $table) {
            $table->dropUnique('application_requests_opportunity_employee_unique');
        });
    }
};

