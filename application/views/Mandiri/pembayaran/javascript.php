<!-- DataTables -->
<script src="<?= base_url() ?>assets/libs/datatables/jquery.dataTables.js"></script>
<script src="<?= base_url() ?>assets/libs/datatables-bs5/datatables-bootstrap5.js"></script>
<script src="<?= base_url() ?>assets/libs/toastr/toastr.js"></script>
<script src="<?= base_url() ?>assets/libs/sweetalert2/sweetalert2.js"></script>
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
				"url": "<?php echo base_url(); ?>mandiri/pembayaran/jsondatatable/",
				"type": "POST",
				"data": function(data) {
					data.nama = $('#nama_filter').val();
					data.idp_formulir = $('#idp_formulir_filter').val();
					data.nomor_peserta = $('#nomor_peserta_filter').val();
					data.alias_bank = $('#alias_bank_filter').val();
					data.va = $('#va_filter').val();
					data.id_billing = $('#id_billing_filter').val();
					data.expire_at = $('#expire_at_filter').val();
					data.pembayaran = $('#pembayaran_filter').val();
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

	function load_data() {
		$.ajax({
			url: "<?= $_ENV['MASTER_HOST'] ?>bank/?status=YA&limit=1000&keterangan=mandiri",
			type: "GET",
			headers: {
				"Authorization": "Bearer <?= $this->input->cookie($_ENV['COOKIE_NAME'], TRUE) ?>"
			},
			dataType: "JSON",
			success: function(response) {
				if (response.code == 200) {
					var sel = $("#alias_bank_filter");
					sel.empty();
					sel.append('<option value="">&laquo; Semua &raquo;</option>');
					for (var i = 0; i < response.data.data.length; i++) {
						sel.append('<option value="' + response.data.data[i].alias + '">' + response.data.data[i].alias + '</option>');
					}
					var selTambah = $("#alias_bank_tambah");
					selTambah.empty();
					selTambah.append('<option value="">&laquo; Semua &raquo;</option>');
					for (var i = 0; i < response.data.data.length; i++) {
						selTambah.append('<option value="' + response.data.data[i].ids_bank + '">' + response.data.data[i].alias + '</option>');
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
	}

	function tambah_pembayaran()
  {
    $('#btnSubmit').html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>&nbsp;Tunggu sebentar...');
    $('#btnSubmit').attr('disabled',true);
    var ids_bank = $('#alias_bank_tambah').val();
    var kode_pembayaran = $('#kode_pembayaran_tambah').val();
    var nominal = $('#nominal_tambah').val();
    $.ajax({
      url : "<?=base_url('mandiri/pembayaran/pilih-bank/')?>",
      type: "POST",
      data: {
        ids_bank: ids_bank,
        kode_pembayaran: kode_pembayaran,
        nominal: nominal,
      },
      dataType: "JSON",
      success: function(response)
      {
        if(response.status == 200) //if success close modal and reload ajax table
        {
          Promise.all([
            reload_table(),
          ]).then(function(){
          	notif_success(response.message);
						$('#tambahPembayaran').modal('hide');
						$('#form-tambah-bayar')[0].reset();
            $('#btnSubmit').html("Submit"); //change button text
            $('#btnSubmit').attr('disabled',false); //set button enable 
          });
        }else{
          notif_error(response.message);
        }
        $('#btnSubmit').html("Submit"); //change button text
        $('#btnSubmit').attr('disabled',false); //set button enable 
      },
      error: function (jqXHR, textStatus, errorThrown)
      {
        notif_error("Error get data from server");
        $('#btnSubmit').html("Submit"); //change button text
        $('#btnSubmit').attr('disabled',false); //set button enable 
      }
    });
  }

	function edit_bank(id)
	{
		save_method = 'update';
    $('#form-edit')[0].reset(); // reset form on modals
    $('.form-control').removeClass('is-invalid'); // clear error class
    $('.invalid-feedback').empty(); // clear error string

		//Ajax Load data from ajax
    $.ajax({
			url : "<?php echo base_url(); ?>mandiri/pembayaran/get/" + id,
			type: "GET",
			dataType: "JSON",
			success: function(response)
			{
				$('[name="idp_pembayaran_edit"]').val(response.data.idp_pembayaran);
				$('[name="pembayaran_edit"]').val(response.data.pembayaran);
				$('[name="id_billing_edit"]').val(response.data.id_billing);
				var tahun = new Date().getFullYear();
				$('[name="kode_pembayaran_edit"]').val(tahun + response.data.idp_formulir);
				$('#modal_form_edit').modal('show'); // show bootstrap modal when complete loaded
				$('.modal-title-edit').text('Edit Pembayaran'); // Set title to Bootstrap modal title
			},
			error: function (jqXHR, textStatus, errorThrown)
			{
				notif_error("Error get data from ajax");
			}
    });
	}

  function act_edit_bank()
  {
    $('#btnEdit').html('Edit');
    $('#btnEdit').attr('disabled',true);
    $.ajax({
      url : "<?=base_url('mandiri/pembayaran/edit-bank/')?>",
			data: $('#form-edit').serialize(),
      type: "POST",
      dataType: "JSON",
      success: function(response)
      {
        if(response.status == 200) //if success close modal and reload ajax table
        {
          Promise.all([
            reload_table(),
          ]).then(function(){
						notif_success(response.message);
						$('#modal_form_edit').modal('hide');
            $('#btnEdit').html("Edit"); //change button text
            $('#btnEdit').attr('disabled',false); //set button enable
          });
        }else{
          notif_error(response.message);
          $('#btnEdit').html("Edit"); //change button text
          $('#btnEdit').attr('disabled',false); //set button enable 
        }
      },
      error: function (jqXHR, textStatus, errorThrown)
      {
        notif_error("Error get data from server");
        $('#btnEdit').html("Edit"); //change button text
        $('#btnEdit').attr('disabled',false); //set button enable 
      }
    });
  }

  function batalkan_bank(ids_bank, id)
  {
		$('#ids_bank_batalkan').val(ids_bank);
		$('#idd_kelulusan_batalkan').val(id);
		$('#batalkanBankModal').modal('show'); // show bootstrap modal when complete loaded
		$('.modal-title-batal').text('Batalkan Pilihan'); // Set title to Bootstrap modal title
  }
	

  function act_batalkan_bank()
  {
    $('#btnBatalkan').html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>&nbsp;Tunggu sebentar...');
    $('#btnBatalkan').attr('disabled',true);
    $.ajax({
      url : "<?=base_url('mandiri/pembayaran/batalkan-bank/')?>"+$('#ids_bank_batalkan').val()+'/'+$('#idd_kelulusan_batalkan').val(),
      type: "POST",
      dataType: "JSON",
      success: function(response)
      {
        if(response.status == 200) //if success close modal and reload ajax table
        {
          Promise.all([
            reload_table(),
          ]).then(function(){
						notif_success(response.message);
						$('#batalkanBankModal').modal('hide');
            $('#btnBatalkan').html("Batalkan"); //change button text
            $('#btnBatalkan').attr('disabled',false); //set button enable
          });
        }else{
          notif_error(response.message);
          $('#btnBatalkan').html("Batalkan"); //change button text
          $('#btnBatalkan').attr('disabled',false); //set button enable 
        }
      },
      error: function (jqXHR, textStatus, errorThrown)
      {
        notif_error("Error get data from server");
        $('#btnBatalkan').html("Batalkan"); //change button text
        $('#btnBatalkan').attr('disabled',false); //set button enable 
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