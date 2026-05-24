document.addEventListener("DOMContentLoaded", function () {
    // ==========================================
    // 1. DEKLARASI VARIABEL & ELEMEN HTML
    // ==========================================
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

    let emptyRowTemplate = '';
    let kegiatanOptionsHTML = '';
    if (tableBody && tableBody.querySelector('.row-kegiatan')) {
        emptyRowTemplate = tableBody.querySelector('.row-kegiatan').outerHTML;
        kegiatanOptionsHTML = tableBody.querySelector('.select-kegiatan').innerHTML;
    }

    // ==========================================
    // 2. FUNGSI FETCH AKUMULASI (AJAX) - REVISI ANTI STUCK
    // ==========================================
    function fetchAkumulasi() {
        if (!selectMitra || !selectBulan) return;
        const mitraId = selectMitra.value;
        const bulan = selectBulan.value;
        const elementEditId = document.getElementById('editPenugasanId');
        const editId = elementEditId ? elementEditId.value : '';

        if (mitraId && bulan) {
            infoBatas.innerHTML = '<span class="text-info"><i class="bi bi-hourglass-split"></i> Mengecek riwayat honor...</span>';

            let url = `/kelolakegiatan/cek-akumulasi?mitra_id=${mitraId}&bulan=${bulan}`;
            if(editId) url += `&penugasan_id=${editId}`;

            fetch(url)
                .then(res => {
                    if (!res.ok) throw new Error('Terjadi kesalahan koneksi ke server.');
                    return res.json();
                })
                .then(data => {
                    if (data.status === 'success') {
                        honorDiDatabase = parseInt(data.akumulasi) || 0;
                        limitSaatIni = parseInt(data.limit_maksimal) || 3258000;
                        
                        infoBatas.innerHTML = `Akumulasi Tersimpan: <b class="text-primary">Rp ${honorDiDatabase.toLocaleString('id-ID')}</b> | Batas Maks: <b class="text-danger">Rp ${limitSaatIni.toLocaleString('id-ID')}</b>`;
                        
                        document.querySelectorAll('.row-kegiatan').forEach(row => updatePeranOptions(row));
                        calculateGrandTotal(); 
                    } else {
                        throw new Error(data.message || 'Gagal memuat batas maksimum.');
                    }
                })
                .catch(err => {
                    console.error("Error Fetch Akumulasi:", err);
                    honorDiDatabase = 0;
                    limitSaatIni = 3258000; 
                    
                    infoBatas.innerHTML = `<span class="text-danger"><i class="bi bi-exclamation-triangle"></i> Terjadi kesalahan jaringan. Menggunakan limit standar.</span>`;
                    
                    document.querySelectorAll('.row-kegiatan').forEach(row => updatePeranOptions(row));
                    calculateGrandTotal(); 
                });
        }
    }

    // ==========================================
    // 3. FUNGSI DEPENDENT DROPDOWN PERAN
    // ==========================================
    function updatePeranOptions(row) {
        const selectPeran = row.querySelector('.select-peran');
        const optMitra = selectMitra.options[selectMitra.selectedIndex];
        
        if (!selectPeran || !optMitra) return;

        const posisiMitra = optMitra.getAttribute('data-posisi') ? optMitra.getAttribute('data-posisi') : '';
        const oldValue = selectPeran.value;

        selectPeran.innerHTML = '<option value="">-- Pilih --</option>';

        let options = [];

        if (posisiMitra == '3') {
            options = ['PCL', 'PML', 'Pengolahan'];
        } else if (posisiMitra == '2') {
            options = ['Pengolahan'];
        } else {
            options = ['PCL', 'PML'];
        }

        options.forEach(peran => {
            let option = document.createElement('option');
            option.value = peran;
            option.text = peran;
            if (peran === oldValue) option.selected = true; 
            selectPeran.appendChild(option);
        });
    }

    // ==========================================
    // 4. FUNGSI KALKULASI & UPDATE UI
    // ==========================================
    function calculateGrandTotal() {
        let totalDiForm = 0;
        document.querySelectorAll('.input-subtotal').forEach(input => {
            let val = input.value.replace(/\./g, '') || 0;
            totalDiForm += parseInt(val);
        });

        const grandTotalKeseluruhan = totalDiForm + honorDiDatabase;
        const sisaLimit = limitSaatIni - grandTotalKeseluruhan;

        if (displayTotalForm) displayTotalForm.innerText = 'Rp ' + totalDiForm.toLocaleString('id-ID');

        if (grandTotalKeseluruhan > limitSaatIni) {
            if (statusHonorText) {
                statusHonorText.innerText = `Melebihi Batas! (-Rp ${Math.abs(sisaLimit).toLocaleString('id-ID')})`;
                statusHonorText.className = "fw-bold text-danger";
            }
            if (btnSimpan) {
                btnSimpan.disabled = true;
                btnSimpan.style.opacity = "0.5";
            }
        } else {
            if (statusHonorText) {
                statusHonorText.innerText = `Aman ( Tersisa Rp ${sisaLimit.toLocaleString('id-ID')} )`;
                statusHonorText.className = "fw-bold text-success";
            }
            if (btnSimpan) {
                btnSimpan.disabled = false;
                btnSimpan.style.opacity = "1";
            }
        }
    }

    // ==========================================
    // 5. FUNGSI HITUNG MATEMATIKA PER BARIS
    // ==========================================
    function calculateRow(row) {
        const selectKegiatan = row.querySelector('.select-kegiatan');
        const selectPeran = row.querySelector('.select-peran');
        const inputVolume = row.querySelector('.input-volume');
        const inputHarga = row.querySelector('.input-harga');
        const inputSubtotal = row.querySelector('.input-subtotal');

        const optKegiatan = selectKegiatan.options[selectKegiatan.selectedIndex];
        let harga = 0;

        if (optKegiatan && optKegiatan.value !== "" && selectPeran.value !== "") {
            const peran = selectPeran.value;
            if (peran === 'PCL') harga = optKegiatan.getAttribute('data-pcl') || 0;
            else if (peran === 'PML') harga = optKegiatan.getAttribute('data-pml') || 0;
            else if (peran === 'Pengolahan') harga = optKegiatan.getAttribute('data-pengolahan') || 0;
        }

        const vol = parseInt(inputVolume.value) || 0;
        const hargaSatuan = parseInt(harga) || 0;
        const subtotalHitung = hargaSatuan * vol;

        if (inputHarga) inputHarga.value = hargaSatuan.toLocaleString('id-ID');
        if (inputSubtotal) inputSubtotal.value = subtotalHitung.toLocaleString('id-ID');

        calculateGrandTotal();
    }

    // ==========================================
    // 6. EVENT LISTENER UNTUK TRIGGER FUNGSI
    // ==========================================
    if (selectMitra) selectMitra.addEventListener('change', fetchAkumulasi);
    if (selectBulan) selectBulan.addEventListener('change', fetchAkumulasi);

    if (btnAddRow) {
        btnAddRow.addEventListener('click', function () {
            tableBody.insertAdjacentHTML('beforeend', emptyRowTemplate);
            const newRow = tableBody.lastElementChild;
            newRow.querySelectorAll('input').forEach(i => {
                if(i.name === "satuan[]") i.value = "Dokumen";
                else if(i.type === "date") i.value = "";
                else i.value = "0";
            });
            updatePeranOptions(newRow);
            attachEvents(newRow);
        });
    }

    if (tableBody) {
        tableBody.addEventListener('click', (e) => {
            if (e.target.closest('.btn-hapus-baris')) {
                if (tableBody.querySelectorAll('.row-kegiatan').length > 1) {
                    e.target.closest('.row-kegiatan').remove();
                    calculateGrandTotal();
                } else {
                    Swal.fire('Perhatian', 'Minimal harus ada satu rincian kegiatan.', 'warning');
                }
            }
        });
    }

    function attachEvents(row) {
        ['.select-kegiatan', '.select-peran', '.input-volume'].forEach(s => {
            const el = row.querySelector(s);
            if (el) {
                el.addEventListener('change', () => calculateRow(row));
                el.addEventListener('input', () => calculateRow(row));
            }
        });
    }

    document.querySelectorAll('.row-kegiatan').forEach(row => {
        updatePeranOptions(row);
        attachEvents(row);
    });

    // ==========================================
    // 7. RESET FORM SAAT KLIK TAMBAH BARU
    // ==========================================
    const btnTambahMaster = document.querySelector('[data-bs-target="#modalKelolaKegiatan"]');
    if (btnTambahMaster && !btnTambahMaster.classList.contains('btn-edit-penugasan')) {
        btnTambahMaster.addEventListener('click', function () {
            document.getElementById('modalTitle').innerHTML = '<i class="bi bi-pencil-square me-2"></i> Form Penugasan Baru';
            form.reset();
            document.getElementById('formMethod').value = 'POST';
            
            // [REVISI PENTING 1] Hardcode route bawaan Laravel
            form.action = '/kelolakegiatan'; 
            
            const editIdInput = document.getElementById('editPenugasanId');
            if (editIdInput) editIdInput.value = '';

            tableBody.innerHTML = emptyRowTemplate;
            attachEvents(tableBody.querySelector('.row-kegiatan'));

            document.getElementById('infoBatas').innerHTML = 'Info Batas Maksimum : Silahkan pilih mitra dan Bulan.';
            
            document.getElementById('displayTotal').innerText = 'Rp 0';
            if (statusHonorText) {
                statusHonorText.innerText = 'Aman ( Tersisa Rp 0 )';
                statusHonorText.className = 'fw-bold text-success';
            }
        });
    }

    // ==========================================
    // 8. SUBMIT FORM VIA AJAX
    // ==========================================
    if (form) {
        form.addEventListener('submit', function (e) {
            e.preventDefault();
            if (btnSimpan.disabled) return false;

            // [REVISI PENTING 2] Ambil CSRF Meta Tag langsung dari Master Layout
            let token = document.querySelector('meta[name="csrf-token"]');
            let csrfToken = token ? token.getAttribute('content') : '';

            fetch(form.action, {
                method: 'POST',
                headers: { 
                    'X-CSRF-TOKEN': csrfToken, 
                    'Accept': 'application/json' 
                },
                body: new FormData(form)
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'success') {
                    Swal.fire({ icon: 'success', title: 'Berhasil!', showConfirmButton: false, timer: 1500 })
                        .then(() => window.location.href = data.redirect);
                } else {
                    Swal.fire('Gagal!', data.message, 'error');
                }
            })
            .catch(err => {
                console.error(err);
                Swal.fire('Error System!', 'Gagal memproses data ke server.', 'error');
            });
        });
    }

    // ==========================================
    // 9. LOGIKA EXPORT EXCEL & HAPUS MASTER
    // ==========================================
    const checkAll = document.getElementById('checkAll');
    const checkItems = document.querySelectorAll('.check-item');
    const btnExportExcel = document.getElementById('btnExportExcel');
    const formExport = document.getElementById('formExport');

    if (checkAll) {
        checkAll.addEventListener('change', function () {
            checkItems.forEach(item => item.checked = this.checked);
        });
    }

    if (btnExportExcel && formExport) {
        btnExportExcel.addEventListener('click', function () {
            const isChecked = Array.from(checkItems).some(item => item.checked);
            if (!isChecked) {
                Swal.fire('Perhatian', 'Silakan centang minimal satu data yang ingin di-export.', 'warning');
                return;
            }
            formExport.submit();
        });
    }

    const formDeleteMaster = document.getElementById('formDeleteMaster');
    document.querySelectorAll('.btn-delete-custom').forEach(btn => {
        btn.addEventListener('click', function () {
            const id = this.getAttribute('data-id');
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Surat Tugas ini dan rinciannya akan dihapus permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                confirmButtonText: 'Ya, Hapus!'
            }).then((result) => {
                if (result.isConfirmed) {
                    formDeleteMaster.action = `/kelolakegiatan/${id}`;
                    formDeleteMaster.submit();
                }
            });
        });
    });

    // ==========================================
    // 10. EDIT DATA PENUGASAN (MODAL)
    // ==========================================
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

            fetch(`/kelolakegiatan/show/${idPenugasan}`)
                .then(res => res.json())
                .then(data => {
                    if (data.status === 'success') {
                        const p = data.data;

                        document.getElementById('modalTitle').innerHTML = '<i class="bi bi-pencil-square me-2"></i> Edit Penugasan: ' + (p.no_surat || 'Tanpa Nomor');
                        document.getElementById('formMethod').value = 'PUT';
                        document.getElementById('formKelolaKegiatan').action = `/kelolakegiatan/${p.id_penugasan || p.id}`;
                        
                        document.getElementById('noSurat').value = p.no_surat;
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
                                        <select name="kegiatan_id[]" class="form-select form-select-sm select-kegiatan border-secondary" required>
                                            ${kegiatanOptionsHTML}
                                        </select>
                                    </td>
                                    <td>
                                        <select name="peran[]" class="form-select form-select-sm select-peran border-secondary" required>
                                            <option value="PCL" ${dt.uraian_tugas === 'PCL' ? 'selected' : ''}>PCL</option>
                                            <option value="PML" ${dt.uraian_tugas === 'PML' ? 'selected' : ''}>PML</option>
                                            <option value="Pengolahan" ${dt.uraian_tugas === 'Pengolahan' ? 'selected' : ''}>Pengolahan</option>
                                        </select>
                                    </td>
                                    <td><input type="date" name="tanggal_mulai[]" class="form-control form-control-sm border-secondary" value="${tglMulai}" required></td>
                                    <td><input type="date" name="tanggal_selesai[]" class="form-control form-control-sm border-secondary" value="${tglSelesai}" required></td>
                                    <td><input type="number" name="volume[]" class="form-control form-control-sm input-volume border-secondary text-center" value="${dt.volume}" min="1" required></td>
                                    <td><input type="text" name="satuan[]" class="form-control form-control-sm bg-light text-center border-secondary" value="${dt.satuan || 'Dokumen'}" readonly></td>
                                    <td><input type="text" class="form-control form-control-sm input-harga bg-light text-end" value="${parseInt(dt.harga_satuan).toLocaleString('id-ID')}" readonly></td>
                                    <td><input type="text" class="form-control form-control-sm input-subtotal bg-light text-end fw-bold text-dark" value="${(dt.volume * dt.harga_satuan).toLocaleString('id-ID')}" readonly></td>
                                    <td class="text-center align-middle">
                                        <button type="button" class="btn btn-sm btn-outline-danger btn-hapus-baris shadow-sm px-2 py-1"><i class="bi bi-trash"></i></button>
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

    // ==========================================
    // 11. MODAL LIHAT DETAIL VIA AJAX
    // ==========================================
    const modalDetail = new bootstrap.Modal(document.getElementById('modalDetailPenugasan'));
    
    document.querySelectorAll('.btn-lihat-detail').forEach(btn => {
        btn.addEventListener('click', function () {
            const id = this.getAttribute('data-id');

            fetch(`/kelolakegiatan/show/${id}`)
                .then(response => response.json())
                .then(res => {
                    if (res.status === 'success') {
                        const d = res.data;

                        document.getElementById('detailNoSurat').innerText = d.no_surat || '-';
                        document.getElementById('detailNamaMitra').innerText = d.mitra ? d.mitra.nama_petugas : '-';
                        document.getElementById('detailBulan').innerText = d.bulan_kegiatan || '-';
                        
                        const tgl = new Date(d.tanggal_surat);
                        document.getElementById('detailTanggalSurat').innerText = tgl.toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });
                        
                        // --- MULAI KODE YANG BARU DITEMPEL ---
                        const statusSpan = document.getElementById('detailStatus');
                        const statusText = d.status_kontrak || '-';
                        statusSpan.innerText = statusText;

                        // Reset semua kelas warna
                        statusSpan.className = 'badge'; 

                        // Cek status dan berikan warna yang sesuai
                        if (statusText.toLowerCase() === 'disetujui') {
                            statusSpan.classList.add('bg-success', 'text-white'); // Hijau
                        } else if (statusText.toLowerCase() === 'ditolak') {
                            statusSpan.classList.add('bg-danger', 'text-white'); // Merah
                        } else {
                            statusSpan.classList.add('bg-warning', 'text-dark'); // Kuning (Menunggu)
                        }
                        // --- AKHIR KODE YANG BARU DITEMPEL ---

                        document.getElementById('detailTotalHonor').innerText = "Rp " + parseInt(d.total_nilai_perjanjian).toLocaleString('id-ID');

                        const tbody = document.getElementById('tbodyDetailRincian');
                        tbody.innerHTML = ''; 

                        if (d.details && d.details.length > 0) {
                            d.details.forEach((det, index) => {
                                const tr = document.createElement('tr');
                                const namaKeg = det.kegiatan ? det.kegiatan.nama_kegiatan : 'Kegiatan Terhapus';
                                const harga = parseInt(det.harga_satuan) || 0;
                                const subtotal = harga * (parseInt(det.volume) || 0);

                                tr.innerHTML = `
                                    <td class="text-center">${index + 1}</td>
                                    <td class="fw-bold text-dark">${namaKeg}</td>
                                    <td class="text-center"><span class="badge bg-secondary">${det.uraian_tugas}</span></td>
                                    <td class="text-center">${det.tanggal_mulai}</td>
                                    <td class="text-center">${det.tanggal_selesai}</td>
                                    <td class="text-center fw-bold">${det.volume}</td>
                                    <td class="text-center">${det.satuan}</td>
                                    <td class="text-end">Rp ${harga.toLocaleString('id-ID')}</td>
                                    <td class="text-end fw-bold text-success">Rp ${subtotal.toLocaleString('id-ID')}</td>
                                `;
                                tbody.appendChild(tr);
                            });
                        } else {
                            tbody.innerHTML = `<tr><td colspan="9" class="text-center text-muted py-3">Tidak ada rincian kegiatan</td></tr>`;
                        }

                        modalDetail.show();
                    } else {
                        Swal.fire('Error', res.message, 'error');
                    }
                })
                .catch(err => console.error(err));
        });
    });
});