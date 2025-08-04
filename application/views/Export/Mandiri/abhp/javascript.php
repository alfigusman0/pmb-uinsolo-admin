<script src="<?= base_url() ?>assets/libs/toastr/toastr.js"></script>
<script>
  $(document).ready(function(){
    $('#tanggal').attr('disabled', true);
    $('#jam').attr('disabled', true);
    $('#tahun').attr('disabled', true);
    $('#ids_tipe_ujian').attr('disabled', true);
    $('#ids_area').attr('disabled', true);
    $('#ids_gedung').attr('disabled', true);
    $('#ids_ruangan').attr('disabled', true);
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
        sel.append('<option label="-- Pilih --"></option>');
        for (var i=0; i<response.data.length; i++) {
          sel.append('<option value="' + response.data[i].ids_program + '">' + response.data[i].program + '</option>');
        }
        if($('#program').val()==''){
          $('#tanggal').attr('disabled', true);
          $('#jam').attr('disabled', true);
          $('#ids_tipe_ujian').attr('disabled', true);
          $('#tanggal').prop('selectedIndex',0);
          $('#jam').prop('selectedIndex',0);
          $('#ids_tipe_ujian').prop('selectedIndex',0);
          $('#ids_area').attr('disabled', true);
          $('#ids_area').prop('selectedIndex',0);
          $('#ids_gedung').attr('disabled', true);
          $('#ids_gedung').prop('selectedIndex',0);
          $('#ids_ruangan').attr('disabled', true);
          $('#ids_ruangan').prop('selectedIndex',0);
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
          sel.append('<option label="-- Pilih --"></option>');
          for (var i=0; i<response.data.length; i++) {
            sel.append('<option value="' + response.data[i].ids_tipe_ujian + '">' + response.data[i].tipe_ujian + '</option>');
          }
          if($('#ids_program').val()==''){
            $('#tanggal').attr('disabled', true);
            $('#jam').attr('disabled', true);
            $('#tanggal').prop('selectedIndex',0);
            $('#jam').prop('selectedIndex',0);
            $('#tahun').attr('disabled', true);
            $('#tahun').prop('selectedIndex',0);
            $('#ids_tipe_ujian').attr('disabled', true);
            $('#ids_tipe_ujian').prop('selectedIndex',0);
            $('#ids_area').attr('disabled', true);
            $('#ids_area').prop('selectedIndex',0);
            $('#ids_gedung').attr('disabled', true);
            $('#ids_gedung').prop('selectedIndex',0);
            $('#ids_ruangan').attr('disabled', true);
            $('#ids_ruangan').prop('selectedIndex',0);
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
      $('#tanggal').attr('disabled', true);
      $('#tanggal').prop('selectedIndex',0);
      $('#tahun').attr('disabled', true);
      $('#tahun').prop('selectedIndex',0);
      $('#jam').attr('disabled', true);
      $('#jam').prop('selectedIndex',0);
      $('#ids_area').attr('disabled', true);
      $('#ids_area').prop('selectedIndex',0);
      $('#ids_gedung').attr('disabled', true);
      $('#ids_gedung').prop('selectedIndex',0);
      $('#ids_ruangan').attr('disabled', true);
      $('#ids_ruangan').prop('selectedIndex',0);
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
          var sel = $("#tanggal");
          sel.empty();
          sel.append('<option label="-- Pilih --"></option>');
          for (var i=0; i<response.data.length; i++) {
            sel.append('<option value="' + response.data[i].tanggal + '">' + response.data[i].tanggal + '</option>');
          }
          if($('#tahun').val()==''){
            $('#tanggal').attr('disabled', true);
            $('#jam').attr('disabled', true);
            $('#tanggal').prop('selectedIndex',0);
            $('#jam').prop('selectedIndex',0);
            $('#ids_area').attr('disabled', true);
            $('#ids_area').prop('selectedIndex',0);
            $('#ids_gedung').attr('disabled', true);
            $('#ids_gedung').prop('selectedIndex',0);
            $('#ids_ruangan').attr('disabled', true);
            $('#ids_ruangan').prop('selectedIndex',0);
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
  
  $('#tanggal').on('change', function(){
    $.ajax({
      url : "<?=base_url('mandiri/export/abhp/jam/get')?>",
      type: "POST",
      data: {
        ids_tipe_ujian: $('#ids_tipe_ujian').val(),
        tahun: $('#tahun').val(),
        tanggal: $(this).val()
      },
      dataType: "JSON",
      async: false,
      success: function(response)
      {
        if(response.status == 200){
          var sel = $("#jam");
          sel.empty();
          sel.append('<option label="-- Pilih --"></option>');
          for (var i=0; i<response.data.length; i++) {
            sel.append('<option value="' + response.data[i].jam_awal + ',' + response.data[i].jam_akhir + '">' + response.data[i].jam_awal + ' - ' + response.data[i].jam_akhir + '</option>');
          }
          if($('#tanggal').val()==''){
            $('#jam').attr('disabled', true);
            $('#jam').prop('selectedIndex',0);
            $('#ids_area').attr('disabled', true);
            $('#ids_area').prop('selectedIndex',0);
            $('#ids_gedung').attr('disabled', true);
            $('#ids_gedung').prop('selectedIndex',0);
            $('#ids_ruangan').attr('disabled', true);
            $('#ids_ruangan').prop('selectedIndex',0);
          }else{
            $('select[name="jam"]').removeAttr('disabled');
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
  
  $('#jam').on('change', function(){
    $.ajax({
      url : "<?=base_url('mandiri/export/abhp/area/get')?>",
      type: "POST",
      data: {
        tahun: $('#tahun').val(),
        tanggal: $('#tanggal').val(),
        jam: $(this).val()
      },
      dataType: "JSON",
      async: false,
      success: function(response)
      {
        if(response.status == 200){
          var sel = $("#ids_area");
          sel.empty();
          sel.append('<option label="-- Pilih --"></option>');
          for (var i=0; i<response.data.length; i++) {
            sel.append('<option value="' + response.data[i].ids_area + '">' + response.data[i].area + '</option>');
          }
          if($('#jam').val()==''){
            $('#ids_area').attr('disabled', true);
            $('#ids_area').prop('selectedIndex',0);
            $('#ids_gedung').attr('disabled', true);
            $('#ids_gedung').prop('selectedIndex',0);
            $('#ids_ruangan').attr('disabled', true);
            $('#ids_ruangan').prop('selectedIndex',0);
          }else{
            $('select[name="ids_area"]').removeAttr('disabled');
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
  
  $('#ids_area').on('change', function(){
    $.ajax({
      url : "<?=base_url('mandiri/export/abhp/gedung/get')?>",
      type: "POST",
      data: {
        tahun: $('#tahun').val(),
        tanggal: $('#tanggal').val(),
        jam: $('#jam').val(),
        ids_program: $('#ids_program').val(),
        ids_tipe_ujian: $('#ids_tipe_ujian').val(),
        ids_area: $(this).val()
      },
      dataType: "JSON",
      async: false,
      success: function(response)
      {
        if(response.status == 200){
          var sel = $("#ids_gedung");
          sel.empty();
          sel.append('<option label="-- Pilih --"></option>');
          for (var i=0; i<response.data.length; i++) {
            sel.append('<option value="' + response.data[i].ids_gedung + '">' + response.data[i].gedung + '</option>');
          }
          if($('#ids_area').val()==''){
            $('#ids_gedung').attr('disabled', true);
            $('#ids_gedung').prop('selectedIndex',0);
            $('#ids_ruangan').attr('disabled', true);
            $('#ids_ruangan').prop('selectedIndex',0);
          }else{
            $('select[name="ids_gedung"]').removeAttr('disabled');
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
  
  $('#ids_gedung').on('change', function(){
    $.ajax({
      url : "<?=base_url('mandiri/export/abhp/ruangan/get')?>",
      type: "POST",
      data: {
        tahun: $('#tahun').val(),
        tanggal: $('#tanggal').val(),
        jam: $('#jam').val(),
        ids_program: $('#ids_program').val(),
        ids_tipe_ujian: $('#ids_tipe_ujian').val(),
        ids_area: $('#ids_area').val(),
        ids_gedung: $(this).val()
      },
      dataType: "JSON",
      async: false,
      success: function(response)
      {
        if(response.status == 200){
          var sel = $("#ids_ruangan");
          sel.empty();
          sel.append('<option label="-- Pilih --"></option>');
          for (var i=0; i<response.data.length; i++) {
            sel.append('<option value="' + response.data[i].ids_ruangan + '">' + response.data[i].ruangan + '</option>');
          }
          if($('#ids_gedung').val()==''){
            $('#ids_ruangan').attr('disabled', true);
            $('#ids_ruangan').prop('selectedIndex',0);
          }else{
            $('select[name="ids_ruangan"]').removeAttr('disabled');
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
