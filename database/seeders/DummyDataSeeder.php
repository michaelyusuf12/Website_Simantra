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
        // Password default untuk semua akun
        $passwordDefault = Hash::make('password123'); 

        // ==========================================
        // 1. DATA PEGAWAI & ADMIN (26 Orang)
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

            User::updateOrCreate(
                ['username' => $username], 
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
        // 2. DATA MITRA (Total 46 Orang)
        // ==========================================
        $dataMitra = [
            // Kelompok Mitra Awal & Tambahan Sebelumnya
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
            ['nama' => 'Fara Azzahra Dilla', 'hp' => '082261332363', 'sobat_id' => '740422030016', 'email' => 'faraazzahradilla@gmail.com', 'posisi' => '3', 'alamat' => 'Jl. Mekonnga Indah Lingk. III Muara'],
            ['nama' => 'TRI OKTAFIANI', 'hp' => '082239842228', 'sobat_id' => '740422030033', 'email' => 'trioktafiani188@gmail.com', 'posisi' => '1', 'alamat' => 'Jl. Sangia Nibandera'],
            ['nama' => 'ANDI AKBAR, S.AP', 'hp' => '085242322189', 'sobat_id' => '740422020036', 'email' => 'andiakbararjun284@gmail.com', 'posisi' => '3', 'alamat' => 'Jalan Dusun IV Polewali Desa Ulaweng'],
            ['nama' => 'Asriyani', 'hp' => '081341759160', 'sobat_id' => '740423010004', 'email' => 'asrigiano@gmail.com', 'posisi' => '3', 'alamat' => 'Jln.poros kendari-konawe'],
            ['nama' => 'Windi Widian Sari', 'hp' => '082293270507', 'sobat_id' => '740423040008', 'email' => 'Windiwidiansari3@gmail.com', 'posisi' => '1', 'alamat' => 'LINGK. V KAMPUNG BARU RT 002 RW 003'],
            ['nama' => 'Ahmad Hartono Sawendy', 'hp' => '081299533394', 'sobat_id' => '740423050020', 'email' => 'ahmadhartonoapril03@gmail.com', 'posisi' => '1', 'alamat' => 'Jalan lapaga induha'],
            ['nama' => 'Syachdin ganis bintang shalad', 'hp' => '082193325131', 'sobat_id' => '740423110038', 'email' => 'bintanggaminh@gmail.com', 'posisi' => '1', 'alamat' => 'Jalan masjid Nurul Yaqin'],
            ['nama' => 'Jusnianti', 'hp' => '082293325261', 'sobat_id' => '740423110091', 'email' => 'jusnianti2306@gmail.com', 'posisi' => '3', 'alamat' => 'RT 01 RW 02 ngapa'],
            ['nama' => 'Magfirah Maulani', 'hp' => '082346491359', 'sobat_id' => '740422030006', 'email' => 'Viramagfirah777@gmail.com', 'posisi' => '3', 'alamat' => 'Jln swedi, no. 13 Desa Tambea'],
            ['nama' => 'Andiminasa', 'hp' => '082221899576', 'sobat_id' => '740422030031', 'email' => 'andiminasa6@gmail.com', 'posisi' => '1', 'alamat' => 'Jl. A yani'],
            ['nama' => 'Nur Alfiana', 'hp' => '082296051928', 'sobat_id' => '740422100036', 'email' => 'nur27alfiana@gmail.com', 'posisi' => '1', 'alamat' => 'Jl trans Sulawesi'],
            ['nama' => 'Ega Asri Nur', 'hp' => '082260512837', 'sobat_id' => '740422100002', 'email' => 'egaasri99@icloud.com', 'posisi' => '3', 'alamat' => 'HKSN MANGOLO'],
            ['nama' => 'Bella Nabila', 'hp' => '082298870976', 'sobat_id' => '740423050046', 'email' => 'bellasyam167@gmail.com', 'posisi' => '1', 'alamat' => 'Desa kukutip sp 5 dusun 4'],

            // --- 15 DATA MITRA TAMBAHAN BARU ---
            ['nama' => 'Andi Yusuf', 'hp' => '081234567890', 'sobat_id' => '740422050088', 'email' => 'andiyusuf@gmail.com', 'posisi' => '1', 'alamat' => 'Jln. Pemuda No. 45, Kolaka'],
            ['nama' => 'Siti Aminah', 'hp' => '082198765432', 'sobat_id' => '740423060099', 'email' => 'sitiaminah99@gmail.com', 'posisi' => '2', 'alamat' => 'Kel. Watuliandu, RT 03 RW 02'],
            ['nama' => 'Budi Santoso', 'hp' => '085344556677', 'sobat_id' => '740422110211', 'email' => 'budisantoso88@gmail.com', 'posisi' => '3', 'alamat' => 'Dusun IV Kecamatan Baula'],
            ['nama' => 'Dewi Lestari', 'hp' => '082211223344', 'sobat_id' => '740423040122', 'email' => 'dewilestari.bps@gmail.com', 'posisi' => '1', 'alamat' => 'Jln. Abadi, Lalodipu'],
            ['nama' => 'Eko Prasetyo', 'hp' => '081355667788', 'sobat_id' => '740422090233', 'email' => 'ekopras96@gmail.com', 'posisi' => '2', 'alamat' => 'Jln. Pahlawan KM 3, Kolaka'],
            ['nama' => 'Rina Wijaya', 'hp' => '085288990011', 'sobat_id' => '740423110344', 'email' => 'rinawijaya.mitra@gmail.com', 'posisi' => '3', 'alamat' => 'Perumnas Lalombaa Blok B No. 12'],
            ['nama' => 'Fajar Ramadhan', 'hp' => '082234455661', 'sobat_id' => '740422100455', 'email' => 'fajarramadhan@gmail.com', 'posisi' => '1', 'alamat' => 'Kel. Balandete, Lingkungan IV'],
            ['nama' => 'Indah Permatasari', 'hp' => '081299887766', 'sobat_id' => '740423080566', 'email' => 'indahps98@gmail.com', 'posisi' => '2', 'alamat' => 'Jln. Dr. Sutomo No. 88'],
            ['nama' => 'Hendra Wijaya', 'hp' => '085311223344', 'sobat_id' => '740422030677', 'email' => 'hendrawijaya77@gmail.com', 'posisi' => '3', 'alamat' => 'Dusun I Wundulako'],
            ['nama' => 'Mega Utami', 'hp' => '082266778899', 'sobat_id' => '740423050788', 'email' => 'megautami.mitra@gmail.com', 'posisi' => '1', 'alamat' => 'Jln. Poros Pomalaa, KM 10'],
            ['nama' => 'Rizky Fauzi', 'hp' => '081344332211', 'sobat_id' => '740422020899', 'email' => 'rizkyfauzi@gmail.com', 'posisi' => '2', 'alamat' => 'Lingkungan II Sabilambo'],
            ['nama' => 'Putri Rahayu', 'hp' => '085277665544', 'sobat_id' => '740423010911', 'email' => 'putrirahayu86@gmail.com', 'posisi' => '3', 'alamat' => 'Jln. Pramuka No. 12, Kolaka'],
            ['nama' => 'Agus Setiawan', 'hp' => '082188776655', 'sobat_id' => '740422100922', 'email' => 'agussetiawan@gmail.com', 'posisi' => '1', 'alamat' => 'Kel. Toba, Kolaka Kabupaten'],
            ['nama' => 'Anita Sari', 'hp' => '081255443322', 'sobat_id' => '740423110933', 'email' => 'anitasari93@gmail.com', 'posisi' => '2', 'alamat' => 'Dusun III Tosiba, Kolaka'],
            ['nama' => 'Dian Kusuma', 'hp' => '085366778811', 'sobat_id' => '740422090944', 'email' => 'diankusuma.bps@gmail.com', 'posisi' => '3', 'alamat' => 'Jln. Khairil Anwar Blok C'],
        ];

        foreach ($dataMitra as $mitra) {
            $user = User::updateOrCreate(
                ['username' => $mitra['email']], 
                [
                    'nama' => $mitra['nama'],
                    'password' => $passwordDefault,
                    'role' => 'mitra',
                ]
            );

            Mitra::updateOrCreate(
                ['sobat_id' => $mitra['sobat_id']], 
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
        // 3. DATA KEGIATAN BPS (Total 57 Kegiatan)
        // ==========================================
        $kegiatans = [
            // --- Kelompok 1: Data Awal (K-001 s.d K-015) ---
            ['kode' => 'K-001', 'nama' => 'Survei Angkatan Kerja Nasional (Sakernas)', 'pj' => 'Dyah Tari Nur\'aini, S.ST, M.M.', 'fungsi' => 'Sosial', 'pml' => 35000, 'pcl' => 25000, 'pengolahan' => 15000],
            ['kode' => 'K-002', 'nama' => 'Survei Sosial Ekonomi Nasional (Susenas)', 'pj' => 'Zulfikar Halim Lumintang, S.ST., M.M.', 'fungsi' => 'Sosial', 'pml' => 45000, 'pcl' => 30000, 'pengolahan' => 18000],
            ['kode' => 'K-003', 'nama' => 'Sensus Pertanian 2023 (Lanjutan)', 'pj' => 'Abdurrahman S.Tr.Stat.', 'fungsi' => 'Produksi', 'pml' => 50000, 'pcl' => 35000, 'pengolahan' => 20000],
            ['kode' => 'K-004', 'nama' => 'Survei Industri Mikro dan Kecil (IMK)', 'pj' => 'Amrisany Sektora Daud, A.Md.Stat.', 'fungsi' => 'Produksi', 'pml' => 30000, 'pcl' => 20000, 'pengolahan' => 12000],
            ['kode' => 'K-005', 'nama' => 'Survei Perusahaan Hortikultura (SPH)', 'pj' => 'Fikron Tanazzul Ahsani S.Tr.Stat.', 'fungsi' => 'Produksi', 'pml' => 25000, 'pcl' => 18000, 'pengolahan' => 10000],
            ['kode' => 'K-006', 'nama' => 'Survei Harga Konsumen (SHK)', 'pj' => 'Idhar Rahim, SE', 'fungsi' => 'Distribusi', 'pml' => 20000, 'pcl' => 15000, 'pengolahan' => 8000],
            ['kode' => 'K-007', 'nama' => 'Survei Pola Distribusi Perdagangan', 'pj' => 'Marniati, S.E.', 'fungsi' => 'Distribusi', 'pml' => 40000, 'pcl' => 28000, 'pengolahan' => 15000],
            ['kode' => 'K-008', 'nama' => 'Survei Harga Perdagangan Besar (SHPB)', 'pj' => 'Andi Muh. Irfan', 'fungsi' => 'Distribusi', 'pml' => 30000, 'pcl' => 20000, 'pengolahan' => 12000],
            ['kode' => 'K-009', 'nama' => 'Survei Indeks Kemahalan Konstruksi (IKK)', 'pj' => 'Fitri Permata Sari, SST', 'fungsi' => 'Neraca', 'pml' => 45000, 'pcl' => 32000, 'pengolahan' => 20000],
            ['kode' => 'K-010', 'nama' => 'Survei Khusus Lembaga Non Profit', 'pj' => 'Ina Apriani, S.Stat.', 'fungsi' => 'Neraca', 'pml' => 35000, 'pcl' => 25000, 'pengolahan' => 15000],
            ['kode' => 'K-011', 'nama' => 'Survei Ubinan Padi dan Palawija', 'pj' => 'Lilis Dwiyanti, S.Tr.Stat.', 'fungsi' => 'Produksi', 'pml' => 40000, 'pcl' => 28000, 'pengolahan' => 0],
            ['kode' => 'K-012', 'nama' => 'Pemutakhiran Kerangka Geospasial', 'pj' => 'Kurniawan Arief Prasetyo SST', 'fungsi' => 'IPDS', 'pml' => 50000, 'pcl' => 35000, 'pengolahan' => 25000],
            ['kode' => 'K-013', 'nama' => 'Penyusunan Direktori Perusahaan', 'pj' => 'Rohmah Dini Ayunda Mustofa S.Tr.Stat.', 'fungsi' => 'IPDS', 'pml' => 25000, 'pcl' => 15000, 'pengolahan' => 10000],
            ['kode' => 'K-014', 'nama' => 'Survei Wisatawan Nusantara (Wisnus)', 'pj' => 'Sanur Saprah, SE', 'fungsi' => 'Sosial', 'pml' => 30000, 'pcl' => 20000, 'pengolahan' => 12000],
            ['kode' => 'K-015', 'nama' => 'Survei Statistik Keuangan Pemda', 'pj' => 'Muhamad Hasdi, SE', 'fungsi' => 'Neraca', 'pml' => 0, 'pcl' => 0, 'pengolahan' => 30000],

            // --- Kelompok 2: Survei Produksi & Peternakan (K-016 s.d K-033) ---
            ['kode' => 'K-016', 'nama' => 'Survei Komoditas Strategis Perkebunan', 'pj' => 'Abdurrahman S.Tr.Stat.', 'fungsi' => 'Produksi', 'pml' => 45000, 'pcl' => 30000, 'pengolahan' => 15000],
            ['kode' => 'K-017', 'nama' => 'Updating Direktori Perusahaan Pertanian (DPP) & DUTL', 'pj' => 'Amrisany Sektora Daud, A.Md.Stat.', 'fungsi' => 'Produksi', 'pml' => 35000, 'pcl' => 25000, 'pengolahan' => 12000],
            ['kode' => 'K-018', 'nama' => 'Survei Perusahaan Perkebunan Bulanan', 'pj' => 'Zulfikar Halim Lumintang, S.ST., M.M.', 'fungsi' => 'Produksi', 'pml' => 20000, 'pcl' => 15000, 'pengolahan' => 8000],
            ['kode' => 'K-019', 'nama' => 'Survei Perusahaan Perkebunan Tahunan', 'pj' => 'Ashadi, SE', 'fungsi' => 'Produksi', 'pml' => 50000, 'pcl' => 35000, 'pengolahan' => 18000],
            ['kode' => 'K-020', 'nama' => 'Laporan Tahunan Perusahaan Peternakan', 'pj' => 'Asri Samsu Alam, SE', 'fungsi' => 'Produksi', 'pml' => 40000, 'pcl' => 25000, 'pengolahan' => 10000],
            ['kode' => 'K-021', 'nama' => 'Laporan Pemotongan Ternak Bulanan', 'pj' => 'Atika Putri Purwaningrum SST', 'fungsi' => 'Produksi', 'pml' => 15000, 'pcl' => 10000, 'pengolahan' => 5000],
            ['kode' => 'K-022', 'nama' => 'Laporan Tahunan Perusahaan Penangkapan Ikan', 'pj' => 'Darmin Laipo, S. Stat.', 'fungsi' => 'Produksi', 'pml' => 45000, 'pcl' => 30000, 'pengolahan' => 15000],
            ['kode' => 'K-023', 'nama' => 'Laporan Triwulanan Pendaratan Ikan & TPI', 'pj' => 'Dyah Tari Nur\'aini, S.ST, M.M.', 'fungsi' => 'Produksi', 'pml' => 25000, 'pcl' => 18000, 'pengolahan' => 10000],
            ['kode' => 'K-024', 'nama' => 'Laporan Tahunan Perusahaan Budidaya Ikan', 'pj' => 'Fikron Tanazzul Ahsani S.Tr.Stat.', 'fungsi' => 'Produksi', 'pml' => 35000, 'pcl' => 20000, 'pengolahan' => 12000],
            ['kode' => 'K-025', 'nama' => 'Survei Perusahaan Kehutanan', 'pj' => 'Fitri Permata Sari, SST', 'fungsi' => 'Produksi', 'pml' => 55000, 'pcl' => 40000, 'pengolahan' => 20000],
            ['kode' => 'K-026', 'nama' => 'Survei Industri Besar dan Sedang (IBS) Bulanan', 'pj' => 'Hasnawati P., S.M.', 'fungsi' => 'Produksi', 'pml' => 25000, 'pcl' => 20000, 'pengolahan' => 10000],
            ['kode' => 'K-027', 'nama' => 'Survei Komoditas Perusahaan Industri Manufaktur', 'pj' => 'Idhar Rahim, SE', 'fungsi' => 'Produksi', 'pml' => 30000, 'pcl' => 22000, 'pengolahan' => 15000],
            ['kode' => 'K-028', 'nama' => 'Pemutakhiran Direktori Perusahaan Awal (DPA)', 'pj' => 'Kurniawan Arief Prasetyo SST', 'fungsi' => 'IPDS', 'pml' => 25000, 'pcl' => 15000, 'pengolahan' => 8000],
            ['kode' => 'K-029', 'nama' => 'Survei Tahunan Perusahaan Industri Manufaktur', 'pj' => 'Lilis Dwiyanti, S.Tr.Stat.', 'fungsi' => 'Produksi', 'pml' => 60000, 'pcl' => 45000, 'pengolahan' => 25000],
            ['kode' => 'K-030', 'nama' => 'Survei IMK Tahunan & Triwulanan', 'pj' => 'Marniati, S.E.', 'fungsi' => 'Produksi', 'pml' => 35000, 'pcl' => 25000, 'pengolahan' => 15000],
            ['kode' => 'K-031', 'nama' => 'Survei Pertambangan Migas, Listrik & Gas', 'pj' => 'Muh. Sadar', 'fungsi' => 'Produksi', 'pml' => 70000, 'pcl' => 50000, 'pengolahan' => 30000],
            ['kode' => 'K-032', 'nama' => 'Survei Perusahaan Panas Bumi', 'pj' => 'Muhamad Hasdi, SE', 'fungsi' => 'Produksi', 'pml' => 50000, 'pcl' => 35000, 'pengolahan' => 20000],
            ['kode' => 'K-033', 'nama' => 'Survei Perusahaan Pertambangan Non Migas', 'pj' => 'Sanur Saprah, SE', 'fungsi' => 'Produksi', 'pml' => 60000, 'pcl' => 45000, 'pengolahan' => 25000],

            // --- Kelompok 3: Survei Distribusi & Harga (K-034 s.d K-042) ---
            ['kode' => 'K-034', 'nama' => 'Survei Pola Distribusi Barang', 'pj' => 'Abdurrahman S.Tr.Stat.', 'fungsi' => 'Distribusi', 'pml' => 45000, 'pcl' => 30000, 'pengolahan' => 15000],
            ['kode' => 'K-035', 'nama' => 'Survei Pola Usaha Non Pertanian', 'pj' => 'Amrisany Sektora Daud, A.Md.Stat.', 'fungsi' => 'Produksi', 'pml' => 40000, 'pcl' => 25000, 'pengolahan' => 12000],
            ['kode' => 'K-036', 'nama' => 'Survei Jasa Penunjang Angkutan', 'pj' => 'Andi Muh. Irfan', 'fungsi' => 'Distribusi', 'pml' => 30000, 'pcl' => 20000, 'pengolahan' => 10000],
            ['kode' => 'K-037', 'nama' => 'Survei Harga Produsen (HP, HPJ, HPBG)', 'pj' => 'Ardan, A.Md', 'fungsi' => 'Distribusi', 'pml' => 35000, 'pcl' => 25000, 'pengolahan' => 15000],
            ['kode' => 'K-038', 'nama' => 'Survei Harga Perdagangan Besar', 'pj' => 'Ashadi, SE', 'fungsi' => 'Distribusi', 'pml' => 25000, 'pcl' => 15000, 'pengolahan' => 8000],
            ['kode' => 'K-039', 'nama' => 'Survei Harga Mesin & Peralatan (SHMP) & HPT', 'pj' => 'Asri Samsu Alam, SE', 'fungsi' => 'Distribusi', 'pml' => 40000, 'pcl' => 30000, 'pengolahan' => 15000],
            ['kode' => 'K-040', 'nama' => 'Survei Harga Properti Perumahan', 'pj' => 'Atika Putri Purwaningrum SST', 'fungsi' => 'Distribusi', 'pml' => 35000, 'pcl' => 25000, 'pengolahan' => 10000],
            ['kode' => 'K-041', 'nama' => 'Survei Harga Konsumen (HK 1.1 Pasar)', 'pj' => 'Darmin Laipo, S. Stat.', 'fungsi' => 'Distribusi', 'pml' => 20000, 'pcl' => 15000, 'pengolahan' => 8000],
            ['kode' => 'K-042', 'nama' => 'Survei Harga Konsumen (HK 1.1 Outlet)', 'pj' => 'Dyah Tari Nur\'aini, S.ST, M.M.', 'fungsi' => 'Distribusi', 'pml' => 20000, 'pcl' => 15000, 'pengolahan' => 8000],

            // --- Kelompok 4: 15 DATA KEGIATAN BARU TAMBAHAN (K-043 s.d K-057) ---
            ['kode' => 'K-043', 'nama' => 'Survei Pertanian Antar Sensus (SUTAS)', 'pj' => 'Fikron Tanazzul Ahsani S.Tr.Stat.', 'fungsi' => 'Produksi', 'pml' => 50000, 'pcl' => 35000, 'pengolahan' => 18000],
            ['kode' => 'K-044', 'nama' => 'Survei Angkutan Darat (SAD)', 'pj' => 'Fitri Permata Sari, SST', 'fungsi' => 'Distribusi', 'pml' => 30000, 'pcl' => 20000, 'pengolahan' => 10000],
            ['kode' => 'K-045', 'nama' => 'Survei Usaha Terintegrasi (SUTRI)', 'pj' => 'Hasnawati P., S.M.', 'fungsi' => 'Neraca', 'pml' => 45000, 'pcl' => 30000, 'pengolahan' => 15000],
            ['kode' => 'K-046', 'nama' => 'Pemutakhiran Wilayah Kerja Statistik (Wilkerstat)', 'pj' => 'Kurniawan Arief Prasetyo SST', 'fungsi' => 'IPDS', 'pml' => 55000, 'pcl' => 40000, 'pengolahan' => 20000],
            ['kode' => 'K-047', 'nama' => 'Survei Neraca Pendapatan Nasional', 'pj' => 'Ina Apriani, S.Stat.', 'fungsi' => 'Neraca', 'pml' => 40000, 'pcl' => 25000, 'pengolahan' => 12000],
            ['kode' => 'K-048', 'nama' => 'Kompilasi Data Statistik Sektoral', 'pj' => 'Rohmah Dini Ayunda Mustofa S.Tr.Stat.', 'fungsi' => 'IPDS', 'pml' => 25000, 'pcl' => 15000, 'pengolahan' => 10000],
            ['kode' => 'K-049', 'nama' => 'Survei Struktur Ongkos Usaha Tani (SOUT)', 'pj' => 'Lilis Dwiyanti, S.Tr.Stat.', 'fungsi' => 'Produksi', 'pml' => 45000, 'pcl' => 30000, 'pengolahan' => 15000],
            ['kode' => 'K-050', 'nama' => 'Survei Perdagangan Antar Wilayah', 'pj' => 'Marniati, S.E.', 'fungsi' => 'Distribusi', 'pml' => 35000, 'pcl' => 25000, 'pengolahan' => 12000],
            ['kode' => 'K-051', 'nama' => 'Survei Kepuasan Jasa Kebutuhan Data (SKIK)', 'pj' => 'Yunita Nur Khasanah, SST', 'fungsi' => 'Sosial', 'pml' => 30000, 'pcl' => 20000, 'pengolahan' => 10000],
            ['kode' => 'K-052', 'nama' => 'Survei Indeks Tendensi Konsumen (ITK)', 'pj' => 'Zulfikar Halim Lumintang, S.ST., M.M.', 'fungsi' => 'Neraca', 'pml' => 40000, 'pcl' => 30000, 'pengolahan' => 15000],
            ['kode' => 'K-053', 'nama' => 'Survei Kebutuhan Data (SKD) BPS', 'pj' => 'Ishak', 'fungsi' => 'Umum', 'pml' => 25000, 'pcl' => 15000, 'pengolahan' => 8000],
            ['kode' => 'K-054', 'nama' => 'Survei Real Estat Sektor Perusahaan', 'pj' => 'Muh. Sadar', 'fungsi' => 'Produksi', 'pml' => 50000, 'pcl' => 35000, 'pengolahan' => 20000],
            ['kode' => 'K-055', 'nama' => 'Survei Industri Makanan Tradisional', 'pj' => 'Sapari', 'fungsi' => 'Produksi', 'pml' => 30000, 'pcl' => 20000, 'pengolahan' => 10000],
            ['kode' => 'K-056', 'nama' => 'Pemetaan Blok Sensus & Muatan Keluarga', 'pj' => 'Idhar Rahim, SE', 'fungsi' => 'IPDS', 'pml' => 60000, 'pcl' => 45000, 'pengolahan' => 25000],
            ['kode' => 'K-057', 'nama' => 'Survei Harga Eceran Komoditas Tradisional', 'pj' => 'Sanur Saprah, SE', 'fungsi' => 'Distribusi', 'pml' => 20000, 'pcl' => 15000, 'pengolahan' => 8000],
        ];

        foreach ($kegiatans as $keg) {
            Kegiatan::updateOrCreate(
                ['kode_kegiatan' => $keg['kode']], 
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
                ]
            );
        }
    }
}