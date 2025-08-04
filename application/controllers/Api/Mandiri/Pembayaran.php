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
        $this->load->model('Mandiri/Tbp_formulir');
        $this->load->model('Mandiri/Tbp_jadwal');
        $this->load->model('Mandiri/Tbp_pembayaran');
        $this->load->model('Mandiri/Viewp_pembayaran');
        $this->load->model('Settings/Tbs_jadwal');
        $this->load->model('Settings/Tbs_tipe_ujian');
        $this->load->model('Settings/Views_tipe_ujian');
    }

    function Payment_post()
    {
        $post = $this->post();
        $headers = json_encode($this->head());
        $token = $post['token'] ?? null;
        $kode_bayar = $post['kode_bayar'] ?? '';
        $id_billing = $post['id_billing'] ?? null;
        $ids_bank = $post['ids_bank'] ?? null;

        $cekToken = $this->master->CekToken($token);
        if ($cekToken->code != 200) {
            $this->_sendResponse($cekToken->code, $cekToken->status, $cekToken->message, $headers, $post);
            return;
        }

        $idp_formulir = substr($kode_bayar, 4, 6);
        $rules = [
            'where' => [
                'idp_formulir' => $idp_formulir,
                'id_billing' => $id_billing,
                'ids_bank' => $ids_bank,
            ],
            'order' => 'date_created DESC',
            'limit' => 1,
        ];
        $viewpPembayaran = $this->Viewp_pembayaran->search($rules);

        if ($viewpPembayaran->num_rows() != 1) {
            $this->_sendResponse(404, 'error', 'Data not found.', $headers, $post);
            return;
        }

        $view = $viewpPembayaran->row();
        if ($view->pembayaran == 'SUDAH') {
            $this->_sendResponse(200, 'success', 'Payment already paid.', $headers, $post);
            return;
        }

        // Proses penomoran peserta
        $formulir = $this->Tbp_formulir->search(['where' => ['idp_formulir' => $idp_formulir]])->row();
        $success = $error1 = $error2 = 0;

        if (empty($formulir->nomor_peserta)) {
            $nomor_peserta = $this->NomorPeserta($formulir->ids_tipe_ujian);
            $update = $this->Tbp_formulir->update([
                'where' => ['idp_formulir' => $formulir->idp_formulir],
                'data'  => ['nomor_peserta' => $nomor_peserta]
            ]);
            $success += $update['status'] ? 0 : 1;
            $error1 = $update['status'] ? $nomor_peserta : 0;
        } else {
            $success++;
        }

        // Proses Jadwal
        $tipeUjian = $this->Tbs_tipe_ujian->search(['where' => ['ids_tipe_ujian' => $formulir->ids_tipe_ujian]])->row();
        if ($tipeUjian->status_jadwal == 'TIDAK') {
            $update = $this->Tbs_tipe_ujian->update([
                'where' => ['ids_tipe_ujian' => $tipeUjian->ids_tipe_ujian],
                'data' => ['quota' => $tipeUjian->quota - 1]
            ]);
            $success += $update['status'] ? 0 : 1;
            $error2 += $update['status'] ? 1 : 0;
        } else {
            $jadwalAda = $this->Tbp_jadwal->search(['where' => ['idp_formulir' => $idp_formulir]]);
            if ($jadwalAda->num_rows() == 0) {
                $jadwal = $this->Tbs_jadwal->search([
                    'where' => [
                        'ids_tipe_ujian' => $formulir->ids_tipe_ujian,
                        'quota >' => 0,
                        'status' => 'YA'
                    ],
                    'order' => 'tanggal ASC, jam_awal ASC, ids_jadwal ASC, quota ASC',
                    'limit' => 1,
                ])->row();

                $create = $this->Tbp_jadwal->create([
                    'idp_formulir' => $idp_formulir,
                    'ids_jadwal' => $jadwal->ids_jadwal,
                    'created_by' => 1,
                    'updated_by' => 1,
                ]);
                if (!$create['status']) {
                    $success++;
                    $this->Tbs_jadwal->update([
                        'where' => ['ids_jadwal' => $jadwal->ids_jadwal],
                        'data'  => ['quota' => $jadwal->quota - 1]
                    ]);
                } else {
                    $error2++;
                }
            } else {
                $success++;
            }
        }

        // Proses akhir update pembayaran jika sukses
        if ($success == 2) {
            $fb1 = $this->Tbp_pembayaran->update([
                'where' => ['idp_pembayaran' => $view->idp_pembayaran],
                'data' => ['pembayaran' => 'SUDAH']
            ]);
            $fb2 = $this->Tbp_formulir->update([
                'where' => ['idp_formulir' => $idp_formulir],
                'data' => ['pembayaran' => 'SUDAH']
            ]);
            if (!$fb1['status'] && !$fb2['status']) {
                $this->_sendResponse(200, 'success', 'Payment successful.', $headers, $post, ['idp_formulir' => $idp_formulir]);
            } else {
                $msg = $fb1['status'] ? $fb1['message'] : $fb2['message'];
                $this->_sendResponse(500, 'error', $msg, $headers, $post);
            }
        } else {
            $this->_sendResponse(500, 'error', 'Terjadi kesalahan dalam internal server, silahkan coba lagi.', $headers, $post, [
                'nomor_peserta' => ($error1 == 0) ? 'success' : $error1,
                'jadwal' => ($error2 == 0) ? 'success' : 'error'
            ]);
        }
    }

    function NomorPeserta($ids_tipe_ujian)
    {
        $tahun = substr(date('Y'), 2, 2);
        $views = $this->Views_tipe_ujian->search(['where' => ['ids_tipe_ujian' => $ids_tipe_ujian]])->row();
        $nomor_peserta = $tahun . $views->kode_program . $views->kode . "00001";

        $cek = $this->Tbp_formulir->search([
            'where' => ['nomor_peserta' => $nomor_peserta, 'ids_tipe_ujian' => $ids_tipe_ujian]
        ])->num_rows();

        if ($cek > 0) {
            $last = $this->Tbp_formulir->search([
                'where' => ['ids_tipe_ujian' => $ids_tipe_ujian],
                'order' => 'nomor_peserta DESC',
                'limit' => 1
            ])->row();
            $nomor_peserta = $last->nomor_peserta + 1;
        }

        return $nomor_peserta;
    }

    function _sendResponse($code, $status, $message, $headers, $request, $data = [])
    {
        $response = [
            'code' => $code,
            'status' => $status,
            'message' => $message,
        ];

        if (!empty($data)) {
            $response['data'] = $data;
        }

        $this->Ciresponses->create([
            'tipe' => 'Mandiri - Pembayaran',
            'headers' => $headers,
            'request' => json_encode($request),
            'response' => json_encode($response),
        ]);

        $this->response($response, $code);
    }
}
