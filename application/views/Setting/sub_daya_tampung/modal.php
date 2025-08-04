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
                <input type="hidden" value="" name="ids_sub_daya_tampung"/> 
                <div class="row">
                    <div class="col mb-3">
                        <label for="kode_jurusan" class="form-label">Jurusan</label>
                        <select id="kode_jurusan " name="kode_jurusan" class="form-control">
                          <option value="">-- Pilih --</option>
                          <?php foreach($jurusan->data->data as $a): ?>
                            <option value="<?=$a->kode_jurusan?>"><?=$a->jurusan?></option>
                          <?php endforeach; ?>
                        </select>
                        <span class="invalid-feedback"></span>
                    </div>
                </div>
                <div class="row">
                    <div class="col mb-3">
                        <label for="ids_jalur_masuk" class="form-label">Jalur Masuk</label>
                        <select id="ids_jalur_masuk " name="ids_jalur_masuk" class="form-control">
                          <option value="">-- Pilih --</option>
                          <?php foreach($jalurmasuk->data->data as $a): ?>
                            <option value="<?=$a->ids_jalur_masuk?>"><?=$a->alias?></option>
                          <?php endforeach; ?>
                        </select>
                        <span class="invalid-feedback"></span>
                    </div>
                </div>
                <div class="row">
                    <div class="col mb-3">
                        <label for="daya_tampung" class="form-label">Daya Tampung</label>
                        <input type="number" min="0" id="daya_tampung" name="daya_tampung" class="form-control">
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