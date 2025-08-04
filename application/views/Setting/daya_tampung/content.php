<div class="container-xxl flex-grow-1 container-p-y">
  <h4 class="py-3 breadcrumb-wrapper mb-4">
    <span class="text-muted fw-light">Setting /</span> Daya Tampung
  </h4>
  <p>
    <?php $this->load->view('layout/notification'); ?>
  </p>
  <p>
  <div class="card">
    <div class="card-header header-elements">
      <span class="me-2 h5">Data Daya Tampung</span>
      <div class="card-header-elements ms-auto">
        <div class="dropdown">
          <button class="btn btn-secondary btn-xs" style="margin-top: -15px" type="button" id="option1" data-bs-toggle="dropdown" aria-expanded="false">
            <i class='bx bx-dots-vertical-rounded'></i>
          </button>
          <ul class="dropdown-menu" aria-labelledby="option1">
            <li><a class="dropdown-item" href="javascript:void(0)" onclick="add_data()">Tambah</a></li>
            <li class="dropdown-divider"></li>
            <li><a class="dropdown-item" href="javascript:void(0)" onclick="generate_data()">Hitung Daya Tampung</a></li>
            <li><a class="dropdown-item" href="javascript:void(0)" onclick="tambahdaya_data()">Tambah 10%</a></li>
            <li><a class="dropdown-item" href="<?= base_url('setting/import/daya-tampung') ?>" target="_blank">Import Daya Tampung</a></li>
            <li><a class="dropdown-item" href="<?= base_url('setting/import/pengurangan-kuota') ?>" target="_blank">Import Pengurangan Kuota</a></li>
          </ul>
        </div>
        <button type="button" class="btn btn-xs btn-primary" style="margin-top: -11px" data-bs-toggle="collapse" data-bs-target="#filterForm" aria-expanded="false" aria-controls="filterPerFakultas">
          <span class="tf-icon bx bx-filter bx-xs"></span>
        </button>
      </div>
    </div>
    <div class="card-datatable table-responsive pt-0">
      <div class="collapse" id="filterForm">
        <div class="border p-4">
          <div class="row">
            <div class="col-md-12">
              <form class="form-horizontal" id="form-filter">
                <div class="form-group row mb-3">
                  <label for="tahun_filter" class="col-sm-2 col-form-label">Tahun</label>
                  <div class="col-sm-10">
                    <select class="form-control" id="tahun_filter">
                      <option value="">&laquo; Semua &raquo;</option>
                      <?php foreach ($tahun as $a) : ?>
                        <option value="<?= $a->tahun ?>"><?= $a->tahun ?></option>
                      <?php endforeach; ?>
                    </select>
                  </div>
                </div>
                <div class="form-group row mb-3">
                  <label for="jenjang_filter" class="col-sm-2 col-form-label">Jenjang</label>
                  <div class="col-sm-10">
                    <select class="form-control" id="jenjang_filter">
                      <option value="">&laquo; Semua &raquo;</option>
                      <option value="S1">S1</option>
                      <option value="S2">S2</option>
                      <option value="S3">S3</option>
                    </select>
                  </div>
                </div>
                <div class="form-group row mb-3">
                  <label for="ids_fakultas_filter" class="col-sm-2 col-form-label">Fakultas</label>
                  <div class="col-sm-10">
                    <select class="form-control" id="ids_fakultas_filter">
                      <option value="">&laquo; Semua &raquo;</option>
                    </select>
                  </div>
                </div>
                <div class="form-group row mb-3">
                  <label for="kode_jurusan_filter" class="col-sm-2 col-form-label">Jurusan</label>
                  <div class="col-sm-10">
                    <select class="form-control" id="kode_jurusan_filter">
                      <option value="">&laquo; Semua &raquo;</option>
                    </select>
                  </div>
                </div>
                <div class="form-group row mb-3">
                  <label for="dt_awal_filter" class="col-sm-2 col-form-label">Daya Tampung (Awal)</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control" id="dt_awal_filter" placeholder="Daya Tampung (Awal)">
                  </div>
                </div>
                <div class="form-group row mb-3">
                  <label for="daya_tampung_filter" class="col-sm-2 col-form-label">Daya Tampung</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control" id="daya_tampung_filter" placeholder="Daya Tampung">
                  </div>
                </div>
                <div class="form-group row mb-3">
                  <label for="afirmasi_filter" class="col-sm-2 col-form-label">Afirmasi</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control" id="afirmasi_filter" placeholder="Afirmasi">
                  </div>
                </div>
                <div class="form-group row mb-3">
                  <label for="kuota_filter" class="col-sm-2 col-form-label">Kuota</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control" id="kuota_filter" placeholder="Kuota">
                  </div>
                </div>
                <div class="form-group row mb-3">
                  <label for="grade_filter" class="col-sm-2 col-form-label">Grade</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control" id="grade_filter" placeholder="Grade">
                  </div>
                </div>
                <div class="form-group row mb-3">
                  <label for="nilai_min_filter" class="col-sm-2 col-form-label">Nilai Minimal</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control" id="nilai_min_filter" placeholder="Grade">
                  </div>
                </div>
                <div class="form-group row mb-3">
                  <label for="nilai_max_filter" class="col-sm-2 col-form-label">Nilai Maksimal</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control" id="nilai_max_filter" placeholder="Grade">
                  </div>
                </div>
                <div class="float-end">
                  <button type="button" id="btn-filter" class="btn btn-primary float-end">Filter</button>
                  <button type="button" id="btn-reset" class="btn btn-default float-end">Reset</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
      <table id="dataTabel" class="datatables-basic table table-bordered">
        <thead>
          <tr>
            <th>No</th>
            <th>Aksi</th>
            <th>Tahun</th>
            <th>Jenjang</th>
            <th>Kode Jurusan</th>
            <th>Jurusan</th>
            <th>Fakultas</th>
            <th>Kelas</th>
            <th>Daya Tampung (Awal)</th>
            <th>Daya Tampung</th>
            <th>10%</th>
            <th>Kuota</th>
            <th>Grade</th>
            <th>Nilai Minimal</th>
            <th>Nilai Maksimal</th>
            <th>Status</th>
          </tr>
        </thead>
        <tbody>
        </tbody>
      </table>
    </div>
  </div>
  </p>
</div>