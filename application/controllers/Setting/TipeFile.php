<?php
defined('BASEPATH') or exit('No direct script access allowed');
class TipeFile extends CI_Controller
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
        $this->load->model('Settings/Views_jalur_masuk');
        $this->load->model('Settings/Tbs_tipe_ujian');
        $this->load->model('Settings/Tbs_tipe_file');
        $this->load->model('ServerSide/SS_tipe_file');
    }

    function index()
    {
        $hak_akses = $this->master->read('hak-akses/?ids_level=' . $this->jwt->ids_level . '&ids_modul=50&aksi_hak_akses=read');
        if ($hak_akses->code == 200) {
            $data = array(
                'title'         => 'Tipe File',
                'content'       => 'Setting/tipe_file/content',
                'css'           => 'Setting/tipe_file/css',
                'javascript'    => 'Setting/tipe_file/javascript',
                'modal'         => 'Setting/tipe_file/modal',
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
        $hak_akses = $this->master->read('hak-akses/?ids_level=' . $this->jwt->ids_level . '&ids_modul=50&aksi_hak_akses=create');
        if ($hak_akses->code == 200) {
            $this->_validate('tambah');
            if ($this->input->post('setting') == 'PEMBERKASAN' || $this->input->post('setting') == 'DAFTAR_UKT') {
                $ids = $this->input->post('ids_jalur_masuk');
                $ids = is_array($ids) ? $ids : [$ids];
                $data = array(
                    'setting'  => $this->input->post('setting'),
                    'nama_file'  => $this->input->post('nama_file'),
                    'extensi'  => $this->input->post('extensi'),
                    'max_size'  => $this->input->post('max_size'),
                    'upload'  => $this->input->post('upload'),
                    'status'  => $this->input->post('status'),
                    'jalur_masuk'  => implode(',', $ids),
                    'created_by' => $this->jwt->ids_user,
                    'updated_by' => $this->jwt->ids_user,
                );
            } else {
                $ids = $this->input->post('ids_tipe_ujian');
                $ids = is_array($ids) ? $ids : [$ids];
                $data = array(
                    'setting'  => $this->input->post('setting'),
                    'nama_file'  => $this->input->post('nama_file'),
                    'extensi'  => $this->input->post('extensi'),
                    'max_size'  => $this->input->post('max_size'),
                    'upload'  => $this->input->post('upload'),
                    'status'  => $this->input->post('status'),
                    'tipe_ujian'  => implode(',', $ids),
                    'created_by' => $this->jwt->ids_user,
                    'updated_by' => $this->jwt->ids_user,
                );
            }
            $fb = $this->Tbs_tipe_file->create($data);
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
        $hak_akses = $this->master->read('hak-akses/?ids_level=' . $this->jwt->ids_level . '&ids_modul=50&aksi_hak_akses=update');
        if ($hak_akses->code == 200) {
            $this->_validate();
            if ($this->input->post('setting') == 'PEMBERKASAN' || $this->input->post('setting') == 'DAFTAR_UKT') {
                $ids = $this->input->post('ids_jalur_masuk');
                $ids = is_array($ids) ? $ids : [$ids];
                $rules = array(
                    'where' => array(
                        'ids_tipe_file'  => $this->input->post('ids_tipe_file'),
                    ),
                    'or_where'            => null,
                    'like'                => null,
                    'or_like'            => null,
                    'data'  => array(
                        'setting'  => $this->input->post('setting'),
                        'nama_file'  => $this->input->post('nama_file'),
                        'extensi'  => $this->input->post('extensi'),
                        'max_size'  => $this->input->post('max_size'),
                        'upload'  => $this->input->post('upload'),
                        'jalur_masuk'  => implode(',', $ids),
                        'status'  => $this->input->post('status'),
                        'updated_by'  => $this->jwt->ids_user,
                    ),
                );
            } else {
                $ids = $this->input->post('ids_tipe_ujian');
                $ids = is_array($ids) ? $ids : [$ids];
                $rules = array(
                    'where' => array(
                        'ids_tipe_file'  => $this->input->post('ids_tipe_file'),
                    ),
                    'or_where'            => null,
                    'like'                => null,
                    'or_like'            => null,
                    'data'  => array(
                        'setting'  => $this->input->post('setting'),
                        'nama_file'  => $this->input->post('nama_file'),
                        'extensi'  => $this->input->post('extensi'),
                        'max_size'  => $this->input->post('max_size'),
                        'upload'  => $this->input->post('upload'),
                        'tipe_ujian'  => implode(',', $ids),
                        'status'  => $this->input->post('status'),
                        'updated_by'  => $this->jwt->ids_user,
                    ),
                );
            }
            $fb = $this->Tbs_tipe_file->update($rules);
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
        $hak_akses = $this->master->read('hak-akses/?ids_level=' . $this->jwt->ids_level . '&ids_modul=50&aksi_hak_akses=delete');
        if ($hak_akses->code == 200) {
            $where = array(
                'where'                => array(
                    'ids_tipe_file' => $id
                ),
                'or_where'            => null,
                'like'                => null,
                'or_like'            => null,
            );
            if ($this->Tbs_tipe_file->delete($where)) {
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
        $hak_akses = $this->master->read('hak-akses/?ids_level=' . $this->jwt->ids_level . '&ids_modul=50&aksi_hak_akses=single');
        if ($hak_akses->code == 200) {
            if ($id == null) {
                $rules = array(
                    'database'    => null, //Database master
                    'select'    => null,
                    'order'     => null,
                    'limit'     => null,
                    'pagging'   => null,
                );
                $tbsTipeFile = $this->Tbs_tipe_file->read($rules);
                if ($tbsTipeFile->num_rows() > 0) {
                    $response = array(
                        'status' => 200,
                        'data' => $tbsTipeFile->result()
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
                        'ids_tipe_file' => $id,
                    ),
                    'or_where'            => null,
                    'like'                => null,
                    'or_like'            => null,
                    'order'                => null,
                    'limit'                => null,
                    'group_by'            => null,
                );
                $tbsTipeFile = $this->Tbs_tipe_file->search($rules);
                if ($tbsTipeFile->num_rows() > 0) {
                    $response = array(
                        'status' => 200,
                        'data' => $tbsTipeFile->row()
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
        $list = $this->SS_tipe_file->get_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $row) {
            $sub_array = array();
            $sub_array[] = ++$no;
            $sub_array[] = "
                <button type=\"button\" title=\"Edit\" onclick=\"edit_data(" . $row->ids_tipe_file . ")\" class=\"btn btn-xs btn-warning\">
                    <span class=\"tf-icon bx bx-edit bx-xs\"></span> Edit
                </button>
                <a href=\"javascript:void(0)\" title=\"Hapus\" class=\"btn btn-xs btn-danger\" onclick=\"delete_data(" . $row->ids_tipe_file . ")\">
                    <span class=\"tf-icon bx bx-trash bx-xs\"></span> Hapus
                </a>";
            $sub_array[] = $row->nama_file;
            $sub_array[] = $row->setting;
            $sub_array[] = $row->extensi;
            $sub_array[] = $row->max_size;

            $jalur = explode(',', $row->jalur_masuk);
            $jalurs = "";
            if ($jalur[0] != '') {
                $jalurs = "<ul>";
                foreach ($jalur as $a) {
                    $rules = array(
                        'database'    => null, //Database master
                        'select'    => null,
                        'where'     => array(
                            'ids_jalur_masuk' => $a,
                        ),
                        'or_where'            => null,
                        'like'                => null,
                        'or_like'            => null,
                        'order'                => null,
                        'limit'                => null,
                        'group_by'            => null,
                    );
                    $tbsJalurMasuk = $this->Views_jalur_masuk->search($rules)->row();
                    $jalurs .= "<li>";
                    $jalurs .= $tbsJalurMasuk->alias_jalur_masuk;
                    $jalurs .= "</li>";
                }
                $jalurs .= "</ul>";
            }

            $tipe = explode(',', $row->tipe_ujian);
            $tipes = "";
            if ($tipe[0] != '') {
                $tipes = "<ul>";
                foreach ($tipe as $a) {
                    $rules = array(
                        'database'    => null, //Database master
                        'select'    => null,
                        'where'     => array(
                            'ids_tipe_ujian' => $a,
                        ),
                        'or_where'            => null,
                        'like'                => null,
                        'or_like'            => null,
                        'order'                => null,
                        'limit'                => null,
                        'group_by'            => null,
                    );
                    $tbsTipeUjian = $this->Tbs_tipe_ujian->search($rules)->row();
                    $tipes .= "<li>";
                    $tipes .= $tbsTipeUjian->tipe_ujian;
                    $tipes .= "</li>";
                }
                $tipes .= "</ul>";
            }

            $sub_array[] = $jalurs;
            $sub_array[] = $tipes;
            $sub_array[] = ($row->upload == 'Wajib') ? '<div class="badge bg-danger">Wajib</div>' : '<div class="badge bg-warning">Opsional</div>';
            $sub_array[] = ($row->status == 'YA') ? '<div class="badge bg-success">Tampilkan</div>' : '<div class="badge bg-danger">Sembunyikan</div>';

            $data[] = $sub_array;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->SS_tipe_file->count_all(),
            "recordsFiltered" => $this->SS_tipe_file->count_filtered(),
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

        if ($this->input->post('nama_file') == '') {
            $data['inputerror'][] = 'nama_file';
            $data['error_string'][] = 'Nama file wajib diisi.';
            $data['status'] = FALSE;
        }

        if ($this->input->post('setting') == '') {
            $data['inputerror'][] = 'setting';
            $data['error_string'][] = 'Setting wajib diisi.';
            $data['status'] = FALSE;
        }

        if ($this->input->post('extensi') == '') {
            $data['inputerror'][] = 'extensi';
            $data['error_string'][] = 'Ekstensi wajib diisi.';
            $data['status'] = FALSE;
        }

        if ($this->input->post('max_size') == '') {
            $data['inputerror'][] = 'max_size';
            $data['error_string'][] = 'Max size wajib diisi.';
            $data['status'] = FALSE;
        }

        if ($this->input->post('upload') == '') {
            $data['inputerror'][] = 'upload';
            $data['error_string'][] = 'Upload wajib diisi.';
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
