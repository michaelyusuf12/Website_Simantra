<div class="modal fade" id="modalMitra" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title w-100 text-center" id="modalTitle">Tambah Data Mitra</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formMitra" action="" method="POST">
                @csrf
                <input type="hidden" name="_method" id="formMethod">
                <div class="modal-body">
                    <input type="hidden" id="mitraId" name="id">
                    <div class="row">
                        
                        {{-- ================= KOLOM KIRI ================= --}}
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="namaPetugas" class="form-label">Nama Petugas</label>
                                <input type="text" class="form-control" id="namaPetugas" name="nama_petugas" required>
                            </div>
                            
                            {{-- TAMBAHAN: Form Username (Untuk Login) --}}
                            <div class="mb-3">
                                <label for="username" class="form-label text-primary fw-bold">Username(Gunakan Email Mitra)</label>
                                <input type="text" class="form-control border-primary @error('username') is-invalid @enderror" id="username" name="username" required>
                                @error('username')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="emailMitra" class="form-label">Email</label>
                                <input type="email" class="form-control" id="emailMitra" name="email" required>
                            </div>
                            <div class="mb-3">
                                <label for="telepon" class="form-label">Nomor Telepon</label>
                                <input type="text" class="form-control" id="telepon" name="telepon">
                            </div>
                            <div class="mb-3">
                                <label for="posisiPetugas" class="form-label">Posisi Petugas</label>
                                <select class="form-select @error('posisi_petugas') is-invalid @enderror" id="posisiPetugas" name="posisi_petugas">
                                    <option value="" selected disabled>-- Pilih Posisi --</option>
                                    {{-- Loop melalui opsi dari Controller --}}
                                    @foreach($posisiOptions as $kode => $nama)
                                        <option value="{{ $kode }}">{{ $nama }}</option>
                                    @endforeach
                                </select>
                                @error('posisi_petugas')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- ================= KOLOM KANAN ================= --}}
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="sobatId" class="form-label">SOBAT ID</label>
                                <input type="text" class="form-control" id="sobatId" name="sobat_id">
                            </div>

                            {{-- TAMBAHAN: Form Password (Untuk Login) --}}
                            <div class="mb-3">
                                <label for="password" class="form-label text-primary fw-bold">Password</label>
                                <input type="password" class="form-control border-primary @error('password') is-invalid @enderror" id="password" name="password" required minlength="6">
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @else
                                    <small class="form-text text-muted">Minimal 6 karakter.</small>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="kodeProvinsi" class="form-label">Kode Provinsi</label>
                                <input type="text" class="form-control" id="kodeProvinsi" name="kode_prov" value="74">
                            </div>
                            <div class="mb-3">
                                <label for="kodeKabupaten" class="form-label">Kode Kabupaten</label>
                                <input type="text" class="form-control" id="kodeKabupaten" name="kode_kab" value="04">
                            </div>
                            <div class="mb-3">
                                <label for="alamat" class="form-label">Alamat</label>
                                <textarea class="form-control" id="alamat" name="alamat" rows="2"></textarea>
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