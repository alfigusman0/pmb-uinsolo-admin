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

			// Load data for the table's content from an Ajax source
			"ajax": {
				"url": "<?php echo base_url(); ?>mandiri/sanggah2/jsondatatable/",
				"type": "POST",
				"data": function(data) {
					data.nama = $('#nama_filter').val();
					data.nomor_peserta = $('#nomor_peserta_filter').val();
					data.kelurahan = $('#kelurahan_filter').val();
					data.nama_sekolah = $('#nama_sekolah_filter').val();
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

	function modal_jawab(id)
	{
		save_method = 'update';
    $('#form')[0].reset(); // reset form on modals
    $('.form-control').removeClass('is-invalid'); // clear error class
    $('.invalid-feedback').empty(); // clear error string

		//Ajax Load data from ajax
    $.ajax({
			url : "<?php echo base_url(); ?>mandiri/sanggah/get/" + id,
			type: "GET",
			dataType: "JSON",
			success: function(response)
			{
				$.ajax({
					url : "<?php echo base_url(); ?>mandiri/kelulusan/getKelulusan/" + response.data.idp_formulir,
					type: "GET",
					dataType: "JSON",
					success: function(response2)
					{
						$('#nilaiKelulusan').html(response2.data.total); 
					},
					error: function (jqXHR, textStatus, errorThrown)
					{
						notif_error("Error get data from ajax");
					}
				});
				$.ajax({
					url : "<?php echo base_url(); ?>mandiri/kelulusan/getGrade/" + response.data.idp_formulir,
					type: "GET",
					dataType: "JSON",
					success: function(response2)
					{
						var trHTML = '';
						for(var f=0;f<response2.data.length;f++) {
							trHTML += '<tr>';
							trHTML += '<td>' + response2.data[f]['pilihan'] + '</td>'
							trHTML += '<td>' + response2.data[f]['jurusan'] + '</td>'
							trHTML += '<td>' + response2.data[f]['grade'] + '</td>'
							trHTML += '</tr>';
						}
						$('#dataGrade').html(trHTML); 
					},
					error: function (jqXHR, textStatus, errorThrown)
					{
						notif_error("Error get data from ajax");
					}
				});
				$('[name="idp_sanggah"]').val(response.data.idp_sanggah);
				$('[name="ids_sanggah"]').val(response.data.ids_sanggah);
				$('[name="sanggah"]').val(response.data.sanggah);
				$('#modal_form').modal('show'); // show bootstrap modal when complete loaded
				$('.modal-title').text('Edit Program'); // Set title to Bootstrap modal title
			},
			error: function (jqXHR, textStatus, errorThrown)
			{
				notif_error("Error get data from ajax");
			}
    });
	}

	function save()
	{
		$('#btnSave').text('Simpan...'); //change button text
		$('#btnSave').attr('disabled',true); //set button disable 
		var url;
		if(save_method == 'add') {
			url = "<?php echo base_url('mandiri/sanggah/add')?>";
		} else {
			url = "<?php echo base_url('mandiri/sanggah/update')?>";
		}

		// ajax adding data to database
		$.ajax({
			url : url,
			type: "POST",
			data: $('#form').serialize(),
			dataType: "JSON",
			success: function(response)
			{
				if(response.status == 200) //if success close modal and reload ajax table
				{
						$('#modal_form').modal('hide');
						reload_table();
						notif_success(response.message);
				}
				else
				{
						for (var i = 0; i < response.inputerror.length; i++) 
						{
							$('[name="'+response.inputerror[i]+'"]').addClass('is-invalid'); //select parent twice to select div form-group class and add is-invalid class
							$('[name="'+response.inputerror[i]+'"]').next().text(response.error_string[i]); //select span invalid-feedback class set text error string
						}
				}
				$('#btnSave').text('Simpan'); //change button text
				$('#btnSave').attr('disabled',false); //set button enable 
			},
			error: function (jqXHR, textStatus, errorThrown)
			{
				notif_error("Error tambah / edit data");
				$('#btnSave').text('Simpan'); //change button text
				$('#btnSave').attr('disabled',false); //set button enable 
			}
		});
	}

	function generate()
	{
		$('#btnGenerate').text('Generate...'); //change button text
		$('#btnGenerate').attr('disabled',true); //set button disable 

		// ajax adding data to database
		$.ajax({
			url : "<?php echo base_url('mandiri/sanggah/generate')?>",
			type: "POST",
			data: $('#form_generate').serialize(),
			dataType: "JSON",
			success: function(response)
			{
				if(response.status == 200) //if success close modal and reload ajax table
				{
						$('#generateModal').modal('hide');
						reload_table();
						notif_success(response.message);
						$('#form_generate')[0].reset();
				}
				else
				{
						for (var i = 0; i < response.inputerror.length; i++) 
						{
							$('[name="'+response.inputerror[i]+'"]').addClass('is-invalid'); //select parent twice to select div form-group class and add is-invalid class
							$('[name="'+response.inputerror[i]+'"]').next().text(response.error_string[i]); //select span invalid-feedback class set text error string
						}
				}
				$('#btnGenerate').text('Simpan'); //change button text
				$('#btnGenerate').attr('disabled',false); //set button enable 
			},
			error: function (jqXHR, textStatus, errorThrown)
			{
				notif_error("Error generate data");
				$('#btnGenerate').text('Simpan'); //change button text
				$('#btnGenerate').attr('disabled',false); //set button enable 
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