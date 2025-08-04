<div class="modal fade" id="batalkanBankModal" tabindex="-1" aria-labelledby="batalkanBankModal" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title-batal" id="batalkanBankModalLabel">Batalkan Bank</h5>
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

<!-- Edit -->
<div class="modal fade" id="modal_form_edit">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title-edit" id="exampleModalLabel1"></h5>
                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                    aria-label="Close"
                ></button>
            </div>
            <form method="POST" action="#" id="form-edit" enctype="multipart/form-data">
            <div class="modal-body" style="overflow:hidden;">
                <input type="hidden" value="" name="idp_pembayaran_edit"/> 
                <div class="form-group mb-3">
                    <label for="kode_pembayaran_edit" class="form-label">Kode Pembayaran</label>
                    <div class="col-sm-12">
                        <input type="text" class="form-control" id="kode_pembayaran_edit" name="kode_pembayaran_edit" placeholder="Kode Pembayaran" readonly>
                    </div>
                </div>
                <div class="form-group mb-3">
                    <label for="id_billing_edit" class="form-label">ID Billing</label>
                    <div class="col-sm-12">
                        <input type="text" class="form-control" id="id_billing_edit" name="id_billing_edit" placeholder="id_billing">
                    </div>
                </div>
                <div class="form-group mb-3">
                    <label for="pembayaran_edit" class="form-label">Status Pembayaran</label>
                    <div class="col-sm-12">
                        <select class="form-control" id="pembayaran_edit" name="pembayaran_edit">
                            <option value="SUDAH">Sudah</option>
                            <option value="BELUM">Belum</option>
                            <option value="EXPIRED">Kadaluarsa</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">
                    Tutup
                </button>
                <button type="button" id="btnEdit" onclick="act_edit_bank()" class="btn btn-primary">Edit</button>
            </div>
            </form>
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
              <form class="form-horizontal" id="form-tambah-bayar">
                  <div class="form-group row mb-3">
                      <label for="kode_pembayaran_tambah" class="col-sm-2 col-form-label">Kode Pembayaran</label>
                      <div class="col-sm-10">
                          <input type="text" class="form-control" id="kode_pembayaran_tambah" placeholder="Kode Pembayaran">
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
                  <div class="form-group row mb-3">
                      <label for="nominal_tambah" class="col-sm-2 col-form-label">Nominal</label>
                      <div class="col-sm-10">
                          <input type="number" class="form-control" id="nominal_tambah" placeholder="Nominal">
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