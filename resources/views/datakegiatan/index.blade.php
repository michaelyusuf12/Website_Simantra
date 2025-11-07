@extends('layouts.master')
@section('title', 'Data Kegiatan')
@section('content')
<div class="container-fluid">
    <h3 class="mb-4 text-center">Data Kegiatan</h3>

     @if ($errors->any()) 
       <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Gagal menyimpan!</strong> Periksa input Anda di form.
             <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <button class="btn btn-success mb-3 btnTambah" data-bs-toggle="modal" data-bs-target="#modalKegiatan">
        <i class="bi bi-plus-circle"></i> Tambah Kegiatan
    </button>

    {{-- Form Pencarian --}}
    <div class="row mb-3"> <div class="col-md-5 ms-auto">
    <form action="{{ route('datakegiatan.index') }}" method="GET"> <div class="input-group">
        <input type="text" class="form-control" placeholder="Cari kode atau nama kegiatan..." name="search" value="{{ request('search') }}">
        <button class="btn btn-primary" type="submit"><i class="bi bi-search"></i> Cari</button>
    </div> </form> </div> </div>

    <div class="table-responsive">
        <table class="table table-striped table-bordered">
            <thead class="table-primary text-center align-middle">
                <tr>
                    <th>No.</th>
                    <th>Nama Kegiatan</th>
                    <th>Penanggung Jawab</th>
                    <th>Fungsi</th>
                    <th>Jenis</th>
                    <th>Honor per Dokumen (Rp)</th>
                    <th>Periode</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody class="align-middle">
                @forelse ($kegiatans as $kegiatan)
                    <tr>
                        <td class="text-center">{{ $loop->iteration + $kegiatans->firstItem() - 1 }}</td>
                        <td>{{ $kegiatan->nama_kegiatan }}</td>
                        <td>{{ $kegiatan->penanggung_jawab }}</td> 
                        <td class="text-center">{{ $kegiatan->fungsi }}</td>
                        <td class="text-center">{{ $kegiatan->jenis_kegiatan }}</td>
                        {{-- Menampilkan honor yang relevan --}}
                        <td class="text-end">
                            @if($kegiatan->jenis_kegiatan == 'Lapangan')
                                {{-- Tambahkan "Rp " di sini --}}
                                PML: Rp {{ number_format($kegiatan->honor_pml_per_dokumen ?? 0, 0, ',', '.') }}<br> 
                                PCL: Rp {{ number_format($kegiatan->honor_pcl_per_dokumen ?? 0, 0, ',', '.') }}
                            @elseif($kegiatan->jenis_kegiatan == 'Pengolahan')
                                {{-- Tambahkan "Rp " di sini --}}
                                Rp {{ number_format($kegiatan->honor_pengolahan_per_dokumen ?? 0, 0, ',', '.') }}
                            @else
                                -
                            @endif
                        </td>
                        <td class="text-center">{{ \Carbon\Carbon::parse($kegiatan->tgl_mulai)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($kegiatan->tgl_selesai)->format('d/m/Y') }}</td>
                        <td class="text-center">
                            <button class="btn btn-warning btn-sm btnEdit"
                                    data-bs-toggle="modal" data-bs-target="#modalKegiatan"
                                    data-id="{{ $kegiatan->id }}"
                                    data-kode="{{ $kegiatan->kode_kegiatan }}"
                                    data-nama="{{ $kegiatan->nama_kegiatan }}"
                                    data-penanggung="{{ $kegiatan->penanggung_jawab }}"
                                    data-tim="{{ $kegiatan->nama_tim }}"
                                    data-target="{{ $kegiatan->target_dokumen }}"
                                    data-fungsi="{{ $kegiatan->fungsi }}"
                                    data-jenis="{{ $kegiatan->jenis_kegiatan }}"
                                    data-mulai="{{ $kegiatan->tgl_mulai }}"
                                    data-selesai="{{ $kegiatan->tgl_selesai }}"
                                    {{-- Tambah data honor ke tombol --}}
                                    data-honor_pml="{{ $kegiatan->honor_pml_per_dokumen }}"
                                    data-honor_pcl="{{ $kegiatan->honor_pcl_per_dokumen }}"
                                    data-honor_pengolahan="{{ $kegiatan->honor_pengolahan_per_dokumen }}">
                                <i class="bi bi-pencil"></i>
                            </button>
                            <form action="{{ route('datakegiatan.destroy', $kegiatan->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus data ini?');">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr> <td colspan="8" class="text-center">Data kegiatan tidak ditemukan.</td> </tr> {{-- Colspan diubah --}}
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="d-flex justify-content-end mt-3"> {{ $kegiatans->links() }} </div>
</div>

@include('datakegiatan.modal')
@endsection

