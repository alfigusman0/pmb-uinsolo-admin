<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="py-3 breadcrumb-wrapper mb-4">Mandiri</h4>
    <p>
    <div class="card">
        <div class="card-header header-elements">
            <span class="me-2 h5">Pembayaran</span>
            <div class="card-header-elements ms-auto">
                <button type="button" style="margin-top: -15px" class="btn btn-xs btn-primary" data-bs-toggle="collapse" data-bs-target="#filterForm" aria-expanded="false" aria-controls="filterForm">
                    <span class="tf-icon bx bx-filter bx-xs"></span> Filter
                </button>
                <div class="dropdown" style="margin-top: -15px">
                    <button class="btn btn-xs btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bx bx-cog"></i>
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                        <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#tambahPembayaran">Tambah</a></li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="collapse" id="filterForm">
            <div class="border p-4">
                <div class="row">
                    <div class="col-md-12">
                        <form class="form-horizontal" id="form-filter">
                            <div class="form-group row mb-3">
                                <label for="idp_formulir_filter" class="col-sm-2 col-form-label">Kode Pembayaran</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="idp_formulir_filter" placeholder="Kode Pembayaran">
                                </div>
                            </div>
                            <div class="form-group row mb-3">
                                <label for="nomor_peserta_filter" class="col-sm-2 col-form-label">Nomor Peserta</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="nomor_peserta_filter" placeholder="Nomor Peserta">
                                </div>
                            </div>
                            <div class="form-group row mb-3">
                                <label for="nama_filter" class="col-sm-2 col-form-label">Nama</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="nama_filter" placeholder="Nama">
                                </div>
                            </div>
                            <div class="form-group row mb-3">
                                <label for="alias_bank_filter" class="col-sm-2 col-form-label">Bank</label>
                                <div class="col-sm-10">
                                    <select class="form-control" id="alias_bank_filter">
                                        <option value="">&laquo; Semua &raquo;</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row mb-3">
                                <label for="expire_at_filter" class="col-sm-2 col-form-label">Tanggal Kadaluarsa</label>
                                <div class="col-sm-10">
                                    <input type="date" class="form-control" id="expire_at_filter">
                                </div>
                            </div>
                            <div class="form-group row mb-3">
                                <label for="va_filter" class="col-sm-2 col-form-label">VA</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="va_filter" placeholder="VA">
                                </div>
                            </div>
                            <div class="form-group row mb-3">
                                <label for="id_billing_filter" class="col-sm-2 col-form-label">ID Billing</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="id_billing_filter" placeholder="ID Billing">
                                </div>
                            </div>
                            <div class="form-group row mb-3">
                                <label for="pembayaran_filter" class="col-sm-2 col-form-label">Status Pembayaran</label>
                                <div class="col-sm-10">
                                    <select class="form-control" id="pembayaran_filter">
                                        <option value="">&laquo; Semua &raquo;</option>
                                        <option value="SUDAH">Sudah</option>
                                        <option value="BELUM">Belum</option>
                                        <option value="EXPIRED">Kadaluarsa</option>
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
                        <th>Kode Pembayaran</th>
                        <th>Nomor Peserta</th>
                        <th>Nama</th>
                        <th>Bank</th>
                        <th>VA</th>
                        <th>ID Billing</th>
                        <th>Tanggal Kadaluarsa</th>
                        <th>Status Pembayaran</th>
                        <th>Date Created</th>
                        <th>Date Updated</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
    </p>
</div>