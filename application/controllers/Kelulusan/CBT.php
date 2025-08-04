<?php
defined('BASEPATH') or exit('No direct script access allowed');
class CBT extends CI_Controller
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

        $this->load->model('Mandiri/Tbp_nilai');
        $this->load->model('Mandiri/Viewp_nilai');
        $this->load->model('Mandiri/Viewp_pilihan');
        $this->load->model('Mandiri/Viewp_jadwal');
        $this->load->model('Mandiri/Viewp_formulir');
        $this->load->model('Settings/Tbs_bobot_jurusan');
        $this->load->model('Settings/Views_jadwal');
    }

    function index()
    {
        $hak_akses = $this->master->read('hak-akses/?ids_level=' . $this->jwt->ids_level . '&ids_modul=89&aksi_hak_akses=generate');
        if ($hak_akses->code == 200) {
            $rules = array(
                'database'  => null, //Default database master
                'select'    => 'YEAR(tanggal) as tahun', // not null
                'where'     => array(
                    'ids_tipe_ujian' => 1
                ),
                'or_where' => null,
                'like' => null,
                'or_like' => null,
                'order' => null,
                'limit' => null,
                'group_by' => null,
            );
            $data = array(
                'title'         => 'Setting',
                'content'       => 'Kelulusan/cbt/content',
                'css'           => 'Kelulusan/cbt/css',
                'javascript'    => 'Kelulusan/cbt/javascript',
                'modal'         => 'Kelulusan/cbt/modal',

                'tahun'         => $this->Views_jadwal->distinct($rules)->result(),
            );
            $this->load->view('index', $data);
        } else {
            $this->session->set_flashdata('message', 'Hak akses ditolak.');
            $this->session->set_flashdata('type_message', 'danger');
            redirect('dashboard/');
        }
    }

    function GetTanggal()
    {
        $where = $data = array();
        if (!empty($this->input->post('tahun'))) {
            $where['YEAR(tanggal)'] = $this->input->post('tahun');
        }
        if (!empty($this->input->post('program'))) {
            $where['ids_program'] = $this->input->post('program');
        }
        // $where['ids_tipe_ujian'] = 1;

        $rules = array(
            'database'  => null, //Default database master
            'select'    => 'tanggal', // not null
            'where'     => $where,
            'or_where'  => null,
            'like'      => array(
                'tipe_ujian' => 'COMPUTER BASED TEST'
            ),
            'or_like'   => null,
            'order'     => 'tanggal ASC',
            'group_by'  => null,
        );
        $viewpJadwal = $this->Viewp_jadwal->distinct($rules);
        if ($viewpJadwal->num_rows() > 0) {
            $viewpJadwal = $viewpJadwal->result();
            foreach ($viewpJadwal as $a) {
                $data[] = array(
                    'tanggal' => date('d-m-Y', strtotime($a->tanggal)),
                );
            }
            $response = array(
                'status' => 200,
                'message' => 'Berhasil',
                'data' => $data
            );
        } else {
            $response = array(
                'status' => 400,
                'message' => 'Data kosong.'
            );
        }
        echo json_encode($response);
    }

    function Generate()
    {
        $hak_akses = $this->master->read('hak-akses/?ids_level=' . $this->jwt->ids_level . '&ids_modul=89&aksi_hak_akses=generate');
        if ($hak_akses->code == 200) {
            $berhasil = $error = $peserta = 0;
            $pesan = '';
            $data = array(
                'key' => '83471c36e093c391a9fbf989ea3a1684',
                'program' => $this->input->post('program'),
                'tanggal' => $this->input->post('tanggal')
            );
            $parrams = array(
                'url' => 'http://192.168.18.81/Api2/GetNilai?'.http_build_query($data),
                'method' => 'GET',
                'header' => array(
                    'Content-Type: application/json'
                ),
                'request' => null,
            );
            $result = $this->utilities->curl($parrams);
            // var_dump($parrams);
            // var_dump($result);
            // exit();
            if ($this->input->post('program') == 1) {
                foreach ($result->data as $a) {
                    $nomor_peserta = substr($a->nomor_peserta, 4);
                    $rules = array(
                        'database'  => null, //Default database master
                        'select'    => null,
                        'where'     => array(
                            'nomor_peserta' => $nomor_peserta,
                        ),
                        'or_where'  => null,
                        'like'      => null,
                        'or_like'   => null,
                        'order'     => null,
                        'limit'     => null,
                        'group_by'  => null,
                    );
                    $viewpFormulir = $this->Viewp_formulir->search($rules);
                    if ($viewpFormulir->num_rows() == 0) {
                        $pesan .= $nomor_peserta . '<br>';
                        $error++;
                    } else {
                        $viewpFormulir = $viewpFormulir->row();
                        // $rules = array(
                        //     'database'  => null, //Default database master
                        //     'select'    => null, // not null
                        //     'where'     => array(
                        //         'idp_formulir' => $viewpFormulir->idp_formulir,
                        //         'keterangan' => 'TEST POTENSI AKADEMIK',
                        //     ),
                        //     'or_where' => null,
                        //     'like' => null,
                        //     'or_like' => null,
                        //     'order' => null,
                        //     'limit' => null,
                        //     'group_by' => null,
                        // );
                        // $tbpNilaiTPA = $this->Tbp_nilai->search($rules);
                        // if ($tbpNilaiTPA->num_rows() == 0) {
                        //     $data = array(
                        //         'idp_formulir' => $viewpFormulir->idp_formulir,
                        //         'nilai' => $a->tpa,
                        //         'keterangan' => 'TEST POTENSI AKADEMIK',
                        //         'created_by' => $this->jwt->ids_user,
                        //         'updated_by' => $this->jwt->ids_user,
                        //     );
                        //     $fb = $this->Tbp_nilai->create($data);
                        //     if (!$fb['status']) {
                        //         $berhasil++;
                        //     } else {
                        //         $error++;
                        //     }
                        // }else{
                        //     $rules = array(
                        //         'where'     => array(
                        //             'idp_formulir' => $viewpFormulir->idp_formulir,
                        //             'keterangan' => 'TEST POTENSI AKADEMIK',
                        //         ),
                        //         'or_where'  => null,
                        //         'like'      => null,
                        //         'or_like'   => null,
                        //         'data'      => array(
                        //             'nilai' => $a->tpa,
                        //             'updated_by' => $this->jwt->ids_user,
                        //         ), // not null
                        //     );
                        //     $fb = $this->Tbp_nilai->update($rules);
                        //     if (!$fb['status']) {
                        //         $berhasil++;
                        //     } else {
                        //         $error++;
                        //     }
                        // }

                        // $rules = array(
                        //     'database'  => null, //Default database master
                        //     'select'    => null, // not null
                        //     'where'     => array(
                        //         'idp_formulir' => $viewpFormulir->idp_formulir,
                        //         'keterangan' => 'BAHASA INGGRIS',
                        //     ),
                        //     'or_where' => null,
                        //     'like' => null,
                        //     'or_like' => null,
                        //     'order' => null,
                        //     'limit' => null,
                        //     'group_by' => null,
                        // );
                        // $tbpNilaiInggris = $this->Tbp_nilai->search($rules);
                        // if ($tbpNilaiInggris->num_rows() == 0) {
                        //     $data = array(
                        //         'idp_formulir' => $viewpFormulir->idp_formulir,
                        //         'nilai' => $a->bahasa_inggris,
                        //         'keterangan' => 'BAHASA INGGRIS',
                        //         'created_by' => $this->jwt->ids_user,
                        //         'updated_by' => $this->jwt->ids_user,
                        //     );
                        //     $fb = $this->Tbp_nilai->create($data);
                        //     if (!$fb['status']) {
                        //         $berhasil++;
                        //     } else {
                        //         $error++;
                        //     }
                        // }else{
                        //     $rules = array(
                        //         'where'     => array(
                        //             'idp_formulir' => $viewpFormulir->idp_formulir,
                        //             'keterangan' => 'BAHASA INGGRIS',
                        //         ),
                        //         'or_where'  => null,
                        //         'like'      => null,
                        //         'or_like'   => null,
                        //         'data'      => array(
                        //             'nilai' => $a->bahasa_inggris,
                        //             'updated_by' => $this->jwt->ids_user,
                        //         ), // not null
                        //     );
                        //     $fb = $this->Tbp_nilai->update($rules);
                        //     if (!$fb['status']) {
                        //         $berhasil++;
                        //     } else {
                        //         $error++;
                        //     }
                        // }

                        // $rules = array(
                        //     'database'  => null, //Default database master
                        //     'select'    => null, // not null
                        //     'where'     => array(
                        //         'idp_formulir' => $viewpFormulir->idp_formulir,
                        //         'keterangan' => 'BAHASA ARAB',
                        //     ),
                        //     'or_where' => null,
                        //     'like' => null,
                        //     'or_like' => null,
                        //     'order' => null,
                        //     'limit' => null,
                        //     'group_by' => null,
                        // );
                        // $tbpNilaiArab = $this->Tbp_nilai->search($rules);
                        // if ($tbpNilaiArab->num_rows() == 0) {
                        //     $data = array(
                        //         'idp_formulir' => $viewpFormulir->idp_formulir,
                        //         'nilai' => $a->bahasa_arab,
                        //         'keterangan' => 'BAHASA ARAB',
                        //         'created_by' => $this->jwt->ids_user,
                        //         'updated_by' => $this->jwt->ids_user,
                        //     );
                        //     $fb = $this->Tbp_nilai->create($data);
                        //     if (!$fb['status']) {
                        //         $berhasil++;
                        //     } else {
                        //         $error++;
                        //     }
                        // }else{
                        //     $rules = array(
                        //         'where'     => array(
                        //             'idp_formulir' => $viewpFormulir->idp_formulir,
                        //             'keterangan' => 'BAHASA ARAB',
                        //         ),
                        //         'or_where'  => null,
                        //         'like'      => null,
                        //         'or_like'   => null,
                        //         'data'      => array(
                        //             'nilai' => $a->bahasa_arab,
                        //             'updated_by' => $this->jwt->ids_user,
                        //         ), // not null
                        //     );
                        //     $fb = $this->Tbp_nilai->update($rules);
                        //     if (!$fb['status']) {
                        //         $berhasil++;
                        //     } else {
                        //         $error++;
                        //     }
                        // }

                        $rules = array(
                            'database'  => null, //Default database master
                            'select'    => null, // not null
                            'where'     => array(
                                'idp_formulir' => $viewpFormulir->idp_formulir,
                                'keterangan' => 'CBT',
                            ),
                            'or_where' => null,
                            'like' => null,
                            'or_like' => null,
                            'order' => null,
                            'limit' => null,
                            'group_by' => null,
                        );
                        $tbpNilaiCBT = $this->Tbp_nilai->search($rules);
                        if ($tbpNilaiCBT->num_rows() == 0) {
                            $data = array(
                                'idp_formulir' => $viewpFormulir->idp_formulir,
                                'nilai' => $a->score_irt,
                                'keterangan' => 'CBT',
                                'created_by' => $this->jwt->ids_user,
                                'updated_by' => $this->jwt->ids_user,
                            );
                            $fb = $this->Tbp_nilai->create($data);
                            if (!$fb['status']) {
                                $berhasil++;
                            } else {
                                $error++;
                            }
                        }else{
                            $rules = array(
                                'where'     => array(
                                    'idp_formulir' => $viewpFormulir->idp_formulir,
                                    'keterangan' => 'CBT',
                                ),
                                'or_where'  => null,
                                'like'      => null,
                                'or_like'   => null,
                                'data'      => array(
                                    'nilai' => $a->score_irt,
                                    'updated_by' => $this->jwt->ids_user,
                                ), // not null
                            );
                            $fb = $this->Tbp_nilai->update($rules);
                            if (!$fb['status']) {
                                $berhasil++;
                            } else {
                                $error++;
                            }
                        }

                        $peserta++;
                    }
                }
            } else {
                foreach ($result->data as $a) {
                    $nomor_peserta = $a->nomor_peserta;
                    $rules = array(
                        'database'  => null, //Default database master
                        'select'    => null,
                        'where'     => array(
                            'nomor_peserta' => $nomor_peserta,
                        ),
                        'or_where'  => null,
                        'like'      => null,
                        'or_like'   => null,
                        'order'     => null,
                        'limit'     => null,
                        'group_by'  => null,
                    );
                    $viewpFormulir = $this->Viewp_formulir->search($rules);
                    if ($viewpFormulir->num_rows() == 0) {
                        $pesan .= $nomor_peserta . '<br>';
                        $error++;
                    } else {
                        $viewpFormulir = $viewpFormulir->row();
                        $rules = array(
                            'database'  => null, //Default database master
                            'select'    => null, // not null
                            'where'     => array(
                                'idp_formulir' => $viewpFormulir->idp_formulir,
                                'keterangan' => 'TEST POTENSI AKADEMIK',
                            ),
                            'or_where' => null,
                            'like' => null,
                            'or_like' => null,
                            'order' => null,
                            'limit' => null,
                            'group_by' => null,
                        );
                        $tbpNilaiTPA = $this->Tbp_nilai->search($rules);
                        if ($tbpNilaiTPA->num_rows() == 0) {
                            $data = array(
                                'idp_formulir' => $viewpFormulir->idp_formulir,
                                'nilai' => $a->tpa,
                                'keterangan' => 'TEST POTENSI AKADEMIK',
                                'created_by' => $this->jwt->ids_user,
                                'updated_by' => $this->jwt->ids_user,
                            );
                            $fb = $this->Tbp_nilai->create($data);
                            if (!$fb['status']) {
                                $berhasil++;
                            } else {
                                $error++;
                            }
                        }else{
                            $rules = array(
                                'where'     => array(
                                    'idp_formulir' => $viewpFormulir->idp_formulir,
                                    'keterangan' => 'TEST POTENSI AKADEMIK',
                                ),
                                'or_where'  => null,
                                'like'      => null,
                                'or_like'   => null,
                                'data'      => array(
                                    'nilai' => $a->tpa,
                                    'updated_by' => $this->jwt->ids_user,
                                ), // not null
                            );
                            $fb = $this->Tbp_nilai->update($rules);
                            if (!$fb['status']) {
                                $berhasil++;
                            } else {
                                $error++;
                            }
                        }

                        $rules = array(
                            'database'  => null, //Default database master
                            'select'    => null, // not null
                            'where'     => array(
                                'idp_formulir' => $viewpFormulir->idp_formulir,
                                'keterangan' => 'BAHASA INGGRIS',
                            ),
                            'or_where' => null,
                            'like' => null,
                            'or_like' => null,
                            'order' => null,
                            'limit' => null,
                            'group_by' => null,
                        );
                        $tbpNilaiInggris = $this->Tbp_nilai->search($rules);
                        if ($tbpNilaiInggris->num_rows() == 0) {
                            $data = array(
                                'idp_formulir' => $viewpFormulir->idp_formulir,
                                'nilai' => $a->bahasa_inggris,
                                'keterangan' => 'BAHASA INGGRIS',
                                'created_by' => $this->jwt->ids_user,
                                'updated_by' => $this->jwt->ids_user,
                            );
                            $fb = $this->Tbp_nilai->create($data);
                            if (!$fb['status']) {
                                $berhasil++;
                            } else {
                                $error++;
                            }
                        }else{
                            $rules = array(
                                'where'     => array(
                                    'idp_formulir' => $viewpFormulir->idp_formulir,
                                    'keterangan' => 'BAHASA INGGRIS',
                                ),
                                'or_where'  => null,
                                'like'      => null,
                                'or_like'   => null,
                                'data'      => array(
                                    'nilai' => $a->bahasa_inggris,
                                    'updated_by' => $this->jwt->ids_user,
                                ), // not null
                            );
                            $fb = $this->Tbp_nilai->update($rules);
                            if (!$fb['status']) {
                                $berhasil++;
                            } else {
                                $error++;
                            }
                        }

                        $rules = array(
                            'database'  => null, //Default database master
                            'select'    => null, // not null
                            'where'     => array(
                                'idp_formulir' => $viewpFormulir->idp_formulir,
                                'keterangan' => 'BAHASA ARAB',
                            ),
                            'or_where' => null,
                            'like' => null,
                            'or_like' => null,
                            'order' => null,
                            'limit' => null,
                            'group_by' => null,
                        );
                        $tbpNilaiArab = $this->Tbp_nilai->search($rules);
                        if ($tbpNilaiArab->num_rows() == 0) {
                            $data = array(
                                'idp_formulir' => $viewpFormulir->idp_formulir,
                                'nilai' => $a->bahasa_arab,
                                'keterangan' => 'BAHASA ARAB',
                                'created_by' => $this->jwt->ids_user,
                                'updated_by' => $this->jwt->ids_user,
                            );
                            $fb = $this->Tbp_nilai->create($data);
                            if (!$fb['status']) {
                                $berhasil++;
                            } else {
                                $error++;
                            }
                        }else{
                            $rules = array(
                                'where'     => array(
                                    'idp_formulir' => $viewpFormulir->idp_formulir,
                                    'keterangan' => 'BAHASA ARAB',
                                ),
                                'or_where'  => null,
                                'like'      => null,
                                'or_like'   => null,
                                'data'      => array(
                                    'nilai' => $a->bahasa_arab,
                                    'updated_by' => $this->jwt->ids_user,
                                ), // not null
                            );
                            $fb = $this->Tbp_nilai->update($rules);
                            if (!$fb['status']) {
                                $berhasil++;
                            } else {
                                $error++;
                            }
                        }

                        $rules = array(
                            'database'  => null, //Default database master
                            'select'    => null, // not null
                            'where'     => array(
                                'idp_formulir' => $viewpFormulir->idp_formulir,
                                'keterangan' => 'CBT',
                            ),
                            'or_where' => null,
                            'like' => null,
                            'or_like' => null,
                            'order' => null,
                            'limit' => null,
                            'group_by' => null,
                        );
                        $tbpNilaiCBT = $this->Tbp_nilai->search($rules);
                        if ($tbpNilaiCBT->num_rows() == 0) {
                            $data = array(
                                'idp_formulir' => $viewpFormulir->idp_formulir,
                                'nilai' => $a->score_akhir_irt,
                                'keterangan' => 'CBT',
                                'created_by' => $this->jwt->ids_user,
                                'updated_by' => $this->jwt->ids_user,
                            );
                            $fb = $this->Tbp_nilai->create($data);
                            if (!$fb['status']) {
                                $berhasil++;
                            } else {
                                $error++;
                            }
                        }else{
                            $rules = array(
                                'where'     => array(
                                    'idp_formulir' => $viewpFormulir->idp_formulir,
                                    'keterangan' => 'CBT',
                                ),
                                'or_where'  => null,
                                'like'      => null,
                                'or_like'   => null,
                                'data'      => array(
                                    'nilai' => $a->score_akhir_irt,
                                    'updated_by' => $this->jwt->ids_user,
                                ), // not null
                            );
                            $fb = $this->Tbp_nilai->update($rules);
                            if (!$fb['status']) {
                                $berhasil++;
                            } else {
                                $error++;
                            }
                        }

                        $peserta++;
                    }
                }
            }

            $this->session->set_flashdata('message', 'Generate nilai ' . $peserta . ' peserta berhasil. Sukses : ' . $berhasil . '. Error : ' . $error . '. Pesan : ' . $pesan);
            $this->session->set_flashdata('type_message', 'success');
            redirect('kelulusan/cbt');
        } else {
            $this->session->set_flashdata('message', 'Hak akses ditolak.');
            $this->session->set_flashdata('type_message', 'danger');
            redirect('dashboard/');
        }
    }
}
