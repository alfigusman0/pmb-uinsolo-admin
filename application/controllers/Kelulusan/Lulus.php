<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Lulus extends CI_Controller
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

        $this->load->model('Mandiri/Tbp_kelulusan');
        $this->load->model('Mandiri/Tbp_pilihan');
        $this->load->model('Mandiri/Tbp_formulir');
        $this->load->model('Mandiri/Viewp_kelulusan');
        $this->load->model('Settings/Tbs_daya_tampung');
    }

    function index()
    {
        $hak_akses = $this->master->read('hak-akses/?ids_level=' . $this->jwt->ids_level . '&ids_modul=91&aksi_hak_akses=read');
        if ($hak_akses->code == 200) {
            $rules = array(
                'database'  => null,
                'select'    => 'YEAR(date_created) as tahun', // not null
                'where'     => null,
                'or_where'  => null,
                'like'      => null,
                'or_like'   => null,
                'order'     => 'tahun DESC',
                'group_by'  => null,
            );
            $tahun = $this->Viewp_kelulusan->distinct($rules)->result();
            $data = array(
                'title'         => 'Kelulusan | ' . $_ENV['APPLICATION_NAME'],
                'content'       => 'Kelulusan/lulus/generate/content',
                'css'           => 'Kelulusan/lulus/generate/css',
                'javascript'    => 'Kelulusan/lulus/generate/javascript',
                'modal'         => 'Kelulusan/lulus/generate/modal',
                'tahun'         => $tahun,
            );
            $this->load->view('index', $data);
        } else {
            $this->session->set_flashdata('message', 'Hak akses ditolak.');
            $this->session->set_flashdata('type_message', 'danger');
            redirect('dashboard/');
        }
    }

    function TidakLulus()
    {
        $hak_akses = $this->master->read('hak-akses/?ids_level=' . $this->jwt->ids_level . '&ids_modul=91&aksi_hak_akses=update');
        if ($hak_akses->code == 200) {
            $rules = array(
                'database'  => null,
                'select'    => 'YEAR(date_created) as tahun', // not null
                'where'     => null,
                'or_where'  => null,
                'like'      => null,
                'or_like'   => null,
                'order'     => 'tahun DESC',
                'group_by'  => null,
            );
            $tahun = $this->Viewp_kelulusan->distinct($rules)->result();
            $data = array(
                'title'         => 'Kelulusan | ' . $_ENV['APPLICATION_NAME'],
                'content'       => 'Kelulusan/lulus/tidak/content',
                'css'           => 'Kelulusan/lulus/tidak/css',
                'javascript'    => 'Kelulusan/lulus/tidak/javascript',
                'modal'         => 'Kelulusan/lulus/tidak/modal',
                'tahun'         => $tahun,
            );
            $this->load->view('index', $data);
        } else {
            $this->session->set_flashdata('message', 'Hak akses ditolak.');
            $this->session->set_flashdata('type_message', 'danger');
            redirect('dashboard/');
        }
    }

    function Reset()
    {
        $hak_akses = $this->master->read('hak-akses/?ids_level=' . $this->jwt->ids_level . '&ids_modul=91&aksi_hak_akses=delete');
        if ($hak_akses->code == 200) {
            $rules = array(
                'database'  => null,
                'select'    => 'YEAR(date_created) as tahun', // not null
                'where'     => null,
                'or_where'  => null,
                'like'      => null,
                'or_like'   => null,
                'order'     => 'tahun DESC',
                'group_by'  => null,
            );
            $tahun = $this->Viewp_kelulusan->distinct($rules)->result();
            $data = array(
                'title'         => 'Kelulusan | ' . $_ENV['APPLICATION_NAME'],
                'content'       => 'Kelulusan/lulus/reset/content',
                'css'           => 'Kelulusan/lulus/reset/css',
                'javascript'    => 'Kelulusan/lulus/reset/javascript',
                'modal'         => 'Kelulusan/lulus/reset/modal',
                'tahun'         => $tahun,
            );
            $this->load->view('index', $data);
        } else {
            $this->session->set_flashdata('message', 'Hak akses ditolak.');
            $this->session->set_flashdata('type_message', 'danger');
            redirect('dashboard/');
        }
    }

    function prosesLulus()
    {
        $hak_akses = $this->master->read('hak-akses/?ids_level=' . $this->jwt->ids_level . '&ids_modul=91&aksi_hak_akses=generate');
        if ($hak_akses->code == 200) {
            $rules[] = array('field' => 'pilihan', 'label' => 'Pilihan Jurusan', 'rules' => 'required');
            $rules[] = array('field' => 'tahun', 'label' => 'Tahun', 'rules' => 'required');
            $this->form_validation->set_rules($rules);
            if ($this->form_validation->run() == FALSE) {
                $this->session->set_flashdata('message', validation_errors());
                $this->session->set_flashdata('type_message', 'danger');
                redirect('kelulusan/lulus/');
            } else {
                $where = array();
                $lulus = $kuota = $grade = $eu_kelulusan = $eu_daya_tampung = 0;
                $pilihan = $this->input->post('pilihan');
                $tahun = $this->input->post('tahun');
                $ids_tipe_ujian = implode(',', $this->input->post('ids_tipe_ujian'));
                if (!empty($ids_tipe_ujian)) {
                    $where["ids_tipe_ujian IN ($ids_tipe_ujian)"] = null;
                }
                $where["lulus !="] = 'YA';
                $where['YEAR(date_created)'] = $tahun;
                $rules = array(
                    'database'  => null,
                    'select'    => null, // not null
                    'where'     => $where,
                    'or_where'  => null,
                    'like'      => null,
                    'or_like'   => null,
                    'order'     => 'total DESC',
                    'limit'     => null,
                    'group_by'  => null,
                );
                $viewPKelulusan = $this->Viewp_kelulusan->search($rules)->result();
                foreach ($viewPKelulusan as $value) {
                    // Pilihan
                    $rules = array(
                        'database'  => null,
                        'select'    => null,
                        'where'     => array(
                            'idp_formulir' => $value->idp_formulir,
                            'pilihan' => "$pilihan",
                        ),
                        'or_where'  => null,
                        'like'      => null,
                        'or_like'   => null,
                        'order'     => null,
                        'limit'     => null,
                        'group_by'  => null,
                    );
                    $tbpPilihan = $this->Tbp_pilihan->search($rules)->row();
                    // Daya Tampung
                    $rules = array(
                        'database'  => null,
                        'select'    => null,
                        'where'     => array(
                            'kode_jurusan' => $tbpPilihan->kode_jurusan,
                            'YEAR(date_created)' => $tahun,
                        ),
                        'or_where'  => null,
                        'like'      => null,
                        'or_like'   => null,
                        'order'     => null,
                        'limit'     => null,
                        'group_by'  => null,
                    );
                    $tbSDayaTampung = $this->Tbs_daya_tampung->search($rules)->row();
                    if ($value->total > 0 && $value->total >= $tbSDayaTampung->grade) {
                        if ($tbSDayaTampung->kuota > 0) {
                            // Update Kelulusan - Lulus
                            $rules = array(
                                'where'     => array('idp_formulir' => $value->idp_formulir),
                                'or_where'  => null,
                                'like'      => null,
                                'or_like'   => null,
                                'data'      => array(
                                    'kode_jurusan' => $tbpPilihan->kode_jurusan,
                                    'lulus' => 'YA',
                                    'updated_by' => $this->jwt->ids_user,
                                ), // not null
                            );
                            $fb = $this->Tbp_kelulusan->update($rules);
                            if (!$fb['status']) {
                                // Update Kuota Daya Tampung Kelulusan
                                $rules = array(
                                    'where'     => array(
                                        'kode_jurusan' => $tbpPilihan->kode_jurusan,
                                        'YEAR(date_created)' => $tahun,
                                    ),
                                    'or_where'  => null,
                                    'like'      => null,
                                    'or_like'   => null,
                                    'data'      => array(
                                        'kuota' => $tbSDayaTampung->kuota - 1,
                                    ), // not null
                                );
                                $fb = $this->Tbs_daya_tampung->update($rules);
                                if (!$fb['status']) {
                                    $lulus++;
                                } else {
                                    $eu_daya_tampung++;
                                }
                            } else {
                                $eu_kelulusan++;
                            }
                        } else {
                            // Update Kelulusan - Tidak Lulus Kuota
                            $rules = array(
                                'where'     => array('idp_formulir' => $value->idp_formulir),
                                'or_where'  => null,
                                'like'      => null,
                                'or_like'   => null,
                                'data'      => array(
                                    'lulus' => 'TIDAK',
                                    'updated_by' => $this->jwt->ids_user,
                                ), // not null
                            );
                            $fb = $this->Tbp_kelulusan->update($rules);
                            if (!$fb['status']) {
                                $kuota++;
                            } else {
                                $eu_kelulusan++;
                            }
                        }
                    } else {
                        // Update Kelulusan - Tidak Lulus Grade
                        $rules = array(
                            'where'     => array('idp_formulir' => $value->idp_formulir),
                            'or_where'  => null,
                            'like'      => null,
                            'or_like'   => null,
                            'data'      => array(
                                'lulus' => 'TIDAK',
                                'updated_by' => $this->jwt->ids_user,
                            ), // not null
                        );
                        $fb = $this->Tbp_kelulusan->update($rules);
                        if (!$fb['status']) {
                            $grade++;
                        } else {
                            $eu_kelulusan++;
                        }
                    }
                }
            }
            $this->session->set_flashdata('message', "Generate kelulusan berhasil. Lulus :  $lulus . Kuota Habis : $kuota . Grade Kurang : $grade . Error Kelulusan :  $eu_kelulusan . Error Daya Tampung :  $eu_daya_tampung .");
            $this->session->set_flashdata('type_message', ($eu_kelulusan == 0 && $eu_daya_tampung == 0) ? 'success' : 'warning');
            redirect('kelulusan/lulus/');
        } else {
            $this->session->set_flashdata('message', 'Hak akses ditolak.');
            $this->session->set_flashdata('type_message', 'danger');
            redirect('dashboard/');
        }
    }

    function prosesTidakLulus()
    {
        $hak_akses = $this->master->read('hak-akses/?ids_level=' . $this->jwt->ids_level . '&ids_modul=91&aksi_hak_akses=update');
        if ($hak_akses->code == 200) {
            $rules[] = array('field' => 'tahun', 'label' => 'Tahun', 'rules' => 'required');
            $this->form_validation->set_rules($rules);
            if ($this->form_validation->run() == FALSE) {
                $this->session->set_flashdata('message', validation_errors());
                $this->session->set_flashdata('type_message', 'danger');
                redirect('kelulusan/tidak-lulus/');
            } else {
                $where = array();
                $updated = $error = 0;
                $tahun = $this->input->post('tahun');
                $ids_tipe_ujian = implode(',', $this->input->post('ids_tipe_ujian'));
                if (!empty($ids_tipe_ujian)) {
                    $where["ids_tipe_ujian IN ($ids_tipe_ujian)"] = null;
                }
                $where['formulir'] = 'SUDAH';
                $where['pembayaran'] = 'SUDAH';
                $where['YEAR(date_created)'] = $tahun;
                $rules = array(
                    'database'  => null,
                    'select'    => null, // not null
                    'where'     => $where,
                    'or_where'  => null,
                    'like'      => null,
                    'or_like'   => null,
                    'order'     => null,
                    'group_by'  => null,
                );
                $tbpFormulir = $this->Tbp_formulir->search($rules)->result();
                foreach ($tbpFormulir as $a) {
                    $rules = array(
                        'database'  => null,
                        'select'    => null, // not null
                        'where'     => array(
                            'idp_formulir' => $a->idp_formulir,
                            'YEAR(date_created)' => $tahun,
                            'lulus !=' => 'YA'
                        ),
                        'or_where'  => null,
                        'like'      => null,
                        'or_like'   => null,
                        'order'     => null,
                        'group_by'  => null,
                    );
                    $num = $this->Tbp_kelulusan->search($rules)->num_rows();
                    if ($num > 0) {
                        $rules = array(
                            'where'     => array(
                                'idp_formulir' => $a->idp_formulir,
                            ),
                            'or_where'  => null,
                            'like'      => null,
                            'or_like'   => null,
                            'data'      => array(
                                'kode_jurusan' => '000',
                                'lulus' => 'TIDAK',
                            ), // not null
                        );
                        $fb = $this->Tbp_kelulusan->update($rules);
                        if (!$fb['status']) {
                            $updated++;
                        } else {
                            $error++;
                        }
                    }
                }
                $this->session->set_flashdata('message', "Generate Tidak Lulus berhasil. Update : $updated, Error : $error.");
                $this->session->set_flashdata('type_message', 'info');
                redirect('kelulusan/tidak-lulus/');
            }
        } else {
            $this->session->set_flashdata('message', 'Hak akses ditolak.');
            $this->session->set_flashdata('type_message', 'danger');
            redirect('dashboard/');
        }
    }

    function prosesReset()
    {
        $hak_akses = $this->master->read('hak-akses/?ids_level=' . $this->jwt->ids_level . '&ids_modul=91&aksi_hak_akses=delete');
        if ($hak_akses->code == 200) {
            $rules[] = array('field' => 'tahun', 'label' => 'Tahun', 'rules' => 'required');
            $this->form_validation->set_rules($rules);
            if ($this->form_validation->run() == FALSE) {
                $this->session->set_flashdata('message', validation_errors());
                $this->session->set_flashdata('type_message', 'danger');
                redirect('kelulusan/reset/');
            } else {
                $where = array();
                $updated = $error = 0;
                $tahun = $this->input->post('tahun');
                $ids_tipe_ujian = implode(',', $this->input->post('ids_tipe_ujian'));
                if (!empty($ids_tipe_ujian)) {
                    $where["ids_tipe_ujian IN ($ids_tipe_ujian)"] = null;
                }
                $where['formulir'] = 'SUDAH';
                $where['pembayaran'] = 'SUDAH';
                $where['YEAR(date_created)'] = $tahun;
                $rules = array(
                    'database'  => null,
                    'select'    => null, // not null
                    'where'     => $where,
                    'or_where'  => null,
                    'like'      => null,
                    'or_like'   => null,
                    'limit'     => null,
                    'order'     => null,
                    'group_by'  => null,
                );
                $tbpFormulir = $this->Tbp_formulir->search($rules)->result();
                foreach ($tbpFormulir as $a) {
                    $rules = array(
                        'where'     => array(
                            'idp_formulir' => $a->idp_formulir,
                            'YEAR(date_created)' => $tahun,
                        ),
                        'or_where'  => null,
                        'like'      => null,
                        'or_like'   => null,
                        'data'      => array(
                            'kode_jurusan' => '000',
                            'lulus' => 'BELUM',
                            'nama_penitip' => '-',
                            'keterangan' => '-'
                        ), // not null
                    );
                    $fb = $this->Tbp_kelulusan->update($rules);
                    if (!$fb['status']) {
                        $updated++;
                    } else {
                        $error++;
                    }
                }
                $this->session->set_flashdata('message', "Reset kelulusan berhasil. Updated: $updated, Error: $error.");
                $this->session->set_flashdata('type_message', 'success');
                redirect('kelulusan/reset/');
            }
        } else {
            $this->session->set_flashdata('message', 'Hak akses ditolak.');
            $this->session->set_flashdata('type_message', 'danger');
            redirect('dashboard/');
        }
    }
}
