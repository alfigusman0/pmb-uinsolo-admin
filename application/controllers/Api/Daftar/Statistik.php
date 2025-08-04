<?php
defined('BASEPATH') or exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;

class Statistik extends RestController
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
        $this->load->model('Daftar/Viewd_kelulusan');
        $this->load->model('Daftar/Viewd_rumah');
        $this->load->model('Daftar/Viewd_sekolah');
        $this->load->model('Settings/Views_daya_tampung');
        $this->load->model('Settings/Views_sub_daya_tampung');
    }

    function index_get()
    {
        $this->response(array(
            'status' => false,
            'message' => 'Index get is not found.'
        ), 404);
    }

    function index_post()
    {
        $this->response(array(
            'status' => false,
            'message' => 'Index post is not found.'
        ), 404);
    }

    // Jalur Masuk - Kelulusan
    function D1_get()
    {
        $data = $where = array();
        $tahun = ($this->get('tahun') != null) ? $this->get('tahun') : date('Y');
        $daftar = ($this->get('daftar') != null) ? $this->get('daftar') : '';
        $submit = ($this->get('submit') != null) ? $this->get('submit') : '';
        $pembayaran = ($this->get('pembayaran') != null) ? $this->get('pembayaran') : '';
        $pemberkasan = ($this->get('pemberkasan') != null) ? $this->get('pemberkasan') : '';

        if ($tahun != '') {
            $where['YEAR(date_created)'] = $tahun;
        }
        if ($daftar != '') {
            $where['daftar'] = $daftar;
        }
        if ($submit != '') {
            $where['submit'] = $submit;
        }
        if ($pembayaran != '') {
            $where['pembayaran'] = $pembayaran;
        }
        if ($pemberkasan != '') {
            $where['pemberkasan'] = $pemberkasan;
        }

        $feedback = $this->master->read("jalur-masuk/?status=YA&page=1&limit=1000");
        if ($feedback->code == 200) {
            foreach ($feedback->data->data as $a) {
                $where['ids_jalur_masuk'] = $a->ids_jalur_masuk;
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
                $data[] =  array(
                    'alias' => $a->alias,
                    'num' => $this->Viewd_kelulusan->search($rules)->num_rows(),
                );
            }
            $this->response(array(
                'code' => 200,
                'status' => "success",
                'message' => null,
                'data' => $data
            ), 200);
        } else {
            $this->response($feedback, $feedback->code);
        }
    }

    // Fakultas - Kelulusan
    function D2_get()
    {
        $data = $where = array();
        $tahun = ($this->get('tahun') != null) ? $this->get('tahun') : date('Y');
        $ids_jalur_masuk = ($this->get('ids_jalur_masuk') != null) ? $this->get('ids_jalur_masuk') : '';
        $ids_fakultas = ($this->get('ids_fakultas') != null) ? '&ids_fakultas=' . $this->get('ids_fakultas') : '';
        $daftar = ($this->get('daftar') != null) ? $this->get('daftar') : '';
        $submit = ($this->get('submit') != null) ? $this->get('submit') : '';
        $pembayaran = ($this->get('pembayaran') != null) ? $this->get('pembayaran') : '';
        $pemberkasan = ($this->get('pemberkasan') != null) ? $this->get('pemberkasan') : '';

        if ($tahun != '') {
            $where['YEAR(date_created)'] = $tahun;
        }
        if ($ids_jalur_masuk != '') {
            $where['ids_jalur_masuk'] = $ids_jalur_masuk;
        }
        if ($daftar != '') {
            $where['daftar'] = $daftar;
        }
        if ($submit != '') {
            $where['submit'] = $submit;
        }
        if ($pembayaran != '') {
            $where['pembayaran'] = $pembayaran;
        }
        if ($pemberkasan != '') {
            $where['pemberkasan'] = $pemberkasan;
        }

        $feedback = $this->master->read("fakultas/?status=YA&page=1&limit=1000" . $ids_fakultas);
        if ($feedback->code == 200) {
            $total = 0;
            foreach ($feedback->data->data as $a) {
                $where['ids_fakultas'] = $a->ids_fakultas;
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
                $num = $this->Viewd_kelulusan->search($rules)->num_rows();
                $total += $num;
                $data[] =  array(
                    'ids_fakultas' => $a->ids_fakultas,
                    'fakultas' => $a->fakultas,
                    'num' => $num,
                );
            }
            $this->response(array(
                'code' => 200,
                'status' => "success",
                'message' => null,
                'total' => $total,
                'data' => $data
            ), 200);
        } else {
            $this->response($feedback, $feedback->code);
        }
    }

    // Jurusan - Kelulusan
    function D3_get()
    {
        $data = $where = array();
        $tahun = ($this->get('tahun') != null) ? $this->get('tahun') : date('Y');
        $ids_jalur_masuk = ($this->get('ids_jalur_masuk') != null) ? $this->get('ids_jalur_masuk') : '';
        $kode_jurusan = ($this->get('kode_jurusan') != null) ? '&kode_jurusan=' . $this->get('kode_jurusan') : '';
        $daftar = ($this->get('daftar') != null) ? $this->get('daftar') : '';
        $submit = ($this->get('submit') != null) ? $this->get('submit') : '';
        $pembayaran = ($this->get('pembayaran') != null) ? $this->get('pembayaran') : '';
        $pemberkasan = ($this->get('pemberkasan') != null) ? $this->get('pemberkasan') : '';

        if ($tahun != '') {
            $where['YEAR(date_created)'] = $tahun;
        }
        if ($ids_jalur_masuk != '') {
            $where['ids_jalur_masuk'] = $ids_jalur_masuk;
        }
        if ($daftar != '') {
            $where['daftar'] = $daftar;
        }
        if ($submit != '') {
            $where['submit'] = $submit;
        }
        if ($pembayaran != '') {
            $where['pembayaran'] = $pembayaran;
        }
        if ($pemberkasan != '') {
            $where['pemberkasan'] = $pemberkasan;
        }

        $feedback = $this->master->read("jurusan/?status=YA&page=1&limit=100" . $kode_jurusan);
        if ($feedback->code == 200) {
            $total = 0;
            foreach ($feedback->data->data as $a) {
                $where['kode_jurusan'] = $a->kode_jurusan;
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
                $num = $this->Viewd_kelulusan->search($rules)->num_rows();
                $total += $num;
                $data[] =  array(
                    'kode_jurusan' => $a->kode_jurusan,
                    'jurusan' => $a->jurusan,
                    'fakultas' => $a->fakultas,
                    'num' => $num
                );
            }
            $this->response(array(
                'code' => 200,
                'status' => "success",
                'message' => null,
                'total' => $total,
                'data' => $data
            ), 200);
        } else {
            $this->response($feedback, $feedback->code);
        }
    }

    // Negara - Mahasiswa
    function D4_get()
    {
        $data = $where = array();
        $tahun = ($this->get('tahun') != null) ? $this->get('tahun') : date('Y');
        $daftar = ($this->get('daftar') != null) ? $this->get('daftar') : '';
        $submit = ($this->get('submit') != null) ? $this->get('submit') : '';
        $pembayaran = ($this->get('pembayaran') != null) ? $this->get('pembayaran') : '';
        $pemberkasan = ($this->get('pemberkasan') != null) ? $this->get('pemberkasan') : '';
        $page = ($this->get('page') != null) ? $this->get('page') : '1';
        $limit = ($this->get('limit') != null) ? $this->get('limit') : $_ENV['LIMIT_DATA'];

        if ($tahun != '') {
            $where['YEAR(date_created)'] = $tahun;
        }
        if ($daftar != '') {
            $where['daftar'] = $daftar;
        }
        if ($submit != '') {
            $where['submit'] = $submit;
        }
        if ($pembayaran != '') {
            $where['pembayaran'] = $pembayaran;
        }
        if ($pemberkasan != '') {
            $where['pemberkasan'] = $pemberkasan;
        }

        $feedback = $this->master->read("negara/?status=YA&page=$page&limit=$limit");
        if ($feedback->code == 200) {
            foreach ($feedback->data->data as $a) {
                $where['ids_negara'] = $a->ids_negara;
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
                $data[] =  array(
                    'negara' => $a->negara,
                    'num' => $this->Viewd_rumah->search($rules)->num_rows(),
                );
            }
            $this->response(array(
                'code' => 200,
                'status' => "success",
                'message' => null,
                'data' => $data,
                'pagination' => array(
                    'totaldata' => $feedback->data->pagination->totaldata,
                    'totalpagination' => $feedback->data->pagination->totalpagination,
                    'currentpage' => $feedback->data->pagination->currentpage,
                )
            ), 200);
        } else {
            $this->response($feedback, $feedback->code);
        }
    }

    // Provinsi - Mahasiswa
    function D5_get()
    {
        $data = $where = array();
        $ids_negara = ($this->get('ids_negara') != null) ? $this->get('ids_negara') : '103';
        $tahun = ($this->get('tahun') != null) ? $this->get('tahun') : date('Y');
        $daftar = ($this->get('daftar') != null) ? $this->get('daftar') : '';
        $submit = ($this->get('submit') != null) ? $this->get('submit') : '';
        $pembayaran = ($this->get('pembayaran') != null) ? $this->get('pembayaran') : '';
        $pemberkasan = ($this->get('pemberkasan') != null) ? $this->get('pemberkasan') : '';
        $page = ($this->get('page') != null) ? $this->get('page') : '1';
        $limit = ($this->get('limit') != null) ? $this->get('limit') : $_ENV['LIMIT_DATA'];

        if ($tahun != '') {
            $where['YEAR(date_created)'] = $tahun;
        }
        if ($daftar != '') {
            $where['daftar'] = $daftar;
        }
        if ($submit != '') {
            $where['submit'] = $submit;
        }
        if ($pembayaran != '') {
            $where['pembayaran'] = $pembayaran;
        }
        if ($pemberkasan != '') {
            $where['pemberkasan'] = $pemberkasan;
        }
        if ($ids_negara != '') {
            $where['ids_negara'] = $ids_negara;
        }

        $feedback = $this->master->read("provinsi/?ids_negara=$ids_negara&status=YA&page=$page&limit=$limit");
        if ($feedback->code == 200) {
            foreach ($feedback->data->data as $a) {
                $where['ids_provinsi'] = $a->ids_provinsi;
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
                $data[] =  array(
                    'provinsi' => $a->provinsi,
                    'num' => $this->Viewd_rumah->search($rules)->num_rows(),
                );
            }
            $this->response(array(
                'code' => 200,
                'status' => "success",
                'message' => null,
                'data' => $data,
                'pagination' => array(
                    'totaldata' => $feedback->data->pagination->totaldata,
                    'totalpagination' => $feedback->data->pagination->totalpagination,
                    'currentpage' => $feedback->data->pagination->currentpage,
                )
            ), 200);
        } else {
            $this->response($feedback, $feedback->code);
        }
    }

    // Kabupaten atau Kota - Mahasiswa
    function D6_get()
    {
        $data = $where = array();
        $ids_negara = ($this->get('ids_negara') != null) ? $this->get('ids_negara') : '103';
        $ids_provinsi = ($this->get('ids_provinsi') != null) ? $this->get('ids_provinsi') : '12';
        $tahun = ($this->get('tahun') != null) ? $this->get('tahun') : date('Y');
        $daftar = ($this->get('daftar') != null) ? $this->get('daftar') : '';
        $submit = ($this->get('submit') != null) ? $this->get('submit') : '';
        $pembayaran = ($this->get('pembayaran') != null) ? $this->get('pembayaran') : '';
        $pemberkasan = ($this->get('pemberkasan') != null) ? $this->get('pemberkasan') : '';
        $page = ($this->get('page') != null) ? $this->get('page') : '1';
        $limit = ($this->get('limit') != null) ? $this->get('limit') : $_ENV['LIMIT_DATA'];

        if ($tahun != '') {
            $where['YEAR(date_created)'] = $tahun;
        }
        if ($daftar != '') {
            $where['daftar'] = $daftar;
        }
        if ($submit != '') {
            $where['submit'] = $submit;
        }
        if ($pembayaran != '') {
            $where['pembayaran'] = $pembayaran;
        }
        if ($pemberkasan != '') {
            $where['pemberkasan'] = $pemberkasan;
        }
        if ($ids_negara != '') {
            $where['ids_negara'] = $ids_negara;
        }
        if ($ids_provinsi != '') {
            $where['ids_provinsi'] = $ids_provinsi;
        }

        $feedback = $this->master->read("kabupaten-kota/?ids_negara=$ids_negara&ids_provinsi=$ids_provinsi&status=YA&page=$page&limit=$limit");
        if ($feedback->code == 200) {
            foreach ($feedback->data->data as $a) {
                $where['ids_kab_kota'] = $a->ids_kab_kota;
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
                $data[] =  array(
                    'kab_kota' => $a->kab_kota,
                    'num' => $this->Viewd_rumah->search($rules)->num_rows(),
                );
            }
            $this->response(array(
                'code' => 200,
                'status' => "success",
                'message' => null,
                'data' => $data,
                'pagination' => array(
                    'totaldata' => $feedback->data->pagination->totaldata,
                    'totalpagination' => $feedback->data->pagination->totalpagination,
                    'currentpage' => $feedback->data->pagination->currentpage,
                )
            ), 200);
        } else {
            $this->response($feedback, $feedback->code);
        }
    }

    // Kecamatan - Mahasiswa
    function D7_get()
    {
        $data = $where = array();
        $ids_negara = ($this->get('ids_negara') != null) ? $this->get('ids_negara') : '103';
        $ids_provinsi = ($this->get('ids_provinsi') != null) ? $this->get('ids_provinsi') : '12';
        $ids_kab_kota = ($this->get('ids_kab_kota') != null) ? $this->get('ids_kab_kota') : '181';
        $tahun = ($this->get('tahun') != null) ? $this->get('tahun') : date('Y');
        $daftar = ($this->get('daftar') != null) ? $this->get('daftar') : '';
        $submit = ($this->get('submit') != null) ? $this->get('submit') : '';
        $pembayaran = ($this->get('pembayaran') != null) ? $this->get('pembayaran') : '';
        $pemberkasan = ($this->get('pemberkasan') != null) ? $this->get('pemberkasan') : '';
        $page = ($this->get('page') != null) ? $this->get('page') : '1';
        $limit = ($this->get('limit') != null) ? $this->get('limit') : $_ENV['LIMIT_DATA'];

        if ($tahun != '') {
            $where['YEAR(date_created)'] = $tahun;
        }
        if ($daftar != '') {
            $where['daftar'] = $daftar;
        }
        if ($submit != '') {
            $where['submit'] = $submit;
        }
        if ($pembayaran != '') {
            $where['pembayaran'] = $pembayaran;
        }
        if ($pemberkasan != '') {
            $where['pemberkasan'] = $pemberkasan;
        }
        if ($ids_negara != '') {
            $where['ids_negara'] = $ids_negara;
        }
        if ($ids_provinsi != '') {
            $where['ids_provinsi'] = $ids_provinsi;
        }
        if ($ids_kab_kota != '') {
            $where['ids_kab_kota'] = $ids_kab_kota;
        }

        $feedback = $this->master->read("kecamatan/?ids_negara=$ids_negara&ids_provinsi=$ids_provinsi&ids_kab_kota=$ids_kab_kota&status=YA&page=$page&limit=$limit");
        if ($feedback->code == 200) {
            foreach ($feedback->data->data as $a) {
                $where['ids_kecamatan'] = $a->ids_kecamatan;
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
                $data[] =  array(
                    'kecamatan' => $a->kecamatan,
                    'num' => $this->Viewd_rumah->search($rules)->num_rows(),
                );
            }
            $this->response(array(
                'code' => 200,
                'status' => "success",
                'message' => null,
                'data' => $data,
                'pagination' => array(
                    'totaldata' => $feedback->data->pagination->totaldata,
                    'totalpagination' => $feedback->data->pagination->totalpagination,
                    'currentpage' => $feedback->data->pagination->currentpage,
                )
            ), 200);
        } else {
            $this->response($feedback, $feedback->code);
        }
    }

    // Kelurahan - Mahasiswa
    function D8_get()
    {
        $data = $where = array();
        $ids_negara = ($this->get('ids_negara') != null) ? $this->get('ids_negara') : '103';
        $ids_provinsi = ($this->get('ids_provinsi') != null) ? $this->get('ids_provinsi') : '12';
        $ids_kab_kota = ($this->get('ids_kab_kota') != null) ? $this->get('ids_kab_kota') : '181';
        $ids_kecamatan = ($this->get('ids_kecamatan') != null) ? $this->get('ids_kecamatan') : '2543';
        $tahun = ($this->get('tahun') != null) ? $this->get('tahun') : date('Y');
        $daftar = ($this->get('daftar') != null) ? $this->get('daftar') : '';
        $submit = ($this->get('submit') != null) ? $this->get('submit') : '';
        $pembayaran = ($this->get('pembayaran') != null) ? $this->get('pembayaran') : '';
        $pemberkasan = ($this->get('pemberkasan') != null) ? $this->get('pemberkasan') : '';
        $page = ($this->get('page') != null) ? $this->get('page') : '1';
        $limit = ($this->get('limit') != null) ? $this->get('limit') : $_ENV['LIMIT_DATA'];

        if ($tahun != '') {
            $where['YEAR(date_created)'] = $tahun;
        }
        if ($daftar != '') {
            $where['daftar'] = $daftar;
        }
        if ($submit != '') {
            $where['submit'] = $submit;
        }
        if ($pembayaran != '') {
            $where['pembayaran'] = $pembayaran;
        }
        if ($pemberkasan != '') {
            $where['pemberkasan'] = $pemberkasan;
        }
        if ($ids_negara != '') {
            $where['ids_negara'] = $ids_negara;
        }
        if ($ids_provinsi != '') {
            $where['ids_provinsi'] = $ids_provinsi;
        }
        if ($ids_kab_kota != '') {
            $where['ids_kab_kota'] = $ids_kab_kota;
        }
        if ($ids_kecamatan != '') {
            $where['ids_kecamatan'] = $ids_kecamatan;
        }

        $feedback = $this->master->read("kelurahan/?ids_negara=$ids_negara&ids_provinsi=$ids_provinsi&ids_kab_kota=$ids_kab_kota&ids_kecamatan=$ids_kecamatan&status=YA&page=$page&limit=$limit");
        if ($feedback->code == 200) {
            foreach ($feedback->data->data as $a) {
                $where['ids_kelurahan'] = $a->ids_kelurahan;
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
                $data[] =  array(
                    'kelurahan' => $a->kelurahan,
                    'num' => $this->Viewd_rumah->search($rules)->num_rows(),
                );
            }
            $this->response(array(
                'code' => 200,
                'status' => "success",
                'message' => null,
                'data' => $data,
                'pagination' => array(
                    'totaldata' => $feedback->data->pagination->totaldata,
                    'totalpagination' => $feedback->data->pagination->totalpagination,
                    'currentpage' => $feedback->data->pagination->currentpage,
                )
            ), 200);
        } else {
            $this->response($feedback, $feedback->code);
        }
    }

    // Daya Tampung Penyerapan
    function D9_get()
    {
        $data = $where = array();
        $tahun = ($this->get('tahun') != null) ? $this->get('tahun') : date('Y');

        if ($tahun != '') {
            $where['YEAR(date_created)'] = $tahun;
        }
        $where['status'] = 'YA';
        // $feedback = $this->master->read("jurusan/?status=YA&limit=1000");
        $rules = array(
            'database'  => null, //Database master
            'select'    => null,
            'where'     => $where,
            'or_where'  => null,
            'like'      => null,
            'or_like'   => null,
            'order'     => 'ids_fakultas ASC, kode_jurusan ASC',
            'limit'     => null,
            'group_by'  => null,
        );
        $feedback = $this->Views_daya_tampung->search($rules)->result();
        $totalDayaTampung = 0;
        foreach ($feedback as $a) {
            $rules = array(
                'database'  => null, //Database master
                'select'    => null,
                'where'     => array(
                    'kode_jurusan' => $a->kode_jurusan,
                ),
                'or_where'  => null,
                'like'      => null,
                'or_like'   => null,
                'order'     => null,
                'limit'     => null,
                'group_by'  => null,
            );
            $dayaTampung = $this->Views_daya_tampung->search($rules)->row();
            $totalDayaTampung += $dayaTampung->daya_tampung;
            //SNBP
            $rules = array(
                'database'  => null, //Default database master
                'select'    => null,
                'where'     => array(
                    'kode_jurusan' => $a->kode_jurusan,
                    'ids_jalur_masuk' => 7,
                    'pembayaran' => 'SUDAH',
                    'YEAR(date_created)' => $tahun
                ),
                'or_where'  => null,
                'like'      => null,
                'or_like'   => null,
                'order'     => null,
                'limit'     => null,
                'group_by'  => null,
            );
            $terisiSNBP = $this->Viewd_kelulusan->search($rules)->num_rows();
            //SNBT
            $rules = array(
                'database'  => null, //Default database master
                'select'    => null,
                'where'     => array(
                    'kode_jurusan' => $a->kode_jurusan,
                    'ids_jalur_masuk' => 8,
                    'pembayaran' => 'SUDAH',
                    'YEAR(date_created)' => $tahun
                ),
                'or_where'  => null,
                'like'      => null,
                'or_like'   => null,
                'order'     => null,
                'limit'     => null,
                'group_by'  => null,
            );
            $terisiSNBT = $this->Viewd_kelulusan->search($rules)->num_rows();
            //UMPTKIN
            $rules = array(
                'database'  => null, //Default database master
                'select'    => null,
                'where'     => array(
                    'kode_jurusan' => $a->kode_jurusan,
                    'ids_jalur_masuk' => 4,
                    'pembayaran' => 'SUDAH',
                    'YEAR(date_created)' => $tahun
                ),
                'or_where'  => null,
                'like'      => null,
                'or_like'   => null,
                'order'     => null,
                'limit'     => null,
                'group_by'  => null,
            );
            $terisiUMPTKIN = $this->Viewd_kelulusan->search($rules)->num_rows();
            //SPAN
            $rules = array(
                'database'  => null, //Default database master
                'select'    => null,
                'where'     => array(
                    'kode_jurusan' => $a->kode_jurusan,
                    'ids_jalur_masuk' => 2,
                    'pembayaran' => 'SUDAH',
                    'YEAR(date_created)' => $tahun
                ),
                'or_where'  => null,
                'like'      => null,
                'or_like'   => null,
                'order'     => null,
                'limit'     => null,
                'group_by'  => null,
            );
            $terisiSPAN = $this->Viewd_kelulusan->search($rules)->num_rows();
            //MANDIRI
            $rules = array(
                'database'  => null, //Default database master
                'select'    => null,
                'where'     => array(
                    'kode_jurusan' => $a->kode_jurusan,
                    'ids_jalur_masuk' => 5,
                    'pembayaran' => 'SUDAH',
                    'YEAR(date_created)' => $tahun
                ),
                'or_where'  => null,
                'like'      => null,
                'or_like'   => null,
                'order'     => null,
                'limit'     => null,
                'group_by'  => null,
            );
            $terisiMANDIRI = $this->Viewd_kelulusan->search($rules)->num_rows();
            $data[] =  array(
                'kode_jurusan' => $a->kode_jurusan,
                'jurusan' => $a->jurusan,
                'fakultas' => $a->fakultas,
                'daya_tampung' => $dayaTampung->daya_tampung,
                'terisi_snbp' => $terisiSNBP,
                'terisi_snbt' => $terisiSNBT,
                'terisi_umptkin' => $terisiUMPTKIN,
                'terisi_span' => $terisiSPAN,
                'terisi_mandiri' => $terisiMANDIRI,
                'total_terisi' => $terisiSNBP + $terisiSNBT + $terisiUMPTKIN + $terisiSPAN + $terisiMANDIRI
            );
        }
        $this->response(array(
            'code' => 200,
            'status' => "success",
            'message' => null,
            'data' => $data,
            'total' => $totalDayaTampung
        ), 200);
    }

    // Daya Tampung Berdasarkan Kuota Awal
    function D10_get()
    {
        $data = $persentase = $where = null;
        $tahun = ($this->get('tahun') != null) ? $this->get('tahun') : date('Y');
        $daftar = ($this->get('daftar') != null) ? $this->get('daftar') : '';
        $submit = ($this->get('submit') != null) ? $this->get('submit') : '';
        $pembayaran = ($this->get('pembayaran') != null) ? $this->get('pembayaran') : 'SUDAH';
        $pemberkasan = ($this->get('pemberkasan') != null) ? $this->get('pemberkasan') : '';

        if ($tahun != '') {
            $where['YEAR(date_created)'] = $tahun;
        }
        if ($daftar != '') {
            $where['daftar'] = $daftar;
        }
        if ($submit != '') {
            $where['submit'] = $submit;
        }
        if ($pembayaran != '') {
            $where['pembayaran'] = $pembayaran;
        }
        if ($pemberkasan != '') {
            $where['pemberkasan'] = $pemberkasan;
        }

        $jurusan = $this->master->read("jurusan/?jenjang=S1&status=YA&page=1&limit=100");
        if ($jurusan->code != 200) {
            $this->response($jurusan, $jurusan->code);
        }

        $jalur_masuk = $this->master->read("jalur-masuk/?ids_jalur_masuk=2,4,5,6,7,8,12&status=YA&page=1&limit=1000");
        if ($jalur_masuk->code != 200) {
            $this->response($jalur_masuk, $jalur_masuk->code);
        }

        foreach ($jurusan->data->data as $a) {
            $where['kode_jurusan'] = $a->kode_jurusan;
            $sub = array();
            $sub_total_daya_tampung = $sub_total_terisi = 0;
            foreach ($jalur_masuk->data->data as $b) {
                $where['ids_jalur_masuk'] = $b->ids_jalur_masuk;
                $rules = array(
                    'database'  => null, //Default database master
                    'select'    => null,
                    'where'     => array(
                        'kode_jurusan' => $a->kode_jurusan,
                        'ids_jalur_masuk' => $b->ids_jalur_masuk,
                        'YEAR(date_created)' => $tahun,
                        'status' => 'YA'
                    ),
                    'or_where'  => null,
                    'like'      => null,
                    'or_like'   => null,
                    'order'     => null,
                    'limit'     => null,
                    'group_by'  => null,
                );
                $SubDayaTampung = $this->Views_sub_daya_tampung->search($rules)->row();
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
                $num = $this->Viewd_kelulusan->search($rules)->num_rows();
                $sub_total_daya_tampung += $SubDayaTampung->daya_tampung;
                $sub_total_terisi += $num;
                $jm = strtolower(str_replace('-', '', $b->alias));
                $sub[$jm] = array(
                    'daya_tampung' => $SubDayaTampung->daya_tampung,
                    'terisi' => $num,
                    'persentase' => ($num != 0) ? round((($num / $SubDayaTampung->daya_tampung) * 100)) . '%' : '0%'
                );
            }
            $sub['total'] = array(
                'daya_tampung' => $sub_total_daya_tampung,
                'terisi' => $sub_total_terisi,
                'persentase' => ($sub_total_terisi != 0) ? round((($sub_total_terisi / $sub_total_daya_tampung) * 100)) . '%' : '0%'
            );
            $rules = array(
                'database'  => null, //Default database master
                'select'    => null,
                'where'     => array(
                    'kode_jurusan' => $a->kode_jurusan,
                    'YEAR(date_created)' => $tahun,
                    'status' => 'YA'
                ),
                'or_where'  => null,
                'like'      => null,
                'or_like'   => null,
                'order'     => null,
                'limit'     => null,
                'group_by'  => null,
            );
            $viewDT = $this->Views_daya_tampung->search($rules)->row();
            $data[] = array(
                'kode_jurusan' => $a->kode_jurusan,
                'jurusan' => $a->jurusan,
                'fakultas' => $a->fakultas,
                'daya_tampung' => $viewDT->daya_tampung,
                'kuota' => $viewDT->kuota,
                'afirmasi' => $viewDT->afirmasi,
                'jalur_masuk' => $sub
            );
        }
        foreach ($data as $jurusan) {
            foreach ($jurusan['jalur_masuk'] as $jalur => $nilai) {
                if (!isset($persentase[$jalur])) {
                    $persentase[$jalur] = array(
                        'daya_tampung' => 0,
                        'terisi' => 0,
                        'persentase' => '0%'
                    );
                }
                $persentase[$jalur]['daya_tampung'] += (int)$nilai['daya_tampung'];
                $persentase[$jalur]['terisi'] += (int)$nilai['terisi'];
                $persentase[$jalur]['persentase'] = ($persentase[$jalur]['terisi'] != 0) ? round((($persentase[$jalur]['terisi'] / $persentase[$jalur]['daya_tampung']) * 100)) . '%' : '0%';
            }
        }

        $this->response(array(
            'code' => 200,
            'status' => "success",
            'message' => null,
            'persentase' => $persentase,
            'data' => $data,
        ), 200);
    }

    // Jenis Sekolah
    function D11_get()
    {
        $data = $where = array();
        $tahun = ($this->get('tahun') != null) ? $this->get('tahun') : date('Y');
        $ids_jalur_masuk = ($this->get('ids_jalur_masuk') != null) ? $this->get('ids_jalur_masuk') : '';

        if ($tahun != '') {
            $where['YEAR(date_created)'] = $tahun;
        }
        if ($ids_jalur_masuk != '') {
            $where['ids_jalur_masuk'] = $ids_jalur_masuk;
        }

        $feedback = $this->master->read("jenis-sekolah/?status=YA&page=1&limit=1000");
        if ($feedback->code == 200) {
            foreach ($feedback->data->data as $a) {
                $where['ids_jenis_sekolah'] = $a->ids_jenis_sekolah;
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
                $data[] =  array(
                    'jenis_sekolah' => $a->jenis_sekolah,
                    'num' => $this->Viewd_sekolah->search($rules)->num_rows(),
                );

                $this->response(array(
                    'code' => 200,
                    'status' => "success",
                    'message' => null,
                    'data' => $data
                ), 200);
            }
        } else {
            $this->response($feedback, $feedback->code);
        }
    }

    // Jurusan Sekolah
    function D12_get()
    {
        $data = $where = array();
        $tahun = ($this->get('tahun') != null) ? $this->get('tahun') : date('Y');
        $ids_jalur_masuk = ($this->get('ids_jalur_masuk') != null) ? $this->get('ids_jalur_masuk') : '';
        $ids_jenis_sekolah = ($this->get('ids_jenis_sekolah') != null) ? $this->get('ids_jenis_sekolah') : '';

        if ($tahun != '') {
            $where['YEAR(date_created)'] = $tahun;
        }
        if ($ids_jalur_masuk != '') {
            $where['ids_jalur_masuk'] = $ids_jalur_masuk;
        }
        if ($ids_jenis_sekolah != '') {
            $where['ids_jenis_sekolah'] = $ids_jenis_sekolah;
        }

        $feedback = $this->master->read("jurusan-sekolah/?status=YA&page=1&limit=1000");
        if ($feedback->code == 200) {
            foreach ($feedback->data->data as $a) {
                $where['ids_jurusan_sekolah'] = $a->ids_jurusan_sekolah;
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
                $data[] =  array(
                    'jenis_sekolah' => $a->jenis_sekolah,
                    'jurusan_sekolah' => $a->jurusan_sekolah,
                    'num' => $this->Viewd_sekolah->search($rules)->num_rows(),
                );

                $this->response(array(
                    'code' => 200,
                    'status' => "success",
                    'message' => null,
                    'data' => $data
                ), 200);
            }
        } else {
            $this->response($feedback, $feedback->code);
        }
    }
}
