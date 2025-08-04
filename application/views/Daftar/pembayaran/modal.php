

<div class="modal fade" id="modal_form_pembayaran" tabindex="-1" aria-labelledby="modal_form_pembayaranLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modal_form_pembayaranLabel">Tambah Pembayaran</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form method="POST" action="#" id="formPembayaran" enctype="multipart/form-data">
      <div class="modal-body">
        <input type="hidden" value="" id="idd_pembayaran" name="idd_pembayaran" />
        <div class="row">
          <div class="col mb-3">
            <label for="pembayaran" class="form-label">Status Pembayaran</label>
            <div class="row">
              <div class="col-md-4">
                <div class="input-group">
                  <span class="input-group-text" id="basic-addon11">
                    <input class="form-check-input" type="radio" name="pembayaran" value="SUDAH" id="pembayaran1" />
                  </span>
                  <input type="text" class="form-control" value="Sudah" readonly="" />
                </div>
              </div>
              <div class="col-md-4">
                <div class="input-group">
                  <span class="input-group-text" id="basic-addon11">
                    <input class="form-check-input" type="radio" name="pembayaran" value="BELUM" id="pembayaran0" />
                  </span>
                  <input type="text" class="form-control" value="Belum" readonly="" />
                </div>
              </div>
              <div class="col-md-4">
                <div class="input-group">
                  <span class="input-group-text" id="basic-addon11">
                    <input class="form-check-input" type="radio" name="pembayaran" value="EXPIRED" id="pembayaran2" />
                  </span>
                  <input type="text" class="form-control" value="Expired" readonly="" />
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
        <button type="button" id="btnSave" onclick="update()" class="btn btn-primary">Simpan</button>
      </div>
      </form>
    </div>
  </div>
</div>
<div class="modal fade" id="batalkanBankModal" tabindex="-1" aria-labelledby="batalkanBankModal" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="batalkanBankModalLabel">Modal title</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        Apakah anda yakin batalkan bank?
        <input type="hidden" name="idd_kelulusan_batalkan" id="idd_kelulusan_batalkan">
        <input type="hidden" name="ids_bank_batalkan" id="ids_bank_batalkan">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
        <button type="button" id="btnBatalkan" onclick="act_batalkan_bank()" class="btn btn-primary">Batalkan</button>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="tambahPembayaran" tabindex="-1" aria-labelledby="tambahPembayaranLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="tambahPembayaranLabel">Tambah Pembayaran</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-12">
              <form class="form-horizontal" id="form-filter">
                  <div class="form-group row mb-3">
                      <label for="nomor_peserta_tambah" class="col-sm-2 col-form-label">Nomor Peserta</label>
                      <div class="col-sm-10">
                          <input type="text" class="form-control" id="nomor_peserta_tambah" placeholder="Nomor Peserta">
                      </div>
                  </div>
                  <div class="form-group row mb-4">
                      <label for="alias_bank_tambah" class="col-sm-2 col-form-label">Bank</label>
                      <div class="col-sm-10">
                          <select class="form-control" id="alias_bank_tambah">
                              <option value="">&laquo; Semua &raquo;</option>
                          </select>
                      </div>
                  </div>
                  <div class="form-group">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="button" id="btnSubmit" onclick="tambah_pembayaran()" class="btn btn-primary float-end">Submit</button>
                  </div>
          </div>
      </div>
      </form>
    </div>
  </div>
</div>