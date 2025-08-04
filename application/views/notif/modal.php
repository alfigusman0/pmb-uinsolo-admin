<div class="modal fade" id="modal_form">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"></h5>
                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                    aria-label="Close"
                ></button>
            </div>
            <form method="POST" action="#" id="form" enctype="multipart/form-data">
            <div class="modal-body" style="overflow:hidden;">
                <input type="hidden" value="" name="id_user"/>
                <div class="row">
                    <div class="col mb-3">
                        <label for="jenis_akun" class="form-label">Jenis Akun</label>
                        <select id="jenis_akun" name="jenis_akun" class="form-control">
                          <option value="Daftar Ulang">Daftar Ulang</option>
                          <option value="Mandiri">Mandiri</option>
                        </select>
                        <span class="invalid-feedback"></span>
                    </div>
                </div>
                <div class="row jalur_masuk_field">
                    <div class="col mb-3">
                        <label for="ids_jalur_masuk" class="form-label">Jalur Masuk</label>
                        <select id="ids_jalur_masuk" name="ids_jalur_masuk" class="form-control">
                        </select>
                        <span class="invalid-feedback"></span>
                    </div>
                </div>
                <div class="row tipe_ujian_field">
                    <div class="col mb-3">
                        <label for="ids_tipe_ujian" class="form-label">Tipe Ujian</label>
                        <select id="ids_tipe_ujian" name="ids_tipe_ujian" class="form-control">
                        </select>
                        <span class="invalid-feedback"></span>
                    </div>
                </div>
                <div class="row jalur_masuk_field">
                    <div class="col mb-3">
                        <label for="daftar" class="form-label">Daftar</label>
                        <select id="daftar" name="daftar" class="form-control">
                            <option value="">&laquo; Semua &raquo;</option>
                            <option value="SUDAH">SUDAH</option>
                            <option value="BELUM">BELUM</option>
                        </select>
                        <span class="invalid-feedback"></span>
                    </div>
                </div>
                <div class="row jalur_masuk_field">
                    <div class="col mb-3">
                        <label for="submit" class="form-label">Submit</label>
                        <select id="submit" name="submit" class="form-control">
                            <option value="">&laquo; Semua &raquo;</option>
                            <option value="SUDAH">SUDAH</option>
                            <option value="BELUM">BELUM</option>
                        </select>
                        <span class="invalid-feedback"></span>
                    </div>
                </div>
                <div class="row jalur_masuk_field">
                    <div class="col mb-3">
                        <label for="pembayaran_du" class="form-label">Pembayaran</label>
                        <select id="pembayaran_du" name="pembayaran_du" class="form-control">
                            <option value="">&laquo; Semua &raquo;</option>
                            <option value="SUDAH">SUDAH</option>
                            <option value="BELUM">BELUM</option>
                        </select>
                        <span class="invalid-feedback"></span>
                    </div>
                </div>
                <div class="row tipe_ujian_field">
                    <div class="col mb-3">
                        <label for="formulir" class="form-label">Formulir</label>
                        <select id="formulir" name="formulir" class="form-control">
                            <option value="">&laquo; Semua &raquo;</option>
                            <option value="SUDAH">SUDAH</option>
                            <option value="BELUM">BELUM</option>
                        </select>
                        <span class="invalid-feedback"></span>
                    </div>
                </div>
                <div class="row tipe_ujian_field">
                    <div class="col mb-3">
                        <label for="pembayaran" class="form-label">Pembayaran</label>
                        <select id="pembayaran" name="pembayaran" class="form-control">
                            <option value="">&laquo; Semua &raquo;</option>
                            <option value="SUDAH">SUDAH</option>
                            <option value="BELUM">BELUM</option>
                        </select>
                        <span class="invalid-feedback"></span>
                    </div>
                </div>
                <div class="row">
                    <div class="col mb-3">
                        <label for="akun" class="form-label">Akun</label>
                        <select id="akun" name="akun[]" class="form-control">
                        </select>
                        <span class="invalid-feedback"></span>
                        <div class="badge bg-info">kosongkan jika kirim ke semua</div>
                    </div>
                </div>
                <div class="row">
                    <div class="col mb-3">
                        <label for="judul" class="form-label">Judul</label>
                        <input type="text" id="judul" name="judul" class="form-control">
                        <span class="invalid-feedback"></span>
                    </div>
                </div>
                <div class="row">
                    <div class="col mb-3">
                        <label for="isi" class="form-label">Isi</label>
                        <textarea id="isi" name="isi" class="form-control"></textarea>
                        <input type="hidden" name="isi_text">
                        <span class="invalid-feedback"></span>
                    </div>
                </div>
                <div class="row">
                    <div class="col mb-3">
                        <label for="semail" class="form-label">Kirim Email?</label>
                        <div class="row">
                            <div class="col-md-6">
                            <div class="input-group">
                                <span class="input-group-text" id="basic-addon11">
                                <input
                                    class="form-check-input"
                                    type="radio"
                                    name="semail"
                                    value="YA"
                                    id="semail1"
                                />
                                </span>
                                <input
                                type="text"
                                class="form-control"
                                value="Iya"
                                readonly=""
                                />
                            </div>
                            </div>
                            <div class="col-md-6">
                            <div class="input-group">
                                <span class="input-group-text" id="basic-addon11">
                                <input
                                    class="form-check-input"
                                    type="radio"
                                    name="semail"
                                    value="TIDAK"
                                    id="semail0"
                                />
                                </span>
                                <input
                                type="text"
                                class="form-control"
                                value="Tidak"
                                readonly=""
                                />
                            </div>
                            </div>
                        </div>
                        <span class="invalid-feedback"></span>
                    </div>
                </div>
                <div class="row">
                    <div class="col mb-3">
                        <label for="swhatsapp" class="form-label">Kirim Whatsapp?</label>
                        <div class="row">
                            <div class="col-md-6">
                            <div class="input-group">
                                <span class="input-group-text" id="basic-addon11">
                                <input
                                    class="form-check-input"
                                    type="radio"
                                    name="swhatsapp"
                                    value="YA"
                                    id="swhatsapp1"
                                />
                                </span>
                                <input
                                type="text"
                                class="form-control"
                                value="Iya"
                                readonly=""
                                />
                            </div>
                            </div>
                            <div class="col-md-6">
                            <div class="input-group">
                                <span class="input-group-text" id="basic-addon11">
                                <input
                                    class="form-check-input"
                                    type="radio"
                                    name="swhatsapp"
                                    value="TIDAK"
                                    id="swhatsapp0"
                                />
                                </span>
                                <input
                                type="text"
                                class="form-control"
                                value="Tidak"
                                readonly=""
                                />
                            </div>
                            </div>
                        </div>
                        <span class="invalid-feedback"></span>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">
                    Tutup
                </button>
                <button type="button" id="btnSave" onclick="save()" class="btn btn-primary">Tambah</button>
            </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modal_form_edit">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"></h5>
                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                    aria-label="Close"
                ></button>
            </div>
            <form method="POST" action="#" id="form_update" enctype="multipart/form-data">
            <div class="modal-body" style="overflow:hidden;">
                <input type="hidden" value="" name="id_notif"/>
                <div class="row">
                    <div class="col mb-3">
                        <label for="nama" class="form-label">Nama</label>
                        <input type="text" id="nama" name="nama" class="form-control" readonly="">
                        <span class="invalid-feedback"></span>
                    </div>
                </div>
                <div class="row">
                    <div class="col mb-3">
                        <label for="judul" class="form-label">Judul</label>
                        <input type="text" id="judul" name="judul" class="form-control">
                        <span class="invalid-feedback"></span>
                    </div>
                </div>
                <div class="row">
                    <div class="col mb-3">
                        <label for="isi2" class="form-label">Isi</label>
                        <textarea id="isi2" name="isi2" class="form-control"></textarea>
                        <input type="hidden" name="isi2_text">
                        <span class="invalid-feedback"></span>
                    </div>
                </div>
                <div class="row">
                    <div class="col mb-3">
                        <label for="semail" class="form-label">Kirim Email?</label>
                        <div class="row">
                            <div class="col-md-6">
                            <div class="input-group">
                                <span class="input-group-text" id="basic-addon11">
                                <input
                                    class="form-check-input"
                                    type="radio"
                                    name="semail"
                                    value="YA"
                                    id="semail1"
                                />
                                </span>
                                <input
                                type="text"
                                class="form-control"
                                value="Iya"
                                readonly=""
                                />
                            </div>
                            </div>
                            <div class="col-md-6">
                            <div class="input-group">
                                <span class="input-group-text" id="basic-addon11">
                                <input
                                    class="form-check-input"
                                    type="radio"
                                    name="semail"
                                    value="TIDAK"
                                    id="semail0"
                                />
                                </span>
                                <input
                                type="text"
                                class="form-control"
                                value="Tidak"
                                readonly=""
                                />
                            </div>
                            </div>
                        </div>
                        <span class="invalid-feedback"></span>
                    </div>
                </div>
                <div class="row">
                    <div class="col mb-3">
                        <label for="swhatsapp" class="form-label">Kirim Whatsapp?</label>
                        <div class="row">
                            <div class="col-md-6">
                            <div class="input-group">
                                <span class="input-group-text" id="basic-addon11">
                                <input
                                    class="form-check-input"
                                    type="radio"
                                    name="swhatsapp"
                                    value="YA"
                                    id="swhatsapp1"
                                />
                                </span>
                                <input
                                type="text"
                                class="form-control"
                                value="Iya"
                                readonly=""
                                />
                            </div>
                            </div>
                            <div class="col-md-6">
                            <div class="input-group">
                                <span class="input-group-text" id="basic-addon11">
                                <input
                                    class="form-check-input"
                                    type="radio"
                                    name="swhatsapp"
                                    value="TIDAK"
                                    id="swhatsapp0"
                                />
                                </span>
                                <input
                                type="text"
                                class="form-control"
                                value="Tidak"
                                readonly=""
                                />
                            </div>
                            </div>
                        </div>
                        <span class="invalid-feedback"></span>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">
                    Tutup
                </button>
                <button type="button" id="btnUpdate" onclick="update()" class="btn btn-primary">Simpan</button>
            </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modal_view_isi">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"></h5>
                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                    aria-label="Close"
                ></button>
            </div>
            <div class="modal-body" style="overflow:hidden;">
                <iframe id="view_isi" style="width: 100%; height: 500px"></iframe>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>