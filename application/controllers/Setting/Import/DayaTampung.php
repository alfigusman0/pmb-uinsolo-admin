<?php
defined('BASEPATH') or exit('No direct script access allowed');
class DayaTampung extends CI_Controller
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
        $this->load->model('Settings/Tbs_daya_tampung');
        $this->load->model('Settings/Tbs_sub_daya_tampung');
    }

    function index()
    {
        $hak_akses = $this->master->read('hak-akses/?ids_level=' . $this->jwt->ids_level . '&ids_modul=69&aksi_hak_akses=import');
        if ($hak_akses->code == 200) {
            $rules = array(
                'database'  => null, //Default database master
                'select'    => 'YEAR(date_created) as tahun', // not null
                'where'     => null,
                'or_where' => null,
                'like' => null,
                'or_like' => null,
                'order' => null,
                'limit' => null,
                'group_by' => null,
            );
            $data = array(
                'title' => 'Import Setting Daya Tampung | ' . $_ENV['APPLICATION_NAME'],
                'content' => 'Import/Setting/daya_tampung/content',
                'css' => 'Import/Setting/daya_tampung/css',
                'javascript' => 'Import/Setting/daya_tampung/javascript',
                'modal' => 'Import/Setting/daya_tampung/modal',
                'tbsJalurMasuk' => $this->master->read('jalur-masuk/?status=YA'),
                'tahun'         => $this->Tbs_daya_tampung->distinct($rules)->result(),
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
        $hak_akses = $this->master->read('hak-akses/?ids_level=' . $this->jwt->ids_level . '&ids_modul=69&aksi_hak_akses=import');
        if ($hak_akses->code == 200) {
            $config = array(
                'upload_path' => './upload/setting/',
                'allowed_types' => 'xls|xlsx|csv|ods|ots',
                'max_size' => 51200,
                'overwrite' => TRUE,
                'file_name' => 'Daya_Tampung_' . date('H_i_s_d_m_Y'),
            );
            $this->upload->initialize($config);
            if (!$this->upload->do_upload()) {
                $this->session->set_flashdata('message', $this->upload->display_errors());
                $this->session->set_flashdata('type_message', 'danger');
                redirect('import/setting/daya-tampung');
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
                $created = $updated = $error = 0;
                $error_message = '';
                for ($row = 2; $row <= $highestRow; $row++) {
                    $kode_jurusan = $sheet->getCellByColumnAndRow(1, $row)->getValue();
                    $jurusan = $sheet->getCellByColumnAndRow(2, $row)->getValue();
                    $kuota = $sheet->getCellByColumnAndRow(3, $row)->getValue();
                    $rules = array(
                        'database'    => null, //Database master
                        'select'    => null,
                        'where'     => array(
                            'kode_jurusan' => $kode_jurusan,
                            'YEAR(date_created)' => $this->input->post('tahun')
                        ),
                        'or_where'            => null,
                        'like'                => null,
                        'or_like'            => null,
                        'order'                => null,
                        'limit'                => null,
                        'group_by'            => null,
                    );
                    $cek = $this->Tbs_daya_tampung->search($rules);
                    if ($cek->num_rows() > 0) {
                        $cek = $cek->row();
                        $rules = array(
                            'where' => array(
                                'kode_jurusan'  => $kode_jurusan
                            ),
                            'or_where'            => null,
                            'like'                => null,
                            'or_like'            => null,
                            'data'  => array(
                                'kuota'  => $cek->kuota + $kuota,
                                'updated_by' => $this->jwt->ids_user,
                            ),
                        );
                        $fb = $this->Tbs_daya_tampung->update($rules);
                        if (!$fb['status']) {
                            $updated++;
                        } else {
                            $error++;
                            $error_message .= "Daya Tampung : Jurusan $jurusan ($kode_jurusan / " . $this->input->post('ids_jalur_masuk') . ") : $fb->message <br>";
                        }
                    } else {
                        $data = array(
                            'kode_jurusan'  => $kode_jurusan,
                            'daya_tampung'  => 0,
                            'kuota'  => $kuota,
                            'grade'  => 0,
                            'grade_ipa'  => 0,
                            'grade_ips'  => 0,
                            'status'  => 'YA',
                            'created_by' => $this->jwt->ids_user,
                            'updated_by' => $this->jwt->ids_user,
                        );
                        $fb = $this->Tbs_daya_tampung->create($data);
                        if (!$fb['status']) {
                            $updated++;
                        } else {
                            $error++;
                            $error_message .= "Daya Tampung : Jurusan $jurusan ($kode_jurusan / " . $this->input->post('ids_jalur_masuk') . ") : $fb->message <br>";
                        }
                    }

                    /* Sub Daya Tampung
                    $rules = array(
                        'database'    => null, //Database master
                        'select'    => null,
                        'where'     => array(
                            'kode_jurusan' => $kode_jurusan,
                            'ids_jalur_masuk' => $this->input->post('ids_jalur_masuk'),
                            'YEAR(date_created)' => $this->input->post('tahun')
                        ),
                        'or_where'            => null,
                        'like'                => null,
                        'or_like'            => null,
                        'order'                => null,
                        'limit'                => null,
                        'group_by'            => null,
                    );
                    $cek = $this->Tbs_sub_daya_tampung->search($rules);
                    if ($cek->num_rows() > 0) {
                        $cek = $cek->row();
                        $rules = array(
                            'where' => array(
                                'kode_jurusan'  => $kode_jurusan,
                                'ids_jalur_masuk'  => $this->input->post('ids_jalur_masuk'),
                            ),
                            'or_where'            => null,
                            'like'                => null,
                            'or_like'            => null,
                            'data'  => array(
                                'daya_tampung'  => $cek->daya_tampung + $kuota,
                                'status'  => 'YA',
                                'updated_by' => $this->jwt->ids_user,
                            ),
                        );
                        $fb = $this->Tbs_sub_daya_tampung->update($rules);
                        if (!$fb['status']) {
                            $updated++;
                        } else {
                            $error++;
                            $error_message .= "Sub Daya Tampung : Jurusan $jurusan ($kode_jurusan / " . $this->input->post('ids_jalur_masuk') . ") : $fb->message <br>";
                        }
                    } else {
                        $data = array(
                            'kode_jurusan'  => $kode_jurusan,
                            'ids_jalur_masuk'  => $this->input->post('ids_jalur_masuk'),
                            'daya_tampung'  => $kuota,
                            'status'  => 'YA',
                            'created_by' => $this->jwt->ids_user,
                            'updated_by' => $this->jwt->ids_user,
                        );
                        $fb = $this->Tbs_sub_daya_tampung->create($data);
                        if (!$fb['status']) {
                            $updated++;
                        } else {
                            $error++;
                            $error_message .= "Sub Daya Tampung : Jurusan $jurusan ($kode_jurusan / " . $this->input->post('ids_jalur_masuk') . ") : $fb->message <br>";
                        }
                    }
                    */
                }
                $this->session->set_flashdata('message', "Import berhasil. Created: $created. Updated: $updated. Error: $error. Message: <br> $error_message");
                $this->session->set_flashdata('type_message', ($error > 0) ? 'warning' : 'success');
                redirect('setting/import/daya-tampung');
            }
        } else {
            $this->session->set_flashdata('message', 'Hak akses ditolak.');
            $this->session->set_flashdata('type_message', 'danger');
            redirect('dashboard/');
        }
    }
}
