<?php
defined('BASEPATH') or exit('No direct script access allowed');
class BobotNilaiUkt extends CI_Controller
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
        $this->load->model('Daftar/Viewd_mahasiswa');
        $this->load->model('Daftar/Viewd_orangtua');
        $this->load->model('Daftar/Viewd_rumah');
        $this->load->model('Histori/Tbh_bobot_nilai_ukt');
        $this->load->model('Settings/Tbs_bobot_nilai_ukt');
        $this->load->model('Settings/Views_bobot_nilai_ukt');
        $this->load->model('ServerSide/SS_bobot_nilai_ukt');
    }

    function index()
    {
        $hak_akses = $this->master->read('hak-akses/?ids_level=' . $this->jwt->ids_level . '&ids_modul=47&aksi_hak_akses=read');
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
                'title'         => 'Setting Bobot Nilai | ' . $_ENV['APPLICATION_NAME'],
                'content'       => 'Setting/bobot_nilai/content',
                'css'           => 'Setting/bobot_nilai/css',
                'javascript'    => 'Setting/bobot_nilai/javascript',
                'modal'         => 'Setting/bobot_nilai/modal',

                'tbsJalurMasuk' => $this->master->read("jalur-masuk/?status=YA"),
                'tahun'         => $this->Tbd_kelulusan->distinct($rules[1])->result(),
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
        $hak_akses = $this->master->read('hak-akses/?ids_level=' . $this->jwt->ids_level . '&ids_modul=47&aksi_hak_akses=create');
        if ($hak_akses->code == 200) {
            $this->_validate('create');
            $data = array(
                'nama_field' => $this->input->post('nama_field'),
                'alias' => strtoupper($this->input->post('alias')),
                'bobot' => $this->input->post('bobot'),
                'ids_jalur_masuk' => 1,
                'tahun' => date('Y'),
                'nilai_max' => $this->input->post('nilai_max'),
                'created_by' => $this->jwt->ids_user,
                'updated_by' => $this->jwt->ids_user,
            );
            $fb = $this->Tbs_bobot_nilai_ukt->create($data);
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
        $hak_akses = $this->master->read('hak-akses/?ids_level=' . $this->jwt->ids_level . '&ids_modul=47&aksi_hak_akses=update');
        if ($hak_akses->code == 200) {
            $this->_validate('update');
            $rules = array(
                'where'                => array('ids_bobot_nilai_ukt' => $this->input->post('ids_bobot_nilai_ukt')),
                'or_where'            => null,
                'like'                => null,
                'or_like'            => null,
                'data'                => array(
                    'nama_field'    => $this->input->post('nama_field'),
                    'alias'         => strtoupper($this->input->post('alias')),
                    'bobot'         => $this->input->post('bobot'),
                    'nilai_max'     => $this->input->post('nilai_max'),
                    'updated_by'    => $this->jwt->ids_user,
                ), // not null
            );
            $fb = $this->Tbs_bobot_nilai_ukt->update($rules);
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
        $hak_akses = $this->master->read('hak-akses/?ids_level=' . $this->jwt->ids_level . '&ids_modul=47&aksi_hak_akses=delete');
        if ($hak_akses->code == 200) {
            $rules = array(
                'where'                => array('ids_bobot_nilai_ukt' => $id),
                'or_where'            => null,
                'like'                => null,
                'or_like'            => null,
            );
            $fb = $this->Tbs_bobot_nilai_ukt->delete($rules);
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
        $hak_akses = $this->master->read('hak-akses/?ids_level=' . $this->jwt->ids_level . '&ids_modul=47&aksi_hak_akses=single');
        if ($hak_akses->code == 200) {
            if ($id == null) {
                $rules = array(
                    'database'    => null, //Database master
                    'select'    => null,
                    'order'     => null,
                    'limit'     => null,
                    'pagging'   => null,
                );
                $viewsBobotNilaiUKT = $this->Views_bobot_nilai_ukt->read($rules);
                if ($viewsBobotNilaiUKT->num_rows() > 0) {
                    $response = array(
                        'status' => 200,
                        'data' => $viewsBobotNilaiUKT->result()
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
                        'ids_bobot_nilai_ukt' => $id,
                    ),
                    'or_where'            => null,
                    'like'                => null,
                    'or_like'            => null,
                    'order'                => null,
                    'limit'                => null,
                    'group_by'            => null,
                );
                $viewsBobotNilaiUKT = $this->Views_bobot_nilai_ukt->search($rules);
                if ($viewsBobotNilaiUKT->num_rows() > 0) {
                    $response = array(
                        'status' => 200,
                        'data' => $viewsBobotNilaiUKT->row()
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
        $hak_akses = $this->master->read('hak-akses/?ids_level=' . $this->jwt->ids_level . '&ids_modul=47&aksi_hak_akses=generate');
        if ($hak_akses->code == 200) {
            $this->_validateGenerate();
            $ids_jalur_masuk = $this->input->post('ids_jalur_masuk');
            $tahun = $this->input->post('tahun');
            // Nilai Max Orang Tua dan Wali
            $rules = array(
                'database'          => null, //Default database master
                'select'            => null,
                'where'                => array(
                    'ids_bobot_nilai_ukt >=' => 12,
                    'ids_bobot_nilai_ukt <=' => 14
                ),
                'or_where'            => null,
                'like'                => null,
                'or_like'            => null,
                'order'                => null,
                'limit'                => null,
                'group_by'            => null,
            );
            $tbsBobotNilaiUkt = $this->Tbs_bobot_nilai_ukt->search($rules)->result();
            foreach ($tbsBobotNilaiUkt as $value) {
                try {
                    $data = explode('_', $value->nama_field);
                    $nama_field = $data[0] . '_' . $data[1];
                    $rules = array(
                        'database'          => null, //Default database master
                        'select'            => "max($nama_field) as nilai",
                        'where'                => array(
                            'orangtua' => strtoupper($data[2]),
                            'ids_jalur_masuk' => $ids_jalur_masuk,
                            'submit' => 'SUDAH',
                            'YEAR(date_created)' => $tahun,
                        ),
                        'or_where'            => null,
                        'like'                => null,
                        'or_like'            => null,
                        'order'                => null,
                        'limit'                => null,
                        'group_by'            => null,
                    );
                    $generate = $this->Viewd_orangtua->search($rules)->row();
                    $this->UpdateNilai($value->ids_bobot_nilai_ukt, $ids_jalur_masuk, $generate->nilai, $tahun);
                } catch (Exception $e) {
                    echo $e->getMessage();
                    exit;
                }
            }
            // Nilai Max Rumah
            $rules = array(
                'database'          => null, //Default database master
                'select'            => null,
                'where'                => array(
                    'ids_bobot_nilai_ukt >=' => 1,
                    'ids_bobot_nilai_ukt <=' => 10
                ),
                'or_where'            => null,
                'like'                => null,
                'or_like'            => null,
                'order'                => null,
                'limit'                => null,
                'group_by'            => null,
            );
            $tbsBobotNilaiUkt = $this->Tbs_bobot_nilai_ukt->search($rules)->result();
            foreach ($tbsBobotNilaiUkt as $value) {
                try {
                    $rules = array(
                        'database'          => null, //Default database master
                        'select'            => "max($value->nama_field) as nilai",
                        'where'                => array(
                            'ids_jalur_masuk' => $ids_jalur_masuk,
                            'submit' => 'SUDAH',
                            'YEAR(date_created)' => $tahun,
                        ),
                        'or_where'            => null,
                        'like'                => null,
                        'or_like'            => null,
                        'order'                => null,
                        'limit'                => null,
                        'group_by'            => null,
                    );
                    $generate = $this->Viewd_rumah->search($rules)->row();
                    $this->UpdateNilai($value->ids_bobot_nilai_ukt, $ids_jalur_masuk, $generate->nilai, $tahun);
                } catch (Exception $e) {
                    echo $e->getMessage();
                    exit;
                }
            }
            // Nilai Max Mahasiswa
            $rules = array(
                'database'          => null, //Default database master
                'select'            => null,
                'where'                => array(
                    'ids_bobot_nilai_ukt >=' => 11,
                    'ids_bobot_nilai_ukt <=' => 11
                ),
                'or_where'            => null,
                'like'                => null,
                'or_like'            => null,
                'order'                => null,
                'limit'                => null,
                'group_by'            => null,
            );
            $tbsBobotNilaiUkt = $this->Tbs_bobot_nilai_ukt->search($rules)->result();
            foreach ($tbsBobotNilaiUkt as $value) {
                try {
                    $rules = array(
                        'database'          => null, //Default database master
                        'select'            => "max($value->nama_field) as nilai",
                        'where'                => array(
                            'ids_jalur_masuk' => $ids_jalur_masuk,
                            'submit' => 'SUDAH',
                            'YEAR(date_created)' => $tahun,
                        ),
                        'or_where'            => null,
                        'like'                => null,
                        'or_like'            => null,
                        'order'                => null,
                        'limit'                => null,
                        'group_by'            => null,
                    );
                    $generate = $this->Viewd_mahasiswa->search($rules)->row();
                    $this->UpdateNilai($value->ids_bobot_nilai_ukt, $ids_jalur_masuk, $generate->nilai, $tahun);
                } catch (Exception $e) {
                    echo $e->getMessage();
                    exit;
                }
            }
            $response = array(
                'status' => 200,
                'message' => 'Berhasil generate data.'
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
        $hak_akses = $this->master->read('hak-akses/?ids_level=' . $this->jwt->ids_level . '&ids_modul=47&aksi_hak_akses=history');
        if ($hak_akses->code == 200) {
            $created = $updated = $error = 0;
            $rules = array(
                'database'    => null, //Database master
                'select'    => null,
                'order'     => null,
                'limit'     => null,
                'group_by'    => null,
            );
            $tbsBobotNilaiUkt = $this->Tbs_bobot_nilai_ukt->read($rules)->result();
            foreach ($tbsBobotNilaiUkt as $value) {
                $rules = array(
                    'database'          => null, //Default database master
                    'select'            => null,
                    'where'                => array(
                        'nama_field' => $value->nama_field,
                        'ids_jalur_masuk' => $value->ids_jalur_masuk,
                        'tahun' => $value->tahun,
                    ),
                    'or_where'            => null,
                    'like'                => null,
                    'or_like'            => null,
                    'order'                => null,
                    'limit'                => null,
                    'group_by'            => null,
                );
                $tbhBobotNilaiUkt = $this->Tbh_bobot_nilai_ukt->search($rules);
                if ($tbhBobotNilaiUkt->num_rows() == 0) {
                    $data = array(
                        'nama_field' => $value->nama_field,
                        'alias' => $value->alias,
                        'bobot' => $value->bobot,
                        'nilai_max' => $value->nilai_max,
                        'ids_jalur_masuk' => $value->ids_jalur_masuk,
                        'tahun' => $value->tahun,
                        'created_by' => $this->jwt->ids_user,
                        'updated_by' => $this->jwt->ids_user,
                    );
                    $fb = $this->Tbh_bobot_nilai_ukt->create($data);
                    if (!$fb['status']) {
                        $created++;
                    } else {
                        $error++;
                    }
                } else {
                    $tbhBobotNilaiUkt = $tbhBobotNilaiUkt->row();
                    $rules = array(
                        'where'                => array('idh_bobot_nilai_ukt' => $tbhBobotNilaiUkt->idh_bobot_nilai_ukt),
                        'or_where'            => null,
                        'like'                => null,
                        'or_like'            => null,
                        'data'                => array(
                            'nama_field' => $value->nama_field,
                            'alias' => $value->alias,
                            'bobot' => $value->bobot,
                            'nilai_max' => $value->nilai_max,
                            'ids_jalur_masuk' => $value->ids_jalur_masuk,
                            'tahun' => $value->tahun,
                            'updated_by' => $this->jwt->ids_user,
                        ), // not null
                    );
                    $fb = $this->Tbh_bobot_nilai_ukt->update($rules);
                    if (!$fb['status']) {
                        $updated++;
                    } else {
                        $error++;
                    }
                }
            }
            $response = array(
                'status' => 200,
                'message' => "Proses penyimpanan selesai. Proses Created : $created. Updated : $updated. Error : $error"
            );
        } else {
            $response = array(
                'status' => 400,
                'message' => 'Hak akses ditolak.'
            );
        }
        echo json_encode($response);
    }

    function UpdateNilai($ids_bobot_nilai_ukt, $ids_jalur_masuk, $nilai, $tahun)
    {
        try {
            $nilai = ($nilai == null) ? 0 : $nilai;
            $rules = array(
                'where' => array('ids_bobot_nilai_ukt' => $ids_bobot_nilai_ukt),
                'or_where' => null,
                'like' => null,
                'or_like' => null,
                'data' => array(
                    'ids_jalur_masuk' => $ids_jalur_masuk,
                    'nilai_max' => $nilai,
                    'tahun' => $tahun,
                ), // not null
            );
            $fb = $this->Tbs_bobot_nilai_ukt->update($rules);
            return $fb['status'];
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    function JsonFormFilter()
    {
        $list = $this->SS_bobot_nilai_ukt->get_datatables();
        $data = array();
        $no = $_POST['start'];
        $total_bobot = 0;
        foreach ($list as $row) {
            $sub_array = array();
            $sub_array[] = ++$no;
            $sub_array[] = "
                <button type=\"button\" title=\"Edit\" onclick=\"edit_data(" . $row->ids_bobot_nilai_ukt . ")\" class=\"btn btn-xs btn-warning\">
                    <span class=\"tf-icon bx bx-edit bx-xs\"></span> Edit
                </button>
                <a href=\"javascript:void(0)\" title=\"Hapus\" class=\"btn btn-xs btn-danger\" onclick=\"delete_data(" . $row->ids_bobot_nilai_ukt . ")\">
                    <span class=\"tf-icon bx bx-trash bx-xs\"></span> Hapus
                </a>";
            $sub_array[] = $row->nama_field;
            $sub_array[] = $row->alias;
            $sub_array[] = ($row->bobot * 100) . "%";
            $sub_array[] = $row->nilai_max;
            $sub_array[] = $row->alias_jalur_masuk;
            $sub_array[] = $row->tahun;

            $total_bobot += ($row->bobot * 100);

            $data[] = $sub_array;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->SS_bobot_nilai_ukt->count_all(),
            "recordsFiltered" => $this->SS_bobot_nilai_ukt->count_filtered(),
            "data" => $data,
            "total_bobot" => $total_bobot
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

        if ($this->input->post('nama_field') == '') {
            $data['inputerror'][] = 'nama_field';
            $data['error_string'][] = 'Nama field wajib diisi.';
            $data['status'] = FALSE;
        }

        if ($this->input->post('alias') == '') {
            $data['inputerror'][] = 'alias';
            $data['error_string'][] = 'Alias min wajib diisi.';
            $data['status'] = FALSE;
        }

        if ($this->input->post('bobot') == '') {
            $data['inputerror'][] = 'bobot';
            $data['error_string'][] = 'Bobot wajib diisi.';
            $data['status'] = FALSE;
        }

        if ($this->input->post('nilai_max') == '') {
            $data['inputerror'][] = 'nilai_max';
            $data['error_string'][] = 'Nilai max wajib diisi.';
            $data['status'] = FALSE;
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
