<div class="container-xxl flex-grow-1 container-p-y">
  <h4 class="py-3 breadcrumb-wrapper mb-4">Statistik</h4>

  <!-- Notification -->
  <?php $this->load->view('layout/notification'); ?>

  <p>
  <div class="row" id="container-statistik">
      <div class="col-12 mb-2">
        <div class="card" id="card-daya-tampung">
            <div class="card-header">
                <button class="btn btn-danger btn-xs float-end" id="toggleButtonDayaTampung"><i class='bx bx-refresh'>&nbsp;</i>Refresh Off</button>
                <h5 class="card-title mb-2">Daya Tampung</h5>
            </div>
            <div class="card-datatable table-responsive">
                <table class="table table-bordered" id="mandiriTabelDayaTampung">
                    <thead>
                    <tr class="text-center">
                        <th rowspan="2">No</th>
                        <th rowspan="2">Jurusan</th>
                        <th rowspan="2">Fakultas</th>
                        <th rowspan="2">Daya Tampung</th>
                        <th colspan="2">SNBP</th>
                        <th colspan="2">SPAN-PTKIN</th>
                        <th colspan="2">SNBT</th>
                        <th colspan="2">UM-PTKIN</th>
                        <th colspan="2">Mandiri</th>
                        <th colspan="2">Total</th>
                    </tr>
                    <tr class="text-center">
                        <th></th>
                        <th><span class="badge bg-label-primary">0%</span></th>
                        <th></th>
                        <th><span class="badge bg-label-secondary">25%</span></th>
                        <th></th>
                        <th><span class="badge bg-label-success">0%</span></th>
                        <th></th>
                        <th><span class="badge bg-label-danger">50%</span></th>
                        <th></th>
                        <th><span class="badge bg-label-warning">0%</span></th>
                        <th></th>
                        <th><span class="badge bg-label-info">75%</span></th>
                    </tr>
                    </thead>
                    <tbody id="jurusan-daya-tampung">
                    
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-6">
      <div class="card">
        <div class="card-header header-elements">
          <div class="card-title h5 mb-0">Statistik Per Fakultas</div>
          <div class="card-header-elements ms-auto">
            <button type="button" id="btn-refresh-perfakultas" class="btn btn-xs btn-danger">
              <span class="tf-icon bx bx-refresh bx-xs"></span>
            </button>
            <button type="button" class="btn btn-xs btn-primary" data-bs-toggle="collapse" data-bs-target="#filterPerFakultas" aria-expanded="false" aria-controls="filterPerFakultas">
              <span class="tf-icon bx bx-filter bx-xs"></span>
            </button>
          </div>
        </div>
        <div class="collapse" id="filterPerFakultas">
          <div class="border p-4">
            <div class="row">
              <div class="col-md-12">
                <form class="form-horizontal" id="form-filter-perfakultas">
                  <div class="row">
                    <div class="col-12 mb-3">
                      <label for="daftar-perfakultas" class="form-label">Status Daftar</label>
                      <select id="daftar-perfakultas" name="daftar-perfakultas" class="form-select">
                        <option value="">-- Semua --</option>
                        <option value="SUDAH">Sudah</option>
                        <option value="BELUM">Belum</option>
                      </select>
                      <span class="invalid-feedback"></span>
                    </div>
                    <div class="col-12 mb-3">
                      <label for="submit-perfakultas" class="form-label">Status Submit</label>
                      <select id="submit-perfakultas" name="submit-perfakultas" class="form-select">
                        <option value="">-- Semua --</option>
                        <option value="SUDAH">Sudah</option>
                        <option value="BELUM">Belum</option>
                      </select>
                      <span class="invalid-feedback"></span>
                    </div>
                    <div class="col-12 mb-3">
                      <label for="pembayaran-perfakultas" class="form-label">Status Pembayaran</label>
                      <select id="pembayaran-perfakultas" name="pembayaran-perfakultas" class="form-select">
                        <option value="">-- Semua --</option>
                        <option value="SUDAH">Sudah</option>
                        <option value="BELUM">Belum</option>
                      </select>
                      <span class="invalid-feedback"></span>
                    </div>
                    <div class="col-12 mb-3">
                      <label for="tahun-perfakultas" class="form-label">Tahun</label>
                      <select id="tahun-perfakultas" name="tahun-perfakultas" class="form-select">
                        <option value="">-- Semua --</option>
                        <?php foreach ($tahun as $a) : ?>
                          <option value="<?= $a->tahun ?>"><?= $a->tahun ?></option>
                        <?php endforeach; ?>
                      </select>
                      <span class="invalid-feedback"></span>
                    </div>
                  </div>
                  <div class="float-end">
                    <button type="button" id="btn-filter-per-fakultas" onclick="statistik_perfakultas()" class="btn btn-primary float-end">Filter</button>
                    <button type="button" id="btn-reset-per-fakultas" onclick="reset_perfakultas()" class="btn btn-default float-end">Reset</button>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
        <div class="card-body table-responsive">
          <table id="dataTabelFakultas" class="datatables-basic table table-bordered">
            <thead>
              <tr>
                <th>No</th>
                <th>Fakultas</th>
                <th>Jumlah</th>
              </tr>
            </thead>
            <tbody>
            </tbody>
          </table>
        </div>
      </div>
      <br>
      <div class="card">
        <div class="card-header header-elements">
          <div class="card-title h5 mb-0">Statistik Per Jalur Masuk</div>
          <div class="card-header-elements ms-auto">
            <button type="button" id="btn-refresh-perjalurmasuk" class="btn btn-xs btn-danger">
              <span class="tf-icon bx bx-refresh bx-xs"></span>
            </button>
            <button type="button" class="btn btn-xs btn-primary" data-bs-toggle="collapse" data-bs-target="#filterPerJalurMasuk" aria-expanded="false" aria-controls="filterPerJalurMasuk">
              <span class="tf-icon bx bx-filter bx-xs"></span>
            </button>
          </div>
        </div>
        <div class="collapse" id="filterPerJalurMasuk">
          <div class="border p-4">
            <div class="row">
              <div class="col-md-12">
                <form class="form-horizontal" id="form-filter-perjalurmasuk">
                  <div class="row">
                    <div class="col-12 mb-3">
                      <label for="daftar-perjalurmasuk" class="form-label">Status Daftar</label>
                      <select id="daftar-perjalurmasuk" name="daftar-perjalurmasuk" class="form-select">
                        <option value="">-- Semua --</option>
                        <option value="SUDAH">Sudah</option>
                        <option value="BELUM">Belum</option>
                      </select>
                      <span class="invalid-feedback"></span>
                    </div>
                    <div class="col-12 mb-3">
                      <label for="submit-perjalurmasuk" class="form-label">Status Submit</label>
                      <select id="submit-perjalurmasuk" name="submit-perjalurmasuk" class="form-select">
                        <option value="">-- Semua --</option>
                        <option value="SUDAH">Sudah</option>
                        <option value="BELUM">Belum</option>
                      </select>
                      <span class="invalid-feedback"></span>
                    </div>
                    <div class="col-12 mb-3">
                      <label for="pembayaran-perjalurmasuk" class="form-label">Status Pembayaran</label>
                      <select id="pembayaran-perjalurmasuk" name="pembayaran-perjalurmasuk" class="form-select">
                        <option value="">-- Semua --</option>
                        <option value="SUDAH">Sudah</option>
                        <option value="BELUM">Belum</option>
                      </select>
                      <span class="invalid-feedback"></span>
                    </div>
                    <div class="col-12 mb-3">
                      <label for="tahun-perjalurmasuk" class="form-label">Tahun</label>
                      <select id="tahun-perjalurmasuk" name="tahun-perjalurmasuk" class="form-select">
                        <option value="">-- Semua --</option>
                        <?php foreach ($tahun as $a) : ?>
                          <option value="<?= $a->tahun ?>"><?= $a->tahun ?></option>
                        <?php endforeach; ?>
                      </select>
                      <span class="invalid-feedback"></span>
                    </div>
                  </div>
                  <div class="float-end">
                    <button type="button" id="btn-filter-per-jalur-masuk" onclick="statistik_perjalurmasuk()" class="btn btn-primary float-end">Filter</button>
                    <button type="button" id="btn-reset-per-jalur-masuk" onclick="reset_perjalurmasuk()" class="btn btn-default float-end">Reset</button>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
        <div class="card-body table-responsive">
          <table id="dataTabelJalurMasuk" class="datatables-basic table table-bordered">
            <thead>
              <tr>
                <th>No</th>
                <th>Jalur Masuk</th>
                <th>Jumlah</th>
              </tr>
            </thead>
            <tbody>
            </tbody>
          </table>
        </div>
      </div>
      <br>
      <div class="card">
        <div class="card-header header-elements">
          <div class="card-title h5 mb-0">Statistik Per Provinsi</div>
          <div class="card-header-elements ms-auto">
            <button type="button" id="btn-refresh-perprovinsi" class="btn btn-xs btn-danger">
              <span class="tf-icon bx bx-refresh bx-xs"></span>
            </button>
            <button type="button" class="btn btn-xs btn-primary" data-bs-toggle="collapse" data-bs-target="#filterPerProvinsi" aria-expanded="false" aria-controls="filterPerProvinsi">
              <span class="tf-icon bx bx-filter bx-xs"></span>
            </button>
          </div>
        </div>
        <div class="collapse" id="filterPerProvinsi">
          <div class="border p-4">
            <div class="row">
              <div class="col-md-12">
                <form class="form-horizontal" id="form-filter-perprovinsi">
                  <div class="row">
                    <div class="col-12 mb-3">
                      <label for="daftar-perprovinsi" class="form-label">Status Daftar</label>
                      <select id="daftar-perprovinsi" name="daftar-perprovinsi" class="form-select">
                        <option value="">-- Semua --</option>
                        <option value="SUDAH">Sudah</option>
                        <option value="BELUM">Belum</option>
                      </select>
                      <span class="invalid-feedback"></span>
                    </div>
                    <div class="col-12 mb-3">
                      <label for="submit-perprovinsi" class="form-label">Status Submit</label>
                      <select id="submit-perprovinsi" name="submit-perprovinsi" class="form-select">
                        <option value="">-- Semua --</option>
                        <option value="SUDAH">Sudah</option>
                        <option value="BELUM">Belum</option>
                      </select>
                      <span class="invalid-feedback"></span>
                    </div>
                    <div class="col-12 mb-3">
                      <label for="pembayaran-perprovinsi" class="form-label">Status Pembayaran</label>
                      <select id="pembayaran-perprovinsi" name="pembayaran-perprovinsi" class="form-select">
                        <option value="">-- Semua --</option>
                        <option value="SUDAH">Sudah</option>
                        <option value="BELUM">Belum</option>
                      </select>
                      <span class="invalid-feedback"></span>
                    </div>
                    <div class="col-12 mb-3">
                      <label for="tahun-perprovinsi" class="form-label">Tahun</label>
                      <select id="tahun-perprovinsi" name="tahun-perprovinsi" class="form-select">
                        <option value="">-- Semua --</option>
                        <?php foreach ($tahun as $a) : ?>
                          <option value="<?= $a->tahun ?>"><?= $a->tahun ?></option>
                        <?php endforeach; ?>
                      </select>
                      <span class="invalid-feedback"></span>
                    </div>
                    <div class="col-12 mb-3">
                      <label for="page-perprovinsi" class="form-label">Page</label>
                      <input type="number" id="page-perprovinsi" name="page-perprovinsi" class="form-control" min="1">
                      <span class="invalid-feedback"></span>
                    </div>
                    <div class="col-12 mb-3">
                      <label for="limit-perprovinsi" class="form-label">Limit</label>
                      <input type="number" id="limit-perprovinsi" name="limit-perprovinsi" class="form-control" value="10" min="1">
                      <span class="invalid-feedback"></span>
                    </div>
                  </div>
                  <div class="float-end">
                    <button type="button" id="btn-filter-per-jurusan" onclick="statistik_perprovinsi()" class="btn btn-primary float-end">Filter</button>
                    <button type="button" id="btn-reset-per-jurusan" onclick="reset_perprovinsi()" class="btn btn-default float-end">Reset</button>
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
      <br>
      <div class="card">
        <div class="card-header header-elements">
          <div class="card-title h5 mb-0">Statistik Per Kecamatan</div>
          <div class="card-header-elements ms-auto">
            <button type="button" id="btn-refresh-perkecamatan" class="btn btn-xs btn-danger">
              <span class="tf-icon bx bx-refresh bx-xs"></span>
            </button>
            <button type="button" class="btn btn-xs btn-primary" data-bs-toggle="collapse" data-bs-target="#filterPerKecamatan" aria-expanded="false" aria-controls="filterPerKecamatan">
              <span class="tf-icon bx bx-filter bx-xs"></span>
            </button>
          </div>
        </div>
        <div class="collapse" id="filterPerKecamatan">
          <div class="border p-4">
            <div class="row">
              <div class="col-md-12">
                <form class="form-horizontal" id="form-filter-perkecamatan">
                  <div class="row">
                    <div class="col-12 mb-3">
                      <label for="daftar-perkecamatan" class="form-label">Status Daftar</label>
                      <select id="daftar-perkecamatan" name="daftar-perkecamatan" class="form-select">
                        <option value="">-- Semua --</option>
                        <option value="SUDAH">Sudah</option>
                        <option value="BELUM">Belum</option>
                      </select>
                      <span class="invalid-feedback"></span>
                    </div>
                    <div class="col-12 mb-3">
                      <label for="submit-perkecamatan" class="form-label">Status Submit</label>
                      <select id="submit-perkecamatan" name="submit-perkecamatan" class="form-select">
                        <option value="">-- Semua --</option>
                        <option value="SUDAH">Sudah</option>
                        <option value="BELUM">Belum</option>
                      </select>
                      <span class="invalid-feedback"></span>
                    </div>
                    <div class="col-12 mb-3">
                      <label for="pembayaran-perkecamatan" class="form-label">Status Pembayaran</label>
                      <select id="pembayaran-perkecamatan" name="pembayaran-perkecamatan" class="form-select">
                        <option value="">-- Semua --</option>
                        <option value="SUDAH">Sudah</option>
                        <option value="BELUM">Belum</option>
                      </select>
                      <span class="invalid-feedback"></span>
                    </div>
                    <div class="col-12 mb-3">
                      <label for="tahun-perkecamatan" class="form-label">Tahun</label>
                      <select id="tahun-perkecamatan" name="tahun-perkecamatan" class="form-select">
                        <option value="">-- Semua --</option>
                        <?php foreach ($tahun as $a) : ?>
                          <option value="<?= $a->tahun ?>"><?= $a->tahun ?></option>
                        <?php endforeach; ?>
                      </select>
                      <span class="invalid-feedback"></span>
                    </div>
                    <div class="col-12 mb-3">
                      <label for="page-perkecamatan" class="form-label">Page</label>
                      <input type="number" id="page-perkecamatan" name="page-perkecamatan" class="form-control" min="1">
                      <span class="invalid-feedback"></span>
                    </div>
                    <div class="col-12 mb-3">
                      <label for="limit-perkecamatan" class="form-label">Limit</label>
                      <input type="number" id="limit-perkecamatan" name="limit-perkecamatan" class="form-control" value="10" min="1">
                      <span class="invalid-feedback"></span>
                    </div>
                  </div>
                  <div class="float-end">
                    <button type="button" id="btn-filter-per-jalur-masuk" onclick="statistik_perkecamatan()" class="btn btn-primary float-end">Filter</button>
                    <button type="button" id="btn-reset-per-jalur-masuk" onclick="reset_perkecamatan()" class="btn btn-default float-end">Reset</button>
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
    </div>
    <div class="col-md-6">
      <div class="card">
        <div class="card-header header-elements">
          <div class="card-title h5 mb-0">Statistik Per Jurusan</div>
          <div class="card-header-elements ms-auto">
            <button type="button" id="btn-refresh-perjurusan" class="btn btn-xs btn-danger">
              <span class="tf-icon bx bx-refresh bx-xs"></span>
            </button>
            <button type="button" class="btn btn-xs btn-primary" data-bs-toggle="collapse" data-bs-target="#filterPerJurusan" aria-expanded="false" aria-controls="filterPerJurusan">
              <span class="tf-icon bx bx-filter bx-xs"></span>
            </button>
          </div>
        </div>
        <div class="collapse" id="filterPerJurusan">
          <div class="border p-4">
            <div class="row">
              <div class="col-md-12">
                <form class="form-horizontal" id="form-filter-perjurusan">
                  <div class="row">
                    <div class="col-12 mb-3">
                      <label for="daftar-perjurusan" class="form-label">Status Daftar</label>
                      <select id="daftar-perjurusan" name="daftar-perjurusan" class="form-select">
                        <option value="">-- Semua --</option>
                        <option value="SUDAH">Sudah</option>
                        <option value="BELUM">Belum</option>
                      </select>
                      <span class="invalid-feedback"></span>
                    </div>
                    <div class="col-12 mb-3">
                      <label for="submit-perjurusan" class="form-label">Status Submit</label>
                      <select id="submit-perjurusan" name="submit-perjurusan" class="form-select">
                        <option value="">-- Semua --</option>
                        <option value="SUDAH">Sudah</option>
                        <option value="BELUM">Belum</option>
                      </select>
                      <span class="invalid-feedback"></span>
                    </div>
                    <div class="col-12 mb-3">
                      <label for="pembayaran-perjurusan" class="form-label">Status Pembayaran</label>
                      <select id="pembayaran-perjurusan" name="pembayaran-perjurusan" class="form-select">
                        <option value="">-- Semua --</option>
                        <option value="SUDAH">Sudah</option>
                        <option value="BELUM">Belum</option>
                      </select>
                      <span class="invalid-feedback"></span>
                    </div>
                    <div class="col-12 mb-3">
                      <label for="tahun-perjurusan" class="form-label">Tahun</label>
                      <select id="tahun-perjurusan" name="tahun-perjurusan" class="form-select">
                        <option value="">-- Semua --</option>
                        <?php foreach ($tahun as $a) : ?>
                          <option value="<?= $a->tahun ?>"><?= $a->tahun ?></option>
                        <?php endforeach; ?>
                      </select>
                      <span class="invalid-feedback"></span>
                    </div>
                  </div>
                  <div class="float-end">
                    <button type="button" id="btn-filter-per-jurusan" onclick="statistik_perjurusan()" class="btn btn-primary float-end">Filter</button>
                    <button type="button" id="btn-reset-per-jurusan" onclick="reset_perjurusan()" class="btn btn-default float-end">Reset</button>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
        <div class="card-body table-responsive">
          <table id="dataTabelJurusan" class="datatables-basic table table-bordered">
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
      <br>
      <div class="card">
        <div class="card-header header-elements">
          <div class="card-title h5 mb-0">Statistik Per Negara</div>
          <div class="card-header-elements ms-auto">
            <button type="button" id="btn-refresh-pernegara" class="btn btn-xs btn-danger">
              <span class="tf-icon bx bx-refresh bx-xs"></span>
            </button>
            <button type="button" class="btn btn-xs btn-primary" data-bs-toggle="collapse" data-bs-target="#filterPerNegara" aria-expanded="false" aria-controls="filterPerNegara">
              <span class="tf-icon bx bx-filter bx-xs"></span>
            </button>
          </div>
        </div>
        <div class="collapse" id="filterPerNegara">
          <div class="border p-4">
            <div class="row">
              <div class="col-md-12">
                <form class="form-horizontal" id="form-filter-pernegara">
                  <div class="row">
                    <div class="col-12 mb-3">
                      <label for="daftar-pernegara" class="form-label">Status Daftar</label>
                      <select id="daftar-pernegara" name="daftar-pernegara" class="form-select">
                        <option value="">-- Semua --</option>
                        <option value="SUDAH">Sudah</option>
                        <option value="BELUM">Belum</option>
                      </select>
                      <span class="invalid-feedback"></span>
                    </div>
                    <div class="col-12 mb-3">
                      <label for="submit-pernegara" class="form-label">Status Submit</label>
                      <select id="submit-pernegara" name="submit-pernegara" class="form-select">
                        <option value="">-- Semua --</option>
                        <option value="SUDAH">Sudah</option>
                        <option value="BELUM">Belum</option>
                      </select>
                      <span class="invalid-feedback"></span>
                    </div>
                    <div class="col-12 mb-3">
                      <label for="pembayaran-pernegara" class="form-label">Status Pembayaran</label>
                      <select id="pembayaran-pernegara" name="pembayaran-pernegara" class="form-select">
                        <option value="">-- Semua --</option>
                        <option value="SUDAH">Sudah</option>
                        <option value="BELUM">Belum</option>
                      </select>
                      <span class="invalid-feedback"></span>
                    </div>
                    <div class="col-12 mb-3">
                      <label for="tahun-pernegara" class="form-label">Tahun</label>
                      <select id="tahun-pernegara" name="tahun-pernegara" class="form-select">
                        <option value="">-- Semua --</option>
                        <?php foreach ($tahun as $a) : ?>
                          <option value="<?= $a->tahun ?>"><?= $a->tahun ?></option>
                        <?php endforeach; ?>
                      </select>
                      <span class="invalid-feedback"></span>
                    </div>
                    <div class="col-12 mb-3">
                      <label for="page-pernegara" class="form-label">Page</label>
                      <input type="number" id="page-pernegara" name="page-pernegara" class="form-control" min="1">
                      <span class="invalid-feedback"></span>
                    </div>
                    <div class="col-12 mb-3">
                      <label for="limit-pernegara" class="form-label">Limit</label>
                      <input type="number" id="limit-pernegara" name="limit-pernegara" class="form-control" value="10" min="1">
                      <span class="invalid-feedback"></span>
                    </div>
                  </div>
                  <div class="float-end">
                    <button type="button" id="btn-filter-per-negara" onclick="statistik_pernegara()" class="btn btn-primary float-end">Filter</button>
                    <button type="button" id="btn-reset-per-negara" onclick="reset_pernegara()" class="btn btn-default float-end">Reset</button>
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
      <br>
      <div class="card">
        <div class="card-header header-elements">
          <div class="card-title h5 mb-0">Statistik Per Kabupaten / Kota</div>
          <div class="card-header-elements ms-auto">
            <button type="button" id="btn-refresh-pernegara" class="btn btn-xs btn-danger">
              <span class="tf-icon bx bx-refresh bx-xs"></span>
            </button>
            <button type="button" class="btn btn-xs btn-primary" data-bs-toggle="collapse" data-bs-target="#filterPerKabKota" aria-expanded="false" aria-controls="filterPerKabKota">
              <span class="tf-icon bx bx-filter bx-xs"></span>
            </button>
          </div>
        </div>
        <div class="collapse" id="filterPerKabKota">
          <div class="border p-4">
            <div class="row">
              <div class="col-md-12">
                <form class="form-horizontal" id="form-filter-perkabkota">
                  <div class="row">
                    <div class="col-12 mb-3">
                      <label for="daftar-perkabkota" class="form-label">Status Daftar</label>
                      <select id="daftar-perkabkota" name="daftar-perkabkota" class="form-select">
                        <option value="">-- Semua --</option>
                        <option value="SUDAH">Sudah</option>
                        <option value="BELUM">Belum</option>
                      </select>
                      <span class="invalid-feedback"></span>
                    </div>
                    <div class="col-12 mb-3">
                      <label for="submit-perkabkota" class="form-label">Status Submit</label>
                      <select id="submit-perkabkota" name="submit-perkabkota" class="form-select">
                        <option value="">-- Semua --</option>
                        <option value="SUDAH">Sudah</option>
                        <option value="BELUM">Belum</option>
                      </select>
                      <span class="invalid-feedback"></span>
                    </div>
                    <div class="col-12 mb-3">
                      <label for="pembayaran-perkabkota" class="form-label">Status Pembayaran</label>
                      <select id="pembayaran-perkabkota" name="pembayaran-perkabkota" class="form-select">
                        <option value="">-- Semua --</option>
                        <option value="SUDAH">Sudah</option>
                        <option value="BELUM">Belum</option>
                      </select>
                      <span class="invalid-feedback"></span>
                    </div>
                    <div class="col-12 mb-3">
                      <label for="tahun-perkabkota" class="form-label">Tahun</label>
                      <select id="tahun-perkabkota" name="tahun-perkabkota" class="form-select">
                        <option value="">-- Semua --</option>
                        <?php foreach ($tahun as $a) : ?>
                          <option value="<?= $a->tahun ?>"><?= $a->tahun ?></option>
                        <?php endforeach; ?>
                      </select>
                      <span class="invalid-feedback"></span>
                    </div>
                    <div class="col-12 mb-3">
                      <label for="page-perkabkota" class="form-label">Page</label>
                      <input type="number" id="page-perkabkota" name="page-perkabkota" class="form-control" min="1">
                      <span class="invalid-feedback"></span>
                    </div>
                    <div class="col-12 mb-3">
                      <label for="limit-perkabkota" class="form-label">Limit</label>
                      <input type="number" id="limit-perkabkota" name="limit-perkabkota" class="form-control" value="10" min="1">
                      <span class="invalid-feedback"></span>
                    </div>
                  </div>
                  <div class="float-end">
                    <button type="button" id="btn-filter-per-kab-kota" onclick="statistik_perkabkota()" class="btn btn-primary float-end">Filter</button>
                    <button type="button" id="btn-reset-per-kab-kota" onclick="reset_perkabkota()" class="btn btn-default float-end">Reset</button>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
        <div class="card-body table-responsive">
          <table id="dataTabelKabKota" class="datatables-basic table table-bordered">
            <thead>
              <tr>
                <th>No</th>
                <th>Kabupaten / Kota</th>
                <th>Jumlah</th>
              </tr>
            </thead>
            <tbody>
            </tbody>
          </table>
        </div>
      </div>
      <br>
      <div class="card">
        <div class="card-header header-elements">
          <div class="card-title h5 mb-0">Statistik Per Kelurahan</div>
          <div class="card-header-elements ms-auto">
            <button type="button" id="btn-refresh-perkelurahan" class="btn btn-xs btn-danger">
              <span class="tf-icon bx bx-refresh bx-xs"></span>
            </button>
            <button type="button" class="btn btn-xs btn-primary" data-bs-toggle="collapse" data-bs-target="#filterPerKelurahan" aria-expanded="false" aria-controls="filterPerKelurahan">
              <span class="tf-icon bx bx-filter bx-xs"></span>
            </button>
          </div>
        </div>
        <div class="collapse" id="filterPerKelurahan">
          <div class="border p-4">
            <div class="row">
              <div class="col-md-12">
                <form class="form-horizontal" id="form-filter-perkelurahan">
                  <div class="row">
                    <div class="col-12 mb-3">
                      <label for="daftar-perkelurahan" class="form-label">Status Daftar</label>
                      <select id="daftar-perkelurahan" name="daftar-perkelurahan" class="form-select">
                        <option value="">-- Semua --</option>
                        <option value="SUDAH">Sudah</option>
                        <option value="BELUM">Belum</option>
                      </select>
                      <span class="invalid-feedback"></span>
                    </div>
                    <div class="col-12 mb-3">
                      <label for="submit-perkelurahan" class="form-label">Status Submit</label>
                      <select id="submit-perkelurahan" name="submit-perkelurahan" class="form-select">
                        <option value="">-- Semua --</option>
                        <option value="SUDAH">Sudah</option>
                        <option value="BELUM">Belum</option>
                      </select>
                      <span class="invalid-feedback"></span>
                    </div>
                    <div class="col-12 mb-3">
                      <label for="pembayaran-perkelurahan" class="form-label">Status Pembayaran</label>
                      <select id="pembayaran-perkelurahan" name="pembayaran-perkelurahan" class="form-select">
                        <option value="">-- Semua --</option>
                        <option value="SUDAH">Sudah</option>
                        <option value="BELUM">Belum</option>
                      </select>
                      <span class="invalid-feedback"></span>
                    </div>
                    <div class="col-12 mb-3">
                      <label for="tahun-perkelurahan" class="form-label">Tahun</label>
                      <select id="tahun-perkelurahan" name="tahun-perkelurahan" class="form-select">
                        <option value="">-- Semua --</option>
                        <?php foreach ($tahun as $a) : ?>
                          <option value="<?= $a->tahun ?>"><?= $a->tahun ?></option>
                        <?php endforeach; ?>
                      </select>
                      <span class="invalid-feedback"></span>
                    </div>
                    <div class="col-12 mb-3">
                      <label for="page-perkelurahan" class="form-label">Page</label>
                      <input type="number" id="page-perkelurahan" name="page-perkelurahan" class="form-control" min="1">
                      <span class="invalid-feedback"></span>
                    </div>
                    <div class="col-12 mb-3">
                      <label for="limit-perkelurahan" class="form-label">Limit</label>
                      <input type="number" id="limit-perkelurahan" name="limit-perkelurahan" class="form-control" value="10" min="1">
                      <span class="invalid-feedback"></span>
                    </div>
                  </div>
                  <div class="float-end">
                    <button type="button" id="btn-filter-per-kelurahan" onclick="statistik_perkelurahan()" class="btn btn-primary float-end">Filter</button>
                    <button type="button" id="btn-reset-per-kelurahan" onclick="reset_perkelurahan()" class="btn btn-default float-end">Reset</button>
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
    </div>
  </div>
  </p>
</div>