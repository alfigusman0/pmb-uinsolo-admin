<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="py-3 breadcrumb-wrapper mb-4">Cek Sanggah</h4>

    <!-- Notification -->
    <?php $this->load->view('layout/notification'); ?>

    <div class="row">
        <div class="col-md-6">
            <?= form_open_multipart('kelulusan/import/cek-sanggah') ?>
            <div class="card">
                <div class="card-body">
                    <div class="input-group">
                        <input type="hidden" name="check" value="upload">
                        <input type="file" class="form-control" name="userfile" id="fileUpload" aria-describedby="buttonUpload" aria-label="Upload" required />
                        <button class="btn btn-primary" type="submit" onclick="return confirm('Apakah anda yakin cek kelulusan?')" id="buttonUpload">Upload</button>
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
                        <li>Download template cek sanggah yang sudah disediakan.</li>
                        <li>Isi kolom pada tabel sesuai nama kolomnya.</li>
                        <li>Pilih file yang ingin di upload.</li>
                        <li>klik import.</li>
                    </ol>
                    <a href="<?= base_url('file/template_cek_sanggah_mandiri.xlsx') ?>" class="btn btn-info" target="_blank">Download Template</a>
                </div>
            </div>
        </div>
        <?php if ($reportHtml != null) : ?>
        <div class="col-md-12 mt-4">
            <div class="card">
                <div class="card-body">
                    <div class='table-responsive'>
                        <table id="dataTabel" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nomor Peserta</th>
                                    <th>Nama</th>
                                    <th>Kelulusan</th>
                                    <th>Sanggah</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?= $reportHtml ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>No</th>
                                    <th>Nomor Peserta</th>
                                    <th>Nama</th>
                                    <th>Kelulusan</th>
                                    <th>Sanggah</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>