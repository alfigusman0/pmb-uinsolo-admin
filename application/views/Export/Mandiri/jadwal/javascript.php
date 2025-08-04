<script src="<?= base_url() ?>assets/libs/toastr/toastr.js"></script>
<script>
  $(document).ready(function(){
    $('#jam').attr('disabled', true);
    $('#tahun').attr('disabled', true);
    $('#ids_tipe_ujian').attr('disabled', true);
    $('#tanggal_ujian').attr('disabled', true);
  })
  
  $.ajax({
    url : "<?=base_url('mandiri/export/abhp/program/get')?>",
    type: "POST",
    dataType: "JSON",
    async: false,
    success: function(response)
    {
      if(response.status == 200){
        var sel = $("#ids_program");
        sel.empty();
        sel.append('<option value="">-- Semua --</option>');
        for (var i=0; i<response.data.length; i++) {
          sel.append('<option value="' + response.data[i].ids_program + '">' + response.data[i].program + '</option>');
        }
        if($('#program').val()==''){
          $('#tanggal_ujian').attr('disabled', true);
          $('#jam').attr('disabled', true);
          $('#ids_tipe_ujian').attr('disabled', true);
          $('#tanggal_ujian').prop('selectedIndex',0);
          $('#jam').prop('selectedIndex',0);
          $('#ids_tipe_ujian').prop('selectedIndex',0);
        }else{
          $('select[name="ids_tipe_ujian"]').removeAttr('disabled');
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
  
  $('#ids_program').on('change', function(){
    $.ajax({
      url : "<?=base_url('mandiri/export/abhp/tipe-ujian/get')?>",
      type: "POST",
      data: {
        ids_program: $(this).val()
      },
      dataType: "JSON",
      async: false,
      success: function(response)
      {
        if(response.status == 200){
          var sel = $("#ids_tipe_ujian");
          sel.empty();
          sel.append('<option value="">-- Semua --</option>');
          for (var i=0; i<response.data.length; i++) {
            sel.append('<option value="' + response.data[i].ids_tipe_ujian + '">' + response.data[i].tipe_ujian + '</option>');
          }
          if($('#ids_program').val()==''){
            $('#tanggal_ujian').attr('disabled', true);
            $('#jam').attr('disabled', true);
            $('#tanggal_ujian').prop('selectedIndex',0);
            $('#jam').prop('selectedIndex',0);
            $('#tahun').attr('disabled', true);
            $('#tahun').prop('selectedIndex',0);
            $('#ids_tipe_ujian').attr('disabled', true);
            $('#ids_tipe_ujian').prop('selectedIndex',0);
          }else{
            $('select[name="ids_tipe_ujian"]').removeAttr('disabled');
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
  
  $('#ids_tipe_ujian').on('change', function(){
    if($('#ids_tipe_ujian').val()==''){
      $('#tanggal_ujian').attr('disabled', true);
      $('#tanggal_ujian').prop('selectedIndex',0);
      $('#tahun').attr('disabled', true);
      $('#tahun').prop('selectedIndex',0);
    }else{
      $('select[name="tahun"]').removeAttr('disabled');
    }
  })
  
  $('#tahun').on('change', function(){
    $.ajax({
      url : "<?=base_url('mandiri/export/abhp/tanggal/get')?>",
      type: "POST",
      data: {
        ids_tipe_ujian: $('#ids_tipe_ujian').val(),
        tahun: $(this).val()
      },
      dataType: "JSON",
      async: false,
      success: function(response)
      {
        if(response.status == 200){
          var sel = $("#tanggal_ujian");
          sel.empty();
          sel.append('<option value="">-- Semua --</option>');
          for (var i=0; i<response.data.length; i++) {
            sel.append('<option value="' + response.data[i].tanggal + '">' + response.data[i].tanggal + '</option>');
          }
          if($('#tahun').val()==''){
            $('#tanggal_ujian').attr('disabled', true);
            $('#tanggal_ujian').prop('selectedIndex',0);
          }else{
            $('#tanggal_ujian').removeAttr('disabled');
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