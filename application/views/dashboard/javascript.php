<script src="<?= base_url() ?>assets/libs/datatables/jquery.dataTables.js"></script>
<script src="<?= base_url() ?>assets/libs/datatables-bs5/datatables-bootstrap5.js"></script>
<script src="<?= base_url() ?>assets/libs/block-ui/block-ui.js"></script>

<?php if (!empty($hak_akses)) : ?>
  <?php if ($hak_akses->code == 200) : ?>
    <script>
      $(document).ready(function() {

        loadingStatistikSemua();
        loadingStatistikMandiri();

        load_statistik_semua_formulir();
        load_statistik_semua_tipe_ujian();
        load_statistik_semua_jurusan();

        load_statistik_daya_tampung();

        var toggleStatusSemuaFormulir = false;
        var toggleStatusSemuaTipeUjian = false;
        var toggleStatusSemuaJurusan = false;
        var intervalSemuaFormulir;
        var intervalSemuaTipeUjian;
        var intervalSemuaJurusan;

        var toggleStatusMandiriFormulir = false;
        var toggleStatusMandiriTipeUjian = false;
        var toggleStatusMandiriJurusan = false;
        var intervalMandiriFormulir;
        var intervalMandiriTipeUjian;
        var intervalMandiriJurusan;

        var toggleStatusInternasionalFormulir = false;
        var toggleStatusInternasionalTipeUjian = false;
        var toggleStatusInternasionalJurusan = false;
        var intervalInternasionalFormulir;
        var intervalInternasionalTipeUjian;
        var intervalInternasionalJurusan;

        var toggleStatusPascasarjanaFormulir = false;
        var toggleStatusPascasarjanaTipeUjian = false;
        var toggleStatusPascasarjanaJurusan = false;
        var intervalPascasarjanaFormulir;
        var intervalPascasarjanaTipeUjian;
        var intervalPascasarjanaJurusan;

        var toggleStatusDayaTampung = false;
        var intervalDayaTampung;

        $("#toggleButtonSemuaFormulir").click(function() {
          toggleStatusSemuaFormulir = !toggleStatusSemuaFormulir;

          if (toggleStatusSemuaFormulir) {
            // Do something when toggle is ON
            $("#toggleButtonSemuaFormulir").removeClass("btn-primary").addClass("btn-success").html("<i class='bx bx-refresh'>&nbsp;</i>Refresh On");

            // Set interval for toggle off after 3 seconds
            intervalSemuaFormulir = setInterval(function() {
              load_statistik_semua_formulir();
            }, 10000);
          } else {
            // Do something when toggle is OFF
            $("#toggleButtonSemuaFormulir").removeClass("btn-success").addClass("btn-primary").html("<i class='bx bx-refresh'>&nbsp;</i>Refresh Off");

            // Clear interval if toggle off manually
            clearInterval(intervalSemuaFormulir);
          }
        });
        $("#toggleButtonSemuaTipeUjian").click(function() {
          toggleStatusSemuaTipeUjian = !toggleStatusSemuaTipeUjian;

          if (toggleStatusSemuaTipeUjian) {
            // Do something when toggle is ON
            $("#toggleButtonSemuaTipeUjian").removeClass("btn-primary").addClass("btn-success").html("<i class='bx bx-refresh'>&nbsp;</i>Refresh On");

            // Set interval for toggle off after 3 seconds
            intervalSemuaTipeUjian = setInterval(function() {
              load_statistik_semua_tipe_ujian();
            }, 10000);
          } else {
            // Do something when toggle is OFF
            $("#toggleButtonSemuaTipeUjian").removeClass("btn-success").addClass("btn-primary").html("<i class='bx bx-refresh'>&nbsp;</i>Refresh Off");

            // Clear interval if toggle off manually
            clearInterval(intervalSemuaTipeUjian);
          }
        });
        $("#toggleButtonSemuaJurusan").click(function() {
          toggleStatusSemuaJurusan = !toggleStatusSemuaJurusan;

          if (toggleStatusSemuaJurusan) {
            // Do something when toggle is ON
            $("#toggleButtonSemuaJurusan").removeClass("btn-primary").addClass("btn-success").html("<i class='bx bx-refresh'>&nbsp;</i>Refresh On");

            // Set interval for toggle off after 3 seconds
            intervalSemuaJurusan = setInterval(function() {
              load_statistik_semua_jurusan();
            }, 10000);
          } else {
            // Do something when toggle is OFF
            $("#toggleButtonSemuaJurusan").removeClass("btn-success").addClass("btn-primary").html("<i class='bx bx-refresh'>&nbsp;</i>Refresh Off");

            // Clear interval if toggle off manually
            clearInterval(intervalSemuaJurusan);
          }
        });

        $("#toggleButtonMandiriFormulir").click(function() {
          toggleStatusMandiriFormulir = !toggleStatusMandiriFormulir;

          if (toggleStatusMandiriFormulir) {
            // Do something when toggle is ON
            $("#toggleButtonMandiriFormulir").removeClass("btn-primary").addClass("btn-success").html("<i class='bx bx-refresh'>&nbsp;</i>Refresh On");

            // Set interval for toggle off after 3 seconds
            intervalMandiriFormulir = setInterval(function() {
              load_statistik_mandiri_formulir();
            }, 10000);
          } else {
            // Do something when toggle is OFF
            $("#toggleButtonMandiriFormulir").removeClass("btn-success").addClass("btn-primary").html("<i class='bx bx-refresh'>&nbsp;</i>Refresh Off");

            // Clear interval if toggle off manually
            clearInterval(intervalMandiriFormulir);
          }
        });
        $("#toggleButtonMandiriTipeUjian").click(function() {
          toggleStatusMandiriTipeUjian = !toggleStatusMandiriTipeUjian;

          if (toggleStatusMandiriTipeUjian) {
            // Do something when toggle is ON
            $("#toggleButtonMandiriTipeUjian").removeClass("btn-primary").addClass("btn-success").html("<i class='bx bx-refresh'>&nbsp;</i>Refresh On");

            // Set interval for toggle off after 3 seconds
            intervalMandiriTipeUjian = setInterval(function() {
              load_statistik_mandiri_tipe_ujian();
            }, 10000);
          } else {
            // Do something when toggle is OFF
            $("#toggleButtonMandiriTipeUjian").removeClass("btn-success").addClass("btn-primary").html("<i class='bx bx-refresh'>&nbsp;</i>Refresh Off");

            // Clear interval if toggle off manually
            clearInterval(intervalMandiriTipeUjian);
          }
        });
        $("#toggleButtonMandiriJurusan").click(function() {
          toggleStatusMandiriJurusan = !toggleStatusMandiriJurusan;

          if (toggleStatusMandiriJurusan) {
            // Do something when toggle is ON
            $("#toggleButtonMandiriJurusan").removeClass("btn-primary").addClass("btn-success").html("<i class='bx bx-refresh'>&nbsp;</i>Refresh On");

            // Set interval for toggle off after 3 seconds
            intervalMandiriJurusan = setInterval(function() {
              load_statistik_mandiri_jurusan();
            }, 10000);
          } else {
            // Do something when toggle is OFF
            $("#toggleButtonMandiriJurusan").removeClass("btn-success").addClass("btn-primary").html("<i class='bx bx-refresh'>&nbsp;</i>Refresh Off");

            // Clear interval if toggle off manually
            clearInterval(intervalMandiriJurusan);
          }
        });

        $("#toggleButtonInternasionalFormulir").click(function() {
          toggleStatusInternasionalFormulir = !toggleStatusInternasionalFormulir;

          if (toggleStatusInternasionalFormulir) {
            // Do something when toggle is ON
            $("#toggleButtonInternasionalFormulir").removeClass("btn-primary").addClass("btn-success").html("<i class='bx bx-refresh'>&nbsp;</i>Refresh On");

            // Set interval for toggle off after 3 seconds
            intervalInternasionalFormulir = setInterval(function() {
              load_statistik_internasional_formulir();
            }, 10000);
          } else {
            // Do something when toggle is OFF
            $("#toggleButtonInternasionalFormulir").removeClass("btn-success").addClass("btn-primary").html("<i class='bx bx-refresh'>&nbsp;</i>Refresh Off");

            // Clear interval if toggle off manually
            clearInterval(intervalInternasionalFormulir);
          }
        });
        $("#toggleButtonInternasionalTipeUjian").click(function() {
          toggleStatusInternasionalTipeUjian = !toggleStatusInternasionalTipeUjian;

          if (toggleStatusInternasionalTipeUjian) {
            // Do something when toggle is ON
            $("#toggleButtonInternasionalTipeUjian").removeClass("btn-primary").addClass("btn-success").html("<i class='bx bx-refresh'>&nbsp;</i>Refresh On");

            // Set interval for toggle off after 3 seconds
            intervalInternasionalTipeUjian = setInterval(function() {
              load_statistik_internasional_tipe_ujian();
            }, 10000);
          } else {
            // Do something when toggle is OFF
            $("#toggleButtonInternasionalTipeUjian").removeClass("btn-success").addClass("btn-primary").html("<i class='bx bx-refresh'>&nbsp;</i>Refresh Off");

            // Clear interval if toggle off manually
            clearInterval(intervalInternasionalTipeUjian);
          }
        });
        $("#toggleButtonInternasionalJurusan").click(function() {
          toggleStatusInternasionalJurusan = !toggleStatusInternasionalJurusan;

          if (toggleStatusInternasionalJurusan) {
            // Do something when toggle is ON
            $("#toggleButtonInternasionalJurusan").removeClass("btn-primary").addClass("btn-success").html("<i class='bx bx-refresh'>&nbsp;</i>Refresh On");

            // Set interval for toggle off after 3 seconds
            intervalInternasionalJurusan = setInterval(function() {
              load_statistik_internasional_jurusan();
            }, 10000);
          } else {
            // Do something when toggle is OFF
            $("#toggleButtonInternasionalJurusan").removeClass("btn-success").addClass("btn-primary").html("<i class='bx bx-refresh'>&nbsp;</i>Refresh Off");

            // Clear interval if toggle off manually
            clearInterval(intervalInternasionalJurusan);
          }
        });

        $("#toggleButtonPascasarjanaFormulir").click(function() {
          toggleStatusPascasarjanaFormulir = !toggleStatusPascasarjanaFormulir;

          if (toggleStatusPascasarjanaFormulir) {
            // Do something when toggle is ON
            $("#toggleButtonPascasarjanaFormulir").removeClass("btn-primary").addClass("btn-success").html("<i class='bx bx-refresh'>&nbsp;</i>Refresh On");

            // Set interval for toggle off after 3 seconds
            intervalPascasarjanaFormulir = setInterval(function() {
              load_statistik_pascasarjana_formulir();
            }, 10000);
          } else {
            // Do something when toggle is OFF
            $("#toggleButtonPascasarjanaFormulir").removeClass("btn-success").addClass("btn-primary").html("<i class='bx bx-refresh'>&nbsp;</i>Refresh Off");

            // Clear interval if toggle off manually
            clearInterval(intervalPascasarjanaFormulir);
          }
        });
        $("#toggleButtonPascasarjanaTipeUjian").click(function() {
          toggleStatusPascasarjanaTipeUjian = !toggleStatusPascasarjanaTipeUjian;

          if (toggleStatusPascasarjanaTipeUjian) {
            // Do something when toggle is ON
            $("#toggleButtonPascasarjanaTipeUjian").removeClass("btn-primary").addClass("btn-success").html("<i class='bx bx-refresh'>&nbsp;</i>Refresh On");

            // Set interval for toggle off after 3 seconds
            intervalPascasarjanaTipeUjian = setInterval(function() {
              load_statistik_pascasarjana_tipe_ujian();
            }, 10000);
          } else {
            // Do something when toggle is OFF
            $("#toggleButtonPascasarjanaTipeUjian").removeClass("btn-success").addClass("btn-primary").html("<i class='bx bx-refresh'>&nbsp;</i>Refresh Off");

            // Clear interval if toggle off manually
            clearInterval(intervalPascasarjanaTipeUjian);
          }
        });
        $("#toggleButtonPascasarjanaJurusan").click(function() {
          toggleStatusPascasarjanaJurusan = !toggleStatusPascasarjanaJurusan;

          if (toggleStatusPascasarjanaJurusan) {
            // Do something when toggle is ON
            $("#toggleButtonPascasarjanaJurusan").removeClass("btn-primary").addClass("btn-success").html("<i class='bx bx-refresh'>&nbsp;</i>Refresh On");

            // Set interval for toggle off after 3 seconds
            intervalPascasarjanaJurusan = setInterval(function() {
              load_statistik_pascasarjana_jurusan();
            }, 10000);
          } else {
            // Do something when toggle is OFF
            $("#toggleButtonPascasarjanaJurusan").removeClass("btn-success").addClass("btn-primary").html("<i class='bx bx-refresh'>&nbsp;</i>Refresh Off");

            // Clear interval if toggle off manually
            clearInterval(intervalPascasarjanaJurusan);
          }
        });


        $("#toggleButtonDayaTampung").click(function() {
          toggleStatusDayaTampung = !toggleStatusDayaTampung;

          if (toggleStatusDayaTampung) {
            // Do something when toggle is ON
            $("#toggleButtonDayaTampung").removeClass("btn-primary").addClass("btn-success").html("<i class='bx bx-refresh'>&nbsp;</i>Refresh On");

            // Set interval for toggle off after 3 seconds
            intervalDayaTampung = setInterval(function() {
              load_statistik_daya_tampung();
            }, 60000);
          } else {
            // Do something when toggle is OFF
            $("#toggleButtonDayaTampung").removeClass("btn-success").addClass("btn-primary").html("<i class='bx bx-refresh'>&nbsp;</i>Refresh Off");

            // Clear interval if toggle off manually
            clearInterval(intervalDayaTampung);
          }
        });
      })

      function load_tab_mandiri() {
        load_statistik_mandiri_formulir();
        load_statistik_mandiri_tipe_ujian();
        load_statistik_mandiri_jurusan();
      }

      function load_tab_internasional() {
        load_statistik_internasional_formulir();
        load_statistik_internasional_tipe_ujian();
        load_statistik_internasional_jurusan();
      }

      function load_tab_pascasarjana() {
        load_statistik_pascasarjana_formulir();
        load_statistik_pascasarjana_tipe_ujian();
        load_statistik_pascasarjana_jurusan();
      }

      function loadingStatistikSemua(){
        $('.card-ujian-mandiri-semua').block({
          message: '<div class="d-flex justify-content-center align-items-center"><div class="sk-chase"><div class="sk-chase-dot"></div><div class="sk-chase-dot"></div><div class="sk-chase-dot"></div><div class="sk-chase-dot"></div><div class="sk-chase-dot"></div><div class="sk-chase-dot"></div></div>&nbsp;&nbsp;&nbsp;&nbsp;<p class="mb-0">Tunggu sebentar...</p></div>',
          css: {
            backgroundColor: 'transparent',
            color: '#fff',
            border: '0'
          },
          overlayCSS: {
            opacity: 0.5
          }
        });
      }

      function loadingStatistikMandiri(){
        $('.card-ujian-mandiri-mandiri').block({
          message: '<div class="d-flex justify-content-center align-items-center"><div class="sk-chase"><div class="sk-chase-dot"></div><div class="sk-chase-dot"></div><div class="sk-chase-dot"></div><div class="sk-chase-dot"></div><div class="sk-chase-dot"></div><div class="sk-chase-dot"></div></div>&nbsp;&nbsp;&nbsp;&nbsp;<p class="mb-0">Tunggu sebentar...</p></div>',
          css: {
            backgroundColor: 'transparent',
            color: '#fff',
            border: '0'
          },
          overlayCSS: {
            opacity: 0.5
          }
        });
      }

      $('#tahun_statistik_semua').change(function () {
        loadingStatistikSemua();
        load_statistik_semua_formulir($(this).val());
        load_statistik_semua_tipe_ujian($(this).val());
        load_statistik_semua_jurusan($(this).val());
      })

      function load_statistik_semua_formulir(tahun = null) {
        var url_statistik = "<?= base_url('api/mandiri/statistik/m1/') ?>";
        var url_kebutuhan_khusus = "<?= base_url('api/mandiri/statistik/m15/') ?>"
        //semua
        $.ajax({
          url: url_statistik,
          type: "GET",
          data: {
            tahun: tahun,
          },
          dataType: "JSON",
          // async: false,
          success: function(response) {
            if (response.code == 200) {
              $('#input_semua_formulir_total').val(response.data)
              $('#semua_formulir_total').html(response.data)
            }
          }
        }).then(function() {
          $.ajax({
            url: url_statistik,
            type: "GET",
            data: {
              tahun: tahun,
              formulir: 'SUDAH'
            },
            dataType: "JSON",
            async: false,
            success: function(response) {
              if (response.code == 200) {
                var persentase = Math.round(((100 * response.data) / $('#input_semua_formulir_total').val()))
                $('#semua_formulir_sudah').html(response.data)
                $('#semua_formulir_sudah_p').html(persentase + '%')

                $('#semua_formulir_sudah_p2').css({
                  "width": persentase + '%'
                });
                $('#semua_formulir_sudah_p2').attr('aria-valuenow', persentase);
              }
            }
          });

          $.ajax({
            url: url_statistik,
            type: "GET",
            data: {
              tahun: tahun,
              formulir: 'BELUM'
            },
            dataType: "JSON",
            async: false,
            success: function(response) {
              if (response.code == 200) {
                var persentase = Math.round(((100 * response.data) / $('#input_semua_formulir_total').val()))
                $('#semua_formulir_belum').html(response.data)
                $('#semua_formulir_belum_p').html(persentase + '%')

                $('#semua_formulir_belum_p2').css({
                  "width": persentase + '%'
                });
                $('#semua_formulir_belum_p2').attr('aria-valuenow', persentase);
              }
            }
          });

          $.ajax({
            url: url_statistik,
            type: "GET",
            data: {
              tahun: tahun,
              pembayaran: 'SUDAH'
            },
            dataType: "JSON",
            async: false,
            success: function(response) {
              if (response.code == 200) {
                var persentase = Math.round(((100 * response.data) / $('#input_semua_formulir_total').val()))
                $('#semua_pembayaran_sudah').html(response.data)
                $('#semua_pembayaran_sudah_p').html(persentase + '%')

                $('#semua_pembayaran_sudah_p2').css({
                  "width": persentase + '%'
                });
                $('#semua_pembayaran_sudah_p2').attr('aria-valuenow', persentase);
              }
            }
          });

          $.ajax({
            url: url_statistik,
            type: "GET",
            data: {
              tahun: tahun,
              pembayaran: 'BELUM'
            },
            dataType: "JSON",
            async: false,
            success: function(response) {
              if (response.code == 200) {
                var persentase = Math.round(((100 * response.data) / $('#input_semua_formulir_total').val()))
                $('#semua_pembayaran_belum').html(response.data)
                $('#semua_pembayaran_belum_p').html(persentase + '%')

                $('#semua_pembayaran_belum_p2').css({
                  "width": persentase + '%'
                });
                $('#semua_pembayaran_belum_p2').attr('aria-valuenow', persentase);
              }
            }
          });

          $.ajax({
            url: url_statistik,
            type: "GET",
            data: {
              tahun: tahun,
              jenis_kelamin: 'LAKI-LAKI',
              formulir: 'SUDAH'
            },
            async: false,
            dataType: "JSON",
            success: function(response) {
              if (response.code == 200) {
                $('#semua_formulir_laki').html(response.data)
              }
            }
          });

          $.ajax({
            url: url_statistik,
            type: "GET",
            data: {
              tahun: tahun,
              jenis_kelamin: 'PEREMPUAN',
              formulir: 'SUDAH'
            },
            async: false,
            dataType: "JSON",
            success: function(response) {
              if (response.code == 200) {
                $('#semua_formulir_perempuan').html(response.data)
              }
            }
          });

          $.ajax({
            url: url_statistik,
            type: "GET",
            data: {
              tahun: tahun,
              kategori: 'IPA',
              formulir: 'SUDAH',
              jenjang: 'S1'
            },
            async: false,
            dataType: "JSON",
            success: function(response) {
              if (response.code == 200) {
                $('#semua_formulir_ipa').html(response.data)
              }
            }
          });

          $.ajax({
            url: url_statistik,
            type: "GET",
            data: {
              tahun: tahun,
              kategori: 'IPS',
              formulir: 'SUDAH',
              jenjang: 'S1'
            },
            async: false,
            dataType: "JSON",
            success: function(response) {
              if (response.code == 200) {
                $('#semua_formulir_ips').html(response.data)
              }
            }
          });

          $.ajax({
            url: url_kebutuhan_khusus,
            type: "GET",
            data: {
              tahun: tahun,
              formulir: 'SUDAH'
            },
            async: false,
            dataType: "JSON",
            success: function(response) {
              if (response.code == 200) {
                var num_keb_khusus = 0;
                $.each(response.data, function(i, v) {
                  if (v.keb_khusus != 'TIDAK BERKEBUTUHAN KHUSUS') {
                    num_keb_khusus += v.num;
                  }
                })
                $('#semua_formulir_abk').html(num_keb_khusus)
              } else {
                var num_keb_khusus = 0;
              }
            }
          });

          $.ajax({
            url: url_statistik,
            type: "GET",
            data: {
              tahun: tahun,
              pembayaran: 'SUDAH',
              jenjang: 'S1',
              ids_tipe_ujian_not: 31
            },
            async: false,
            dataType: "JSON",
            success: function(response) {
              if (response.code == 200) {
                var total_s1 = 250000 * response.data
                $.ajax({
                  url: url_statistik,
                  type: "GET",
                  data: {
                    tahun: tahun,
                    pembayaran: 'SUDAH',
                    jenjang: 'S2'
                  },
                  async: false,
                  dataType: "JSON",
                  success: function(response2) {
                    if (response2.code == 200) {
                      var total_s2 = ((tahun < '2025' && tahun !== null) ? 350000 : 500000) * response2.data
                      $.ajax({
                        url: url_statistik,
                        type: "GET",
                        data: {
                          tahun: tahun,
                          pembayaran: 'SUDAH',
                          jenjang: 'S3'
                        },
                        async: false,
                        dataType: "JSON",
                        success: function(response3) {
                          if (response3.code == 200) {
                            var total_s3 = ((tahun < '2025' && tahun !== null) ? 500000 : 750000) * response3.data
                            var total2 = total_s2 + total_s3 + total_s1
                            var total3 = Intl.NumberFormat("id-ID", {
                              currency: "IDR",
                              style: "currency",
                            }).format(total2);
                            $('#semua_pembayaran_nominal').html(total3)
                          }
                        }
                      });
                    }
                  }
                });
              }
            }
          });
        }).then(function() {
          $('.card-ujian-mandiri-semua').unblock()
        })
      }

      function load_statistik_semua_tipe_ujian(tahun = null) {
        $('#semuaTabelTipeUjian').DataTable().clear().destroy();
        var table_tipe_ujian = $('#semuaTabelTipeUjian').DataTable({
          processing: true,
          serverside: true,
          // info: false,
          ajax: {
            url: "<?= base_url('api/mandiri/statistik/m3/') ?>",
            type: "GET",
            data: {
              pembayaran: 'SUDAH',
              tahun: tahun
            },
            dataSrc: function(json) {
              return json.data
            }
          },
          columns: [{
              data: null
            },
            {
              data: 'tipe_ujian'
            },
            {
              data: 'num'
            },
          ],
          columnDefs: [{
            searchable: false,
            targets: 0
          }],
          order: [
            [2, 'desc']
          ],
        });
        table_tipe_ujian.on('order.dt search.dt', function() {
          table_tipe_ujian.column(0, {
            search: 'applied',
            order: 'applied'
          }).nodes().each(function(cell, i) {
            cell.innerHTML = i + 1;
          });
        }).draw();
      }

      function load_statistik_semua_jurusan(tahun = null) {
        $('#semuaTabelJurusan').DataTable().clear().destroy();
        var table_jurusan = $('#semuaTabelJurusan').DataTable({
          processing: true,
          serverside: true,
          // searching: false,
          // paging: false,
          // info: false,
          ajax: {
            url: "<?= base_url('api/mandiri/statistik/m10/') ?>",
            type: "GET",
            data: {
              pembayaran: 'SUDAH'
            },
            dataSrc: function(json) {
              var list = json.data;
              var list2 = list.sort((a, b) => b.total - a.total)
              var size = 10;
              var data = [];
              var items = list2.slice(0, size).map(i => {
                var value = {
                  jurusan: i.jurusan,
                  total: i.total
                }
                data.push(value)
              });
              return data
            }
          },
          pageLength: 10,
          columns: [{
              data: null
            },
            {
              data: 'jurusan'
            },
            {
              data: 'total'
            },
          ],
          columnDefs: [{
            searchable: false,
            targets: 0
          }],
          order: [
            [2, 'desc']
          ],
        });
        table_jurusan.on('order.dt search.dt', function() {
          table_jurusan.column(0, {
            search: 'applied',
            order: 'applied'
          }).nodes().each(function(cell, i) {
            cell.innerHTML = i + 1;
          });
        }).draw();
      }

      $('#tahun_statistik_mandiri').change(function () {
        loadingStatistikMandiri();
        load_statistik_mandiri_formulir($(this).val());
        load_statistik_mandiri_tipe_ujian($(this).val());
        load_statistik_mandiri_jurusan($(this).val());
      })

      function load_statistik_mandiri_formulir(tahun = null) {
        var url_statistik = "<?= base_url('api/mandiri/statistik/m1/') ?>";
        var url_kebutuhan_khusus = "<?= base_url('api/mandiri/statistik/m15/') ?>"
        $.ajax({
          url: url_statistik,
          type: "GET",
          data: {
            tahun: tahun,
            jenjang: 'S1',
            ids_tipe_ujian_not: 31
          },
          // async: false,
          dataType: "JSON",
          success: function(response) {
            if (response.code == 200) {
              $('#input_mandiri_formulir_total').val(response.data)
              $('#mandiri_formulir_total').html(response.data)
            }
          }
        }).then(function() {
          $.ajax({
            url: url_statistik,
            type: "GET",
            data: {
              tahun: tahun,
              formulir: 'SUDAH',
              jenjang: 'S1',
              ids_tipe_ujian_not: 31
            },
            async: false,
            dataType: "JSON",
            success: function(response) {
              if (response.code == 200) {
                var persentase = Math.round(((100 * response.data) / $('#input_mandiri_formulir_total').val()))
                $('#mandiri_formulir_sudah').html(response.data)
                $('#mandiri_formulir_sudah_p').html(persentase + '%')

                $('#mandiri_formulir_sudah_p2').css({
                  "width": persentase + '%'
                });
                $('#mandiri_formulir_sudah_p2').attr('aria-valuenow', persentase);
              }
            }
          });

          $.ajax({
            url: url_statistik,
            type: "GET",
            data: {
              tahun: tahun,
              formulir: 'BELUM',
              jenjang: 'S1',
              ids_tipe_ujian_not: 31
            },
            async: false,
            dataType: "JSON",
            success: function(response) {
              if (response.code == 200) {
                var persentase = Math.round(((100 * response.data) / $('#input_mandiri_formulir_total').val()))
                $('#mandiri_formulir_belum').html(response.data)
                $('#mandiri_formulir_belum_p').html(persentase + '%')

                $('#mandiri_formulir_belum_p2').css({
                  "width": persentase + '%'
                });
                $('#mandiri_formulir_belum_p2').attr('aria-valuenow', persentase);
              }
            }
          });

          $.ajax({
            url: url_statistik,
            type: "GET",
            data: {
              tahun: tahun,
              pembayaran: 'SUDAH',
              jenjang: 'S1',
              ids_tipe_ujian_not: 31
            },
            async: false,
            dataType: "JSON",
            success: function(response) {
              if (response.code == 200) {
                var persentase = Math.round(((100 * response.data) / $('#input_mandiri_formulir_total').val()))
                $('#mandiri_pembayaran_sudah').html(response.data)
                $('#mandiri_pembayaran_sudah_p').html(persentase + '%')

                $('#mandiri_pembayaran_sudah_p2').css({
                  "width": persentase + '%'
                });
                $('#mandiri_pembayaran_sudah_p2').attr('aria-valuenow', persentase);
              }
            }
          });

          $.ajax({
            url: url_statistik,
            type: "GET",
            data: {
              tahun: tahun,
              pembayaran: 'BELUM',
              jenjang: 'S1',
              ids_tipe_ujian_not: 31
            },
            async: false,
            dataType: "JSON",
            success: function(response) {
              if (response.code == 200) {
                var persentase = Math.round(((100 * response.data) / $('#input_mandiri_formulir_total').val()))
                $('#mandiri_pembayaran_belum').html(response.data)
                $('#mandiri_pembayaran_belum_p').html(persentase + '%')

                $('#mandiri_pembayaran_belum_p2').css({
                  "width": persentase + '%'
                });
                $('#mandiri_pembayaran_belum_p2').attr('aria-valuenow', persentase);
              }
            }
          });

          $.ajax({
            url: url_statistik,
            type: "GET",
            data: {
              tahun: tahun,
              jenis_kelamin: 'LAKI-LAKI',
              formulir: 'SUDAH',
              jenjang: 'S1',
              ids_tipe_ujian_not: 31
            },
            async: false,
            dataType: "JSON",
            success: function(response) {
              if (response.code == 200) {
                $('#mandiri_formulir_laki').html(response.data)
              }
            }
          });

          $.ajax({
            url: url_statistik,
            type: "GET",
            data: {
              tahun: tahun,
              jenis_kelamin: 'PEREMPUAN',
              formulir: 'SUDAH',
              jenjang: 'S1',
              ids_tipe_ujian_not: 31
            },
            async: false,
            dataType: "JSON",
            success: function(response) {
              if (response.code == 200) {
                $('#mandiri_formulir_perempuan').html(response.data)
              }
            }
          });

          $.ajax({
            url: url_statistik,
            type: "GET",
            data: {
              tahun: tahun,
              kategori: 'IPA',
              formulir: 'SUDAH',
              jenjang: 'S1',
              ids_tipe_ujian_not: 31
            },
            async: false,
            dataType: "JSON",
            success: function(response) {
              if (response.code == 200) {
                $('#mandiri_formulir_ipa').html(response.data)
              }
            }
          });

          $.ajax({
            url: url_statistik,
            type: "GET",
            data: {
              tahun: tahun,
              kategori: 'IPS',
              formulir: 'SUDAH',
              jenjang: 'S1',
              ids_tipe_ujian_not: 31
            },
            async: false,
            dataType: "JSON",
            success: function(response) {
              if (response.code == 200) {
                $('#mandiri_formulir_ips').html(response.data)
              }
            }
          });

          $.ajax({
            url: url_kebutuhan_khusus,
            type: "GET",
            data: {
              tahun: tahun,
              formulir: 'SUDAH',
              jenjang: 'S1',
              ids_tipe_ujian_not: 31
            },
            async: false,
            dataType: "JSON",
            success: function(response) {
              if (response.code == 200) {
                var num_keb_khusus = 0;
                $.each(response.data, function(i, v) {
                  if (v.keb_khusus != 'TIDAK BERKEBUTUHAN KHUSUS') {
                    num_keb_khusus += v.num;
                  }
                })
                $('#mandiri_formulir_abk').html(num_keb_khusus)
              } else {
                var num_keb_khusus = 0;
              }
            }
          });

          $.ajax({
            url: url_statistik,
            type: "GET",
            data: {
              tahun: tahun,
              pembayaran: 'SUDAH',
              jenjang: 'S1',
              ids_tipe_ujian_not: 31
            },
            async: false,
            dataType: "JSON",
            success: function(response) {
              if (response.code == 200) {
                var total = 250000 * response.data
                var total2 = Intl.NumberFormat("id-ID", {
                  currency: "IDR",
                  style: "currency",
                }).format(total);
                $('#mandiri_pembayaran_nominal').html(total2)
              }
            }
          });
        }).then(function() {
          $('.card-ujian-mandiri-mandiri').unblock()
        })
      }

      function load_statistik_mandiri_tipe_ujian(tahun = null) {
        $('#mandiriTabelTipeUjian').DataTable().clear().destroy();
        var table_tipe_ujian = $('#mandiriTabelTipeUjian').DataTable({
          processing: true,
          serverside: true,
          // info: false,
          ajax: {
            url: "<?= base_url('api/mandiri/statistik/m3/') ?>",
            type: "GET",
            data: {
              pembayaran: 'SUDAH',
              jenjang: 'S1',
              ids_tipe_ujian_not: 31,
              tahun: tahun
            },
            dataSrc: function(json) {
              return json.data
            }
          },
          columns: [{
              data: null
            },
            {
              data: 'tipe_ujian'
            },
            {
              data: 'num'
            },
          ],
          columnDefs: [{
            searchable: false,
            targets: 0
          }],
          order: [
            [2, 'desc']
          ],
        });
        table_tipe_ujian.on('order.dt search.dt', function() {
          table_tipe_ujian.column(0, {
            search: 'applied',
            order: 'applied'
          }).nodes().each(function(cell, i) {
            cell.innerHTML = i + 1;
          });
        }).draw();
      }

      function load_statistik_mandiri_jurusan(tahun = null) {
        $('#mandiriTabelJurusan').DataTable().clear().destroy();
        var table_jurusan = $('#mandiriTabelJurusan').DataTable({
          processing: true,
          serverside: true,
          // searching: false,
          // paging: false,
          // info: false,
          ajax: {
            url: "<?= base_url('api/mandiri/statistik/m10/') ?>",
            type: "GET",
            data: {
              pembayaran: 'SUDAH',
              jenjang: 'S1',
              ids_tipe_ujian_not: 31,
              tahun: tahun
            },
            dataSrc: function(json) {
              var list = json.data;
              var list2 = list.sort((a, b) => b.total - a.total)
              var size = 10;
              var data = [];
              var items = list2.slice(0, size).map(i => {
                var value = {
                  jurusan: i.jurusan,
                  total: i.total
                }
                data.push(value)
              });
              return data
            }
          },
          pageLength: 10,
          columns: [{
              data: null
            },
            {
              data: 'jurusan'
            },
            {
              data: 'total'
            },
          ],
          columnDefs: [{
            searchable: false,
            targets: 0
          }],
          order: [
            [2, 'desc']
          ],
        });
        table_jurusan.on('order.dt search.dt', function() {
          table_jurusan.column(0, {
            search: 'applied',
            order: 'applied'
          }).nodes().each(function(cell, i) {
            cell.innerHTML = i + 1;
          });
        }).draw();
      }

      $('#tahun_statistik_internasional').change(function () {
        load_statistik_internasional_formulir($(this).val());
        load_statistik_internasional_tipe_ujian($(this).val());
        load_statistik_internasional_jurusan($(this).val());
      })

      function load_statistik_internasional_formulir(tahun = null) {
        var url_statistik = "<?= base_url('api/mandiri/statistik/m1/') ?>";
        var url_kebutuhan_khusus = "<?= base_url('api/mandiri/statistik/m15/') ?>"
        $('.card-ujian-mandiri-internasional').block({
          message: '<div class="d-flex justify-content-center align-items-center"><div class="sk-chase"><div class="sk-chase-dot"></div><div class="sk-chase-dot"></div><div class="sk-chase-dot"></div><div class="sk-chase-dot"></div><div class="sk-chase-dot"></div><div class="sk-chase-dot"></div></div>&nbsp;&nbsp;&nbsp;&nbsp;<p class="mb-0">Tunggu sebentar...</p></div>',
          css: {
            backgroundColor: 'transparent',
            color: '#fff',
            border: '0'
          },
          overlayCSS: {
            opacity: 0.5
          }
        });
        $.ajax({
          url: url_statistik,
          type: "GET",
          data: {
            tahun: tahun,
            jenjang: 'S1',
            ids_tipe_ujian: 31
          },
          // async: false,
          dataType: "JSON",
          success: function(response) {
            if (response.code == 200) {
              $('#input_internasional_formulir_total').val(response.data)
              $('#international_admission_formulir_total').html(response.data)
            }
          }
        }).then(function() {
          $.ajax({
            url: url_statistik,
            type: "GET",
            data: {
              tahun: tahun,
              formulir: 'SUDAH',
              jenjang: 'S1',
              ids_tipe_ujian: 31
            },
            async: false,
            dataType: "JSON",
            success: function(response) {
              if (response.code == 200) {
                var persentase = Math.round(((100 * response.data) / $('#input_internasional_formulir_total').val()))
                $('#international_admission_formulir_sudah').html(response.data)
                $('#international_admission_formulir_sudah_p').html(persentase + '%')

                $('#international_admission_formulir_sudah_p2').css({
                  "width": persentase + '%'
                });
                $('#international_admission_formulir_sudah_p2').attr('aria-valuenow', persentase);
              }
            }
          });

          $.ajax({
            url: url_statistik,
            type: "GET",
            data: {
              tahun: tahun,
              formulir: 'BELUM',
              jenjang: 'S1',
              ids_tipe_ujian: 31
            },
            async: false,
            dataType: "JSON",
            success: function(response) {
              if (response.code == 200) {
                var persentase = Math.round(((100 * response.data) / $('#input_internasional_formulir_total').val()))
                $('#international_admission_formulir_belum').html(response.data)
                $('#international_admission_formulir_belum_p').html(persentase + '%')

                $('#international_admission_formulir_belum_p2').css({
                  "width": persentase + '%'
                });
                $('#international_admission_formulir_belum_p2').attr('aria-valuenow', persentase);
              }
            }
          });

          $.ajax({
            url: url_statistik,
            type: "GET",
            data: {
              tahun: tahun,
              pembayaran: 'SUDAH',
              jenjang: 'S1',
              ids_tipe_ujian: 31
            },
            async: false,
            dataType: "JSON",
            success: function(response) {
              if (response.code == 200) {
                var persentase = Math.round(((100 * response.data) / $('#input_internasional_formulir_total').val()))
                $('#international_admission_pembayaran_sudah').html(response.data)
                $('#international_admission_pembayaran_sudah_p').html(persentase + '%')

                $('#international_admission_pembayaran_sudah_p2').css({
                  "width": persentase + '%'
                });
                $('#international_admission_pembayaran_sudah_p2').attr('aria-valuenow', persentase);
              }
            }
          });

          $.ajax({
            url: url_statistik,
            type: "GET",
            data: {
              tahun: tahun,
              pembayaran: 'BELUM',
              jenjang: 'S1',
              ids_tipe_ujian: 31
            },
            async: false,
            dataType: "JSON",
            success: function(response) {
              if (response.code == 200) {
                var persentase = Math.round(((100 * response.data) / $('#input_internasional_formulir_total').val()))
                $('#international_admission_pembayaran_belum').html(response.data)
                $('#international_admission_pembayaran_belum_p').html(persentase + '%')

                $('#international_admission_pembayaran_belum_p2').css({
                  "width": persentase + '%'
                });
                $('#international_admission_pembayaran_belum_p2').attr('aria-valuenow', persentase);
              }
            }
          });

          $.ajax({
            url: url_statistik,
            type: "GET",
            data: {
              tahun: tahun,
              jenis_kelamin: 'LAKI-LAKI',
              formulir: 'SUDAH',
              jenjang: 'S1',
              ids_tipe_ujian: 31
            },
            async: false,
            dataType: "JSON",
            success: function(response) {
              if (response.code == 200) {
                $('#international_admission_formulir_laki').html(response.data)
              }
            }
          });

          $.ajax({
            url: url_statistik,
            type: "GET",
            data: {
              tahun: tahun,
              jenis_kelamin: 'PEREMPUAN',
              formulir: 'SUDAH',
              jenjang: 'S1',
              ids_tipe_ujian: 31
            },
            async: false,
            dataType: "JSON",
            success: function(response) {
              if (response.code == 200) {
                $('#international_admission_formulir_perempuan').html(response.data)
              }
            }
          });

          $.ajax({
            url: url_statistik,
            type: "GET",
            data: {
              tahun: tahun,
              kategori: 'IPA',
              formulir: 'SUDAH',
              jenjang: 'S1',
              ids_tipe_ujian: 31
            },
            async: false,
            dataType: "JSON",
            success: function(response) {
              if (response.code == 200) {
                $('#international_admission_formulir_ipa').html(response.data)
              }
            }
          });

          $.ajax({
            url: url_statistik,
            type: "GET",
            data: {
              tahun: tahun,
              kategori: 'IPS',
              formulir: 'SUDAH',
              jenjang: 'S1',
              ids_tipe_ujian: 31
            },
            async: false,
            dataType: "JSON",
            success: function(response) {
              if (response.code == 200) {
                $('#international_admission_formulir_ips').html(response.data)
              }
            }
          });

          $.ajax({
            url: url_kebutuhan_khusus,
            type: "GET",
            data: {
              tahun: tahun,
              formulir: 'SUDAH',
              jenjang: 'S1',
              ids_tipe_ujian: 31
            },
            async: false,
            dataType: "JSON",
            success: function(response) {
              if (response.code == 200) {
                var num_keb_khusus = 0;
                $.each(response.data, function(i, v) {
                  if (v.keb_khusus != 'TIDAK BERKEBUTUHAN KHUSUS') {
                    num_keb_khusus += v.num;
                  }
                })
                $('#international_admission_formulir_abk').html(num_keb_khusus)
              } else {
                var num_keb_khusus = 0;
              }
            }
          });

          $.ajax({
            url: url_statistik,
            type: "GET",
            data: {
              tahun: tahun,
              pembayaran: 'SUDAH',
              jenjang: 'S1',
              ids_tipe_ujian: 31
            },
            async: false,
            dataType: "JSON",
            success: function(response) {
              if (response.code == 200) {
                var total = 250000 * response.data
                var total2 = Intl.NumberFormat("id-ID", {
                  currency: "IDR",
                  style: "currency",
                }).format(total);
                $('#international_admission_pembayaran_nominal').html(total2)
              }
            }
          });
        }).then(function() {
          $('.card-ujian-mandiri-internasional').unblock()
        })
      }

      function load_statistik_internasional_tipe_ujian(tahun = null) {
        $('#internasionalTabelTipeUjian').DataTable().clear().destroy();
        var table_tipe_ujian = $('#internasionalTabelTipeUjian').DataTable({
          processing: true,
          serverside: true,
          // info: false,
          ajax: {
            url: "<?= base_url('api/mandiri/statistik/m3/') ?>",
            type: "GET",
            data: {
              pembayaran: 'SUDAH',
              jenjang: 'S1',
              ids_tipe_ujian: 31,
              tahun: tahun
            },
            dataSrc: function(json) {
              return json.data
            }
          },
          columns: [{
              data: null
            },
            {
              data: 'tipe_ujian'
            },
            {
              data: 'num'
            },
          ],
          columnDefs: [{
            searchable: false,
            targets: 0
          }],
          order: [
            [2, 'desc']
          ],
        });
        table_tipe_ujian.on('order.dt search.dt', function() {
          table_tipe_ujian.column(0, {
            search: 'applied',
            order: 'applied'
          }).nodes().each(function(cell, i) {
            cell.innerHTML = i + 1;
          });
        }).draw();
      }

      function load_statistik_internasional_jurusan(tahun = null) {
        $('#internasionalTabelJurusan').DataTable().clear().destroy();
        var table_jurusan = $('#internasionalTabelJurusan').DataTable({
          processing: true,
          serverside: true,
          // searching: false,
          // paging: false,
          // info: false,
          ajax: {
            url: "<?= base_url('api/mandiri/statistik/m10/') ?>",
            type: "GET",
            data: {
              pembayaran: 'SUDAH',
              ids_tipe_ujian: 31,
              tahun: tahun
            },
            dataSrc: function(json) {
              var list = json.data;
              var list2 = list.sort((a, b) => b.total - a.total)
              var size = 10;
              var data = [];
              var items = list2.slice(0, size).map(i => {
                var value = {
                  jurusan: i.jurusan,
                  total: i.total
                }
                data.push(value)
              });
              return data
            }
          },
          pageLength: 10,
          columns: [{
              data: null
            },
            {
              data: 'jurusan'
            },
            {
              data: 'total'
            },
          ],
          columnDefs: [{
            searchable: false,
            targets: 0
          }],
          order: [
            [2, 'desc']
          ],
        });
        table_jurusan.on('order.dt search.dt', function() {
          table_jurusan.column(0, {
            search: 'applied',
            order: 'applied'
          }).nodes().each(function(cell, i) {
            cell.innerHTML = i + 1;
          });
        }).draw();
      }

      $('#tahun_statistik_pascasarjana').change(function () {
        load_statistik_pascasarjana_formulir($(this).val());
        load_statistik_pascasarjana_tipe_ujian($(this).val());
        load_statistik_pascasarjana_jurusan($(this).val());
      })

      function load_statistik_pascasarjana_formulir(tahun = null) {
        var url_statistik = "<?= base_url('api/mandiri/statistik/m1/') ?>";
        var url_kebutuhan_khusus = "<?= base_url('api/mandiri/statistik/m15/') ?>"
        $('#card-ujian-mandiri-pascasarjana').block({
          message: '<div class="d-flex justify-content-center align-items-center"><div class="sk-chase"><div class="sk-chase-dot"></div><div class="sk-chase-dot"></div><div class="sk-chase-dot"></div><div class="sk-chase-dot"></div><div class="sk-chase-dot"></div><div class="sk-chase-dot"></div></div>&nbsp;&nbsp;&nbsp;&nbsp;<p class="mb-0">Tunggu sebentar...</p></div>',
          css: {
            backgroundColor: 'transparent',
            color: '#fff',
            border: '0'
          },
          overlayCSS: {
            opacity: 0.5
          }
        });
        $.ajax({
          url: url_statistik,
          type: "GET",
          data: {
            tahun: tahun,
            jenjang_not: 'S1'
          },
          // async: false,
          dataType: "JSON",
          success: function(response) {
            if (response.code == 200) {
              $('#input_pascasarjana_formulir_total').val(response.data)
              $('#pascasarjana_formulir_total').html(response.data)
            }
          }
        }).then(function() {
          $.ajax({
            url: url_statistik,
            type: "GET",
            data: {
              tahun: tahun,
              formulir: 'SUDAH',
              jenjang_not: 'S1'
            },
            async: false,
            dataType: "JSON",
            success: function(response) {
              if (response.code == 200) {
                var persentase = Math.round(((100 * response.data) / $('#input_pascasarjana_formulir_total').val()))
                $('#pascasarjana_formulir_sudah').html(response.data)
                $('#pascasarjana_formulir_sudah_p').html(persentase + '%')

                $('#pascasarjana_formulir_sudah_p2').css({
                  "width": persentase + '%'
                });
                $('#pascasarjana_formulir_sudah_p2').attr('aria-valuenow', persentase);
              }
            }
          });

          $.ajax({
            url: url_statistik,
            type: "GET",
            data: {
              tahun: tahun,
              formulir: 'BELUM',
              jenjang_not: 'S1'
            },
            async: false,
            dataType: "JSON",
            success: function(response) {
              if (response.code == 200) {
                var persentase = Math.round(((100 * response.data) / $('#input_pascasarjana_formulir_total').val()))
                $('#pascasarjana_formulir_belum').html(response.data)
                $('#pascasarjana_formulir_belum_p').html(persentase + '%')

                $('#pascasarjana_formulir_belum_p2').css({
                  "width": persentase + '%'
                });
                $('#pascasarjana_formulir_belum_p2').attr('aria-valuenow', persentase);
              }
            }
          });

          $.ajax({
            url: url_statistik,
            type: "GET",
            data: {
              tahun: tahun,
              pembayaran: 'SUDAH',
              jenjang_not: 'S1'
            },
            async: false,
            dataType: "JSON",
            success: function(response) {
              if (response.code == 200) {
                var persentase = Math.round(((100 * response.data) / $('#input_pascasarjana_formulir_total').val()))
                $('#pascasarjana_pembayaran_sudah').html(response.data)
                $('#pascasarjana_pembayaran_sudah_p').html(persentase + '%')

                $('#pascasarjana_pembayaran_sudah_p2').css({
                  "width": persentase + '%'
                });
                $('#pascasarjana_pembayaran_sudah_p2').attr('aria-valuenow', persentase);
              }
            }
          });

          $.ajax({
            url: url_statistik,
            type: "GET",
            data: {
              tahun: tahun,
              pembayaran: 'BELUM',
              jenjang_not: 'S1'
            },
            async: false,
            dataType: "JSON",
            success: function(response) {
              if (response.code == 200) {
                var persentase = Math.round(((100 * response.data) / $('#input_pascasarjana_formulir_total').val()))
                $('#pascasarjana_pembayaran_belum').html(response.data)
                $('#pascasarjana_pembayaran_belum_p').html(persentase + '%')

                $('#pascasarjana_pembayaran_belum_p2').css({
                  "width": persentase + '%'
                });
                $('#pascasarjana_pembayaran_belum_p2').attr('aria-valuenow', persentase);
              }
            }
          });

          $.ajax({
            url: url_statistik,
            type: "GET",
            data: {
              tahun: tahun,
              jenis_kelamin: 'LAKI-LAKI',
              formulir: 'SUDAH',
              jenjang_not: 'S1'
            },
            async: false,
            dataType: "JSON",
            success: function(response) {
              if (response.code == 200) {
                $('#pascasarjana_formulir_laki').html(response.data)
              }
            }
          });

          $.ajax({
            url: url_statistik,
            type: "GET",
            data: {
              tahun: tahun,
              jenis_kelamin: 'PEREMPUAN',
              formulir: 'SUDAH',
              jenjang_not: 'S1'
            },
            async: false,
            dataType: "JSON",
            success: function(response) {
              if (response.code == 200) {
                $('#pascasarjana_formulir_perempuan').html(response.data)
              }
            }
          });

          $.ajax({
            url: url_statistik,
            type: "GET",
            data: {
              tahun: tahun,
              formulir: 'SUDAH',
              jenjang: 'S2'
            },
            async: false,
            dataType: "JSON",
            success: function(response) {
              if (response.code == 200) {
                $('#pascasarjana_formulir_s2').html(response.data)
              }
            }
          });

          $.ajax({
            url: url_statistik,
            type: "GET",
            data: {
              tahun: tahun,
              formulir: 'SUDAH',
              jenjang: 'S3'
            },
            async: false,
            dataType: "JSON",
            success: function(response) {
              if (response.code == 200) {
                $('#pascasarjana_formulir_s3').html(response.data)
              }
            }
          });

          $.ajax({
            url: url_kebutuhan_khusus,
            type: "GET",
            data: {
              tahun: tahun,
              formulir: 'SUDAH',
              jenjang_not: 'S1'
            },
            async: false,
            dataType: "JSON",
            success: function(response) {
              if (response.code == 200) {
                var num_keb_khusus = 0;
                $.each(response.data, function(i, v) {
                  if (v.keb_khusus != 'TIDAK BERKEBUTUHAN KHUSUS') {
                    num_keb_khusus += v.num;
                  }
                })
                $('#pascasarjana_formulir_abk').html(num_keb_khusus)
              } else {
                var num_keb_khusus = 0;
              }
            }
          });

          $.ajax({
            url: url_statistik,
            type: "GET",
            data: {
              tahun: tahun,
              pembayaran: 'SUDAH',
              jenjang: 'S2'
            },
            async: false,
            dataType: "JSON",
            success: function(response) {
              if (response.code == 200) {
                var total_s2 = ((tahun < '2025' && tahun !== null) ? 350000 : 500000) * response.data
                $.ajax({
                  url: url_statistik,
                  type: "GET",
                  data: {
                    tahun: tahun,
                    pembayaran: 'SUDAH',
                    jenjang: 'S3'
                  },
                  async: false,
                  dataType: "JSON",
                  success: function(response2) {
                    if (response2.code == 200) {
                      var total_s3 = ((tahun < '2025' && tahun !== null) ? 500000 : 750000) * response2.data
                      var total2 = total_s2 + total_s3
                      var total3 = Intl.NumberFormat("id-ID", {
                        currency: "IDR",
                        style: "currency",
                      }).format(total2);
                      $('#pascasarjana_pembayaran_nominal').html(total3)
                    }
                  }
                });
              }
            }
          });
        }).then(function() {
          $('#card-ujian-mandiri-pascasarjana').unblock()
        })
      }

      function load_statistik_pascasarjana_tipe_ujian(tahun = null) {
        $('#pascasarjanaTabelTipeUjian').DataTable().clear().destroy();
        var table_tipe_ujian = $('#pascasarjanaTabelTipeUjian').DataTable({
          processing: true,
          serverside: true,
          // info: false,
          ajax: {
            url: "<?= base_url('api/mandiri/statistik/m3/') ?>",
            type: "GET",
            data: {
              pembayaran: 'SUDAH',
              jenjang_not: 'S1',
              tahun: tahun
            },
            dataSrc: function(json) {
              return json.data
            }
          },
          columns: [{
              data: null
            },
            {
              data: 'tipe_ujian'
            },
            {
              data: 'num'
            },
          ],
          columnDefs: [{
            searchable: false,
            targets: 0
          }],
          order: [
            [2, 'desc']
          ],
        });
        table_tipe_ujian.on('order.dt search.dt', function() {
          table_tipe_ujian.column(0, {
            search: 'applied',
            order: 'applied'
          }).nodes().each(function(cell, i) {
            cell.innerHTML = i + 1;
          });
        }).draw();
      }

      function load_statistik_pascasarjana_jurusan(tahun = null) {
        $('#pascasarjanaTabelJurusan').DataTable().clear().destroy();
        var table_jurusan = $('#pascasarjanaTabelJurusan').DataTable({
          processing: true,
          serverside: true,
          // searching: false,
          // paging: false,
          // info: false,
          ajax: {
            url: "<?= base_url('api/mandiri/statistik/m10/') ?>",
            type: "GET",
            data: {
              pembayaran: 'SUDAH',
              jenjang_not: 'S1',
              tahun: tahun
            },
            dataSrc: function(json) {
              var list = json.data;
              var list2 = list.sort((a, b) => b.total - a.total)
              var size = 10;
              var data = [];
              var items = list2.slice(0, size).map(i => {
                var value = {
                  jurusan: i.jurusan,
                  total: i.total
                }
                data.push(value)
              });
              return data
            }
          },
          pageLength: 10,
          columns: [{
              data: null
            },
            {
              data: 'jurusan'
            },
            {
              data: 'total'
            },
          ],
          columnDefs: [{
            searchable: false,
            targets: 0
          }],
          order: [
            [2, 'desc']
          ],
        });
        table_jurusan.on('order.dt search.dt', function() {
          table_jurusan.column(0, {
            search: 'applied',
            order: 'applied'
          }).nodes().each(function(cell, i) {
            cell.innerHTML = i + 1;
          });
        }).draw();
      }

      $('#tahun_statistik_daya_tampung').change(function () {
        load_statistik_daya_tampung($(this).val());
      })

      function load_statistik_daya_tampung(tahun = null) {
        $('#card-daya-tampung').block({
          message: '<div class="d-flex justify-content-center align-items-center"><div class="sk-chase"><div class="sk-chase-dot"></div><div class="sk-chase-dot"></div><div class="sk-chase-dot"></div><div class="sk-chase-dot"></div><div class="sk-chase-dot"></div><div class="sk-chase-dot"></div></div>&nbsp;&nbsp;&nbsp;&nbsp;<p class="mb-0">Loading...</p></div>',
          css: {
            backgroundColor: 'transparent',
            color: '#fff',
            border: '0'
          },
          overlayCSS: {
            opacity: 0.5
          }
        });
        $.ajax({
          type: 'GET',
          url: '<?= base_url('api/daftar/statistik/d10/') ?>',
          data: {
            tahun: tahun
          },
          dataType: 'json',
          success: function(response) {
            // This will clear table of the old data other than the header row
            $('#daya_tampung_snbp').html(response.persentase.snbp.daya_tampung)
            $('#terisi_snbp').html(response.persentase.snbp.terisi)
            $('#persentase_snbp').html(response.persentase.snbp.persentase)
            $('#daya_tampung_spanptkin').html(response.persentase.spanptkin.daya_tampung)
            $('#terisi_spanptkin').html(response.persentase.spanptkin.terisi)
            $('#persentase_spanptkin').html(response.persentase.spanptkin.persentase)
            $('#daya_tampung_snbt').html(response.persentase.snbt.daya_tampung)
            $('#terisi_snbt').html(response.persentase.snbt.terisi)
            $('#persentase_snbt').html(response.persentase.snbt.persentase)
            $('#daya_tampung_umptkin').html(response.persentase.umptkin.daya_tampung)
            $('#terisi_umptkin').html(response.persentase.umptkin.terisi)
            $('#persentase_umptkin').html(response.persentase.umptkin.persentase)
            $('#daya_tampung_mandiri').html(response.persentase.mandiri.daya_tampung)
            $('#terisi_mandiri').html(response.persentase.mandiri.terisi)
            $('#persentase_mandiri').html(response.persentase.mandiri.persentase)
            $('#daya_tampung_pbsb').html(response.persentase.pbsb.daya_tampung)
            $('#terisi_pbsb').html(response.persentase.pbsb.terisi)
            $('#persentase_pbsb').html(response.persentase.pbsb.persentase)
            $('#daya_tampung_mandiriprestasi').html(response.persentase.mandiriprestasi.daya_tampung)
            $('#terisi_mandiriprestasi').html(response.persentase.mandiriprestasi.terisi)
            $('#persentase_mandiriprestasi').html(response.persentase.mandiriprestasi.persentase)
            $('#daya_tampung_total').html(response.persentase.total.daya_tampung)
            $('#terisi_total').html(response.persentase.total.terisi)
            $('#persentase_total').html(response.persentase.total.persentase)
            var trHTML = '';
            $('#mandiriTabelDayaTampung tBody').empty();
            $.each(response.data, function(i, item) {
              trHTML +=
                '<tr><td>' +
                item.kode_jurusan +
                '</td><td>' +
                item.jurusan +
                '</td><td>' +
                item.fakultas +
                '</td><td class="text-center">' +
                item.daya_tampung +
                '</td><td class="text-center">' +
                (parseInt(item.kuota) + parseInt(item.afirmasi)) +
                '</td><td class="text-center">' +
                item.jalur_masuk.snbp.daya_tampung +
                '</td><td class="text-center">' +
                item.jalur_masuk.snbp.terisi +
                '</td><td class="text-center">' +
                item.jalur_masuk.snbp.persentase +
                '</td><td class="text-center">' +
                item.jalur_masuk.spanptkin.daya_tampung +
                '</td><td class="text-center">' +
                item.jalur_masuk.spanptkin.terisi +
                '</td><td class="text-center">' +
                item.jalur_masuk.spanptkin.persentase +
                '</td><td class="text-center">' +
                item.jalur_masuk.snbt.daya_tampung +
                '</td><td class="text-center">' +
                item.jalur_masuk.snbt.terisi +
                '</td><td class="text-center">' +
                item.jalur_masuk.snbt.persentase +
                '</td><td class="text-center">' +
                item.jalur_masuk.umptkin.daya_tampung +
                '</td><td class="text-center">' +
                item.jalur_masuk.umptkin.terisi +
                '</td><td class="text-center">' +
                item.jalur_masuk.umptkin.persentase +
                '</td><td class="text-center">' +
                item.jalur_masuk.mandiri.daya_tampung +
                '</td><td class="text-center">' +
                item.jalur_masuk.mandiri.terisi +
                '</td><td class="text-center">' +
                item.jalur_masuk.mandiri.persentase +
                '</td><td class="text-center">' +
                item.jalur_masuk.mandiriprestasi.daya_tampung +
                '</td><td class="text-center">' +
                item.jalur_masuk.mandiriprestasi.terisi +
                '</td><td class="text-center">' +
                item.jalur_masuk.mandiriprestasi.persentase +
                '</td><td class="text-center">' +
                item.jalur_masuk.pbsb.daya_tampung +
                '</td><td class="text-center">' +
                item.jalur_masuk.pbsb.terisi +
                '</td><td class="text-center">' +
                item.jalur_masuk.pbsb.persentase +
                '</td><td class="text-center">' +
                item.jalur_masuk.total.daya_tampung +
                '</td><td class="text-center">' +
                item.jalur_masuk.total.terisi +
                '</td><td class="text-center">' +
                item.jalur_masuk.total.persentase +
                '</td></tr>';
            });
            $('#mandiriTabelDayaTampung tBody').append(trHTML);
            $('#mandiriTabelDayaTampung').DataTable();
            $('#card-daya-tampung').unblock();
          },
          error: function() {
            $('#card-daya-tampung').unblock();
          }
        });
      }
    </script>
  <?php endif; ?>
<?php endif; ?>