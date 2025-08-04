<?php
defined('BASEPATH') or exit('No direct script access allowed');
class BobotJurusan extends CI_Controller
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
        $this->load->model('Settings/Tbs_bobot_jurusan');
        $this->load->model('Settings/Views_bobot_jurusan');
        $this->load->model('ServerSide/SS_bobot_jurusan');
    }

    function index()
    {
        $hak_akses = $this->master->read('hak-akses/?ids_level=' . $this->jwt->ids_level . '&ids_modul=59&aksi_hak_akses=read');
        if ($hak_akses->code == 200) {
            $jurusan = $this->master->read('jurusan/?status=YA&limit=1000');
            $data = array(
                'title'         => 'Bobot Jurusan',
                'content'       => 'Setting/bobot_jurusan/content',
                'css'           => 'Setting/bobot_jurusan/css',
                'javascript'    => 'Setting/bobot_jurusan/javascript',
                'modal'         => 'Setting/bobot_jurusan/modal',
                'jurusan'         => $jurusan,
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
        $hak_akses = $this->master->read('hak-akses/?ids_level=' . $this->jwt->ids_level . '&ids_modul=59&aksi_hak_akses=create');
        if ($hak_akses->code == 200) {
            $this->_validate('tambah');
            $data = array(
                'kode_jurusan'  => $this->input->post('kode_jurusan'),
                'tpa'  => $this->input->post('tpa'),
                'ips'  => $this->input->post('ips'),
                'ipa'  => $this->input->post('ipa'),
                'btq'  => $this->input->post('btq'),
                'tkd'  => $this->input->post('tkd'),
                'keislaman'  => $this->input->post('keislaman'),
                'bhs_arab'  => $this->input->post('bhs_arab'),
                'bhs_indonesia'  => $this->input->post('bhs_indonesia'),
                'pembagi'  => $this->input->post('pembagi'),
                'created_by' => $this->jwt->ids_user,
                'updated_by' => $this->jwt->ids_user,
            );
            $fb = $this->Tbs_bobot_jurusan->create($data);
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
        $hak_akses = $this->master->read('hak-akses/?ids_level=' . $this->jwt->ids_level . '&ids_modul=59&aksi_hak_akses=update');
        if ($hak_akses->code == 200) {
            $this->_validate();
            $rules = array(
                'where' => array(
                    'ids_bobot_jurusan'  => $this->input->post('ids_bobot_jurusan'),
                ),
                'or_where'            => null,
                'like'                => null,
                'or_like'            => null,
                'data'  => array(
                    'tpa'  => $this->input->post('tpa'),
                    'ips'  => $this->input->post('ips'),
                    'ipa'  => $this->input->post('ipa'),
                    'btq'  => $this->input->post('btq'),
                    'tkd'  => $this->input->post('tkd'),
                    'keislaman'  => $this->input->post('keislaman'),
                    'bhs_arab'  => $this->input->post('bhs_arab'),
                    'bhs_indonesia'  => $this->input->post('bhs_indonesia'),
                    'pembagi'  => $this->input->post('pembagi'),
                    'updated_by'  => $this->jwt->ids_user,
                ),
            );
            $fb = $this->Tbs_bobot_jurusan->update($rules);
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
        $hak_akses = $this->master->read('hak-akses/?ids_level=' . $this->jwt->ids_level . '&ids_modul=59&aksi_hak_akses=delete');
        if ($hak_akses->code == 200) {
            $where = array(
                'where'                => array(
                    'ids_bobot_jurusan' => $id
                ),
                'or_where'            => null,
                'like'                => null,
                'or_like'            => null,
            );
            if ($this->Tbs_bobot_jurusan->delete($where)) {
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
        $hak_akses = $this->master->read('hak-akses/?ids_level=' . $this->jwt->ids_level . '&ids_modul=59&aksi_hak_akses=single');
        if ($hak_akses->code == 200) {
            $where = array();
            if (!empty($this->input->post('kode_jurusan'))) {
                $where['kode_jurusan'] = $this->input->post('kode_jurusan');
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
                $tbsBobotJurusan = $this->Views_bobot_jurusan->search($rules);
                if ($tbsBobotJurusan->num_rows() > 0) {
                    $response = array(
                        'status' => 200,
                        'data' => $tbsBobotJurusan->result()
                    );
                } else {
                    $response = array(
                        'status' => 204,
                        'data' => null
                    );
                }
            } else {
                $where['ids_bobot_jurusan'] = $id;
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
                $tbsBobotJurusan = $this->Views_bobot_jurusan->search($rules);
                if ($tbsBobotJurusan->num_rows() > 0) {
                    $response = array(
                        'status' => 200,
                        'data' => $tbsBobotJurusan->row()
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
        $list = $this->SS_bobot_jurusan->get_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $row) {
            $sub_array = array();
            $sub_array[] = ++$no;
            $sub_array[] = "
                <button type=\"button\" title=\"Edit\" onclick=\"edit_data(" . $row->ids_bobot_jurusan . ")\" class=\"btn btn-xs btn-warning\">
                    <span class=\"tf-icon bx bx-edit bx-xs\"></span> Edit
                </button>
                <a href=\"javascript:void(0)\" title=\"Hapus\" class=\"btn btn-xs btn-danger\" onclick=\"delete_data(" . $row->ids_bobot_jurusan . ")\">
                    <span class=\"tf-icon bx bx-trash bx-xs\"></span> Hapus
                </a>";
            $sub_array[] = $row->fakultas;
            $sub_array[] = $row->jurusan;
            $sub_array[] = $row->tpa;
            $sub_array[] = $row->ips;
            $sub_array[] = $row->ipa;
            $sub_array[] = $row->btq;
            $sub_array[] = $row->tkd;
            $sub_array[] = $row->keislaman;
            $sub_array[] = $row->bhs_arab;
            $sub_array[] = $row->bhs_inggris;
            $sub_array[] = $row->bhs_indonesia;
            $sub_array[] = $row->pembagi;

            $data[] = $sub_array;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->SS_bobot_jurusan->count_all(),
            "recordsFiltered" => $this->SS_bobot_jurusan->count_filtered(),
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

        if ($this->input->post('kode_jurusan') == '') {
            $data['inputerror'][] = 'kode_jurusan';
            $data['error_string'][] = 'Kode jurusan wajib diisi.';
            $data['status'] = FALSE;
        }

        if ($this->input->post('tpa') == '') {
            $data['inputerror'][] = 'tpa';
            $data['error_string'][] = 'TPA wajib diisi.';
            $data['status'] = FALSE;
        }

        if ($this->input->post('ips') == '') {
            $data['inputerror'][] = 'ips';
            $data['error_string'][] = 'IPS wajib diisi.';
            $data['status'] = FALSE;
        }

        if ($this->input->post('ipa') == '') {
            $data['inputerror'][] = 'ipa';
            $data['error_string'][] = 'IPA wajib diisi.';
            $data['status'] = FALSE;
        }

        if ($this->input->post('btq') == '') {
            $data['inputerror'][] = 'btq';
            $data['error_string'][] = 'BTQ wajib diisi.';
            $data['status'] = FALSE;
        }

        if ($this->input->post('keislaman') == '') {
            $data['inputerror'][] = 'keislaman';
            $data['error_string'][] = 'Keislaman wajib diisi.';
            $data['status'] = FALSE;
        }

        if ($this->input->post('bhs_arab') == '') {
            $data['inputerror'][] = 'bhs_arab';
            $data['error_string'][] = 'Bahasa arab wajib diisi.';
            $data['status'] = FALSE;
        }

        if ($this->input->post('bhs_inggris') == '') {
            $data['inputerror'][] = 'bhs_inggris';
            $data['error_string'][] = 'Bahasa inggris wajib diisi.';
            $data['status'] = FALSE;
        }

        if ($this->input->post('bhs_indonesia') == '') {
            $data['inputerror'][] = 'bhs_indonesia';
            $data['error_string'][] = 'Bahasa indonesia wajib diisi.';
            $data['status'] = FALSE;
        }

        if ($this->input->post('pembagi') == '') {
            $data['inputerror'][] = 'pembagi';
            $data['error_string'][] = 'Pembagi wajib diisi.';
            $data['status'] = FALSE;
        }

        if ($data['status'] === FALSE) {
            echo json_encode($data);
            exit();
        }
    }
}
