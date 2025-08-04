<div class="container-xxl flex-grow-1 container-p-y">
  <h4 class="py-3 breadcrumb-wrapper mb-4">
    <span class="text-muted fw-light">Setting /</span> Bobot Jurusan
  </h4>
  <p>
    <?php $this->load->view('layout/notification'); ?>
  </p>
  <p>
    <div class="card">
      <div class="card-header header-elements">
        <span class="me-2 h5">Data Bobot Jurusan</span>
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
            </ul>
          </div>
        </div>
      </div>
      <div class="card-datatable table-responsive pt-0">
        <table id="dataTabel" class="datatables-basic table table-bordered">
          <thead>
            <tr>
              <th>No</th>
              <th>Aksi</th>
              <th>Fakultas</th>
              <th>Jurusan</th>
              <th>TPA</th>
              <th>IPS</th>
              <th>IPA</th>
              <th>BTQ</th>
              <th>TKD</th>
              <th>Keislaman</th>
              <th>B. Arab</th>
              <th>B. Inggris</th>
              <th>B. Indonesia</th>
              <th>Pembagi</th>
            </tr>
          </thead>
          <tbody>
          </tbody>
        </table>
      </div>
    </div>
  </p>
</div>