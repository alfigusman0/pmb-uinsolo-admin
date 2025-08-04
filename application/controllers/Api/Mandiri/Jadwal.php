<?php
defined('BASEPATH') or exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;

class Jadwal extends RestController
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
        $this->load->model('Tbl_users');
        $this->load->model('Mandiri/Viewp_biodata');
        $this->load->model('Mandiri/Viewp_rumah');
        $this->load->model('Mandiri/Viewp_file');
        $this->load->model('Mandiri/Viewp_jadwal');
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
    function Read_get()
    {
        $CekToken = $this->master->CekToken($this->get('token'));
        if ($CekToken->code == 200) {
            $where['YEAR(date_created)'] = ($this->get('tahun') != null) ? $this->get('tahun') : date('Y');
            $nomor_peserta = ($this->get('nomor_peserta') != null) ? $this->get('nomor_peserta') : '';
            $kategori = ($this->get('kategori') != null) ? $this->get('kategori') : '';
            $ids_tipe_ujian = ($this->get('ids_tipe_ujian') != null) ? $this->get('ids_tipe_ujian') : '';
            $ids_jadwal = ($this->get('ids_jadwal') != null) ? $this->get('ids_jadwal') : '';
            $tanggal = ($this->get('tanggal') != null) ? $this->get('tanggal') : '';
            $ids_area = ($this->get('ids_area') != null) ? $this->get('ids_area') : '';
            $ids_gedung = ($this->get('ids_gedung') != null) ? $this->get('ids_gedung') : '';
            $ids_ruangan = ($this->get('ids_ruangan') != null) ? $this->get('ids_ruangan') : '';

            if ($nomor_peserta != '') {
                $where['nomor_peserta'] = $nomor_peserta;
            }
            if ($kategori != '') {
                $where['kategori'] = $kategori;
            }
            if ($ids_tipe_ujian != '') {
                $where['ids_tipe_ujian'] = $ids_tipe_ujian;
            }
            if ($ids_jadwal != '') {
                $where['ids_jadwal'] = $ids_jadwal;
            }
            if ($tanggal != '') {
                $where['tanggal'] = $tanggal;
            }
            if ($ids_area != '') {
                $where['ids_area'] = $ids_area;
            }
            if ($ids_gedung != '') {
                $where['ids_gedung'] = $ids_gedung;
            }
            if ($ids_ruangan != '') {
                $where['ids_ruangan'] = $ids_ruangan;
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
            $viewpJadwal = $this->Viewp_jadwal->search($rules)->result();

            $data = array();
            foreach ($viewpJadwal as $a) {
                $rules = array(
                    'database'  => null, //Default database master
                    'select'    => null,
                    'where'     => array(
                        'idp_formulir' => $a->idp_formulir
                    ),
                    'or_where'  => null,
                    'like'      => null,
                    'or_like'   => null,
                    'order'     => null,
                    'limit'     => null,
                    'group_by'  => null,
                );
                $viewpBiodata = $this->Viewp_biodata->search($rules)->row();
                $viewpRumah = $this->Viewp_rumah->search($rules)->row();
                $rules = array(
                    'database'  => null, //Default database master
                    'select'    => null,
                    'where'     => array(
                        'id_user' => $viewpBiodata->created_by
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
                        'idp_formulir' => $a->idp_formulir,
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
                $viewpFileFoto2 = null;
                if($viewpFileFoto->num_rows() > 0){
                    $viewpFileFoto2 = $viewpFileFoto->row();
                }
                $rules = array(
                    'database'  => null, //Default database master
                    'select'    => null,
                    'where'     => array(
                        'idp_formulir' => $a->idp_formulir,
                        'ids_tipe_file' => 69
                    ),
                    'or_where'  => null,
                    'like'      => null,
                    'or_like'   => null,
                    'order'     => null,
                    'limit'     => null,
                    'group_by'  => null,
                );
                $viewpFileKTP = $this->Viewp_file->search($rules);
                $viewpFileKTP2 = null;
                if($viewpFileKTP->num_rows() > 0){
                    $viewpFileKTP2 = $viewpFileKTP->row();
                }
                $date = $viewpBiodata->tgl_lahir;
                $formatted = date('d/m/Y', strtotime($date));
                $data[] = array(
                    'idp_jadwal' => $a->idp_jadwal,
                    'idp_formulir' => $a->idp_formulir,
                    'nomor_peserta' => $a->nomor_peserta,
                    'nama' => $a->nama,
                    'email' => $tblUsers->email,
                    'nomor_telepon' => (!empty($tblUsers->nmr_tlpn)) ? $tblUsers->nmr_tlpn : '0',
                    'kategori' => $a->kategori,
                    'ids_tipe_ujian' => $a->ids_tipe_ujian,
                    'tipe_ujian' => $a->tipe_ujian,
                    'tanggal' => $a->tanggal,
                    'jam_awal' => $a->jam_awal,
                    'jam_akhir' => $a->jam_akhir,
                    'ids_area' => $a->ids_area,
                    'area' => $a->area,
                    'ids_gedung' => $a->ids_gedung,
                    'gedung' => $a->gedung,
                    'ids_ruangan' => $a->ids_ruangan,
                    'ruangan' => $a->ruangan,
                    'jenis_kelamin' => $viewpBiodata->jenis_kelamin,
                    'tempat_lahir' => $viewpBiodata->tempat_lahir,
                    'tgl_lahir' => $formatted,
                    'kewarganegaraan' => $viewpBiodata->kewarganegaraan,
                    'alamat' => $viewpRumah->jalan,
                    'foto' => ($viewpFileFoto2 != null) ? $viewpFileFoto2->url : '#',
                    'ktp' => ($viewpFileKTP2 != null) ? $viewpFileKTP2->url : '#'
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
                'code' => $CekToken->code,
                'status' => $CekToken->status,
                'message' => $CekToken->message,
            ), $CekToken->code);
        }
    }
}
