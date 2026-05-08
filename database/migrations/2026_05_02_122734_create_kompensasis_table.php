<?php
 
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
 
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kompensasis', function (Blueprint $table) {
            $table->id();
 
            $table->foreignId('mahasiswa_id')
                  ->constrained('mahasiswas')
                  ->onDelete('cascade');
 
            $table->integer('semester');
            $table->string('tahun_akademik', 20); // contoh: 2024/2025
 
            $table->integer('jam_alpha');          // total alpha semester tsb
            $table->integer('multiplier')->default(1); // 1=sem ini, 2=sem depan, 4=2 sem, dst
            $table->integer('jam_kompen_wajib');   // jam_alpha * multiplier * 2
 
            $table->enum('status', ['pending', 'lunas'])->default('pending');
 
            $table->text('catatan_tugas')->nullable(); // tugas yang diberikan admin
            $table->boolean('ttd_admin')->default(false);
            $table->boolean('ttd_kajur')->default(false);
 
            $table->timestamp('tanggal_lunas')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
 
            $table->timestamps();
 
            // Satu mahasiswa hanya boleh punya 1 kompen per semester per tahun
            $table->unique(['mahasiswa_id', 'semester', 'tahun_akademik']);
        });
    }
 
    public function down(): void
    {
        Schema::dropIfExists('kompensasis');
    }
};