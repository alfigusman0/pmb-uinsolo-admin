<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="py-3 breadcrumb-wrapper mb-4">Export Berita Acara</h4>

    <!-- Notification -->
    <?php $this->load->view('layout/notification'); ?>

    <div class="row">
        <div class="col-md-12">
            <form method="POST" action="<?=base_url('mandiri/export/berita-acara/export')?>" target="_blank" id="form" enctype="multipart/form-data">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                    <div class="col-12 mb-2">
                            <label for="ids_program" class="form-label">Program</label>
                            <select name="ids_program" id="ids_program" class="form-select" required>
                                <option value="">-- Pilih --</option>
                            </select>
                        </div>
                        <div class="col-12 mb-2">
                            <label for="ids_tipe_ujian" class="form-label">Tipe Ujian</label>
                            <select name="ids_tipe_ujian" id="ids_tipe_ujian" class="form-select" required>
                                <option value="">-- Pilih --</option>
                            </select>
                        </div>
                        <div class="col-12 mb-2">
                            <label for="tahun" class="form-label">Tahun</label>
                            <select name="tahun" id="tahun" class="form-select" required>
                                <option value="">-- Pilih --</option>
                                <?php foreach ($tahun as $a) : ?>
                                    <option value="<?= $a->tahun ?>"><?= $a->tahun ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-12 mb-2">
                            <label for="tanggal" class="form-label">Tanggal</label>
                            <select name="tanggal" id="tanggal" class="form-select" required>
                                <option value="">-- Pilih --</option>
                            </select>
                        </div>
                        <div class="col-12 mb-2">
                            <label for="jam" class="form-label">Jam</label>
                            <select name="jam" id="jam" class="form-select" required>
                                <option value="">-- Pilih --</option>
                            </select>
                        </div>
                        <div class="col-12 mb-2">
                            <label for="ids_area" class="form-label">Area</label>
                            <select name="ids_area" id="ids_area" class="form-select" required>
                                <option value="">-- Pilih --</option>
                            </select>
                        </div>
                        <div class="col-12 mb-2">
                            <label for="ids_gedung" class="form-label">Gedung</label>
                            <select name="ids_gedung" id="ids_gedung" class="form-select" required>
                                <option value="">-- Pilih --</option>
                            </select>
                        </div>
                        <div class="col-12 mb-2">
                            <label for="ids_ruangan" class="form-label">Ruangan</label>
                            <select name="ids_ruangan" id="ids_ruangan" class="form-select" required>
                                <option value="">-- Pilih --</option>
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