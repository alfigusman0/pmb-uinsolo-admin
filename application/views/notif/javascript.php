<!-- DataTables -->
<script src="<?=base_url()?>assets/libs/datatables/jquery.dataTables.js"></script>
<script src="<?=base_url()?>assets/libs/datatables-bs5/datatables-bootstrap5.js"></script>
<script src="<?=base_url()?>assets/libs/toastr/toastr.js"></script>
<script src="<?=base_url()?>assets/libs/sweetalert2/sweetalert2.js"></script>
<script src="<?=base_url()?>assets/libs/select2/select2.js"></script>
<script src="<?=base_url('assets/plugins/ckeditor/ckeditor.js')?>"></script>
<script type="text/javascript">
	var save_method; //for save method string
	var table;

	$(document).ready(function() {
		CKEDITOR.replace('isi');
		CKEDITOR.replace('isi2');
		$('#ids_jalur_masuk').attr('disabled', true);
		$('#ids_tipe_ujian').attr('disabled', true);
		if($('#jenis_akun').val() == 'Daftar Ulang'){
			$('.jalur_masuk_field').show();
			$('.tipe_ujian_field').hide();
			$('#ids_jalur_masuk').attr('disabled', false);
		}else{
			$('.jalur_masuk_field').hide();
			$('.tipe_ujian_field').show();
			$('#ids_tipe_ujian').attr('disabled', false);
		}
		$('#jenis_akun').change(function(){
			if($(this).val() == 'Daftar Ulang'){
				$('.jalur_masuk_field').show();
				$('.tipe_ujian_field').hide();
				$('#ids_jalur_masuk').attr('disabled', false);
				$('#ids_tipe_ujian').attr('disabled', true);
			}else{
				$('.jalur_masuk_field').hide();
				$('.tipe_ujian_field').show();
				$('#ids_jalur_masuk').attr('disabled', true);
				$('#ids_tipe_ujian').attr('disabled', false);
			}	
		})
		$("#akun").select2({
			ajax: {
        url: "<?=base_url('notifikasi/akun')?>",
				type: 'GET',
				data: function (q){
					return {
						q: q,
						jenis_akun: $('#jenis_akun').val(),
						tahun: "<?=date('Y')?>",
						ids_jalur_masuk: $('#ids_jalur_masuk').val(),
						ids_tipe_ujian: $('#ids_tipe_ujian').val(),
						formulir: $('#formulir').val(),
						pembayaran: $('#pembayaran').val()
					};
				},
        dataType: 'json',
        delay: 300,
        processResults: function (data) {
          return {
            results: data
          };
        },
        cache: true
      },
			multiple: true,
			placeholder: 'Cari nama atau nomor peserta...',
			minimumInputLength: 3,
			dropdownParent: $("#modal_form")
		});
		$.ajax({
			url: "<?= $_ENV['MASTER_HOST'] ?>jalur-masuk/?status=YA&limit=1000",
			type: "GET",
			headers: {
				"Authorization": "Bearer <?= $this->input->cookie($_ENV['COOKIE_NAME'], TRUE) ?>"
			},
			dataType: "JSON",
			success: function(response) {
				if (response.code == 200) {
					var sel = $("#ids_jalur_masuk");
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
			url : "<?=base_url('setting/tipe-ujian/get')?>",
			type: "GET",
			dataType: "JSON",
			async: false,
			success: function(response2)
			{
				if(response2.status == 200){
					var sel = $("#ids_tipe_ujian");
					sel.empty();
					sel.append('<option value="">&laquo; Semua &raquo;</option>');
					for (var i=0; i<response2.data.length; i++) {
						sel.append('<option value="' + response2.data[i].ids_tipe_ujian + '">' + response2.data[i].tipe_ujian + ' (' + response2.data[i].jenjang + ')</option>');
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
		//datatables
		table = $('#dataTabel').DataTable({
			"processing": true, //Feature control the processing indicator.
			"serverSide": true, //Feature control DataTables' server-side processing mode.
			"order": [], //Initial no order.

			// Load data for the table's content from an Ajax source
			"ajax": {
				"url": "<?php echo base_url(); ?>notifikasi/jsondatatable/",
				"type": "POST",
				"data": function(data) {
					data.judul = $('#judul_filter').val();
					data.isi = $('#isi_filter').val();
					data.dibaca = $('#dibaca_filter').val();
					data.status_email = $('#status_email_filter').val();
					data.nama = $('#nama_filter').val();
					data.email = $('#email_filter').val();
					data.semail = $('#semail_filter').val();
					data.swhatsapp = $('#swhatsapp_filter').val();
					data.whatsapp = $('#whatsapp_filter').val();
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
	});
  
	function reload_table()
	{
			table.ajax.reload(); //reload datatable ajax 
	}

	function add_data()
	{
			save_method = 'add';
			$('#form')[0].reset(); // reset form on modals
			$('.form-control').removeClass('is-invalid'); // clear error class
			$('.invalid-feedback').empty(); // clear error string
			$('#modal_form').modal('show'); // show bootstrap modal
			$('.modal-title').text('Tambah Notifikasi'); // Set Title to Bootstrap modal title
	}

	function view_isi(id)
	{
		//Ajax Load data from ajax
    $.ajax({
			url : "<?php echo base_url(); ?>notifikasi/get",
			data: {
				id_notif: id
			},
			type: "GET",
			dataType: "JSON",
			success: function(response)
			{
				$('#modal_view_isi').modal('show'); // show bootstrap modal when complete loaded
				$('.modal-title').text('Lihat Isi'); // Set title to Bootstrap modal title
				$('#view_isi').contents().find('html').html(response.data[0].isi);
			},
			error: function (jqXHR, textStatus, errorThrown)
			{
				notif_error("Error get data from ajax");
			}
    });
	}

	function edit_data(id)
	{
		save_method = 'update';
    $('#form')[0].reset(); // reset form on modals
    $('.form-control').removeClass('is-invalid'); // clear error class
    $('.invalid-feedback').empty(); // clear error string

		//Ajax Load data from ajax
    $.ajax({
			url : "<?php echo base_url(); ?>notifikasi/get",
			data: {
				id_notif: id
			},
			type: "GET",
			dataType: "JSON",
			success: function(response)
			{
				CKEDITOR.instances.isi2.setData(response.data[0].isi)
				$('[name="id_notif"]').val(response.data[0].id_notif);
				$('[name="id_user"]').val(response.data[0].id_user);
				$('[name="nama"]').val(response.data[0].nama);
				$('[name="isi2_text"]').val(response.data[0].isi);
				$('[name="judul"]').val(response.data[0].judul);
				if (response.data.semail == 'TIDAK') {
					$('[name="semail"][value="TIDAK"]').prop("checked", true);
				} else {
					$('[name="semail"][value="YA"]').prop("checked", true);
				}
				if (response.data.swhatsapp == 'TIDAK') {
					$('[name="swhatsapp"][value="TIDAK"]').prop("checked", true);
				} else {
					$('[name="swhatsapp"][value="YA"]').prop("checked", true);
				}
				$('#modal_form_edit').modal('show'); // show bootstrap modal when complete loaded
				$('.modal-title').text('Edit Notifikasi'); // Set title to Bootstrap modal title
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
		var url, type;
		url = "<?php echo base_url('notifikasi/')?>";
		type = 'POST';
		var editor_data = CKEDITOR.instances.isi.getData();  // editor is the textarea field's name
		$("input[name='isi_text']").val(editor_data);

		// ajax adding data to database
		$.ajax({
			url : url,
			type: type,
			data: $('#form').serialize(),
			dataType: "JSON",
			success: function(response)
			{
				if(response.status == 200) //if success close modal and reload ajax table
				{
						$('#modal_form').modal('hide');
						reload_table();
						notif_success(response.message);
						notif_success(response.alt_message);
						$('#akun').empty();
						CKEDITOR.instances.isi.setData('')
						$('#form')[0].reset();
				}
				else
				{
					if(response.inputerror){
						for (var i = 0; i < response.inputerror.length; i++) 
						{
							$('[name="'+response.inputerror[i]+'"]').addClass('is-invalid'); //select parent twice to select div form-group class and add is-invalid class
							$('[name="'+response.inputerror[i]+'"]').next().text(response.error_string[i]); //select span invalid-feedback class set text error string
						}
					}else{
						// notif_error(response.message);
						notif_error(response.alt_message);
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
	
	function update()
	{
		$('#btnUpdate').text('Update...'); //change button text
		$('#btnUpdate').attr('disabled',true); //set button disable 
		var url;
		url = "<?php echo base_url('notifikasi/update')?>";
		var editor_data = CKEDITOR.instances.isi2.getData();  // editor is the textarea field's name
		$("input[name='isi2_text']").val(editor_data);

		// ajax adding data to database
		$.ajax({
			url : url,
			type: 'POST',
			data: $('#form_update').serialize(),
			dataType: "JSON",
			success: function(response)
			{
				if(response.status == 200) //if success close modal and reload ajax table
				{
						$('#modal_form_edit').modal('hide');
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
				$('#btnUpdate').text('Update'); //change button text
				$('#btnUpdate').attr('disabled',false); //set button enable 
			},
			error: function (jqXHR, textStatus, errorThrown)
			{
				notif_error("Error update data");
				$('#btnUpdate').text('Update'); //change button text
				$('#btnUpdate').attr('disabled',false); //set button enable 
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
					url : "<?php echo base_url('notifikasi/')?>"+id,
					type: "DELETE",
					dataType: "JSON",
					success: function(response)
					{
						//if success reload ajax table
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