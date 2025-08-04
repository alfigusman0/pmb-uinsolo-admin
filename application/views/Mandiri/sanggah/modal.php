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
                <div class="row">
                    <div class="col-md-12 text-center">
                        <h5>Nilai Akhir : <span id="nilaiKelulusan"></span></h5>
                    </div>
                    <div class="col-md-12">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Pilihan</th>
                                    <th>Jurusan</th>
                                    <th>Grade</th>
                                </tr>
                            </thead>
                            <tbody id="dataGrade">
                            </tbody>
                        </table>
                    </div>
                </div>
                <input type="hidden" value="" name="idp_sanggah"/>
                <div class="row">
                    <div class="col mb-3">
                        <label for="sanggah" class="form-label">Sanggah</label>
                        <textarea id="sanggah" name="sanggah" class="form-control" readonly="" rows="10"></textarea>
                        <span class="invalid-feedback"></span>
                    </div>
                </div>
                <div class="row">
                    <div class="col mb-3">
                        <label for="ids_sanggah" class="form-label">Jawaban</label>
                        <select id="ids_sanggah " name="ids_sanggah" class="form-control">
                          <option value="">-- Pilih --</option>
                          <?php foreach($tbsSanggah as $a): ?>
                            <option value="<?=$a->ids_sanggah?>"><?=$a->sanggah?></option>
                          <?php endforeach; ?>
                        </select>
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

<!-- Generate -->
<div class="modal fade" id="generateModal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel1">Generate Jawaban Sanggah</h5>
                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                    aria-label="Close"
                ></button>
            </div>
            <form method="POST" action="#" id="form_generate" enctype="multipart/form-data">
            <div class="modal-body" style="overflow:hidden;">
                <div class="row">
                    <div class="col mb-3">
                        <label for="tahun" class="form-label">Tahun</label>
                        <select id="tahun " name="tahun" class="form-control">
                          <option value="">-- Pilih --</option>
                          <?php foreach($tahun as $a): ?>
                            <option value="<?=$a->tahun?>"><?=$a->tahun?></option>
                          <?php endforeach; ?>
                        </select>
                        <span class="invalid-feedback"></span>
                    </div>
                </div>
                <div class="row">
                    <div class="col mb-3">
                        <label for="ids_sanggah" class="form-label">Jawaban</label>
                        <select id="ids_sanggah " name="ids_sanggah" class="form-control">
                          <option value="">-- Pilih --</option>
                          <?php foreach($tbsSanggah as $a): ?>
                            <option value="<?=$a->ids_sanggah?>"><?=$a->sanggah?></option>
                          <?php endforeach; ?>
                        </select>
                        <span class="invalid-feedback"></span>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">
                    Tutup
                </button>
                <button type="button" id="btnGenerate" onclick="generate()" class="btn btn-primary">Generate</button>
            </div>
            </form>
        </div>
    </div>
</div>