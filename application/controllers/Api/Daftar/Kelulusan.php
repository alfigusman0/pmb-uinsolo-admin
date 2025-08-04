<?php
defined('BASEPATH') or exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;

class Kelulusan extends RestController
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
        $this->load->model('Daftar/Tbd_file');
        $this->load->model('Daftar/Viewd_file');
        $this->load->model('Daftar/Viewd_kelulusan');
        $this->load->model('Daftar/Viewd_mahasiswa');
        $this->load->model('Daftar/Viewd_orangtua');
        $this->load->model('Daftar/Viewd_rumah');
        $this->load->model('Daftar/Viewd_sekolah');
        $this->load->model('Daftar/Viewd_ukt');
    }

    function Create_post()
    {
        $this->response(array(
            'status' => false,
            'message' => 'Index post is not found.'
        ), 404);
    }

    function Read_get()
    {
        // Cek apakah Authorization header ada, jika tidak gunakan Token
        $header = $this->head();
        if (isset($header['Authorization'])) {
            $token = explode(' ', $header['Authorization']);
        } elseif (isset($header['Token'])) {
            $token = explode(
                ' ',
                $header['Token']
            );
        } else {
            $this->response(array(
                'code' => 401,
                'status' => 'error',
                'message' => 'Token tidak ditemukan.'
            ), 401);
        }

        // Cek validitas token
        if (isset($token[1])) {
            $cek = $this->master->CekToken($token[1]);
        } else {
            $this->response(array(
                'code' => 401,
                'status' => 'error',
                'message' => 'Token tidak valid.'
            ), 401);
        }

        $logical = strtoupper($this->get('logical') ?: 'AND');
        $page = max(1, intval($this->get('page') ?: 1));
        $resPerPage = intval($this->get('limit') ?: $_ENV['LIMIT_DATA']);
        $offset = ($page - 1) * $resPerPage;

        // Kumpulan filter untuk where dan like
        $filters = [
            'idd_kelulusan' => $this->get('idd_kelulusan'),
            'nomor_peserta' => $this->get('nomor_peserta'),
            'nim' => $this->get('nim'),
            'ids_fakultas' => $this->get('ids_fakultas'),
            'kode_jurusan' => $this->get('kode_jurusan'),
            'akreditasi' => $this->get('akreditasi'),
            'ids_jalur_masuk' => $this->get('ids_jalur_masuk'),
            'alias_jalur_masuk' => $this->get('alias_jalur_masuk'),
            'tahun' => $this->get('tahun'),
            'daftar' => $this->get('daftar'),
            'submit' => $this->get('submit'),
            'pembayaran' => $this->get('pembayaran')
        ];

        $likeFilters = [
            'nama' => $this->get('nama'),
            'fakultas' => $this->get('fakultas'),
            'jurusan' => $this->get('jurusan'),
            'jalur_masuk' => $this->get('jalur_masuk')
        ];

        // Membersihkan dan menyiapkan kondisi where dan like
        $where = array_filter(
            $filters,
            fn($value) => $value !== null && $value !== ''
        );
        $like = array_map(fn($value) => str_replace(' ', '%', $value), array_filter($likeFilters, fn($value) => $value !== null && $value !== ''));

        // Penyesuaian untuk jenjang dengan kondisi IN
        if ($jenjang = $this->get('jenjang')) {
            $where["jenjang IN ('" . str_replace(',', "','", $jenjang) . "')"] = null;
        }

        $rules = [
            'database'  => null,
            'select'    => null,
            'where'     => ($logical === 'AND') ? $where : null,
            'or_where'  => ($logical === 'OR') ? $where : null,
            'like'      => ($logical === 'AND') ? $like : null,
            'or_like'   => ($logical === 'OR') ? $like : null,
            'order'     => 'idd_kelulusan ASC',
            'limit'     => ['awal' => $offset, 'akhir' => $resPerPage],
            'group_by'  => null
        ];

        $viewdKelulusan = $this->Viewd_kelulusan->search($rules);

        // Hitung total data untuk pagination
        $rules['select'] = 'COUNT(idd_kelulusan) as total';
        unset($rules['order'], $rules['limit']);
        $num_rows = $this->Viewd_kelulusan->search($rules)->row();

        if ($viewdKelulusan->num_rows() > 0) {
            $this->response([
                'code' => 200,
                'status' => 'success',
                'message' => '',
                'data' => $viewdKelulusan->result(),
                'pagination' => $this->utilities->getPagination($num_rows->total, $resPerPage, $page)
            ], 200);
        } else {
            $this->response([
                'code' => 404,
                'status' => 'error',
                'message' => 'Data tidak ditemukan.'
            ], 404);
        }
    }


    function Update_put()
    {
        $this->response(array(
            'status' => false,
            'message' => 'Index post is not found.'
        ), 404);
    }

    function Delete_delete()
    {
        $this->response(array(
            'status' => false,
            'message' => 'Index post is not found.'
        ), 404);
    }

    function Single_get($id)
    {
        $this->response(array(
            'status' => false,
            'message' => 'Index post is not found.'
        ), 404);
    }

    // Full Biodata - Integrasi Salam
    function Full_get()
    {
        $header = $this->head();

        // Cek apakah Authorization header ada, jika tidak gunakan Token
        if (isset($header['Authorization'])) {
            $token = explode(' ', $header['Authorization']);
        } elseif (isset($header['Token'])) {
            $token = explode(' ', $header['Token']);
        } else {
            $this->response(array(
                'code' => 401,
                'status' => 'error',
                'message' => 'Token tidak ditemukan.'
            ), 401);
        }

        // Cek validitas token
        if (isset($token[1])) {
            $cek = $this->master->CekToken($token[1]);
        } else {
            $this->response(array(
                'code' => 401,
                'status' => 'error',
                'message' => 'Token tidak valid.'
            ), 401);
        }

        if ($cek->code !== 200) {
            $this->response(array(
                'code' => 401,
                'status' => 'error',
                'message' => 'Token tidak ada akses.'
            ), 401);
        }

        // Jika token sesuai dengan MASTER_TOKEN
        if ($token[1] !== $_ENV['MASTER_TOKEN']) {
            $this->response(array(
                'code' => 401,
                'status' => 'error',
                'message' => 'Token tidak ada akses.'
            ), 401);
        }

        $where = array();
        $where['tahun'] = ($this->get('tahun') !== null) ? $this->get('tahun') : date('Y');
        $nomor_peserta = $this->get('nomor_peserta') ?: '';
        $nim = $this->get('nim') ?: '';
        $jenjang = $this->get('jenjang') ?: '';
        $ids_fakultas = $this->get('ids_fakultas') ?: '';
        $kode_jurusan = $this->get('kode_jurusan') ?: '';
        $ids_jalur_masuk = $this->get('ids_jalur_masuk') ?: '';
        $daftar = $this->get('daftar') ?: '';
        $submit = $this->get('submit') ?: '';
        $pembayaran = $this->get('pembayaran') ?: '';
        $pemberkasan = $this->get('pemberkasan') ?: '';

        // Pagging
        $resPerPage = $this->get('limit') ?: $_ENV['LIMIT_DATA'];
        $page = ($this->get('page') ?: 1) - 1;
        $page = $page * $resPerPage;
        $currentPage = $this->get('page') ?: 1;

        // Assign filters
        if ($nomor_peserta !== '') $where['nomor_peserta'] = $nomor_peserta;
        if ($nim !== '') $where['nim'] = $nim;
        if ($jenjang !== '') $where["jenjang IN ('" . str_replace(',', "','", $jenjang) . "')"] = null;
        if ($ids_fakultas !== '') $where['ids_fakultas'] = $ids_fakultas;
        if ($kode_jurusan !== '') $where['kode_jurusan'] = $kode_jurusan;
        if ($ids_jalur_masuk !== '') $where['ids_jalur_masuk'] = $ids_jalur_masuk;
        if ($daftar !== '') $where['daftar'] = $daftar;
        if ($submit !== '') $where['submit'] = $submit;
        if ($pembayaran !== '') $where['pembayaran'] = $pembayaran;
        if ($pemberkasan !== '') $where['pemberkasan'] = $pemberkasan;

        // Query data
        $rules = array(
            'database'  => null,
            'select'    => null,
            'where'     => $where,
            'order'     => 'nim ASC',
            'limit'     => array('awal' => $page, 'akhir' => $resPerPage),
        );
        $viewdKelulusan = $this->Viewd_kelulusan->search($rules);

        // Query total data count
        $rules['select'] = 'COUNT(idd_kelulusan) as total';
        unset($rules['order'], $rules['limit']);
        $num_rows = $this->Viewd_kelulusan->search($rules)->row();

        if ($viewdKelulusan->num_rows() > 0) {
            $data = array();
            foreach ($viewdKelulusan->result() as $a) {
                $viewdMahasiswa = $this->Viewd_mahasiswa->search(['where' => ['idd_kelulusan' => $a->idd_kelulusan]])->row();
                $viewdRumah = $this->Viewd_rumah->search(['where' => ['idd_kelulusan' => $a->idd_kelulusan]])->row();
                $viewdSekolah = $this->Viewd_sekolah->search(['where' => ['idd_kelulusan' => $a->idd_kelulusan]])->row();
                $viewdFile = $this->Viewd_file->search(['where' => ['idd_kelulusan' => $a->idd_kelulusan]])->result();
                $viewdUKT = $this->Viewd_ukt->search(['where' => ['idd_kelulusan' => $a->idd_kelulusan]])->row();
                $tblUser = $this->Tbl_users->search(['where' => ['id_user' => $a->id_user]])->row();
                $viewdOrangTua = $this->Viewd_orangtua->search(['where' => ['idd_kelulusan' => $a->idd_kelulusan]])->result();

                $data[] = array(
                    'nomor_peserta' => $a->nomor_peserta,
                    'nim' => $a->nim,
                    'nik' => $viewdMahasiswa->nik,
                    'nama' => $a->nama,
                    'ids_fakultas' => $a->ids_fakultas,
                    'fakultas' => $a->fakultas,
                    'kode_jurusan' => $a->kode_jurusan,
                    'jurusan' => $a->jurusan,
                    'ids_konsentrasi' => $a->ids_konsentrasi,
                    'konsentrasi' => $a->konsentrasi,
                    'ids_jalur_masuk' => $a->ids_jalur_masuk,
                    'id_salam_jalur_masuk' => $a->id_salam_jalur_masuk,
                    'jalur_masuk' => $a->jalur_masuk,
                    'jenis_kelamin' => $viewdMahasiswa->jenis_kelamin,
                    'tempat_lahir' => $viewdMahasiswa->tempat_lahir,
                    'tgl_lahir' => $viewdMahasiswa->tgl_lahir,
                    'ids_agama' => $viewdMahasiswa->ids_agama,
                    'agama' => $viewdMahasiswa->agama,
                    'kewarganegaraan' => $viewdMahasiswa->kewarganegaraan,
                    'ids_jenis_tinggal' => $viewdMahasiswa->ids_jenis_tinggal,
                    'jenis_tinggal' => $viewdMahasiswa->jenis_tinggal,
                    'ids_alat_transportasi' => $viewdMahasiswa->ids_alat_transportasi,
                    'id_salam_alat_transportasi' => $viewdMahasiswa->id_salam_alat_transportasi,
                    'alat_transportasi' => $viewdMahasiswa->alat_transportasi,
                    'nilai_alat_transportasi' => $viewdMahasiswa->nilai_alat_transportasi,
                    'terima_kps' => $viewdMahasiswa->terima_kps,
                    'no_kps' => $viewdMahasiswa->no_kps,
                    'ids_jenis_pendaftaran' => $viewdMahasiswa->ids_jenis_pendaftaran,
                    'jenis_pendaftaran' => $viewdMahasiswa->jenis_pendaftaran,
                    'ids_jenis_pembiayaan' => $viewdMahasiswa->ids_jenis_pembiayaan,
                    'jenis_pembiayaan' => $viewdMahasiswa->jenis_pembiayaan,
                    'ids_hubungan' => $viewdMahasiswa->ids_hubungan,
                    'hubungan' => $viewdMahasiswa->hubungan,
                    'nilai_hubungan' => $viewdMahasiswa->nilai_hubungan,
                    'email' => $tblUser->email,
                    'nmr_tlpn' => $tblUser->nmr_tlpn,
                    'orangtua' => $viewdOrangTua,
                    'rumah' => array(
                        'ids_negara' => $viewdRumah->ids_negara,
                        'kode1' => $viewdRumah->kode1,
                        'kode2' => $viewdRumah->kode2,
                        'negara' => $viewdRumah->negara,
                        'ids_provinsi' => $viewdRumah->ids_provinsi,
                        'provinsi' => $viewdRumah->provinsi,
                        'ids_kab_kota' => $viewdRumah->ids_kab_kota,
                        'kab_kota' => $viewdRumah->kab_kota,
                        'wilayah_3t' => $viewdRumah->wilayah_3t,
                        'ids_kecamatan' => $viewdRumah->ids_kecamatan,
                        'id_salam_kecamatan' => $viewdRumah->id_salam_kecamatan,
                        'kecamatan' => $viewdRumah->kecamatan,
                        'ids_kelurahan' => $viewdRumah->ids_kelurahan,
                        'kelurahan' => $viewdRumah->kelurahan,
                        'dusun' => $viewdRumah->dusun,
                        'rw' => $viewdRumah->rw,
                        'rt' => $viewdRumah->rt,
                        'jalan' => $viewdRumah->jalan,
                        'kode_pos' => $viewdRumah->kode_pos,
                        'ids_tanggungan' => $viewdRumah->ids_tanggungan,
                        'tanggungan' => $viewdRumah->tanggungan,
                        'nilai_tanggungan' => $viewdRumah->nilai_tanggungan,
                        'ids_rekening_listrik' => $viewdRumah->ids_rekening_listrik,
                        'rekening_listrik' => $viewdRumah->rekening_listrik,
                        'nilai_rekening_listrik' => $viewdRumah->nilai_rekening_listrik,
                        'ids_daya_listrik' => $viewdRumah->ids_daya_listrik,
                        'daya_listrik' => $viewdRumah->daya_listrik,
                        'nilai_daya_listrik' => $viewdRumah->nilai_daya_listrik,
                        'ids_rekening_pbb' => $viewdRumah->ids_rekening_pbb,
                        'rekening_pbb' => $viewdRumah->rekening_pbb,
                        'nilai_rekening_pbb' => $viewdRumah->nilai_rekening_pbb,
                        'ids_pembayaran_pbb' => $viewdRumah->ids_pembayaran_pbb,
                        'pembayaran_pbb' => $viewdRumah->pembayaran_pbb,
                        'nilai_pembayaran_pbb' => $viewdRumah->nilai_pembayaran_pbb
                    ),
                    'sekolah' => array(
                        'nisn' => $viewdSekolah->nisn,
                        'ids_jenis_sekolah' => $viewdSekolah->ids_jenis_sekolah,
                        'jenis_sekolah' => $viewdSekolah->jenis_sekolah,
                        'ids_jurusan_sekolah' => $viewdSekolah->ids_jurusan_sekolah,
                        'jurusan_sekolah' => $viewdSekolah->jurusan_sekolah,
                        'nama_sekolah' => $viewdSekolah->nama_sekolah,
                        'akreditasi_sekolah' => $viewdSekolah->akreditasi_sekolah,
                        'ids_rumpun' => $viewdSekolah->ids_rumpun,
                        'rumpun' => $viewdSekolah->rumpun,
                    ),
                    'ukt' => array(
                        'score' => $viewdUKT->score,
                        'kategori' => $viewdUKT->kategori,
                        'jumlah' => $viewdUKT->jumlah,
                    ),
                    'file' => $viewdFile
                );
            }

            $this->response(array(
                'code' => 200,
                'status' => 'success',
                'message' => '',
                'data' => $data,
                'pagination' => $this->utilities->getPagination($num_rows->total, $resPerPage, $currentPage),
            ), 200);
        } else {
            $this->response(array(
                'code' => 401,
                'status' => 'error',
                'message' => 'data tidak ditemukan.'
            ), 401);
        }
    }

    // Full Biodata - Integrasi Salam
    function UKT_get()
    {
        // Cek apakah Authorization header ada, jika tidak gunakan Token
        $header = $this->head();

        // Cek apakah Authorization header ada, jika tidak gunakan Token
        if (isset($header['Authorization'])) {
            $token = explode(' ', $header['Authorization']);
        } elseif (isset($header['Token'])) {
            $token = explode(' ', $header['Token']);
        } else {
            $this->response(array(
                'code' => 401,
                'status' => 'error',
                'message' => 'Token tidak ditemukan.'
            ), 401);
        }

        // Cek validitas token
        if (isset($token[1])) {
            $cek = $this->master->CekToken($token[1]);
        } else {
            $this->response(array(
                'code' => 401,
                'status' => 'error',
                'message' => 'Token tidak valid.'
            ), 401);
        }

        if ($cek->code !== 200) {
            $this->response(array(
                'code' => 401,
                'status' => 'error',
                'message' => 'Token tidak ada akses.'
            ), 401);
        }

        // Jika token sesuai dengan MASTER_TOKEN
        if ($token[1] !== $_ENV['MASTER_TOKEN']) {
            $this->response(array(
                'code' => 401,
                'status' => 'error',
                'message' => 'Token tidak ada akses.'
            ), 401);
        }

        // Inisialisasi variabel filter
        $where = array(
            'tahun' => $this->get('tahun') ?? date('Y')
        );

        $filterFields = [
            'nomor_peserta',
            'nim',
            'ids_fakultas',
            'kode_jurusan',
            'ids_jalur_masuk',
            'daftar',
            'submit',
            'pembayaran',
            'pemberkasan'
        ];

        // Menambahkan kondisi ke array where jika ada
        foreach ($filterFields as $field) {
            $value = $this->get($field);
            if ($value !== null) {
                $where[$field] = $value;
            }
        }

        // Pagging
        $resPerPage = intval($this->get('limit') ?? $_ENV['LIMIT_DATA']);
        $currentPage = max(1, intval($this->get('page') ?? 1));
        $offset = ($currentPage - 1) * $resPerPage;

        // Query untuk mendapatkan data
        $rules = array(
            'database'  => null, // Default database master
            'select'    => null,
            'where'     => $where,
            'limit'     => array(
                'awal' => $offset,
                'akhir' => $resPerPage
            ),
            'group_by'  => null
        );

        $viewdUKT = $this->Viewd_ukt->search($rules);

        // Query untuk mendapatkan total data (pagination)
        $rules['select'] = 'COUNT(idd_kelulusan) as total';
        unset($rules['limit'], $rules['group_by']);
        $num_rows = $this->Viewd_ukt->search($rules)->row();

        // Mengirimkan response berdasarkan hasil pencarian
        if ($viewdUKT->num_rows() > 0) {
            $this->response(array(
                'code' => 200,
                'status' => 'success',
                'message' => '',
                'data' => ($viewdUKT->num_rows() > 1) ? $viewdUKT->result() : $viewdUKT->row(),
                'pagination' => $this->utilities->getPagination($num_rows->total, $resPerPage, $currentPage),
            ), 200);
        } else {
            $this->response(array(
                'code' => 404,
                'status' => 'error',
                'message' => 'Data tidak ditemukan.'
            ), 404);
        }
    }
}
