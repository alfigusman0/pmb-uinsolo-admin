<div class="container-xxl flex-grow-1 container-p-y">
  <h4 class="py-3 breadcrumb-wrapper mb-4">Akun</h4>
  <p>
    <div class="card">
      <div class="card-header header-elements">
        <span class="me-2 h5">Data Users</span>
        <div class="card-header-elements ms-auto">
            <button type="button" style="margin-top: -15px" onclick="add_data()" class="btn btn-xs btn-primary">
              <span class="tf-icon bx bx-plus bx-xs"></span> Tambah
            </button>
        </div>
      </div>
      <div class="card-datatable table-responsive pt-0">
        <table id="dataTabel" class="datatables-basic table table-bordered">
          <thead>
            <tr>
              <th>No</th>
              <th>Aksi</th>
              <th>Nama</th>
              <th>Username</th>
              <th>Level</th>
            </tr>
          </thead>
          <tbody>
          </tbody>
        </table>
      </div>
    </div>
  </p>
</div>