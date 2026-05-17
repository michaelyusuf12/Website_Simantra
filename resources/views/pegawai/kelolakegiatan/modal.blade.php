<div class="modal fade" id="modalKelolaKegiatan" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl" style="max-width: 80%;">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 15px;">
            {{-- HEADER: Dibuat melengkung, judul kiri + ikon, tombol X putih --}}
            <div class="modal-header bg-primary text-white py-3" style="border-radius: 15px 15px 0 0;">
                <h5 class="modal-title fw-bold" id="modalTitle">
                    <i class="bi bi-pencil-square me-2"></i> Form Penugasan
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <form id="formKelolaKegiatan" action="" method="POST">
                @csrf
                <input type="hidden" name="_method" id="formMethod">
                <input type="hidden" id="editPenugasanId" value="">
                <div class="modal-body p-4 bg-light">
                    
                    {{-- SEKSI 1: DATA SURAT --}}
                    <div class="card border-0 shadow-sm mb-4" style="border: 1px solid #dee2e6 !important;">
                        <div class="card-body">
                            <h6 class="fw-bold mb-3 text-primary"><i class="bi bi-file-earmark-text me-2"></i>Data Surat</h6>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="small fw-bold">Pilih Mitra</label>
                                    <select name="mitra_id" id="idMitra" class="form-select select2" required>
                                        <option value="">-- Pilih Mitra --</option>
                                        @foreach($mitras as $m)
                                            <option value="{{ $m->sobat_id ?? $m->id_mitra }}" data-posisi="{{ $m->posisi_petugas }}">
                                                {{ $m->nama_petugas }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="small fw-bold">Bulan Penugasan</label>
                                    <select id="bulanKegiatan" name="bulan_penugasan" class="form-select" required>
                                        <option value="" disabled selected>--- Pilih Bulan ---</option>
                                        @foreach (['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'] as $bulan)
                                            <option value="{{ $bulan }}">{{ $bulan }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-12 mt-3">
                                    <div class="p-2 px-3 mb-0 d-flex align-items-center rounded" style="background-color: #f8f9fa; border: 1px dashed #0d6efd;">
                                        <i class="bi bi-info-circle-fill me-2 fs-5 text-primary"></i>
                                        <span id="infoBatas" class="small text-dark">Info Batas Maksimum : Silahkan pilih mitra dan Bulan.</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- SEKSI 2: RINCIAN KEGIATAN --}}
                    <div class="card border-0 shadow-sm mb-4" style="border: 1px solid #dee2e6 !important;">
                        <div class="card-body">
                            <h6 class="fw-bold mb-3 text-primary"><i class="bi bi-list-check me-2"></i>Rincian Kegiatan (Detail)</h6>
                            <div class="table-responsive">
                                <table class="table table-bordered align-middle" id="tableRincian">
                                    <thead class="table-light text-center small fw-bold">
                                        <tr>
                                            <th style="width: 250px;">Kegiatan</th>
                                            <th style="width: 120px;">Peran</th>
                                            <th style="width: 130px;">Tanggal Mulai</th>
                                            <th style="width: 130px;">Tanggal Selesai</th>
                                            <th style="width: 100px;">Volume</th>
                                            <th style="width: 100px;">Satuan</th>
                                            <th style="width: 150px;">Harga (Rp)</th>
                                            <th style="width: 150px;">Subtotal</th>
                                            <th style="width: 50px;">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tbodyRincian">
                                        <tr class="row-kegiatan">
                                            <td>
                                                <select name="kegiatan_id[]" class="form-select form-select-sm select-kegiatan" required>
                                                    <option value="">-- Pilih --</option>
                                                    @foreach($kegiatans as $k)
                                                        <option value="{{ $k->id_kegiatan ?? $k->id }}" 
                                                                data-pcl="{{ $k->honor_pcl_per_dokumen }}" 
                                                                data-pml="{{ $k->honor_pml_per_dokumen }}"
                                                                data-pengolahan="{{ $k->honor_pengolahan_per_dokumen }}"
                                                                data-satuan="Dokumen"
                                                                data-jenis="{{ $k->jenis_kegiatan }}"> {{ $k->nama_kegiatan }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td>
                                                <select name="peran[]" class="form-select form-select-sm select-peran" required>
                                                    <option value="PCL">PCL</option>
                                                    <option value="PML">PML</option>
                                                    <option value="Pengolahan">Pengolahan</option>
                                                </select>
                                            </td>
                                            <td><input type="date" name="tanggal_mulai[]" class="form-control form-control-sm" required></td>
                                            <td><input type="date" name="tanggal_selesai[]" class="form-control form-control-sm" required></td>
                                            <td><input type="number" name="volume[]" class="form-control form-control-sm input-volume" value="0"></td>
                                            <td><input type="text" name="satuan[]" class="form-control form-control-sm input-satuan bg-light" value="Dokumen" readonly></td>
                                            <td><input type="text" class="form-control form-control-sm input-harga bg-light text-end" value="0" readonly></td>
                                            <td><input type="text" class="form-control form-control-sm input-subtotal bg-light text-end fw-bold" value="0" readonly></td>
                                            <td class="text-center">
                                                <button type="button" class="btn btn-sm btn-outline-danger btn-hapus-baris"><i class="bi bi-trash"></i></button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <button type="button" class="btn btn-outline-primary btn-sm w-100 mt-2 fw-bold" id="btnAddRow">
                                <i class="bi bi-plus-lg"></i> Tambah Baris Kegiatan Lain
                            </button>
                        </div>
                    </div>

                    {{-- SEKSI 3: TOTAL --}}
                    <div class="card border-0 shadow-sm" style="border: 1px solid #dee2e6 !important;">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-md-6">
                                    <h6 class="small fw-bold text-muted mb-1">Total Honor Surat Ini :</h6>
                                    <h3 class="fw-bold text-primary mb-0" id="displayTotal">Rp 0</h3>
                                </div>
                                <div class="col-md-6 text-end">
                                    <div class="d-inline-block p-2 px-3 rounded bg-white border">
                                        <span class="small fw-bold">Status : <span id="statusPagu" class="text-success">Aman ( Tersisa Rp 0 )</span></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-white border-top py-3">
                    <button type="button" class="btn btn-secondary px-4 fw-bold shadow-sm" data-bs-dismiss="modal">BATAL</button>
                    <button type="submit" class="btn btn-success px-4 fw-bold shadow-sm" id="btnSimpan"><i class="bi bi-save me-1"></i> SIMPAN PENUGASAN</button>
                </div>
            </form>
        </div>
    </div>
</div>