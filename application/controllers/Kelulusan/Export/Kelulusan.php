<?php
defined('BASEPATH') or exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

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
        $this->load->model('Mandiri/Tbp_formulir');
        $this->load->model('Tbl_users');
        $this->load->model('Mandiri/Viewp_kelulusan');
        $this->load->model('Mandiri/Viewp_formulir');
    }

    function index()
    {
        $hak_akses = $this->master->read('hak-akses/?ids_level=' . $this->jwt->ids_level . '&ids_modul=91&aksi_hak_akses=export');
        if ($hak_akses->code == 200) {
            $rules1 = array(
                'database'  => null, //Default database master
                'select'    => 'YEAR(date_created) as tahun',
                'where'     => null,
                'or_where'  => null,
                'like'      => null,
                'or_like'   => null,
                'order'     => null,
                'group_by'  => null,
            );
            $rules2 = array(
                'database'  => null, //Default database master
                'select'    => 'nama_penitip',
                'where'     => null,
                'or_where'  => null,
                'like'      => null,
                'or_like'   => null,
                'order'     => null,
                'group_by'  => null,
            );
            $rules3 = array(
                'database'  => null, //Default database master
                'select'    => 'keterangan',
                'where'     => null,
                'or_where'  => null,
                'like'      => null,
                'or_like'   => null,
                'order'     => null,
                'group_by'  => null,
            );
            $keterangan = json_decode($this->jwt->keterangan);
            $data = array(
                'title'         => 'Export Kelulusan | ' . $_ENV['APPLICATION_NAME'],
                'content'       => 'Export/Kelulusan/kelulusan/content',
                'css'           => 'Export/Kelulusan/kelulusan/css',
                'javascript'    => 'Export/Kelulusan/kelulusan/javascript',
                'modal'         => 'Export/Kelulusan/kelulusan/modal',
                'tahun'       => $this->Viewp_kelulusan->distinct($rules1)->result(),
                'nama_penitip'       => $this->Viewp_kelulusan->distinct($rules2)->result(),
                'keterangan'       => $this->Viewp_kelulusan->distinct($rules3)->result(),
                'ket_user' => $keterangan,
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
        $hak_akses = $this->master->read('hak-akses/?ids_level=' . $this->jwt->ids_level . '&ids_modul=91&aksi_hak_akses=export');
        if ($hak_akses->code == 200) {
            $tahun = $this->input->post('tahun');
            $nama_penitip = $this->input->post('nama_penitip');
            $keterangan = $this->input->post('keterangan');
            $where = $data = array();
            if ($this->jwt->ids_level <= 2) {
                if ($tahun != 'Semua') {
                    $where['YEAR(date_created)'] = $tahun;
                }
                if ($nama_penitip != 'Semua') {
                    $where['nama_penitip'] = $nama_penitip;
                }
                if ($keterangan != 'Semua') {
                    $where['keterangan'] = $keterangan;
                }
            } else {
                if ($tahun != 'Semua') {
                    $where['YEAR(date_created)'] = $tahun;
                } else {
                    $where = null;
                }
            }
            if ($this->input->post('jenjang') != '') {
                $where['jenjang'] = $this->input->post('jenjang');
            }
            if ($this->input->post('ids_fakultas') != '') {
                $where['ids_fakultas'] = $this->input->post('ids_fakultas');
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
                'order'     => null,
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
                if ($this->jwt->ids_level <= 2) {
                    $cols = array("A", "B", "C", "D", "E", "F", "G", "H", "I", "J");
                    $val = array("No", "Nomor Peserta", "Nama", "Tipe Ujian", "Status Kelulusan", "Total Nilai", "Kode Jurusan", "Jurusan", "Nama Penitip", "Keterangan");
                    for ($a = 0; $a < 10; $a++) {
                        $sheet->setCellValue($cols[$a] . '1', $val[$a]);
                    }
                } else {
                    $cols = array("A", "B", "C", "D", "E", "F", "G");
                    $val = array("No", "Nomor Peserta", "Nama", "Tipe Ujian", "Status Kelulusan", "Kode Jurusan", "Jurusan");
                    for ($a = 0; $a < 7; $a++) {
                        $sheet->setCellValue($cols[$a] . '1', $val[$a]);
                    }
                }
                $baris  = 2;
                $no = 1;
                foreach ($viewpKelulusan->result() as $value) {
                    //pemanggilan sesuaikan dengan nama kolom tabel
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
                    $tbpFormulir = $this->Viewp_formulir->search($rules)->row();
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
                    $tblUsers = $this->Tbl_users->search($rules)->row();

                    if ($this->jwt->ids_level <= 2) {
                        $sheet->setCellValue("A" . $baris, $no);
                        $sheet->setCellValueExplicit("B" . $baris, $value->nomor_peserta, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                        $sheet->setCellValue("C" . $baris, $tbpFormulir->nama);
                        $sheet->setCellValue("D" . $baris, $value->tipe_ujian);
                        $sheet->setCellValue("E" . $baris, ($value->lulus == 'YA') ? 'Lulus' : 'Tidak Lulus');
                        $sheet->setCellValue("F" . $baris, $value->total);
                        $sheet->setCellValue("G" . $baris, $value->kode_jurusan);
                        $sheet->setCellValue("H" . $baris, $value->jurusan);
                        $sheet->setCellValue("I" . $baris, $value->nama_penitip);
                        $sheet->setCellValue("J" . $baris, $value->keterangan);
                    } else {
                        $sheet->setCellValue("A" . $baris, $no);
                        $sheet->setCellValueExplicit("B" . $baris, $value->nomor_peserta, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                        $sheet->setCellValue("C" . $baris, $tbpFormulir->nama);
                        $sheet->setCellValue("D" . $baris, $value->tipe_ujian);
                        $sheet->setCellValue("E" . $baris, ($value->lulus == 'YA') ? 'Lulus' : 'Tidak Lulus');
                        $sheet->setCellValue("F" . $baris, $value->kode_jurusan);
                        $sheet->setCellValue("G" . $baris, $value->jurusan);
                    }

                    //Set number value
                    //$objPHPExcel->getActiveSheet()->getStyle('C1:C'.$baris)->getNumberFormat()->setFormatCode('0');

                    $baris++;
                    $no++;
                }
                $writer = new Xlsx($spreadsheet);
                $filename = urlencode("Kelulusan_" . date("Y_m_d_H_i_s") . ".xlsx");
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment;filename="' . $filename . '"');
                header('Cache-Control: max-age=0');
                $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
                $writer->save('php://output');
            } else {
                $this->session->set_flashdata('message', 'Data kosong.');
                $this->session->set_flashdata('type_message', 'danger');
                redirect('kelulusan/export/kelulusan');
            }
        } else {
            $this->session->set_flashdata('message', 'Hak akses ditolak.');
            $this->session->set_flashdata('type_message', 'danger');
            redirect('dashboard/');
        }
    }
}
