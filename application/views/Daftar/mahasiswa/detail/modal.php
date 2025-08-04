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
          <div class="row" id="tgl_pembayaran_field">
            <div class="col mb-3">
              <label for="tgl_pembayaran" class="form-label">Tanggal Pembayaran</label>
              <input type="datetime-local" id="tgl_pembayaran" name="tgl_pembayaran" class="form-control">
              <span class="invalid-feedback"></span>
            </div>
          </div>
          <div class="row">
            <div class="col mb-3">
              <label for="ket_pembayaran" class="form-label">Keterangan Pembayaran</label>
              <textarea id="ket_pembayaran" name="ket_pembayaran" class="form-control"></textarea>
              <span class="invalid-feedback"></span>
            </div>
          </div>
          <div class="row">
            <div class="col mb-3">
              <label for="pemberkasan" class="form-label">Status Pemberkasan</label>
              <div class="row">
                <div class="col-md-6">
                  <div class="input-group">
                    <span class="input-group-text" id="basic-addon11">
                      <input class="form-check-input" type="radio" name="pemberkasan" value="SUDAH" id="pemberkasan1" />
                    </span>
                    <input type="text" class="form-control" value="Sudah" readonly="" />
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="input-group">
                    <span class="input-group-text" id="basic-addon11">
                      <input class="form-check-input" type="radio" name="pemberkasan" value="BELUM" id="pemberkasan0" />
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

<!-- View -->
<div class="modal fade" id="modal_view">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="view_title"></h5>
                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                    aria-label="Close"
                ></button>
            </div>
            <div class="modal-body text-center">
              <a href="#" id="url" target="_blank"><img src="https://placehold.co/300x400" id="view_foto" class="img-fluid"></a>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal_pindah">
    <div class="modal-dialog" role="document">
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
            <form method="POST" action="#" id="formPindah" enctype="multipart/form-data">
            <div class="modal-body">
              <input type="hidden" value="<?=$viewdKelulusan->idd_kelulusan?>" name="idd_kelulusan" id="idd_kelulusan">
              <div class="row">
                <div class="col mb-3">
                  <div class="alert alert-warning">
                    <b>Syarat pindah jalur masuk:</b>
                    <ul>
                      <li>Peserta asal harus sudah melakukan pembayaran.</li>
                      <li>Proses perpindahan dilakukan setelah UKT peserta tujuan ditetapkan.</li>
                      <li>Jika nominal UKT peserta asal lebih besar dari nominal UKT peserta tujuan, maka pembayaran pada peserta tujuan akan dilunaskan.</li>
                      <li>Jika nominal UKT peserta asal lebih kecil dari nominal UKT peserta tujuan, maka pembayaran pada peserta tujuan akan dipotong dari nominal UKT asal.</li>
                    </ul>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col mb-3">
                  <label for="pencarian_kelulusan" class="form-label">Pencarian</label>
                  <select id="pencarian_kelulusan" name="pencarian_kelulusan" class="form-control"></select>
                  <span class="invalid-feedback"></span>
                </div>
              </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">
                    Tutup
                </button>
                <button type="button" id="btnPindah" onclick="pindah()" class="btn btn-primary">Pindahkan</button>
            </div>
            </form>
        </div>
    </div>
</div>