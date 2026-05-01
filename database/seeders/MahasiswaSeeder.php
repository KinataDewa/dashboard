<?php
// database/seeders/MahasiswaSeeder.php
// GANTI SELURUH ISI
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Mahasiswa;
use App\Models\Kelas;
use App\Models\Dosen;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class MahasiswaSeeder extends Seeder
{
    public function run(): void
    {
        $role = Role::firstOrCreate(['name' => 'mahasiswa']);

        $ti3c = Kelas::where('nama','TI3C')->first();
        $ti3d = Kelas::where('nama','TI3D')->first();
        $ti3a = Kelas::where('nama','TI3A')->first();
        $ti3b = Kelas::where('nama','TI3B')->first();

        $budi   = Dosen::where('nip','197501012005011001')->first();
        $elok   = Dosen::where('nip','198505152010012001')->first();
        $zawar  = Dosen::where('nip','198712202015031001')->first();
        $ridwan = Dosen::where('nip','197803152006041002')->first();

        $mahasiswas = [

            // ════════════════════════════════════════════════
            // TI3D — PA: Elok Nur Hamdana (27 mahasiswa)
            // ════════════════════════════════════════════════
            ['nim'=>'2341720099','nama'=>'Kinata Dewa',            'email'=>'kinata.dewa@student.polinema.ac.id',        'kelas_id'=>$ti3d?->id,'dosen_pa_id'=>$elok?->id,'angkatan'=>2023,'status'=>'aktif'],
            ['nim'=>'2341720100','nama'=>'Ahmad Rizaldi',          'email'=>'ahmad.rizaldi@student.polinema.ac.id',      'kelas_id'=>$ti3d?->id,'dosen_pa_id'=>$elok?->id,'angkatan'=>2023,'status'=>'aktif'],
            ['nim'=>'2341720101','nama'=>'Berliana Putri',         'email'=>'berliana.putri@student.polinema.ac.id',     'kelas_id'=>$ti3d?->id,'dosen_pa_id'=>$elok?->id,'angkatan'=>2023,'status'=>'aktif'],
            ['nim'=>'2341720102','nama'=>'Cahyo Nugroho',          'email'=>'cahyo.nugroho@student.polinema.ac.id',      'kelas_id'=>$ti3d?->id,'dosen_pa_id'=>$elok?->id,'angkatan'=>2023,'status'=>'aktif'],
            ['nim'=>'2341720103','nama'=>'Dewi Ayu Lestari',       'email'=>'dewi.ayu@student.polinema.ac.id',           'kelas_id'=>$ti3d?->id,'dosen_pa_id'=>$elok?->id,'angkatan'=>2023,'status'=>'aktif'],
            ['nim'=>'2341720104','nama'=>'Endra Saputra',          'email'=>'endra.saputra@student.polinema.ac.id',      'kelas_id'=>$ti3d?->id,'dosen_pa_id'=>$elok?->id,'angkatan'=>2023,'status'=>'aktif'],
            ['nim'=>'2341720105','nama'=>'Fira Nabilah',           'email'=>'fira.nabilah@student.polinema.ac.id',       'kelas_id'=>$ti3d?->id,'dosen_pa_id'=>$elok?->id,'angkatan'=>2023,'status'=>'aktif'],
            ['nim'=>'2341720106','nama'=>'Ghifari Akbar',          'email'=>'ghifari.akbar@student.polinema.ac.id',      'kelas_id'=>$ti3d?->id,'dosen_pa_id'=>$elok?->id,'angkatan'=>2023,'status'=>'aktif'],
            ['nim'=>'2341720107','nama'=>'Hasna Aulia',            'email'=>'hasna.aulia@student.polinema.ac.id',        'kelas_id'=>$ti3d?->id,'dosen_pa_id'=>$elok?->id,'angkatan'=>2023,'status'=>'aktif'],
            ['nim'=>'2341720108','nama'=>'Ilham Pradipta',         'email'=>'ilham.pradipta@student.polinema.ac.id',     'kelas_id'=>$ti3d?->id,'dosen_pa_id'=>$elok?->id,'angkatan'=>2023,'status'=>'aktif'],
            ['nim'=>'2341720109','nama'=>'Jasmine Azzahra',        'email'=>'jasmine.azzahra@student.polinema.ac.id',    'kelas_id'=>$ti3d?->id,'dosen_pa_id'=>$elok?->id,'angkatan'=>2023,'status'=>'aktif'],
            ['nim'=>'2341720110','nama'=>'Kevin Ardianto',         'email'=>'kevin.ardianto@student.polinema.ac.id',     'kelas_id'=>$ti3d?->id,'dosen_pa_id'=>$elok?->id,'angkatan'=>2023,'status'=>'aktif'],
            ['nim'=>'2341720111','nama'=>'Laras Wulandari',        'email'=>'laras.wulandari@student.polinema.ac.id',    'kelas_id'=>$ti3d?->id,'dosen_pa_id'=>$elok?->id,'angkatan'=>2023,'status'=>'aktif'],
            ['nim'=>'2341720112','nama'=>'Muhammad Farhan',        'email'=>'muhammad.farhan@student.polinema.ac.id',    'kelas_id'=>$ti3d?->id,'dosen_pa_id'=>$elok?->id,'angkatan'=>2023,'status'=>'aktif'],
            ['nim'=>'2341720113','nama'=>'Nadia Safitri',          'email'=>'nadia.safitri@student.polinema.ac.id',      'kelas_id'=>$ti3d?->id,'dosen_pa_id'=>$elok?->id,'angkatan'=>2023,'status'=>'aktif'],
            ['nim'=>'2341720114','nama'=>'Oscar Firmansyah',       'email'=>'oscar.firmansyah@student.polinema.ac.id',   'kelas_id'=>$ti3d?->id,'dosen_pa_id'=>$elok?->id,'angkatan'=>2023,'status'=>'aktif'],
            ['nim'=>'2341720115','nama'=>'Putri Rahayu',           'email'=>'putri.rahayu@student.polinema.ac.id',       'kelas_id'=>$ti3d?->id,'dosen_pa_id'=>$elok?->id,'angkatan'=>2023,'status'=>'aktif'],
            ['nim'=>'2341720116','nama'=>'Qori Maharani',          'email'=>'qori.maharani@student.polinema.ac.id',      'kelas_id'=>$ti3d?->id,'dosen_pa_id'=>$elok?->id,'angkatan'=>2023,'status'=>'aktif'],
            ['nim'=>'2341720117','nama'=>'Rafi Santosa',           'email'=>'rafi.santosa@student.polinema.ac.id',       'kelas_id'=>$ti3d?->id,'dosen_pa_id'=>$elok?->id,'angkatan'=>2023,'status'=>'aktif'],
            ['nim'=>'2341720118','nama'=>'Sabrina Kusuma',         'email'=>'sabrina.kusuma@student.polinema.ac.id',     'kelas_id'=>$ti3d?->id,'dosen_pa_id'=>$elok?->id,'angkatan'=>2023,'status'=>'aktif'],
            ['nim'=>'2341720119','nama'=>'Taufik Hidayat',         'email'=>'taufik.hidayat@student.polinema.ac.id',     'kelas_id'=>$ti3d?->id,'dosen_pa_id'=>$elok?->id,'angkatan'=>2023,'status'=>'aktif'],
            ['nim'=>'2341720120','nama'=>'Ulfah Mardiyah',         'email'=>'ulfah.mardiyah@student.polinema.ac.id',     'kelas_id'=>$ti3d?->id,'dosen_pa_id'=>$elok?->id,'angkatan'=>2023,'status'=>'aktif'],
            ['nim'=>'2341720121','nama'=>'Vino Ardiansyah',        'email'=>'vino.ardiansyah@student.polinema.ac.id',    'kelas_id'=>$ti3d?->id,'dosen_pa_id'=>$elok?->id,'angkatan'=>2023,'status'=>'aktif'],
            ['nim'=>'2341720122','nama'=>'Wulan Permatasari',      'email'=>'wulan.permatasari@student.polinema.ac.id',  'kelas_id'=>$ti3d?->id,'dosen_pa_id'=>$elok?->id,'angkatan'=>2023,'status'=>'aktif'],
            ['nim'=>'2341720123','nama'=>'Yoga Pratama',           'email'=>'yoga.pratama@student.polinema.ac.id',       'kelas_id'=>$ti3d?->id,'dosen_pa_id'=>$elok?->id,'angkatan'=>2023,'status'=>'aktif'],
            ['nim'=>'2341720124','nama'=>'Zahra Aulia',            'email'=>'zahra.aulia@student.polinema.ac.id',        'kelas_id'=>$ti3d?->id,'dosen_pa_id'=>$elok?->id,'angkatan'=>2023,'status'=>'aktif'],
            ['nim'=>'2341720125','nama'=>'Arya Dwijayanto',        'email'=>'arya.dwijayanto@student.polinema.ac.id',    'kelas_id'=>$ti3d?->id,'dosen_pa_id'=>$elok?->id,'angkatan'=>2023,'status'=>'aktif'],

            // ════════════════════════════════════════════════
            // TI3C — PA: Budi Santoso (26 mahasiswa)
            // ════════════════════════════════════════════════
            ['nim'=>'2341720001','nama'=>'Aldi Firmansyah',        'email'=>'aldi@student.polinema.ac.id',               'kelas_id'=>$ti3c?->id,'dosen_pa_id'=>$budi?->id,'angkatan'=>2023,'status'=>'aktif'],
            ['nim'=>'2341720002','nama'=>'Bella Novita',           'email'=>'bella.novita@student.polinema.ac.id',        'kelas_id'=>$ti3c?->id,'dosen_pa_id'=>$budi?->id,'angkatan'=>2023,'status'=>'aktif'],
            ['nim'=>'2341720003','nama'=>'Candra Putra',           'email'=>'candra.putra@student.polinema.ac.id',        'kelas_id'=>$ti3c?->id,'dosen_pa_id'=>$budi?->id,'angkatan'=>2023,'status'=>'aktif'],
            ['nim'=>'2341720004','nama'=>'Dea Maharani',           'email'=>'dea.maharani@student.polinema.ac.id',        'kelas_id'=>$ti3c?->id,'dosen_pa_id'=>$budi?->id,'angkatan'=>2023,'status'=>'aktif'],
            ['nim'=>'2341720005','nama'=>'Eko Prasetyo',           'email'=>'eko.prasetyo@student.polinema.ac.id',        'kelas_id'=>$ti3c?->id,'dosen_pa_id'=>$budi?->id,'angkatan'=>2023,'status'=>'aktif'],
            ['nim'=>'2341720006','nama'=>'Fajar Nugroho',          'email'=>'fajar.nugroho@student.polinema.ac.id',       'kelas_id'=>$ti3c?->id,'dosen_pa_id'=>$budi?->id,'angkatan'=>2023,'status'=>'aktif'],
            ['nim'=>'2341720007','nama'=>'Galih Satrio',           'email'=>'galih.satrio@student.polinema.ac.id',        'kelas_id'=>$ti3c?->id,'dosen_pa_id'=>$budi?->id,'angkatan'=>2023,'status'=>'aktif'],
            ['nim'=>'2341720008','nama'=>'Hana Permata',           'email'=>'hana.permata@student.polinema.ac.id',        'kelas_id'=>$ti3c?->id,'dosen_pa_id'=>$budi?->id,'angkatan'=>2023,'status'=>'aktif'],
            ['nim'=>'2341720009','nama'=>'Ivan Kurniawan',         'email'=>'ivan.kurniawan@student.polinema.ac.id',      'kelas_id'=>$ti3c?->id,'dosen_pa_id'=>$budi?->id,'angkatan'=>2023,'status'=>'aktif'],
            ['nim'=>'2341720010','nama'=>'Jihan Ramadhani',        'email'=>'jihan.ramadhani@student.polinema.ac.id',     'kelas_id'=>$ti3c?->id,'dosen_pa_id'=>$budi?->id,'angkatan'=>2023,'status'=>'aktif'],
            ['nim'=>'2341720011','nama'=>'Kayla Andriani',         'email'=>'kayla.andriani@student.polinema.ac.id',      'kelas_id'=>$ti3c?->id,'dosen_pa_id'=>$budi?->id,'angkatan'=>2023,'status'=>'aktif'],
            ['nim'=>'2341720012','nama'=>'Luki Setiawan',          'email'=>'luki.setiawan@student.polinema.ac.id',       'kelas_id'=>$ti3c?->id,'dosen_pa_id'=>$budi?->id,'angkatan'=>2023,'status'=>'aktif'],
            ['nim'=>'2341720013','nama'=>'Mira Kusumawati',        'email'=>'mira.kusumawati@student.polinema.ac.id',     'kelas_id'=>$ti3c?->id,'dosen_pa_id'=>$budi?->id,'angkatan'=>2023,'status'=>'aktif'],
            ['nim'=>'2341720014','nama'=>'Novan Hidayat',          'email'=>'novan.hidayat@student.polinema.ac.id',       'kelas_id'=>$ti3c?->id,'dosen_pa_id'=>$budi?->id,'angkatan'=>2023,'status'=>'aktif'],
            ['nim'=>'2341720015','nama'=>'Ovita Sari',             'email'=>'ovita.sari@student.polinema.ac.id',          'kelas_id'=>$ti3c?->id,'dosen_pa_id'=>$budi?->id,'angkatan'=>2023,'status'=>'aktif'],
            ['nim'=>'2341720016','nama'=>'Prasetyo Adi',           'email'=>'prasetyo.adi@student.polinema.ac.id',        'kelas_id'=>$ti3c?->id,'dosen_pa_id'=>$budi?->id,'angkatan'=>2023,'status'=>'aktif'],
            ['nim'=>'2341720017','nama'=>'Qonita Zahra',           'email'=>'qonita.zahra@student.polinema.ac.id',        'kelas_id'=>$ti3c?->id,'dosen_pa_id'=>$budi?->id,'angkatan'=>2023,'status'=>'aktif'],
            ['nim'=>'2341720018','nama'=>'Rendi Saputra',          'email'=>'rendi.saputra@student.polinema.ac.id',       'kelas_id'=>$ti3c?->id,'dosen_pa_id'=>$budi?->id,'angkatan'=>2023,'status'=>'aktif'],
            ['nim'=>'2341720019','nama'=>'Salsabila Putri',        'email'=>'salsabila.putri@student.polinema.ac.id',     'kelas_id'=>$ti3c?->id,'dosen_pa_id'=>$budi?->id,'angkatan'=>2023,'status'=>'aktif'],
            ['nim'=>'2341720020','nama'=>'Tri Wahyono',            'email'=>'tri.wahyono@student.polinema.ac.id',         'kelas_id'=>$ti3c?->id,'dosen_pa_id'=>$budi?->id,'angkatan'=>2023,'status'=>'aktif'],
            ['nim'=>'2341720021','nama'=>'Ulya Fadhilah',          'email'=>'ulya.fadhilah@student.polinema.ac.id',       'kelas_id'=>$ti3c?->id,'dosen_pa_id'=>$budi?->id,'angkatan'=>2023,'status'=>'aktif'],
            ['nim'=>'2341720022','nama'=>'Vega Maharani',          'email'=>'vega.maharani@student.polinema.ac.id',       'kelas_id'=>$ti3c?->id,'dosen_pa_id'=>$budi?->id,'angkatan'=>2023,'status'=>'aktif'],
            ['nim'=>'2341720023','nama'=>'Wahyu Triastuti',        'email'=>'wahyu.triastuti@student.polinema.ac.id',     'kelas_id'=>$ti3c?->id,'dosen_pa_id'=>$budi?->id,'angkatan'=>2023,'status'=>'aktif'],
            ['nim'=>'2341720024','nama'=>'Xandra Febriyanti',      'email'=>'xandra.febriyanti@student.polinema.ac.id',   'kelas_id'=>$ti3c?->id,'dosen_pa_id'=>$budi?->id,'angkatan'=>2023,'status'=>'aktif'],
            ['nim'=>'2341720025','nama'=>'Yoga Eka Putra',         'email'=>'yoga.eka@student.polinema.ac.id',            'kelas_id'=>$ti3c?->id,'dosen_pa_id'=>$budi?->id,'angkatan'=>2023,'status'=>'aktif'],
            ['nim'=>'2341720026','nama'=>'Zulfa Amalia',           'email'=>'zulfa.amalia@student.polinema.ac.id',        'kelas_id'=>$ti3c?->id,'dosen_pa_id'=>$budi?->id,'angkatan'=>2023,'status'=>'aktif'],

            // ════════════════════════════════════════════════
            // TI3A — PA: Zawaruddin (25 mahasiswa)
            // ════════════════════════════════════════════════
            ['nim'=>'2341730001','nama'=>'Hendra Wijaya',          'email'=>'hendra.wijaya@student.polinema.ac.id',       'kelas_id'=>$ti3a?->id,'dosen_pa_id'=>$zawar?->id,'angkatan'=>2023,'status'=>'aktif'],
            ['nim'=>'2341730002','nama'=>'Indah Permata',          'email'=>'indah.permata@student.polinema.ac.id',       'kelas_id'=>$ti3a?->id,'dosen_pa_id'=>$zawar?->id,'angkatan'=>2023,'status'=>'aktif'],
            ['nim'=>'2341730003','nama'=>'Jaka Santoso',           'email'=>'jaka.santoso@student.polinema.ac.id',        'kelas_id'=>$ti3a?->id,'dosen_pa_id'=>$zawar?->id,'angkatan'=>2023,'status'=>'aktif'],
            ['nim'=>'2341730004','nama'=>'Kirana Dewi',            'email'=>'kirana.dewi@student.polinema.ac.id',         'kelas_id'=>$ti3a?->id,'dosen_pa_id'=>$zawar?->id,'angkatan'=>2023,'status'=>'aktif'],
            ['nim'=>'2341730005','nama'=>'Lendra Kusuma',          'email'=>'lendra.kusuma@student.polinema.ac.id',       'kelas_id'=>$ti3a?->id,'dosen_pa_id'=>$zawar?->id,'angkatan'=>2023,'status'=>'aktif'],
            ['nim'=>'2341730006','nama'=>'Mega Puspita',           'email'=>'mega.puspita@student.polinema.ac.id',        'kelas_id'=>$ti3a?->id,'dosen_pa_id'=>$zawar?->id,'angkatan'=>2023,'status'=>'aktif'],
            ['nim'=>'2341730007','nama'=>'Nanda Pratiwi',          'email'=>'nanda.pratiwi@student.polinema.ac.id',       'kelas_id'=>$ti3a?->id,'dosen_pa_id'=>$zawar?->id,'angkatan'=>2023,'status'=>'aktif'],
            ['nim'=>'2341730008','nama'=>'Omar Abdillah',          'email'=>'omar.abdillah@student.polinema.ac.id',       'kelas_id'=>$ti3a?->id,'dosen_pa_id'=>$zawar?->id,'angkatan'=>2023,'status'=>'aktif'],
            ['nim'=>'2341730009','nama'=>'Pandu Wicaksono',        'email'=>'pandu.wicaksono@student.polinema.ac.id',     'kelas_id'=>$ti3a?->id,'dosen_pa_id'=>$zawar?->id,'angkatan'=>2023,'status'=>'aktif'],
            ['nim'=>'2341730010','nama'=>'Qisti Aulia',            'email'=>'qisti.aulia@student.polinema.ac.id',         'kelas_id'=>$ti3a?->id,'dosen_pa_id'=>$zawar?->id,'angkatan'=>2023,'status'=>'aktif'],
            ['nim'=>'2341730011','nama'=>'Rizka Amelia',           'email'=>'rizka.amelia@student.polinema.ac.id',        'kelas_id'=>$ti3a?->id,'dosen_pa_id'=>$zawar?->id,'angkatan'=>2023,'status'=>'aktif'],
            ['nim'=>'2341730012','nama'=>'Surya Darma',            'email'=>'surya.darma@student.polinema.ac.id',         'kelas_id'=>$ti3a?->id,'dosen_pa_id'=>$zawar?->id,'angkatan'=>2023,'status'=>'aktif'],
            ['nim'=>'2341730013','nama'=>'Tasya Ramadhani',        'email'=>'tasya.ramadhani@student.polinema.ac.id',     'kelas_id'=>$ti3a?->id,'dosen_pa_id'=>$zawar?->id,'angkatan'=>2023,'status'=>'aktif'],
            ['nim'=>'2341730014','nama'=>'Umar Hakim',             'email'=>'umar.hakim@student.polinema.ac.id',          'kelas_id'=>$ti3a?->id,'dosen_pa_id'=>$zawar?->id,'angkatan'=>2023,'status'=>'aktif'],
            ['nim'=>'2341730015','nama'=>'Vani Oktavia',           'email'=>'vani.oktavia@student.polinema.ac.id',        'kelas_id'=>$ti3a?->id,'dosen_pa_id'=>$zawar?->id,'angkatan'=>2023,'status'=>'aktif'],
            ['nim'=>'2341730016','nama'=>'Wahyudi Santoso',        'email'=>'wahyudi.santoso@student.polinema.ac.id',     'kelas_id'=>$ti3a?->id,'dosen_pa_id'=>$zawar?->id,'angkatan'=>2023,'status'=>'aktif'],
            ['nim'=>'2341730017','nama'=>'Xiomara Putri',          'email'=>'xiomara.putri@student.polinema.ac.id',       'kelas_id'=>$ti3a?->id,'dosen_pa_id'=>$zawar?->id,'angkatan'=>2023,'status'=>'aktif'],
            ['nim'=>'2341730018','nama'=>'Yudha Pratama',          'email'=>'yudha.pratama@student.polinema.ac.id',       'kelas_id'=>$ti3a?->id,'dosen_pa_id'=>$zawar?->id,'angkatan'=>2023,'status'=>'aktif'],
            ['nim'=>'2341730019','nama'=>'Zara Novita',            'email'=>'zara.novita@student.polinema.ac.id',         'kelas_id'=>$ti3a?->id,'dosen_pa_id'=>$zawar?->id,'angkatan'=>2023,'status'=>'aktif'],
            ['nim'=>'2341730020','nama'=>'Adit Setiawan',          'email'=>'adit.setiawan@student.polinema.ac.id',       'kelas_id'=>$ti3a?->id,'dosen_pa_id'=>$zawar?->id,'angkatan'=>2023,'status'=>'aktif'],
            ['nim'=>'2341730021','nama'=>'Bunga Citra',            'email'=>'bunga.citra@student.polinema.ac.id',         'kelas_id'=>$ti3a?->id,'dosen_pa_id'=>$zawar?->id,'angkatan'=>2023,'status'=>'aktif'],
            ['nim'=>'2341730022','nama'=>'Chandra Eka',            'email'=>'chandra.eka@student.polinema.ac.id',         'kelas_id'=>$ti3a?->id,'dosen_pa_id'=>$zawar?->id,'angkatan'=>2023,'status'=>'aktif'],
            ['nim'=>'2341730023','nama'=>'Diana Pertiwi',          'email'=>'diana.pertiwi@student.polinema.ac.id',       'kelas_id'=>$ti3a?->id,'dosen_pa_id'=>$zawar?->id,'angkatan'=>2023,'status'=>'aktif'],
            ['nim'=>'2341730024','nama'=>'Evan Maulana',           'email'=>'evan.maulana@student.polinema.ac.id',        'kelas_id'=>$ti3a?->id,'dosen_pa_id'=>$zawar?->id,'angkatan'=>2023,'status'=>'aktif'],
            ['nim'=>'2341730025','nama'=>'Fitri Handayani',        'email'=>'fitri.handayani@student.polinema.ac.id',     'kelas_id'=>$ti3a?->id,'dosen_pa_id'=>$zawar?->id,'angkatan'=>2023,'status'=>'aktif'],

            // ════════════════════════════════════════════════
            // TI3B — PA: Ridwan Rismanto (25 mahasiswa)
            // ════════════════════════════════════════════════
            ['nim'=>'2341740001','nama'=>'Kartika Dewi',           'email'=>'kartika.dewi@student.polinema.ac.id',        'kelas_id'=>$ti3b?->id,'dosen_pa_id'=>$ridwan?->id,'angkatan'=>2023,'status'=>'aktif'],
            ['nim'=>'2341740002','nama'=>'Lutfi Hakim',            'email'=>'lutfi.hakim@student.polinema.ac.id',         'kelas_id'=>$ti3b?->id,'dosen_pa_id'=>$ridwan?->id,'angkatan'=>2023,'status'=>'aktif'],
            ['nim'=>'2341740003','nama'=>'Mutiara Sari',           'email'=>'mutiara.sari@student.polinema.ac.id',        'kelas_id'=>$ti3b?->id,'dosen_pa_id'=>$ridwan?->id,'angkatan'=>2023,'status'=>'aktif'],
            ['nim'=>'2341740004','nama'=>'Nanang Wijaya',          'email'=>'nanang.wijaya@student.polinema.ac.id',       'kelas_id'=>$ti3b?->id,'dosen_pa_id'=>$ridwan?->id,'angkatan'=>2023,'status'=>'aktif'],
            ['nim'=>'2341740005','nama'=>'Okta Fitriani',          'email'=>'okta.fitriani@student.polinema.ac.id',       'kelas_id'=>$ti3b?->id,'dosen_pa_id'=>$ridwan?->id,'angkatan'=>2023,'status'=>'aktif'],
            ['nim'=>'2341740006','nama'=>'Panji Nugroho',          'email'=>'panji.nugroho@student.polinema.ac.id',       'kelas_id'=>$ti3b?->id,'dosen_pa_id'=>$ridwan?->id,'angkatan'=>2023,'status'=>'aktif'],
            ['nim'=>'2341740007','nama'=>'Rahma Yunita',           'email'=>'rahma.yunita@student.polinema.ac.id',        'kelas_id'=>$ti3b?->id,'dosen_pa_id'=>$ridwan?->id,'angkatan'=>2023,'status'=>'aktif'],
            ['nim'=>'2341740008','nama'=>'Satria Bagas',           'email'=>'satria.bagas@student.polinema.ac.id',        'kelas_id'=>$ti3b?->id,'dosen_pa_id'=>$ridwan?->id,'angkatan'=>2023,'status'=>'aktif'],
            ['nim'=>'2341740009','nama'=>'Tari Kusuma',            'email'=>'tari.kusuma@student.polinema.ac.id',         'kelas_id'=>$ti3b?->id,'dosen_pa_id'=>$ridwan?->id,'angkatan'=>2023,'status'=>'aktif'],
            ['nim'=>'2341740010','nama'=>'Ucok Situmorang',        'email'=>'ucok.situmorang@student.polinema.ac.id',     'kelas_id'=>$ti3b?->id,'dosen_pa_id'=>$ridwan?->id,'angkatan'=>2023,'status'=>'aktif'],
            ['nim'=>'2341740011','nama'=>'Vivi Handayani',         'email'=>'vivi.handayani@student.polinema.ac.id',      'kelas_id'=>$ti3b?->id,'dosen_pa_id'=>$ridwan?->id,'angkatan'=>2023,'status'=>'aktif'],
            ['nim'=>'2341740012','nama'=>'Wahyu Saputra',          'email'=>'wahyu.saputra@student.polinema.ac.id',       'kelas_id'=>$ti3b?->id,'dosen_pa_id'=>$ridwan?->id,'angkatan'=>2023,'status'=>'aktif'],
            ['nim'=>'2341740013','nama'=>'Yulia Ningrum',          'email'=>'yulia.ningrum@student.polinema.ac.id',       'kelas_id'=>$ti3b?->id,'dosen_pa_id'=>$ridwan?->id,'angkatan'=>2023,'status'=>'aktif'],
            ['nim'=>'2341740014','nama'=>'Zulkifli Ahmad',         'email'=>'zulkifli.ahmad@student.polinema.ac.id',      'kelas_id'=>$ti3b?->id,'dosen_pa_id'=>$ridwan?->id,'angkatan'=>2023,'status'=>'aktif'],
            ['nim'=>'2341740015','nama'=>'Andika Pratama',         'email'=>'andika.pratama@student.polinema.ac.id',      'kelas_id'=>$ti3b?->id,'dosen_pa_id'=>$ridwan?->id,'angkatan'=>2023,'status'=>'aktif'],
            ['nim'=>'2341740016','nama'=>'Bintang Ramadhan',       'email'=>'bintang.ramadhan@student.polinema.ac.id',    'kelas_id'=>$ti3b?->id,'dosen_pa_id'=>$ridwan?->id,'angkatan'=>2023,'status'=>'aktif'],
            ['nim'=>'2341740017','nama'=>'Cindy Paramita',         'email'=>'cindy.paramita@student.polinema.ac.id',      'kelas_id'=>$ti3b?->id,'dosen_pa_id'=>$ridwan?->id,'angkatan'=>2023,'status'=>'aktif'],
            ['nim'=>'2341740018','nama'=>'Danang Setiawan',        'email'=>'danang.setiawan@student.polinema.ac.id',     'kelas_id'=>$ti3b?->id,'dosen_pa_id'=>$ridwan?->id,'angkatan'=>2023,'status'=>'aktif'],
            ['nim'=>'2341740019','nama'=>'Elsa Mardiana',          'email'=>'elsa.mardiana@student.polinema.ac.id',       'kelas_id'=>$ti3b?->id,'dosen_pa_id'=>$ridwan?->id,'angkatan'=>2023,'status'=>'aktif'],
            ['nim'=>'2341740020','nama'=>'Fachri Hidayat',         'email'=>'fachri.hidayat@student.polinema.ac.id',      'kelas_id'=>$ti3b?->id,'dosen_pa_id'=>$ridwan?->id,'angkatan'=>2023,'status'=>'aktif'],
            ['nim'=>'2341740021','nama'=>'Gita Purnama',           'email'=>'gita.purnama@student.polinema.ac.id',        'kelas_id'=>$ti3b?->id,'dosen_pa_id'=>$ridwan?->id,'angkatan'=>2023,'status'=>'aktif'],
            ['nim'=>'2341740022','nama'=>'Hafidz Aryanto',         'email'=>'hafidz.aryanto@student.polinema.ac.id',      'kelas_id'=>$ti3b?->id,'dosen_pa_id'=>$ridwan?->id,'angkatan'=>2023,'status'=>'aktif'],
            ['nim'=>'2341740023','nama'=>'Isna Rahmawati',         'email'=>'isna.rahmawati@student.polinema.ac.id',      'kelas_id'=>$ti3b?->id,'dosen_pa_id'=>$ridwan?->id,'angkatan'=>2023,'status'=>'aktif'],
            ['nim'=>'2341740024','nama'=>'Johan Prasetya',         'email'=>'johan.prasetya@student.polinema.ac.id',      'kelas_id'=>$ti3b?->id,'dosen_pa_id'=>$ridwan?->id,'angkatan'=>2023,'status'=>'aktif'],
            ['nim'=>'2341740025','nama'=>'Khoirul Anwar',          'email'=>'khoirul.anwar@student.polinema.ac.id',       'kelas_id'=>$ti3b?->id,'dosen_pa_id'=>$ridwan?->id,'angkatan'=>2023,'status'=>'aktif'],
        ];

        foreach ($mahasiswas as $m) {
            $user = User::updateOrCreate(
                ['email' => $m['email']],
                [
                    'name'     => $m['nama'],
                    'password' => Hash::make('password'),
                ]
            );
            $user->syncRoles([$role]);

            Mahasiswa::updateOrCreate(
                ['nim' => $m['nim']],
                [
                    'user_id'     => $user->id,
                    'nim'         => $m['nim'],
                    'nama'        => $m['nama'],
                    'kelas_id'    => $m['kelas_id'],
                    'dosen_pa_id' => $m['dosen_pa_id'],
                    'angkatan'    => $m['angkatan'],
                    'status'      => $m['status'],
                ]
            );
        }

        $this->command->info('✅ ' . count($mahasiswas) . ' mahasiswa berhasil di-seed.');
        $this->command->info('   TI3D (Elok)     : 27 mahasiswa');
        $this->command->info('   TI3C (Budi)     : 26 mahasiswa');
        $this->command->info('   TI3A (Zawarudin): 25 mahasiswa');
        $this->command->info('   TI3B (Ridwan)   : 25 mahasiswa');
    }
}