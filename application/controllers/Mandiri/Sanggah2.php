<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Sanggah2 extends CI_Controller
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

        $this->load->model('ServerSide/SS_sanggah2');
        $this->load->model('Settings/Tbs_sanggah');
        $this->load->model('Mandiri/Tbp_sanggah');
        $this->load->model('Mandiri/Viewp_sanggah');
    }

    function index()
    {
        $hak_akses = $this->master->read('hak-akses/?ids_level=' . $this->jwt->ids_level . '&ids_modul=95&aksi_hak_akses=read');
        if ($hak_akses->code == 200) {
            $rules = array(
                'database'  => null,
                'select'    => 'YEAR(date_created) as tahun', // not null
                'where'     => null,
                'or_where'  => null,
                'like'      => null,
                'or_like'   => null,
                'order'     => null,
                'group_by'  => null,
            );
            $tahun = $this->Viewp_sanggah->distinct($rules)->result();
            $rules2 = array(
                'database'          => null, //Default database master
                'select'            => null, // not null
                'where'                => array('status' => 'YA'),
                'or_where'            => null,
                'like'                => null,
                'or_like'            => null,
                'order'                => null,
                'group_by'            => null,
                'limit'            => null,
            );
            $data = array(
                'title'         => 'Sanggah | ' . $_ENV['APPLICATION_NAME'],
                'content'       => 'Mandiri/sanggah2/content',
                'css'           => 'Mandiri/sanggah2/css',
                'javascript'    => 'Mandiri/sanggah2/javascript',
                'modal'         => 'Mandiri/sanggah2/modal',
                'tahun'         => $tahun,
                'tbsSanggah'         => $this->Tbs_sanggah->search($rules2)->result(),
            );
            $this->load->view('index', $data);
        } else {
            $this->session->set_flashdata('message', 'Hak akses ditolak.');
            $this->session->set_flashdata('type_message', 'danger');
            redirect('dashboard/');
        }
    }

    function Update()
    {
        $response = null;
        $hak_akses = $this->master->read('hak-akses/?ids_level=' . $this->jwt->ids_level . '&ids_modul=95&aksi_hak_akses=update');
        if ($hak_akses->code == 200) {
            $rules = array(
                'where' => array(
                    'idp_sanggah'  => $this->input->post('idp_sanggah'),
                ),
                'or_where' => null,
                'like' => null,
                'or_like' => null,
                'data' => array(
                    'ids_sanggah'  => $this->input->post('ids_sanggah'),
                    'updated_by'  => $this->jwt->ids_user,
                ),
            );
            $fb = $this->Tbp_sanggah->update($rules);
            if (!$fb['status']) {
                $response = array(
                    'status' => 200,
                    'message' => 'Berhasil ubah data.'
                );
            } else {
                $response = array(
                    'status' => 400,
                    'message' => 'Gagal ubah data.'
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

    function Get($id = null)
    {
        $response = null;
        $hak_akses = $this->master->read('hak-akses/?ids_level=' . $this->jwt->ids_level . '&ids_modul=95&aksi_hak_akses=single');
        if ($hak_akses->code == 200) {
            if ($id == null) {
                $rules = array(
                    'database'    => null, //Database master
                    'select'    => null,
                    'order'     => null,
                    'limit'     => null,
                    'pagging'   => null,
                    'group_by'  => null,
                );
                $tbpSanggah = $this->Viewp_sanggah->read($rules);
                if ($tbpSanggah->num_rows() > 0) {
                    $response = array(
                        'status' => 200,
                        'data' => $tbpSanggah->result()
                    );
                } else {
                    $response = array(
                        'status' => 204,
                        'data' => null
                    );
                }
            } else {
                $rules = array(
                    'database'    => null, //Database master
                    'select'    => null,
                    'where'     => array(
                        'idp_sanggah' => $id,
                    ),
                    'or_where'            => null,
                    'like'                => null,
                    'or_like'            => null,
                    'order'                => null,
                    'limit'                => null,
                    'group_by'            => null,
                );
                $tbpSanggah = $this->Viewp_sanggah->search($rules);
                if ($tbpSanggah->num_rows() > 0) {
                    $response = array(
                        'status' => 200,
                        'data' => $tbpSanggah->row()
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

    function Generate()
    {
        $response = null;
        $hak_akses = $this->master->read('hak-akses/?ids_level=' . $this->jwt->ids_level . '&ids_modul=95&aksi_hak_akses=generate');
        if ($hak_akses->code == 200) {
            $rules = array(
                'where' => array(
                    'YEAR(date_created)'  => $this->input->post('tahun'),
                ),
                'or_where' => null,
                'like' => null,
                'or_like' => null,
                'data' => array(
                    'ids_sanggah'  => $this->input->post('ids_sanggah'),
                    'updated_by'  => $this->jwt->ids_user,
                ),
            );
            $fb = $this->Tbp_sanggah->update($rules);
            if (!$fb['status']) {
                $response = array(
                    'status' => 200,
                    'message' => 'Berhasil Generate Jawaban data.'
                );
            } else {
                $response = array(
                    'status' => 400,
                    'message' => 'Gagal Generate Jawaban data.'
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
        $list = $this->SS_sanggah2->get_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $row) {
            $sub_array = array();
            $sub_array[] = ++$no;
            $sub_array[] = "
                            <a href=\"javascript:void(0)\" title=\"Detail\" class=\"btn btn-xs btn-warning m-1\" onclick=\"modal_jawab(" . $row->idp_sanggah . ")\">
                                <span class=\"tf-icon bx bx-edit bx-xs\"></span> Jawab
                            </a>
                            <a href=\"" . base_url('mandiri/mahasiswa/detail/' . $row->idp_formulir) . "\" title=\"Biodata\" class=\"btn btn-xs btn-primary m-1\" target=\"_blank\">
                                <span class=\"tf-icon bx bx-detail bx-xs\"></span> Biodata
                            </a>";
            $sub_array[] = date('Y', strtotime($row->date_created));
            $sub_array[] = $row->nomor_peserta;
            $sub_array[] = $row->nama;
            $sub_array[] = $row->kategori;
            $sub_array[] = $row->tipe_ujian;
            $sub_array[] = $row->nama_sekolah;
            $sub_array[] = $row->kelurahan;
            $sub_array[] = ($row->ids_sanggah == 1) ? '<div class="badge bg-danger">Belum dijawab</div>' : '<div class="badge bg-success">Sudah dijawab</div>';
            $data[] = $sub_array;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->SS_sanggah2->count_all(),
            "recordsFiltered" => $this->SS_sanggah2->count_filtered(),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }
}
