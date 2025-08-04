<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="py-3 breadcrumb-wrapper mb-4">Sanggah</h4>
    <p>
    <div class="card">
        <div class="card-header header-elements">
            <span class="me-2 h5">Data Sanggah</span>
            <div class="card-header-elements ms-auto">
                <button type="button" style="margin-top: -15px" class="btn btn-xs btn-primary" data-bs-toggle="modal" data-bs-target="#generateModal">
                    <span class="tf-icon bx bx-refresh bx-xs"></span> Generate
                </button>
                <button type="button" style="margin-top: -15px" class="btn btn-xs btn-primary" data-bs-toggle="collapse" data-bs-target="#filterForm" aria-expanded="false" aria-controls="filterForm">
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
                        <th>Tahun</th>
                        <th>Nomor Peserta</th>
                        <th>Nama</th>
                        <th>Kategori</th>
                        <th>Tipe Ujian</th>
                        <th>Status Sanggah</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
    </p>
</div>