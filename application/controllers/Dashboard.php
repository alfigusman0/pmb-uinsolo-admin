<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Dashboard extends CI_Controller
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
        $hak_akses = $this->master->read('hak-akses/?ids_level=' . $this->jwt->ids_level . '&ids_modul=43&aksi_hak_akses=read');
        if ($hak_akses->code == 200) {
            $data = array(
                'title'         => 'Dashboard | ' . $_ENV['APPLICATION_NAME'],
                'content'       => 'dashboard/content',
                'css'           => 'dashboard/css',
                'javascript'    => 'dashboard/javascript',
                'modal'         => 'dashboard/modal',
                'hak_akses'     => $this->master->read('hak-akses/?ids_level=' . $this->jwt->ids_level . '&ids_modul=149&aksi_hak_akses=read')
            );
            $this->load->view('index', $data);
        } else {
            $this->session->set_flashdata('message', 'Hak akses ditolak.');
            $this->session->set_flashdata('type_message', 'danger');
            redirect('Auth/Notifikasi/');
        }
    }
}
