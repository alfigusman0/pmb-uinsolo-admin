<?php
defined('BASEPATH') or exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Mahasiswa extends CI_Controller
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
        $this->load->model('Daftar/Tbd_mahasiswa');
        $this->load->model('Daftar/Viewd_mahasiswa');
        $this->load->model('Daftar/Viewd_kelulusan');
    }

    function index()
    {
        $hak_akses = $this->master->read('hak-akses/?ids_level=' . $this->jwt->ids_level . '&ids_modul=45&aksi_hak_akses=export');
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
                'title'         => 'Export Mahasiswa | ' . $_ENV['APPLICATION_NAME'],
                'content'       => 'Export/Daftar/mahasiswa/content',
                'css'           => 'Export/Daftar/mahasiswa/css',
                'javascript'    => 'Export/Daftar/mahasiswa/javascript',
                'modal'         => 'Export/Daftar/mahasiswa/modal',
                'tahun'         => $this->Viewd_kelulusan->distinct($rules)->result(),
                'fakultas'      => $fakultas
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
        $hak_akses = $this->master->read('hak-akses/?ids_level=' . $this->jwt->ids_level . '&ids_modul=45&aksi_hak_akses=export');
        if ($hak_akses->code == 200) {
            $tahun = $this->input->post('tahun');
            $daftar = $this->input->post('daftar');
            $submit = $this->input->post('submit');
            $pembayaran = $this->input->post('pembayaran');
            $pemberkasan = $this->input->post('pemberkasan');
            $ids_fakultas = $this->input->post('ids_fakultas');
            $kode_jurusan = $this->input->post('kode_jurusan');
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
            if ($ids_fakultas != 'SEMUA') {
                $where['ids_fakultas'] = $ids_fakultas;
            }
            if ($kode_jurusan != 'SEMUA') {
                $where['kode_jurusan'] = $kode_jurusan;
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
                $cols = array("A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z", "AA", "AB");
                $val = array("No", "Nomor Peserta", "NIM", "NIK", "Nama", "Jenjang", "Fakultas", "Jurusan", "Konsentrasi", "Kelas", "Jalur Masuk", "Tahun", "Jenis Kelamin", "Tempat Lahir", "Tanggal Lahir", "Agama", "Kewarganegaraan", "Jenis Tinggal", "Alat Transportasi", "Terima KPS", "No. KPS", "Jenis Pendaftaran", "Jenis Pembiayaan", "Rumpun", "Hubungan", "Ukuran Baju", "Ukuran Jas", "Nomor Telepon");
                for ($a = 0; $a < 28; $a++) {
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
                        $sheet->setCellValueExplicit("B" . $baris, $value->nomor_peserta, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                        $sheet->setCellValueExplicit("C" . $baris, $value->nim, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                        $sheet->setCellValueExplicit("D" . $baris, '', \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                        $sheet->setCellValue("E" . $baris, $value->nama);
                        $sheet->setCellValue("F" . $baris, $value->jenjang);
                        $sheet->setCellValue("G" . $baris, $value->fakultas);
                        $sheet->setCellValue("H" . $baris, $value->jurusan);
                        $sheet->setCellValue("I" . $baris, $value->konsentrasi);
                        $sheet->setCellValue("J" . $baris, $value->kelas);
                        $sheet->setCellValue("K" . $baris, $value->alias_jalur_masuk);
                        $sheet->setCellValue("L" . $baris, $value->tahun);
                        $sheet->setCellValue("M" . $baris, '');
                        $sheet->setCellValue("N" . $baris, '');
                        $sheet->setCellValue("O" . $baris, '');
                        $sheet->setCellValue("P" . $baris, '');
                        $sheet->setCellValue("Q" . $baris, '');
                        $sheet->setCellValue("R" . $baris, '');
                        $sheet->setCellValue("S" . $baris, '');
                        $sheet->setCellValue("T" . $baris, '');
                        $sheet->setCellValue("U" . $baris, '');
                        $sheet->setCellValue("V" . $baris, '');
                        $sheet->setCellValue("W" . $baris, '');
                        $sheet->setCellValue("X" . $baris, '');
                        $sheet->setCellValue("Y" . $baris, '');
                        $sheet->setCellValue("Z" . $baris, '');
                        $sheet->setCellValue("AA" . $baris, '');
                        $sheet->setCellValue("AB" . $baris, '');
                    } else {
                        $viewdMahasiswa = $viewdMahasiswa->row();
                        $rules = array(
                            'database'  => null, //Default database master
                            'select'    => null,
                            'where'     => array(
                                'id_user' => $value->id_user,
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
                        $sheet->setCellValueExplicit("B" . $baris, $value->nomor_peserta, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                        $sheet->setCellValueExplicit("C" . $baris, $value->nim, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                        $sheet->setCellValueExplicit("D" . $baris, $viewdMahasiswa->nik, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                        $sheet->setCellValue("E" . $baris, $value->nama);
                        $sheet->setCellValue("F" . $baris, $value->jenjang);
                        $sheet->setCellValue("G" . $baris, $value->fakultas);
                        $sheet->setCellValue("H" . $baris, $value->jurusan);
                        $sheet->setCellValue("I" . $baris, $value->konsentrasi);
                        $sheet->setCellValue("J" . $baris, $value->kelas);
                        $sheet->setCellValue("K" . $baris, $value->alias_jalur_masuk);
                        $sheet->setCellValue("L" . $baris, $value->tahun);
                        $sheet->setCellValue("M" . $baris, $viewdMahasiswa->jenis_kelamin);
                        $sheet->setCellValue("N" . $baris, $viewdMahasiswa->tempat_lahir);
                        $sheet->setCellValue("O" . $baris, $viewdMahasiswa->tgl_lahir);
                        $sheet->setCellValue("P" . $baris, $viewdMahasiswa->agama);
                        $sheet->setCellValue("Q" . $baris, $viewdMahasiswa->kewarganegaraan);
                        $sheet->setCellValue("R" . $baris, $viewdMahasiswa->jenis_tinggal);
                        $sheet->setCellValue("S" . $baris, $viewdMahasiswa->alat_transportasi);
                        $sheet->setCellValue("T" . $baris, $viewdMahasiswa->terima_kps);
                        $sheet->setCellValue("U" . $baris, $viewdMahasiswa->no_kps);
                        $sheet->setCellValue("V" . $baris, $viewdMahasiswa->jenis_pendaftaran);
                        $sheet->setCellValue("W" . $baris, $viewdMahasiswa->jenis_pembiayaan);
                        $sheet->setCellValue("X" . $baris, $viewdMahasiswa->rumpun);
                        $sheet->setCellValue("Y" . $baris, $viewdMahasiswa->hubungan);
                        $sheet->setCellValue("Z" . $baris, $viewdMahasiswa->ukuran_baju);
                        $sheet->setCellValue("AA" . $baris, $viewdMahasiswa->ukuran_jas);
                        $sheet->setCellValue("AB" . $baris, $tblUsers->nmr_tlpn);
                    }

                    //Set number value
                    //$objPHPExcel->getActiveSheet()->getStyle('C1:C'.$baris)->getNumberFormat()->setFormatCode('0');

                    $baris++;
                    $no++;
                }
                $writer = new Xlsx($spreadsheet);
                $filename = urlencode("Mahasiswa_" . date("Y_m_d_H_i_s") . ".xlsx");
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment;filename="' . $filename . '"');
                header('Cache-Control: max-age=0');
                $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
                $writer->save('php://output');
            } else {
                $this->session->set_flashdata('message', 'Data kosong.');
                $this->session->set_flashdata('type_message', 'danger');
                redirect('daftar/export/mahasiswa');
            }
        } else {
            $this->session->set_flashdata('message', 'Hak akses ditolak.');
            $this->session->set_flashdata('type_message', 'danger');
            redirect('dashboard/');
        }
    }
}
