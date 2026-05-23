@extends('layouts.master')

@section('title', 'Data Kegiatan')

@section('content')
<div class="container-fluid py-4">
    <div class="text-center mb-5">
        <h3 class="text-dark fw-bold">Data Kegiatan</h3>
    </div>

    @if ($errors->any()) 
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>Gagal menyimpan!</strong> Periksa kembali input Anda di form.
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="row mb-3 align-items-center">
        <div class="col-md-6 mb-2 mb-md-0">
            <button class="btn btn-success btnTambah" data-bs-toggle="modal" data-bs-target="#modalKegiatan">
                <i class="bi bi-plus-circle me-1"></i> Tambah Kegiatan
            </button>
        </div>

        <div class="col-md-6 d-flex justify-content-md-end">
            <form action="{{ route('datakegiatan.index') }}" method="GET" style="width: 100%; max-width: 400px;">
                <div class="input-group">
                    <input type="text" class="form-control" placeholder="Cari kode atau nama kegiatan..." name="search" value="{{ request('search') }}">
                    <button class="btn btn-primary px-3" type="submit">
                        <i class="bi bi-search me-1"></i> Cari
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="table-responsive shadow-sm" style="border-radius: 10px;">
        <table class="table table-striped table-bordered table-hover bg-white mb-0" style="border-radius: 10px; overflow: hidden;">
            <thead class="table-primary text-center align-middle">
                <tr>
                    <th class="py-3" style="width: 50px;">No.</th>
                    <th class="py-3">Nama Kegiatan</th>
                    <th class="py-3">Penanggung Jawab</th>
                    <th class="py-3">Fungsi</th>
                    {{-- Kolom Jenis dan Periode Dihapus --}}
                    <th class="py-3">Honor per Dokumen</th>
                    <th class="py-3" style="width: 120px;">Aksi</th>
                </tr>
            </thead>
            <tbody class="align-middle">
                @forelse ($kegiatans as $kegiatan)
                <tr>
                    <td class="text-center">{{ $loop->iteration + $kegiatans->firstItem() - 1 }}</td>
                    <td class="fw-medium">{{ $kegiatan->nama_kegiatan }}</td>
                    <td>{{ $kegiatan->penanggung_jawab }}</td> 
                    <td class="text-center">
                        <span class="badge bg-secondary">{{ $kegiatan->fungsi }}</span>
                    </td>
                    <td class="text-end px-4">
                        {{-- Menampilkan Honor Secara Dinamis --}}
                        @if($kegiatan->honor_pml_per_dokumen > 0)
                            <small class="text-muted d-block" style="font-size: 0.85rem;">PML: <b class="text-dark">Rp {{ number_format($kegiatan->honor_pml_per_dokumen, 0, ',', '.') }}</b></small>
                        @endif

                        @if($kegiatan->honor_pcl_per_dokumen > 0)
                            <small class="text-muted d-block" style="font-size: 0.85rem;">PCL: <b class="text-dark">Rp {{ number_format($kegiatan->honor_pcl_per_dokumen, 0, ',', '.') }}</b></small>
                        @endif

                        @if($kegiatan->honor_pengolahan_per_dokumen > 0)
                            <small class="text-success d-block mt-1" style="font-size: 0.85rem;">Pengolahan: <b>Rp {{ number_format($kegiatan->honor_pengolahan_per_dokumen, 0, ',', '.') }}</b></small>
                        @endif

                        @if($kegiatan->honor_pml_per_dokumen == 0 && $kegiatan->honor_pcl_per_dokumen == 0 && $kegiatan->honor_pengolahan_per_dokumen == 0)
                            <span class="text-muted fst-italic small">-</span>
                        @endif
                    </td>
                    <td class="text-center">
                        <div class="d-flex justify-content-center gap-2">
                            {{-- TOMBOL EDIT --}}
                            <button class="btn btn-outline-warning btn-sm shadow-sm btnEdit"
                                title="Edit Data"
                                data-bs-toggle="modal" 
                                data-bs-target="#modalKegiatan"
                                data-id="{{ $kegiatan->id_kegiatan }}"
                                data-kode="{{ $kegiatan->kode_kegiatan }}"
                                data-nama="{{ $kegiatan->nama_kegiatan }}"
                                data-penanggung="{{ $kegiatan->penanggung_jawab }}"
                                data-tim="{{ $kegiatan->nama_tim }}"
                                data-target="{{ $kegiatan->target_dokumen }}"
                                data-fungsi="{{ $kegiatan->fungsi }}"
                                data-jenis="{{ $kegiatan->jenis_kegiatan }}"
                                data-mulai="{{ $kegiatan->tgl_mulai }}"
                                data-selesai="{{ $kegiatan->tgl_selesai }}"
                                data-honor_pml="{{ $kegiatan->honor_pml_per_dokumen }}"
                                data-honor_pcl="{{ $kegiatan->honor_pcl_per_dokumen }}"
                                data-honor_pengolahan="{{ $kegiatan->honor_pengolahan_per_dokumen }}">
                                <i class="bi bi-pencil-fill"></i>
                            </button>

                            {{-- TOMBOL HAPUS --}}
                            <form action="{{ route('datakegiatan.destroy', $kegiatan->id_kegiatan) }}" method="POST" class="d-inline form-hapus">
                                @csrf 
                                @method('DELETE')
                                <button type="button" class="btn btn-outline-danger btn-sm shadow-sm btn-hapus-sweet" title="Hapus Data">
                                    <i class="bi bi-trash-fill"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr> 
                    <td colspan="6" class="text-center py-5 text-muted">
                        <i class="bi bi-inbox fs-1 d-block mb-3 text-secondary" style="opacity: 0.5;"></i>
                        Data kegiatan tidak ditemukan.
                    </td> 
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="d-flex justify-content-end mt-3"> 
        {{ $kegiatans->links() }} 
    </div>
