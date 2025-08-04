<?php
defined('BASEPATH') or exit('No direct script access allowed');
class BobotNilaiUkt extends CI_Controller
{

    var $jwt = null;

    function __construct()
    {
        parent::__construct();
        $this->jwt = $this->jsonwebtoken->jwtDecodeSSO();
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

        $this->load->model('Histori/Tbh_bobot_nilai_ukt');
        $this->load->model('ServerSide/SSh_bobot_nilai_ukt');
    }

    function index()
    {
        $hak_akses = $this->master->read('hak-akses/?ids_level=' . $this->jwt->ids_level . '&ids_modul=47&aksi_hak_akses=history');
        if ($hak_akses->code == 200) {
            $data = array(
                'title'             => 'Histori Bobot Nilai UKT | ' . $_ENV['APPLICATION_NAME'],
                'content'           => 'ukt/histori/bobot_nilai/content',
                'css'               => 'ukt/histori/bobot_nilai/css',
                'javascript'        => 'ukt/histori/bobot_nilai/javascript',
                'modal'             => 'ukt/histori/bobot_nilai/modal',
            );
            $this->load->view('index', $data);
        } else {
            $this->session->set_flashdata('message', 'Hak akses ditolak.');
            $this->session->set_flashdata('type_message', 'danger');
            redirect('dashboard/');
        }
    }

    function JsonFormSearch()
    {
        // Mengambil data dari model
        $list = $this->SSh_bobot_nilai_ukt->get_datatables();
        $data = array();
        $no = $this->input->post('start'); // Menggunakan $this->input->post() daripada $_POST

        foreach ($list as $row) {
            $sub_array = array();
            $sub_array[] = ++$no;
            $sub_array[] = $row->nama_field;
            $sub_array[] = $row->alias;
            $sub_array[] = $row->bobot;
            $sub_array[] = $row->nilai_max;
            $sub_array[] = $row->alias_jalur_masuk;
            $sub_array[] = $row->tahun;

            $data[] = $sub_array;
        }

        // Menyusun output dalam format JSON
        $output = array(
            "draw"            => intval($this->input->post("draw")),
            "recordsTotal"    => $this->SSh_bobot_nilai_ukt->count_all(),
            "recordsFiltered" => $this->SSh_bobot_nilai_ukt->count_filtered(),
            "data"            => $data
        );

        // Mengirimkan output JSON
        echo json_encode($output);
    }
}
