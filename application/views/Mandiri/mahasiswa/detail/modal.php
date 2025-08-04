<div class="modal fade" id="modal_form_formulir">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form method="POST" action="#" id="formFormulir" enctype="multipart/form-data">
        <div class="modal-body" style="overflow:hidden;">
          <input type="hidden" value="" id="idp_formulir" name="idp_formulir" />
          <div class="row">
            <div class="col mb-3">
              <label class="form-label" for="kategori">Pilih Kategori<span class="text-danger">*</span></label>
              <select name="kategori" id="kategori" class="form-select">
                <option label="-- Pilih --"></option>
                <option value="IPA">IPA / Sains</option>
                <option value="IPS">IPS / Sosial</option>
              </select>
            </div>
          </div>
          <div class="row">
            <div class="col mb-3">
              <label class="form-label" for="ids_program">Pilih Program<span class="text-danger">*</span></label>
              <select name="ids_program" id="ids_program" class="form-select">
                <option label="-- Pilih --"></option>
              </select>
            </div>
          </div>
          <div class="row">
            <div class="col mb-3">
              <label class="form-label" for="ids_tipe_ujian">Pilih Tipe Ujian<span class="text-danger">*</span></label>
              <select name="ids_tipe_ujian" id="ids_tipe_ujian" class="form-select">
                <option label="-- Pilih --"></option>
              </select>
            </div>
          </div>
          <div class="row">
            <div class="col mb-3">
              <label for="formulir" class="form-label">Status Formulir</label>
              <div class="row">
                <div class="col-md-6">
                  <div class="input-group">
                    <span class="input-group-text" id="basic-addon11">
                      <input class="form-check-input" type="radio" name="formulir" value="SUDAH" id="formulir1" />
                    </span>
                    <input type="text" class="form-control" value="Sudah" readonly="" />
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="input-group">
                    <span class="input-group-text" id="basic-addon11">
                      <input class="form-check-input" type="radio" name="formulir" value="BELUM" id="formulir0" />
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
          <div class="row">
            <div class="col mb-3">
              <label class="form-label" for="ket_pembayaran">Keterangan Pembayaran<span class="text-danger">*</span></label>
              <textarea name="ket_pembayaran" id="ket_pembayaran" class="form-control">
              </textarea>
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