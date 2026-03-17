<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('application_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('opportunity_id')->constrained('opportunities');
            $table->foreignId('employee_id')->nullable()->constrained('employees');
            $table->date('request_date')->nullable();
            $table->enum('status', ['submitted', 'under_review', 'approved', 'rejected', 'withdrawn'])->default('submitted');
            $table->text('decision_reason')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('application_requests');
    }
};
