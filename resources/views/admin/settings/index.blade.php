@extends('layouts.master')
@section('title', 'Pengaturan Batas Honor')

@section('content')
<div class="container-fluid">

    {{-- Header dengan Pilihan Tahun --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="mb-0">Pengaturan Batas Honor</h3>
        <form action="{{ route('settings.index') }}" method="GET" id="filterForm">
             <div class="input-group input-group-sm">
                <span class="input-group-text">Tahun:</span>
                <select class="form-select" name="year" onchange="this.form.submit()">
                    @foreach($availableYears as $year)
                        <option value="{{ $year }}" {{ $selectedYear == $year ? 'selected' : '' }}>{{ $year }}</option>
                    @endforeach
                </select>
            </div>
        </form>
    </div>

    {{-- Cek apakah tahun yang dipilih adalah masa lalu --}}
    @php
        $isReadOnly = $selectedYear < $currentYear;
        $readOnlyAttr = $isReadOnly ? 'disabled readonly' : '';
    @endphp

    {{-- Form Utama untuk Simpan --}}
    <div class="card shadow-sm mt-4">
         <div class="card-header text-center">
            <h5>Edit Batas Honor Bulanan - Tahun {{ $selectedYear }}</h5>
            @if($isReadOnly)
                <span class="badge bg-danger">Data Riwayat (Read-Only)</span>
            @endif
        </div>
        <div class="card-body">
            <form action="{{ route('settings.update') }}" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" name="tahun" value="{{ $selectedYear }}">

            {{-- Dasar Aturan / SK --}}
                <div class="mb-4 row align-items-center">
                    <label for="dasar_aturan" class="col-sm-3 col-form-label fw-semibold">
                        <i class="bi bi-file-earmark-text text-secondary me-1"></i> Dasar Aturan (SK)
                    </label>
                    <div class="col-sm-8"> <!-- Diperlebar ke col-sm-8 -->
                        <input type="text" class="form-control {{ $isReadOnly ? 'bg-light' : '' }}" 
                               id="dasar_aturan" name="dasar_aturan" 
                               value="{{ old('dasar_aturan', optional($settingLapangan)->dasar_aturan) }}"
                               placeholder="Contoh: SK KPA No. 123/BPS/2026" {{ $readOnlyAttr }}>
                    </div>
                </div>
                <hr>

                {{-- Batas Honor Lapangan --}}
                <div class="mb-4 row">
                    <label for="batas_honor_lapangan" class="col-sm-3 col-form-label fw-semibold">
                        <i class="bi bi-cash-stack text-secondary me-1"></i> Honor Lapangan
                    </label>
                    <div class="col-sm-8">
                        {{-- Menggunakan Input Group untuk Rp --}}
                        <div class="input-group">
                            <span class="input-group-text bg-light fw-semibold">Rp</span>
                            <input type="text" class="form-control input-rupiah @error('batas_honor_lapangan') is-invalid @enderror {{ $isReadOnly ? 'bg-light' : '' }}"
                                   id="batas_honor_lapangan" name="batas_honor_lapangan"
                                   value="{{ old('batas_honor_lapangan', optional($settingLapangan)->batas_honor ? number_format($settingLapangan->batas_honor, 0, ',', '.') : '') }}"
                                   {{ $readOnlyAttr }} required>
                        </div>
                        @error('batas_honor_lapangan') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                        
                        <small class="form-text text-muted d-block mb-1 mt-1">Batas honor bulanan yang berlaku sepanjang tahun {{ $selectedYear }} untuk posisi Lapangan (Kode: 1).</small>
                        <small class="text-primary fw-bold terbilang-text" id="terbilang_lapangan"></small>
                    </div>
                </div>

                {{-- Batas Honor Pengolahan --}}
                <div class="mb-4 row">
                    <label for="batas_honor_pengolahan" class="col-sm-3 col-form-label fw-semibold">
                        <i class="bi bi-cash-stack text-secondary me-1"></i> Honor Pengolahan
                    </label>
                    <div class="col-sm-8">
                        {{-- Menggunakan Input Group untuk Rp --}}
                        <div class="input-group">
                            <span class="input-group-text bg-light fw-semibold">Rp</span>
                            <input type="text" class="form-control input-rupiah @error('batas_honor_pengolahan') is-invalid @enderror {{ $isReadOnly ? 'bg-light' : '' }}"
                                   id="batas_honor_pengolahan" name="batas_honor_pengolahan"
                                    value="{{ old('batas_honor_pengolahan', optional($settingPengolahan)->batas_honor ? number_format($settingPengolahan->batas_honor, 0, ',', '.') : '') }}"
                                    {{ $readOnlyAttr }} required>
                        </div>
                         @error('batas_honor_pengolahan') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                         
                         <small class="form-text text-muted d-block mb-1 mt-1">Batas honor bulanan yang berlaku sepanjang tahun {{ $selectedYear }} untuk posisi Pengolahan (Kode: 2).</small>
                         <small class="text-primary fw-bold terbilang-text" id="terbilang_pengolahan"></small>
                    </div>
                </div>

                <hr>
                
                {{-- Audit Trail (Terakhir Diperbarui) --}}
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div class="text-muted small">
                        {{-- PERBAIKAN: Menambahkan optional() agar tidak error saat tahun baru dipilih --}}
                        @if(optional($settingLapangan)->updated_at && optional($settingLapangan)->updated_by)
                            <i class="bi bi-info-circle me-1"></i> Terakhir diperbarui pada: <strong>{{ \Carbon\Carbon::parse($settingLapangan->updated_at)->translatedFormat('d F Y H:i') }}</strong> oleh <strong>{{ $settingLapangan->updated_by }}</strong>                        
                        @else
                            <i class="bi bi-info-circle me-1"></i> Belum ada riwayat pembaruan untuk tahun ini.
                        @endif
                    </div>
                    
                    {{-- Tombol simpan menghilang jika read-only --}}
                    @if(!$isReadOnly)
                        <button type="submit" class="btn btn-primary px-4">Simpan Pengaturan Tahun {{ $selectedYear }}</button>
                    @endif
                </div>
            </form>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
document.addEventListener("DOMContentLoaded", function () {
    
    // Fungsi mengubah angka menjadi Terbilang
    function penyebut(nilai) {
        nilai = Math.floor(Math.abs(nilai));
        var huruf = ["", "Satu", "Dua", "Tiga", "Empat", "Lima", "Enam", "Tujuh", "Delapan", "Sembilan", "Sepuluh", "Sebelas"];
        var temp = "";
        if (nilai < 12) {
            temp = " " + huruf[nilai];
        } else if (nilai < 20) {
            temp = penyebut(nilai - 10) + " Belas";
        } else if (nilai < 100) {
            temp = penyebut(nilai / 10) + " Puluh" + penyebut(nilai % 10);
        } else if (nilai < 200) {
            temp = " Seratus" + penyebut(nilai - 100);
        } else if (nilai < 1000) {
            temp = penyebut(nilai / 100) + " Ratus" + penyebut(nilai % 100);
        } else if (nilai < 2000) {
            temp = " Seribu" + penyebut(nilai - 1000);
        } else if (nilai < 1000000) {
            temp = penyebut(nilai / 1000) + " Ribu" + penyebut(nilai % 1000);
        } else if (nilai < 1000000000) {
            temp = penyebut(nilai / 1000000) + " Juta" + penyebut(nilai % 1000000);
        }
        return temp;
    }

    function terbilang(nilai) {
        if(nilai === 0 || nilai === "") return "";
        let hasil = penyebut(nilai);
        return "Terbilang: " + hasil.trim() + " Rupiah";
    }

    // Terapkan ke semua input yang memiliki class 'input-rupiah'
    const rupiahInputs = document.querySelectorAll('.input-rupiah');
    
    rupiahInputs.forEach(function(input) {
        const targetTerbilang = input.closest('.col-sm-8').querySelector('.terbilang-text');

        let rawValue = input.value.replace(/[^0-9]/g, '');
        if(targetTerbilang && rawValue) {
            targetTerbilang.textContent = terbilang(parseInt(rawValue));
        }

        // Format saat pengguna mengetik
        input.addEventListener('keyup', function(e) {
            // Bersihkan semua karakter selain angka
            let val = this.value.replace(/[^0-9]/g, '');
            
            // Tulis ulang dengan format ribuan
            this.value = val.replace(/\B(?=(\d{3})+(?!\d))/g, ".");
            
            // Update teks terbilang
            if(targetTerbilang) {
                targetTerbilang.textContent = val ? terbilang(parseInt(val)) : '';
            }
        });
    });

    // Menutup alert otomatis (dari sesi sebelumnya)
    setTimeout(function() {
        var alertBoxes = document.querySelectorAll('.alert');
        alertBoxes.forEach(function(alertBox) {
            var bsAlert = new bootstrap.Alert(alertBox);
            bsAlert.close();
        });
    }, 5000);
});
</script>
@endpush