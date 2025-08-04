<?php
defined('BASEPATH') or exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Jurusan extends CI_Controller
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
    }

    function index()
    {
        $hak_akses = $this->master->read('hak-akses/?ids_level=' . $this->jwt->ids_level . '&ids_modul=69&aksi_hak_akses=export');
        if ($hak_akses->code == 200) {
            $data = array(
                'title'         => 'Export Jurusan | ' . $_ENV['APPLICATION_NAME'],
                'content'       => 'Export/Setting/jurusan/content',
                'css'           => 'Export/Setting/jurusan/css',
                'javascript'    => 'Export/Setting/jurusan/javascript',
                'modal'         => 'Export/Setting/jurusan/modal',

                'tbsFakultas' => $this->master->read('fakultas/?status=YA&limit=1000'),
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
            if ($this->input->post('ids_fakultas') != 'SEMUA') {
                $where['ids_fakultas'] = $this->input->post('ids_fakultas');
            }
            if ($this->input->post('kode_jurusan') != 'SEMUA') {
                $where['kode_jurusan'] = $this->input->post('kode_jurusan');
            }
            if ($this->input->post('status') != 'SEMUA') {
                $where['status'] = $this->input->post('status');
            }
            $token = $this->input->cookie($_ENV['COOKIE_NAME'], TRUE);
            if (empty($token)) {
                $token = $_ENV['MASTER_TOKEN'];
            }
            $parrams = array(
                'url' => $_ENV['MASTER_HOST'] . 'jurusan?limit=1000&' . http_build_query($where),
                'method' => 'GET',
                "header" => array(
                    "Authorization: Bearer $token"
                ),
                "request" => null,
            );
            $res = $this->utilities->curl($parrams);
            if ($res->code == 200) {
                if (count($res->data->data) > 0) {
                    $spreadsheet = new Spreadsheet();
                    $sheet = $spreadsheet->getActiveSheet();
                    $spreadsheet->getActiveSheet()->getPageSetup()->setFitToWidth(1);
                    $spreadsheet->getActiveSheet()->getPageSetup()->setFitToHeight(1);
                    //table header
                    $cols = array("A", "B", "C", "D", "E", "F", "G");
                    $val = array(
                        "No.", "Fakultas", "Kode Jurusan", "Jurusan", "Akreditasi", "Kategori", "Status"
                    );
                    for ($a = 0; $a < 7; $a++) {
                        $sheet->setCellValue($cols[$a] . '1', $val[$a]);
                    }
                    $baris  = 2;
                    $no = 1;
                    foreach ($res->data->data as $value) {
                        $sheet->setCellValue("A" . $baris, $no);
                        $sheet->setCellValue("B" . $baris, $value->fakultas);
                        $sheet->setCellValue("C" . $baris, $value->kode_jurusan);
                        $sheet->setCellValue("D" . $baris, $value->jurusan);
                        $sheet->setCellValue("E" . $baris, $value->akreditasi);
                        $sheet->setCellValue("F" . $baris, $value->kategori);
                        $sheet->setCellValue("G" . $baris, $value->status);

                        $baris++;
                        $no++;
                    }
                    $writer = new Xlsx($spreadsheet);
                    $filename = urlencode("Jurusan_" . date("Y_m_d_H_i_s") . ".xlsx");
                    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                    header('Content-Disposition: attachment;filename="' . $filename . '"');
                    header('Cache-Control: max-age=0');
                    $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
                    $writer->save('php://output');
                } else {
                    $this->session->set_flashdata('message', 'Data kosong.');
                    $this->session->set_flashdata('type_message', 'danger');
                    redirect('setting/export/jurusan');
                }
            } else {
                $this->session->set_flashdata('message', $res->message);
                $this->session->set_flashdata('type_message', 'danger');
                redirect('setting/export/jurusan');
            }
        } else {
            $this->session->set_flashdata('message', 'Hak akses ditolak.');
            $this->session->set_flashdata('type_message', 'danger');
            redirect('dashboard/');
        }
    }
}
