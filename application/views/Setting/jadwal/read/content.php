<div class="container-xxl flex-grow-1 container-p-y">
  <h4 class="py-3 breadcrumb-wrapper mb-4">
    <span class="text-muted fw-light">Setting /</span> Jadwal
  </h4>
  <p>
    <?php $this->load->view('layout/notification'); ?>
  </p>
  <p>
    <div class="card">
      <div class="card-header header-elements">
        <span class="me-2 h5">Data Jadwal</span>
        <div class="card-header-elements ms-auto">
            <button type="button" style="margin-top: -15px" onclick="add_data()" class="btn btn-xs btn-primary">
              <span class="tf-icon bx bx-plus bx-xs"></span> Tambah
            </button>
            <button type="button" style="margin-top: -15px" class="btn btn-xs btn-warning" data-bs-toggle="collapse" data-bs-target="#filterForm" aria-expanded="false" aria-controls="filterForm">
              <span class="tf-icon bx bx-filter bx-xs"></span> Filter
            </button>
        </div>
      </div>
      <div class="collapse" id="filterForm">
          <div class="border p-4">
              <div class="row">
                  <div class="col-md-12">
                      <form class="form-horizontal" id="form-filter">
                          <div class="form-group row mb-3">
                              <label for="ids_program_filter" class="col-sm-2 col-form-label">Program</label>
                              <div class="col-sm-10">
                                  <select class="form-control" id="ids_program_filter">
                                      <option value="">&laquo; Semua &raquo;</option>
                                  </select>
                              </div>
                          </div>
                          <div class="form-group row mb-3">
                              <label for="ids_tipe_ujian_filter" class="col-sm-2 col-form-label">Tipe Ujian</label>
                              <div class="col-sm-10">
                                  <select class="form-control" id="ids_tipe_ujian_filter">
                                      <option value="">&laquo; Semua &raquo;</option>
                                  </select>
                              </div>
                          </div>
                          <div class="form-group row mb-3">
                              <label for="tanggal_filter" class="col-sm-2 col-form-label">Tanggal</label>
                              <div class="col-sm-10">
                                  <select class="form-control" id="tanggal_filter">
                                      <option value="">&laquo; Semua &raquo;</option>
                                  </select>
                              </div>
                          </div>
                          <div class="form-group row mb-3">
                              <label for="jam_awal_filter" class="col-sm-2 col-form-label">Jam Awal</label>
                              <div class="col-sm-10">
                                  <select class="form-control" id="jam_awal_filter">
                                      <option value="">&laquo; Semua &raquo;</option>
                                  </select>
                              </div>
                          </div>
                          <div class="form-group row mb-3">
                              <label for="jam_akhir_filter" class="col-sm-2 col-form-label">Jam Akhir</label>
                              <div class="col-sm-10">
                                  <select class="form-control" id="jam_akhir_filter">
                                      <option value="">&laquo; Semua &raquo;</option>
                                  </select>
                              </div>
                          </div>
                          <div class="form-group row mb-3">
                              <label for="ids_area_filter" class="col-sm-2 col-form-label">Area</label>
                              <div class="col-sm-10">
                                  <select class="form-control" id="ids_area_filter">
                                      <option value="">&laquo; Semua &raquo;</option>
                                  </select>
                              </div>
                          </div>
                          <div class="form-group row mb-3">
                              <label for="ids_gedung_filter" class="col-sm-2 col-form-label">Gedung</label>
                              <div class="col-sm-10">
                                  <select class="form-control" id="ids_gedung_filter">
                                      <option value="">&laquo; Semua &raquo;</option>
                                  </select>
                              </div>
                          </div>
                          <div class="form-group row mb-3">
                              <label for="ids_ruangan_filter" class="col-sm-2 col-form-label">Ruangan</label>
                              <div class="col-sm-10">
                                  <select class="form-control" id="ids_ruangan_filter">
                                      <option value="">&laquo; Semua &raquo;</option>
                                  </select>
                              </div>
                          </div>
                          <div class="form-group row mb-3">
                              <label for="status_filter" class="col-sm-2 col-form-label">Status</label>
                              <div class="col-sm-10">
                                  <select class="form-control" id="status_filter">
                                      <option value="">&laquo; Semua &raquo;</option>
                                      <option value="YA">Tampilkan</option>
                                      <option value="TIDAK">Sembunyikan</option>
                                  </select>
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
      <div class="card-datatable table-responsive pt-0">
        <table id="dataTabel" class="datatables-basic table table-bordered">
          <thead>
            <tr>
              <th>No</th>
              <th>Aksi</th>
              <th>Tipe Ujian</th>
              <th>Tanggal</th>
              <th>Waktu</th>
              <th>Area</th>
              <th>Gedung</th>
              <th>Ruangan</th>
              <th>Kuota</th>
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