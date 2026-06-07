<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('email_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mahasiswa_id')->constrained('mahasiswas')->onDelete('cascade');
            $table->string('email_tujuan');
            $table->string('nama_mahasiswa');
            $table->string('kelas')->nullable();
            $table->json('kategori_risiko'); // ['nilai', 'absensi'] atau salah satu
            $table->integer('jumlah_nilai_de')->default(0);
            $table->integer('total_alpha')->default(0);
            $table->enum('status', ['berhasil', 'gagal'])->default('berhasil');
            $table->text('pesan_error')->nullable(); // jika gagal
            $table->foreignId('dikirim_oleh')->constrained('users')->onDelete('cascade'); // admin yang kirim
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('email_logs');
    }
};