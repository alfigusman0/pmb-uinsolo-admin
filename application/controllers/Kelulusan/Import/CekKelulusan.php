<?php
defined('BASEPATH') or exit('No direct script access allowed');
class CekKelulusan extends CI_Controller
{
  var $jwt = null;

  function __construct()
  {
    parent::__construct();
    if (!empty($this->input->cookie($_ENV['COOKIE_NAME'], TRUE))) {
      $this->jwt = $this->jsonwebtoken->jwtDecodeSSO();
      if ($this->jwt['status'] == 'success') {
          $this->jwt = $this->jwt['data'];
      } else {
          $this->session->set_flashdata('message', $this->jwt['message']);
          $this->session->set_flashdata('type_message', 'danger');
          redirect('login-back');
      }
    } else {
        header('Location: ' . $_ENV['SSO']);
    }

    $this->load->model('Mandiri/Tbp_kelulusan');
    $this->load->model('Mandiri/Tbp_formulir');
    $this->load->model('Mandiri/Tbp_pilihan');
    $this->load->model('Mandiri/Viewp_kelulusan');
    $this->load->model('Mandiri/Viewp_pilihan');
    $this->load->model('Settings/Views_daya_tampung');
  }

  function index()
  {
    $hak_akses = $this->master->read('hak-akses/?ids_level=' . $this->jwt->ids_level . '&ids_modul=90&aksi_hak_akses=import');
    if ($hak_akses->code == 200) {
      if ($this->input->post('check') == 'upload') {
        $config = array(
          'upload_path'   => './upload/mandiri/',
          'allowed_types' => 'xls|xlsx|csv|ods|ots',
          'max_size'      => 51200,
          'overwrite'     => TRUE,
          'file_name'     => 'Cek_Kelulusan_' . date('H i s d m Y'),
        );
        $this->upload->initialize($config);
        if (!$this->upload->do_upload()) {
          $this->session->set_flashdata('message', $this->upload->display_errors());
          $this->session->set_flashdata('type_message', 'danger');
          redirect('kelulusan/import/cek-kelulusan/');
        } else {
          $file = $this->upload->data();
          $inputFileName = 'upload/mandiri/' . $file['file_name'];
          try {
            $inputFileType = 'Xlsx'; // Xls, Xlsx, Xml, Ods, Slk, Gnumeric, Csv
            /**  Create a new Reader of the type defined in $inputFileType  **/
            $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($inputFileType);
            /**  Advise the Reader that we only want to load cell data  **/
            $reader->setReadDataOnly(true);
            /**  Advise the Reader of which WorkSheets we want to load  **/
            //$reader->setLoadSheetsOnly($sheetname);
            /**  Load $inputFileName to a Spreadsheet Object  **/
            $spreadsheet = $reader->load($inputFileName);
          } catch (Exception $e) {
            die('Error loading file "' . pathinfo($inputFileName, PATHINFO_BASENAME) . '" : ' . $e->getMessage());
          }
          $sheet = $spreadsheet->getSheet(0);
          $highestRow = $sheet->getHighestRow();
          try {
            $berhasil = $error = $no = 0;
            $reportHtml = null;
            for ($row = 2; $row <= $highestRow; $row++) {
              $nomor_peserta = $sheet->getCellByColumnAndRow(1, $row)->getValue();
              $rules = array(
                'database' => null, //Default database master
                'select'    => null,
                'where'     => array(
                  'nomor_peserta' => $nomor_peserta
                ),
                'or_where' => null,
                'like' => null,
                'or_like' => null,
                'order' => null,
                'limit' => null,
                'group_by' => null,
              );
              $tbpFormulir = $this->Tbp_formulir->search($rules)->row();
              $rules = array(
                'database' => null, //Default database master
                'select'    => null,
                'where'     => array(
                  'idp_formulir' => $tbpFormulir->idp_formulir
                ),
                'or_where' => null,
                'like' => null,
                'or_like' => null,
                'order' => null,
                'limit' => null,
                'group_by' => null,
              );
              $viewpKelulusan = $this->Viewp_kelulusan->search($rules);
              if ($viewpKelulusan->num_rows() > 0) {
                $viewpKelulusan = $viewpKelulusan->row();
                if ($viewpKelulusan->lulus == 'YA') {
                  $lulus = "<div class='badge bg-success'>Lulus</div>";
                } else if ($viewpKelulusan->lulus == 'TIDAK') {
                  $lulus = "<div class='badge bg-danger'>Tidak Lulus</div>";
                } else {
                  $lulus = "<div class='badge bg-warning'>Belum</div>";
                }

                $rules = array(
                  'database' => null, //Default database master
                  'select'    => null,
                  'where'     => array(
                    'idp_formulir' => $tbpFormulir->idp_formulir,
                  ),
                  'or_where' => null,
                  'like' => null,
                  'or_like' => null,
                  'order' => 'pilihan ASC',
                  'limit' => null,
                  'group_by' => null,
                );
                $tbpListPilihan = $this->Viewp_pilihan->search($rules)->result();
                $pilihan = '';
                foreach ($tbpListPilihan as $a) {
                  $pilihan .= "<li>Pilihan ke-$a->pilihan : ($a->kode_jurusan) $a->jurusan - $a->fakultas</li>";
                }

                if ($viewpKelulusan->lulus == 'YA') {
                  $rules = array(
                    'database' => null, //Default database master
                    'select'    => null,
                    'where'     => array(
                      'idp_formulir' => $tbpFormulir->idp_formulir,
                      'kode_jurusan' => $viewpKelulusan->kode_jurusan,
                    ),
                    'or_where' => null,
                    'like' => null,
                    'or_like' => null,
                    'order' => null,
                    'limit' => null,
                    'group_by' => null,
                  );
                  $tbpPilihan = $this->Tbp_pilihan->search($rules)->row();
                  $reportHtml .=
                    "<tr>
                      <td>" . ++$no . "</td>
                      <td> $nomor_peserta</td>
                      <td> $viewpKelulusan->nama </td>
                      <td> $lulus </td>
                      <td> $viewpKelulusan->total </td>
                      <td> <ul>$pilihan</ul> </td>
                      <td> Lulus Pilihan ke-$tbpPilihan->pilihan </td>
                    </tr>";
                } else {
                  $keterangan1 = $ket1 = [];
                  $keterangan2 = $ket2 = [];
                  $keterangan3 = $ket3 = [];
                  //keterangan pilihan 1
                  $rules = array(
                    'database' => null, //Default database master
                    'select'    => null,
                    'where'     => array(
                      'idp_formulir' => $tbpFormulir->idp_formulir,
                      'pilihan' => '1',
                    ),
                    'or_where' => null,
                    'like' => null,
                    'or_like' => null,
                    'order' => null,
                    'limit' => null,
                    'group_by' => null,
                  );
                  $tbpPilihan1 = $this->Tbp_pilihan->search($rules)->row();
                  $rules = array(
                    'database'    => null,
                    'select'    => null,
                    'where'     => array(
                      'kode_jurusan' => $tbpPilihan1->kode_jurusan,
                    ),
                    'or_where' => null,
                    'like' => null,
                    'or_like' => null,
                    'order' => null,
                    'limit' => null,
                    'group_by' => null,
                  );
                  $viewsDayaTampung = $this->Views_daya_tampung->search($rules)->row();
                  $grade = $viewsDayaTampung->grade / 2;
                  $kuota = $viewsDayaTampung->kuota;
                  if ($viewpKelulusan->total < $grade) {
                    $ket1[] = "Masuk zona merah";
                  } else {
                    $ket1[] = "Tidak masuk zona merah";
                  }
                  if ($kuota == 0) {
                    $ket1[] = "Kuota sudah habis";
                  } else {
                    $ket1[] = "Kuota masih tersedia";
                  }
                  $keterangan1 = implode(', ', $ket1);

                  //keterangan pilihan 2
                  $rules = array(
                    'database' => null, //Default database master
                    'select'    => null,
                    'where'     => array(
                      'idp_formulir' => $tbpFormulir->idp_formulir,
                      'pilihan' => '2',
                    ),
                    'or_where' => null,
                    'like' => null,
                    'or_like' => null,
                    'order' => null,
                    'limit' => null,
                    'group_by' => null,
                  );
                  $tbpPilihan2 = $this->Tbp_pilihan->search($rules)->row();
                  $rules = array(
                    'database'    => null,
                    'select'    => null,
                    'where'     => array(
                      'kode_jurusan' => $tbpPilihan2->kode_jurusan,
                    ),
                    'or_where' => null,
                    'like' => null,
                    'or_like' => null,
                    'order' => null,
                    'limit' => null,
                    'group_by' => null,
                  );
                  $viewsDayaTampung = $this->Views_daya_tampung->search($rules)->row();
                  $grade = $viewsDayaTampung->grade / 2;
                  $kuota = $viewsDayaTampung->kuota;
                  if ($viewpKelulusan->total < $grade) {
                    $ket2[] = "Masuk zona merah";
                  } else {
                    $ket2[] = "Tidak masuk zona merah";
                  }
                  if ($kuota == 0) {
                    $ket2[] = "Kuota sudah habis";
                  } else {
                    $ket2[] = "Kuota masih tersedia";
                  }
                  $keterangan2 = implode(', ', $ket2);

                  //keterangan pilihan 3
                  $rules = array(
                    'database' => null, //Default database master
                    'select'    => null,
                    'where'     => array(
                      'idp_formulir' => $tbpFormulir->idp_formulir,
                      'pilihan' => '3',
                    ),
                    'or_where' => null,
                    'like' => null,
                    'or_like' => null,
                    'order' => null,
                    'limit' => null,
                    'group_by' => null,
                  );
                  $tbpPilihan3 = $this->Tbp_pilihan->search($rules)->row();
                  $rules = array(
                    'database'    => null,
                    'select'    => null,
                    'where'     => array(
                      'kode_jurusan' => $tbpPilihan3->kode_jurusan,
                    ),
                    'or_where' => null,
                    'like' => null,
                    'or_like' => null,
                    'order' => null,
                    'limit' => null,
                    'group_by' => null,
                  );
                  $viewsDayaTampung = $this->Views_daya_tampung->search($rules)->row();
                  $grade = $viewsDayaTampung->grade / 2;
                  $kuota = $viewsDayaTampung->kuota;
                  if ($viewpKelulusan->total < $grade) {
                    $ket3[] = "Masuk zona merah";
                  } else {
                    $ket3[] = "Tidak masuk zona merah";
                  }
                  if ($kuota == 0) {
                    $ket3[] = "Kuota sudah habis";
                  } else {
                    $ket3[] = "Kuota masih tersedia";
                  }
                  $keterangan3 = implode(', ', $ket3);
                  $reportHtml .=
                    "<tr>
                      <td>" . ++$no . "</td>
                      <td> $nomor_peserta</td>
                      <td> $viewpKelulusan->nama </td>
                      <td> $lulus </td>
                      <td> $viewpKelulusan->total </td>
                      <td> <ul>$pilihan</ul> </td>
                      <td> 
                        <ul>
                          <li>Pilihan ke-1 : $keterangan1</li>
                          <li>Pilihan ke-2 : $keterangan2</li>
                          <li>Pilihan ke-3 : $keterangan3</li>
                        </ul>
                      </td>
                    </tr>";
                }
                $berhasil++;
              } else {
                $reportHtml .=
                  "<tr>
                    <td>" . ++$no . "</td>
                    <td> $nomor_peserta </td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                    <td> <ul>$pilihan</ul> </td>
                    <td>-</td>
                  </tr>";
                $error++;
              }
            }
          } catch (Exception $e) {
            $this->session->set_flashdata('message', $e->getMessage());
            $this->session->set_flashdata('type_message', 'danger');
            redirect('kelulusan/import/cek-kelulusan/');
          }
        }
      }
      $data = array(
        'title'       => 'Import Cek Kelulusan | ' . $_ENV['APPLICATION_NAME'],
        'content'     => 'Import/Kelulusan/cek_kelulusan/content',
        'css'         => 'Import/Kelulusan/cek_kelulusan/css',
        'javascript'  => 'Import/Kelulusan/cek_kelulusan/javascript',
        'modal'       => 'Import/Kelulusan/cek_kelulusan/modal',
        'reportHtml'  => (!empty($reportHtml)) ? $reportHtml : null,
      );
      $this->load->view('index', $data);
    } else {
      $this->session->set_flashdata('message', 'Hak akses ditolak.');
      $this->session->set_flashdata('type_message', 'danger');
      redirect('dashboard/');
    }
  }

