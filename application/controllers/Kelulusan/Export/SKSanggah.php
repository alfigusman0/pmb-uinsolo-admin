<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Mpdf\Mpdf;

class SKSanggah extends CI_Controller
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
        ini_set("pcre.backtrack_limit", "5000000");
        $this->load->model('Mandiri/Viewp_formulir');
        $this->load->model('Mandiri/Viewp_biodata');
        $this->load->model('Mandiri/Viewp_kelulusan');
        $this->load->model('Mandiri/Tbp_sanggah');
    }

    function index()
    {
        $hak_akses = $this->master->read('hak-akses/?ids_level=' . $this->jwt->ids_level . '&ids_modul=146&aksi_hak_akses=export');
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
            $fakultas = $this->master->read('fakultas/?status=YA&limit=1000');
            $data = array(
                'title' => 'Export SK Sanggah | ' . $_ENV['APPLICATION_NAME'],
                'content' => 'Export/Kelulusan/sk_sanggah/content',
                'css' => 'Export/Kelulusan/sk_sanggah/css',
                'javascript' => 'Export/Kelulusan/sk_sanggah/javascript',
                'modal' => 'Export/Kelulusan/sk_sanggah/modal',
                'tahun' => $this->Viewp_kelulusan->distinct($rules)->result(),
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
        $hak_akses = $this->master->read('hak-akses/?ids_level=' . $this->jwt->ids_level . '&ids_modul=146&aksi_hak_akses=export');
        if ($hak_akses->code == 200) {
            $tahun = $this->input->post('tahun');
            $fakultas = $this->input->post('fakultas');
            if ($tahun != 'Semua') {
                $where['YEAR(date_created)'] = $tahun;
            }
            if ($fakultas != 'Semua') {
                $where['fakultas'] = $fakultas;
            }
            $where['lulus'] = 'YA';
            $rules = array(
                'database'  => null, //Default database master
                'select'    => null,
                'where'     => $where,
                'or_where'  => null,
                'like'      => null,
                'or_like'   => null,
                'order'     => 'kode_jurusan ASC',
                'limit'     => null,
                'group_by'  => null,
            );
            $viewpKelulusan = $this->Viewp_kelulusan->search($rules)->result();

            $data = array(
                'viewpKelulusan' => $viewpKelulusan,
                'fakultas' => $fakultas
            );

            $html = $this->load->view('Export/Kelulusan/pdf/sk_sanggah', $data, true);
            $pdfFilePath = "SK_Sanggah_" . $jurusan . "_" . date("Y_m_d_H_i_s") . ".pdf";
            $mpdf = new Mpdf(['mode' => 'c']);
            $mpdf->showImageErrors = true;
            $mpdf->writehtml($html);
            $mpdf->Output($pdfFilePath, "I");
        } else {
            $this->session->set_flashdata('message', 'Hak akses ditolak.');
            $this->session->set_flashdata('type_message', 'danger');
            redirect('dashboard/');
        }
    }
}
