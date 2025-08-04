<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title>SK</title>
		<style type="text/css">
			.container {margin-right: auto; margin-left: auto; padding-left: 15px; padding-right: 15px;}
			.row {margin-left: -5px; margin-right: -15px;}
			.col-md-1, .col-md-2, .col-md-3, .col-md-4, .col-md-5, .col-md-6, .col-md-7, .col-md-8, .col-md-9, .col-md-10, .col-md-11, .col-md-12 {float: left;}
			.col-md-12 {width: 100%;}
			.col-md-11 {width: 91.66666667%;}
			.col-md-10 {width: 83.33333333%;}
			.col-md-9 {width: 75%;}
			.col-md-8 {width: 66.66666667%;}
			.col-md-7 {width: 58.33333333%;}
			.col-md-6 {width: 50%;}
			.col-md-5 {width: 41.66666667%;}
			.col-md-4 {width: 33.33333333%;}
			.col-md-3 {width: 25%;}
			.col-md-offset-7 {margin-left: 58.33333333%;}
			.col-md-2 {width: 16.66666667%;}
			.col-md-1 {width: 8.33333333%;}
			.text-center {text-align: center;}
			.thumbnail {display: block;padding: 4px;margin-bottom: 20px;line-height: 1.42857143;background-color: #ffffff;border: 1px solid #dddddd;border-radius: 4px;-webkit-transition: border 0.2s ease-in-out;-o-transition: border 0.2s ease-in-out;transition: border 0.2s ease-in-out; height:150px;}
			.center{
				width: 80%;
				margin: 0 auto;
				text-align: center;
			}
			table{border-spacing:0;border-collapse:collapse}td,th{padding:5px}
			.table{border-collapse:collapse!important}.table td,.table th{background-color:#fff!important}.table-bordered td,.table-bordered th{border:1px solid black!important;}
			@page *{
					margin-top: 0cm;
					margin-bottom: 2.54cm;
					margin-left: 3.175cm;
					margin-right: 3.175cm;
			}
			.lower-alpha {
				list-style: lower-alpha;
			}
			.table-list tr th {
				border: 1px solid;
				padding: 2;
			}
			.table-list tr td {
				border: 1px solid;
				padding: 2;
			}
		</style>
	</head>
	<body style="font-family: Cambria, Georgia, serif;">
		<div class="row" style="margin-top: 15px">
			<div class="col-md-12" style="text-align: justify">
				<b>Lampiran</b>
				<div class="col-md-2">
					NOMOR&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:
				</div>
				<div class="col-md-9" style="text-align: justify">
					B-1344/Un.05/I.1/PP.00.9/08/2023
				</div>
				<div class="col-md-2">
					TENTANG&nbsp;&nbsp;:
				</div>
				<div class="col-md-9" style="text-align: justify">
					PENETAPAN PESERTA LULUS SELEKSI PENERIMAAN MAHASISWA BARU SETELAH MASA SANGGAH PROGRAM SARJANA UIN SUNAN GUNUNG DJATI BANDUNG TAHUN AKADEMIK TAHUN AKADEMIK 2023/2024
				</div>
			</div>
			<div class="col-md-12" style="margin-top: 15px">
				<div style="margin-bottom: 7px"><?=($fakultas != 'Semua') ? 'Fakultas : '.$fakultas : ''?></div>
				<table class="table-list" style="width: 100%">
					<tr>
						<th>No.</th>
						<th>Nomor Peserta</th>
						<th>Nama</th>
						<th>Kode</th>
						<th>Nama Prodi</th>
					</tr>
					<?php $no=1; foreach($viewpKelulusan as $a){ ?>
						<?php
							$rules = array(
								'database'  => null, //Default database master
								'select'    => null,
								'where'     => array(
									'idp_formulir' => $a->idp_formulir
								),
								'or_where'  => null,
								'like'      => null,
								'or_like'   => null,
								'order'     => null,
								'limit'     => null,
								'group_by'  => null,
							);
							$tbpSanggah = $this->Tbp_sanggah->search($rules)->num_rows();
							if($tbpSanggah == 0){
								continue;
							}
						?>
						<tr>
							<td style="text-align: center; width: 40px"><?=$no++?></td>
							<td style="text-align: center;"><?=$a->nomor_peserta?></td>
							<td><?=$a->nama?></td>
							<td style="text-align: center; width: 40px"><?=$a->kode_jurusan?></td>
							<td style="text-align: center;"><?=$a->jurusan?></td>
						</tr>
					<?php } ?>
				</table>
			</div>
			
			<div class="row" style="margin-top: 15px">
				<div class="col-md-6">
					&nbsp;
				</div>
				<div class="col-md-5">
					<table>
						<tr>
							<td style="padding: 1">Ditetapkan di</td>
							<td style="padding: 1">&nbsp;:&nbsp;</td>
							<td style="padding: 1">Bandung</td>
						</tr>
						<tr>
							<td style="padding: 1">Pada Tanggal</td>
							<td style="padding: 1">&nbsp;:&nbsp;</td>
							<td style="padding: 1">8 Agustus 2023</td>
						</tr>
						<tr>
							<td colspan="3" style="padding: 1">Rektor,</td>
						</tr>
						<tr>
							<td><br><br><br><br><br></td>
						</tr>
						<tr>
							<td colspan="3" style="padding: 1">Prof. Dr. H. Mahmud, M.Si, CSEE</td>
						</tr>
						<tr>
							<td colspan="3" style="padding: 1">NIP. 196204101988031001</td>
						</tr>
					</table>
				</div>
			</div>
		</div>
	</body>
</html>