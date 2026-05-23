@extends('layouts.master')
 
@section('content')
<div class="container-fluid py-4">
 
    {{-- Logika PHP untuk Filter Bulan & Tahun --}}
    @php
        $bulanIndo = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];
        $bulanPilih = request('month', date('n')); 
        $tahunPilih = request('year', date('Y'));
        $listTahun = range(2024, date('Y') + 1); // List tahun dinamis
    @endphp
 
    {{-- JUDUL HALAMAN --}}
    <div class="mb-4">
        <h2 class="fw-bold text-dark mb-1">Riwayat Penugasan</h2>
    </div>
 
    <div class="card border-0 shadow-sm" style="border-radius: 12px;">
        
        {{-- ========================================== --}}
        {{-- HEADER CARD: JUDUL, FILTER ACCORDION & SEARCH --}}
        {{-- ========================================== --}}
        <div class="card-header bg-white border-bottom py-3 px-4 d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3" style="border-radius: 12px 12px 0 0;">
            <h6 class="fw-bold mb-0 text-dark">Seluruh Riwayat Penugasan Anda</h6>
            
            <div class="d-flex flex-column flex-sm-row gap-2 align-items-sm-center">
                
                {{-- 1. Dropdown Filter Accordion Premium --}}
                <div class="dropdown shadow-sm">
                    <button class="btn btn-white dropdown-toggle border-primary bg-white fw-bold text-primary shadow-sm px-3" type="button" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false" style="border-radius: 8px; height: 38px;">
                        <i class="bi bi-calendar3 me-1"></i> {{ $bulanIndo[(int)$bulanPilih] }} {{ $tahunPilih }}
                    </button>
                    
                    <div class="dropdown-menu dropdown-menu-end p-3 shadow-lg border-0" style="width: 320px; border-radius: 12px;">
                        <div class="text-center mb-3 pb-2 border-bottom">
                            <span class="fw-bold text-dark" style="font-size: 0.95rem;"><i class="bi bi-funnel-fill me-1 text-primary"></i> Pilih Tahun & Bulan</span>
                        </div>
                        
                        <div class="accordion accordion-flush" id="accordionTahunRiwayat">
                            @foreach($listTahun as $th)
                            <div class="accordion-item border-0 mb-2">
                                <h2 class="accordion-header" id="heading-rw-{{ $th }}">
                                    <button class="accordion-button {{ $tahunPilih == $th ? '' : 'collapsed' }} py-2 px-3 fw-bold rounded bg-light border shadow-sm" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-rw-{{ $th }}" style="font-size: 0.9rem;">
                                        Tahun {{ $th }}
                                    </button>
                                </h2>
                                <div id="collapse-rw-{{ $th }}" class="accordion-collapse collapse {{ $tahunPilih == $th ? 'show' : '' }}" data-bs-parent="#accordionTahunRiwayat">
                                    <div class="accordion-body p-2 border border-top-0 rounded-bottom bg-white">
                                        <div class="row g-2">
                                            @foreach($bulanIndo as $angka => $nama)
                                            <div class="col-4">
                                                {{-- Link ini otomatis menyertakan keyword search jika sedang melakukan pencarian --}}
                                                <a href="?year={{ $th }}&month={{ $angka }}{{ request('search') ? '&search='.request('search') : '' }}" class="btn btn-sm w-100 {{ ($tahunPilih == $th && $bulanPilih == $angka) ? 'btn-primary text-white fw-bold shadow' : 'btn-outline-primary' }}" style="font-size: 0.75rem; border-radius: 6px;">
                                                    {{ substr($nama, 0, 3) }}
                                                </a>
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- 2. Form Pencarian Teks --}}
                <form action="{{ route('mitra.riwayat') }}" method="GET" class="d-flex mb-0">
                    {{-- Menyimpan status bulan dan tahun saat melakukan pencarian --}}
                    <input type="hidden" name="month" value="{{ $bulanPilih }}">
                    <input type="hidden" name="year" value="{{ $tahunPilih }}">
                    
                    <div class="input-group shadow-sm" style="border-radius: 8px; overflow: hidden;">
                        <input type="text" class="form-control border" name="search" placeholder="Cari No. Surat / Kegiatan..." value="{{ request('search') }}" style="font-size: 0.9rem; height: 38px; min-width: 250px;">
                        <button class="btn btn-primary px-3 fw-medium" type="submit" style="height: 38px;">
                            <i class="bi bi-search me-1"></i> Cari
                        </button>
                    </div>
                </form>
                
            </div>
        </div>
        
        {{-- ========================================== --}}
        {{-- TABEL DATA (DESAIN CLEAN & PROFESIONAL)    --}}
        {{-- ========================================== --}}
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover mb-0 align-middle border-top">
                    <thead class="table-primary text-center">
                        <tr>
                            <th class="py-3" style="width: 50px;">No</th>
                            <th class="py-3">Nomor Surat Tugas</th>
                            <th class="py-3">Bulan Penugasan</th>
                            <th class="py-3">Fungsi</th>
                            <th class="py-3">Kegiatan</th>
                            <th class="py-3">Total Honor</th>
                            <th class="py-3">Status</th>
                            <th class="py-3" style="width: 120px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($riwayatPenugasan as $index => $p)
                        <tr>
                            <td class="text-center text-muted fw-bold">{{ $index + 1 }}</td>
                            <td class="text-center">
                                <span class="text-primary fw-bold" style="font-size: 0.9rem;">
                                    {{ $p->no_surat }}
                                </span>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-white text-dark border px-3 py-2 fw-normal shadow-sm" style="border-radius: 6px;">{{ $p->bulan_kegiatan }}</span>
                            </td>
                            
                            {{-- KOLOM FUNGSI --}}
                            <td class="text-center fw-medium text-dark">
                                @if($p->details && $p->details->count() > 0)
                                    {{ $p->details->first()->kegiatan->fungsi ?? '-' }}
                                @else
                                    -
                                @endif
                            </td>
                            
                            {{-- KOLOM KEGIATAN --}}
                            <td class="text-start fw-medium text-dark">
                                @if($p->details && $p->details->count() > 0)
                                    {{ $p->details->first()->kegiatan->Nama_kegiatan ?? $p->details->first()->kegiatan->nama_kegiatan ?? '-' }}
                                @else
                                    -
                                @endif
                            </td>

                            <td class="text-end px-3 fw-bold text-primary">Rp {{ number_format($p->total_nilai_perjanjian, 0, ',', '.') }}</td>
                            
                            <td class="text-center">
                                @php
                                    $status = strtolower($p->status_kontrak ?? 'menunggu approval');
                                    if($status == 'disetujui' || $status == 'acc') {
                                        $badgeClass = 'bg-success';
                                    } elseif($status == 'ditolak') {
                                        $badgeClass = 'bg-danger';
                                    } else {
                                        $badgeClass = 'bg-warning text-dark';
                                    }
                                @endphp
                                <span class="badge {{ $badgeClass }} px-3 py-2 shadow-sm" style="border-radius: 6px; font-weight: 500;">
                                    {{ ucwords($p->status_kontrak ?? 'Menunggu Approval') }}
                                </span>
                            </td>

                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-2">
                                    
                                    {{-- TOMBOL MATA (Preview Pop-Up) --}}
                                    <button type="button" class="btn btn-sm btn-outline-info shadow-sm btn-lihat-kontrak" 
                                        title="Lihat Detail Penugasan" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#modalDetail"
                                        data-nosurat="{{ $p->no_surat }}"
                                        data-mitra="{{ $p->mitra->nama_petugas ?? '-' }}"
                                        data-bulan="{{ $p->bulan_kegiatan }}"
                                        data-tanggal="{{ $p->tanggal_surat }}"
                                        data-status="{{ $p->status_kontrak ?? 'Menunggu Approval' }}"
                                        data-total="{{ $p->total_nilai_perjanjian }}"
                                        data-details="{{ json_encode($p->details->map(function($d) {
                                            return [
                                                'kegiatan' => $d->kegiatan->Nama_kegiatan ?? $d->kegiatan->nama_kegiatan ?? '-',
                                                'peran'    => $d->uraian_tugas ?? '-', 
                                                'mulai'    => $d->tanggal_mulai ?? '-', 
                                                'selesai'  => $d->tanggal_selesai ?? '-', 
                                                'volume'   => $d->volume ?? 0,
                                                'satuan'   => $d->satuan ?? 'Dokumen',
                                                'harga'    => $d->harga_satuan ?? 0,
                                                'subtotal' => ($d->volume ?? 0) * ($d->harga_satuan ?? 0)
                                            ];
                                        })) }}">
                                        <i class="bi bi-eye-fill"></i>
                                    </button>

                                    {{-- TOMBOL CETAK SPK --}}
                                    <a href="{{ route('kelolakegiatan.cetak', $p->id_penugasan) }}" target="_blank" class="btn btn-sm btn-outline-secondary shadow-sm" title="Cetak SPK">
                                        <i class="bi bi-printer-fill"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-5 text-muted bg-white">
                                <i class="bi bi-inbox fs-1 d-block mb-3 text-secondary" style="opacity: 0.5;"></i>
                                Belum ada penugasan yang ditemukan.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- PANGGIL FILE MODAL DETAIL --}}