  function Import()
  {
    $hak_akses = $this->master->read('hak-akses/?ids_level=' . $this->jwt->ids_level . '&ids_modul=90&aksi_hak_akses=import');
    if ($hak_akses->code == 200) {
      $config = array(
        'upload_path'   => './upload/mandiri/',
        'allowed_types' => 'xls|xlsx|csv|ods|ots',
        'max_size'      => 51200,
        'overwrite'     => TRUE,
        'file_name'     => 'Cek_Kelulusan_' . date('H i s d m Y'),
      );
      $this->upload->initialize($config);
      if (!$this->upload->do_upload()) {
        $this->session->set_flashdata('message', $this->upload->display_errors());
        $this->session->set_flashdata('type_message', 'danger');
        redirect('kelulusan/import/cek-kelulusan/');
      } else {
        $file = $this->upload->data();
        $inputFileName = 'upload/mandiri/' . $file['file_name'];
        try {
          $inputFileType = 'Xlsx'; // Xls, Xlsx, Xml, Ods, Slk, Gnumeric, Csv
          /**  Create a new Reader of the type defined in $inputFileType  **/
          $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($inputFileType);
          /**  Advise the Reader that we only want to load cell data  **/
          $reader->setReadDataOnly(true);
          /**  Advise the Reader of which WorkSheets we want to load  **/
          //$reader->setLoadSheetsOnly($sheetname);
          /**  Load $inputFileName to a Spreadsheet Object  **/
          $spreadsheet = $reader->load($inputFileName);
        } catch (Exception $e) {
          die('Error loading file "' . pathinfo($inputFileName, PATHINFO_BASENAME) . '" : ' . $e->getMessage());
        }
        $sheet = $spreadsheet->getSheet(0);
        $highestRow = $sheet->getHighestRow();
        try {
          $berhasil = $error = $no = 0;
          $reportHtml = null;
          for ($row = 2; $row <= $highestRow; $row++) {
            $nomor_peserta = $sheet->getCellByColumnAndRow(1, $row)->getValue();
            $rules = array(
              'database' => null, //Default database master
              'select'    => null,
              'where'     => array(
                'nomor_peserta' => $nomor_peserta
              ),
              'or_where' => null,
              'like' => null,
              'or_like' => null,
              'order' => null,
              'limit' => null,
              'group_by' => null,
            );
            $tbpFormulir = $this->Tbp_formulir->search($rules)->row();
            $rules = array(
              'database' => null, //Default database master
              'select'    => null,
              'where'     => array(
                'idp_formulir' => $tbpFormulir->idp_formulir
              ),
              'or_where' => null,
              'like' => null,
              'or_like' => null,
              'order' => null,
              'limit' => null,
              'group_by' => null,
            );
            $viewpKelulusan = $this->Viewp_kelulusan->search($rules);
            if ($viewpKelulusan->num_rows() > 0) {
              $viewpKelulusan = $viewpKelulusan->row();
              $rules = array(
                'database' => null, //Default database master
                'select'    => null,
                'where'     => array(
                  'idp_formulir' => $tbpFormulir->idp_formulir,
                  'kode_jurusan' => $viewpKelulusan->kode_jurusan,
                ),
                'or_where' => null,
                'like' => null,
                'or_like' => null,
                'order' => null,
                'limit' => null,
                'group_by' => null,
              );
              $tbpPilihan = $this->Tbp_pilihan->search($rules)->row();
              $reportHtml .=
                "<tr>
                    <td>" . ++$no . "</td>
                    <td> $nomor_peserta</td>
                    <td> $viewpKelulusan->nama </td>
                    <td> $viewpKelulusan->lulus </td>
                    <td> Pilihan ke-$tbpPilihan->pilihan </td>
                    <td> $viewpKelulusan->kode_jurusan  - $viewpKelulusan->jurusan </td>
                    <td> $viewpKelulusan->fakultas </td>
                  </tr>";
              $berhasil++;
            } else {
              $reportHtml .=
                "<tr>
                    <td>" . ++$no . "</td>
                    <td> $nomor_peserta </td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                  </tr>";
              $error++;
            }
          }
        } catch (Exception $e) {
          $this->session->set_flashdata('message', $e->getMessage());
          $this->session->set_flashdata('type_message', 'danger');
          redirect('kelulusan/import/cek-kelulusan/');
        }
      }
      $data = array(
        'title'       => 'Import Cek Kelulusan | ' . $_ENV['APPLICATION_NAME'],
        'content'     => 'Import/Kelulusan/cek_kelulusan/content',
        'css'         => 'Import/Kelulusan/cek_kelulusan/css',
        'javascript'  => 'Import/Kelulusan/cek_kelulusan/javascript',
        'modal'       => 'Import/Kelulusan/cek_kelulusan/modal',
        'reportHtml'  => (!empty($reportHtml)) ? $reportHtml : null,
      );
      $this->load->view('index', $data);
    } else {
      $this->session->set_flashdata('message', 'Hak akses ditolak.');
      $this->session->set_flashdata('type_message', 'danger');
      redirect('dashboard/');
    }
  }
}
