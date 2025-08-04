<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="py-3 breadcrumb-wrapper mb-4">Afirmasi</h4>

    <!-- Notification -->
    <?php $this->load->view('layout/notification'); ?>

    <div class="row">
        <div class="col-md-6">
            <?= form_open_multipart('kelulusan/import/afirmasi/import') ?>
            <div class="card">
                <div class="card-body">
                    <label for="tahun" class="form-label">Tahun</label>
                    <select name="tahun" id="tahun" class="selectpicker w-100" data-style="btn-default" required>
                        <?php foreach ($tahun as $a) : ?>
                            <option value="<?= $a->tahun ?>"><?= $a->tahun ?></option>
                        <?php endforeach; ?>
                    </select> <br />
                    <div class="input-group">
                        <input type="file" class="form-control" name="userfile" id="fileUpload" aria-describedby="buttonUpload" aria-label="Upload" required />
                        <button class="btn btn-primary" type="submit" onclick="return confirm('Apakah anda yakin import afirmasi?')" id="buttonUpload">Upload</button>
                    </div>
                </div>
            </div>
            <?= form_close() ?>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <p>Tatacara import data: (Only Windows)</p>
                    <ol>
                        <li>Download template yang sudah disediakan.</li>
                        <li>Isi kolom pada tabel sesuai nama kolomnya.</li>
                        <li>Pilih file yang ingin di upload.</li>
                        <li>klik import.</li>
                    </ol>
                    <a href="<?= base_url('file/template_kelulusan_mandiri.xlsx') ?>" class="btn btn-info" target="_blank">Download Template</a>
                </div>
            </div>
        </div>
        <?php $error = $this->session->flashdata('error');
        if ($error > 0) : ?>
            <div class="col-md-12 mt-4">
                <div class="card">
                    <div class="card-body">
                        <?= $this->session->flashdata('errorHtml') ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>