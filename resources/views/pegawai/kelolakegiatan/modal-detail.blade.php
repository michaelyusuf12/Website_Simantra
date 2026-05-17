{{-- MODAL LIHAT DETAIL --}}
<div class="modal fade" id="modalDetailPenugasan" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl" style="max-width: 80%;">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 15px;">
            <div class="modal-header bg-primary text-white py-3" style="border-radius: 15px 15px 0 0;">
                <h5 class="modal-title fw-bold"><i class="bi bi-file-earmark-text me-2"></i>Detail Surat Perjanjian Kerja</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <div class="modal-body p-4 bg-light">
                {{-- Info Header Surat --}}
                <div class="card border-0 shadow-sm mb-4" style="border: 1px solid #dee2e6 !important;">
                    <div class="card-body">
                        <h6 class="fw-bold mb-3 text-primary"><i class="bi bi-info-circle me-2"></i>Informasi Umum</h6>
                        <div class="row">
                            <div class="col-md-6">
                                <table class="table table-borderless table-sm mb-0">
                                    <tr><td style="width: 130px;" class="fw-bold text-secondary">Nomor SPK</td><td style="width: 10px;">:</td><td class="fw-bold text-primary" id="detailNoSurat">-</td></tr>
                                    <tr><td class="fw-bold text-secondary">Nama Mitra</td><td>:</td><td id="detailNamaMitra" class="fw-bold text-dark">-</td></tr>
                                    <tr><td class="fw-bold text-secondary">Bulan Tugas</td><td>:</td><td id="detailBulan">-</td></tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <table class="table table-borderless table-sm mb-0">
                                    <tr><td style="width: 130px;" class="fw-bold text-secondary">Tanggal Input</td><td style="width: 10px;">:</td><td id="detailTanggalSurat">-</td></tr>
                                    <tr><td class="fw-bold text-secondary">Status Kontrak</td><td>:</td><td><span class="badge bg-warning text-dark" id="detailStatus">-</span></td></tr>
                                    <tr><td class="fw-bold text-secondary">Total Honor</td><td>:</td><td class="fw-bold text-success" id="detailTotalHonor">-</td></tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Tabel Rincian Kegiatan --}}
                <div class="card border-0 shadow-sm" style="border: 1px solid #dee2e6 !important;">
                    <div class="card-body">
                        <h6 class="fw-bold mb-3 text-primary"><i class="bi bi-list-check me-2"></i>Daftar Rincian Pekerjaan:</h6>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover align-middle mb-0">
                                <thead class="table-light text-center small fw-bold">
                                    <tr>
                                        <th style="width: 50px;">No</th>
                                        <th>Nama Kegiatan</th>
                                        <th>Peran</th>
                                        <th>Tgl Mulai</th>
                                        <th>Tgl Selesai</th>
                                        <th>Volume</th>
                                        <th>Satuan</th>
                                        <th>Harga (Rp)</th>
                                        <th>Subtotal (Rp)</th>
                                    </tr>
                                </thead>
                                <tbody id="tbodyDetailRincian">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer bg-white border-top py-3">
                <button type="button" class="btn btn-secondary px-4 fw-bold shadow-sm" data-bs-dismiss="modal">TUTUP</button>
            </div>
        </div>
    </div>
</div>