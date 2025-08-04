<?php
defined('BASEPATH') or exit('No direct script access allowed');
class SubDayaTampung extends CI_Controller
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
        $this->load->model('Settings/Tbs_sub_daya_tampung');
        $this->load->model('Settings/Views_sub_daya_tampung');
        $this->load->model('ServerSide/SS_sub_daya_tampung');
    }

    function index()
    {
        $hak_akses = $this->master->read('hak-akses/?ids_level=' . $this->jwt->ids_level . '&ids_modul=70&aksi_hak_akses=read');
        if ($hak_akses->code == 200) {
            $jurusan = $this->master->read('jurusan/?status=YA&limit=1000');
            $jalurmasuk = $this->master->read('jalur-masuk/?status=YA&limit=1000');
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
            $tahun = $this->Views_sub_daya_tampung->distinct($rules)->result();
            $data = array(
                'title'         => 'Sub Daya Tampung',
                'content'       => 'Setting/sub_daya_tampung/content',
                'css'           => 'Setting/sub_daya_tampung/css',
                'javascript'    => 'Setting/sub_daya_tampung/javascript',
                'modal'         => 'Setting/sub_daya_tampung/modal',
                'jurusan'         => $jurusan,
                'jalurmasuk'         => $jalurmasuk,
                'tahun'         => $tahun,
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
        $hak_akses = $this->master->read('hak-akses/?ids_level=' . $this->jwt->ids_level . '&ids_modul=70&aksi_hak_akses=create');
        if ($hak_akses->code == 200) {
            $this->_validate('tambah');
            $data = array(
                'kode_jurusan'  => $this->input->post('kode_jurusan'),
                'ids_jalur_masuk'  => $this->input->post('ids_jalur_masuk'),
                'daya_tampung'  => $this->input->post('daya_tampung'),
                'status'  => $this->input->post('status'),
                'created_by' => $this->jwt->ids_user,
                'updated_by' => $this->jwt->ids_user,
            );
            $fb = $this->Tbs_sub_daya_tampung->create($data);
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
        $hak_akses = $this->master->read('hak-akses/?ids_level=' . $this->jwt->ids_level . '&ids_modul=70&aksi_hak_akses=update');
        if ($hak_akses->code == 200) {
            $this->_validate();
            $rules = array(
                'where' => array(
                    'ids_sub_daya_tampung'  => $this->input->post('ids_sub_daya_tampung'),
                ),
                'or_where'            => null,
                'like'                => null,
                'or_like'            => null,
                'data'  => array(
                    'kode_jurusan'  => $this->input->post('kode_jurusan'),
                    'ids_jalur_masuk'  => $this->input->post('ids_jalur_masuk'),
                    'daya_tampung'  => $this->input->post('daya_tampung'),
                    'status'  => $this->input->post('status'),
                    'updated_by'  => $this->jwt->ids_user,
                ),
            );
            $fb = $this->Tbs_sub_daya_tampung->update($rules);
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
        $hak_akses = $this->master->read('hak-akses/?ids_level=' . $this->jwt->ids_level . '&ids_modul=70&aksi_hak_akses=delete');
        if ($hak_akses->code == 200) {
            $where = array(
                'where'                => array(
                    'ids_sub_daya_tampung' => $id
                ),
                'or_where'            => null,
                'like'                => null,
                'or_like'            => null,
            );
            if ($this->Tbs_sub_daya_tampung->delete($where)) {
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
        $hak_akses = $this->master->read('hak-akses/?ids_level=' . $this->jwt->ids_level . '&ids_modul=70&aksi_hak_akses=single');
        if ($hak_akses->code == 200) {
            $where = array();
            $where['status'] = 'YA';
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
                $tbsDayaTampung = $this->Views_sub_daya_tampung->search($rules);
                if ($tbsDayaTampung->num_rows() > 0) {
                    $response = array(
                        'status' => 200,
                        'data' => $tbsDayaTampung->result()
                    );
                } else {
                    $response = array(
                        'status' => 204,
                        'data' => null
                    );
                }
            } else {
                $where['ids_sub_daya_tampung'] = $id;
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
                $tbsDayaTampung = $this->Views_sub_daya_tampung->search($rules);
                if ($tbsDayaTampung->num_rows() > 0) {
                    $response = array(
                        'status' => 200,
                        'data' => $tbsDayaTampung->row()
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
        $list = $this->SS_sub_daya_tampung->get_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $row) {
            $sub_array = array();
            $sub_array[] = ++$no;
            $sub_array[] = "
                <button type=\"button\" title=\"Edit\" onclick=\"edit_data(" . $row->ids_sub_daya_tampung . ")\" class=\"btn btn-xs btn-warning\">
                    <span class=\"tf-icon bx bx-edit bx-xs\"></span> Edit
                </button>
                <a href=\"javascript:void(0)\" title=\"Hapus\" class=\"btn btn-xs btn-danger\" onclick=\"delete_data(" . $row->ids_sub_daya_tampung . "," . $row->ids_jalur_masuk . ")\">
                    <span class=\"tf-icon bx bx-trash bx-xs\"></span> Hapus
                </a>";
            $sub_array[] = date('Y', strtotime($row->date_created));
            $sub_array[] = $row->jurusan;
            $sub_array[] = $row->fakultas;
            $sub_array[] = $row->alias_jalur_masuk;
            $sub_array[] = $row->daya_tampung;
            $sub_array[] = ($row->status == 'YA') ? '<div class="badge bg-success">Tampilkan</div>' : '<div class="badge bg-danger">Sembunyikan</div>';

            $data[] = $sub_array;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->SS_sub_daya_tampung->count_all(),
            "recordsFiltered" => $this->SS_sub_daya_tampung->count_filtered(),
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

        if ($this->input->post('daya_tampung') == '') {
            $data['inputerror'][] = 'daya_tampung';
            $data['error_string'][] = 'Daya tampung wajib diisi.';
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
