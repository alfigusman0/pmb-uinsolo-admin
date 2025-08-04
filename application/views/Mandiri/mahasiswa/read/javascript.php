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
				"url": "<?php echo base_url(); ?>mandiri/mahasiswa/jsondatatable/",
				"type": "POST",
				"data": function(data) {
					data.idp_formulir = $('#idp_formulir_filter').val();
					data.nama = $('#nama_filter').val();
					data.nomor_peserta = $('#nomor_peserta_filter').val();
					data.ids_program = $('#ids_program_filter').val();
					data.ids_tipe_ujian = $('#ids_tipe_ujian_filter').val();
					data.pembayaran = $('#pembayaran_filter').val();
					data.formulir = $('#formulir_filter').val();
					data.tahun = $('#tahun_filter').val();
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
		//program - tipe ujian
		$.ajax({
			url : "<?=base_url('setting/program/get')?>",
			type: "GET",
			dataType: "JSON",
			success: function(response2)
			{
				if(response2.status == 200){
					var sel = $("#ids_program_filter");
					sel.empty();
					sel.append('<option value="">&laquo; Semua &raquo;</option>');
					for (var i=0; i<response2.data.length; i++) {
						sel.append('<option value="' + response2.data[i].ids_program + '">' + response2.data[i].program + '</option>');
					}
					$('#ids_tipe_ujian_filter').attr('disabled', true);
				}else{
					notif_error(response2.message)
				}
			},
			error: function (jqXHR, textStatus, errorThrown)
			{
				notif_error("Tidak dapat mengambil data program dari server.")
			}
		});
		$('#ids_program_filter').on('change', function() {
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
						var sel = $("#ids_tipe_ujian_filter");
						sel.empty();
						sel.append('<option value="">&laquo; Semua &raquo;</option>');
						for (var i=0; i<response2.data.length; i++) {
							sel.append('<option value="' + response2.data[i].ids_tipe_ujian + '">' + response2.data[i].tipe_ujian + '</option>');
						}
						if($('#ids_program_filter').val()==''){
							$('#ids_tipe_ujian_filter').attr('disabled', true);
						}else{
							$('#ids_tipe_ujian_filter').removeAttr('disabled');
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