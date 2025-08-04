<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Kelulusan extends CI_Controller
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

        $this->load->model('ServerSide/SS_kelulusan');
        $this->load->model('Settings/Tbs_daya_tampung');
        $this->load->model('Mandiri/Viewp_pilihan');
        $this->load->model('Mandiri/Viewp_kelulusan');
    }

    function index()
    {
        $hak_akses = $this->master->read('hak-akses/?ids_level=' . $this->jwt->ids_level . '&ids_modul=67&aksi_hak_akses=read');
        if ($hak_akses->code == 200) {
            $rules = array(
                'database'  => null,
                'select'    => 'YEAR(date_created) as tahun', // not null
                'where'     => null,
                'or_where'  => null,
                'like'      => null,
                'or_like'   => null,
                'order'     => 'tahun DESC',
                'group_by'  => null,
            );
            $tahun = $this->Viewp_kelulusan->distinct($rules)->result();
            $data = array(
                'title'         => 'Kelulusan | ' . $_ENV['APPLICATION_NAME'],
                'content'       => 'Mandiri/kelulusan/content',
                'css'           => 'Mandiri/kelulusan/css',
                'javascript'    => 'Mandiri/kelulusan/javascript',
                'modal'         => 'Mandiri/kelulusan/modal',
                'tahun'         => $tahun,
            );
            $this->load->view('index', $data);
        } else {
            $this->session->set_flashdata('message', 'Hak akses ditolak.');
            $this->session->set_flashdata('type_message', 'danger');
            redirect('dashboard/');
        }
    }

    function GetKelulusan($id = null)
    {
        $response = null;
        $hak_akses = $this->master->read('hak-akses/?ids_level=' . $this->jwt->ids_level . '&ids_modul=67&aksi_hak_akses=single');
        if ($hak_akses->code == 200) {
            if ($id == null) {
                $rules = array(
                    'database'  => null,
                    'select'    => null,
                    'order'     => null,
                    'limit'     => null,
                    'group_by'    => null,
                );
                $tbpKelulusan = $this->Viewp_formulir->read($rules);
                if ($tbpKelulusan->num_rows() > 0) {
                    $data = array(
                        'status' => 200,
                        'data' => $tbpKelulusan->result()
                    );
                } else {
                    $data = array(
                        'status' => 204,
                        'data' => null
                    );
                }
            } else {
                $rules = array(
                    'database'  => null,
                    'select'    => null,
                    'where'     => array(
                        'idp_formulir' => $id,
                    ),
                    'or_where'  => null,
                    'like'      => null,
                    'or_like'   => null,
                    'order'     => null,
                    'limit'     => null,
                    'group_by'  => null,
                );
                $tbpKelulusan = $this->Viewp_kelulusan->search($rules);
                if ($tbpKelulusan->num_rows() > 0) {
                    $response = array(
                        'status' => 200,
                        'data' => $tbpKelulusan->row()
                    );
                } else {
                    $response = array(
                        'status' => 204,
                        'data' => null
                    );
                }
            }
        } else {
            $response = array(
                'status' => 400,
                'message' => 'Hak akses ditolak.'
            );
        }
        echo json_encode($response);
    }

    function GetGrade($id = null)
    {
        $response = null;
        $hak_akses = $this->master->read('hak-akses/?ids_level=' . $this->jwt->ids_level . '&ids_modul=67&aksi_hak_akses=single');
        if ($hak_akses->code == 200) {
            if ($id == null) {
                $data = array(
                    'status' => 204,
                    'data' => 'ID formulir tidak boleh kosong.'
                );
            } else {
                $rules = array(
                    'database'  => null,
                    'select'    => null,
                    'where'     => array(
                        'idp_formulir' => $id,
                    ),
                    'or_where'  => null,
                    'like'      => null,
                    'or_like'   => null,
                    'order'     => 'pilihan ASC',
                    'limit'     => null,
                    'group_by'  => null,
                );
                $tbpPilihan = $this->Viewp_pilihan->search($rules)->result();
                $pilihan = [];
                foreach ($tbpPilihan as $a) {
                    $rules = array(
                        'database'  => null,
                        'select'    => null,
                        'where'     => array(
                            'kode_jurusan' => $a->kode_jurusan,
                            'YEAR(date_created)' => date('Y')
                        ),
                        'or_where'  => null,
                        'like'      => null,
                        'or_like'   => null,
                        'order'     => null,
                        'limit'     => null,
                        'group_by'  => null,
                    );
                    $tbsDayaTampung = $this->Tbs_daya_tampung->search($rules)->row();
                    $pilihan[] = array(
                        'pilihan' => $a->pilihan,
                        'kode_jurusan' => $a->kode_jurusan,
                        'jurusan' => $a->jurusan,
                        'grade' => $tbsDayaTampung->grade
                    );
                }
                $response = array(
                    'status' => 200,
                    'data' => $pilihan
                );
            }
        } else {
            $response = array(
                'status' => 400,
                'message' => 'Hak akses ditolak.'
            );
        }
        echo json_encode($response);
    }

    function JsonFormFilter()
    {
        $list = $this->SS_kelulusan->get_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $row) {
            if ($row->lulus == 'YA') {
                $lulus = '<div class="badge bg-success">Lulus</div>';
            } else if ($row->lulus == 'TIDAK') {
                $lulus = '<div class="badge bg-danger">Tidak Lulus</div>';
            } else {
                $lulus = '<div class="badge bg-warning">Belum Lulus</div>';
            }
            $sub_array = array();
            $sub_array[] = ++$no;
            $sub_array[] = "<a href=\"" . base_url('mandiri/mahasiswa/detail/' . $row->idp_formulir) . "\" title=\"Detail\" class=\"btn btn-xs btn-primary\" target=\"_blank\">
                                <span class=\"tf-icon bx bx-detail bx-xs\"></span> Detail
                            </a>";
            $sub_array[] = date('Y', strtotime($row->date_created));
            $sub_array[] = $row->nomor_peserta;
            $sub_array[] = $row->nama;
            $sub_array[] = $row->fakultas;
            $sub_array[] = $row->jurusan;
            $sub_array[] = $lulus;
            $data[] = $sub_array;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->SS_kelulusan->count_all(),
            "recordsFiltered" => $this->SS_kelulusan->count_filtered(),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }
}
