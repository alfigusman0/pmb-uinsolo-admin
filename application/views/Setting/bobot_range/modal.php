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
                <input type="hidden" value="" name="ids_bobot_range_ukt"/> 
                <div class="row">
                    <div class="col mb-3">
                        <label for="kategori" class="form-label">Kategori</label>
                        <select id="kategori" name="kategori" class="form-control">
                            <option value="">-- Pilih --</option>
                            <option value="K1">K1</option>
                            <option value="K2">K2</option>
                            <option value="K3">K3</option>
                            <option value="K4">K4</option>
                            <option value="K5">K5</option>
                            <option value="K6">K6</option>
                            <option value="K7">K7</option>
                        </select>
                        <span class="invalid-feedback"></span>
                    </div>
                </div>
                <div class="row">
                    <div class="col mb-3">
                        <label for="nilai_min" class="form-label">Nilai Min</label>
                        <input type="text" id="nilai_min" name="nilai_min" class="form-control">
                        <span class="invalid-feedback"></span>
                    </div>
                </div>
                <div class="row">
                    <div class="col mb-3">
                        <label for="nilai_max" class="form-label">Nilai Max</label>
                        <input type="text" id="nilai_max " name="nilai_max" class="form-control">
                        <span class="invalid-feedback"></span>
                    </div>
                </div>
                <div class="row">
                    <div class="col mb-3">
                        <label for="ids_jalur_masuk" class="form-label">Jalur Masuk</label>
                        <select id="ids_jalur_masuk" name="ids_jalur_masuk" class="form-control">
                            <option value="">-- Pilih --</option>
                            <?php foreach($tbsJalurMasuk->data->data as $a): ?>
                                <option value="<?=$a->ids_jalur_masuk?>"><?=$a->alias?></option>
                            <?php endforeach; ?>
                        </select>
                        <span class="invalid-feedback"></span>
                    </div>
                </div>
                <div class="row">
                    <div class="col mb-3">
                        <label for="tahun" class="form-label">Tahun</label>
                        <select id="tahun" name="tahun" class="form-control">
                            <option value="">-- Pilih --</option>
                            <?php foreach($tahun as $a): ?>
                                <option value="<?=$a->tahun?>"><?=$a->tahun?></option>
                            <?php endforeach; ?>
                        </select>
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

<!-- Generate -->
<div class="modal fade" id="modal_form_generate">
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
            <form method="POST" action="#" id="formGenerate" enctype="multipart/form-data">
            <div class="modal-body" style="overflow:hidden;">
                <input type="hidden" value="" name="ids_bobot_range"/>
                <div class="row">
                    <div class="col mb-3">
                        <label for="ids_jalur_masuk" class="form-label">Jalur Masuk</label>
                        <select id="ids_jalur_masuk" name="ids_jalur_masuk" class="form-control">
                            <option value="">-- Pilih --</option>
                            <?php foreach($tbsJalurMasuk->data->data as $a): ?>
                                <option value="<?=$a->ids_jalur_masuk?>"><?=$a->alias?></option>
                            <?php endforeach; ?>
                        </select>
                        <span class="invalid-feedback"></span>
                    </div>
                </div>
                <div class="row">
                    <div class="col mb-3">
                        <label for="tahun" class="form-label">Tahun</label>
                        <select id="tahun" name="tahun" class="form-control">
                            <option value="">-- Pilih --</option>
                            <?php foreach($tahun as $a): ?>
                                <option value="<?=$a->tahun?>"><?=$a->tahun?></option>
                            <?php endforeach; ?>
                        </select>
                        <span class="invalid-feedback"></span>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">
                    Tutup
                </button>
                <button type="button" id="btnGenerate" onclick="act_generate()" class="btn btn-primary">Generate</button>
            </div>
            </form>
        </div>
    </div>
</div>

<!-- Simpan -->
<div class="modal fade" id="modal_form_simpan">
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
            <div class="modal-body" style="overflow:hidden;">
                Apakah anda yakin simpan setting?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">
                    Tutup
                </button>
                <button type="button" id="btnSimpanSetting" onclick="act_simpan_setting()" class="btn btn-primary">Simpan</button>
            </div>
        </div>
    </div>
</div>