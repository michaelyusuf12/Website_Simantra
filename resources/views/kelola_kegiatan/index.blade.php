@extends('layouts.master')
@section('title', 'Kelola Kegiatan')

@section('content') {{-- SECTION KONTEN DIMULAI --}}
<div class="container-fluid">
    <h3 class="mb-4 text-center">Kelola Kegiatan Mitra</h3>

    {{-- Kotak Info Batas Honor --}}
    <div class="card mb-3 bg-light border-primary">
      <div class="card-body">
        <h5 class="card-title text-primary"><i class="bi bi-info-circle-fill"></i> Informasi Batas Honor Bulanan - Tahun {{ $currentYear }}</h5>
        <div class="row">
            <div class="col-md-6">
                <p class="card-text mb-1">
                  <strong><i class="bi bi-person-walking"></i> Lapangan :</strong>
                  @if($batasLapangan !== null)
                    <span class="fw-bold text-success">Rp {{ number_format($batasLapangan, 0, ',', '.') }}</span>
                  @else
                    <span class="text-danger fw-bold">Belum diatur</span>
                  @endif
                </p>
            </div>
            <div class="col-md-6">
                 <p class="card-text">
                  <strong><i class="bi bi-pc-display"></i> Pengolahan :</strong>
                  @if($batasPengolahan !== null)
                    <span class="fw-bold text-success">Rp {{ number_format($batasPengolahan, 0, ',', '.') }}</span>
                  @else
                    <span class="text-danger fw-bold">Belum diatur</span>
                  @endif
                </p>
            </div>
        </div>
        <a href="{{ route('settings.index') }}" class="btn btn-sm btn-outline-primary mt-2">
            <i class="bi bi-gear-fill"></i> Atur Batas Honor
        </a>
      </div>
    </div>

    <button class="btn btn-success mb-3 btnTambah" data-bs-toggle="modal" data-bs-target="#modalKelolaKegiatan">
        <i class="bi bi-plus-circle"></i> Tambah Penugasan
    </button>

    {{-- Form Pencarian --}}
    <div class="row mb-3">
        <div class="col-md-5 ms-auto">
            <form action="{{ route('kelolakegiatan.index') }}" method="GET">
                <div class="input-group">
                    <input type="text" class="form-control" placeholder="Cari berdasarkan nama mitra atau kegiatan..." name="search" value="{{ request('search') }}">
                    <button class="btn btn-primary" type="submit"><i class="bi bi-search"></i> Cari</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Tabel --}}
    <div class="table-responsive">
        <table class="table table-striped table-bordered">
            <thead class="table-primary text-center align-middle">
                <tr>
                    <th>No</th>
                    <th>Nama Mitra</th>
                    <th>Nama Kegiatan</th>
                    <th>Bulan Kegiatan</th>
                    <th>Total Honor</th>
                    <th>Jumlah Dokumen</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody class="align-middle">
                @forelse($penugasans as $row)
                    <tr>
                        <td class="text-center">{{ $loop->iteration + $penugasans->firstItem() - 1 }}</td>
                        <td>{{ $row->mitra->nama_petugas ?? 'N/A' }}</td>
                        <td>{{ $row->kegiatan->nama_kegiatan ?? 'N/A' }}</td>
                        <td class="text-center">{{ $row->bulan_kegiatan }}</td>
                        <td class="text-end">Rp {{ number_format($row->honor, 0, ',', '.') }}</td>
                        <td class="text-center">{{ $row->jumlah_dokumen }}</td>
                        <td class="text-center">
                            {{-- Tombol Edit --}}
                            <button class="btn btn-warning btn-sm btnEdit"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modalKelolaKegiatan"
                                    data-id="{{ $row->id }}" {{-- Anda masih perlu ID penugasan untuk URL update --}}
                                    data-mitra_id="{{ $row->mitra_id }}" {{-- Ini berisi sobat_id --}}
                                    data-kegiatan_id="{{ $row->kegiatan_id }}"
                                    data-bulan="{{ $row->bulan_kegiatan }}"
                                    data-dokumen="{{ $row->jumlah_dokumen }}"
                                    data-peran="{{ $row->peran_petugas }}">
                                <i class="bi bi-pencil"></i>
                            </button>
                            {{-- Tombol Hapus --}}
                            <form action="{{ route('kelolakegiatan.destroy', $row->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?');">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="text-center">Belum ada data penugasan.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="d-flex justify-content-end mt-3">
        {{ $penugasans->links() }}
    </div>
