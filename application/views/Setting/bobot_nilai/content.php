<div class="container-xxl flex-grow-1 container-p-y">
  <h4 class="py-3 breadcrumb-wrapper mb-4">
    <span class="text-muted fw-light">Setting /</span> Bobot Nilai UKT
  </h4>
  <p>
    <?php $this->load->view('layout/notification'); ?>
  </p>
  <p>
    <div class="card">
      <div class="card-header header-elements">
        <span class="me-2 h5">Data Bobot Nilai UKT</span>
        <div class="card-header-elements ms-auto">
          <div class="btn-group">
            <button
              type="button"
              class="btn btn-primary btn-sm btn-icon rounded-pill dropdown-toggle hide-arrow"
              data-bs-toggle="dropdown"
              aria-expanded="false"
            >
              <i class="bx bx-dots-vertical-rounded"></i>
            </button>
            <ul class="dropdown-menu dropdown-menu-end">
              <li><a class="dropdown-item" href="javascript:void(0);" onclick="add_data()">Tambah</a></li>
              <li><a class="dropdown-item" href="javascript:void(0);" onclick="generate()">Generate Nilai</a></li>
              <li>
                <hr class="dropdown-divider" />
              </li>
              <li><a class="dropdown-item" href="javascript:void(0);" onclick="simpan_setting()">Simpan Setting</a></li>
            </ul>
          </div>
        </div>
      </div>
      <div class="mt-2">
        <div class="alert alert-info">Total Bobot: <span id="total-bobot">0</span></div>
      </div>
      <div class="card-datatable table-responsive pt-0">
        <table id="dataTabel" class="datatables-basic table table-bordered">
          <thead>
            <tr>
              <th>No</th>
              <th>Aksi</th>
              <th>Nama Field</th>
              <th>Alias</th>
              <th>Bobot</th>
              <th>Nilai Max</th>
              <th>Jalur Masuk</th>
              <th>Tahun</th>
            </tr>
          </thead>
          <tbody>
          </tbody>
        </table>
      </div>
    </div>
  </p>
</div>