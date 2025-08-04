<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="py-3 breadcrumb-wrapper mb-4">Kelulusan</h4>

    <!-- Notification -->
    <?php $this->load->view('layout/notification'); ?>

    <div class="row">
        <div class="col-md-6">
            <?= form_open_multipart('daftar/import/kelulusan/import') ?>
            <div class="card">
                <div class="card-body">
                    <label for="jalur_masuk" class="form-label">Jalur Masuk</label>
                    <select name="ids_jalur_masuk" id="jalur_masuk" class="selectpicker w-100" data-style="btn-default" required>
                        <?php foreach ($tbsJalurMasuk->data->data as $a) : ?>
                            <option value="<?= $a->ids_jalur_masuk ?>"><?= $a->alias ?></option>
                        <?php endforeach; ?>
                    </select> <br />
                    <div class="input-group">
                        <input type="file" class="form-control" name="userfile" id="fileUpload" aria-describedby="buttonUpload" aria-label="Upload" required />
                        <button class="btn btn-primary" type="submit" onclick="return confirm('Apakah jalur masuk sudah sesuai ?')" id="buttonUpload">Upload</button>
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
                        <li>Download template kelulusan yang sudah disediakan.</li>
                        <li>Isi kolom pada tabel sesuai nama kolomnya.</li>
                        <li>Pilih jalur masuk dan pilih file yang ingin di upload.</li>
                        <li>klik import.</li>
                    </ol>
                    <a href="<?= base_url('file/template_kelulusan.xlsx') ?>" class="btn btn-info" target="_blank">Download Template</a>
                </div>
            </div>
        </div>
    </div>
</div>