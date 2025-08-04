<?php
defined('BASEPATH') or exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Akademik extends CI_Controller
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
        $this->load->model('Tbl_users');
    }

    function index()
    {
        $hak_akses = $this->master->read('hak-akses/?ids_level=' . $this->jwt->ids_level . '&ids_modul=65&aksi_hak_akses=export');
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
                'title'         => 'Export Akademik | ' . $_ENV['APPLICATION_NAME'],
                'content'       => 'Export/Mandiri/akademik/content',
                'css'           => 'Export/Mandiri/akademik/css',
                'javascript'    => 'Export/Mandiri/akademik/javascript',
                'modal'         => 'Export/Mandiri/akademik/modal',

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
        $hak_akses = $this->master->read('hak-akses/?ids_level=' . $this->jwt->ids_level . '&ids_modul=65&aksi_hak_akses=export');
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
            $where['pembayaran'] = 'SUDAH';
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
                $cols = array("A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U");
                $val = array(
                    "NIK(No.KTP)", "Nomor Peserta", "Nama", "Tempat Lahir", "Tanggal Lahir", "Jenis Kelamin", "Warga Negara", "Agama", "Provinsi",
                    "Kabupaten / Kota", "Kecamatan", "Kelurahan", "Alamat", "Kode Pos", "Telepon", "Rumpun", "Jenis Sekolah", "Jurusan Sekolah", "Nama Sekolah", "Akreditasi Sekolah", "Email"
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
                    $viewpSekolah = $this->Viewp_sekolah->search($rules);
                    $viewpSekolah_status = false;
                    if ($viewpSekolah->num_rows() > 0) {
                        $viewpSekolah = $viewpSekolah->row();
                        $viewpSekolah_status = true;
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

                    $sheet->setCellValueExplicit("A" . $baris, $viewpBiodata->nik, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                    $sheet->setCellValueExplicit("B" . $baris, $value->nomor_peserta, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                    $sheet->setCellValue("C" . $baris, $value->nama);
                    $sheet->setCellValue("D" . $baris, $viewpBiodata->tempat_lahir);
                    $sheet->setCellValue("E" . $baris, $viewpBiodata->tgl_lahir);
                    $sheet->setCellValue("F" . $baris, $viewpBiodata->jenis_kelamin);
                    $sheet->setCellValue("G" . $baris, $viewpBiodata->kewarganegaraan);
                    $sheet->setCellValue("H" . $baris, $viewpBiodata->agama);
                    $sheet->setCellValue("I" . $baris, $viewpRumah->provinsi);
                    $sheet->setCellValue("J" . $baris, $viewpRumah->kab_kota);
                    $sheet->setCellValue("K" . $baris, $viewpRumah->kecamatan);
                    $sheet->setCellValue("L" . $baris, $viewpRumah->kelurahan);
                    $sheet->setCellValue("M" . $baris, $viewpRumah->jalan);
                    $sheet->setCellValue("N" . $baris, $viewpRumah->kode_pos);
                    $sheet->setCellValueExplicit("O" . $baris, $tblUsers->nmr_tlpn, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                    $sheet->setCellValue("P" . $baris, ($viewpSekolah_status == true) ? $viewpSekolah->rumpun : '');
                    $sheet->setCellValue("Q" . $baris, ($viewpSekolah_status == true) ? $viewpSekolah->jenis_sekolah : '');
                    $sheet->setCellValue("R" . $baris, ($viewpSekolah_status == true) ? $viewpSekolah->jurusan_sekolah : '');
                    $sheet->setCellValue("S" . $baris, ($viewpSekolah_status == true) ? $viewpSekolah->nama_sekolah : '');
                    $sheet->setCellValue("T" . $baris, ($viewpSekolah_status == true) ? $viewpSekolah->akreditasi_sekolah : '');
                    $sheet->setCellValue("U" . $baris, $tblUsers->email);

                    $baris++;
                    $no++;
                }
                $writer = new Xlsx($spreadsheet);
                $filename = urlencode("Akademik_" . $this->input->post('tahun') . "_" . $this->input->post('ids_program') . "_" . $this->input->post('ids_tipe_ujian') . "_" . date("Y_m_d_H_i_s") . ".xlsx");
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment;filename="' . $filename . '"');
                header('Cache-Control: max-age=0');
                $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
                $writer->save('php://output');
            } else {
                $this->session->set_flashdata('message', 'Data kosong.');
                $this->session->set_flashdata('type_message', 'danger');
                redirect('mandiri/export/akademik');
            }
        } else {
            $this->session->set_flashdata('message', 'Hak akses ditolak.');
            $this->session->set_flashdata('type_message', 'danger');
            redirect('dashboard/');
        }
    }
}
