<div class="container-xxl flex-grow-1 container-p-y">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="py-3 mb-0">Penilaian</h4>
        <button id="btnLihatRekap" class="btn btn-primary">Lihat Rekap Nilai</button>
    </div>
    <p>
    <div class="row g-3">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col mb-3">
                            <label for="cari_mahasiswa" class="form-label">Cari Mahasiswa</label>
                            <select id="cari_mahasiswa" name="cari_mahasiswa" class="form-control">
                            </select>
                            <span class="invalid-feedback"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-sm-12 div-detail">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">
                        <span class="me-2 h5">Profil Mahasiswa</span>
                    </div>
                </div>
                <div class="card-datatable" style="margin-top: -30px">
                    <div class="table-responseive">
                        <table class="table table-hover">
                            <tr>
                                <td colspan='3' class="text-center">
                                    <img src="https://cdn-icons-png.flaticon.com/512/3607/3607444.png" class="img-fluid" style="width: 200px; height: auto" id="foto">
                                </td>
                            </tr>
                            <tr>
                                <td>Nomor Peserta</td>
                                <td>:</td>
                                <td id="td-nomor-peserta"></td>
                            </tr>
                            <tr>
                                <td>Nama</td>
                                <td>:</td>
                                <td id="td-nama"></td>
                            </tr>
                            <tr>
                                <td>Program</td>
                                <td>:</td>
                                <td id="td-program"></td>
                            </tr>
                            <tr>
                                <td>Tipe Ujian</td>
                                <td>:</td>
                                <td id="td-tipe-ujian"></td>
                            </tr>
                            <tr>
                                <td>Pilihan 1</td>
                                <td>:</td>
                                <td id="td-pilihan1"></td>
                            </tr>
                            <tr>
                                <td>Pilihan 2</td>
                                <td>:</td>
                                <td id="td-pilihan2"></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-8 col-sm-12 div-detail">
            <div class="card">
                <div class="card-body">
                    <ul class="nav nav-tabs mb-3" id="pills-tab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="pills-home-tab" data-bs-toggle="pill" data-bs-target="#pills-home" type="button" role="tab" aria-controls="pills-home" aria-selected="true">Proposal Tesis/ Disertasi</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="pills-ijazah-tab" data-bs-toggle="pill" data-bs-target="#pills-ijazah" type="button" role="tab" aria-controls="pills-ijazah" aria-selected="false">Ijazah</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="pills-rekomendasi-tab" data-bs-toggle="pill" data-bs-target="#pills-rekomendasi" type="button" role="tab" aria-controls="pills-rekomendasi" aria-selected="false">Rekomendasi</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="pills-profile-tab" data-bs-toggle="pill" data-bs-target="#pills-profile" type="button" role="tab" aria-controls="pills-profile" aria-selected="false">Penilaian</button>
                        </li>
                    </ul>
                    <div class="tab-content" id="pills-tabContent">
                        <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
                            <div id="my-pdf-proposal"></div>
                        </div>
                        <div class="tab-pane fade" id="pills-ijazah" role="tabpanel" aria-labelledby="pills-ijazah-tab">
                            <div id="my-pdf-ijazah"></div>
                        </div>
                        <div class="tab-pane fade" id="pills-rekomendasi" role="tabpanel" aria-labelledby="pills-rekomendasi-tab">
                            <div id="my-pdf-rekomendasi"></div>
                        </div>
                        <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
                            <form id="form-nilai">
                                <input type="hidden" id="idp_formulir" name="idp_formulir">
                                <div class="row">
                                    <div class="col mb-3">
                                        <label for="studi_naskah" class="form-label">Studi Naskah/BTQ</label>
                                        <input type="number" min="0" max="40" id="studi_naskah" name="studi_naskah" class="form-control" value="0">
                                        <span class="invalid-feedback"></span>
                                        <div class="badge bg-info">Range nilai 0-40</div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col mb-3">
                                        <label for="proposal" class="form-label">Proposal Thesis/ Disertasi</label>
                                        <input type="number" min="0" max="40" id="proposal" name="proposal" class="form-control" value="0">
                                        <span class="invalid-feedback"></span>
                                        <div class="badge bg-info">Range nilai 0-40</div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col mb-3">
                                        <label for="moderasi" class="form-label">Moderasi Beragama dan Wawasan Kebangsaan</label>
                                        <input type="number" min="0" max="20" id="moderasi" name="moderasi" class="form-control" value="0">
                                        <span class="invalid-feedback"></span>
                                        <div class="badge bg-info">Range nilai 0-20</div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col mb-3">
                                        <button type="button" id="btnSimpan" class="btn btn-primary float-end" onclick="simpan_nilai()">Simpan</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </p>
</div>