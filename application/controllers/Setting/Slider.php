<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Slider extends CI_Controller
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
        $this->load->model('Settings/Tbs_slider');
        $this->load->model('ServerSide/SS_setting_slider');
    }

    function index()
    {
        $hak_akses = $this->master->read('hak-akses/?ids_level=' . $this->jwt->ids_level . '&ids_modul=147&aksi_hak_akses=read');
        if ($hak_akses->code == 200) {
            $data = array(
                'title'         => 'Slider',
                'content'       => 'Setting/slider/content',
                'css'           => 'Setting/slider/css',
                'javascript'    => 'Setting/slider/javascript',
                'modal'         => 'Setting/slider/modal',
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
        $hak_akses = $this->master->read('hak-akses/?ids_level=' . $this->jwt->ids_level . '&ids_modul=147&aksi_hak_akses=create');
        if ($hak_akses->code == 200) {
            $this->_validate('tambah');
            $config['upload_path'] = './upload/setting/slider/';
            $config['allowed_types'] = 'jpeg|jpg|png|' . strtoupper('jpeg|jpg|png');
            $config['max_size'] = 2048;
            $config['overwrite']     = TRUE;
            $config['file_name']     = 'slider' . time();
            $this->upload->initialize($config);
            if (!$this->upload->do_upload('gambar')) {
                $response = array(
                    'status' => 400,
                    'message' => $this->upload->display_errors()
                );
            } else {
                $file = $this->upload->data();
                $data = array(
                    'gambar'  => $file['file_name'],
                    'konten'  => $this->input->post('konten'),
                    'status'  => $this->input->post('status'),
                    'created_by' => $this->jwt->ids_user,
                    'updated_by' => $this->jwt->ids_user,
                );
                $fb = $this->Tbs_slider->create($data);
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
        $hak_akses = $this->master->read('hak-akses/?ids_level=' . $this->jwt->ids_level . '&ids_modul=147&aksi_hak_akses=update');
        if ($hak_akses->code == 200) {
            $this->_validate();
            if (isset($_FILES["gambar"])) {
                $config['upload_path'] = './upload/setting/slider/';
                $config['allowed_types'] = 'jpeg|jpg|png|' . strtoupper('jpeg|jpg|png');
                $config['max_size'] = 2048;
                $config['overwrite']     = TRUE;
                $config['file_name']     = 'slider' . time();
                $this->upload->initialize($config);
                if (!$this->upload->do_upload('gambar')) {
                    $response = array(
                        'status' => 400,
                        'message' => $this->upload->display_errors()
                    );
                } else {
                    $file = $this->upload->data();
                    $rules = array(
                        'where' => array(
                            'ids_slider'  => $this->input->post('ids_slider'),
                        ),
                        'or_where' => null,
                        'like' => null,
                        'or_like' => null,
                        'data' => array(
                            'gambar'  => $file['file_name'],
                            'konten'  => $this->input->post('konten'),
                            'status'  => $this->input->post('status'),
                            'updated_by'  => $this->jwt->ids_user,
                        ),
                    );
                    $fb = $this->Tbs_slider->update($rules);
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
                }
            } else {
                $rules = array(
                    'where' => array(
                        'ids_slider'  => $this->input->post('ids_slider'),
                    ),
                    'or_where' => null,
                    'like' => null,
                    'or_like' => null,
                    'data' => array(
                        'konten'  => $this->input->post('konten'),
                        'status'  => $this->input->post('status'),
                        'updated_by'  => $this->jwt->ids_user,
                    ),
                );
                $fb = $this->Tbs_slider->update($rules);
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
        $hak_akses = $this->master->read('hak-akses/?ids_level=' . $this->jwt->ids_level . '&ids_modul=147&aksi_hak_akses=delete');
        if ($hak_akses->code == 200) {
            $where = array(
                'where'                => array(
                    'ids_slider' => $id
                ),
                'or_where'            => null,
                'like'                => null,
                'or_like'            => null,
            );
            if ($this->Tbs_slider->delete($where)) {
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
        $hak_akses = $this->master->read('hak-akses/?ids_level=' . $this->jwt->ids_level . '&ids_modul=147&aksi_hak_akses=single');
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
                $tbsSlider = $this->Tbs_slider->read($rules);
                if ($tbsSlider->num_rows() > 0) {
                    $response = array(
                        'status' => 200,
                        'data' => $tbsSlider->result()
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
                        'ids_slider' => $id,
                    ),
                    'or_where'            => null,
                    'like'                => null,
                    'or_like'            => null,
                    'order'                => null,
                    'limit'                => null,
                    'group_by'            => null,
                );
                $tbsSlider = $this->Tbs_slider->search($rules);
                if ($tbsSlider->num_rows() > 0) {
                    $response = array(
                        'status' => 200,
                        'data' => $tbsSlider->row()
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
        $list = $this->SS_setting_slider->get_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $row) {
            $sub_array = array();
            $sub_array[] = ++$no;
            $sub_array[] = "
                <button type=\"button\" title=\"Edit\" onclick=\"edit_data(" . $row->ids_slider . ")\" class=\"btn btn-xs btn-warning\">
                    <span class=\"tf-icon bx bx-edit bx-xs\"></span> Edit
                </button>
                <a href=\"javascript:void(0)\" title=\"Hapus\" class=\"btn btn-xs btn-danger\" onclick=\"delete_data(" . $row->ids_slider . ")\">
                    <span class=\"tf-icon bx bx-trash bx-xs\"></span> Hapus
                </a>";
            $sub_array[] = "<img src='" . base_url('upload/setting/slider/' . $row->gambar) . "' class='img-fluid'>";
            $sub_array[] = $row->konten;
            $sub_array[] = ($row->status == 'YA') ? '<div class="badge bg-success">Tampilkan</div>' : '<div class="badge bg-danger">Sembunyikan</div>';

            $data[] = $sub_array;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->SS_setting_slider->count_all(),
            "recordsFiltered" => $this->SS_setting_slider->count_filtered(),
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

        if ($this->input->post('konten') == '') {
            $data['inputerror'][] = 'konten';
            $data['error_string'][] = 'Konten wajib diisi.';
            $data['status'] = FALSE;
        }

        if ($method == 'tambah') {
            if (!isset($_FILES["gambar"])) {
                $data['inputerror'][] = 'gambar';
                $data['error_string'][] = 'Gambar wajib diisi.';
                $data['status'] = FALSE;
            } else {
                $allowed = array('png', 'jpg', 'jpeg');
                $filename = $_FILES['gambar']['name'];
                $ext = pathinfo($filename, PATHINFO_EXTENSION);
                if (!in_array($ext, $allowed)) {
                    $data['inputerror'][] = 'gambar';
                    $data['error_string'][] = 'Format gambar ' . implode(',', $allowed);
                    $data['status'] = FALSE;
                }
            }
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
