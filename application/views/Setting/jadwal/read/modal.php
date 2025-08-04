<!-- Tambah -->
<div class="modal fade" id="modal_form">
    <div class="modal-dialog modal-lg" role="document">
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
                <input type="hidden" value="" name="ids_jadwal"/> 
                <div class="row">
                    <div class="col-6 mb-3">
                        <label for="ids_program" class="form-label">Program</label>
                        <select id="ids_program" name="ids_program" class="form-control">
                          <option value="">-- Pilih --</option>
                          <?php foreach($tbsProgram as $a): ?>
                            <option value="<?=$a->ids_program?>"><?=$a->program?></option>
                          <?php endforeach; ?>
                        </select>
                        <span class="invalid-feedback"></span>
                    </div>
                    <div class="col-6 mb-3">
                        <label for="tipe_ujian" class="form-label">Tipe Ujian</label>
                        <select id="ids_tipe_ujian" name="ids_tipe_ujian" class="form-control">
                          <option value="">-- Pilih --</option>
                          <?php foreach($tbsTipeUjian as $a): ?>
                            <option value="<?=$a->ids_tipe_ujian?>" data-chained="<?=$a->ids_program?>"><?=$a->tipe_ujian?></option>
                          <?php endforeach; ?>
                        </select>
                        <span class="invalid-feedback"></span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-4 mb-3">
                        <label for="tanggal" class="form-label">Tanggal</label>
                        <input type="date" id="tanggal" name="tanggal" class="form-control">
                        <span class="invalid-feedback"></span>
                    </div>
                    <div class="col-4 mb-3">
                        <label for="jam_awal" class="form-label">Jam Awal</label>
                        <input type="time" id="jam_awal" name="jam_awal" class="form-control">
                        <span class="invalid-feedback"></span>
                    </div>
                    <div class="col-4 mb-3">
                        <label for="jam_akhir" class="form-label">Jam Akhir</label>
                        <input type="time" id="jam_akhir" name="jam_akhir" class="form-control">
                        <span class="invalid-feedback"></span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6 mb-3">
                        <label for="quota" class="form-label">Quota</label>
                        <input type="number" id="quota" name="quota" class="form-control">
                        <span class="invalid-feedback"></span>
                    </div>
                    <div class="col-6 mb-3">
                        <label for="ids_area" class="form-label">Area</label>
                        <select id="ids_area" name="ids_area" class="form-control">
                          <option value="">-- Pilih --</option>
                        </select>
                        <span class="invalid-feedback"></span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6 mb-3">
                        <label for="ids_gedung" class="form-label">Gedung</label>
                        <select id="ids_gedung" name="ids_gedung" class="form-control">
                          <option value="">-- Pilih --</option>
                        </select>
                        <span class="invalid-feedback"></span>
                    </div>
                    <div class="col-6 mb-3">
                        <label for="ids_ruangan" class="form-label">Ruangan</label>
                        <select id="ids_ruangan" name="ids_ruangan" class="form-control">
                          <option value="">-- Pilih --</option>
                        </select>
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