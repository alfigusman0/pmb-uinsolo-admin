<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="py-3 breadcrumb-wrapper mb-4">Export Kelulusan</h4>

    <!-- Notification -->
    <?php $this->load->view('layout/notification'); ?>

    <div class="row">
        <div class="col-md-12">
            <form method="POST" action="<?=base_url('daftar/export/ukuran-baju/export')?>" target="_blank" id="form" enctype="multipart/form-data">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-12 mb-2">
                            <label for="tahun" class="form-label">Tahun</label>
                            <select name="tahun" id="tahun" class="form-select" required>
                                <option value="Semua">Semua</option>
                                <?php foreach ($tahun as $a) : ?>
                                    <option value="<?= $a->tahun ?>"><?= $a->tahun ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-12 mb-2">
                            <label for="submit" class="form-label">Status Submit</label>
                            <select name="submit" id="submit" class="form-select" required>
                                <option value="SEMUA">Semua</option>
                                <option value="SUDAH">SUDAH</option>
                                <option value="BELUM">BELUM</option>
                            </select>
                        </div>
                        <div class="col-12 mb-2">
                            <label for="pembayaran" class="form-label">Status Pembayaran</label>
                            <select name="pembayaran" id="pembayaran" class="form-select" required>
                                <option value="SEMUA">Semua</option>
                                <option value="SUDAH">SUDAH</option>
                                <option value="BELUM">BELUM</option>
                            </select>
                        </div>
                        <div class="col-12 mb-2">
                            <label for="pemberkasan" class="form-label">Status Pemberkasan</label>
                            <select name="pemberkasan" id="pemberkasan" class="form-select" required>
                                <option value="SEMUA">Semua</option>
                                <option value="SUDAH">SUDAH</option>
                                <option value="BELUM">BELUM</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <button class="btn btn-primary float-end" type="submit" id="buttonExport">Export</button>
                        </div>
                    </div>
                </div>
            </div>
            <?= form_close() ?>
        </div>
    </div>
</div>