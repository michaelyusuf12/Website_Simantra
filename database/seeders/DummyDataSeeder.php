<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Mitra;
use App\Models\Kegiatan;

class DummyDataSeeder extends Seeder
{
    public function run()
    {
        $passwordDefault = Hash::make('password123'); // Password default untuk semua akun

    // ==========================================
    // 1. DATA PEGAWAI & ADMIN
    // ==========================================
        $daftarPegawai = [
            'Abdurrahman S.Tr.Stat.', 'Amrisany Sektora Daud, A.Md.Stat.', 'Andi Muh. Irfan', 'Ardan, A.Md', 
            'Ashadi, SE', 'Asri Samsu Alam, SE', 'Atika Putri Purwaningrum SST', 'Darmin Laipo, S. Stat.', 
            'Dyah Tari Nur\'aini, S.ST, M.M.', 'Fikron Tanazzul Ahsani S.Tr.Stat.', 'Fitri Permata Sari, SST', 
            'Hasnawati P., S.M.', 'Idhar Rahim, SE', 'Ina Apriani, S.Stat.', 'Ishak', 
            'Kurniawan Arief Prasetyo SST', 'Lilis Dwiyanti, S.Tr.Stat.', 'Marniati, S.E.', 'Muh. Sadar', 
            'Muh. Shamad, SST', 'Muhamad Hasdi, SE', 'Rohmah Dini Ayunda Mustofa S.Tr.Stat.', 
            'Sanur Saprah, SE', 'Sapari', 'Yunita Nur Khasanah, SST', 'Zulfikar Halim Lumintang, S.ST., M.M.'
        ];

        $daftarFungsi = ['Produksi', 'Sosial', 'Distribusi', 'Neraca', 'IPDS', 'Umum'];

foreach ($daftarPegawai as $index => $nama) {
            $username = strtolower(preg_replace('/[^a-zA-Z]/', '', explode(' ', $nama)[0])) . ($index + 1);
            $role = str_contains($nama, 'Muh. Shamad') ? 'admin' : 'pegawai';
            $nip = '19' . rand(70, 99) . rand(10, 12) . rand(10, 28) . '20' . rand(10, 24) . '100' . rand(1, 9);

            // [REVISI] Gunakan updateOrCreate
            User::updateOrCreate(
                ['username' => $username], // Cari berdasarkan username ini
                [
                    'nama' => $nama,
                    'password' => $passwordDefault,
                    'nip' => $nip,
                    'role' => $role,
                    'fungsi' => $daftarFungsi[array_rand($daftarFungsi)], 
                ]
            );
        }

    // ==========================================
    // 2. DATA MITRA (Diinput ke tabel Users & Mitras)
    // ==========================================
        $dataMitra = [
            ['nama' => 'Sugeng Riadi', 'hp' => '082236887239', 'sobat_id' => '740422020027', 'email' => 'Sugengriadi050392@gmail.com', 'posisi' => '3', 'alamat' => 'Lingkungan 2 lalodipu'],
            ['nama' => 'Andi Astarina', 'hp' => '082293633741', 'sobat_id' => '740422100059', 'email' => 'rinaapriliahasan04@gmail.com', 'posisi' => '1', 'alamat' => 'Dusun 1 Puudongi'],
            ['nama' => 'HARIYANTO', 'hp' => '082322319423', 'sobat_id' => '740422100058', 'email' => 'hariyanto110690@gmail.com', 'posisi' => '1', 'alamat' => 'Dusun II Ruwitari'],
            ['nama' => 'Sulaeni Arifka', 'hp' => '082257343215', 'sobat_id' => '740422090007', 'email' => 'sulaeniarifka23032001@gmail.com', 'posisi' => '3', 'alamat' => 'Dusun I Liku'],
            ['nama' => 'Yusril Mahendra', 'hp' => '085294493375', 'sobat_id' => '740422100132', 'email' => 'mahendrayusril473@gmail.com', 'posisi' => '1', 'alamat' => 'Lingkungan 3 beringin'],
            ['nama' => 'ALI IMRAN', 'hp' => '082234731375', 'sobat_id' => '740422090064', 'email' => 'Aliimransallo@gmail.com', 'posisi' => '3', 'alamat' => 'Lawulo'],
            ['nama' => 'MUH WARIS', 'hp' => '082194244494', 'sobat_id' => '740422030010', 'email' => 'ariyesaskar@gmail.com', 'posisi' => '1', 'alamat' => 'Dusun 2 Ambuao'],
            ['nama' => 'Herniati', 'hp' => '081346778704', 'sobat_id' => '740422090143', 'email' => 'herniatiipon71@gmail.com', 'posisi' => '1', 'alamat' => 'jl.pemuda Rt 02 Rw 01'],
            ['nama' => 'Susnidarti', 'hp' => '082296009448', 'sobat_id' => '740423090001', 'email' => 'susnidarti@gmail.com', 'posisi' => '3', 'alamat' => 'Lingk V Laloinggopi'],
            ['nama' => 'Rahman', 'hp' => '081244311409', 'sobat_id' => '740422100189', 'email' => 'rahmanijhonk0@gmail.com', 'posisi' => '1', 'alamat' => 'Dusun 2 Cappa Ujung'],
            ['nama' => 'NOOR KHARINA APRILLIA', 'hp' => '085823595968', 'sobat_id' => '740423050001', 'email' => 'Kharinaliya1@gmail.com', 'posisi' => '1', 'alamat' => 'Jl.poros kolaka-pomalaa'],
            ['nama' => 'Muthmainna Alfausyah', 'hp' => '082292913210', 'sobat_id' => '740423080018', 'email' => 'malfausyah@gmail.com', 'posisi' => '1', 'alamat' => 'Kel.Ngapa, RT 01 RW 01'],
            ['nama' => 'RIRIN RISKYA', 'hp' => '085299430600', 'sobat_id' => '740422090091', 'email' => 'ririnriskya94@gmail.com', 'posisi' => '3', 'alamat' => 'Jalan poros kolaka'],
            ['nama' => 'Sri andriani. S', 'hp' => '082290426766', 'sobat_id' => '740423080021', 'email' => 'andrianisri472@gmail.com', 'posisi' => '1', 'alamat' => 'Jl. Badewi 1 BTN villa'],
            ['nama' => 'Ilham Giffari', 'hp' => '082193011922', 'sobat_id' => '740422020010', 'email' => 'ilhamgiffari02@gmail.com', 'posisi' => '1', 'alamat' => 'Jalan puluase dusun I'],
            ['nama' => 'Ni Ketut Meriandani', 'hp' => '085343977095', 'sobat_id' => '740422090031', 'email' => 'niketutmeriandani@gmail.com', 'posisi' => '1', 'alamat' => 'Dusun II Darma Jaya'],
            ['nama' => 'Misbahuddin', 'hp' => '085394119535', 'sobat_id' => '740422100140', 'email' => 'Mhisbafhitry95@gmail.com', 'posisi' => '1', 'alamat' => 'Jl.poros wolulu polinggo'],
            ['nama' => 'IRDAWATI.AR', 'hp' => '082393670393', 'sobat_id' => '740422030020', 'email' => 'watiirda469@gmail.com', 'posisi' => '1', 'alamat' => 'JALAN KOLAKA BOMBAN'],
        
            ['nama' => 'MUH. HAMKA', 'hp' => '081524754268', 'sobat_id' => '740423040012', 'email' => 'hamka9983@gmail.com', 'posisi' => '2', 'alamat' => 'Jl. Poros Kolaka-Pomalaa KM. 16-17'],
            ['nama' => 'NAFILAH SHAF', 'hp' => '085399884725', 'sobat_id' => '731322040001', 'email' => 'nafilahshaf67@gmail.com', 'posisi' => '2', 'alamat' => 'Jln. Pondui, BTN Pondui Blok C9'],
            ['nama' => 'Siti Hajar', 'hp' => '082345894817', 'sobat_id' => '740423110058', 'email' => 'bzhiity@yahoo.com', 'posisi' => '2', 'alamat' => 'Perumnas Lalombaa no 198'],
            ['nama' => 'Cece Kirani', 'hp' => '082291399441', 'sobat_id' => '740423060006', 'email' => 'cecekiraniraffy@gmail.com', 'posisi' => '2', 'alamat' => 'Dusun II Wulende'],
            ['nama' => 'Fadliya Cahyani', 'hp' => '082195111487', 'sobat_id' => '740423110066', 'email' => 'fadliyaright0@gmail.com', 'posisi' => '2', 'alamat' => 'Dusun I Polewali'],
            ['nama' => 'Musliha Nurul Ilma', 'hp' => '085333375634', 'sobat_id' => '740423110069', 'email' => 'muslihanurul@gmail.com', 'posisi' => '2', 'alamat' => 'Jalan Kemakmuran'],
            ['nama' => 'Muh.Arief Juliamsah', 'hp' => '085825515112', 'sobat_id' => '740422090113', 'email' => 'muhariefjuliamsah@gmail.com', 'posisi' => '2', 'alamat' => 'Jl.poros pomalaa-watubangga'],
        ];

    foreach ($dataMitra as $mitra) {
            // [REVISI] Gunakan updateOrCreate
            $user = User::updateOrCreate(
                ['username' => $mitra['email']], // Cari berdasarkan email
                [
                    'nama' => $mitra['nama'],
                    'password' => $passwordDefault,
                    'role' => 'mitra',
                ]
            );

            Mitra::updateOrCreate(
                ['sobat_id' => $mitra['sobat_id']], // Cari berdasarkan sobat_id
                [
                    'id_user' => $user->id_user ?? $user->id,
                    'nama_petugas' => $mitra['nama'],
                    'email' => $mitra['email'],
                    'telepon' => $mitra['hp'],
                    'alamat' => $mitra['alamat'],
                    'kode_prov' => '74',
                    'kode_kab' => '04',
                    'posisi_petugas' => $mitra['posisi'], 
                ]
            );
        }
    // ==========================================
    // 3. DATA KEGIATAN BPS (15 Kegiatan)
    // ==========================================
        $kegiatans = [
            ['kode' => 'K-001', 'nama' => 'Survei Angkatan Kerja Nasional (Sakernas)', 'pj' => 'Dyah Tari Nur\'aini', 'fungsi' => 'Sosial', 'pml' => 35000, 'pcl' => 25000, 'pengolahan' => 15000],
            ['kode' => 'K-002', 'nama' => 'Survei Sosial Ekonomi Nasional (Susenas)', 'pj' => 'Zulfikar Halim', 'fungsi' => 'Sosial', 'pml' => 45000, 'pcl' => 30000, 'pengolahan' => 18000],
            ['kode' => 'K-003', 'nama' => 'Sensus Pertanian 2023 (Lanjutan)', 'pj' => 'Abdurrahman', 'fungsi' => 'Produksi', 'pml' => 50000, 'pcl' => 35000, 'pengolahan' => 20000],
            ['kode' => 'K-004', 'nama' => 'Survei Industri Mikro dan Kecil (IMK)', 'pj' => 'Amrisany Sektora', 'fungsi' => 'Produksi', 'pml' => 30000, 'pcl' => 20000, 'pengolahan' => 12000],
            ['kode' => 'K-005', 'nama' => 'Survei Perusahaan Hortikultura (SPH)', 'pj' => 'Fikron Tanazzul', 'fungsi' => 'Produksi', 'pml' => 25000, 'pcl' => 18000, 'pengolahan' => 10000],
            ['kode' => 'K-006', 'nama' => 'Survei Harga Konsumen (SHK)', 'pj' => 'Idhar Rahim', 'fungsi' => 'Distribusi', 'pml' => 20000, 'pcl' => 15000, 'pengolahan' => 8000],
            ['kode' => 'K-007', 'nama' => 'Survei Pola Distribusi Perdagangan', 'pj' => 'Marniati', 'fungsi' => 'Distribusi', 'pml' => 40000, 'pcl' => 28000, 'pengolahan' => 15000],
            ['kode' => 'K-008', 'nama' => 'Survei Harga Perdagangan Besar (SHPB)', 'pj' => 'Andi Muh. Irfan', 'fungsi' => 'Distribusi', 'pml' => 30000, 'pcl' => 20000, 'pengolahan' => 12000],
            ['kode' => 'K-009', 'nama' => 'Survei Indeks Kemahalan Konstruksi (IKK)', 'pj' => 'Fitri Permata Sari', 'fungsi' => 'Neraca', 'pml' => 45000, 'pcl' => 32000, 'pengolahan' => 20000],
            ['kode' => 'K-010', 'nama' => 'Survei Khusus Lembaga Non Profit', 'pj' => 'Ina Apriani', 'fungsi' => 'Neraca', 'pml' => 35000, 'pcl' => 25000, 'pengolahan' => 15000],
            ['kode' => 'K-011', 'nama' => 'Survei Ubinan Padi dan Palawija', 'pj' => 'Lilis Dwiyanti', 'fungsi' => 'Produksi', 'pml' => 40000, 'pcl' => 28000, 'pengolahan' => 0], // Tidak ada pengolahan
            ['kode' => 'K-012', 'nama' => 'Pemutakhiran Kerangka Geospasial', 'pj' => 'Kurniawan Arief', 'fungsi' => 'IPDS', 'pml' => 50000, 'pcl' => 35000, 'pengolahan' => 25000],
            ['kode' => 'K-013', 'nama' => 'Penyusunan Direktori Perusahaan', 'pj' => 'Rohmah Dini', 'fungsi' => 'IPDS', 'pml' => 25000, 'pcl' => 15000, 'pengolahan' => 10000],
            ['kode' => 'K-014', 'nama' => 'Survei Wisatawan Nusantara (Wisnus)', 'pj' => 'Sanur Saprah', 'fungsi' => 'Sosial', 'pml' => 30000, 'pcl' => 20000, 'pengolahan' => 12000],
            ['kode' => 'K-015', 'nama' => 'Survei Statistik Keuangan Pemda', 'pj' => 'Muhamad Hasdi', 'fungsi' => 'Neraca', 'pml' => 0, 'pcl' => 0, 'pengolahan' => 30000], // Hanya pengolahan
        ];

foreach ($kegiatans as $keg) {
            // [REVISI] Gunakan updateOrCreate
            Kegiatan::updateOrCreate(
                ['kode_kegiatan' => $keg['kode']], // Cari berdasarkan kode kegiatan
                [
                    'nama_kegiatan' => $keg['nama'],
                    'penanggung_jawab' => $keg['pj'],
                    'nama_tim' => 'Tim ' . $keg['fungsi'],
                    'target_dokumen' => rand(50, 200),
                    'fungsi' => $keg['fungsi'],
                    'jenis_kegiatan' => 'Sensus/Survei',
                    'tgl_mulai' => date('Y-05-01'),
                    'tgl_selesai' => date('Y-05-31'),
                    'honor_pml_per_dokumen' => $keg['pml'],
                    'honor_pcl_per_dokumen' => $keg['pcl'],
                    'honor_pengolahan_per_dokumen' => $keg['pengolahan'],
                ]);
        }
    }
}