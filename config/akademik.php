<?php
return [
    /*
     * Tahun akademik yang sedang aktif.
     * Ubah via TAHUN_AKADEMIK_AKTIF di file .env, atau langsung di sini.
     */
    'tahun_akademik' => env('TAHUN_AKADEMIK_AKTIF', '2024/2025'),

    /*
     * Bobot perhitungan nilai akhir.
     * Total bobot harus = 1.0 (100%).
     */
    'bobot_nilai' => [
        'tugas' => (float) env('BOBOT_TUGAS', 0.3),
        'uts'   => (float) env('BOBOT_UTS',   0.3),
        'uas'   => (float) env('BOBOT_UAS',   0.4),
    ],
];
