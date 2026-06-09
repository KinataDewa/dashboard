<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('kompensasis', function (Blueprint $table) {
            // Buat index biasa dulu agar MySQL punya penopang foreign key
            // sebelum unique composite index dihapus
            $table->index('mahasiswa_id', 'kompensasis_mahasiswa_id_index');
        });

        Schema::table('kompensasis', function (Blueprint $table) {
            $table->dropUnique(['mahasiswa_id', 'semester', 'tahun_akademik']);
        });
    }

    public function down(): void
    {
        Schema::table('kompensasis', function (Blueprint $table) {
            $table->unique(['mahasiswa_id', 'semester', 'tahun_akademik']);
        });

        Schema::table('kompensasis', function (Blueprint $table) {
            $table->dropIndex('kompensasis_mahasiswa_id_index');
        });
    }
};
