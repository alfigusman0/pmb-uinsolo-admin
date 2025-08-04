<?php
defined('BASEPATH') or exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class UkuranBaju extends CI_Controller
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

        $this->load->model('Daftar/Tbd_mahasiswa');
        $this->load->model('Daftar/Viewd_mahasiswa');
        $this->load->model('Daftar/Viewd_kelulusan');
    }

    function index()
    {
        $hak_akses = $this->master->read('hak-akses/?ids_level=' . $this->jwt->ids_level . '&ids_modul=141&aksi_hak_akses=export');
        if ($hak_akses->code == 200) {
            $rules = array(
                'database'  => null, //Default database master
                'select'    => 'tahun',
                'where'     => null,
                'or_where'  => null,
                'like'      => null,
                'or_like'   => null,
                'order'     => null,
                'group_by'  => null,
            );
            $data = array(
                'title'         => 'Export Mahasiswa | ' . $_ENV['APPLICATION_NAME'],
                'content'       => 'Export/Daftar/ukuran_baju/content',
                'css'           => 'Export/Daftar/ukuran_baju/css',
                'javascript'    => 'Export/Daftar/ukuran_baju/javascript',
                'modal'         => 'Export/Daftar/ukuran_baju/modal',
                'tahun'       => $this->Viewd_kelulusan->distinct($rules)->result(),
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
        $hak_akses = $this->master->read('hak-akses/?ids_level=' . $this->jwt->ids_level . '&ids_modul=141&aksi_hak_akses=export');
        if ($hak_akses->code == 200) {
            $tahun = $this->input->post('tahun');
            $daftar = $this->input->post('daftar');
            $submit = $this->input->post('submit');
            $pembayaran = $this->input->post('pembayaran');
            $pemberkasan = $this->input->post('pemberkasan');
            $where = $data = array();
            $where['daftar'] = 'SUDAH';
            if ($tahun != 'SEMUA') {
                $where['YEAR(date_created)'] = $tahun;
            }
            if ($submit != 'SEMUA') {
                $where['submit'] = $submit;
            }
            if ($pembayaran != 'SEMUA') {
                $where['pembayaran'] = $pembayaran;
            }
            if ($pemberkasan != 'SEMUA') {
                $where['pemberkasan'] = $pemberkasan;
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
            $viewdKelulusan = $this->Viewd_kelulusan->search($rules);
            if ($viewdKelulusan->num_rows() > 0) {
                $spreadsheet = new Spreadsheet();
                $sheet = $spreadsheet->getActiveSheet();
                $spreadsheet->getActiveSheet()->getPageSetup()->setFitToWidth(1);
                $spreadsheet->getActiveSheet()->getPageSetup()->setFitToHeight(1);
                //table header
                $cols = array("A", "B", "C", "D", "E", "F", "G", "H");
                $val = array("No", "NIM", "Nama", "Fakultas", "Jurusan", "Jenis Kelamin", "Ukuran Baju", "Ukuran Jas");
                for ($a = 0; $a < 8; $a++) {
                    $sheet->setCellValue($cols[$a] . '1', $val[$a]);
                }
                $baris  = 2;
                $no = 1;
                foreach ($viewdKelulusan->result() as $value) {
                    //pemanggilan sesuaikan dengan nama kolom tabel
                    $rules = array(
                        'database'  => null, //Default database master
                        'select'    => null,
                        'where'     => array(
                            'idd_kelulusan' => $value->idd_kelulusan,
                        ),
                        'or_where'  => null,
                        'like'      => null,
                        'or_like'   => null,
                        'order'     => null,
                        'limit'     => null,
                        'group_by'  => null,
                    );
                    $viewdMahasiswa = $this->Viewd_mahasiswa->search($rules);
                    if ($viewdMahasiswa->num_rows() == 0) {
                        $sheet->setCellValue("A" . $baris, $no);
                        $sheet->setCellValueExplicit("B" . $baris, $value->nim, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                        $sheet->setCellValue("C" . $baris, $value->nama);
                        $sheet->setCellValue("D" . $baris, $value->fakultas);
                        $sheet->setCellValue("E" . $baris, $value->jurusan);
                        $sheet->setCellValue("F" . $baris, '');
                        $sheet->setCellValue("G" . $baris, '');
                        $sheet->setCellValue("H" . $baris, '');
                    } else {
                        $viewdMahasiswa = $viewdMahasiswa->row();
                        $sheet->setCellValue("A" . $baris, $no);
                        $sheet->setCellValueExplicit("B" . $baris, $value->nim, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                        $sheet->setCellValue("C" . $baris, $value->nama);
                        $sheet->setCellValue("D" . $baris, $value->fakultas);
                        $sheet->setCellValue("E" . $baris, $value->jurusan);
                        $sheet->setCellValue("F" . $baris, $viewdMahasiswa->jenis_kelamin);
                        $sheet->setCellValue("G" . $baris, $viewdMahasiswa->ukuran_baju);
                        $sheet->setCellValue("H" . $baris, $viewdMahasiswa->ukuran_jas);
                    }

                    //Set number value
                    //$objPHPExcel->getActiveSheet()->getStyle('C1:C'.$baris)->getNumberFormat()->setFormatCode('0');

                    $baris++;
                    $no++;
                }
                $writer = new Xlsx($spreadsheet);
                $filename = urlencode("UkuranBaju_" . date("Y_m_d_H_i_s") . ".xlsx");
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment;filename="' . $filename . '"');
                header('Cache-Control: max-age=0');
                $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
                $writer->save('php://output');
            } else {
                $this->session->set_flashdata('message', 'Data kosong.');
                $this->session->set_flashdata('type_message', 'danger');
                redirect('daftar/export/ukuran-baju');
            }
        } else {
            $this->session->set_flashdata('message', 'Hak akses ditolak.');
            $this->session->set_flashdata('type_message', 'danger');
            redirect('dashboard/');
        }
    }
}
