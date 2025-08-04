<div class="container-xxl flex-grow-1 container-p-y">
  <h4 class="py-3 breadcrumb-wrapper mb-4">Detail Jadwal</h4>
  <div class="row">
    <div class="col-md-12">
      <p>
      <div class="card">
        <div class="card-header header-elements">
          <span class="me-2 h6">Data Jadwal</span>
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-hover">
              <tr>
                <th>Program</th>
                <td>:</td>
                <td><?= $viewsJadwal->program ?></td>
              </tr>
              <tr>
                <th>Tingkat</th>
                <td>:</td>
                <td><?= $viewsJadwal->tingkat ?></td>
              </tr>
              <tr>
                <th>Tipe Ujian</th>
                <td>:</td>
                <td><?= $viewsJadwal->tipe_ujian ?></td>
              </tr>
              <tr>
                <th>Tanggal</th>
                <td>:</td>
                <td><?= $viewsJadwal->tanggal ?></td>
              </tr>
              <tr>
                <th>Jam Awal</th>
                <td>:</td>
                <td><?= $viewsJadwal->jam_awal ?></td>
              </tr>
              <tr>
                <th>Jam Akhir</th>
                <td>:</td>
                <td><?= $viewsJadwal->jam_akhir ?></td>
              </tr>
              <tr>
                <th>Area</th>
                <td>:</td>
                <td><?= $viewsJadwal->area ?></td>
              </tr>
              <tr>
                <th>Gedung</th>
                <td>:</td>
                <td><?= $viewsJadwal->gedung ?></td>
              </tr>
              <tr>
                <th>Ruangan</th>
                <td>:</td>
                <td><?= $viewsJadwal->ruangan ?></td>
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
            <table class="table table-hover" id="dataTabel">
              <thead>
                <tr>
                  <td>No.</td>
                  <td>Aksi</td>
                  <td>Foto</td>
                  <td>Nomor Peserta</td>
                  <td>Nama</td>
                </tr>
              </thead>
              <tbody>
              <?php if ($viewpJadwal->num_rows() > 0) : ?>
                <?php $viewpJadwal = $viewpJadwal->result(); ?>
                <?php $no=1; foreach($viewpJadwal as $a): ?>
                  <tr>
                    <td><?=$no++?></td>
                    <td><a href="<?=base_url('mandiri/mahasiswa/detail/'.$a->idp_formulir)?>" class="btn btn-primary btn-xs" target="_blank"><i class="bx bx-detail">&nbsp;</i>Detail</a></td>
                    <td><img src="<?=$_ENV['HOST_FRONTEND']?>upload/mandiri/<?=date('Y', strtotime($a->date_created))?>/<?=$viewpFile[$a->idp_formulir]->file?>" class="img-responsive"></td>
                    <td><?=$a->nomor_peserta?></td>
                    <td><?=$a->nama?></td>
                  </tr>
                <?php endforeach; ?>
              <?php else : ?>
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
    </div>
  </div>
</div>