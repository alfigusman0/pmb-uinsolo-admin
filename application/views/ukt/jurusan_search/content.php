<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="py-3 breadcrumb-wrapper mb-4">Daftar Users UKT</h4>
    <p>
        <?php $this->load->view('layout/notification'); ?>
    </p>
    <p>
    <div class="card">
        <div class="card-header header-elements">
            <span class="me-2 h5">Tabel Users UKT(<?= $tahun . '/' . $kategori ?>)</span>
        </div>
        <div class="card-datatable table-responsive pt-0">
            <table id="dataTabel" class="datatables-basic table table-bordered">
                <thead>
                    <tr>
                        <th>Action</th>
                        <th>Nomor Peserta</th>
                        <th>Nama</th>
                        <th>Fakultas</th>
                        <th>Jurusan</th>
                        <th>Jalur Masuk</th>
                        <th>Skore</th>
                        <th>Nominal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($viewDaftarUKT as $value) : ?>
                        <tr>
                            <td><a class="btn btn-primary" href="<?= base_url('daftar/mahasiswa/detail/' . $value->idd_kelulusan) ?>" target='_blank'>Detail</a></td>
                            <td><?= $value->nomor_peserta ?></td>
                            <td><?= $value->nama ?></td>
                            <td><?= $value->fakultas ?></td>
                            <td><?= $value->jurusan ?></td>
                            <td><?= $value->alias_jalur_masuk ?></td>
                            <td><?= $value->score ?></td>
                            <td>Rp <?= number_format($value->jumlah, 2) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th>Action</th>
                        <th>Nomor Peserta</th>
                        <th>Nama</th>
                        <th>Fakultas</th>
                        <th>Jurusan</th>
                        <th>Jalur Masuk</th>
                        <th>Skore</th>
                        <th>Nominal</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
    </p>
</div>