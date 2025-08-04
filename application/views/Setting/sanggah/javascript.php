<!-- DataTables -->
<script src="<?=base_url()?>assets/libs/datatables/jquery.dataTables.js"></script>
<script src="<?=base_url()?>assets/libs/datatables-bs5/datatables-bootstrap5.js"></script>
<script src="<?=base_url()?>assets/libs/toastr/toastr.js"></script>
<script src="<?=base_url()?>assets/libs/sweetalert2/sweetalert2.js"></script>
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
				"url": "<?php echo base_url(); ?>setting/sanggah/jsondatatable",
				"type": "POST",
				"data": function(data) {
					data.sanggah = $('#sanggah').val();
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
			$('.modal-title').text('Tambah Program'); // Set Title to Bootstrap modal title
			$('[name="status"][value="YA"]').prop("checked", true);
	}

	function edit_data(id)
	{
		save_method = 'update';
    $('#form')[0].reset(); // reset form on modals
    $('.form-control').removeClass('is-invalid'); // clear error class
    $('.invalid-feedback').empty(); // clear error string

		//Ajax Load data from ajax
    $.ajax({
			url : "<?php echo base_url(); ?>setting/sanggah/get/" + id,
			type: "GET",
			dataType: "JSON",
			success: function(response)
			{
				if(response.data.status == 'TIDAK'){
					$('[name="status"][value="TIDAK"]').prop("checked", true);
				}else{
					$('[name="status"][value="YA"]').prop("checked", true);
				}
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
			url = "<?php echo base_url('setting/sanggah/add')?>";
		} else {
			url = "<?php echo base_url('setting/sanggah/update')?>";
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
					url : "<?php echo base_url('setting/sanggah/delete/')?>"+id,
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