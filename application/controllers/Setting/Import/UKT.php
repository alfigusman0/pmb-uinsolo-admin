<?php
defined('BASEPATH') or exit('No direct script access allowed');
class UKT extends CI_Controller
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
    }

    function index()
    {
        $hak_akses = $this->master->read('hak-akses/?ids_level=' . $this->jwt->ids_level . '&ids_modul=37&aksi_hak_akses=import');
        if ($hak_akses->code == 200) {
            $data = array(
                'title' => 'Import Setting Kategori UKT | ' . $_ENV['APPLICATION_NAME'],
                'content' => 'Import/Setting/kategori_ukt/content',
                'css' => 'Import/Setting/kategori_ukt/css',
                'javascript' => 'Import/Setting/kategori_ukt/javascript',
                'modal' => 'Import/Setting/kategori_ukt/modal',
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
        $hak_akses = $this->master->read('hak-akses/?ids_level=' . $this->jwt->ids_level . '&ids_modul=37&aksi_hak_akses=import');
        if ($hak_akses->code == 200) {
            $config = array(
                'upload_path' => './upload/setting/',
                'allowed_types' => 'xls|xlsx|csv|ods|ots',
                'max_size' => 51200,
                'overwrite' => TRUE,
                'file_name' => 'Kategori_UKT_' . date('H_i_s_d_m_Y'),
            );
            $this->upload->initialize($config);
            if (!$this->upload->do_upload()) {
                $this->session->set_flashdata('message', $this->upload->display_errors());
                $this->session->set_flashdata('type_message', 'danger');
                redirect('import/setting/ukt');
            } else {
                $file = $this->upload->data();
                $inputFileName = 'upload/setting/' . $file['file_name'];
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
                $updated = $error = 0;
                $error_message = '';
                for ($row = 2; $row <= $highestRow; $row++) {
                    $kode_jurusan = $sheet->getCellByColumnAndRow(1, $row)->getValue();
                    $jurusan = $sheet->getCellByColumnAndRow(2, $row)->getValue();
                    $kategori = $sheet->getCellByColumnAndRow(3, $row)->getValue();
                    $snbp = $sheet->getCellByColumnAndRow(4, $row)->getValue();
                    $spanptkin = $sheet->getCellByColumnAndRow(5, $row)->getValue();
                    $snbt = $sheet->getCellByColumnAndRow(6, $row)->getValue();
                    $umptkin = $sheet->getCellByColumnAndRow(7, $row)->getValue();
                    $mandiri = $sheet->getCellByColumnAndRow(8, $row)->getValue();
                    $viewsUKT = $this->master->read("ukt/?kode_jurusan=$kode_jurusan&kategori=$kategori");
                    if ($viewsUKT->code == 200) {
                        $viewsUKT = json_decode(json_encode($viewsUKT->data->data), true);
                        $rules = array(
                            'url' => 'ukt/' . $viewsUKT[0]['ids_ukt'],
                            'data' => array(
                                'snbp' => $snbp,
                                'spanptkin' => $spanptkin,
                                'snbt' => $snbt,
                                'umptkin' => $umptkin,
                                'mandiri' => $mandiri,
                            ),
                        );
                        $fb = $this->master->update($rules);
                        if ($fb->code == 200) {
                            $updated++;
                        } else {
                            $error++;
                            $error_message .= "Jurusan $jurusan ($kode_jurusan / $kategori) : $fb->message <br>";
                        }
                    } else {
                        $error++;
                        $error_message .= "Jurusan : $jurusan ($kode_jurusan) : $viewsUKT->message <br>";
                    }
                }
                $this->session->set_flashdata('message', "Import berhasil. Updated: $updated. Error: $error. Message: <br> $error_message");
                $this->session->set_flashdata('type_message', ($error > 0) ? 'warning' : 'success');
                redirect('import/setting/ukt');
            }
        } else {
            $this->session->set_flashdata('message', 'Hak akses ditolak.');
            $this->session->set_flashdata('type_message', 'danger');
            redirect('dashboard/');
        }
    }
}
