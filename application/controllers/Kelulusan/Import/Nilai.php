<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Nilai extends CI_Controller
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

        $this->load->model('Mandiri/Tbp_nilai');
        $this->load->model('Mandiri/Tbp_formulir');
    }

    function index()
    {
        $hak_akses = $this->master->read('hak-akses/?ids_level=' . $this->jwt->ids_level . '&ids_modul=89&aksi_hak_akses=import');
        if ($hak_akses->code == 200) {
            $data = array(
                'title' => 'Import Nilai | ' . $_ENV['APPLICATION_NAME'],
                'content'   => 'Import/Kelulusan/nilai/content',
                'css'       => 'Import/Kelulusan/nilai/css',
                'javascript' => 'Import/Kelulusan/nilai/javascript',
                'modal'     => 'Import/Kelulusan/nilai/modal',
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
        $hak_akses = $this->master->read('hak-akses/?ids_level=' . $this->jwt->ids_level . '&ids_modul=89&aksi_hak_akses=import');
        if ($hak_akses->code == 200) {
            $config = array(
                'upload_path' => './upload/mandiri/',
                'allowed_types' => 'xls|xlsx|csv|ods|ots',
                'max_size' => 51200,
                'overwrite' => TRUE,
                'file_name' => 'Nilai_' . date('Y') . '_' . date('HisdmY'),
            );
            $this->upload->initialize($config);
            if (!$this->upload->do_upload()) {
                $this->session->set_flashdata('message', $this->upload->display_errors());
                $this->session->set_flashdata('type_message', 'danger');
                redirect('kelulusan/import/nilai');
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
                $berhasil = 0;
                $error = 0;
                for ($row = 2; $row <= $highestRow; $row++) {
                    $nomor_peserta = preg_replace("!\n+!", '', preg_replace('!\s+!', '', $sheet->getCellByColumnAndRow(1, $row)->getValue()));
                    $keterangan = $sheet->getCellByColumnAndRow(3, $row)->getValue();
                    $nilai = $sheet->getCellByColumnAndRow(4, $row)->getValue();
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
                    $tbpFormulir = $this->Tbp_formulir->search($rules);
                    if ($tbpFormulir->num_rows() > 0) {
                        $tbpFormulir = $tbpFormulir->row();
                        $rules = array(
                            'database'  => null, //Default database master
                            'select'    => null,
                            'where'     => array(
                                'idp_formulir' => $tbpFormulir->idp_formulir,
                                'keterangan' => $keterangan,
                            ),
                            'or_where' => null,
                            'like' => null,
                            'or_like' => null,
                            'order' => null,
                            'limit' => null,
                            'group_by' => null,
                        );
                        $tblNilai = $this->Tbp_nilai->search($rules);
                        if ($tblNilai->num_rows() > 0) {
                            $tblNilai = $tblNilai->row();
                            $rules = array(
                                'where' => array(
                                    'idp_nilai' => $tblNilai->idp_nilai,
                                ),
                                'or_where'  => null,
                                'like'      => null,
                                'or_like'   => null,
                                'data'  => array(
                                    'nilai' => $nilai,
                                    'updated_by' => $this->jwt->ids_user,
                                ),
                            );
                            $this->Tbp_nilai->update($rules);
                            $update++;
                            $pesan .= "Nomor Peserta : $nomor_peserta. Nilai $keterangan : $nilai <br>";
                        } else {
                            $data = array(
                                'idp_formulir' => $tbpFormulir->idp_formulir,
                                'nilai' => $nilai,
                                'keterangan' => $keterangan,
                                'created_by' => $this->jwt->ids_user,
                                'updated_by' => $this->jwt->ids_user,
                            );
                            $fb = $this->Tbp_nilai->create($data);
                            if(!$fb['status']){
                                $berhasil++;
                            }else{
                                $error++;
                            }
                        }
                    }
                }
                $this->session->set_flashdata('message', 'Import berhasil.');
                $this->session->set_flashdata('type_message', 'success');
                redirect('kelulusan/import/nilai');
            }
        } else {
            $this->session->set_flashdata('message', 'Hak akses ditolak.');
            $this->session->set_flashdata('type_message', 'danger');
            redirect('dashboard/');
        }
    }
}
