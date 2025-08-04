<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="py-3 breadcrumb-wrapper mb-4">Tidak Lulus Kelulusan Ujian Mandiri</h4>

    <!-- Notification -->
    <?php $this->load->view('layout/notification'); ?>

    <div class="row">
        <div class="col-md-12">
            <form method="POST" action="<?= base_url('kelulusan/tidak-lulus/generate') ?>" id="form" enctype="multipart/form-data">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12 mb-2">
                                <label for="ids_program" class="form-label">Program</label>
                                <select name="ids_program" id="ids_program" class="form-select">
                                </select>
                            </div>
                            <div class="col-12 mb-2">
                                <label for="ids_tipe_ujian" class="form-label">Tipe Ujian</label>
                                <select name="ids_tipe_ujian[]" id="ids_tipe_ujian" class="form-select" style="height: 150px" required multiple>
                                </select>
                            </div>
                            <div class="col-12 mb-2">
                                <label for="tahun" class="form-label">Tahun</label>
                                <select name="tahun" id="tahun" class="form-select" required>
                                    <?php foreach ($tahun as $a) : ?>
                                        <option value="<?= $a->tahun ?>"><?= $a->tahun ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-12">
                                <button class="btn btn-primary float-end" type="submit" id="buttonGenerate">Submit</button>
                            </div>
                        </div>
                    </div>
                </div>
                <?= form_close() ?>
        </div>
    </div>
</div>