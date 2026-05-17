{{-- MODAL PREVIEW KONTRAK MENGGUNAKAN IFRAME --}}
<div class="modal fade" id="modalDetail" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl" style="max-width: 90%;">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title fw-bold"><i class="bi bi-file-earmark-text me-2"></i>Preview Draf Kontrak (SPK)</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            {{-- Body modal diatur tinggi 75vh agar lebar ke bawah --}}
            <div class="modal-body p-0 bg-light" style="height: 75vh;">
                {{-- Layar Iframe untuk memuat halaman cetak --}}
                <iframe id="iframePreviewKontrak" src="" style="width: 100%; height: 100%; border: none;"></iframe>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup Preview</button>
            </div>
        </div>
    </div>
</div>