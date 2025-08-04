<?php
defined('BASEPATH') or exit('No direct script access allowed');
class RekapJurusan extends CI_Controller
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

        $this->load->model('Daftar/Viewd_ukt');
    }

    function index()
    {
        $hak_akses = $this->master->read('hak-akses/?ids_level=' . $this->jwt->ids_level . '&ids_modul=56&aksi_hak_akses=read');
        if ($hak_akses->code == 200) {
            $ids_jalur_masuk = $this->input->post('ids_jalur_masuk');
            $tahun = $this->input->post('tahun');
            $tbsJurusan = $this->master->read("jurusan/?status=YA&page=1&limit=100");
            if (empty($ids_jalur_masuk) && empty($tahun)) {
                $kosong = true;
            } else {
                $kosong = false;
                if ($tbsJurusan->code == 200) {
                    foreach ($tbsJurusan->data->data as $value) {
                        for ($i = 0; $i < 9; $i++) {
                            $rules = array(
                                'database'  => null, //Default database master
                                'select'    => null,
                                'where'     => array(
                                    'kode_jurusan'  => $value->kode_jurusan,
                                    'kategori'  => 'K' . ($i + 1),
                                    'ids_jalur_masuk' => $ids_jalur_masuk,
                                    'tahun' => $tahun,
                                ),
                                'or_where'  => null,
                                'like'      => null,
                                'or_like'   => null,
                                'order'     => null,
                                'limit'     => null,
                                'group_by'  => null,
                            );
                            $count[$value->kode_jurusan][$i] = $this->Viewd_ukt->search($rules)->num_rows();
                        }
                    }
                } else {
                    $kosong = true;
                }
            }

            $rules = array(
                'database'  => null, //Default database master
                'select'    => 'tahun as tahun', // not null
                'where'     => null,
                'or_where'  => null,
                'like'      => null,
                'or_like'   => null,
                'order'     => 'tahun DESC',
                'group_by'  => null,
            );
            $data = array(
                'title'             => 'Rekap Jurusan UKT | ' . $_ENV['APPLICATION_NAME'],
                'content'           => 'ukt/rekap_jurusan/content',
                'css'               => 'ukt/rekap_jurusan/css',
                'javascript'        => 'ukt/rekap_jurusan/javascript',
                'modal'             => 'ukt/rekap_jurusan/modal',
                'kosong'            => $kosong,
                'tbsJalurMasuk'     => $this->master->read("jalur-masuk/?status=YA"),
                'tbsJurusan'        => $tbsJurusan,
                'tahun'             => $this->Viewd_ukt->distinct($rules)->result(),
                'count'             => (!empty($count)) ? $count : '',
                'ids_jalur_masuk'   => (!empty($ids_jalur_masuk)) ? $ids_jalur_masuk : '',
                'old_tahun'         => (!empty($tahun)) ? $tahun : '',
                'jumlah'            => null,
            );
            $this->load->view('index', $data);
        } else {
            $this->session->set_flashdata('message', 'Hak akses ditolak.');
            $this->session->set_flashdata('type_message', 'danger');
            redirect('dashboard/');
        }
    }

    function Search($tahun, $ids_jalur_masuk, $kategori)
    {
        $hak_akses = $this->master->read('hak-akses/?ids_level=' . $this->jwt->ids_level . '&ids_modul=56&aksi_hak_akses=read');
        if ($hak_akses->code == 200) {
            $rules = array(
                'database'  => null, //Default database master
                'select'    => null,
                'where'     => array(
                    'tahun' => $tahun,
                    'ids_jalur_masuk' => $ids_jalur_masuk,
                    'kategori' => $kategori,
                ),
                'or_where'  => null,
                'like'      => null,
                'or_like'   => null,
                'order'     => null,
                'limit'     => null,
                'group_by'  => null,
            );
            $data = array(
                'title'             => 'Rekap Jurusan UKT - Search | ' . $_ENV['APPLICATION_NAME'],
                'content'           => 'ukt/jurusan_search/content',
                'css'               => 'ukt/jurusan_search/css',
                'javascript'        => 'ukt/jurusan_search/javascript',
                'modal'             => 'ukt/jurusan_search/modal',
                'viewDaftarUKT'     => $this->Viewd_ukt->search($rules)->result(),
                'tahun'             => $tahun,
                'kategori'          => $kategori,
            );
            $this->load->view('index', $data);
        } else {
            $this->session->set_flashdata('message', 'Hak akses ditolak.');
            $this->session->set_flashdata('type_message', 'danger');
            redirect('dashboard/');
        }
    }
}
