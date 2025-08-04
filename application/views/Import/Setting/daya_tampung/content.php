<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="py-3 breadcrumb-wrapper mb-4">Daya Tampung
        <a href="<?= base_url('setting/import/pengurangan-kuota') ?>" class="btn btn-primary btn-sm float-end" target="_blank">Pengurangan Kuota</a>
    </h4>

    <!-- Notification -->
    <?php $this->load->view('layout/notification'); ?>

    <div class="row">
        <div class="col-md-6">
            <?= form_open_multipart('setting/import/daya-tampung/import') ?>
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col mb-3">
                            <label for="tahun" class="form-label">Tahun</label>
                            <select name="tahun" id="tahun" class="form-control" required>
                                <?php foreach ($tahun as $a) : ?>
                                    <option value="<?= $a->tahun ?>"><?= $a->tahun ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col mb-3">
                            <label for="ids_jalur_masuk" class="form-label">Jalur Masuk</label>
                            <select id="ids_jalur_masuk " name="ids_jalur_masuk" data-style="btn-default" class="form-control">
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
                            <label for="fileUpload" class="form-label">File</label>
                            <input type="file" class="form-control" name="userfile" id="fileUpload" aria-describedby="buttonUpload" aria-label="Upload" required />
                        </div>
                    </div>
                    <div class="row">
                        <div class="col mb-3">
                            <button class="btn btn-primary" type="submit" onclick="return confirm('Apakah sudah sesuai ?')" id="buttonUpload">Upload</button>
                        </div>
                    </div>
                </div>
            </div>
            <?= form_close() ?>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <p>Tatacara import data : (Only Windows)</p>
                    <ol>
                        <li>Download template yang sudah disediakan.</li>
                        <li>Isi kolom pada tabel sesuai nama kolomnya.</li>
                        <li>Pilih file yang ingin di upload.</li>
                        <li>klik import.</li>
                    </ol>
                    <a href="<?= base_url('file/template_daya_tampung.xlsx') ?>" class="btn btn-info" target="_blank">Download Template</a>
                </div>
            </div>
        </div>
    </div>
</div>