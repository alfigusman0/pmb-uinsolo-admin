<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Pembayaran extends CI_Controller
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
                redirect('Auth/Notifikasi/');
            }
        } else {
            header('Location: ' . $_ENV['SSO']);
        }
        $this->load->model('ServerSide/SS_pembayaran');
        $this->load->model('Daftar/Tbd_ukt');
        $this->load->model('Daftar/Tbd_kelulusan');
        $this->load->model('Daftar/Tbd_pembayaran');
        $this->load->model('Daftar/Viewd_kelulusan');
        $this->load->model('Daftar/Viewd_ukt');
        $this->load->model('Daftar/Viewd_pembayaran');
        $this->load->model('Settings/Views_jalur_masuk');
    }

    function index()
    {
        $hak_akses = $this->master->read('hak-akses/?ids_level=' . $this->jwt->ids_level . '&ids_modul=52&aksi_hak_akses=read');
        if ($hak_akses->code == 200) {
            $data = array(
                'title'         => 'Pembayaran | ' . $_ENV['APPLICATION_NAME'],
                'content'       => 'Daftar/pembayaran/content',
                'css'           => 'Daftar/pembayaran/css',
                'javascript'    => 'Daftar/pembayaran/javascript',
                'modal'         => 'Daftar/pembayaran/modal',
            );
            $this->load->view('index', $data);
        } else {
            $this->session->set_flashdata('message', 'Hak akses ditolak.');
            $this->session->set_flashdata('type_message', 'danger');
            redirect('dashboard/');
        }
    }

    public function PilihBank()
    {
        $response = null;
        $hak_akses = $this->master->read('hak-akses/?ids_level=' . $this->jwt->ids_level . '&ids_modul=52&aksi_hak_akses=create');
        if ($hak_akses->code == 200) {
            $np = $this->input->post('nomor_peserta');
            $ids_bank = $this->input->post('ids_bank');
            if (empty($ids_bank)) {
                $response = array(
                    'status' => 400,
                    'message' => 'Silahkan pilih bank terlebih dahulu.'
                );
            }
            $rules = array(
                'database'  => null,
                'select'    => null,
                'where'     => array(
                    'nomor_peserta' => $np,
                ),
                'or_where'        => null,
                'like'                => null,
                'or_like'            => null,
                'order'                => null,
                'limit'                => null,
                'group_by'        => null,
            );
            $viewdKelulusan = $this->Viewd_kelulusan->search($rules)->row();
            $viewdUKT = $this->Viewd_ukt->search($rules)->row();
            $cek_tagihan = array(
                'nim' => $viewdKelulusan->nomor_peserta,
                'periode' => date('Y') . '1'
            );
            $parrams = array(
                'url' => $_ENV['SALAM_HOST'] . 'tagihan/get',
                'method' => 'POST',
                'header' => array(
                    "Content-Type:application/x-www-form-urlencoded",
                    "Authorization:e418c1f7666f37f93192beb0fe06cae6",
                ),
                'request' => http_build_query($cek_tagihan),
            );
            $response = $this->utilities->curl($parrams);

            if ($response->status->code == 200) {
                $exp_date = date("Y-m-d H:i:s", strtotime($response->data->exp_date));
                if ($ids_bank == 2) {
                    $data = array(
                        "nim" => $viewdUKT->nomor_peserta,
                        "nama" => $viewdUKT->nama,
                        "tagihan" => $response->data->nominal,
                        "expired" => $exp_date,
                        "description" => $viewdUKT->alias_jalur_masuk . " " . date('Y')
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
                            'database'  => null,
                            'select'    => null,
                            'where'     => array(
                                'idd_kelulusan' => $viewdKelulusan->idd_kelulusan,
                                'pembayaran' => 'BELUM'
                            ),
                            'or_where'        => null,
                            'like'                => null,
                            'or_like'            => null,
                            'order'                => null,
                            'limit'                => null,
                            'group_by'        => null,
                        );
                        $tbdPembayaran = $this->Tbd_pembayaran->search($rules);
                        if ($tbdPembayaran->num_rows() == 0) {
                            $data = array(
                                'idd_kelulusan' => $viewdKelulusan->idd_kelulusan,
                                'id_billing' => 0,
                                'ids_bank' => $ids_bank,
                                'va' => $response->virtual_account,
                                'pembayaran' => 'BELUM',
                                'expire_at' => $exp_date,
                                'created_by' => $this->jwt->id_user,
                                'updated_by' => $this->jwt->id_user
                            );
                            $fb = $this->Tbd_pembayaran->create($data);
                            if (!$fb['status']) {
                                $data_tagihan = array(
                                    'nim' => $viewdKelulusan->nomor_peserta,
                                    'periode' => date('Y') . '1',
                                    'billing_id' => 0,
                                    'va' => $response->virtual_account,
                                    'bank' => '03',
                                    'exp_date' => $exp_date
                                );
                                $parrams = array(
                                    'url' => $_ENV['SALAM_HOST'] . 'tagihan',
                                    'method' => 'POST',
                                    'header' => array(
                                        "Content-Type:application/x-www-form-urlencoded",
                                        "Authorization:e418c1f7666f37f93192beb0fe06cae6",
                                    ),
                                    'request' => http_build_query($data_tagihan),
                                );
                                $response = $this->utilities->curl($parrams);
                                $msg = array(
                                    'status' => 200,
                                    'message' => 'Berhasil memilih bank. Silahkan lanjutkan pembayaran.'
                                );
                            } else {
                                $msg = array(
                                    'status' => 400,
                                    'message' => 'Bank gagal dipilih. Silahkan ulangi.'
                                );
                            }
                        } else {
                            $msg = array(
                                'status' => 400,
                                'message' => 'Bank sudah dipilih. Silahkan lanjutkan pembayaran.'
                            );
                        }
                    } else {
                        $msg = array(
                            'status' => 400,
                            'message' => $response->message
                        );
                    }
                } else if ($ids_bank == 4) {
                    $rules = array(
                        'database'  => null,
                        'select'    => null,
                        'where'     => array(
                            'idd_kelulusan' => $viewdUKT->idd_kelulusan,
                            'pembayaran' => 'BELUM'
                        ),
                        'or_where'        => null,
                        'like'                => null,
                        'or_like'            => null,
                        'order'                => null,
                        'limit'                => null,
                        'group_by'        => null,
                    );
                    $tbdPembayaran = $this->Tbd_pembayaran->search($rules);
                    if ($tbdPembayaran->num_rows() == 0) {
                        $data_tagihan = array(
                            'nim' => $viewdUKT->nomor_peserta,
                            'periode' => date('Y') . '1',
                            'billing_id' => 0,
                            'va' => $viewdUKT->nomor_peserta,
                            'bank' => '06',
                            'exp_date' => $exp_date
                        );
                        $parrams = array(
                            'url' => $_ENV['SALAM_HOST'] . 'tagihan',
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
                                'idd_kelulusan' => $viewdKelulusan->idd_kelulusan,
                                'id_billing' => 0,
                                'ids_bank' => $ids_bank,
                                'va' => $viewdUKT->nomor_peserta,
                                'pembayaran' => 'BELUM',
                                'expire_at' => $exp_date,
                                'created_by' => $this->jwt->id_user,
                                'updated_by' => $this->jwt->id_user
                            );
                            $fb = $this->Tbd_pembayaran->create($data);
                            if (!$fb['status']) {
                                $msg = array(
                                    'status' => 200,
                                    'message' => 'Berhasil memilih bank. Silahkan lanjutkan pembayaran.'
                                );
                            } else {
                                $msg = array(
                                    'status' => 200,
                                    'message' => 'Gagal memilih bank. Silahkan ulangi.'
                                );
                            }
                        } else {
                            $msg = array(
                                'status' => 400,
                                'message' => $response->status->message
                            );
                        }
                    } else {
                        $msg = array(
                            'status' => 400,
                            'message' => 'Bank sudah dipilih. Silahkan lanjutkan pembayaran.'
                        );
                    }
                } else if ($ids_bank == 5) {
                    $rules = array(
                        'database'  => null,
                        'select'    => null,
                        'where'     => array(
                            'idd_kelulusan' => $viewdUKT->idd_kelulusan,
                            'pembayaran' => 'BELUM'
                        ),
                        'or_where'        => null,
                        'like'                => null,
                        'or_like'            => null,
                        'order'                => null,
                        'limit'                => null,
                        'group_by'        => null,
                    );
                    $tbdPembayaran = $this->Tbd_pembayaran->search($rules);
                    if ($tbdPembayaran->num_rows() == 0) {
                        if ($viewdKelulusan->ids_jalur_masuk == 5) {
                            $va = '88525' . $this->utilities->NPForVA($viewdKelulusan->nomor_peserta);
                        } else {
                            $va = '88525' . $viewdKelulusan->nomor_peserta;
                        }
                        $data_tagihan = array(
                            'nim' => $viewdKelulusan->nomor_peserta,
                            'periode' => date('Y') . '1',
                            'billing_id' => 0,
                            'va' => $va,
                            'bank' => '05',
                            'exp_date' => $exp_date
                        );
                        $parrams = array(
                            'url' => $_ENV['SALAM_HOST'] . 'tagihan',
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
                                'idd_kelulusan' => $viewdKelulusan->idd_kelulusan,
                                'id_billing' => 0,
                                'ids_bank' => $ids_bank,
                                'va' => $va,
                                'pembayaran' => 'BELUM',
                                'expire_at' => $exp_date,
                                'created_by' => $this->jwt->id_user,
                                'updated_by' => $this->jwt->id_user
                            );
                            $fb = $this->Tbd_pembayaran->create($data);
                            if (!$fb['status']) {
                                $msg = array(
                                    'status' => 200,
                                    'message' => 'Berhasil memilih bank. Silahkan lanjutkan pembayaran.'
                                );
                            } else {
                                $msg = array(
                                    'status' => 200,
                                    'message' => 'Gagal memilih bank. Silahkan ulangi.'
                                );
                            }
                        } else {
                            $msg = array(
                                'status' => 400,
                                'message' => $response->status->message
                            );
                        }
                    } else {
                        $msg = array(
                            'status' => 400,
                            'message' => 'Bank sudah dipilih. Silahkan lanjutkan pembayaran.'
                        );
                    }
                } else if ($ids_bank == 1) {
                    // $request = array(
                    // 	'url' => $_ENV['BJBS_02_HOST'] . 'getBilling/token/' . $_ENV['BJBS_02_TOKEN'],
                    // 	'method' => 'POST',
                    // 	'header' => array(
                    // 		"Content-Type:application/json",
                    // 	),
                    // 	'request' => json_encode(array(
                    // 		"nim" => $viewdKelulusan->nomor_peserta
                    // 	))
                    // );
                    // $response = $this->utilities->curl($request);
                    // $success = $error = 0;
                    // if ($response->status == 'success') {
                    // 	foreach ($response->data->billing as $a) {
                    //         if($a->expired == false || $a->pembayaran == 'Belum Bayar'){
                    //             $parrams = array(
                    //                 'url' => $_ENV['BJBS_02_HOST'] . 'nonActiveBilling/token/' . $_ENV['BJBS_02_TOKEN'],
                    //                 'method' => 'DELETE',
                    //                 'header' => array(
                    //                     "Content-Type:application/json",
                    //                 ),
                    //                 'request' => json_encode(array(
                    //                     "billing_id" => $a->billing_id,
                    //                 )),
                    //             );
                    //             $response = $this->utilities->curl($parrams);
                    //             if ($response->status == 'success') {
                    //                 $success++;
                    //             } else {
                    //                 $error++;
                    //             }
                    //         }
                    // 	}
                    // }
                    $data_bill = array(
                        "nim" => $viewdKelulusan->nomor_peserta,
                        "amount" => $response->data->nominal,
                        "product_id" => $_ENV['BJBS_02_PRODUCT_DAFTAR'],
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
                                "newDate" => $exp_date
                            ))
                        );
                        $response2 = $this->utilities->curl($request);
                        if ($response2->status == 'success') {
                            $rules = array(
                                'database'  => null,
                                'select'    => null,
                                'where'     => array(
                                    'idd_kelulusan' => $viewdKelulusan->idd_kelulusan,
                                    'pembayaran' => 'BELUM'
                                ),
                                'or_where'        => null,
                                'like'                => null,
                                'or_like'            => null,
                                'order'                => null,
                                'limit'                => null,
                                'group_by'        => null,
                            );
                            $tbdPembayaran = $this->Tbd_pembayaran->search($rules);
                            if ($tbdPembayaran->num_rows() == 0) {
                                $data = array(
                                    'idd_kelulusan' => $viewdKelulusan->idd_kelulusan,
                                    'id_billing' => $response->billing_id,
                                    'ids_bank' => $ids_bank,
                                    'va' => $response->va_acc_no,
                                    'pembayaran' => 'BELUM',
                                    'expire_at' => $exp_date,
                                    'created_by' => $this->jwt->id_user,
                                    'updated_by' => $this->jwt->id_user
                                );
                                $fb = $this->Tbd_pembayaran->create($data);
                                if (!$fb['status']) {
                                    $data_tagihan = array(
                                        'nim' => $viewdKelulusan->nomor_peserta,
                                        'periode' => date('Y') . '1',
                                        'billing_id' => $response->billing_id,
                                        'va' => $response->va_acc_no,
                                        'bank' => '02',
                                        'exp_date' => $exp_date
                                    );
                                    $parrams = array(
                                        'url' => $_ENV['SALAM_HOST'] . 'tagihan',
                                        'method' => 'POST',
                                        'header' => array(
                                            "Content-Type:application/x-www-form-urlencoded",
                                            "Authorization:e418c1f7666f37f93192beb0fe06cae6",
                                        ),
                                        'request' => http_build_query($data_tagihan),
                                    );
                                    $response = $this->utilities->curl($parrams);
                                    $msg = array(
                                        'status' => 200,
                                        'message' => 'Berhasil memilih bank. Silahkan lanjutkan pembayaran.'
                                    );
                                } else {
                                    $msg = array(
                                        'status' => 200,
                                        'message' => 'Gagal memilih bank. Silahkan ulangi.'
                                    );
                                }
                            } else {
                                $msg = array(
                                    'status' => 400,
                                    'message' => 'Bank sudah dipilih. Silahkan lanjutkan pembayaran.'
                                );
                            }
                        } else {
                            $msg = array(
                                'status' => 400,
                                'message' => $response2->message
                            );
                        }
                    } else {
                        $msg = array(
                            'status' => 400,
                            'message' => $response->message
                        );
                    }
                } else if ($ids_bank == 3) {
                    $rules = array(
                        'database'  => null,
                        'select'    => null,
                        'where'     => array(
                            'idd_kelulusan' => $viewdUKT->idd_kelulusan,
                            'pembayaran' => 'BELUM'
                        ),
                        'or_where'        => null,
                        'like'                => null,
                        'or_like'            => null,
                        'order'                => null,
                        'limit'                => null,
                        'group_by'        => null,
                    );
                    $tbdPembayaran = $this->Tbd_pembayaran->search($rules);
                    if ($tbdPembayaran->num_rows() == 0) {
                        if ($viewdKelulusan->ids_jalur_masuk == 5) {
                            $va = '905300' . $this->utilities->NPForVA($viewdKelulusan->nomor_peserta);
                        } else {
                            $va = '905300' . $viewdKelulusan->nomor_peserta;
                        }
                        $data_tagihan = array(
                            'nim' => $viewdUKT->nomor_peserta,
                            'periode' => date('Y') . '1',
                            'billing_id' => 0,
                            'va' => $va,
                            'bank' => '01',
                            'exp_date' => $exp_date
                        );
                        $parrams = array(
                            'url' => $_ENV['SALAM_HOST'] . 'tagihan',
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
                                'idd_kelulusan' => $viewdKelulusan->idd_kelulusan,
                                'id_billing' => 0,
                                'ids_bank' => $ids_bank,
                                'va' => $va,
                                'pembayaran' => 'BELUM',
                                'expire_at' => $exp_date,
                                'created_by' => $this->jwt->id_user,
                                'updated_by' => $this->jwt->id_user
                            );
                            $fb = $this->Tbd_pembayaran->create($data);
                            if (!$fb['status']) {
                                $msg = array(
                                    'status' => 200,
                                    'message' => 'Berhasil memilih bank. Silahkan lanjutkan pembayaran.'
                                );
                            } else {
                                $msg = array(
                                    'status' => 200,
                                    'message' => 'Gagal memilih bank. Silahkan ulangi.'
                                );
                            }
                        } else {
                            $msg = array(
                                'status' => 400,
                                'message' => $response->status->message
                            );
                        }
                    } else {
                        $msg = array(
                            'status' => 400,
                            'message' => 'Bank sudah dipilih. Silahkan lanjutkan pembayaran.'
                        );
                    }
                }
            } else {
                $msg = array(
                    'status' => 400,
                    'message' => $response->status->message
                );
            }
        } else {
            $response = array(
                'status' => 400,
                'message' => 'Hak akses ditolak.'
            );
        }
        echo json_encode($msg);
    }

    public function BatalkanBank($ids_bank, $idd_kelulusan)
    {
        $response = null;
        $hak_akses = $this->master->read('hak-akses/?ids_level=' . $this->jwt->ids_level . '&ids_modul=52&aksi_hak_akses=delete');
        if ($hak_akses->code == 200) {
            $rules = array(
                'database'  => null,
                'select'    => null,
                'where'     => array(
                    'idd_kelulusan' => $idd_kelulusan,
                    'pembayaran' => 'BELUM'
                ),
                'or_where'        => null,
                'like'                => null,
                'or_like'            => null,
                'order'                => null,
                'limit'                => null,
                'group_by'        => null,
            );
            $tbdPembayaran = $this->Viewd_pembayaran->search($rules)->row();
            if ($ids_bank == 2) {
                $data = array(
                    'nim' => $tbdPembayaran->nomor_peserta
                );
                $parrams = array(
                    'url' => $_ENV['SALAM_HOST'] . 'tagihan/cancel',
                    'method' => 'POST',
                    'header' => array(
                        "Content-Type:application/x-www-form-urlencoded",
                        "Authorization:e418c1f7666f37f93192beb0fe06cae6",
                    ),
                    'request' => http_build_query($data),
                );
                $respond = $this->utilities->curl($parrams);
                if ($respond->status->code == 200) {
                    $data = array(
                        "va" => $tbdPembayaran->va,
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
                                'va' => $tbdPembayaran->va,
                                'idd_kelulusan' => $idd_kelulusan,
                                'pembayaran' => 'BELUM'
                            ),
                            'or_where'            => null,
                            'like'                => null,
                            'or_like'            => null,
                            'data'  => array(
                                'pembayaran' => 'EXPIRED',
                            ),
                        );
                        $fb = $this->Tbd_pembayaran->update($rules);
                        if (!$fb['status']) {
                            $msg = array(
                                'status' => 200,
                                'message' => 'Berhasil membatalkan pilihan bank.'
                            );
                        } else {
                            $msg = array(
                                'status' => 400,
                                'message' => 'Gagal membatalkan pilihan bank. Silahkan coba lagi.'
                            );
                        }
                    } else {
                        $msg = array(
                            'status' => 400,
                            'message' => 'Gagal membatalkan pilihan bank. Silahkan coba lagi.'
                        );
                    }
                } else {
                    $msg = array(
                        'status' => 400,
                        'message' => 'Gagal membatalkan pilihan bank. Silahkan coba lagi.'
                    );
                }
            } else if ($ids_bank == 4) {
                $data = array(
                    'nim' => $tbdPembayaran->nomor_peserta
                );
                $parrams = array(
                    'url' => $_ENV['SALAM_HOST'] . 'tagihan/cancel',
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
                            'va' => $tbdPembayaran->va,
                            'idd_kelulusan' => $idd_kelulusan,
                            'pembayaran' => 'BELUM'
                        ),
                        'or_where'            => null,
                        'like'                => null,
                        'or_like'            => null,
                        'data'  => array(
                            'pembayaran' => 'EXPIRED',
                        ),
                    );
                    $fb = $this->Tbd_pembayaran->update($rules);
                    if (!$fb['status']) {
                        $msg = array(
                            'status' => 200,
                            'message' => 'Berhasil membatalkan pilihan bank.'
                        );
                    } else {
                        $msg = array(
                            'status' => 400,
                            'message' => 'Gagal membatalkan pilihan bank. Silahkan coba lagi.'
                        );
                    }
                } else {
                    $rules = array(
                        'where' => array(
                            'va' => $tbdPembayaran->va,
                            'idd_kelulusan' => $idd_kelulusan,
                            'pembayaran' => 'BELUM'
                        ),
                        'data'  => array(
                            'pembayaran' => 'EXPIRED',
                        ),
                    );
                    $fb = $this->Tbd_pembayaran->update($rules);
                    if (!$fb['status']) {
                        $msg = array(
                            'status' => 200,
                            'message' => 'Berhasil membatalkan pilihan bank.'
                        );
                    } else {
                        $msg = array(
                            'status' => 400,
                            'message' => 'Gagal membatalkan pilihan bank. Silahkan coba lagi.'
                        );
                    }
                }
            } else if ($ids_bank == 5) {
                $data = array(
                    'nim' => $tbdPembayaran->nomor_peserta
                );
                $parrams = array(
                    'url' => $_ENV['SALAM_HOST'] . 'tagihan/cancel',
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
                            'va' => $tbdPembayaran->va,
                            'idd_kelulusan' => $idd_kelulusan,
                            'pembayaran' => 'BELUM'
                        ),
                        'or_where'            => null,
                        'like'                => null,
                        'or_like'            => null,
                        'data'  => array(
                            'pembayaran' => 'EXPIRED',
                        ),
                    );
                    $fb = $this->Tbd_pembayaran->update($rules);
                    if (!$fb['status']) {
                        $msg = array(
                            'status' => 200,
                            'message' => 'Berhasil membatalkan pilihan bank.'
                        );
                    } else {
                        $msg = array(
                            'status' => 400,
                            'message' => 'Gagal membatalkan pilihan bank. Silahkan coba lagi.'
                        );
                    }
                } else {
                    $rules = array(
                        'where' => array(
                            'va' => $tbdPembayaran->va,
                            'idd_kelulusan' => $idd_kelulusan,
                            'pembayaran' => 'BELUM'
                        ),
                        'data'  => array(
                            'pembayaran' => 'EXPIRED',
                        ),
                    );
                    $fb = $this->Tbd_pembayaran->update($rules);
                    if (!$fb['status']) {
                        $msg = array(
                            'status' => 200,
                            'message' => 'Berhasil membatalkan pilihan bank.'
                        );
                    } else {
                        $msg = array(
                            'status' => 400,
                            'message' => 'Gagal membatalkan pilihan bank. Silahkan coba lagi.'
                        );
                    }
                }
            } else if ($ids_bank == 1) {
                $data = array(
                    'nim' => $tbdPembayaran->nomor_peserta
                );
                $parrams = array(
                    'url' => $_ENV['SALAM_HOST'] . 'tagihan/cancel',
                    'method' => 'POST',
                    'header' => array(
                        "Content-Type:application/x-www-form-urlencoded",
                        "Authorization:e418c1f7666f37f93192beb0fe06cae6",
                    ),
                    'request' => http_build_query($data),
                );
                $respond = $this->utilities->curl($parrams);
                if ($respond->status->code == 200) {
                    $request = array(
                        'url' => $_ENV['BJBS_02_HOST'] . 'getBilling/token/' . $_ENV['BJBS_02_TOKEN'],
                        'method' => 'POST',
                        'header' => array(
                            "Content-Type:application/json",
                        ),
                        'request' => json_encode(array(
                            "nim" => $tbdPembayaran->nomor_peserta
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
                                        'id_billing' => $tbdPembayaran->id_billing,
                                    ),
                                    'or_where'            => null,
                                    'like'                => null,
                                    'or_like'            => null,
                                    'data'  => array(
                                        'pembayaran' => 'EXPIRED',
                                    ),
                                );
                                $fb = $this->Tbd_pembayaran->update($rules);
                                if (!$fb['status']) {
                                    $success++;
                                } else {
                                    $error++;
                                }
                            } else {
                                $error++;
                            }
                        }
                        if ($error > 0) {
                            $msg = array(
                                'status' => 200,
                                'message' => 'Gagal membatalkan pilihan bank. Silahkan coba lagi.'
                            );
                        } else {
                            $msg = array(
                                'status' => 200,
                                'message' => 'Berhasil membatalkan pilihan bank.'
                            );
                        }
                    }
                } else {
                    $msg = array(
                        'status' => 200,
                        'message' => 'Gagal membatalkan pilihan bank. Silahkan coba lagi.'
                    );
                }
            } else if ($ids_bank == 3) {
                $data = array(
                    'nim' => $tbdPembayaran->nomor_peserta
                );
                $parrams = array(
                    'url' => $_ENV['SALAM_HOST'] . 'tagihan/cancel',
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
                            'va' => $tbdPembayaran->va,
                            'idd_kelulusan' => $idd_kelulusan,
                            'pembayaran' => 'BELUM'
                        ),
                        'or_where'            => null,
                        'like'                => null,
                        'or_like'            => null,
                        'data'  => array(
                            'pembayaran' => 'EXPIRED',
                        ),
                    );
                    $fb = $this->Tbd_pembayaran->update($rules);
                    if (!$fb['status']) {
                        $msg = array(
                            'status' => 200,
                            'message' => 'Berhasil membatalkan pilihan bank.'
                        );
                    } else {
                        $msg = array(
                            'status' => 400,
                            'message' => 'Gagal membatalkan pilihan bank. Silahkan coba lagi.'
                        );
                    }
                } else {
                    $rules = array(
                        'where' => array(
                            'va' => $tbdPembayaran->va,
                            'idd_kelulusan' => $idd_kelulusan,
                            'pembayaran' => 'BELUM'
                        ),
                        'data'  => array(
                            'pembayaran' => 'EXPIRED',
                        ),
                    );
                    $fb = $this->Tbd_pembayaran->update($rules);
                    if (!$fb['status']) {
                        $msg = array(
                            'status' => 200,
                            'message' => 'Berhasil membatalkan pilihan bank.'
                        );
                    } else {
                        $msg = array(
                            'status' => 400,
                            'message' => 'Gagal membatalkan pilihan bank. Silahkan coba lagi.'
                        );
                    }
                }
            }
        } else {
            $response = array(
                'status' => 400,
                'message' => 'Hak akses ditolak.'
            );
        }
        echo json_encode($msg);
    }

    function JsonFormFilter()
    {
        $list = $this->SS_pembayaran->get_datatables();
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
                        <li><a class=\"dropdown-item\" href=\"javascript:void(0)\" onclick=\"edit_bank(" . $row->idd_pembayaran . ")\">Edit</a></li>
                        <li><a class=\"dropdown-item\" href=\"javascript:void(0)\" onclick=\"batalkan_bank(" . $row->ids_bank . "," . $row->idd_kelulusan . ")\">Batalkan</a></li>
                    </ul>
                </div>
            ";
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
            "recordsTotal" => $this->SS_pembayaran->count_all(),
            "recordsFiltered" => $this->SS_pembayaran->count_filtered(),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }

    function update()
    {
        $response = null;
        $hak_akses = $this->master->read('hak-akses/?ids_level=' . $this->jwt->ids_level . '&ids_modul=52&aksi_hak_akses=update');
        if ($hak_akses->code == 200) {
            $rules = array(
                'where'     => array('idd_pembayaran' => $this->input->post('idd_pembayaran')),
                'or_where'  => null,
                'like'      => null,
                'or_like'   => null,
                'data'                => array(
                    'pembayaran'  => $this->input->post('pembayaran'),
                    'updated_by' => $this->jwt->ids_user
                ),
            );
            $fb = $this->Tbd_pembayaran->update($rules);
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
                'message' => 'Hak akses ditolak.'
            );
        }
        echo json_encode($response);
    }

    function GetPembayaran($id = null)
    {
        $response = null;
        $hak_akses = $this->master->read('hak-akses/?ids_level=' . $this->jwt->ids_level . '&ids_modul=52&aksi_hak_akses=single');
        if ($hak_akses->code == 200) {
            if ($id == null) {
                $rules = array(
                    'database'  => null,
                    'select'    => null,
                    'order'     => null,
                    'limit'     => null,
                    'group_by'    => null,
                );
                $tbdPembayaran = $this->Viewd_pembayaran->read($rules);
                if ($tbdPembayaran->num_rows() > 0) {
                    $data = array(
                        'status' => 200,
                        'data' => $tbdPembayaran->result()
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
                        'idd_pembayaran' => $id,
                    ),
                    'or_where'  => null,
                    'like'      => null,
                    'or_like'   => null,
                    'order'     => null,
                    'limit'     => null,
                    'group_by'  => null,
                );
                $tbdPembayaran = $this->Viewd_pembayaran->search($rules);
                if ($tbdPembayaran->num_rows() > 0) {
                    $data = array(
                        'status' => 200,
                        'data' => $tbdPembayaran->row()
                    );
                } else {
                    $data = array(
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
        echo json_encode($data);
    }
}
