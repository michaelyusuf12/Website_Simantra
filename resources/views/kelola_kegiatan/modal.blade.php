<div class="modal fade" id="modalKelolaKegiatan" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
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
                    <div id="general-error-alert" class="alert alert-danger" style="display: none;" role="alert"></div>

                    <div class="mb-3">
                        <label for="idMitra" class="form-label">Pilih Mitra</label>
                        {{-- Pastikan name="mitra_id" --}}
                        <select id="idMitra" name="mitra_id" class="form-select @error('mitra_id') is-invalid @enderror" required>
                            <option value="" disabled selected>-- Pilih seorang mitra --</option>
                            @foreach ($mitras as $mitra)
                                {{-- /// --- PERUBAHAN DI SINI --- /// --}}
                                {{-- Gunakan sobat_id sebagai value --}}
                                <option value="{{ $mitra->sobat_id }}" {{ old('mitra_id') == $mitra->sobat_id ? 'selected' : '' }}>
                                    {{ $mitra->nama_petugas }}
                                </option>
                                {{-- /// --- BATAS AKHIR PERUBAHAN --- /// --}}
                            @endforeach
                        </select>
                        <div class="invalid-feedback"></div>
                    </div>

                    {{-- (Dropdown Kegiatan, Jumlah Dokumen, Bulan, Peran biarkan sama) --}}
                    <div class="mb-3">
                        <label for="idKegiatan" class="form-label">Nama Kegiatan</label>
                        <select id="idKegiatan" name="kegiatan_id" class="form-select @error('kegiatan_id') is-invalid @enderror" required>
                            <option value="" disabled selected data-jenis="">-- Pilih kegiatan --</option>
                             @foreach ($kegiatans as $kegiatan)
                                <option value="{{ $kegiatan->id }}"
                                        data-jenis="{{ $kegiatan->jenis_kegiatan }}"
                                        {{ old('kegiatan_id') == $kegiatan->id ? 'selected' : '' }}>
                                    {{ $kegiatan->nama_kegiatan }} ({{ $kegiatan->jenis_kegiatan }})
                                </option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="mb-3">
                        <label for="jumlahDokumen" class="form-label">Jumlah Dokumen</label>
                        <input type="number" class="form-control @error('jumlah_dokumen') is-invalid @enderror"
                               id="jumlahDokumen" name="jumlah_dokumen"
                               value="{{ old('jumlah_dokumen') }}"
                               placeholder="Contoh: 50" required>
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="mb-3">
                        <label for="bulanKegiatan" class="form-label">Bulan Kegiatan</label>
                        <select id="bulanKegiatan" name="bulan_kegiatan" class="form-select @error('bulan_kegiatan') is-invalid @enderror" required>
                             <option value="" disabled selected>-- Pilih bulan --</option>
                             @php $bulanList = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember']; @endphp
                             @foreach ($bulanList as $bulan)
                                <option value="{{ $bulan }}" {{ old('bulan_kegiatan') == $bulan ? 'selected' : '' }}>{{ $bulan }}</option>
                             @endforeach
                        </select>
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="mb-3">
                        <label for="peranPetugas" class="form-label">Peran Petugas</label>
                        <select class="form-select @error('peran_petugas') is-invalid @enderror" id="peranPetugas" name="peran_petugas" required>
                            <option value="" selected disabled>-- Pilih Peran --</option>
                            <option value="PML" {{ old('peran_petugas') == 'PML' ? 'selected' : '' }}>PML</option>
                            <option value="PCL" {{ old('peran_petugas') == 'PCL' ? 'selected' : '' }}>PCL</option>
                            <option value="Petugas" {{ old('peran_petugas') == 'Petugas' ? 'selected' : '' }}>Petugas</option>
                        </select>
                        <div class="invalid-feedback"></div>
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