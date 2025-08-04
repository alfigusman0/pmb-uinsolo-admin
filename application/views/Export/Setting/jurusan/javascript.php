<!-- <script>
  $('#ids_fakultas').on('change', function(){
    var parrams = "";
    if($(this).val() != "SEMUA"){
      parrams += "&ids_fakultas="+$(this).val()
    }
    $.ajax({
      url : "<?=$_ENV['MASTER_HOST']?>jurusan/?status=YA&limit=1000"+parrams,
      type: "GET",
      headers: {"Authorization": "Bearer <?=$this->input->cookie($_ENV['COOKIE_NAME'], TRUE)?>"},
      dataType: "JSON",
      success: function(response)
      {
        if(response.code == 200){
          var sel = $("#kode_jurusan");
          sel.empty();
          sel.append('<option value="SEMUA">Semua</option>');
          for (var i=0; i<response.data.data.length; i++) {
            sel.append('<option value="' + response.data.data[i].kode_jurusan + '">' + response.data.data[i].jurusan + '</option>');
          }
        }else{
          error++;
          notif_error(response.message)
        }
      },
      error: function (jqXHR, textStatus, errorThrown)
      {
        notif_error("Tidak dapat mengambil data jurusan dari server.")
      }
    })
  })
</script> -->