<div class="container-xxl flex-grow-1 container-p-y">
  <h4 class="py-3 breadcrumb-wrapper mb-4">Detail Mahasiswa</h4>
  <div class="row">
    <div class="col-md-6">
      <p>
      <div class="card">
        <div class="card-header header-elements">
          <span class="me-2 h6">Data Formulir</span>
          <div class="card-header-elements ms-auto">
            <button type="button" style="margin-top: -15px" onclick="edit_data_formulir(<?= $this->uri->segment(4) ?>)" class="btn btn-xs btn-warning">
              <span class="tf-icon bx bx-edit bx-xs"></span>
            </button>
          </div>
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-hover">
              <tr>
                <th>Kode Bayar</th>
                <td>:</td>
                <td><?= date('Y', strtotime($viewpFormulir->date_created)).$viewpFormulir->idp_formulir ?></td>
              </tr>
              <tr>
                <th>Nomor Peserta</th>
                <td>:</td>
                <td><?= $viewpFormulir->nomor_peserta ?></td>
              </tr>
              <tr>
                <th>Nama</th>
                <td>:</td>
                <td><?= $viewpFormulir->nama ?></td>
              </tr>
              <tr>
                <th>Kategori</th>
                <td>:</td>
                <td><?= $viewpFormulir->kategori ?></td>
              </tr>
              <tr>
                <th>Program</th>
                <td>:</td>
                <td><?= $viewpFormulir->program ?></td>
              </tr>
              <tr>
                <th>Tipe Ujian</th>
                <td>:</td>
                <td><?= $viewpFormulir->tipe_ujian ?></td>
              </tr>
              <tr>
                <th>Foto</th>
                <td>:</td>
                <td>
                  <?php 
                    if($viewpFileFoto->num_rows() > 0):
                      $viewpFileFoto = $viewpFileFoto->row();
                      if($viewpFileFoto->url == ''){
                        echo "<a href='https://damba.uinsgd.ac.id/upload/mandiri/" . date('Y') . "/" . $viewpFileFoto->file . "' class='btn btn-primary btn-xs' target='_blank'><i class='bx bx-show'></i> Lihat</a>";
                      }else{
                        echo "<a href='$viewpFileFoto->url' class='btn btn-primary btn-xs' target='_blank'><i class='bx bx-show'></i> Lihat</a>";
                      }
                    else:
                      echo '';
                    endif; 
                  ?>
                </td>
              </tr>
              <tr>
                <th>Formulir</th>
                <td>:</td>
                <td><?= ($viewpFormulir->formulir == 'BELUM') ? '<div class="badge bg-danger">Belum</div>' : '<div class="badge bg-success">Sudah</div>' ?></td>
              </tr>
              <tr>
                <th>Pembayaran</th>
                <td>:</td>
                <td><?= ($viewpFormulir->pembayaran == 'BELUM') ? '<div class="badge bg-danger">Belum</div>' : '<div class="badge bg-success">Sudah</div>' ?></td>
              </tr>
              <tr>
                <th>Keterangan Pembayaran</th>
                <td>:</td>
                <td><?= $viewpFormulir->ket_pembayaran ?></td>
              </tr>
            </table>
          </div>
        </div>
      </div>
      </p>
      <p>
      <div class="card">
        <div class="card-header header-elements">
          <span class="me-2 h6">Data Biodata</span>
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-hover">
              <?php if ($viewpBiodata->num_rows() > 0) : ?>
                <?php $viewpBiodata = $viewpBiodata->row(); ?>
                <tr>
                  <th>NIK</th>
                  <td>:</td>
                  <td><?= $viewpBiodata->nik ?></td>
                </tr>
                <tr>
                  <th>Jenis Kelamin</th>
                  <td>:</td>
                  <td><?= $viewpBiodata->jenis_kelamin ?></td>
                </tr>
                <tr>
                  <th>Tempat Lahir</th>
                  <td>:</td>
                  <td><?= $viewpBiodata->tempat_lahir ?></td>
                </tr>
                <tr>
                  <th>Tanggal Lahir</th>
                  <td>:</td>
                  <td><?= $viewpBiodata->tgl_lahir ?></td>
                </tr>
                <tr>
                  <th>Agama</th>
                  <td>:</td>
                  <td><?= $viewpBiodata->agama ?></td>
                </tr>
                <tr>
                  <th>Kewarganegaraan</th>
                  <td>:</td>
                  <td><?= $viewpBiodata->kewarganegaraan ?></td>
                </tr>
                <tr>
                  <th>Kebutuhan Khusus</th>
                  <td>:</td>
                  <td><?= $viewpBiodata->keb_khusus ?></td>
                </tr>
              <?php else : ?>
                <tr>
                  <td colspan='3' class="text-center">Data Kosong.</td>
                </tr>
              <?php endif; ?>
            </table>
          </div>
        </div>
      </div>
      </p>
      <p>
      <?php if($viewpFormulir->jenjang == 'S2' || $viewpFormulir->jenjang == 'S3'): ?>
      <div class="card">
        <div class="card-header header-elements">
          <span class="me-2 h6">Data Riwayat Pendidikan</span>
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-hover" id="dataTabel">
              <thead>
                <tr>
                  <th>No.</th>
                  <th>Jenjang</th>
                  <th>Nama Universitas (Status)</th>
                  <th>Fakultas / Jurusan (Akreditasi)</th>
                  <th>Jalur Penyesuaian Studi</th>
                  <th>IPK</th>
                  <th>Tanggal Lulus</th>
                  <th>Gelar</th>
                </tr>
              </thead>
              <tbody>
                <?php if ($tbpPendidikan->num_rows() > 0) : $tbpPendidikan = $tbpPendidikan->result() ?>
                  <?php $no = 1;
                  foreach ($tbpPendidikan as $value) : ?>
                    <tr>
                      <td><?= $no++; ?></td>
                      <td><?= $value->jenjang ?></td>
                      <td><?= $value->nama_univ ?> (<?=$value->status_univ?>)</td>
                      <td><?= $value->fakultas ?> / <?=$value->jurusan?> (<?=$value->akreditasi?>)</td>
                      <td><?= $value->jalur_penyesuaian_studi ?></td>
                      <td><?= $value->ipk ?></td>
                      <td><?= $value->tgl_lulus ?></td>
                      <td><?= $value->gelar ?></td>
                    </tr>
                  <?php endforeach; ?>
                <?php endif; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
      <?php else: ?>
      <div class="card">
        <div class="card-header header-elements">
          <span class="me-2 h6">Data Sekolah</span>
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-hover">
              <?php if ($viewpSekolah->num_rows() > 0) : ?>
                <?php $viewpSekolah = $viewpSekolah->row(); ?>
                <tr>
                  <th>NISN</th>
                  <td>:</td>
                  <td><?= $viewpSekolah->nisn ?></td>
                </tr>
                <tr>
                  <th>Jenis Sekolah</th>
                  <td>:</td>
                  <td><?= $viewpSekolah->jenis_sekolah ?></td>
                </tr>
                <tr>
                  <th>Rumpun</th>
                  <td>:</td>
                  <td><?= $viewpSekolah->rumpun ?></td>
                </tr>
                <tr>
                  <th>Jurusan Sekolah</th>
                  <td>:</td>
                  <td><?= $viewpSekolah->jurusan_sekolah ?></td>
                </tr>
                <tr>
                  <th>Nama Sekolah</th>
                  <td>:</td>
                  <td><?= $viewpSekolah->nama_sekolah ?></td>
                </tr>
                <tr>
                  <th>Akreditasi Sekolah</th>
                  <td>:</td>
                  <td><?= $viewpSekolah->akreditasi_sekolah ?></td>
                </tr>
              <?php else : ?>
                <tr>
                  <td colspan='3' class="text-center">Data Kosong.</td>
                </tr>
              <?php endif; ?>
            </table>
          </div>
        </div>
      </div>
      <?php endif; ?>
      </p>
      <?php if($viewpFormulir->jenjang == 'S1'): ?>
      <p>
      <div class="card">
        <div class="card-header header-elements">
          <span class="me-2 h6">Data Prestasi</span>
        </div>
        <div class="card-body">
          <div class="table-responsive" id="tabel_berkas">
            <table class="table table-hover table-bordered">
              <thead>
                <tr>
                  <th class="text-center">No.</th>
                  <th class="text-center">Prestasi</th>
                  <th class="text-center">Tingkat</th>
                  <th class="text-center">Sertifikat</th>
                </tr>
                <thead>
                <tbody>
                <?php if ($viewpPrestasi->num_rows() > 0) : ?>
                  <?php $no = 1;
                  foreach ($viewpPrestasi->result() as $data) : ?>
                    <tr>
                      <td><?= $no++ ?></td>
                      <td><?= $data->prestasi ?></td>
                      <td><?= $data->tingkat ?></td>
                      <td>
                        <?php if($data->url == ''){ ?>
                          <?="<a href='https://damba.uinsgd.ac.id/upload/mandiri/" . date('Y') . "/" . $data->sertifikat . "' class='btn btn-primary btn-xs' target='_blank'><i class='bx bx-show'></i> Lihat</a><div class='badge bg-success'>Sudah upload</div>"?>
                        <?php }else{ ?>
                          <?="<a href='$data->url' class='btn btn-primary btn-xs' target='_blank'><i class='bx bx-show'></i> Lihat</a><div class='badge bg-success'>Sudah upload</div>"?>
                        <?php } ?>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                      <td colspan='4' class="text-center">Data Kosong.</td>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>
          </div>
        </div>
      </div>
      </p>
      <?php endif; ?>
      <p>
      <div class="card">
        <div class="card-header header-elements">
          <span class="me-2 h6">Data File Berkas</span>
        </div>
        <div class="card-body">
          <div class="table-responsive" id="tabel_file_upload">
            <table class="table table-hover table-bordered">
              <thead>
                <tr>
                  <th class="text-center">No.</th>
                  <th class="text-center">Nama File</th>
                  <th class="text-center">Status</th>
                </tr>
                <thead>
                <tbody>
                <?php if ($tbsTipeFile->num_rows() > 0) : ?>
                  <?php $no = 1;
                  foreach ($tbsTipeFile->result() as $data) : ?>
                    <tr>
                      <td><?= $no++ ?></td>
                      <td><?= $data->nama_file ?></td>
                      <td>
                        <?php if($viewpFileUpload[$data->ids_tipe_file]->num_rows() > 0): ?>
                          <?php $viewpFileUpload[$data->ids_tipe_file] = $viewpFileUpload[$data->ids_tipe_file]->row(); ?>
                          <?php if($viewpFileUpload[$data->ids_tipe_file]->url == ''){ ?>
                            <?="<a href='https://damba.uinsgd.ac.id/upload/mandiri/" . date('Y') . "/" . $viewpFileUpload[$data->ids_tipe_file]->file . "' class='btn btn-primary btn-xs' target='_blank'><i class='bx bx-show'></i> Lihat</a><div class='badge bg-success'>Sudah upload</div>"?>
                          <?php }else{ ?>
                            <?="<a href='".$viewpFileUpload[$data->ids_tipe_file]->url."' class='btn btn-primary btn-xs' target='_blank'><i class='bx bx-show'></i> Lihat</a><div class='badge bg-success'>Sudah upload</div>"?>
                          <?php } ?>
                        <?php else: ?>
                          <div class='badge bg-danger'>Belum upload</div>
                        <?php endif; ?>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                      <td colspan='4' class="text-center">Data Kosong.</td>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>
          </div>
        </div>
      </div>
      </p>
      <p>
      <div class="card">
        <div class="card-header header-elements">
          <span class="me-2 h6">Data Jadwal</span>
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-hover">
              <?php if ($viewpJadwal->num_rows() > 0) : ?>
                <?php $viewpJadwal = $viewpJadwal->row(); ?>
                <tr>
                  <th>Tanggal</th>
                  <td>:</td>
                  <td><?= $viewpJadwal->tanggal ?></td>
                </tr>
                <tr>
                  <th>Jam</th>
                  <td>:</td>
                  <td><?= $viewpJadwal->jam_awal ?> - <?= $viewpJadwal->jam_akhir ?></td>
                </tr>
                <tr>
                  <th>Area</th>
                  <td>:</td>
                  <td><?= $viewpJadwal->area ?></td>
                </tr>
                <tr>
                  <th>Gedung</th>
                  <td>:</td>
                  <td><?= $viewpJadwal->gedung ?></td>
                </tr>
                <tr>
                  <th>Ruangan</th>
                  <td>:</td>
                  <td><?= $viewpJadwal->ruangan ?></td>
                </tr>
              <?php else : ?>
                <tr>
                  <td colspan='3' class="text-center">Data Kosong.</td>
                </tr>
              <?php endif; ?>
            </table>
          </div>
        </div>
      </div>
      </p>
      <?php if($this->jwt->ids_level == '1' || $this->jwt->ids_level == '2' || $this->jwt->ids_level == '4'): ?>
      <p>
      <div class="card">
        <div class="card-header header-elements">
          <span class="me-2 h6">Data Kelulusan</span>
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-hover">
              <?php if ($viewpKelulusan->num_rows() > 0) : ?>
                <?php $viewpKelulusan = $viewpKelulusan->row(); ?>
                <tr>
                  <th>Fakultas</th>
                  <td>:</td>
                  <td><?= $viewpKelulusan->fakultas ?></td>
                </tr>
                <tr>
                  <th>Jurusan</th>
                  <td>:</td>
                  <td><?= $viewpKelulusan->jurusan ?></td>
                </tr>
                <tr>
                  <th>Nilai Total</th>
                  <td>:</td>
                  <td><?= $viewpKelulusan->total ?></td>
                </tr>
                <tr>
                  <th>Status</th>
                  <td>:</td>
                  <td><?= ($viewpKelulusan->lulus == 'YA') ? '<div class="badge bg-success">Lulus</div>' : '<div class="badge bg-danger">Tidak Lulus</div>'; ?></td>
                </tr>
                <?php if ($viewpSanggah->num_rows() > 0) : ?>
                  <?php $viewpSanggah = $viewpSanggah->row(); ?>
                  <tr>
                    <th>Sanggah</th>
                    <td>:</td>
                    <td><?= $viewpSanggah->sanggah ?></td>
                  </tr>
                  <tr>
                    <th>Jawaban</th>
                    <td>:</td>
                    <td><?= $viewpSanggah->jawaban_sanggah ?></td>
                  </tr>
                <?php endif; ?>
              <?php else : ?>
                <tr>
                  <td colspan='3' class="text-center">Data Kosong.</td>
                </tr>
              <?php endif; ?>
            </table>
          </div>
        </div>
      </div>
      </p>
      <?php endif; ?>
    </div>
    <div class="col-md-6">
      <p>
      <div class="card">
        <div class="card-header header-elements">
          <span class="me-2 h6">Data Akun</span>
          <div class="card-header-elements ms-auto">
              <?php if ($tblUsers->id_user != 1): ?>
              <a href="<?= base_url('login-as/'.$tblUsers->id_user) ?>" style="margin-top: -15px" class="btn btn-xs btn-primary">
                  <span class="tf-icon bx bx-user bx-xs"></span> Login As
              </a>
              <?php endif; ?>
          </div>
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-hover">
              <tr>
                <th>Nama</th>
                <td>:</td>
                <td><?= $tblUsers->nama ?></td>
              </tr>
              <tr>
                <th>Email</th>
                <td>:</td>
                <td><?= $tblUsers->email ?></td>
              </tr>
              <tr>
                <th>Nomor Telepon</th>
                <td>:</td>
                <td><?= $tblUsers->nmr_tlpn ?></td>
              </tr>
            </table>
          </div>
        </div>
      </div>
      </p>
      <p>
      <div class="card">
        <div class="card-header header-elements">
          <span class="me-2 h6">Data Pembayaran</span>
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-hover" id="dataTabel">
              <thead>
                <tr>
                  <th>No.</th>
                  <th>Bank</th>
                  <th>VA</th>
                  <th>ID Billing</th>
                  <th>Expire At</th>
                  <th>Status</th>
                </tr>
              </thead>
              <tbody>
                <?php if ($viewpPembayaran->num_rows() > 0) : $viewpPembayaran = $viewpPembayaran->result() ?>
                  <?php $no = 1;
                  foreach ($viewpPembayaran as $value) : ?>
                    <tr>
                      <td><?= $no++; ?></td>
                      <td><?= $value->alias_bank ?></td>
                      <td><?= $value->va ?></td>
                      <td><?= $value->id_billing ?></td>
                      <td><?= $value->expire_at ?></td>
                      <td>
                        <?php if ($value->pembayaran == "SUDAH") : ?>
                          <div class="badge bg-success">Sudah</div>
                        <?php elseif ($value->pembayaran == "BELUM") : ?>
                          <div class="badge bg-danger">Belum</div>
                        <?php else : ?>
                          <div class="badge bg-danger">Expired</div>
                        <?php endif; ?>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                <?php endif; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
      </p>
      <p>
      <div class="card">
        <div class="card-header header-elements">
          <span class="me-2 h6">Data Rumah</span>
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-hover">
              <?php if ($viewpRumah->num_rows() > 0) : ?>
                <?php $viewpRumah = $viewpRumah->row(); ?>
                <tr>
                  <th>Negara</th>
                  <td>:</td>
                  <td><?= $viewpRumah->negara ?></td>
                </tr>
                <tr>
                  <th>Provinsi</th>
                  <td>:</td>
                  <td><?= $viewpRumah->provinsi ?></td>
                </tr>
                <tr>
                  <th>Kabupaten / Kota</th>
                  <td>:</td>
                  <td><?= $viewpRumah->kab_kota ?></td>
                </tr>
                <tr>
                  <th>Kecamatan</th>
                  <td>:</td>
                  <td><?= $viewpRumah->kecamatan ?></td>
                </tr>
                <tr>
                  <th>Kelurahan</th>
                  <td>:</td>
                  <td><?= $viewpRumah->kelurahan ?></td>
                </tr>
                <tr>
                  <th>Dusun</th>
                  <td>:</td>
                  <td><?= $viewpRumah->dusun ?></td>
                </tr>
                <tr>
                  <th>RT</th>
                  <td>:</td>
                  <td><?= $viewpRumah->rt ?></td>
                </tr>
                <tr>
                  <th>RW</th>
                  <td>:</td>
                  <td><?= $viewpRumah->rw ?></td>
                </tr>
                <tr>
                  <th>Jalan</th>
                  <td>:</td>
                  <td><?= $viewpRumah->jalan ?></td>
                </tr>
                <tr>
                  <th>Kode POS</th>
                  <td>:</td>
                  <td><?= $viewpRumah->kode_pos ?></td>
                </tr>
              <?php else : ?>
                <tr>
                  <td colspan='3' class="text-center">Data Kosong.</td>
                </tr>
              <?php endif; ?>
            </table>
          </div>
        </div>
      </div>
      </p>
      <p>
      <div class="card">
        <div class="card-header header-elements">
          <span class="me-2 h6">Data Pilihan</span>
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-hover" id="dataTabel2">
              <?php if ($viewpPilihan->num_rows() > 0) : $viewpPilihan = $viewpPilihan->result() ?>
                <thead>
                  <tr>
                    <th>No.</th>
                    <th>Pilihan</th>
                    <th>Kode Jurusan</th>
                    <th>Jurusan</th>
                    <th>Fakultas</th>
                  </tr>
                </thead>
                <tbody>
                  <?php $no = 1;
                  foreach ($viewpPilihan as $value) : ?>
                    <tr>
                      <td><?= $no++; ?></td>
                      <td><?= $value->pilihan ?></td>
                      <td><?= $value->kode_jurusan ?></td>
                      <td><?= $value->jurusan ?></td>
                      <td><?= $value->fakultas ?></td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              <?php else : ?>
                <tr>
                  <td colspan='3' class="text-center">Data Kosong.</td>
                </tr>
              <?php endif; ?>
            </table>
          </div>
        </div>
      </div>
      </p>
      <?php if($viewpFormulir->jenjang == 'S2' || $viewpFormulir->jenjang == 'S3'): ?>
      <p>
        <div class="card">
          <div class="card-header header-elements">
            <span class="me-2 h6">Data Pekerjaan</span>
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-hover">
                <?php if ($tbpPekerjaan->num_rows() > 0) : ?>
                  <?php $tbpPekerjaan = $tbpPekerjaan->row(); ?>
                  <tr>
                    <th>Tempat Bekerja</th>
                    <td>:</td>
                    <td><?= $tbpPekerjaan->tempat_bekerja ?></td>
                  </tr>
                  <tr>
                    <th>Alamat Bekerja</th>
                    <td>:</td>
                    <td><?= $tbpPekerjaan->alamat_bekerja ?></td>
                  </tr>
                <?php else : ?>
                  <tr>
                    <td colspan='3' class="text-center">Data Kosong.</td>
                  </tr>
                <?php endif; ?>
              </table>
            </div>
          </div>
        </div>
      </p>
      <?php endif; ?>
      <?php if($this->jwt->ids_level == '1' || $this->jwt->ids_level == '2'): ?>
      <p>
      <div class="card">
        <div class="card-header header-elements">
          <span class="me-2 h6">Data Nilai</span>
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-hover" id="dataTabel2">
              <?php if ($viewpNilai->num_rows() > 0) : $viewpNilai = $viewpNilai->result() ?>
                <thead>
                  <tr>
                    <th>No.</th>
                    <th>Keterangan</th>
                    <th>Nilai</th>
                  </tr>
                </thead>
                <tbody>
                  <?php $no = 1;
                  foreach ($viewpNilai as $value) : ?>
                    <tr>
                      <td><?= $no++; ?></td>
                      <td><?= $value->keterangan ?></td>
                      <td><?= $value->nilai ?></td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              <?php else : ?>
                <tr>
                  <td colspan='3' class="text-center">Data Kosong.</td>
                </tr>
              <?php endif; ?>
            </table>
          </div>
        </div>
      </div>
      </p>
      <?php endif; ?>
    </div>
  </div>
</div>