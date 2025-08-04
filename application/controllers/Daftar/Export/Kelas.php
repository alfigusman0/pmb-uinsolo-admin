<?php
defined('BASEPATH') or exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Mpdf\Mpdf;

class Kelas extends CI_Controller
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
        $hak_akses = $this->master->read('hak-akses/?ids_level=' . $this->jwt->ids_level . '&ids_modul=140&aksi_hak_akses=export');
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
            $fakultas = $this->master->read('fakultas/?status=YA&limit=1000');
            $data = array(
                'title'         => 'Export Absen | ' . $_ENV['APPLICATION_NAME'],
                'content'       => 'Export/Daftar/kelas/content',
                'css'           => 'Export/Daftar/kelas/css',
                'javascript'    => 'Export/Daftar/kelas/javascript',
                'modal'         => 'Export/Daftar/kelas/modal',
                'tahun'       => $this->Viewd_kelulusan->distinct($rules)->result(),
                'fakultas' => $fakultas
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
        $hak_akses = $this->master->read('hak-akses/?ids_level=' . $this->jwt->ids_level . '&ids_modul=140&aksi_hak_akses=export');
        if ($hak_akses->code == 200) {
            $tahun = $this->input->post('tahun');
            $ids_fakultas = $this->input->post('ids_fakultas');
            $kode_jurusan = $this->input->post('kode_jurusan');
            $jenjang = $this->input->post('jenjang');
            $where = $data = array();
            $where['YEAR(date_created)'] = $tahun;
            $where['fakultas'] = $ids_fakultas;
            $where['jurusan'] = $kode_jurusan;
            $where['pembayaran'] = 'SUDAH';
            $rules = array(
                'database'  => null, //Default database master
                'select'    => null,
                'where'     => $where,
                'or_where'  => null,
                'like'      => null,
                'or_like'   => null,
                'order'     => 'kelas, nim ASC',
                'limit'     => null,
                'group_by'  => null,
            );
            $viewdKelulusan = $this->Viewd_kelulusan->search($rules);
            if ($this->input->post('jenis') == 'PDF') {
                $data = array(
                    'viewdKelulusan' => $viewdKelulusan->result(),
                    'fakultas' => $ids_fakultas,
                    'jurusan' => $kode_jurusan,
                    'jenjang' => $jenjang
                );

                $html = $this->load->view('Export/Daftar/pdf/kelas', $data, true);
                $pdfFilePath = "ABSEN_" . $ids_fakultas . '_' . $kode_jurusan . '_' . date('YmdHis') . ".pdf";
                $mpdf = new Mpdf();
                $mpdf->showImageErrors = true;
                $mpdf->WriteHTML($html);
                $mpdf->Output($pdfFilePath, "I");
            } else {
                if ($viewdKelulusan->num_rows() > 0) {
                    $spreadsheet = new Spreadsheet();
                    $sheet = $spreadsheet->getActiveSheet();
                    $spreadsheet->getActiveSheet()->getPageSetup()->setFitToWidth(1);
                    $spreadsheet->getActiveSheet()->getPageSetup()->setFitToHeight(1);
                    //table header
                    $cols = array("A", "B", "C", "D", "E", "F", "G");
                    $val = array("No", "NIM", "Nama", "Kelas", "Fakultas", "Jurusan", "Konsentrasi");
                    if ($jenjang == "S3") {
                        for ($a = 0; $a < 7; $a++) {
                            $sheet->setCellValue($cols[$a] . '1', $val[$a]);
                        }
                    } else {
                        for ($a = 0; $a < 6; $a++) {
                            $sheet->setCellValue($cols[$a] . '1', $val[$a]);
                        }
                    }
                    $baris  = 2;
                    $no = 1;
                    foreach ($viewdKelulusan->result() as $value) {
                        $sheet->setCellValue("A" . $baris, $no);
                        $sheet->setCellValueExplicit("B" . $baris, $value->nim, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                        $sheet->setCellValue("C" . $baris, $value->nama);
                        $sheet->setCellValue("D" . $baris, $value->kelas);
                        $sheet->setCellValue("E" . $baris, $value->fakultas);
                        $sheet->setCellValue("F" . $baris, $value->jurusan);
                        if ($jenjang == "S3") {
                            $sheet->setCellValue("G" . $baris, $value->konsentrasi);
                        }

                        //Set number value
                        //$objPHPExcel->getActiveSheet()->getStyle('C1:C'.$baris)->getNumberFormat()->setFormatCode('0');

                        $baris++;
                        $no++;
                    }
                    $writer = new Xlsx($spreadsheet);
                    $filename = urlencode("ABSEN_" . $ids_fakultas . '_' . $kode_jurusan . '_' . date('YmdHis') . ".xlsx");
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
            }
        } else {
            $this->session->set_flashdata('message', 'Hak akses ditolak.');
            $this->session->set_flashdata('type_message', 'danger');
            redirect('dashboard/');
        }
    }
}
