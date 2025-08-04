<?php
defined('BASEPATH') or exit('No direct script access allowed');
class TipeUjian extends CI_Controller
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
        $this->load->model('Settings/Tbs_tipe_ujian');
        $this->load->model('Settings/Tbs_program');
        $this->load->model('Settings/Views_tipe_ujian');
        $this->load->model('ServerSide/SS_tipe_ujian');
    }

    function index()
    {
        $hak_akses = $this->master->read('hak-akses/?ids_level=' . $this->jwt->ids_level . '&ids_modul=51&aksi_hak_akses=read');
        if ($hak_akses->code == 200) {
            $rules[1] = array(
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
                'title'         => 'Tipe Ujian',
                'content'       => 'Setting/tipe_ujian/content',
                'css'           => 'Setting/tipe_ujian/css',
                'javascript'    => 'Setting/tipe_ujian/javascript',
                'modal'         => 'Setting/tipe_ujian/modal',
                'tbsProgram'         => $this->Tbs_program->search($rules[1])->result(),
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
        $hak_akses = $this->master->read('hak-akses/?ids_level=' . $this->jwt->ids_level . '&ids_modul=51&aksi_hak_akses=create');
        if ($hak_akses->code == 200) {
            $this->_validate('tambah');
            $data = array(
                'ids_program'  => $this->input->post('ids_program'),
                'tipe_ujian'  => $this->input->post('tipe_ujian'),
                'kode'  => $this->input->post('kode'),
                'status_jadwal'  => $this->input->post('status_jadwal'),
                'quota'  => $this->input->post('quota'),
                'status'  => $this->input->post('status'),
                'created_by' => $this->jwt->ids_user,
                'updated_by' => $this->jwt->ids_user,
            );
            $fb = $this->Tbs_tipe_ujian->create($data);
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
        $hak_akses = $this->master->read('hak-akses/?ids_level=' . $this->jwt->ids_level . '&ids_modul=51&aksi_hak_akses=update');
        if ($hak_akses->code == 200) {
            $this->_validate();
            $rules = array(
                'where' => array(
                    'ids_tipe_ujian'  => $this->input->post('ids_tipe_ujian'),
                ),
                'or_where'            => null,
                'like'                => null,
                'or_like'            => null,
                'data'  => array(
                    'ids_program'  => $this->input->post('ids_program'),
                    'tipe_ujian'  => $this->input->post('tipe_ujian'),
                    'kode'  => $this->input->post('kode'),
                    'status_jadwal'  => $this->input->post('status_jadwal'),
                    'quota'  => $this->input->post('quota'),
                    'status'  => $this->input->post('status'),
                    'updated_by'  => $this->jwt->ids_user,
                ),
            );
            $fb = $this->Tbs_tipe_ujian->update($rules);
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
        $hak_akses = $this->master->read('hak-akses/?ids_level=' . $this->jwt->ids_level . '&ids_modul=51&aksi_hak_akses=delete');
        if ($hak_akses->code == 200) {
            $where = array(
                'where'                => array(
                    'ids_tipe_ujian' => $id
                ),
                'or_where'            => null,
                'like'                => null,
                'or_like'            => null,
            );
            if ($this->Tbs_tipe_ujian->delete($where)) {
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
        $hak_akses = $this->master->read('hak-akses/?ids_level=' . $this->jwt->ids_level . '&ids_modul=51&aksi_hak_akses=single');
        if ($hak_akses->code == 200) {
            $where = array();
            if (!empty($this->input->get('ids_program'))) {
                $where['ids_program'] = $this->input->get('ids_program');
            }
            if (!empty($this->input->get('jenjang'))) {
                $where['jenjang'] = $this->input->get('jenjang');
            }
            if (!empty($this->input->get('status_jadwal'))) {
                $where['status_jadwal'] = $this->input->get('status_jadwal');
            }
            if (!empty($this->input->get('status'))) {
                $where['status'] = $this->input->get('status');
            }
            if ($id == null) {
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
                $tbsTipeUjian = $this->Views_tipe_ujian->search($rules);
                if ($tbsTipeUjian->num_rows() > 0) {
                    $response = array(
                        'status' => 200,
                        'data' => $tbsTipeUjian->result()
                    );
                } else {
                    $response = array(
                        'status' => 204,
                        'data' => null
                    );
                }
            } else {
                $where['ids_tipe_ujian'] = $id;
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
                $tbsTipeUjian = $this->Views_tipe_ujian->search($rules);
                if ($tbsTipeUjian->num_rows() > 0) {
                    $response = array(
                        'status' => 200,
                        'data' => $tbsTipeUjian->row()
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
        $list = $this->SS_tipe_ujian->get_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $row) {
            $sub_array = array();
            $sub_array[] = ++$no;
            $sub_array[] = "
                <button type=\"button\" title=\"Edit\" onclick=\"edit_data(" . $row->ids_tipe_ujian . ")\" class=\"btn btn-xs btn-warning\">
                    <span class=\"tf-icon bx bx-edit bx-xs\"></span> Edit
                </button>
                <a href=\"javascript:void(0)\" title=\"Hapus\" class=\"btn btn-xs btn-danger\" onclick=\"delete_data(" . $row->ids_tipe_ujian . ")\">
                    <span class=\"tf-icon bx bx-trash bx-xs\"></span> Hapus
                </a>";
            $sub_array[] = $row->tipe_ujian;
            $sub_array[] = $row->program;
            $sub_array[] = $row->kode;
            $sub_array[] = "";
            // $sub_array[] = $row->status_jadwal;
            $sub_array[] = $row->quota;
            $sub_array[] = ($row->status == 'YA') ? '<div class="badge bg-success">Tampilkan</div>' : '<div class="badge bg-danger">Sembunyikan</div>';

            $data[] = $sub_array;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->SS_tipe_ujian->count_all(),
            "recordsFiltered" => $this->SS_tipe_ujian->count_filtered(),
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

        if ($this->input->post('ids_program') == '') {
            $data['inputerror'][] = 'ids_program';
            $data['error_string'][] = 'Program wajib diisi.';
            $data['status'] = FALSE;
        }

        if ($this->input->post('tipe_ujian') == '') {
            $data['inputerror'][] = 'tipe_ujian';
            $data['error_string'][] = 'Tipe ujian wajib diisi.';
            $data['status'] = FALSE;
        }

        if ($this->input->post('kode') == '') {
            $data['inputerror'][] = 'kode';
            $data['error_string'][] = 'Kode wajib diisi.';
            $data['status'] = FALSE;
        }

        if ($this->input->post('status_jadwal') == '') {
            $data['inputerror'][] = 'status_jadwal';
            $data['error_string'][] = 'status status_jadwal wajib diisi.';
            $data['status'] = FALSE;
        }

        if ($this->input->post('quota') == '') {
            $data['inputerror'][] = 'quota';
            $data['error_string'][] = 'Quota wajib diisi.';
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
