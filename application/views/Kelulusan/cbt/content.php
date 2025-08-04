<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="py-3 breadcrumb-wrapper mb-4">Tarik Nilai CBT</h4>

    <!-- Notification -->
    <?php $this->load->view('layout/notification'); ?>

    <div class="row">
        <div class="col-md-12">
            <form method="POST" action="<?= base_url('kelulusan/cbt/generate') ?>" id="form" enctype="multipart/form-data">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12 mb-2">
                                <label for="program" class="form-label">Program</label>
                                <select name="program" id="program" class="form-select" required>
                                    <option value="">-- Pilih --</option>
                                    <option value="1">S1</option>
                                    <option value="2">S2</option>
                                    <option value="3">S3</option>
                                </select>
                            </div>
                            <div class="col-12 mb-2">
                                <label for="tahun" class="form-label">Tahun</label>
                                <select name="tahun" id="tahun" class="form-select" required>
                                    <option value="">-- Pilih --</option>
                                    <?php foreach ($tahun as $a) : ?>
                                        <option value="<?=$a->tahun?>"><?=$a->tahun?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-12 mb-2">
                                <label for="tanggal" class="form-label">Tanggal</label>
                                <select name="tanggal" id="tanggal" class="form-select">
                                    <option value="">-- Semua --</option>
                                </select>
                            </div>
                            <div class="col-12">
                                <button class="btn btn-primary float-end" type="submit" id="buttonGenerate">Generate</button>
                            </div>
                        </div>
                    </div>
                </div>
                <?= form_close() ?>
        </div>
    </div>
</div>