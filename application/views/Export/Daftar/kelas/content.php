<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="py-3 breadcrumb-wrapper mb-4">Export Absen</h4>

    <!-- Notification -->
    <?php $this->load->view('layout/notification'); ?>

    <div class="row">
        <div class="col-md-12">
            <form method="POST" action="<?= base_url('daftar/export/kelas/export') ?>" target="_blank" id="form" enctype="multipart/form-data">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
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
                                <label for="ids_fakultas" class="form-label">Fakultas</label>
                                <select name="ids_fakultas" id="ids_fakultas" class="form-select" required>
                                    <option value="">-- Pilih --</option>
                                    <?php foreach ($fakultas->data->data as $a) : ?>
                                        <option value="<?= $a->fakultas ?>"><?= $a->fakultas ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-12 mb-2">
                                <label for="kode_jurusan" class="form-label">Jurusan / Prodi</label>
                                <select name="kode_jurusan" id="kode_jurusan" class="form-select" data-style="btn-default" required>
                                    <option value="">-- Pilih --</option>
                                </select>
                                <input type="hidden" name="jenjang" id="jenjang" require>
                            </div>
                            <div class="col-12 mb-2">
                                <label for="jenis" class="form-label">Jenis</label>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <span class="input-group-text" id="basic-addon11">
                                                <input
                                                    class="form-check-input"
                                                    type="radio"
                                                    name="jenis"
                                                    value="PDF"
                                                    id="jenis1"
                                                    checked />
                                            </span>
                                            <input
                                                type="text"
                                                class="form-control"
                                                value="PDF"
                                                readonly="" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <span class="input-group-text" id="basic-addon11">
                                                <input
                                                    class="form-check-input"
                                                    type="radio"
                                                    name="jenis"
                                                    value="EXCEL"
                                                    id="jenis0" />
                                            </span>
                                            <input
                                                type="text"
                                                class="form-control"
                                                value="Excel"
                                                readonly="" />
                                        </div>
                                    </div>
                                </div>
                                <span class="invalid-feedback"></span>
                            </div>
                            <div class="col-12">
                                <button type="submit" id="btnExport" class="btn btn-primary float-end">Export</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>