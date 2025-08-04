<div class="container-xxl flex-grow-1 container-p-y">
  <h4 class="py-3 breadcrumb-wrapper mb-4">Detail Mahasiswa</h4>
  <div class="row">
    <div class="col-md-6">
      <p>
      <div class="card">
        <div class="card-header header-elements">
          <span class="me-2 h6">Data Kelulusan</span>
          <div class="card-header-elements ms-auto">
            <button type="button" style="margin-top: -15px" onclick="edit_data_kelulusan(<?= $this->uri->segment(4) ?>)" class="btn btn-xs btn-warning">
              <span class="tf-icon bx bx-edit bx-xs"></span>
            </button>
            <?php if($viewdKelulusan->pembayaran == 'SUDAH'): ?>
            <button type="button" style="margin-top: -15px" onclick="pindah_kelulusan()" class="btn btn-xs btn-info">
              Pindah Jalur Masuk
            </button>
            <?php endif; ?>
          </div>
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-hover">
              <tr>
                <th>Nomor Peserta</th>
                <td>:</td>
                <td><?= $viewdKelulusan->nomor_peserta ?></td>
              </tr>
              <tr>
                <th>NIM</th>
                <td>:</td>
                <td><?= $viewdKelulusan->nim ?></td>
              </tr>
              <tr>
                <th>Nama</th>
                <td>:</td>
                <td><?= $viewdKelulusan->nama ?></td>
              </tr>
              <tr>
                <th>Fakultas</th>
                <td>:</td>
                <td><?= $viewdKelulusan->fakultas ?></td>
              </tr>
              <tr>
                <th>Jurusan</th>
                <td>:</td>
                <td><?= $viewdKelulusan->jurusan ?></td>
              </tr>
              <tr>
                <th>Jalur Masuk</th>
                <td>:</td>
                <td><?= $viewdKelulusan->jalur_masuk ?> (<?= $viewdKelulusan->alias_jalur_masuk ?>)</td>
              </tr>
              <tr>
                <th>Tahun</th>
                <td>:</td>
                <td><?= $viewdKelulusan->tahun ?></td>
              </tr>
              <tr>
                <th>Foto</th>
                <td>:</td>
                <td>
                <?php
                  if ($viewdFileFoto->num_rows() > 0) {
                    $a = $viewdFileFoto->row();
                    echo "<a href='#' onclick='view_foto(\"" . $a->url . "\", \"Foto\")' class='btn btn-primary btn-xs'><i class='bx bx-show'></i> Lihat</a>";
                  } else {
                    echo "<div class='badge bg-danger'>Belum upload</div>";
                  }
                ?>
                </td>
              </tr>
              <tr>
                <th>Daftar</th>
                <td>:</td>
                <td><?= ($viewdKelulusan->daftar == 'BELUM') ? '<div class="badge bg-danger">Belum</div>' : '<div class="badge bg-success">Sudah</div>' ?> <div class="badge bg-info"><?=$viewdKelulusan->tgl_daftar?></div></td>
              </tr>
              <tr>
                <th>Submit</th>
                <td>:</td>
                <td><?= ($viewdKelulusan->submit == 'BELUM') ? '<div class="badge bg-danger">Belum</div>' : '<div class="badge bg-success">Sudah</div>' ?> <div class="badge bg-info"><?=$viewdKelulusan->tgl_submit?></td>
              </tr>
              <tr>
                <th>Pembayaran</th>
                <td>:</td>
                <td>
                  <?php if($viewdKelulusan->pembayaran == 'BELUM'){
                    echo '<div class="badge bg-danger">Belum</div>';
                  }else if($viewdKelulusan->pembayaran == 'PINDAH'){
                    echo '<div class="badge bg-warning">Pindah</div>';
                  }else{
                    echo '<div class="badge bg-success">Sudah</div>';
                  }
                  ?>
                  <div class="badge bg-info"><?=$viewdKelulusan->tgl_pembayaran?></div>
                </td>
              </tr>
              <tr>
                <th>Pemberkasan</th>
                <td>:</td>
                <td><?= ($viewdKelulusan->pemberkasan == 'BELUM') ? '<div class="badge bg-danger">Belum</div>' : '<div class="badge bg-success">Sudah</div>' ?> <div class="badge bg-info"><?=$viewdKelulusan->tgl_pemberkasan?></td>
              </tr>
              <tr>
                <th>Keterangan Pembayaran</th>
                <td>:</td>
                <td><?= $viewdKelulusan->ket_pembayaran?></td>
              </tr>
            </table>
          </div>
        </div>
      </div>
      </p>
      <p>
      <div class="card">
        <div class="card-header header-elements">
          <span class="me-2 h6">Data Mahasiswa</span>
          <!-- <div class="card-header-elements ms-auto">
                <button type="button" style="margin-top: -15px" class="btn btn-xs btn-primary">
                  <span class="tf-icon bx bx-filter bx-xs"></span> Filter
                </button>
            </div> -->
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-hover">
              <?php if ($viewdMahasiswa->num_rows() > 0) : ?>
                <?php $viewdMahasiswa = $viewdMahasiswa->row(); ?>
                <tr>
                  <th>Jenis Kelamin</th>
                  <td>:</td>
                  <td><?= $viewdMahasiswa->jenis_kelamin ?></td>
                </tr>
                <tr>
                  <th>Tempat Lahir</th>
                  <td>:</td>
                  <td><?= $viewdMahasiswa->tempat_lahir ?></td>
                </tr>
                <tr>
                  <th>Tanggal Lahir</th>
                  <td>:</td>
                  <td><?= $viewdMahasiswa->tgl_lahir ?></td>
                </tr>
                <tr>
                  <th>Agama</th>
                  <td>:</td>
                  <td><?= $viewdMahasiswa->agama ?></td>
                </tr>
                <tr>
                  <th>Kewarganegaraan</th>
                  <td>:</td>
                  <td><?= $viewdMahasiswa->kewarganegaraan ?></td>
                </tr>
                <tr>
                  <th>Jenis Tinggal</th>
                  <td>:</td>
                  <td><?= $viewdMahasiswa->jenis_tinggal ?></td>
                </tr>
                <tr>
                  <th>Alat Transportasi</th>
                  <td>:</td>
                  <td><?= $viewdMahasiswa->alat_transportasi ?></td>
                </tr>
                <tr>
                  <th>Terima KPS?</th>
                  <td>:</td>
                  <td><?= $viewdMahasiswa->terima_kps ?></td>
                </tr>
                <tr>
                  <th>No. KPS</th>
                  <td>:</td>
                  <td><?= $viewdMahasiswa->no_kps ?></td>
                </tr>
                <tr>
                  <th>Jenis Pendaftaran</th>
                  <td>:</td>
                  <td><?= $viewdMahasiswa->jenis_pendaftaran ?></td>
                </tr>
                <tr>
                  <th>Jenis Pembiayaan</th>
                  <td>:</td>
                  <td><?= $viewdMahasiswa->jenis_pembiayaan ?></td>
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
          <span class="me-2 h6">Data Sekolah</span>
          <!-- <div class="card-header-elements ms-auto">
                <button type="button" style="margin-top: -15px" class="btn btn-xs btn-primary">
                  <span class="tf-icon bx bx-filter bx-xs"></span> Filter
                </button>
            </div> -->
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-hover">
              <?php if ($viewdSekolah->num_rows() > 0) : ?>
                <?php $viewdSekolah = $viewdSekolah->row(); ?>
                <tr>
                  <th>NISN</th>
                  <td>:</td>
                  <td><?= $viewdSekolah->nisn ?></td>
                </tr>
                <tr>
                  <th>Jenis Sekolah</th>
                  <td>:</td>
                  <td><?= $viewdSekolah->jenis_sekolah ?></td>
                </tr>
                <tr>
                  <th>Jurusan Sekolah</th>
                  <td>:</td>
                  <td><?= $viewdSekolah->jurusan_sekolah ?></td>
                </tr>
                <tr>
                  <th>Nama Sekolah</th>
                  <td>:</td>
                  <td><?= $viewdSekolah->nama_sekolah ?></td>
                </tr>
                <tr>
                  <th>Akreditasi Sekolah</th>
                  <td>:</td>
                  <td><?= $viewdSekolah->akreditasi_sekolah ?></td>
                </tr>
                <tr>
                  <th>Rumpun</th>
                  <td>:</td>
                  <td><?= $viewdSekolah->rumpun ?></td>
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
          <span class="me-2 h6">Data File</span>
          <!-- <div class="card-header-elements ms-auto">
              <button type="button" style="margin-top: -15px" class="btn btn-xs btn-primary">
                <span class="tf-icon bx bx-filter bx-xs"></span> Filter
              </button>
          </div> -->
        </div>
        <div class="card-body">
          <div class="table-responsive" id="tabel_berkas">
            <table class="table table-hover table-bordered">
              <thead>
                <tr>
                  <th class="text-center">No.</th>
                  <th class="text-center">Nama File</th>
                  <th class="text-center">Status</th>
                </tr>
                <thead>
                <tbody>
                  <?php $no = 1;
                  foreach ($tbsTipeFile as $data) : ?>
                    <tr>
                      <td><?= $no++ ?></td>
                      <td><?= $data->nama_file ?></td>
                      <td>
                        <?php
                        if ($viewdFile[$data->ids_tipe_file]->num_rows() > 0) {
                          $a = $viewdFile[$data->ids_tipe_file]->row();
                          echo "<a href='#' onclick='view_foto(\"" . $a->url . "\", \"" . $data->nama_file . "\")' class='btn btn-primary btn-xs'><i class='bx bx-show'></i> Lihat</a><div class='badge bg-success'>Sudah upload</div>";
                        } else {
                          echo "<div class='badge bg-danger'>Belum upload</div>";
                        }
                        ?>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
            </table>
          </div>
        </div>
      </div>
      </p>
      <p>
      <div class="card">
        <div class="card-header header-elements">
          <span class="me-2 h6">Data Pemberkasan</span>
          <!-- <div class="card-header-elements ms-auto">
              <button type="button" style="margin-top: -15px" class="btn btn-xs btn-primary">
                <span class="tf-icon bx bx-filter bx-xs"></span> Filter
              </button>
          </div> -->
        </div>
        <div class="card-body">
          <div class="table-responsive" id="tabel_berkas">
            <table class="table table-hover table-bordered">
              <thead>
                <tr>
                  <th class="text-center">No.</th>
                  <th class="text-center">Nama File</th>
                  <th class="text-center">Status</th>
                </tr>
                <thead>
                <tbody>
                  <?php if($viewdFilePemberkasan != null): ?>
                  <?php $no = 1;
                  foreach ($tbsTipeFile2 as $data) : ?>
                    <tr>
                      <td><?= $no++ ?></td>
                      <td><?= $data->nama_file ?></td>
                      <td>
                        <?php
                        if ($viewdFilePemberkasan[$data->ids_tipe_file]->num_rows() > 0) {
                          $a = $viewdFilePemberkasan[$data->ids_tipe_file]->row();
                          echo "<a href='" . $a->url . "' class='btn btn-primary btn-xs' target='_blank'><i class='bx bx-show'></i> Lihat</a><div class='badge bg-success'>Sudah upload</div>";
                        } else {
                          echo "<div class='badge bg-danger'>Belum upload</div>";
                        }
                        ?>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                  <?php else: ?>
                    <tr>
                      <td colspan='3' class="text-center">Data kosong.</td>
                    </tr>
                  <?php endif; ?>
                </tbody>
            </table>
          </div>
        </div>
      </div>
      </p>
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
          <span class="me-2 h6">Data UKT</span>
          <!-- <div class="card-header-elements ms-auto">
                <button type="button" style="margin-top: -15px" class="btn btn-xs btn-primary">
                  <span class="tf-icon bx bx-filter bx-xs"></span> Filter
                </button>
            </div> -->
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-hover">
              <?php if ($viewdUKT->num_rows() > 0) : $viewdUKT = $viewdUKT->row() ?>
                <tr>
                  <th>Kategori</th>
                  <td>:</td>
                  <td><?= $viewdUKT->kategori ?></td>
                </tr>
                <tr>
                  <th>Jumlah</th>
                  <td>:</td>
                  <td>Rp. <?= number_format($viewdUKT->jumlah, 2) ?></td>
                </tr>
                <tr>
                  <th>Potongan</th>
                  <td>:</td>
                  <td>Rp. <?= number_format($viewdUKT->potongan, 2) ?></td>
                </tr>
                <tr>
                  <th>Total</th>
                  <td>:</td>
                  <td>
                    <?php
                      $total = $viewdUKT->jumlah-$viewdUKT->potongan;
                    ?>
                    Rp. <?= number_format($total, 2) ?>
                  </td>
                </tr>
              <?php else : ?>
                <tr>
                  <th>Kategori</th>
                  <td>:</td>
                  <td colspan='2'>UKT belum ditetapkan.</td>
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
          <span class="me-2 h6">Data Pembayaran</span>
          <!-- <div class="card-header-elements ms-auto">
                <button type="button" style="margin-top: -15px" class="btn btn-xs btn-primary">
                  <span class="tf-icon bx bx-filter bx-xs"></span> Filter
                </button>
            </div> -->
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-hover" id="dataTabel">
              <?php if ($viewdPembayaran->num_rows() > 0 || $viewdPembayaran != null) : $viewdPembayaran = $viewdPembayaran->result() ?>
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
                  <?php $no = 1;
                  foreach ($viewdPembayaran as $value) : ?>
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
      <p>
      <div class="card">
        <div class="card-header header-elements">
          <span class="me-2 h6">Data Orangtua</span>
          <!-- <div class="card-header-elements ms-auto">
                <button type="button" style="margin-top: -15px" class="btn btn-xs btn-primary">
                  <span class="tf-icon bx bx-filter bx-xs"></span> Filter
                </button>
            </div> -->
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-hover">
              <?php if ($viewdOrangtua->num_rows() > 0) : ?>
                <?php foreach ($viewdOrangtua->result() as $a) : ?>
                  <tr>
                    <th>NIK <?= $a->orangtua ?></th>
                    <td>:</td>
                    <td><?= $a->nik_orangtua ?></td>
                  </tr>
                  <tr>
                    <th>Nama <?= $a->orangtua ?></th>
                    <td>:</td>
                    <td><?= $a->nama_orangtua ?></td>
                  </tr>
                  <tr>
                    <th>Tanggal Lahir <?= $a->orangtua ?></th>
                    <td>:</td>
                    <td><?= $a->tgl_lahir_orangtua ?></td>
                  </tr>
                  <tr>
                    <th>Pendidikan <?= $a->orangtua ?></th>
                    <td>:</td>
                    <td><?= $a->pendidikan ?></td>
                  </tr>
                  <tr>
                    <th>Pekerjaan <?= $a->orangtua ?></th>
                    <td>:</td>
                    <td><?= $a->pekerjaan ?></td>
                  </tr>
                  <tr>
                    <th>Penghasilan <?= $a->orangtua ?></th>
                    <td>:</td>
                    <td><?= $a->penghasilan ?></td>
                  </tr>
                  <tr>
                    <th>Nominal Penghasilan <?= $a->orangtua ?></th>
                    <td>:</td>
                    <td><?= $a->nominal_penghasilan ?></td>
                  </tr>
                  <tr>
                    <th>Terbilang Penghasilan <?= $a->orangtua ?></th>
                    <td>:</td>
                    <td><?= $a->terbilang_penghasilan ?></td>
                  </tr>
                <?php endforeach; ?>
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
          <span class="me-2 h6">Data Rumah</span>
          <!-- <div class="card-header-elements ms-auto">
                <button type="button" style="margin-top: -15px" class="btn btn-xs btn-primary">
                  <span class="tf-icon bx bx-filter bx-xs"></span> Filter
                </button>
            </div> -->
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-hover">
              <?php if ($viewdRumah->num_rows() > 0) : ?>
                <?php $viewdRumah = $viewdRumah->row(); ?>
                <tr>
                  <th>Tanggungan</th>
                  <td>:</td>
                  <td><?= $viewdRumah->tanggungan ?></td>
                </tr>
                <tr>
                  <th>Rekening Listrik</th>
                  <td>:</td>
                  <td><?= $viewdRumah->rekening_listrik ?></td>
                </tr>
                <tr>
                  <th>Daya Listrik</th>
                  <td>:</td>
                  <td><?= $viewdRumah->daya_listrik ?></td>
                </tr>
                <tr>
                  <th>Kepemilikan Rumah</th>
                  <td>:</td>
                  <td><?= $viewdRumah->kepemilikan_rumah ?></td>
                </tr>
                <tr>
                  <th>NJOP</th>
                  <td>:</td>
                  <td><?= $viewdRumah->njop ?></td>
                </tr>
                <tr>
                  <th>LKTL</th>
                  <td>:</td>
                  <td><?= $viewdRumah->lktl ?></td>
                </tr>
                <tr>
                  <th>Kepemilikan Mobil</th>
                  <td>:</td>
                  <td><?= $viewdRumah->kepemilikan_mobil ?></td>
                </tr>
                <tr>
                  <th>Pajak Mobil</th>
                  <td>:</td>
                  <td><?= $viewdRumah->pajak_mobil ?></td>
                </tr>
                <tr>
                  <th>Kepemilikan Motor</th>
                  <td>:</td>
                  <td><?= $viewdRumah->kepemilikan_motor ?></td>
                </tr>
                <tr>
                  <th>Pajak Motor</th>
                  <td>:</td>
                  <td><?= $viewdRumah->pajak_motor ?></td>
                </tr>
                <tr>
                  <th>Provinsi</th>
                  <td>:</td>
                  <td><?= $viewdRumah->provinsi ?></td>
                </tr>
                <tr>
                  <th>Kabupaten / Kota</th>
                  <td>:</td>
                  <td><?= $viewdRumah->kab_kota ?></td>
                </tr>
                <tr>
                  <th>Kecamatan</th>
                  <td>:</td>
                  <td><?= $viewdRumah->kecamatan ?></td>
                </tr>
                <tr>
                  <th>Kelurahan</th>
                  <td>:</td>
                  <td><?= $viewdRumah->kelurahan ?></td>
                </tr>
                <tr>
                  <th>Dusun</th>
                  <td>:</td>
                  <td><?= $viewdRumah->dusun ?></td>
                </tr>
                <tr>
                  <th>RT</th>
                  <td>:</td>
                  <td><?= $viewdRumah->rt ?></td>
                </tr>
                <tr>
                  <th>RW</th>
                  <td>:</td>
                  <td><?= $viewdRumah->rw ?></td>
                </tr>
                <tr>
                  <th>Jalan</th>
                  <td>:</td>
                  <td><?= $viewdRumah->jalan ?></td>
                </tr>
                <tr>
                  <th>Kode POS</th>
                  <td>:</td>
                  <td><?= $viewdRumah->kode_pos ?></td>
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
    </div>
  </div>
</div>