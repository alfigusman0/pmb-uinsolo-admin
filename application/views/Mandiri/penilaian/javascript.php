<!-- DataTables -->
<script src="<?= base_url() ?>assets/libs/datatables/jquery.dataTables.js"></script>
<script src="<?= base_url() ?>assets/libs/datatables-bs5/datatables-bootstrap5.js"></script>
<script src="<?= base_url() ?>assets/libs/select2/select2.js"></script>
<script src="<?= base_url() ?>assets/libs/toastr/toastr.js"></script>
<script src="<?= base_url() ?>assets/libs/block-ui/block-ui.js"></script>
<script src="https://unpkg.com/pdfobject"></script>
<script type="text/javascript">
	$(document).ready(function() {
		$('.div-detail').hide();
		$("#cari_mahasiswa").select2({
			ajax: {
				url: "<?= base_url('mandiri/penilaian/get-users') ?>",
				type: 'GET',
				data: function(q) {
					return {
						q: q,
						tahun: "<?= date('Y') ?>",
						jenjang_not: 'S1',
						formulir: 'SUDAH',
						pembayaran: 'SUDAH'
					};
				},
				dataType: 'json',
				delay: 300,
				processResults: function(data) {
					return {
						results: data
					};
				},
				cache: true
			},
			placeholder: 'Cari nama atau nomor peserta...',
			minimumInputLength: 3,
		});
	});

	$("#cari_mahasiswa").change(function() {
		$('.div-detail').show();
		var idp_formulir = $(this).val();
		$('.div-detail').block({
			message: '<div class="spinner-border text-white" role="status"></div>',
			css: {
				backgroundColor: 'transparent',
				border: '0'
			},
			overlayCSS: {
				opacity: 0.5
			}
		});
		$.ajax({
			url: "<?= base_url('mandiri/penilaian/get-formulir') ?>",
			data: {
				idp_formulir: idp_formulir,
			},
			type: "GET",
			dataType: "JSON",
			success: function(response) {
				if (response.status == 200) {
					$('#idp_formulir').val(response.data[0].idp_formulir);
					$('#td-nomor-peserta').html(response.data[0].nomor_peserta);
					$('#td-nama').html(response.data[0].nama);
					$('#td-program').html(response.data[0].program);
					$('#td-tipe-ujian').html(response.data[0].tipe_ujian);
					$('#td-pilihan1').html(response.pilihan[0]?.jurusan);
					$('#td-pilihan2').html(response.pilihan[1]?.jurusan);
				} else {
					notif_error("Data tidak ditemukan.")
				}
			},
			error: function(jqXHR, textStatus, errorThrown) {
				notif_error("Tidak dapat mengambil data formulir dari server.")
			}
		}).then(function() {
			$.ajax({
				url: "<?= base_url('mandiri/penilaian/get-file') ?>",
				data: {
					idp_formulir: idp_formulir,
					ids_tipe_file: 14
				},
				type: "GET",
				dataType: "JSON",
				success: function(response) {
					if (response.status == 200) {
						let url = response.data[0].url 
								? response.data[0].url 
								: "<?= $_ENV['HOST_FRONTEND'] ?>upload/mandiri/<?= date('Y') ?>/" + response.data[0].file;

						$('#foto').attr('src', url);
					} else {
						notif_error("Data tidak ditemukan.")
					}
				},
				error: function(jqXHR, textStatus, errorThrown) {
					notif_error("Tidak dapat mengambil data foto dari server.")
				}
			}).then(function() {
				$.ajax({
					url: "<?= base_url('mandiri/penilaian/get-file') ?>",
					data: {
						idp_formulir: idp_formulir,
						ids_tipe_file: 75
					},
					type: "GET",
					dataType: "JSON",
					success: function(response) {
						if (response.status == 200) {
							let pdfUrl = response.data[0].url 
								? response.data[0].url 
								: "<?= $_ENV['HOST_FRONTEND'] ?>upload/mandiri/<?= date('Y') ?>/" + response.data[0].file;

							fetch(pdfUrl)
								.then(res => res.blob())
								.then(blob => {
									// Paksa set Content-Type jadi application/pdf
									const forcedPdfBlob = new Blob([blob], { type: "application/pdf" });
									const blobUrl = URL.createObjectURL(forcedPdfBlob);

									// Gunakan iframe atau PDFObject
									PDFObject.embed(blobUrl, "#my-pdf-proposal");
								})
								.catch(err => {
									console.error("Gagal fetch PDF:", err);
									document.querySelector("#my-pdf-ijazah").innerHTML = "Gagal menampilkan PDF.";
								});


						} else {
							$('#my-pdf-proposal').html('<div class="alert alert-warning">Data tidak ada / belum diupload.</div>')
						}
					},
					error: function(jqXHR, textStatus, errorThrown) {
						notif_error("Tidak dapat mengambil data proposal dari server.")
					}
				}).then(function() {
					$.ajax({
						url: "<?= base_url('mandiri/penilaian/get-file') ?>",
						data: {
							idp_formulir: idp_formulir,
							ids_tipe_file: 71
						},
						type: "GET",
						dataType: "JSON",
						success: function(response) {
							if (response.status == 200) {
								let pdfUrl = response.data[0].url 
									? response.data[0].url 
									: "<?= $_ENV['HOST_FRONTEND'] ?>upload/mandiri/<?= date('Y') ?>/" + response.data[0].file;

								fetch(pdfUrl)
									.then(res => res.blob())
									.then(blob => {
										// Paksa set Content-Type jadi application/pdf
										const forcedPdfBlob = new Blob([blob], { type: "application/pdf" });
										const blobUrl = URL.createObjectURL(forcedPdfBlob);

										// Gunakan iframe atau PDFObject
										PDFObject.embed(blobUrl, "#my-pdf-ijazah");
									})
									.catch(err => {
										console.error("Gagal fetch PDF:", err);
										document.querySelector("#my-pdf-ijazah").innerHTML = "Gagal menampilkan PDF.";
									});

							} else {
								$('#my-pdf-ijazah').html('<div class="alert alert-warning">Data tidak ada / belum diupload.</div>')
							}
						},
						error: function(jqXHR, textStatus, errorThrown) {
							notif_error("Tidak dapat mengambil data ijazah dari server.")
						}
					}).then(function() {
						$.ajax({
							url: "<?= base_url('mandiri/penilaian/get-file') ?>",
							data: {
								idp_formulir: idp_formulir,
								ids_tipe_file: 73
							},
							type: "GET",
							dataType: "JSON",
							success: function(response) {
								if (response.status == 200) {
									let pdfUrl = response.data[0].url 
										? response.data[0].url 
										: "<?= $_ENV['HOST_FRONTEND'] ?>upload/mandiri/<?= date('Y') ?>/" + response.data[0].file;

									fetch(pdfUrl)
										.then(res => res.blob())
										.then(blob => {
											// Paksa set Content-Type jadi application/pdf
											const forcedPdfBlob = new Blob([blob], { type: "application/pdf" });
											const blobUrl = URL.createObjectURL(forcedPdfBlob);

											// Gunakan iframe atau PDFObject
											PDFObject.embed(blobUrl, "#my-pdf-rekomendasi");
										})
										.catch(err => {
											console.error("Gagal fetch PDF:", err);
											document.querySelector("#my-pdf-ijazah").innerHTML = "Gagal menampilkan PDF.";
										});

								} else {
									$('#my-pdf-rekomendasi').html('<div class="alert alert-warning">Data tidak ada / belum diupload.</div>')
								}
							},
							error: function(jqXHR, textStatus, errorThrown) {
								notif_error("Tidak dapat mengambil data surat rekomendasi dari server.")
							}
						}).then(function() {
							$.ajax({
								url: "<?= base_url('mandiri/penilaian/get-nilai') ?>",
								data: {
									idp_formulir: idp_formulir,
									keterangan: 'STUDI NASKAH'
								},
								type: "GET",
								dataType: "JSON",
								success: function(response) {
									if (response.status == 200) {
										$('#studi_naskah').val(response.data[0].nilai);
									}else{
										$('#studi_naskah').val(0);
									}
								},
								error: function(jqXHR, textStatus, errorThrown) {
									notif_error("Tidak dapat mengambil data nilai studi naskah dari server.")
								}
							}).then(function() {
								$.ajax({
									url: "<?= base_url('mandiri/penilaian/get-nilai') ?>",
									data: {
										idp_formulir: idp_formulir,
										keterangan: 'PROPOSAL'
									},
									type: "GET",
									dataType: "JSON",
									success: function(response) {
										if (response.status == 200) {
											$('#proposal').val(response.data[0].nilai);
										}else{
											$('#proposal').val(0);
										}
									},
									error: function(jqXHR, textStatus, errorThrown) {
										notif_error("Tidak dapat mengambil data nilai proposal dari server.")
									}
								}).then(function() {
									$.ajax({
										url: "<?= base_url('mandiri/penilaian/get-nilai') ?>",
										data: {
											idp_formulir: idp_formulir,
											keterangan: 'MODERASI BERAGAMA'
										},
										type: "GET",
										dataType: "JSON",
										success: function(response) {
											if (response.status == 200) {
												$('#moderasi').val(response.data[0].nilai);
											}else{
												$('#moderasi').val(0);
											}
										},
										error: function(jqXHR, textStatus, errorThrown) {
											notif_error("Tidak dapat mengambil data nilai moderasi beragama dan wawasan kebangsaan dari server.")
										}
									}).then(function(){
										$('.div-detail').unblock();
									})
								})
							})
						})
					})
				})
			})
		})
	})

	function load_data(id) {
		$('.div-detail').show();
		var idp_formulir = id;
		$('.div-detail').block({
			message: '<div class="spinner-border text-white" role="status"></div>',
			css: {
				backgroundColor: 'transparent',
				border: '0'
			},
			overlayCSS: {
				opacity: 0.5
			}
		});
		$.ajax({
			url: "<?= base_url('mandiri/penilaian/get-formulir') ?>",
			data: {
				idp_formulir: idp_formulir,
			},
			type: "GET",
			dataType: "JSON",
			success: function(response) {
				if (response.status == 200) {
					$('#idp_formulir').val(response.data[0].idp_formulir);
					$('#td-nama').html(response.data[0].nama);
					$('#td-nomor-peserta').html(response.data[0].nomor_peserta);
					$('#td-program').html(response.data[0].program);
					$('#td-tipe-ujian').html(response.data[0].tipe_ujian);
				} else {
					notif_error("Data tidak ditemukan.")
				}
			},
			error: function(jqXHR, textStatus, errorThrown) {
				notif_error("Tidak dapat mengambil data formulir dari server.")
			}
		}).then(function() {
			$.ajax({
				url: "<?= base_url('mandiri/penilaian/get-file') ?>",
				data: {
					idp_formulir: idp_formulir,
					ids_tipe_file: 14
				},
				type: "GET",
				dataType: "JSON",
				success: function(response) {
					if (response.status == 200) {
						let url = response.data[0].url 
								? response.data[0].url 
								: "<?= $_ENV['HOST_FRONTEND'] ?>upload/mandiri/<?= date('Y') ?>/" + response.data[0].file;

						$('#foto').attr('src', url);
					} else {
						notif_error("Data tidak ditemukan.")
					}
				},
				error: function(jqXHR, textStatus, errorThrown) {
					notif_error("Tidak dapat mengambil data foto dari server.")
				}
			}).then(function() {
				$.ajax({
					url: "<?= base_url('mandiri/penilaian/get-file') ?>",
					data: {
						idp_formulir: idp_formulir,
						ids_tipe_file: 37
					},
					type: "GET",
					dataType: "JSON",
					success: function(response) {
						if (response.status == 200) {
							PDFObject.embed("<?= $_ENV['HOST_FRONTEND'] ?>upload/mandiri/<?= date('Y') ?>/" + response.data[0].file, "#my-pdf");
						} else {
							notif_error("Data tidak ditemukan.")
						}
					},
					error: function(jqXHR, textStatus, errorThrown) {
						notif_error("Tidak dapat mengambil data proposal dari server.")
					}
				}).then(function() {
					$.ajax({
						url: "<?= base_url('mandiri/penilaian/get-nilai') ?>",
						data: {
							idp_formulir: idp_formulir,
							keterangan: 'STUDI NASKAH'
						},
						type: "GET",
						dataType: "JSON",
						success: function(response) {
							if (response.status == 200) {
								$('#studi_naskah').val(response.data[0].nilai);
							}else{
								$('#studi_naskah').val(0);
							}
						},
						error: function(jqXHR, textStatus, errorThrown) {
							notif_error("Tidak dapat mengambil data nilai studi naskah dari server.")
						}
					}).then(function() {
						$.ajax({
							url: "<?= base_url('mandiri/penilaian/get-nilai') ?>",
							data: {
								idp_formulir: idp_formulir,
								keterangan: 'PROPOSAL'
							},
							type: "GET",
							dataType: "JSON",
							success: function(response) {
								if (response.status == 200) {
									$('#proposal').val(response.data[0].nilai);
								}else{
									$('#proposal').val(0);
								}
							},
							error: function(jqXHR, textStatus, errorThrown) {
								notif_error("Tidak dapat mengambil data nilai proposal dari server.")
							}
						}).then(function() {
							$.ajax({
								url: "<?= base_url('mandiri/penilaian/get-nilai') ?>",
								data: {
									idp_formulir: idp_formulir,
									keterangan: 'MODERASI BERAGAMA'
								},
								type: "GET",
								dataType: "JSON",
								success: function(response) {
									if (response.status == 200) {
										$('#moderasi').val(response.data[0].nilai);
									}else{
										$('#moderasi').val(response.data[0].nilai);
									}
									$('.div-detail').unblock();
								},
								error: function(jqXHR, textStatus, errorThrown) {
									notif_error("Tidak dapat mengambil data nilai moderasi beragama dan wawasan kebangsaan dari server.")
								}
							})
						})
					})
				})
			})
		})
	}

	function simpan_nilai() {
		$('#btnSimpan').text('Loading...'); //change button text
		$('#btnSimpan').attr('disabled', true); //set button disable

		if($('#studi_naskah').val() > 40){
			notif_error("Nilai studi naskah tidak boleh lebih dari 40")
			$('#btnSimpan').text('Simpan'); //change button text
			$('#btnSimpan').attr('disabled', false); //set button enable
			return;
		}

		if($('#proposal').val() > 40){
			notif_error("Nilai proposal tidak boleh lebih dari 40")
			$('#btnSimpan').text('Simpan'); //change button text
			$('#btnSimpan').attr('disabled', false); //set button enable
			return;
		}

		if($('#moderasi').val() > 20){
			notif_error("Nilai moderasi beragama dan wawasan kebangsaan tidak boleh lebih dari 20")
			$('#btnSimpan').text('Simpan'); //change button text
			$('#btnSimpan').attr('disabled', false); //set button enable
			return;
		}

		// ajax adding data to database
		$.ajax({
			url: "<?php echo base_url('mandiri/penilaian/update/studi-naskah/') ?>" + $('#idp_formulir').val(),
			type: 'POST',
			data: {
				nilai: $('#studi_naskah').val()
			},
			dataType: "JSON",
			success: function(response) {
				if (response.status == 200) //if success close modal and reload ajax table
				{
					notif_success(response.message)
				} else {
					notif_error(response.message)
				}
			},
			error: function(jqXHR, textStatus, errorThrown) {
				notif_error("Error update nilai studi naskah");
				$('#btnSimpan').text('Simpan'); //change button text
				$('#btnSimpan').attr('disabled', false); //set button enable
			}
		}).then(function() {
			$.ajax({
				url: "<?php echo base_url('mandiri/penilaian/update/proposal/') ?>" + $('#idp_formulir').val(),
				type: 'POST',
				data: {
					nilai: $('#proposal').val()
				},
				dataType: "JSON",
				success: function(response) {
					if (response.status == 200) //if success close modal and reload ajax table
					{
						notif_success(response.message)
					} else {
						notif_error(response.message)
					}
				},
				error: function(jqXHR, textStatus, errorThrown) {
					notif_error("Error update nilai proposal");
					$('#btnSimpan').text('Simpan'); //change button text
					$('#btnSimpan').attr('disabled', false); //set button enable
				}
			})
		}).then(function() {
			$.ajax({
				url: "<?php echo base_url('mandiri/penilaian/update/moderasi/') ?>" + $('#idp_formulir').val(),
				type: 'POST',
				data: {
					nilai: $('#moderasi').val()
				},
				dataType: "JSON",
				success: function(response) {
					if (response.status == 200) //if success close modal and reload ajax table
					{
						notif_success(response.message)
						// load_data($('#idp_formulir').val())
					} else {
						notif_error(response.message)
					}
					$('#btnSimpan').text('Simpan'); //change button text
					$('#btnSimpan').attr('disabled', false); //set button enable
				},
				error: function(jqXHR, textStatus, errorThrown) {
					notif_error("Error update nilai moderasi beragama dan wawasan kebangsaan");
					$('#btnSimpan').text('Simpan'); //change button text
					$('#btnSimpan').attr('disabled', false); //set button enable
				}
			})
		});
	}

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

