<!-- Tambah -->
<div class="modal fade" id="modal_form">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel1"></h5>
                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                    aria-label="Close"
                ></button>
            </div>
            <form method="POST" action="#" id="form" enctype="multipart/form-data">
            <div class="modal-body" style="overflow:hidden;">
                <input type="hidden" value="" name="ids_bobot_jurusan"/> 
                <div class="row">
                    <div class="col mb-3">
                        <label for="kode_jurusan" class="form-label">Jurusan</label>
                        <select id="kode_jurusan" name="kode_jurusan" class="form-control">
                            <option value="">-- Pilih --</option>
                            <?php foreach($jurusan->data->data as $a): ?>
                                <option value="<?=$a->kode_jurusan?>"><?=$a->jurusan?></option>
                            <?php endforeach; ?>
                        </select>
                        <span class="invalid-feedback"></span>
                    </div>
                </div>
                <div class="row">
                    <div class="col mb-3">
                        <label for="tpa" class="form-label">TPA</label>
                        <input type="number" id="tpa" name="tpa" class="form-control">
                        <span class="invalid-feedback"></span>
                    </div>
                </div>
                <div class="row">
                    <div class="col mb-3">
                        <label for="ips" class="form-label">IPS</label>
                        <input type="number" id="ips " name="ips" class="form-control">
                        <span class="invalid-feedback"></span>
                    </div>
                </div>
                <div class="row">
                    <div class="col mb-3">
                        <label for="ipa" class="form-label">IPA</label>
                        <input type="number" id="ipa " name="ipa" class="form-control">
                        <span class="invalid-feedback"></span>
                    </div>
                </div>
                <div class="row">
                    <div class="col mb-3">
                        <label for="btq" class="form-label">BTQ</label>
                        <input type="number" id="btq " name="btq" class="form-control">
                        <span class="invalid-feedback"></span>
                    </div>
                </div>
                <div class="row">
                    <div class="col mb-3">
                        <label for="tkd" class="form-label">TKD</label>
                        <input type="number" id="tkd " name="tkd" class="form-control">
                        <span class="invalid-feedback"></span>
                    </div>
                </div>
                <div class="row">
                    <div class="col mb-3">
                        <label for="keislaman" class="form-label">Keislaman</label>
                        <input type="number" id="keislaman " name="keislaman" class="form-control">
                        <span class="invalid-feedback"></span>
                    </div>
                </div>
                <div class="row">
                    <div class="col mb-3">
                        <label for="bhs_arab" class="form-label">B. Arab</label>
                        <input type="number" id="bhs_arab " name="bhs_arab" class="form-control">
                        <span class="invalid-feedback"></span>
                    </div>
                </div>
                <div class="row">
                    <div class="col mb-3">
                        <label for="bhs_inggris" class="form-label">B. Inggris</label>
                        <input type="number" id="bhs_inggris " name="bhs_inggris" class="form-control">
                        <span class="invalid-feedback"></span>
                    </div>
                </div>
                <div class="row">
                    <div class="col mb-3">
                        <label for="bhs_indonesia" class="form-label">B. Indonesia</label>
                        <input type="number" id="bhs_indonesia " name="bhs_indonesia" class="form-control">
                        <span class="invalid-feedback"></span>
                    </div>
                </div>
                <div class="row">
                    <div class="col mb-3">
                        <label for="pembagi" class="form-label">Pembagi</label>
                        <input type="number" id="pembagi " name="pembagi" class="form-control">
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