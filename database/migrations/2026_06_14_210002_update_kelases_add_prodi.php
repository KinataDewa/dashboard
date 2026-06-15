<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasColumn('kelas', 'prodi')) {
            Schema::table('kelas', function (Blueprint $table) {
                $table->string('prodi', 50)->nullable()->after('semester');
            });
        }
    }

    public function down(): void
    {
        // prodi mungkin sudah ada sebelum migration ini — jangan drop
    }
};
