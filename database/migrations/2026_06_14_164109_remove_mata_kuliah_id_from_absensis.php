<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        \DB::table('absensis')->truncate();

        // Tambah unique index baru agar FK mahasiswa_id tetap punya pendukung
        // ketika unique_absensi lama dihapus
        if (!$this->indexExists('absensis', 'unique_absensi_semester')) {
            \DB::statement('ALTER TABLE absensis ADD UNIQUE KEY unique_absensi_semester (mahasiswa_id, semester)');
        }

        // Hapus index lama jika masih ada
        if ($this->indexExists('absensis', 'unique_absensi')) {
            \DB::statement('ALTER TABLE absensis DROP INDEX unique_absensi');
        }
        if ($this->indexExists('absensis', 'absensis_mata_kuliah_id_foreign')) {
            \DB::statement('ALTER TABLE absensis DROP INDEX absensis_mata_kuliah_id_foreign');
        }

        // Hapus kolom yang tidak diperlukan lagi
        foreach (['mata_kuliah_id', 'tahun_akademik', 'tanggal', 'pertemuan_ke'] as $col) {
            if (Schema::hasColumn('absensis', $col)) {
                \DB::statement("ALTER TABLE absensis DROP COLUMN `{$col}`");
            }
        }
    }

    private function indexExists(string $table, string $indexName): bool
    {
        $indexes = \DB::select("SHOW INDEX FROM `{$table}` WHERE Key_name = ?", [$indexName]);
        return !empty($indexes);
    }

    public function down(): void
    {
        Schema::table('absensis', function (Blueprint $table) {
            $table->dropUnique('unique_absensi_semester');
        });

        Schema::table('absensis', function (Blueprint $table) {
            $table->foreignId('mata_kuliah_id')->after('mahasiswa_id')->constrained('mata_kuliahs')->onDelete('cascade');
            $table->string('tahun_akademik', 10)->after('semester');
            $table->date('tanggal')->nullable()->after('tahun_akademik');
            $table->integer('pertemuan_ke')->default(1)->after('tanggal');
            $table->unique(['mahasiswa_id', 'mata_kuliah_id', 'tahun_akademik'], 'unique_absensi');
        });
    }
};
