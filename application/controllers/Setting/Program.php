<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Program extends CI_Controller
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
        $this->load->model('Settings/Tbs_program');
        $this->load->model('ServerSide/SS_program');
    }

    function index()
    {
        $hak_akses = $this->master->read('hak-akses/?ids_level=' . $this->jwt->ids_level . '&ids_modul=46&aksi_hak_akses=read');
        if ($hak_akses->code == 200) {
            $data = array(
                'title'         => 'Program',
                'content'       => 'Setting/program/content',
                'css'           => 'Setting/program/css',
                'javascript'    => 'Setting/program/javascript',
                'modal'         => 'Setting/program/modal',
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
        $hak_akses = $this->master->read('hak-akses/?ids_level=' . $this->jwt->ids_level . '&ids_modul=46&aksi_hak_akses=create');
        if ($hak_akses->code == 200) {
            $this->_validate('tambah');
            $data = array(
                'kode_program'  => $this->input->post('kode_program'),
                'program'  => $this->input->post('program'),
                'jenjang'  => $this->input->post('jenjang'),
                'kelas'  => $this->input->post('kelas'),
                'status'  => $this->input->post('status'),
                'created_by' => $this->jwt->ids_user,
                'updated_by' => $this->jwt->ids_user,
            );
            $fb = $this->Tbs_program->create($data);
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
        $hak_akses = $this->master->read('hak-akses/?ids_level=' . $this->jwt->ids_level . '&ids_modul=46&aksi_hak_akses=update');
        if ($hak_akses->code == 200) {
            $this->_validate();
            $rules = array(
                'where' => array(
                    'ids_program'  => $this->input->post('ids_program'),
                ),
                'or_where' => null,
                'like' => null,
                'or_like' => null,
                'data' => array(
                    'kode_program'  => $this->input->post('kode_program'),
                    'program'  => $this->input->post('program'),
                    'jenjang'  => $this->input->post('jenjang'),
                    'kelas'  => $this->input->post('kelas'),
                    'status'  => $this->input->post('status'),
                    'updated_by'  => $this->jwt->ids_user,
                ),
            );
            $fb = $this->Tbs_program->update($rules);
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
        $hak_akses = $this->master->read('hak-akses/?ids_level=' . $this->jwt->ids_level . '&ids_modul=46&aksi_hak_akses=delete');
        if ($hak_akses->code == 200) {
            $where = array(
                'where'                => array(
                    'ids_program' => $id
                ),
                'or_where'            => null,
                'like'                => null,
                'or_like'            => null,
            );
            if ($this->Tbs_program->delete($where)) {
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

    function Get()
    {
        $response = null;
        $hak_akses = $this->master->read('hak-akses/?ids_level=' . $this->jwt->ids_level . '&ids_modul=46&aksi_hak_akses=single');
        if ($hak_akses->code == 200) {
            if (!empty($this->input->get('jenjang'))) {
                $where['jenjang'] = $this->input->get('jenjang');
            }
            if (!empty($this->input->get('ids_program'))) {
                $where['ids_program'] = $this->input->get('ids_program');
            }
            if (empty($where)) {
                $rules = array(
                    'database'    => null, //Database master
                    'select'    => null,
                    'order'     => null,
                    'limit'     => null,
                    'pagging'   => null,
                    'group_by'  => null,
                );
                $tbsProgram = $this->Tbs_program->read($rules);
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
                    'where'     => $where,
                    'or_where'            => null,
                    'like'                => null,
                    'or_like'            => null,
                    'order'                => null,
                    'limit'                => null,
                    'group_by'            => null,
                );
                $tbsProgram = $this->Tbs_program->search($rules);
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
        $list = $this->SS_program->get_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $row) {
            $sub_array = array();
            $sub_array[] = ++$no;
            $sub_array[] = "
                <button type=\"button\" title=\"Edit\" onclick=\"edit_data(" . $row->ids_program . ")\" class=\"btn btn-xs btn-warning\">
                    <span class=\"tf-icon bx bx-edit bx-xs\"></span> Edit
                </button>
                <a href=\"javascript:void(0)\" title=\"Hapus\" class=\"btn btn-xs btn-danger\" onclick=\"delete_data(" . $row->ids_program . ")\">
                    <span class=\"tf-icon bx bx-trash bx-xs\"></span> Hapus
                </a>";
            $sub_array[] = $row->kode_program;
            $sub_array[] = $row->program;
            $sub_array[] = $row->jenjang;
            $sub_array[] = $row->kelas;
            $sub_array[] = ($row->status == 'YA') ? '<div class="badge bg-success">Tampilkan</div>' : '<div class="badge bg-danger">Sembunyikan</div>';

            $data[] = $sub_array;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->SS_program->count_all(),
            "recordsFiltered" => $this->SS_program->count_filtered(),
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

        if ($this->input->post('kode_program') == '') {
            $data['inputerror'][] = 'kode_program';
            $data['error_string'][] = 'Kode program wajib diisi.';
            $data['status'] = FALSE;
        }

        if ($this->input->post('program') == '') {
            $data['inputerror'][] = 'program';
            $data['error_string'][] = 'Program wajib diisi.';
            $data['status'] = FALSE;
        }

        if ($this->input->post('jenjang_select') == '') {
            $data['inputerror'][] = 'jenjang_select';
            $data['error_string'][] = 'Jenjang wajib diisi.';
            $data['status'] = FALSE;
        }

        if ($this->input->post('kelas_select') == '') {
            $data['inputerror'][] = 'kelas_select';
            $data['error_string'][] = 'Kelas wajib diisi.';
            $data['status'] = FALSE;
        }

        if ($this->input->post('status') == '') {
            $data['inputerror'][] = 'status';
            $data['error_string'][] = 'Status wajib diisi.';
            $data['status'] = FALSE;
        }

        if ($data['status'] === FALSE) {
            echo json_encode($data);
            exit();
        }
    }
}
