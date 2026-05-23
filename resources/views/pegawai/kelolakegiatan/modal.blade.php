{{-- MODAL TAMBAH / EDIT PENUGASAN --}}
<div class="modal fade" id="modalKelolaKegiatan" tabindex="-1" aria-labelledby="modalKelolaKegiatanLabel" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content border-0 shadow">
            
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title fw-bold" id="modalTitle"><i class="bi bi-pencil-square me-2"></i> Form Penugasan Baru</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <form id="formKelolaKegiatan" method="POST" action="">
                @csrf
                <input type="hidden" name="_method" id="formMethod" value="POST">
                <input type="hidden" name="penugasan_id" id="editPenugasanId" value="">

                <div class="modal-body bg-light">
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label class="form-label fw-bold text-dark">No. Surat Tugas <span class="text-danger">*</span></label>
                            <input type="text" class="form-control border-secondary" name="no_surat" id="noSurat" placeholder="Contoh: B-1103/74041/05/2026" required>
                        </div>

                        <div class="col-md-4">
                        <label class="form-label fw-bold text-dark">Nama Mitra <span class="text-danger">*</span></label>
                        <select class="form-select border-secondary select2-mitra" name="mitra_id" id="idMitra" required>
                            <option value="">-- Pilih Mitra --</option>
                            @foreach($mitras as $m)
                                @php
                                    // Logika mengubah angka menjadi teks kalimat
                                    $labelPosisi = $m->posisi_petugas;
                                    if ($m->posisi_petugas == '1') {
                                        $labelPosisi = 'Lapangan';
                                    } elseif ($m->posisi_petugas == '2') {
                                        $labelPosisi = 'Pengolahan';
                                    } elseif ($m->posisi_petugas == '3') {
                                        $labelPosisi = 'Lapangan & Pengolahan';
                                    }
                                @endphp
                                
                                <option value="{{ $m->sobat_id }}" data-posisi="{{ $m->posisi_petugas }}">
                                    {{ $labelPosisi }} - {{ $m->sobat_id }} - {{ $m->nama_petugas }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                        <div class="col-md-4">
                            <label class="form-label fw-bold text-dark">Bulan Penugasan <span class="text-danger">*</span></label>
                            <select class="form-select border-secondary" name="bulan_penugasan" id="bulanKegiatan" required>
                                <option value="">-- Pilih Bulan --</option>
                                @foreach($daftarBulan as $bulan)
                                    <option value="{{ $bulan }}">{{ $bulan }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="alert alert-info border-0 shadow-sm py-2 mb-3" role="alert" style="border-radius: 8px; background-color: #d1ecf1; color: #0c5460;">
                        <i class="bi bi-info-circle-fill me-2"></i> <span id="infoBatas">Info Batas Maksimum : Silahkan pilih mitra dan Bulan.</span>
                    </div>

                    <div class="card border-0 shadow-sm" style="border-radius: 10px;">
                        <div class="card-header bg-white pb-0 border-0 d-flex justify-content-between align-items-center">
                            <h6 class="fw-bold text-primary mb-0"><i class="bi bi-list-task me-2"></i>Rincian Kegiatan (Detail)</h6>
                            <button type="button" class="btn btn-sm btn-outline-success fw-bold px-3" id="btnAddRow" style="border-radius: 6px;">
                                <i class="bi bi-plus-lg"></i> Tambah Kegiatan
                            </button>
                        </div>
                        <div class="card-body p-3">
                            <div class="table-responsive">
                                <table class="table table-bordered align-middle mb-0" style="min-width: 1000px; border-radius: 8px; overflow: hidden;">
                                    <thead class="table-light text-center small fw-bold">
                                        <tr>
                                            <th style="width: 28%;">Kegiatan</th>
                                            <th style="width: 12%;">Peran</th>
                                            <th style="width: 12%;">Tanggal Mulai</th>
                                            <th style="width: 12%;">Tanggal Selesai</th>
                                            <th style="width: 7%;">Volume</th>
                                            <th style="width: 8%;">Satuan</th>
                                            <th style="width: 11%;">Harga (Rp)</th>
                                            <th style="width: 11%;">Subtotal (Rp)</th>
                                            <th style="width: 5%;">Aksi</th> </tr>
                                    </thead>
                                    <tbody id="tbodyRincian">
                                        <tr class="row-kegiatan">
                                            <td>
                                    <select name="kegiatan_id[]" class="form-select select-kegiatan" required>
                                        <option value="">-- Pilih Kegiatan --</option>
                                        @foreach($kegiatans as $keg)
                                            {{-- [REVISI] Menampilkan Fungsi sebelum Nama Kegiatan --}}
                                            <option value="{{ $keg->id_kegiatan }}" 
                                                data-pml="{{ $keg->honor_pml_per_dokumen }}" 
                                                data-pcl="{{ $keg->honor_pcl_per_dokumen }}" 
                                                data-pengolahan="{{ $keg->honor_pengolahan_per_dokumen }}">
                                                {{ $keg->fungsi }} - {{ $keg->nama_kegiatan }}
                                            </option>
                                        @endforeach
                                    </select>
                                            </td>
                                            <td>
                                                <select name="peran[]" class="form-select form-select-sm select-peran border-secondary" required>
                                                    <option value="">-- Pilih --</option>
                                                </select>
                                            </td>
                                            <td><input type="date" name="tanggal_mulai[]" class="form-control form-control-sm border-secondary" required></td>
                                            <td><input type="date" name="tanggal_selesai[]" class="form-control form-control-sm border-secondary" required></td>
                                            <td><input type="number" name="volume[]" class="form-control form-control-sm input-volume text-center border-secondary" min="1" value="0" required></td>
                                            <td><input type="text" name="satuan[]" class="form-control form-control-sm text-center border-secondary bg-light" value="Dokumen" readonly></td>
                                            <td><input type="text" class="form-control form-control-sm input-harga text-end bg-light" value="0" readonly></td>
                                            <td><input type="text" class="form-control form-control-sm input-subtotal text-end text-dark fw-bold bg-light" value="0" readonly></td>
                                            <td class="text-center align-middle">
                                                <button type="button" class="btn btn-sm btn-outline-danger btn-hapus-baris shadow-sm px-2 py-1" tabindex="-1"><i class="bi bi-trash"></i></button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <div class="card border mt-3 bg-white" style="border-radius: 8px; border-color: #dee2e6 !important;">
                                <div class="card-body d-flex justify-content-between align-items-center py-3">
                                    <div>
                                        <span class="text-dark fw-bold d-block" style="font-size: 0.9rem; margin-bottom: 2px;">Total Honor Surat Ini :</span>
                                        <span class="text-primary fw-bold lh-1" id="displayTotal" style="font-size: 2.2rem; letter-spacing: -1px;">Rp 0</span>
                                    </div>
                                    <div>
                                        <div class="border px-4 py-2" style="border-radius: 8px; border-color: #dee2e6 !important; background-color: #f8f9fa;">
                                            <span class="text-dark fw-bold" style="font-size: 0.95rem;">Status : </span>
                                            <span id="statusPagu" class="fw-bold text-success" style="font-size: 0.95rem;">Aman ( Tersisa Rp 0 )</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                
                <div class="modal-footer bg-white border-top py-3 d-flex justify-content-end">
                    <button type="button" class="btn btn-secondary px-4 fw-bold text-white shadow-sm me-2" data-bs-dismiss="modal">BATAL</button>
                    <button type="submit" class="btn btn-primary px-4 fw-bold shadow-sm" id="btnSimpan">
                        <i class="bi bi-save me-1"></i> SIMPAN PENUGASAN
                    </button>
                </div>
            </form>
            
        </div>
    </div>
</div>