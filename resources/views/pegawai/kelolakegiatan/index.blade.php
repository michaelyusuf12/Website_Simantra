@extends('layouts.master')

@section('content')
<div class="container-fluid py-4">
    
    {{-- JUDUL HALAMAN --}}
    <h3 class="text-center fw-bold text-dark mb-4" style="letter-spacing: 0.5px;">Kelola Penugasan Mitra</h3>

    {{-- TOMBOL TAMBAH --}}
    <div class="mb-4">
        <button type="button" class="btn btn-success px-4 shadow-sm" data-bs-toggle="modal" data-bs-target="#modalKelolaKegiatan" style="border-radius: 6px; font-weight: 500;">
            <i class="bi bi-plus-circle me-1"></i> Tambah Penugasan
        </button>
    </div>

    {{-- FILTER DAN PENCARIAN --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="fw-bold text-dark mb-0">Daftar Surat Perjanjian Kerja</h5>
        
        <div class="d-flex gap-2">
            
    {{-- DROPDOWN BULAN (Sama persis dengan Beranda) --}}
            @php
                $bulanIndo = [
                    1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                    5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                    9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
                ];
                
                // Set default bulan ke bulan saat ini jika tidak ada filter
                $bulanSaatIni = date('n');
                $namaBulanAktif = request('bulan') ? request('bulan') : $bulanIndo[$bulanSaatIni];
                $tahunSaatIni = date('Y');
            @endphp

            <div class="dropdown shadow-sm">
                <button class="btn btn-white dropdown-toggle px-4 border bg-white h-100" type="button" data-bs-toggle="dropdown" style="border-radius: 10px; font-weight: 500;">
                    <i class="bi bi-calendar3 me-2 text-primary"></i> 
                    {{ $namaBulanAktif }} {{ $tahunSaatIni }}
                </button>
                <ul class="dropdown-menu dropdown-menu-end shadow border-0" style="border-radius: 12px; max-height: 300px; overflow-y: auto;">
                    <li><h6 class="dropdown-header">Pilih Periode</h6></li>
                    
                    {{-- Looping Bulan Saja (Tanpa opsi Semua Bulan) --}}
                    @foreach($bulanIndo as $angka => $nama)
                        <li>
                            <a class="dropdown-item {{ $namaBulanAktif == $nama ? 'active bg-primary text-white' : '' }}" 
                               href="?bulan={{ urlencode($nama) }}{{ request('search') ? '&search='.urlencode(request('search')) : '' }}">
                               {{ $nama }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
            
            {{-- PENCARIAN --}}
            <form action="{{ route('kelolakegiatan.index') }}" method="GET" class="mb-0">
                @if(request('bulan'))
                    <input type="hidden" name="bulan" value="{{ request('bulan') }}">
                @endif
                
                <div class="input-group shadow-sm" style="width: 300px; border-radius: 10px; overflow: hidden;">
                    <input type="text" class="form-control border-0 bg-white" name="search" placeholder="Cari nama mitra atau SPK..." value="{{ request('search') }}">
                    <button class="btn btn-primary px-3" type="submit" style="font-weight: 500;">
                        <i class="bi bi-search me-1"></i> Cari
                    </button>
                </div>
            </form>

        </div>
    </div>

    {{-- TABEL UTAMA --}}
    <div class="table-responsive shadow-sm" style="border-radius: 10px;">
        <table class="table table-striped table-bordered table-hover bg-white mb-0 align-middle" style="border-radius: 10px; overflow: hidden;">
            <thead class="table-primary text-center">
                <tr>
                    <th class="py-3" style="width: 60px;">No</th>
                    <th class="py-3">Nama Mitra</th>
                    <th class="py-3">No. Surat (SPK)</th>
                    <th class="py-3">Bulan</th>
                    <th class="py-3">Jumlah Kegiatan</th>
                    <th class="py-3">Total Honor</th>
                    <th class="py-3" style="width: 170px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($penugasans as $index => $p)
                <tr>
                    <td class="text-center text-muted fw-bold">{{ $index + 1 }}</td>
                    <td><div class="fw-bold text-dark">{{ $p->mitra->nama_petugas ?? '-' }}</div></td>
                    <td class="text-center">
                        <span class="text-primary fw-bold" style="font-size: 0.9rem;">
                            {{ $p->no_surat ?? 'Belum ada nomor' }}
                        </span>
                    </td>
                    <td class="text-center">
                        <span class="badge bg-white text-dark border px-3 py-2 fw-normal shadow-sm" style="border-radius: 6px;">{{ $p->bulan_kegiatan }}</span>
                    </td>
                    <td class="text-center fw-bold text-dark">{{ $p->details ? $p->details->count() : 0 }}</td>
                    <td class="text-end px-3 fw-bold text-primary">Rp {{ number_format($p->total_nilai_perjanjian, 0, ',', '.') }}</td>
                    <td class="text-center">
                        
                        <div class="d-flex justify-content-center gap-2">
                            {{-- TOMBOL LIHAT DETAIL --}}
                            <button type="button" class="btn btn-sm btn-outline-info shadow-sm btn-lihat-detail" data-id="{{ $p->id_penugasan ?? $p->id }}" title="Lihat Detail">
                                <i class="bi bi-eye-fill"></i>
                            </button>
                            
                            {{-- TOMBOL EDIT --}}
                            <button type="button" class="btn btn-sm btn-outline-warning shadow-sm btn-edit-penugasan" data-id="{{ $p->id_penugasan ?? $p->id }}" title="Edit">
                                <i class="bi bi-pencil-fill"></i>
                            </button>
                            
                            {{-- TOMBOL HAPUS --}}
                            <form action="{{ route('kelolakegiatan.destroy', $p->id_penugasan ?? $p->id) }}" method="POST" class="d-inline form-hapus-data">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger shadow-sm" title="Hapus Data">
                                    <i class="bi bi-trash-fill"></i>
                                </button>
                            </form>
                            
                            {{-- TOMBOL CETAK --}}
                            <a href="{{ route('kelolakegiatan.cetak', $p->id_penugasan ?? $p->id) }}" target="_blank" class="btn btn-sm btn-outline-secondary shadow-sm" title="Cetak SPK">
                                <i class="bi bi-printer-fill"></i>
                            </a>
                        </div>

                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-5 text-muted">
                        <i class="bi bi-inbox fs-1 d-block mb-3 text-secondary" style="opacity: 0.5;"></i>
                        Belum ada data penugasan.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- MEMANGGIL KOMPONEN MODAL DARI FILE EKSTERNAL --}}
    @include('pegawai.kelolakegiatan.modal')
    @include('pegawai.kelolakegiatan.modal-detail')

</div>
@endsection

@push('scripts')
{{-- JEMBATAN VARIABEL LARAVEL KE JAVASCRIPT EXTERNAL --}}
<script>
    window.AppRoutes = {
        cekAkumulasi: "{{ route('kelolakegiatan.cekAkumulasi') }}",
        store: "{{ route('kelolakegiatan.store') }}",
        csrfToken: "{{ csrf_token() }}"
    };
</script>

{{-- MEMANGGIL LOGIKA JAVASCRIPT UTAMA --}}
<script src="{{ asset('js/kelolakegiatan.js') }}"></script>
@endpush