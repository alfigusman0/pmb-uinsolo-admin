<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Auth extends CI_Controller
{
    private $jwt = null;

    public function __construct()
    {
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');

        $cookieName = $_ENV['COOKIE_NAME'] ?? '';
        if (!empty($this->input->cookie($cookieName, TRUE))) {
            $this->jwt = $this->jsonwebtoken->jwtDecodeSSO();
            if ($this->jwt['status'] === 'success') {
                $this->jwt = $this->jwt['data'];
            } else {
                $this->session->set_flashdata('message', $this->jwt['message']);
                $this->session->set_flashdata('type_message', 'danger');
                redirect('login-back');
            }
        } else {
            redirect($_ENV['SSO']);
        }

        $this->load->model('View_grup_users');
    }

    public function LoginAs($id_user)
    {
        if (!empty($this->jwt->level)) {
            $rules = array(
                'where' => array('id_user' => $id_user),
            );
            $viewUsers = $this->View_grup_users->search($rules);
            if ($viewUsers->num_rows() > 0) {
                $viewUsers = $viewUsers->row();
                $data = array(
                    'user_time'     => time(),
                    'app'           => $_ENV['APPLICATION_NAME'],
                    'id_user'       => $viewUsers->id_user,
                    'nama'          => $viewUsers->nama,
                    'email'         => $viewUsers->email,
                    'username'      => $viewUsers->username,
                    'nmr_tlpn'      => $viewUsers->nmr_tlpn,
                    'mandiri'       => $viewUsers->mandiri,
                    'ids_level'     => $viewUsers->ids_level,
                    'level'         => $viewUsers->level,
                    'tingkat'       => $viewUsers->tingkat,
                    'ids_grup'      => $viewUsers->ids_grup,
                    'grup'          => $viewUsers->grup,
                    'keterangan'    => $viewUsers->mandiri,
                    'login_as'      => 'YA',
                    'id_admin'      => $this->jwt->ids_user
                );
                $fb = $this->jsonwebtoken->jwtEncode($_ENV['COOKIE_FRONTEND'], $data);
                if ($fb['status'] === 'success') {
                    redirect($_ENV['HOST_FRONTEND']);
                } else {
                    $this->session->set_flashdata('message', $fb['message']);
                    $this->session->set_flashdata('type_message', 'warning');
                    redirect('dashboard');
                }
            } else {
                $this->session->set_flashdata('message', 'Akun tidak terdaftar.');
                $this->session->set_flashdata('type_message', 'warning');
                redirect('dashboard');
            }
        } else {
            redirect('/');
        }
    }

    public function Notifikasi()
    {
        $this->load->view('notif');
    }
}
