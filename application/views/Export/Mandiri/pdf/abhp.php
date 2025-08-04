<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Kartu Peserta</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <style>
    * {
      box-sizing: border-box;
    }

    body {
      font-family: Arial, sans-serif;
      margin: 20px;
      font-size: 12px;
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
    .col-6 { width: 60%; }
    .col-4 { width: 40%; }
    .col-7 { width: 58.33%; }
    .col-12 { width: 100%; }

    .text-center {
      text-align: center;
    }

    .header-logo img {
      max-width: 100%;
      height: auto;
    }

    .table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 10px;
      margin-bottom: 10px;
    }

    .table-bordered th, .table-bordered td {
      border: 1px solid black;
      padding: 5px;
      vertical-align: top;
    }

    ol {
      padding-left: 20px;
      margin: 0;
    }

    hr {
      margin: 10px 0;
    }
  </style>
</head>
<body>

  <div class="row">
    <div class="col col-2 text-center header-logo">
      <img src="<?= base_url('assets/img/logo_uin2.png') ?>" alt="Logo UIN" width="80">
    </div>
    <div class="col col-6">
      <p class="text-center">
        <strong>ALBUM BUKTI HADIR PESERTA</strong><br>
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

  <div class="row">
    <div class="col col-7">
      <table class="table">
        <tr><td>Tipe Ujian</td><td>:</td><td><?= $viewsJadwal->tipe_ujian ?></td></tr>
        <tr><td>Gedung</td><td>:</td><td><?= $viewsJadwal->gedung ?></td></tr>
        <tr><td>Tanggal</td><td>:</td><td><?= $this->utilities->tgl_indo($viewsJadwal->tanggal) ?></td></tr>
      </table>
    </div>
    <div class="col col-4">
      <table class="table">
        <tr><td>Ruangan</td><td>:</td><td><?= $viewsJadwal->ruangan ?></td></tr>
        <tr>
          <td>Waktu</td><td>:</td>
          <td><?= date('H:i', strtotime($viewsJadwal->jam_awal)) ?> - <?= date('H:i', strtotime($viewsJadwal->jam_akhir)) ?> WIB</td>
        </tr>
      </table>
    </div>
  </div>

  <table class="table table-bordered">
    <thead>
      <tr>
        <th>No.</th>
        <th>No. Ujian</th>
        <th>Foto</th>
        <th>Nama</th>
        <th>Program Pilihan Studi</th>
        <th>Tanda Tangan</th>
      </tr>
    </thead>
    <tbody>
      <?php $no = 1; $ttd = 1; foreach ($viewpJadwal as $value): ?>
        <tr>
          <td class="text-center" style="vertical-align: middle"><?= $no++ ?></td>
          <td class="text-center" style="vertical-align: middle"><?= $value->nomor_peserta ?></td>
          <td class="text-center">
            <img src="<?= $tbpFileFoto[$value->idp_formulir]->url != '' ? $tbpFileFoto[$value->idp_formulir]->url : 'upload/mandiri/'.date('Y').'/'.$tbpFileFoto[$value->idp_formulir]->file; ?>" style="width: 70px; height: auto;" alt="Foto Peserta">
          </td>
					
          <td class="text-center" style="vertical-align: middle"><?= $value->nama ?></td>
          <td class="text-center" style="vertical-align: middle">
              <?php $num=1; foreach ($viewpPilihan[$value->idp_formulir] as $a): ?>
                <?=$num++?>. <?= $a->jurusan ?></br>
              <?php endforeach; ?>
          </td>
          <td><?= $ttd++ ?></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
	<script>
  	window.onload = () => window.print();
	</script>
</body>
</html>
