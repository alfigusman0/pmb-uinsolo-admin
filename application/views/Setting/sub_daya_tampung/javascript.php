<!-- DataTables -->
<script src="<?=base_url()?>assets/libs/datatables/jquery.dataTables.js"></script>
<script src="<?=base_url()?>assets/libs/datatables-bs5/datatables-bootstrap5.js"></script>
<script src="<?=base_url()?>assets/libs/toastr/toastr.js"></script>
<script src="<?=base_url()?>assets/libs/sweetalert2/sweetalert2.js"></script>
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
				"url": "<?php echo base_url(); ?>setting/sub-daya-tampung/jsondatatable",
				"type": "POST",
				"data": function(data) {
					data.tahun = $('#tahun_filter').val();
					data.kode_jurusan = $('#kode_jurusan_filter').val();
					data.ids_fakultas = $('#ids_fakultas_filter').val();
					data.daya_tampung = $('#daya_tampung_filter').val();
					data.ids_jalur_masuk = $('#ids_jalur_masuk_filter').val();
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
				notif_error("Tidak dapat mengambil data jalur masuk dari server.")
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
					notif_error("Tidak dapat mengambil data jalur masuk dari server.")
				}
			});
		});
	}

	function add_data()
	{
			save_method = 'add';
			$('#form')[0].reset(); // reset form on modals
			$('.form-control').removeClass('is-invalid'); // clear error class
			$('.invalid-feedback').empty(); // clear error string
			$('#modal_form').modal('show'); // show bootstrap modal
			$('.modal-title').text('Tambah Daya Tampung'); // Set Title to Bootstrap modal title
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
			url : "<?php echo base_url(); ?>setting/sub-daya-tampung/get/" + id,
			type: "GET",
			dataType: "JSON",
			async: false,
			success: function(response)
			{
				console.log(response)
				if(response.data.status == 'TIDAK'){
					$('[name="status"][value="TIDAK"]').prop("checked", true);
				}else{
					$('[name="status"][value="YA"]').prop("checked", true);
				}
				$('[name="ids_sub_daya_tampung"]').val(response.data.ids_sub_daya_tampung);
				$('[name="kode_jurusan"]').val(response.data.kode_jurusan);
				$('[name="daya_tampung"]').val(response.data.daya_tampung);
				$('[name="ids_jalur_masuk"]').val(response.data.ids_jalur_masuk);
				$('#modal_form').modal('show'); // show bootstrap modal when complete loaded
				$('.modal-title').text('Edit Daya Tampung'); // Set title to Bootstrap modal title
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
			url = "<?php echo base_url('setting/sub-daya-tampung/add')?>";
		} else {
			url = "<?php echo base_url('setting/sub-daya-tampung/update')?>";
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
					url : "<?php echo base_url('setting/sub-daya-tampung/delete/')?>"+id,
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