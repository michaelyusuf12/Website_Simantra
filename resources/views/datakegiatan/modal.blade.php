<div class="modal fade" id="modalKegiatan" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title w-100 text-center" id="modalTitle">Tambah Data Kegiatan</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formKegiatan" action="" method="POST">
                @csrf
                <input type="hidden" name="_method" id="formMethod">
                <div class="modal-body">
                    <input type="hidden" id="kegiatanId" name="id">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="kodeKegiatan" class="form-label">Kode Kegiatan</label>
                                <input type="text" class="form-control" id="kodeKegiatan" name="kode_kegiatan" required>
                            </div>
                            <div class="mb-3">
                                <label for="namaKegiatan" class="form-label">Nama Kegiatan</label>
                                <input type="text" class="form-control" id="namaKegiatan" name="nama_kegiatan" required>
                            </div>
                            <div class="mb-3">
                                <label for="penanggungJawab" class="form-label">Penanggung Jawab</label>
                                <input type="text" class="form-control" id="penanggungJawab" name="penanggung_jawab">
                            </div>
                            <div class="mb-3">
                                <label for="namaTim" class="form-label">Nama Tim</label>
                                <input type="text" class="form-control" id="namaTim" name="nama_tim">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="fungsi" class="form-label">Fungsi</label>
                                <select id="fungsi" name="fungsi" class="form-select">
                                    <option value="Sosial">Sosial</option>
                                    <option value="Produksi">Produksi</option>
                                    <option value="Distribusi">Distribusi</option>
                                    <option value="Neraca">Neraca</option>
                                    <option value="IPDS">IPDS</option>
                                    <option value="Sub Bagian Umum">Sub Bagian Umum</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="jenisKegiatan" class="form-label">Jenis Kegiatan</label>
                                <select id="jenisKegiatan" name="jenis_kegiatan" class="form-select">
                                    <option value="Lapangan">Lapangan</option>
                                    <option value="Pengolahan">Pengolahan</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="tanggalMulai" class="form-label">Tanggal Mulai</label>
                                <input type="date" class="form-control" id="tanggalMulai" name="tgl_mulai">
                            </div>
                            <div class="mb-3">
                                <label for="tanggalSelesai" class="form-label">Tanggal Selesai</label>
                                <input type="date" class="form-control" id="tanggalSelesai" name="tgl_selesai">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>