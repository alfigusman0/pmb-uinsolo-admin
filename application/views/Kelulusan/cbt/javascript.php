<script src="<?= base_url() ?>assets/libs/toastr/toastr.js"></script>
<script>
  $(document).ready(function(){
    $('#tanggal').attr('disabled', true);
  })
  $('#tahun').on('change', function(){
    $.ajax({
      url : "<?=base_url('kelulusan/cbt/tanggal/get')?>",
      type: "POST",
      data: {
        program: $('#program').val(),
        tahun: $(this).val(),
      },
      dataType: "JSON",
      async: false,
      success: function(response)
      {
        if(response.status == 200){
          var sel = $("#tanggal");
          sel.empty();
          sel.append('<option value="">-- Semua --</option>');
          for (var i=0; i<response.data.length; i++) {
            sel.append('<option value="' + response.data[i].tanggal + '">' + response.data[i].tanggal + '</option>');
          }
          if($('#tahun').val()==''){
            $('#tanggal').attr('disabled', true);
            $('#tanggal').prop('selectedIndex',0);
          }else{
            $('#tanggal').removeAttr('disabled');
          }
        }else{
          notif_error(response.message)
        }
      },
      error: function (jqXHR, textStatus, errorThrown)
      {
        notif_error("Tidak dapat mengambil data tanggal dari server.")
      }
    });
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