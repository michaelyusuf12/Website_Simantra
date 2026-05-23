<div class="modal fade" id="modalKegiatan" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content shadow-lg border-0" style="border-radius: 12px;">
            <div class="modal-header bg-primary text-white" style="border-radius: 12px 12px 0 0;">
                <h5 class="modal-title w-100 text-center fw-bold" id="modalTitle">Tambah Data Kegiatan</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <form id="formKegiatan" action="" method="POST">
                @csrf
                <input type="hidden" name="_method" id="formMethod" value="POST">
                <div class="modal-body p-4 bg-light">
                    <input type="hidden" id="kegiatanId" name="id">
                    
                    <div class="row g-3">
                        {{-- ========================================== --}}
                        {{-- KOLOM KIRI (INFORMASI UMUM KEGIATAN)       --}}
                        {{-- ========================================== --}}
                        <div class="col-md-6">
                            <div class="card border-0 shadow-sm h-100" style="border-radius: 10px;">
                                <div class="card-body p-3">
                                    <div class="mb-3">
                                        <label for="kodeKegiatan" class="form-label fw-bold text-dark small mb-1">Kode Kegiatan</label>
                                        <input type="text" class="form-control border-secondary" id="kodeKegiatan" name="kode_kegiatan" placeholder="Contoh: 001" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="namaKegiatan" class="form-label fw-bold text-dark small mb-1">Nama Kegiatan</label>
                                        <input type="text" class="form-control border-secondary" id="namaKegiatan" name="nama_kegiatan" placeholder="Contoh: Sensus Pertanian 2026" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="penanggungJawab" class="form-label fw-bold text-dark small mb-1">Penanggung Jawab</label>
                                        <input type="text" class="form-control border-secondary" id="penanggungJawab" name="penanggung_jawab" placeholder="Nama Ketua Tim/PJ">
                                    </div>
                                    <div class="mb-3">
                                        <label for="namaTim" class="form-label fw-bold text-dark small mb-1">Nama Tim</label>
                                        <input type="text" class="form-control border-secondary" id="namaTim" name="nama_tim" placeholder="Contoh: Tim Sosial/Produksi">
                                    </div>
                                    <div class="mb-0">
                                        <label for="targetDokumen" class="form-label fw-bold text-dark small mb-1">Target Dokumen</label>
                                        <input type="number" class="form-control border-secondary text-center" id="targetDokumen" name="target_dokumen" value="100" min="1" required>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- ========================================== --}}
                        {{-- KOLOM KANAN (KLASIFIKASI & TARIF HONOR)    --}}
                        {{-- ========================================== --}}
                        <div class="col-md-6">
                            <div class="card border-0 shadow-sm h-100" style="border-radius: 10px;">
                                <div class="card-body p-3 d-flex flex-column gap-3">
                                    
                                    {{-- Klasifikasi Struktur --}}
                                    <div>
                                        <label for="fungsi" class="form-label fw-bold text-dark small mb-1">Fungsi</label>
                                        <select id="fungsi" name="fungsi" class="form-select border-secondary" required>
                                            <option value="" disabled selected>-- Pilih Fungsi --</option>
                                            <option value="Sosial">Sosial</option>
                                            <option value="Produksi">Produksi</option>
                                            <option value="Distribusi">Distribusi</option>
                                            <option value="Neraca">Neraca</option>
                                            <option value="IPDS">IPDS</option>
                                            <option value="Umum">Umum</option>
                                        </select>
                                    </div>
                                    
                                    <div>
                                        <label for="jenisKegiatan" class="form-label fw-bold text-dark small mb-1">Jenis Kegiatan (Klasifikasi)</label>
                                        <select id="jenisKegiatan" name="jenis_kegiatan" class="form-select border-secondary" required>
                                            <option value="" disabled selected>-- Pilih Jenis --</option>
                                            <option value="Lapangan">Lapangan</option>
                                            <option value="Pengolahan">Pengolahan</option>
                                            <option value="Lapangan & Pengolahan">Lapangan & Pengolahan</option>
                                        </select>
                                    </div>

                                    {{-- BOX INPUT TARIF HONOR (SELALU MUNCUL SECARA PERMANEN) --}}
                                    <div class="card border-secondary shadow-sm mt-2" style="border-style: dashed !important; background-color: #fafafa;">
                                        <div class="card-header bg-white fw-bold py-2 border-bottom-0 text-primary" style="font-size: 0.85rem;">
                                            <i class="bi bi-cash-stack me-2"></i>Komponen Tarif Honor Per Dokumen
                                        </div>
                                        <div class="card-body pt-1 pb-3 px-3">
                                            <div class="mb-2">
                                                <label for="honor_pml_per_dokumen" class="form-label text-secondary small mb-1" style="font-size: 0.8rem;">Honor PML (Rp)</label>
                                                {{-- Ubah type jadi text, tambahkan class 'input-rupiah', hapus step="100" min="0" --}}
                                                <input type="text" class="form-control form-control-sm border-secondary text-end fw-bold text-dark input-rupiah" id="honor_pml_per_dokumen" name="honor_pml_per_dokumen" value="0" required>
                                            </div>
                                            <div class="mb-2">
                                                <label for="honor_pcl_per_dokumen" class="form-label text-secondary small mb-1" style="font-size: 0.8rem;">Honor PCL (Rp)</label>
                                                <input type="text" class="form-control form-control-sm border-secondary text-end fw-bold text-dark input-rupiah" id="honor_pcl_per_dokumen" name="honor_pcl_per_dokumen" value="0" required>
                                            </div>
                                            <div class="mb-0">
                                                <label for="honor_pengolahan_per_dokumen" class="form-label text-secondary small mb-1" style="font-size: 0.8rem;">Honor Pengolahan (Rp)</label>
                                                <input type="text" class="form-control form-control-sm border-secondary text-end fw-bold text-success input-rupiah" id="honor_pengolahan_per_dokumen" name="honor_pengolahan_per_dokumen" value="0" required>
                                            </div>
                                        </div>
                                    </div>
                                    {{-- END BOX TARIF --}}

                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Baris Input Tanggal Tersembunyi (Sesuai Bawaan Kode Asli Anda) --}}
                    <input type="hidden" name="tgl_mulai" id="tanggalMulai" value="{{ date('Y-m-d') }}">
                    <input type="hidden" name="tgl_selesai" id="tanggalSelesai" value="{{ date('Y-m-d') }}">
                </div>
                
                <div class="modal-footer bg-white border-top py-3" style="border-radius: 0 0 12px 12px;">
                    <button type="button" class="btn btn-secondary px-4 fw-medium" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success px-4 fw-bold shadow-sm">Simpan Kegiatan</button>
                </div>
            </form>
        </div>
    </div>
</div>