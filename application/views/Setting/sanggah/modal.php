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
                <input type="hidden" value="" name="ids_sanggah"/>
                <div class="row">
                    <div class="col mb-3">
                        <label for="sanggah" class="form-label">Sanggah</label>
                        <input type="text" id="sanggah" name="sanggah" class="form-control">
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