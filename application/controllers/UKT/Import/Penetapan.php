<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Penetapan extends CI_Controller
{
    var $jwt = null;

    function __construct()
    {
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');
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

        $this->load->model('Daftar/Tbd_ukt');
        $this->load->model('Daftar/Viewd_kelulusan');
        $this->load->model('Daftar/Viewd_ukt');
    }

    function index()
    {
        $hak_akses = $this->master->read('hak-akses/?ids_level=' . $this->jwt->ids_level . '&ids_modul=55&aksi_hak_akses=import');
        if ($hak_akses->code == 200) {
            $data = array(
                'title' => 'Import Petenatpan UKT | ' . $_ENV['APPLICATION_NAME'],
                'content' => 'Import/UKT/penetapan/content',
                'css' => 'Import/UKT/penetapan/css',
                'javascript' => 'Import/UKT/penetapan/javascript',
                'modal' => 'Import/UKT/penetapan/modal',
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
        $hak_akses = $this->master->read('hak-akses/?ids_level=' . $this->jwt->ids_level . '&ids_modul=55&aksi_hak_akses=import');
        if ($hak_akses->code == 200) {
            $config = array(
                'upload_path' => './upload/daftar/',
                'allowed_types' => 'xls|xlsx|csv|ods|ots',
                'max_size' => 51200,
                'overwrite' => TRUE,
                'file_name' => 'PENETAPAN_UKT_' . date('H_i_s_d_m_Y'),
            );
            $this->upload->initialize($config);
            if (!$this->upload->do_upload()) {
                $this->session->set_flashdata('message', $this->upload->display_errors());
                $this->session->set_flashdata('type_message', 'danger');
                redirect('ukt/import/penetapan');
            } else {
                $file = $this->upload->data();
                $inputFileName = 'upload/daftar/' . $file['file_name'];
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
                $created = $updated = $error = 0;
                $error_message = '';
                try {
                    for ($row = 2; $row <= $highestRow; $row++) {
                        $nomor_peserta = $sheet->getCellByColumnAndRow(1, $row)->getValue();
                        $nama = $sheet->getCellByColumnAndRow(2, $row)->getValue();
                        $kategori = $sheet->getCellByColumnAndRow(3, $row)->getValue();
                        $rules = array(
                            'database' => null, //Default database master
                            'select' => null,
                            'where' => array(
                                'nomor_peserta' => $nomor_peserta
                            ),
                            'or_where' => null,
                            'like' => null,
                            'or_like' => null,
                            'order' => null,
                            'limit' => null,
                            'group_by' => null,
                        );
                        $viewdKelulusan = $this->Viewd_kelulusan->search($rules)->row();
                        $tbsUKT = $this->master->read("ukt/?kode_jurusan=$viewdKelulusan->kode_jurusan&kategori=$kategori");
                        if ($tbsUKT->code == 200) {
                            $jumlah = 0;
                            $tbsUKT = json_decode(json_encode($tbsUKT->data->data), true);
                            if ($viewdKelulusan->ids_jalur_masuk == 1) {
                                $jumlah = $tbsUKT[0]['snmptn'];
                            } else if ($viewdKelulusan->ids_jalur_masuk == 2) {
                                $jumlah = $tbsUKT[0]['spanptkin'];
                            } else if ($viewdKelulusan->ids_jalur_masuk == 3) {
                                $jumlah = $tbsUKT[0]['sbmptn'];
                            } else if ($viewdKelulusan->ids_jalur_masuk == 4) {
                                $jumlah = $tbsUKT[0]['umptkin'];
                            } else if ($viewdKelulusan->ids_jalur_masuk == 5) {
                                $jumlah = $tbsUKT[0]['mandiri'];
                            } else if ($viewdKelulusan->ids_jalur_masuk == 6) {
                                $jumlah = $tbsUKT[0]['pbsb'];
                            } else if ($viewdKelulusan->ids_jalur_masuk == 7) {
                                $jumlah = $tbsUKT[0]['snbp'];
                            } else if ($viewdKelulusan->ids_jalur_masuk == 8) {
                                $jumlah = $tbsUKT[0]['snbt'];
                            }
                            $viewdUKT = $this->Viewd_ukt->search($rules);
                            if ($viewdUKT->num_rows() == 0) {
                                $rules = array(
                                    'idd_kelulusan' => $viewdKelulusan->idd_kelulusan,
                                    'score' => 0,
                                    'kategori' => $kategori,
                                    'jumlah' => $jumlah,
                                    'created_by' => $this->jwt->ids_user,
                                    'updated_by' => $this->jwt->ids_user,
                                );
                                $fb = $this->Tbd_ukt->create($rules);
                                if (!$fb['status']) {
                                    $created++;
                                } else {
                                    $error++;
                                }
                            } else {
                                $viewdUKT = $viewdUKT->row();
                                $rules = array(
                                    'where' => array('idd_ukt' => $viewdUKT->idd_ukt),
                                    'or_where' => null,
                                    'like' => null,
                                    'or_like' => null,
                                    'data' => array(
                                        'kategori' => $kategori,
                                        'jumlah' => $jumlah,
                                        'updated_by' => $this->jwt->ids_user,
                                    ), // not null
                                );
                                $fb = $this->Tbd_ukt->update($rules);
                                if (!$fb['status']) {
                                    $updated++;
                                } else {
                                    $error++;
                                }
                            }
                        } else {
                            $error++;
                            $error_message .= "Nomor Peserta/Nama : $nomor_peserta / $nama. Error Message: $tbsUKT->message. <br>";
                        }
                    }
                    $this->session->set_flashdata('message', 'Import berhasil. Created: ' . $created . '. Updated: ' . $updated . '. Error: ' . $error . '. Message: ' . $error_message);
                    $this->session->set_flashdata('type_message', ($error > 0) ? 'warning' : 'success');
                    redirect('ukt/import/penetapan');
                } catch (Exception $e) {
                    $this->session->set_flashdata('message', $e->getMessage());
                    $this->session->set_flashdata('type_message', 'danger');
                    redirect('ukt/import/penetapan');
                }
            }
        } else {
            $this->session->set_flashdata('message', 'Hak akses ditolak.');
            $this->session->set_flashdata('type_message', 'danger');
            redirect('dashboard/');
        }
    }
}
