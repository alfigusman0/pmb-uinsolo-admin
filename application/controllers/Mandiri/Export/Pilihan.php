<?php
defined('BASEPATH') or exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Pilihan extends CI_Controller
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
        $this->load->model('Mandiri/Viewp_pilihan');
        $this->load->model('Mandiri/Viewp_biodata');
        $this->load->model('Mandiri/Viewp_sekolah');
        $this->load->model('Mandiri/Viewp_rumah');
        $this->load->model('Tbl_users');
    }

    function index()
    {
        $hak_akses = $this->master->read('hak-akses/?ids_level=' . $this->jwt->ids_level . '&ids_modul=144&aksi_hak_akses=export');
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
                'title'         => 'Export Pilihan | ' . $_ENV['APPLICATION_NAME'],
                'content'       => 'Export/Mandiri/pilihan/content',
                'css'           => 'Export/Mandiri/pilihan/css',
                'javascript'    => 'Export/Mandiri/pilihan/javascript',
                'modal'         => 'Export/Mandiri/pilihan/modal',

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
        $hak_akses = $this->master->read('hak-akses/?ids_level=' . $this->jwt->ids_level . '&ids_modul=144&aksi_hak_akses=export');
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
            $where['formulir'] = 'SUDAH';

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
                $cols = array("A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "M", "O", "P", "Q", "R", "S", "T", "U");
                $val = array(
                    "No.", "Nomor Peserta", "Nama", "Program", "Tipe Ujian", "Kode Jurusan 1","Pilihan 1", "Kode Jurusan 2", "Pilihan 2", "Kode Jurusan 3", "Pilihan 3", "Jenis Kelamin", "Asal Sekolah", "Alamat Rumah", "Provinsi", "Kabupaten", "Kecamatan", "Kelurahan", "Kode POS", "No. HP", "Email"
                );
                for ($a = 0; $a < 21; $a++) {
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
                    $viewpRumah = $this->Viewp_rumah->search($rules)->row();
                    $viewpSekolah = $this->Viewp_sekolah->search($rules)->row();
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
                    $sheet->setCellValue("D" . $baris, $value->program);
                    $sheet->setCellValue("E" . $baris, $value->tipe_ujian);
                    $sheet->setCellValue("F" . $baris, $viewpPilihan1->kode_jurusan);
                    $sheet->setCellValue("G" . $baris, $viewpPilihan1->jurusan);
                    $sheet->setCellValue("H" . $baris, $viewpPilihan2->kode_jurusan);
                    $sheet->setCellValue("I" . $baris, $viewpPilihan2->jurusan);
                    $sheet->setCellValue("J" . $baris, ($pilihan3 != null) ? $pilihan3->kode_jurusan : '');
                    $sheet->setCellValue("K" . $baris, ($pilihan3 != null) ? $pilihan3->jurusan : '');
                    $sheet->setCellValue("L" . $baris, $viewpBiodata->jenis_kelamin);
                    $sheet->setCellValue("M" . $baris, ($viewpSekolah == null) ? '' : $viewpSekolah->nama_sekolah);
                    $sheet->setCellValue("N" . $baris, $viewpRumah->jalan);
                    $sheet->setCellValue("O" . $baris, $viewpRumah->provinsi);
                    $sheet->setCellValue("P" . $baris, $viewpRumah->kab_kota);
                    $sheet->setCellValue("Q" . $baris, $viewpRumah->kecamatan);
                    $sheet->setCellValue("R" . $baris, $viewpRumah->kelurahan);
                    $sheet->setCellValue("S" . $baris, $viewpRumah->kode_pos);
                    $sheet->setCellValueExplicit("T" . $baris, $tblUsers->nmr_tlpn, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                    $sheet->setCellValue("U" . $baris, $tblUsers->email);

                    $baris++;
                    $no++;
                }
                $writer = new Xlsx($spreadsheet);
                $filename = urlencode("Pilihan_" . date("Y_m_d_H_i_s") . ".xlsx");
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment;filename="' . $filename . '"');
                header('Cache-Control: max-age=0');
                $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
                $writer->save('php://output');
            } else {
                $this->session->set_flashdata('message', 'Data kosong.');
                $this->session->set_flashdata('type_message', 'danger');
                redirect('mandiri/export/pilihan');
            }
        } else {
            $this->session->set_flashdata('message', 'Hak akses ditolak.');
            $this->session->set_flashdata('type_message', 'danger');
            redirect('dashboard/');
        }
    }
}
