<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50)->unique();
            $table->string('description', 255)->nullable();
            $table->timestamps();
        });

        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('username', 50)->unique();
            $table->string('full_name', 150);
            $table->string('email', 150)->nullable()->unique();
            $table->string('password', 255);
            $table->boolean('is_active')->default(true);
            $table->timestamp('last_login_at')->nullable();
            $table->timestamps();
        });

        Schema::create('role_user', function (Blueprint $table) {
            $table->foreignId('role_id')->constrained('roles');
            $table->foreignId('user_id')->constrained('users');
            $table->primary(['role_id', 'user_id']);
        });

        Schema::create('departments', function (Blueprint $table) {
            $table->id();
            $table->string('name', 150)->unique();
            $table->foreignId('parent_id')->nullable()->constrained('departments');
            $table->timestamps();
        });

        Schema::create('missions', function (Blueprint $table) {
            $table->id();
            $table->string('name', 150)->unique();
            $table->string('country', 100)->nullable();
            $table->string('city', 100)->nullable();
            $table->timestamps();
        });

        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('employee_no', 50)->unique();
            $table->string('full_name', 150);
            $table->foreignId('department_id')->nullable()->constrained('departments');
            $table->foreignId('mission_id')->nullable()->constrained('missions');
            $table->string('job_title', 120)->nullable();
            $table->string('job_grade', 50)->nullable();
            $table->string('education_level', 80)->nullable();
            $table->string('specialization', 120)->nullable();
            $table->text('languages')->nullable();
            $table->string('language_level', 50)->nullable();
            $table->integer('years_of_service')->nullable();
            $table->string('work_location', 120)->nullable();
            $table->string('employment_status', 50)->nullable();
            $table->integer('previous_opportunities_count')->default(0);
            $table->date('last_training_date')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('partners', function (Blueprint $table) {
            $table->id();
            $table->string('name', 200);
            $table->string('partner_type', 80);
            $table->string('country', 100)->nullable();
            $table->string('partnership_nature', 120)->nullable();
            $table->text('cooperation_areas')->nullable();
            $table->string('contact_name', 120)->nullable();
            $table->string('contact_email', 150)->nullable();
            $table->string('contact_phone', 50)->nullable();
            $table->text('typical_opportunities')->nullable();
            $table->string('typical_funding', 120)->nullable();
            $table->text('evaluation_notes')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
        });

        Schema::create('opportunity_types', function (Blueprint $table) {
            $table->id();
            $table->string('name', 80)->unique();
            $table->string('description', 255)->nullable();
            $table->timestamps();
        });

        Schema::create('funding_details', function (Blueprint $table) {
            $table->id();
            $table->enum('funding_type', ['fully_funded', 'partially_funded', 'not_funded', 'co_funded']);
            $table->enum('training_fees', ['included', 'excluded', 'unspecified'])->default('unspecified');
            $table->enum('international_tickets', ['included', 'excluded', 'unspecified'])->default('unspecified');
            $table->enum('domestic_tickets', ['included', 'excluded', 'unspecified'])->default('unspecified');
            $table->enum('accommodation', ['included', 'excluded', 'unspecified'])->default('unspecified');
            $table->enum('meals', ['included', 'excluded', 'unspecified'])->default('unspecified');
            $table->enum('local_transport', ['included', 'excluded', 'unspecified'])->default('unspecified');
            $table->enum('health_insurance', ['included', 'excluded', 'unspecified'])->default('unspecified');
            $table->enum('visa_fees', ['included', 'excluded', 'unspecified'])->default('unspecified');
            $table->enum('per_diem', ['included', 'excluded', 'unspecified'])->default('unspecified');
            $table->enum('training_materials', ['included', 'excluded', 'unspecified'])->default('unspecified');
            $table->enum('tech_support', ['included', 'excluded', 'unspecified'])->default('unspecified');
            $table->text('ministry_obligations')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('opportunities', function (Blueprint $table) {
            $table->id();
            $table->string('reference_no', 20)->unique();
            $table->string('title', 200);
            $table->foreignId('opportunity_type_id')->constrained('opportunity_types');
            $table->text('summary')->nullable();
            $table->string('provider_entity', 200)->nullable();
            $table->string('organizer_entity', 200)->nullable();
            $table->enum('delivery_mode', ['onsite', 'online', 'hybrid']);
            $table->string('location_country', 100)->nullable();
            $table->string('location_city', 100)->nullable();
            $table->string('location_platform', 120)->nullable();
            $table->string('language', 50)->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->integer('duration_days')->nullable();
            $table->integer('seats')->nullable();
            $table->date('nomination_deadline')->nullable();
            $table->string('target_group', 200)->nullable();
            $table->text('eligibility_requirements')->nullable();
            $table->text('admin_notes')->nullable();
            $table->enum('status', ['draft', 'received', 'under_review', 'open_for_nomination', 'closed', 'nominated', 'executed', 'closed_no_benefit', 'referred', 'cancelled'])->default('draft');
            $table->foreignId('partner_id')->nullable()->constrained('partners');
            $table->foreignId('funding_detail_id')->nullable()->constrained('funding_details');
            $table->foreignId('created_by')->nullable()->constrained('users');
            $table->timestamps();
        });

        Schema::create('external_entities', function (Blueprint $table) {
            $table->id();
            $table->string('name', 200);
            $table->string('entity_type', 80)->nullable();
            $table->string('contact_name', 120)->nullable();
            $table->string('contact_phone', 50)->nullable();
            $table->integer('seats')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('nominations', function (Blueprint $table) {
            $table->id();
            $table->string('nomination_no', 30)->unique();
            $table->foreignId('opportunity_id')->constrained('opportunities');
            $table->foreignId('employee_id')->nullable()->constrained('employees');
            $table->foreignId('external_entity_id')->nullable()->constrained('external_entities');
            $table->foreignId('nominated_by_department_id')->nullable()->constrained('departments');
            $table->date('nomination_date')->nullable();
            $table->string('nomination_type', 80)->nullable();
            $table->enum('status', ['nominated', 'under_review', 'approved', 'reserve', 'rejected', 'declined', 'attended', 'not_attended', 'completed', 'closed'])->default('nominated');
            $table->boolean('accepted')->nullable();
            $table->boolean('declined')->nullable();
            $table->boolean('attended')->nullable();
            $table->boolean('certificate_received')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('attachments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('opportunity_id')->nullable()->constrained('opportunities');
            $table->foreignId('nomination_id')->nullable()->constrained('nominations');
            $table->string('file_name', 255);
            $table->string('file_path', 500);
            $table->unsignedBigInteger('file_size')->nullable();
            $table->string('mime_type', 120)->nullable();
            $table->foreignId('uploaded_by')->nullable()->constrained('users');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('status_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('opportunity_id')->constrained('opportunities');
            $table->string('old_status', 50)->nullable();
            $table->string('new_status', 50);
            $table->foreignId('changed_by')->nullable()->constrained('users');
            $table->timestamp('changed_at')->useCurrent();
            $table->text('notes')->nullable();
        });

        Schema::create('training_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees');
            $table->foreignId('opportunity_id')->constrained('opportunities');
            $table->foreignId('nomination_id')->nullable()->constrained('nominations');
            $table->enum('completion_status', ['completed', 'not_completed'])->default('completed');
            $table->boolean('certificate_received')->nullable();
            $table->date('completion_date')->nullable();
            $table->text('notes')->nullable();
            $table->timestamp('created_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('training_history');
        Schema::dropIfExists('status_logs');
        Schema::dropIfExists('attachments');
        Schema::dropIfExists('nominations');
        Schema::dropIfExists('external_entities');
        Schema::dropIfExists('opportunities');
        Schema::dropIfExists('funding_details');
        Schema::dropIfExists('opportunity_types');
        Schema::dropIfExists('partners');
        Schema::dropIfExists('employees');
        Schema::dropIfExists('missions');
        Schema::dropIfExists('departments');
        Schema::dropIfExists('role_user');
        Schema::dropIfExists('users');
        Schema::dropIfExists('roles');
    }
};
