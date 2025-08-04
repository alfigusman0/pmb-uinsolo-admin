<script src="<?= base_url() ?>assets/libs/datatables/jquery.dataTables.js"></script>
<script src="<?= base_url() ?>assets/libs/datatables-bs5/datatables-bootstrap5.js"></script>
<script src="<?= base_url() ?>assets/libs/toastr/toastr.js"></script>
<script src="<?= base_url() ?>assets/libs/sweetalert2/sweetalert2.js"></script>
<script src="<?=base_url()?>assets/libs/block-ui/block-ui.js"></script>
<script>
  var table_jurusan, table_fakultas, table_jalurmasuk, table_negara, table_provinsi, table_kabkota, table_kecamatan, table_kelurahan;

  $(document).ready(function() {
    $('#container-statistik').show();
    load_data();

    var toggleStatusDayaTampung = false;
    var intervalDayaTampung;

    $("#toggleButtonDayaTampung").click(function() {
      toggleStatusDayaTampung = !toggleStatusDayaTampung;

      if (toggleStatusDayaTampung) {
        // Do something when toggle is ON
        $("#toggleButtonDayaTampung").removeClass("btn-danger").addClass("btn-success").html("<i class='bx bx-refresh'>&nbsp;</i>Refresh On");

        // Set interval for toggle off after 3 seconds
        intervalDayaTampung = setInterval(function() {
          load_statistik_daya_tampung();
        }, 60000);
      } else {
        // Do something when toggle is OFF
        $("#toggleButtonDayaTampung").removeClass("btn-success").addClass("btn-danger").html("<i class='bx bx-refresh'>&nbsp;</i>Refresh Off");

        // Clear interval if toggle off manually
        clearInterval(intervalDayaTampung);
      }
    });
  });

  // setInterval(statistik_perfakultas, 5000);

  function load_data()
  {
    load_statistik_daya_tampung();
    load_data_perfakultas();
    load_data_perjurusan();
    load_data_perjalurmasuk();
    load_data_pernegara();
    load_data_perprovinsi();
    load_data_perkabkota();
    load_data_perkecamatan();
    load_data_perkelurahan();
  }

  function load_statistik_daya_tampung(){
    $('#card-daya-tampung').block({
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
      type: 'GET',
      url: '<?=base_url('api/daftar/statistik/d9/')?>',
      dataType: 'json',
      success: function(response) {
        // This will clear table of the old data other than the header row
        var trHTML = '';
        var total_terisi_snbp = 0;
        var total_terisi_snbt = 0;
        var total_terisi_span = 0;
        var total_terisi_umptkin = 0;
        var total_terisi_mandiri = 0;
        var total_terisi_total = 0;
        $('#mandiriTabelDayaTampung tbody').empty();
        var num = 1;
        $.each(response.data, function (i, item) {
          total_terisi_snbp += item.terisi_snbp
          total_terisi_snbt += item.terisi_snbt
          total_terisi_span += item.terisi_span
          total_terisi_umptkin += item.terisi_umptkin
          total_terisi_mandiri += item.terisi_mandiri
          total_terisi_total += item.total_terisi
          trHTML +=
                  '<tr><td class="text-center">'
                  + num
                  + '</td><td>'
                  + item.jurusan
                  + '</td><td>'
                  + item.fakultas
                  + '</td><td>'
                  + item.daya_tampung
                  + '</td><td class="text-center">'
                  + item.terisi_snbp
                  + '</td><td class="text-center">'
                  + '<span class="badge bg-label-primary">'+Math.round((item.terisi_snbp/item.daya_tampung)*100)+'%</span>'
                  + '</td><td class="text-center">'
                  + item.terisi_span
                  + '</td><td class="text-center">'
                  + '<span class="badge bg-label-secondary">'+Math.round((item.terisi_span/item.daya_tampung)*100)+'%</span>'
                  + '</td><td class="text-center">'
                  + item.terisi_snbt
                  + '</td><td class="text-center">'
                  + '<span class="badge bg-label-success">'+Math.round((item.terisi_snbt/item.daya_tampung)*100)+'%</span>'
                  + '</td><td class="text-center">'
                  + item.terisi_umptkin
                  + '</td><td class="text-center">'
                  + '<span class="badge bg-label-danger">'+Math.round((item.terisi_umptkin/item.daya_tampung)*100)+'%</span>'
                  + '</td><td class="text-center">'
                  + item.terisi_mandiri
                  + '</td><td class="text-center">'
                  + '<span class="badge bg-label-warning">'+Math.round((item.terisi_mandiri/item.daya_tampung)*100)+'%</span>'
                  + '</td><td class="text-center">'
                  + item.total_terisi
                  + '</td><td class="text-center">'
                  + '<span class="badge bg-label-info">'+Math.round((item.total_terisi/item.daya_tampung)*100)+'%</span>'
                  + '</td></tr>';
              num++;
        });
        $('#persen_snbp').html(Math.round((total_terisi_snbp/response.total)*100)+"%");
        $('#persen_span').html(Math.round((total_terisi_span/response.total)*100)+"%");
        $('#persen_snbt').html(Math.round((total_terisi_snbt/response.total)*100)+"%");
        $('#persen_umptkin').html(Math.round((total_terisi_umptkin/response.total)*100)+"%");
        $('#persen_mandiri').html(Math.round((total_terisi_mandiri/response.total)*100)+"%");
        $('#persen_total').html(Math.round((total_terisi_total/response.total)*100)+"%");
        $('#mandiriTabelDayaTampung tbody').append(trHTML);
        $('#card-daya-tampung').unblock(); 
        $('#mandiriTabelDayaTampung').DataTable();
      }
    });
  }

  function load_data_perfakultas()
  {
    var daftar = $('#daftar-perfakultas').val();
    var submit = $('#submit-perfakultas').val();
    var pembayaran = $('#pembayaran-perfakultas').val();
    var tahun = $('#tahun-perfakultas').val();
    var parram = "?"

    if (daftar != "") {
      parram += "daftar=" + daftar + "&";
    }
    if (submit != "") {
      parram += "submit=" + submit + "&";
    }
    if (pembayaran != "") {
      parram += "pembayaran=" + pembayaran + "&";
    }
    if (tahun != "") {
      parram += "tahun=" + tahun + "&";
    }

    url_fakultas = "<?= base_url('api') ?>/daftar/statistik/d2/" + parram + "datatable=YA";
    
    table_fakultas = $('#dataTabelFakultas').DataTable({
      processing: true,
			serverside: true,
			ajax: {
        url: url_fakultas,
        type: "GET",
				dataSrc: function ( json ) {
					return json.data
				}
    	},
			columns: [
        { data: null },
        { data: 'fakultas' },
        { data: 'num' },
			],
      columnDefs: [
        {
          searchable: false,
					targets: 0
        }
			],
      order: [[2, 'desc']],
    });
    table_fakultas.on( 'order.dt search.dt', function () {
        table_fakultas.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
            cell.innerHTML = i+1;
        } );
    } ).draw();
  }
  
  function load_data_perjurusan()
  {
    var daftar = $('#daftar-perjurusan').val();
    var submit = $('#submit-perjurusan').val();
    var pembayaran = $('#pembayaran-perjurusan').val();
    var tahun = $('#tahun-perjurusan').val();
    var parram = "?"

    if (daftar != "") {
      parram += "daftar=" + daftar + "&";
    }
    if (submit != "") {
      parram += "submit=" + submit + "&";
    }
    if (pembayaran != "") {
      parram += "pembayaran=" + pembayaran + "&";
    }
    if (tahun != "") {
      parram += "tahun=" + tahun + "&";
    }

    url_jurusan = "<?= base_url('api') ?>/daftar/statistik/d3/" + parram + "datatable=YA";
    
    table_jurusan = $('#dataTabelJurusan').DataTable({
      processing: true,
			serverside: true,
			ajax: {
        url: url_jurusan,
        type: "GET",
				dataSrc: function ( json ) {
					return json.data
				}
    	},
			columns: [
        { data: null },
        { data: 'jurusan' },
        { data: 'num' },
			],
      columnDefs: [
        {
          searchable: false,
					targets: 0
        }
			],
      order: [[2, 'desc']],
    });
    table_jurusan.on( 'order.dt search.dt', function () {
        table_jurusan.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
            cell.innerHTML = i+1;
        } );
    } ).draw();
  }
  
  function load_data_perjalurmasuk()
  {
    var daftar = $('#daftar-perkelurahan').val();
    var submit = $('#submit-perkelurahan').val();
    var pembayaran = $('#pembayaran-perkelurahan').val();
    var tahun = $('#tahun-perkelurahan').val();
    var parram = "?"

    if (daftar != "") {
      parram += "daftar=" + daftar + "&";
    }
    if (submit != "") {
      parram += "submit=" + submit + "&";
    }
    if (pembayaran != "") {
      parram += "pembayaran=" + pembayaran + "&";
    }
    if (tahun != "") {
      parram += "tahun=" + tahun + "&";
    }
    
    url_jalurmasuk = "<?= base_url('api') ?>/daftar/statistik/d1/" + parram + "datatable=YA";
    
    table_jalurmasuk = $('#dataTabelJalurMasuk').DataTable({
      processing: true,
			serverside: true,
			ajax: {
        url: url_jalurmasuk,
        type: "GET",
				dataSrc: function ( json ) {
					return json.data
				}
    	},
			columns: [
        { data: null },
        { data: 'alias' },
        { data: 'num' },
			],
      columnDefs: [
        {
          searchable: false,
					targets: 0
        }
			],
      order: [[2, 'desc']],
    });
    table_jalurmasuk.on( 'order.dt search.dt', function () {
        table_jalurmasuk.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
            cell.innerHTML = i+1;
        } );
    } ).draw();
  }

  function load_data_pernegara()
  {
    var daftar = $('#daftar-pernegara').val();
    var submit = $('#submit-pernegara').val();
    var pembayaran = $('#pembayaran-pernegara').val();
    var tahun = $('#tahun-pernegara').val();
    var page = $('#page-pernegara').val();
    var limit = $('#limit-pernegara').val();
    var parram = "?"

    if (daftar != "") {
      parram += "daftar=" + daftar + "&";
    }
    if (submit != "") {
      parram += "submit=" + submit + "&";
    }
    if (pembayaran != "") {
      parram += "pembayaran=" + pembayaran + "&";
    }
    if (tahun != "") {
      parram += "tahun=" + tahun + "&";
    }
    if (page != "") {
      parram += "page=" + page + "&";
    }
    if (limit != "") {
      parram += "limit=" + limit + "&";
    }

    url_negara = "<?= base_url('api') ?>/daftar/statistik/d4/" + parram;
    
    table_negara = $('#dataTabelNegara').DataTable({
      processing: true,
      serverside: true,
      ajax: {
        url: url_negara,
        type: "GET",
        dataSrc: function ( json ) {
          $('#page-pernegara').attr('max', json.pagination.totalpagination);
          $('#page-pernegara').attr('value', json.pagination.currentpage);
          return json.data
        }
      },
      columns: [
        { data: null },
        { data: 'negara' },
        { data: 'num' },
      ],
      columnDefs: [
        {
          searchable: false,
          targets: 0
        }
      ],
      order: [[2, 'desc']],
    });
    table_negara.on( 'order.dt search.dt', function () {
        table_negara.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
            cell.innerHTML = i+1;
        } );
    } ).draw();
  }

  function load_data_perprovinsi()
  {
    var daftar = $('#daftar-perprovinsi').val();
    var submit = $('#submit-perprovinsi').val();
    var pembayaran = $('#pembayaran-perprovinsi').val();
    var tahun = $('#tahun-perprovinsi').val();
    var page = $('#page-perprovinsi').val();
    var limit = $('#limit-perprovinsi').val();
    var parram = "?"

    if (daftar != "") {
      parram += "daftar=" + daftar + "&";
    }
    if (submit != "") {
      parram += "submit=" + submit + "&";
    }
    if (pembayaran != "") {
      parram += "pembayaran=" + pembayaran + "&";
    }
    if (tahun != "") {
      parram += "tahun=" + tahun + "&";
    }
    if (page != "") {
      parram += "page=" + page + "&";
    }
    if (limit != "") {
      parram += "limit=" + limit + "&";
    }

    url_provinsi = "<?= base_url('api') ?>/daftar/statistik/d5/" + parram;
    
    table_provinsi = $('#dataTabelProvinsi').DataTable({
      processing: true,
      serverside: true,
      ajax: {
        url: url_provinsi,
        type: "GET",
        dataSrc: function ( json ) {
          $('#page-perprovinsi').attr('max', json.pagination.totalpagination);
          $('#page-perprovinsi').attr('value', json.pagination.currentpage);
          return json.data
        }
      },
      columns: [
        { data: null },
        { data: 'provinsi' },
        { data: 'num' },
      ],
      columnDefs: [
        {
          searchable: false,
          targets: 0
        }
      ],
      order: [[2, 'desc']],
    });
    table_provinsi.on( 'order.dt search.dt', function () {
        table_provinsi.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
            cell.innerHTML = i+1;
        } );
    } ).draw();
  }

  function load_data_perkabkota()
  {
    var daftar = $('#daftar-perkabkota').val();
    var submit = $('#submit-perkabkota').val();
    var pembayaran = $('#pembayaran-perkabkota').val();
    var tahun = $('#tahun-perkabkota').val();
    var page = $('#page-perkabkota').val();
    var limit = $('#limit-perkabkota').val();
    var parram = "?"

    if (daftar != "") {
      parram += "daftar=" + daftar + "&";
    }
    if (submit != "") {
      parram += "submit=" + submit + "&";
    }
    if (pembayaran != "") {
      parram += "pembayaran=" + pembayaran + "&";
    }
    if (tahun != "") {
      parram += "tahun=" + tahun + "&";
    }
    if (page != "") {
      parram += "page=" + page + "&";
    }
    if (limit != "") {
      parram += "limit=" + limit + "&";
    }

    url_kabkota = "<?= base_url('api') ?>/daftar/statistik/d6/" + parram;
    
    table_kabkota = $('#dataTabelKabKota').DataTable({
      processing: true,
      serverside: true,
      ajax: {
        url: url_kabkota,
        type: "GET",
        dataSrc: function ( json ) {
          $('#page-perkabkota').attr('max', json.pagination.totalpagination);
          $('#page-perkabkota').attr('value', json.pagination.currentpage);
          return json.data
        }
      },
      columns: [
        { data: null },
        { data: 'kab_kota' },
        { data: 'num' },
      ],
      columnDefs: [
        {
          searchable: false,
          targets: 0
        }
      ],
      order: [[2, 'desc']],
    });
    table_kabkota.on( 'order.dt search.dt', function () {
        table_kabkota.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
            cell.innerHTML = i+1;
        } );
    } ).draw();
  }

  function load_data_perkecamatan()
  {
    var daftar = $('#daftar-perkecamatan').val();
    var submit = $('#submit-perkecamatan').val();
    var pembayaran = $('#pembayaran-perkecamatan').val();
    var tahun = $('#tahun-perkecamatan').val();
    var page = $('#page-perkecamatan').val();
    var limit = $('#limit-perkecamatan').val();
    var parram = "?"

    if (daftar != "") {
      parram += "daftar=" + daftar + "&";
    }
    if (submit != "") {
      parram += "submit=" + submit + "&";
    }
    if (pembayaran != "") {
      parram += "pembayaran=" + pembayaran + "&";
    }
    if (tahun != "") {
      parram += "tahun=" + tahun + "&";
    }
    if (page != "") {
      parram += "page=" + page + "&";
    }
    if (limit != "") {
      parram += "limit=" + limit + "&";
    }

    url_kecamatan = "<?= base_url('api') ?>/daftar/statistik/d7/" + parram;
    
    table_kecamatan = $('#dataTabelKecamatan').DataTable({
      processing: true,
      serverside: true,
      ajax: {
        url: url_kecamatan,
        type: "GET",
        dataSrc: function ( json ) {
          $('#page-perkecamatan').attr('max', json.pagination.totalpagination);
          $('#page-perkecamatan').attr('value', json.pagination.currentpage);
          return json.data
        }
      },
      columns: [
        { data: null },
        { data: 'kecamatan' },
        { data: 'num' },
      ],
      columnDefs: [
        {
          searchable: false,
          targets: 0
        }
      ],
      order: [[2, 'desc']],
    });
    table_kecamatan.on( 'order.dt search.dt', function () {
        table_kecamatan.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
            cell.innerHTML = i+1;
        } );
    } ).draw();
  }
  

  function load_data_perkelurahan()
  {
    var daftar = $('#daftar-perkelurahan').val();
    var submit = $('#submit-perkelurahan').val();
    var pembayaran = $('#pembayaran-perkelurahan').val();
    var tahun = $('#tahun-perkelurahan').val();
    var page = $('#page-perkelurahan').val();
    var limit = $('#limit-perkelurahan').val();
    var parram = "?"

    if (daftar != "") {
      parram += "daftar=" + daftar + "&";
    }
    if (submit != "") {
      parram += "submit=" + submit + "&";
    }
    if (pembayaran != "") {
      parram += "pembayaran=" + pembayaran + "&";
    }
    if (tahun != "") {
      parram += "tahun=" + tahun + "&";
    }
    if (page != "") {
      parram += "page=" + page + "&";
    }
    if (limit != "") {
      parram += "limit=" + limit + "&";
    }

    url_kelurahan = "<?= base_url('api') ?>/daftar/statistik/d8/" + parram;
    
    table_kelurahan = $('#dataTabelKelurahan').DataTable({
      processing: true,
      serverside: true,
      ajax: {
        url: url_kelurahan,
        type: "GET",
        dataSrc: function ( json ) {
          $('#page-perkelurahan').attr('max', json.pagination.totalpagination);
          $('#page-perkelurahan').attr('value', json.pagination.currentpage);
          return json.data
        }
      },
      columns: [
        { data: null },
        { data: 'kelurahan' },
        { data: 'num' },
      ],
      columnDefs: [
        {
          searchable: false,
          targets: 0
        }
      ],
      order: [[2, 'desc']],
    });
    table_kelurahan.on( 'order.dt search.dt', function () {
        table_kelurahan.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
            cell.innerHTML = i+1;
        } );
    } ).draw();
  }

  function statistik_perfakultas()
  {
    var daftar = $('#daftar-perfakultas').val();
    var submit = $('#submit-perfakultas').val();
    var pembayaran = $('#pembayaran-perfakultas').val();
    var tahun = $('#tahun-perfakultas').val();
    var parram = "?"
    console.log('rte')
    if (daftar != "") {
      parram += "daftar=" + daftar + "&";
    }
    if (submit != "") {
      parram += "submit=" + submit + "&";
    }
    if (pembayaran != "") {
      parram += "pembayaran=" + pembayaran + "&";
    }
    if (tahun != "") {
      parram += "tahun=" + tahun + "&";
    }
    url_fakultas = "<?= base_url('api') ?>/daftar/statistik/d2/" + parram + "datatable=YA";
    table_fakultas.ajax.url( url_fakultas ).load(null, false);
  }
  
  function statistik_perjurusan()
  {
    var daftar = $('#daftar-perjurusan').val();
    var submit = $('#submit-perjurusan').val();
    var pembayaran = $('#pembayaran-perjurusan').val();
    var tahun = $('#tahun-perjurusan').val();
    var parram = "?"

    if (daftar != "") {
      parram += "daftar=" + daftar + "&";
    }
    if (submit != "") {
      parram += "submit=" + submit + "&";
    }
    if (pembayaran != "") {
      parram += "pembayaran=" + pembayaran + "&";
    }
    if (tahun != "") {
      parram += "tahun=" + tahun + "&";
    }
    url_jurusan = "<?= base_url('api') ?>/daftar/statistik/d3/" + parram + "datatable=YA";
    table_jurusan.ajax.url( url_jurusan ).load(null, false);
  }
  
  function statistik_perjalurmasuk()
  {
    var daftar = $('#daftar-perjalurmasuk').val();
    var submit = $('#submit-perjalurmasuk').val();
    var pembayaran = $('#pembayaran-perjalurmasuk').val();
    var tahun = $('#tahun-perjalurmasuk').val();
    var parram = "?"

    if (daftar != "") {
      parram += "daftar=" + daftar + "&";
    }
    if (submit != "") {
      parram += "submit=" + submit + "&";
    }
    if (pembayaran != "") {
      parram += "pembayaran=" + pembayaran + "&";
    }
    if (tahun != "") {
      parram += "tahun=" + tahun + "&";
    }
    url_jalurmasuk = "<?= base_url('api') ?>/daftar/statistik/d1/" + parram + "datatable=YA";
    table_jalurmasuk.ajax.url( url_jalurmasuk ).load(null, false);
  }

  function statistik_pernegara()
  {
    var daftar = $('#daftar-pernegara').val();
    var submit = $('#submit-pernegara').val();
    var pembayaran = $('#pembayaran-pernegara').val();
    var tahun = $('#tahun-pernegara').val();
    var page = $('#page-pernegara').val();
    var limit = $('#limit-pernegara').val();
    var parram = "?"

    if (daftar != "") {
      parram += "daftar=" + daftar + "&";
    }
    if (submit != "") {
      parram += "submit=" + submit + "&";
    }
    if (pembayaran != "") {
      parram += "pembayaran=" + pembayaran + "&";
    }
    if (tahun != "") {
      parram += "tahun=" + tahun + "&";
    }
    if (page != "") {
      parram += "page=" + page + "&";
    }
    if (limit != "") {
      parram += "limit=" + limit + "&";
    }
    url_negara = "<?= base_url('api') ?>/daftar/statistik/d4/" + parram;
    table_negara.ajax.url( url_negara ).load(null, false);
  }

  function statistik_perprovinsi()
  {
    var daftar = $('#daftar-perprovinsi').val();
    var submit = $('#submit-perprovinsi').val();
    var pembayaran = $('#pembayaran-perprovinsi').val();
    var tahun = $('#tahun-perprovinsi').val();
    var page = $('#page-perprovinsi').val();
    var limit = $('#limit-perprovinsi').val();
    var parram = "?"

    if (daftar != "") {
      parram += "daftar=" + daftar + "&";
    }
    if (submit != "") {
      parram += "submit=" + submit + "&";
    }
    if (pembayaran != "") {
      parram += "pembayaran=" + pembayaran + "&";
    }
    if (tahun != "") {
      parram += "tahun=" + tahun + "&";
    }
    if (page != "") {
      parram += "page=" + page + "&";
    }
    if (limit != "") {
      parram += "limit=" + limit + "&";
    }
    url_provinsi = "<?= base_url('api') ?>/daftar/statistik/d5/" + parram;
    table_provinsi.ajax.url( url_provinsi ).load(null, false);
  }

  function statistik_perkabkota()
  {
    var daftar = $('#daftar-perkabkota').val();
    var submit = $('#submit-perkabkota').val();
    var pembayaran = $('#pembayaran-perkabkota').val();
    var tahun = $('#tahun-perkabkota').val();
    var page = $('#page-perkabkota').val();
    var limit = $('#limit-perkabkota').val();
    var parram = "?"

    if (daftar != "") {
      parram += "daftar=" + daftar + "&";
    }
    if (submit != "") {
      parram += "submit=" + submit + "&";
    }
    if (pembayaran != "") {
      parram += "pembayaran=" + pembayaran + "&";
    }
    if (tahun != "") {
      parram += "tahun=" + tahun + "&";
    }
    if (page != "") {
      parram += "page=" + page + "&";
    }
    if (limit != "") {
      parram += "limit=" + limit + "&";
    }
    urlkabkota = "<?= base_url('api') ?>/daftar/statistik/d6/" + parram;
    tablekabkota.ajax.url( urlkabkota ).load(null, false);
  }

  function statistik_perkecamatan()
  {
    var daftar = $('#daftar-perkecamatan').val();
    var submit = $('#submit-perkecamatan').val();
    var pembayaran = $('#pembayaran-perkecamatan').val();
    var tahun = $('#tahun-perkecamatan').val();
    var page = $('#page-perkecamatan').val();
    var limit = $('#limit-perkecamatan').val();
    var parram = "?"

    if (daftar != "") {
      parram += "daftar=" + daftar + "&";
    }
    if (submit != "") {
      parram += "submit=" + submit + "&";
    }
    if (pembayaran != "") {
      parram += "pembayaran=" + pembayaran + "&";
    }
    if (tahun != "") {
      parram += "tahun=" + tahun + "&";
    }
    if (page != "") {
      parram += "page=" + page + "&";
    }
    if (limit != "") {
      parram += "limit=" + limit + "&";
    }
    url_kecamatan = "<?= base_url('api') ?>/daftar/statistik/d7/" + parram;
    table_kecamatan.ajax.url( url_kecamatan ).load(null, false);
  }

  function statistik_perkelurahan()
  {
    var daftar = $('#daftar-perkelurahan').val();
    var submit = $('#submit-perkelurahan').val();
    var pembayaran = $('#pembayaran-perkelurahan').val();
    var tahun = $('#tahun-perkelurahan').val();
    var page = $('#page-perkelurahan').val();
    var limit = $('#limit-perkelurahan').val();
    var parram = "?"

    if (daftar != "") {
      parram += "daftar=" + daftar + "&";
    }
    if (submit != "") {
      parram += "submit=" + submit + "&";
    }
    if (pembayaran != "") {
      parram += "pembayaran=" + pembayaran + "&";
    }
    if (tahun != "") {
      parram += "tahun=" + tahun + "&";
    }
    if (page != "") {
      parram += "page=" + page + "&";
    }
    if (limit != "") {
      parram += "limit=" + limit + "&";
    }
    url_kelurahan = "<?= base_url('api') ?>/daftar/statistik/d8/" + parram;
    table_kelurahan.ajax.url( url_kelurahan ).load(null, false);
  }

  function reset_perfakultas(){
    $('#form-filter-perfakultas')[0].reset();
    statistik_perfakultas();
  };
  
  function reset_perjurusan(){
    $('#form-filter-perjurusan')[0].reset();
    statistik_perjurusan();
  };
  
  function reset_perjalurmasuk(){
    $('#form-filter-perjalurmasuk')[0].reset();
    statistik_perjalurmasuk();
  };

  function reset_pernegara(){
    $('#form-filter-pernegara')[0].reset();
    statistik_pernegara();
  };

  function reset_perprovinsi(){
    $('#form-filter-perprovinsi')[0].reset();
    statistik_perprovinsi();
  };

  function reset_perkabkota(){
    $('#form-filter-perkabkota')[0].reset();
    statistik_perkabkota();
  };

  function reset_perkecamatan(){
    $('#form-filter-perkecamatan')[0].reset();
    statistik_perkecamatan();
  };

  function reset_perkelurahan(){
    $('#form-filter-perkelurahan')[0].reset();
    statistik_perkelurahan();
  };

  let interval_perfakultas = null;
  $("#btn-refresh-perfakultas").on('click', function(){
    if (interval_perfakultas === null) {
      interval_perfakultas = setInterval(function () {
        statistik_perfakultas()
      }, 5000);
      $("#btn-refresh-perfakultas").toggleClass("btn-danger btn-success");
    }else{
      clearInterval(interval_perfakultas);
      interval_perfakultas = null;
      $("#btn-refresh-perfakultas").toggleClass("btn-danger btn-success");
    }
  });

  let interval_perjurusan = null;
  $("#btn-refresh-perjurusan").on('click', function(){
    if (interval_perjurusan === null) {
      interval_perjurusan = setInterval(function () {
        statistik_perjurusan()
      }, 5000);
      $("#btn-refresh-perjurusan").toggleClass("btn-danger btn-success");
    }else{
      clearInterval(interval_perjurusan);
      interval_perjurusan = null;
      $("#btn-refresh-perjurusan").toggleClass("btn-danger btn-success");
    }
  });

  let interval_perjalurmasuk = null;
  $("#btn-refresh-perjalurmasuk").on('click', function(){
    if (interval_perjalurmasuk === null) {
      interval_perjalurmasuk = setInterval(function () {
        statistik_perjalurmasuk()
      }, 5000);
      $("#btn-refresh-perjalurmasuk").toggleClass("btn-danger btn-success");
    }else{
      clearInterval(interval_perjalurmasuk);
      interval_perjalurmasuk = null;
      $("#btn-refresh-perjalurmasuk").toggleClass("btn-danger btn-success");
    }
  });

  let interval_pernegara = null;
  $("#btn-refresh-pernegara").on('click', function(){
    if (interval_pernegara === null) {
      interval_pernegara = setInterval(function () {
        statistik_pernegara()
      }, 5000);
      $("#btn-refresh-pernegara").toggleClass("btn-danger btn-success");
    }else{
      clearInterval(interval_pernegara);
      interval_pernegara = null;
      $("#btn-refresh-pernegara").toggleClass("btn-danger btn-success");
    }
  });

  let interval_perprovinsi = null;
  $("#btn-refresh-perprovinsi").on('click', function(){
    if (interval_perprovinsi === null) {
      interval_perprovinsi = setInterval(function () {
        statistik_perprovinsi()
      }, 5000);
      $("#btn-refresh-perprovinsi").toggleClass("btn-danger btn-success");
    }else{
      clearInterval(interval_perprovinsi);
      interval_perprovinsi = null;
      $("#btn-refresh-perprovinsi").toggleClass("btn-danger btn-success");
    }
  });

  let interval_perkabkota = null;
  $("#btn-refresh-perkabkota").on('click', function(){
    if (interval_perkabkota === null) {
      interval_perkabkota = setInterval(function () {
        statistik_perkabkota()
      }, 5000);
      $("#btn-refresh-perkabkota").toggleClass("btn-danger btn-success");
    }else{
      clearInterval(interval_perkabkota);
      interval_perkabkota = null;
      $("#btn-refresh-perkabkota").toggleClass("btn-danger btn-success");
    }
  });

  let interval_perkecamatan = null;
  $("#btn-refresh-perkecamatan").on('click', function(){
    if (interval_perkecamatan === null) {
      interval_perkecamatan = setInterval(function () {
        statistik_perkecamatan()
      }, 5000);
      $("#btn-refresh-perkecamatan").toggleClass("btn-danger btn-success");
    }else{
      clearInterval(interval_perkecamatan);
      interval_perkecamatan = null;
      $("#btn-refresh-perkecamatan").toggleClass("btn-danger btn-success");
    }
  });

  let interval_perkelurahan = null;
  $("#btn-refresh-perkelurahan").on('click', function(){
    if (interval_perkelurahan === null) {
      interval_perkelurahan = setInterval(function () {
        statistik_perkelurahan()
      }, 5000);
      $("#btn-refresh-perkelurahan").toggleClass("btn-danger btn-success");
    }else{
      clearInterval(interval_perkelurahan);
      interval_perkelurahan = null;
      $("#btn-refresh-perkelurahan").toggleClass("btn-danger btn-success");
    }
  });

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