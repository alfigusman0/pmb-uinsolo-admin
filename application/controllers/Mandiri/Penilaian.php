<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Penilaian extends CI_Controller
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
        $this->load->model('Mandiri/Viewp_formulir');
        $this->load->model('Mandiri/Viewp_file');
        $this->load->model('Mandiri/Viewp_biodata');
        $this->load->model('Mandiri/Viewp_pilihan');
        $this->load->model('Mandiri/Viewp_nilai');
        $this->load->model('Mandiri/Tbp_nilai');
        $this->load->model('Tbl_users');
    }

    function index()
    {
        $hak_akses = $this->master->read('hak-akses/?ids_level=' . $this->jwt->ids_level . '&ids_modul=139&aksi_hak_akses=read');
        if ($hak_akses->code == 200) {
            $data = array(
                'title'         => 'Penilaian | ' . $_ENV['APPLICATION_NAME'],
                'content'       => 'Mandiri/penilaian/content',
                'css'           => 'Mandiri/penilaian/css',
                'javascript'    => 'Mandiri/penilaian/javascript',
                'modal'         => 'Mandiri/penilaian/modal',
            );
            $this->load->view('index', $data);
        } else {
            $this->session->set_flashdata('message', 'Hak akses ditolak.');
            $this->session->set_flashdata('type_message', 'danger');
            redirect('dashboard/');
        }
    }

    function UpdateNilaiStudiNaskah($id)
    {
        $rules = array(
            'database'  => null,
            'select'    => null,
            'where'     => array(
                'idp_formulir' => $id,
                'keterangan' => 'STUDI NASKAH'
            ),
            'or_where'        => null,
            'like'                => null,
            'or_like'            => null,
            'order'                => null,
            'limit'                => null,
            'group_by'        => null,
        );
        $tbpNilai = $this->Tbp_nilai->search($rules);
        if ($tbpNilai->num_rows() == 0) {
            $data = array(
                'idp_formulir' => $id,
                'nilai' => $this->input->post('nilai'),
                'keterangan' => 'STUDI NASKAH',
                'created_by' => $this->jwt->ids_user,
                'updated_by' => $this->jwt->ids_user
            );
            $fb = $this->Tbp_nilai->create($data);
            if (!$fb['status']) {
                $msg = array(
                    'status' => 200,
                    'message' => 'Berhasil simpan nilai studi naskah.'
                );
            } else {
                $msg = array(
                    'status' => 400,
                    'message' => 'Gagal simpan nilai studi naskah.'
                );
            }
        } else {
            $tbpNilai = $tbpNilai->row();
            $rules = array(
                'where' => array(
                    'idp_nilai' => $tbpNilai->idp_nilai,
                ),
                'or_where'            => null,
                'like'                => null,
                'or_like'            => null,
                'data'  => array(
                    'nilai' => $this->input->post('nilai'),
                    'updated_by' => $this->jwt->ids_user
                ),
            );
            $fb = $this->Tbp_nilai->update($rules);
            if (!$fb['status']) {
                $msg = array(
                    'status' => 200,
                    'message' => 'Berhasil update nilai studi naskah.'
                );
            } else {
                $msg = array(
                    'status' => 400,
                    'message' => 'Gagal update nilai studi naskah.'
                );
            }
        }
        echo json_encode($msg);
    }

    function UpdateNilaiProposal($id)
    {
        $rules = array(
            'database'  => null,
            'select'    => null,
            'where'     => array(
                'idp_formulir' => $id,
                'keterangan' => 'PROPOSAL'
            ),
            'or_where'        => null,
            'like'                => null,
            'or_like'            => null,
            'order'                => null,
            'limit'                => null,
            'group_by'        => null,
        );
        $tbpNilai = $this->Tbp_nilai->search($rules);
        if ($tbpNilai->num_rows() == 0) {
            $data = array(
                'idp_formulir' => $id,
                'nilai' => $this->input->post('nilai'),
                'keterangan' => 'PROPOSAL',
                'created_by' => $this->jwt->ids_user,
                'updated_by' => $this->jwt->ids_user
            );
            $fb = $this->Tbp_nilai->create($data);
            if (!$fb['status']) {
                $msg = array(
                    'status' => 200,
                    'message' => 'Berhasil simpan nilai proposal.'
                );
            } else {
                $msg = array(
                    'status' => 400,
                    'message' => 'Gagal simpan nilai proposal.'
                );
            }
        } else {
            $tbpNilai = $tbpNilai->row();
            $rules = array(
                'where' => array(
                    'idp_nilai' => $tbpNilai->idp_nilai,
                ),
                'or_where'            => null,
                'like'                => null,
                'or_like'            => null,
                'data'  => array(
                    'nilai' => $this->input->post('nilai'),
                    'updated_by' => $this->jwt->ids_user
                ),
            );
            $fb = $this->Tbp_nilai->update($rules);
            if (!$fb['status']) {
                $msg = array(
                    'status' => 200,
                    'message' => 'Berhasil update nilai proposal.'
                );
            } else {
                $msg = array(
                    'status' => 400,
                    'message' => 'Gagal update nilai proposal.'
                );
            }
        }
        echo json_encode($msg);
    }
    
    function UpdateNilaiModerasi($id)
    {
        $rules = array(
            'database'  => null,
            'select'    => null,
            'where'     => array(
                'idp_formulir' => $id,
                'keterangan' => 'MODERASI BERAGAMA'
            ),
            'or_where'        => null,
            'like'                => null,
            'or_like'            => null,
            'order'                => null,
            'limit'                => null,
            'group_by'        => null,
        );
        $tbpNilai = $this->Tbp_nilai->search($rules);
        if ($tbpNilai->num_rows() == 0) {
            $data = array(
                'idp_formulir' => $id,
                'nilai' => $this->input->post('nilai'),
                'keterangan' => 'MODERASI BERAGAMA',
                'created_by' => $this->jwt->ids_user,
                'updated_by' => $this->jwt->ids_user
            );
            $fb = $this->Tbp_nilai->create($data);
            if (!$fb['status']) {
                $msg = array(
                    'status' => 200,
                    'message' => 'Berhasil simpan nilai moderasi beragama dan wawasan kebangsaan.'
                );
            } else {
                $msg = array(
                    'status' => 400,
                    'message' => 'Gagal simpan nilai moderasi beragama dan wawasan kebangsaan.'
                );
            }
        } else {
            $tbpNilai = $tbpNilai->row();
            $rules = array(
                'where' => array(
                    'idp_nilai' => $tbpNilai->idp_nilai,
                ),
                'or_where'            => null,
                'like'                => null,
                'or_like'            => null,
                'data'  => array(
                    'nilai' => $this->input->post('nilai'),
                    'updated_by' => $this->jwt->ids_user
                ),
            );
            $fb = $this->Tbp_nilai->update($rules);
            if (!$fb['status']) {
                $msg = array(
                    'status' => 200,
                    'message' => 'Berhasil update nilai moderasi beragama dan wawasan kebangsaan.'
                );
            } else {
                $msg = array(
                    'status' => 400,
                    'message' => 'Gagal update nilai moderasi beragama dan wawasan kebangsaan.'
                );
            }
        }
        echo json_encode($msg);
    }

    function GetUsers()
    {
        $where = array();
        $like = array();
        $data_res = array();
        if (!empty($this->input->get('idp_formulir'))) {
            $where['idp_formulir'] = $this->input->get('idp_formulir');
        }
        if (!empty($this->input->get('formulir'))) {
            $where['formulir'] = $this->input->get('formulir');
        }
        if (!empty($this->input->get('pembayaran'))) {
            $where['pembayaran'] = $this->input->get('pembayaran');
        }
        if (!empty($this->input->get('jenjang_not'))) {
            $where['jenjang !='] = $this->input->get('jenjang_not');
        }
        if (!empty($this->input->get('ids_program'))) {
            $where['ids_program'] = $this->input->get('ids_program');
        }
        if (!empty($this->input->get('ids_tipe_ujian'))) {
            $where['ids_tipe_ujian'] = $this->input->get('ids_tipe_ujian');
        }
        if (!empty($this->input->get('tahun'))) {
            $where['YEAR(date_created)'] = $this->input->get('tahun');
        }
        if (!empty($this->input->get('tipe_ujian'))) {
            $like['tipe_ujian'] = $this->input->get('tipe_ujian');
        }
        $like['CONCAT(nama,nomor_peserta)'] = $this->input->get('q')['term'];
        $rules = array(
            'database'  => null,
            'select'    => null,
            'where'     => $where,
            'or_where'  => null,
            'like'      => $like,
            'or_like'   => null,
            'order'     => null,
            'limit'     => 10,
            'group_by'  => null,
        );
        $viewpFormulir = $this->Viewp_formulir->search($rules);
        if ($viewpFormulir->num_rows() > 0) {
            foreach ($viewpFormulir->result() as $a) {
                $data_res[] = array(
                    'id' => $a->idp_formulir,
                    'text' => $a->nama . ' (' . $a->nomor_peserta . ')'
                );
            }
        }
        echo json_encode($data_res);
    }

    function GetFormulir()
    {
        $where = array();
        if (!empty($this->input->get('idp_formulir'))) {
            $where['idp_formulir'] = $this->input->get('idp_formulir');
        }
        $rules = array(
            'database'  => null,
            'select'    => null,
            'where'     => $where,
            'or_where'        => null,
            'like'                => null,
            'or_like'            => null,
            'order'                => null,
            'limit'                => null,
            'group_by'        => null,
        );
        $viewp_formulir = $this->Viewp_formulir->search($rules);
        if ($viewp_formulir->num_rows() > 0) {
            $pilihan = array();
            $rules = array(
                'database'  => null,
                'select'    => null,
                'where'     => $where,
                'or_where'        => null,
                'like'                => null,
                'or_like'            => null,
                'order'                => null,
                'limit'                => null,
                'group_by'        => null,
            );
            $viewpPilihan = $this->Viewp_pilihan->search($rules);
            if ($viewpPilihan->num_rows() > 0) {
                $viewpPilihan = $viewpPilihan->result();
                foreach($viewpPilihan as $a){
                    $pilihan[] = array(
                        'pilihan' => $a->pilihan,
                        'fakultas' => $a->fakultas,
                        'jurusan' => $a->jurusan
                    );
                }
            }
            $data = array(
                'status' => 200,
                'data' => $viewp_formulir->result(),
                'pilihan' => $pilihan
            );
        } else {
            $data = array(
                'status' => 204,
                'data' => null
            );
        }
        echo json_encode($data);
    }

    function GetBiodata()
    {
        $where = array();
        if (!empty($this->input->get('idp_formulir'))) {
            $where['idp_formulir'] = $this->input->get('idp_formulir');
        }
        $rules = array(
            'database'  => null,
            'select'    => null,
            'where'     => $where,
            'or_where'        => null,
            'like'                => null,
            'or_like'            => null,
            'order'                => null,
            'limit'                => null,
            'group_by'        => null,
        );
        $viewpBiodata = $this->Viewp_biodata->search($rules);
        if ($viewpBiodata->num_rows() > 0) {
            $data = array(
                'status' => 200,
                'data' => $viewpBiodata->result()
            );
        } else {
            $data = array(
                'status' => 204,
                'data' => null
            );
        }
        echo json_encode($data);
    }

    function GetFile()
    {
        $where = array();
        if (!empty($this->input->get('idp_formulir'))) {
            $where['idp_formulir'] = $this->input->get('idp_formulir');
        }
        if (!empty($this->input->get('ids_tipe_file'))) {
            $where['ids_tipe_file'] = $this->input->get('ids_tipe_file');
        }
        $rules = array(
            'database'  => null,
            'select'    => null,
            'where'     => $where,
            'or_where'        => null,
            'like'                => null,
            'or_like'            => null,
            'order'                => null,
            'limit'                => null,
            'group_by'        => null,
        );
        $viewpFile = $this->Viewp_file->search($rules);
        if ($viewpFile->num_rows() > 0) {
            $data = array(
                'status' => 200,
                'data' => $viewpFile->result()
            );
        } else {
            $data = array(
                'status' => 204,
                'data' => null
            );
        }
        echo json_encode($data);
    }

    function GetUsers2()
    {
        $where = array();
        if (!empty($this->input->get('id_user'))) {
            $where['id_user'] = $this->input->get('id_user');
        }
        $rules = array(
            'database'  => null,
            'select'    => null,
            'where'     => $where,
            'or_where'        => null,
            'like'                => null,
            'or_like'            => null,
            'order'                => null,
            'limit'                => null,
            'group_by'        => null,
        );
        $tblUsers = $this->Tbl_users->search($rules);
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
        echo json_encode($data);
    }

    function GetNilai()
    {
        $where = array();
        if (!empty($this->input->get('idp_formulir'))) {
            $where['idp_formulir'] = $this->input->get('idp_formulir');
        }
        if (!empty($this->input->get('keterangan'))) {
            $where['keterangan'] = $this->input->get('keterangan');
        }
        $rules = array(
            'database'  => null,
            'select'    => null,
            'where'     => $where,
            'or_where'        => null,
            'like'                => null,
            'or_like'            => null,
            'order'                => null,
            'limit'                => null,
            'group_by'        => null,
        );
        $tbpNilai = $this->Tbp_nilai->search($rules);
        if ($tbpNilai->num_rows() > 0) {
            $data = array(
                'status' => 200,
                'data' => $tbpNilai->result()
            );
        } else {
            $data = array(
                'status' => 204,
                'data' => null
            );
        }
        echo json_encode($data);
    }

    function GetRekapNilai()
    {
        $rules = array(
            'database'  => null,
            'select'    => null,
            'where'     => array(
                'created_by' => $this->jwt->ids_user,
                'YEAR(date_created)' => date('Y')
            ),
            'or_where'        => null,
            'like'                => null,
            'or_like'            => null,
            'order'                => null,
            'limit'                => null,
            'group_by'        => null,
        );
        $tbpNilai = $this->Viewp_nilai->search($rules);
        if ($tbpNilai->num_rows() > 0) {
            $tbpNilai = $tbpNilai->result();
            $rekap = [];
            foreach($tbpNilai as $row){
                $id = $row->idp_formulir; // atau ganti sesuai kolom ID-nya
                $jenis = strtolower(str_replace(' ', '_', $row->keterangan)); // jadi nilai_moderasi, dst
                $nilaiKey = 'nilai_' . $jenis;

                // Ambil data user yang mengupdate
                $userRes = $this->master->read("users/single?ids_user=$row->updated_by");

                // Ambil nama user jika ada
                $diupdateOleh = null;
                if (isset($userRes->code) && $userRes->code === 200 && isset($userRes->data->nama)) {
                    $diupdateOleh = $userRes->data->nama;
                }

                // Kalau belum ada entri untuk id ini, inisialisasi
                if (!isset($rekap[$id])) {
                    $rekap[$id] = [
                        'idp_formulir' => $id,
                        'nomor_peserta' => $row->nomor_peserta,
                        'nama' => $row->nama,
                        'updated_by' => $diupdateOleh
                    ];
                }

                // Masukkan nilai ke field yang sesuai
                $rekap[$id][$nilaiKey] = (int)$row->nilai;
            }

            // Reset array key jadi numerik
            $json = array_values($rekap);
            $data = array(
                'status' => 200,
                'data' => $json
            );
        } else {
            $data = array(
                'status' => 204,
                'data' => null
            );
        }
        echo json_encode($data);
    }
}
