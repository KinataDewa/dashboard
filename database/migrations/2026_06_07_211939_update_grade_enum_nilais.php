<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // MySQL: ubah enum untuk tambah B+ dan C+
        DB::statement("ALTER TABLE nilais MODIFY COLUMN grade ENUM('A','B+','B','C+','C','D','E') NULL");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE nilais MODIFY COLUMN grade ENUM('A','B','C','D','E') NULL");
    }
};
