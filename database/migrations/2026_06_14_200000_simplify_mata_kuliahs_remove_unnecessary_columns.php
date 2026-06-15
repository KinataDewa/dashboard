<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        \DB::statement('SET FOREIGN_KEY_CHECKS=0');

        foreach (['mata_kuliahs_kelas_id_foreign', 'mata_kuliahs_dosen_id_foreign'] as $fk) {
            $exists = \DB::select(
                "SELECT COUNT(*) AS cnt FROM information_schema.TABLE_CONSTRAINTS
                 WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'mata_kuliahs'
                 AND CONSTRAINT_NAME = ? AND CONSTRAINT_TYPE = 'FOREIGN KEY'",
                [$fk]
            );
            if ($exists[0]->cnt > 0) {
                \DB::statement("ALTER TABLE mata_kuliahs DROP FOREIGN KEY `{$fk}`");
            }
        }

        foreach (['semester', 'kelas_id', 'dosen_id'] as $col) {
            if (Schema::hasColumn('mata_kuliahs', $col)) {
                \DB::statement("ALTER TABLE mata_kuliahs DROP COLUMN `{$col}`");
            }
        }

        \DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }

    public function down(): void
    {
        Schema::table('mata_kuliahs', function (Blueprint $table) {
            $table->integer('semester')->nullable()->after('sks');
            $table->foreignId('kelas_id')->nullable()->after('semester')->constrained('kelas')->onDelete('set null');
            $table->foreignId('dosen_id')->nullable()->after('kelas_id')->constrained('dosens')->onDelete('set null');
        });
    }
};
