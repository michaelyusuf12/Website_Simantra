<div class="modal fade" id="modalKelolaKegiatan" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title w-100 text-center" id="modalTitle">Tambah Penugasan</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formKelolaKegiatan" action="" method="POST">
                @csrf
                <input type="hidden" name="_method" id="formMethod">
                <div class="modal-body">
                    <input type="hidden" id="penugasanId" name="id">

                    <div class="mb-3">
                        <label for="idMitra" class="form-label">Pilih Mitra</label>
                        <select id="idMitra" name="mitra_id" class="form-select @error('mitra_id') is-invalid @enderror" required>
                            <option value="" disabled selected>-- Pilih seorang mitra --</option>
                            @foreach ($mitras as $mitra)
                                <option value="{{ $mitra->id }}">{{ $mitra->nama_petugas }}</option>
                            @endforeach
                        </select>
                        @error('mitra_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="idKegiatan" class="form-label">Nama Kegiatan</label>
                        <select id="idKegiatan" name="kegiatan_id" class="form-select @error('kegiatan_id') is-invalid @enderror" required>
                            <option value="" disabled selected>-- Pilih kegiatan --</option>
                             @foreach ($kegiatans as $kegiatan)
                                <option value="{{ $kegiatan->id }}">{{ $kegiatan->nama_kegiatan }}</option>
                            @endforeach
                        </select>
                        @error('kegiatan_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="honor" class="form-label">Honor</label>
                        <input type="number" class="form-control @error('honor') is-invalid @enderror" id="honor" name="honor" placeholder="Contoh: 1500000" required>
                        @error('honor')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="jumlahDokumen" class="form-label">Jumlah Dokumen</label>
                        <input type="number" class="form-control @error('jumlah_dokumen') is-invalid @enderror" id="jumlahDokumen" name="jumlah_dokumen" placeholder="Contoh: 50">
                        @error('jumlah_dokumen')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="bulanKegiatan" class="form-label">Bulan Kegiatan</label>
                        <select id="bulanKegiatan" name="bulan_kegiatan" class="form-select @error('bulan_kegiatan') is-invalid @enderror" required>
                            <option value="" disabled selected>-- Pilih bulan --</option>
                            <option>Januari</option> <option>Februari</option> <option>Maret</option>
                            <option>April</option> <option>Mei</option> <option>Juni</option>
                            <option>Juli</option> <option>Agustus</option> <option>September</option>
                            <option>Oktober</option> <option>November</option> <option>Desember</option>
                        </select>
                        @error('bulan_kegiatan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>