<script>
$('#btnLihatRekap').on('click', function () {
  const $btn = $(this);
  const originalText = $btn.html();

  // Disable dan ganti teks tombol
  $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span> Memuat...');

  // Kosongkan isi tabel dulu
  $('#rekapTableBody').html('<tr><td colspan="6">Memuat data...</td></tr>');

  // Ajax request
  $.ajax({
    url: "<?= base_url('mandiri/penilaian/get-rekap-nilai') ?>",
    method: "GET",
    dataType: "json",
    success: function (res) {
      let rows = '';
      if (res.status === 200 && Array.isArray(res.data)) {
        res.data.forEach((item, index) => {
          rows += `
            <tr>
              <td>${index + 1}</td>
              <td>${item.nomor_peserta}</td>
              <td>${item.nama}</td>
              <td>${item.nilai_studi_naskah ?? 0}</td>
              <td>${item.nilai_proposal ?? 0}</td>
              <td>${item.nilai_moderasi_beragama ?? 0}</td>
              <td>${item.updated_by}</td>
            </tr>
          `;
        });
      } else {
        rows = '<tr><td colspan="6">Data tidak ditemukan / Kosong</td></tr>';
      }
      $('#rekapTableBody').html(rows);
      $('#modalRekapNilai').modal('show');
    },
    error: function () {
      $('#rekapTableBody').html('<tr><td colspan="6">Terjadi kesalahan saat mengambil data</td></tr>');
      $('#modalRekapNilai').modal('show');
    },
    complete: function () {
      // Kembalikan tombol ke semula
      $btn.prop('disabled', false).html(originalText);
    }
  });
});
</script>
