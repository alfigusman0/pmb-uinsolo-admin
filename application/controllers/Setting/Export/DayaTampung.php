<?php
defined('BASEPATH') or exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

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
        $this->load->model('Settings/Views_daya_tampung');
    }

    function index()
    {
        $hak_akses = $this->master->read('hak-akses/?ids_level=' . $this->jwt->ids_level . '&ids_modul=69&aksi_hak_akses=export');
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
                'title'         => 'Export Daya Tampung | ' . $_ENV['APPLICATION_NAME'],
                'content'       => 'Export/Setting/daya_tampung/content',
                'css'           => 'Export/Setting/daya_tampung/css',
                'javascript'    => 'Export/Setting/daya_tampung/javascript',
                'modal'         => 'Export/Setting/daya_tampung/modal',

                'tbsJurusan' => $this->master->read('jurusan/?status=YA&limit=1000'),
                'tahun'         => $this->Views_daya_tampung->distinct($rules)->result(),
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
        $hak_akses = $this->master->read('hak-akses/?ids_level=' . $this->jwt->ids_level . '&ids_modul=69&aksi_hak_akses=export');
        if ($hak_akses->code == 200) {
            $where = array();
            $tahun = $this->input->post('tahun');
            // if($this->input->post('ids_fakultas') != 'SEMUA'){
            //     $where['ids_fakultas'] = $this->input->post('ids_fakultas');
            // }
            if ($this->input->post('kode_jurusan') != 'SEMUA') {
                $where['kode_jurusan'] = $this->input->post('kode_jurusan');
            }
            if ($this->input->post('status') != 'SEMUA') {
                $where['status'] = $this->input->post('status');
            }
            $where["YEAR(date_created)"] = $tahun;
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
            $viewsDayaTampung = $this->Views_daya_tampung->search($rules);
            if ($viewsDayaTampung->num_rows() > 0) {
                $spreadsheet = new Spreadsheet();
                $sheet = $spreadsheet->getActiveSheet();
                $spreadsheet->getActiveSheet()->getPageSetup()->setFitToWidth(1);
                $spreadsheet->getActiveSheet()->getPageSetup()->setFitToHeight(1);
                //table header
                $cols = array("A", "B", "C", "D", "E", "F", "G", "H", "I");
                $val = array(
                    "No.", "Jenjang", "Fakultas", "Kode Jurusan", "Jurusan", "Grade", "Daya Tampung", "Kuota", "Status"
                );
                for ($a = 0; $a < 9; $a++) {
                    $sheet->setCellValue($cols[$a] . '1', $val[$a]);
                }
                $baris  = 2;
                $no = 1;
                foreach ($viewsDayaTampung->result() as $value) {
                    $sheet->setCellValue("A" . $baris, $no);
                    $sheet->setCellValue("B" . $baris, $value->jenjang);
                    $sheet->setCellValue("C" . $baris, $value->fakultas);
                    $sheet->setCellValue("D" . $baris, $value->kode_jurusan);
                    $sheet->setCellValue("E" . $baris, $value->jurusan);
                    $sheet->setCellValue("F" . $baris, $value->grade);
                    $sheet->setCellValue("G" . $baris, $value->daya_tampung);
                    $sheet->setCellValue("H" . $baris, $value->kuota);
                    $sheet->setCellValue("I" . $baris, $value->status);

                    $baris++;
                    $no++;
                }
                $writer = new Xlsx($spreadsheet);
                $filename = urlencode("DayaTampung_" . date("Y_m_d_H_i_s") . ".xlsx");
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment;filename="' . $filename . '"');
                header('Cache-Control: max-age=0');
                $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
                $writer->save('php://output');
            } else {
                $this->session->set_flashdata('message', 'Data kosong.');
                $this->session->set_flashdata('type_message', 'danger');
                redirect('setting/export/daya-tampung');
            }
        } else {
            $this->session->set_flashdata('message', 'Hak akses ditolak.');
            $this->session->set_flashdata('type_message', 'danger');
            redirect('dashboard/');
        }
    }
}