</div>

@include('admin.datakegiatan.modal')
@endsection

@push('scripts')
<script>
document.addEventListener("DOMContentLoaded", function () {
    
    const modalTitle = document.getElementById("modalTitle");
    const form = document.getElementById("formKegiatan");
    const formMethodInput = document.getElementById("formMethod");
    const kegiatanIdInput = document.getElementById("kegiatanId");

    // ==========================================
    // 1. FUNGSI FORMAT RUPIAH OTOMATIS
    // ==========================================
    function formatRupiah(angka) {
        // Hapus semua karakter selain angka
        let number_string = angka.toString().replace(/[^,\d]/g, ''),
            split = number_string.split(','),
            sisa = split[0].length % 3,
            rupiah = split[0].substr(0, sisa),
            ribuan = split[0].substr(sisa).match(/\d{3}/gi);

        // Tambahkan titik jika yang diinput sudah menjadi angka ribuan
        if (ribuan) {
            let separator = sisa ? '.' : '';
            rupiah += separator + ribuan.join('.');
        }
        return rupiah;
    }

    // Pasang event listener ke semua input yang punya class 'input-rupiah'
    document.querySelectorAll('.input-rupiah').forEach(function(input) {
        input.addEventListener('input', function(e) {
            // Format langsung saat diketik
            this.value = formatRupiah(this.value);
        });
    });

    // ==========================================
    // 2. BERSIHKAN TITIK SEBELUM DISIMPAN KE DB
    // ==========================================
    form.addEventListener('submit', function() {
        document.querySelectorAll('.input-rupiah').forEach(function(input) {
            // Hapus titik sebelum form dikirim ke controller
            input.value = input.value.replace(/\./g, '');
        });
    });

    // ==========================================
    // 3. EVENT TAMBAH DATA
    // ==========================================
    document.querySelector(".btnTambah").addEventListener("click", function () {
        modalTitle.textContent = "Tambah Data Kegiatan";
        form.reset();
        
        document.getElementById("honor_pml_per_dokumen").value = 0;
        document.getElementById("honor_pcl_per_dokumen").value = 0;
        document.getElementById("honor_pengolahan_per_dokumen").value = 0;
        document.getElementById("targetDokumen").value = 100;

        kegiatanIdInput.value = "";
        form.action = "{{ route('datakegiatan.store') }}";
        formMethodInput.value = "POST";
    });

// --- EVENT EDIT DATA ---
    document.querySelectorAll(".btnEdit").forEach(btn => {
        btn.addEventListener("click", function () {
            modalTitle.textContent = "Edit Data Kegiatan";
            form.reset();

            const kegiatanId = this.dataset.id;
            form.action = `/datakegiatan/${kegiatanId}`;
            formMethodInput.value = "PUT";

            kegiatanIdInput.value = kegiatanId;
            document.getElementById("kodeKegiatan").value = this.dataset.kode || '';
            document.getElementById("namaKegiatan").value = this.dataset.nama || '';
            document.getElementById("penanggungJawab").value = this.dataset.penanggung || '';
            document.getElementById("namaTim").value = this.dataset.tim || '';
            document.getElementById("targetDokumen").value = this.dataset.target || 100;
            document.getElementById("fungsi").value = this.dataset.fungsi || '';
            document.getElementById("jenisKegiatan").value = this.dataset.jenis || '';
            
            document.getElementById("tanggalMulai").value = this.dataset.mulai || '';
            document.getElementById("tanggalSelesai").value = this.dataset.selesai || '';
            
            //"15000.00" menjadi angka murni 15000 dengan Number() & Math.floor()
            let pmlAsli = Math.floor(Number(this.dataset.honor_pml || 0));
            let pclAsli = Math.floor(Number(this.dataset.honor_pcl || 0));
            let pengolahanAsli = Math.floor(Number(this.dataset.honor_pengolahan || 0));

            document.getElementById("honor_pml_per_dokumen").value = formatRupiah(pmlAsli);
            document.getElementById("honor_pcl_per_dokumen").value = formatRupiah(pclAsli);
            document.getElementById("honor_pengolahan_per_dokumen").value = formatRupiah(pengolahanAsli);
        });
    });

});
</script>
@endpush