@include('ppk.modal_detail')
 
@endsection
 
@push('scripts')
<script>
document.addEventListener("DOMContentLoaded", function () {
    const btnLihatKontrak = document.querySelectorAll('.btn-lihat-kontrak');
 
    // Fungsi pembantu untuk mengubah format tanggal YYYY-MM-DD menjadi DD Bulan YYYY (Indonesia)
    function formatTanggalIndo(dateString) {
        if(!dateString || dateString === '-') return '-';
        const months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        const date = new Date(dateString);
        if (isNaN(date.getTime())) return dateString; // Jika sudah berformat lain, kembalikan teks aslinya
        
        return `${String(date.getDate()).padStart(2, '0')} ${months[date.getMonth()]} ${date.getFullYear()}`;
    }

    if(btnLihatKontrak.length > 0) {
        btnLihatKontrak.forEach(btn => {
            btn.addEventListener('click', function () {
                // 1. Isi Header Pop-up Modal (Gunakan Format Indonesia)
                document.getElementById('detailNoSurat').textContent = this.dataset.nosurat;
                document.getElementById('detailNamaMitra').textContent = this.dataset.mitra;
                document.getElementById('detailBulan').textContent = this.dataset.bulan;
                document.getElementById('detailTanggalSurat').textContent = formatTanggalIndo(this.dataset.tanggal);
                
                // 2. Isi Status Kontrak beserta Warnanya (Hijau Sukses Konsisten)
                const statusBadge = document.getElementById('detailStatus');
                const statusVal = this.dataset.status.toLowerCase();
                statusBadge.textContent = this.dataset.status;
                if(statusVal === 'disetujui' || statusVal === 'acc') {
                    statusBadge.className = 'badge bg-success';
                } else if(statusVal === 'ditolak') {
                    statusBadge.className = 'badge bg-danger';
                } else {
                    statusBadge.className = 'badge bg-warning text-dark';
                }

                // 3. Format Total Honor ke Rupiah
                document.getElementById('detailTotalHonor').textContent = new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(this.dataset.total);

                // 4. Looping untuk Mengisi Tabel Rincian Pekerjaan di dalam Modal
                const tbody = document.getElementById('tbodyDetailRincian');
                tbody.innerHTML = ''; 
                
                const details = JSON.parse(this.dataset.details); 
                
                if(details.length > 0) {
                    details.forEach((d, index) => {
                        const tr = document.createElement('tr');
                        tr.innerHTML = `
                            <td class="text-center">${index + 1}</td>
                            <td class="text-start fw-bold text-dark">${d.kegiatan}</td>
                            <td class="text-center"><span class="badge bg-secondary">${d.peran}</span></td>
                            <td class="text-center">${formatTanggalIndo(d.mulai)}</td>
                            <td class="text-center">${formatTanggalIndo(d.selesai)}</td>
                            <td class="text-center fw-bold">${d.volume}</td>
                            <td class="text-center">${d.satuan}</td>
                            <td class="text-end">Rp ${new Intl.NumberFormat('id-ID').format(d.harga)}</td>
                            <td class="text-end fw-bold text-success">Rp ${new Intl.NumberFormat('id-ID').format(d.subtotal)}</td>
                        `;
                        tbody.appendChild(tr);
                    });
                } else {
                    tbody.innerHTML = `<tr><td colspan="9" class="text-center text-muted py-4">Tidak ada rincian pekerjaan</td></tr>`;
                }
            });
        });
    }
});
</script>
@endpush