<?php
defined('BASEPATH') or exit('No direct script access allowed');
class BobotRangeUkt extends CI_Controller
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

        $this->load->model('Daftar/Tbd_kelulusan');
        $this->load->model('Histori/Tbh_bobot_range_ukt');
        $this->load->model('Settings/Tbs_bobot_range_ukt');
        $this->load->model('Settings/Views_bobot_range_ukt');
        $this->load->model('ServerSide/SS_bobot_range_ukt');
    }

    function index()
    {
        $hak_akses = $this->master->read('hak-akses/?ids_level=' . $this->jwt->ids_level . '&ids_modul=48&aksi_hak_akses=read');
        if ($hak_akses->code == 200) {
            $rules[0] = array(
                'database'    => null, //Database master
                'select'    => null,
                'order'     => null,
                'limit'     => null,
                'group_by'    => null,
            );
            $rules[1] = array(
                'database'          => null, //Default database master
                'select'            => 'YEAR(date_created) as tahun', // not null
                'where'                => null,
                'or_where'            => null,
                'like'                => null,
                'or_like'            => null,
                'order'                => 'tahun DESC',
                'group_by'            => null,
            );
            $data = array(
                'title'             => 'Setting Bobot Range | ' . $_ENV['APPLICATION_NAME'],
                'content'           => 'Setting/bobot_range/content',
                'css'               => 'Setting/bobot_range/css',
                'javascript'        => 'Setting/bobot_range/javascript',
                'modal'             => 'Setting/bobot_range/modal',

                'tbsJalurMasuk'     => $this->master->read("jalur-masuk/?status=YA"),
                'tahun'             => $this->Tbd_kelulusan->distinct($rules[1])->result(),
            );
            $this->load->view('index', $data);
        } else {
            $this->session->set_flashdata('message', 'Hak akses ditolak.');
            $this->session->set_flashdata('type_message', 'danger');
            redirect('dashboard/');
        }
    }

    function Create()
    {
        $response = null;
        $hak_akses = $this->master->read('hak-akses/?ids_level=' . $this->jwt->ids_level . '&ids_modul=48&aksi_hak_akses=create');
        if ($hak_akses->code == 200) {
            $this->_validate('create');
            $data = array(
                'kategori' => $this->input->post('kategori'),
                'nilai_min' => $this->input->post('nilai_min'),
                'nilai_max' => $this->input->post('nilai_max'),
                'ids_jalur_masuk' => $this->input->post('ids_jalur_masuk'),
                'tahun' => date('Y'),
                'created_by' => $this->jwt->ids_user,
                'updated_by' => $this->jwt->ids_user,
            );
            $fb = $this->Tbs_bobot_range_ukt->create($data);
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
        $hak_akses = $this->master->read('hak-akses/?ids_level=' . $this->jwt->ids_level . '&ids_modul=48&aksi_hak_akses=update');
        if ($hak_akses->code == 200) {
            $this->_validate('update');
            $rules = array(
                'where'                => array('ids_bobot_range_ukt' => $this->input->post('ids_bobot_range_ukt')),
                'or_where'            => null,
                'like'                => null,
                'or_like'            => null,
                'data'                => array(
                    'kategori'  => $this->input->post('kategori'),
                    'nilai_min' => $this->input->post('nilai_min'),
                    'nilai_max' => $this->input->post('nilai_max'),
                    'updated_by' => $this->jwt->ids_user,
                ), // not null
            );
            $fb = $this->Tbs_bobot_range_ukt->update($rules);
            if (!$fb['status']) {
                $response = array(
                    'status' => 200,
                    'message' => 'Berhasil update data.'
                );
            } else {
                $response = array(
                    'status' => 400,
                    'message' => 'Gagal update data.'
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
        $hak_akses = $this->master->read('hak-akses/?ids_level=' . $this->jwt->ids_level . '&ids_modul=48&aksi_hak_akses=delete');
        if ($hak_akses->code == 200) {
            $rules = array(
                'where'                => array('ids_bobot_range_ukt' => $id),
                'or_where'            => null,
                'like'                => null,
                'or_like'            => null,
            );
            $fb = $this->Tbs_bobot_range_ukt->delete($rules);
            if (!$fb['status']) {
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
        $hak_akses = $this->master->read('hak-akses/?ids_level=' . $this->jwt->ids_level . '&ids_modul=48&aksi_hak_akses=single');
        if ($hak_akses->code == 200) {
            if ($id == null) {
                $rules = array(
                    'database'    => null, //Database master
                    'select'    => null,
                    'order'     => null,
                    'limit'     => null,
                    'pagging'   => null,
                );
                $viewsBobotRangeUKT = $this->Views_bobot_range_ukt->read($rules);
                if ($viewsBobotRangeUKT->num_rows() > 0) {
                    $response = array(
                        'status' => 200,
                        'data' => $viewsBobotRangeUKT->result()
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
                        'ids_bobot_range_ukt' => $id,
                    ),
                    'or_where'            => null,
                    'like'                => null,
                    'or_like'            => null,
                    'order'                => null,
                    'limit'                => null,
                    'group_by'            => null,
                );
                $viewsBobotRangeUKT = $this->Views_bobot_range_ukt->search($rules);
                if ($viewsBobotRangeUKT->num_rows() > 0) {
                    $response = array(
                        'status' => 200,
                        'data' => $viewsBobotRangeUKT->row()
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
        $hak_akses = $this->master->read('hak-akses/?ids_level=' . $this->jwt->ids_level . '&ids_modul=48&aksi_hak_akses=generate');
        if ($hak_akses->code == 200) {
            $this->_validateGenerate();
            $updated = $error = 0;
            $rules = array(
                'database'    => null, //Database master
                'select'    => null,
                'order'     => null,
                'limit'     => null,
                'group_by'    => null,
            );
            $tbsBobotRange = $this->Tbs_bobot_range_ukt->read($rules)->result();
            foreach ($tbsBobotRange as $value) {
                $rules = array(
                    'where'                => array('ids_bobot_range_ukt' => $value->ids_bobot_range_ukt),
                    'or_where'            => null,
                    'like'                => null,
                    'or_like'            => null,
                    'data'                => array(
                        'ids_jalur_masuk' => $this->input->post('ids_jalur_masuk'),
                        'tahun' => $this->input->post('tahun'),
                        'updated_by' => $this->jwt->ids_user,
                    ), // not null
                );
                $fb = $this->Tbs_bobot_range_ukt->update($rules);
                if (!$fb['status']) {
                    $updated++;
                } else {
                    $error++;
                }
            }
            $response = array(
                'status' => 200,
                'message' => "Data berhasil diubah. Updated: $updated. Error: $error"
            );
        } else {
            $response = array(
                'status' => 400,
                'message' => 'Hak akses ditolak.'
            );
        }
        echo json_encode($response);
    }

    function Simpan()
    {
        $response = null;
        $hak_akses = $this->master->read('hak-akses/?ids_level=' . $this->jwt->ids_level . '&ids_modul=48&aksi_hak_akses=history');
        if ($hak_akses->code == 200) {
            $created = $updated = $error = 0;
            $rules = array(
                'database'    => null, //Database master
                'select'    => null,
                'order'     => null,
                'limit'     => null,
                'group_by'    => null,
            );
            $tbsBobotRange = $this->Tbs_bobot_range_ukt->read($rules)->result();
            foreach ($tbsBobotRange as $value) {
                $rules = array(
                    'database'          => null, //Default database master
                    'select'            => null,
                    'where'                => array(
                        'kategori' => $value->kategori,
                        'ids_jalur_masuk' => $value->ids_jalur_masuk,
                        'tahun'     => $value->tahun,
                    ),
                    'or_where'            => null,
                    'like'                => null,
                    'or_like'            => null,
                    'order'                => null,
                    'limit'                => null,
                    'group_by'            => null,
                );
                $tblHBobotRange = $this->Tbh_bobot_range_ukt->search($rules);
                if ($tblHBobotRange->num_rows() == 0) {
                    $data = array(
                        'kategori'  => $value->kategori,
                        'nilai_min' => $value->nilai_min,
                        'nilai_max' => $value->nilai_max,
                        'ids_jalur_masuk' => $value->ids_jalur_masuk,
                        'tahun'     => $value->tahun,
                        'created_by' => $this->jwt->ids_user,
                        'updated_by' => $this->jwt->ids_user,
                    );
                    $fb = $this->Tbh_bobot_range_ukt->create($data);
                    if (!$fb['status']) {
                        $created++;
                    } else {
                        $error++;
                    }
                } else {
                    $tblHBobotRange = $tblHBobotRange->row();
                    $rules = array(
                        'where'                => array('idh_bobot_range_ukt' => $tblHBobotRange->idh_bobot_range_ukt),
                        'or_where'            => null,
                        'like'                => null,
                        'or_like'            => null,
                        'data'                => array(
                            'kategori'  => $value->kategori,
                            'nilai_min' => $value->nilai_min,
                            'nilai_max' => $value->nilai_max,
                            'ids_jalur_masuk'     => $value->ids_jalur_masuk,
                            'tahun'     => $value->tahun,
                            'updated_by' => $this->jwt->ids_user,
                        ), // not null
                    );
                    $fb = $this->Tbh_bobot_range_ukt->update($rules);
                    if (!$fb['status']) {
                        $updated++;
                    } else {
                        $error++;
                    }
                }
            }
            $response = array(
                'status' => 200,
                'message' => "Proses penyimpanan selesai. Created : $created. Update : $updated. Error : $error"
            );
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
        $list = $this->SS_bobot_range_ukt->get_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $row) {
            $sub_array = array();
            $sub_array[] = ++$no;
            $sub_array[] = "
                <button type=\"button\" title=\"Edit\" onclick=\"edit_data(" . $row->ids_bobot_range_ukt . ")\" class=\"btn btn-xs btn-warning\">
                    <span class=\"tf-icon bx bx-edit bx-xs\"></span> Edit
                </button>
                <a href=\"javascript:void(0)\" title=\"Hapus\" class=\"btn btn-xs btn-danger\" onclick=\"delete_data(" . $row->ids_bobot_range_ukt . ")\">
                    <span class=\"tf-icon bx bx-trash bx-xs\"></span> Hapus
                </a>";
            $sub_array[] = $row->alias_jalur_masuk;
            $sub_array[] = $row->kategori;
            $sub_array[] = $row->nilai_min;
            $sub_array[] = $row->nilai_max;
            $sub_array[] = $row->tahun;

            $data[] = $sub_array;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->SS_bobot_range_ukt->count_all(),
            "recordsFiltered" => $this->SS_bobot_range_ukt->count_filtered(),
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

        if ($this->input->post('kategori') == '') {
            $data['inputerror'][] = 'kategori';
            $data['error_string'][] = 'Kategori wajib diisi.';
            $data['status'] = FALSE;
        }

        if ($this->input->post('nilai_min') == '') {
            $data['inputerror'][] = 'nilai_min';
            $data['error_string'][] = 'Nilai min wajib diisi.';
            $data['status'] = FALSE;
        }

        if ($this->input->post('nilai_max') == '') {
            $data['inputerror'][] = 'nilai_max';
            $data['error_string'][] = 'Nilai max wajib diisi.';
            $data['status'] = FALSE;
        }

        if ($this->input->post('tahun') == '') {
            $data['inputerror'][] = 'tahun';
            $data['error_string'][] = 'Tahun wajib diisi.';
            $data['status'] = FALSE;
        }

        if ($method == 'create') {
            if ($this->input->post('ids_jalur_masuk') == '') {
                $data['inputerror'][] = 'ids_jalur_masuk';
                $data['error_string'][] = 'Jalur masuk wajib diisi.';
                $data['status'] = FALSE;
            }
        }

        if ($data['status'] === FALSE) {
            echo json_encode($data);
            exit();
        }
    }

    private function _validateGenerate()
    {
        $data = array();
        $data['error_string'] = array();
        $data['inputerror'] = array();
        $data['status'] = TRUE;

        if ($this->input->post('tahun') == '') {
            $data['inputerror'][] = 'tahun';
            $data['error_string'][] = 'Tahun wajib diisi.';
            $data['status'] = FALSE;
        }

        if ($this->input->post('ids_jalur_masuk') == '') {
            $data['inputerror'][] = 'ids_jalur_masuk';
            $data['error_string'][] = 'Jalur masuk wajib diisi.';
            $data['status'] = FALSE;
        }

        if ($data['status'] === FALSE) {
            echo json_encode($data);
            exit();
        }
    }
}
