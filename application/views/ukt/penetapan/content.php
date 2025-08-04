<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="py-3 breadcrumb-wrapper mb-4">Penetapan UKT</h4>

    <!-- Notification -->
    <?php $this->load->view('layout/notification'); ?>

    <div class="card">
        <div class="card-header header-elements">
            <span class="me-2 h5">Penetapan UKT</span>
        </div>
        <form method="POST" action="#" id="formGenerate" enctype="multipart/form-data">
            <div class="card-body">
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
            <div class="card-footer">
                <button type="button" id="btnGenerate" onclick="swa()" class="btn btn-primary">Generate</button>
            </div>
        </form>
    </div>
</div>