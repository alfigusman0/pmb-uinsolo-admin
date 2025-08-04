<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Users extends CI_Controller
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
        $this->load->model('ServerSide/SS_users');
    }

    function index()
    {
        $hak_akses = $this->master->read('hak-akses/?ids_level=' . $this->jwt->ids_level . '&ids_modul=44&aksi_hak_akses=read');
        if ($hak_akses->code == 200) {
            $data = array(
                'title'         => 'Users | ' . $_ENV['APPLICATION_NAME'],
                'content'       => 'users/content',
                'css'           => 'users/css',
                'javascript'    => 'users/javascript',
                'modal'         => 'users/modal',
            );
            $this->load->view('index', $data);
        } else {
            $this->session->set_flashdata('message', 'Hak akses ditolak.');
            $this->session->set_flashdata('type_message', 'danger');
            redirect('dashboard/');
        }
    }

    function Add()
    {
        $hak_akses = $this->master->read('hak-akses/?ids_level=' . $this->jwt->ids_level . '&ids_modul=44&aksi_hak_akses=create');
        if ($hak_akses->code == 200) {
            $this->_validate('tambah');
            $data = array(
                'nama'  => strtoupper($this->input->post('nama')),
                'email'  => $this->input->post('email'),
                'password'  => password_hash($this->input->post('password'), PASSWORD_DEFAULT),
                'nmr_tlpn'  => $this->input->post('nmr_tlpn'),
            );
            if ($this->Tbl_users->create($data)) {
                $response = array(
                    'status' => 200,
                    'message' => 'Berhasil tambah data.'
                );
            } else {
                $response = array(
                    'status' => 400,
                    'message' => 'Gagal tambah data.'
                );
            }
            echo json_encode($response);
        } else {
            $response = array(
                'status' => 401,
                'message' => 'Hak akses ditolak.'
            );
            echo json_encode($response);
        }
    }

    function Update()
    {
        $hak_akses = $this->master->read('hak-akses/?ids_level=' . $this->jwt->ids_level . '&ids_modul=44&aksi_hak_akses=update');
        if ($hak_akses->code == 200) {
            $this->_validate();
            if (!empty($this->input->post('password'))) {
                $rules = array(
                    'where' => array(
                        'id_user'  => $this->input->post('id_user'),
                    ),
                    'or_where'            => null,
                    'like'                => null,
                    'or_like'            => null,
                    'data'  => array(
                        'nama'  => strtoupper($this->input->post('nama')),
                        'email'  => $this->input->post('email'),
                        'password'  => password_hash($this->input->post('password'), PASSWORD_DEFAULT),
                        'nmr_tlpn'  => $this->input->post('nmr_tlpn'),
                    ),
                );
            } else {
                $rules = array(
                    'where' => array(
                        'id_user'  => $this->input->post('id_user'),
                    ),
                    'or_where'            => null,
                    'like'                => null,
                    'or_like'            => null,
                    'data'  => array(
                        'nama'  => strtoupper($this->input->post('nama')),
                        'email'  => $this->input->post('email'),
                        'nmr_tlpn'  => $this->input->post('nmr_tlpn'),
                    ),
                );
            }
            if ($this->Tbl_users->update($rules)) {
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
            echo json_encode($response);
        } else {
            $response = array(
                'status' => 401,
                'message' => 'Hak akses ditolak.'
            );
            echo json_encode($response);
        }
    }

    function Delete($id)
    {
        $hak_akses = $this->master->read('hak-akses/?ids_level=' . $this->jwt->ids_level . '&ids_modul=44&aksi_hak_akses=delete');
        if ($hak_akses->code == 200) {
            $rules = array(
                'where'                => array(
                    'id_user' => $id
                ),
                'or_where'            => null,
                'like'                => null,
                'or_like'            => null,
            );
            if ($this->Tbl_users->delete($rules)) {
                $response = array(
                    'status' => 200,
                    'message' => 'Berhasil hapus data.'
                );
            } else {
                $response = array(
                    'status' => 400,
                    'message' => 'Gagal hapus data.'
                );
            }
            echo json_encode($response);
        } else {
            $response = array(
                'status' => 401,
                'message' => 'Hak akses ditolak.'
            );
            echo json_encode($response);
        }
    }

    function Get($id = null)
    {
        $hak_akses = $this->master->read('hak-akses/?ids_level=' . $this->jwt->ids_level . '&ids_modul=44&aksi_hak_akses=single');
        if ($hak_akses->code == 200) {
            if ($id == null) {
                $rules = array(
                    'database'    => null, //Database master
                    'select'    => null,
                    'order'     => null,
                    'limit'     => null,
                    'group_by'    => null,
                );
                $tbsUsers = $this->Tbl_users->read($rules);
                if ($tbsUsers->num_rows() > 0) {
                    $data = array(
                        'status' => 200,
                        'data' => $tbsUsers->result()
                    );
                } else {
                    $data = array(
                        'status' => 204,
                        'data' => null
                    );
                }
            } else {
                $rules = array(
                    'database'          => null, //Default database master
                    'select'            => null,
                    'where'                => array(
                        'id_user' => $id
                    ),
                    'or_where'            => null,
                    'like'                => null,
                    'or_like'            => null,
                    'order'                => null,
                    'limit'                => null,
                    'group_by'            => null,
                );
                $tbsUsers = $this->Tbl_users->search($rules);
                if ($tbsUsers->num_rows() > 0) {
                    $data = array(
                        'status' => 200,
                        'data' => $tbsUsers->row()
                    );
                } else {
                    $data = array(
                        'status' => 204,
                        'data' => null
                    );
                }
            }
            echo json_encode($data);
        } else {
            $response = array(
                'status' => 401,
                'message' => 'Hak akses ditolak.'
            );
            echo json_encode($response);
        }
    }

    function JsonFormFilter()
    {
        $hak_akses = $this->master->read('hak-akses/?ids_level=' . $this->jwt->ids_level . '&ids_modul=44&aksi_hak_akses=read');
        if ($hak_akses->code == 200) {
            $list = $this->SS_users->get_datatables();
            $data = array();
            $no = $_POST['start'];
            foreach ($list as $row) {
                $sub_array = array();
                $sub_array[] = ++$no;
                $sub_array[] = "<a href=\"" . base_url('login-as/' . $row->id_user) . "\" title=\"Login As\" class=\"btn btn-xs btn-primary\">
                                <span class=\"tf-icon bx bx-user bx-xs\"></span> Login As
                            </a>
                            <button type=\"button\" title=\"Edit\" onclick=\"edit_data(" . $row->id_user . ")\" class=\"btn btn-xs btn-warning\">
                                <span class=\"tf-icon bx bx-edit bx-xs\"></span> Edit
                            </button>
                            <a href=\"javascript:void(0)\" title=\"Hapus\" class=\"btn btn-xs btn-danger\" onclick=\"delete_data(" . $row->id_user . ")\">
                                <span class=\"tf-icon bx bx-trash bx-xs\"></span> Hapus
                            </a>";
                $sub_array[] = $row->nama;
                $sub_array[] = $row->email;
                $sub_array[] = $row->nmr_tlpn;
                $data[] = $sub_array;
            }

            $output = array(
                "draw" => $_POST['draw'],
                "recordsTotal" => $this->SS_users->count_all(),
                "recordsFiltered" => $this->SS_users->count_filtered(),
                "data" => $data,
            );
            //output to json format
            echo json_encode($output);
        } else {
            $this->session->set_flashdata('message', 'Hak akses ditolak.');
            $this->session->set_flashdata('type_message', 'danger');
            redirect('dashboard/');
        }
    }

    private function _validate($method = '')
    {
        $data = array();
        $data['error_string'] = array();
        $data['inputerror'] = array();
        $data['status'] = TRUE;

        if ($this->input->post('nama') == '') {
            $data['inputerror'][] = 'nama';
            $data['error_string'][] = 'Nama wajib diisi.';
            $data['status'] = FALSE;
        }

        if ($this->input->post('email') == '') {
            $data['inputerror'][] = 'email';
            $data['error_string'][] = 'Email wajib diisi.';
            $data['status'] = FALSE;
        }

        if ($this->input->post('nmr_tlpn') == '') {
            $data['inputerror'][] = 'nmr_tlpn';
            $data['error_string'][] = 'Nomor Telepon wajib diisi.';
            $data['status'] = FALSE;
        }

        if ($method == 'tambah') {
            if ($this->input->post('password') == '') {
                $data['inputerror'][] = 'password';
                $data['error_string'][] = 'Password wajib diisi.';
                $data['status'] = FALSE;
            }
        }

        if ($data['status'] === FALSE) {
            echo json_encode($data);
            exit();
        }
    }
}
