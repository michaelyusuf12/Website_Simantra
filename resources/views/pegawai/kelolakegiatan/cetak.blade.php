<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cetak SPK - {{ $penugasan->no_surat ?? 'Dokumen' }}</title>
    <style>
        /* DomPDF membutuhkan pengaturan margin lewat CSS body/html */
        @page { margin: 1cm; } 
        body { 
            font-family: 'serif'; /* Times New Roman standar dompdf */
            font-size: 11pt; 
            line-height: 1.4;
            color: #000;
        }
        
        .kop-surat { text-align: center; margin-bottom: 20px; }
        .kop-surat h3 { margin: 0; padding: 0; font-size: 13pt; text-transform: uppercase; }
        .no-surat { margin-top: 5px; font-weight: bold; }

        /* Tabel untuk dompdf harus menggunakan width 100% dan border-collapse */
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #000; padding: 6px; vertical-align: top; }
        th { background-color: #f2f2f2; }

        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .text-bold { font-weight: bold; }

        /* Untuk tanda tangan, dompdf paling stabil menggunakan tabel */
        .ttd-table { width: 100%; margin-top: 40px; border: none; }
        .ttd-table td { border: none; width: 50%; text-align: center; padding: 0; }
        .ttd-space { height: 70px; }
    </style>
</head>
<body>

    <div class="kop-surat">
        <h3>SURAT PERJANJIAN KERJA</h3>
        <h3>MITRA STATISTIK</h3> 
        <h3>KEGIATAN SURVEI/SENSUS TAHUN {{ $penugasan->tahun_anggaran ?? '2026' }}</h3>
        <h3>PADA BADAN PUSAT STATISTIK KABUPATEN KOLAKA</h3>
        <div class="no-surat">
            No. SURAT {{ $penugasan->no_surat ?? '............................' }}
        </div>
    </div>

    <p style="text-align: justify;">
        Pada hari ini, telah disepakati Perjanjian Kerja antara Badan Pusat Statistik Kabupaten Kolaka dengan <b>{{ $penugasan->mitra->nama_petugas ?? '...................' }}</b> selaku Pihak Kedua (Mitra Statistik), untuk melaksanakan kegiatan pada bulan <b>{{ $penugasan->bulan_kegiatan }} {{ $penugasan->tahun_anggaran }}</b> dengan rincian pekerjaan sebagai berikut:
    </p>

    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="35%">Nama Kegiatan</th>
                <th width="15%">Peran</th>
                <th width="20%">Waktu Pelaksanaan</th>
                <th width="10%">Vol</th>
                <th width="15%">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($penugasan->details as $index => $dt)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>{{ $dt->kegiatan->Nama_kegiatan ?? $dt->kegiatan->nama_kegiatan ?? '-' }}</td>
                <td class="text-center">{{ $dt->uraian_tugas }}</td>
                <td class="text-center">
                    {{ \Carbon\Carbon::parse($dt->tanggal_mulai)->locale('id')->translatedFormat('d F') }} - 
                    {{ \Carbon\Carbon::parse($dt->tanggal_selesai)->locale('id')->translatedFormat('d F Y') }}
                </td>
                <td class="text-center">{{ $dt->volume }}</td>
                <td class="text-right">{{ number_format($dt->volume * $dt->harga_satuan, 0, ',', '.') }}</td>
            </tr>
            @endforeach
            <tr>
                <td colspan="5" class="text-center text-bold">TOTAL HONORARIUM</td>
                <td class="text-right text-bold">{{ number_format($penugasan->total_nilai_perjanjian, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>

    {{-- TANDA TANGAN MENGGUNAKAN TABEL (Lebih Stabil di PDF) --}}
    <table class="ttd-table">
        <tr>
            <td>
                <p>Pihak Kedua,<br>Mitra Statistik</p>
                <div class="ttd-space"></div>
                <p><b><u>{{ $penugasan->mitra->nama_petugas ?? '.....................' }}</u></b></p>
            </td>
            <td>
                <p>Kolaka, {{ \Carbon\Carbon::now()->locale('id')->translatedFormat('d F Y') }}<br>Kepala BPS Kolaka</p>
                <div class="ttd-space"></div>
                <p><b><u>{{ $pejabat ? strtoupper($pejabat->nama) : '(NAMA PEJABAT)' }}</u></b><br>
                NIP. {{ $pejabat ? $pejabat->nip : '.....................' }}</p>
            </td>
        </tr>
    </table>

</body>
</html>