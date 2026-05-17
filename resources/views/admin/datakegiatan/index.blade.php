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
                    <th class="py-3">Jenis</th>
                    <th class="py-3">Honor per Dokumen</th>
                    <th class="py-3">Periode</th>
                    <th class="py-3" style="width: 120px;">Aksi</th>
                </tr>
            </thead>
            <tbody class="align-middle">
                @forelse ($kegiatans as $kegiatan)
                <tr>
                    <td class="text-center">{{ $loop->iteration + $kegiatans->firstItem() - 1 }}</td>
                    <td class="fw-medium">{{ $kegiatan->nama_kegiatan }}</td>
                    <td>{{ $kegiatan->penanggung_jawab }}</td> 
                    <td class="text-center">{{ $kegiatan->fungsi }}</td>
                    <td class="text-center">
                        <span class="badge {{ $kegiatan->jenis_kegiatan == 'Lapangan' ? 'bg-info' : 'bg-secondary' }}">
                            {{ $kegiatan->jenis_kegiatan }}
                        </span>
                    </td>
                    <td class="text-end px-3">
                        @if($kegiatan->jenis_kegiatan == 'Lapangan')
                            <small class="text-muted d-block">PML: <b>Rp {{ number_format($kegiatan->honor_pml_per_dokumen ?? 0, 0, ',', '.') }}</b></small>
                            <small class="text-muted d-block">PCL: <b>Rp {{ number_format($kegiatan->honor_pcl_per_dokumen ?? 0, 0, ',', '.') }}</b></small>
                        @elseif($kegiatan->jenis_kegiatan == 'Pengolahan')
                            <b>Rp {{ number_format($kegiatan->honor_pengolahan_per_dokumen ?? 0, 0, ',', '.') }}</b>
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td>
                    <td class="text-center small">
                        {{ \Carbon\Carbon::parse($kegiatan->tgl_mulai)->format('d/m/Y') }}<br>s.d<br>{{ \Carbon\Carbon::parse($kegiatan->tgl_selesai)->format('d/m/Y') }}
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
                    <td colspan="8" class="text-center py-5 text-muted">
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
    
    // --- INISIALISASI VARIABEL FORM ---
    const modalKegiatanElement = document.getElementById('modalKegiatan');
    const modalTitle = document.getElementById("modalTitle");
    const form = document.getElementById("formKegiatan");
    const formMethodInput = document.getElementById("formMethod");
    const kegiatanIdInput = document.getElementById("kegiatanId");
    const jenisKegiatanSelect = document.getElementById('jenisKegiatan');
    const inputHonorPML = document.getElementById('honor_pml_per_dokumen');
    const inputHonorPCL = document.getElementById('honor_pcl_per_dokumen');
    const inputHonorPengolahan = document.getElementById('honor_pengolahan_per_dokumen');
    const targetDokumenInput = document.getElementById('targetDokumen');
    const honorLapanganDiv = document.getElementById('honorLapanganFields');
    const honorPengolahanDiv = document.getElementById('honorPengolahanFields');

    // --- FUNGSI TOGGLE HONOR ---
    function toggleHonorFields() {
        if (!jenisKegiatanSelect || !honorLapanganDiv || !honorPengolahanDiv) return;
        
        const selectedJenis = jenisKegiatanSelect.value;
        honorLapanganDiv.style.display = 'none';
        honorPengolahanDiv.style.display = 'none';
        
        if (inputHonorPML) inputHonorPML.required = false;
        if (inputHonorPCL) inputHonorPCL.required = false;
        if (inputHonorPengolahan) inputHonorPengolahan.required = false;

        if (selectedJenis === 'Lapangan') {
            honorLapanganDiv.style.display = 'block';
            if (inputHonorPML) inputHonorPML.required = true;
            if (inputHonorPCL) inputHonorPCL.required = true;
        } else if (selectedJenis === 'Pengolahan') {
            honorPengolahanDiv.style.display = 'block';
            if (inputHonorPengolahan) inputHonorPengolahan.required = true;
        }
    }

    // --- EVENT TAMBAH DATA ---
    document.querySelector(".btnTambah").addEventListener("click", function () {
        modalTitle.textContent = "Tambah Data Kegiatan";
        form.reset();
        kegiatanIdInput.value = "";
        form.action = "{{ route('datakegiatan.store') }}";
        formMethodInput.value = "POST";
        toggleHonorFields();
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
            if(targetDokumenInput) targetDokumenInput.value = this.dataset.target || '';
            document.getElementById("fungsi").value = this.dataset.fungsi || '';
            if(jenisKegiatanSelect) jenisKegiatanSelect.value = this.dataset.jenis || '';
            document.getElementById("tanggalMulai").value = this.dataset.mulai || '';
            document.getElementById("tanggalSelesai").value = this.dataset.selesai || '';
            
            if(inputHonorPML) inputHonorPML.value = this.dataset.honor_pml || '';
            if(inputHonorPCL) inputHonorPCL.value = this.dataset.honor_pcl || '';
            if(inputHonorPengolahan) inputHonorPengolahan.value = this.dataset.honor_pengolahan || '';
            
            toggleHonorFields();
        });
    });

    // Listen change on select inside modal
    if(jenisKegiatanSelect){
        jenisKegiatanSelect.addEventListener('change', toggleHonorFields);
    }
});
</script>
@endpush