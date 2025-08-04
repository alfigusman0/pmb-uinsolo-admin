<?php
defined('BASEPATH') or exit('No direct script access allowed');
class CekSanggah extends CI_Controller
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
    $this->load->model('Mandiri/Tbp_sanggah');
    $this->load->model('Mandiri/Tbp_formulir');
    $this->load->model('Mandiri/Tbp_pilihan');
    $this->load->model('Mandiri/Viewp_kelulusan');
    $this->load->model('Mandiri/Viewp_sanggah');
    $this->load->model('Mandiri/Viewp_pilihan');
    $this->load->model('Settings/Tbs_sanggah');
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
          redirect('kelulusan/import/cek-sanggah/');
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
                    'idp_formulir' => $tbpFormulir->idp_formulir
                  ),
                  'or_where' => null,
                  'like' => null,
                  'or_like' => null,
                  'order' => null,
                  'limit' => null,
                  'group_by' => null,
                );
                $viewpSanggah = $this->Viewp_sanggah->search($rules);
                $sanggah = null;
                if($viewpSanggah->num_rows() > 0){
                  $sanggah = $viewpSanggah->row();
                }
                $reportHtml .=
                  "<tr>
                    <td>" . ++$no . "</td>
                    <td> $nomor_peserta </td>
                    <td> $viewpKelulusan->nama </td>
                    <td> $lulus </td>
                    <td> $sanggah->sanggah </td>
                    <td>
                      <a href=\"javascript:void(0)\" title=\"Detail\" class=\"btn btn-xs btn-warning m-1\" onclick=\"modal_jawab(" . $sanggah->idp_sanggah . ")\">
                          <span class=\"tf-icon bx bx-edit bx-xs\"></span> Jawab
                      </a>
                      <a href=\"" . base_url('mandiri/mahasiswa/detail/' . $tbpFormulir->idp_formulir) . "\" title=\"Biodata\" class=\"btn btn-xs btn-primary m-1\" target=\"_blank\">
                          <span class=\"tf-icon bx bx-detail bx-xs\"></span> Biodata
                      </a>
                    </td>
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
                  </tr>";
                $error++;
              }
            }
          } catch (Exception $e) {
            $this->session->set_flashdata('message', $e->getMessage());
            $this->session->set_flashdata('type_message', 'danger');
            redirect('kelulusan/import/cek-sanggah/');
          }
        }
      }
      $rules2 = array(
        'database'          => null, //Default database master
        'select'            => null, // not null
        'where'                => array('status' => 'YA'),
        'or_where'            => null,
        'like'                => null,
        'or_like'            => null,
        'order'                => null,
        'group_by'            => null,
        'limit'            => null,
      );
      $data = array(
        'title'       => 'Import Cek Sanggah | ' . $_ENV['APPLICATION_NAME'],
        'content'     => 'Import/Kelulusan/cek_sanggah/content',
        'css'         => 'Import/Kelulusan/cek_sanggah/css',
        'javascript'  => 'Import/Kelulusan/cek_sanggah/javascript',
        'modal'       => 'Import/Kelulusan/cek_sanggah/modal',
        'reportHtml'  => (!empty($reportHtml)) ? $reportHtml : null,
        'tbsSanggah'         => $this->Tbs_sanggah->search($rules2)->result(),
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
        redirect('kelulusan/import/cek-sanggah/');
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
                  'idp_formulir' => $tbpFormulir->idp_formulir
                ),
                'or_where' => null,
                'like' => null,
                'or_like' => null,
                'order' => null,
                'limit' => null,
                'group_by' => null,
              );
              $viewpSanggah = $this->Viewp_sanggah->search($rules);
              $sanggah = null;
              if($viewpSanggah->num_rows() > 0){
                $sanggah = $viewpSanggah->row();
              }
              $reportHtml .=
                "<tr>
                  <td>" . ++$no . "</td>
                  <td> $nomor_peserta </td>
                  <td> $viewpKelulusan->nama </td>
                  <td> $lulus </td>
                  <td> $sanggah->sanggah </td>
                  <td>
                    <a href=\"javascript:void(0)\" title=\"Detail\" class=\"btn btn-xs btn-warning m-1\" onclick=\"modal_jawab(" . $sanggah->idp_sanggah . ")\">
                        <span class=\"tf-icon bx bx-edit bx-xs\"></span> Jawab
                    </a>
                    <a href=\"" . base_url('mandiri/mahasiswa/detail/' . $tbpFormulir->idp_formulir) . "\" title=\"Biodata\" class=\"btn btn-xs btn-primary m-1\" target=\"_blank\">
                        <span class=\"tf-icon bx bx-detail bx-xs\"></span> Biodata
                    </a>
                  </td>
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
                </tr>";
              $error++;
            }
          }
        } catch (Exception $e) {
          $this->session->set_flashdata('message', $e->getMessage());
          $this->session->set_flashdata('type_message', 'danger');
          redirect('kelulusan/import/cek-sanggah/');
        }
      }
      $rules2 = array(
        'database'          => null, //Default database master
        'select'            => null, // not null
        'where'                => array('status' => 'YA'),
        'or_where'            => null,
        'like'                => null,
        'or_like'            => null,
        'order'                => null,
        'group_by'            => null,
        'limit'            => null,
      );
      $data = array(
        'title'       => 'Import Cek Sanggah | ' . $_ENV['APPLICATION_NAME'],
        'content'     => 'Import/Kelulusan/cek_sanggah/content',
        'css'         => 'Import/Kelulusan/cek_sanggah/css',
        'javascript'  => 'Import/Kelulusan/cek_sanggah/javascript',
        'modal'       => 'Import/Kelulusan/cek_sanggah/modal',
        'reportHtml'  => (!empty($reportHtml)) ? $reportHtml : null,
        'tbsSanggah'         => $this->Tbs_sanggah->search($rules2)->result(),
      );
      $this->load->view('index', $data);
    } else {
      $this->session->set_flashdata('message', 'Hak akses ditolak.');
      $this->session->set_flashdata('type_message', 'danger');
      redirect('dashboard/');
    }
  }
}
