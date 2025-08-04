<div class="container-xxl flex-grow-1 container-p-y">
    <!-- Notification -->
    <?php $this->load->view('layout/notification'); ?>

    <?php if (!empty($hak_akses)) : ?>
        <?php if ($hak_akses->code == 200) : ?>
            <div class="row">
                <!-- Formulir -->
                <div class="nav-align-top mb-4">
                    <ul class="nav nav-pills mb-3" role="tablist">
                        <li class="nav-item">
                            <button type="button" class="nav-link active" role="tab" id="btn-tab-semua" data-bs-toggle="tab" data-bs-target="#navs-semua" aria-controls="navs-semua" aria-selected="true">
                                Semua
                            </button>
                        </li>
                        <li class="nav-item">
                            <button type="button" class="nav-link" role="tab" id="btn-tab-mandiri" data-bs-toggle="tab" data-bs-target="#navs-mandiri" aria-controls="navs-mandiri" aria-selected="true" onclick="load_tab_mandiri(); this.onclick=null;">
                                Mandiri
                            </button>
                        </li>
                        <li class="nav-item">
                            <button type="button" class="nav-link" role="tab" id="btn-tab-internasional" data-bs-toggle="tab" data-bs-target="#navs-international-admission" aria-controls="navs-international-admission" aria-selected="false" onclick="load_tab_internasional(); this.onclick=null;">
                                International Admission
                            </button>
                        </li>
                        <li class="nav-item">
                            <button type="button" class="nav-link" role="tab" id="btn-tab-pascasarjana" data-bs-toggle="tab" data-bs-target="#navs-pascasarjana" aria-controls="navs-pascasarjana" aria-selected="false" onclick="load_tab_pascasarjana(); this.onclick=null;">
                                Pascasarjana
                            </button>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane fade show active" id="navs-semua" role="tabpanel">
                            <div class="row card-ujian-mandiri-semua">
                                <div class="col-md-4">
                                    <div class="card">
                                        <div class="card-header">
                                            <div class="input-group mb-4">
                                                <select class="form-select" id="tahun_statistik_semua">
                                                    <option value="2025">2025</option>
                                                    <option value="2024">2024</option>
                                                    <option value="2023">2023</option>
                                                </select>
                                                <button class="btn btn-primary btn-xs float-end" id="toggleButtonSemuaFormulir"><i class='bx bx-refresh'>&nbsp;</i>Refresh Off</button>
                                            </div>
                                            <h5 class="card-title mb-2">Total Pendaftar</h5>
                                            <h1 class="display-6 fw-normal mb-0" id="semua_formulir_total">0</h1>
                                        </div>
                                        <div class="card-body">
                                            <span class="d-block mb-2">Current Activity</span>
                                            <ul class="p-0 m-0">
                                                <li class="mb-3" style="list-style:none">
                                                    <div class="progress progress-stacked" style="height: 15px">
                                                        <div class="progress-bar bg-success" id="semua_formulir_sudah_p2" role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                                        <div class="progress-bar bg-danger" id="semua_formulir_belum_p2" role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                                    </div>
                                                </li>
                                                <li class="mb-3 d-flex justify-content-between">
                                                    <div class="d-flex align-items-center lh-1 me-3">
                                                        <span class="badge badge-dot bg-success me-2"></span> Mengisi Formulir
                                                    </div>
                                                    <div class="d-flex gap-3">
                                                        <span id="semua_formulir_sudah">0</span>
                                                        <span class="fw-semibold" id="semua_formulir_sudah_p">0%</span>
                                                    </div>
                                                </li>
                                                <li class="mb-3 d-flex justify-content-between">
                                                    <div class="d-flex align-items-center lh-1 me-3">
                                                        <span class="badge badge-dot bg-danger me-2"></span> Belum Mengisi Formulir
                                                    </div>
                                                    <div class="d-flex gap-3">
                                                        <span id="semua_formulir_belum">0</span>
                                                        <span class="fw-semibold" id="semua_formulir_belum_p">0%</span>
                                                    </div>
                                                </li>
                                                <li class="mb-3" style="list-style:none">
                                                    <div class="progress progress-stacked" style="height: 15px">
                                                        <div class="progress-bar bg-info" id="semua_pembayaran_sudah_p2" role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                                        <div class="progress-bar bg-primary" id="semua_pembayaran_belum_p2" role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                                    </div>
                                                </li>
                                                <li class="mb-3 d-flex justify-content-between">
                                                    <div class="d-flex align-items-center lh-1 me-3">
                                                        <span class="badge badge-dot bg-info me-2"></span> Sudah Bayar
                                                    </div>
                                                    <div class="d-flex gap-3">
                                                        <span id="semua_pembayaran_sudah">0</span>
                                                        <span class="fw-semibold" id="semua_pembayaran_sudah_p">0%</span>
                                                    </div>
                                                </li>
                                                <li class="mb-3 d-flex justify-content-between">
                                                    <div class="d-flex align-items-center lh-1 me-3">
                                                        <span class="badge badge-dot bg-primary me-2"></span> Belum Bayar
                                                    </div>
                                                    <div class="d-flex gap-3">
                                                        <span id="semua_pembayaran_belum">0</span>
                                                        <span class="fw-semibold" id="semua_pembayaran_belum_p">0%</span>
                                                    </div>
                                                </li>
                                            </ul>
                                            <hr>
                                            <div style="margin-top: 35px">
                                                <a href="<?= base_url('mandiri/mahasiswa') ?>" class="btn btn-primary d-flex justify-content-center">Selengkapnya</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-8 mt-3">
                                    <div class="row">
                                        <!-- Statistics Cards -->
                                        <div class="col-12 col-md-12 col-lg-4 mb-4">
                                            <div class="card h-100">
                                                <div class="card-body text-center">
                                                    <div class="avatar mx-auto mb-2">
                                                        <span class="avatar-initial rounded-circle bg-label-success"><i class="bx bx-male fs-4"></i></span>
                                                    </div>
                                                    <span class="d-block text-nowrap">Laki-laki</span>
                                                    <h2 class="mb-0" id="semua_formulir_laki">0</h2>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-12 col-lg-4 mb-4">
                                            <div class="card h-100">
                                                <div class="card-body text-center">
                                                    <div class="avatar mx-auto mb-2">
                                                        <span class="avatar-initial rounded-circle bg-label-danger"><i class="bx bx-female fs-4"></i></span>
                                                    </div>
                                                    <span class="d-block text-nowrap">Perempuan</span>
                                                    <h2 class="mb-0" id="semua_formulir_perempuan">0</h2>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-12 col-lg-4 mb-4">
                                            <div class="card h-100">
                                                <div class="card-body text-center">
                                                    <div class="avatar mx-auto mb-2">
                                                        <span class="avatar-initial rounded-circle bg-label-info"><i class="bx bx-accessibility fs-4"></i></span>
                                                    </div>
                                                    <span class="d-block text-nowrap">Kebutuhan Khusus</span>
                                                    <h2 class="mb-0" id="semua_formulir_abk">0</h2>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-12 col-lg-6 mb-4">
                                            <div class="card h-100">
                                                <div class="card-body text-center">
                                                    <div class="avatar mx-auto mb-2">
                                                        <span class="avatar-initial rounded-circle bg-label-primary"><i class="bx bx-test-tube fs-4"></i></span>
                                                    </div>
                                                    <div class="badge bg-info">Mandiri</div>
                                                    <div class="badge bg-primary">International Admission</div>
                                                    <span class="d-block text-nowrap">Kategori IPA / Sains</span>
                                                    <h2 class="mb-0" id="semua_formulir_ipa">0</h2>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-12 col-lg-6 mb-4">
                                            <div class="card h-100">
                                                <div class="card-body text-center">
                                                    <div class="avatar mx-auto mb-2">
                                                        <span class="avatar-initial rounded-circle bg-label-secondary"><i class="bx bx-landscape fs-4"></i></span>
                                                    </div>
                                                    <div class="badge bg-info">Mandiri</div>
                                                    <div class="badge bg-primary">International Admission</div>
                                                    <span class="d-block text-nowrap">Kategori IPS / Sosial</span>
                                                    <h2 class="mb-0" id="semua_formulir_ips">0</h2>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-12 col-lg-12 mb-4">
                                            <div class="card">
                                                <div class="card-body">
                                                    <div class="d-flex justify-content-between">
                                                        <div class="d-flex align-items-center gap-3">
                                                            <div class="avatar">
                                                                <span class="avatar-initial bg-label-warning rounded-circle"><i class="bx bx-money fs-4"></i></span>
                                                            </div>
                                                            <div class="card-info">
                                                                <h5 class="card-title mb-0 me-2" id="semua_pembayaran_nominal">Rp. 0</h5>
                                                            </div>
                                                        </div>
                                                        <div id="incomeChart"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!--/ Statistics Cards -->
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-6 mb-3">
                                    <div class="card card-ujian-mandiri-semua2">
                                        <div class="card-header">
                                            <button class="btn btn-primary btn-xs float-end" id="toggleButtonSemuaTipeUjian"><i class='bx bx-refresh'>&nbsp;</i>Refresh Off</button>
                                            <h5 class="card-title mb-2">Tipe Ujian</h5>
                                        </div>
                                        <div class="card-datatable table-responsive">
                                            <table id="semuaTabelTipeUjian" class="datatables-basic table table-bordered">
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
                                </div>
                                <div class="col-md-6">
                                    <div class="card card-ujian-mandiri-semua3">
                                        <div class="card-header">
                                            <button class="btn btn-primary btn-xs float-end" id="toggleButtonSemuaJurusan"><i class='bx bx-refresh'>&nbsp;</i>Refresh Off</button>
                                            <h5 class="card-title mb-2">Top 10 Jurusan Pendaftar</h5>
                                        </div>
                                        <div class="card-datatable table-responsive">
                                            <table id="semuaTabelJurusan" class="datatables-basic table table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>No</th>
                                                        <th>Jurusan</th>
                                                        <th>Jumlah</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <!-- Daftar Ulang -->
                                <div class="col-12 mt-2">
                                    <div class="card" id="card-daya-tampung">
                                        <div class="card-header">
                                            <div class="input-group mb-4">
                                                <select class="form-select" id="tahun_statistik_daya_tampung">
                                                    <option value="2025">2025</option>
                                                    <option value="2024">2024</option>
                                                    <option value="2023">2023</option>
                                                </select>
                                            <button class="btn btn-primary btn-xs float-end" id="toggleButtonDayaTampung"><i class='bx bx-refresh'>&nbsp;</i>Refresh Off</button>
                                            </div>
                                            <h5 class="card-title mb-2">Daya Tampung</h5>
                                        </div>
                                        <div class="card-datatable table-responsive">
                                            <table id="mandiriTabelDayaTampung" class="table table-bordered display" style="width:100%">
                                                <thead>
                                                    <tr class="text-center">
                                                        <th rowspan="3">Kode Jurusan</th>
                                                        <th rowspan="3">Jurusan</th>
                                                        <th rowspan="3">Fakultas</th>
                                                        <th rowspan="3">Daya Tampung</th>
                                                        <th rowspan="3">Sisa Kuota</th>
                                                        <th colspan="3">SNBP</th>
                                                        <th colspan="3">SPAN-PTKIN</th>
                                                        <th colspan="3">SNBT</th>
                                                        <th colspan="3">UM-PTKIN</th>
                                                        <th colspan="3">Mandiri</th>
                                                        <th colspan="3">Mandiri - Prestasi</th>
                                                        <th colspan="3">PBSB</th>
                                                        <th colspan="3">Total</th>
                                                    </tr>
                                                    <tr class="text-center">
                                                        <th>D</th>
                                                        <th>T</th>
                                                        <th>P</th>
                                                        <th>D</th>
                                                        <th>T</th>
                                                        <th>P</th>
                                                        <th>D</th>
                                                        <th>T</th>
                                                        <th>P</th>
                                                        <th>D</th>
                                                        <th>T</th>
                                                        <th>P</th>
                                                        <th>D</th>
                                                        <th>T</th>
                                                        <th>P</th>
                                                        <th>D</th>
                                                        <th>T</th>
                                                        <th>P</th>
                                                        <th>D</th>
                                                        <th>T</th>
                                                        <th>P</th>
                                                        <th>D</th>
                                                        <th>T</th>
                                                        <th>P</th>
                                                    </tr>
                                                    <tr class="text-center">
                                                        <th id="daya_tampung_snbp"></th>
                                                        <th id="terisi_snbp"></th>
                                                        <th id="persentase_snbp"></th>
                                                        <th id="daya_tampung_spanptkin"></th>
                                                        <th id="terisi_spanptkin"></th>
                                                        <th id="persentase_spanptkin"></th>
                                                        <th id="daya_tampung_snbt"></th>
                                                        <th id="terisi_snbt"></th>
                                                        <th id="persentase_snbt"></th>
                                                        <th id="daya_tampung_umptkin"></th>
                                                        <th id="terisi_umptkin"></th>
                                                        <th id="persentase_umptkin"></th>
                                                        <th id="daya_tampung_mandiri"></th>
                                                        <th id="terisi_mandiri"></th>
                                                        <th id="persentase_mandiri"></th>
                                                        <th id="daya_tampung_mandiriprestasi"></th>
                                                        <th id="terisi_mandiriprestasi"></th>
                                                        <th id="persentase_mandiriprestasi"></th>
                                                        <th id="daya_tampung_pbsb"></th>
                                                        <th id="terisi_pbsb"></th>
                                                        <th id="persentase_pbsb"></th>
                                                        <th id="daya_tampung_total"></th>
                                                        <th id="terisi_total"></th>
                                                        <th id="persentase_total"></th>
                                                    </tr>
                                                </thead>
                                                <tbody id="jurusan-daya-tampung">
                                                </tbody>
                                            </table>
                                            <div class="m-4">
                                                <div class="alert alert-info">
                                                    <b>Keterangan:</b>
                                                    <ul>
                                                        <li>D: Daya Tampung</li>
                                                        <li>T: Terisi</li>
                                                        <li>P: Persentase</li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!--/ Daftar Ulang -->
                            </div>
                        </div>
                        <div class="tab-pane fade" id="navs-mandiri" role="tabpanel">
                            <div class="row card-ujian-mandiri-mandiri">
                                <div class="col-md-4">
                                    <div class="card">
                                        <div class="card-header">
                                            <div class="input-group mb-4">
                                                <select class="form-select" id="tahun_statistik_mandiri">
                                                    <option value="2025">2025</option>
                                                    <option value="2024">2024</option>
                                                    <option value="2023">2023</option>
                                                </select>
                                                <button class="btn btn-primary btn-xs float-end" id="toggleButtonMandiriFormulir"><i class='bx bx-refresh'>&nbsp;</i>Refresh Off</button>    
                                            </div>
                                            <h5 class="card-title mb-2">Total Pendaftar</h5>
                                            <h1 class="display-6 fw-normal mb-0" id="mandiri_formulir_total">0</h1>
                                        </div>
                                        <div class="card-body">
                                            <span class="d-block mb-2">Current Activity</span>
                                            <ul class="p-0 m-0">
                                                <li class="mb-3" style="list-style:none">
                                                    <div class="progress progress-stacked" style="height: 15px">
                                                        <div class="progress-bar bg-success" id="mandiri_formulir_sudah_p2" role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                                        <div class="progress-bar bg-danger" id="mandiri_formulir_belum_p2" role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                                    </div>
                                                </li>
                                                <li class="mb-3 d-flex justify-content-between">
                                                    <div class="d-flex align-items-center lh-1 me-3">
                                                        <span class="badge badge-dot bg-success me-2"></span> Mengisi Formulir
                                                    </div>
                                                    <div class="d-flex gap-3">
                                                        <span id="mandiri_formulir_sudah">0</span>
                                                        <span class="fw-semibold" id="mandiri_formulir_sudah_p">0%</span>
                                                    </div>
                                                </li>
                                                <li class="mb-3 d-flex justify-content-between">
                                                    <div class="d-flex align-items-center lh-1 me-3">
                                                        <span class="badge badge-dot bg-danger me-2"></span> Belum Mengisi Formulir
                                                    </div>
                                                    <div class="d-flex gap-3">
                                                        <span id="mandiri_formulir_belum">0</span>
                                                        <span class="fw-semibold" id="mandiri_formulir_belum_p">0%</span>
                                                    </div>
                                                </li>
                                                <li class="mb-3" style="list-style:none">
                                                    <div class="progress progress-stacked" style="height: 15px">
                                                        <div class="progress-bar bg-info" id="mandiri_pembayaran_sudah_p2" role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                                        <div class="progress-bar bg-primary" id="mandiri_pembayaran_belum_p2" role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                                    </div>
                                                </li>
                                                <li class="mb-3 d-flex justify-content-between">
                                                    <div class="d-flex align-items-center lh-1 me-3">
                                                        <span class="badge badge-dot bg-info me-2"></span> Sudah Bayar
                                                    </div>
                                                    <div class="d-flex gap-3">
                                                        <span id="mandiri_pembayaran_sudah">0</span>
                                                        <span class="fw-semibold" id="mandiri_pembayaran_sudah_p">0%</span>
                                                    </div>
                                                </li>
                                                <li class="mb-3 d-flex justify-content-between">
                                                    <div class="d-flex align-items-center lh-1 me-3">
                                                        <span class="badge badge-dot bg-primary me-2"></span> Belum Bayar
                                                    </div>
                                                    <div class="d-flex gap-3">
                                                        <span id="mandiri_pembayaran_belum">0</span>
                                                        <span class="fw-semibold" id="mandiri_pembayaran_belum_p">0%</span>
                                                    </div>
                                                </li>
                                            </ul>
                                            <hr>
                                            <div style="margin-top: 35px">
                                                <a href="<?= base_url('mandiri/mahasiswa') ?>" class="btn btn-primary d-flex justify-content-center">Selengkapnya</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-8 mt-3">
                                    <div class="row">
                                        <!-- Statistics Cards -->
                                        <div class="col-12 col-md-12 col-lg-4 mb-4">
                                            <div class="card h-100">
                                                <div class="card-body text-center">
                                                    <div class="avatar mx-auto mb-2">
                                                        <span class="avatar-initial rounded-circle bg-label-success"><i class="bx bx-male fs-4"></i></span>
                                                    </div>
                                                    <span class="d-block text-nowrap">Laki-laki</span>
                                                    <h2 class="mb-0" id="mandiri_formulir_laki">0</h2>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-12 col-lg-4 mb-4">
                                            <div class="card h-100">
                                                <div class="card-body text-center">
                                                    <div class="avatar mx-auto mb-2">
                                                        <span class="avatar-initial rounded-circle bg-label-danger"><i class="bx bx-female fs-4"></i></span>
                                                    </div>
                                                    <span class="d-block text-nowrap">Perempuan</span>
                                                    <h2 class="mb-0" id="mandiri_formulir_perempuan">0</h2>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-12 col-lg-4 mb-4">
                                            <div class="card h-100">
                                                <div class="card-body text-center">
                                                    <div class="avatar mx-auto mb-2">
                                                        <span class="avatar-initial rounded-circle bg-label-info"><i class="bx bx-accessibility fs-4"></i></span>
                                                    </div>
                                                    <span class="d-block text-nowrap">Kebutuhan Khusus</span>
                                                    <h2 class="mb-0" id="mandiri_formulir_abk">0</h2>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-12 col-lg-6 mb-4">
                                            <div class="card h-100">
                                                <div class="card-body text-center">
                                                    <div class="avatar mx-auto mb-2">
                                                        <span class="avatar-initial rounded-circle bg-label-primary"><i class="bx bx-test-tube fs-4"></i></span>
                                                    </div>
                                                    <span class="d-block text-nowrap">Kategori IPA / Sains</span>
                                                    <h2 class="mb-0" id="mandiri_formulir_ipa">0</h2>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-12 col-lg-6 mb-4">
                                            <div class="card h-100">
                                                <div class="card-body text-center">
                                                    <div class="avatar mx-auto mb-2">
                                                        <span class="avatar-initial rounded-circle bg-label-secondary"><i class="bx bx-landscape fs-4"></i></span>
                                                    </div>
                                                    <span class="d-block text-nowrap">Kategori IPS / Sosial</span>
                                                    <h2 class="mb-0" id="mandiri_formulir_ips">0</h2>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-12 col-lg-12 mb-4">
                                            <div class="card">
                                                <div class="card-body">
                                                    <div class="d-flex justify-content-between">
                                                        <div class="d-flex align-items-center gap-3">
                                                            <div class="avatar">
                                                                <span class="avatar-initial bg-label-warning rounded-circle"><i class="bx bx-money fs-4"></i></span>
                                                            </div>
                                                            <div class="card-info">
                                                                <h5 class="card-title mb-0 me-2" id="mandiri_pembayaran_nominal">Rp. 0</h5>
                                                            </div>
                                                        </div>
                                                        <div id="incomeChart"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!--/ Statistics Cards -->
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-6 mb-3">
                                    <div class="card">
                                        <div class="card-header">
                                            <button class="btn btn-primary btn-xs float-end" id="toggleButtonMandiriTipeUjian"><i class='bx bx-refresh'>&nbsp;</i>Refresh Off</button>
                                            <h5 class="card-title mb-2">Tipe Ujian</h5>
                                        </div>
                                        <div class="card-datatable table-responsive">
                                            <table id="mandiriTabelTipeUjian" class="datatables-basic table table-bordered">
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
                                </div>
                                <div class="col-md-6">
                                    <div class="card">
                                        <div class="card-header">
                                            <button class="btn btn-primary btn-xs float-end" id="toggleButtonMandiriJurusan"><i class='bx bx-refresh'>&nbsp;</i>Refresh Off</button>
                                            <h5 class="card-title mb-2">Top 10 Jurusan Pendaftar</h5>
                                        </div>
                                        <div class="card-datatable table-responsive">
                                            <table id="mandiriTabelJurusan" class="datatables-basic table table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>No</th>
                                                        <th>Jurusan</th>
                                                        <th>Jumlah</th>
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
                        <div class="tab-pane fade" id="navs-international-admission" role="tabpanel">
                            <div class="row card-ujian-mandiri-internasional">
                                <div class="col-md-4">
                                    <div class="card">
                                        <div class="card-header">
                                            <button class="btn btn-primary btn-xs float-end" id="toggleButtonInternasionalFormulir"><i class='bx bx-refresh'>&nbsp;</i>Refresh Off</button>
                                            <h5 class="card-title mb-2">Total Pendaftar</h5>
                                            <h1 class="display-6 fw-normal mb-0" id="international_admission_formulir_total">0</h1>
                                        </div>
                                        <div class="card-body">
                                            <span class="d-block mb-2">Current Activity</span>
                                            <ul class="p-0 m-0">
                                                <li class="mb-3" style="list-style:none">
                                                    <div class="progress progress-stacked" style="height: 15px">
                                                        <div class="progress-bar bg-success" id="international_admission_formulir_sudah_p2" role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                                        <div class="progress-bar bg-danger" id="international_admission_formulir_belum_p2" role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                                    </div>
                                                </li>
                                                <li class="mb-3 d-flex justify-content-between">
                                                    <div class="d-flex align-items-center lh-1 me-3">
                                                        <span class="badge badge-dot bg-success me-2"></span> Mengisi Formulir
                                                    </div>
                                                    <div class="d-flex gap-3">
                                                        <span id="international_admission_formulir_sudah">0</span>
                                                        <span class="fw-semibold" id="international_admission_formulir_sudah_p">0%</span>
                                                    </div>
                                                </li>
                                                <li class="mb-3 d-flex justify-content-between">
                                                    <div class="d-flex align-items-center lh-1 me-3">
                                                        <span class="badge badge-dot bg-danger me-2"></span> Belum Mengisi Formulir
                                                    </div>
                                                    <div class="d-flex gap-3">
                                                        <span id="international_admission_formulir_belum">0</span>
                                                        <span class="fw-semibold" id="international_admission_formulir_belum_p">0%</span>
                                                    </div>
                                                </li>
                                                <li class="mb-3" style="list-style:none">
                                                    <div class="progress progress-stacked" style="height: 15px">
                                                        <div class="progress-bar bg-info" id="international_admission_pembayaran_sudah_p2" role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                                        <div class="progress-bar bg-primary" id="international_admission_pembayaran_belum_p2" role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                                    </div>
                                                </li>
                                                <li class="mb-3 d-flex justify-content-between">
                                                    <div class="d-flex align-items-center lh-1 me-3">
                                                        <span class="badge badge-dot bg-info me-2"></span> Sudah Bayar
                                                    </div>
                                                    <div class="d-flex gap-3">
                                                        <span id="international_admission_pembayaran_sudah">0</span>
                                                        <span class="fw-semibold" id="international_admission_pembayaran_sudah_p">0%</span>
                                                    </div>
                                                </li>
                                                <li class="mb-3 d-flex justify-content-between">
                                                    <div class="d-flex align-items-center lh-1 me-3">
                                                        <span class="badge badge-dot bg-primary me-2"></span> Belum Bayar
                                                    </div>
                                                    <div class="d-flex gap-3">
                                                        <span id="international_admission_pembayaran_belum">0</span>
                                                        <span class="fw-semibold" id="international_admission_pembayaran_belum_p">0%</span>
                                                    </div>
                                                </li>
                                            </ul>
                                            <hr>
                                            <div style="margin-top: 35px">
                                                <a href="<?= base_url('mandiri/mahasiswa') ?>" class="btn btn-primary d-flex justify-content-center">Selengkapnya</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-8 mt-3">
                                    <div class="row">
                                        <!-- Statistics Cards -->
                                        <div class="col-12 col-md-12 col-lg-4 mb-4">
                                            <div class="card h-100">
                                                <div class="card-body text-center">
                                                    <div class="avatar mx-auto mb-2">
                                                        <span class="avatar-initial rounded-circle bg-label-success"><i class="bx bx-male fs-4"></i></span>
                                                    </div>
                                                    <span class="d-block text-nowrap">Laki-laki</span>
                                                    <h2 class="mb-0" id="international_admission_formulir_laki">0</h2>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-12 col-lg-4 mb-4">
                                            <div class="card h-100">
                                                <div class="card-body text-center">
                                                    <div class="avatar mx-auto mb-2">
                                                        <span class="avatar-initial rounded-circle bg-label-danger"><i class="bx bx-female fs-4"></i></span>
                                                    </div>
                                                    <span class="d-block text-nowrap">Perempuan</span>
                                                    <h2 class="mb-0" id="international_admission_formulir_perempuan">0</h2>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-12 col-lg-4 mb-4">
                                            <div class="card h-100">
                                                <div class="card-body text-center">
                                                    <div class="avatar mx-auto mb-2">
                                                        <span class="avatar-initial rounded-circle bg-label-info"><i class="bx bx-accessibility fs-4"></i></span>
                                                    </div>
                                                    <span class="d-block text-nowrap">Kebutuhan Khusus</span>
                                                    <h2 class="mb-0" id="international_admission_formulir_abk">0</h2>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-12 col-lg-6 mb-4">
                                            <div class="card h-100">
                                                <div class="card-body text-center">
                                                    <div class="avatar mx-auto mb-2">
                                                        <span class="avatar-initial rounded-circle bg-label-primary"><i class="bx bx-test-tube fs-4"></i></span>
                                                    </div>
                                                    <span class="d-block text-nowrap">Kategori IPA / Sains</span>
                                                    <h2 class="mb-0" id="international_admission_formulir_ipa">0</h2>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-12 col-lg-6 mb-4">
                                            <div class="card h-100">
                                                <div class="card-body text-center">
                                                    <div class="avatar mx-auto mb-2">
                                                        <span class="avatar-initial rounded-circle bg-label-secondary"><i class="bx bx-landscape fs-4"></i></span>
                                                    </div>
                                                    <span class="d-block text-nowrap">Kategori IPS / Sosial</span>
                                                    <h2 class="mb-0" id="international_admission_formulir_ips">0</h2>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-12 col-lg-12 mb-4">
                                            <div class="card">
                                                <div class="card-body">
                                                    <div class="d-flex justify-content-between">
                                                        <div class="d-flex align-items-center gap-3">
                                                            <div class="avatar">
                                                                <span class="avatar-initial bg-label-warning rounded-circle"><i class="bx bx-money fs-4"></i></span>
                                                            </div>
                                                            <div class="card-info">
                                                                <h5 class="card-title mb-0 me-2" id="international_admission_pembayaran_nominal">Rp. 0</h5>
                                                            </div>
                                                        </div>
                                                        <div id="incomeChart"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!--/ Statistics Cards -->
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <div class="card">
                                        <div class="card-header">
                                            <button class="btn btn-primary btn-xs float-end" id="toggleButtonInternasionalTipeUjian"><i class='bx bx-refresh'>&nbsp;</i>Refresh Off</button>
                                            <h5 class="card-title mb-2">Tipe Ujian</h5>
                                        </div>
                                        <div class="card-datatable table-responsive">
                                            <table id="internasionalTabelTipeUjian" class="datatables-basic table table-bordered">
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
                                </div>
                                <div class="col-md-6">
                                    <div class="card">
                                        <div class="card-header">
                                            <button class="btn btn-primary btn-xs float-end" id="toggleButtonInternasionalJurusan"><i class='bx bx-refresh'>&nbsp;</i>Refresh Off</button>
                                            <h5 class="card-title mb-2">Top 10 Jurusan Pendaftar</h5>
                                        </div>
                                        <div class="card-datatable table-responsive">
                                            <table id="internasionalTabelJurusan" class="datatables-basic table table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>No</th>
                                                        <th>Jurusan</th>
                                                        <th>Jumlah</th>
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
                        <div class="tab-pane fade" id="navs-pascasarjana" role="tabpanel">
                            <div class="row card-ujian-mandiri-pascasarjana">
                                <div class="col-md-4">
                                    <div class="card">
                                        <div class="card-header">
                                            <button class="btn btn-primary btn-xs float-end" id="toggleButtonPascasarjanaFormulir"><i class='bx bx-refresh'>&nbsp;</i>Refresh Off</button>
                                            <h5 class="card-title mb-2">Total Pendaftar</h5>
                                            <h1 class="display-6 fw-normal mb-0" id="pascasarjana_formulir_total">0</h1>
                                        </div>
                                        <div class="card-body">
                                            <span class="d-block mb-2">Current Activity</span>
                                            <ul class="p-0 m-0">
                                                <li class="mb-3" style="list-style:none">
                                                    <div class="progress progress-stacked" style="height: 15px">
                                                        <div class="progress-bar bg-success" id="pascasarjana_formulir_sudah_p2" role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                                        <div class="progress-bar bg-danger" id="pascasarjana_formulir_belum_p2" role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                                    </div>
                                                </li>
                                                <li class="mb-3 d-flex justify-content-between">
                                                    <div class="d-flex align-items-center lh-1 me-3">
                                                        <span class="badge badge-dot bg-success me-2"></span> Mengisi Formulir
                                                    </div>
                                                    <div class="d-flex gap-3">
                                                        <span id="pascasarjana_formulir_sudah">0</span>
                                                        <span class="fw-semibold" id="pascasarjana_formulir_sudah_p">0%</span>
                                                    </div>
                                                </li>
                                                <li class="mb-3 d-flex justify-content-between">
                                                    <div class="d-flex align-items-center lh-1 me-3">
                                                        <span class="badge badge-dot bg-danger me-2"></span> Belum Mengisi Formulir
                                                    </div>
                                                    <div class="d-flex gap-3">
                                                        <span id="pascasarjana_formulir_belum">0</span>
                                                        <span class="fw-semibold" id="pascasarjana_formulir_belum_p">0%</span>
                                                    </div>
                                                </li>
                                                <li class="mb-3" style="list-style:none">
                                                    <div class="progress progress-stacked" style="height: 15px">
                                                        <div class="progress-bar bg-info" id="pascasarjana_pembayaran_sudah_p2" role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                                        <div class="progress-bar bg-primary" id="pascasarjana_pembayaran_belum_p2" role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                                    </div>
                                                </li>
                                                <li class="mb-3 d-flex justify-content-between">
                                                    <div class="d-flex align-items-center lh-1 me-3">
                                                        <span class="badge badge-dot bg-info me-2"></span> Sudah Bayar
                                                    </div>
                                                    <div class="d-flex gap-3">
                                                        <span id="pascasarjana_pembayaran_sudah">0</span>
                                                        <span class="fw-semibold" id="pascasarjana_pembayaran_sudah_p">0%</span>
                                                    </div>
                                                </li>
                                                <li class="mb-3 d-flex justify-content-between">
                                                    <div class="d-flex align-items-center lh-1 me-3">
                                                        <span class="badge badge-dot bg-primary me-2"></span> Belum Bayar
                                                    </div>
                                                    <div class="d-flex gap-3">
                                                        <span id="pascasarjana_pembayaran_belum">0</span>
                                                        <span class="fw-semibold" id="pascasarjana_pembayaran_belum_p">0%</span>
                                                    </div>
                                                </li>
                                            </ul>
                                            <hr>
                                            <div style="margin-top: 35px">
                                                <a href="<?= base_url('mandiri/mahasiswa') ?>" class="btn btn-primary d-flex justify-content-center">Selengkapnya</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-8 mt-3">
                                    <div class="row">
                                        <!-- Statistics Cards -->
                                        <div class="col-12 col-md-12 col-lg-4 mb-4">
                                            <div class="card h-100">
                                                <div class="card-body text-center">
                                                    <div class="avatar mx-auto mb-2">
                                                        <span class="avatar-initial rounded-circle bg-label-success"><i class="bx bx-male fs-4"></i></span>
                                                    </div>
                                                    <span class="d-block text-nowrap">Laki-laki</span>
                                                    <h2 class="mb-0" id="pascasarjana_formulir_laki">0</h2>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-12 col-lg-4 mb-4">
                                            <div class="card h-100">
                                                <div class="card-body text-center">
                                                    <div class="avatar mx-auto mb-2">
                                                        <span class="avatar-initial rounded-circle bg-label-danger"><i class="bx bx-female fs-4"></i></span>
                                                    </div>
                                                    <span class="d-block text-nowrap">Perempuan</span>
                                                    <h2 class="mb-0" id="pascasarjana_formulir_perempuan">0</h2>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-12 col-lg-4 mb-4">
                                            <div class="card h-100">
                                                <div class="card-body text-center">
                                                    <div class="avatar mx-auto mb-2">
                                                        <span class="avatar-initial rounded-circle bg-label-info"><i class="bx bx-accessibility fs-4"></i></span>
                                                    </div>
                                                    <span class="d-block text-nowrap">Kebutuhan Khusus</span>
                                                    <h2 class="mb-0" id="pascasarjana_formulir_abk">0</h2>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-12 col-lg-6 mb-4">
                                            <div class="card h-100">
                                                <div class="card-body text-center">
                                                    <div class="avatar mx-auto mb-2">
                                                        <span class="avatar-initial rounded-circle bg-label-primary"><i class="bx bx-test-tube fs-4"></i></span>
                                                    </div>
                                                    <span class="d-block text-nowrap">S2</span>
                                                    <h2 class="mb-0" id="pascasarjana_formulir_s2">0</h2>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-12 col-lg-6 mb-4">
                                            <div class="card h-100">
                                                <div class="card-body text-center">
                                                    <div class="avatar mx-auto mb-2">
                                                        <span class="avatar-initial rounded-circle bg-label-secondary"><i class="bx bx-landscape fs-4"></i></span>
                                                    </div>
                                                    <span class="d-block text-nowrap">S3</span>
                                                    <h2 class="mb-0" id="pascasarjana_formulir_s3">0</h2>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-12 col-lg-12 mb-4">
                                            <div class="card">
                                                <div class="card-body">
                                                    <div class="d-flex justify-content-between">
                                                        <div class="d-flex align-items-center gap-3">
                                                            <div class="avatar">
                                                                <span class="avatar-initial bg-label-warning rounded-circle"><i class="bx bx-money fs-4"></i></span>
                                                            </div>
                                                            <div class="card-info">
                                                                <h5 class="card-title mb-0 me-2" id="pascasarjana_pembayaran_nominal">Rp. 0</h5>
                                                            </div>
                                                        </div>
                                                        <div id="incomeChart"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!--/ Statistics Cards -->
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-6 mb-3">
                                    <div class="card">
                                        <div class="card-header">
                                            <button class="btn btn-primary btn-xs float-end" id="toggleButtonPascasarjanaTipeUjian"><i class='bx bx-refresh'>&nbsp;</i>Refresh Off</button>
                                            <h5 class="card-title mb-2">Tipe Ujian</h5>
                                        </div>
                                        <div class="card-datatable table-responsive">
                                            <table id="pascasarjanaTabelTipeUjian" class="datatables-basic table table-bordered">
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
                                </div>
                                <div class="col-md-6">
                                    <div class="card">
                                        <div class="card-header">
                                            <button class="btn btn-primary btn-xs float-end" id="toggleButtonPascasarjanaJurusan"><i class='bx bx-refresh'>&nbsp;</i>Refresh Off</button>
                                            <h5 class="card-title mb-2">Top 10 Jurusan Pendaftar</h5>
                                        </div>
                                        <div class="card-datatable table-responsive">
                                            <table id="pascasarjanaTabelJurusan" class="datatables-basic table table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>No</th>
                                                        <th>Jurusan</th>
                                                        <th>Jumlah</th>
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
                    </div>
                </div>
                <!--/ Formulir -->
            </div>
            <input type="hidden" id="input_semua_formulir_total">
            <input type="hidden" id="input_mandiri_formulir_total">
            <input type="hidden" id="input_internasional_formulir_total">
            <input type="hidden" id="input_pascasarjana_formulir_total">
        <?php else : ?>
            <div class="card">
                <div class="card-body">
                    <div class="alert alert-info">Selamat datang <?= ($this->jwt->ids_level == 32) ? 'pewawancara' : '' ?></div>
                </div>
            </div>
        <?php endif; ?>
    <?php endif; ?>

</div>