<div class="modal fade" id="modalPegawai" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered"> 
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title w-100 text-center" id="modalTitle">Tambah Data Pegawai</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formPegawai" action="" method="POST">
                @csrf
                <input type="hidden" name="_method" id="formMethod">
                <div class="modal-body">
                    <input type="hidden" id="pegawaiId" name="id">

                    <div class="mb-3">
                        <label for="nama" class="form-label">Nama Pegawai</label>
                        <input type="text" class="form-control @error('nama') is-invalid @enderror" id="nama" name="nama" value="{{ old('nama') }}" required placeholder="Masukkan nama lengkap...">
                        @error('nama') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label for="username" class="form-label">Username (Untuk Login)</label>
                        <input type="text" class="form-control @error('username') is-invalid @enderror" id="username" name="username" value="{{ old('username') }}" required>
                        @error('username') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        {{-- HAPUS atribut 'required' dari sini agar tidak bentrok dengan JS saat proses Edit --}}
                        <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" minlength="6">
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @else
                            <small class="form-text text-muted">Minimal 6 karakter. <span class="text-warning fw-bold">Kosongkan saat edit jika tidak ingin mengubah password.</span></small>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="nip" class="form-label">NIP</label>
                        <input type="text" class="form-control @error('nip') is-invalid @enderror" id="nip" name="nip" value="{{ old('nip') }}">
                        @error('nip') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Role Akses</label>
                        <select id="role" name="role" class="form-select" required>                            <option value="">-- Pilih Role --</option>
                            <option value="admin">Admin</option>
                            <option value="pegawai">Pegawai</option>
                            <option value="ppk">PPK (Pejabat Pembuat Komitmen)</option>
                            <option value="kepala_bps">Kepala BPS</option>
                        </select>
                        @error('role') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="fungsi" class="form-label">Fungsi / Bagian</label>
                        <select id="fungsi" name="fungsi" class="form-select @error('fungsi') is-invalid @enderror">
                            <option value="" disabled {{ old('fungsi') ? '' : 'selected' }}>-- Pilih Fungsi --</option>
                            <option value="Sosial" {{ old('fungsi') == 'Sosial' ? 'selected' : '' }}>Sosial</option>
                            <option value="Produksi" {{ old('fungsi') == 'Produksi' ? 'selected' : '' }}>Produksi</option>
                            <option value="Distribusi" {{ old('fungsi') == 'Distribusi' ? 'selected' : '' }}>Distribusi</option>
                            <option value="IPDS" {{ old('fungsi') == 'IPDS' ? 'selected' : '' }}>IPDS</option>
                            <option value="Neraca" {{ old('fungsi') == 'Neraca' ? 'selected' : '' }}>Neraca</option>
                            <option value="Umum" {{ old('fungsi') == 'Umum' ? 'selected' : '' }}>Umum</option>
                        </select>
                        @error('fungsi') <div class="invalid-feedback">{{ $message }}</div> @enderror
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