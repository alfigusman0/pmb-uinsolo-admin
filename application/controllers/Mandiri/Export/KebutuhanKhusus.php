<?php
defined('BASEPATH') or exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class KebutuhanKhusus extends CI_Controller
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
        $this->load->model('Mandiri/Viewp_kelulusan');
        $this->load->model('Mandiri/Viewp_pilihan');
        $this->load->model('Tbl_users');
    }

    function index()
    {
        $hak_akses = $this->master->read('hak-akses/?ids_level=' . $this->jwt->ids_level . '&ids_modul=145&aksi_hak_akses=export');
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
                'title'         => 'Export Kebutuhan Khusus | ' . $_ENV['APPLICATION_NAME'],
                'content'       => 'Export/Mandiri/kebutuhan_khusus/content',
                'css'           => 'Export/Mandiri/kebutuhan_khusus/css',
                'javascript'    => 'Export/Mandiri/kebutuhan_khusus/javascript',
                'modal'         => 'Export/Mandiri/kebutuhan_khusus/modal',

                'tbsJurusan' => $this->master->read('jurusan/?status=YA&limit=1000'),
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
        $hak_akses = $this->master->read('hak-akses/?ids_level=' . $this->jwt->ids_level . '&ids_modul=145&aksi_hak_akses=export');
        if ($hak_akses->code == 200) {
            $where = array();
            $tahun = $this->input->post('tahun');
            $where["YEAR(date_created)"] = $tahun;
            $where["ids_keb_khusus !="] = 1;
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
            $viewpBiodata = $this->Viewp_biodata->search($rules);
            if ($viewpBiodata->num_rows() > 0) {
                $spreadsheet = new Spreadsheet();
                $sheet = $spreadsheet->getActiveSheet();
                $spreadsheet->getActiveSheet()->getPageSetup()->setFitToWidth(1);
                $spreadsheet->getActiveSheet()->getPageSetup()->setFitToHeight(1);
                //table header
                $cols = array("A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O","P","Q");
                $val = array(
                    "No.", "Nomor Peserta", "Nama", "Program", "Tipe Ujian", "Jenis Kelamin", "Asal Sekolah", "Alamat Rumah", "Provinsi", "Kabupaten", "Kecamatan", "Kelurahan", "Kode POS", "No. HP", "Email", "Kebutuhan Khusus", "Keterangan"
                );
                for ($a = 0; $a < 17; $a++) {
                    $sheet->setCellValue($cols[$a] . '1', $val[$a]);
                }
                $baris  = 2;
                $no = 1;
                foreach ($viewpBiodata->result() as $value) {
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
                    $viewpFormulir = $this->Viewp_formulir->search($rules)->row();
                    if ($viewpFormulir->pembayaran == 'SUDAH') {
                        $viewpRumah = $this->Viewp_rumah->search($rules)->row();
                        $viewpSekolah = $this->Viewp_sekolah->search($rules);
                        $viewpKelulusan = $this->Viewp_kelulusan->search($rules);
                        if ($viewpKelulusan->num_rows() > 0) {
                            $viewpKelulusan = $viewpKelulusan->row();
                            if ($viewpKelulusan->total == 0) {
                                $total = "";
                            } else {
                                $total = "Nilai 0, Tidak ikut ujian";
                            }
                        } else {
                            $total = "Data Kelulusan Belum Ada.";
                        }
                        $viewpSekolah2 = null;
                        if ($viewpSekolah->num_rows() > 0) {
                            $viewpSekolah2 = $viewpSekolah->row();
                        }
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

                        $sheet->setCellValue("A" . $baris, $no);
                        $sheet->setCellValueExplicit("B" . $baris, $viewpFormulir->nomor_peserta, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                        $sheet->setCellValue("C" . $baris, $viewpFormulir->nama);
                        $sheet->setCellValue("D" . $baris, $viewpFormulir->program);
                        $sheet->setCellValue("E" . $baris, $viewpFormulir->tipe_ujian);
                        $sheet->setCellValue("F" . $baris, $value->jenis_kelamin);
                        $sheet->setCellValue("G" . $baris, ($viewpSekolah2 != null) ? $viewpSekolah2->nama_sekolah : '');
                        $sheet->setCellValue("H" . $baris, $viewpRumah->jalan);
                        $sheet->setCellValue("I" . $baris, $viewpRumah->provinsi);
                        $sheet->setCellValue("J" . $baris, $viewpRumah->kab_kota);
                        $sheet->setCellValue("K" . $baris, $viewpRumah->kecamatan);
                        $sheet->setCellValue("L" . $baris, $viewpRumah->kelurahan);
                        $sheet->setCellValue("M" . $baris, $viewpRumah->kode_pos);
                        $sheet->setCellValueExplicit("N" . $baris, $tblUsers->nmr_tlpn, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                        $sheet->setCellValue("O" . $baris, $tblUsers->email);
                        $sheet->setCellValue("P" . $baris, $value->keb_khusus);
                        $sheet->setCellValue("Q" . $baris, $total);

                        $baris++;
                        $no++;
                    }
                }
                $writer = new Xlsx($spreadsheet);
                $filename = urlencode("Kebutuhan_khusus_" . date("Y_m_d_H_i_s") . ".xlsx");
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment;filename="' . $filename . '"');
                header('Cache-Control: max-age=0');
                $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
                $writer->save('php://output');
            } else {
                $this->session->set_flashdata('message', 'Data kosong.');
                $this->session->set_flashdata('type_message', 'danger');
                redirect('mandiri/export/kebutuhan-khusus');
            }
        } else {
            $this->session->set_flashdata('message', 'Hak akses ditolak.');
            $this->session->set_flashdata('type_message', 'danger');
            redirect('dashboard/');
        }
    }
}
