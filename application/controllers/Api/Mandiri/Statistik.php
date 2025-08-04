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
        $this->load->model('Mandiri/Viewp_biodata');
        $this->load->model('Mandiri/Viewp_kelulusan');
        $this->load->model('Mandiri/Viewp_pilihan');
        $this->load->model('Mandiri/Viewp_rumah');
        $this->load->model('Mandiri/Viewp_sekolah');
        $this->load->model('Settings/Tbs_program');
        $this->load->model('Settings/Tbs_tipe_ujian');
        $this->load->model('Settings/Views_tipe_ujian');
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

    // Biodata
    function M1_get()
    {
        $where['YEAR(date_created)'] = ($this->get('tahun') != null) ? $this->get('tahun') : date('Y');
        $jenis_kelamin = ($this->get('jenis_kelamin') != null) ? $this->get('jenis_kelamin') : '';
        $kategori = ($this->get('kategori') != null) ? $this->get('kategori') : '';
        $formulir = ($this->get('formulir') != null) ? $this->get('formulir') : '';
        $pembayaran = ($this->get('pembayaran') != null) ? $this->get('pembayaran') : '';
        $jenjang = ($this->get('jenjang') != null) ? $this->get('jenjang') : '';
        $jenjang_not = ($this->get('jenjang_not') != null) ? $this->get('jenjang_not') : '';
        $ids_program = ($this->get('ids_program') != null) ? $this->get('ids_program') : '';
        $ids_tipe_ujian = ($this->get('ids_tipe_ujian') != null) ? $this->get('ids_tipe_ujian') : '';
        $ids_tipe_ujian_not = ($this->get('ids_tipe_ujian_not') != null) ? $this->get('ids_tipe_ujian_not') : '';

        if ($jenis_kelamin != '') {
            $where['jenis_kelamin'] = $jenis_kelamin;
        }
        if ($kategori != '') {
            $where['kategori'] = $kategori;
        }
        if ($formulir != '') {
            $where['formulir'] = $formulir;
        }
        if ($pembayaran != '') {
            $where['pembayaran'] = $pembayaran;
        }
        if ($jenjang != '') {
            $where['jenjang'] = $jenjang;
        }
        if ($jenjang_not != '') {
            $where['jenjang !='] = $jenjang_not;
        }
        if ($ids_program != '') {
            $where['ids_program'] = $ids_program;
        }
        if ($ids_tipe_ujian != '') {
            $where['ids_tipe_ujian'] = $ids_tipe_ujian;
        }
        if ($ids_tipe_ujian_not != '') {
            $where['ids_tipe_ujian !='] = $ids_tipe_ujian_not;
        }

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

        $this->response(array(
            'code' => 200,
            'status' => "success",
            'message' => null,
            'data' => $this->Viewp_biodata->search($rules)->num_rows()
        ), 200);
    }

    // Biodata - Program
    function M2_get()
    {
        $data = $where = array();
        $where['YEAR(date_created)'] = ($this->get('tahun') != null) ? $this->get('tahun') : date('Y');
        $jenis_kelamin = ($this->get('jenis_kelamin') != null) ? $this->get('jenis_kelamin') : '';
        $kategori = ($this->get('kategori') != null) ? $this->get('kategori') : '';
        $formulir = ($this->get('formulir') != null) ? $this->get('formulir') : '';
        $pembayaran = ($this->get('pembayaran') != null) ? $this->get('pembayaran') : '';
        $jenjang = ($this->get('jenjang') != null) ? $this->get('jenjang') : '';
        $ids_program = ($this->get('ids_program') != null) ? $this->get('ids_program') : '';
        $ids_tipe_ujian = ($this->get('ids_tipe_ujian') != null) ? $this->get('ids_tipe_ujian') : '';

        if ($jenis_kelamin != '') {
            $where['jenis_kelamin'] = $jenis_kelamin;
        }
        if ($kategori != '') {
            $where['kategori'] = $kategori;
        }
        if ($formulir != '') {
            $where['formulir'] = $formulir;
        }
        if ($pembayaran != '') {
            $where['pembayaran'] = $pembayaran;
        }
        if ($jenjang != '') {
            $where['jenjang'] = $jenjang;
        }
        if ($ids_program != '') {
            $where['ids_program'] = $ids_program;
        }
        if ($ids_tipe_ujian != '') {
            $where['ids_tipe_ujian'] = $ids_tipe_ujian;
        }

        $rules = array(
            'database'  => null, //Database master
            'select'    => null,
            'order'     => null,
            'limit'     => null,
            'group_by'  => null,
        );
        $tbsProgram = $this->Tbs_program->read($rules);
        if ($tbsProgram->num_rows() > 0) {
            foreach ($tbsProgram->result() as $a) {
                $where['ids_program'] = $a->ids_program;
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
                    'program' => $a->program,
                    'jenjang' => $a->jenjang,
                    'kelas' => $a->kelas,
                    'num' => $this->Viewp_biodata->search($rules)->num_rows(),
                );
            }
            $this->response(array(
                'code' => 200,
                'status' => "success",
                'message' => null,
                'data' => $data
            ), 200);
        } else {
            $this->response(array(
                'code' => 400,
                'status' => "error",
                'message' => "Data tidak tersedia, silahkan coba lagi.",
            ), 400);
        }
    }

    // Biodata - Tipe Ujian
    function M3_get()
    {
        $data = $where = $where2 = array();
        $where['YEAR(date_created)'] = ($this->get('tahun') != null) ? $this->get('tahun') : date('Y');
        $jenis_kelamin = ($this->get('jenis_kelamin') != null) ? $this->get('jenis_kelamin') : '';
        $kategori = ($this->get('kategori') != null) ? $this->get('kategori') : '';
        $formulir = ($this->get('formulir') != null) ? $this->get('formulir') : '';
        $pembayaran = ($this->get('pembayaran') != null) ? $this->get('pembayaran') : '';
        $jenjang = ($this->get('jenjang') != null) ? $this->get('jenjang') : '';
        $jenjang_not = ($this->get('jenjang_not') != null) ? $this->get('jenjang_not') : '';
        $ids_program = ($this->get('ids_program') != null) ? $this->get('ids_program') : '';
        $ids_tipe_ujian = ($this->get('ids_tipe_ujian') != null) ? $this->get('ids_tipe_ujian') : '';
        $ids_tipe_ujian_not = ($this->get('ids_tipe_ujian_not') != null) ? $this->get('ids_tipe_ujian_not') : '';

        if ($jenis_kelamin != '') {
            $where['jenis_kelamin'] = $jenis_kelamin;
        }
        if ($kategori != '') {
            $where['kategori'] = $kategori;
        }
        if ($formulir != '') {
            $where['formulir'] = $formulir;
        }
        if ($pembayaran != '') {
            $where['pembayaran'] = $pembayaran;
        }
        if ($jenjang != '') {
            $where['jenjang'] = $jenjang;
            $where2['jenjang'] = $jenjang;
        }
        if ($jenjang_not != '') {
            $where['jenjang !='] = $jenjang_not;
            $where2['jenjang !='] = $jenjang_not;
        }
        if ($ids_program != '') {
            $where['ids_program'] = $ids_program;
            $where2['ids_program'] = $ids_program;
        }
        if ($ids_tipe_ujian != '') {
            $where['ids_tipe_ujian'] = $ids_tipe_ujian;
            $where2['ids_tipe_ujian'] = $ids_tipe_ujian;
        }
        if ($ids_tipe_ujian_not != '') {
            $where['ids_tipe_ujian !='] = $ids_tipe_ujian_not;
            $where2['ids_tipe_ujian !='] = $ids_tipe_ujian_not;
        }

        $rules = array(
            'database'  => null, //Default database master
            'select'    => null,
            'where'     => $where2,
            'or_where'  => null,
            'like'      => null,
            'or_like'   => null,
            'order'     => null,
            'limit'     => null,
            'group_by'  => null,
        );
        $tbsTipeUjian = $this->Views_tipe_ujian->search($rules);
        if ($tbsTipeUjian->num_rows() > 0) {
            foreach ($tbsTipeUjian->result() as $a) {
                $where['ids_tipe_ujian'] = $a->ids_tipe_ujian;
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
                    'tipe_ujian' => $a->tipe_ujian,
                    'num' => $this->Viewp_biodata->search($rules)->num_rows(),
                );
            }
            $this->response(array(
                'code' => 200,
                'status' => "success",
                'message' => null,
                'data' => $data
            ), 200);
        } else {
            $this->response(array(
                'code' => 400,
                'status' => "error",
                'message' => "Data tidak tersedia, silahkan coba lagi.",
            ), 400);
        }
    }

    // Biodata - Agama
    function M4_get()
    {
        $data = $where = array();
        $where['YEAR(date_created)'] = ($this->get('tahun') != null) ? $this->get('tahun') : date('Y');
        $jenis_kelamin = ($this->get('jenis_kelamin') != null) ? $this->get('jenis_kelamin') : '';
        $kategori = ($this->get('kategori') != null) ? $this->get('kategori') : '';
        $formulir = ($this->get('formulir') != null) ? $this->get('formulir') : '';
        $pembayaran = ($this->get('pembayaran') != null) ? $this->get('pembayaran') : '';

        if ($jenis_kelamin != '') {
            $where['jenis_kelamin'] = $jenis_kelamin;
        }
        if ($kategori != '') {
            $where['kategori'] = $kategori;
        }
        if ($formulir != '') {
            $where['formulir'] = $formulir;
        }
        if ($pembayaran != '') {
            $where['pembayaran'] = $pembayaran;
        }

        $feedback = $this->master->read("agama/?page=1&limit=1000");
        if ($feedback->code == 200) {
            foreach ($feedback->data->data as $a) {
                $where['ids_agama'] = $a->ids_agama;
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
                    'agama' => $a->agama,
                    'num' => $this->Viewp_biodata->search($rules)->num_rows(),
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

    // Rumah - Negara
    function M5_get()
    {
        $data = $where = array();
        $where['YEAR(date_created)'] = ($this->get('tahun') != null) ? $this->get('tahun') : date('Y');
        $kategori = ($this->get('kategori') != null) ? $this->get('kategori') : '';
        $ids_tipe_ujian = ($this->get('ids_tipe_ujian') != null) ? $this->get('ids_tipe_ujian') : '';
        $page = ($this->get('page') != null) ? $this->get('page') : '1';
        $limit = ($this->get('limit') != null) ? $this->get('limit') : $_ENV['LIMIT_DATA'];

        if ($kategori != '') {
            $where['kategori'] = $kategori;
        }
        if ($ids_tipe_ujian != '') {
            $where['ids_tipe_ujian'] = $ids_tipe_ujian;
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
                    'num' => $this->Viewp_rumah->search($rules)->num_rows(),
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

    // Rumah - Provinsi
    function M6_get()
    {
        $data = $where = array();
        $ids_negara = ($this->get('ids_negara') != null) ? $this->get('ids_negara') : '103';
        $where['YEAR(date_created)'] = ($this->get('tahun') != null) ? $this->get('tahun') : date('Y');
        $kategori = ($this->get('kategori') != null) ? $this->get('kategori') : '';
        $ids_tipe_ujian = ($this->get('ids_tipe_ujian') != null) ? $this->get('ids_tipe_ujian') : '';
        $page = ($this->get('page') != null) ? $this->get('page') : '1';
        $limit = ($this->get('limit') != null) ? $this->get('limit') : $_ENV['LIMIT_DATA'];

        if ($kategori != '') {
            $where['kategori'] = $kategori;
        }
        if ($ids_tipe_ujian != '') {
            $where['ids_tipe_ujian'] = $ids_tipe_ujian;
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
                    'num' => $this->Viewp_rumah->search($rules)->num_rows(),
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

    // Rumah - Kabupaten atau Kota
    function M7_get()
    {
        $data = $where = array();
        $ids_negara = ($this->get('ids_negara') != null) ? $this->get('ids_negara') : '103';
        $ids_provinsi = ($this->get('ids_provinsi') != null) ? $this->get('ids_provinsi') : '12';
        $where['YEAR(date_created)'] = ($this->get('tahun') != null) ? $this->get('tahun') : date('Y');
        $kategori = ($this->get('kategori') != null) ? $this->get('kategori') : '';
        $ids_tipe_ujian = ($this->get('ids_tipe_ujian') != null) ? $this->get('ids_tipe_ujian') : '';
        $page = ($this->get('page') != null) ? $this->get('page') : '1';
        $limit = ($this->get('limit') != null) ? $this->get('limit') : $_ENV['LIMIT_DATA'];

        if ($kategori != '') {
            $where['kategori'] = $kategori;
        }
        if ($ids_tipe_ujian != '') {
            $where['ids_tipe_ujian'] = $ids_tipe_ujian;
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
                    'num' => $this->Viewp_rumah->search($rules)->num_rows(),
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

    // Rumah - Kecamatan
    function M8_get()
    {
        $data = $where = array();
        $ids_negara = ($this->get('ids_negara') != null) ? $this->get('ids_negara') : '103';
        $ids_provinsi = ($this->get('ids_provinsi') != null) ? $this->get('ids_provinsi') : '12';
        $ids_kab_kota = ($this->get('ids_kab_kota') != null) ? $this->get('ids_kab_kota') : '181';
        $where['YEAR(date_created)'] = ($this->get('tahun') != null) ? $this->get('tahun') : date('Y');
        $kategori = ($this->get('kategori') != null) ? $this->get('kategori') : '';
        $ids_tipe_ujian = ($this->get('ids_tipe_ujian') != null) ? $this->get('ids_tipe_ujian') : '';
        $page = ($this->get('page') != null) ? $this->get('page') : '1';
        $limit = ($this->get('limit') != null) ? $this->get('limit') : $_ENV['LIMIT_DATA'];

        if ($kategori != '') {
            $where['kategori'] = $kategori;
        }
        if ($ids_tipe_ujian != '') {
            $where['ids_tipe_ujian'] = $ids_tipe_ujian;
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
                    'num' => $this->Viewp_rumah->search($rules)->num_rows(),
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

    // Rumah - Kelurahan
    function M9_get()
    {
        $data = $where = array();
        $ids_negara = ($this->get('ids_negara') != null) ? $this->get('ids_negara') : '103';
        $ids_provinsi = ($this->get('ids_provinsi') != null) ? $this->get('ids_provinsi') : '12';
        $ids_kab_kota = ($this->get('ids_kab_kota') != null) ? $this->get('ids_kab_kota') : '181';
        $ids_kecamatan = ($this->get('ids_kecamatan') != null) ? $this->get('ids_kecamatan') : '2543';
        $where['YEAR(date_created)'] = ($this->get('tahun') != null) ? $this->get('tahun') : date('Y');
        $kategori = ($this->get('kategori') != null) ? $this->get('kategori') : '';
        $ids_tipe_ujian = ($this->get('ids_tipe_ujian') != null) ? $this->get('ids_tipe_ujian') : '';
        $page = ($this->get('page') != null) ? $this->get('page') : '1';
        $limit = ($this->get('limit') != null) ? $this->get('limit') : $_ENV['LIMIT_DATA'];

        if ($kategori != '') {
            $where['kategori'] = $kategori;
        }
        if ($ids_tipe_ujian != '') {
            $where['ids_tipe_ujian'] = $ids_tipe_ujian;
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
                    'num' => $this->Viewp_rumah->search($rules)->num_rows(),
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

    // Jurusan - Pilihan
    function M10_get()
    {
        $data = $where = array();
        $where['YEAR(date_created)'] = ($this->get('tahun') != null) ? $this->get('tahun') : date('Y');
        $ids_tipe_ujian = ($this->get('ids_tipe_ujian') != null) ? $this->get('ids_tipe_ujian') : '';
        $ids_tipe_ujian_not = ($this->get('ids_tipe_ujian_not') != null) ? $this->get('ids_tipe_ujian_not') : '';
        $jenjang = ($this->get('jenjang') != null) ? $this->get('jenjang') : '';
        $jenjang_not = ($this->get('jenjang_not') != null) ? $this->get('jenjang_not') : '';
        $formulir = ($this->get('formulir') != null) ? $this->get('formulir') : 'SUDAH';
        $pembayaran = ($this->get('pembayaran') != null) ? $this->get('pembayaran') : '';
        $kategori = ($this->get('kategori') != null) ? $this->get('kategori') : '';

        if ($ids_tipe_ujian != '') {
            $where['ids_tipe_ujian'] = $ids_tipe_ujian;
        }

        if ($ids_tipe_ujian_not != '') {
            $where['ids_tipe_ujian !='] = $ids_tipe_ujian_not;
        }
        
        if ($jenjang != '') {
            $where['jenjang'] = $jenjang;
        }

        if ($jenjang_not != '') {
            $where['jenjang !='] = $jenjang_not;
        }

        if ($formulir != '') {
            $where['formulir'] = $formulir;
        }

        if ($pembayaran != '') {
            $where['pembayaran'] = $pembayaran;
        }

        if ($kategori != '') {
            $where['kategori'] = $kategori;
        }

        $feedback = $this->master->read("jurusan/?status=YA&page=1&limit=100");
        if ($feedback->code == 200) {
            for ($i = 1; $i <= 3; $i++) {
                $where['pilihan'] = $i;
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
                    $subdata[$i][$a->kode_jurusan] =  $this->Viewp_pilihan->search($rules)->num_rows();
                }
            }
            foreach ($feedback->data->data as $a) {
                $data[] =  array(
                    'jurusan' => $a->jurusan,
                    'fakultas' => $a->fakultas,
                    'pilihan1' => $subdata[1][$a->kode_jurusan],
                    'pilihan2' => $subdata[2][$a->kode_jurusan],
                    'pilihan3' => $subdata[3][$a->kode_jurusan],
                    'total' => ($subdata[1][$a->kode_jurusan] + $subdata[2][$a->kode_jurusan] + $subdata[3][$a->kode_jurusan]),
                );
            }
            $this->response(array(
                'code' => 200,
                'status' => "success",
                'message' => null,
                'data' => $data
            ), 200);
        } else {
            $this->response(array(
                'code' => 400,
                'status' => "error",
                'message' => "Data tidak tersedia, silahkan coba lagi.",
            ), 400);
        }
    }

    // Jurusan - Kelulusan
    function M11_get()
    {
        $data = $where = array();
        $where['YEAR(date_created)'] = ($this->get('tahun') != null) ? $this->get('tahun') : date('Y');
        $kategori = ($this->get('kategori') != null) ? $this->get('kategori') : '';

        if ($kategori != '') {
            $where['kategori'] = $kategori;
        }

        $feedback = $this->master->read("jurusan/?status=YA&page=1&limit=100");
        if ($feedback->code == 200) {
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
                $data[] =  array(
                    'kode_jurusan' => $a->kode_jurusan,
                    'jurusan' => $a->jurusan,
                    'fakultas' => $a->fakultas,
                    'num' => $this->Viewp_kelulusan->search($rules)->num_rows(),
                );
            }
            $this->response(array(
                'code' => 200,
                'status' => "success",
                'message' => null,
                'data' => $data
            ), 200);
        } else {
            $this->response(array(
                'code' => 400,
                'status' => "error",
                'message' => "Data tidak tersedia, silahkan coba lagi.",
            ), 400);
        }
    }

    // Sekolah - Rumpun
    function M12_get()
    {
        $data = $where = array();
        $where['YEAR(date_created)'] = ($this->get('tahun') != null) ? $this->get('tahun') : date('Y');
        $kategori = ($this->get('kategori') != null) ? $this->get('kategori') : '';
        $ids_tipe_ujian = ($this->get('ids_tipe_ujian') != null) ? $this->get('ids_tipe_ujian') : '';
        $akreditasi_sekolah = ($this->get('akreditasi_sekolah') != null) ? $this->get('akreditasi_sekolah') : '';

        if ($kategori != '') {
            $where['kategori'] = $kategori;
        }
        if ($ids_tipe_ujian != '') {
            $where['ids_tipe_ujian'] = $ids_tipe_ujian;
        }
        if ($akreditasi_sekolah != '') {
            $where['akreditasi_sekolah'] = $akreditasi_sekolah;
        }

        $feedback = $this->master->read("rumpun/?status=YA&page=1&limit=100");
        if ($feedback->code == 200) {
            foreach ($feedback->data->data as $a) {
                $where['ids_rumpun'] = $a->ids_rumpun;
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
                    'rumpun' => $a->rumpun,
                    'num' => $this->Viewp_sekolah->search($rules)->num_rows(),
                );
            }
            $this->response(array(
                'code' => 200,
                'status' => "success",
                'message' => null,
                'data' => $data
            ), 200);
        } else {
            $this->response(array(
                'code' => 400,
                'status' => "error",
                'message' => "Data tidak tersedia, silahkan coba lagi.",
            ), 400);
        }
    }

    // Sekolah - Jenis Sekolah
    function M13_get()
    {
        $data = $where = array();
        $where['YEAR(date_created)'] = ($this->get('tahun') != null) ? $this->get('tahun') : date('Y');
        $kategori = ($this->get('kategori') != null) ? $this->get('kategori') : '';
        $ids_tipe_ujian = ($this->get('ids_tipe_ujian') != null) ? $this->get('ids_tipe_ujian') : '';
        $akreditasi_sekolah = ($this->get('akreditasi_sekolah') != null) ? $this->get('akreditasi_sekolah') : '';

        if ($kategori != '') {
            $where['kategori'] = $kategori;
        }
        if ($ids_tipe_ujian != '') {
            $where['ids_tipe_ujian'] = $ids_tipe_ujian;
        }
        if ($akreditasi_sekolah != '') {
            $where['akreditasi_sekolah'] = $akreditasi_sekolah;
        }

        $feedback = $this->master->read("jenis-sekolah/?status=YA&page=1&limit=100");
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
                    'num' => $this->Viewp_sekolah->search($rules)->num_rows(),
                );
            }
            $this->response(array(
                'code' => 200,
                'status' => "success",
                'message' => null,
                'data' => $data
            ), 200);
        } else {
            $this->response(array(
                'code' => 400,
                'status' => "error",
                'message' => "Data tidak tersedia, silahkan coba lagi.",
            ), 400);
        }
    }

    // Sekolah - Jurusan Sekolah
    function M14_get()
    {
        $data = $where = array();
        $where['YEAR(date_created)'] = ($this->get('tahun') != null) ? $this->get('tahun') : date('Y');
        $kategori = ($this->get('kategori') != null) ? $this->get('kategori') : '';
        $ids_tipe_ujian = ($this->get('ids_tipe_ujian') != null) ? $this->get('ids_tipe_ujian') : '';
        $akreditasi_sekolah = ($this->get('akreditasi_sekolah') != null) ? $this->get('akreditasi_sekolah') : '';

        if ($kategori != '') {
            $where['kategori'] = $kategori;
        }
        if ($ids_tipe_ujian != '') {
            $where['ids_tipe_ujian'] = $ids_tipe_ujian;
        }
        if ($akreditasi_sekolah != '') {
            $where['akreditasi_sekolah'] = $akreditasi_sekolah;
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
                    'jurusan_sekolah' => $a->jurusan_sekolah,
                    'num' => $this->Viewp_sekolah->search($rules)->num_rows(),
                );
            }
            $this->response(array(
                'code' => 200,
                'status' => "success",
                'message' => null,
                'data' => $data
            ), 200);
        } else {
            $this->response(array(
                'code' => 400,
                'status' => "error",
                'message' => "Data tidak tersedia, silahkan coba lagi.",
            ), 400);
        }
    }

    // Biodata - Kebutuhan Khusus
    function M15_get()
    {
        $data = $where = array();
        $where['YEAR(date_created)'] = ($this->get('tahun') != null) ? $this->get('tahun') : date('Y');
        $jenis_kelamin = ($this->get('jenis_kelamin') != null) ? $this->get('jenis_kelamin') : '';
        $kategori = ($this->get('kategori') != null) ? $this->get('kategori') : '';
        $formulir = ($this->get('formulir') != null) ? $this->get('formulir') : '';
        $pembayaran = ($this->get('pembayaran') != null) ? $this->get('pembayaran') : '';
        $jenjang = ($this->get('jenjang') != null) ? $this->get('jenjang') : '';
        $jenjang_not = ($this->get('jenjang_not') != null) ? $this->get('jenjang_not') : '';
        $ids_program = ($this->get('ids_program') != null) ? $this->get('ids_program') : '';
        $ids_tipe_ujian = ($this->get('ids_tipe_ujian') != null) ? $this->get('ids_tipe_ujian') : '';
        $ids_tipe_ujian_not = ($this->get('ids_tipe_ujian_not') != null) ? $this->get('ids_tipe_ujian_not') : '';

        if ($jenis_kelamin != '') {
            $where['jenis_kelamin'] = $jenis_kelamin;
        }
        if ($kategori != '') {
            $where['kategori'] = $kategori;
        }
        if ($formulir != '') {
            $where['formulir'] = $formulir;
        }
        if ($pembayaran != '') {
            $where['pembayaran'] = $pembayaran;
        }
        if ($ids_program != '') {
            $where['ids_program'] = $ids_program;
        }
        if ($ids_tipe_ujian != '') {
            $where['ids_tipe_ujian'] = $ids_tipe_ujian;
        }
        if ($jenjang != '') {
            $where['jenjang'] = $jenjang;
        }
        if ($ids_tipe_ujian_not != '') {
            $where['ids_tipe_ujian !='] = $ids_tipe_ujian_not;
        }
        if ($jenjang_not != '') {
            $where['jenjang !='] = $jenjang_not;
        }

        $feedback = $this->master->read("kebutuhan-khusus/?page=1&limit=1000");
        if ($feedback->code == 200) {
            foreach ($feedback->data->data as $a) {
                $where['ids_keb_khusus'] = $a->ids_keb_khusus;
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
                    'keb_khusus' => $a->keb_khusus,
                    'num' => $this->Viewp_biodata->search($rules)->num_rows(),
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
}
