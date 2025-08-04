<div class="container-xxl flex-grow-1 container-p-y">
  <h4 class="py-3 breadcrumb-wrapper mb-4">
    <span class="text-muted fw-light">Setting /</span> Sub Daya Tampung
  </h4>
  <p>
    <?php $this->load->view('layout/notification'); ?>
  </p>
  <p>
    <div class="card">
      <div class="card-header header-elements">
        <span class="me-2 h5">Data Sub Daya Tampung</span>
        <div class="card-header-elements ms-auto">
          <div class="dropdown">
            <button class="btn btn-secondary btn-xs" style="margin-top: -15px" type="button" id="option1" data-bs-toggle="dropdown" aria-expanded="false">
              <i class='bx bx-dots-vertical-rounded'></i>
            </button>
            <ul class="dropdown-menu" aria-labelledby="option1">
              <li><a class="dropdown-item" href="javascript:void(0)" onclick="add_data()">Tambah</a></li>
              <li class="dropdown-divider"></li>
              <li><a class="dropdown-item" href="<?=base_url('setting/import/sub-daya-tampung')?>" target="_blank">Update Sub Daya Tampung</a></li>
            </ul>
            <button type="button" class="btn btn-xs btn-primary" style="margin-top: -15px" data-bs-toggle="collapse" data-bs-target="#filterForm" aria-expanded="false" aria-controls="filterPerFakultas">
                <span class="tf-icon bx bx-filter bx-xs"></span>
            </button>
          </div>
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
                                      <option value="<?=$a->tahun?>"><?=$a->tahun?></option>
                                  <?php endforeach; ?>
                                </select>
                              </div>
                          </div>
                          <div class="form-group row mb-3">
                              <label for="ids_jalur_masuk_filter" class="col-sm-2 col-form-label">Jalur Masuk</label>
                              <div class="col-sm-10">
                                  <select class="form-control" id="ids_jalur_masuk_filter">
                                      <option value="">&laquo; Semua &raquo;</option>
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
                              <label for="daya_tampung_filter" class="col-sm-2 col-form-label">Daya Tampung</label>
                              <div class="col-sm-10">
                                  <input type="text" class="form-control" id="daya_tampung_filter" placeholder="Daya Tampung">
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
              <th>Jurusan</th>
              <th>Fakultas</th>
              <th>Jalur Masuk</th>
              <th>Daya Tampung</th>
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