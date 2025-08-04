<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Statistik extends CI_Controller
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
        $this->load->model('Daftar/Viewd_kelulusan');
    }

    function index()
    {
        $hak_akses = $this->master->read('hak-akses/?ids_level=' . $this->jwt->ids_level . '&ids_modul=53&aksi_hak_akses=read');
        if ($hak_akses->code == 200) {
            $rules = array(
                'database'      => null, //Default database master
                'select'        => "tahun as tahun", // not null
                'where'         => null,
                'or_where'      => null,
                'like'          => null,
                'or_like'       => null,
                'order'         => 'tahun DESC',
                'group_by'      => null,
            );
            $data = array(
                'title'         => 'Statistik | ' . $_ENV['APPLICATION_NAME'],
                'content'       => 'Daftar/statistik/content',
                'css'           => 'Daftar/statistik/css',
                'javascript'    => 'Daftar/statistik/javascript',
                'modal'         => 'Daftar/statistik/modal',

                'tahun'         => $this->Viewd_kelulusan->distinct($rules)->result(),
            );
            $this->load->view('index', $data);
        } else {
            $this->session->set_flashdata('message', 'Hak akses ditolak.');
            $this->session->set_flashdata('type_message', 'danger');
            redirect('dashboard/');
        }
    }
}
