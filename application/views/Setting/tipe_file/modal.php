<!-- Tambah -->
<div class="modal fade" id="modal_form">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel1"></h5>
                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                    aria-label="Close"
                ></button>
            </div>
            <form method="POST" action="#" id="form" enctype="multipart/form-data">
            <div class="modal-body" style="overflow:hidden;">
                <input type="hidden" value="" name="ids_tipe_file"/>
                <div class="row">
                    <div class="col mb-3">
                        <label for="setting" class="form-label">Setting</label>
                        <input type="text" id="setting" name="setting" class="form-control" readonly="">
                        <span class="invalid-feedback"></span>
                    </div>
                </div>
                <div class="row">
                    <div class="col mb-3">
                        <label for="nama_file" class="form-label">Nama File</label>
                        <input type="text" id="nama_file" name="nama_file" class="form-control">
                        <span class="invalid-feedback"></span>
                    </div>
                </div>
                <div class="row">
                    <div class="col mb-3">
                        <label for="extensi" class="form-label">Ekstensi</label>
                        <input type="text" id="extensi " name="extensi" class="form-control">
                        <span class="invalid-feedback"></span>
                    </div>
                </div>
                <div class="row">
                    <div class="col mb-3">
                        <label for="max_size" class="form-label">Max Size</label>
                        <input type="text" id="max_size" name="max_size" class="form-control">
                        <span class="invalid-feedback"></span>
                    </div>
                </div>
                <div class="row jalur_masuk_field">
                    <div class="col mb-3">
                        <label for="ids_jalur_masuk" class="form-label">Jalur Masuk</label>
                        <select id="ids_jalur_masuk" name="ids_jalur_masuk[]" class="form-control" multiple></select>
                        <span class="invalid-feedback"></span>
                    </div>
                </div>
                <div class="row tipe_ujian_field">
                    <div class="col mb-3">
                        <label for="ids_tipe_ujian" class="form-label">Tipe Ujian</label>
                        <select id="ids_tipe_ujian" name="ids_tipe_ujian[]" class="form-control" multiple></select>
                        <span class="invalid-feedback"></span>
                    </div>
                </div>
                <div class="row">
                    <div class="col mb-3">
                        <label for="upload" class="form-label">Upload</label>
                        <div class="row">
                          <div class="col-md-6">
                            <div class="input-group">
                              <span class="input-group-text" id="basic-addon11">
                                <input
                                  class="form-check-input"
                                  type="radio"
                                  name="upload"
                                  value="Wajib"
                                  id="upload1"
                                />
                              </span>
                              <input
                                type="text"
                                class="form-control"
                                value="Wajib"
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
                                  name="upload"
                                  value="Opsional"
                                  id="upload0"
                                />
                              </span>
                              <input
                                type="text"
                                class="form-control"
                                value="Opsional"
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
                        <label for="status" class="form-label">Status</label>
                        <div class="row">
                          <div class="col-md-6">
                            <div class="input-group">
                              <span class="input-group-text" id="basic-addon11">
                                <input
                                  class="form-check-input"
                                  type="radio"
                                  name="status"
                                  value="YA"
                                  id="status1"
                                />
                              </span>
                              <input
                                type="text"
                                class="form-control"
                                value="Tampilkan"
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
                                  name="status"
                                  value="TIDAK"
                                  id="status0"
                                />
                              </span>
                              <input
                                type="text"
                                class="form-control"
                                value="Sembuyikan"
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
                <button type="button" id="btnSave" onclick="save()" class="btn btn-primary">Simpan</button>
            </div>
            </form>
        </div>
    </div>
</div>