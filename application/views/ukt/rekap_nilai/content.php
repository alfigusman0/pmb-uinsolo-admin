<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="py-3 breadcrumb-wrapper mb-4">Rekap Nilai UKT</h4>

    <!-- Notification -->
    <?php $this->load->view('layout/notification'); ?>
    <div class="row">
        <div class="col-md-3"></div>
        <div class="col-md-6">
            <div class="card">
                <form method="POST" action="<?= base_url('ukt/rekap-nilai') ?>" id="formGenerate" enctype="multipart/form-data">
                    <div class="card-body">
                        <div class="card-title h5">Rekap Nilai UKT</div>
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
                        <span class="me-2 h5">Tabel Rekap Nilai UKT Jurusan (<?= $ids_jalur_masuk . '/' . $old_tahun ?>)</span>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th class="text-center">NO</th>
                                        <th class="text-center">NOMOR PESERTA</th>
                                        <th class="text-center">NAMA</th>
                                        <th class="text-center">JURUSAN</th>
                                        <th class="text-center">SKOR</th>
                                        <th class="text-center">KATEGORI</th>
                                        <th class="text-center">TARIF</th>
                                        <th class="text-center">DAYA LISTRIK</th>
                                        <th class="text-center">KEPEMILIKAN MOBIL</th>
                                        <th class="text-center">KEPEMILIKAN MOTOR</th>
                                        <th class="text-center">STATUS KEPEMILIKAN RUMAH</th>
                                        <th class="text-center">LUAS KEPEMILIKAN TANAH LAINNYA</th>
                                        <th class="text-center">HARGA NJOP TANAH</th>
                                        <th class="text-center">TAKSIRAN PAJAK MOBIL</th>
                                        <th class="text-center">TAKSIRAN PAJAK MOTOR</th>
                                        <th class="text-center">PENGHASILAN AYAH</th>
                                        <th class="text-center">PENGHASILAN IBU</th>
                                        <th class="text-center">PENGHASILAN WALI</th>
                                        <th class="text-center">REKENING LISTRIK</th>
                                        <th class="text-center">SURAT KETERANGAN TIDAK MAMPU</th>
                                        <th class="text-center">JUMLAH ANGGOTA KELUARGA</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $no = 1;
                                    $total = 0;
                                    foreach ($viewdUKT as $a) : ?>
                                        <tr>
                                            <td><?= $no ?></td>
                                            <td><?= $a['nomor_peserta'] ?></td>
                                            <td><?= $a['nama'] ?></td>
                                            <td><?= $a['jurusan'] ?></td>
                                            <td><?= $a['score'] ?></td>
                                            <td><?= $a['kategori'] ?></td>
                                            <td><?= number_format($a['jumlah'], 2) ?></td>
                                            <td><?= $a['daya_listrik'] ?></td>
                                            <td><?= $a['kepemilikan_mobil'] ?></td>
                                            <td><?= $a['kepemilikan_motor'] ?></td>
                                            <td><?= $a['kepemilikan_rumah'] ?></td>
                                            <td><?= $a['lktl'] ?></td>
                                            <td><?= $a['njop'] ?></td>
                                            <td><?= $a['pajak_mobil'] ?></td>
                                            <td><?= $a['pajak_motor'] ?></td>
                                            <td><?= $a['penghasilan_ayah'] ?></td>
                                            <td><?= $a['penghasilan_ibu'] ?></td>
                                            <td><?= $a['penghasilan_wali'] ?></td>
                                            <td><?= $a['rekening_listrik'] ?></td>
                                            <td><?= $a['sktm'] ?></td>
                                            <td><?= $a['tanggungan'] ?></td>
                                        </tr>
                                    <?php $no++;
                                        $total += $a['jumlah'];
                                    endforeach ?>
                                </tbody>
                                <tfoot>
                                    <th class="text-center" colspan="6">TOTAL</th>
                                    <td colspan="14">Rp <?= number_format($total, 2) ?></td>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>