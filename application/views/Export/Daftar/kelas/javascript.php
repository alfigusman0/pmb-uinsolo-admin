<script src="<?= base_url() ?>assets/libs/toastr/toastr.js"></script>
<script>
  $(document).ready(function() {
    $('#ids_program').attr('disabled', true);
  })

  $('#ids_fakultas').on('change', function() {
    var parrams = "";
    if ($(this).val() != "") {
      parrams += "&fakultas=" + $(this).val()
    }
    $.ajax({
      url: "<?= $_ENV['MASTER_HOST'] ?>jurusan/?status=YA&limit=1000" + parrams,
      type: "GET",
      headers: {
        "Authorization": "Bearer <?= $this->input->cookie($_ENV['COOKIE_NAME'], TRUE) ?>"
      },
      dataType: "JSON",
      success: function(response) {
        if (response.code == 200) {
          var sel = $("#kode_jurusan");
          sel.empty();
          sel.append('<option value="">-- Pilih --</option>');
          for (var i = 0; i < response.data.data.length; i++) {
            sel.append('<option value="' + response.data.data[i].jurusan + '" data-jenjang="' + response.data.data[i].jenjang + '">' + response.data.data[i].jurusan + '</option>');
          }

          // Tambahkan event listener untuk update jenjang saat jurusan dipilih
          sel.on('change', function() {
            var selectedOption = $(this).find('option:selected');
            var jenjang = selectedOption.data('jenjang');
            $('#jenjang').val(jenjang);
          });
        } else {
          error++;
          notif_error(response.message)
        }
      },
      error: function(jqXHR, textStatus, errorThrown) {
        notif_error("Tidak dapat mengambil data jurusan dari server.")
      }
    })
  })

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