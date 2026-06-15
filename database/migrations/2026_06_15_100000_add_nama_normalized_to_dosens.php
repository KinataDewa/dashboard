<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('dosens', function (Blueprint $table) {
            $table->string('nama_normalized')->nullable()->after('nama');
            $table->index('nama_normalized');
        });

        // Populate existing records
        foreach (DB::table('dosens')->get() as $dosen) {
            DB::table('dosens')->where('id', $dosen->id)->update([
                'nama_normalized' => self::normalize($dosen->nama ?? ''),
            ]);
        }
    }

    public function down(): void
    {
        Schema::table('dosens', function (Blueprint $table) {
            $table->dropIndex(['nama_normalized']);
            $table->dropColumn('nama_normalized');
        });
    }

    private static function normalize(string $nama): string
    {
        // Hapus gelar prefiks (Prof., Dr., Ir., Drs., Dra., Hj., H.)
        $nama = preg_replace('/\b(Prof|Dr|Ir|Drs|Dra|Hj|H)\.?\s+/iu', '', $nama);

        // Ambil bagian sebelum koma pertama (gelar sufiks biasanya setelah koma)
        if (strpos($nama, ',') !== false) {
            $nama = explode(',', $nama)[0];
        }

        // Hapus singkatan gelar tersisa (e.g. S.Kom., M.T., Ph.D., M.B.A.)
        $nama = preg_replace('/\b[A-Za-z]{1,5}(\.[A-Za-z]{1,5}){1,3}\.?/', '', $nama);

        return trim(preg_replace('/\s+/', ' ', mb_strtolower($nama)));
    }
};
