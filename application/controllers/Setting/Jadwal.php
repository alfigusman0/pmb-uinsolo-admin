<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Jadwal extends CI_Controller
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
        $this->load->model('Mandiri/Viewp_jadwal');
        $this->load->model('Mandiri/Viewp_file');
        $this->load->model('Settings/Tbs_jadwal');
        $this->load->model('Settings/Tbs_program');
        $this->load->model('Settings/Tbs_tipe_ujian');
        $this->load->model('Settings/Views_jadwal');
        $this->load->model('ServerSide/SS_jadwal');
    }

    function index()
    {
        $hak_akses = $this->master->read('hak-akses/?ids_level=' . $this->jwt->ids_level . '&ids_modul=49&aksi_hak_akses=read');
        if ($hak_akses->code == 200) {
            $rules[1] = array(
                'database'          => null, //Default database master
                'select'            => null, // not null
                'order'                => null,
                'group_by'            => null,
                'limit'            => null,
            );
            $rules[2] = array(
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
                'title'         => 'Jadwal',
                'content'       => 'Setting/jadwal/read/content',
                'css'           => 'Setting/jadwal/read/css',
                'javascript'    => 'Setting/jadwal/read/javascript',
                'modal'         => 'Setting/jadwal/read/modal',

                'tbsProgram'         => $this->Tbs_program->search($rules[2])->result(),
                'tbsTipeUjian'         => $this->Tbs_tipe_ujian->read($rules[1])->result(),
            );
            $this->load->view('index', $data);
        } else {
            $this->session->set_flashdata('message', 'Hak akses ditolak.');
            $this->session->set_flashdata('type_message', 'danger');
            redirect('dashboard/');
        }
    }

    function Detail($id)
    {
        $hak_akses = $this->master->read('hak-akses/?ids_level=' . $this->jwt->ids_level . '&ids_modul=49&aksi_hak_akses=read');
        if ($hak_akses->code == 200) {
            $rules[1] = array(
                'database'          => null, //Default database master
                'select'            => null, // not null
                'where'                => array('ids_jadwal' => $id),
                'or_where'            => null,
                'like'                => null,
                'or_like'            => null,
                'order'                => null,
                'group_by'            => null,
                'limit'            => null,
            );
            $viewpJadwal = $this->Viewp_jadwal->search($rules[1]);
            if ($viewpJadwal->num_rows() > 0) {
                foreach ($viewpJadwal->result() as $a) {
                    $rules[2] = array(
                        'database'          => null, //Default database master
                        'select'            => null, // not null
                        'where'                => array('idp_formulir' => $a->idp_formulir, 'ids_tipe_file' => 14),
                        'or_where'            => null,
                        'like'                => null,
                        'or_like'            => null,
                        'order'                => null,
                        'group_by'            => null,
                        'limit'            => null,
                    );
                    $viewpFile[$a->idp_formulir] = $this->Viewp_file->search($rules[2])->row();
                }
            }

            $data = array(
                'title'         => 'Jadwal',
                'content'       => 'Setting/jadwal/detail/content',
                'css'           => 'Setting/jadwal/detail/css',
                'javascript'    => 'Setting/jadwal/detail/javascript',
                'modal'         => 'Setting/jadwal/detail/modal',

                'viewpJadwal'         => $viewpJadwal,
                'viewpFile'         => ($viewpJadwal->num_rows() > 0) ? $viewpFile : 'null',
                'viewsJadwal'         => $this->Views_jadwal->search($rules[1])->row()
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
        $hak_akses = $this->master->read('hak-akses/?ids_level=' . $this->jwt->ids_level . '&ids_modul=49&aksi_hak_akses=create');
        if ($hak_akses->code == 200) {
            $this->_validate('tambah');
            $data = array(
                'ids_tipe_ujian'  => $this->input->post('ids_tipe_ujian'),
                'tanggal'  => $this->input->post('tanggal'),
                'jam_awal'  => $this->input->post('jam_awal'),
                'jam_akhir'  => $this->input->post('jam_akhir'),
                'ids_ruangan'  => $this->input->post('ids_ruangan'),
                'quota'  => $this->input->post('quota'),
                'status'  => $this->input->post('status'),
                'created_by' => $this->jwt->ids_user,
                'updated_by' => $this->jwt->ids_user,
            );
            $fb = $this->Tbs_jadwal->create($data);
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
        $hak_akses = $this->master->read('hak-akses/?ids_level=' . $this->jwt->ids_level . '&ids_modul=49&aksi_hak_akses=update');
        if ($hak_akses->code == 200) {
            $this->_validate();
            $rules = array(
                'where' => array(
                    'ids_jadwal'  => $this->input->post('ids_jadwal'),
                ),
                'or_where'            => null,
                'like'                => null,
                'or_like'            => null,
                'data'  => array(
                    'ids_tipe_ujian'  => $this->input->post('ids_tipe_ujian'),
                    'tanggal'  => $this->input->post('tanggal'),
                    'jam_awal'  => $this->input->post('jam_awal'),
                    'jam_akhir'  => $this->input->post('jam_akhir'),
                    'ids_ruangan'  => $this->input->post('ids_ruangan'),
                    'quota'  => $this->input->post('quota'),
                    'status'  => $this->input->post('status'),
                    'updated_by'  => $this->jwt->ids_user,
                ),
            );
            $fb = $this->Tbs_jadwal->update($rules);
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
        $hak_akses = $this->master->read('hak-akses/?ids_level=' . $this->jwt->ids_level . '&ids_modul=49&aksi_hak_akses=delete');
        if ($hak_akses->code == 200) {
            $where = array(
                'where'                => array(
                    'ids_jadwal' => $id
                ),
                'or_where'            => null,
                'like'                => null,
                'or_like'            => null,
            );
            if ($this->Tbs_jadwal->delete($where)) {
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
        $hak_akses = $this->master->read('hak-akses/?ids_level=' . $this->jwt->ids_level . '&ids_modul=49&aksi_hak_akses=single');
        if ($hak_akses->code == 200) {
            if ($id == null) {
                $rules = array(
                    'database'    => null, //Database master
                    'select'    => null,
                    'order'     => null,
                    'limit'     => null,
                    'pagging'   => null,
                );
                $viewsJadwal = $this->Views_jadwal->read($rules);
                if ($viewsJadwal->num_rows() > 0) {
                    $response = array(
                        'status' => 200,
                        'data' => $viewsJadwal->result()
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
                        'ids_jadwal' => $id,
                    ),
                    'or_where'            => null,
                    'like'                => null,
                    'or_like'            => null,
                    'order'                => null,
                    'limit'                => null,
                    'group_by'            => null,
                );
                $viewsJadwal = $this->Views_jadwal->search($rules);
                if ($viewsJadwal->num_rows() > 0) {
                    $response = array(
                        'status' => 200,
                        'data' => $viewsJadwal->row()
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

    function Get2($id = null)
    {
        $response = null;
        $hak_akses = $this->master->read('hak-akses/?ids_level=' . $this->jwt->ids_level . '&ids_modul=49&aksi_hak_akses=single');
        if ($hak_akses->code == 200) {
            $where = array();
            $select = null;
            if (!empty($this->input->get('ids_jadwal'))) {
                $where['ids_jadwal'] = $this->input->get('ids_jadwal');
            }
            if (!empty($this->input->get('ids_tipe_ujian'))) {
                $where['ids_tipe_ujian'] = $this->input->get('ids_tipe_ujian');
            }
            if (!empty($this->input->get('tanggal'))) {
                $where['tanggal'] = $this->input->get('tanggal');
            }
            if (!empty($this->input->get('jam_awal'))) {
                $where['jam_awal'] = $this->input->get('jam_awal');
            }
            if (!empty($this->input->get('jam_akhir'))) {
                $where['jam_akhir'] = $this->input->get('jam_akhir');
            }
            if (!empty($this->input->get('ids_area'))) {
                $where['ids_area'] = $this->input->get('ids_area');
            }
            if (!empty($this->input->get('ids_gedung'))) {
                $where['ids_gedung'] = $this->input->get('ids_gedung');
            }
            if (!empty($this->input->get('ids_ruangan'))) {
                $where['ids_ruangan'] = $this->input->get('ids_ruangan');
            }
            if (!empty($this->input->get('status'))) {
                $where['status'] = $this->input->get('status');
            }
            if (!empty($this->input->get('select'))) {
                $select = $this->input->get('select');
            }
            $rules = array(
                'database'    => null, //Database master
                'select'    => $select,
                'where'     => $where,
                'or_where'            => null,
                'like'                => null,
                'or_like'            => null,
                'order'                => null,
                'limit'                => null,
                'group_by'            => null,
            );
            $viewsJadwal = $this->Views_jadwal->search($rules);
            if ($viewsJadwal->num_rows() > 0) {
                $response = array(
                    'status' => 200,
                    'data' => $viewsJadwal->result()
                );
            } else {
                $response = array(
                    'status' => 204,
                    'data' => null
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
        $list = $this->SS_jadwal->get_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $row) {
            $sub_array = array();
            $sub_array[] = ++$no;
            $sub_array[] = "
                            <a href=\"" . base_url('setting/jadwal/detail/' . $row->ids_jadwal) . "\" target=\"_blank\" class=\"btn btn-xs btn-primary\">
                              <span class=\"tf-icon bx bx-detail bx-xs\"></span> Detail
                            </a>
                                <button type=\"button\" title=\"Edit\" onclick=\"edit_data(" . $row->ids_jadwal . ")\" class=\"btn btn-xs btn-warning\">
                                <span class=\"tf-icon bx bx-edit bx-xs\"></span> Edit
                            </button>
                            <a href=\"javascript:void(0)\" title=\"Hapus\" class=\"btn btn-xs btn-danger\" onclick=\"delete_data(" . $row->ids_jadwal . ")\">
                                <span class=\"tf-icon bx bx-trash bx-xs\"></span> Hapus
                            </a>";
            $sub_array[] = $row->tipe_ujian;
            $sub_array[] = $row->tanggal;
            $sub_array[] = $row->jam_awal . " - " . $row->jam_akhir;
            $sub_array[] = $row->area;
            $sub_array[] = $row->gedung;
            $sub_array[] = $row->ruangan;
            $sub_array[] = $row->quota;
            $sub_array[] = ($row->status == 'YA') ? '<div class="badge bg-success">Tampilkan</div>' : '<div class="badge bg-danger">Sembunyikan</div>';

            $data[] = $sub_array;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->SS_jadwal->count_all(),
            "recordsFiltered" => $this->SS_jadwal->count_filtered(),
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

        if ($this->input->post('ids_tipe_ujian') == '') {
            $data['inputerror'][] = 'ids_tipe_ujian';
            $data['error_string'][] = 'Tipe Ujian wajib diisi.';
            $data['status'] = FALSE;
        }

        if ($this->input->post('tanggal') == '') {
            $data['inputerror'][] = 'tanggal';
            $data['error_string'][] = 'Tanggal wajib diisi.';
            $data['status'] = FALSE;
        }

        if ($this->input->post('jam_awal') == '') {
            $data['inputerror'][] = 'jam_awal';
            $data['error_string'][] = 'Jam awal wajib diisi.';
            $data['status'] = FALSE;
        }

        if ($this->input->post('jam_akhir') == '') {
            $data['inputerror'][] = 'jam_akhir';
            $data['error_string'][] = 'Jam akhir wajib diisi.';
            $data['status'] = FALSE;
        }

        if ($this->input->post('ids_ruangan') == '') {
            $data['inputerror'][] = 'ids_ruangan';
            $data['error_string'][] = 'Ruangan wajib diisi.';
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
