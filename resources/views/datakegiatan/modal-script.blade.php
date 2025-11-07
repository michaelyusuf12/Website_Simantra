document.addEventListener('DOMContentLoaded', function () {
    // Pastikan ID ini SAMA PERSIS dengan di modal.blade.php
    const jenisKegiatanSelect = document.getElementById('jenisKegiatan');
    const honorLapanganDiv = document.getElementById('honorLapanganFields');
    const honorPengolahanDiv = document.getElementById('honorPengolahanFields');
    const inputHonorPML = document.getElementById('honor_pml_per_dokumen');
    const inputHonorPCL = document.getElementById('honor_pcl_per_dokumen');
    const inputHonorPengolahan = document.getElementById('honor_pengolahan_per_dokumen');

    function toggleHonorFields() {
        // Cek dulu apakah elemennya ada
        if (!jenisKegiatanSelect || !honorLapanganDiv || !honorPengolahanDiv || !inputHonorPML || !inputHonorPCL || !inputHonorPengolahan) {
            console.error("Satu atau lebih elemen form honor tidak ditemukan! Periksa ID di modal.blade.php.");
            return; // Hentikan jika ada elemen yang hilang
        }

        const selectedJenis = jenisKegiatanSelect.value;

        // Sembunyikan semua dulu
        honorLapanganDiv.style.display = 'none';
        honorPengolahanDiv.style.display = 'none';

        // Nonaktifkan requirement
        inputHonorPML.required = false;
        inputHonorPCL.required = false;
        inputHonorPengolahan.required = false;

        // Tampilkan yang sesuai & aktifkan requirement
        if (selectedJenis === 'Lapangan') {
            honorLapanganDiv.style.display = 'block';
            inputHonorPML.required = true;
            inputHonorPCL.required = true;
            inputHonorPengolahan.value = ''; // Kosongkan nilai yang tidak relevan
        } else if (selectedJenis === 'Pengolahan') {
            honorPengolahanDiv.style.display = 'block';
            inputHonorPengolahan.required = true;
            inputHonorPML.value = ''; // Kosongkan nilai yang tidak relevan
            inputHonorPCL.value = ''; // Kosongkan nilai yang tidak relevan
        }
    }

    // Panggil saat halaman dimuat (untuk handle old input atau edit)
    toggleHonorFields();

    // Panggil saat pilihan berubah
    jenisKegiatanSelect.addEventListener('change', toggleHonorFields);
});