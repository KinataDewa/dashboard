<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('mata_kuliahs', function (Blueprint $table) {
            $table->integer('semester')->nullable()->change();
        });
    }

    public function down(): void
    {
        \DB::statement('UPDATE mata_kuliahs SET semester = 0 WHERE semester IS NULL');
        Schema::table('mata_kuliahs', function (Blueprint $table) {
            $table->integer('semester')->nullable(false)->change();
        });
    }
};
