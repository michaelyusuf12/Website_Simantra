<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Daftar Mitra</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #000; padding-bottom: 10px; }
        .header h3, .header h4 { margin: 0; padding: 2px; }
        .table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .table th, .table td { border: 1px solid #000; padding: 6px; text-align: left; }
        .table th { background-color: #f2f2f2; text-align: center; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .ttd { width: 300px; float: right; margin-top: 30px; text-align: center; }
    </style>
</head>
<body>

    <div class="header">
        <h3>BADAN PUSAT STATISTIK</h3>
        <h4>DAFTAR NOMINATIF HONORARIUM MITRA STATISTIK</h4>
        <p>Bulan Penugasan: {{ $namaBulan }} 2026 | Status: {{ strtoupper($statusFilter) }}</p>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="15%">No. Surat/Draf</th>
                <th width="20%">Nama Mitra</th>
                <th width="35%">Rincian Kegiatan</th>
                <th width="10%">Tanggal</th>
                <th width="15%">Total Honor (Rp)</th>
            </tr>
        </thead>
        <tbody>
            @forelse($penugasans as $index => $p)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $p->no_surat }}</td>
                    <td>{{ $p->mitra->nama_petugas ?? '-' }}</td>
                    <td>
                        @if($p->details)
                            <ul style="margin: 0; padding-left: 15px;">
                                @foreach($p->details as $d)
                                    <li>{{ $d->kegiatan->Nama_kegiatan ?? $d->kegiatan->nama_kegiatan ?? '-' }} ({{ $d->uraian_tugas }})</li>
                                @endforeach
                            </ul>
                        @endif
                    </td>
                    <td class="text-center">{{ \Carbon\Carbon::parse($p->created_at)->format('d/m/Y') }}</td>
                    <td class="text-right">{{ number_format($p->total_nilai_perjanjian, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center">Tidak ada data dokumen.</td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr>
                <th colspan="5" class="text-right">TOTAL KESELURUHAN</th>
                <th class="text-right">{{ number_format($totalHonor, 0, ',', '.') }}</th>
            </tr>
        </tfoot>
    </table>

    <div class="ttd">
        <p>Mengetahui/Menyetujui,</p>
        <p><b>Kepala BPS Kabupaten/Kota</b></p>
        <br><br><br><br>
        <p><u>Drs. Ahmad (Nama Kepala)</u><br>NIP. 19700101 200001 1 001</p>
    </div>

</body>
</html>