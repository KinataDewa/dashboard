<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
 
return new class extends Migration {
    public function up(): void {
        Schema::create('nilais', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mahasiswa_id')->constrained('mahasiswas')->onDelete('cascade');
            $table->foreignId('mata_kuliah_id')->constrained('mata_kuliahs')->onDelete('cascade');
            $table->integer('semester');
            $table->string('tahun_akademik', 10);   // contoh: 2024/2025
            $table->decimal('nilai_tugas', 5, 2)->default(0);
            $table->decimal('nilai_uts', 5, 2)->default(0);
            $table->decimal('nilai_uas', 5, 2)->default(0);
            $table->decimal('nilai_akhir', 5, 2)->default(0); // dihitung otomatis
            $table->string('grade', 2)->nullable();           // A/B/C/D/E
            $table->unique(['mahasiswa_id', 'mata_kuliah_id', 'tahun_akademik'], 'unique_nilai');
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('nilais'); }
};