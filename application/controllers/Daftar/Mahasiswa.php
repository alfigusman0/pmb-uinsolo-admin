<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Mahasiswa extends CI_Controller
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

        $this->load->model('ServerSide/SS_daftar');
        $this->load->model('Daftar/Tbd_kelulusan');
        $this->load->model('Daftar/Tbd_file');
        $this->load->model('Daftar/Tbd_mahasiswa');
        $this->load->model('Daftar/Tbd_orangtua');
        $this->load->model('Daftar/Tbd_sekolah');
        $this->load->model('Daftar/Tbd_rumah');
        $this->load->model('Daftar/Tbd_ukt');
        $this->load->model('Daftar/Tbd_pembayaran');
        $this->load->model('Tbl_users');
        $this->load->model('Daftar/Viewd_kelulusan');
        $this->load->model('Daftar/Viewd_mahasiswa');
        $this->load->model('Daftar/Viewd_orangtua');
        $this->load->model('Daftar/Viewd_rumah');
        $this->load->model('Daftar/Viewd_sekolah');
        $this->load->model('Daftar/Viewd_file');
        $this->load->model('Daftar/Viewd_ukt');
        $this->load->model('Daftar/Viewd_pembayaran');
        $this->load->model('Settings/Tbs_tipe_file');
        $this->load->model('Settings/Views_jalur_masuk');
    }

    function index()
    {
        $hak_akses = $this->master->read('hak-akses/?ids_level=' . $this->jwt->ids_level . '&ids_modul=45&aksi_hak_akses=read');
        if ($hak_akses->code == 200) {
            $rules = array(
                'database'      => null, //Default database master
                'select'        => "tahun as tahun", // not null
                'where'         => null,
                'or_where'      => null,
                'like'          => null,
                'or_like'       => null,
                'order'         => 'tahun DESC',
                'group_by'      => null,
            );
            $data = array(
                'title'         => 'Mahasiswa | ' . $_ENV['APPLICATION_NAME'],
                'content'       => 'Daftar/mahasiswa/read/content',
                'css'           => 'Daftar/mahasiswa/read/css',
                'javascript'    => 'Daftar/mahasiswa/read/javascript',
                'modal'         => 'Daftar/mahasiswa/read/modal',

                'tahun'         => $this->Tbd_kelulusan->distinct($rules)->result()
            );
            $this->load->view('index', $data);
        } else {
            $this->session->set_flashdata('message', 'Hak akses ditolak.');
            $this->session->set_flashdata('type_message', 'danger');
            redirect('dashboard/');
        }
    }

    function Detail($id)
    {
        $hak_akses = $this->master->read('hak-akses/?ids_level=' . $this->jwt->ids_level . '&ids_modul=45&aksi_hak_akses=single');
        if ($hak_akses->code == 200) {
            $rules = array(
                'database'  => null, //Default database master
                'select'    => null,
                'where'     => array(
                    'idd_kelulusan' => $id
                ),
                'or_where'  => null,
                'like'      => null,
                'or_like'   => null,
                'order'     => null,
                'limit'     => null,
                'group_by'  => null,
            );
            $viewdKelulusan = $this->Viewd_kelulusan->search($rules)->row();
            $viewdMahasiswa = $this->Viewd_mahasiswa->search($rules);
            $viewdOrangtua = $this->Viewd_orangtua->search($rules);
            $viewdRumah = $this->Viewd_rumah->search($rules);
            $viewdSekolah = $this->Viewd_sekolah->search($rules);
            $viewdUKT = $this->Viewd_ukt->search($rules);
            $viewdPembayaran = $this->Viewd_pembayaran->search($rules);
            $rules = array(
                'database'  => null, //Default database master
                'select'    => null,
                'where'     => array(
                    'id_user' => $viewdKelulusan->id_user
                ),
                'or_where'  => null,
                'like'      => null,
                'or_like'   => null,
                'order'     => null,
                'limit'     => null,
                'group_by'  => null,
            );
            $tblUsers = $this->Tbl_users->search($rules)->row();
            $rules = array(
                'database'  => null, //Default database master
                'select'    => null,
                'where'     => array(
                    'setting' => 'DAFTAR_UKT'
                ),
                'or_where'  => null,
                'like'      => null,
                'or_like'   => null,
                'order'     => null,
                'limit'     => null,
                'group_by'  => null,
            );
            $tbsTipeFile = $this->Tbs_tipe_file->search($rules)->result();
            foreach ($tbsTipeFile as $data) {
                $rules = array(
                    'database'  => null, //Default database master
                    'select'    => null,
                    'where'     => array(
                        'idd_kelulusan' => $id,
                        'ids_tipe_file' => $data->ids_tipe_file
                    ),
                    'or_where'  => null,
                    'like'      => null,
                    'or_like'   => null,
                    'order'     => null,
                    'limit'     => null,
                    'group_by'  => null,
                );
                $viewdFile[$data->ids_tipe_file] = $this->Viewd_file->search($rules);
            }
            $rules = array(
                'database'  => null, //Default database master
                'select'    => null,
                'where'     => array(
                    'setting' => 'PEMBERKASAN'
                ),
                'or_where'  => null,
                'like'      => array(
                    "CONCAT(',',jalur_masuk,',')" => ','.$viewdKelulusan->ids_jalur_masuk.','
                ),
                'or_like'   => null,
                'order'     => null,
                'limit'     => null,
                'group_by'  => null,
            );
            $tbsTipeFile2 = $this->Tbs_tipe_file->search($rules)->result();
            $viewdFilePemberkasan = null;
            foreach ($tbsTipeFile2 as $data2) {
                $rules = array(
                    'database'  => null, //Default database master
                    'select'    => null,
                    'where'     => array(
                        'idd_kelulusan' => $id,
                        'ids_tipe_file' => $data2->ids_tipe_file
                    ),
                    'or_where'  => null,
                    'like'      => null,
                    'or_like'   => null,
                    'order'     => null,
                    'limit'     => null,
                    'group_by'  => null,
                );
                $viewdFilePemberkasan[$data2->ids_tipe_file] = $this->Viewd_file->search($rules);
            }
            $rules = array(
                'database'  => null, //Default database master
                'select'    => null,
                'where'     => array(
                    'idd_kelulusan' => $id,
                    'ids_tipe_file' => '13'
                ),
                'or_where'  => null,
                'like'      => null,
                'or_like'   => null,
                'order'     => null,
                'limit'     => null,
                'group_by'  => null,
            );
            $viewdFileFoto = $this->Viewd_file->search($rules);

            $data = array(
                'title'         => 'Detail Mahasiswa | ' . $_ENV['APPLICATION_NAME'],
                'content'       => 'Daftar/mahasiswa/detail/content',
                'css'           => 'Daftar/mahasiswa/detail/css',
                'javascript'    => 'Daftar/mahasiswa/detail/javascript',
                'modal'         => 'Daftar/mahasiswa/detail/modal',
                'viewdKelulusan' => $viewdKelulusan,
                'viewdMahasiswa' => $viewdMahasiswa,
                'viewdOrangtua' => $viewdOrangtua,
                'viewdRumah' => $viewdRumah,
                'viewdSekolah' => $viewdSekolah,
                'viewdFile' => $viewdFile,
                'viewdFilePemberkasan' => $viewdFilePemberkasan,
                'viewdUKT' => $viewdUKT,
                'viewdPembayaran' => $viewdPembayaran,
                'tblUsers' => $tblUsers,
                'tbsTipeFile' => $tbsTipeFile,
                'tbsTipeFile2' => $tbsTipeFile2,
                'viewdFileFoto' => $viewdFileFoto,
            );
            $this->load->view('index', $data);
        } else {
            $this->session->set_flashdata('message', 'Hak akses ditolak.');
            $this->session->set_flashdata('type_message', 'danger');
            redirect('dashboard/');
        }
    }

    function simpanDataDiri()
    {
        $rules = array(
            'select'    => null,
            'where'     => array(
                'idd_kelulusan' => $this->input->post('idd_kelulusan')
            ),
            'or_where'  => null,
            'like'      => null,
            'or_like'   => null,
            'order'     => null,
            'limit'     => null,
            'group_by'  => null,
        );
        $tbdMahasiswa = $this->Tbd_mahasiswa->search($rules);
        if ($tbdMahasiswa->num_rows() == 0) {
            $hak_akses = $this->master->read('hak-akses/?ids_level=' . $this->jwt->ids_level . '&ids_modul=45&aksi_hak_akses=create');
            if ($hak_akses->code == 200) {
                $data = array(
                    'idd_kelulusan'  => $this->input->post('idd_kelulusan'),
                    'nik'  => $this->input->post('nik'),
                    'nim'  => '0',
                    'jenis_kelamin'  => $this->input->post('jenis_kelamin'),
                    'tempat_lahir'  => $this->input->post('tempat_lahir'),
                    'tgl_lahir'  => date("Y-m-d", strtotime($this->input->post('tgl_lahir'))),
                    'ids_agama'  => $this->input->post('ids_agama'),
                    'kewarganegaraan'  => $this->input->post('kewarganegaraan'),
                    'ids_jenis_tinggal'  => $this->input->post('ids_jenis_tinggal'),
                    'ids_alat_transportasi'  => $this->input->post('ids_alat_transportasi'),
                    'terima_kps'  => $this->input->post('terima_kps'),
                    'no_kps'  => $this->input->post('no_kps'),
                    'ids_jenis_pendaftaran'  => $this->input->post('ids_jenis_pendaftaran'),
                    'ids_rumpun'  => $this->input->post('ids_rumpun'),
                    'ids_jenis_pembiayaan'  => $this->input->post('ids_jenis_pembiayaan'),
                    'ids_hubungan'  => $this->input->post('ids_hubungan'),
                    'ukuran_baju'  => 'S',
                    'ukuran_jas'  => 'S',
                    'created_by'  => $this->jwt->id_user,
                    'updated_by'  => $this->jwt->id_user,
                );
                $fb = $this->Tbd_mahasiswa->create($data);
                if (!$fb['status']) {
                    $response = array(
                        'status' => 200,
                        'message' => 'Data berhasil disimpan.'
                    );
                } else {
                    $response = array(
                        'status' => 400,
                        'message' => 'Data gagal disimpan.'
                    );
                }
            } else {
                $response = array(
                    'status' => 401,
                    'message' => 'Hak akses ditolak.'
                );
            }
        } else {
            $hak_akses = $this->master->read('hak-akses/?ids_level=' . $this->jwt->ids_level . '&ids_modul=45&aksi_hak_akses=update');
            if ($hak_akses->code == 200) {
                $tbdMahasiswa = $tbdMahasiswa->row();
                $rules = array(
                    'where'     => array('idd_kelulusan' => $tbdMahasiswa->idd_kelulusan),
                    'or_where'  => null,
                    'like'      => null,
                    'or_like'   => null,
                    'data'                => array(
                        'nik'  => $this->input->post('nik'),
                        'jenis_kelamin'  => $this->input->post('jenis_kelamin'),
                        'tempat_lahir'  => $this->input->post('tempat_lahir'),
                        'tgl_lahir'  => date("Y-m-d", strtotime($this->input->post('tgl_lahir'))),
                        'ids_agama'  => $this->input->post('ids_agama'),
                        'kewarganegaraan'  => $this->input->post('kewarganegaraan'),
                        'ids_jenis_tinggal'  => $this->input->post('ids_jenis_tinggal'),
                        'ids_alat_transportasi'  => $this->input->post('ids_alat_transportasi'),
                        'terima_kps'  => $this->input->post('terima_kps'),
                        'no_kps'  => $this->input->post('no_kps'),
                        'ids_jenis_pendaftaran'  => $this->input->post('ids_jenis_pendaftaran'),
                        'ids_rumpun'  => $this->input->post('ids_rumpun'),
                        'ids_jenis_pembiayaan'  => $this->input->post('ids_jenis_pembiayaan'),
                        'ids_hubungan'  => $this->input->post('ids_hubungan'),
                        'updated_by'  => $this->jwt->id_user,
                    ),
                );
                $fb = $this->Tbd_mahasiswa->update($rules);
                if (!$fb['status']) {
                    $response = array(
                        'status' => 200,
                        'message' => 'Data berhasil diupdate.'
                    );
                } else {
                    $response = array(
                        'status' => 400,
                        'message' => 'Data gagal diupdate.'
                    );
                }
            } else {
                $response = array(
                    'status' => 401,
                    'message' => 'Hak akses ditolak.'
                );
            }
        }
        echo json_encode($response);
    }

    function simpanDataSekolah()
    {
        $rules = array(
            'select'    => null,
            'where'     => array(
                'idd_kelulusan' => $this->input->post('idd_kelulusan')
            ),
            'or_where'  => null,
            'like'      => null,
            'or_like'   => null,
            'order'     => null,
            'limit'     => null,
            'group_by'  => null,
        );
        $tbdSekolah = $this->Tbd_sekolah->search($rules);
        if ($tbdSekolah->num_rows() == 0) {
            $hak_akses = $this->master->read('hak-akses/?ids_level=' . $this->jwt->ids_level . '&ids_modul=45&aksi_hak_akses=create');
            if ($hak_akses->code == 200) {
                $data = array(
                    'idd_kelulusan' => $this->input->post('idd_kelulusan'),
                    'nisn'  => $this->input->post('nisn'),
                    'ids_jurusan_sekolah'  => $this->input->post('ids_jurusan_sekolah'),
                    'nama_sekolah'  => $this->input->post('nama_sekolah'),
                    'akreditasi_sekolah'  => $this->input->post('akreditasi_sekolah'),
                    'created_by'  => $this->jwt->id_user,
                    'updated_by'  => $this->jwt->id_user,
                );
                $fb = $this->Tbd_sekolah->create($data);
                if (!$fb['status']) {
                    $response = array(
                        'status' => 200,
                        'message' => 'Data berhasil disimpan.'
                    );
                } else {
                    $response = array(
                        'status' => 400,
                        'message' => 'Data gagal disimpan.'
                    );
                }
            } else {
                $response = array(
                    'status' => 401,
                    'message' => 'Hak akses ditolak.'
                );
            }
        } else {
            $hak_akses = $this->master->read('hak-akses/?ids_level=' . $this->jwt->ids_level . '&ids_modul=45&aksi_hak_akses=update');
            if ($hak_akses->code == 200) {
                $tbdSekolah = $tbdSekolah->row();
                $rules = array(
                    'where'     => array('idd_kelulusan' => $tbdSekolah->idd_kelulusan),
                    'or_where'  => null,
                    'like'      => null,
                    'or_like'   => null,
                    'data'                => array(
                        'nisn'  => $this->input->post('nisn'),
                        'ids_jurusan_sekolah'  => $this->input->post('ids_jurusan_sekolah'),
                        'nama_sekolah'  => $this->input->post('nama_sekolah'),
                        'akreditasi_sekolah'  => $this->input->post('akreditasi_sekolah'),
                        'updated_by'  => $this->jwt->id_user,
                    ),
                );
                $fb = $this->Tbd_sekolah->update($rules);
                if (!$fb['status']) {
                    $response = array(
                        'status' => 200,
                        'message' => 'Data berhasil diupdate.'
                    );
                } else {
                    $response = array(
                        'status' => 400,
                        'message' => 'Data gagal diupdate.'
                    );
                }
            } else {
                $response = array(
                    'status' => 401,
                    'message' => 'Hak akses ditolak.'
                );
            }
        }
        echo json_encode($response);
    }

    function simpanDataOrangtua()
    {
        $rules = array(
            'select'    => null,
            'where'     => array(
                'idd_kelulusan' => $this->input->post('idd_kelulusan'),
                'orangtua' => $this->input->post('orangtua')
            ),
            'or_where'  => null,
            'like'      => null,
            'or_like'   => null,
            'order'     => null,
            'limit'     => null,
            'group_by'  => null,
        );
        $tbdOrangtua = $this->Tbd_orangtua->search($rules);
        if ($tbdOrangtua->num_rows() == 0) {
            $hak_akses = $this->master->read('hak-akses/?ids_level=' . $this->jwt->ids_level . '&ids_modul=45&aksi_hak_akses=create');
            if ($hak_akses->code == 200) {
                $data = array(
                    'idd_kelulusan' => $this->input->post('idd_kelulusan'),
                    'orangtua'  => $this->input->post('orangtua'),
                    'nik_orangtua'  => $this->input->post('nik_orangtua'),
                    'nama_orangtua'  => $this->input->post('nama_orangtua'),
                    'tgl_lahir_orangtua'  => date("Y-m-d", strtotime($this->input->post('tgl_lahir_orangtua'))),
                    'ids_pendidikan'  => $this->input->post('ids_pendidikan'),
                    'ids_pekerjaan'  => $this->input->post('ids_pekerjaan'),
                    'ids_penghasilan'  => $this->input->post('ids_penghasilan'),
                    'nominal_penghasilan'  => preg_replace('/[Rp. ]/', '', $this->input->post('nominal_penghasilan')),
                    'terbilang_penghasilan'  => $this->input->post('terbilang_penghasilan'),
                    'created_by'  => $this->jwt->id_user,
                    'updated_by'  => $this->jwt->id_user,
                );
                $fb = $this->Tbd_orangtua->create($data);
                if (!$fb['status']) {
                    $response = array(
                        'status' => 200,
                        'message' => 'Data berhasil disimpan.'
                    );
                } else {
                    $response = array(
                        'status' => 400,
                        'message' => 'Data gagal disimpan.'
                    );
                }
            } else {
                $response = array(
                    'status' => 401,
                    'message' => 'Hak akses ditolak.'
                );
            }
        } else {
            $hak_akses = $this->master->read('hak-akses/?ids_level=' . $this->jwt->ids_level . '&ids_modul=45&aksi_hak_akses=update');
            if ($hak_akses->code == 200) {
                $tbdOrangtua = $tbdOrangtua->row();
                $rules = array(
                    'where'     => array(
                        'idd_kelulusan' => $tbdOrangtua->idd_kelulusan,
                        'orangtua' => $this->input->post('orangtua')
                    ),
                    'or_where'  => null,
                    'like'      => null,
                    'or_like'   => null,
                    'data'                => array(
                        'orangtua'  => $this->input->post('orangtua'),
                        'nik_orangtua'  => $this->input->post('nik_orangtua'),
                        'nama_orangtua'  => $this->input->post('nama_orangtua'),
                        'tgl_lahir_orangtua'  => date("Y-m-d", strtotime($this->input->post('tgl_lahir_orangtua'))),
                        'ids_pendidikan'  => $this->input->post('ids_pendidikan'),
                        'ids_pekerjaan'  => $this->input->post('ids_pekerjaan'),
                        'ids_penghasilan'  => $this->input->post('ids_penghasilan'),
                        'nominal_penghasilan'  => preg_replace('/[Rp. ]/', '', $this->input->post('nominal_penghasilan')),
                        'terbilang_penghasilan'  => $this->input->post('terbilang_penghasilan'),
                        'updated_by'  => $this->jwt->id_user,
                    ),
                );
                $fb = $this->Tbd_orangtua->update($rules);
                if (!$fb['status']) {
                    $rules = array(
                        'where'     => array(
                            'idd_kelulusan' => $tbdOrangtua->idd_kelulusan,
                        ),
                        'or_where'  => null,
                        'like'      => null,
                        'or_like'   => null,
                        'data'                => array(
                            'ids_tanggungan'  => $this->input->post('ids_tanggungan'),
                            'updated_by'  => $this->jwt->id_user,
                        ),
                    );
                    $fb = $this->Tbd_rumah->update($rules);
                    if (!$fb['status']) {
                        $response = array(
                            'status' => 200,
                            'message' => 'Data berhasil diupdate.'
                        );
                    } else {
                        $response = array(
                            'status' => 400,
                            'message' => 'Data gagal diupdate.'
                        );
                    }
                } else {
                    $response = array(
                        'status' => 400,
                        'message' => 'Data gagal diupdate.'
                    );
                }
            } else {
                $response = array(
                    'status' => 401,
                    'message' => 'Hak akses ditolak.'
                );
            }
        }
        echo json_encode($response);
    }

    function hapusDataOrangtua()
    {
        $rules = array(
            'select'    => null,
            'where'     => array(
                'idd_kelulusan' => $this->input->post('idd_kelulusan'),
                'orangtua' => $this->input->post('orangtua')
            ),
            'or_where'  => null,
            'like'      => null,
            'or_like'   => null,
            'order'     => null,
            'limit'     => null,
            'group_by'  => null,
        );
        $tbdOrangtua = $this->Tbd_orangtua->search($rules);
        if ($tbdOrangtua->num_rows() == 0) {
            $response = array(
                'status' => 200,
                'message' => 'Data ' . $this->input->post('orangtua') . ' tidak ada.'
            );
        } else {
            $rules = array(
                'where'     => array(
                    'idd_kelulusan' => $this->input->post('idd_kelulusan'),
                    'orangtua' => $this->input->post('orangtua')
                ),
                'or_where'  => null,
                'like'      => null,
                'or_like'   => null,
            );
            if ($this->Tbd_orangtua->delete($rules)) {
                $response = array(
                    'status' => 200,
                    'message' => 'Data ' . $this->input->post('orangtua') . ' berhasil dihapus.'
                );
            } else {
                $response = array(
                    'status' => 400,
                    'message' => 'Data ' . $this->input->post('orangtua') . ' gagal disimpan.'
                );
            }
        }
        echo json_encode($response);
    }

    function simpanDataRumah()
    {
        $rules = array(
            'select'    => null,
            'where'     => array(
                'idd_kelulusan' => $this->input->post('idd_kelulusan')
            ),
            'or_where'  => null,
            'like'      => null,
            'or_like'   => null,
            'order'     => null,
            'limit'     => null,
            'group_by'  => null,
        );
        $tbdRumah = $this->Tbd_rumah->search($rules);
        if ($tbdRumah->num_rows() == 0) {
            $hak_akses = $this->master->read('hak-akses/?ids_level=' . $this->jwt->ids_level . '&ids_modul=45&aksi_hak_akses=create');
            if ($hak_akses->code == 200) {
                $data = array(
                    'idd_kelulusan' => $this->input->post('idd_kelulusan'),
                    'ids_tanggungan'  => 1,
                    'ids_rekening_listrik'  => $this->input->post('ids_rekening_listrik'),
                    'ids_daya_listrik'  => $this->input->post('ids_daya_listrik'),
                    'ids_rekening_pbb'  => $this->input->post('ids_rekening_pbb'),
                    'ids_pembayaran_pbb'  => $this->input->post('ids_pembayaran_pbb'),
                    'ids_kelurahan'  => $this->input->post('ids_kelurahan'),
                    'dusun'  => $this->input->post('dusun'),
                    'rw'  => $this->input->post('rw'),
                    'rt'  => $this->input->post('rt'),
                    'jalan'  => $this->input->post('jalan'),
                    'kode_pos'  => $this->input->post('kode_pos'),
                    'created_by'  => $this->jwt->id_user,
                    'updated_by'  => $this->jwt->id_user,
                );
                $fb = $this->Tbd_rumah->create($data);
                if (!$fb['status']) {
                    $response = array(
                        'status' => 200,
                        'message' => 'Data berhasil disimpan.'
                    );
                } else {
                    $response = array(
                        'status' => 400,
                        'message' => 'Data gagal disimpan.'
                    );
                }
            } else {
                $response = array(
                    'status' => 401,
                    'message' => 'Hak akses ditolak.'
                );
            }
        } else {
            $hak_akses = $this->master->read('hak-akses/?ids_level=' . $this->jwt->ids_level . '&ids_modul=45&aksi_hak_akses=update');
            if ($hak_akses->code == 200) {
                $tbdRumah = $tbdRumah->row();
                $rules = array(
                    'where'     => array('idd_kelulusan' => $tbdRumah->idd_kelulusan),
                    'or_where'  => null,
                    'like'      => null,
                    'or_like'   => null,
                    'data'                => array(
                        'ids_rekening_listrik'  => $this->input->post('ids_rekening_listrik'),
                        'ids_daya_listrik'  => $this->input->post('ids_daya_listrik'),
                        'ids_rekening_pbb'  => $this->input->post('ids_rekening_pbb'),
                        'ids_pembayaran_pbb'  => $this->input->post('ids_pembayaran_pbb'),
                        'ids_kelurahan'  => $this->input->post('ids_kelurahan'),
                        'dusun'  => $this->input->post('dusun'),
                        'rw'  => $this->input->post('rw'),
                        'rt'  => $this->input->post('rt'),
                        'jalan'  => $this->input->post('jalan'),
                        'kode_pos'  => $this->input->post('kode_pos'),
                        'updated_by'  => $this->jwt->id_user,
                    ),
                );
                $fb = $this->Tbd_rumah->update($rules);
                if (!$fb['status']) {
                    $response = array(
                        'status' => 200,
                        'message' => 'Data berhasil diupdate.'
                    );
                } else {
                    $response = array(
                        'status' => 400,
                        'message' => 'Data gagal diupdate.'
                    );
                }
            } else {
                $response = array(
                    'status' => 401,
                    'message' => 'Hak akses ditolak.'
                );
            }
        }
        echo json_encode($response);
    }

    function simpanDataBerkas()
    {
        $this->_validate();
        $rules = array(
            'select'    => null,
            'where'     => array(
                'ids_tipe_file' => $this->input->post('ids_tipe_file_upload')
            ),
            'or_where'  => null,
            'like'      => null,
            'or_like'   => null,
            'order'     => null,
            'limit'     => null,
            'group_by'  => null,
        );
        $tbsTipeFile = $this->Tbs_tipe_file->search($rules)->row();
        $rules = array(
            'select'    => null,
            'where'     => array(
                'id_user' => $this->jwt->id_user
            ),
            'or_where'  => null,
            'like'      => null,
            'or_like'   => null,
            'order'     => null,
            'limit'     => null,
            'group_by'  => null,
        );
        $tbdKelulusan = $this->Tbd_kelulusan->search($rules)->row();
        $file_name = $this->input->post('ids_tipe_file_upload') . "_" . time();
        $dir = './upload/daftar/' . date('Y') . '/';
        if (!file_exists($dir)) {
            mkdir($dir, 0777, true);
        }
        $config = array(
            'upload_path'   => $dir,
            'allowed_types' => $tbsTipeFile->extensi . '|' . strtolower($tbsTipeFile->extensi),
            'max_size'      => $tbsTipeFile->max_size,
            'overwrite'     => TRUE,
            'file_name'     => $file_name,
        );
        $this->upload->initialize($config);
        if (!$this->upload->do_upload('file_upload')) {
            $response = array(
                'status' => 204,
                'message' => $this->upload->display_errors()
            );
        } else {
            $file = $this->upload->data();
            $rules = array(
                'select'    => null,
                'where'     => array(
                    'idd_kelulusan' => $tbdKelulusan->idd_kelulusan,
                    'ids_tipe_file' => $this->input->post('ids_tipe_file_upload')
                ),
                'or_where'  => null,
                'like'      => null,
                'or_like'   => null,
                'order'     => null,
                'limit'     => null,
                'group_by'  => null,
            );
            $tbdFile = $this->Tbd_file->search($rules);
            if ($tbdFile->num_rows() == 0) {
                $hak_akses = $this->master->read('hak-akses/?ids_level=' . $this->jwt->ids_level . '&ids_modul=45&aksi_hak_akses=create');
                if ($hak_akses->code == 200) {
                    $data = array(
                        'idd_kelulusan' => $tbdKelulusan->idd_kelulusan,
                        'ids_tipe_file'  => $this->input->post('ids_tipe_file_upload'),
                        'file'  => $file['file_name'],
                        'created_by'  => $this->jwt->id_user,
                        'updated_by'  => $this->jwt->id_user,
                    );
                    $fb = $this->Tbd_file->create($data);
                    if (!$fb['status']) {
                        $response = array(
                            'status' => 200,
                            'message' => 'Berkas berhasil diupload.'
                        );
                    } else {
                        $response = array(
                            'status' => 400,
                            'message' => 'Berkas gagal diupload.'
                        );
                    }
                } else {
                    $response = array(
                        'status' => 401,
                        'message' => 'Hak akses ditolak.'
                    );
                }
            } else {
                $hak_akses = $this->master->read('hak-akses/?ids_level=' . $this->jwt->ids_level . '&ids_modul=45&aksi_hak_akses=update');
                if ($hak_akses->code == 200) {
                    $tbdFile = $tbdFile->row();
                    $rules = array(
                        'where'     => array('idd_file' => $tbdFile->idd_file),
                        'or_where'  => null,
                        'like'      => null,
                        'or_like'   => null,
                        'data'                => array(
                            'file'  => $file['file_name'],
                            'updated_by'  => $this->jwt->id_user,
                        ),
                    );
                    $fb = $this->Tbd_file->update($rules);
                    if (!$fb['status']) {
                        $response = array(
                            'status' => 200,
                            'message' => 'Berkas berhasil diupdate.'
                        );
                    } else {
                        $response = array(
                            'status' => 400,
                            'message' => 'Berkas gagal diupdate.'
                        );
                    }
                } else {
                    $response = array(
                        'status' => 401,
                        'message' => 'Hak akses ditolak.'
                    );
                }
            }
        }
        echo json_encode($response);
    }

    function simpanDataFoto()
    {
        $this->_validate('foto');
        $ids_tipe_file = 13;
        $rules = array(
            'select'    => null,
            'where'     => array(
                'ids_tipe_file' => $ids_tipe_file
            ),
            'or_where'  => null,
            'like'      => null,
            'or_like'   => null,
            'order'     => null,
            'limit'     => null,
            'group_by'  => null,
        );
        $tbsTipeFile = $this->Tbs_tipe_file->search($rules)->row();
        $rules = array(
            'select'    => null,
            'where'     => array(
                'id_user' => $this->jwt->id_user
            ),
            'or_where'  => null,
            'like'      => null,
            'or_like'   => null,
            'order'     => null,
            'limit'     => null,
            'group_by'  => null,
        );
        $tbdKelulusan = $this->Tbd_kelulusan->search($rules)->row();
        if (!empty($_FILES["file_upload"]['name'])) {
            $file_name = $ids_tipe_file . "_" . time();
            $dir = './upload/daftar/' . date('Y') . '/';
            if (!file_exists($dir)) {
                mkdir($dir, 0777, true);
            }
            $config = array(
                'upload_path'   => $dir,
                'allowed_types' => $tbsTipeFile->extensi . '|' . strtolower($tbsTipeFile->extensi),
                'max_size'      => $tbsTipeFile->max_size,
                'overwrite'     => TRUE,
                'file_name'     => $file_name,
            );
            $this->upload->initialize($config);
            if (!$this->upload->do_upload('file_upload')) {
                $response = array(
                    'status' => 204,
                    'message' => $this->upload->display_errors()
                );
            } else {
                $file = $this->upload->data();
                $rules = array(
                    'select'    => null,
                    'where'     => array(
                        'idd_kelulusan' => $tbdKelulusan->idd_kelulusan,
                        'ids_tipe_file' => $ids_tipe_file
                    ),
                    'or_where'  => null,
                    'like'      => null,
                    'or_like'   => null,
                    'order'     => null,
                    'limit'     => null,
                    'group_by'  => null,
                );
                $tbdFile = $this->Tbd_file->search($rules);
                if ($tbdFile->num_rows() == 0) {
                    $hak_akses = $this->master->read('hak-akses/?ids_level=' . $this->jwt->ids_level . '&ids_modul=45&aksi_hak_akses=create');
                    if ($hak_akses->code == 200) {
                        $data = array(
                            'idd_kelulusan' => $tbdKelulusan->idd_kelulusan,
                            'ids_tipe_file'  => $ids_tipe_file,
                            'file'  => $file['file_name'],
                            'created_by'  => $this->jwt->id_user,
                            'updated_by'  => $this->jwt->id_user,
                        );
                        $fb = $this->Tbd_file->create($data);
                        if (!$fb['status']) {
                            $response = array(
                                'status' => 200,
                                'message' => 'Foto berhasil diupload.'
                            );
                        } else {
                            $response = array(
                                'status' => 400,
                                'message' => 'Foto gagal diupload.'
                            );
                        }
                    } else {
                        $response = array(
                            'status' => 401,
                            'message' => 'Hak akses ditolak.'
                        );
                    }
                } else {
                    $hak_akses = $this->master->read('hak-akses/?ids_level=' . $this->jwt->ids_level . '&ids_modul=45&aksi_hak_akses=update');
                    if ($hak_akses->code == 200) {
                        $tbdFile = $tbdFile->row();
                        $rules = array(
                            'where'     => array('idd_file' => $tbdFile->idd_file),
                            'or_where'  => null,
                            'like'      => null,
                            'or_like'   => null,
                            'data'                => array(
                                'file'  => $file['file_name'],
                                'updated_by'  => $this->jwt->id_user,
                            ),
                        );
                        $fb = $this->Tbd_file->update($rules);
                        if (!$fb['status']) {
                            $response = array(
                                'status' => 200,
                                'message' => 'Foto berhasil diupdate.'
                            );
                        } else {
                            $response = array(
                                'status' => 400,
                                'message' => 'Foto gagal diupdate.'
                            );
                        }
                    } else {
                        $response = array(
                            'status' => 401,
                            'message' => 'Hak akses ditolak.'
                        );
                    }
                }
            }
        } else {
            $rules = array(
                'select'    => null,
                'where'     => array(
                    'idd_kelulusan' => $tbdKelulusan->idd_kelulusan,
                    'ids_tipe_file' => $ids_tipe_file
                ),
                'or_where'  => null,
                'like'      => null,
                'or_like'   => null,
                'order'     => null,
                'limit'     => null,
                'group_by'  => null,
            );
            $tbdFile = $this->Tbd_file->search($rules);
            if ($tbdFile->num_rows() == 0) {
                $response = array(
                    'status' => 400,
                    'message' => 'Foto wajib diisi.'
                );
            } else {
                $response = array(
                    'status' => 200,
                    'message' => 'Foto sudah diupload.'
                );
            }
        }
        echo json_encode($response);
    }

    function simpanDataKelulusan()
    {
        $hak_akses = $this->master->read('hak-akses/?ids_level=' . $this->jwt->ids_level . '&ids_modul=45&aksi_hak_akses=update');
        if ($hak_akses->code == 200) {
            $data = array();
            $data['nama']  = $this->input->post('nama');
            $data['submit']  = $this->input->post('submit');
            $data['daftar']  = $this->input->post('daftar');
            $data['pembayaran']  = $this->input->post('pembayaran');
            $data['pemberkasan']  = $this->input->post('pemberkasan');
            $data['ket_pembayaran']  = $this->input->post('ket_pembayaran');
            if ($this->input->post('pembayaran') == 'SUDAH') {
                $data['tgl_pembayaran'] = $this->input->post('tgl_pembayaran');
            } else {
                $data['tgl_pembayaran'] = NULL;
            }

            $rules = array(
                'where'     => array('idd_kelulusan' => $this->input->post('idd_kelulusan')),
                'or_where'  => null,
                'like'      => null,
                'or_like'   => null,
                'data'      => $data
            );
            $fb = $this->Tbd_kelulusan->update($rules);
            if (!$fb['status']) {
                $response = array(
                    'status' => 200,
                    'message' => 'Data berhasil diupdate.'
                );
            } else {
                $response = array(
                    'status' => 400,
                    'message' => 'Data gagal diupdate.'
                );
            }
        } else {
            $response = array(
                'status' => 401,
                'message' => 'Hak akses ditolak.'
            );
        }
        echo json_encode($response);
    }

    function PindahKelulusan()
    {
        $idd_kelulusan_asal = $this->input->post('idd_kelulusan');
        $idd_kelulusan_tujuan = $this->input->post('pencarian_kelulusan');
        $rules = array(
            'database'  => null,
            'select'    => null,
            'where'     => array(
                'idd_kelulusan' => $idd_kelulusan_asal
            ),
            'or_where'  => null,
            'like'      => null,
            'or_like'   => null,
            'order'     => null,
            'limit'     => null,
            'group_by'  => null,
        );
        $viewdUKT_asal = $this->Viewd_ukt->search($rules)->row();
        $viewdKelulusan_asal = $this->Viewd_kelulusan->search($rules)->row();
        $rules = array(
            'database'  => null,
            'select'    => null,
            'where'     => array(
                'idd_kelulusan' => $idd_kelulusan_tujuan
            ),
            'or_where'  => null,
            'like'      => null,
            'or_like'   => null,
            'order'     => null,
            'limit'     => null,
            'group_by'  => null,
        );
        $viewdUKT_tujuan = $this->Viewd_ukt->search($rules);
        if($viewdUKT_tujuan->num_rows() == 0){
            $response = array(
                'status' => 400,
                'message' => 'Peserta tujuan tidak ditemukan.'
            );
            echo json_encode($response);
            exit();
        }
        $viewdUKT_tujuan = $viewdUKT_tujuan->row();
        $viewdKelulusan_tujuan = $this->Viewd_kelulusan->search($rules)->row();
        if($viewdUKT_asal->submit != "SUDAH"){
            $response = array(
                'status' => 400,
                'message' => 'Peserta asal belum melakukan submit.'
            );
            echo json_encode($response);
            exit();
        }
        if($viewdUKT_asal->pembayaran != "SUDAH"){
            $response = array(
                'status' => 400,
                'message' => 'Peserta asal belum melakukan pembayaran.'
            );
            echo json_encode($response);
            exit();
        }
        if($viewdUKT_tujuan->pembayaran == "SUDAH"){
            $response = array(
                'status' => 400,
                'message' => 'Peserta tujuan sudah melakukan pembayaran.'
            );
            echo json_encode($response);
            exit();
        }
        $selisih = (int)$viewdUKT_asal->jumlah-(int)$viewdUKT_tujuan->jumlah;
        if($selisih > 0){
            $status = "lebih";
            $keterangan = 'Pembayaran '.$viewdUKT_asal->alias_jalur_masuk.' dialihkan ke Jalur '.$viewdUKT_tujuan->alias_jalur_masuk.' nomor pendaftaran : '.$viewdUKT_asal->nomor_peserta.', dengan kelebihan bayar Rp. '.number_format($selisih,2,',','.');
            $potongan = $viewdUKT_tujuan->jumlah;
        }else if($selisih == 0){
            $status = 'sama';
            $keterangan = 'Pembayaran '.$viewdUKT_asal->alias_jalur_masuk.' dialihkan ke Jalur '.$viewdUKT_tujuan->alias_jalur_masuk.' nomor pendaftaran : '.$viewdUKT_asal->nomor_peserta;
            $potongan = $viewdUKT_asal->jumlah;
        }else{
            $status = 'kurang';
            $keterangan = 'Pembayaran ini dialihkan dari Jalur '.$viewdUKT_asal->alias_jalur_masuk.' Nomor Pendaftaran '.$viewdUKT_asal->nomor_peserta.' ke Jalur '.$viewdUKT_tujuan->alias_jalur_masuk.' Nomor Pendaftaran '.$viewdUKT_tujuan->nomor_peserta.' Tagihan ini dipotong dari jumlah yang sudah dibayarkan sebesar Rp. '.number_format($viewdUKT_asal->jumlah,2,',','.').'.';
            $potongan = $viewdUKT_asal->jumlah;
        }
        $request = array(
            "url" => 'https://salam.uinsgd.ac.id/api/tagihan/pindah', //Not Null
            "method" => 'POST', // GET, POST, PUT, PATCH, DELETE
            "header" => array(
                'Authorization: e418c1f7666f37f93192beb0fe06cae6',
                'Content-Type: application/x-www-form-urlencoded'                            
            ),
            "request" => http_build_query(array(
                'no_pendaftaran_awal' => $viewdUKT_asal->nomor_peserta,
                'no_pendaftaran_pindah' => $viewdUKT_tujuan->nomor_peserta,
                'potongan' => $potongan,
                'status' => $status,
                'keterangan' => $keterangan
            )),
        );
        $fb5 = $this->utilities->curl($request);
        if($fb5->status->code == 200){
            if($selisih > 0){
                $rules = array(
                    'where'     => array('idd_kelulusan' => $idd_kelulusan_asal),
                    'or_where'  => null,
                    'like'      => null,
                    'or_like'   => null,
                    'data'      => array(
                        'pembayaran' => 'PINDAH',
                        'ket_pembayaran' => 'Pembayaran ini dialihkan ke Jalur '.$viewdUKT_tujuan->alias_jalur_masuk,
                        'tgl_pembayaran' => null,
                        'updated_by' => $this->jwt->ids_user
                    )
                );
            }else{
                $rules = array(
                    'where'     => array('idd_kelulusan' => $idd_kelulusan_asal),
                    'or_where'  => null,
                    'like'      => null,
                    'or_like'   => null,
                    'data'      => array(
                        'pembayaran' => 'PINDAH',
                        'ket_pembayaran' => $keterangan,
                        'tgl_pembayaran' => null,
                        'updated_by' => $this->jwt->ids_user
                    )
                );
            }
            $fb = $this->Tbd_kelulusan->update($rules);
            if(!$fb['status']){
                $rules = array(
                    'where'     => array('idd_kelulusan' => $idd_kelulusan_tujuan),
                    'or_where'  => null,
                    'like'      => null,
                    'or_like'   => null,
                    'data'      => array(
                        'potongan' => $potongan,
                        'updated_by' => $this->jwt->ids_user
                    )
                );
                $fb2 = $this->Tbd_ukt->update($rules);
                if(!$fb2['status']){
                    $rules = array(
                        'where'     => array('idd_kelulusan' => $idd_kelulusan_tujuan),
                        'or_where'  => null,
                        'like'      => null,
                        'or_like'   => null,
                        'data'      => array(
                            'pembayaran' => ($status == 'kurang') ? 'BELUM' : 'SUDAH',
                            'tgl_pembayaran' => $viewdKelulusan_asal->tgl_pembayaran,
                            'ket_pembayaran' => $keterangan,
                            'updated_by' => $this->jwt->ids_user
                        )
                    );
                    $fb3 = $this->Tbd_kelulusan->update($rules);
                    if(!$fb3['status']){
                        $rules = array(
                            'where'     => array('idd_kelulusan' => $idd_kelulusan_asal),
                            'or_where'  => null,
                            'like'      => null,
                            'or_like'   => null,
                            'data'      => array(
                                'potongan' => 0,
                                'updated_by' => $this->jwt->ids_user
                            )
                        );
                        $fb4 = $this->Tbd_ukt->update($rules);
                        if(!$fb4['status']){
                            $response = array(
                                'status' => 200,
                                'message' => 'Berhasil pindah kelulusan.'
                            );
                        }else{
                            $response = array(
                                'status' => 400,
                                'message' => 'Gagal reset potongan asal.'
                            );
                        }
                    }else{
                        $response = array(
                            'status' => 400,
                            'message' => 'Gagal update status pembayaran kelulusan tujuan.'
                        );
                    }
                }else{
                    $response = array(
                        'status' => 400,
                        'message' => 'Gagal update potongan tujuan.'
                    );
                }
            }else{
                $response = array(
                    'status' => 400,
                    'message' => 'Gagal update kelulusan asal.'
                );
            }
        }else{
            $response = array(
                'status' => $fb5->status->code,
                'message' => $fb5->status->message
            );
        }
        echo json_encode($response);
    }

    function GetMahasiswa($id = null)
    {
        $hak_akses = $this->master->read('hak-akses/?ids_level=' . $this->jwt->ids_level . '&ids_modul=45&aksi_hak_akses=single');
        if ($hak_akses->code == 200) {
            if ($id == null) {
                $rules = array(
                    'database'  => null,
                    'select'    => null,
                    'order'     => null,
                    'limit'     => null,
                    'group_by'    => null,
                );
                $tbdMahasiswa = $this->Viewd_mahasiswa->read($rules);
                if ($tbdMahasiswa->num_rows() > 0) {
                    $data = array(
                        'status' => 200,
                        'data' => $tbdMahasiswa->result()
                    );
                } else {
                    $data = array(
                        'status' => 204,
                        'data' => null
                    );
                }
            } else {
                $rules = array(
                    'database'  => null,
                    'select'    => null,
                    'where'     => array(
                        'idd_kelulusan' => $id,
                    ),
                    'or_where'  => null,
                    'like'      => null,
                    'or_like'   => null,
                    'order'     => null,
                    'limit'     => null,
                    'group_by'  => null,
                );
                $tbdMahasiswa = $this->Viewd_mahasiswa->search($rules);
                if ($tbdMahasiswa->num_rows() > 0) {
                    $data = array(
                        'status' => 200,
                        'data' => $tbdMahasiswa->row()
                    );
                } else {
                    $data = array(
                        'status' => 204,
                        'data' => null
                    );
                }
            }
            echo json_encode($data);
        } else {
            $data = array(
                'status' => 401,
                'message' => 'Hak akses ditolak.'
            );
            echo json_encode($data);
        }
    }

    function GetSekolah($id = null)
    {
        $hak_akses = $this->master->read('hak-akses/?ids_level=' . $this->jwt->ids_level . '&ids_modul=45&aksi_hak_akses=single');
        if ($hak_akses->code == 200) {
            if ($id == null) {
                $rules = array(
                    'database'  => null,
                    'select'    => null,
                    'order'     => null,
                    'limit'     => null,
                    'group_by'    => null,
                );
                $tbdSekolah = $this->Viewd_sekolah->read($rules);
                if ($tbdSekolah->num_rows() > 0) {
                    $data = array(
                        'status' => 200,
                        'data' => $tbdSekolah->result()
                    );
                } else {
                    $data = array(
                        'status' => 204,
                        'data' => null
                    );
                }
            } else {
                $rules = array(
                    'database'  => null,
                    'select'    => null,
                    'where'     => array(
                        'idd_kelulusan' => $id,
                    ),
                    'or_where'  => null,
                    'like'      => null,
                    'or_like'   => null,
                    'order'     => null,
                    'limit'     => null,
                    'group_by'  => null,
                );
                $tbdSekolah = $this->Viewd_sekolah->search($rules);
                if ($tbdSekolah->num_rows() > 0) {
                    $data = array(
                        'status' => 200,
                        'data' => $tbdSekolah->row()
                    );
                } else {
                    $data = array(
                        'status' => 204,
                        'data' => null
                    );
                }
            }
            echo json_encode($data);
        } else {
            $data = array(
                'status' => 401,
                'message' => 'Hak akses ditolak.'
            );
            echo json_encode($data);
        }
    }

    function GetOrangtuaAyah($id = null)
    {
        $hak_akses = $this->master->read('hak-akses/?ids_level=' . $this->jwt->ids_level . '&ids_modul=45&aksi_hak_akses=single');
        if ($hak_akses->code == 200) {
            if ($id == null) {
                $rules = array(
                    'database'  => null,
                    'select'    => null,
                    'where'     => array(
                        'orangtua' => 'Ayah',
                    ),
                    'or_where'  => null,
                    'like'      => null,
                    'or_like'   => null,
                    'order'     => null,
                    'limit'     => null,
                    'group_by'  => null,
                );
                $tbdOrangtua = $this->Viewd_orangtua->search($rules);
                if ($tbdOrangtua->num_rows() > 0) {
                    $data = array(
                        'status' => 200,
                        'data' => $tbdOrangtua->result()
                    );
                } else {
                    $data = array(
                        'status' => 204,
                        'data' => null
                    );
                }
            } else {
                $rules = array(
                    'database'  => null,
                    'select'    => null,
                    'where'     => array(
                        'idd_kelulusan' => $id,
                        'orangtua' => 'Ayah',
                    ),
                    'or_where'  => null,
                    'like'      => null,
                    'or_like'   => null,
                    'order'     => null,
                    'limit'     => null,
                    'group_by'  => null,
                );
                $tbdOrangtua = $this->Viewd_orangtua->search($rules);
                if ($tbdOrangtua->num_rows() > 0) {
                    $data = array(
                        'status' => 200,
                        'data' => $tbdOrangtua->result()
                    );
                } else {
                    $data = array(
                        'status' => 204,
                        'data' => null
                    );
                }
            }
            echo json_encode($data);
        } else {
            $data = array(
                'status' => 401,
                'message' => 'Hak akses ditolak.'
            );
            echo json_encode($data);
        }
    }

    function GetOrangtuaIbu($id = null)
    {
        $hak_akses = $this->master->read('hak-akses/?ids_level=' . $this->jwt->ids_level . '&ids_modul=45&aksi_hak_akses=single');
        if ($hak_akses->code == 200) {
            if ($id == null) {
                $rules = array(
                    'database'  => null,
                    'select'    => null,
                    'where'     => array(
                        'orangtua' => 'Ibu',
                    ),
                    'or_where'  => null,
                    'like'      => null,
                    'or_like'   => null,
                    'order'     => null,
                    'limit'     => null,
                    'group_by'  => null,
                );
                $tbdOrangtua = $this->Viewd_orangtua->search($rules);
                if ($tbdOrangtua->num_rows() > 0) {
                    $data = array(
                        'status' => 200,
                        'data' => $tbdOrangtua->result()
                    );
                } else {
                    $data = array(
                        'status' => 204,
                        'data' => null
                    );
                }
            } else {
                $rules = array(
                    'database'  => null,
                    'select'    => null,
                    'where'     => array(
                        'idd_kelulusan' => $id,
                        'orangtua' => 'Ibu',
                    ),
                    'or_where'  => null,
                    'like'      => null,
                    'or_like'   => null,
                    'order'     => null,
                    'limit'     => null,
                    'group_by'  => null,
                );
                $tbdOrangtua = $this->Viewd_orangtua->search($rules);
                if ($tbdOrangtua->num_rows() > 0) {
                    $data = array(
                        'status' => 200,
                        'data' => $tbdOrangtua->result()
                    );
                } else {
                    $data = array(
                        'status' => 204,
                        'data' => null
                    );
                }
            }
            echo json_encode($data);
        } else {
            $data = array(
                'status' => 401,
                'message' => 'Hak akses ditolak.'
            );
            echo json_encode($data);
        }
    }

    function GetOrangtuaWali($id = null)
    {
        $hak_akses = $this->master->read('hak-akses/?ids_level=' . $this->jwt->ids_level . '&ids_modul=45&aksi_hak_akses=single');
        if ($hak_akses->code == 200) {
            if ($id == null) {
                $rules = array(
                    'database'  => null,
                    'select'    => null,
                    'where'     => array(
                        'orangtua' => 'Wali',
                    ),
                    'or_where'  => null,
                    'like'      => null,
                    'or_like'   => null,
                    'order'     => null,
                    'limit'     => null,
                    'group_by'  => null,
                );
                $tbdOrangtua = $this->Viewd_orangtua->search($rules);
                if ($tbdOrangtua->num_rows() > 0) {
                    $data = array(
                        'status' => 200,
                        'data' => $tbdOrangtua->result()
                    );
                } else {
                    $data = array(
                        'status' => 204,
                        'data' => null
                    );
                }
            } else {
                $rules = array(
                    'database'  => null,
                    'select'    => null,
                    'where'     => array(
                        'idd_kelulusan' => $id,
                        'orangtua' => 'Wali',
                    ),
                    'or_where'  => null,
                    'like'      => null,
                    'or_like'   => null,
                    'order'     => null,
                    'limit'     => null,
                    'group_by'  => null,
                );
                $tbdOrangtua = $this->Viewd_orangtua->search($rules);
                if ($tbdOrangtua->num_rows() > 0) {
                    $data = array(
                        'status' => 200,
                        'data' => $tbdOrangtua->result()
                    );
                } else {
                    $data = array(
                        'status' => 204,
                        'data' => null
                    );
                }
            }
            echo json_encode($data);
        } else {
            $data = array(
                'status' => 401,
                'message' => 'Hak akses ditolak.'
            );
            echo json_encode($data);
        }
    }

    function GetRumah($id = null)
    {
        $hak_akses = $this->master->read('hak-akses/?ids_level=' . $this->jwt->ids_level . '&ids_modul=45&aksi_hak_akses=single');
        if ($hak_akses->code == 200) {
            if ($id == null) {
                $rules = array(
                    'database'  => null,
                    'select'    => null,
                    'order'     => null,
                    'limit'     => null,
                    'group_by'    => null,
                );
                $tbdRumah = $this->Viewd_rumah->read($rules);
                if ($tbdRumah->num_rows() > 0) {
                    $data = array(
                        'status' => 200,
                        'data' => $tbdRumah->result()
                    );
                } else {
                    $data = array(
                        'status' => 204,
                        'data' => null
                    );
                }
            } else {
                $rules = array(
                    'database'  => null,
                    'select'    => null,
                    'where'     => array(
                        'idd_kelulusan' => $id,
                    ),
                    'or_where'  => null,
                    'like'      => null,
                    'or_like'   => null,
                    'order'     => null,
                    'limit'     => null,
                    'group_by'  => null,
                );
                $tbdRumah = $this->Viewd_rumah->search($rules);
                if ($tbdRumah->num_rows() > 0) {
                    $data = array(
                        'status' => 200,
                        'data' => $tbdRumah->row()
                    );
                } else {
                    $data = array(
                        'status' => 204,
                        'data' => null
                    );
                }
            }
            echo json_encode($data);
        } else {
            $data = array(
                'status' => 401,
                'message' => 'Hak akses ditolak.'
            );
            echo json_encode($data);
        }
    }

    function GetFile($ids_tipe_file = null, $id = null)
    {
        $hak_akses = $this->master->read('hak-akses/?ids_level=' . $this->jwt->ids_level . '&ids_modul=45&aksi_hak_akses=single');
        if ($hak_akses->code == 200) {
            if ($id == null) {
                $rules = array(
                    'database'  => null,
                    'select'    => null,
                    'where'     => array(
                        'ids_tipe_file' => $ids_tipe_file,
                    ),
                    'or_where'  => null,
                    'like'      => null,
                    'or_like'   => null,
                    'order'     => null,
                    'limit'     => null,
                    'group_by'  => null,
                );
                $tbdFile = $this->Viewd_file->search($rules);
                if ($tbdFile->num_rows() > 0) {
                    $data = array(
                        'status' => 200,
                        'data' => $tbdFile->result()
                    );
                } else {
                    $data = array(
                        'status' => 204,
                        'data' => null
                    );
                }
            } else {
                $rules = array(
                    'database'  => null,
                    'select'    => null,
                    'where'     => array(
                        'idd_kelulusan' => $id,
                        'ids_tipe_file' => $ids_tipe_file,
                    ),
                    'or_where'  => null,
                    'like'      => null,
                    'or_like'   => null,
                    'order'     => null,
                    'limit'     => null,
                    'group_by'  => null,
                );
                $tbdFile = $this->Viewd_file->search($rules);
                if ($tbdFile->num_rows() > 0) {
                    $data = array(
                        'status' => 200,
                        'data' => $tbdFile->row()
                    );
                } else {
                    $data = array(
                        'status' => 204,
                        'data' => 'null'
                    );
                }
            }
            echo json_encode($data);
        } else {
            $data = array(
                'status' => 401,
                'message' => 'Hak akses ditolak.'
            );
            echo json_encode($data);
        }
    }

    function GetKelulusan($id = null)
    {
        $hak_akses = $this->master->read('hak-akses/?ids_level=' . $this->jwt->ids_level . '&ids_modul=45&aksi_hak_akses=single');
        if ($hak_akses->code == 200) {
            if ($id == null) {
                $rules = array(
                    'database'  => null,
                    'select'    => null,
                    'order'     => null,
                    'limit'     => null,
                    'group_by'    => null,
                );
                $tbdKelulusan = $this->Viewd_kelulusan->read($rules);
                if ($tbdKelulusan->num_rows() > 0) {
                    $data = array(
                        'status' => 200,
                        'data' => $tbdKelulusan->result()
                    );
                } else {
                    $data = array(
                        'status' => 204,
                        'data' => null
                    );
                }
            } else {
                $rules = array(
                    'database'  => null,
                    'select'    => null,
                    'where'     => array(
                        'idd_kelulusan' => $id,
                    ),
                    'or_where'  => null,
                    'like'      => null,
                    'or_like'   => null,
                    'order'     => null,
                    'limit'     => null,
                    'group_by'  => null,
                );
                $tbdKelulusan = $this->Viewd_kelulusan->search($rules);
                if ($tbdKelulusan->num_rows() > 0) {
                    $data = array(
                        'status' => 200,
                        'data' => $tbdKelulusan->row()
                    );
                } else {
                    $data = array(
                        'status' => 204,
                        'data' => null
                    );
                }
            }
            echo json_encode($data);
        } else {
            $data = array(
                'status' => 401,
                'message' => 'Hak akses ditolak.'
            );
            echo json_encode($data);
        }
    }

    function GetKelulusan2($id = null)
    {
        $response = null;
        $hak_akses = $this->master->read('hak-akses/?ids_level=' . $this->jwt->ids_level . '&ids_modul=49&aksi_hak_akses=single');
        if ($hak_akses->code == 200) {
            $where = $like = array();
            $select = null;
            if (!empty($this->input->get('idd_kelulusan'))) {
                $where['idd_kelulusan'] = $this->input->get('idd_kelulusan');
            }
            if (!empty($this->input->get('id_user'))) {
                $where['id_user'] = $this->input->get('id_user');
            }
            if (!empty($this->input->get('nomor_peserta'))) {
                $where['nomor_peserta'] = $this->input->get('nomor_peserta');
            }
            if (!empty($this->input->get('nim'))) {
                $where['nim'] = $this->input->get('nim');
            }
            if (!empty($this->input->get('nama'))) {
                $like['nama'] = $this->input->get('nama');
            }
            if (!empty($this->input->get('ids_fakultas'))) {
                $where['ids_fakultas'] = $this->input->get('ids_fakultas');
            }
            if (!empty($this->input->get('kode_jurusan'))) {
                $where['kode_jurusan'] = $this->input->get('kode_jurusan');
            }
            if (!empty($this->input->get('ids_jalur_masuk'))) {
                $where['ids_jalur_masuk'] = $this->input->get('ids_jalur_masuk');
            }
            if (!empty($this->input->get('ids_jalur_masuk_not'))) {
                $where['ids_jalur_masuk !='] = $this->input->get('ids_jalur_masuk_not');
            }
            if (!empty($this->input->get('tahun'))) {
                $where['tahun'] = $this->input->get('tahun');
            }
            if (!empty($this->input->get('daftar'))) {
                $where['daftar'] = $this->input->get('daftar');
            }
            if (!empty($this->input->get('submit'))) {
                $where['submit'] = $this->input->get('submit');
            }
            if (!empty($this->input->get('pembayaran'))) {
                $where['pembayaran'] = $this->input->get('pembayaran');
            }
            if (!empty($this->input->get('pemberkasan'))) {
                $where['pemberkasan'] = $this->input->get('pemberkasan');
            }
            if (!empty($this->input->get('select'))) {
                $select = $this->input->get('select');
            }
            $rules = array(
                'database'    => null, //Database master
                'select'    => $select,
                'where'     => $where,
                'or_where'            => null,
                'like'                => $like,
                'or_like'            => null,
                'order'                => null,
                'limit'                => null,
                'group_by'            => null,
            );
            $viewdKelulusan = $this->Viewd_kelulusan->search($rules);
            if ($viewdKelulusan->num_rows() > 0) {
                $response = array(
                    'status' => 200,
                    'data' => $viewdKelulusan->result()
                );
            } else {
                $response = array(
                    'status' => 204,
                    'data' => null
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

    function GetUsers($id = null)
    {
        $hak_akses = $this->master->read('hak-akses/?ids_level=' . $this->jwt->ids_level . '&ids_modul=45&aksi_hak_akses=single');
        if ($hak_akses->code == 200) {
            if ($id == null) {
                $rules = array(
                    'database'  => null,
                    'select'    => null,
                    'order'     => null,
                    'limit'     => null,
                    'group_by'    => null,
                );
                $tblUsers = $this->Tbl_users->read($rules);
                if ($tblUsers->num_rows() > 0) {
                    $data = array(
                        'status' => 200,
                        'data' => $tblUsers->result()
                    );
                } else {
                    $data = array(
                        'status' => 204,
                        'data' => null
                    );
                }
            } else {
                $rules = array(
                    'database'  => null,
                    'select'    => null,
                    'where'     => array(
                        'id_user' => $id,
                    ),
                    'or_where'  => null,
                    'like'      => null,
                    'or_like'   => null,
                    'order'     => null,
                    'limit'     => null,
                    'group_by'  => null,
                );
                $tblUsers = $this->Tbl_users->search($rules);
                if ($tblUsers->num_rows() > 0) {
                    $data = array(
                        'status' => 200,
                        'data' => $tblUsers->row()
                    );
                } else {
                    $data = array(
                        'status' => 204,
                        'data' => null
                    );
                }
            }
            echo json_encode($data);
        } else {
            $data = array(
                'status' => 401,
                'message' => 'Hak akses ditolak.'
            );
            echo json_encode($data);
        }
    }

    function GetTipeFile($setting, $id = null)
    {
        $hak_akses = $this->master->read('hak-akses/?ids_level=' . $this->jwt->ids_level . '&ids_modul=45&aksi_hak_akses=single');
        if ($hak_akses->code == 200) {
            if ($id == null) {
                $rules = array(
                    'database'  => null,
                    'select'    => null,
                    'where'     => array(
                        'status' => "YA",
                        'setting' => $setting
                    ),
                    'or_where'  => null,
                    'like'      => null,
                    'or_like'   => null,
                    'order'     => null,
                    'limit'     => null,
                    'group_by'  => null,
                );
                $tbsTipeFile = $this->Tbs_tipe_file->search($rules);
                if ($tbsTipeFile->num_rows() > 0) {
                    $data = array(
                        'status' => 200,
                        'data' => $tbsTipeFile->result()
                    );
                } else {
                    $data = array(
                        'status' => 204,
                        'data' => null
                    );
                }
            } else {
                $rules = array(
                    'database'  => null,
                    'select'    => null,
                    'where'     => array(
                        'ids_tipe_file' => $id,
                        'setting' => $setting,
                        'status' => "YA"
                    ),
                    'or_where'  => null,
                    'like'      => null,
                    'or_like'   => null,
                    'order'     => null,
                    'limit'     => null,
                    'group_by'  => null,
                );
                $tbsTipeFile = $this->Tbs_tipe_file->search($rules);
                if ($tbsTipeFile->num_rows() > 0) {
                    $data = array(
                        'status' => 200,
                        'data' => $tbsTipeFile->row()
                    );
                } else {
                    $data = array(
                        'status' => 204,
                        'data' => null
                    );
                }
            }
            echo json_encode($data);
        } else {
            $data = array(
                'status' => 401,
                'message' => 'Hak akses ditolak.'
            );
            echo json_encode($data);
        }
    }

    function JsonFormFilter()
    {
        $hak_akses = $this->master->read('hak-akses/?ids_level=' . $this->jwt->ids_level . '&ids_modul=45&aksi_hak_akses=read');
        if ($hak_akses->code == 200) {
            $list = $this->SS_daftar->get_datatables();
            $data = array();
            $no = $_POST['start'];
            foreach ($list as $row) {
                $sub_array = array();
                $sub_array[] = ++$no;
                $sub_array[] = "<a href=\"" . base_url('daftar/mahasiswa/detail/' . $row->idd_kelulusan) . "\" title=\"Detail\" class=\"btn btn-xs btn-primary\" target=\"_blank\">
                                <span class=\"tf-icon bx bx-detail bx-xs\"></span> Detail
                            </a>";
                $sub_array[] = $row->nomor_peserta;
                $sub_array[] = $row->nim;
                $sub_array[] = $row->nama;
                $sub_array[] = $row->alias_jalur_masuk;
                $sub_array[] = $row->fakultas;
                $sub_array[] = $row->jurusan;
                $sub_array[] = $row->tahun;
                $sub_array[] = ($row->daftar == 'SUDAH') ? '<div class="badge bg-success">Sudah</div>' : '<div class="badge bg-danger">Belum</div>';
                $sub_array[] = ($row->submit == 'SUDAH') ? '<div class="badge bg-success">Sudah</div>' : '<div class="badge bg-danger">Belum</div>';
                $sub_array[] = ($row->pembayaran == 'SUDAH') ? '<div class="badge bg-success">Sudah</div>' : '<div class="badge bg-danger">Belum</div>';
                $sub_array[] = ($row->pemberkasan == 'SUDAH') ? '<div class="badge bg-success">Sudah</div>' : '<div class="badge bg-danger">Belum</div>';
                $data[] = $sub_array;
            }

            $output = array(
                "draw" => $_POST['draw'],
                "recordsTotal" => $this->SS_daftar->count_all(),
                "recordsFiltered" => $this->SS_daftar->count_filtered(),
                "data" => $data,
            );
            //output to json format
            echo json_encode($output);
        } else {
            $this->session->set_flashdata('message', 'Hak akses ditolak.');
            $this->session->set_flashdata('type_message', 'danger');
            redirect('dashboard/');
        }
    }
}
