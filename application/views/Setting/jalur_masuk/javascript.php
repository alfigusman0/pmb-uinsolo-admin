<!-- DataTables -->
<script src="<?= base_url() ?>assets/libs/datatables/jquery.dataTables.js"></script>
<script src="<?= base_url() ?>assets/libs/datatables-bs5/datatables-bootstrap5.js"></script>
<script src="<?= base_url() ?>assets/libs/toastr/toastr.js"></script>
<script src="<?= base_url() ?>assets/libs/sweetalert2/sweetalert2.js"></script>
<script type="text/javascript">
	var save_method; //for save method string
	var table;

	$(document).ready(function() {
		//datatables
		table = $('#dataTabel').DataTable();

		//set input/textarea/select event when change value, remove class error and remove text help block 
		$("input").change(function() {
			$(this).removeClass('is-invalid');
			$(this).next().empty();
		});
		$("textarea").change(function() {
			$(this).removeClass('is-invalid');
			$(this).next().empty();
		});
		$("select").change(function() {
			$(this).removeClass('is-invalid');
			$(this).next().empty();
		});
	});

	function reload_table() {
		window.location.reload(); //reload datatable ajax 
	}

	function add_data() {
		save_method = 'add';
		$('.collapse').collapse('hide');
		$('#form')[0].reset(); // reset form on modals
		$('.form-control').removeClass('is-invalid'); // clear error class
		$('.invalid-feedback').empty(); // clear error string
		$('#modal_form').modal('show'); // show bootstrap modal
		$('.modal-title').text('Tambah Jalur Masuk'); // Set Title to Bootstrap modal title
		$('[name="status"][value="YA"]').prop("checked", true);
	}

	function edit_data(id) {
		save_method = 'update';
		$('.collapse').collapse('hide');
		$('.form-control').removeClass('is-invalid'); // clear error class
		$('.invalid-feedback').empty(); // clear error string

		$.ajax({
			url: "<?= $_ENV['MASTER_HOST'] ?>" + "jalur-masuk/single/",
			data: {
				ids_jalur_masuk: id
			},
			type: "GET",
			headers: {
				"Authorization": "Bearer <?= $this->input->cookie($_ENV['COOKIE_NAME'], TRUE) ?>"
			},
			dataType: "JSON",
			success: function(response) {
				$('[name="id_salam"]').val(response.data.id_salam);
				$('[name="ids_jalur_masuk"]').val(response.data.ids_jalur_masuk);
				$('[name="jalur_masuk"]').val(response.data.jalur_masuk);
				$('[name="alias"]').val(response.data.alias);
				$('[name="nourut"]').val(response.data.nourut);
				if (response.data.status == 'YA') {
					$('[name="status"][value="YA"]').prop("checked", true);
				} else {
					$('[name="status"][value="TIDAK"]').prop("checked", true);
				}
				$.ajax({
					url: "<?= base_url('setting/jalur-masuk/get/') ?>" + id,
					type: "GET",
					dataType: "JSON",
					success: function(response2) {
						console.log(response2)
						if(response2.status == 200){
							$('[name="pendaftaran_awal"]').val(response2.data.pendaftaran_awal);
							$('[name="pendaftaran_akhir"]').val(response2.data.pendaftaran_akhir);
							$('[name="ukt_awal"]').val(response2.data.ukt_awal);
							$('[name="ukt_akhir"]').val(response2.data.ukt_akhir);
							$('[name="pembayaran_awal"]').val(response2.data.pembayaran_awal);
							$('[name="pembayaran_akhir"]').val(response2.data.pembayaran_akhir);
							$('[name="pemberkasan_awal"]').val(response2.data.pemberkasan_awal);
							$('[name="pemberkasan_akhir"]').val(response2.data.pemberkasan_akhir);
						}
						$('#modal_form').modal('show'); // show bootstrap modal when complete loaded
						$('.modal-title').text('Edit Jalur Masuk'); // Set title to Bootstrap modal title
					},
					error: function(jqXHR, textStatus, errorThrown) {
						notif_error("Error get data from ajax");
					}
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
		if (save_method == 'add') {
			type = "POST";
			url = "<?= $_ENV['MASTER_HOST'] ?>" + "jalur-masuk/";
			url2 = "<?= base_url('setting/jalur-masuk/add/') ?>";
		} else {
			type = "PUT";
			url = "<?= $_ENV['MASTER_HOST'] ?>" + "jalur-masuk/" + $('#ids_jalur_masuk').val();
			url2 = "<?= base_url('setting/jalur-masuk/update/') ?>";
		}

		if($('#pendaftaran_awal').val() == ''){
			notif_error('Waktu pendaftaran awal tidak boleh kosong.')
			$('#btnSave').text('Simpan'); //change button text
			$('#btnSave').attr('disabled', false); //set button enable
			return;
		}
		if($('#pendaftaran_akhir').val() == ''){
			notif_error('Waktu pendaftaran akhir tidak boleh kosong.')
			$('#btnSave').text('Simpan'); //change button text
			$('#btnSave').attr('disabled', false); //set button enable
			return;
		}
		if($('#ukt_awal').val() == ''){
			notif_error('Waktu ukt awal tidak boleh kosong.')
			$('#btnSave').text('Simpan'); //change button text
			$('#btnSave').attr('disabled', false); //set button enable
			return;
		}
		if($('#ukt_akhir').val() == ''){
			notif_error('Waktu ukt akhir tidak boleh kosong.')
			$('#btnSave').text('Simpan'); //change button text
			$('#btnSave').attr('disabled', false); //set button enable
			return;
		}
		if($('#pembayaran_awal').val() == ''){
			notif_error('Waktu pembayaran awal tidak boleh kosong.')
			$('#btnSave').text('Simpan'); //change button text
			$('#btnSave').attr('disabled', false); //set button enable
			return;
		}
		if($('#pembayaran_akhir').val() == ''){
			notif_error('Waktu pembayaran akhir tidak boleh kosong.')
			$('#btnSave').text('Simpan'); //change button text
			$('#btnSave').attr('disabled', false); //set button enable
			return;
		}
		if($('#pemberkasan_awal').val() == ''){
			notif_error('Waktu pemberkasan awal tidak boleh kosong.')
			$('#btnSave').text('Simpan'); //change button text
			$('#btnSave').attr('disabled', false); //set button enable
			return;
		}
		if($('#pemberkasan_akhir').val() == ''){
			notif_error('Waktu pemberkasan akhir tidak boleh kosong.')
			$('#btnSave').text('Simpan'); //change button text
			$('#btnSave').attr('disabled', false); //set button enable
			return;
		}

		// ajax adding data to database
		$.ajax({
			url: url,
			type: type,
			data: $('#form').serialize(),
			headers: {
				"Authorization": "Bearer <?= $this->input->cookie($_ENV['COOKIE_NAME'], TRUE) ?>"
			},
			dataType: "JSON",
			success: function(response) {
				if (response.code == 200) //if success close modal and reload ajax table
				{
					// console.log(response)
					if(save_method == 'add'){
						$('#ids_jalur_masuk').val(response.data.ids_jalur_masuk)
					}
					$.ajax({
						url: url2,
						type: 'POST',
						data: {
							ids_jalur_masuk: $('#ids_jalur_masuk').val(),
							pendaftaran_awal: $('#pendaftaran_awal').val(),
							pendaftaran_akhir: $('#pendaftaran_akhir').val(),
							ukt_awal: $('#ukt_awal').val(),
							ukt_akhir: $('#ukt_akhir').val(),
							pembayaran_awal: $('#pembayaran_awal').val(),
							pembayaran_akhir: $('#pembayaran_akhir').val(),
							pemberkasan_awal: $('#pemberkasan_awal').val(),
							pemberkasan_akhir: $('#pemberkasan_akhir').val(),
						},
						dataType: "JSON",
						success: function(response2) {
							if (response2.status == 200) //if success close modal and reload ajax table
							{
								$('#modal_form').modal('hide');
								reload_table();
								notif_success(response2.message);
								$('#btnSave').text('Simpan'); //change button 	
								$('#btnSave').attr('disabled', false); //set button enable
							} else {
								notif_error(response2.message);
								$('#btnSave').text('Simpan'); //change button text
								$('#btnSave').attr('disabled', false); //set button enable
							}
						},
						error: function(jqXHR, textStatus, errorThrown) {
							var message = jqXHR.responseJSON.message;
							if (isIterable(message)) {
								notif_error(message)
							} else {
								for (propKey in message) {
									$('[name="' + propKey + '"]').addClass('is-invalid'); //select parent twice to select div form-group class and add is-invalid class
									$('[name="' + propKey + '"]').next().text(message[propKey]); //select span invalid-feedback class set text error string
								}
							}
							$('#btnSave').text('Simpan'); //change button text
							$('#btnSave').attr('disabled', false); //set button enable
						}
					});
				} else {
					notif_error(response.message);
				}
				$('#btnSave').text('Simpan'); //change button text
				$('#btnSave').attr('disabled', false); //set button enable
			},
			error: function(jqXHR, textStatus, errorThrown) {
				var message = jqXHR.responseJSON.message;
				if (isIterable(message)) {
					notif_error(message)
				} else {
					for (propKey in message) {
						$('[name="' + propKey + '"]').addClass('is-invalid'); //select parent twice to select div form-group class and add is-invalid class
						$('[name="' + propKey + '"]').next().text(message[propKey]); //select span invalid-feedback class set text error string
					}
				}
				$('#btnSave').text('Simpan'); //change button text
				$('#btnSave').attr('disabled', false); //set button enable
			}
		});
	}

	function delete_data(id) {
		Swal.fire({
			title: 'Apakah anda yakin?',
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Iya, hapus!',
			cancelButtonText: 'Tutup'
		}).then((result) => {
			if (result.isConfirmed) {
				$.ajax({
					url: "<?= base_url('setting/jalur-masuk/delete/') ?>" + id,
					type: "POST",
					dataType: "JSON",
					success: function(response) {
						//if success reload ajax table
						if(response.status == 200){
							$.ajax({
								url: "<?= $_ENV['MASTER_HOST'] ?>" + "jalur-masuk/" + id,
								type: "DELETE",
								headers: {
									"Authorization": "Bearer <?= $this->input->cookie($_ENV['COOKIE_NAME'], TRUE) ?>"
								},
								dataType: "JSON",
								success: function(response2) {
									//if success reload ajax table
									$('#modal_form').modal('hide');
									reload_table();
									notif_success(response2.message);
								},
								error: function(jqXHR, textStatus, errorThrown) {
									notif_error("Error hapus data");
								}
							});
						}else{
							notif_success(response.message);
						}
					},
					error: function(jqXHR, textStatus, errorThrown) {
						notif_error("Error hapus data");
					}
				});
			}
		})
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

	function isIterable(obj) {
		// checks for null and undefined
		if (obj == null) {
			return false;
		}
		return typeof obj[Symbol.iterator] === 'function';
	}
</script>