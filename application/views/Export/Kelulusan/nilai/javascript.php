<script src="<?= base_url() ?>assets/libs/toastr/toastr.js"></script>
<script>
  $(document).ready(function(){
    $.ajax({
      url : "<?=base_url('setting/program/get')?>",
      type: "GET",
      dataType: "JSON",
      success: function(response)
      {
        if(response.status == 200){
          var sel = $("#ids_program");
          sel.empty();
          sel.append('<option label="-- Semua --"></option>');
          for (var i=0; i<response.data.length; i++) {
            sel.append('<option value="' + response.data[i].ids_program + '">' + response.data[i].program + '</option>');
          }
          $('select[name="ids_tipe_ujian"]').attr('disabled', true);
        }else{
          notif_error(response.message)
        }
      },
      error: function (jqXHR, textStatus, errorThrown)
      {
        notif_error("Tidak dapat mengambil data program dari server.")
      }
    })
    $('#ids_tipe_ujian').attr('disabled', true);
  })

  $('#ids_program').on('change', function() {
    //tipe-ujian
    $.ajax({
      url : "<?=base_url('setting/tipe-ujian/get')?>",
      type: "GET",
      data: {
        ids_program: $(this).val(),
      },
      dataType: "JSON",
      async: false,
      success: function(response)
      {
        if(response.status == 200){
          var sel = $("#ids_tipe_ujian");
          sel.empty();
          sel.append('<option label="-- Semua --"></option>');
          for (var i=0; i<response.data.length; i++) {
            sel.append('<option value="' + response.data[i].ids_tipe_ujian + '">' + response.data[i].tipe_ujian + '</option>');
          }
          if($('#ids_program').val()==''){
            $('select[name="ids_tipe_ujian"]').attr('disabled', true);
          }else{
            $('select[name="ids_tipe_ujian"]').removeAttr('disabled');
          }
        }else{
          notif_error(response.message)
        }
      },
      error: function (jqXHR, textStatus, errorThrown)
      {
        notif_error("Tidak dapat mengambil data tipe ujian dari server.")
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