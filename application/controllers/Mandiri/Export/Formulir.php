<?php
defined('BASEPATH') or exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Formulir extends CI_Controller
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

        $this->load->model('Mandiri/Viewp_formulir');
        $this->load->model('Mandiri/Viewp_biodata');
        $this->load->model('Mandiri/Viewp_sekolah');
        $this->load->model('Mandiri/Viewp_rumah');
        $this->load->model('Mandiri/Viewp_formulir');
        $this->load->model('Mandiri/Viewp_pilihan');
        $this->load->model('Mandiri/Tbp_prestasi');
        $this->load->model('Tbl_users');
    }

    function index()
    {
        $hak_akses = $this->master->read('hak-akses/?ids_level=' . $this->jwt->ids_level . '&ids_modul=68&aksi_hak_akses=export');
        if ($hak_akses->code == 200) {
            $rules = array(
                'database'  => null, //Default database master
                'select'    => 'YEAR(date_created) as tahun', // not null
                'where'     => null,
                'or_where'  => null,
                'like'      => null,
                'or_like'   => null,
                'order'     => null,
                'group_by'  => null,
            );
            $data = array(
                'title'         => 'Export Formulir | ' . $_ENV['APPLICATION_NAME'],
                'content'       => 'Export/Mandiri/formulir/content',
                'css'           => 'Export/Mandiri/formulir/css',
                'javascript'    => 'Export/Mandiri/formulir/javascript',
                'modal'         => 'Export/Mandiri/formulir/modal',

                'tahun'         => $this->Viewp_formulir->distinct($rules)->result(),
            );
            $this->load->view('index', $data);
        } else {
            $this->session->set_flashdata('message', 'Hak akses ditolak.');
            $this->session->set_flashdata('type_message', 'danger');
            redirect('dashboard/');
        }
    }

    function Export()
    {
        $hak_akses = $this->master->read('hak-akses/?ids_level=' . $this->jwt->ids_level . '&ids_modul=68&aksi_hak_akses=export');
        if ($hak_akses->code == 200) {
            $where = array();
            if ($this->input->post('tahun') != 'SEMUA') {
                $where['YEAR(date_created)'] = $this->input->post('tahun');
            }
            if ($this->input->post('formulir') != 'SEMUA') {
                $where['formulir'] = $this->input->post('formulir');
            }
            if ($this->input->post('pembayaran') != 'SEMUA') {
                $where['pembayaran'] = $this->input->post('pembayaran');
            }
            if ($this->input->post('ids_program') != 'SEMUA') {
                $where['ids_program'] = $this->input->post('ids_program');
            }
            if ($this->input->post('ids_tipe_ujian') != 'SEMUA') {
                $where['ids_tipe_ujian'] = $this->input->post('ids_tipe_ujian');
            }
            $rules = array(
                'database'  => null, //Default database master
                'select'    => null,
                'where'     => $where,
                'or_where'  => null,
                'like'      => null,
                'or_like'   => null,
                'order'     => null,
                'limit'     => null,
                'group_by'  => null,
            );
            $viewpFormulir = $this->Viewp_formulir->search($rules);
            if ($viewpFormulir->num_rows() > 0) {
                $spreadsheet = new Spreadsheet();
                $sheet = $spreadsheet->getActiveSheet();
                $spreadsheet->getActiveSheet()->getPageSetup()->setFitToWidth(1);
                $spreadsheet->getActiveSheet()->getPageSetup()->setFitToHeight(1);
                //table header
                if (!empty($this->input->post('prestasi'))) {
                    $cols = array("A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M");
                    $val = array(
                        "No.", "Nomor Peserta", "Nama", "Kategori", "Program", "Jenjang", "Kelas", "Tipe Ujian", "No. HP", "Email", "Formulir", "Pembayaran", "Keterangan"
                    );
                    $num_col = 13;
                } else {
                    $cols = array("A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L");
                    $val = array(
                        "No.", "Nomor Peserta", "Nama", "Kategori", "Program", "Jenjang", "Kelas", "Tipe Ujian", "No. HP", "Email", "Formulir", "Pembayaran"
                    );
                    $num_col = 12;
                }
                for ($a = 0; $a < $num_col; $a++) {
                    $sheet->setCellValue($cols[$a] . '1', $val[$a]);
                }
                $baris  = 2;
                $no = 1;
                foreach ($viewpFormulir->result() as $value) {
                    $rules = array(
                        'database'  => null, //Default database master
                        'select'    => null,
                        'where'     => array(
                            'idp_formulir' => $value->idp_formulir,
                        ),
                        'or_where'  => null,
                        'like'      => null,
                        'or_like'   => null,
                        'order'     => null,
                        'limit'     => null,
                        'group_by'  => null,
                    );
                    $viewpBiodata = $this->Viewp_biodata->search($rules)->row();
                    $rules = array(
                        'database'  => null, //Default database master
                        'select'    => null,
                        'where'     => array(
                            'id_user' => $value->created_by,
                        ),
                        'or_where'  => null,
                        'like'      => null,
                        'or_like'   => null,
                        'order'     => null,
                        'limit'     => null,
                        'group_by'  => null,
                    );
                    $tblUser = $this->Tbl_users->search($rules)->row();

                    if (!empty($this->input->post('prestasi'))) {
                        $keyword = "";
                        if ($this->input->post('prestasi') == 'pmr') {
                            $keyword = "LOWER(prestasi) like '%palang%' or LOWER(prestasi) like '%merah%' or LOWER(prestasi) like '%siaga%' or LOWER(prestasi) like '%bencana%' or LOWER(prestasi) like '%balur%' or LOWER(prestasi) like '%pmr%'";
                        } else if ($this->input->post('prestasi') == 'paskibra') {
                            $keyword = "LOWER(prestasi) like '%pengibaran%' or LOWER(prestasi) like '%bendera%' or LOWER(prestasi) like '%paskibra%' or LOWER(prestasi) like '%lkbb%' or LOWER(prestasi) like '%pbb%' or LOWER(prestasi) like '%baris%' or LOWER(prestasi) like '%purwa%'";
                        } else if ($this->input->post('prestasi') == 'osis') {
                            $keyword = "LOWER(prestasi) like '%osis%' or LOWER(prestasi) like '%ketua%'";
                        } else {
                            $keyword = "";
                        }
                        if ($keyword == "") {
                            $rules = array(
                                'database'  => null, //Default database master
                                'select'    => null,
                                'where'     => array(
                                    'idp_formulir' => $value->idp_formulir,
                                    "(LOWER(prestasi) like '%palang%' or LOWER(prestasi) like '%merah%' or LOWER(prestasi) like '%siaga%' or LOWER(prestasi) like '%bencana%' or LOWER(prestasi) like '%balur%' or LOWER(prestasi) like '%pmr%')" => null
                                ),
                                'or_where'  => null,
                                'like'      => null,
                                'or_like'   => null,
                                'order'     => null,
                                'limit'     => null,
                                'group_by'  => null,
                            );
                            $tbpPrestasi = $this->Tbp_prestasi->search($rules);
                            if ($tbpPrestasi->num_rows() == 0) {
                                $rules = array(
                                    'database'  => null, //Default database master
                                    'select'    => null,
                                    'where'     => array(
                                        'idp_formulir' => $value->idp_formulir,
                                        "(LOWER(prestasi) like '%pengibaran%' or LOWER(prestasi) like '%bendera%' or LOWER(prestasi) like '%paskibra%' or LOWER(prestasi) like '%lkbb%' or LOWER(prestasi) like '%pbb%' or LOWER(prestasi) like '%baris%' or LOWER(prestasi) like '%purwa%')" => null
                                    ),
                                    'or_where'  => null,
                                    'like'      => null,
                                    'or_like'   => null,
                                    'order'     => null,
                                    'limit'     => null,
                                    'group_by'  => null,
                                );
                                $tbpPrestasi = $this->Tbp_prestasi->search($rules);
                                if ($tbpPrestasi->num_rows() == 0) {
                                    $rules = array(
                                        'database'  => null, //Default database master
                                        'select'    => null,
                                        'where'     => array(
                                            'idp_formulir' => $value->idp_formulir,
                                            "LOWER(prestasi) like '%osis%' or LOWER(prestasi) like '%ketua%'" => null
                                        ),
                                        'or_where'  => null,
                                        'like'      => null,
                                        'or_like'   => null,
                                        'order'     => null,
                                        'limit'     => null,
                                        'group_by'  => null,
                                    );
                                    $tbpPrestasi = $this->Tbp_prestasi->search($rules);
                                    if ($tbpPrestasi->num_rows() == 0) {
                                        continue;
                                    }
                                }
                            }
                        } else {
                            $rules = array(
                                'database'  => null, //Default database master
                                'select'    => null,
                                'where'     => array(
                                    'idp_formulir' => $value->idp_formulir,
                                    "($keyword)" => null
                                ),
                                'or_where'  => null,
                                'like'      => null,
                                'or_like'   => null,
                                'order'     => null,
                                'limit'     => null,
                                'group_by'  => null,
                            );
                            $tbpPrestasi = $this->Tbp_prestasi->search($rules);
                            if ($tbpPrestasi->num_rows() == 0) {
                                continue;
                            }
                        }
                    }

                    $sheet->setCellValue("A" . $baris, $no);
                    $sheet->setCellValueExplicit("B" . $baris, $value->nomor_peserta, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                    $sheet->setCellValue("C" . $baris, $value->nama);
                    $sheet->setCellValue("D" . $baris, $value->kategori);
                    $sheet->setCellValue("E" . $baris, $value->program);
                    $sheet->setCellValue("F" . $baris, $value->jenjang);
                    $sheet->setCellValue("G" . $baris, $value->kelas);
                    $sheet->setCellValue("H" . $baris, $value->tipe_ujian);
                    $sheet->setCellValue("I" . $baris, $tblUser->nmr_tlpn);
                    $sheet->setCellValue("J" . $baris, $tblUser->email);
                    $sheet->setCellValue("K" . $baris, $value->formulir);
                    $sheet->setCellValue("L" . $baris, $value->pembayaran);
                    if (!empty($this->input->post('prestasi'))) {
                        $sheet->setCellValue("M" . $baris, $this->input->post('prestasi'));
                    }

                    $baris++;
                    $no++;
                }
                $writer = new Xlsx($spreadsheet);
                $filename = urlencode("Formulir_" . date("Y_m_d_H_i_s") . ".xlsx");
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment;filename="' . $filename . '"');
                header('Cache-Control: max-age=0');
                $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
                $writer->save('php://output');
            } else {
                $this->session->set_flashdata('message', 'Data kosong.');
                $this->session->set_flashdata('type_message', 'danger');
                redirect('mandiri/export/formulir');
            }
        } else {
            $this->session->set_flashdata('message', 'Hak akses ditolak.');
            $this->session->set_flashdata('type_message', 'danger');
            redirect('dashboard/');
        }
    }
}
