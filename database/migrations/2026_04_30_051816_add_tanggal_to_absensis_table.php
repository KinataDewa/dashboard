<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
 
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('absensis', function (Blueprint $table) {
            // Tambah kolom tanggal setelah tahun_akademik
            $table->date('tanggal')->nullable()->after('tahun_akademik');
            // Tambah kolom pertemuan ke-
            $table->integer('pertemuan_ke')->default(1)->after('tanggal');
        });
    }
 
    public function down(): void
    {
        Schema::table('absensis', function (Blueprint $table) {
            $table->dropColumn(['tanggal', 'pertemuan_ke']);
        });
    }
};