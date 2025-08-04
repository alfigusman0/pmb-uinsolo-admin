<div class="modal fade" id="modal_form_kelulusan">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form method="POST" action="#" id="formKelulusan" enctype="multipart/form-data">
        <div class="modal-body" style="overflow:hidden;">
          <input type="hidden" value="" id="idd_kelulusan" name="idd_kelulusan" />
          <div class="row">
            <div class="col mb-3">
              <label for="nama" class="form-label">Nama</label>
              <input type="text" id="nama" name="nama" class="form-control">
              <span class="invalid-feedback"></span>
            </div>
          </div>
          <div class="row">
            <div class="col mb-3">
              <label for="daftar" class="form-label">Status Daftar</label>
              <div class="row">
                <div class="col-md-6">
                  <div class="input-group">
                    <span class="input-group-text" id="basic-addon11">
                      <input class="form-check-input" type="radio" name="daftar" value="SUDAH" id="daftar1" />
                    </span>
                    <input type="text" class="form-control" value="Sudah" readonly="" />
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="input-group">
                    <span class="input-group-text" id="basic-addon11">
                      <input class="form-check-input" type="radio" name="daftar" value="BELUM" id="daftar0" />
                    </span>
                    <input type="text" class="form-control" value="Belum" readonly="" />
                  </div>
                </div>
              </div>
              <span class="invalid-feedback"></span>
            </div>
          </div>
          <div class="row">
            <div class="col mb-3">
              <label for="submit" class="form-label">Status Submit</label>
              <div class="row">
                <div class="col-md-6">
                  <div class="input-group">
                    <span class="input-group-text" id="basic-addon11">
                      <input class="form-check-input" type="radio" name="submit" value="SUDAH" id="submit1" />
                    </span>
                    <input type="text" class="form-control" value="Sudah" readonly="" />
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="input-group">
                    <span class="input-group-text" id="basic-addon11">
                      <input class="form-check-input" type="radio" name="submit" value="BELUM" id="submit0" />
                    </span>
                    <input type="text" class="form-control" value="Belum" readonly="" />
                  </div>
                </div>
              </div>
              <span class="invalid-feedback"></span>
            </div>
          </div>
          <div class="row">
            <div class="col mb-3">
              <label for="pembayaran" class="form-label">Status Pembayaran</label>
              <div class="row">
                <div class="col-md-6">
                  <div class="input-group">
                    <span class="input-group-text" id="basic-addon11">
                      <input class="form-check-input" type="radio" name="pembayaran" value="SUDAH" id="pembayaran1" />
                    </span>
                    <input type="text" class="form-control" value="Sudah" readonly="" />
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="input-group">
                    <span class="input-group-text" id="basic-addon11">
                      <input class="form-check-input" type="radio" name="pembayaran" value="BELUM" id="pembayaran0" />
                    </span>
                    <input type="text" class="form-control" value="Belum" readonly="" />
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