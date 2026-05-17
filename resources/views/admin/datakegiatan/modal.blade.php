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
                        {{-- Kolom Kiri --}}
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="kodeKegiatan" class="form-label">Kode Kegiatan</label>
                                <input type="text" class="form-control @error('kode_kegiatan') is-invalid @enderror" id="kodeKegiatan" name="kode_kegiatan" value="{{ old('kode_kegiatan') }}" required>
                                @error('kode_kegiatan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="mb-3">
                                <label for="namaKegiatan" class="form-label">Nama Kegiatan</label>
                                <input type="text" class="form-control @error('nama_kegiatan') is-invalid @enderror" id="namaKegiatan" name="nama_kegiatan" value="{{ old('nama_kegiatan') }}" required>
                                @error('nama_kegiatan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="mb-3">
                                <label for="penanggungJawab" class="form-label">Penanggung Jawab</label>
                                <input type="text" class="form-control @error('penanggung_jawab') is-invalid @enderror" id="penanggungJawab" name="penanggung_jawab" value="{{ old('penanggung_jawab') }}">
                                @error('penanggung_jawab') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                             <div class="mb-3">
                                <label for="namaTim" class="form-label">Nama Tim</label>
                                <input type="text" class="form-control @error('nama_tim') is-invalid @enderror" id="namaTim" name="nama_tim" value="{{ old('nama_tim') }}">
                                @error('nama_tim') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="mb-3">
                                <label for="targetDokumen" class="form-label">Target Dokumen</label>
                                <input type="number" class="form-control @error('target_dokumen') is-invalid @enderror" id="targetDokumen" name="target_dokumen" value="{{ old('target_dokumen', 100) }}">
                                @error('target_dokumen') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>
                        {{-- Kolom Kanan --}}
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="fungsi" class="form-label">Fungsi</label>
                                <select id="fungsi" name="fungsi" class="form-select @error('fungsi') is-invalid @enderror" required>
                                    <option value="" disabled {{ old('fungsi') ? '' : 'selected' }}>-- Pilih Fungsi --</option>
                                    <option value="Sosial" {{ old('fungsi') == 'Sosial' ? 'selected' : '' }}>Sosial</option>
                                    <option value="Produksi" {{ old('fungsi') == 'Produksi' ? 'selected' : '' }}>Produksi</option>
                                    <option value="Distribusi" {{ old('fungsi') == 'Distribusi' ? 'selected' : '' }}>Distribusi</option>
                                    <option value="Neraca" {{ old('fungsi') == 'Neraca' ? 'selected' : '' }}>Neraca</option>
                                    <option value="IPDS" {{ old('fungsi') == 'IPDS' ? 'selected' : '' }}>IPDS</option>
                                    <option value="Umum" {{ old('fungsi') == 'Umum' ? 'selected' : '' }}>Umum</option>
                                </select>
                                @error('fungsi') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="mb-3">
                                <label for="jenisKegiatan" class="form-label">Jenis Kegiatan</label>
                                <select id="jenisKegiatan" name="jenis_kegiatan" class="form-select @error('jenis_kegiatan') is-invalid @enderror" required>
                                    <option value="" disabled {{ old('jenis_kegiatan') ? '' : 'selected' }}>-- Pilih Jenis --</option>
                                    <option value="Lapangan" {{ old('jenis_kegiatan') == 'Lapangan' ? 'selected' : '' }}>Lapangan</option>
                                    <option value="Pengolahan" {{ old('jenis_kegiatan') == 'Pengolahan' ? 'selected' : '' }}>Pengolahan</option>
                                </select>
                                @error('jenis_kegiatan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            {{-- Input Honor Lapangan (Awalnya disembunyikan) --}}
                            <div id="honorLapanganFields" style="display: none;">
                                <div class="mb-3">
                                    <label for="honor_pml_per_dokumen" class="form-label">Honor PML per Dokumen (Rp)</label>
                                    <input type="number" step="100" class="form-control @error('honor_pml_per_dokumen') is-invalid @enderror" id="honor_pml_per_dokumen" name="honor_pml_per_dokumen" value="{{ old('honor_pml_per_dokumen') }}">
                                    @error('honor_pml_per_dokumen') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="mb-3">
                                    <label for="honor_pcl_per_dokumen" class="form-label">Honor PCL per Dokumen (Rp)</label>
                                    <input type="number" step="100" class="form-control @error('honor_pcl_per_dokumen') is-invalid @enderror" id="honor_pcl_per_dokumen" name="honor_pcl_per_dokumen" value="{{ old('honor_pcl_per_dokumen') }}">
                                    @error('honor_pcl_per_dokumen') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            {{-- Input Honor Pengolahan (Awalnya disembunyikan) --}}
                            <div id="honorPengolahanFields" style="display: none;">
                                <div class="mb-3">
                                    <label for="honor_pengolahan_per_dokumen" class="form-label">Honor Petugas Pengolahan per Dokumen (Rp)</label>
                                    <input type="number" step="100" class="form-control @error('honor_pengolahan_per_dokumen') is-invalid @enderror" id="honor_pengolahan_per_dokumen" name="honor_pengolahan_per_dokumen" value="{{ old('honor_pengolahan_per_dokumen') }}">
                                     @error('honor_pengolahan_per_dokumen') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            {{-- Tanggal Mulai & Selesai --}}
                             <div class="mb-3">
                                <label for="tanggalMulai" class="form-label">Tanggal Mulai</label>
                                <input type="date" class="form-control @error('tgl_mulai') is-invalid @enderror" id="tanggalMulai" name="tgl_mulai" value="{{ old('tgl_mulai') }}">
                                @error('tgl_mulai') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="mb-3">
                                <label for="tanggalSelesai" class="form-label">Tanggal Selesai</label>
                                <input type="date" class="form-control @error('tgl_selesai') is-invalid @enderror" id="tanggalSelesai" name="tgl_selesai" value="{{ old('tgl_selesai') }}">
                                @error('tgl_selesai') <div class="invalid-feedback">{{ $message }}</div> @enderror
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