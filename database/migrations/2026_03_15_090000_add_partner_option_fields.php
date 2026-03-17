<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('partners', function (Blueprint $table) {
            $table->string('geographic_level', 50)->nullable();
            $table->string('strategic_importance', 80)->nullable();
            $table->string('sector', 80)->nullable();
        });

        Schema::create('partner_options', function (Blueprint $table) {
            $table->id();
            $table->string('category', 50);
            $table->string('label', 120);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['category', 'label']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('partner_options');

        Schema::table('partners', function (Blueprint $table) {
            $table->dropColumn(['geographic_level', 'strategic_importance', 'sector']);
        });
    }
};
