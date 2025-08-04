<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="py-3 breadcrumb-wrapper mb-4">Pengurangan Kuota</h4>

    <!-- Notification -->
    <?php $this->load->view('layout/notification'); ?>

    <div class="row">
        <div class="col-md-6">
            <?= form_open_multipart('setting/import/pengurangan-kuota/import') ?>
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col mb-3">
                            <label for="tahun" class="form-label">Tahun</label>
                            <select name="tahun" id="tahun" class="selectpicker w-100" data-style="btn-default" required>
                                <?php foreach ($tahun as $a) : ?>
                                    <option value="<?= $a->tahun ?>"><?= $a->tahun ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col mb-3">
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
                    <a href="<?= base_url('file/template_pengurangan_daya_tampung.xlsx') ?>" class="btn btn-info" target="_blank">Download Template</a>
                </div>
            </div>
        </div>
    </div>
</div>