<?php
defined('BASEPATH') or exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

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
        $this->load->model('Mandiri/Viewp_formulir');
        $this->load->model('Mandiri/Viewp_biodata');
        $this->load->model('Mandiri/Viewp_sekolah');
        $this->load->model('Mandiri/Viewp_rumah');
        $this->load->model('Mandiri/Viewp_kelulusan');
        $this->load->model('Mandiri/Viewp_pilihan');
        $this->load->model('Tbl_users');
    }

    function index()
    {
        $hak_akses = $this->master->read('hak-akses/?ids_level=' . $this->jwt->ids_level . '&ids_modul=89&aksi_hak_akses=export');
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
                'title'         => 'Export Nilai | ' . $_ENV['APPLICATION_NAME'],
                'content'       => 'Export/Kelulusan/nilai/content',
                'css'           => 'Export/Kelulusan/nilai/css',
                'javascript'    => 'Export/Kelulusan/nilai/javascript',
                'modal'         => 'Export/Kelulusan/nilai/modal',

                'tahun'         => $this->Viewp_kelulusan->distinct($rules)->result(),
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
        $hak_akses = $this->master->read('hak-akses/?ids_level=' . $this->jwt->ids_level . '&ids_modul=89&aksi_hak_akses=export');
        if ($hak_akses->code == 200) {
            $where = array();
            if ($this->input->post('tahun') != '') {
                $where['YEAR(date_created)'] = $this->input->post('tahun');
            }
            if ($this->input->post('ids_program') != '') {
                $where['ids_program'] = $this->input->post('ids_program');
            }
            if ($this->input->post('ids_tipe_ujian') != '') {
                $where['ids_tipe_ujian'] = $this->input->post('ids_tipe_ujian');
            }
            $rules = array(
                'database'  => null, //Default database master
                'select'    => null,
                'where'     => $where,
                'or_where'  => null,
                'like'      => null,
                'or_like'   => null,
                'order'     => 'total DESC',
                'limit'     => null,
                'group_by'  => null,
            );
            $viewpKelulusan = $this->Viewp_kelulusan->search($rules);
            if ($viewpKelulusan->num_rows() > 0) {
                $spreadsheet = new Spreadsheet();
                $sheet = $spreadsheet->getActiveSheet();
                $spreadsheet->getActiveSheet()->getPageSetup()->setFitToWidth(1);
                $spreadsheet->getActiveSheet()->getPageSetup()->setFitToHeight(1);
                //table header
                if($this->input->post('ids_program') == 1){
                    $cols = array("A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N");
                    $num_cols = 14;
                    $val = array(
                        "No.", "Nomor Peserta", "Nama", "Program", "Tipe Ujian", "Pilihan 1", "Kode Jurusan 1", "Pilihan 2", "Kode Jurusan 2", "Pilihan 3", "Kode Jurusan 3", "No. HP", "Email", "Total Nilai"
                    );
                }else{
                    $cols = array("A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P");
                    $num_cols = 16;
                    $val = array(
                        "No.", "Nomor Peserta", "Nama", "Program", "Tipe Ujian", "Pilihan 1", "Kode Jurusan 1", "Pilihan 2", "Kode Jurusan 2", "Pilihan 3", "Kode Jurusan 3", "No. HP", "Email", "Total Nilai", "CBT", "Wawancara"
                    );
                }
                for ($a = 0; $a < $num_cols; $a++) {
                    $sheet->setCellValue($cols[$a] . '1', $val[$a]);
                }
                $baris  = 2;
                $no = 1;
                foreach ($viewpKelulusan->result() as $value) {
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
                            'id_user' => $viewpBiodata->created_by,
                        ),
                        'or_where'  => null,
                        'like'      => null,
                        'or_like'   => null,
                        'order'     => null,
                        'limit'     => null,
                        'group_by'  => null,
                    );
                    $tblUsers = $this->Tbl_users->search($rules)->row();
                    $rules = array(
                        'database'  => null, //Default database master
                        'select'    => null,
                        'where'     => array(
                            'idp_formulir' => $value->idp_formulir,
                            'pilihan' => 1
                        ),
                        'or_where'  => null,
                        'like'      => null,
                        'or_like'   => null,
                        'order'     => null,
                        'limit'     => null,
                        'group_by'  => null,
                    );
                    $viewpPilihan1 = $this->Viewp_pilihan->search($rules)->row();
                    $rules = array(
                        'database'  => null, //Default database master
                        'select'    => null,
                        'where'     => array(
                            'idp_formulir' => $value->idp_formulir,
                            'pilihan' => 2
                        ),
                        'or_where'  => null,
                        'like'      => null,
                        'or_like'   => null,
                        'order'     => null,
                        'limit'     => null,
                        'group_by'  => null,
                    );
                    $viewpPilihan2 = $this->Viewp_pilihan->search($rules)->row();
                    $rules = array(
                        'database'  => null, //Default database master
                        'select'    => null,
                        'where'     => array(
                            'idp_formulir' => $value->idp_formulir,
                            'pilihan' => 3
                        ),
                        'or_where'  => null,
                        'like'      => null,
                        'or_like'   => null,
                        'order'     => null,
                        'limit'     => null,
                        'group_by'  => null,
                    );
                    $viewpPilihan3 = $this->Viewp_pilihan->search($rules);
                    $pilihan3 = null;
                    if($viewpPilihan3->num_rows() > 0){
                        $pilihan3 = $viewpPilihan3->row();
                    }

                    $sheet->setCellValue("A" . $baris, $no);
                    $sheet->setCellValueExplicit("B" . $baris, $value->nomor_peserta, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                    $sheet->setCellValue("C" . $baris, $value->nama);
                    $sheet->setCellValue("D" . $baris, $value->jenjang);
                    $sheet->setCellValue("E" . $baris, $value->tipe_ujian);
                    $sheet->setCellValue("F" . $baris, $viewpPilihan1->jurusan);
                    $sheet->setCellValue("G" . $baris, $viewpPilihan1->kode_jurusan);
                    $sheet->setCellValue("H" . $baris, $viewpPilihan2->jurusan);
                    $sheet->setCellValue("I" . $baris, $viewpPilihan2->kode_jurusan);
                    $sheet->setCellValue("J" . $baris, ($pilihan3 != null) ? $pilihan3->jurusan : '');
                    $sheet->setCellValue("K" . $baris, ($pilihan3 != null) ? $pilihan3->kode_jurusan : '');
                    $sheet->setCellValueExplicit("L" . $baris, $tblUsers->nmr_tlpn, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                    $sheet->setCellValue("M" . $baris, $tblUsers->email);
                    $sheet->setCellValue("N" . $baris, $value->total);

                    if($value->jenjang != 'S1'){
                        $cbt = $studi_naskah = $proposal = $moderasi_beragama = $wawancara = 0;
                        $rules = array(
                            'database'  => null, //Default database master
                            'select'    => null,
                            'where'     => array(
                                'idp_formulir' => $value->idp_formulir,
                                'keterangan' => 'CBT'
                            ),
                            'or_where'  => null,
                            'like'      => null,
                            'or_like'   => null,
                            'order'     => null,
                            'limit'     => null,
                            'group_by'  => null,
                        );
                        $tbpNilaiCBT = $this->Tbp_nilai->search($rules);
                        if($tbpNilaiCBT->num_rows() > 0){
                            $tbpNilaiCBT = $tbpNilaiCBT->row();
                            $cbt = $tbpNilaiCBT->nilai;
                        }
                        $rules = array(
                            'database'  => null, //Default database master
                            'select'    => null,
                            'where'     => array(
                                'idp_formulir' => $value->idp_formulir,
                                'keterangan' => 'STUDI NASKAH'
                            ),
                            'or_where'  => null,
                            'like'      => null,
                            'or_like'   => null,
                            'order'     => null,
                            'limit'     => null,
                            'group_by'  => null,
                        );
                        $tbpNilaiStudiNaskah = $this->Tbp_nilai->search($rules);
                        if($tbpNilaiStudiNaskah->num_rows() > 0){
                            $tbpNilaiStudiNaskah = $tbpNilaiStudiNaskah->row();
                            $studi_naskah = $tbpNilaiStudiNaskah->nilai;
                        }
                        $rules = array(
                            'database'  => null, //Default database master
                            'select'    => null,
                            'where'     => array(
                                'idp_formulir' => $value->idp_formulir,
                                'keterangan' => 'PROPOSAL'
                            ),
                            'or_where'  => null,
                            'like'      => null,
                            'or_like'   => null,
                            'order'     => null,
                            'limit'     => null,
                            'group_by'  => null,
                        );
                        $tbpNilaiProposal = $this->Tbp_nilai->search($rules);
                        if($tbpNilaiProposal->num_rows() > 0){
                            $tbpNilaiProposal = $tbpNilaiProposal->row();
                            $proposal = $tbpNilaiProposal->nilai;
                        }
                        $rules = array(
                            'database'  => null, //Default database master
                            'select'    => null,
                            'where'     => array(
                                'idp_formulir' => $value->idp_formulir,
                                'keterangan' => 'MODERASI BERAGAMA'
                            ),
                            'or_where'  => null,
                            'like'      => null,
                            'or_like'   => null,
                            'order'     => null,
                            'limit'     => null,
                            'group_by'  => null,
                        );
                        $tbpNilaiModerasi = $this->Tbp_nilai->search($rules);
                        if($tbpNilaiModerasi->num_rows() > 0){
                            $tbpNilaiModerasi = $tbpNilaiModerasi->row();
                            $moderasi_beragama = $tbpNilaiModerasi->nilai;
                        }
                        $wawancara = $studi_naskah + $proposal + $moderasi_beragama;

                        $sheet->setCellValue("O" . $baris, $cbt);
                        $sheet->setCellValue("P" . $baris, ($wawancara*4));
                    }

                    $baris++;
                    $no++;
                }
                $writer = new Xlsx($spreadsheet);
                $filename = urlencode("Nilai_" . date("Y_m_d_H_i_s") . ".xlsx");
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment;filename="' . $filename . '"');
                header('Cache-Control: max-age=0');
                $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
                $writer->save('php://output');
            } else {
                $this->session->set_flashdata('message', 'Data kosong.');
                $this->session->set_flashdata('type_message', 'danger');
                redirect('kelulusan/export/nilai');
            }
        } else {
            $this->session->set_flashdata('message', 'Hak akses ditolak.');
            $this->session->set_flashdata('type_message', 'danger');
            redirect('dashboard/');
        }
    }
}
