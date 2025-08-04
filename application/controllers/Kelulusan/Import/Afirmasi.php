<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Afirmasi extends CI_Controller
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

        $this->load->model('Mandiri/Tbp_pilihan');
        $this->load->model('Mandiri/Tbp_kelulusan');
        $this->load->model('Mandiri/Tbp_formulir');
        $this->load->model('Settings/Tbs_daya_tampung');
        $this->load->model('Settings/Views_daya_tampung');
    }

    function index()
    {
        $hak_akses = $this->master->read('hak-akses/?ids_level=' . $this->jwt->ids_level . '&ids_modul=90&aksi_hak_akses=import');
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
                'title' => 'Import Afirmasi | ' . $_ENV['APPLICATION_NAME'],
                'content'   => 'Import/Kelulusan/afirmasi/content',
                'css'       => 'Import/Kelulusan/afirmasi/css',
                'javascript' => 'Import/Kelulusan/afirmasi/javascript',
                'modal'     => 'Import/Kelulusan/afirmasi/modal',

                'tahun'         => $this->Views_daya_tampung->distinct($rules)->result(),
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
                'upload_path' => './upload/mandiri/',
                'allowed_types' => 'xls|xlsx|csv|ods|ots',
                'max_size' => 51200,
                'overwrite' => TRUE,
                'file_name' => 'Afirmasi_' . date('Y') . '_' . date('HisdmY'),
            );
            $this->upload->initialize($config);
            if (!$this->upload->do_upload()) {
                $this->session->set_flashdata('message', $this->upload->display_errors());
                $this->session->set_flashdata('type_message', 'danger');
                redirect('kelulusan/import/afirmasi');
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
                    $berhasil = 0;
                    $error = 0;
                    $errorHtml = "<div class='table-responsive'><table class='table table-responsive'>";
                    for ($row = 2; $row <= $highestRow; $row++) {
                        $nomor_peserta = $sheet->getCellByColumnAndRow(1, $row)->getValue();
                        $kode_jurusan = $sheet->getCellByColumnAndRow(3, $row)->getValue();
                        $nama_penitip = $sheet->getCellByColumnAndRow(5, $row)->getValue();
                        $keterangan = $sheet->getCellByColumnAndRow(6, $row)->getValue();
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
                        $tbpFormulir = $this->Tbp_formulir->search($rules)->row();
                        // Cek Kelulusan
                        $rules = array(
                            'database' => null, //Default database master
                            'select' => null,
                            'where' => array(
                                'idp_formulir' => $tbpFormulir->idp_formulir,
                                'LULUS' => 'YA'
                            ),
                            'or_where' => null,
                            'like' => null,
                            'or_like' => null,
                            'order' => null,
                            'limit' => null,
                            'group_by' => null,
                        );
                        $check = $this->Tbp_kelulusan->search($rules)->num_rows();
                        if ($check > 0) {
                            $errorHtml .= "<tr><td> $nomor_peserta #Error: Sudah Lulus.</td></tr>";
                            $error++;
                        } else {
                            // Cek Pilihan Jurusan
                            $num = 0;
                            for ($i = 1; $i < 4; $i++) {
                                $rules = array(
                                    'database' => null, //Default database master
                                    'select'    => null,
                                    'where'     => array(
                                        'idp_formulir' => $tbpFormulir->idp_formulir,
                                        'pilihan' => "$i",
                                        'kode_jurusan' => $kode_jurusan,
                                    ), //not null or null
                                    'or_where' => null,
                                    'like' => null,
                                    'or_like' => null,
                                    'order' => null,
                                    'limit' => null,
                                    'group_by' => null,
                                );
                                $num += $this->Tbp_pilihan->search($rules)->num_rows();
                            }
                            if ($num > 0) {
                                // Cek Kuota Daya Tampung
                                $rules = array(
                                    'database'    => null,
                                    'select'    => null,
                                    'where'     => array(
                                        'kode_jurusan' => $kode_jurusan,
                                        'YEAR(date_created)' => $this->input->post('tahun')
                                    ), //not null or null
                                    'or_where' => null,
                                    'like' => null,
                                    'or_like' => null,
                                    'order' => null,
                                    'limit' => null,
                                    'group_by' => null,
                                );
                                $tbsDayaTampung = $this->Tbs_daya_tampung->search($rules)->row();
                                if ($tbsDayaTampung->kuota > 0) {
                                    // Tipe Ujian CBT Cek Grade
                                    if ($tbpFormulir->ids_tipe_ujian == 1) {
                                        $rules = array(
                                            'database' => null, //Default database master
                                            'select'    => null,
                                            'where'     => array(
                                                'idp_formulir' => $tbpFormulir->idp_formulir,
                                            ), //not null or null
                                            'or_where' => null,
                                            'like' => null,
                                            'or_like' => null,
                                            'order' => null,
                                            'limit' => null,
                                            'group_by' => null,
                                        );
                                        $tbpKelulusan = $this->Tbp_kelulusan->search($rules)->row();
                                        if ($tbpKelulusan->lulus != 'YA') {
                                            if ($tbpKelulusan->total >= ($tbsDayaTampung->grade / 2)) {
                                                $rules = array(
                                                    'where' => array(
                                                        'idp_formulir' => $tbpFormulir->idp_formulir,
                                                    ),
                                                    'or_where'  => null,
                                                    'like'      => null,
                                                    'or_like'   => null,
                                                    'data'  => array(
                                                        'kode_jurusan' => $kode_jurusan,
                                                        'lulus' => 'YA',
                                                        'nama_penitip' => ($nama_penitip != null) ? $nama_penitip : '-',
                                                        'keterangan' => ($keterangan != null) ? $keterangan : '-',
                                                        'updated_by' => $this->jwt->ids_user,
                                                    ),
                                                );
                                                $fb = $this->Tbp_kelulusan->update($rules);
                                                if (!$fb['status']) {
                                                    $rules = array(
                                                        'where' => array(
                                                            'ids_daya_tampung' => $tbsDayaTampung->ids_daya_tampung,
                                                        ),
                                                        'or_where'  => null,
                                                        'like'      => null,
                                                        'or_like'   => null,
                                                        'data'  => array(
                                                            'kuota' => $tbsDayaTampung->kuota - 1,
                                                        ),
                                                    );
                                                    $fb = $this->Tbs_daya_tampung->update($rules);
                                                    if (!$fb['status']) {
                                                        $berhasil++;
                                                    } else {
                                                        $errorHtml .= "<tr><td> $nomor_peserta #Error: Update Quota Jurusan.</td></tr>";
                                                        $error++;
                                                    }
                                                } else {
                                                    $errorHtml .= "<tr><td> $nomor_peserta #Error: Update gagal.</td></tr>";
                                                    $error++;
                                                }
                                            } else {
                                                $errorHtml .= "<tr><td> $nomor_peserta #Error: Tidak lulus grade.</td></tr>";
                                                $error++;
                                            }
                                        } else {
                                            $berhasil++;
                                        }
                                    } else {
                                        $rules = array(
                                            'database' => null, //Default database master
                                            'select'    => null,
                                            'where'     => array(
                                                'idp_formulir' => $tbpFormulir->idp_formulir,
                                            ), //not null or null
                                            'or_where' => null,
                                            'like' => null,
                                            'or_like' => null,
                                            'order' => null,
                                            'limit' => null,
                                            'group_by' => null,
                                        );
                                        $tbpKelulusan = $this->Tbp_kelulusan->search($rules)->row();
                                        if ($tbpKelulusan->lulus != 'YA') {
                                            $rules = array(
                                                'where' => array(
                                                    'idp_formulir' => $tbpFormulir->idp_formulir,
                                                ),
                                                'or_where'  => null,
                                                'like'      => null,
                                                'or_like'   => null,
                                                'data'  => array(
                                                    'kode_jurusan' => $kode_jurusan,
                                                    'lulus' => 'YA',
                                                    'nama_penitip' => ($nama_penitip != null) ? $nama_penitip : '-',
                                                    'keterangan' => ($keterangan != null) ? $keterangan : '-',
                                                    'updated_by' => $this->jwt->ids_user,
                                                ),
                                            );
                                            $fb = $this->Tbp_kelulusan->update($rules);
                                            if (!$fb['status']) {
                                                $rules = array(
                                                    'where' => array(
                                                        'ids_daya_tampung' => $tbsDayaTampung->ids_daya_tampung,
                                                    ),
                                                    'or_where'  => null,
                                                    'like'      => null,
                                                    'or_like'   => null,
                                                    'data'  => array(
                                                        'kuota' => $tbsDayaTampung->kuota - 1,
                                                    ),
                                                );
                                                $fb = $this->Tbs_daya_tampung->update($rules);
                                                if (!$fb['status']) {
                                                    $berhasil++;
                                                } else {
                                                    $errorHtml .= "<tr><td> $nomor_peserta #Error: Update Quota Jurusan.</td></tr>";
                                                    $error++;
                                                }
                                            } else {
                                                $errorHtml .= "<tr><td> $nomor_peserta #Error: Update gagal.</td></tr>";
                                                $error++;
                                            }
                                        } else {
                                            $berhasil++;
                                        }
                                    }
                                } else {
                                    $errorHtml .= "<tr><td> $nomor_peserta #Error: Kuota habis.</td></tr>";
                                    $error++;
                                }
                            } else {
                                $errorHtml .= "<tr><td> $nomor_peserta #Error: Pilihan tidak sesuai.</td></tr>";
                                $error++;
                            }
                        }
                    }
                    $errorHtml .= "</table></div>";
                    $this->session->set_flashdata('message', 'Import berhasil. Berhasil : ' . $berhasil . '. Error : ' . $error);
                    $this->session->set_flashdata('error', $error);
                    $this->session->set_flashdata('errorHtml', $errorHtml);
                    $this->session->set_flashdata('type_message', ($error == 0) ? 'success' : 'warning');
                    redirect('kelulusan/import/afirmasi');
                } catch (Exception $e) {
                    $this->session->set_flashdata('message', $e->getMessage());
                    $this->session->set_flashdata('type_message', 'danger');
                    redirect('kelulusan/import/afirmasi');
                }
            }
        } else {
            $this->session->set_flashdata('message', 'Hak akses ditolak.');
            $this->session->set_flashdata('type_message', 'danger');
            redirect('dashboard/');
        }
    }
}
