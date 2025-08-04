<script src="<?= base_url() ?>assets/libs/datatables/jquery.dataTables.js"></script>
<script src="<?= base_url() ?>assets/libs/datatables-bs5/datatables-bootstrap5.js"></script>
<script src="<?= base_url() ?>assets/libs/toastr/toastr.js"></script>
<script src="<?= base_url() ?>assets/libs/sweetalert2/sweetalert2.js"></script>

<!-- buttons -->
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.6.1/css/buttons.dataTables.min.css">
<script src="https://cdn.datatables.net/buttons/1.6.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.1/js/buttons.flash.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.1/js/buttons.print.min.js"></script>
<script>
  $(document).ready(function() {
		var table = $('#dataTabel').DataTable({
      dom: 'Bfrtip',
        "buttons": [
          'excel'
        ]
    });
	});

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