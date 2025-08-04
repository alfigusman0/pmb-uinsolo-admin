<?php
defined('BASEPATH') or exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;

class Pembayaran extends RestController
{
    /*
    HTTP_OK = 200;
    HTTP_CREATED = 201;
    HTTP_NOT_MODIFIED = 304;
    HTTP_BAD_REQUEST = 400;
    HTTP_UNAUTHORIZED = 401;
    HTTP_FORBIDDEN = 403;
    HTTP_NOT_FOUND = 404;
    HTTP_METHOD_NOT_ALLOWED = 405;
    HTTP_NOT_ACCEPTABLE = 406;
    HTTP_INTERNAL_ERROR = 500;
    */

    function __construct()
    {
        // Construct the parent class
        parent::__construct();
        $this->load->model('Ciresponses');
        $this->load->model('Daftar/Tbd_kelulusan');
        $this->load->model('Daftar/Tbd_pembayaran');
        $this->load->model('Daftar/Viewd_pembayaran');
    }

    function Payment_post()
    {
        $CekToken = $this->master->CekToken($this->post('token'));
        if ($CekToken->code == 200) {
            $rules = array(
                'database'  => null, //Default database master
                'select'    => null,
                'where'     => array(
                    'nomor_peserta' => $this->post('nomor_peserta'),
                    'id_billing' => $this->post('id_billing'),
                    'ids_bank' => $this->post('ids_bank'),
                ),
                'or_where'  => null,
                'like'      => null,
                'or_like'   => null,
                'order'     => 'date_created DESC',
                'limit'     => 1,
                'group_by'  => null,
            );
            $viewdPembayaran = $this->Viewd_pembayaran->search($rules);
            if ($viewdPembayaran->num_rows() > 0) {
                $viewdPembayaran = $viewdPembayaran->row();
                if ($viewdPembayaran->pembayaran == 'SUDAH') {
                    $response = array(
                        'code' => 200,
                        'status' => 'success',
                        'message' => 'Payment already paid.',
                    );
                    $rules = array(
                        'tipe' => 'UKT - Pembayaran',
                        'headers' => json_encode($this->head()),
                        'request' => json_encode($this->post()),
                        'response' => json_encode($response)
                    );
                    $this->Ciresponses->create($rules);
                    $this->response($response, 200);
                } else {
                    $rules = array(
                        'where' => array(
                            'idd_pembayaran' => $viewdPembayaran->idd_pembayaran,
                        ),
                        'or_where' => null,
                        'like' => null,
                        'or_like' => null,
                        'data' => array('pembayaran' => 'SUDAH'), // not null
                    );
                    $fb1 = $this->Tbd_pembayaran->update($rules);
                    $rules = array(
                        'where' => array(
                            'idd_kelulusan' => $viewdPembayaran->idd_kelulusan,
                        ),
                        'or_where' => null,
                        'like' => null,
                        'or_like' => null,
                        'data' => array(
                            'pembayaran' => 'SUDAH',
                            'tgl_pembayaran' => date('Y-m-d H:i:s')
                        ), // not null
                    );
                    $fb2 = $this->Tbd_kelulusan->update($rules);
                    if (!$fb1['status']) {
                        if (!$fb2['status']) {
                            $response = array(
                                'code' => 200,
                                'status' => 'success',
                                'message' => 'Payment successful.',
                            );
                            $rules = array(
                                'tipe' => 'UKT - Pembayaran',
                                'headers' => json_encode($this->head()),
                                'request' => json_encode($this->post()),
                                'response' => json_encode($response)
                            );
                            $this->Ciresponses->create($rules);
                            $this->response($response, 200);
                        } else {
                            $response = array(
                                'code' => 500,
                                'status' => 'error',
                                'message' => $fb2['message'],
                            );
                            $rules = array(
                                'tipe' => 'UKT - Pembayaran',
                                'headers' => json_encode($this->head()),
                                'request' => json_encode($this->post()),
                                'response' => json_encode($response)
                            );
                            $this->Ciresponses->create($rules);
                            $this->response($response, 500);
                        }
                    } else {
                        $response = array(
                            'code' => 500,
                            'status' => 'error',
                            'message' => $fb1['message'],
                        );
                        $rules = array(
                            'tipe' => 'UKT - Pembayaran',
                            'headers' => json_encode($this->head()),
                            'request' => json_encode($this->post()),
                            'response' => json_encode($response)
                        );
                        $this->Ciresponses->create($rules);
                        $this->response($response, 500);
                    }
                }
            } else {
                $response = array(
                    'code' => 404,
                    'status' => 'error',
                    'message' => 'Data not found.',
                );
                $rules = array(
                    'tipe' => 'UKT - Pembayaran',
                    'headers' => json_encode($this->head()),
                    'request' => json_encode($this->post()),
                    'response' => json_encode($response)
                );
                $this->response($response, 404);
            }
        } else {
            $response = array(
                'code' => $CekToken->code,
                'status' => $CekToken->status,
                'message' => $CekToken->message,
            );
            $rules = array(
                'tipe' => 'UKT - Pembayaran',
                'headers' => json_encode($this->head()),
                'request' => json_encode($this->post()),
                'response' => json_encode($response)
            );
            $this->Ciresponses->create($rules);
            $this->response($response, $CekToken->code);
        }
    }

    function Reversal_put()
    {
        $CekToken = $this->master->CekToken($this->put('token'));
        if ($CekToken->code == 200) {
            $rules = array(
                'database'  => null, //Default database master
                'select'    => null,
                'where'     => array(
                    'nomor_peserta' => $this->put('nomor_peserta'),
                    'id_billing' => $this->put('id_billing'),
                    'pembayaran' => 'SUDAH'
                ),
                'or_where'  => null,
                'like'      => null,
                'or_like'   => null,
                'order'     => null,
                'limit'     => null,
                'group_by'  => null,
            );
            $viewdPembayaran = $this->Viewd_pembayaran->search($rules);
            if ($viewdPembayaran->num_rows() > 0) {
                $viewdPembayaran = $viewdPembayaran->row();
                $rules = array(
                    'where'     => array(
                        'idd_kelulusan' => $viewdPembayaran->idd_kelulusan,
                        'pembayaran' => 'SUDAH'
                    ),
                    'or_where'  => null,
                    'like'      => null,
                    'or_like'   => null,
                    'data'      => array('pembayaran' => 'BELUM'), // not null
                );
                $fb1 = $this->Tbd_pembayaran->update($rules);
                $fb2 = $this->Tbd_kelulusan->update($rules);
                if (!$fb1['status']) {
                    if (!$fb2['status']) {
                        $this->response(array(
                            'code' => 200,
                            'status' => 'success',
                            'message' => 'Reversal successful.',
                        ), 200);
                    } else {
                        $this->response(array(
                            'code' => 500,
                            'status' => 'error',
                            'message' => $fb2['message'],
                        ), 500);
                    }
                } else {
                    $this->response(array(
                        'code' => 500,
                        'status' => 'error',
                        'message' => $fb1['message'],
                    ), 500);
                }
            } else {
                $this->response(array(
                    'code' => 404,
                    'status' => 'error',
                    'message' => 'Data not found.',
                ), 404);
            }
        } else {
            $this->response(array(
                'code' => $CekToken->code,
                'status' => $CekToken->status,
                'message' => $CekToken->message,
            ), $CekToken->code);
        }
    }
}
