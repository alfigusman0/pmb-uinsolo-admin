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

        $this->load->model('ServerSide/SS_mandiri');
        $this->load->model('Mandiri/Tbp_formulir');
        $this->load->model('Mandiri/Tbp_biodata');
        $this->load->model('Mandiri/Tbp_setting');
        $this->load->model('Mandiri/Tbp_file');
        $this->load->model('Mandiri/Tbp_sekolah');
        $this->load->model('Mandiri/Tbp_pilihan');
        $this->load->model('Mandiri/Tbp_rumah');
        $this->load->model('Mandiri/Tbp_jadwal');
        $this->load->model('Mandiri/Tbp_pendidikan');
        $this->load->model('Mandiri/Tbp_pekerjaan');
        $this->load->model('Mandiri/Tbp_prestasi');
        $this->load->model('Mandiri/Tbp_pembayaran');
        $this->load->model('Mandiri/Tbp_sanggah');
        $this->load->model('Settings/Tbs_program');
        $this->load->model('Settings/Tbs_jadwal');
        $this->load->model('Settings/Tbs_tipe_file');
        $this->load->model('Settings/Tbs_tipe_ujian');
        $this->load->model('Tbl_users');
        $this->load->model('Mandiri/Viewp_formulir');
        $this->load->model('Mandiri/Viewp_pembayaran');
        $this->load->model('Mandiri/Viewp_biodata');
        $this->load->model('Mandiri/Viewp_rumah');
        $this->load->model('Mandiri/Viewp_sekolah');
        $this->load->model('Mandiri/Viewp_pilihan');
        $this->load->model('Mandiri/Viewp_jadwal');
        $this->load->model('Mandiri/Viewp_file');
        $this->load->model('Mandiri/Viewp_nilai');
        $this->load->model('Mandiri/Viewp_kelulusan');
        $this->load->model('Mandiri/Viewp_sanggah');
        $this->load->model('Settings/Views_tipe_ujian');
    }

    function index()
    {
        $hak_akses = $this->master->read('hak-akses/?ids_level=' . $this->jwt->ids_level . '&ids_modul=61&aksi_hak_akses=read');
        if ($hak_akses->code == 200) {
            $rules = array(
                'database'  => null,
                'select'    => 'YEAR(date_created) as tahun', // not null
                'where'     => null,
                'or_where'  => null,
                'like'      => null,
                'or_like'   => null,
                'order'     => null,
                'group_by'  => null,
            );
            $tahun = $this->Viewp_formulir->distinct($rules)->result();
            $data = array(
                'title'         => 'Mahasiswa | ' . $_ENV['APPLICATION_NAME'],
                'content'       => 'Mandiri/mahasiswa/read/content',
                'css'           => 'Mandiri/mahasiswa/read/css',
                'javascript'    => 'Mandiri/mahasiswa/read/javascript',
                'modal'         => 'Mandiri/mahasiswa/read/modal',

                'tahun'         => $tahun
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
        $hak_akses = $this->master->read('hak-akses/?ids_level=' . $this->jwt->ids_level . '&ids_modul=61&aksi_hak_akses=single');
        if ($hak_akses->code == 200) {
            $rules = array(
                'database'  => null, //Default database master
                'select'    => null,
                'where'     => array(
                    'idp_formulir' => $id
                ),
                'or_where'  => null,
                'like'      => null,
                'or_like'   => null,
                'order'     => null,
                'limit'     => null,
                'group_by'  => null,
            );
            $viewpFormulir = $this->Viewp_formulir->search($rules)->row();
            $viewpBiodata = $this->Viewp_biodata->search($rules);
            $viewpRumah = $this->Viewp_rumah->search($rules);
            $viewpSekolah = $this->Viewp_sekolah->search($rules);
            $tbpPendidikan = $this->Tbp_pendidikan->search($rules);
            $tbpPekerjaan = $this->Tbp_pekerjaan->search($rules);
            $viewpPrestasi = $this->Tbp_prestasi->search($rules);
            $viewpPembayaran = $this->Viewp_pembayaran->search($rules);
            $viewpJadwal = $this->Viewp_jadwal->search($rules);
            $viewpPilihan = $this->Viewp_pilihan->search($rules);
            $viewpKelulusan = $this->Viewp_kelulusan->search($rules);
            $viewpNilai = $this->Viewp_nilai->search($rules);
            $viewpSanggah = $this->Viewp_sanggah->search($rules);
            $rules = array(
                'database'  => null, //Default database master
                'select'    => null,
                'where'     => array(
                    'id_user' => $viewpFormulir->created_by
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
                    'idp_formulir' => $id,
                    'ids_tipe_file' => 14
                ),
                'or_where'  => null,
                'like'      => null,
                'or_like'   => null,
                'order'     => null,
                'limit'     => null,
                'group_by'  => null,
            );
            $viewpFileFoto = $this->Viewp_file->search($rules);
            $rules = array(
                'database'  => null, //Default database master
                'select'    => null,
                'where'     => array(
                    'status' => 'YA'
                ),
                'or_where'  => null,
                'like'      => array(
                    'tipe_ujian' => $viewpFormulir->ids_tipe_ujian,
                ),
                'or_like'   => null,
                'order'     => null,
                'limit'     => null,
                'group_by'  => null,
            );
            $tbsTipeFile = $this->Tbs_tipe_file->search($rules);
            foreach ($tbsTipeFile->result() as $a) {
                $rules = array(
                    'database'  => null, //Default database master
                    'select'    => null,
                    'where'     => array(
                        'idp_formulir' => $id,
                        'ids_tipe_file' => $a->ids_tipe_file
                    ),
                    'or_where'  => null,
                    'like'      => null,
                    'or_like'   => null,
                    'order'     => null,
                    'limit'     => null,
                    'group_by'  => null,
                );
                $viewpFileUpload[$a->ids_tipe_file] = $this->Viewp_file->search($rules);
            }

            $data = array(
                'title'         => 'Detail Mahasiswa | ' . $_ENV['APPLICATION_NAME'],
                'content'       => 'Mandiri/mahasiswa/detail/content',
                'css'           => 'Mandiri/mahasiswa/detail/css',
                'javascript'    => 'Mandiri/mahasiswa/detail/javascript',
                'modal'         => 'Mandiri/mahasiswa/detail/modal',
                'tbpPendidikan' => $tbpPendidikan,
                'tbpPekerjaan' => $tbpPekerjaan,
                'viewpFormulir' => $viewpFormulir,
                'viewpBiodata' => $viewpBiodata,
                'viewpRumah' => $viewpRumah,
                'viewpSekolah' => $viewpSekolah,
                'viewpFileFoto' => $viewpFileFoto,
                'viewpPrestasi' => $viewpPrestasi,
                'viewpPembayaran' => $viewpPembayaran,
                'viewpPilihan' => $viewpPilihan,
                'viewpJadwal' => $viewpJadwal,
                'viewpNilai' => $viewpNilai,
                'viewpKelulusan' => $viewpKelulusan,
                'viewpSanggah' => $viewpSanggah,
                'tblUsers' => $tblUsers,
                'tbsTipeFile' => $tbsTipeFile,
                'viewpFileUpload' => ($tbsTipeFile->num_rows() > 0) ? $viewpFileUpload : '',
            );
            $this->load->view('index', $data);
        } else {
            $this->session->set_flashdata('message', 'Hak akses ditolak.');
            $this->session->set_flashdata('type_message', 'danger');
            redirect('dashboard/');
        }
    }

    function GetFormulir($id = null)
    {
        $response = null;
        $hak_akses = $this->master->read('hak-akses/?ids_level=' . $this->jwt->ids_level . '&ids_modul=61&aksi_hak_akses=single');
        if ($hak_akses->code == 200) {
            if ($id == null) {
                $rules = array(
                    'database'  => null,
                    'select'    => null,
                    'order'     => null,
                    'limit'     => null,
                    'group_by'    => null,
                );
                $tbpFormulir = $this->Viewp_formulir->read($rules);
                if ($tbpFormulir->num_rows() > 0) {
                    $data = array(
                        'status' => 200,
                        'data' => $tbpFormulir->result()
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
                        'idp_formulir' => $id,
                    ),
                    'or_where'  => null,
                    'like'      => null,
                    'or_like'   => null,
                    'order'     => null,
                    'limit'     => null,
                    'group_by'  => null,
                );
                $tbpFormulir = $this->Viewp_formulir->search($rules);
                if ($tbpFormulir->num_rows() > 0) {
                    $response = array(
                        'status' => 200,
                        'data' => $tbpFormulir->row()
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

    function simpanDataFormulir()
    {
        $response = null;
        $hak_akses = $this->master->read('hak-akses/?ids_level=' . $this->jwt->ids_level . '&ids_modul=61&aksi_hak_akses=create,update');
        if ($hak_akses->code != 200) {
            $response = array(
                'status' => 400,
                'message' => 'Hak akses ditolak.'
            );
        }

        $idp_formulir = $this->input->post('idp_formulir');
        $kategori = $this->input->post('kategori');
        $ids_tipe_ujian = $this->input->post('ids_tipe_ujian');
        $formulir = $this->input->post('formulir');
        $pembayaran = $this->input->post('pembayaran');
        $ket_pembayaran = $this->input->post('ket_pembayaran');

        $formulirData = $this->Tbp_formulir->search(['where' => ['idp_formulir' => $idp_formulir]]);
        if ($formulirData->num_rows() === 0) {
            $response = array(
                'status' => 400,
                'message' => 'Data formulir tidak ditemukan.'
            );
        }

        $tbpFormulir = $formulirData->row();

        // Kasus 1: Sudah bayar dan pindah tipe ujian
        if ($tbpFormulir->pembayaran === $pembayaran && $pembayaran === 'SUDAH' && $tbpFormulir->nomor_peserta !== null && $tbpFormulir->ids_tipe_ujian != $ids_tipe_ujian) {
            $response = $this->prosesPindahTipeUjian($tbpFormulir, $kategori, $ids_tipe_ujian, $formulir, $pembayaran, $ket_pembayaran);
        }

        // Kasus 2: Sudah bayar tapi belum ada nomor peserta
        if ($tbpFormulir->pembayaran != $pembayaran && $pembayaran === 'SUDAH' && $tbpFormulir->nomor_peserta === null) {
            $response =  $this->prosesRegistrasiBaru($tbpFormulir, $kategori, $ids_tipe_ujian, $formulir, $pembayaran, $ket_pembayaran);
        }

        // Kasus 3: Update biasa
        $fb = $this->Tbp_formulir->update([
            'where' => ['idp_formulir' => $tbpFormulir->idp_formulir],
            'data'  => [
                'kategori' => $kategori,
                'ids_tipe_ujian' => $ids_tipe_ujian,
                'formulir' => $formulir,
                'pembayaran' => $pembayaran,
                'ket_pembayaran' => $ket_pembayaran,
                'updated_by_admin' => $this->jwt->ids_user
            ]
        ]);

        if ($fb['status']) {
            $response = array(
                'status' => 400,
                'message' => 'Gagal memperbarui formulir.'
            );
        } else {
            $response = array(
                'status' => 200,
                'message' => 'Data berhasil diupdate.'
            );
        }

        echo json_encode($response);
    }

    function prosesPindahTipeUjian($tbpFormulir, $kategori, $ids_tipe_ujian, $formulir, $pembayaran, $ket_pembayaran)
    {
        $idp_formulir = $tbpFormulir->idp_formulir;
        $nomor_peserta = $this->NomorPeserta($ids_tipe_ujian);

        $tbpJadwal = $this->Tbp_jadwal->search(['where' => ['idp_formulir' => $idp_formulir]]);
        if ($tbpJadwal->num_rows() > 0) {
            $jadwal = $tbpJadwal->row();

            // Tambah kuota jadwal lama
            $fb = $this->Tbs_jadwal->update([
                'where' => ['ids_jadwal' => $jadwal->ids_jadwal],
                'data'  => ['quota' => +1]
            ]);
            if ($fb['status']) return array('status' => 400, 'message' => 'Gagal memperbarui kuota jadwal lama.');

            // Hapus relasi jadwal lama
            $fb = $this->Tbp_jadwal->delete(['where' => ['idp_formulir' => $idp_formulir]]);
            if ($fb['status']) return array('status' => 400, 'message' => 'Gagal menghapus jadwal sebelumnya.');
        }

        // Update formulir
        $fb = $this->Tbp_formulir->update([
            'where' => ['idp_formulir' => $idp_formulir],
            'data' => [
                'nomor_peserta' => $nomor_peserta,
                'kategori' => $kategori,
                'ids_tipe_ujian' => $ids_tipe_ujian,
                'formulir' => $formulir,
                'pembayaran' => $pembayaran,
                'ket_pembayaran' => $ket_pembayaran,
                'updated_by_admin' => $this->jwt->ids_user
            ]
        ]);
        if ($fb['status']) return array('status' => 400, 'message' => 'Gagal memperbarui formulir.');

        $tipeUjian = $this->Tbs_tipe_ujian->search(['where' => ['ids_tipe_ujian' => $ids_tipe_ujian]])->row();

        if ($tipeUjian->status_jadwal == 'TIDAK') {
            // Kurangi kuota
            $fb = $this->Tbs_tipe_ujian->update([
                'where' => ['ids_tipe_ujian' => $ids_tipe_ujian],
                'data' => ['quota' => $tipeUjian->quota - 1]
            ]);
            if ($fb['status']) return array('status' => 400, 'message' => 'Gagal memperbarui kuota tipe ujian.');
        } else {
            $jadwal = $this->Tbs_jadwal->search([
                'where' => ['ids_tipe_ujian' => $tipeUjian->ids_tipe_ujian, 'quota >' => 0, 'status' => 'YA'],
                'order' => 'tanggal ASC, jam_awal ASC, ids_jadwal ASC, quota ASC',
                'limit' => 1
            ])->row();

            $fb = $this->Tbp_jadwal->create([
                'idp_formulir' => $idp_formulir,
                'ids_jadwal' => $jadwal->ids_jadwal,
                'created_by' => 1,
                'updated_by' => 1
            ]);
            if ($fb['status']) {
                return array('status' => 400, 'message' => 'Gagal memperbarui jadwal.');
            } else {
                // Kurangi kuota jadwal
                $fb = $this->Tbs_jadwal->update([
                    'where' => ['ids_jadwal' => $jadwal->ids_jadwal],
                    'data'  => ['quota' => $jadwal->quota - 1]
                ]);
                if ($fb['status']) return array('status' => 400, 'message' => 'Gagal memperbarui kuota jadwal.');
            }
        }

        return array('status' => 200, 'message' => 'Data berhasil diupdate.');
    }

    function prosesRegistrasiBaru($tbpFormulir, $kategori, $ids_tipe_ujian, $formulir, $pembayaran, $ket_pembayaran)
    {
        $success = 0;
        $error = 0;

        $nomor_peserta = $this->NomorPeserta($ids_tipe_ujian);
        $tipeUjian = $this->Tbs_tipe_ujian->search(['where' => ['ids_tipe_ujian' => $ids_tipe_ujian]])->row();

        if ($tipeUjian->quota <= 0) {
            return array('status' => 400, 'message' => 'Kuota tipe ujian tidak mencukupi.');
        }

        if ($tipeUjian->status_jadwal == 'TIDAK') {
            $fb = $this->Tbs_tipe_ujian->update([
                'where' => ['ids_tipe_ujian' => $ids_tipe_ujian],
                'data' => ['quota' => $tipeUjian->quota - 1]
            ]);
            if (!$fb['status']) $success++;
            else $error++;
        } else {
            $jadwal = $this->Tbs_jadwal->search([
                'where' => ['ids_tipe_ujian' => $ids_tipe_ujian, 'quota >' => 0, 'status' => 'YA'],
                'order' => 'tanggal ASC, jam_awal ASC, ids_jadwal ASC, quota ASC',
                'limit' => 1
            ])->row();

            if ($jadwal) {
                $fb = $this->Tbp_jadwal->create([
                    'idp_formulir' => $tbpFormulir->idp_formulir,
                    'ids_jadwal' => $jadwal->ids_jadwal,
                    'created_by' => 1,
                    'updated_by' => 1
                ]);
                if (!$fb['status']) {
                    $success++;
                    $this->Tbs_jadwal->update([
                        'where' => ['ids_jadwal' => $jadwal->ids_jadwal],
                        'data'  => ['quota' => $jadwal->quota - 1]
                    ]);
                } else $error++;
            } else {
                $error++;
            }
        }

        $fb = $this->Tbp_formulir->update([
            'where' => ['idp_formulir' => $tbpFormulir->idp_formulir],
            'data' => [
                'nomor_peserta' => $nomor_peserta,
                'kategori' => $kategori,
                'ids_tipe_ujian' => $ids_tipe_ujian,
                'formulir' => $formulir,
                'pembayaran' => $pembayaran,
                'ket_pembayaran' => $ket_pembayaran,
                'updated_by_admin' => $this->jwt->ids_user
            ]
        ]);

        if (!$fb['status']) $success++;
        else $error++;

        return ($success > 0 && $error === 0)
            ? array('status' => 200, 'message' => 'Data berhasil diupdate.')
            : array('status' => 400, 'message' => 'Gagal memperbarui data formulir. Silakan coba lagi.');
    }

    function NomorPeserta($ids_tipe_ujian)
    {
        $tahun = substr(date('Y'), 2, 2);
        $rules = array(
            'database'  => null, //Default database master
            'select'    => null,
            'where'     => array('ids_tipe_ujian' => $ids_tipe_ujian),
            'or_where'  => null,
            'like'      => null,
            'or_like'   => null,
            'order'     => null,
            'limit'     => null,
            'group_by'  => null,
        );
        $viewsTipeUjian = $this->Views_tipe_ujian->search($rules)->row();
        $nomor_peserta = $tahun . $viewsTipeUjian->kode_program . $viewsTipeUjian->kode .  "00001";
        $rules = array(
            'database'  => null, //Default database master
            'select'    => null,
            'where'     => array(
                'nomor_peserta' => $nomor_peserta,
                'ids_tipe_ujian' => $ids_tipe_ujian,
            ),
            'or_where'  => null,
            'like'      => null,
            'or_like'   => null,
            'order'     => null,
            'limit'     => null,
            'group_by'  => null,
        );
        $cek = $this->Tbp_formulir->search($rules)->num_rows();
        if ($cek != 0) {
            $rules = array(
                'database'  => null, //Default database master
                'select'    => null,
                'where'     => array(
                    'ids_tipe_ujian' => $ids_tipe_ujian,
                ),
                'or_where'  => null,
                'like'      => null,
                'or_like'   => null,
                'order'     => 'nomor_peserta DESC',
                'limit'     => 1,
                'group_by'  => null,
            );
            $tbpFormulir = $this->Tbp_formulir->search($rules)->row();
            $nomor_peserta = ($tbpFormulir->nomor_peserta + 1);
        }
        return $nomor_peserta;
    }

    function JsonFormFilter()
    {
        $list = $this->SS_mandiri->get_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $row) {
            $sub_array = array();
            $sub_array[] = ++$no;
            $sub_array[] = "<a href=\"" . base_url('mandiri/mahasiswa/detail/' . $row->idp_formulir) . "\" title=\"Detail\" class=\"btn btn-xs btn-primary\" target=\"_blank\">
                                <span class=\"tf-icon bx bx-detail bx-xs\"></span> Detail
                            </a>";
            $sub_array[] = date('Y', strtotime($row->date_created)) . $row->idp_formulir;
            $sub_array[] = $row->nomor_peserta;
            $sub_array[] = $row->nama;
            $sub_array[] = $row->program;
            $sub_array[] = $row->tipe_ujian;
            $sub_array[] = date('Y', strtotime($row->date_created));
            $sub_array[] = ($row->formulir == 'SUDAH') ? '<div class="badge bg-success">Sudah</div>' : '<div class="badge bg-danger">Belum</div>';
            $sub_array[] = ($row->pembayaran == 'SUDAH') ? '<div class="badge bg-success">Sudah</div>' : '<div class="badge bg-danger">Belum</div>';
            $data[] = $sub_array;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->SS_mandiri->count_all(),
            "recordsFiltered" => $this->SS_mandiri->count_filtered(),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }
}
