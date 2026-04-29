<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
 
return new class extends Migration {
    public function up(): void {
        Schema::create('absensis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mahasiswa_id')->constrained('mahasiswas')->onDelete('cascade');
            $table->foreignId('mata_kuliah_id')->constrained('mata_kuliahs')->onDelete('cascade');
            $table->integer('semester');
            $table->string('tahun_akademik', 10);
            $table->integer('jam_hadir')->default(0);
            $table->integer('jam_izin')->default(0);
            $table->integer('jam_sakit')->default(0);
            $table->integer('jam_alpha')->default(0);
            $table->unique(['mahasiswa_id', 'mata_kuliah_id', 'tahun_akademik'], 'unique_absensi');
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('absensis'); }
};