@push('scripts')
{{-- Script untuk mengisi form edit (Biarkan yang ini) --}}
<script>
document.addEventListener("DOMContentLoaded", function () {
    const modalKegiatanElement = document.getElementById('modalKegiatan'); // Ambil elemen modal
    const modal = new bootstrap.Modal(modalKegiatanElement); // Inisiasi modal Bootstrap
    const modalTitle = document.getElementById("modalTitle");
    const form = document.getElementById("formKegiatan");
    const formMethodInput = document.getElementById("formMethod");
    const kegiatanIdInput = document.getElementById("kegiatanId");
    const jenisKegiatanSelect = document.getElementById('jenisKegiatan');
    const inputHonorPML = document.getElementById('honor_pml_per_dokumen');
    const inputHonorPCL = document.getElementById('honor_pcl_per_dokumen');
    const inputHonorPengolahan = document.getElementById('honor_pengolahan_per_dokumen');
    const targetDokumenInput = document.getElementById('targetDokumen');

    // Event saat tombol TAMBAH di-klik
    document.querySelector(".btnTambah").addEventListener("click", function () {
        modalTitle.textContent = "Tambah Data Kegiatan";
        form.reset();
        kegiatanIdInput.value = "";
        form.action = "{{ route('datakegiatan.store') }}";
        formMethodInput.value = "POST";
        // Panggil fungsi toggle SAAT modal akan tampil (lihat di bawah)
    });

    // Event saat tombol EDIT di-klik
    document.querySelectorAll(".btnEdit").forEach(btn => {
        btn.addEventListener("click", function () {
            modalTitle.textContent = "Edit Data Kegiatan";
            form.reset();

            const kegiatanId = this.dataset.id;
            const updateUrl = `/datakegiatan/${kegiatanId}`;
            form.action = updateUrl;
            formMethodInput.value = "PUT";

            // Isi semua field dari data-*
            kegiatanIdInput.value = kegiatanId;
            document.getElementById("kodeKegiatan").value = this.dataset.kode ?? '';
            document.getElementById("namaKegiatan").value = this.dataset.nama ?? '';
            document.getElementById("penanggungJawab").value = this.dataset.penanggung ?? '';
            document.getElementById("namaTim").value = this.dataset.tim ?? '';
            if(targetDokumenInput) targetDokumenInput.value = this.dataset.target ?? '';
            document.getElementById("fungsi").value = this.dataset.fungsi ?? '';
            if(jenisKegiatanSelect) jenisKegiatanSelect.value = this.dataset.jenis ?? '';
            document.getElementById("tanggalMulai").value = this.dataset.mulai ?? '';
            document.getElementById("tanggalSelesai").value = this.dataset.selesai ?? '';
            if(inputHonorPML) inputHonorPML.value = this.dataset.honor_pml ?? '';
            if(inputHonorPCL) inputHonorPCL.value = this.dataset.honor_pcl ?? '';
            if(inputHonorPengolahan) inputHonorPengolahan.value = this.dataset.honor_pengolahan ?? '';

            // Panggil fungsi toggle SAAT modal akan tampil (lihat di bawah)
        });
    });

    // --- FUNGSI BARU UNTUK TOGGLE HONOR ---
    const honorLapanganDiv = document.getElementById('honorLapanganFields');
    const honorPengolahanDiv = document.getElementById('honorPengolahanFields');

    function toggleHonorFields() {
        if (!jenisKegiatanSelect || !honorLapanganDiv || !honorPengolahanDiv || !inputHonorPML || !inputHonorPCL || !inputHonorPengolahan) {
            console.error("Satu atau lebih elemen form honor tidak ditemukan saat toggle!");
            return;
        }
        const selectedJenis = jenisKegiatanSelect.value;

        honorLapanganDiv.style.display = 'none';
        honorPengolahanDiv.style.display = 'none';
        inputHonorPML.required = false;
        inputHonorPCL.required = false;
        inputHonorPengolahan.required = false;

        if (selectedJenis === 'Lapangan') {
            honorLapanganDiv.style.display = 'block';
            inputHonorPML.required = true;
            inputHonorPCL.required = true;
            inputHonorPengolahan.value = '';
        } else if (selectedJenis === 'Pengolahan') {
            honorPengolahanDiv.style.display = 'block';
            inputHonorPengolahan.required = true;
            inputHonorPML.value = '';
            inputHonorPCL.value = '';
        }
    }

    // --- EVENT LISTENER BARU UNTUK MODAL ---
    // Jalankan toggleHonorFields SETIAP KALI modal SELESAI ditampilkan
    modalKegiatanElement.addEventListener('shown.bs.modal', function () {
        console.log('Modal shown, running toggleHonorFields...'); // Pesan debug
        toggleHonorFields(); // Jalankan toggle saat modal sudah tampil
    });

    // Jalankan toggleHonorFields saat pilihan jenis kegiatan berubah DI DALAM modal
    if(jenisKegiatanSelect){
        jenisKegiatanSelect.addEventListener('change', toggleHonorFields);
    }

});
</script>
@endpush