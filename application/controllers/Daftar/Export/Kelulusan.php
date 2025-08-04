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

        $this->load->model('Tbl_users');
        $this->load->model('Daftar/Tbd_kelulusan');
        $this->load->model('Daftar/Viewd_kelulusan');
        $this->load->model('Daftar/Viewd_pembayaran');
    }

    function index()
    {
        $hak_akses = $this->master->read('hak-akses/?ids_level=' . $this->jwt->ids_level . '&ids_modul=54&aksi_hak_akses=export');
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
                'title'         => 'Export Kelulusan | ' . $_ENV['APPLICATION_NAME'],
                'content'       => 'Export/Daftar/kelulusan/content',
                'css'           => 'Export/Daftar/kelulusan/css',
                'javascript'    => 'Export/Daftar/kelulusan/javascript',
                'modal'         => 'Export/Daftar/kelulusan/modal',
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
        $hak_akses = $this->master->read('hak-akses/?ids_level=' . $this->jwt->ids_level . '&ids_modul=54&aksi_hak_akses=export');
        if ($hak_akses->code == 200) {
            $tahun = $this->input->post('tahun');
            $daftar = $this->input->post('daftar');
            $submit = $this->input->post('submit');
            $pembayaran = $this->input->post('pembayaran');
            $pemberkasan = $this->input->post('pemberkasan');
            $where = array();
            if ($tahun != 'SEMUA') {
                $where['tahun'] = $tahun;
            }
            if ($daftar != 'SEMUA') {
                $where['daftar'] = $daftar;
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
                $cols = array("A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S");
                $val = array("No", "Nomor Peserta", "NIM", "Nama", "Fakultas", "Jurusan", "Kelas", "Jalur Masuk", "Tahun", "Daftar", "Tanggal Daftar", "Submit", "Tanggal Submit", "Pembayaran", "Tanggal Pembayaran", "VA", "Bank", "Pemberkasan", "Tanggal Pemberkasan");
                for ($a = 0; $a < 19; $a++) {
                    $sheet->setCellValue($cols[$a] . '1', $val[$a]);
                }
                $baris  = 2;
                $no = 1;
                foreach ($viewdKelulusan->result() as $value) {
                    $rules = array(
                        'database'  => null, //Default database master
                        'select'    => null,
                        'where'     => array(
                            'idd_kelulusan' => $value->idd_kelulusan,
                            'pembayaran' => 'SUDAH'
                        ),
                        'or_where'  => null,
                        'like'      => null,
                        'or_like'   => null,
                        'order'     => null,
                        'limit'     => null,
                        'group_by'  => null,
                    );
                    $tbdPembayaran = $this->Viewd_pembayaran->search($rules);
                    if ($tbdPembayaran->num_rows() > 0) {
                        $tbdPembayaran = $tbdPembayaran->row();
                        $va = $tbdPembayaran->va;
                        $alias_bank = $tbdPembayaran->alias_bank;
                    } else {
                        $va = '';
                        $alias_bank = '';
                    }
                    $sheet->setCellValue("A" . $baris, $no);
                    $sheet->setCellValueExplicit("B" . $baris, $value->nomor_peserta, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                    $sheet->setCellValueExplicit("C" . $baris, $value->nim, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                    $sheet->setCellValue("D" . $baris, $value->nama);
                    $sheet->setCellValue("E" . $baris, $value->fakultas);
                    $sheet->setCellValue("F" . $baris, $value->jurusan);
                    $sheet->setCellValue("G" . $baris, $value->kelas);
                    $sheet->setCellValue("H" . $baris, $value->alias_jalur_masuk);
                    $sheet->setCellValue("I" . $baris, $value->tahun);
                    $sheet->setCellValue("J" . $baris, $value->daftar);
                    $sheet->setCellValue("K" . $baris, $value->tgl_daftar);
                    $sheet->setCellValue("L" . $baris, $value->submit);
                    $sheet->setCellValue("M" . $baris, $value->tgl_submit);
                    $sheet->setCellValue("N" . $baris, $value->pembayaran);
                    $sheet->setCellValue("O" . $baris, $value->tgl_pembayaran);
                    $sheet->setCellValue("P" . $baris, $va);
                    $sheet->setCellValue("Q" . $baris, $alias_bank);
                    $sheet->setCellValue("R" . $baris, $value->pemberkasan);
                    $sheet->setCellValue("S" . $baris, $value->tgl_pemberkasan);

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
                redirect('daftar/export/kelulusan');
            }
        } else {
            $this->session->set_flashdata('message', 'Hak akses ditolak.');
            $this->session->set_flashdata('type_message', 'danger');
            redirect('dashboard/');
        }
    }
}
