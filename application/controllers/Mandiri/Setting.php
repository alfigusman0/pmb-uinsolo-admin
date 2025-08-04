<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Setting extends CI_Controller
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

        $this->load->model('Mandiri/Tbp_setting');
        $this->load->model('Mandiri/Viewp_setting');
        $this->load->model('ServerSide/SS_setting_mandiri');
    }

    function index()
    {
        $hak_akses = $this->master->read('hak-akses/?ids_level=' . $this->jwt->ids_level . '&ids_modul=63&aksi_hak_akses=read');
        if ($hak_akses->code == 200) {
            $data = array(
                'title'         => 'Setting',
                'content'       => 'Mandiri/setting/content',
                'css'           => 'Mandiri/setting/css',
                'javascript'    => 'Mandiri/setting/javascript',
                'modal'         => 'Mandiri/setting/modal',
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
        $response = null;
        $hak_akses = $this->master->read('hak-akses/?ids_level=' . $this->jwt->ids_level . '&ids_modul=63&aksi_hak_akses=create');
        if ($hak_akses->code == 200) {
            $this->_validate('tambah');
            $data = array(
                'ids_tipe_ujian'  => $this->input->post('ids_tipe_ujian'),
                'nama_setting'  => $this->input->post('nama_setting'),
                'setting'  => $this->input->post('setting'),
                'created_by' => $this->jwt->ids_user,
                'updated_by' => $this->jwt->ids_user,
            );
            $fb = $this->Tbp_setting->create($data);
            if (!$fb['status']) {
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
        } else {
            $response = array(
                'status' => 400,
                'message' => 'Hak akses ditolak.'
            );
        }
        echo json_encode($response);
    }

    function Update()
    {
        $response = null;
        $hak_akses = $this->master->read('hak-akses/?ids_level=' . $this->jwt->ids_level . '&ids_modul=63&aksi_hak_akses=update');
        if ($hak_akses->code == 200) {
            $this->_validate();
            $rules = array(
                'where' => array(
                    'idp_setting'  => $this->input->post('idp_setting'),
                ),
                'or_where'            => null,
                'like'                => null,
                'or_like'            => null,
                'data'  => array(
                    'ids_tipe_ujian'  => $this->input->post('ids_tipe_ujian'),
                    'nama_setting'  => $this->input->post('nama_setting'),
                    'setting'  => $this->input->post('setting'),
                    'updated_by'  => $this->jwt->ids_user,
                ),
            );
            $fb = $this->Tbp_setting->update($rules);
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

    function Delete($id)
    {
        $response = null;
        $hak_akses = $this->master->read('hak-akses/?ids_level=' . $this->jwt->ids_level . '&ids_modul=63&aksi_hak_akses=delete');
        if ($hak_akses->code == 200) {
            $where = array(
                'where'                => array(
                    'idp_setting' => $id
                ),
                'or_where'            => null,
                'like'                => null,
                'or_like'            => null,
            );
            if ($this->Tbp_setting->delete($where)) {
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
        $hak_akses = $this->master->read('hak-akses/?ids_level=' . $this->jwt->ids_level . '&ids_modul=63&aksi_hak_akses=single');
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
                $tbsProgram = $this->Viewp_setting->read($rules);
                if ($tbsProgram->num_rows() > 0) {
                    $response = array(
                        'status' => 200,
                        'data' => $tbsProgram->result()
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
                        'idp_setting' => $id,
                    ),
                    'or_where'            => null,
                    'like'                => null,
                    'or_like'            => null,
                    'order'                => null,
                    'limit'                => null,
                    'group_by'            => null,
                );
                $tbsProgram = $this->Viewp_setting->search($rules);
                if ($tbsProgram->num_rows() > 0) {
                    $response = array(
                        'status' => 200,
                        'data' => $tbsProgram->row()
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

    function JsonFormFilter()
    {
        $list = $this->SS_setting_mandiri->get_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $row) {
            $sub_array = array();
            $sub_array[] = ++$no;
            $sub_array[] = "<button type=\"button\" title=\"Edit\" onclick=\"edit_data(" . $row->idp_setting . ")\" class=\"btn btn-xs btn-warning\">
                              <span class=\"tf-icon bx bx-edit bx-xs\"></span> Edit
                          </button>
                          <a href=\"javascript:void(0)\" title=\"Hapus\" class=\"btn btn-xs btn-danger\" onclick=\"delete_data(" . $row->idp_setting . ")\">
                              <span class=\"tf-icon bx bx-trash bx-xs\"></span> Hapus
                          </a>";
            $sub_array[] = $row->program;
            $sub_array[] = $row->tipe_ujian;
            $sub_array[] = $row->nama_setting;
            $sub_array[] = $row->setting;

            $data[] = $sub_array;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->SS_setting_mandiri->count_all(),
            "recordsFiltered" => $this->SS_setting_mandiri->count_filtered(),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }

    private function _validate($method = '')
    {
        $data = array();
        $data['error_string'] = array();
        $data['inputerror'] = array();
        $data['status'] = TRUE;

        if ($this->input->post('nama_setting') == '') {
            $data['inputerror'][] = 'nama_setting';
            $data['error_string'][] = 'Nama setting wajib diisi.';
            $data['status'] = FALSE;
        }

        if ($this->input->post('setting') == '') {
            $data['inputerror'][] = 'setting';
            $data['error_string'][] = 'Setting wajib diisi.';
            $data['status'] = FALSE;
        }

        if ($data['status'] === FALSE) {
            echo json_encode($data);
            exit();
        }
    }
}
