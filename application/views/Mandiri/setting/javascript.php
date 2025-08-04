<!-- DataTables -->
<script src="<?=base_url()?>assets/libs/datatables/jquery.dataTables.js"></script>
<script src="<?=base_url()?>assets/libs/datatables-bs5/datatables-bootstrap5.js"></script>
<script src="<?=base_url()?>assets/libs/toastr/toastr.js"></script>
<script src="<?=base_url()?>assets/libs/sweetalert2/sweetalert2.js"></script>
<script type="text/javascript">
	var save_method; //for save method string
	var table;
	
	$(document).ready(function() {
		get_program();
		$.ajax({
			url : "<?=base_url('setting/tipe-ujian/get')?>",
			data: {
				ids_program: $('#ids_program_filter').val(),
				status: 'YA'
			},
			type: "GET",
			dataType: "JSON",
			success: function(response2)
			{
				console.log(response2.data)
				if(response2.status == 200){
					var sel = $("#ids_tipe_ujian_filter");
					sel.empty();
					for (var i=0; i<response2.data.length; i++) {
						sel.append('<option value="' + response2.data[i].ids_tipe_ujian + '">' + response2.data[i].tipe_ujian + '</option>');
					}
					$('#ids_tipe_ujian_filter').removeAttr('disabled');
				}else{
					var sel = $("#ids_tipe_ujian_filter");
					sel.empty();
					$('#ids_tipe_ujian_filter').attr('disabled', true);
					notif_error('Tipe ujian program ' + $('#ids_program_filter').text() + ' kosong.')
				}
				reload_table()
			},
			error: function (jqXHR, textStatus, errorThrown)
			{
				notif_error("Tidak dapat mengambil data program dari server.")
			}
		});
		$('#ids_program_filter').on('change', function(){
			$.ajax({
				url : "<?=base_url('setting/tipe-ujian/get')?>",
				data: {
					ids_program: $(this).val(),
					status: 'YA'
				},
				type: "GET",
				dataType: "JSON",
				success: function(response2)
				{
					if(response2.status == 200){
						var sel = $("#ids_tipe_ujian_filter");
						sel.empty();
						for (var i=0; i<response2.data.length; i++) {
							sel.append('<option value="' + response2.data[i].ids_tipe_ujian + '">' + response2.data[i].tipe_ujian + '</option>');
						}
						$('#ids_tipe_ujian_filter').removeAttr('disabled');
					}else{
						var sel = $("#ids_tipe_ujian_filter");
						sel.empty();
						$('#ids_tipe_ujian_filter').attr('disabled', true);
						notif_error('Tipe ujian program ' + $('#ids_program_filter').text() + ' kosong.')
					}
					reload_table()
				},
				error: function (jqXHR, textStatus, errorThrown)
				{
					notif_error("Tidak dapat mengambil data program dari server.")
				}
			});
		});
		$('#ids_tipe_ujian_filter').on('change', function(){
			reload_table();
		})
		//datatables
		table = $('#dataTabel').DataTable({
			"processing": true, //Feature control the processing indicator.
			"serverSide": true, //Feature control DataTables' server-side processing mode.
			"order": [], //Initial no order.

			// Load data for the table's content from an Ajax source
			"ajax": {
				"url": "<?php echo base_url(); ?>mandiri/setting/jsondatatable/",
				"type": "POST",
				"data": function(data) {
					data.ids_tipe_ujian = $('#ids_tipe_ujian_filter').val();
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
    $("input").change(function(){
        $(this).parent().parent().removeClass('was-validated');
        $(this).next().empty();
    });
    $("textarea").change(function(){
        $(this).parent().parent().removeClass('was-validated');
        $(this).next().empty();
    });
    $("select").change(function(){
        $(this).parent().parent().removeClass('was-validated');
        $(this).next().empty();
    });
	});

	function reload_table()
	{
			table.ajax.reload(null,false); //reload datatable ajax 
	}

	function get_program()
	{
		$.ajax({
			url : "<?=base_url('setting/program/get')?>",
			type: "GET",
			dataType: "JSON",
			async: false,
			success: function(response2)
			{
				if(response2.status == 200){
					var sel = $("#ids_program_filter");
					sel.empty();
					for (var i=0; i<response2.data.length; i++) {
						sel.append('<option value="' + response2.data[i].ids_program + '">' + response2.data[i].program + '</option>');
					}
					$('#ids_program_filter').removeAttr('disabled');
				}else{
					var sel = $("#ids_program_filter");
					sel.empty();
					$('#ids_program_filter').attr('disabled', true);
					notif_error('Program jenjang ' + $('#jenjang_filter').val() + ' kosong.')
				}
			},
			error: function (jqXHR, textStatus, errorThrown)
			{
				notif_error("Tidak dapat mengambil data program dari server.")
			}
		});
	}

	function add_data()
	{
			save_method = 'add';
			$('#form')[0].reset(); // reset form on modals
			$('.form-control').removeClass('is-invalid'); // clear error class
			$('.invalid-feedback').empty(); // clear error string
			$('#modal_form').modal('show'); // show bootstrap modal
			$('.modal-title').text('Tambah Setting'); // Set Title to Bootstrap modal title
			$('[name="status"][value="YA"]').prop("checked", true);
			$('#ids_tipe_ujian').val($('#ids_tipe_ujian_filter').val());
			$('#ids_tipe_ujian_text').val($('#ids_tipe_ujian_filter option:selected').text());
			$('#ids_tipe_ujian_text').attr('disabled', true);
			$('#ids_program').val($('#ids_program_filter').val());
			$('#ids_program_text').val($('#ids_program_filter option:selected').text());
			$('#ids_program_text').attr('disabled', true);
	}

	function edit_data(id)
	{
		save_method = 'update';
    $('#form')[0].reset(); // reset form on modals
    $('.form-control').removeClass('is-invalid'); // clear error class
    $('.invalid-feedback').empty(); // clear error string

		//Ajax Load data from ajax
    $.ajax({
			url : "<?php echo base_url(); ?>mandiri/setting/get/" + id,
			type: "GET",
			dataType: "JSON",
			success: function(response)
			{
				$('[name="idp_setting"]').val(response.data.idp_setting);
				$('[name="jenjang"]').val(response.data.jenjang);
				$('[name="ids_program"]').val(response.data.ids_program);
				$('[name="ids_program_text"]').val(response.data.program);
				$('[name="ids_tipe_ujian"]').val(response.data.ids_tipe_ujian);
				$('[name="ids_tipe_ujian_text"]').val(response.data.tipe_ujian);
				$('[name="nama_setting"]').val(response.data.nama_setting);
				$('[name="setting"]').val(response.data.setting);
				$('#ids_program_text').attr('disabled', true);
				$('#ids_tipe_ujian_text').attr('disabled', true);
				$('#modal_form').modal('show'); // show bootstrap modal when complete loaded
				$('.modal-title').text('Edit Setting'); // Set title to Bootstrap modal title
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
			url = "<?php echo base_url('mandiri/setting/add')?>";
		} else {
			url = "<?php echo base_url('mandiri/setting/update')?>";
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

	function delete_data(id)
	{
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
					url : "<?php echo base_url('mandiri/setting/delete/')?>"+id,
					type: "POST",
					dataType: "JSON",
					success: function(response)
					{
						//if success reload ajax table
						$('#modal_form').modal('hide');
						reload_table();
						notif_success(response.message);
					},
					error: function (jqXHR, textStatus, errorThrown)
					{
						notif_error("Error hapus data");
					}
				});
			}
		})
	}

	function notif_success(msg)
	{
		toastr.options.closeButton = true;
		toastr.options.progressBar = true;
		toastr.success(msg);
	}

	function notif_error(msg)
	{
		toastr.options.closeButton = true;
		toastr.options.progressBar = true;
		toastr.error(msg);
	}
</script>