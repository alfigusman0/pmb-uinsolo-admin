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
		table = $('#dataTabel').DataTable({
			"processing": true, //Feature control the processing indicator.
			"serverSide": true, //Feature control DataTables' server-side processing mode.
			"order": [], //Initial no order.
			"pageLength": 50,

			// Load data for the table's content from an Ajax source
			"ajax": {
				"url": "<?php echo base_url(); ?>setting/bobot-nilai/jsondatatable",
				"type": "POST",
				"data": function(data) {
					data.nama_field = $('#nama_field_filter').val();
				},
				"dataSrc": function(json) {
					// Tampilkan total bobot
					$('#total-bobot').text(json.total_bobot+"%");
					return json.data;
				}
			},

			//Set column definition initialisation properties.
			"columnDefs": [{
				"targets": [1], //first column / numbering column
				"orderable": false, //set not orderable
			}],
		});
		$('#btn-filter').click(function() { //button filter event click
			table.ajax.reload(); //just reload table
		});
		$('#btn-reset').click(function() { //button reset event click
			$('#form-filter')[0].reset();
			table.ajax.reload(); //just reload table
		});

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
		table.ajax.reload(null, false); //reload datatable ajax
	}

	function add_data() {
		save_method = 'add';
		$('#form')[0].reset(); // reset form on modals
		$('.form-control').removeClass('is-invalid'); // clear error class
		$('.invalid-feedback').empty(); // clear error string
		$('#modal_form').modal('show'); // show bootstrap modal
		$('.modal-title').text('Tambah Bobot Nilai UKT'); // Set Title to Bootstrap modal title
	}

	function edit_data(id) {
		save_method = 'update';
		$('#form')[0].reset(); // reset form on modals
		$('.form-control').removeClass('is-invalid'); // clear error class
		$('.invalid-feedback').empty(); // clear error string

		//Ajax Load data from ajax
		$.ajax({
			url: "<?php echo base_url(); ?>setting/bobot-nilai/get/" + id,
			type: "GET",
			dataType: "JSON",
			success: function(response) {
				$('[name="ids_bobot_nilai_ukt"]').val(response.data.ids_bobot_nilai_ukt);
				$('[name="nama_field"]').val(response.data.nama_field);
				$('[name="alias"]').val(response.data.alias);
				$('[name="bobot"]').val(response.data.bobot);
				$('[name="nilai_max"]').val(response.data.nilai_max);
				$('#modal_form').modal('show'); // show bootstrap modal when complete loaded
				$('.modal-title').text('Edit Bobot Nilai UKT'); // Set title to Bootstrap modal title
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
			url = "<?php echo base_url('setting/bobot-nilai/add') ?>";
		} else {
			url = "<?php echo base_url('setting/bobot-nilai/update') ?>";
		}

		// ajax adding data to database
		$.ajax({
			url: url,
			type: "POST",
			data: $('#form').serialize(),
			dataType: "JSON",
			success: function(response) {
				if (response.status == 200) //if success close modal and reload ajax table
				{
					$('#modal_form').modal('hide');
					reload_table();
					notif_success(response.message);
				} else {
					for (var i = 0; i < response.inputerror.length; i++) {
						$('[name="' + response.inputerror[i] + '"]').addClass('is-invalid'); //select parent twice to select div form-group class and add is-invalid class
						$('[name="' + response.inputerror[i] + '"]').next().text(response.error_string[i]); //select span invalid-feedback class set text error string
					}
				}
				$('#btnSave').text('Simpan'); //change button text
				$('#btnSave').attr('disabled', false); //set button enable
			},
			error: function(jqXHR, textStatus, errorThrown) {
				notif_error("Error tambah / edit data");
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
					url: "<?php echo base_url('setting/bobot-nilai/delete/') ?>" + id,
					type: "POST",
					dataType: "JSON",
					success: function(response) {
						//if success reload ajax table
						$('#modal_form').modal('hide');
						reload_table();
						notif_success(response.message);
					},
					error: function(jqXHR, textStatus, errorThrown) {
						notif_error("Error hapus data");
					}
				});
			}
		})
	}

	function generate() {
		$('#formGenerate')[0].reset(); // reset form on modals
		$('.form-control').removeClass('is-invalid'); // clear error class
		$('.invalid-feedback').empty(); // clear error string
		$('#modal_form_generate').modal('show'); // show bootstrap modal
		$('.modal-title').text('Generate Nilai UKT'); // Set Title to Bootstrap modal title
	}

	function act_generate() {
		$('#btnGenerate').text('Tunggu sebentar...'); //change button text
		$('#btnGenerate').attr('disabled', true); //set button disable

		// ajax adding data to database
		$.ajax({
			url: "<?php echo base_url('setting/bobot-nilai/generate') ?>",
			type: "POST",
			data: $('#formGenerate').serialize(),
			dataType: "JSON",
			success: function(response) {
				if (response.status == 200) //if success close modal and reload ajax table
				{
					$('#modal_form_generate').modal('hide');
					reload_table();
					notif_success(response.message);
				} else {
					for (var i = 0; i < response.inputerror.length; i++) {
						$('[name="' + response.inputerror[i] + '"]').addClass('is-invalid'); //select parent twice to select div form-group class and add is-invalid class
						$('[name="' + response.inputerror[i] + '"]').next().text(response.error_string[i]); //select span invalid-feedback class set text error string
					}
				}
				$('#btnGenerate').text('Generate'); //change button text
				$('#btnGenerate').attr('disabled', false); //set button enable
			},
			error: function(jqXHR, textStatus, errorThrown) {
				notif_error("Error generate data");
				$('#btnGenerate').text('Generate'); //change button text
				$('#btnGenerate').attr('disabled', false); //set button enable
			}
		});
	}

	function simpan_setting() {
		$('#modal_form_simpan').modal('show'); // show bootstrap modal
		$('.modal-title').text('Simpan Setting'); // Set Title to Bootstrap modal title
	}

	function act_simpan_setting() {
		$('#btnSimpanSetting').text('Tunggu sebentar...'); //change button text
		$('#btnSimpanSetting').attr('disabled', true); //set button disable

		// ajax adding data to database
		$.ajax({
			url: "<?php echo base_url('setting/bobot-nilai/simpan') ?>",
			type: "POST",
			dataType: "JSON",
			success: function(response) {
				if (response.status == 200) //if success close modal and reload ajax table
				{
					$('#modal_form_simpan').modal('hide');
					reload_table();
					notif_success(response.message);
				} else {
					notif_error(response.message);
				}
				$('#btnSimpanSetting').text('Simpan'); //change button text
				$('#btnSimpanSetting').attr('disabled', false); //set button enable
			},
			error: function(jqXHR, textStatus, errorThrown) {
				notif_error("Error simpan setting data");
				$('#btnSimpanSetting').text('Simpan'); //change button text
				$('#btnSimpanSetting').attr('disabled', false); //set button enable
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