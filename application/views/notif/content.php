<div class="container-xxl flex-grow-1 container-p-y">
  <h4 class="py-3 breadcrumb-wrapper mb-4">Notifikasi</h4>
  <p>
    <div class="card">
      <div class="card-header header-elements">
        <span class="me-2 h5">Data Notifikasi</span>
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
                              <label for="nama_filter" class="col-sm-2 col-form-label">Nama</label>
                              <div class="col-sm-10">
                                  <input type="text" class="form-control" id="nama_filter" placeholder="Nama">
                              </div>
                          </div>
                          <div class="form-group row mb-3">
                              <label for="email_filter" class="col-sm-2 col-form-label">Email</label>
                              <div class="col-sm-10">
                                  <input type="text" class="form-control" id="email_filter" placeholder="Email">
                              </div>
                          </div>
                          <div class="form-group row mb-3">
                              <label for="judul_filter" class="col-sm-2 col-form-label">Judul</label>
                              <div class="col-sm-10">
                                  <input type="text" class="form-control" id="judul_filter" placeholder="Judul">
                              </div>
                          </div>
                          <div class="form-group row mb-3">
                              <label for="isi_filter" class="col-sm-2 col-form-label">Isi</label>
                              <div class="col-sm-10">
                                  <input type="text" class="form-control" id="isi_filter" placeholder="Isi">
                              </div>
                          </div>
                          <div class="form-group row mb-3">
                              <label for="dibaca_filter" class="col-sm-2 col-form-label">Dibaca</label>
                              <div class="col-sm-10">
                                  <select class="form-control" id="dibaca_filter">
                                      <option value="">&laquo; Semua &raquo;</option>
                                      <option value="YA">IYA</option>
                                      <option value="TIDAK">TIDAK</option>
                                  </select>
                              </div>
                          </div>
                            <div class="form-group row mb-3">
                                <label for="semail_filter" class="col-sm-2 col-form-label">Kirim Email?</label>
                                <div class="col-sm-10">
                                    <select class="form-control" id="semail_filter">
                                        <option value="">&laquo; Semua &raquo;</option>
                                        <option value="YA">IYA</option>
                                        <option value="TIDAK">TIDAK</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row mb-3">
                                <label for="status_email_filter" class="col-sm-2 col-form-label">Respon Email</label>
                                <div class="col-sm-10">
                                    <select class="form-control" id="status_email_filter">
                                        <option value="">&laquo; Semua &raquo;</option>
                                        <option value="YA">IYA</option>
                                        <option value="TIDAK">TIDAK</option>
                                        <option value="ERROR">ERROR</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row mb-3">
                                <label for="swhatsapp_filter" class="col-sm-2 col-form-label">Kirim Whatsapp?</label>
                                <div class="col-sm-10">
                                    <select class="form-control" id="swhatsapp_filter">
                                        <option value="">&laquo; Semua &raquo;</option>
                                        <option value="YA">IYA</option>
                                        <option value="TIDAK">TIDAK</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row mb-3">
                                <label for="whatsapp_filter" class="col-sm-2 col-form-label">Respon Whatsapp</label>
                                <div class="col-sm-10">
                                    <select class="form-control" id="whatsapp_filter">
                                        <option value="">&laquo; Semua &raquo;</option>
                                        <option value="YA">IYA</option>
                                        <option value="TIDAK">TIDAK</option>
                                        <option value="ERROR">ERROR</option>
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
              <th>Nama</th>
              <th>Email</th>
              <th>Judul</th>
              <th>Isi</th>
              <th>Dibaca</th>
              <th>Status Email</th>
              <th>Respon Email</th>
              <th>Status Whatsapp</th>
              <th>Respon Whatsapp</th>
            </tr>
          </thead>
          <tbody>
          </tbody>
        </table>
      </div>
    </div>
  </p>
</div>