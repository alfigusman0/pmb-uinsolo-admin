<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Kelulusan extends CI_Controller
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

        $this->load->model('Daftar/Tbd_kelulusan');
    }

    function index()
    {
        $hak_akses = $this->master->read('hak-akses/?ids_level=' . $this->jwt->ids_level . '&ids_modul=54&aksi_hak_akses=import');
        if ($hak_akses->code == 200) {
            $data = array(
                'title' => 'Import Daftar Kelulusan | ' . $_ENV['APPLICATION_NAME'],
                'content' => 'Import/Daftar/kelulusan/content',
                'css' => 'Import/Daftar/kelulusan/css',
                'javascript' => 'Import/Daftar/kelulusan/javascript',
                'modal' => 'Import/Daftar/kelulusan/modal',
                'tbsJalurMasuk' => $this->master->read('jalur-masuk/?status=YA')
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
        $hak_akses = $this->master->read('hak-akses/?ids_level=' . $this->jwt->ids_level . '&ids_modul=54&aksi_hak_akses=import');
        if ($hak_akses->code == 200) {
            $rules[] = array('field' => 'ids_jalur_masuk', 'label' => 'Jalur Masuk', 'rules' => 'required');
            $this->form_validation->set_rules($rules);
            if ($this->form_validation->run() == FALSE) {
                $this->session->set_flashdata('message', validation_errors());
                $this->session->set_flashdata('type_message', 'danger');
                redirect('daftar/import/kelulusan');
            } else {
                $config = array(
                    'upload_path' => './upload/daftar/',
                    'allowed_types' => 'xls|xlsx|csv|ods|ots',
                    'max_size' => 51200,
                    'overwrite' => TRUE,
                    'file_name' => 'Kelulusan_' . $this->input->post('ids_jalur_masuk') . '_' . date('Y') . '_' . date('HisdmY'),
                );
                $this->upload->initialize($config);
                if (!$this->upload->do_upload()) {
                    $this->session->set_flashdata('message', $this->upload->display_errors());
                    $this->session->set_flashdata('type_message', 'danger');
                    redirect('daftar/import/kelulusan');
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
                    if ($this->input->post('ids_jalur_masuk') != '5') {
                        for ($row = 2; $row <= $highestRow; $row++) {
                            $nomor_peserta = preg_replace("!\n+!", '', preg_replace('!\s+!', '', $sheet->getCellByColumnAndRow(1, $row)->getValue()));
                            $nama = preg_replace('!\n+!', '', preg_replace('!\s+!', ' ', str_replace('\'', '`', strtoupper($sheet->getCellByColumnAndRow(2, $row)->getValue()))));
                            $kode_jurusan = preg_replace("!\n+!", '', preg_replace('!\s+!', '', $sheet->getCellByColumnAndRow(3, $row)->getValue()));
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
                            $num_rows = $this->Tbd_kelulusan->search($rules)->num_rows();
                            if ($num_rows == 0) {
                                $data = array(
                                    'id_user' => 1,
                                    'nomor_peserta' => $nomor_peserta,
                                    'nim' => null,
                                    'nama' => $nama,
                                    'kode_jurusan' => $kode_jurusan,
                                    'ids_jalur_masuk' => $this->input->post('ids_jalur_masuk'),
                                    'tahun' => date('Y'),
                                    'daftar' => 'BELUM',
                                    'submit' => 'BELUM',
                                    'pembayaran' => 'BELUM',
                                    'pemberkasan' => 'BELUM',
                                    'created_by' => $this->jwt->ids_user,
                                    'updated_by' => $this->jwt->ids_user,
                                );
                                $this->Tbd_kelulusan->create($data);
                            } else {
                                $rules = array(
                                    'where' => array(
                                        'nomor_peserta' => $nomor_peserta
                                    ),
                                    'data' => array(
                                        'nama' => $nama,
                                        'kode_jurusan' => $kode_jurusan,
                                        'ids_jalur_masuk' => $this->input->post('ids_jalur_masuk'),
                                        'updated_by' => $this->jwt->ids_user,
                                    ),
                                );
                                $this->Tbd_kelulusan->update($rules);
                            }
                        }
                        $this->session->set_flashdata('message', 'Import berhasil.');
                        $this->session->set_flashdata('type_message', 'success');
                        redirect('daftar/import/kelulusan');
                    } else {
                        $this->session->set_flashdata('message', 'Gunakan mekanisme integrasi untuk kelulusan Ujian Mandiri.');
                        $this->session->set_flashdata('type_message', 'warning');
                        redirect('daftar/import/kelulusan');
                    }
                }
            }
        } else {
            $this->session->set_flashdata('message', 'Hak akses ditolak.');
            $this->session->set_flashdata('type_message', 'danger');
            redirect('dashboard/');
        }
    }
}
