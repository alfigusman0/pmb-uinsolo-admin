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
                <input type="hidden" value="" name="idp_setting"/>
                <div class="row">
                    <div class="col mb-3">
                        <label for="ids_program" class="form-label">Program</label>
                        <input type="text" id="ids_program_text" name="ids_program_text" class="form-control">
                        <input type="hidden" id="ids_program" name="ids_program" class="form-control">
                        <span class="invalid-feedback"></span>
                    </div>
                </div>
                <div class="row">
                    <div class="col mb-3">
                        <label for="ids_tipe_ujian" class="form-label">Tipe Ujian</label>
                        <input type="text" id="ids_tipe_ujian_text" name="ids_tipe_ujian_text" class="form-control">
                        <input type="hidden" id="ids_tipe_ujian" name="ids_tipe_ujian" class="form-control">
                        <span class="invalid-feedback"></span>
                    </div>
                </div>
                <div class="row">
                    <div class="col mb-3">
                        <label for="nama_setting" class="form-label">Nama Setting</label>
                        <input type="text" id="nama_setting" name="nama_setting" class="form-control">
                        <span class="invalid-feedback"></span>
                    </div>
                </div>
                <div class="row">
                    <div class="col mb-3">
                        <label for="setting" class="form-label">Setting</label>
                        <input type="text" id="setting" name="setting" class="form-control">
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