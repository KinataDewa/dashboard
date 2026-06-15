<?php

use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
    public function up(): void
    {
        \DB::statement('ALTER TABLE mahasiswas MODIFY angkatan VARCHAR(10) NOT NULL');
    }

    public function down(): void
    {
        \DB::statement('ALTER TABLE mahasiswas MODIFY angkatan YEAR(4) NOT NULL');
    }
};
