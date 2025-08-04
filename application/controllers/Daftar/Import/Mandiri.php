<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Mandiri extends CI_Controller
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
        $this->load->model('Daftar/Tbd_mahasiswa');
        $this->load->model('Daftar/Tbd_rumah');
        $this->load->model('Daftar/Tbd_sekolah');
        $this->load->model('Daftar/Tbd_file');
        $this->load->model('Daftar/Tbd_pendidikan');
        $this->load->model('Mandiri/Tbp_kelulusan');
        $this->load->model('Mandiri/Tbp_sekolah');
        $this->load->model('Mandiri/Tbp_formulir');
        $this->load->model('Mandiri/Tbp_biodata');
        $this->load->model('Mandiri/Tbp_rumah');
        $this->load->model('Mandiri/Tbp_file');
        $this->load->model('Mandiri/Tbp_pendidikan');
        $this->load->model('Mandiri/Viewp_kelulusan');
    }

    function index()
    {
        $hak_akses = $this->master->read('hak-akses/?ids_level=' . $this->jwt->ids_level . '&ids_modul=142&aksi_hak_akses=import');
        if ($hak_akses->code == 200) {
            $rules = array(
                'database'  => null, //Default database master
                'select'    => 'YEAR(date_created) as tahun', // not null
                'where'     => null,
                'or_where' => null,
                'like' => null,
                'or_like' => null,
                'order' => 'YEAR(date_created) DESC',
                'limit' => null,
                'group_by' => null,
            );
            $data = array(
                'title' => 'Import Data Mandiri | ' . $_ENV['APPLICATION_NAME'],
                'content' => 'Import/Daftar/mandiri/content',
                'css' => 'Import/Daftar/mandiri/css',
                'javascript' => 'Import/Daftar/mandiri/javascript',
                'modal' => 'Import/Daftar/mandiri/modal',
                'tahun'         => $this->Tbp_kelulusan->distinct($rules)->result(),
            );
            $this->load->view('index', $data);
        } else {
            $this->session->set_flashdata('message', 'Hak akses ditolak.');
            $this->session->set_flashdata('type_message', 'danger');
            redirect('dashboard/');
        }
    }

    function Import()
    {
        $hak_akses = $this->master->read('hak-akses/?ids_level=' . $this->jwt->ids_level . '&ids_modul=142&aksi_hak_akses=import');
        if ($hak_akses->code == 200) {
            $berhasil_create = $berhasil_update = $error = 0;
            $tahun = $this->input->post('tahun');
            $ids_jalur_masuk = $this->input->post('ids_jalur_masuk');
            $data_import = $this->input->post('data_import');
            $ids_tipe_ujian = implode(',', $this->input->post('ids_tipe_ujian'));
            if(!empty($ids_tipe_ujian)){
                $where["ids_tipe_ujian IN ($ids_tipe_ujian)"] = null;
            }
            $where["lulus"] = 'YA';
            $where["YEAR(date_created)"] = $tahun;
            $rules = array(
                'database'  => null, //Default database master
                'select'    => null,
                'where'     => $where,
                'or_where'  => null,
                'like'      => null,
                'or_like'   => null,
                'order'     => null,
                'limit'     => null,
                'group_by'  => null,
            );
            $viewpKelulusan = $this->Viewp_kelulusan->search($rules)->result();
            if ($data_import == 'Kelulusan') {
                foreach ($viewpKelulusan as $a) {
                    $rules = array(
                        'database'  => null, //Default database master
                        'select'    => null,
                        'where'     => array(
                            'nomor_peserta' => $a->nomor_peserta,
                        ),
                        'or_where'  => null,
                        'like'      => null,
                        'or_like'   => null,
                        'order'     => null,
                        'limit'     => null,
                        'group_by'  => null,
                    );
                    $tbdKelulusan = $this->Tbd_kelulusan->search($rules);
                    if ($tbdKelulusan->num_rows() > 0) {
                        $tbdKelulusan = $tbdKelulusan->row();
                        // if($tbdKelulusan->daftar == 'BELUM'){
                        //     $rules = array(
                        //         'where'     => array('idd_kelulusan' => $tbdKelulusan->idd_kelulusan),
                        //         'or_where'  => null,
                        //         'like'      => null,
                        //         'or_like'   => null,
                        //         'data'      => array(
                        //             'id_user'  => 1,
                        //             'nomor_peserta' => $a->nomor_peserta,
                        //             'nama' => $a->nama,
                        //             'kode_jurusan' => $a->kode_jurusan,
                        //             'ids_jalur_masuk' => 5,
                        //             'tahun' => $tahun,
                        //             'daftar' => 'BELUM',
                        //             'submit' => 'BELUM',
                        //             'pembayaran' => 'BELUM',
                        //             'updated_by'  => 1,
                        //         ),
                        //     );
                        //     if ($this->Tbd_kelulusan->update($rules)) {
                        //         $berhasil_update++;
                        //     } else {
                        //         $error++;
                        //     }
                        // }
                    } else {
                        $data = array(
                            'id_user'  => 1,
                            'nomor_peserta' => $a->nomor_peserta,
                            'nama' => $a->nama,
                            'kode_jurusan' => $a->kode_jurusan,
                            'ids_jalur_masuk' => $ids_jalur_masuk,
                            'tahun' => $tahun,
                            'daftar' => 'BELUM',
                            'submit' => 'BELUM',
                            'pembayaran' => 'BELUM',
                            'created_by'  => 1,
                            'updated_by'  => 1,
                        );
                        $fb = $this->Tbd_kelulusan->create($data);
                        if (!$fb['status']) {
                            $berhasil_create++;
                        } else {
                            $error++;
                        }
                    }
                }
            } else if ($data_import == 'Mahasiswa') {
                foreach ($viewpKelulusan as $a) {
                    $rules1 = array(
                        'database'  => null, //Default database master
                        'select'    => null,
                        'where'     => array(
                            'nomor_peserta' => $a->nomor_peserta,
                        ),
                        'or_where'  => null,
                        'like'      => null,
                        'or_like'   => null,
                        'order'     => null,
                        'limit'     => null,
                        'group_by'  => null,
                    );
                    $tbdKelulusan = $this->Tbd_kelulusan->search($rules1);
                    if ($tbdKelulusan->num_rows() > 0) {
                        $tbdKelulusan = $tbdKelulusan->row();
                        if ($tbdKelulusan->daftar == 'BELUM') {
                            $rules2 = array(
                                'database'  => null, //Default database master
                                'select'    => null,
                                'where'     => array(
                                    'idd_kelulusan' => $tbdKelulusan->idd_kelulusan,
                                ),
                                'or_where'  => null,
                                'like'      => null,
                                'or_like'   => null,
                                'order'     => null,
                                'limit'     => null,
                                'group_by'  => null,
                            );
                            $tbdMahasiswa = $this->Tbd_mahasiswa->search($rules2);
                            $tbpFormulir = $this->Tbp_formulir->search($rules1)->row();
                            $rules3 = array(
                                'database'  => null, //Default database master
                                'select'    => null,
                                'where'     => array(
                                    'idp_formulir' => $tbpFormulir->idp_formulir,
                                ),
                                'or_where'  => null,
                                'like'      => null,
                                'or_like'   => null,
                                'order'     => null,
                                'limit'     => null,
                                'group_by'  => null,
                            );
                            $tbpBiodata = $this->Tbp_biodata->search($rules3)->row();
                            if ($tbdMahasiswa->num_rows() > 0) {
                                $tbdMahasiswa = $tbdMahasiswa->row();
                                // $rules = array(
                                //     'where'     => array('idd_kelulusan' => $tbdKelulusan->idd_kelulusan),
                                //     'or_where'  => null,
                                //     'like'      => null,
                                //     'or_like'   => null,
                                //     'data'      => array(
                                //         'idd_kelulusan'  => $tbdKelulusan->idd_kelulusan,
                                //         'nik' => $tbpBiodata->nik,
                                //         'nim' => 0,
                                //         'jenis_kelamin' => $tbpBiodata->jenis_kelamin,
                                //         'tempat_lahir' => $tbpBiodata->tempat_lahir,
                                //         'tgl_lahir' => $tbpBiodata->tgl_lahir,
                                //         'ids_agama' => $tbpBiodata->ids_agama,
                                //         'kewarganegaraan' => $tbpBiodata->kewarganegaraan,
                                //         'ids_jenis_tinggal' => 1,
                                //         'ids_alat_transportasi' => 3,
                                //         'terima_kps' => 'TIDAK',
                                //         'no_kps' => '',
                                //         'ids_jenis_pendaftaran' => 1,
                                //         'ids_jenis_pembiayaan' => 1,
                                //         'ids_rumpun' => $tbpSekolah->ids_rumpun,
                                //         'ids_hubungan' => 1,
                                //         'ukuran_baju' => 'S',
                                //         'ukuran_jas' => 'S',
                                //         'updated_by'  => 1,
                                //     ),
                                // );
                                // if ($this->Tbd_mahasiswa->update($rules)) {
                                //     $berhasil_update++;
                                // } else {
                                //     $error++;
                                // }
                            } else {
                                $data = array(
                                    'idd_kelulusan'  => $tbdKelulusan->idd_kelulusan,
                                    'nik' => $tbpBiodata->nik,
                                    'jenis_kelamin' => $tbpBiodata->jenis_kelamin,
                                    'tempat_lahir' => $tbpBiodata->tempat_lahir,
                                    'tgl_lahir' => $tbpBiodata->tgl_lahir,
                                    'ids_agama' => $tbpBiodata->ids_agama,
                                    'kewarganegaraan' => $tbpBiodata->kewarganegaraan,
                                    'ids_jenis_tinggal' => 1,
                                    'ids_alat_transportasi' => 1,
                                    'terima_kps' => 'TIDAK',
                                    'no_kps' => '',
                                    'ids_jenis_pendaftaran' => 1,
                                    'ids_jenis_pembiayaan' => 1,
                                    'ids_hubungan' => 1,
                                    'ukuran_baju' => 'S',
                                    'ukuran_jas' => 'S',
                                    'created_by'  => $tbpBiodata->created_by,
                                    'updated_by'  => $tbpBiodata->updated_by,
                                );
                                $fb = $this->Tbd_mahasiswa->create($data);
                                if (!$fb['status']) {
                                    $berhasil_create++;
                                } else {
                                    $error++;
                                }
                            }
                        }
                    } else {
                        $error;
                    }
                }
            } else if ($data_import == 'Rumah') {
                foreach ($viewpKelulusan as $a) {
                    $rules1 = array(
                        'database'  => null, //Default database master
                        'select'    => null,
                        'where'     => array(
                            'nomor_peserta' => $a->nomor_peserta,
                        ),
                        'or_where'  => null,
                        'like'      => null,
                        'or_like'   => null,
                        'order'     => null,
                        'limit'     => null,
                        'group_by'  => null,
                    );
                    $tbdKelulusan = $this->Tbd_kelulusan->search($rules1);
                    if ($tbdKelulusan->num_rows() > 0) {
                        $tbdKelulusan = $tbdKelulusan->row();
                        if ($tbdKelulusan->daftar == 'BELUM') {
                            $rules2 = array(
                                'database'  => null, //Default database master
                                'select'    => null,
                                'where'     => array(
                                    'idd_kelulusan' => $tbdKelulusan->idd_kelulusan,
                                ),
                                'or_where'  => null,
                                'like'      => null,
                                'or_like'   => null,
                                'order'     => null,
                                'limit'     => null,
                                'group_by'  => null,
                            );
                            $tbdRumah = $this->Tbd_rumah->search($rules2);
                            $tbpFormulir = $this->Tbp_formulir->search($rules1)->row();
                            $rules3 = array(
                                'database'  => null, //Default database master
                                'select'    => null,
                                'where'     => array(
                                    'idp_formulir' => $tbpFormulir->idp_formulir,
                                ),
                                'or_where'  => null,
                                'like'      => null,
                                'or_like'   => null,
                                'order'     => null,
                                'limit'     => null,
                                'group_by'  => null,
                            );
                            $tbpRumah = $this->Tbp_rumah->search($rules3)->row();
                            if ($tbdRumah->num_rows() > 0) {
                                $tbdRumah = $tbdRumah->row();
                                // $rules = array(
                                //     'where'     => array('idd_kelulusan' => $tbdKelulusan->idd_kelulusan),
                                //     'or_where'  => null,
                                //     'like'      => null,
                                //     'or_like'   => null,
                                //     'data'      => array(
                                //         'idd_kelulusan'  => $tbdKelulusan->idd_kelulusan,
                                //         'ids_tanggungan' => 8,
                                //         'ids_rekening_listrik' => 3,
                                //         'ids_daya_listrik' => 4,
                                //         'ids_rekening_pbb' => 3,
                                //         'ids_pembayaran_pbb' => 4,
                                //         'ids_kelurahan' => $tbpRumah->ids_kelurahan,
                                //         'dusun' => $tbpRumah->dusun,
                                //         'rw' => $tbpRumah->rw,
                                //         'rt' => $tbpRumah->rt,
                                //         'jalan' => $tbpRumah->jalan,
                                //         'kode_pos' => $tbpRumah->kode_pos,
                                //         'updated_by'  => 1,
                                //     ),
                                // );
                                // if ($this->Tbd_rumah->update($rules)) {
                                //     $berhasil_update++;
                                // } else {
                                //     $error++;
                                // }
                            } else {
                                $data = array(
                                    'idd_kelulusan'  => $tbdKelulusan->idd_kelulusan,
                                    'ids_tanggungan' => 1,
                                    'ids_rekening_listrik' => 1,
                                    'ids_daya_listrik' => 1,
                                    'ids_rekening_pbb' => 1,
                                    'ids_pembayaran_pbb' => 1,
                                    'ids_kelurahan' => $tbpRumah->ids_kelurahan,
                                    'dusun' => $tbpRumah->dusun,
                                    'rw' => $tbpRumah->rw,
                                    'rt' => $tbpRumah->rt,
                                    'jalan' => $tbpRumah->jalan,
                                    'kode_pos' => $tbpRumah->kode_pos,
                                    'created_by'  => $tbpRumah->created_by,
                                    'updated_by'  => $tbpRumah->updated_by,
                                );
                                $fb = $this->Tbd_rumah->create($data);
                                if (!$fb['status']) {
                                    $berhasil_create++;
                                } else {
                                    $error++;
                                }
                            }
                        }
                    } else {
                        $error;
                    }
                }
            } else if ($data_import == 'Sekolah') {
                foreach ($viewpKelulusan as $a) {
                    $rules1 = array(
                        'database'  => null, //Default database master
                        'select'    => null,
                        'where'     => array(
                            'nomor_peserta' => $a->nomor_peserta,
                        ),
                        'or_where'  => null,
                        'like'      => null,
                        'or_like'   => null,
                        'order'     => null,
                        'limit'     => null,
                        'group_by'  => null,
                    );
                    $tbdKelulusan = $this->Tbd_kelulusan->search($rules1);
                    if ($tbdKelulusan->num_rows() > 0) {
                        $tbdKelulusan = $tbdKelulusan->row();
                        if ($tbdKelulusan->daftar == 'BELUM') {
                            $rules2 = array(
                                'database'  => null, //Default database master
                                'select'    => null,
                                'where'     => array(
                                    'idd_kelulusan' => $tbdKelulusan->idd_kelulusan,
                                ),
                                'or_where'  => null,
                                'like'      => null,
                                'or_like'   => null,
                                'order'     => null,
                                'limit'     => null,
                                'group_by'  => null,
                            );
                            $tbdSekolah = $this->Tbd_sekolah->search($rules2);
                            $tbpFormulir = $this->Tbp_formulir->search($rules1)->row();
                            $rules3 = array(
                                'database'  => null, //Default database master
                                'select'    => null,
                                'where'     => array(
                                    'idp_formulir' => $tbpFormulir->idp_formulir,
                                ),
                                'or_where'  => null,
                                'like'      => null,
                                'or_like'   => null,
                                'order'     => null,
                                'limit'     => null,
                                'group_by'  => null,
                            );
                            $tbpSekolah = $this->Tbp_sekolah->search($rules3)->row();
                            if ($tbdSekolah->num_rows() > 0) {
                                $tbdSekolah = $tbdSekolah->row();
                                // $rules = array(
                                //     'where'     => array('idd_kelulusan' => $tbdKelulusan->idd_kelulusan),
                                //     'or_where'  => null,
                                //     'like'      => null,
                                //     'or_like'   => null,
                                //     'data'      => array(
                                //         'idd_kelulusan'  => $tbdKelulusan->idd_kelulusan,
                                //         'nisn' => $tbpSekolah->nisn,
                                //         'ids_jurusan_sekolah' => $tbpSekolah->ids_jurusan_sekolah,
                                //         'nama_sekolah' => $tbpSekolah->nama_sekolah,
                                //         'akreditasi_sekolah' => $tbpSekolah->akreditasi_sekolah,
                                //         'updated_by'  => 1,
                                //     ),
                                // );
                                // if ($this->Tbd_sekolah->update($rules)) {
                                //     $berhasil_update++;
                                // } else {
                                //     $error++;
                                // }
                            } else {
                                $data = array(
                                    'idd_kelulusan'  => $tbdKelulusan->idd_kelulusan,
                                    'nisn' => $tbpSekolah->nisn,
                                    'ids_jurusan_sekolah' => $tbpSekolah->ids_jurusan_sekolah,
                                    'nama_sekolah' => $tbpSekolah->nama_sekolah,
                                    'akreditasi_sekolah' => $tbpSekolah->akreditasi_sekolah,
                                    'ids_rumpun' => $tbpSekolah->ids_rumpun,
                                    'created_by'  => 1,
                                    'updated_by'  => 1,
                                );
                                $fb = $this->Tbd_sekolah->create($data);
                                if (!$fb['status']) {
                                    $berhasil_create++;
                                } else {
                                    $error++;
                                }
                            }
                        }
                    } else {
                        $error;
                    }
                }
            } else if ($data_import == 'File') {
                foreach ($viewpKelulusan as $a) {
                    $rules1 = array(
                        'database'  => null, //Default database master
                        'select'    => null,
                        'where'     => array(
                            'nomor_peserta' => $a->nomor_peserta,
                        ),
                        'or_where'  => null,
                        'like'      => null,
                        'or_like'   => null,
                        'order'     => null,
                        'limit'     => null,
                        'group_by'  => null,
                    );
                    $tbdKelulusan = $this->Tbd_kelulusan->search($rules1);
                    if ($tbdKelulusan->num_rows() > 0) {
                        $tbdKelulusan = $tbdKelulusan->row();
                        if ($tbdKelulusan->daftar == 'BELUM') {
                            $rules2 = array(
                                'database'  => null, //Default database master
                                'select'    => null,
                                'where'     => array(
                                    'idd_kelulusan' => $tbdKelulusan->idd_kelulusan,
                                    'ids_tipe_file' => 13
                                ),
                                'or_where'  => null,
                                'like'      => null,
                                'or_like'   => null,
                                'order'     => null,
                                'limit'     => null,
                                'group_by'  => null,
                            );
                            $tbdFile = $this->Tbd_file->search($rules2);
                            $tbpFormulir = $this->Tbp_formulir->search($rules1)->row();
                            $rules3 = array(
                                'database'  => null, //Default database master
                                'select'    => null,
                                'where'     => array(
                                    'idp_formulir' => $tbpFormulir->idp_formulir,
                                    'ids_tipe_file' => 13
                                ),
                                'or_where'  => null,
                                'like'      => null,
                                'or_like'   => null,
                                'order'     => null,
                                'limit'     => null,
                                'group_by'  => null,
                            );
                            $tbpFile = $this->Tbp_file->search($rules3)->row();
                            if ($tbdFile->num_rows() > 0) {
                                $tbdFile = $tbdFile->row();
                                // $rules = array(
                                //     'where'     => array('idd_kelulusan' => $tbdKelulusan->idd_kelulusan),
                                //     'or_where'  => null,
                                //     'like'      => null,
                                //     'or_like'   => null,
                                //     'data'      => array(
                                //         'idd_kelulusan'  => $tbdKelulusan->idd_kelulusan,
                                //         'ids_tipe_file' => 13,
                                //         'file' => $tbpFile->file,
                                //         'updated_by'  => 1,
                                //     ),
                                // );
                                // if ($this->Tbd_file->update($rules)) {
                                //     $berhasil_update++;
                                // } else {
                                //     $error++;
                                // }
                            } else {
                                $data = array(
                                    'idd_kelulusan'  => $tbdKelulusan->idd_kelulusan,
                                    'ids_tipe_file' => 13,
                                    'file' => $tbpFile->file,
                                    'created_by'  => $tbpFile->created_by,
                                    'updated_by'  => $tbpFile->updated_by,
                                );
                                $fb = $this->Tbd_file->create($data);
                                if (!$fb['status']) {
                                    $berhasil_create++;
                                } else {
                                    $error++;
                                }
                            }
                        }
                    } else {
                        $error;
                    }
                }
            } else if ($data_import == 'Pendidikan') {
                foreach ($viewpKelulusan as $a) {
                    $rules1 = array(
                        'database'  => null, //Default database master
                        'select'    => null,
                        'where'     => array(
                            'nomor_peserta' => $a->nomor_peserta,
                        ),
                        'or_where'  => null,
                        'like'      => null,
                        'or_like'   => null,
                        'order'     => null,
                        'limit'     => null,
                        'group_by'  => null,
                    );
                    $tbdKelulusan = $this->Tbd_kelulusan->search($rules1);
                    if ($tbdKelulusan->num_rows() > 0) {
                        $tbdKelulusan = $tbdKelulusan->row();
                        if ($tbdKelulusan->daftar == 'BELUM') {
                            $rules2 = array(
                                'database'  => null, //Default database master
                                'select'    => null,
                                'where'     => array(
                                    'idd_kelulusan' => $tbdKelulusan->idd_kelulusan,
                                ),
                                'or_where'  => null,
                                'like'      => null,
                                'or_like'   => null,
                                'order'     => null,
                                'limit'     => null,
                                'group_by'  => null,
                            );
                            $tbdPendidikan = $this->Tbd_pendidikan->search($rules2);
                            $tbpFormulir = $this->Tbp_formulir->search($rules1)->row();
                            $rules3 = array(
                                'database'  => null, //Default database master
                                'select'    => null,
                                'where'     => array(
                                    'idp_formulir' => $tbpFormulir->idp_formulir,
                                ),
                                'or_where'  => null,
                                'like'      => null,
                                'or_like'   => null,
                                'order'     => null,
                                'limit'     => null,
                                'group_by'  => null,
                            );
                            $tbpPendidikan = $this->Tbp_pendidikan->search($rules3)->result();
                            if ($tbdPendidikan->num_rows() > 0) {
                                $tbdPendidikan = $tbdPendidikan->row();
                                // $rules = array(
                                //     'where'     => array('idd_kelulusan' => $tbdKelulusan->idd_kelulusan),
                                //     'or_where'  => null,
                                //     'like'      => null,
                                //     'or_like'   => null,
                                //     'data'      => array(
                                //         'idd_kelulusan'  => $tbdKelulusan->idd_kelulusan,
                                //         'ids_tipe_file' => 13,
                                //         'file' => $tbpFile->file,
                                //         'updated_by'  => 1,
                                //     ),
                                // );
                                // if ($this->Tbd_file->update($rules)) {
                                //     $berhasil_update++;
                                // } else {
                                //     $error++;
                                // }
                            } else {
                                foreach($tbpPendidikan as $b){
                                    $data = array(
                                        'idd_kelulusan'  => $tbdKelulusan->idd_kelulusan,
                                        'jenjang' => $b->jenjang,
                                        'nama_univ' => $b->nama_univ,
                                        'status_univ' => $b->status_univ,
                                        'fakultas' => $b->fakultas,
                                        'jurusan' => $b->jurusan,
                                        'akreditasi' => $b->akreditasi,
                                        'jalur_penyesuaian_studi' => $b->jalur_penyesuaian_studi,
                                        'ipk' => $b->ipk,
                                        'tgl_lulus' => $b->tgl_lulus,
                                        'gelar' => $b->gelar,
                                        'created_by'  => $b->created_by,
                                        'updated_by'  => $b->updated_by,
                                    );
                                    $fb = $this->Tbd_pendidikan->create($data);
                                    if (!$fb['status']) {
                                        $berhasil_create++;
                                    } else {
                                        $error++;
                                    }
                                }
                            }
                        }
                    } else {
                        $error;
                    }
                }
            }
            $this->session->set_flashdata('message', "Import data $data_import berhasil. Berhasil create : $berhasil_create, berhasil update : $berhasil_update, error : $error");
            $this->session->set_flashdata('type_message', 'success');
            redirect('daftar/import/mandiri');
        } else {
            $this->session->set_flashdata('message', 'Hak akses ditolak.');
            $this->session->set_flashdata('type_message', 'danger');
            redirect('dashboard/');
        }
    }
}
