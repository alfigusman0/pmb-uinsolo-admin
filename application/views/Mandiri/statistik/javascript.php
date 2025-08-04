<script src="<?= base_url() ?>assets/libs/datatables/jquery.dataTables.js"></script>
<script src="<?= base_url() ?>assets/libs/datatables-bs5/datatables-bootstrap5.js"></script>
<script src="<?=base_url()?>assets/libs/block-ui/block-ui.js"></script>
<script>
  var table_agama, table_program, table_tipe_ujian, table_negara, table_provinsi, table_kecamatan, table_kab_kota, table_kelurahan, table_pilihan, table_kelulusan, table_rumpun, table_jenis_sekolah, table_jurusan_sekolah

  $(document).ready(function(){
    $('#container-statistik').show();
    load_data();

    let interval_agama = null;
    $("#btn-refresh-agama").on('click', function(){
      if (interval_agama === null) {
        interval_agama = setInterval(function () {
          statistik_agama()
        }, 5000);
        $("#btn-refresh-agama").toggleClass("btn-danger btn-success");
        $("#btn-refresh-agama").html("<i class='bx bx-refresh'>&nbsp;</i>Refresh On");
      }else{
        clearInterval(interval_agama);
        interval_agama = null;
        $("#btn-refresh-agama").toggleClass("btn-danger btn-success");
        $("#btn-refresh-agama").html("<i class='bx bx-refresh'>&nbsp;</i>Refresh Off");
      }
    });

    let interval_program = null;
    $("#btn-refresh-program").on('click', function(){
      if (interval_program === null) {
        interval_program = setInterval(function () {
          statistik_program()
        }, 5000);
        $("#btn-refresh-program").html("<i class='bx bx-refresh'>&nbsp;</i>Refresh On");
        $("#btn-refresh-program").toggleClass("btn-danger btn-success");
      }else{
        clearInterval(interval_program);
        interval_program = null;
        $("#btn-refresh-program").html("<i class='bx bx-refresh'>&nbsp;</i>Refresh Off");
        $("#btn-refresh-program").toggleClass("btn-danger btn-success");
      }
    });

    let interval_tipe_ujian = null;
    $("#btn-refresh-tipe_ujian").on('click', function(){
      if (interval_tipe_ujian === null) {
        interval_tipe_ujian = setInterval(function () {
          statistik_tipe_ujian()
        }, 5000);
        $("#btn-refresh-tipe_ujian").html("<i class='bx bx-refresh'>&nbsp;</i>Refresh On");
        $("#btn-refresh-tipe_ujian").toggleClass("btn-danger btn-success");
      }else{
        clearInterval(interval_tipe_ujian);
        interval_tipe_ujian = null;
        $("#btn-refresh-tipe_ujian").html("<i class='bx bx-refresh'>&nbsp;</i>Refresh Off");
        $("#btn-refresh-tipe_ujian").toggleClass("btn-danger btn-success");
      }
    });

    let interval_negara = null;
    $("#btn-refresh-negara").on('click', function(){
      if (interval_negara === null) {
        interval_negara = setInterval(function () {
          statistik_negara()
        }, 5000);
        $("#btn-refresh-negara").html("<i class='bx bx-refresh'>&nbsp;</i>Refresh On");
        $("#btn-refresh-negara").toggleClass("btn-danger btn-success");
      }else{
        clearInterval(interval_negara);
        interval_negara = null;
        $("#btn-refresh-negara").html("<i class='bx bx-refresh'>&nbsp;</i>Refresh Off");
        $("#btn-refresh-negara").toggleClass("btn-danger btn-success");
      }
    });

    let interval_provinsi = null;
    $("#btn-refresh-provinsi").on('click', function(){
      if (interval_provinsi === null) {
        interval_provinsi = setInterval(function () {
          statistik_provinsi()
        }, 5000);
        $("#btn-refresh-provinsi").html("<i class='bx bx-refresh'>&nbsp;</i>Refresh On");
        $("#btn-refresh-provinsi").toggleClass("btn-danger btn-success");
      }else{
        clearInterval(interval_provinsi);
        interval_provinsi = null;
        $("#btn-refresh-provinsi").html("<i class='bx bx-refresh'>&nbsp;</i>Refresh Off");
        $("#btn-refresh-provinsi").toggleClass("btn-danger btn-success");
      }
    });

    let interval_kab_kota = null;
    $("#btn-refresh-kab_kota").on('click', function(){
      if (interval_kab_kota === null) {
        interval_kab_kota = setInterval(function () {
          statistik_kab_kota()
        }, 5000);
        $("#btn-refresh-kab_kota").html("<i class='bx bx-refresh'>&nbsp;</i>Refresh On");
        $("#btn-refresh-kab_kota").toggleClass("btn-danger btn-success");
      }else{
        clearInterval(interval_kab_kota);
        interval_kab_kota = null;
        $("#btn-refresh-kab_kota").html("<i class='bx bx-refresh'>&nbsp;</i>Refresh Off");
        $("#btn-refresh-kab_kota").toggleClass("btn-danger btn-success");
      }
    });

    let interval_kecamatan = null;
    $("#btn-refresh-kecamatan").on('click', function(){
      if (interval_kecamatan === null) {
        interval_kecamatan = setInterval(function () {
          statistik_kecamatan()
        }, 5000);
        $("#btn-refresh-kecamatan").html("<i class='bx bx-refresh'>&nbsp;</i>Refresh On");
        $("#btn-refresh-kecamatan").toggleClass("btn-danger btn-success");
      }else{
        clearInterval(interval_kecamatan);
        interval_kecamatan = null;
        $("#btn-refresh-kecamatan").html("<i class='bx bx-refresh'>&nbsp;</i>Refresh Off");
        $("#btn-refresh-kecamatan").toggleClass("btn-danger btn-success");
      }
    });

    let interval_kelurahan = null;
    $("#btn-refresh-kelurahan").on('click', function(){
      if (interval_kelurahan === null) {
        interval_kelurahan = setInterval(function () {
          statistik_kelurahan()
        }, 5000);
        $("#btn-refresh-kelurahan").html("<i class='bx bx-refresh'>&nbsp;</i>Refresh On");
        $("#btn-refresh-kelurahan").toggleClass("btn-danger btn-success");
      }else{
        clearInterval(interval_kelurahan);
        interval_kelurahan = null;
        $("#btn-refresh-kelurahan").html("<i class='bx bx-refresh'>&nbsp;</i>Refresh Off");
        $("#btn-refresh-kelurahan").toggleClass("btn-danger btn-success");
      }
    });

    let interval_pilihan = null;
    $("#btn-refresh-pilihan").on('click', function(){
      if (interval_pilihan === null) {
        interval_pilihan = setInterval(function () {
          statistik_pilihan()
        }, 5000);
        $("#btn-refresh-pilihan").html("<i class='bx bx-refresh'>&nbsp;</i>Refresh On");
        $("#btn-refresh-pilihan").toggleClass("btn-danger btn-success");
      }else{
        clearInterval(interval_pilihan);
        interval_pilihan = null;
        $("#btn-refresh-pilihan").html("<i class='bx bx-refresh'>&nbsp;</i>Refresh Off");
        $("#btn-refresh-pilihan").toggleClass("btn-danger btn-success");
      }
    });

    let interval_kelulusan = null;
    $("#btn-refresh-kelulusan").on('click', function(){
      if (interval_kelulusan === null) {
        interval_kelulusan = setInterval(function () {
          statistik_kelulusan()
        }, 5000);
        $("#btn-refresh-kelulusan").html("<i class='bx bx-refresh'>&nbsp;</i>Refresh On");
        $("#btn-refresh-kelulusan").toggleClass("btn-danger btn-success");
      }else{
        clearInterval(interval_kelulusan);
        interval_kelulusan = null;
        $("#btn-refresh-kelulusan").html("<i class='bx bx-refresh'>&nbsp;</i>Refresh Off");
        $("#btn-refresh-kelulusan").toggleClass("btn-danger btn-success");
      }
    });

    let interval_rumpun = null;
    $("#btn-refresh-rumpun").on('click', function(){
      if (interval_rumpun === null) {
        interval_rumpun = setInterval(function () {
          statistik_rumpun()
        }, 5000);
        $("#btn-refresh-rumpun").html("<i class='bx bx-refresh'>&nbsp;</i>Refresh On");
        $("#btn-refresh-rumpun").toggleClass("btn-danger btn-success");
      }else{
        clearInterval(interval_rumpun);
        interval_rumpun = null;
        $("#btn-refresh-rumpun").html("<i class='bx bx-refresh'>&nbsp;</i>Refresh Off");
        $("#btn-refresh-rumpun").toggleClass("btn-danger btn-success");
      }
    });

    let interval_jenis_sekolah = null;
    $("#btn-refresh-jenis_sekolah").on('click', function(){
      if (interval_jenis_sekolah === null) {
        interval_jenis_sekolah = setInterval(function () {
          statistik_jenis_sekolah()
        }, 5000);
        $("#btn-refresh-jenis_sekolah").html("<i class='bx bx-refresh'>&nbsp;</i>Refresh On");
        $("#btn-refresh-jenis_sekolah").toggleClass("btn-danger btn-success");
      }else{
        clearInterval(interval_jenis_sekolah);
        interval_jenis_sekolah = null;
        $("#btn-refresh-jenis_sekolah").html("<i class='bx bx-refresh'>&nbsp;</i>Refresh Off");
        $("#btn-refresh-jenis_sekolah").toggleClass("btn-danger btn-success");
      }
    });

    let interval_jurusan_sekolah = null;
    $("#btn-refresh-jurusan_sekolah").on('click', function(){
      if (interval_jurusan_sekolah === null) {
        interval_jurusan_sekolah = setInterval(function () {
          statistik_jurusan_sekolah()
        }, 5000);
        $("#btn-refresh-jurusan_sekolah").html("<i class='bx bx-refresh'>&nbsp;</i>Refresh On");
        $("#btn-refresh-jurusan_sekolah").toggleClass("btn-danger btn-success");
      }else{
        clearInterval(interval_jurusan_sekolah);
        interval_jurusan_sekolah = null;
        $("#btn-refresh-jurusan_sekolah").html("<i class='bx bx-refresh'>&nbsp;</i>Refresh Off");
        $("#btn-refresh-jurusan_sekolah").toggleClass("btn-danger btn-success");
      }
    });
  })

  function load_data()
  {
    load_data_agama();
    load_data_program();
    load_data_tipe_ujian();
    load_data_negara();
    load_data_provinsi();
    load_data_kecamatan();
    load_data_kab_kota();
    load_data_kelurahan();
    load_data_pilihan();
    load_data_kelulusan();
    load_data_rumpun();
    load_data_jenis_sekolah();
    load_data_jurusan_sekolah();
  }

  function load_data_agama()
  {
    var tahun = $('#tahun-agama').val();
    var jenis_kelamin = $('#jenis-kelamin-agama').val();
    var kebutuhan_khusus = $('#kebutuhan-khusus-agama').val();
    var kategori = $('#kategori-agama').val();
    var pembayaran = $('#pembayaran-agama').val();
    var formulir = $('#formulir-agama').val();
    var parram = "?"

    if (tahun != "") {
      parram += "tahun=" + tahun + "&";
    }
    if (jenis_kelamin != "") {
      parram += "jenis_kelamin=" + jenis_kelamin + "&";
    }
    if (kebutuhan_khusus != "") {
      parram += "kebutuhan_khusus=" + kebutuhan_khusus + "&";
    }
    if (kategori != "") {
      parram += "kategori=" + kategori + "&";
    }
    if (pembayaran != "") {
      parram += "pembayaran=" + pembayaran + "&";
    }
    if (formulir != "") {
      parram += "formulir=" + formulir + "&";
    }

    url_agama = "<?= base_url('api') ?>/mandiri/statistik/m4/" + parram;
    
    table_agama = $('#dataTabelAgama').DataTable({
      processing: true,
      serverside: true,
      ajax: {
        url: url_agama,
        type: "GET",
        dataSrc: function ( json ) {
          return json.data
        }
      },
      columns: [
        { data: null },
        { data: 'agama' },
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
    table_agama.on( 'order.dt search.dt', function () {
        table_agama.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
            cell.innerHTML = i+1;
        } );
    } ).draw();
  }

  function statistik_agama()
  {
    var tahun = $('#tahun-agama').val();
    var jenis_kelamin = $('#jenis-kelamin-agama').val();
    var kebutuhan_khusus = $('#kebutuhan-khusus-agama').val();
    var kategori = $('#kategori-agama').val();
    var pembayaran = $('#pembayaran-agama').val();
    var formulir = $('#formulir-agama').val();
    var parram = "?"

    if (tahun != "") {
      parram += "tahun=" + tahun + "&";
    }
    if (jenis_kelamin != "") {
      parram += "jenis_kelamin=" + jenis_kelamin + "&";
    }
    if (kebutuhan_khusus != "") {
      parram += "kebutuhan_khusus=" + kebutuhan_khusus + "&";
    }
    if (kategori != "") {
      parram += "kategori=" + kategori + "&";
    }
    if (pembayaran != "") {
      parram += "pembayaran=" + pembayaran + "&";
    }
    if (formulir != "") {
      parram += "formulir=" + formulir + "&";
    }

    url_agama = "<?= base_url('api') ?>/mandiri/statistik/m4/" + parram;
    table_agama.ajax.url( url_agama ).load(null, false);
  }

  function reset_agama(){
    $('#form-filter-agama')[0].reset();
    statistik_agama();
  };

  function load_data_program()
  {
    var tahun = $('#tahun-program').val();
    var jenis_kelamin = $('#jenis-kelamin-program').val();
    var kebutuhan_khusus = $('#kebutuhan-khusus-program').val();
    var kategori = $('#kategori-program').val();
    var pembayaran = $('#pembayaran-program').val();
    var formulir = $('#formulir-program').val();
    var parram = "?"

    if (tahun != "") {
      parram += "tahun=" + tahun + "&";
    }
    if (jenis_kelamin != "") {
      parram += "jenis_kelamin=" + jenis_kelamin + "&";
    }
    if (kebutuhan_khusus != "") {
      parram += "kebutuhan_khusus=" + kebutuhan_khusus + "&";
    }
    if (kategori != "") {
      parram += "kategori=" + kategori + "&";
    }
    if (pembayaran != "") {
      parram += "pembayaran=" + pembayaran + "&";
    }
    if (formulir != "") {
      parram += "formulir=" + formulir + "&";
    }

    url_program = "<?= base_url('api') ?>/mandiri/statistik/m2/" + parram;
    
    table_program = $('#dataTabelProgram').DataTable({
      processing: true,
      serverside: true,
      ajax: {
        url: url_program,
        type: "GET",
        dataSrc: function ( json ) {
          return json.data
        }
      },
      columns: [
        { data: null },
        { data: 'program' },
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
    table_program.on( 'order.dt search.dt', function () {
        table_program.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
            cell.innerHTML = i+1;
        } );
    } ).draw();
  }

  function statistik_program()
  {
    var tahun = $('#tahun-program').val();
    var jenis_kelamin = $('#jenis-kelamin-program').val();
    var kebutuhan_khusus = $('#kebutuhan-khusus-program').val();
    var kategori = $('#kategori-program').val();
    var pembayaran = $('#pembayaran-program').val();
    var formulir = $('#formulir-program').val();
    var parram = "?"

    if (tahun != "") {
      parram += "tahun=" + tahun + "&";
    }
    if (jenis_kelamin != "") {
      parram += "jenis_kelamin=" + jenis_kelamin + "&";
    }
    if (kebutuhan_khusus != "") {
      parram += "kebutuhan_khusus=" + kebutuhan_khusus + "&";
    }
    if (kategori != "") {
      parram += "kategori=" + kategori + "&";
    }
    if (pembayaran != "") {
      parram += "pembayaran=" + pembayaran + "&";
    }
    if (formulir != "") {
      parram += "formulir=" + formulir + "&";
    }

    url_program = "<?= base_url('api') ?>/mandiri/statistik/m2/" + parram;
    table_program.ajax.url( url_program ).load(null, false);
  }

  function reset_program(){
    $('#form-filter-program')[0].reset();
    statistik_program();
  };

  function load_data_tipe_ujian()
  {
    var tahun = $('#tahun-tipe-ujian').val();
    var jenis_kelamin = $('#jenis-kelamin-tipe-ujian').val();
    var kebutuhan_khusus = $('#kebutuhan-khusus-tipe-ujian').val();
    var kategori = $('#kategori-tipe-ujian').val();
    var pembayaran = $('#pembayaran-tipe-ujian').val();
    var formulir = $('#formulir-tipe-ujian').val();
    var parram = "?"

    if (tahun != "") {
      parram += "tahun=" + tahun + "&";
    }
    if (jenis_kelamin != "") {
      parram += "jenis_kelamin=" + jenis_kelamin + "&";
    }
    if (kebutuhan_khusus != "") {
      parram += "kebutuhan_khusus=" + kebutuhan_khusus + "&";
    }
    if (kategori != "") {
      parram += "kategori=" + kategori + "&";
    }
    if (pembayaran != "") {
      parram += "pembayaran=" + pembayaran + "&";
    }
    if (formulir != "") {
      parram += "formulir=" + formulir + "&";
    }

    url_tipe_ujian = "<?= base_url('api') ?>/mandiri/statistik/m3/" + parram;
    
    table_tipe_ujian = $('#dataTabelTipeUjian').DataTable({
      processing: true,
      serverside: true,
      ajax: {
        url: url_tipe_ujian,
        type: "GET",
        dataSrc: function ( json ) {
          return json.data
        }
      },
      columns: [
        { data: null },
        { data: 'tipe_ujian' },
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
    table_tipe_ujian.on( 'order.dt search.dt', function () {
        table_tipe_ujian.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
            cell.innerHTML = i+1;
        } );
    } ).draw();
  }

  function statistik_tipe_ujian()
  {
    var tahun = $('#tahun-tipe-ujian').val();
    var jenis_kelamin = $('#jenis-kelamin-tipe-ujian').val();
    var kebutuhan_khusus = $('#kebutuhan-khusus-tipe-ujian').val();
    var kategori = $('#kategori-tipe-ujian').val();
    var pembayaran = $('#pembayaran-tipe-ujian').val();
    var formulir = $('#formulir-tipe-ujian').val();
    var parram = "?"

    if (tahun != "") {
      parram += "tahun=" + tahun + "&";
    }
    if (jenis_kelamin != "") {
      parram += "jenis_kelamin=" + jenis_kelamin + "&";
    }
    if (kebutuhan_khusus != "") {
      parram += "kebutuhan_khusus=" + kebutuhan_khusus + "&";
    }
    if (kategori != "") {
      parram += "kategori=" + kategori + "&";
    }
    if (pembayaran != "") {
      parram += "pembayaran=" + pembayaran + "&";
    }
    if (formulir != "") {
      parram += "formulir=" + formulir + "&";
    }

    url_tipe_ujian = "<?= base_url('api') ?>/mandiri/statistik/m3/" + parram;
    table_tipe_ujian.ajax.url( url_tipe_ujian ).load(null, false);
  }

  function reset_tipe_ujian(){
    $('#form-filter-tipe-ujian')[0].reset();
    statistik_tipe_ujian();
  };

  function load_data_negara()
  {
    var tahun = $('#tahun-negara').val();
    var kategori = $('#kategori-negara').val();
    var tipe_ujian = $('#tipe-ujian-negara').val();
    var parram = "?"

    if (tahun != "") {
      parram += "tahun=" + tahun + "&";
    }
    if (kategori != "") {
      parram += "kategori=" + kategori + "&";
    }
    if (tipe_ujian != "") {
      parram += "ids_tipe_ujian=" + tipe_ujian + "&";
    }

    url_negara = "<?= base_url('api') ?>/mandiri/statistik/m5/" + parram;
    
    table_negara = $('#dataTabelNegara').DataTable({
      processing: true,
      serverside: true,
      ajax: {
        url: url_negara,
        type: "GET",
        dataSrc: function ( json ) {
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

  function statistik_negara()
  {
    var tahun = $('#tahun-negara').val();
    var kategori = $('#kategori-negara').val();
    var tipe_ujian = $('#tipe-ujian-negara').val();
    var parram = "?"

    if (tahun != "") {
      parram += "tahun=" + tahun + "&";
    }
    if (kategori != "") {
      parram += "kategori=" + kategori + "&";
    }
    if (tipe_ujian != "") {
      parram += "ids_tipe_ujian=" + tipe_ujian + "&";
    }

    url_negara = "<?= base_url('api') ?>/mandiri/statistik/m5/" + parram;
    table_negara.ajax.url( url_negara ).load(null, false);
  }

  function reset_negara(){
    $('#form-filter-negara')[0].reset();
    statistik_negara();
  };

  function load_data_provinsi()
  {
    var tahun = $('#tahun-provinsi').val();
    var kategori = $('#kategori-provinsi').val();
    var tipe_ujian = $('#tipe-ujian-provinsi').val();
    var parram = "?"

    if (tahun != "") {
      parram += "tahun=" + tahun + "&";
    }
    if (kategori != "") {
      parram += "kategori=" + kategori + "&";
    }
    if (tipe_ujian != "") {
      parram += "ids_tipe_ujian=" + tipe_ujian + "&";
    }

    url_provinsi = "<?= base_url('api') ?>/mandiri/statistik/m6/" + parram;
    
    table_provinsi = $('#dataTabelProvinsi').DataTable({
      processing: true,
      serverside: true,
      ajax: {
        url: url_provinsi,
        type: "GET",
        dataSrc: function ( json ) {
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

  function statistik_provinsi()
  {
    var tahun = $('#tahun-provinsi').val();
    var kategori = $('#kategori-provinsi').val();
    var tipe_ujian = $('#tipe-ujian-provinsi').val();
    var parram = "?"

    if (tahun != "") {
      parram += "tahun=" + tahun + "&";
    }
    if (kategori != "") {
      parram += "kategori=" + kategori + "&";
    }
    if (tipe_ujian != "") {
      parram += "ids_tipe_ujian=" + tipe_ujian + "&";
    }

    url_provinsi = "<?= base_url('api') ?>/mandiri/statistik/m6/" + parram;
    table_provinsi.ajax.url( url_provinsi ).load(null, false);
  }

  function reset_provinsi(){
    $('#form-filter-provinsi')[0].reset();
    statistik_provinsi();
  };

  function load_data_kab_kota()
  {
    var tahun = $('#tahun-kab_kota').val();
    var kategori = $('#kategori-kab_kota').val();
    var tipe_ujian = $('#tipe-ujian-kab_kota').val();
    var parram = "?"

    if (tahun != "") {
      parram += "tahun=" + tahun + "&";
    }
    if (kategori != "") {
      parram += "kategori=" + kategori + "&";
    }
    if (tipe_ujian != "") {
      parram += "ids_tipe_ujian=" + tipe_ujian + "&";
    }

    url_kab_kota = "<?= base_url('api') ?>/mandiri/statistik/m7/" + parram;
    
    table_kab_kota = $('#dataTabelKabupatenKota').DataTable({
      processing: true,
      serverside: true,
      ajax: {
        url: url_kab_kota,
        type: "GET",
        dataSrc: function ( json ) {
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
    table_kab_kota.on( 'order.dt search.dt', function () {
        table_kab_kota.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
            cell.innerHTML = i+1;
        } );
    } ).draw();
  }

  function statistik_kab_kota()
  {
    var tahun = $('#tahun-kab_kota').val();
    var kategori = $('#kategori-kab_kota').val();
    var tipe_ujian = $('#tipe-ujian-kab_kota').val();
    var parram = "?"

    if (tahun != "") {
      parram += "tahun=" + tahun + "&";
    }
    if (kategori != "") {
      parram += "kategori=" + kategori + "&";
    }
    if (tipe_ujian != "") {
      parram += "ids_tipe_ujian=" + tipe_ujian + "&";
    }

    url_kab_kota = "<?= base_url('api') ?>/mandiri/statistik/m7/" + parram;
    table_kab_kota.ajax.url( url_kab_kota ).load(null, false);
  }

  function reset_kab_kota(){
    $('#form-filter-kab_kota')[0].reset();
    statistik_kab_kota();
  };

  function load_data_kecamatan()
  {
    var tahun = $('#tahun-kecamatan').val();
    var kategori = $('#kategori-kecamatan').val();
    var tipe_ujian = $('#tipe-ujian-kecamatan').val();
    var parram = "?"

    if (tahun != "") {
      parram += "tahun=" + tahun + "&";
    }
    if (kategori != "") {
      parram += "kategori=" + kategori + "&";
    }
    if (tipe_ujian != "") {
      parram += "ids_tipe_ujian=" + tipe_ujian + "&";
    }

    url_kecamatan = "<?= base_url('api') ?>/mandiri/statistik/m8/" + parram;
    
    table_kecamatan = $('#dataTabelKecamatan').DataTable({
      processing: true,
      serverside: true,
      ajax: {
        url: url_kecamatan,
        type: "GET",
        dataSrc: function ( json ) {
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

  function statistik_kecamatan()
  {
    var tahun = $('#tahun-kecamatan').val();
    var kategori = $('#kategori-kecamatan').val();
    var tipe_ujian = $('#tipe-ujian-kecamatan').val();
    var parram = "?"

    if (tahun != "") {
      parram += "tahun=" + tahun + "&";
    }
    if (kategori != "") {
      parram += "kategori=" + kategori + "&";
    }
    if (tipe_ujian != "") {
      parram += "ids_tipe_ujian=" + tipe_ujian + "&";
    }

    url_kecamatan = "<?= base_url('api') ?>/mandiri/statistik/m8/" + parram;
    table_kecamatan.ajax.url( url_kecamatan ).load(null, false);
  }

  function reset_kecamatan(){
    $('#form-filter-kecamatan')[0].reset();
    statistik_kecamatan();
  };

  function load_data_kelurahan()
  {
    var tahun = $('#tahun-kelurahan').val();
    var kategori = $('#kategori-kelurahan').val();
    var tipe_ujian = $('#tipe-ujian-kelurahan').val();
    var parram = "?"

    if (tahun != "") {
      parram += "tahun=" + tahun + "&";
    }
    if (kategori != "") {
      parram += "kategori=" + kategori + "&";
    }
    if (tipe_ujian != "") {
      parram += "ids_tipe_ujian=" + tipe_ujian + "&";
    }

    url_kelurahan = "<?= base_url('api') ?>/mandiri/statistik/m9/" + parram;
    
    table_kelurahan = $('#dataTabelKelurahan').DataTable({
      processing: true,
      serverside: true,
      ajax: {
        url: url_kelurahan,
        type: "GET",
        dataSrc: function ( json ) {
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

  function statistik_kelurahan()
  {
    var tahun = $('#tahun-kelurahan').val();
    var kategori = $('#kategori-kelurahan').val();
    var tipe_ujian = $('#tipe-ujian-kelurahan').val();
    var parram = "?"

    if (tahun != "") {
      parram += "tahun=" + tahun + "&";
    }
    if (kategori != "") {
      parram += "kategori=" + kategori + "&";
    }
    if (tipe_ujian != "") {
      parram += "ids_tipe_ujian=" + tipe_ujian + "&";
    }

    url_kelurahan = "<?= base_url('api') ?>/mandiri/statistik/m9/" + parram;
    table_kelurahan.ajax.url( url_kelurahan ).load(null, false);
  }

  function reset_kelurahan(){
    $('#form-filter-kelurahan')[0].reset();
    statistik_kelurahan();
  };

  function load_data_pilihan()
  {
    var tahun = $('#tahun-pilihan').val();
    var kategori = $('#kategori-pilihan').val();
    var parram = "?"

    if (tahun != "") {
      parram += "tahun=" + tahun + "&";
    }
    if (kategori != "") {
      parram += "kategori=" + kategori + "&";
    }

    url_pilihan = "<?= base_url('api') ?>/mandiri/statistik/m10/" + parram;
    
    table_pilihan = $('#dataTabelPilihan').DataTable({
      processing: true,
      serverside: true,
      ajax: {
        url: url_pilihan,
        type: "GET",
        dataSrc: function ( json ) {
          return json.data
        }
      },
      columns: [
        { data: null },
        { data: 'jurusan' },
        { data: 'fakultas' },
        { data: 'pilihan1' },
        { data: 'pilihan2' },
        { data: 'pilihan3' },
        { data: 'total' },
      ],
      columnDefs: [
        {
          searchable: false,
          targets: 0
        }
      ],
      order: [[2, 'desc']],
    });
    table_pilihan.on( 'order.dt search.dt', function () {
        table_pilihan.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
            cell.innerHTML = i+1;
        } );
    } ).draw();
  }

  function statistik_pilihan()
  {
    var tahun = $('#tahun-pilihan').val();
    var kategori = $('#kategori-pilihan').val();
    var parram = "?"

    if (tahun != "") {
      parram += "tahun=" + tahun + "&";
    }
    if (kategori != "") {
      parram += "kategori=" + kategori + "&";
    }

    url_pilihan = "<?= base_url('api') ?>/mandiri/statistik/m10/" + parram;
    table_pilihan.ajax.url( url_pilihan ).load(null, false);
  }

  function reset_pilihan(){
    $('#form-filter-pilihan')[0].reset();
    statistik_pilihan();
  };

  function load_data_kelulusan()
  {
    var tahun = $('#tahun-kelulusan').val();
    var kategori = $('#kategori-kelulusan').val();
    var parram = "?"

    if (tahun != "") {
      parram += "tahun=" + tahun + "&";
    }
    if (kategori != "") {
      parram += "kategori=" + kategori + "&";
    }

    url_kelulusan = "<?= base_url('api') ?>/mandiri/statistik/m11/" + parram;
    
    table_kelulusan = $('#dataTabelKelulusan').DataTable({
      processing: true,
      serverside: true,
      ajax: {
        url: url_kelulusan,
        type: "GET",
        dataSrc: function ( json ) {
          return json.data
        }
      },
      columns: [
        { data: null },
        { data: 'kode_jurusan' },
        { data: 'jurusan' },
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
    table_kelulusan.on( 'order.dt search.dt', function () {
        table_kelulusan.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
            cell.innerHTML = i+1;
        } );
    } ).draw();
  }

  function statistik_kelulusan()
  {
    var tahun = $('#tahun-kelulusan').val();
    var kategori = $('#kategori-kelulusan').val();
    var parram = "?"

    if (tahun != "") {
      parram += "tahun=" + tahun + "&";
    }
    if (kategori != "") {
      parram += "kategori=" + kategori + "&";
    }

    url_kelulusan = "<?= base_url('api') ?>/mandiri/statistik/m11/" + parram;
    table_kelulusan.ajax.url( url_kelulusan ).load(null, false);
  }

  function reset_kelulusan(){
    $('#form-filter-kelulusan')[0].reset();
    statistik_kelulusan();
  };

  function load_data_rumpun()
  {
    var tahun = $('#tahun-rumpun').val();
    var kategori = $('#kategori-rumpun').val();
    var tipe_ujian = $('#tipe_ujian-rumpun').val();
    var akreditasi = $('#akreditasi-rumpun').val();
    var parram = "?"

    if (tahun != "") {
      parram += "tahun=" + tahun + "&";
    }
    if (kategori != "") {
      parram += "kategori=" + kategori + "&";
    }
    if (tipe_ujian != "") {
      parram += "ids_tipe_ujian=" + tipe_ujian + "&";
    }
    if (akreditasi != "") {
      parram += "akreditasi=" + akreditasi + "&";
    }

    url_rumpun = "<?= base_url('api') ?>/mandiri/statistik/m12/" + parram;
    
    table_rumpun = $('#dataTabelRumpun').DataTable({
      processing: true,
      serverside: true,
      ajax: {
        url: url_rumpun,
        type: "GET",
        dataSrc: function ( json ) {
          return json.data
        }
      },
      columns: [
        { data: null },
        { data: 'rumpun' },
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
    table_rumpun.on( 'order.dt search.dt', function () {
        table_rumpun.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
            cell.innerHTML = i+1;
        } );
    } ).draw();
  }

  function statistik_rumpun()
  {
    var tahun = $('#tahun-rumpun').val();
    var kategori = $('#kategori-rumpun').val();
    var tipe_ujian = $('#tipe_ujian-rumpun').val();
    var akreditasi = $('#akreditasi-rumpun').val();
    var parram = "?"

    if (tahun != "") {
      parram += "tahun=" + tahun + "&";
    }
    if (kategori != "") {
      parram += "kategori=" + kategori + "&";
    }
    if (tipe_ujian != "") {
      parram += "ids_tipe_ujian=" + tipe_ujian + "&";
    }
    if (akreditasi != "") {
      parram += "akreditasi=" + akreditasi + "&";
    }

    url_rumpun = "<?= base_url('api') ?>/mandiri/statistik/m12/" + parram;
    table_rumpun.ajax.url( url_rumpun ).load(null, false);
  }

  function reset_rumpun(){
    $('#form-filter-rumpun')[0].reset();
    statistik_rumpun();
  };

  function load_data_jenis_sekolah()
  {
    var tahun = $('#tahun-jenis_sekolah').val();
    var kategori = $('#kategori-jenis_sekolah').val();
    var tipe_ujian = $('#tipe_ujian-jenis_sekolah').val();
    var akreditasi = $('#akreditasi-jenis_sekolah').val();
    var parram = "?"

    if (tahun != "") {
      parram += "tahun=" + tahun + "&";
    }
    if (kategori != "") {
      parram += "kategori=" + kategori + "&";
    }
    if (tipe_ujian != "") {
      parram += "ids_tipe_ujian=" + tipe_ujian + "&";
    }
    if (akreditasi != "") {
      parram += "akreditasi=" + akreditasi + "&";
    }

    url_jenis_sekolah = "<?= base_url('api') ?>/mandiri/statistik/m13/" + parram;
    
    table_jenis_sekolah = $('#dataTabelJenisSekolah').DataTable({
      processing: true,
      serverside: true,
      ajax: {
        url: url_jenis_sekolah,
        type: "GET",
        dataSrc: function ( json ) {
          return json.data
        }
      },
      columns: [
        { data: null },
        { data: 'jenis_sekolah' },
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
    table_jenis_sekolah.on( 'order.dt search.dt', function () {
        table_jenis_sekolah.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
            cell.innerHTML = i+1;
        } );
    } ).draw();
  }

  function statistik_jenis_sekolah()
  {
    var tahun = $('#tahun-jenis_sekolah').val();
    var kategori = $('#kategori-jenis_sekolah').val();
    var tipe_ujian = $('#tipe_ujian-jenis_sekolah').val();
    var akreditasi = $('#akreditasi-jenis_sekolah').val();
    var parram = "?"

    if (tahun != "") {
      parram += "tahun=" + tahun + "&";
    }
    if (kategori != "") {
      parram += "kategori=" + kategori + "&";
    }
    if (tipe_ujian != "") {
      parram += "ids_tipe_ujian=" + tipe_ujian + "&";
    }
    if (akreditasi != "") {
      parram += "akreditasi=" + akreditasi + "&";
    }

    url_jenis_sekolah = "<?= base_url('api') ?>/mandiri/statistik/m13/" + parram;
    table_jenis_sekolah.ajax.url( url_jenis_sekolah ).load(null, false);
  }

  function reset_jenis_sekolah(){
    $('#form-filter-jenis_sekolah')[0].reset();
    statistik_jenis_sekolah();
  };

  function load_data_jurusan_sekolah()
  {
    var tahun = $('#tahun-jurusan_sekolah').val();
    var kategori = $('#kategori-jurusan_sekolah').val();
    var tipe_ujian = $('#tipe_ujian-jurusan_sekolah').val();
    var akreditasi = $('#akreditasi-jurusan_sekolah').val();
    var parram = "?"

    if (tahun != "") {
      parram += "tahun=" + tahun + "&";
    }
    if (kategori != "") {
      parram += "kategori=" + kategori + "&";
    }
    if (tipe_ujian != "") {
      parram += "ids_tipe_ujian=" + tipe_ujian + "&";
    }
    if (akreditasi != "") {
      parram += "akreditasi=" + akreditasi + "&";
    }

    url_jurusan_sekolah = "<?= base_url('api') ?>/mandiri/statistik/m14/" + parram;
    
    table_jurusan_sekolah = $('#dataTabelJurusanSekolah').DataTable({
      processing: true,
      serverside: true,
      ajax: {
        url: url_jurusan_sekolah,
        type: "GET",
        dataSrc: function ( json ) {
          return json.data
        }
      },
      columns: [
        { data: null },
        { data: 'jurusan_sekolah' },
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
    table_jurusan_sekolah.on( 'order.dt search.dt', function () {
        table_jurusan_sekolah.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
            cell.innerHTML = i+1;
        } );
    } ).draw();
  }

  function statistik_jurusan_sekolah()
  {
    var tahun = $('#tahun-jurusan_sekolah').val();
    var kategori = $('#kategori-jurusan_sekolah').val();
    var tipe_ujian = $('#tipe_ujian-jurusan_sekolah').val();
    var akreditasi = $('#akreditasi-jurusan_sekolah').val();
    var parram = "?"

    if (tahun != "") {
      parram += "tahun=" + tahun + "&";
    }
    if (kategori != "") {
      parram += "kategori=" + kategori + "&";
    }
    if (tipe_ujian != "") {
      parram += "ids_tipe_ujian=" + tipe_ujian + "&";
    }
    if (akreditasi != "") {
      parram += "akreditasi=" + akreditasi + "&";
    }

    url_jurusan_sekolah = "<?= base_url('api') ?>/mandiri/statistik/m14/" + parram;
    table_jurusan_sekolah.ajax.url( url_jurusan_sekolah ).load(null, false);
  }

  function reset_jurusan_sekolah(){
    $('#form-filter-jurusan_sekolah')[0].reset();
    statistik_jurusan_sekolah();
  };
</script>