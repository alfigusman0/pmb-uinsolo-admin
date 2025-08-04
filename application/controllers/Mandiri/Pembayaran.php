<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Pembayaran extends CI_Controller
{
    var $jwt = null;
    function __construct()
    {
        parent::__construct();
        date_default_timezone_set("Asia/Jakarta");
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

        $this->load->model('ServerSide/SS_pembayaran_mandiri');
        $this->load->model('Mandiri/Tbp_formulir');
        $this->load->model('Mandiri/Tbp_pembayaran');
        $this->load->model('Mandiri/Tbp_setting');
        $this->load->model('Tbl_users');
        $this->load->model('Mandiri/Viewp_biodata');
        $this->load->model('Mandiri/Viewp_rumah');
        $this->load->model('Mandiri/Viewp_formulir');
        $this->load->model('Mandiri/Viewp_pembayaran');
    }

    function index()
    {
        $hak_akses = $this->master->read('hak-akses/?ids_level=' . $this->jwt->ids_level . '&ids_modul=67&aksi_hak_akses=read');
        if ($hak_akses->code == 200) {
            $data = array(
                'title'         => 'Pembayaran | ' . $_ENV['APPLICATION_NAME'],
                'content'       => 'Mandiri/pembayaran/content',
                'css'           => 'Mandiri/pembayaran/css',
                'javascript'    => 'Mandiri/pembayaran/javascript',
                'modal'         => 'Mandiri/pembayaran/modal',
            );
            $this->load->view('index', $data);
        } else {
            $this->session->set_flashdata('message', 'Hak akses ditolak.');
            $this->session->set_flashdata('type_message', 'danger');
            redirect('dashboard/');
        }
    }

    function GetPembayaran($id = null)
    {
        $response = null;
        $hak_akses = $this->master->read('hak-akses/?ids_level=' . $this->jwt->ids_level . '&ids_modul=67&aksi_hak_akses=single');
        if ($hak_akses->code == 200) {
            if ($id == null) {
                $rules = array(
                    'database'  => null,
                    'select'    => null,
                    'order'     => null,
                    'limit'     => null,
                    'group_by'    => null,
                );
                $tbpPembayaran = $this->Viewp_pembayaran->read($rules);
                if ($tbpPembayaran->num_rows() > 0) {
                    $response = array(
                        'status' => 200,
                        'data' => $tbpPembayaran->result()
                    );
                } else {
                    $response = array(
                        'status' => 204,
                        'data' => null
                    );
                }
            } else {
                $rules = array(
                    'database'  => null,
                    'select'    => null,
                    'where'     => array(
                        'idp_pembayaran' => $id,
                    ),
                    'or_where'  => null,
                    'like'      => null,
                    'or_like'   => null,
                    'order'     => null,
                    'limit'     => null,
                    'group_by'  => null,
                );
                $tbpPembayaran = $this->Viewp_pembayaran->search($rules);
                if ($tbpPembayaran->num_rows() > 0) {
                    $response = array(
                        'status' => 200,
                        'data' => $tbpPembayaran->row()
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

    public function PilihBank()
    {
        $hak_akses = $this->master->read('hak-akses/?ids_level=' . $this->jwt->ids_level . '&ids_modul=67&aksi_hak_akses=create');
        if ($hak_akses->code == 200) {
            $ids_bank = $this->input->post('ids_bank');
            $kode_pembayaran = substr($this->input->post('kode_pembayaran'), 4);
            if (empty($ids_bank)) {
                $response = array(
                    'status' => 400,
                    'message' => 'Silahkan pilih bank terlebih dahulu.'
                );
            }
            $rules = array(
                'database'  => null, //Default database master
                'select'    => null,
                'where'     => array(
                    'idp_formulir' => $kode_pembayaran,
                ),
                'or_where'        => null,
                'like'                => null,
                'or_like'            => null,
                'order'                => null,
                'limit'                => null,
                'group_by'        => null,
            );
            $viewpFormulir = $this->Viewp_formulir->search($rules)->row();
            $viewpBiodata = $this->Viewp_biodata->search($rules)->row();
            $viewpRumah = $this->Viewp_rumah->search($rules)->row();
            $rules = array(
                'database'  => null,
                'select'    => null,
                'where'     => array(
                    'id_user' => $viewpFormulir->created_by,
                ),
                'or_where'        => null,
                'like'                => null,
                'or_like'            => null,
                'order'                => null,
                'limit'                => null,
                'group_by'        => null,
            );
            $tblUsers = $this->Tbl_users->search($rules)->row();
            $rules = array(
                'database'  => null, //Default database master
                'select'    => null,
                'where'     => array(
                    'nama_setting' => "Batas Akhir Pendaftaran",
                ),
                'or_where'        => null,
                'like'                => null,
                'or_like'            => null,
                'order'                => null,
                'limit'                => null,
                'group_by'        => null,
            );
            $tbpSettingAkhir = $this->Tbp_setting->search($rules)->row();
            $tbpSettingAkhir2 = date('Y-m-d H:i:s', strtotime("2 days"));
            if ($ids_bank == 1) {
                /* Check Virtual Account */
                $data = array(
                    "nim" => date('Y') . $viewpFormulir->idp_formulir,
                );
                $request = array(
                    'url' => $_ENV['BJBS_02_HOST'] . 'checkVA/token/' . $_ENV['BJBS_02_TOKEN'],
                    "method" => "POST",
                    'header' => array(
                        "Content-Type:application/json",
                    ),
                    'request' => json_encode($data),
                );
                $respond = $this->utilities->curl($request);
                if ($respond->code == 200) {
                    $data_bill = array(
                        "nim" => date('Y') . $viewpFormulir->idp_formulir,
                        "amount" => $this->input->post('nominal'),
                        "product_id" => $_ENV['BJBS_02_PRODUCT_PRADAFTAR'],
                        "thn_akademik" => date('Y'),
                        "jenis_semester" => '1'
                    );
                    $parrams = array(
                        'url' => $_ENV['BJBS_02_HOST'] . 'createBill/token/' . $_ENV['BJBS_02_TOKEN'],
                        'method' => 'POST',
                        'header' => array(
                            "Content-Type:application/json",
                        ),
                        'request' => json_encode($data_bill),
                    );
                    $response = $this->utilities->curl($parrams);
                    if ($response->status == 'success') {
                        $request = array(
                            'url' => $_ENV['BJBS_02_HOST'] . 'updateExpired/token/' . $_ENV['BJBS_02_TOKEN'],
                            'method' => 'POST',
                            'header' => array(
                                "Content-Type:application/json",
                            ),
                            'request' => json_encode(array(
                                "billing_id" => $response->billing_id,
                                "newDate" => $tbpSettingAkhir->setting
                            ))
                        );
                        $response2 = $this->utilities->curl($request);
                        if ($response2->status == 'success') {
                            $rules = array(
                                'select'    => null,
                                'where'     => array(
                                    'idp_formulir' => $viewpFormulir->idp_formulir,
                                    'pembayaran' => 'BELUM'
                                ),
                                'or_where'        => null,
                                'like'                => null,
                                'or_like'            => null,
                                'order'                => null,
                                'limit'                => null,
                                'group_by'        => null,
                            );
                            $tbpPembayaran = $this->Tbp_pembayaran->search($rules);
                            if ($tbpPembayaran->num_rows() == 0) {
                                $data = array(
                                    'idp_formulir' => $viewpFormulir->idp_formulir,
                                    'id_billing' => $response->billing_id,
                                    'ids_bank' => $ids_bank,
                                    'va' => $response->va_acc_no,
                                    'pembayaran' => 'BELUM',
                                    'expire_at' => $tbpSettingAkhir2,
                                    'created_by' => 1,
                                    'updated_by' => 1
                                );
                                $fb = $this->Tbp_pembayaran->create($data);
                                if (!$fb['status']) {
                                    $response = array(
                                        'status' => 200,
                                        'message' => 'Berhasil memilih bank. Silahkan lanjutkan pembayaran.'
                                    );
                                } else {
                                    $response = array(
                                        'status' => 400,
                                        'message' => 'Gagal memilih bank. Silahkan ulangi.'
                                    );
                                }
                            } else {
                                $response = array(
                                    'status' => 400,
                                    'message' => 'Bank sudah dipilih. Silahkan lanjutkan pembayaran.'
                                );
                            }
                        } else {
                            $response = array(
                                'status' => 400,
                                'message' => $response2->message
                            );
                        }
                    } else {
                        $response = array(
                            'status' => 400,
                            'message' => $response->message
                        );
                    }
                } else {
                    /* Create Virtual Account */
                    $data = array(
                        "nim" => date('Y') . $viewpFormulir->idp_formulir,
                        "name" => strtoupper($viewpFormulir->nama),
                        "birthdate" => $viewpBiodata->tgl_lahir,
                        "birthplace" => $viewpBiodata->tempat_lahir,
                        "address" => $viewpRumah->jalan,
                        "phone" => $tblUsers->nmr_tlpn,
                        "premi_no" => "",
                        "nik" => $viewpBiodata->nik,
                        "ortu" =>  "-",
                        "ket" => "PRADAFTAR " . date('Y'),
                    );
                    $request = array(
                        'url' => $_ENV['BJBS_02_HOST'] . 'createVA/token/' . $_ENV['BJBS_02_TOKEN'],
                        "method" => "POST",
                        'header' => array(
                            "Content-Type:application/json",
                        ),
                        'request' => json_encode($data),
                    );
                    $respond = $this->utilities->curl($request);
                    if ($respond->status == 'success') {
                        $data_bill = array(
                            "nim" => date('Y') . $viewpFormulir->idp_formulir,
                            "amount" => $this->input->post('nominal'),
                            "product_id" => $_ENV['BJBS_02_PRODUCT_PRADAFTAR'],
                            "thn_akademik" => date('Y'),
                            "jenis_semester" => '1'
                        );
                        $parrams = array(
                            'url' => $_ENV['BJBS_02_HOST'] . 'createBill/token/' . $_ENV['BJBS_02_TOKEN'],
                            'method' => 'POST',
                            'header' => array(
                                "Content-Type:application/json",
                            ),
                            'request' => json_encode($data_bill),
                        );
                        $response = $this->utilities->curl($parrams);
                        if ($response->status == 'success') {
                            $request = array(
                                'url' => $_ENV['BJBS_02_HOST'] . 'updateExpired/token/' . $_ENV['BJBS_02_TOKEN'],
                                'method' => 'POST',
                                'header' => array(
                                    "Content-Type:application/json",
                                ),
                                'request' => json_encode(array(
                                    "billing_id" => $response->billing_id,
                                    "newDate" => $tbpSettingAkhir->setting
                                ))
                            );
                            $response2 = $this->utilities->curl($request);
                            if ($response2->status == 'success') {
                                $rules = array(
                                    'select'    => null,
                                    'where'     => array(
                                        'idp_formulir' => $viewpFormulir->idp_formulir,
                                        'pembayaran' => 'BELUM'
                                    ),
                                    'or_where'        => null,
                                    'like'                => null,
                                    'or_like'            => null,
                                    'order'                => null,
                                    'limit'                => null,
                                    'group_by'        => null,
                                );
                                $tbpPembayaran = $this->Tbp_pembayaran->search($rules);
                                if ($tbpPembayaran->num_rows() == 0) {
                                    $data = array(
                                        'idp_formulir' => $viewpFormulir->idp_formulir,
                                        'id_billing' => $response->billing_id,
                                        'ids_bank' => $ids_bank,
                                        'va' => $response->va_acc_no,
                                        'pembayaran' => 'BELUM',
                                        'expire_at' => $tbpSettingAkhir2,
                                        'created_by' => 1,
                                        'updated_by' => 1
                                    );
                                    $fb = $this->Tbp_pembayaran->create($data);
                                    if (!$fb['status']) {
                                        $response = array(
                                            'status' => 200,
                                            'message' => 'Berhasil memilih bank. Silahkan lanjutkan pembayaran.'
                                        );
                                    } else {
                                        $response = array(
                                            'status' => 400,
                                            'message' => 'Gagal memilih bank. Silahkan ulangi.'
                                        );
                                    }
                                } else {
                                    $response = array(
                                        'status' => 400,
                                        'message' => 'Bank sudah dipilih. Silahkan lanjutkan pembayaran.'
                                    );
                                }
                            } else {
                                $response = array(
                                    'status' => 400,
                                    'message' => $response2->message
                                );
                            }
                        } else {
                            $response = array(
                                'status' => 400,
                                'message' => $response->message
                            );
                        }
                    } else {
                        $response = array(
                            'status' => 400,
                            'message' => $respond->message
                        );
                    }
                }
            } else if ($ids_bank == 2) {
                $data = array(
                    "nim" => date('Y') . $viewpFormulir->idp_formulir,
                    "nama" => $viewpFormulir->nama,
                    "tagihan" => $this->input->post('nominal'),
                    "expired" => $tbpSettingAkhir->setting,
                    "description" => "PRADAFTAR " . date('Y')
                );
                $parrams = array(
                    'url' => $_ENV['BTN_HOST'] . 'VA/token/' . $_ENV['BTN_TOKEN'],
                    'method' => 'POST',
                    'header' => array(
                        "Content-Type:application/json",
                    ),
                    'request' => json_encode($data),
                );
                $response = $this->utilities->curl($parrams);
                if ($response->status == 'success') {
                    $rules = array(
                        'select'    => null,
                        'where'     => array(
                            'idp_formulir' => $viewpFormulir->idp_formulir,
                            'pembayaran' => 'BELUM'
                        ),
                        'or_where'        => null,
                        'like'                => null,
                        'or_like'            => null,
                        'order'                => null,
                        'limit'                => null,
                        'group_by'        => null,
                    );
                    $tbpPembayaran = $this->Tbp_pembayaran->search($rules);
                    if ($tbpPembayaran->num_rows() == 0) {
                        $data = array(
                            'idp_formulir' => $viewpFormulir->idp_formulir,
                            'id_billing' => 0,
                            'ids_bank' => $ids_bank,
                            'va' => $response->virtual_account,
                            'pembayaran' => 'BELUM',
                            'expire_at' => $tbpSettingAkhir2,
                            'created_by' => 1,
                            'updated_by' => 1
                        );
                        $fb = $this->Tbp_pembayaran->create($data);
                        if (!$fb['status']) {
                            $response = array(
                                'status' => 200,
                                'message' => 'Berhasil memilih bank. Silahkan lanjutkan pembayaran.'
                            );
                        } else {
                            $response = array(
                                'status' => 400,
                                'message' => 'Gagal memilih bank. Silahkan ulangi.'
                            );
                        }
                    } else {
                        $response = array(
                            'status' => 400,
                            'message' => 'Bank sudah dipilih. Silahkan lanjutkan pembayaran.'
                        );
                    }
                } else {
                    $response = array(
                        'status' => 400,
                        'message' => $response->message
                    );
                }
            } else if ($ids_bank == 3) {
                $rules = array(
                    'select'    => null,
                    'where'     => array(
                        'idp_formulir' => $viewpFormulir->idp_formulir,
                        'pembayaran' => 'BELUM'
                    ),
                    'or_where'        => null,
                    'like'                => null,
                    'or_like'            => null,
                    'order'                => null,
                    'limit'                => null,
                    'group_by'        => null,
                );
                $tbpPembayaran = $this->Tbp_pembayaran->search($rules);
                if ($tbpPembayaran->num_rows() == 0) {
                    $data_tagihan = array(
                        'no_pendaftar' => date('Y') . $viewpFormulir->idp_formulir,
                        'nama_pendaftar' => $viewpFormulir->nama,
                        'email' => $tblUsers->email,
                        'nominal' => $this->input->post('nominal'),
                        'bank' => '01',
                        'exp_date' => $tbpSettingAkhir->setting
                    );
                    $parrams = array(
                        'url' => $_ENV['SALAM_HOST'] . 'formulir',
                        'method' => 'POST',
                        'header' => array(
                            "Content-Type:application/x-www-form-urlencoded",
                            "Authorization:e418c1f7666f37f93192beb0fe06cae6",
                        ),
                        'request' => http_build_query($data_tagihan),
                    );
                    $response = $this->utilities->curl($parrams);
                    if ($response->status->code == 201) {
                        $data = array(
                            'idp_formulir' => $viewpFormulir->idp_formulir,
                            'id_billing' => 0,
                            'ids_bank' => $ids_bank,
                            'va' => 0,
                            'pembayaran' => 'BELUM',
                            'expire_at' => $tbpSettingAkhir2,
                            'created_by' => 1,
                            'updated_by' => 1
                        );
                        $fb = $this->Tbp_pembayaran->create($data);
                        if (!$fb['status']) {
                            $response = array(
                                'status' => 200,
                                'message' => 'Berhasil memilih bank. Silahkan lanjutkan pembayaran.'
                            );
                        } else {
                            $response = array(
                                'status' => 400,
                                'message' => 'Gagal memilih bank. Silahkan ulangi.'
                            );
                        }
                    } else {
                        $response = array(
                            'status' => 400,
                            'message' => $response->status->message
                        );
                    }
                } else {
                    $response = array(
                        'status' => 400,
                        'message' => 'Bank sudah dipilih. Silahkan lanjutkan pembayaran.'
                    );
                }
            } else if ($ids_bank == 4) {
                $rules = array(
                    'select'    => null,
                    'where'     => array(
                        'idp_formulir' => $viewpFormulir->idp_formulir,
                        'pembayaran' => 'BELUM'
                    ),
                    'or_where'        => null,
                    'like'                => null,
                    'or_like'            => null,
                    'order'                => null,
                    'limit'                => null,
                    'group_by'        => null,
                );
                $tbpPembayaran = $this->Tbp_pembayaran->search($rules);
                if ($tbpPembayaran->num_rows() == 0) {
                    $data_tagihan = array(
                        'no_pendaftar' => date('Y') . $viewpFormulir->idp_formulir,
                        'nama_pendaftar' => $viewpFormulir->nama,
                        'email' => $tblUsers->email,
                        'nominal' => $this->input->post('nominal'),
                        'bank' => '06',
                        'exp_date' => $tbpSettingAkhir->setting
                    );
                    $parrams = array(
                        'url' => $_ENV['SALAM_HOST'] . 'formulir',
                        'method' => 'POST',
                        'header' => array(
                            "Content-Type:application/x-www-form-urlencoded",
                            "Authorization:e418c1f7666f37f93192beb0fe06cae6",
                        ),
                        'request' => http_build_query($data_tagihan),
                    );
                    $response = $this->utilities->curl($parrams);
                    if ($response->status->code == 201) {
                        $data = array(
                            'idp_formulir' => $viewpFormulir->idp_formulir,
                            'id_billing' => 0,
                            'ids_bank' => $ids_bank,
                            'va' => 0,
                            'pembayaran' => 'BELUM',
                            'expire_at' => $tbpSettingAkhir2,
                            'created_by' => 1,
                            'updated_by' => 1
                        );
                        $fb = $this->Tbp_pembayaran->create($data);
                        if (!$fb['status']) {
                            $response = array(
                                'status' => 200,
                                'message' => 'Berhasil memilih bank. Silahkan lanjutkan pembayaran.'
                            );
                        } else {
                            $response = array(
                                'status' => 400,
                                'message' => 'Gagal memilih bank. Silahkan ulangi.'
                            );
                        }
                    } else {
                        $response = array(
                            'status' => 400,
                            'message' => $response->status->message
                        );
                    }
                } else {
                    $response = array(
                        'status' => 400,
                        'message' => 'Bank sudah dipilih. Silahkan lanjutkan pembayaran.'
                    );
                }
            } else if ($ids_bank == 5) {
                $rules = array(
                    'select'    => null,
                    'where'     => array(
                        'idp_formulir' => $viewpFormulir->idp_formulir,
                        'pembayaran' => 'BELUM'
                    ),
                    'or_where'        => null,
                    'like'                => null,
                    'or_like'            => null,
                    'order'                => null,
                    'limit'                => null,
                    'group_by'        => null,
                );
                $tbpPembayaran = $this->Tbp_pembayaran->search($rules);
                if ($tbpPembayaran->num_rows() == 0) {
                    $data_tagihan = array(
                        'no_pendaftar' => date('Y') . $viewpFormulir->idp_formulir,
                        'nama_pendaftar' => $viewpFormulir->nama,
                        'email' => $tblUsers->email,
                        'nominal' => $this->input->post('nominal'),
                        'bank' => '05',
                        'exp_date' => $tbpSettingAkhir->setting
                    );
                    $parrams = array(
                        'url' => $_ENV['SALAM_HOST'] . 'formulir',
                        'method' => 'POST',
                        'header' => array(
                            "Content-Type:application/x-www-form-urlencoded",
                            "Authorization:e418c1f7666f37f93192beb0fe06cae6",
                        ),
                        'request' => http_build_query($data_tagihan),
                    );
                    $response = $this->utilities->curl($parrams);
                    if ($response->status->code == 201) {
                        $data = array(
                            'idp_formulir' => $viewpFormulir->idp_formulir,
                            'id_billing' => 0,
                            'ids_bank' => $ids_bank,
                            'va' => 0,
                            'pembayaran' => 'BELUM',
                            'expire_at' => $tbpSettingAkhir2,
                            'created_by' => 1,
                            'updated_by' => 1
                        );
                        $fb = $this->Tbp_pembayaran->create($data);
                        if (!$fb['status']) {
                            $response = array(
                                'status' => 200,
                                'message' => 'Berhasil memilih bank. Silahkan lanjutkan pembayaran.'
                            );
                        } else {
                            $response = array(
                                'status' => 400,
                                'message' => 'Gagal memilih bank. Silahkan ulangi.'
                            );
                        }
                    } else {
                        $response = array(
                            'status' => 400,
                            'message' => $response->status->message
                        );
                    }
                } else {
                    $response = array(
                        'status' => 400,
                        'message' => 'Bank sudah dipilih. Silahkan lanjutkan pembayaran.'
                    );
                }
            } else {
                $response = array(
                    'status' => 400,
                    'message' => 'Bank tidak bisa dipilih.'
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

    public function BatalkanBank($ids_bank, $idp_formulir)
    {
        $response = null;
        $hak_akses = $this->master->read('hak-akses/?ids_level=' . $this->jwt->ids_level . '&ids_modul=67&aksi_hak_akses=delete');
        if ($hak_akses->code == 200) {
            $rules = array(
                'select'    => null,
                'where'     => array(
                    'idp_formulir' => $idp_formulir,
                    'pembayaran' => 'BELUM'
                ),
                'or_where'        => null,
                'like'                => null,
                'or_like'            => null,
                'order'                => null,
                'limit'                => null,
                'group_by'        => null,
            );
            $tbpPembayaran = $this->Viewp_pembayaran->search($rules)->row();
            if ($ids_bank == 1) {
                $request = array(
                    'url' => $_ENV['BJBS_02_HOST'] . 'getBilling/token/' . $_ENV['BJBS_02_TOKEN'],
                    'method' => 'POST',
                    'header' => array(
                        "Content-Type:application/json",
                    ),
                    'request' => json_encode(array(
                        "nim" => date('Y') . $idp_formulir
                    ))
                );
                $response = $this->utilities->curl($request);
                $success = $error = 0;
                if ($response->status == 'success') {
                    foreach ($response->data->billing as $a) {
                        $data = array(
                            "billing_id" => $a->billing_id,
                        );
                        $parrams = array(
                            'url' => $_ENV['BJBS_02_HOST'] . 'nonActiveBilling/token/' . $_ENV['BJBS_02_TOKEN'],
                            'method' => 'DELETE',
                            'header' => array(
                                "Content-Type:application/json",
                            ),
                            'request' => json_encode($data),
                        );
                        $respond = $this->utilities->curl($parrams);
                        if ($respond->status == 'success') {
                            $rules = array(
                                'where' => array(
                                    'id_billing' => $tbpPembayaran->id_billing,
                                ),
                                'or_where'            => null,
                                'like'                => null,
                                'or_like'            => null,
                                'data'  => array(
                                    'pembayaran' => 'EXPIRED',
                                ),
                            );
                            $fb = $this->Tbp_pembayaran->update($rules);
                            if (!$fb['status']) {
                                $success++;
                            } else {
                                $error++;
                            }
                        } else {
                            $error++;
                        }
                    }
                    $response = array(
                        'status' => 200,
                        'message' => 'Berhasil membatalkan pilihan bank.'
                    );
                } else {
                    $response = array(
                        'status' => 400,
                        'message' => $response->message
                    );
                }
            } else if ($ids_bank == 2) {
                $data = array(
                    "va" => $tbpPembayaran->va,
                );
                $parrams = array(
                    'url' => $_ENV['BTN_HOST'] . 'VA/token/' . $_ENV['BTN_TOKEN'],
                    'method' => 'DELETE',
                    'header' => array(
                        "Content-Type:application/json",
                    ),
                    'request' => json_encode($data),
                );
                $respond = $this->utilities->curl($parrams);
                if ($respond->status == 'success') {
                    $rules = array(
                        'where' => array(
                            'va' => $tbpPembayaran->va,
                            'idp_formulir' => $idp_formulir,
                            'pembayaran' => 'BELUM'
                        ),
                        'or_where'            => null,
                        'like'                => null,
                        'or_like'            => null,
                        'data'  => array(
                            'pembayaran' => 'EXPIRED',
                        ),
                    );
                    $fb = $this->Tbp_pembayaran->update($rules);
                    if (!$fb['status']) {
                        $response = array(
                            'status' => 200,
                            'message' => 'Berhasil membatalkan pilihan bank.'
                        );
                    } else {
                        $response = array(
                            'status' => 400,
                            'message' => 'Gagal membatalkan pilihan bank.'
                        );
                    }
                } else {
                    $response = array(
                        'status' => 400,
                        'message' => 'Gagal membatalkan pilihan bank. Silahkan coba lagi.'
                    );
                }
            } else if ($ids_bank == 3) {
                $data = array(
                    'no_pendaftar' => date('Y') . $idp_formulir,
                    'bank' => '01'
                );
                $parrams = array(
                    'url' => $_ENV['SALAM_HOST'] . 'formulir/cancel',
                    'method' => 'POST',
                    'header' => array(
                        "Content-Type:application/x-www-form-urlencoded",
                        "Authorization:e418c1f7666f37f93192beb0fe06cae6",
                    ),
                    'request' => http_build_query($data),
                );
                $respond = $this->utilities->curl($parrams);
                if ($respond->status->code == 200) {
                    $rules = array(
                        'where' => array(
                            'idp_formulir' => $idp_formulir,
                            'pembayaran' => 'BELUM'
                        ),
                        'or_where'            => null,
                        'like'                => null,
                        'or_like'            => null,
                        'data'  => array(
                            'pembayaran' => 'EXPIRED',
                        ),
                    );
                    $fb = $this->Tbp_pembayaran->update($rules);
                    if (!$fb['status']) {
                        $response = array(
                            'status' => 200,
                            'message' => 'Berhasil membatalkan pilihan bank.'
                        );
                    } else {
                        $response = array(
                            'status' => 400,
                            'message' => 'Gagal membatalkan pilihan bank.'
                        );
                    }
                } else {
                    $response = array(
                        'status' => 400,
                        'message' => 'Gagal membatalkan pilihan bank. Silahkan coba lagi.'
                    );
                }
            } else if ($ids_bank == 4) {
                $data = array(
                    'no_pendaftar' => date('Y') . $idp_formulir,
                    'bank' => '06'
                );
                $parrams = array(
                    'url' => $_ENV['SALAM_HOST'] . 'formulir/cancel',
                    'method' => 'POST',
                    'header' => array(
                        "Content-Type:application/x-www-form-urlencoded",
                        "Authorization:e418c1f7666f37f93192beb0fe06cae6",
                    ),
                    'request' => http_build_query($data),
                );
                $respond = $this->utilities->curl($parrams);
                if ($respond->status->code == 200) {
                    $rules = array(
                        'where' => array(
                            'idp_formulir' => $idp_formulir,
                            'pembayaran' => 'BELUM'
                        ),
                        'or_where'            => null,
                        'like'                => null,
                        'or_like'            => null,
                        'data'  => array(
                            'pembayaran' => 'EXPIRED',
                        ),
                    );
                    $fb = $this->Tbp_pembayaran->update($rules);
                    if (!$fb['status']) {
                        $response = array(
                            'status' => 200,
                            'message' => 'Berhasil membatalkan pilihan bank.'
                        );
                    } else {
                        $response = array(
                            'status' => 400,
                            'message' => 'Gagal membatalkan pilihan bank.'
                        );
                    }
                } else {
                    $response = array(
                        'status' => 400,
                        'message' => 'Gagal membatalkan pilihan bank. Silahkan coba lagi.'
                    );
                }
            } else if ($ids_bank == 5) {
                $data = array(
                    'no_pendaftar' => date('Y') . $idp_formulir,
                    'bank' => '05'
                );
                $parrams = array(
                    'url' => $_ENV['SALAM_HOST'] . 'formulir/cancel',
                    'method' => 'POST',
                    'header' => array(
                        "Content-Type:application/x-www-form-urlencoded",
                        "Authorization:e418c1f7666f37f93192beb0fe06cae6",
                    ),
                    'request' => http_build_query($data),
                );
                $respond = $this->utilities->curl($parrams);
                if ($respond->status->code == 200) {
                    $rules = array(
                        'where' => array(
                            'idp_formulir' => $idp_formulir,
                            'pembayaran' => 'BELUM'
                        ),
                        'or_where'            => null,
                        'like'                => null,
                        'or_like'            => null,
                        'data'  => array(
                            'pembayaran' => 'EXPIRED',
                        ),
                    );
                    $fb = $this->Tbp_pembayaran->update($rules);
                    if (!$fb['status']) {
                        $response = array(
                            'status' => 200,
                            'message' => 'Berhasil membatalkan pilihan bank.'
                        );
                    } else {
                        $response = array(
                            'status' => 400,
                            'message' => 'Gagal membatalkan pilihan bank.'
                        );
                    }
                } else {
                    $response = array(
                        'status' => 400,
                        'message' => 'Gagal membatalkan pilihan bank. Silahkan coba lagi.'
                    );
                }
            } else {
                $response = array(
                    'status' => 400,
                    'message' => 'Bank tidak bisa dipilih.'
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
        $hak_akses = $this->master->read('hak-akses/?ids_level=' . $this->jwt->ids_level . '&ids_modul=67&aksi_hak_akses=read');
        if ($hak_akses->code == 200) {
            $rules = array(
                'where' => array(
                    'idp_pembayaran'  => $this->input->post('idp_pembayaran_edit'),
                ),
                'or_where'            => null,
                'like'                => null,
                'or_like'            => null,
                'data'  => array(
                    'pembayaran'  => $this->input->post('pembayaran_edit'),
                    'id_billing'  => $this->input->post('id_billing_edit'),
                    'updated_by'  => 1,
                ),
            );
            $fb = $this->Tbp_pembayaran->update($rules);
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
            $response = array(
                'status' => 400,
                'message' => 'Hak akses ditolak.'
            );
        }
        echo json_encode($response);
    }

    function JsonFormFilter()
    {
        $list = $this->SS_pembayaran_mandiri->get_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $row) {
            $sub_array = array();
            if ($row->pembayaran == "BELUM") {
                $pembayaran = "<div class='badge bg-danger'>Belum</div>";
            } else if ($row->pembayaran == "SUDAH") {
                $pembayaran = "<div class='badge bg-success'>Sudah</div>";
            } else {
                $pembayaran = "<div class='badge bg-warning'>Kadaluarsa</div>";
            }
            $sub_array[] = ++$no;
            $sub_array[] = "
                <div class=\"dropdown\">
                    <button class=\"btn btn-xs btn-secondary dropdown-toggle\" type=\"button\" id=\"dropdownMenuButton1\" data-bs-toggle=\"dropdown\" aria-expanded=\"false\">
                        <i class=\"bx bx-cog\"></i>
                    </button>
                    <ul class=\"dropdown-menu\" aria-labelledby=\"dropdownMenuButton1\">
                        <li><a class=\"dropdown-item\" href=\"javascript:void(0)\" onclick=\"edit_bank(" . $row->idp_pembayaran . ")\">Edit</a></li>
                        <li><a class=\"dropdown-item\" href=\"javascript:void(0)\" onclick=\"batalkan_bank(" . $row->ids_bank . "," . $row->idp_formulir . ")\">Batalkan</a></li>
                    </ul>
                </div>
            ";
            $sub_array[] = date('Y') . $row->idp_formulir;
            $sub_array[] = $row->nomor_peserta;
            $sub_array[] = $row->nama;
            $sub_array[] = $row->alias_bank;
            $sub_array[] = $row->va;
            $sub_array[] = $row->id_billing;
            $sub_array[] = $row->expire_at;
            $sub_array[] = $pembayaran;
            $sub_array[] = $row->date_created;
            $sub_array[] = $row->date_updated;
            $data[] = $sub_array;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->SS_pembayaran_mandiri->count_all(),
            "recordsFiltered" => $this->SS_pembayaran_mandiri->count_filtered(),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }
}
