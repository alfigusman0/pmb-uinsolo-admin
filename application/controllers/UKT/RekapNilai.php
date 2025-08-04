<?php
defined('BASEPATH') or exit('No direct script access allowed');
class RekapNilai extends CI_Controller
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

        $this->load->model('Daftar/Viewd_mahasiswa');
        $this->load->model('Daftar/Viewd_orangtua');
        $this->load->model('Daftar/Viewd_rumah');
        $this->load->model('Daftar/Viewd_ukt');
    }

    function index()
    {
        $hak_akses = $this->master->read('hak-akses/?ids_level=' . $this->jwt->ids_level . '&ids_modul=57&aksi_hak_akses=read');
        if ($hak_akses->code == 200) {
            $ids_jalur_masuk = $this->input->post('ids_jalur_masuk');
            $tahun = $this->input->post('tahun');
            if (empty($ids_jalur_masuk) && empty($tahun)) {
                $kosong = true;
            } else {
                $kosong = false;
                $rules = array(
                    'database'  => null, //Default database master
                    'select'    => null,
                    'where'     => array(
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
                $viewDUKT    = $this->Viewd_ukt->search($rules)->result();
                foreach ($viewDUKT as $value) {
                    $rules = array(
                        'database'  => null, //Default database master
                        'select'    => null,
                        'where'     => array('idd_kelulusan' => $value->idd_kelulusan),
                        'or_where'  => null,
                        'like'      => null,
                        'or_like'   => null,
                        'order'     => null,
                        'limit'     => null,
                        'group_by'  => null,
                    );
                    $viewdMahasiswa = $this->Viewd_mahasiswa->search($rules)->row();
                    $viewdOrangTua = $this->Viewd_orangtua->search($rules)->result();
                    $viewdRumah = $this->Viewd_rumah->search($rules)->row();

                    $pekerjaan_ayah = $penghasilan_ayah = $pekerjaan_ibu = $penghasilan_ibu = $pekerjaan_wali = $penghasilan_wali = '-';
                    foreach ($viewdOrangTua as $a) {
                        if ($a->orangtua == 'Ayah') {
                            $penghasilan_ayah = $a->penghasilan;
                        }
                        if ($a->orangtua == 'Ibu') {
                            $penghasilan_ibu = $a->penghasilan;
                        }
                        if ($a->orangtua == 'Wali') {
                            $penghasilan_wali = $a->penghasilan;
                        }
                    }

                    $dataUkt[] = array(
                        'nomor_peserta'     => $value->nomor_peserta,
                        'nama'              => $value->nama,
                        'fakultas'          => $value->fakultas,
                        'jurusan'           => $value->jurusan,
                        'kategori'          => $value->kategori,
                        'score'             => $value->score,
                        'jumlah'            => $value->jumlah,
                        'daya_listrik'      => (!empty($viewdRumah->daya_listrik)) ? $viewdRumah->daya_listrik : '-',
                        'kepemilikan_mobil' => (!empty($viewdRumah->kepemilikan_mobil)) ? $viewdRumah->kepemilikan_mobil : '-',
                        'kepemilikan_motor' => (!empty($viewdRumah->kepemilikan_motor)) ? $viewdRumah->kepemilikan_motor : '-',
                        'kepemilikan_rumah' => (!empty($viewdRumah->kepemilikan_rumah)) ? $viewdRumah->kepemilikan_rumah : '-',
                        'lktl'              => (!empty($viewdRumah->lktl)) ? $viewdRumah->lktl : '-',
                        'njop'              => (!empty($viewdRumah->njop)) ? $viewdRumah->njop : '-',
                        'pajak_mobil'       => (!empty($viewdRumah->pajak_mobil)) ? $viewdRumah->pajak_mobil : '-',
                        'pajak_motor'       => (!empty($viewdRumah->pajak_motor)) ? $viewdRumah->pajak_motor : '-',
                        'penghasilan_ayah'  => $penghasilan_ayah,
                        'penghasilan_ibu'   => $penghasilan_ibu,
                        'penghasilan_wali'  => $penghasilan_wali,
                        'rekening_listrik'  => (!empty($viewdRumah->rekening_listrik)) ? $viewdRumah->rekening_listrik : '-',
                        'sktm'              => (!empty($viewdMahasiswa->sktm)) ? $viewdMahasiswa->sktm : '-',
                        'tanggungan'        => (!empty($viewdRumah->tanggungan)) ? $viewdRumah->tanggungan : '-',
                    );
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
                'title'             => 'Rekap Nilai UKT | ' . $_ENV['APPLICATION_NAME'],
                'content'           => 'ukt/rekap_nilai/content',
                'css'               => 'ukt/rekap_nilai/css',
                'javascript'        => 'ukt/rekap_nilai/javascript',
                'modal'             => 'ukt/rekap_nilai/modal',
                'kosong'            => $kosong,
                'tbsJalurMasuk'     => $this->master->read('jalur-masuk/?status=YA'),
                'tahun'             => $this->Viewd_ukt->distinct($rules)->result(),
                'ids_jalur_masuk'   => (!empty($ids_jalur_masuk)) ? $ids_jalur_masuk : '',
                'old_tahun'         => (!empty($tahun)) ? $tahun : '',
                'viewdUKT'          => (!empty($dataUkt)) ? $dataUkt : '',
            );
            $this->load->view('index', $data);
        } else {
            $this->session->set_flashdata('message', 'Hak akses ditolak.');
            $this->session->set_flashdata('type_message', 'danger');
            redirect('dashboard/');
        }
    }
}
