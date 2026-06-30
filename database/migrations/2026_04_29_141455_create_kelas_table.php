<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
 
return new class extends Migration {
    public function up(): void {
        Schema::create('kelas', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 20);
            $table->string('angkatan', 10);
            $table->integer('semester');
            $table->string('tahun_akademik', 10);
            $table->string('prodi', 50)->default('Teknologi Informasi');
            $table->foreignId('dosen_pa_id')->nullable()->constrained('dosens')->onDelete('set null');
            $table->timestamps();
            $table->unique(['nama', 'angkatan', 'tahun_akademik', 'semester']);
        });
    }
    public function down(): void { Schema::dropIfExists('kelas'); }
};