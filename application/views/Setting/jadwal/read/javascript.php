<!-- DataTables -->
<script src="<?=base_url()?>assets/libs/datatables/jquery.dataTables.js"></script>
<script src="<?=base_url()?>assets/libs/datatables-bs5/datatables-bootstrap5.js"></script>
<script src="<?=base_url()?>assets/libs/toastr/toastr.js"></script>
<script src="<?=base_url()?>assets/libs/sweetalert2/sweetalert2.js"></script>
<script src="<?=base_url()?>assets/plugins/jQuery/jquery.chained.js"></script>
<script type="text/javascript">
	var save_method; //for save method string
	var table;
	
	$(document).ready(function() {
		$("#ids_tipe_ujian").chained("#ids_program");
		//datatables
		table = $('#dataTabel').DataTable({
			"processing": true, //Feature control the processing indicator.
			"serverSide": true, //Feature control DataTables' server-side processing mode.
			"order": [], //Initial no order.

			// Load data for the table's content from an Ajax source
			"ajax": {
				"url": "<?php echo base_url(); ?>setting/jadwal/jsondatatable",
				"type": "POST",
				"data": function(data) {
					data.ids_program = $('#ids_program_filter').val();
					data.ids_tipe_ujian = $('#ids_tipe_ujian_filter').val();
					data.tanggal = $('#tanggal_filter').val();
					data.jam_awal = $('#jam_awal_filter').val();
					data.jam_akhir = $('#jam_akhir_filter').val();
					data.ids_area = $('#ids_area_filter').val();
					data.ids_gedung = $('#ids_gedung_filter').val();
					data.ids_ruangan = $('#ids_ruangan_filter').val();
					data.status = $('#status_filter').val();
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
					$('#tanggal_filter').attr('disabled', true);
					$('#jam_awal_filter').attr('disabled', true);
					$('#jam_akhir_filter').attr('disabled', true);
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
						$('#tanggal_filter').attr('disabled', true);
						$('#jam_awal_filter').attr('disabled', true);
						$('#jam_akhir_filter').attr('disabled', true);
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
		$('#ids_tipe_ujian_filter').on('change', function() {
			//tipe-ujian
			$.ajax({
				url : "<?=base_url('setting/jadwal/get2')?>",
				type: "GET",
				data: {
					ids_tipe_ujian: $(this).val(),
					select: 'distinct(tanggal)',
				},
				dataType: "JSON",
				async: false,
				success: function(response2)
				{
					if(response2.status == 200){
						var sel = $("#tanggal_filter");
						sel.empty();
						sel.append('<option value="">&laquo; Semua &raquo;</option>');
						for (var i=0; i<response2.data.length; i++) {
							sel.append('<option value="' + response2.data[i].tanggal + '">' + response2.data[i].tanggal + '</option>');
						}
						if($('#ids_tipe_ujian_filter').val()==''){
							$('#tanggal_filter').attr('disabled', true);
						}else{
							$('#tanggal_filter').removeAttr('disabled');
						}
						$('#jam_awal_filter').attr('disabled', true);
						$('#jam_akhir_filter').attr('disabled', true);
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
		$('#tanggal_filter').on('change', function() {
			$.ajax({
				url : "<?=base_url('setting/jadwal/get2')?>",
				type: "GET",
				data: {
					tanggal: $(this).val(),
					select: 'distinct(jam_awal)',
				},
				dataType: "JSON",
				async: false,
				success: function(response2)
				{
					if(response2.status == 200){
						var sel = $("#jam_awal_filter");
						sel.empty();
						sel.append('<option value="">&laquo; Semua &raquo;</option>');
						for (var i=0; i<response2.data.length; i++) {
							sel.append('<option value="' + response2.data[i].jam_awal + '">' + response2.data[i].jam_awal + '</option>');
						}
						if($('#tanggal_filter').val()==''){
							$('#jam_awal_filter').attr('disabled', true);
						}else{
							$('#jam_awal_filter').removeAttr('disabled');
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
			$.ajax({
				url : "<?=base_url('setting/jadwal/get2')?>",
				type: "GET",
				data: {
					tanggal: $(this).val(),
					select: 'distinct(jam_akhir)',
				},
				dataType: "JSON",
				async: false,
				success: function(response2)
				{
					if(response2.status == 200){
						var sel = $("#jam_akhir_filter");
						sel.empty();
						sel.append('<option value="">&laquo; Semua &raquo;</option>');
						for (var i=0; i<response2.data.length; i++) {
							sel.append('<option value="' + response2.data[i].jam_akhir + '">' + response2.data[i].jam_akhir + '</option>');
						}
						if($('#tanggal_filter').val()==''){
							$('#jam_akhir_filter').attr('disabled', true);
						}else{
							$('#jam_akhir_filter').removeAttr('disabled');
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

		$.ajax({
			url : "<?=$_ENV['MASTER_HOST']?>area/?status=YA&limit=1000",
			type: "GET",
			headers: {"Authorization": "Bearer <?=$this->input->cookie($_ENV['COOKIE_NAME'], TRUE)?>"},
			dataType: "JSON",
			async: false,
			success: function(response)
			{
				if(response.code == 200){
					var sel = $("#ids_area_filter");
					sel.empty();
					sel.append('<option value="">&laquo; Semua &raquo;</option>');
					for (var i=0; i<response.data.data.length; i++) {
						sel.append('<option value="' + response.data.data[i].ids_area + '">' + response.data.data[i].area + '</option>');
					}
					$('#ids_gedung_filter').attr('disabled', true);
					$('#ids_gedung_filter').prop('selectedIndex',0);
					$('#ids_ruangan_filter').attr('disabled', true);
					$('#ids_ruangan_filter').prop('selectedIndex',0);
				}else{
					notif_error(response.message)
				}
			},
			error: function (jqXHR, textStatus, errorThrown)
			{
				notif_error("Tidak dapat mengambil data jenis sekolah dari server.")
			}
		})
		$('#ids_area_filter').on('change', function() {
			$.ajax({
				url : "<?=$_ENV['MASTER_HOST']?>gedung/?status=YA&limit=1000&ids_area="+$(this).val(),
				type: "GET",
				headers: {"Authorization": "Bearer <?=$this->input->cookie($_ENV['COOKIE_NAME'], TRUE)?>"},
				dataType: "JSON",
				async: false,
				success: function(response)
				{
					if(response.code == 200){
						var sel = $("#ids_gedung_filter");
						sel.empty();
						sel.append('<option value="">&laquo; Semua &raquo;</option>');
						for (var i=0; i<response.data.data.length; i++) {
							sel.append('<option value="' + response.data.data[i].ids_gedung + '">' + response.data.data[i].gedung + '</option>');
						}
						if($('#ids_area_filter').val()==''){
							$('#ids_gedung_filter').attr('disabled', true);
						}else{
							$('#ids_gedung_filter').removeAttr('disabled');
						}
						$('#ids_ruangan_filter').attr('disabled', true);
						$('#ids_ruangan_filter').prop('selectedIndex',0);
					}else{
						notif_error(response.message)
					}
				},
				error: function (jqXHR, textStatus, errorThrown)
				{
					notif_error("Tidak dapat mengambil data gedung dari server.")
				}
			})
			$('#ids_gedung_filter').on('change', function() {
				$.ajax({
					url : "<?=$_ENV['MASTER_HOST']?>ruangan/?status=YA&limit=1000&ids_gedung="+$(this).val(),
					type: "GET",
					headers: {"Authorization": "Bearer <?=$this->input->cookie($_ENV['COOKIE_NAME'], TRUE)?>"},
					dataType: "JSON",
					async: false,
					success: function(response)
					{
						if(response.code == 200){
							var sel = $("#ids_ruangan_filter");
							sel.empty();
							sel.append('<option value="">&laquo; Semua &raquo;</option>');
							for (var i=0; i<response.data.data.length; i++) {
								sel.append('<option value="' + response.data.data[i].ids_ruangan + '">' + response.data.data[i].ruangan + '</option>');
							}
							if($('#ids_gedung_filter').val()==''){
								$('#ids_ruangan_filter').attr('disabled', true);
							}else{
								$('#ids_ruangan_filter').removeAttr('disabled');
							}
						}else{
							notif_error(response.message)
						}
					},
					error: function (jqXHR, textStatus, errorThrown)
					{
						notif_error("Tidak dapat mengambil data ruangan dari server.")
					}
				})
			})
		})
	});

	function reload_table()
	{
			table.ajax.reload(null,false); //reload datatable ajax 
	}

	function load_data()
	{
		$.ajax({
			url : "<?=$_ENV['MASTER_HOST']?>area/?status=YA&limit=1000",
			type: "GET",
			headers: {"Authorization": "Bearer <?=$this->input->cookie($_ENV['COOKIE_NAME'], TRUE)?>"},
			dataType: "JSON",
			async: false,
			success: function(response)
			{
				if(response.code == 200){
					var sel = $("#ids_area");
					sel.empty();
					sel.append('<option label="-- Pilih --"></option>');
					for (var i=0; i<response.data.data.length; i++) {
						sel.append('<option value="' + response.data.data[i].ids_area + '">' + response.data.data[i].area + '</option>');
					}
					$('select[name="ids_gedung"]').attr('disabled', true);
					$('select[name="ids_gedung"]').prop('selectedIndex',0);
					$('select[name="ids_ruangan"]').attr('disabled', true);
					$('select[name="ids_ruangan"]').prop('selectedIndex',0);
				}else{
					notif_error(response.message)
				}
			},
			error: function (jqXHR, textStatus, errorThrown)
			{
				notif_error("Tidak dapat mengambil data jenis sekolah dari server.")
			}
		})
		$('#ids_area').on('change', function() {
			$.ajax({
				url : "<?=$_ENV['MASTER_HOST']?>gedung/?status=YA&limit=1000&ids_area="+$(this).val(),
				type: "GET",
				headers: {"Authorization": "Bearer <?=$this->input->cookie($_ENV['COOKIE_NAME'], TRUE)?>"},
				dataType: "JSON",
				async: false,
				success: function(response)
				{
					if(response.code == 200){
						var sel = $("#ids_gedung");
						sel.empty();
						sel.append('<option label="-- Pilih --"></option>');
						for (var i=0; i<response.data.data.length; i++) {
							sel.append('<option value="' + response.data.data[i].ids_gedung + '">' + response.data.data[i].gedung + '</option>');
						}
						if($('#ids_area').val()==''){
							$('select[name="ids_gedung"]').attr('disabled', true);
						}else{
							$('select[name="ids_gedung"]').removeAttr('disabled');
						}
						$('select[name="ids_ruangan"]').attr('disabled', true);
						$('select[name="ids_ruangan"]').prop('selectedIndex',0);
					}else{
						notif_error(response.message)
					}
				},
				error: function (jqXHR, textStatus, errorThrown)
				{
					notif_error("Tidak dapat mengambil data gedung dari server.")
				}
			})
			$('#ids_gedung').on('change', function() {
				$.ajax({
					url : "<?=$_ENV['MASTER_HOST']?>ruangan/?status=YA&limit=1000&ids_gedung="+$(this).val(),
					type: "GET",
					headers: {"Authorization": "Bearer <?=$this->input->cookie($_ENV['COOKIE_NAME'], TRUE)?>"},
					dataType: "JSON",
					async: false,
					success: function(response)
					{
						if(response.code == 200){
							var sel = $("#ids_ruangan");
							sel.empty();
							sel.append('<option label="-- Pilih --"></option>');
							for (var i=0; i<response.data.data.length; i++) {
								sel.append('<option value="' + response.data.data[i].ids_ruangan + '">' + response.data.data[i].ruangan + '</option>');
							}
							if($('#ids_gedung').val()==''){
								$('select[name="ids_ruangan"]').attr('disabled', true);
							}else{
								$('select[name="ids_ruangan"]').removeAttr('disabled');
							}
						}else{
							notif_error(response.message)
						}
					},
					error: function (jqXHR, textStatus, errorThrown)
					{
						notif_error("Tidak dapat mengambil data ruangan dari server.")
					}
				})
			})
		})
	}

	function add_data()
	{
			save_method = 'add';
			$('#form')[0].reset(); // reset form on modals
			$('.form-control').removeClass('is-invalid'); // clear error class
			$('.invalid-feedback').empty(); // clear error string
			$('#modal_form').modal('show'); // show bootstrap modal
			$('.modal-title').text('Tambah Jadwal'); // Set Title to Bootstrap modal title
			$('[name="status"][value="YA"]').prop("checked", true);
			load_data()
	}

	function edit_data(id)
	{
		save_method = 'update';
    $('#form')[0].reset(); // reset form on modals
    $('.form-control').removeClass('is-invalid'); // clear error class
    $('.invalid-feedback').empty(); // clear error string

		Promise.all([
			load_data()
		]).then(function(){
			$.ajax({
				url : "<?php echo base_url(); ?>setting/jadwal/get/" + id,
				type: "GET",
				dataType: "JSON",
				async: false,
				success: function(response)
				{
					if(response.data.status == 'TIDAK'){
						$('[name="status"][value="TIDAK"]').prop("checked", true);
					}else{
						$('[name="status"][value="YA"]').prop("checked", true);
					}
					$('[name="ids_jadwal"]').val(response.data.ids_jadwal);
					$('[name="ids_program"]').val(response.data.ids_program).trigger('change');
					$('[name="ids_tipe_ujian"]').val(response.data.ids_tipe_ujian);
					$('[name="tanggal"]').val(response.data.tanggal);
					$('[name="jam_awal"]').val(response.data.jam_awal);
					$('[name="jam_akhir"]').val(response.data.jam_akhir);
					$('[name="ids_area"]').val(response.data.ids_area);
					$.ajax({
						url : "<?=$_ENV['MASTER_HOST']?>gedung/?status=YA&limit=1000&ids_area="+response.data.ids_area,
						type: "GET",
						headers: {"Authorization": "Bearer <?=$this->input->cookie($_ENV['COOKIE_NAME'], TRUE)?>"},
						dataType: "JSON",
						success: function(response2)
						{
							if(response2.code == 200){
								var sel = $("#ids_gedung");
								sel.empty();
								sel.append('<option label="-- Pilih --"></option>');
								for (var i=0; i<response2.data.data.length; i++) {
									sel.append('<option value="' + response2.data.data[i].ids_gedung + '">' + response2.data.data[i].gedung + '</option>');
								}
								$('select[name="ids_gedung"]').removeAttr('disabled');
								$('[name="ids_gedung"]').val(response.data.ids_gedung);

								$.ajax({
									url : "<?=$_ENV['MASTER_HOST']?>ruangan/?status=YA&limit=1000&ids_gedung="+response.data.ids_gedung,
									type: "GET",
									headers: {"Authorization": "Bearer <?=$this->input->cookie($_ENV['COOKIE_NAME'], TRUE)?>"},
									dataType: "JSON",
									success: function(response3)
									{
										if(response3.code == 200){
											var sel = $("#ids_ruangan");
											sel.empty();
											sel.append('<option label="-- Pilih --"></option>');
											for (var i=0; i<response3.data.data.length; i++) {
												sel.append('<option value="' + response3.data.data[i].ids_ruangan + '">' + response3.data.data[i].ruangan + '</option>');
											}
											$('select[name="ids_ruangan"]').removeAttr('disabled');
											$('[name="ids_ruangan"]').val(response.data.ids_ruangan);
										}else{
											notif_error(response3.message)
										}
									},
									error: function (jqXHR, textStatus, errorThrown)
									{
										notif_error("Tidak dapat mengambil data ruangan dari server.")
									}
								})
							}else{
								notif_error(response2.message)
							}
						},
						error: function (jqXHR, textStatus, errorThrown)
						{
							notif_error("Tidak dapat mengambil data gedung dari server.")
						}
					})
					$('[name="ids_ruangan"]').val(response.data.ids_ruangan);
					$('[name="quota"]').val(response.data.quota);
					$('#modal_form').modal('show'); // show bootstrap modal when complete loaded
					$('.modal-title').text('Edit Jadwal'); // Set title to Bootstrap modal title
				},
				error: function (jqXHR, textStatus, errorThrown)
				{
					notif_error("Error get data from ajax");
				}
			});
		})
		//Ajax Load data from ajax
	}

	function save()
	{
		$('#btnSave').text('Simpan...'); //change button text
		$('#btnSave').attr('disabled',true); //set button disable 
		var url;
		if(save_method == 'add') {
			url = "<?php echo base_url('setting/jadwal/add')?>";
		} else {
			url = "<?php echo base_url('setting/jadwal/update')?>";
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
					url : "<?php echo base_url('setting/jadwal/delete/')?>"+id,
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