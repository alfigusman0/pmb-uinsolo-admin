<?php
defined('BASEPATH') or exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Jadwal extends CI_Controller
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

        $this->load->model('Mandiri/Viewp_jadwal');
        $this->load->model('Settings/Views_jadwal');
        $this->load->model('Tbl_users');
    }

    function index()
    {
        $hak_akses = $this->master->read('hak-akses/?ids_level=' . $this->jwt->ids_level . '&ids_modul=143&aksi_hak_akses=export');
        if ($hak_akses->code == 200) {
            $rules = array(
                'database'  => null, //Default database master
                'select'    => 'YEAR(date_created) as tahun', // not null
                'where'     => array(
                    'status' => 'YA'
                ),
                'or_where'  => null,
                'like'      => null,
                'or_like'   => null,
                'order'     => 'tahun DESC',
                'group_by'  => null,
            );
            $data = array(
                'title'         => 'Export Jadwal | ' . $_ENV['APPLICATION_NAME'],
                'content'       => 'Export/Mandiri/jadwal/content',
                'css'           => 'Export/Mandiri/jadwal/css',
                'javascript'    => 'Export/Mandiri/jadwal/javascript',
                'modal'         => 'Export/Mandiri/jadwal/modal',

                'tahun' => $this->Views_jadwal->distinct($rules)->result(),
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
        $hak_akses = $this->master->read('hak-akses/?ids_level=' . $this->jwt->ids_level . '&ids_modul=143&aksi_hak_akses=export');
        if ($hak_akses->code == 200) {
            $where = array();
            if ($this->input->post('tanggal_ujian') != '') {
                $where['tanggal'] = $this->input->post('tanggal_ujian');
            }
            if ($this->input->post('ids_program') != '') {
                $where['ids_program'] = $this->input->post('ids_program');
            }
            if ($this->input->post('ids_tipe_ujian') != '') {
                $where['ids_tipe_ujian'] = $this->input->post('ids_tipe_ujian');
            }
            if ($this->input->post('tahun') != '') {
                $where['YEAR(date_created)'] = $this->input->post('tahun');
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
            $viewpJadwal = $this->Viewp_jadwal->search($rules);
            if ($viewpJadwal->num_rows() > 0) {
                $spreadsheet = new Spreadsheet();
                $sheet = $spreadsheet->getActiveSheet();
                $spreadsheet->getActiveSheet()->getPageSetup()->setFitToWidth(1);
                $spreadsheet->getActiveSheet()->getPageSetup()->setFitToHeight(1);
                //table header
                $cols = array("A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K");
                $val = array(
                    "No.", "Nomor Peserta", "Nama", "Kategori", "Tipe Ujian", "Tanggal", "Jam Awal", "Jam Akhir", "Area", "Gedung", "Ruangan"
                );
                for ($a = 0; $a < 11; $a++) {
                    $sheet->setCellValue($cols[$a] . '1', $val[$a]);
                }
                $baris  = 2;
                $no = 1;
                foreach ($viewpJadwal->result() as $value) {
                    $sheet->setCellValue("A" . $baris, $no);
                    $sheet->setCellValueExplicit("B" . $baris, $value->nomor_peserta, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                    $sheet->setCellValue("C" . $baris, $value->nama);
                    $sheet->setCellValue("D" . $baris, $value->kategori);
                    $sheet->setCellValue("E" . $baris, $value->tipe_ujian);
                    $sheet->setCellValue("F" . $baris, $value->tanggal);
                    $sheet->setCellValue("G" . $baris, $value->jam_awal);
                    $sheet->setCellValue("H" . $baris, $value->jam_akhir);
                    $sheet->setCellValue("I" . $baris, $value->area);
                    $sheet->setCellValue("J" . $baris, $value->gedung);
                    $sheet->setCellValue("K" . $baris, $value->ruangan);

                    $baris++;
                    $no++;
                }
                $writer = new Xlsx($spreadsheet);
                $filename = urlencode("Jadwal_" . date("Y_m_d_H_i_s") . ".xlsx");
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment;filename="' . $filename . '"');
                header('Cache-Control: max-age=0');
                $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
                $writer->save('php://output');
            } else {
                $this->session->set_flashdata('message', 'Data kosong.');
                $this->session->set_flashdata('type_message', 'danger');
                redirect('mandiri/export/jadwal');
            }
        } else {
            $this->session->set_flashdata('message', 'Hak akses ditolak.');
            $this->session->set_flashdata('type_message', 'danger');
            redirect('dashboard/');
        }
    }
}