</div>
@endsection {{-- SECTION KONTEN BERAKHIR --}}

{{-- Panggil modal di luar section content --}}
@include('kelola_kegiatan.modal')

@push('scripts')
{{-- JavaScript untuk modal Kelola Kegiatan --}}
<script>
document.addEventListener("DOMContentLoaded", function () {
    const modalElement = document.getElementById('modalKelolaKegiatan');
    const modal = new bootstrap.Modal(modalElement);
    const modalTitle = document.getElementById("modalTitle");
    const form = document.getElementById("formKelolaKegiatan");
    const formMethodInput = document.getElementById("formMethod");
    const penugasanIdInput = document.getElementById("penugasanId"); // Input hidden untuk ID penugasan

    const kegiatanSelect = document.getElementById('idKegiatan');
    const peranSelect = document.getElementById('peranPetugas');
    const peranOptions = peranSelect.querySelectorAll('option');

    // Fungsi filterPeranOptions (lengkap)
    function filterPeranOptions() {
        const selectedOption = kegiatanSelect.options[kegiatanSelect.selectedIndex];
        const jenisKegiatan = selectedOption ? selectedOption.getAttribute('data-jenis') : '';
        const currentPeranValue = peranSelect.value;
        let isCurrentValueStillValid = false;
        peranOptions.forEach(option => {
            if (option.value === '') { option.disabled = false; option.style.display = ''; return; }
            let shouldBeEnabled = false;
            if (jenisKegiatan === 'Lapangan' && (option.value === 'PML' || option.value === 'PCL')) { shouldBeEnabled = true; }
            else if (jenisKegiatan === 'Pengolahan' && option.value === 'Petugas') { shouldBeEnabled = true; }
            option.disabled = !shouldBeEnabled; option.style.display = '';
            if (option.value === currentPeranValue && shouldBeEnabled) { isCurrentValueStillValid = true; }
        });
        if (!isCurrentValueStillValid) { peranSelect.value = ''; }
        else { peranSelect.value = currentPeranValue; }
    }

    // Event tombol Tambah (lengkap)
    document.querySelector(".btnTambah").addEventListener("click", function () {
        modalTitle.textContent = "Tambah Penugasan";
        form.reset();
        penugasanIdInput.value = ""; // Kosongkan ID penugasan
        form.action = "{{ route('kelolakegiatan.store') }}";
        formMethodInput.value = "POST";
        filterPeranOptions();
        form.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
        form.querySelectorAll('.invalid-feedback').forEach(el => el.textContent = '');
        const generalErrorAlert = document.getElementById('general-error-alert');
        if (generalErrorAlert) generalErrorAlert.style.display = 'none';
    });

    // Event tombol Edit (lengkap)
    document.querySelectorAll(".btnEdit").forEach(btn => {
        btn.addEventListener("click", function () {
            modalTitle.textContent = "Edit Penugasan";
            const penugasanId = this.dataset.id; // Ambil ID penugasan dari tombol
            // Buat URL update dengan ID penugasan (PK tabel penugasans)
            const updateUrl = `{{ url('kelolakegiatan') }}/${penugasanId}`; 
            form.action = updateUrl;
            formMethodInput.value = "PUT";
            penugasanIdInput.value = penugasanId; // Isi ID penugasan ke input hidden
            document.getElementById("idMitra").value = this.dataset.mitra_id ?? ''; // mitra_id berisi sobat_id
            document.getElementById("idKegiatan").value = this.dataset.kegiatan_id ?? '';
            document.getElementById("jumlahDokumen").value = this.dataset.dokumen ?? '';
            document.getElementById("bulanKegiatan").value = this.dataset.bulan ?? '';
            document.getElementById("peranPetugas").value = this.dataset.peran ?? '';
            filterPeranOptions();
            document.getElementById("peranPetugas").value = this.dataset.peran ?? '';
            form.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
            form.querySelectorAll('.invalid-feedback').forEach(el => el.textContent = '');
            const generalErrorAlert = document.getElementById('general-error-alert');
            if (generalErrorAlert) generalErrorAlert.style.display = 'none';
        });
    });

    // Event listener 'change' untuk filter (lengkap)
    kegiatanSelect.addEventListener('change', filterPeranOptions);
    filterPeranOptions(); // Panggil saat load

    // AJAX Submit Handler (tanpa loading spinner)
    form.addEventListener('submit', function(event) {
        event.preventDefault(); // Mencegah reload

        // Hapus error lama
        form.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
        form.querySelectorAll('.invalid-feedback').forEach(el => el.textContent = '');
        const generalErrorAlert = document.getElementById('general-error-alert');
        if (generalErrorAlert) generalErrorAlert.style.display = 'none';

        const formData = new FormData(form);
        const url = form.action;
        const method = formMethodInput.value === 'PUT' ? 'POST' : 'POST';
        // Penting: _method sudah dihandle oleh input hidden, tidak perlu append lagi
        // if(formMethodInput.value === 'PUT') { formData.append('_method', 'PUT'); } 

        fetch(url, {
            method: method,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
                // Jangan set Content-Type, biarkan FormData menentukannya
            },
            body: formData
        })
        .then(response => {
            if (response.status === 422) { // Unprocessable Content (Validation Error)
                return response.json().then(data => {
                    // Cek jika ada errors property
                    if (data && data.errors) {
                         throw { status: response.status, errors: data.errors, message: data.message };
                    } else {
                        // Jika tidak ada 'errors', mungkin error umum dari controller
                        throw { status: response.status, message: data.message || 'Input tidak valid.' };
                    }
                });
            }
            if (!response.ok) { // Error server lainnya
                 return response.json().then(data => { throw { status: response.status, message: data.message || 'Terjadi kesalahan server.' }; });
             }
            return response.json(); // Jika sukses
        })
        .then(data => { // Jika Sukses
            modal.hide();
            window.location.reload(); // Reload untuk lihat data baru & pesan sukses
        })
        .catch(errorInfo => { // Jika Gagal (Error Validasi atau Server)
            console.error('AJAX Error:', errorInfo);

            // Tampilkan error validasi per field
            if (errorInfo.status === 422 && errorInfo.errors) {
                Object.keys(errorInfo.errors).forEach(field => {
                    const inputElement = form.querySelector(`[name="${field}"]`);
                    // Cari elemen SIBLING berikutnya yang punya kelas .invalid-feedback
                    const errorElement = inputElement ? inputElement.nextElementSibling : null;

                    if (inputElement) {
                        inputElement.classList.add('is-invalid');
                    }
                    // Pastikan errorElement ditemukan DAN punya kelas yg benar
                    if (errorElement && errorElement.classList.contains('invalid-feedback') && errorInfo.errors[field][0]) {
                        errorElement.textContent = errorInfo.errors[field][0];
                    }
                    // Fallback ke general error jika div spesifik tidak ketemu
                    else if (generalErrorAlert && errorInfo.errors[field][0]) {
                         generalErrorAlert.textContent += errorInfo.errors[field][0] + ' ';
                         generalErrorAlert.style.display = 'block';
                    }
                });
                // Tampilkan error umum (seperti batas honor) jika dikirim via 'message' atau 'errors.batas_honor'
                if (generalErrorAlert && (errorInfo.message || (errorInfo.errors && errorInfo.errors.batas_honor))) {
                   generalErrorAlert.textContent = errorInfo.message || errorInfo.errors.batas_honor[0];
                   generalErrorAlert.style.display = 'block';
                }
            } else { // Error Server (500, 404, dll) atau error umum dari 422
                 if (generalErrorAlert) {
                    generalErrorAlert.textContent = errorInfo.message || 'Gagal menyimpan data karena kesalahan server. Silakan coba lagi.';
                    generalErrorAlert.style.display = 'block';
                 } else { // Fallback jika div alert tidak ada
                    alert(errorInfo.message || 'Gagal menyimpan data karena kesalahan server. Silakan coba lagi.');
                 }
            }
        });
    });

});
</script>
@endpush