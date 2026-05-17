document.addEventListener("DOMContentLoaded", function () {
    // VARIABEL FORM TAMBAH/EDIT
    const selectMitra = document.getElementById('idMitra');
    const selectBulan = document.getElementById('bulanKegiatan');
    const infoBatas = document.getElementById('infoBatas');
    const btnSimpan = document.getElementById('btnSimpan');
    const statusHonorText = document.getElementById('statusPagu');
    const tableBody = document.getElementById('tbodyRincian');
    const btnAddRow = document.getElementById('btnAddRow');
    const displayTotalForm = document.getElementById('displayTotal');
    const form = document.getElementById('formKelolaKegiatan');

    let honorDiDatabase = 0;
    let limitSaatIni = 3258000;

    // Menyimpan template row kosong dan option kegiatan
    let emptyRowTemplate = '';
    let kegiatanOptionsHTML = '';
    if(tableBody && tableBody.querySelector('.row-kegiatan')) {
        emptyRowTemplate = tableBody.querySelector('.row-kegiatan').outerHTML;
        kegiatanOptionsHTML = tableBody.querySelector('.select-kegiatan').innerHTML;
    }

   // --- FUNGSI CEK RIWAYAT DATABASE VIA AJAX ---
    function fetchAkumulasi() {
        if(!selectMitra || !selectBulan) return;
        const mitraId = selectMitra.value;
        const bulan = selectBulan.value;
        
        const elementEditId = document.getElementById('editPenugasanId');
        const editId = elementEditId ? elementEditId.value : ''; 

        if (mitraId && bulan) {
            infoBatas.innerHTML = 'Mengecek riwayat honor...';
            
            // PERHATIKAN: Membaca URL dari variabel global window.AppRoutes
            fetch(`${window.AppRoutes.cekAkumulasi}?mitra_id=${mitraId}&bulan=${bulan}`)
                .then(res => {
                    if (!res.ok) {
                        return res.json().then(err => { throw new Error(err.message || 'Terjadi kesalahan di server'); });
                    }
                    return res.json();
                })
                .then(data => {
                    if(data.status === 'success') {
                        honorDiDatabase = parseInt(data.akumulasi) || 0;
                        limitSaatIni = parseInt(data.limit_maksimal) || 3258000; 

                        infoBatas.innerHTML = `Akumulasi Tersimpan: <b>Rp ${honorDiDatabase.toLocaleString('id-ID')}</b> | Batas Maks: <b>Rp ${limitSaatIni.toLocaleString('id-ID')}</b>`;
                        calculateGrandTotal(); 
                    } else {
                        throw new Error(data.message);
                    }
                })
                .catch(err => {
                    console.error("AJAX Error: ", err);
                    infoBatas.innerHTML = `Gagal: ${err.message}`;
                });
        }
    }

    // --- FUNGSI HITUNG TOTAL DAN VALIDASI BATAS MAKSIMAL ---
    function calculateGrandTotal() {
        let totalDiForm = 0;
        document.querySelectorAll('.input-subtotal').forEach(input => {
            let val = input.value.replace(/\./g, '') || 0;
            totalDiForm += parseInt(val);
        });

        const grandTotalKeseluruhan = totalDiForm + honorDiDatabase;
        const sisaLimit = limitSaatIni - grandTotalKeseluruhan;

        if(displayTotalForm) displayTotalForm.innerText = 'Rp ' + totalDiForm.toLocaleString('id-ID');

        if (grandTotalKeseluruhan > limitSaatIni) {
            if(statusHonorText) {
                statusHonorText.innerText = `Melebihi Batas! (Total Kumulatif: Rp ${grandTotalKeseluruhan.toLocaleString('id-ID')})`;
                statusHonorText.className = "text-danger fw-bold";
            }
            if(btnSimpan) {
                btnSimpan.disabled = true;
                btnSimpan.style.opacity = "0.5";
                btnSimpan.style.cursor = "not-allowed";
            }
        } else {
            if(statusHonorText) {
                statusHonorText.innerText = `Aman (Tersisa Rp ${sisaLimit.toLocaleString('id-ID')})`;
                statusHonorText.className = "text-success fw-bold";
            }
            if(btnSimpan) {
                btnSimpan.disabled = false;
                btnSimpan.style.opacity = "1";
                btnSimpan.style.cursor = "pointer";
            }
        }
    }

    // --- FUNGSI HITUNG HARGA PER BARIS DENGAN VALIDASI LAPANGAN VS PENGOLAHAN ---
    function calculateRow(row) {
        const selectKegiatan = row.querySelector('.select-kegiatan');
        const selectPeran = row.querySelector('.select-peran');
        const inputVolume = row.querySelector('.input-volume');
        const inputHarga = row.querySelector('.input-harga');
        const inputSubtotal = row.querySelector('.input-subtotal');
        
        const optMitra = selectMitra.options[selectMitra.selectedIndex];
        const optKegiatan = selectKegiatan.options[selectKegiatan.selectedIndex];
        
        let harga = 0;
        
        if (optKegiatan && optKegiatan.value !== "" && optMitra && optMitra.value !== "") {
            
            const peran = selectPeran.value;
            const jenisKegiatan = optKegiatan.getAttribute('data-jenis'); // "Lapangan" atau "Pengolahan"
            const posisiMitra = optMitra.getAttribute('data-posisi'); // "1", "2", atau "3"
            
            // 🚨 VALIDASI BUSINESS LOGIC BPS 🚨
            let bolehMengerjakan = true;
            let pesanError = "";

            if (posisiMitra == '1' && jenisKegiatan !== 'Lapangan') {
                bolehMengerjakan = false;
                pesanError = "Mitra ini adalah Petugas Lapangan, tidak bisa memilih kegiatan Pengolahan.";
            } else if (posisiMitra == '2' && jenisKegiatan !== 'Pengolahan') {
                bolehMengerjakan = false;
                pesanError = "Mitra ini adalah Petugas Pengolahan, tidak bisa memilih kegiatan Lapangan.";
            } 

            // EKSEKUSI PENJEGALAN
            if (!bolehMengerjakan) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Tidak Sesuai Aturan',
                    text: pesanError
                });
                
                selectKegiatan.value = ""; 
                inputVolume.value = "0";
                inputHarga.value = "0";
                inputSubtotal.value = "0";
                calculateGrandTotal();
                return; 
            }

            // LANJUT HITUNG HARGA
            if (peran === 'PCL') harga = optKegiatan.getAttribute('data-pcl') || 0;
            else if (peran === 'PML') harga = optKegiatan.getAttribute('data-pml') || 0;
            else if (peran === 'Pengolahan') harga = optKegiatan.getAttribute('data-pengolahan') || 0;
        }
        
        const vol = parseInt(inputVolume.value) || 0;
        const hargaSatuan = parseInt(harga) || 0;
        const subtotalHitung = hargaSatuan * vol;
        
        if(inputHarga) inputHarga.value = hargaSatuan.toLocaleString('id-ID');
        if(inputSubtotal) inputSubtotal.value = subtotalHitung.toLocaleString('id-ID');
        
        calculateGrandTotal();
    }

    // --- MENGHUBUNGKAN EVENT FORM ---
    if(selectMitra) selectMitra.addEventListener('change', fetchAkumulasi);
    if(selectBulan) selectBulan.addEventListener('change', fetchAkumulasi);

    if (btnAddRow) {
        btnAddRow.addEventListener('click', function () {
            tableBody.insertAdjacentHTML('beforeend', emptyRowTemplate);
            const newRow = tableBody.lastElementChild;
            
            newRow.querySelectorAll('input').forEach(i => {
                if(i.name === "satuan[]") {
                    i.value = "Dokumen"; 
                } else if(i.type === "date") {
                    i.value = "";
                } else {
                    i.value = "0";
                }
            });
            attachEvents(newRow);
        });
    }

    if(tableBody) {
        tableBody.addEventListener('click', (e) => {
            if (e.target.closest('.btn-hapus-baris')) {
                const rows = tableBody.querySelectorAll('.row-kegiatan');
                if (rows.length > 1) {
                    e.target.closest('.row-kegiatan').remove();
                    calculateGrandTotal();
                } else {
                    alert("Minimal harus ada satu kegiatan.");
                }
            }
        });
    }

    function attachEvents(row) {
        ['.select-kegiatan', '.select-peran', '.input-volume'].forEach(s => {
            const el = row.querySelector(s);
            if(el){
                el.addEventListener('change', () => calculateRow(row));
                el.addEventListener('input', () => calculateRow(row));
            }
        });
    }

    document.querySelectorAll('.row-kegiatan').forEach(attachEvents);

    // --- PENGIRIMAN FORM (SIMPAN DATA) ---
    if(form) {
        form.addEventListener('submit', function(e) {
            if(btnSimpan.disabled) {
                e.preventDefault();
                return false;
            }
            e.preventDefault();
            
            fetch(form.action, { 
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': window.AppRoutes.csrfToken, // Dari variabel global
                    'Accept': 'application/json'
                },
                body: new FormData(form)
            })
            .then(res => res.json())
            .then(data => {
                if(data.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: 'Penugasan berhasil disimpan!',
                        showConfirmButton: false,
                        timer: 1500
                    }).then(() => {
                        window.location.href = data.redirect;
                    });
                } else {
                    Swal.fire('Gagal!', data.message, 'error');
                }
            })
            .catch(err => {
                console.error("Submit Error:", err);
                Swal.fire('Error!', 'Terjadi kesalahan saat menyimpan data.', 'error');
            });
        });
    }

    // --- FUNGSI POP-UP HAPUS DATA (SWEETALERT2) ---
    const formHapus = document.querySelectorAll('.form-hapus-data');
    formHapus.forEach(f => {
        f.addEventListener('submit', function (e) {
            e.preventDefault(); 
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Semua rincian kegiatan pada SPK ini akan ikut terhapus secara permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: '<i class="bi bi-trash-fill"></i> Ya, Hapus Data!',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    f.submit(); 
                }
            });
        });
    });

    // --- 🚨 FUNGSI LIHAT DETAIL DATA (MODAL) ---
    const btnLihatDetail = document.querySelectorAll('.btn-lihat-detail');
    const elementModalDetail = document.getElementById('modalDetailPenugasan');
    
    if(elementModalDetail) {
        const modalDetail = new bootstrap.Modal(elementModalDetail);

        btnLihatDetail.forEach(btn => {
            btn.addEventListener('click', function () {
                const idPenugasan = this.getAttribute('data-id');
                const tbodyDetail = document.getElementById('tbodyDetailRincian');
                
                tbodyDetail.innerHTML = '<tr><td colspan="9" class="text-center text-muted py-4"><i class="bi bi-hourglass-split me-2"></i>Memuat data detail...</td></tr>';
                modalDetail.show();

                fetch(`/kelolakegiatan/${idPenugasan}/detail`)
                    .then(res => res.json())
                    .then(data => {
                        if (data.status === 'success') {
                            const p = data.data; 
                            
                            document.getElementById('detailNoSurat').innerText = p.no_surat || 'Belum ada nomor';
                            document.getElementById('detailNamaMitra').innerText = p.mitra ? p.mitra.nama_petugas : '-';
                            document.getElementById('detailBulan').innerText = p.bulan_kegiatan;
                            document.getElementById('detailStatus').innerText = p.status_kontrak || '-';
                            document.getElementById('detailTotalHonor').innerText = 'Rp ' + parseInt(p.total_nilai_perjanjian).toLocaleString('id-ID');
                            
                            const tgl = new Date(p.tanggal_surat);
                            document.getElementById('detailTanggalSurat').innerText = tgl.toLocaleDateString('id-ID', { day: '2-digit', month: 'long', year: 'numeric' });

                            tbodyDetail.innerHTML = ''; 
                            
                            if (p.details && p.details.length > 0) {
                                p.details.forEach((dt, index) => {
                                    const namaKegiatan = dt.kegiatan ? (dt.kegiatan.Nama_kegiatan || dt.kegiatan.nama_kegiatan || 'Relasi Belum Terhubung') : '-';
                                    const subtotal = dt.volume * dt.harga_satuan;
                                    
                                    const tglMulaiRaw = dt.tanggal_mulai ? dt.tanggal_mulai.split(' ')[0].split('-') : null;
                                    const tglSelesaiRaw = dt.tanggal_selesai ? dt.tanggal_selesai.split(' ')[0].split('-') : null;
                                    const tglMulaiIndo = tglMulaiRaw ? `${tglMulaiRaw[2]}-${tglMulaiRaw[1]}-${tglMulaiRaw[0]}` : '-';
                                    const tglSelesaiIndo = tglSelesaiRaw ? `${tglSelesaiRaw[2]}-${tglSelesaiRaw[1]}-${tglSelesaiRaw[0]}` : '-';

                                    const tr = document.createElement('tr');
                                    tr.innerHTML = `
                                        <td class="text-center">${index + 1}</td>
                                        <td>${namaKegiatan}</td>
                                        <td class="text-center">${dt.uraian_tugas}</td>
                                        <td class="text-center">${tglMulaiIndo}</td>
                                        <td class="text-center">${tglSelesaiIndo}</td>
                                        <td class="text-center">${dt.volume}</td>
                                        <td class="text-center">Dokumen</td>
                                        <td class="text-end text-secondary">${parseInt(dt.harga_satuan).toLocaleString('id-ID')}</td>
                                        <td class="text-end fw-bold text-dark">${subtotal.toLocaleString('id-ID')}</td>
                                    `;
                                    tbodyDetail.appendChild(tr);
                                });
                            } else {
                                tbodyDetail.innerHTML = '<tr><td colspan="9" class="text-center text-muted py-3">Tidak ada rincian kegiatan ditemukan.</td></tr>';
                            }
                        } else {
                            Swal.fire('Gagal', 'Data detail tidak ditemukan.', 'error');
                            modalDetail.hide();
                        }
                    })
                    .catch(err => {
                        console.error('AJAX Error:', err);
                        Swal.fire('Gagal', 'Terjadi kesalahan jaringan saat mengambil data detail.', 'error');
                        modalDetail.hide();
                    });
            });
        });
    }

    // --- 🚨 FUNGSI EDIT DATA PENUGASAN (MODAL) ---
    const btnEditPenugasan = document.querySelectorAll('.btn-edit-penugasan');
    const elementModalKelola = document.getElementById('modalKelolaKegiatan');
    let modalKelola = null;
    if(elementModalKelola) modalKelola = new bootstrap.Modal(elementModalKelola);

    btnEditPenugasan.forEach(btn => {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            const idPenugasan = this.getAttribute('data-id');
            if(document.getElementById('editPenugasanId')) {
                    document.getElementById('editPenugasanId').value = idPenugasan;
                }
            document.getElementById('modalTitle').innerHTML = '<i class="bi bi-hourglass-split me-2"></i> Memuat Data...';
            tableBody.innerHTML = '<tr><td colspan="9" class="text-center py-4"><i class="bi bi-hourglass-split me-2"></i>Mengambil data dari server...</td></tr>';
            modalKelola.show();

            fetch(`/kelolakegiatan/${idPenugasan}/detail`)
                .then(res => res.json())
                .then(data => {
                    if (data.status === 'success') {
                        const p = data.data;

                        document.getElementById('modalTitle').innerHTML = '<i class="bi bi-pencil-square me-2"></i> Edit Penugasan: ' + (p.no_surat || 'Tanpa Nomor');
                        document.getElementById('formMethod').value = 'PUT';
                        document.getElementById('formKelolaKegiatan').action = `/kelolakegiatan/${p.id_penugasan}`;
                        
                        document.getElementById('idMitra').value = p.mitra_id;
                        document.getElementById('bulanKegiatan').value = p.bulan_kegiatan;

                        fetchAkumulasi();

                        tableBody.innerHTML = ''; 

                        if (p.details && p.details.length > 0) {
                            p.details.forEach((dt, i) => {
                                const tglMulai = dt.tanggal_mulai ? dt.tanggal_mulai.split(' ')[0] : '';
                                const tglSelesai = dt.tanggal_selesai ? dt.tanggal_selesai.split(' ')[0] : '';

                                const htmlRow = `
                                    <tr class="row-kegiatan">
                                        <td>
                                            <select name="kegiatan_id[]" class="form-select form-select-sm select-kegiatan" required>
                                                ${kegiatanOptionsHTML}
                                            </select>
                                        </td>
                                        <td>
                                            <select name="peran[]" class="form-select form-select-sm select-peran" required>
                                                <option value="PCL" ${dt.uraian_tugas === 'PCL' ? 'selected' : ''}>PCL</option>
                                                <option value="PML" ${dt.uraian_tugas === 'PML' ? 'selected' : ''}>PML</option>
                                                <option value="Pengolahan" ${dt.uraian_tugas === 'Pengolahan' ? 'selected' : ''}>Pengolahan</option>
                                            </select>
                                        </td>
                                        <td><input type="date" name="tanggal_mulai[]" class="form-control form-control-sm" value="${tglMulai}" required></td>
                                        <td><input type="date" name="tanggal_selesai[]" class="form-control form-control-sm" value="${tglSelesai}" required></td>
                                        <td><input type="number" name="volume[]" class="form-control form-control-sm input-volume" value="${dt.volume}"></td>
                                        <td><input type="text" name="satuan[]" class="form-control form-control-sm input-satuan bg-light" value="Dokumen" readonly></td>
                                        <td><input type="text" class="form-control form-control-sm input-harga bg-light text-end" value="${parseInt(dt.harga_satuan).toLocaleString('id-ID')}" readonly></td>
                                        <td><input type="text" class="form-control form-control-sm input-subtotal bg-light text-end fw-bold" value="${(dt.volume * dt.harga_satuan).toLocaleString('id-ID')}" readonly></td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-sm btn-outline-danger btn-hapus-baris"><i class="bi bi-trash"></i></button>
                                        </td>
                                    </tr>
                                `;
                                tableBody.insertAdjacentHTML('beforeend', htmlRow);
                                
                                const lastRow = tableBody.lastElementChild;
                                lastRow.querySelector('.select-kegiatan').value = dt.id_kegiatan;
                                attachEvents(lastRow);
                            });
                        }
                        setTimeout(() => calculateGrandTotal(), 500);

                    } else {
                        Swal.fire('Gagal', 'Data tidak ditemukan.', 'error');
                        modalKelola.hide();
                    }
                })
                .catch(err => {
                    console.error('Fetch Error:', err);
                    Swal.fire('Error', 'Gagal memuat data form edit.', 'error');
                    modalKelola.hide();
                });
        });
    });

    // --- RESET FORM SAAT KLIK TAMBAH BARU ---
    const btnTambahMaster = document.querySelector('[data-bs-target="#modalKelolaKegiatan"]');
    if(btnTambahMaster && !btnTambahMaster.classList.contains('btn-edit-penugasan')) {
        btnTambahMaster.addEventListener('click', function() {
            document.getElementById('modalTitle').innerHTML = '<i class="bi bi-pencil-square me-2"></i> Form Penugasan Baru';
            form.reset();
            document.getElementById('formMethod').value = 'POST';
            
            // PERHATIKAN: Membaca rute dari variabel global
            form.action = window.AppRoutes.store;
            
            const editIdInput = document.getElementById('editPenugasanId');
            if(editIdInput) {
                editIdInput.value = '';
            }
            
            tableBody.innerHTML = emptyRowTemplate;
            attachEvents(tableBody.querySelector('.row-kegiatan')); 
            
            document.getElementById('infoBatas').innerHTML = 'Info Batas Maksimum : Silahkan pilih mitra dan Bulan.';
            document.getElementById('displayTotal').innerText = 'Rp 0';
            if(statusHonorText) {
                statusHonorText.innerText = 'Aman ( Tersisa Rp 0 )';
                statusHonorText.className = 'text-success fw-bold';
            }
        });
    }
});