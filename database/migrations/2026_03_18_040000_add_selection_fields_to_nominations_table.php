<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('nominations', function (Blueprint $table) {
            $table->string('selection_category', 20)->nullable()->after('status');
            $table->unsignedInteger('rank_order')->nullable()->after('selection_category');
        });
    }

    public function down(): void
    {
        Schema::table('nominations', function (Blueprint $table) {
            $table->dropColumn(['selection_category', 'rank_order']);
        });
    }
};
