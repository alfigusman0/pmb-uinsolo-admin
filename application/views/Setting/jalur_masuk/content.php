<div class="container-xxl flex-grow-1 container-p-y">
  <h4 class="py-3 breadcrumb-wrapper mb-4">
    <span class="text-muted fw-light">Setting /</span> <?= $title ?>
  </h4>
  <p>
    <?php $this->load->view('layout/notification'); ?>
  </p>
  <p>
  <div class="card">
    <div class="card-header header-elements">
      <span class="me-2 h5">Data <?= $title ?></span>
      <div class="card-header-elements ms-auto">
        <button type="button" style="margin-top: -15px" onclick="add_data()" class="btn btn-xs btn-primary">
          <span class="tf-icon bx bx-plus bx-xs"></span> Tambah
        </button>
      </div>
    </div>
    <div class="card-datatable table-responsive pt-0">
      <table id="dataTabel" class="datatables-basic table table-bordered">
        <thead>
          <tr>
            <th>No</th>
            <th>Aksi</th>
            <th>ID Salam</th>
            <th>Jalur Masuk</th>
            <th>Pendaftaran</th>
            <th>UKT</th>
            <th>Pembayaran</th>
            <th>Pemberkasan</th>
            <th>No. Urut</th>
            <th>Status</th>
          </tr>
        </thead>
        <tbody>
          <?php $no = 1;
          foreach ($masterJalurMasuk->data->data as $data) : ?>
            <tr>
              <td><?= $no++ ?></td>
              <td>
                <button type="button" title="Edit" onclick="edit_data(<?= $data->ids_jalur_masuk ?>)" class="btn btn-xs btn-warning"><span class="tf-icon bx bx-edit bx-xs"></span> Edit</button>
                <a href="javascript:void(0)" title="Hapus" class="btn btn-xs btn-danger" onclick="delete_data(<?= $data->ids_jalur_masuk ?>)"><span class="tf-icon bx bx-trash bx-xs"></span> Hapus</a>
              </td>
              <td><?= $data->id_salam ?></td>
              <td><?= $data->alias ?></td>
              <td><?=($tbsJalurMasuk[$data->ids_jalur_masuk]) ? $tbsJalurMasuk[$data->ids_jalur_masuk]->pendaftaran_awal . ' - ' . $tbsJalurMasuk[$data->ids_jalur_masuk]->pendaftaran_akhir : 'Belum di set'?></td>
              <td><?=($tbsJalurMasuk[$data->ids_jalur_masuk]) ? $tbsJalurMasuk[$data->ids_jalur_masuk]->ukt_awal . ' - ' . $tbsJalurMasuk[$data->ids_jalur_masuk]->ukt_akhir : 'Belum di set'?></td>
              <td><?=($tbsJalurMasuk[$data->ids_jalur_masuk]) ? $tbsJalurMasuk[$data->ids_jalur_masuk]->pembayaran_awal . ' - ' . $tbsJalurMasuk[$data->ids_jalur_masuk]->pembayaran_akhir : 'Belum di set'?></td>
              <td><?=($tbsJalurMasuk[$data->ids_jalur_masuk]) ? $tbsJalurMasuk[$data->ids_jalur_masuk]->pemberkasan_awal . ' - ' . $tbsJalurMasuk[$data->ids_jalur_masuk]->pemberkasan_akhir : 'Belum di set'?></td>
              <td><?= $data->nourut ?></td>
              <td><?= ($data->status == 'YA') ? "<div class='badge bg-success'>Tampilkan</div>" : "<div class='badge bg-danger'>Sembunyikan</div>" ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
  </p>
</div>