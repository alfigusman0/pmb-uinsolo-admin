<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<style type="text/css">
		body {
			font-family: 'Times New Roman', Times, serif;
		}

		.container {
			margin-right: auto;
			margin-left: auto;
			padding-left: 15px;
			padding-right: 15px;
		}

		.row {
			margin-left: -5px;
			margin-right: -15px;
		}

		.col-md-1,
		.col-md-2,
		.col-md-3,
		.col-md-4,
		.col-md-5,
		.col-md-6,
		.col-md-7,
		.col-md-8,
		.col-md-9,
		.col-md-10,
		.col-md-11,
		.col-md-12 {
			float: left;
		}

		.col-md-12 {
			width: 100%;
		}

		.col-md-11 {
			width: 91.66666667%;
		}

		.col-md-10 {
			width: 83.33333333%;
		}

		.col-md-9 {
			width: 75%;
		}

		.col-md-8 {
			width: 66.66666667%;
		}

		.col-md-7 {
			width: 58.33333333%;
		}

		.col-md-6 {
			width: 50%;
		}

		.col-md-5 {
			width: 41.66666667%;
		}

		.col-md-4 {
			width: 33.33333333%;
		}

		.col-md-3 {
			width: 25%;
		}

		.col-md-offset-2 {
			margin-left: 18.33333333%;
		}

		.col-md-offset-7 {
			margin-left: 58.33333333%;
		}

		.col-md-2 {
			width: 16.66666667%;
		}

		.col-md-1 {
			width: 8.33333333%;
		}

		.text-center {
			text-align: center;
		}

		.thumbnail {
			display: block;
			padding: 4px;
			margin-bottom: 20px;
			line-height: 1.42857143;
			background-color: #ffffff;
			border: 1px solid #dddddd;
			border-radius: 4px;
			-webkit-transition: border 0.2s ease-in-out;
			-o-transition: border 0.2s ease-in-out;
			transition: border 0.2s ease-in-out;
		}

		.table-border {
			border: 1px solid black;
			border-collapse: collapse;
			padding: 2px;
		}
	</style>
</head>

<body>
	<div class="row">
		<div class="col-md-2">
			<img src="<?= './assets/img/logo_uin2.png'; ?>" style="margin-left: 50px;width:60%;height: 80%;">
		</div>
		<div class="col-md-9">
			<p class="text-center">
				<span style="font-size: 14px; font-weight: bold;">
					KEMENTERIAN AGAMA REPUBLIK INDONESIA <br>
					UNIVERSITAS ISLAM NEGERI <br>
					SUNAN GUNUNG DJATI BANDUNG
				</span><br>
				<span style="font-size: 10px;">
					Jalan A.H. Nasution No. 105 Cibiru Bandung 40614 Telepon (022) 7800525 Fak (022) 7803936<br>
					Website: www.uinsgd.ac.id e-mail: info@uinsgd.ac.id
				</span>
			</p>
		</div>
	</div>
	<hr style="border-color: black;margin: 3px;" />
	<br>
	<div class="row">
		<div class="text-center" style="font-size: 18px; font-weight: bold; padding-top: 10px; padding-bottom: 10px">
			DAFTAR KELAS
		</div>
	</div>
	<div class="row">
		<div style="font-weight: bold; padding-top: 5px; padding-bottom: 10px">
			<table>
				<tr>
					<th style="text-align:left">FAKULTAS</th>
					<td>:</td>
					<td><?= $fakultas ?></td>
				</tr>
				<tr>
					<th style="text-align:left">JURUSAN</th>
					<td>:</td>
					<td><?= $jurusan ?></td>
				</tr>
			</table>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			<table style="width: 100%;" class="table-border">
				<tr>
					<th class="table-border">No</th>
					<th class="table-border">NIM</th>
					<th class="table-border">Nama</th>
					<?php if ($jenjang == 'S3'): ?>
						<th class="table-border">Konsentrasi</th>
					<?php endif; ?>
					<th class="table-border">Kelas</th>
				</tr>
				<?php $no = 1;
				foreach ($viewdKelulusan as $a): ?>
					<tr>
						<td class="table-border text-center"><?= $no++ ?></td>
						<td class="table-border text-center"><?= $a->nim ?></td>
						<td class="table-border"><?= $a->nama ?></td>
						<?php if ($jenjang == 'S3'): ?>
							<td class="table-border"><?= $a->konsentrasi ?></td>
						<?php endif; ?>
						<td class="table-border text-center"><?= $a->kelas ?></td>
					</tr>
				<?php endforeach; ?>
			</table>
		</div>
	</div>
</body>

</html>