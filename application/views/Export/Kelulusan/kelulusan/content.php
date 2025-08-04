<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="py-3 breadcrumb-wrapper mb-4">Export Kelulusan</h4>

    <!-- Notification -->
    <?php $this->load->view('layout/notification'); ?>

    <div class="row">
        <div class="col-md-12">
            <form method="POST" action="<?=base_url('kelulusan/export/kelulusan/export')?>" target="_blank" id="form" enctype="multipart/form-data">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-12 mb-2">
                            <label for="tahun" class="form-label">Tahun</label>
                            <select name="tahun" id="tahun" class="form-select" required>
                                <option value="Semua">-- Semua --</option>
                                <?php foreach ($tahun as $a) : ?>
                                    <option value="<?= $a->tahun ?>"><?= $a->tahun ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-12 mb-2">
                            <label for="jenjang" class="form-label">Jenjang</label>
                            <select name="jenjang" id="jenjang" class="form-select">
                                <option value="">-- Semua --</option>
                                <option value="S1">S1</option>
                                <option value="S2">S2</option>
                                <option value="S3">S3</option>
                            </select>
                        </div>
                        <div class="col-12 mb-2">
                            <label for="ids_fakultas" class="form-label">Fakultas</label>
                            <select name="ids_fakultas" id="ids_fakultas" class="form-select">
                                <option value="">-- Semua --</option>
                            </select>
                        </div>
                        <div class="col-12 mb-2">
                            <label for="ids_tipe_ujian" class="form-label">Tipe Ujian</label>
                            <select name="ids_tipe_ujian" id="ids_tipe_ujian" class="form-select">
                                <option value="">-- Semua --</option>
                            </select>
                        </div>
                        <?php if($this->jwt->ids_level <= 2): ?>
                        <div class="col-12 mb-2">
                            <label for="nama_penitip" class="form-label">Nama Penitip</label>
                            <select name="nama_penitip" id="nama_penitip" class="form-select" required>
                                <option value="Semua">-- Semua --</option>
                                <?php foreach ($nama_penitip as $a) : ?>
                                    <option value="<?= $a->nama_penitip ?>"><?= $a->nama_penitip ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-12 mb-2">
                            <label for="keterangan" class="form-label">Keterangan</label>
                            <select name="keterangan" id="keterangan" class="form-select" required>
                                <option value="Semua">-- Semua --</option>
                                <?php foreach ($keterangan as $a) : ?>
                                    <option value="<?= $a->keterangan ?>"><?= $a->keterangan ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <?php endif; ?>
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