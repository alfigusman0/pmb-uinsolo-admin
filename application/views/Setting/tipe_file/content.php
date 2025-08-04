<div class="container-xxl flex-grow-1 container-p-y">
  <h4 class="py-3 breadcrumb-wrapper mb-4">
    <span class="text-muted fw-light">Setting /</span> Tipe File
  </h4>
  <p>
    <?php $this->load->view('layout/notification'); ?>
  </p>
  <p>
    <div class="card">
      <div class="card-header header-elements">
        <span class="me-2 h5">Data Tipe File</span>
        <div class="card-header-elements ms-auto">
            <button type="button" style="margin-top: -15px" onclick="add_data()" class="btn btn-xs btn-primary">
              <span class="tf-icon bx bx-plus bx-xs"></span> Tambah
            </button>
        </div>
      </div>
      <div class="card-datatable table-responsive pt-0">
        <div class="border p-4">
            <div class="row">
                <div class="col-md-12">
                    <form class="form-horizontal" id="form-filter">
                        <div class="form-group row mb-3">
                            <label for="setting_filter" class="col-sm-2 col-form-label">Setting</label>
                            <div class="col-sm-10">
                                <select class="form-control" id="setting_filter">
                                  <option value="PEMBERKASAN">PEMBERKASAN</option>
                                  <option value="PENDAFTARAN">PENDAFTARAN</option>
                                  <option value="DAFTAR_UKT">UKT</option>
                                </select>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <table id="dataTabel" class="datatables-basic table table-bordered">
          <thead>
            <tr>
              <th>No</th>
              <th>Aksi</th>
              <th>Nama File</th>
              <th>Setting</th>
              <th>Ekstensi</th>
              <th>Max Size</th>
              <th>Jalur Masuk</th>
              <th>Tipe Ujian</th>
              <th>Upload</th>
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