<div class="container-xxl flex-grow-1 container-p-y">
    <!-- Notification -->
    <?php $this->load->view('layout/notification'); ?>

    <div class="row" id="container-statistik">
        <div class="col-md-6">
            <div class="card mt-4">
                <div class="card-header header-elements">
                    <div class="card-title h5 mb-0">Statistik Tipe Ujian</div>
                    <div class="card-header-elements ms-auto">
                        <button type="button" id="btn-refresh-tipe_ujian" class="btn btn-xs btn-danger">
                            <span class="tf-icon bx bx-refresh bx-xs">&nbsp;</span>Refresh Off
                        </button>
                        <button type="button" class="btn btn-xs btn-primary" data-bs-toggle="collapse" data-bs-target="#filterTipeUjian" aria-expanded="false" aria-controls="filterPerFakultas">
                            <span class="tf-icon bx bx-filter bx-xs"></span>
                        </button>
                    </div>
                </div>
                <br>
                <div class="collapse" id="filterTipeUjian">
                    <div class="border p-4">
                        <div class="row">
                            <div class="col-md-12">
                                <form class="form-horizontal" id="form-filter-tipe-ujian">
                                    <div class="row">
                                        <div class="col-12 mb-3">
                                            <label for="tahun-tipe-ujian" class="form-label">Tahun</label>
                                            <select id="tahun-tipe-ujian" name="tahun-tipe-ujian" class="form-select">
                                                <?php foreach ($tahun as $a) : ?>
                                                <option value="<?= $a->tahun ?>"><?= $a->tahun ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                            <span class="invalid-feedback"></span>
                                        </div>
                                        <div class="col-12 mb-3">
                                            <label for="jenis-kelamin-tipe-ujian" class="form-label">Jenis Kelamin</label>
                                            <select id="jenis-kelamin-tipe-ujian" name="jenis-kelamin-tipe-ujian" class="form-select">
                                                <option value="">-- Semua --</option>
                                                <option value="LAKI-LAKI">LAKI-LAKI</option>
                                                <option value="PEREMPUAN">PEREMPUAN</option>
                                            </select>
                                            <span class="invalid-feedback"></span>
                                        </div>
                                        <div class="col-12 mb-3">
                                            <label for="kebutuhan-khusus-tipe-ujian" class="form-label">Kebutuhan Khusus</label>
                                            <select id="kebutuhan-khusus-tipe-ujian" name="kebutuhan-khusus-tipe-ujian" class="form-select">
                                                <option value="">-- Semua --</option>
                                                <option value="YA">YA</option>
                                                <option value="TIDAK">TIDAK</option>
                                            </select>
                                            <span class="invalid-feedback"></span>
                                        </div>
                                        <div class="col-12 mb-3">
                                            <label for="kategori-tipe-ujian" class="form-label">Kategori</label>
                                            <select id="kategori-tipe-ujian" name="kategori-tipe-ujian" class="form-select">
                                                <option value="">-- Semua --</option>
                                                <option value="IPA">IPA</option>
                                                <option value="IPS">IPS</option>
                                            </select>
                                            <span class="invalid-feedback"></span>
                                        </div>
                                        <div class="col-12 mb-3">
                                            <label for="pembayaran-tipe-ujian" class="form-label">Pembayaran</label>
                                            <select id="pembayaran-tipe-ujian" name="pembayaran-tipe-ujian" class="form-select">
                                                <option value="">-- Semua --</option>
                                                <option value="SUDAH">SUDAH</option>
                                                <option value="BELUM">BELUM</option>
                                            </select>
                                            <span class="invalid-feedback"></span>
                                        </div>
                                        <div class="col-12 mb-3">
                                            <label for="formulir-tipe-ujian" class="form-label">Formulir</label>
                                            <select id="formulir-tipe-ujian" name="formulir-tipe-ujian" class="form-select">
                                                <option value="">-- Semua --</option>
                                                <option value="SUDAH">SUDAH</option>
                                                <option value="BELUM">BELUM</option>
                                            </select>
                                            <span class="invalid-feedback"></span>
                                        </div>
                                    </div>
                                    <div class="float-end">
                                        <button type="button" id="btn-filter-tipe-ujian" onclick="statistik_tipe_ujian()" class="btn btn-primary float-end">Filter</button>
                                        <button type="button" id="btn-reset-tipe-ujian" onclick="reset_tipe_ujian()" class="btn btn-default float-end">Reset</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body table-responsive">
                    <table id="dataTabelTipeUjian" class="datatables-basic table table-bordered">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Tipe Ujian</th>
                                <th>Jumlah</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card mt-4">
                <div class="card-header header-elements">
                    <div class="card-title h5 mb-0">Statistik Agama</div>
                    <div class="card-header-elements ms-auto">
                        <button type="button" id="btn-refresh-agama" class="btn btn-xs btn-danger">
                            <span class="tf-icon bx bx-refresh bx-xs">&nbsp;</span>Refresh Off
                        </button>
                        <button type="button" class="btn btn-xs btn-primary" data-bs-toggle="collapse" data-bs-target="#filterAgama" aria-expanded="false" aria-controls="filterPerFakultas">
                            <span class="tf-icon bx bx-filter bx-xs"></span>
                        </button>
                    </div>
                </div>
                <div class="collapse" id="filterAgama">
                    <div class="border p-4">
                        <div class="row">
                            <div class="col-md-12">
                                <form class="form-horizontal" id="form-filter-agama">
                                    <div class="row">
                                        <div class="col-12 mb-3">
                                            <label for="tahun-agama" class="form-label">Tahun</label>
                                            <select id="tahun-agama" name="tahun-agama" class="form-select">
                                                <?php foreach ($tahun as $a) : ?>
                                                <option value="<?= $a->tahun ?>"><?= $a->tahun ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                            <span class="invalid-feedback"></span>
                                        </div>
                                        <div class="col-12 mb-3">
                                            <label for="jenis-kelamin-agama" class="form-label">Jenis Kelamin</label>
                                            <select id="jenis-kelamin-agama" name="jenis-kelamin-agama" class="form-select">
                                                <option value="">-- Semua --</option>
                                                <option value="LAKI-LAKI">LAKI-LAKI</option>
                                                <option value="PEREMPUAN">PEREMPUAN</option>
                                            </select>
                                            <span class="invalid-feedback"></span>
                                        </div>
                                        <div class="col-12 mb-3">
                                            <label for="kebutuhan-khusus-agama" class="form-label">Kebutuhan Khusus</label>
                                            <select id="kebutuhan-khusus-agama" name="kebutuhan-khusus-agama" class="form-select">
                                                <option value="">-- Semua --</option>
                                                <option value="YA">YA</option>
                                                <option value="TIDAK">TIDAK</option>
                                            </select>
                                            <span class="invalid-feedback"></span>
                                        </div>
                                        <div class="col-12 mb-3">
                                            <label for="kategori-agama" class="form-label">Kategori</label>
                                            <select id="kategori-agama" name="kategori-agama" class="form-select">
                                                <option value="">-- Semua --</option>
                                                <option value="IPA">IPA</option>
                                                <option value="IPS">IPS</option>
                                            </select>
                                            <span class="invalid-feedback"></span>
                                        </div>
                                        <div class="col-12 mb-3">
                                            <label for="pembayaran-agama" class="form-label">Pembayaran</label>
                                            <select id="pembayaran-agama" name="pembayaran-agama" class="form-select">
                                                <option value="">-- Semua --</option>
                                                <option value="SUDAH">SUDAH</option>
                                                <option value="BELUM">BELUM</option>
                                            </select>
                                            <span class="invalid-feedback"></span>
                                        </div>
                                        <div class="col-12 mb-3">
                                            <label for="formulir-agama" class="form-label">Formulir</label>
                                            <select id="formulir-agama" name="formulir-agama" class="form-select">
                                                <option value="">-- Semua --</option>
                                                <option value="SUDAH">SUDAH</option>
                                                <option value="BELUM">BELUM</option>
                                            </select>
                                            <span class="invalid-feedback"></span>
                                        </div>
                                    </div>
                                    <div class="float-end">
                                        <button type="button" id="btn-filter-agama" onclick="statistik_agama()" class="btn btn-primary float-end">Filter</button>
                                        <button type="button" id="btn-reset-agama" onclick="reset_agama()" class="btn btn-default float-end">Reset</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body table-responsive">
                    <table id="dataTabelAgama" class="datatables-basic table table-bordered">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Agama</th>
                                <th>Jumlah</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card mt-4">
                <div class="card-header header-elements">
                    <div class="card-title h5 mb-0">Statistik Provinsi</div>
                    <div class="card-header-elements ms-auto">
                        <button type="button" id="btn-refresh-provinsi" class="btn btn-xs btn-danger">
                            <span class="tf-icon bx bx-refresh bx-xs">&nbsp;</span>Refresh Off
                        </button>
                        <button type="button" class="btn btn-xs btn-primary" data-bs-toggle="collapse" data-bs-target="#filterProvinsi" aria-expanded="false" aria-controls="filterPerFakultas">
                            <span class="tf-icon bx bx-filter bx-xs"></span>
                        </button>
                    </div>
                </div>
                <div class="collapse" id="filterProvinsi">
                    <div class="border p-4">
                        <div class="row">
                            <div class="col-md-12">
                                <form class="form-horizontal" id="form-filter-provinsi">
                                    <div class="row">
                                        <div class="col-12 mb-3">
                                            <label for="tahun-provinsi" class="form-label">Tahun</label>
                                            <select id="tahun-provinsi" name="tahun-provinsi" class="form-select">
                                                <?php foreach ($tahun as $a) : ?>
                                                <option value="<?= $a->tahun ?>"><?= $a->tahun ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                            <span class="invalid-feedback"></span>
                                        </div>
                                        <div class="col-12 mb-3">
                                            <label for="kategori-provinsi" class="form-label">Kategori</label>
                                            <select id="kategori-provinsi" name="kategori-provinsi" class="form-select">
                                                <option value="">-- Semua --</option>
                                                <option value="IPA">IPA</option>
                                                <option value="IPS">IPS</option>
                                            </select>
                                            <span class="invalid-feedback"></span>
                                        </div>
                                        <div class="col-12 mb-3">
                                            <label for="tipe-ujian-provinsi" class="form-label">Tipe Ujian</label>
                                            <select id="tipe-ujian-provinsi" name="tipe-ujian-provinsi" class="form-select">
                                                <option value="">-- Semua --</option>
                                                <?php foreach ($tbsTipeUjian as $a) : ?>
                                                <option value="<?= $a->ids_tipe_ujian ?>"><?= $a->tipe_ujian ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                            <span class="invalid-feedback"></span>
                                        </div>
                                    </div>
                                    <div class="float-end">
                                        <button type="button" id="btn-filter-provinsi" onclick="statistik_provinsi()" class="btn btn-primary float-end">Filter</button>
                                        <button type="button" id="btn-reset-provinsi" onclick="reset_provinsi()" class="btn btn-default float-end">Reset</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body table-responsive">
                    <table id="dataTabelProvinsi" class="datatables-basic table table-bordered">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Provinsi</th>
                                <th>Jumlah</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card mt-4">
                <div class="card-header header-elements">
                    <div class="card-title h5 mb-0">Statistik Kecamatan</div>
                    <div class="card-header-elements ms-auto">
                        <button type="button" id="btn-refresh-kecamatan" class="btn btn-xs btn-danger">
                            <span class="tf-icon bx bx-refresh bx-xs">&nbsp;</span>Refresh Off
                        </button>
                        <button type="button" class="btn btn-xs btn-primary" data-bs-toggle="collapse" data-bs-target="#filterKecamatan" aria-expanded="false" aria-controls="filterPerFakultas">
                            <span class="tf-icon bx bx-filter bx-xs"></span>
                        </button>
                    </div>
                </div>
                <div class="collapse" id="filterKecamatan">
                    <div class="border p-4">
                        <div class="row">
                            <div class="col-md-12">
                                <form class="form-horizontal" id="form-filter-kecamatan">
                                    <div class="row">
                                        <div class="col-12 mb-3">
                                            <label for="tahun-kecamatan" class="form-label">Tahun</label>
                                            <select id="tahun-kecamatan" name="tahun-kecamatan" class="form-select">
                                                <?php foreach ($tahun as $a) : ?>
                                                <option value="<?= $a->tahun ?>"><?= $a->tahun ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                            <span class="invalid-feedback"></span>
                                        </div>
                                        <div class="col-12 mb-3">
                                            <label for="kategori-kecamatan" class="form-label">Kategori</label>
                                            <select id="kategori-kecamatan" name="kategori-kecamatan" class="form-select">
                                                <option value="">-- Semua --</option>
                                                <option value="IPA">IPA</option>
                                                <option value="IPS">IPS</option>
                                            </select>
                                            <span class="invalid-feedback"></span>
                                        </div>
                                        <div class="col-12 mb-3">
                                            <label for="tipe-ujian-kecamatan" class="form-label">Tipe Ujian</label>
                                            <select id="tipe-ujian-kecamatan" name="tipe-ujian-kecamatan" class="form-select">
                                                <option value="">-- Semua --</option>
                                                <?php foreach ($tbsTipeUjian as $a) : ?>
                                                <option value="<?= $a->ids_tipe_ujian ?>"><?= $a->tipe_ujian ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                            <span class="invalid-feedback"></span>
                                        </div>
                                    </div>
                                    <div class="float-end">
                                        <button type="button" id="btn-filter-kecamatan" onclick="statistik_kecamatan()" class="btn btn-primary float-end">Filter</button>
                                        <button type="button" id="btn-reset-kecamatan" onclick="reset_kecamatan()" class="btn btn-default float-end">Reset</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body table-responsive">
                    <table id="dataTabelKecamatan" class="datatables-basic table table-bordered">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Kecamatan</th>
                                <th>Jumlah</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card mt-4">
                <div class="card-header header-elements">
                    <div class="card-title h5 mb-0">Statistik Rumpun</div>
                    <div class="card-header-elements ms-auto">
                        <button type="button" id="btn-refresh-rumpun" class="btn btn-xs btn-danger">
                            <span class="tf-icon bx bx-refresh bx-xs">&nbsp;</span>Refresh Off
                        </button>
                        <button type="button" class="btn btn-xs btn-primary" data-bs-toggle="collapse" data-bs-target="#filterRumpun" aria-expanded="false" aria-controls="filterPerFakultas">
                            <span class="tf-icon bx bx-filter bx-xs"></span>
                        </button>
                    </div>
                </div>
                <div class="collapse" id="filterRumpun">
                    <div class="border p-4">
                        <div class="row">
                            <div class="col-md-12">
                                <form class="form-horizontal" id="form-filter-rumpun">
                                    <div class="row">
                                        <div class="col-12 mb-3">
                                            <label for="tahun-rumpun" class="form-label">Tahun</label>
                                            <select id="tahun-rumpun" name="tahun-rumpun" class="form-select">
                                                <?php foreach ($tahun as $a) : ?>
                                                <option value="<?= $a->tahun ?>"><?= $a->tahun ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                            <span class="invalid-feedback"></span>
                                        </div>
                                        <div class="col-12 mb-3">
                                            <label for="kategori-rumpun" class="form-label">Kategori</label>
                                            <select id="kategori-rumpun" name="kategori-rumpun" class="form-select">
                                                <option value="">-- Semua --</option>
                                                <option value="IPA">IPA</option>
                                                <option value="IPS">IPS</option>
                                            </select>
                                            <span class="invalid-feedback"></span>
                                        </div>
                                        <div class="col-12 mb-3">
                                            <label for="tipe_ujian-rumpun" class="form-label">Tipe Ujian</label>
                                            <select id="tipe_ujian-rumpun" name="tipe_ujian-rumpun" class="form-select">
                                                <option value="">-- Semua --</option>
                                                <?php foreach ($tipe_ujian as $a) : ?>
                                                <option value="<?= $a->ids_tipe_ujian ?>"><?= $a->tipe_ujian ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                            <span class="invalid-feedback"></span>
                                        </div>
                                        <div class="col-12 mb-3">
                                            <label for="akreditasi-rumpun" class="form-label">Akreditasi</label>
                                            <select id="akreditasi-rumpun" name="akreditasi-rumpun" class="form-select">
                                                <option value="">-- Semua --</option>
                                                <option value="A">A</option>
                                                <option value="B">B</option>
                                                <option value="C">C</option>
                                                <option value="Belum Akreditasi">Belum Akreditasi</option>
                                                <option value="Belum Terakreditasi">Belum Terakreditasi</option>
                                            </select>
                                            <span class="invalid-feedback"></span>
                                        </div>
                                    </div>
                                    <div class="float-end">
                                        <button type="button" id="btn-filter-rumpun" onclick="statistik_rumpun()" class="btn btn-primary float-end">Filter</button>
                                        <button type="button" id="btn-reset-rumpun" onclick="reset_rumpun()" class="btn btn-default float-end">Reset</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body table-responsive">
                    <table id="dataTabelRumpun" class="datatables-basic table table-bordered">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Rumpun</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card mt-4">
                <div class="card-header header-elements">
                    <div class="card-title h5 mb-0">Statistik Jenis Sekolah</div>
                    <div class="card-header-elements ms-auto">
                        <button type="button" id="btn-refresh-jenis_sekolah" class="btn btn-xs btn-danger">
                            <span class="tf-icon bx bx-refresh bx-xs">&nbsp;</span>Refresh Off
                        </button>
                        <button type="button" class="btn btn-xs btn-primary" data-bs-toggle="collapse" data-bs-target="#filterJenisSekolah" aria-expanded="false" aria-controls="filterPerFakultas">
                            <span class="tf-icon bx bx-filter bx-xs"></span>
                        </button>
                    </div>
                </div>
                <div class="collapse" id="filterJenisSekolah">
                    <div class="border p-4">
                        <div class="row">
                            <div class="col-md-12">
                                <form class="form-horizontal" id="form-filter-jenis_sekolah">
                                    <div class="row">
                                        <div class="col-12 mb-3">
                                            <label for="tahun-jenis_sekolah" class="form-label">Tahun</label>
                                            <select id="tahun-jenis_sekolah" name="tahun-jenis_sekolah" class="form-select">
                                                <?php foreach ($tahun as $a) : ?>
                                                <option value="<?= $a->tahun ?>"><?= $a->tahun ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                            <span class="invalid-feedback"></span>
                                        </div>
                                        <div class="col-12 mb-3">
                                            <label for="kategori-jenis_sekolah" class="form-label">Kategori</label>
                                            <select id="kategori-jenis_sekolah" name="kategori-jenis_sekolah" class="form-select">
                                                <option value="">-- Semua --</option>
                                                <option value="IPA">IPA</option>
                                                <option value="IPS">IPS</option>
                                            </select>
                                            <span class="invalid-feedback"></span>
                                        </div>
                                        <div class="col-12 mb-3">
                                            <label for="tipe_ujian-jenis_sekolah" class="form-label">Tipe Ujian</label>
                                            <select id="tipe_ujian-jenis_sekolah" name="tipe_ujian-jenis_sekolah" class="form-select">
                                                <option value="">-- Semua --</option>
                                                <?php foreach ($tipe_ujian as $a) : ?>
                                                <option value="<?= $a->ids_tipe_ujian ?>"><?= $a->tipe_ujian ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                            <span class="invalid-feedback"></span>
                                        </div>
                                        <div class="col-12 mb-3">
                                            <label for="akreditasi-jenis_sekolah" class="form-label">Akreditasi</label>
                                            <select id="akreditasi-jenis_sekolah" name="akreditasi-jenis_sekolah" class="form-select">
                                                <option value="">-- Semua --</option>
                                                <option value="A">A</option>
                                                <option value="B">B</option>
                                                <option value="C">C</option>
                                                <option value="Belum Akreditasi">Belum Akreditasi</option>
                                                <option value="Belum Terakreditasi">Belum Terakreditasi</option>
                                            </select>
                                            <span class="invalid-feedback"></span>
                                        </div>
                                    </div>
                                    <div class="float-end">
                                        <button type="button" id="btn-filter-jenis_sekolah" onclick="statistik_jenis_sekolah()" class="btn btn-primary float-end">Filter</button>
                                        <button type="button" id="btn-reset-jenis_sekolah" onclick="reset_jenis_sekolah()" class="btn btn-default float-end">Reset</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body table-responsive">
                    <table id="dataTabelJenisSekolah" class="datatables-basic table table-bordered">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Jenis Sekolah</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card mt-4">
                <div class="card-header header-elements">
                    <div class="card-title h5 mb-0">Statistik Jurusan Sekolah</div>
                    <div class="card-header-elements ms-auto">
                        <button type="button" id="btn-refresh-jenis_sekolah" class="btn btn-xs btn-danger">
                            <span class="tf-icon bx bx-refresh bx-xs">&nbsp;</span>Refresh Off
                        </button>
                        <button type="button" class="btn btn-xs btn-primary" data-bs-toggle="collapse" data-bs-target="#filterJurusanSekolah" aria-expanded="false" aria-controls="filterPerFakultas">
                            <span class="tf-icon bx bx-filter bx-xs"></span>
                        </button>
                    </div>
                </div>
                <div class="collapse" id="filterJurusanSekolah">
                    <div class="border p-4">
                        <div class="row">
                            <div class="col-md-12">
                                <form class="form-horizontal" id="form-filter-jurusan_sekolah">
                                    <div class="row">
                                        <div class="col-12 mb-3">
                                            <label for="tahun-jurusan_sekolah" class="form-label">Tahun</label>
                                            <select id="tahun-jurusan_sekolah" name="tahun-jurusan_sekolah" class="form-select">
                                                <?php foreach ($tahun as $a) : ?>
                                                <option value="<?= $a->tahun ?>"><?= $a->tahun ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                            <span class="invalid-feedback"></span>
                                        </div>
                                        <div class="col-12 mb-3">
                                            <label for="kategori-jurusan_sekolah" class="form-label">Kategori</label>
                                            <select id="kategori-jurusan_sekolah" name="kategori-jurusan_sekolah" class="form-select">
                                                <option value="">-- Semua --</option>
                                                <option value="IPA">IPA</option>
                                                <option value="IPS">IPS</option>
                                            </select>
                                            <span class="invalid-feedback"></span>
                                        </div>
                                        <div class="col-12 mb-3">
                                            <label for="tipe_ujian-jurusan_sekolah" class="form-label">Tipe Ujian</label>
                                            <select id="tipe_ujian-jurusan_sekolah" name="tipe_ujian-jurusan_sekolah" class="form-select">
                                                <option value="">-- Semua --</option>
                                                <?php foreach ($tipe_ujian as $a) : ?>
                                                <option value="<?= $a->ids_tipe_ujian ?>"><?= $a->tipe_ujian ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                            <span class="invalid-feedback"></span>
                                        </div>
                                        <div class="col-12 mb-3">
                                            <label for="akreditasi-jurusan_sekolah" class="form-label">Akreditasi</label>
                                            <select id="akreditasi-jurusan_sekolah" name="akreditasi-jurusan_sekolah" class="form-select">
                                                <option value="">-- Semua --</option>
                                                <option value="A">A</option>
                                                <option value="B">B</option>
                                                <option value="C">C</option>
                                                <option value="Belum Akreditasi">Belum Akreditasi</option>
                                                <option value="Belum Terakreditasi">Belum Terakreditasi</option>
                                            </select>
                                            <span class="invalid-feedback"></span>
                                        </div>
                                    </div>
                                    <div class="float-end">
                                        <button type="button" id="btn-filter-jurusan_sekolah" onclick="statistik_jurusan_sekolah()" class="btn btn-primary float-end">Filter</button>
                                        <button type="button" id="btn-reset-jurusan_sekolah" onclick="reset_jurusan_sekolah()" class="btn btn-default float-end">Reset</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body table-responsive">
                    <table id="dataTabelJurusanSekolah" class="datatables-basic table table-bordered">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Jurusan Sekolah</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card mt-4">
                <div class="card-header header-elements">
                    <div class="card-title h5 mb-0">Statistik Program</div>
                    <div class="card-header-elements ms-auto">
                        <button type="button" id="btn-refresh-program" class="btn btn-xs btn-danger">
                            <span class="tf-icon bx bx-refresh bx-xs">&nbsp;</span>Refresh Off
                        </button>
                        <button type="button" class="btn btn-xs btn-primary" data-bs-toggle="collapse" data-bs-target="#filterProgram" aria-expanded="false" aria-controls="filterPerFakultas">
                            <span class="tf-icon bx bx-filter bx-xs"></span>
                        </button>
                    </div>
                </div>
                <div class="collapse" id="filterProgram">
                    <div class="border p-4">
                        <div class="row">
                            <div class="col-md-12">
                                <form class="form-horizontal" id="form-filter-program">
                                    <div class="row">
                                        <div class="col-12 mb-3">
                                            <label for="tahun-program" class="form-label">Tahun</label>
                                            <select id="tahun-program" name="tahun-program" class="form-select">
                                                <?php foreach ($tahun as $a) : ?>
                                                <option value="<?= $a->tahun ?>"><?= $a->tahun ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                            <span class="invalid-feedback"></span>
                                        </div>
                                        <div class="col-12 mb-3">
                                            <label for="jenis-kelamin-program" class="form-label">Jenis Kelamin</label>
                                            <select id="jenis-kelamin-program" name="jenis-kelamin-program" class="form-select">
                                                <option value="">-- Semua --</option>
                                                <option value="LAKI-LAKI">LAKI-LAKI</option>
                                                <option value="PEREMPUAN">PEREMPUAN</option>
                                            </select>
                                            <span class="invalid-feedback"></span>
                                        </div>
                                        <div class="col-12 mb-3">
                                            <label for="kebutuhan-khusus-program" class="form-label">Kebutuhan Khusus</label>
                                            <select id="kebutuhan-khusus-program" name="kebutuhan-khusus-program" class="form-select">
                                                <option value="">-- Semua --</option>
                                                <option value="YA">YA</option>
                                                <option value="TIDAK">TIDAK</option>
                                            </select>
                                            <span class="invalid-feedback"></span>
                                        </div>
                                        <div class="col-12 mb-3">
                                            <label for="kategori-program" class="form-label">Kategori</label>
                                            <select id="kategori-program" name="kategori-program" class="form-select">
                                                <option value="">-- Semua --</option>
                                                <option value="IPA">IPA</option>
                                                <option value="IPS">IPS</option>
                                            </select>
                                            <span class="invalid-feedback"></span>
                                        </div>
                                        <div class="col-12 mb-3">
                                            <label for="pembayaran-program" class="form-label">Pembayaran</label>
                                            <select id="pembayaran-program" name="pembayaran-program" class="form-select">
                                                <option value="">-- Semua --</option>
                                                <option value="SUDAH">SUDAH</option>
                                                <option value="BELUM">BELUM</option>
                                            </select>
                                            <span class="invalid-feedback"></span>
                                        </div>
                                        <div class="col-12 mb-3">
                                            <label for="formulir-program" class="form-label">Formulir</label>
                                            <select id="formulir-program" name="formulir-program" class="form-select">
                                                <option value="">-- Semua --</option>
                                                <option value="SUDAH">SUDAH</option>
                                                <option value="BELUM">BELUM</option>
                                            </select>
                                            <span class="invalid-feedback"></span>
                                        </div>
                                    </div>
                                    <div class="float-end">
                                        <button type="button" id="btn-filter-program" onclick="statistik_program()" class="btn btn-primary float-end">Filter</button>
                                        <button type="button" id="btn-reset-program" onclick="reset_program()" class="btn btn-default float-end">Reset</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body table-responsive">
                    <table id="dataTabelProgram" class="datatables-basic table table-bordered">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Program</th>
                                <th>Jumlah</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card mt-4">
                <div class="card-header header-elements">
                    <div class="card-title h5 mb-0">Statistik Negara</div>
                    <div class="card-header-elements ms-auto">
                        <button type="button" id="btn-refresh-negara" class="btn btn-xs btn-danger">
                            <span class="tf-icon bx bx-refresh bx-xs">&nbsp;</span>Refresh Off
                        </button>
                        <button type="button" class="btn btn-xs btn-primary" data-bs-toggle="collapse" data-bs-target="#filterNegara" aria-expanded="false" aria-controls="filterPerFakultas">
                            <span class="tf-icon bx bx-filter bx-xs"></span>
                        </button>
                    </div>
                </div>
                <div class="collapse" id="filterNegara">
                    <div class="border p-4">
                        <div class="row">
                            <div class="col-md-12">
                                <form class="form-horizontal" id="form-filter-negara">
                                    <div class="row">
                                        <div class="col-12 mb-3">
                                            <label for="tahun-negara" class="form-label">Tahun</label>
                                            <select id="tahun-negara" name="tahun-negara" class="form-select">
                                                <?php foreach ($tahun as $a) : ?>
                                                <option value="<?= $a->tahun ?>"><?= $a->tahun ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                            <span class="invalid-feedback"></span>
                                        </div>
                                        <div class="col-12 mb-3">
                                            <label for="kategori-negara" class="form-label">Kategori</label>
                                            <select id="kategori-negara" name="kategori-negara" class="form-select">
                                                <option value="">-- Semua --</option>
                                                <option value="IPA">IPA</option>
                                                <option value="IPS">IPS</option>
                                            </select>
                                            <span class="invalid-feedback"></span>
                                        </div>
                                        <div class="col-12 mb-3">
                                            <label for="tipe-ujian-negara" class="form-label">Tipe Ujian</label>
                                            <select id="tipe-ujian-negara" name="tipe-ujian-negara" class="form-select">
                                                <option value="">-- Semua --</option>
                                                <?php foreach ($tbsTipeUjian as $a) : ?>
                                                <option value="<?= $a->ids_tipe_ujian ?>"><?= $a->tipe_ujian ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                            <span class="invalid-feedback"></span>
                                        </div>
                                    </div>
                                    <div class="float-end">
                                        <button type="button" id="btn-filter-negara" onclick="statistik_negara()" class="btn btn-primary float-end">Filter</button>
                                        <button type="button" id="btn-reset-negara" onclick="reset_negara()" class="btn btn-default float-end">Reset</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body table-responsive">
                    <table id="dataTabelNegara" class="datatables-basic table table-bordered">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Negara</th>
                                <th>Jumlah</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card mt-4">
                <div class="card-header header-elements">
                    <div class="card-title h5 mb-0">Statistik Kabupaten Kota</div>
                    <div class="card-header-elements ms-auto">
                        <button type="button" id="btn-refresh-kab_kota" class="btn btn-xs btn-danger">
                            <span class="tf-icon bx bx-refresh bx-xs">&nbsp;</span>Refresh Off
                        </button>
                        <button type="button" class="btn btn-xs btn-primary" data-bs-toggle="collapse" data-bs-target="#filterKabupatenKota" aria-expanded="false" aria-controls="filterPerFakultas">
                            <span class="tf-icon bx bx-filter bx-xs"></span>
                        </button>
                    </div>
                </div>
                <div class="collapse" id="filterKabupatenKota">
                    <div class="border p-4">
                        <div class="row">
                            <div class="col-md-12">
                                <form class="form-horizontal" id="form-filter-kab_kota">
                                    <div class="row">
                                        <div class="col-12 mb-3">
                                            <label for="tahun-kab_kota" class="form-label">Tahun</label>
                                            <select id="tahun-kab_kota" name="tahun-kab_kota" class="form-select">
                                                <?php foreach ($tahun as $a) : ?>
                                                <option value="<?= $a->tahun ?>"><?= $a->tahun ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                            <span class="invalid-feedback"></span>
                                        </div>
                                        <div class="col-12 mb-3">
                                            <label for="kategori-kab_kota" class="form-label">Kategori</label>
                                            <select id="kategori-kab_kota" name="kategori-kab_kota" class="form-select">
                                                <option value="">-- Semua --</option>
                                                <option value="IPA">IPA</option>
                                                <option value="IPS">IPS</option>
                                            </select>
                                            <span class="invalid-feedback"></span>
                                        </div>
                                        <div class="col-12 mb-3">
                                            <label for="tipe-ujian-kab_kota" class="form-label">Tipe Ujian</label>
                                            <select id="tipe-ujian-kab_kota" name="tipe-ujian-kab_kota" class="form-select">
                                                <option value="">-- Semua --</option>
                                                <?php foreach ($tbsTipeUjian as $a) : ?>
                                                <option value="<?= $a->ids_tipe_ujian ?>"><?= $a->tipe_ujian ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                            <span class="invalid-feedback"></span>
                                        </div>
                                    </div>
                                    <div class="float-end">
                                        <button type="button" id="btn-filter-kab_kota" onclick="statistik_kab_kota()" class="btn btn-primary float-end">Filter</button>
                                        <button type="button" id="btn-reset-kab_kota" onclick="reset_kab_kota()" class="btn btn-default float-end">Reset</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body table-responsive">
                    <table id="dataTabelKabupatenKota" class="datatables-basic table table-bordered">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Kabupaten Kota</th>
                                <th>Jumlah</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card mt-4">
                <div class="card-header header-elements">
                    <div class="card-title h5 mb-0">Statistik Kelurahan</div>
                    <div class="card-header-elements ms-auto">
                        <button type="button" id="btn-refresh-kelurahan" class="btn btn-xs btn-danger">
                            <span class="tf-icon bx bx-refresh bx-xs">&nbsp;</span>Refresh Off
                        </button>
                        <button type="button" class="btn btn-xs btn-primary" data-bs-toggle="collapse" data-bs-target="#filterKelurahan" aria-expanded="false" aria-controls="filterPerFakultas">
                            <span class="tf-icon bx bx-filter bx-xs"></span>
                        </button>
                    </div>
                </div>
                <div class="collapse" id="filterKelurahan">
                    <div class="border p-4">
                        <div class="row">
                            <div class="col-md-12">
                                <form class="form-horizontal" id="form-filter-kelurahan">
                                    <div class="row">
                                        <div class="col-12 mb-3">
                                            <label for="tahun-kelurahan" class="form-label">Tahun</label>
                                            <select id="tahun-kelurahan" name="tahun-kelurahan" class="form-select">
                                                <?php foreach ($tahun as $a) : ?>
                                                <option value="<?= $a->tahun ?>"><?= $a->tahun ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                            <span class="invalid-feedback"></span>
                                        </div>
                                        <div class="col-12 mb-3">
                                            <label for="kategori-kelurahan" class="form-label">Kategori</label>
                                            <select id="kategori-kelurahan" name="kategori-kelurahan" class="form-select">
                                                <option value="">-- Semua --</option>
                                                <option value="IPA">IPA</option>
                                                <option value="IPS">IPS</option>
                                            </select>
                                            <span class="invalid-feedback"></span>
                                        </div>
                                        <div class="col-12 mb-3">
                                            <label for="tipe-ujian-kelurahan" class="form-label">Tipe Ujian</label>
                                            <select id="tipe-ujian-kelurahan" name="tipe-ujian-kelurahan" class="form-select">
                                                <option value="">-- Semua --</option>
                                                <?php foreach ($tbsTipeUjian as $a) : ?>
                                                <option value="<?= $a->ids_tipe_ujian ?>"><?= $a->tipe_ujian ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                            <span class="invalid-feedback"></span>
                                        </div>
                                    </div>
                                    <div class="float-end">
                                        <button type="button" id="btn-filter-kelurahan" onclick="statistik_kelurahan()" class="btn btn-primary float-end">Filter</button>
                                        <button type="button" id="btn-reset-kelurahan" onclick="reset_kelurahan()" class="btn btn-default float-end">Reset</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body table-responsive">
                    <table id="dataTabelKelurahan" class="datatables-basic table table-bordered">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Kelurahan</th>
                                <th>Jumlah</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card mt-4">
                <div class="card-header header-elements">
                    <div class="card-title h5 mb-0">Statistik Pilihan</div>
                    <div class="card-header-elements ms-auto">
                        <button type="button" id="btn-refresh-pilihan" class="btn btn-xs btn-danger">
                            <span class="tf-icon bx bx-refresh bx-xs">&nbsp;</span>Refresh Off
                        </button>
                        <button type="button" class="btn btn-xs btn-primary" data-bs-toggle="collapse" data-bs-target="#filterPilihan" aria-expanded="false" aria-controls="filterPerFakultas">
                            <span class="tf-icon bx bx-filter bx-xs"></span>
                        </button>
                    </div>
                </div>
                <div class="collapse" id="filterPilihan">
                    <div class="border p-4">
                        <div class="row">
                            <div class="col-md-12">
                                <form class="form-horizontal" id="form-filter-pilihan">
                                    <div class="row">
                                        <div class="col-12 mb-3">
                                            <label for="tahun-pilihan" class="form-label">Tahun</label>
                                            <select id="tahun-pilihan" name="tahun-pilihan" class="form-select">
                                                <?php foreach ($tahun as $a) : ?>
                                                <option value="<?= $a->tahun ?>"><?= $a->tahun ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                            <span class="invalid-feedback"></span>
                                        </div>
                                        <div class="col-12 mb-3">
                                            <label for="kategori-pilihan" class="form-label">Kategori</label>
                                            <select id="kategori-pilihan" name="kategori-pilihan" class="form-select">
                                                <option value="">-- Semua --</option>
                                                <option value="IPA">IPA</option>
                                                <option value="IPS">IPS</option>
                                            </select>
                                            <span class="invalid-feedback"></span>
                                        </div>
                                    </div>
                                    <div class="float-end">
                                        <button type="button" id="btn-filter-pilihan" onclick="statistik_pilihan()" class="btn btn-primary float-end">Filter</button>
                                        <button type="button" id="btn-reset-pilihan" onclick="reset_pilihan()" class="btn btn-default float-end">Reset</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body table-responsive">
                    <table id="dataTabelPilihan" class="datatables-basic table table-bordered">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Jurusan</th>
                                <th>Fakultas</th>
                                <th>Pilihan 1</th>
                                <th>Pilihan 2</th>
                                <th>Pilihan 3</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card mt-4">
                <div class="card-header header-elements">
                    <div class="card-title h5 mb-0">Statistik Kelulusan</div>
                    <div class="card-header-elements ms-auto">
                        <button type="button" id="btn-refresh-kelulusan" class="btn btn-xs btn-danger">
                            <span class="tf-icon bx bx-refresh bx-xs">&nbsp;</span>Refresh Off
                        </button>
                        <button type="button" class="btn btn-xs btn-primary" data-bs-toggle="collapse" data-bs-target="#filterKelulusan" aria-expanded="false" aria-controls="filterPerFakultas">
                            <span class="tf-icon bx bx-filter bx-xs"></span>
                        </button>
                    </div>
                </div>
                <div class="collapse" id="filterKelulusan">
                    <div class="border p-4">
                        <div class="row">
                            <div class="col-md-12">
                                <form class="form-horizontal" id="form-filter-kelulusan">
                                    <div class="row">
                                        <div class="col-12 mb-3">
                                            <label for="tahun-kelulusan" class="form-label">Tahun</label>
                                            <select id="tahun-kelulusan" name="tahun-kelulusan" class="form-select">
                                                <?php foreach ($tahun as $a) : ?>
                                                <option value="<?= $a->tahun ?>"><?= $a->tahun ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                            <span class="invalid-feedback"></span>
                                        </div>
                                        <div class="col-12 mb-3">
                                            <label for="kategori-kelulusan" class="form-label">Kategori</label>
                                            <select id="kategori-kelulusan" name="kategori-kelulusan" class="form-select">
                                                <option value="">-- Semua --</option>
                                                <option value="IPA">IPA</option>
                                                <option value="IPS">IPS</option>
                                            </select>
                                            <span class="invalid-feedback"></span>
                                        </div>
                                    </div>
                                    <div class="float-end">
                                        <button type="button" id="btn-filter-kelulusan" onclick="statistik_kelulusan()" class="btn btn-primary float-end">Filter</button>
                                        <button type="button" id="btn-reset-kelulusan" onclick="reset_kelulusan()" class="btn btn-default float-end">Reset</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body table-responsive">
                    <table id="dataTabelKelulusan" class="datatables-basic table table-bordered">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Kode Jurusan</th>
                                <th>Jurusan</th>
                                <th>Fakultas</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>