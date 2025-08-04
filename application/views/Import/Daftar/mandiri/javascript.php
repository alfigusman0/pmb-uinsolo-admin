<script src="<?= base_url() ?>assets/libs/datatables/jquery.dataTables.js"></script>
<script src="<?= base_url() ?>assets/libs/datatables-bs5/datatables-bootstrap5.js"></script>
<script src="<?= base_url() ?>assets/libs/toastr/toastr.js"></script>
<script src="<?= base_url() ?>assets/libs/sweetalert2/sweetalert2.js"></script>

<script>
  $(document).ready(function(){
    load_data();
  })

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
					var sel = $("#ids_jalur_masuk");
					sel.empty();
					sel.append('<option value="">&laquo; Semua &raquo;</option>');
					for (var i = 0; i < response.data.data.length; i++) {
						sel.append('<option value="' + response.data.data[i].ids_jalur_masuk + '">' + response.data.data[i].alias + '</option>');
					}
				} else {
					notif_error(response.message)
				}
			},
			error: function(jqXHR, textStatus, errorThrown) {
				notif_error("Tidak dapat mengambil data jalur masuk dari server.")
			}
		});
		//program - tipe ujian
		$.ajax({
			url : "<?=base_url('setting/program/get')?>",
			type: "GET",
			dataType: "JSON",
			success: function(response2)
			{
				if(response2.status == 200){
					var sel = $("#ids_program");
					sel.empty();
					sel.append('<option value="">&laquo; Semua &raquo;</option>');
					for (var i=0; i<response2.data.length; i++) {
						sel.append('<option value="' + response2.data[i].ids_program + '">' + response2.data[i].program + '</option>');
					}
				}else{
					notif_error(response2.message)
				}
			},
			error: function (jqXHR, textStatus, errorThrown)
			{
				notif_error("Tidak dapat mengambil data program dari server.")
			}
		});
    $.ajax({
			url : "<?=base_url('setting/tipe-ujian/get')?>",
			type: "GET",
			dataType: "JSON",
			success: function(response2)
			{
				if(response2.status == 200){
					var sel = $("#ids_tipe_ujian");
          sel.empty();
          sel.append('<option value="">&laquo; Semua &raquo;</option>');
          for (var i=0; i<response2.data.length; i++) {
            sel.append('<option value="' + response2.data[i].ids_tipe_ujian + '">' + response2.data[i].tipe_ujian + '</option>');
          }
				}else{
					notif_error(response2.message)
				}
			},
			error: function (jqXHR, textStatus, errorThrown)
			{
				notif_error("Tidak dapat mengambil data program dari server.")
			}
		});
		$('#ids_program').on('change', function() {
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
						var sel = $("#ids_tipe_ujian");
						sel.empty();
						sel.append('<option value="">&laquo; Semua &raquo;</option>');
						for (var i=0; i<response2.data.length; i++) {
							sel.append('<option value="' + response2.data[i].ids_tipe_ujian + '">' + response2.data[i].tipe_ujian + '</option>');
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