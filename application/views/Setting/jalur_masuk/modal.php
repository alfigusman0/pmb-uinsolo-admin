<div class="modal fade" id="modal_form">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="#" id="form" enctype="multipart/form-data">
                <div class="modal-body" style="overflow:hidden;">
                    <input type="hidden" value="" id="ids_jalur_masuk" name="ids_jalur_masuk" />
                    <div class="row">
                        <div class="col mb-3">
                            <label for="id_salam" class="form-label">ID Salam</label>
                            <input type="number" id="id_salam" name="id_salam" class="form-control">
                            <span class="invalid-feedback"></span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col mb-3">
                            <label for="jalur_masuk" class="form-label">Jalur Masuk</label>
                            <input type="text" id="jalur_masuk" name="jalur_masuk" class="form-control">
                            <span class="invalid-feedback"></span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col mb-3">
                            <label for="alias" class="form-label">Alias / Singkatan</label>
                            <input type="text" id="alias" name="alias" class="form-control">
                            <span class="invalid-feedback"></span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col mb-3">
                            <label for="nourut" class="form-label">No. Urut</label>
                            <input type="number" id="nourut" name="nourut" class="form-control">
                            <span class="invalid-feedback"></span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6 mb-3">
                            <label for="pendaftaran_awal" class="form-label">Pendaftaran Awal</label>
                            <input type="datetime-local" id="pendaftaran_awal" name="pendaftaran_awal" class="form-control" required>
                            <span class="invalid-feedback"></span>
                        </div>
                        <div class="col-6 mb-3">
                            <label for="pendaftaran_akhir" class="form-label">Pendaftaran Akhir</label>
                            <input type="datetime-local" id="pendaftaran_akhir" name="pendaftaran_akhir" class="form-control" required>
                            <span class="invalid-feedback"></span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6 mb-3">
                            <label for="ukt_awal" class="form-label">UKT Awal</label>
                            <input type="datetime-local" id="ukt_awal" name="ukt_awal" class="form-control" required>
                            <span class="invalid-feedback"></span>
                        </div>
                        <div class="col-6 mb-3">
                            <label for="ukt_akhir" class="form-label">UKT Akhir</label>
                            <input type="datetime-local" id="ukt_akhir" name="ukt_akhir" class="form-control" required>
                            <span class="invalid-feedback"></span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6 mb-3">
                            <label for="pembayaran_awal" class="form-label">Pembayaran Awal</label>
                            <input type="datetime-local" id="pembayaran_awal" name="pembayaran_awal" class="form-control" required>
                            <span class="invalid-feedback"></span>
                        </div>
                        <div class="col-6 mb-3">
                            <label for="pembayaran_akhir" class="form-label">Pembayaran Akhir</label>
                            <input type="datetime-local" id="pembayaran_akhir" name="pembayaran_akhir" class="form-control" required>
                            <span class="invalid-feedback"></span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6 mb-3">
                            <label for="pemberkasan_awal" class="form-label">Pemberkasan Awal</label>
                            <input type="datetime-local" id="pemberkasan_awal" name="pemberkasan_awal" class="form-control" required>
                            <span class="invalid-feedback"></span>
                        </div>
                        <div class="col-6 mb-3">
                            <label for="pemberkasan_akhir" class="form-label">Pemberkasan Akhir</label>
                            <input type="datetime-local" id="pemberkasan_akhir" name="pemberkasan_akhir" class="form-control" required>
                            <span class="invalid-feedback"></span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col mb-3">
                            <label for="status" class="form-label">Status</label>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <span class="input-group-text" id="basic-addon11">
                                            <input class="form-check-input" type="radio" name="status" value="YA" id="status1" />
                                        </span>
                                        <input type="text" class="form-control" value="Tampilkan" readonly="" />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <span class="input-group-text" id="basic-addon11">
                                            <input class="form-check-input" type="radio" name="status" value="TIDAK" id="status0" />
                                        </span>
                                        <input type="text" class="form-control" value="Sembuyikan" readonly="" />
                                    </div>
                                </div>
                            </div>
                            <span class="invalid-feedback"></span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">
                        Tutup
                    </button>
                    <button type="button" id="btnSave" onclick="save()" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>