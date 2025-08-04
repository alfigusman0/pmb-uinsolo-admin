/**
 *  Form Wizard
 */

 'use strict';

 (function () { 
   // Wizard Validation
   // --------------------------------------------------------------------
   const wizardValidation = document.querySelector('#wizard-validation');
   if (typeof wizardValidation !== undefined && wizardValidation !== null) {
     // Wizard form
     const wizardValidationForm = wizardValidation.querySelector('#wizard-validation-form');
     // Wizard steps
     const wizardValidationFormStep1 = wizardValidationForm.querySelector('#data-diri');
     const wizardValidationFormStep2 = wizardValidationForm.querySelector('#data-rumah');
     const wizardValidationFormStep3 = wizardValidationForm.querySelector('#data-orangtua');
     const wizardValidationFormStep4 = wizardValidationForm.querySelector('#data-berkas');
     // Wizard next prev button
     const wizardValidationNext = [].slice.call(wizardValidationForm.querySelectorAll('.btn-next'));
     const wizardValidationPrev = [].slice.call(wizardValidationForm.querySelectorAll('.btn-prev'));
 
     const validationStepper = new Stepper(wizardValidation, {
       linear: true
     });
 
     // Data diri
     const FormValidation1 = FormValidation.formValidation(wizardValidationFormStep1, {
       fields: {
        nama: {
          validators: {
            notEmpty: {
              message: 'Nama wajib diisi'
            },
            stringLength: {
              min: 2,
              message: 'Panjang nama harus lebih dari 2 karakter'
            },
            regexp: {
              regexp: /^[a-zA-Z0-9 ]+$/,
              message: 'Nama hanya boleh terdiri dari abjad, angka dan spasi'
            }
          }
        },
        nomor_peserta: {
          validators: {
            notEmpty: {
              message: 'Nomor Peserta wajib diisi'
            }
          }
        },
        nmr_hp: {
          validators: {
            notEmpty: {
              message: 'Nomor HP wajib diisi'
            }
          }
        },
        email: {
          validators: {
            notEmpty: {
              message: 'Email wajib diisi'
            }
          }
        },
        nik: {
          validators: {
            notEmpty: {
              message: 'Nomor Induk Kependudukan (NIK) wajib diisi'
            },
            numeric: {
              message: 'Nomor Induk Kependudukan (NIK) wajib diisi angka'
            },
            stringLength: {
              min: 16,
              max: 16,
              message: 'Nomor Induk Kependudukan (NIK) wajib diisi 16 angka'
            },
          }
        },
        jenis_kelamin: {
          validators: {
            notEmpty: {
              message: 'Jenis kelamin wajib diisi'
            }
          }
        },
        ids_jalur_masuk: {
          validators: {
            notEmpty: {
              message: 'Jalur masuk wajib diisi'
            }
          }
        },
        ids_agama: {
          validators: {
            notEmpty: {
              message: 'Agama wajib diisi'
            }
          }
        },
        kewarganegaraan: {
          validators: {
            notEmpty: {
              message: 'Kewarganegaraan wajib diisi'
            }
          }
        },
        tempat_lahir: {
          validators: {
            notEmpty: {
              message: 'Tempat lahir wajib diisi'
            }
          }
        },
        tgl_lahir: {
          validators: {
            notEmpty: {
              message: 'Tanggal lahir wajib diisi'
            },
            date: {
              format: 'DD/MM/YYYY',
              message: 'Format tanggal lahir tidak valid',
            }
          }
        },
        ids_jenis_tinggal: {
          validators: {
            notEmpty: {
              message: 'Jenis tinggal wajib diisi'
            }
          }
        },
        ids_alat_transportasi: {
          validators: {
            notEmpty: {
              message: 'Alat transportasi wajib diisi'
            }
          }
        },
        ids_jenis_pendaftaran: {
          validators: {
            notEmpty: {
              message: 'Jenis pendaftaran wajib diisi'
            }
          }
        },
        ids_jenis_pembiayaan: {
          validators: {
            notEmpty: {
              message: 'Jenis pembiayaan wajib diisi'
            }
          }
        },
        ids_rumpun: {
          validators: {
            notEmpty: {
              message: 'Rumpun wajib diisi'
            }
          }
        },
        terima_kps: {
          validators: {
            notEmpty: {
              message: 'Terima KPS wajib diisi'
            }
          }
        },
        no_kps: {
          validators: {
            numeric: {
              message: 'No KPS wajib diisi angka'
            },
            callback: {
              message: 'No KPS wajib diisi',
              callback: function(value, validator, $field) {
                var kps = wizardValidationFormStep1.querySelector('[name="terima_kps"]').value;
                if(kps == 'TIDAK'){
                  return true;
                }else{
                  if(value.value){
                    return true;
                  }else{
                    return false;
                  }
                }
              }
          },
          }
        },
        ids_hubungan: {
          validators: {
            notEmpty: {
              message: 'Status perkawinan wajib diisi'
            }
          }
        },
        nisn: {
          validators: {
            notEmpty: {
              message: 'NISN wajib diisi'
            },
            numeric: {
              message: 'NISN diisi angka'
            }
          }
        },
        ids_jenis_sekolah: {
          validators: {
            notEmpty: {
              message: 'Jenis sekolah wajib diisi'
            }
          }
        },
        ids_jurusan_sekolah: {
          validators: {
            notEmpty: {
              message: 'Jurusan sekolah wajib diisi'
            }
          }
        },
        nama_sekolah: {
          validators: {
            notEmpty: {
              message: 'Nama sekolah wajib diisi'
            }
          }
        },
        akreditasi_sekolah: {
          validators: {
            notEmpty: {
              message: 'Akreditasi sekolah wajib diisi'
            }
          }
        }
       },
       plugins: {
         trigger: new FormValidation.plugins.Trigger(),
         bootstrap5: new FormValidation.plugins.Bootstrap5({
           // Use this for enabling/changing valid/invalid class
           // eleInvalidClass: '',
           eleValidClass: '',
           rowSelector: '.col-sm-6, .col-sm-12, .col-sm-4'
         }),
         autoFocus: new FormValidation.plugins.AutoFocus(),
         submitButton: new FormValidation.plugins.SubmitButton()
       },
       init: instance => {
         instance.on('plugins.message.placed', function (e) {
           //* Move the error message out of the `input-group` element
           if (e.element.parentElement.classList.contains('input-group')) {
             e.element.parentElement.insertAdjacentElement('afterend', e.messageElement);
           }
         });
       }
     }).on('core.form.valid', function () {
       // Jump to the next step when all fields in the current step are valid
       save_data_diri();
     });

    wizardValidationForm.querySelector('[name="terima_kps"]').addEventListener('change', function () {
      FormValidation1.revalidateField('no_kps');
    });
 
     // Data rumah
     const FormValidation2 = FormValidation.formValidation(wizardValidationFormStep2, {
       fields: {
        ids_rekening_listrik: {
          validators: {
            notEmpty: {
              message: 'Rekening listrik wajib diisi'
            }
          }
        },
        ids_daya_listrik: {
          validators: {
            notEmpty: {
              message: 'Daya listrik wajib diisi'
            }
          }
        },
        ids_rekening_pbb: {
          validators: {
            notEmpty: {
              message: 'Rekening PBB wajib diisi'
            }
          }
        },
        ids_pembayaran_pbb: {
          validators: {
            notEmpty: {
              message: 'Pembayaran PBB wajib diisi'
            }
          }
        },
        jalan: {
          validators: {
            notEmpty: {
              message: 'Alamat wajib diisi'
            }
          }
        },
        rt: {
          validators: {
            notEmpty: {
              message: 'RT wajib diisi'
            },
            numeric: {
              message: 'RT wajib diisi angka'
            }
          }
        },
        rw: {
          validators: {
            notEmpty: {
              message: 'RW wajib diisi'
            },
            numeric: {
              message: 'RW wajib diisi angka'
            }
          }
        },
        ids_negara: {
          validators: {
            notEmpty: {
              message: 'Negara wajib diisi'
            }
          }
        },
        ids_provinsi: {
          validators: {
            notEmpty: {
              message: 'Provinsi wajib diisi'
            }
          }
        },
        ids_kabupaten: {
          validators: {
            notEmpty: {
              message: 'Kabupaten wajib diisi'
            }
          }
        },
        ids_kecamatan: {
          validators: {
            notEmpty: {
              message: 'Kecamatan wajib diisi'
            }
          }
        },
        ids_kelurahan: {
          validators: {
            notEmpty: {
              message: 'Kelurahan wajib diisi'
            }
          }
        },
        kode_pos: {
          validators: {
            notEmpty: {
              message: 'Kode POS wajib diisi'
            },
            numeric: {
              message: 'Kode POS wajib diisi angka'
            }
          }
        }
       },
       plugins: {
         trigger: new FormValidation.plugins.Trigger(),
         bootstrap5: new FormValidation.plugins.Bootstrap5({
           // Use this for enabling/changing valid/invalid class
           // eleInvalidClass: '',
           eleValidClass: '',
           rowSelector: '.col-sm-6, .col-sm-12, .col-sm-4'
         }),
         autoFocus: new FormValidation.plugins.AutoFocus(),
         submitButton: new FormValidation.plugins.SubmitButton()
       }
     }).on('core.form.valid', function () {
       // Jump to the next step when all fields in the current step are valid
       save_data_rumah();
     });
 
     // Data orangtua
     const FormValidation3 = FormValidation.formValidation(wizardValidationFormStep3, {
      fields: {
        nik_ayah: {
          validators: {
            notEmpty: {
              message: 'NIK ayah wajib diisi'
            },
            numeric: {
              message: 'NIK ayah wajib diisi angka'
            },
            stringLength: {
              min: 16,
              max: 16,
              message: 'NIK ayah wajib diisi 16 angka'
            }
          }
        },
        nama_ayah: {
          validators: {
            notEmpty: {
              message: 'Nama ayah wajib diisi'
            }
          }
        },
        tgl_lahir_ayah: {
          validators: {
            notEmpty: {
              message: 'Tanggal lahir ayah wajib diisi'
            }
          }
        },
        ids_pendidikan_ayah: {
          validators: {
            notEmpty: {
              message: 'Pendidikan ayah wajib diisi'
            }
          }
        },
        ids_pekerjaan_ayah: {
          validators: {
            notEmpty: {
              message: 'Pekerjaan ayah wajib diisi'
            }
          }
        },
        ids_penghasilan_ayah: {
          validators: {
            notEmpty: {
              message: 'Penghasilan ayah wajib diisi'
            }
          }
        },
        nominal_penghasilan_ayah: {
          validators: {
            notEmpty: {
              message: 'Nominal penghasilan ayah wajib diisi'
            }
          }
        },
        terbilang_penghasilan_ayah: {
          validators: {
            notEmpty: {
              message: 'Terbilang penghasilan ayah wajib diisi'
            }
          }
        },
        nik_ibu: {
          validators: {
            notEmpty: {
              message: 'NIK ibu wajib diisi'
            },
            numeric: {
              message: 'NIK ibu wajib diisi angka'
            },
            stringLength: {
              min: 16,
              max: 16,
              message: 'NIK ibu wajib diisi 16 angka'
            }
          }
        },
        nama_ibu: {
          validators: {
            notEmpty: {
              message: 'Nama ibu wajib diisi'
            }
          }
        },
        tgl_lahir_ibu: {
          validators: {
            notEmpty: {
              message: 'Tanggal lahir ibu wajib diisi'
            }
          }
        },
        ids_pendidikan_ibu: {
          validators: {
            notEmpty: {
              message: 'Pendidikan ibu wajib diisi'
            }
          }
        },
        ids_pekerjaan_ibu: {
          validators: {
            notEmpty: {
              message: 'Pekerjaan ibu wajib diisi'
            }
          }
        },
        ids_penghasilan_ibu: {
          validators: {
            notEmpty: {
              message: 'Penghasilan ibu wajib diisi'
            }
          }
        },
        nominal_penghasilan_ibu: {
          validators: {
            notEmpty: {
              message: 'Nominal penghasilan ibu wajib diisi'
            }
          }
        },
        terbilang_penghasilan_ibu: {
          validators: {
            notEmpty: {
              message: 'Terbilang penghasilan ibu wajib diisi'
            }
          }
        },
        nik_wali: {
          validators: {
            numeric: {
              message: 'NIK wali wajib diisi angka'
            }
          },
          stringLength: {
            min: 16,
            max: 16,
            message: 'NIK wali wajib diisi 16 angka'
          }
        }
      },
      plugins: {
        trigger: new FormValidation.plugins.Trigger(),
        bootstrap5: new FormValidation.plugins.Bootstrap5({
          // Use this for enabling/changing valid/invalid class
          // eleInvalidClass: '',
          eleValidClass: '',
          rowSelector: '.col-sm-6, .col-sm-12, .col-sm-4'
        }),
        autoFocus: new FormValidation.plugins.AutoFocus(),
        submitButton: new FormValidation.plugins.SubmitButton()
      }
    }).on('core.form.valid', function () {
      // Jump to the next step when all fields in the current step are valid
      save_data_orangtua();
    });

    // Data berkas
    const FormValidation4 = FormValidation.formValidation(wizardValidationFormStep4, {
      fields: {
       pernyataan: {
         validators: {
           notEmpty: {
             message: 'Pernyataan wajib diisi'
           }
         }
       }
      },
      plugins: {
        trigger: new FormValidation.plugins.Trigger(),
        bootstrap5: new FormValidation.plugins.Bootstrap5({
          // Use this for enabling/changing valid/invalid class
          // eleInvalidClass: '',
          eleValidClass: '',
          rowSelector: '.col-sm-6, .col-sm-12, .col-sm-4'
        }),
        autoFocus: new FormValidation.plugins.AutoFocus(),
        submitButton: new FormValidation.plugins.SubmitButton()
      }
    }).on('core.form.valid', function () {
      // Jump to the next step when all fields in the current step are valid
      // alert('berhasil submit')
      submit_data();
    });
 
    wizardValidationNext.forEach(item => {
      item.addEventListener('click', event => {
        // When click the Next button, we will validate the current step
        switch (validationStepper._currentIndex) {
          case 0:
            FormValidation1.validate();
            break;

          case 1:
            FormValidation2.validate();
            break;

          case 2:
            FormValidation3.validate();
            break;
            
          case 3:
            FormValidation4.validate();
            break;

          default:
            break;
        }
      });
    });

    wizardValidationPrev.forEach(item => {
      item.addEventListener('click', event => {
        switch (validationStepper._currentIndex) {
          case 3:
            validationStepper.previous();
            break;

          case 2:
            validationStepper.previous();
            break;

          case 1:
            validationStepper.previous();
            break;

          case 0:

          default:
            break;
        }
      });
    });

    function save_data_diri(){
      $('#simpan1').html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>&nbsp;Tunggu sebentar...');
      $('#simpan1').attr('disabled',true);
      var formData = new FormData();
      formData.append('file_upload', $('[name="file_foto"]')[0].files[0])
      $.ajax({
        url : url_simpan_datafoto,
        type: "POST",
        processData: false,
        contentType: false,
        cache: false,
        data: formData,
        dataType: "JSON",
        success: function(response)
        {
          if(response.status == 200) //if success close modal and reload ajax table
          {
            $.ajax({
              url : url_simpan_datadiri,
              type: "POST",
              data: {
                idd_kelulusan: $('#idd_kelulusan').val(),
                nik: $('#nik').val(),
                jenis_kelamin: $('#jenis_kelamin').val(),
                ids_agama: $('#ids_agama').val(),
                kewarganegaraan: $('#kewarganegaraan').val(),
                tempat_lahir: $('#tempat_lahir').val(),
                tgl_lahir: $('#tgl_lahir').val(),
                ids_jenis_tinggal: $('#ids_jenis_tinggal').val(),
                ids_alat_transportasi: $('#ids_alat_transportasi').val(),
                ids_jenis_pendaftaran: $('#ids_jenis_pendaftaran').val(),
                ids_jenis_pembiayaan: $('#ids_jenis_pembiayaan').val(),
                ids_rumpun: $('#ids_rumpun').val(),
                ids_hubungan: $('#ids_hubungan').val(),
                terima_kps: $('#terima_kps').val(),
                no_kps: $('#no_kps').val(),
              },
              dataType: "JSON",
              success: function(response2)
              {
                if(response2.status == 200) //if success close modal and reload ajax table
                {
                  $.ajax({
                    url : url_simpan_datasekolah,
                    type: "POST",
                    data: {
                      idd_kelulusan: $('#idd_kelulusan').val(),
                      nisn: $('#nisn').val(),
                      ids_jurusan_sekolah: $('#ids_jurusan_sekolah').val(),
                      nama_sekolah: $('#nama_sekolah').val(),
                      akreditasi_sekolah: $('#akreditasi_sekolah').val(),
                    },
                    dataType: "JSON",
                    success: function(response2)
                    {
                      if(response2.status == 200) //if success close modal and reload ajax table
                      {
                        validationStepper.next();
                        notif_success(response2.message);
                        $('#simpan1').text('Simpan & Lanjutkan');
                        $('#simpan1').attr('disabled',false);
                      }else{
                        notif_error(response2.message);
                        $('#simpan1').text('Simpan & Lanjutkan');
                        $('#simpan1').attr('disabled',false);
                      }
                    },
                    error: function (jqXHR, textStatus, errorThrown)
                    {
                      notif_error("Error simpan data.");
                      $('#simpan1').text('Simpan & Lanjutkan');
                      $('#simpan1').attr('disabled',false);
                    }
                  });
                }else{
                  notif_error(response2.message);
                  $('#simpan1').text('Simpan & Lanjutkan');
                  $('#simpan1').attr('disabled',false);
                }
              },
              error: function (jqXHR, textStatus, errorThrown)
              {
                notif_error("Error simpan data.");
                $('#simpan1').text('Simpan & Lanjutkan');
                $('#simpan1').attr('disabled',false);
              }
            });
          }
          else
          {
            if(response.message){
              notif_error(response.message);
            }
            if(response.inputerror){
              for (var i = 0; i < response.inputerror.length; i++) 
              {
                notif_error(response.error_string[i]);
              }
            }
            $('#simpan1').text('Upload'); //change button text
            $('#simpan1').attr('disabled',false); //set button enable 
          }
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
          notif_error("Error simpan data");
          $('#simpan1').text('Upload'); //change button text
          $('#simpan1').attr('disabled',false); //set button enable 
        }
      });

    }

    function save_data_rumah(){
      $('#simpan2').html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>&nbsp;Tunggu sebentar...');
      $('#simpan2').attr('disabled',true);
      $.ajax({
        url : url_simpan_datarumah,
        type: "POST",
        data: {
          idd_kelulusan: $('#idd_kelulusan').val(),
          ids_rekening_listrik: $('#ids_rekening_listrik').val(),
          ids_daya_listrik: $('#ids_daya_listrik').val(),
          ids_rekening_pbb: $('#ids_rekening_pbb').val(),
          ids_pembayaran_pbb: $('#ids_pembayaran_pbb').val(),
          ids_kelurahan: $('#ids_kelurahan').val(),
          dusun: $('#dusun').val(),
          rt: $('#rt').val(),
          rw: $('#rw').val(),
          jalan: $('#jalan').val(),
          kode_pos: $('#kode_pos').val(),
        },
        dataType: "JSON",
        success: function(response)
        {
          if(response.status == 200) //if success close modal and reload ajax table
          {
            validationStepper.next();
            notif_success(response.message);
            $('#simpan2').text('Simpan & Lanjutkan');
            $('#simpan2').attr('disabled',false);
          }else{
            notif_error(response.message);
            $('#simpan2').text('Simpan & Lanjutkan');
            $('#simpan2').attr('disabled',false);
          }
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
          notif_error("Error simpan data.");
          $('#simpan2').text('Simpan & Lanjutkan');
          $('#simpan2').attr('disabled',false);
        }
      });
    }

    function save_data_orangtua(){
      $('#simpan3').html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>&nbsp;Tunggu sebentar...');
      $('#simpan3').attr('disabled',true);
      $.ajax({
        url : url_simpan_dataorangtua,
        type: "POST",
        data: {
          idd_kelulusan: $('#idd_kelulusan').val(),
          ids_tanggungan: $('#ids_tanggungan').val(),
          orangtua: 'Ayah',
          nik_orangtua: $('#nik_ayah').val(),
          nama_orangtua: $('#nama_ayah').val(),
          tgl_lahir_orangtua: $('#tgl_lahir_ayah').val(),
          ids_pendidikan: $('#ids_pendidikan_ayah').val(),
          ids_pekerjaan: $('#ids_pekerjaan_ayah').val(),
          ids_penghasilan: $('#ids_penghasilan_ayah').val(),
          nominal_penghasilan: $('#nominal_penghasilan_ayah').val(),
          terbilang_penghasilan: $('#terbilang_penghasilan_ayah').val(),
        },
        dataType: "JSON",
        success: function(response)
        {
          if(response.status == 200) //if success close modal and reload ajax table
          {
            $.ajax({
              url : url_simpan_dataorangtua,
              type: "POST",
              data: {
                idd_kelulusan: $('#idd_kelulusan').val(),
                ids_tanggungan: $('#ids_tanggungan').val(),
                orangtua: 'Ibu',
                nik_orangtua: $('#nik_ibu').val(),
                nama_orangtua: $('#nama_ibu').val(),
                tgl_lahir_orangtua: $('#tgl_lahir_ibu').val(),
                ids_pendidikan: $('#ids_pendidikan_ibu').val(),
                ids_pekerjaan: $('#ids_pekerjaan_ibu').val(),
                ids_penghasilan: $('#ids_penghasilan_ibu').val(),
                nominal_penghasilan: $('#nominal_penghasilan_ibu').val(),
                terbilang_penghasilan: $('#terbilang_penghasilan_ibu').val(),
              },
              dataType: "JSON",
              success: function(response2)
              {
                if(response2.status == 200) //if success close modal and reload ajax table
                {
                  if($('#ceklis_wali').is(':checked')){
                    if($('#nik_wali').val() == '' || $('#nama_wali').val() == '' || $('#tgl_lahir_wali').val() == '' || $('#ids_pendidikan_wali').val() == '' || $('#ids_pekerjaan_wali').val() == '' || $('#ids_penghasilan_wali').val() == '' || $('#nominal_penghasilan_wali').val() == '' || $('#terbilang_penghasilan_wali').val() == ''){
                      notif_error('Data wali belum diisi.');
                      $('#simpan3').text('Simpan & Lanjutkan');
                      $('#simpan3').attr('disabled',false);
                    }else{
                      $.ajax({
                        url : url_simpan_dataorangtua,
                        type: "POST",
                        data: {
                          idd_kelulusan: $('#idd_kelulusan').val(),
                          ids_tanggungan: $('#ids_tanggungan').val(),
                          orangtua: 'Wali',
                          nik_orangtua: $('#nik_wali').val(),
                          nama_orangtua: $('#nama_wali').val(),
                          tgl_lahir_orangtua: $('#tgl_lahir_wali').val(),
                          ids_pendidikan: $('#ids_pendidikan_wali').val(),
                          ids_pekerjaan: $('#ids_pekerjaan_wali').val(),
                          ids_penghasilan: $('#ids_penghasilan_wali').val(),
                          nominal_penghasilan: $('#nominal_penghasilan_wali').val(),
                          terbilang_penghasilan: $('#terbilang_penghasilan_wali').val(),
                        },
                        dataType: "JSON",
                        success: function(response3)
                        {
                          if(response3.status == 200) //if success close modal and reload ajax table
                          {
                            validationStepper.next();
                            notif_success(response3.message);
                            $('#simpan3').text('Simpan & Lanjutkan');
                            $('#simpan3').attr('disabled',false);
                          }else{
                            notif_error(response3.message);
                            $('#simpan3').text('Simpan & Lanjutkan');
                            $('#simpan3').attr('disabled',false);
                          }
                        },
                        error: function (jqXHR, textStatus, errorThrown)
                        {
                          notif_error("Error simpan data.");
                          $('#simpan3').text('Simpan & Lanjutkan');
                          $('#simpan3').attr('disabled',false);
                        }
                      });
                    }
                  }else{
                    $.ajax({
                      url : url_hapus_dataorangtua,
                      type: "POST",
                      data: {
                        idd_kelulusan: $('#idd_kelulusan').val(),
                        orangtua: 'Wali',
                      },
                      dataType: "JSON",
                      success: function(response3)
                      {
                        if(response3.status == 200) //if success close modal and reload ajax table
                        {
                          validationStepper.next();
                          notif_success(response2.message);
                          $('#simpan3').text('Simpan & Lanjutkan');
                          $('#simpan3').attr('disabled',false);
                        }else{
                          notif_error(response3.message);
                          $('#simpan3').text('Simpan & Lanjutkan');
                          $('#simpan3').attr('disabled',false);
                        }
                      },
                      error: function (jqXHR, textStatus, errorThrown)
                      {
                        notif_error("Error simpan data.");
                        $('#simpan3').text('Simpan & Lanjutkan');
                        $('#simpan3').attr('disabled',false);
                      }
                    });
                  }
                }else{
                  notif_error(response2.message);
                  $('#simpan3').text('Simpan & Lanjutkan');
                  $('#simpan3').attr('disabled',false);
                }
              },
              error: function (jqXHR, textStatus, errorThrown)
              {
                notif_error("Error simpan data.");
                $('#simpan3').text('Simpan & Lanjutkan');
                $('#simpan3').attr('disabled',false);
              }
            });
          }else{
            notif_error(response.message);
            $('#simpan3').text('Simpan & Lanjutkan');
            $('#simpan3').attr('disabled',false);
          }
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
          notif_error("Error simpan data.");
          $('#simpan3').text('Simpan & Lanjutkan');
          $('#simpan3').attr('disabled',false);
        }
      });
    }

    function submit_data(){
      $('#simpan4').html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>&nbsp;Tunggu sebentar...');
      $('#simpan4').attr('disabled',true);
      console.log($('#idd_kelulusan').val())
      $.ajax({
        url : url_submit_data,
        type: "POST",
        data: {
          idd_kelulusan: $('#idd_kelulusan').val(),
        },
        dataType: "JSON",
        success: function(response)
        {
          if(response.status == 200) //if success close modal and reload ajax table
          {
            Swal.fire({
              icon: 'success',
              title: 'Submit data berhasil.',
            }).then((result) => {
              window.location.reload();
            })
            $('#simpan4').text('Submit');
            $('#simpan4').attr('disabled',false);
          }else{
            notif_error(response.message);
            $('#simpan4').text('Submit');
            $('#simpan4').attr('disabled',false);
          }
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
          notif_error("Error submit data.");
          $('#simpan4').text('Submit');
          $('#simpan4').attr('disabled',false);
        }
      });
    }
   }
 })();