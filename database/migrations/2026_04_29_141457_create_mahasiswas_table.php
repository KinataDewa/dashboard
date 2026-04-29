<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
 
return new class extends Migration {
    public function up(): void {
        Schema::create('mahasiswas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('nim', 20)->unique();
            $table->string('nama', 100);
            $table->foreignId('kelas_id')->nullable()->constrained('kelas')->onDelete('set null');
            $table->year('angkatan');
            $table->enum('status', ['aktif', 'cuti', 'lulus', 'keluar'])->default('aktif');
            $table->foreignId('dosen_pa_id')->nullable()->constrained('dosens')->onDelete('set null');
            $table->string('no_hp', 20)->nullable();
            $table->string('alamat')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('mahasiswas'); }
};