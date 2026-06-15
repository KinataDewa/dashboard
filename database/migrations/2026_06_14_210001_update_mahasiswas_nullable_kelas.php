<?php
use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
    public function up(): void
    {
        // kelas_id dan dosen_pa_id sudah nullable di migration asal.
        // Migration ini memastikan kolom nullable jika ada edge case DB lama.
        \DB::statement('ALTER TABLE mahasiswas MODIFY kelas_id BIGINT UNSIGNED NULL');
        \DB::statement('ALTER TABLE mahasiswas MODIFY dosen_pa_id BIGINT UNSIGNED NULL');
    }

    public function down(): void
    {
        // Biarkan nullable — tidak perlu rollback ke NOT NULL
    }
};
