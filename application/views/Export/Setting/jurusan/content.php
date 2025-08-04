<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="py-3 breadcrumb-wrapper mb-4">Export Jurusan</h4>

    <!-- Notification -->
    <?php $this->load->view('layout/notification'); ?>

    <div class="row">
        <div class="col-md-12">
            <form method="POST" action="<?=base_url('setting/export/jurusan/export')?>" target="_blank" id="form" enctype="multipart/form-data">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-12 mb-2">
                            <label for="ids_fakultas" class="form-label">Fakultas</label>
                            <select name="ids_fakultas" id="ids_fakultas" class="w-100 select2" data-style="btn-default" required>
                                <option value="SEMUA">Semua</option>
                                <?php foreach ($tbsFakultas->data->data as $a) : ?>
                                    <option value="<?= $a->ids_fakultas ?>"><?= $a->fakultas ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <!-- <div class="col-12 mb-2">
                            <label for="kode_jurusan" class="form-label">Jurusan</label>
                            <select name="kode_jurusan" id="kode_jurusan" class="w-100 select2" data-style="btn-default" required>
                                <option value="SEMUA">Semua</option>
                            </select>
                        </div> -->
                        <div class="col-12 mb-2">
                            <label for="status" class="form-label">Status</label>
                            <select name="status" id="status" class="form-select" required>
                                <option value="SEMUA">Semua</option>
                                <option value="YA">YA</option>
                                <option value="TIDAK">Tidak</option>
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