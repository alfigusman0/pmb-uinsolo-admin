<!-- DataTables -->
<script src="<?= base_url() ?>assets/libs/datatables/jquery.dataTables.js"></script>
<script src="<?= base_url() ?>assets/libs/datatables-bs5/datatables-bootstrap5.js"></script>
<script src="<?= base_url() ?>assets/libs/toastr/toastr.js"></script>
<script src="<?= base_url() ?>assets/libs/sweetalert2/sweetalert2.js"></script>
<script type="text/javascript">
	var save_method; //for save method string
	var table;

	$(document).ready(function() {
		load_data();
		//datatables
		table = $('#dataTabel').DataTable({
			"processing": true, //Feature control the processing indicator.
			"serverSide": true, //Feature control DataTables' server-side processing mode.
			"order": [], //Initial no order.

			// Load data for the table's content from an Ajax source
			"ajax": {
				"url": "<?php echo base_url(); ?>daftar/mahasiswa/jsondatatable/",
				"type": "POST",
				"data": function(data) {
					data.nama = $('#nama_filter').val();
					data.nomor_peserta = $('#nomor_peserta_filter').val();
					data.nim = $('#nim_filter').val();
					data.ids_jalur_masuk = $('#ids_jalur_masuk_filter').val();
					data.ids_fakultas = $('#ids_fakultas_filter').val();
					data.kode_jurusan = $('#kode_jurusan_filter').val();
					data.tahun = $('#tahun_filter').val();
					data.daftar = $('#daftar_filter').val();
					data.submit = $('#submit_filter').val();
					data.pembayaran = $('#pembayaran_filter').val();
					data.pemberkasan = $('#pemberkasan_filter').val();
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
			$(this).parent().parent().removeClass('was-validated');
			$(this).next().empty();
		});
		$("textarea").change(function() {
			$(this).parent().parent().removeClass('was-validated');
			$(this).next().empty();
		});
		$("select").change(function() {
			$(this).parent().parent().removeClass('was-validated');
			$(this).next().empty();
		});

		$('#showPassword').on('click', function() {
			var passInput = $('[name="password"]');
			$(this).toggleClass("bx-hide bx-show");
			if (passInput.attr('type') === 'password') {
				passInput.attr('type', 'text');
			} else {
				passInput.attr('type', 'password');
			}
		})
	});

	function reload_table() {
		table.ajax.reload(); //reload datatable ajax
	}

	function load_data() {
		$.ajax({
			url: "<?= $_ENV['MASTER_HOST'] ?>jalur-masuk/?status=YA&limit=1000",
			type: "GET",
			headers: {
				"Authorization": "Bearer <?= $this->input->cookie($_ENV['COOKIE_NAME'], TRUE) ?>"
			},
			dataType: "JSON",
			success: function(response) {
				if (response.code == 200) {
					var sel = $("#ids_jalur_masuk_filter");
					sel.empty();
					sel.append('<option value="">&laquo; Semua &raquo;</option>');
					for (var i = 0; i < response.data.data.length; i++) {
						sel.append('<option value="' + response.data.data[i].ids_jalur_masuk + '">' + response.data.data[i].alias + '</option>');
					}
				} else {
					error++;
					notif_error(response.message)
				}
			},
			error: function(jqXHR, textStatus, errorThrown) {
				notif_error("Tidak dapat mengambil data jalur masuk dari server.")
			}
		});
		$.ajax({
			url: "<?= $_ENV['MASTER_HOST'] ?>fakultas/?status=YA&limit=1000",
			type: "GET",
			headers: {
				"Authorization": "Bearer <?= $this->input->cookie($_ENV['COOKIE_NAME'], TRUE) ?>"
			},
			dataType: "JSON",
			success: function(response) {
				if (response.code == 200) {
					var sel = $("#ids_fakultas_filter");
					sel.empty();
					sel.append('<option value="">&laquo; Semua &raquo;</option>');
					for (var i = 0; i < response.data.data.length; i++) {
						sel.append('<option value="' + response.data.data[i].ids_fakultas + '">' + response.data.data[i].fakultas + '</option>');
					}
					$("#kode_jurusan_filter").attr('disabled', true);
				} else {
					error++;
					notif_error(response.message)
				}
			},
			error: function(jqXHR, textStatus, errorThrown) {
				notif_error("Tidak dapat mengambil data fakultas dari server.")
			}
		});
		$('#ids_fakultas_filter').on('click', function() {
			$.ajax({
				url: "<?= $_ENV['MASTER_HOST'] ?>jurusan/?status=YA&limit=1000&ids_fakultas=" + $(this).val(),
				type: "GET",
				headers: {
					"Authorization": "Bearer <?= $this->input->cookie($_ENV['COOKIE_NAME'], TRUE) ?>"
				},
				dataType: "JSON",
				success: function(response) {
					if (response.code == 200) {
						var sel = $("#kode_jurusan_filter");
						sel.empty();
						sel.append('<option value="">&laquo; Semua &raquo;</option>');
						for (var i = 0; i < response.data.data.length; i++) {
							sel.append('<option value="' + response.data.data[i].kode_jurusan + '">' + response.data.data[i].jurusan + '</option>');
						}
						sel.attr('disabled', false);
					} else {
						error++;
						notif_error(response.message)
					}
				},
				error: function(jqXHR, textStatus, errorThrown) {
					notif_error("Tidak dapat mengambil data jurusan dari server.")
				}
			});
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