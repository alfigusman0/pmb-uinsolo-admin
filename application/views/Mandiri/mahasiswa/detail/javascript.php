<script src="<?= base_url() ?>assets/libs/datatables/jquery.dataTables.js"></script>
<script src="<?= base_url() ?>assets/libs/datatables-bs5/datatables-bootstrap5.js"></script>
<script src="<?= base_url() ?>assets/libs/toastr/toastr.js"></script>
<script src="<?= base_url() ?>assets/libs/sweetalert2/sweetalert2.js"></script>
<script>
	$(document).ready(function() {
		var table = $('#dataTabel').DataTable();
		var table2 = $('#dataTabel2').DataTable();
	});

	function edit_data_formulir(id) {
		save_method = 'update';
		$('.form-control').removeClass('is-invalid'); // clear error class
		$('.invalid-feedback').empty(); // clear error string

		$.ajax({
			url: "<?= base_url('mandiri/mahasiswa/formulir/get/') ?>" + id,
			type: "GET",
			dataType: "JSON",
			success: function(response) {
				Promise.all([
					//program - tipe ujian
					$.ajax({
						url : "<?=base_url('setting/program/get')?>",
						type: "GET",
						dataType: "JSON",
						success: function(response2)
						{
							if(response2.status == 200){
								var sel = $("#ids_program");
								sel.empty();
								sel.append('<option label="-- Pilih --"></option>');
								for (var i=0; i<response2.data.length; i++) {
									sel.append('<option value="' + response2.data[i].ids_program + '">' + response2.data[i].program + '</option>');
								}
								$('select[name="ids_tipe_ujian"]').attr('disabled', true);
							}else{
								notif_error(response2.message)
							}
						},
						error: function (jqXHR, textStatus, errorThrown)
						{
							notif_error("Tidak dapat mengambil data program dari server.")
						}
					}),
					$('#ids_program').on('change', function() {
						//tipe-ujian
						$.ajax({
							url : "<?=base_url('setting/tipe-ujian/get')?>",
							type: "GET",
							data: {
								ids_program: $(this).val()
							},
							dataType: "JSON",
							async: false,
							success: function(response2)
							{
								if(response2.status == 200){
									var sel = $("#ids_tipe_ujian");
									sel.empty();
									sel.append('<option label="-- Pilih --"></option>');
									for (var i=0; i<response2.data.length; i++) {
										sel.append('<option value="' + response2.data[i].ids_tipe_ujian + '">' + response2.data[i].tipe_ujian + '</option>');
									}
									if($('#ids_program').val()==''){
										$('select[name="ids_tipe_ujian"]').attr('disabled', true);
									}else{
										$('select[name="ids_tipe_ujian"]').removeAttr('disabled');
									}
								}else{
									notif_error(response2.message)
								}
							},
							error: function (jqXHR, textStatus, errorThrown)
							{
								notif_error("Tidak dapat mengambil data tipe ujian dari server.")
							}
						});
					}),
				]).then(function(){
					$('[name="idp_formulir"]').val(response.data.idp_formulir);
					$('[name="kategori"]').val(response.data.kategori);
					$('[name="ids_program"]').val(response.data.ids_program);
					$('[name="ket_pembayaran"]').val(response.data.ket_pembayaran);
					//tipe-ujian
					$.ajax({
						url : "<?=base_url('setting/tipe-ujian/get')?>",
						type: "GET",
						data: {
							ids_program: response.data.ids_program
						},
						dataType: "JSON",
						async: false,
						success: function(response2)
						{
							if(response2.status == 200){
								var sel = $("#ids_tipe_ujian");
								sel.empty();
								sel.append('<option label="-- Pilih --"></option>');
								for (var i=0; i<response2.data.length; i++) {
									sel.append('<option value="' + response2.data[i].ids_tipe_ujian + '">' + response2.data[i].tipe_ujian + '</option>');
								}
								if(response.data.ids_program==''){
									$('select[name="ids_tipe_ujian"]').attr('disabled', true);
								}else{
									$('select[name="ids_tipe_ujian"]').removeAttr('disabled');
								}
								$('[name="ids_tipe_ujian"]').val(response.data.ids_tipe_ujian);
							}else{
								notif_error(response2.message)
							}
						},
						error: function (jqXHR, textStatus, errorThrown)
						{
							notif_error("Tidak dapat mengambil data jurusan sekolah dari server.")
						}
					});
					if (response.data.formulir == 'SUDAH') {
						$('[name="formulir"][value="SUDAH"]').prop("checked", true);
					} else {
						$('[name="formulir"][value="BELUM"]').prop("checked", true);
					}
					if (response.data.pembayaran == 'SUDAH') {
						$('[name="pembayaran"][value="SUDAH"]').prop("checked", true);
					} else {
						$('[name="pembayaran"][value="BELUM"]').prop("checked", true);
					}
					$('#modal_form_formulir').modal('show'); // show bootstrap modal when complete loaded
					$('.modal-title').text('Edit Formulir'); // Set title to Bootstrap modal title
				});
			},
			error: function(jqXHR, textStatus, errorThrown) {
				notif_error("Error get data from ajax");
			}
		});
	}

	function save() {
		$('#btnSave').text('Simpan...'); //change button text
		$('#btnSave').attr('disabled', true); //set button disable
		var url;
		url = "<?= base_url('mandiri/mahasiswa/data-formulir/save') ?>";

		// ajax adding data to database
		$.ajax({
			url: url,
			type: 'POST',
			data: $('#formFormulir').serialize(),
			dataType: "JSON",
			success: function(response) {
				if (response.status == 200) //if success close modal and reload ajax table
				{
					$('#modal_form_formulir').modal('hide');
					window.location.reload();
					notif_success(response.message);
				} else {
					notif_error(response.message);
					console.log(response.data);
				}
				$('#btnSave').text('Simpan'); //change button text
				$('#btnSave').attr('disabled', false); //set button enable
			},
			error: function(jqXHR, textStatus, errorThrown) {
				var message = jqXHR.responseJSON.message;
				notif_error(message)
				$('#btnSave').text('Simpan'); //change button text
				$('#btnSave').attr('disabled', false); //set button enable
			}
		});
	}

	function notif_success(msg) {
		toastr.options.closeButton = true;
		toastr.options.progressBar = true;
		toastr.success(msg);
	}

	function notif_error(msg) {
		toastr.options.closeButton = true;
		toastr.options.progressBar = true;
		toastr.error(msg);
	}
</script>