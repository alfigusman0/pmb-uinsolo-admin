<?php
defined('BASEPATH') or exit('No direct script access allowed');
class JalurMasuk extends CI_Controller
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
        $this->load->model('Settings/Tbs_jalur_masuk');
        $this->load->model('Settings/Views_jalur_masuk');
    }

    function index()
    {
        $hak_akses = $this->master->read('hak-akses/?ids_level=' . $this->jwt->ids_level . '&ids_modul=60&aksi_hak_akses=read');
        if ($hak_akses->code == 200) {
            $jalur_masuk = $this->master->read('jalur-masuk/?limit=1000');
            foreach ($jalur_masuk->data->data as $data) {
                $rules = array(
                    'database'          => null, //Default database master
                    'select'            => null,
                    'where'                => array(
                        'ids_jalur_masuk' => $data->ids_jalur_masuk
                    ),
                    'or_where'            => null,
                    'like'                => null,
                    'or_like'            => null,
                    'order'                => null,
                    'limit'                => null,
                    'group_by'            => null,
                );
                $tbsJalurMasuk[$data->ids_jalur_masuk] = $this->Tbs_jalur_masuk->search($rules)->row();
            }

            $data = array(
                'title'         => 'Jalur Masuk',
                'content'       => 'Setting/jalur_masuk/content',
                'css'           => 'Setting/jalur_masuk/css',
                'javascript'    => 'Setting/jalur_masuk/javascript',
                'modal'         => 'Setting/jalur_masuk/modal',
                'masterJalurMasuk' => $jalur_masuk,
                'tbsJalurMasuk' => $tbsJalurMasuk,
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
        $hak_akses = $this->master->read('hak-akses/?ids_level=' . $this->jwt->ids_level . '&ids_modul=60&aksi_hak_akses=create');
        if ($hak_akses->code == 200) {
            $data = array(
                'ids_jalur_masuk'  => $this->input->post('ids_jalur_masuk'),
                'pendaftaran_awal'  => $this->input->post('pendaftaran_awal'),
                'pendaftaran_akhir'  => $this->input->post('pendaftaran_akhir'),
                'ukt_awal'  => $this->input->post('ukt_awal'),
                'ukt_akhir'  => $this->input->post('ukt_akhir'),
                'pembayaran_awal'  => $this->input->post('pembayaran_awal'),
                'pembayaran_akhir'  => $this->input->post('pembayaran_akhir'),
                'pemberkasan_awal'  => $this->input->post('pemberkasan_awal'),
                'pemberkasan_akhir'  => $this->input->post('pemberkasan_akhir'),
                'created_by' => $this->jwt->ids_user,
                'updated_by' => $this->jwt->ids_user,
            );
            $fb = $this->Tbs_jalur_masuk->create($data);
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
        $hak_akses = $this->master->read('hak-akses/?ids_level=' . $this->jwt->ids_level . '&ids_modul=60&aksi_hak_akses=update');
        if ($hak_akses->code == 200) {
            $rules = array(
                'database'          => null, //Default database master
                'select'            => null,
                'where'                => array(
                    'ids_jalur_masuk' => $this->input->post('ids_jalur_masuk')
                ),
                'or_where'            => null,
                'like'                => null,
                'or_like'            => null,
                'order'                => null,
                'limit'                => null,
                'group_by'            => null,
            );
            $tbsJalurMasuk = $this->Tbs_jalur_masuk->search($rules);
            if ($tbsJalurMasuk->num_rows() > 0) {
                $rules = array(
                    'where' => array(
                        'ids_jalur_masuk'  => $this->input->post('ids_jalur_masuk'),
                    ),
                    'or_where'            => null,
                    'like'                => null,
                    'or_like'            => null,
                    'data'  => array(
                        'pendaftaran_awal'  => $this->input->post('pendaftaran_awal'),
                        'pendaftaran_akhir'  => $this->input->post('pendaftaran_akhir'),
                        'ukt_awal'  => $this->input->post('ukt_awal'),
                        'ukt_akhir'  => $this->input->post('ukt_akhir'),
                        'pembayaran_awal'  => $this->input->post('pembayaran_awal'),
                        'pembayaran_akhir'  => $this->input->post('pembayaran_akhir'),
                        'pemberkasan_awal'  => $this->input->post('pemberkasan_awal'),
                        'pemberkasan_akhir'  => $this->input->post('pemberkasan_akhir'),
                        'updated_by'  => $this->jwt->ids_user,
                    ),
                );
                $fb = $this->Tbs_jalur_masuk->update($rules);
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
                $data  = array(
                    'ids_jalur_masuk'  => $this->input->post('ids_jalur_masuk'),
                    'pendaftaran_awal'  => $this->input->post('pendaftaran_awal'),
                    'pendaftaran_akhir'  => $this->input->post('pendaftaran_akhir'),
                    'ukt_awal'  => $this->input->post('ukt_awal'),
                    'ukt_akhir'  => $this->input->post('ukt_akhir'),
                    'pembayaran_awal'  => $this->input->post('pembayaran_awal'),
                    'pembayaran_akhir'  => $this->input->post('pembayaran_akhir'),
                    'pemberkasan_awal'  => $this->input->post('pemberkasan_awal'),
                    'pemberkasan_akhir'  => $this->input->post('pemberkasan_akhir'),
                    'created_by'  => $this->jwt->ids_user,
                    'updated_by'  => $this->jwt->ids_user,
                );
                $fb = $this->Tbs_jalur_masuk->create($data);
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
        $hak_akses = $this->master->read('hak-akses/?ids_level=' . $this->jwt->ids_level . '&ids_modul=60&aksi_hak_akses=delete');
        if ($hak_akses->code == 200) {
            $where = array(
                'where' => array(
                    'ids_jalur_masuk'  => $id,
                ),
                'or_where'            => null,
                'like'                => null,
                'or_like'            => null,
            );
            if ($this->Tbs_jalur_masuk->delete($where)) {
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
        $hak_akses = $this->master->read('hak-akses/?ids_level=' . $this->jwt->ids_level . '&ids_modul=60&aksi_hak_akses=single');
        if ($hak_akses->code == 200) {
            $where = array();
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
                $tbsJalurMasuk = $this->Views_jalur_masuk->search($rules);
                if ($tbsJalurMasuk->num_rows() > 0) {
                    $response = array(
                        'status' => 200,
                        'data' => $tbsJalurMasuk->result()
                    );
                } else {
                    $response = array(
                        'status' => 204,
                        'data' => null
                    );
                }
            } else {
                $where['ids_jalur_masuk'] = $id;
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
                $tbsJalurMasuk = $this->Views_jalur_masuk->search($rules);
                if ($tbsJalurMasuk->num_rows() > 0) {
                    $response = array(
                        'status' => 200,
                        'data' => $tbsJalurMasuk->row()
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
}
