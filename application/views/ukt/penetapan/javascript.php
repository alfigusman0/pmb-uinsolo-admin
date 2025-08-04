<script src="<?=base_url()?>assets/libs/toastr/toastr.js"></script>
<script src="<?=base_url()?>assets/libs/sweetalert2/sweetalert2.js"></script>
<script>
  $(document).ready(function() {
		//set input/textarea/select event when change value, remove class error and remove text help block 
    $("input").change(function(){
        $(this).removeClass('is-invalid');
        $(this).next().empty();
    });
    $("textarea").change(function(){
        $(this).removeClass('is-invalid');
        $(this).next().empty();
    });
    $("select").change(function(){
        $(this).removeClass('is-invalid');
        $(this).next().empty();
    });
  })

	function swa()
	{
		$('#btnGenerate').text('Tunggu sebentar...'); //change button text
		$('#btnGenerate').attr('disabled',true); //set button disable 

		// ajax adding data to database
		$.ajax({
			url : "<?php echo base_url('ukt/penetapan/swa')?>",
			type: "POST",
			data: $('#formGenerate').serialize(),
			dataType: "JSON",
			success: function(response)
			{
				if(response.status == 200) //if success close modal and reload ajax table
				{
            $('#formGenerate')[0].reset();
            if(response.error > 0){
						  notif_warning(response.message);
            }else{
						  notif_success(response.message);
            }
				}
				else
				{
						for (var i = 0; i < response.inputerror.length; i++) 
						{
							$('[name="'+response.inputerror[i]+'"]').addClass('is-invalid'); //select parent twice to select div form-group class and add is-invalid class
							$('[name="'+response.inputerror[i]+'"]').next().text(response.error_string[i]); //select span invalid-feedback class set text error string
						}
				}
				$('#btnGenerate').text('Generate'); //change button text
				$('#btnGenerate').attr('disabled',false); //set button enable 
			},
			error: function (jqXHR, textStatus, errorThrown)
			{
				console.log(jqXHR)
				notif_error("Error penetapan ukt");
				$('#btnGenerate').text('Generate'); //change button text
				$('#btnGenerate').attr('disabled',false); //set button enable 
			}
		});
	}

	function notif_success(msg)
	{
		toastr.options.closeButton = true;
		toastr.options.progressBar = true;
		toastr.success(msg);
	}

  function notif_error(msg)
  {
    toastr.options.closeButton = true;
    toastr.options.progressBar = true;
    toastr.error(msg);
  }

  function notif_warning(msg)
  {
    toastr.options.closeButton = true;
    toastr.options.progressBar = true;
    toastr.warning(msg);
  }
</script>