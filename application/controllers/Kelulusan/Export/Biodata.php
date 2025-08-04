<?php
defined('BASEPATH') or exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Biodata extends CI_Controller
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
        $this->load->model('Mandiri/Tbp_formulir');
        $this->load->model('Tbl_users');
        $this->load->model('Mandiri/Viewp_rumah');
        $this->load->model('Mandiri/Viewp_biodata');
        $this->load->model('Mandiri/Viewp_file');
        $this->load->model('Mandiri/Viewp_kelulusan');
        $this->load->model('Mandiri/Viewp_formulir');
    }

    function index()
    {
        $hak_akses = $this->master->read('hak-akses/?ids_level=' . $this->jwt->ids_level . '&ids_modul=91&aksi_hak_akses=export');
        if ($hak_akses->code == 200) {
            $rules1 = array(
                'database'  => null, //Default database master
                'select'    => 'YEAR(date_created) as tahun',
                'where'     => null,
                'or_where'  => null,
                'like'      => null,
                'or_like'   => null,
                'order'     => null,
                'group_by'  => null,
            );
            $keterangan = json_decode($this->jwt->keterangan);
            $data = array(
                'title'         => 'Export Biodata | ' . $_ENV['APPLICATION_NAME'],
                'content'       => 'Export/Kelulusan/biodata/content',
                'css'           => 'Export/Kelulusan/biodata/css',
                'javascript'    => 'Export/Kelulusan/biodata/javascript',
                'modal'         => 'Export/Kelulusan/biodata/modal',
                'tahun'       => $this->Viewp_kelulusan->distinct($rules1)->result(),
                'ket_user' => $keterangan,
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
        $hak_akses = $this->master->read('hak-akses/?ids_level=' . $this->jwt->ids_level . '&ids_modul=91&aksi_hak_akses=export');
        if ($hak_akses->code == 200) {
            $tahun = $this->input->post('tahun');
            $nama_penitip = $this->input->post('nama_penitip');
            $keterangan = $this->input->post('keterangan');
            $where = $data = array();
            if ($tahun != 'Semua') {
                $where['YEAR(date_created)'] = $tahun;
            }
            if ($this->input->post('jenjang') != '') {
                $where['jenjang'] = $this->input->post('jenjang');
            }
            if ($this->input->post('ids_fakultas') != '') {
                $where['ids_fakultas'] = $this->input->post('ids_fakultas');
            }
            if ($this->input->post('ids_tipe_ujian') != '') {
                $where['ids_tipe_ujian'] = $this->input->post('ids_tipe_ujian');
            }
            $where['lulus'] = 'YA';
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
            $viewpKelulusan = $this->Viewp_kelulusan->search($rules);
            if ($viewpKelulusan->num_rows() > 0) {
                $spreadsheet = new Spreadsheet();
                $sheet = $spreadsheet->getActiveSheet();
                $spreadsheet->getActiveSheet()->getPageSetup()->setFitToWidth(1);
                $spreadsheet->getActiveSheet()->getPageSetup()->setFitToHeight(1);
                //table header
                $cols = array("A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T");
                $val = array("No", "Nomor Peserta", "Nama", "Tanggal Lahir", "Kode Jurusan", "Jurusan", "Nomor Telepon", "Email", "Alamat", "RT", "RW", "Dusun", "Negara", "Provinsi", "Kabupaten", "Kecamatan", "Kelurahan", "Foto", "Ijazah", "KTP");
                for ($a = 0; $a < 20; $a++) {
                    $sheet->setCellValue($cols[$a] . '1', $val[$a]);
                }
                $baris  = 2;
                $no = 1;
                foreach ($viewpKelulusan->result() as $value) {
                    //pemanggilan sesuaikan dengan nama kolom tabel
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
                    $tbpFormulir = $this->Viewp_formulir->search($rules)->row();
                    $tbpRumah = $this->Viewp_rumah->search($rules)->row();
                    $tbpBiodata = $this->Viewp_biodata->search($rules)->row();
                    $rules = array(
                        'database'  => null, //Default database master
                        'select'    => null,
                        'where'     => array(
                            'idp_formulir' => $value->idp_formulir,
                            'ids_tipe_file' => 13
                        ),
                        'or_where'  => null,
                        'like'      => null,
                        'or_like'   => null,
                        'order'     => null,
                        'limit'     => null,
                        'group_by'  => null,
                    );
                    $tbpFileFoto = $this->Viewp_file->search($rules);
                    $foto = null;
                    if($tbpFileFoto->num_rows() > 0){
                        $foto = $tbpFileFoto->row();
                    }
                    $rules = array(
                        'database'  => null, //Default database master
                        'select'    => null,
                        'where'     => array(
                            'idp_formulir' => $value->idp_formulir,
                            'ids_tipe_file' => 31
                        ),
                        'or_where'  => null,
                        'like'      => null,
                        'or_like'   => null,
                        'order'     => null,
                        'limit'     => null,
                        'group_by'  => null,
                    );
                    $tbpFileIjazah = $this->Viewp_file->search($rules);
                    $ijazah = null;
                    if($tbpFileIjazah->num_rows() > 0){
                        $ijazah = $tbpFileIjazah->row();
                    }
                    $rules = array(
                        'database'  => null, //Default database master
                        'select'    => null,
                        'where'     => array(
                            'idp_formulir' => $value->idp_formulir,
                            'ids_tipe_file' => 33
                        ),
                        'or_where'  => null,
                        'like'      => null,
                        'or_like'   => null,
                        'order'     => null,
                        'limit'     => null,
                        'group_by'  => null,
                    );
                    $tbpFileKTP = $this->Viewp_file->search($rules);
                    $ktp = null;
                    if($tbpFileKTP->num_rows() > 0){
                        $ktp = $tbpFileKTP->row();
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
                    $sheet->setCellValueExplicit("B" . $baris, $value->nomor_peserta, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                    $sheet->setCellValue("C" . $baris, $tbpFormulir->nama);
                    $sheet->setCellValue("D" . $baris, $tbpBiodata->tgl_lahir);
                    $sheet->setCellValue("E" . $baris, $value->kode_jurusan);
                    $sheet->setCellValue("F" . $baris, $value->jurusan);
                    $sheet->setCellValue("G" . $baris, $tblUsers->nmr_tlpn);
                    $sheet->setCellValue("H" . $baris, $tblUsers->email);
                    $sheet->setCellValue("I" . $baris, $tbpRumah->jalan);
                    $sheet->setCellValue("J" . $baris, $tbpRumah->rt);
                    $sheet->setCellValue("K" . $baris, $tbpRumah->rw);
                    $sheet->setCellValue("L" . $baris, $tbpRumah->dusun);
                    $sheet->setCellValue("M" . $baris, $tbpRumah->negara);
                    $sheet->setCellValue("N" . $baris, $tbpRumah->provinsi);
                    $sheet->setCellValue("O" . $baris, $tbpRumah->kab_kota);
                    $sheet->setCellValue("P" . $baris, $tbpRumah->kecamatan);
                    $sheet->setCellValue("Q" . $baris, $tbpRumah->kelurahan);
                    $sheet->setCellValue("R" . $baris, ($foto != null) ? $_ENV['HOST_FRONTEND'].'upload/mandiri/'.date('Y').'/'.$foto->file : '-');
                    $sheet->setCellValue("S" . $baris, ($ijazah != null) ? $_ENV['HOST_FRONTEND'].'upload/mandiri/'.date('Y').'/'.$ijazah->file : '-');
                    $sheet->setCellValue("T" . $baris, ($ktp != null) ? $_ENV['HOST_FRONTEND'].'upload/mandiri/'.date('Y').'/'.$ktp->file : '-');

                    //Set number value
                    //$objPHPExcel->getActiveSheet()->getStyle('C1:C'.$baris)->getNumberFormat()->setFormatCode('0');

                    $baris++;
                    $no++;
                }
                $writer = new Xlsx($spreadsheet);
                $filename = urlencode("BiodataKelulusan_" . date("Y_m_d_H_i_s") . ".xlsx");
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment;filename="' . $filename . '"');
                header('Cache-Control: max-age=0');
                $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
                $writer->save('php://output');
            } else {
                $this->session->set_flashdata('message', 'Data kosong.');
                $this->session->set_flashdata('type_message', 'danger');
                redirect('kelulusan/export/biodata');
            }
        } else {
            $this->session->set_flashdata('message', 'Hak akses ditolak.');
            $this->session->set_flashdata('type_message', 'danger');
            redirect('dashboard/');
        }
    }
}
