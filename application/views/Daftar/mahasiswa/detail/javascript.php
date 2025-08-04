<script src="<?= base_url() ?>assets/libs/datatables/jquery.dataTables.js"></script>
<script src="<?= base_url() ?>assets/libs/datatables-bs5/datatables-bootstrap5.js"></script>
<script src="<?= base_url() ?>assets/libs/toastr/toastr.js"></script>
<script src="<?= base_url() ?>assets/libs/sweetalert2/sweetalert2.js"></script>
<script src="<?=base_url()?>assets/libs/select2/select2.js"></script>
<script>
	$(document).ready(function() {
		var table = $('#dataTabel').DataTable();

		$('#pencarian_kelulusan').select2({
			dropdownParent: $('#modal_pindah > .modal-dialog > .modal-content > #formPindah > .modal-body'),
			ajax: {
				url: '<?=base_url('daftar/mahasiswa/kelulusan2/get')?>',
				data: function (params) {
					var query = {
						nama: params.term,
						ids_jalur_masuk_not: <?=$viewdKelulusan->ids_jalur_masuk?>,
						tahun: '<?=date('Y')?>'
					}

					// Query parameters will be ?search=[term]&type=public
					return query;
				},
				processResults: function (response) {
					// Transforms the top-level key of the response object from 'items' to 'results'
					var newData = [];
          if(response.status == 200){
            $.each(response.data, function (index, item) {
                newData.push({                        
                        id: item.idd_kelulusan, //id part present in data                        
                        text: item.nomor_peserta+' - '+item.nama+' - '+item.alias_jalur_masuk //string to be displayed
                });
            });
          }else{
            newData.push({
              text: 'Nama peserta tidak ditemukan.'
            })
          }
          return { results: newData };
				},
        cache: false,
        dataType: "json",
        delay: 200,
      },
      placeholder: "Cari Nama Peserta...",
      minimumInputLength: 3,
		});
	});

	function edit_data_kelulusan(id) {
		save_method = 'update';
		$('.form-control').removeClass('is-invalid'); // clear error class
		$('.invalid-feedback').empty(); // clear error string

		$.ajax({
			url: "<?= base_url('daftar/mahasiswa/kelulusan/get/') ?>" + id,
			type: "GET",
			dataType: "JSON",
			success: function(response) {
				$('[name="idd_kelulusan"]').val(response.data.idd_kelulusan);
				$('[name="nama"]').val(response.data.nama);
				$('[name="ket_pembayaran"]').val(response.data.ket_pembayaran);
				if (response.data.daftar == 'SUDAH') {
					$('[name="daftar"][value="SUDAH"]').prop("checked", true);
				} else {
					$('[name="daftar"][value="BELUM"]').prop("checked", true);
				}
				if (response.data.submit == 'SUDAH') {
					$('[name="submit"][value="SUDAH"]').prop("checked", true);
				} else {
					$('[name="submit"][value="BELUM"]').prop("checked", true);
				}
				if (response.data.pembayaran == 'SUDAH') {
					$('[name="pembayaran"][value="SUDAH"]').prop("checked", true);
					$('#tgl_pembayaran_field').show();
					$('#tgl_pembayaran').val(response.data.tgl_pembayaran);
				} else {
					$('#tgl_pembayaran_field').hide();
					$('[name="pembayaran"][value="BELUM"]').prop("checked", true);
				}
				if (response.data.pemberkasan == 'SUDAH') {
					$('[name="pemberkasan"][value="SUDAH"]').prop("checked", true);
				} else {
					$('[name="pemberkasan"][value="BELUM"]').prop("checked", true);
				}
				$('#modal_form_kelulusan').modal('show'); // show bootstrap modal when complete loaded
				$('.modal-title').text('Edit Kelulusan'); // Set title to Bootstrap modal title

				$('input[type=radio][name=pembayaran]').change(function() {
					if(this.value == 'SUDAH'){
						$('#tgl_pembayaran_field').show();
					}else{
						$('#tgl_pembayaran_field').hide();
					}
				})
			},
			error: function(jqXHR, textStatus, errorThrown) {
				notif_error("Error get data from ajax");
			}
		});
	}

	function view_foto(url, judul){
		$('#modal_view').modal('show');
		$('#view_foto').attr('src', url);
		$('#url').attr('href', url);
		$('#view_title').html(judul);
	}

	function pindah_kelulusan(){
		$('#modal_pindah').modal('show');
		$('.modal-title').html('Pindah Jalur Masuk');
	}

	function pindah()
	{
		$('#btnPindah').text('Tunggu sebentar...'); //change button text
		$('#btnPindah').attr('disabled', true); //set button disable
		var url;
		url = "<?= base_url('daftar/mahasiswa/kelulusan/pindah') ?>";

		Swal.fire({
			title: 'Apakah anda yakin pindahkan jalur masuk peserta ini?',
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Iya, pindahkan!',
			cancelButtonText: 'Tutup'
		}).then((result) => {
			if (result.isConfirmed) {
				$.ajax({
					url: url,
					type: 'POST',
					data: $('#formPindah').serialize(),
					dataType: "JSON",
					success: function(response) {
						if (response.status == 200) //if success close modal and reload ajax table
						{
							$('#modal_pindah').modal('hide');
							window.location.reload();
							notif_success(response.message);
						} else {
							notif_error(response.message);
						}
						$('#btnPindah').text('Pindahkan'); //change button text
						$('#btnPindah').attr('disabled', false); //set button enable
					},
					error: function(jqXHR, textStatus, errorThrown) {
						var message = jqXHR.responseJSON.message;
						notif_error(message)
						$('#btnPindah').text('Pindahkan'); //change button text
						$('#btnPindah').attr('disabled', false); //set button enable
					}
				});
			}else{
				$('#btnPindah').text('Pindahkan'); //change button text
				$('#btnPindah').attr('disabled', false); //set button enable
			}
		})
	}

	function save() {
		$('#btnSave').text('Simpan...'); //change button text
		$('#btnSave').attr('disabled', true); //set button disable
		var url;
		url = "<?= base_url('daftar/mahasiswa/data-kelulusan/save') ?>";

		// ajax adding data to database
		$.ajax({
			url: url,
			type: 'POST',
			data: $('#formKelulusan').serialize(),
			dataType: "JSON",
			success: function(response) {
				if (response.status == 200) //if success close modal and reload ajax table
				{
					$('#modal_form_kelulusan').modal('hide');
					window.location.reload();
					notif_success(response.message);
				} else {
					notif_error(response.message);
				}
				$('#btnSave').text('Simpan'); //change button text
				$('#btnSave').attr('disabled', false); //set button enable
			},
			error: function(jqXHR, textStatus, errorThrown) {
				var message = jqXHR.responseJSON.message;
				notif_error(message)
				$('#btnSave').text('Simpan'); //change button text
				$('#btnSave').attr('disabled', false); //set button enable
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