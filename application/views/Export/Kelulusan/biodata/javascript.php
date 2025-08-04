<script src="<?= base_url() ?>assets/libs/toastr/toastr.js"></script>
<script>
  $(document).ready(function(){
    $('#ids_fakultas').attr('disabled', true);
    $('#ids_tipe_ujian').attr('disabled', true);
  })

  $('#jenjang').on('change', function() {
    //tipe-ujian
    $.ajax({
      url : "<?=base_url('setting/tipe-ujian/get')?>",
      type: "GET",
      data: {
        jenjang: $(this).val(),
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
          if($('#jenjang').val()==''){
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
    //fakultas
    $.ajax({
      url : "<?=$_ENV['MASTER_HOST']?>fakultas/?status=YA&limit=1000&ids_fakultas=<?=$ket_user->data_fakultas?>",
      type: "GET",
      headers: {"Authorization": "Bearer <?=$this->input->cookie($_ENV['COOKIE_NAME'], TRUE)?>"},
      dataType: "JSON",
      data: {
        jenjang: $(this).val(),
      },
      async: false,
      success: function(response)
      {
        if(response.code == 200){
          var sel = $("#ids_fakultas");
          sel.empty();
          sel.append('<option label="-- Semua --"></option>');
          for (var i=0; i<response.data.data.length; i++) {
            sel.append('<option value="' + response.data.data[i].ids_fakultas + '">' + response.data.data[i].fakultas + '</option>');
          }
          if($('#jenjang').val()==''){
            $('select[name="ids_fakultas"]').attr('disabled', true);
          }else{
            $('select[name="ids_fakultas"]').removeAttr('disabled');
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