<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="py-3 breadcrumb-wrapper mb-4">Statistik UKT</h4>

    <!-- Notification -->
    <?php $this->load->view('layout/notification'); ?>
    <div class="row">
        <div class="col-md-3"></div>
        <div class="col-md-6">
            <div class="card">
                <form method="POST" action="<?= base_url('ukt/rekap-jurusan') ?>" id="formGenerate" enctype="multipart/form-data">
                    <div class="card-body">
                        <div class="card-title h5">Statistik UKT Jurusan</div>
                        <div class="row">
                            <div class="col mb-3">
                                <label for="ids_jalur_masuk" class="form-label">Jalur Masuk</label>
                                <select id="ids_jalur_masuk" name="ids_jalur_masuk" class="form-control">
                                    <option value="">-- Pilih --</option>
                                    <?php foreach ($tbsJalurMasuk->data->data as $a) : ?>
                                        <option value="<?= $a->ids_jalur_masuk ?>"><?= $a->alias ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <span class="invalid-feedback"></span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col mb-3">
                                <label for="tahun" class="form-label">Tahun</label>
                                <select id="tahun" name="tahun" class="form-control">
                                    <option value="">-- Pilih --</option>
                                    <?php foreach ($tahun as $a) : ?>
                                        <option value="<?= $a->tahun ?>"><?= $a->tahun ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <span class="invalid-feedback"></span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col mb-3">
                                <button type="submit" class="btn btn-primary float-end"><i class="bx bx-show"></i>Show</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="col-md-3"></div>
    </div>

    <?php if ($kosong == FALSE) : ?>
        <div class="row mt-4">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header header-elements">
                        <span class="me-2 h5">Tabel Statistik UKT Jurusan (<?= $old_tahun ?>)</span>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-responsive">
                                <thead>
                                    <tr>
                                        <th class="text-center">Jurusan</th>
                                        <th class="text-center">K1</th>
                                        <th class="text-center">K2</th>
                                        <th class="text-center">K3</th>
                                        <th class="text-center">K4</th>
                                        <th class="text-center">K5</th>
                                        <th class="text-center">K6</th>
                                        <th class="text-center">K7</th>
                                        <th class="text-center">K8</th>
                                        <th class="text-center">K9</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php for ($i = 0; $i <= 8; $i++) {
                                        $jumlah[$i] = 0;
                                    } ?>
                                    <?php foreach ($tbsJurusan->data->data as $a) : ?>
                                        <?php if ($a->kode_jurusan != 999) : ?>
                                            <tr>
                                                <td><?= $a->jurusan; ?></td>
                                                <?php for ($i = 0; $i <= 8; $i++) : ?>
                                                    <td class="text-center"><?= $count[$a->kode_jurusan][$i] ?></td>
                                                    <?php $jumlah[$i] += $count[$a->kode_jurusan][$i]; ?>
                                                <?php endfor; ?>
                                            </tr>
                                        <?php endif ?>
                                    <?php endforeach ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th class="text-center">Jurusan</th>
                                        <th class="text-center">K1</th>
                                        <th class="text-center">K2</th>
                                        <th class="text-center">K3</th>
                                        <th class="text-center">K4</th>
                                        <th class="text-center">K5</th>
                                        <th class="text-center">K6</th>
                                        <th class="text-center">K7</th>
                                        <th class="text-center">K8</th>
                                        <th class="text-center">K9</th>
                                    </tr>
                                    <tr>
                                        <th>Jumlah</th>
                                        <?php $total = 0;
                                        for ($i = 0; $i < 9; $i++) : $k = 'K' . ($i + 1); ?>
                                            <?php if ($jumlah[$i] > 0) : ?>
                                                <th class="text-center">
                                                    <a href="<?= base_url('ukt/rekap-jurusan/search/' . $old_tahun . '/' . $ids_jalur_masuk . '/' . $k) ?>" target="_blank">
                                                        <?= $jumlah[$i]; ?>
                                                    </a>
                                                </th>
                                            <?php else : ?>
                                                <th class="text-center"><?= $jumlah[$i]; ?></th>
                                            <?php endif; ?>

                                            <?php $total += $jumlah[$i]; ?>
                                        <?php endfor; ?>
                                    </tr>
                                    <tr>
                                        <th>Total</th>
                                        <th colspan="9" class="text-center"><?= $total ?></th>
                                    </tr>
                                    <tr>
                                        <th>Persentase</th>
                                        <?php $totalpersentase = 0;
                                        for ($i = 0; $i <= 8; $i++) { ?>
                                            <th class="text-center"><?php if ($jumlah[$i] != 0) {
                                                                        echo round((($jumlah[$i] / $total) * 100), 2);
                                                                    } else {
                                                                        echo 0;
                                                                    }; ?> %</th>
                                        <?php if ($jumlah[$i] != 0) {
                                                $totalpersentase += (($jumlah[$i] / $total) * 100);
                                            }
                                        } ?>
                                    </tr>
                                    <tr>
                                        <th>Total Persentase</th>
                                        <th colspan="9" class="text-center"><?= $totalpersentase ?>%</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>