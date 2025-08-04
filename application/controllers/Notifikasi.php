<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Notifikasi extends CI_Controller
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
        $this->load->model('Tbl_users');
        $this->load->model('Tbl_notif');
        $this->load->model('Mandiri/Tbp_formulir');
        $this->load->model('Mandiri/Viewp_formulir');
        $this->load->model('Daftar/Viewd_kelulusan');
        $this->load->model('View_notif');
        $this->load->model('ServerSide/SS_notif');
    }

    function index()
    {
        $hak_akses = $this->master->read('hak-akses/?ids_level=' . $this->jwt->ids_level . '&ids_modul=58&aksi_hak_akses=read');
        if ($hak_akses->code == 200) {
            $rules = array(
                'database'  => null, //Database master
                'select'    => null,
                'order'     => null,
                'limit'     => null,
                'group_by'  => null,
            );
            $data = array(
                'title'         => 'Notifikasi | ' . $_ENV['APPLICATION_NAME'],
                'content'       => 'notif/content',
                'css'           => 'notif/css',
                'javascript'    => 'notif/javascript',
                'modal'         => 'notif/modal',

                'tblUsers'         => $this->Tbl_users->read($rules)->result(),
            );
            $this->load->view('index', $data);
        } else {
            $this->session->set_flashdata('message', 'Hak akses ditolak.');
            $this->session->set_flashdata('type_message', 'danger');
            redirect('dashboard/');
        }
    }

    function Get()
    {
        $response = null;
        $hak_akses = $this->master->read('hak-akses/?ids_level=' . $this->jwt->ids_level . '&ids_modul=58&aksi_hak_akses=single');
        if ($hak_akses->code == 200) {
            if (!empty($this->input->get('id_notif'))) {
                $where['id_notif'] = $this->input->get('id_notif');
            }
            if (!empty($this->input->get('id_notif'))) {
                $where['id_notif'] = $this->input->get('id_notif');
            }
            if (!empty($this->input->get('email'))) {
                $where['email'] = $this->input->get('email');
            }
            if (!empty($this->input->get('dibaca'))) {
                $where['dibaca'] = $this->input->get('dibaca');
            }
            if (!empty($this->input->get('status_email'))) {
                $where['status_email'] = $this->input->get('status_email');
            }
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
            $viewNotif = $this->View_notif->search($rules);
            if ($viewNotif->num_rows() > 0) {
                $response = array(
                    'status' => 200,
                    'data' => $viewNotif->result()
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

    function Update()
    {
        $response = null;
        $hak_akses = $this->master->read('hak-akses/?ids_level=' . $this->jwt->ids_level . '&ids_modul=58&aksi_hak_akses=update');
        if ($hak_akses->code == 200) {
            $rules = array(
                'where' => array(
                    'id_notif'  => $this->input->post('id_notif'),
                ),
                'or_where'            => null,
                'like'                => null,
                'or_like'            => null,
                'data'  => array(
                    'judul'  => $this->input->post('judul'),
                    'isi'  => $this->input->post('isi2_text'),
                    'semail'  => $this->input->post('semail'),
                    'swhatsapp'  => $this->input->post('swhatsapp'),
                    'updated_by'  => $this->jwt->ids_user,
                ),
            );
            $fb = $this->Tbl_notif->update($rules);
            if (!$fb['status']) {
                $response = array(
                    'status' => 200,
                    'message' => 'Berhasil ubah notif.'
                );
            } else {
                $response = array(
                    'status' => 400,
                    'message' => 'Gagal ubah notif.'
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
        $hak_akses = $this->master->read('hak-akses/?ids_level=' . $this->jwt->ids_level . '&ids_modul=58&aksi_hak_akses=delete');
        if ($hak_akses->code == 200) {
            $where = array(
                'where'                => array(
                    'id_notif' => $id
                ),
                'or_where'            => null,
                'like'                => null,
                'or_like'            => null,
            );
            if ($this->Tbl_notif->delete($where)) {
                $response = array(
                    'status' => 200,
                    'message' => 'Berhasil hapus notif.'
                );
            } else {
                $response = array(
                    'status' => 400,
                    'message' => 'Gagal hapus notif.'
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

    function KirimNotifikasi()
    {
        $response = null;
        $hak_akses = $this->master->read('hak-akses/?ids_level=' . $this->jwt->ids_level . '&ids_modul=58&aksi_hak_akses=create');
        if ($hak_akses->code == 200) {
            $count = 0;
            $akun = $this->input->post('akun');
            if (!empty($akun)) {
                for ($i = 0; $i < count($akun); $i++) {
                    if ($this->input->post('jenis_akun') == 'Daftar Ulang') {
                        $rules = array(
                            'database'  => null, //Default database master
                            'select'    => null,
                            'where'     => array('idd_kelulusan' => $akun[$i]),
                            'or_where'  => null,
                            'like'      => null,
                            'or_like'   => null,
                            'order'     => null,
                            'limit'     => null,
                            'group_by'  => null,
                        );
                        $tbdKelulusan = $this->Tbd_kelulusan->search($rules)->row();
                        $id_user = $tbdKelulusan->id_user;
                    } else {
                        $rules = array(
                            'database'  => null, //Default database master
                            'select'    => null,
                            'where'     => array('idp_formulir' => $akun[$i]),
                            'or_where'  => null,
                            'like'      => null,
                            'or_like'   => null,
                            'order'     => null,
                            'limit'     => null,
                            'group_by'  => null,
                        );
                        $tbpFormulir = $this->Tbp_formulir->search($rules)->row();
                        $id_user = $tbpFormulir->created_by;
                    }
                    $rules = array(
                        'database'          => null, //Default database master
                        'select'            => null,
                        'where'                => array('id_user' => $id_user),
                        'or_where'            => null,
                        'like'                => null,
                        'or_like'            => null,
                        'order'                => null,
                        'limit'                => null,
                        'group_by'            => null,
                    );
                    $tblUsers = $this->Tbl_users->search($rules)->row();
                    $rules = array(
                        'database'          => null, //Default database master
                        'select'            => null,
                        'where'                => array(
                            'id_user' => $tblUsers->id_user,
                            'judul' => $this->input->post('judul')
                        ),
                        'or_where'            => null,
                        'like'                => null,
                        'or_like'            => null,
                        'order'                => null,
                        'limit'                => null,
                        'group_by'            => null,
                    );
                    $tblNotif = $this->Tbl_notif->search($rules);
                    if ($tblNotif->num_rows() == 0) {
                        $data = array(
                            'id_user'  => $tblUsers->id_user,
                            'judul'  => $this->input->post('judul'),
                            'isi'  => $this->input->post('isi_text'),
                            'dibaca'  => 'TIDAK',
                            'email'  => 'TIDAK',
                            'semail'  => $this->input->post('semail'),
                            'whatsapp'  => 'TIDAK',
                            'swhatsapp'  => $this->input->post('swhatsapp'),
                            'created_by'  => $this->jwt->ids_user,
                            'updated_by'  => $this->jwt->ids_user,
                        );
                        $fb = $this->Tbl_notif->create($data);
                        if (!$fb['status']) {
                            $count++;
                            $response = array(
                                'status' => 200,
                                'message' => 'Notif berhasil dikirim.'
                            );
                        } else {
                            $response = array(
                                'status' => 400,
                                'message' => 'Notif gagal dikirim.'
                            );
                        }
                    }
                }
            } else {
                if ($this->input->post('jenis_akun') == 'Daftar Ulang') {
                    if ($this->input->post('daftar') != '') {
                        $where['daftar'] = $this->input->post('daftar');
                    }
                    if ($this->input->post('submit') != '') {
                        $where['submit'] = $this->input->post('submit');
                    }
                    if ($this->input->post('pembayaran_du') != '') {
                        $where['pembayaran'] = $this->input->post('pembayaran_du');
                    }
                    if ($this->input->post('ids_jalur_masuk') != '') {
                        $where['ids_jalur_masuk'] = $this->input->post('ids_jalur_masuk');
                    }
                    $where['tahun'] = date('Y');
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
                    $tbdKelulusan = $this->Viewd_kelulusan->search($rules);
                    if ($tbdKelulusan->num_rows() > 0) {
                        foreach ($tbdKelulusan->result() as $a) {
                            $judul = str_replace('[nama]', $a->nama, $this->input->post('judul'));
                            $judul = str_replace('[nomor_peserta]', $a->nomor_peserta, $judul);
                            $judul = str_replace('[jalur_masuk]', $a->jalur_masuk, $judul);
                            $isi = str_replace('[nama]', $a->nama, $this->input->post('isi_text'));
                            $isi = str_replace('[nomor_peserta]', $a->nomor_peserta, $isi);
                            $isi = str_replace('[jalur_masuk]', $a->jalur_masuk, $isi);
                            $data = array(
                                'id_user'  => $a->id_user,
                                'judul'  => $judul,
                                'isi'  => $isi,
                                'dibaca'  => 'TIDAK',
                                'email'  => 'TIDAK',
                                'semail'  => $this->input->post('semail'),
                                'whatsapp'  => 'TIDAK',
                                'swhatsapp'  => $this->input->post('swhatsapp'),
                                'created_by'  => $this->jwt->ids_user,
                                'updated_by'  => $this->jwt->ids_user,
                            );
                            $fb = $this->Tbl_notif->create($data);
                            if (!$fb['status']) {
                                $count++;
                                $response = array(
                                    'status' => 200,
                                    'message' => 'Notif berhasil dikirim.'
                                );
                            } else {
                                $response = array(
                                    'status' => 400,
                                    'message' => 'Notif gagal dikirim.'
                                );
                            }
                        }
                    } else {
                        $response = array(
                            'status' => 400,
                            'message' => 'Data tidak ditemukan.'
                        );
                    }
                } else {
                    $where = array();
                    if ($this->input->post('formulir') != '') {
                        $where['formulir'] = $this->input->post('formulir');
                    }
                    if ($this->input->post('pembayaran') != '') {
                        $where['pembayaran'] = $this->input->post('pembayaran');
                    }
                    if ($this->input->post('ids_tipe_ujian') != '') {
                        $where['ids_tipe_ujian'] = $this->input->post('ids_tipe_ujian');
                    }
                    $where['YEAR(date_created)'] = date('Y');
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
                    $tbpFormulir = $this->Viewp_formulir->search($rules);
                    if ($tbpFormulir->num_rows() > 0) {
                        foreach ($tbpFormulir->result() as $a) {
                            $judul = str_replace('[nama]', $a->nama, $this->input->post('judul'));
                            $judul = str_replace('[nomor_peserta]', $a->nomor_peserta, $judul);
                            $judul = str_replace('[tipe_ujian]', $a->tipe_ujian, $judul);
                            $judul = str_replace('[program]', $a->program, $judul);
                            $judul = str_replace('[jenjang]', $a->jenjang, $judul);
                            $isi = str_replace('[nama]', $a->nama, $this->input->post('isi_text'));
                            $isi = str_replace('[nomor_peserta]', $a->nomor_peserta, $isi);
                            $isi = str_replace('[tipe_ujian]', $a->tipe_ujian, $isi);
                            $isi = str_replace('[program]', $a->program, $isi);
                            $isi = str_replace('[jenjang]', $a->jenjang, $isi);
                            $data = array(
                                'id_user'  => $a->created_by,
                                'judul'  => $judul,
                                'isi'  => $isi,
                                'dibaca'  => 'TIDAK',
                                'email'  => 'TIDAK',
                                'semail'  => $this->input->post('semail'),
                                'whatsapp'  => 'TIDAK',
                                'swhatsapp'  => $this->input->post('swhatsapp'),
                                'created_by'  => $this->jwt->ids_user,
                                'updated_by'  => $this->jwt->ids_user,
                            );
                            $fb = $this->Tbl_notif->create($data);
                            if (!$fb['status']) {
                                $count++;
                                $response = array(
                                    'status' => 200,
                                    'message' => 'Notif berhasil dikirim.'
                                );
                            } else {
                                $response = array(
                                    'status' => 400,
                                    'message' => 'Notif gagal dikirim.'
                                );
                            }
                        }
                    } else {
                        $response = array(
                            'status' => 400,
                            'message' => 'Data tidak ditemukan.'
                        );
                    }
                }
            }
            $response['alt_message'] = $count . " notifikasi berhasil dikirim.";
        } else {
            $response = array(
                'status' => 400,
                'message' => 'Hak akses ditolak.'
            );
        }
        echo json_encode($response);
    }

    function GetAkun()
    {
        $where = array();
        if ($this->input->get('id_user') != '') {
            $where['id_user'] = $this->input->get('id_user');
        }
        if ($this->input->get('pembayaran') != '') {
            $where['pembayaran'] = $this->input->get('pembayaran');
        }
        $q = $this->input->get('q');
        $data_res = array();
        if ($this->input->get('jenis_akun') == 'Daftar Ulang') {
            if ($this->input->get('tahun') != '') {
                $where['tahun'] = $this->input->get('tahun');
            }
            if ($this->input->get('daftar') != '') {
                $where['daftar'] = $this->input->get('daftar');
            }
            if ($this->input->get('submit') != '') {
                $where['submit'] = $this->input->get('submit');
            }
            if ($this->input->get('ids_jalur_masuk') != '') {
                $where['ids_jalur_masuk'] = $this->input->get('ids_jalur_masuk');
            }
            $rules = array(
                'database'          => null, //Default database master
                'select'            => null,
                'where'                => $where,
                'or_where'            => null,
                'like'                => array(
                    'nama' => $q['term'],
                ),
                'or_like'            => array(
                    'nomor_peserta' => $q['term'],
                ),
                'order'                => 'nama ASC',
                'limit'                => 10,
                'group_by'            => null,
            );
            $tbdKelulusan = $this->Tbd_kelulusan->search($rules);
            if ($tbdKelulusan->num_rows() > 0) {
                foreach ($tbdKelulusan->result() as $a) {
                    $data_res[] = array(
                        'id' => $a->idd_kelulusan,
                        'text' => $a->nama . ' (' . $a->nomor_peserta . ')'
                    );
                }
            }
        } else {
            if ($this->input->get('tahun') != '') {
                $where['YEAR(date_created)'] = $this->input->get('tahun');
            }
            if ($this->input->get('ids_tipe_ujian') != '') {
                $where['ids_tipe_ujian'] = $this->input->get('ids_tipe_ujian');
            }
            $rules = array(
                'database'          => null, //Default database master
                'select'            => null,
                'where'                => $where,
                'or_where'            => null,
                'like'                => array(
                    'nama' => $q['term'],
                ),
                'or_like'            => array(
                    'nomor_peserta' => $q['term'],
                ),
                'order'                => 'nama ASC',
                'limit'                => 10,
                'group_by'            => null,
            );
            $viewpFormulir = $this->Viewp_formulir->search($rules);
            if ($viewpFormulir->num_rows() > 0) {
                foreach ($viewpFormulir->result() as $a) {
                    $rules = array(
                        'database'          => null, //Default database master
                        'select'            => null,
                        'where'                => array('id_user' => $a->created_by),
                        'or_where'            => null,
                        'like'                => null,
                        'or_like'            => null,
                        'order'                => null,
                        'limit'                => null,
                        'group_by'            => null,
                    );
                    $tbsUsers = $this->Tbl_users->search($rules)->row();
                    $data_res[] = array(
                        'id' => $a->idp_formulir,
                        'text' => $a->nama . ' (' . $tbsUsers->email . ')'
                    );
                }
            }
        }
        echo json_encode($data_res);
    }

    function JsonFormFilter()
    {
        $hak_akses = $this->master->read('hak-akses/?ids_level=' . $this->jwt->ids_level . '&ids_modul=58&aksi_hak_akses=read');
        if ($hak_akses->code == 200) {
            $list = $this->SS_notif->get_datatables();
            $data = array();
            $no = $_POST['start'];
            foreach ($list as $row) {
                $sub_array = array();
                $sub_array[] = ++$no;
                $sub_array[] = "
                            <button type=\"button\" title=\"Edit\" onclick=\"edit_data(" . $row->id_notif . ")\" class=\"btn btn-xs btn-warning\">
                                <span class=\"tf-icon bx bx-edit bx-xs\"></span> Edit
                            </button>
                            <a href=\"javascript:void(0)\" title=\"Hapus\" class=\"btn btn-xs btn-danger\" onclick=\"delete_data(" . $row->id_notif . ")\">
                                <span class=\"tf-icon bx bx-trash bx-xs\"></span> Hapus
                            </a>";
                $sub_array[] = $row->nama;
                $sub_array[] = $row->email;
                $sub_array[] = $row->judul;
                $sub_array[] = "<button type=\"button\" title=\"Lihat\" onclick=\"view_isi(" . $row->id_notif . ")\" class=\"btn btn-xs btn-primary\">
                                    <span class=\"tf-icon bx bx-show bx-xs\"></span> Lihat
                                </button>";
                if ($row->dibaca == 'YA') {
                    $dibaca = "<div class=\"badge bg-success\">Iya</div>";
                } else {
                    $dibaca = "<div class=\"badge bg-danger\">Tidak</div>";
                }
                $sub_array[] = $dibaca;
                if ($row->semail == 'YA') {
                    $semail = "<div class=\"badge bg-success\">Iya</div>";
                } else {
                    $semail = "<div class=\"badge bg-warning\">Tidak</div>";
                }
                $sub_array[] = $semail;
                if ($row->status_email == 'YA') {
                    $email = "<div class=\"badge bg-success\">Iya</div>";
                } else if ($row->status_email == 'TIDAK') {
                    $email = "<div class=\"badge bg-warning\">Tidak</div>";
                } else {
                    $email = "<div class=\"badge bg-danger\">Error</div>";
                }
                $sub_array[] = $email;
                if ($row->swhatsapp == 'YA') {
                    $swhatsapp = "<div class=\"badge bg-success\">Iya</div>";
                } else {
                    $swhatsapp = "<div class=\"badge bg-warning\">Tidak</div>";
                }
                $sub_array[] = $swhatsapp;
                if ($row->whatsapp == 'YA') {
                    $whatsapp = "<div class=\"badge bg-success\">Iya</div>";
                } else if ($row->whatsapp == 'TIDAK') {
                    $whatsapp = "<div class=\"badge bg-warning\">Tidak</div>";
                } else {
                    $whatsapp = "<div class=\"badge bg-danger\">Error</div>";
                }
                $sub_array[] = $whatsapp;
                $data[] = $sub_array;
            }

            $output = array(
                "draw" => $_POST['draw'],
                "recordsTotal" => $this->SS_notif->count_all(),
                "recordsFiltered" => $this->SS_notif->count_filtered(),
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
