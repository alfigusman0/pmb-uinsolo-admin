<!-- DataTables -->
<script src="<?=base_url()?>assets/libs/datatables/jquery.dataTables.js"></script>
<script src="<?=base_url()?>assets/libs/datatables-bs5/datatables-bootstrap5.js"></script>
<script src="<?=base_url()?>assets/libs/toastr/toastr.js"></script>
<script src="<?=base_url()?>assets/libs/sweetalert2/sweetalert2.js"></script>
<script type="text/javascript">
	var save_method; //for save method string
	var table;
	
	$(document).ready(function() {
		$.ajax({
			url : "<?=base_url('setting/tipe-ujian/get')?>",
			data: {
				status: 'YA'
			},
			type: "GET",
			dataType: "JSON",
			success: function(response2)
			{
				console.log(response2.data)
				if(response2.status == 200){
					var sel = $("#ids_tipe_ujian");
					sel.empty();
					for (var i=0; i<response2.data.length; i++) {
						sel.append('<option value="' + response2.data[i].ids_tipe_ujian + '">' + response2.data[i].tipe_ujian + '</option>');
					}
					$('#ids_tipe_ujian').removeAttr('disabled');
				}else{
					var sel = $("#ids_tipe_ujian");
					sel.empty();
					$('#ids_tipe_ujian').attr('disabled', true);
					notif_error('Tipe ujian kosong.')
				}
				reload_table()
			},
			error: function (jqXHR, textStatus, errorThrown)
			{
				notif_error("Tidak dapat mengambil data tipe ujian dari server.")
			}
		});
		$.ajax({
			url : "<?=base_url('setting/jalur-masuk/get')?>",
			type: "GET",
			dataType: "JSON",
			success: function(response2)
			{
				console.log(response2.data)
				if(response2.status == 200){
					var sel = $("#ids_jalur_masuk");
					sel.empty();
					for (var i=0; i<response2.data.length; i++) {
						sel.append('<option value="' + response2.data[i].ids_jalur_masuk + '">' + response2.data[i].alias_jalur_masuk + '</option>');
					}
					$('#ids_jalur_masuk').removeAttr('disabled');
				}else{
					var sel = $("#ids_jalur_masuk");
					sel.empty();
					$('#ids_jalur_masuk').attr('disabled', true);
					notif_error('Jalur masuk kosong.')
				}
				reload_table()
			},
			error: function (jqXHR, textStatus, errorThrown)
			{
				notif_error("Tidak dapat mengambil data jalur masuk dari server.")
			}
		});
		$('#setting_filter').on('change', function(){
			reload_table();
		})
		//datatables
		table = $('#dataTabel').DataTable({
			"processing": true, //Feature control the processing indicator.
			"serverSide": true, //Feature control DataTables' server-side processing mode.
			"order": [], //Initial no order.

			// Load data for the table's content from an Ajax source
			"ajax": {
				"url": "<?php echo base_url(); ?>setting/tipe-file/jsondatatable",
				"type": "POST",
				"data": function(data) {
					data.setting = $('#setting_filter').val();
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

	function add_data()
	{
			save_method = 'add';
			$('#form')[0].reset(); // reset form on modals
			$('.form-control').removeClass('is-invalid'); // clear error class
			$('.invalid-feedback').empty(); // clear error string
			$('#modal_form').modal('show'); // show bootstrap modal
			$('.modal-title').text('Tambah Tipe File'); // Set Title to Bootstrap modal title
			$('[name="status"][value="YA"]').prop("checked", true);
			$('#setting').val($('#setting_filter').val());
			if($('#setting_filter').val() == 'PEMBERKASAN' || $('#setting_filter').val() == 'DAFTAR_UKT'){
				$('.tipe_ujian_field').hide();
				$('.jalur_masuk_field').show();
			}else{
				$('.tipe_ujian_field').show();
				$('.jalur_masuk_field').hide();
			}
	}

	function edit_data(id)
	{
		save_method = 'update';
    $('#form')[0].reset(); // reset form on modals
    $('.form-control').removeClass('is-invalid'); // clear error class
    $('.invalid-feedback').empty(); // clear error string

		if($('#setting_filter').val() == 'PEMBERKASAN' || $('#setting_filter').val() == 'DAFTAR_UKT'){
			$('.tipe_ujian_field').hide();
			$('.jalur_masuk_field').show();
		}else{
			$('.tipe_ujian_field').show();
			$('.jalur_masuk_field').hide();
		}
		//Ajax Load data from ajax
    $.ajax({
			url : "<?php echo base_url(); ?>setting/tipe-file/get/" + id,
			type: "GET",
			dataType: "JSON",
			success: function(response)
			{
				if(response.data.status == 'TIDAK'){
					$('[name="status"][value="TIDAK"]').prop("checked", true);
				}else{
					$('[name="status"][value="YA"]').prop("checked", true);
				}
				if(response.data.upload == 'Wajib'){
					$('[name="upload"][value="Wajib"]').prop("checked", true);
				}else{
					$('[name="upload"][value="Opsional"]').prop("checked", true);
				}
				$('[name="ids_tipe_file"]').val(response.data.ids_tipe_file);
				$('[name="setting"]').val(response.data.setting);
				$('[name="nama_file"]').val(response.data.nama_file);
				$('[name="extensi"]').val(response.data.extensi);
				$('[name="max_size"]').val(response.data.max_size);
				if(response.data.setting == 'PEMBERKASAN' || response.data.setting == 'DAFTAR_UKT'){
					var types = response.data.jalur_masuk;
					if(types){
						$.each(types.split(","), function(i,e){
								$("#ids_jalur_masuk option[value='" + e + "']").prop("selected", true);
						});
					}
				}else{
					var types = response.data.tipe_ujian;
					if(types){
						$.each(types.split(","), function(i,e){
								$("#ids_tipe_ujian option[value='" + e + "']").prop("selected", true);
						});
					}
				}
				$('#modal_form').modal('show'); // show bootstrap modal when complete loaded
				$('.modal-title').text('Edit Tipe File'); // Set title to Bootstrap modal title
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
			url = "<?php echo base_url('setting/tipe-file/add')?>";
		} else {
			url = "<?php echo base_url('setting/tipe-file/update')?>";
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
					url : "<?php echo base_url('setting/tipe-file/delete/')?>"+id,
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

	$('#setting_filter').click(function(){
		if($(this).val() == 'PEMBERKASAN'){
			$('.ids_tipe_ujian_field').hide();
			$('.ids_jalur_masuk_field').show();
		}else{
			$('.ids_tipe_ujian_field').show();
			$('.ids_jalur_masuk_field').hide();
		}
	})
	

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