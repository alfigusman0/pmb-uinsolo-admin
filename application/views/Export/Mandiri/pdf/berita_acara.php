<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Berita Acara</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <style>
    * {
      box-sizing: border-box;
    }

    body {
      font-family: Arial, sans-serif;
      font-size: 12px;
      margin: 20px;
    }

    .row::after {
      content: "";
      display: table;
      clear: both;
    }

    .col {
      float: left;
      padding: 5px;
    }

    .col-2 { width: 20%; }
    .col-3 { width: 25%; }
    .col-4 { width: 33.33%; }
    .col-6 { width: 50%; }
    .col-7 { width: 58.33%; }
    .col-9 { width: 75%; }
    .col-12 { width: 100%; }
    .col-6-2 { width: 60%; }

    .text-center {
      text-align: center;
    }

    .table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 10px;
      margin-bottom: 10px;
    }

    .table td, .table th {
      border: 1px solid black;
      padding: 6px;
      vertical-align: middle;
    }

    .table-no-border td {
      border: none;
      padding: 4px;
    }

    .logo {
      width: 80px;
      height: auto;
    }

    .content-wrapper {
      width: 90%;
      margin: auto;
    }

    .signature-space {
      margin-top: 30px;
    }

    .signature-space div {
      margin-bottom: 10px;
    }
		.text-center {
      text-align: center;
    }

    .header-logo img {
      max-width: 100%;
      height: auto;
    }
    hr {
      margin: 10px 0;
    }
  </style>
</head>
<body>

  <!-- Header -->
  <div class="row">
    <div class="col col-2 text-center header-logo">
      <img src="<?= base_url('assets/img/logo_uin2.png') ?>" alt="Logo UIN" width="80">
    </div>
    <div class="col col-6-2">
      <p class="text-center">
        <strong>BERITA ACARA PELAKSANAAN</strong><br>
        SELEKSI MASUK <?= strtoupper($viewsJadwal->tipe_ujian) ?><br>
        UNIVERSITAS ISLAM NEGERI SUNAN GUNUNG DJATI BANDUNG<br>
        TAHUN <?= date('Y') ?> / <?= date('Y') + 1 ?><br>
        <small>
          Jalan A.H. Nasution No. 105 Cibiru Bandung 40614<br>
          Telp: (022) 7800525, Fax: (022) 7803936<br>
          Website: pmb.uinsgd.ac.id | Email: pmb@uinsgd.ac.id
        </small>
      </p>
    </div>
    <div class="col col-2 text-center header-logo">
      <img src="<?= base_url('assets/img/logo-admisi2.png') ?>" alt="Logo PMB" width="80">
    </div>
  </div>

  <hr>

  <!-- Info Jadwal -->
  <div class="content-wrapper">
    <div class="row">
      <div class="col col-6">
        <table class="table-no-border">
          <tr><td>Hari</td><td>:</td><td><?= $this->utilities->hari_ini($viewsJadwal->tanggal) ?></td></tr>
          <tr><td>Tanggal</td><td>:</td><td><?= $this->utilities->tgl_indo($viewsJadwal->tanggal) ?></td></tr>
          <tr><td>Tipe Ujian</td><td>:</td><td><?= $viewsJadwal->tipe_ujian ?></td></tr>
        </table>
      </div>
      <div class="col col-6">
        <table class="table-no-border">
          <tr><td>Ruangan</td><td>:</td><td><?= $viewsJadwal->ruangan ?></td></tr>
          <tr><td>Gedung</td><td>:</td><td><?= $viewsJadwal->gedung ?></td></tr>
        </table>
      </div>
    </div>

    <!-- Info Pelaksanaan -->
    <h3 class="text-center">PELAKSANAAN UJIAN</h3>
    <table class="table-no-border">
      <tr>
        <td>Ujian dimulai pukul</td>
        <td>:</td>
        <td><?= date('H:i', strtotime($viewsJadwal->jam_awal)) ?> - <?= date('H:i', strtotime($viewsJadwal->jam_akhir)) ?> WIB</td>
      </tr>
      <tr>
        <td>Jumlah peserta yang hadir</td>
        <td>:</td>
        <td>...................................... Orang</td>
      </tr>
      <tr>
        <td>Tidak hadir</td>
        <td>:</td>
        <td>...................................... Orang</td>
      </tr>
    </table>

    <!-- Daftar Tidak Hadir -->
    <h3 class="text-center">NOMOR PESERTA YANG TIDAK HADIR</h3>

    <?php for ($i = 0; $i < 4; $i++): ?>
      <div class="row text-center">
        <?php for ($j = 0; $j < 4; $j++): ?>
          <div class="col col-3">..............................</div>
        <?php endfor; ?>
      </div>
    <?php endfor; ?>

    <!-- Catatan -->
    <h3 class="text-center">HAL-HAL YANG PERLU DICATAT</h3>
    <p style="min-height: 60px; border: 1px solid #000; padding: 10px;">&nbsp;</p>

    <!-- Tanda Tangan -->
    <div class="signature-space">
      <div>
        Pengawas Ujian 1: &nbsp;&nbsp; Nama: .................................... &nbsp;&nbsp; Tanda Tangan: ....................................
      </div>
      <div>
        Pengawas Ujian 2: &nbsp;&nbsp; Nama: .................................... &nbsp;&nbsp; Tanda Tangan: ....................................
      </div>
    </div>
  </div>
	<script>
  	window.onload = () => window.print();
	</script>
</body>
</html>
