<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Mpdf\Mpdf;

class ABHP extends CI_Controller
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

        $this->load->model('Mandiri/Viewp_formulir');
        $this->load->model('Mandiri/Viewp_biodata');
        $this->load->model('Mandiri/Viewp_jadwal');
        $this->load->model('Mandiri/Viewp_pilihan');
        $this->load->model('Mandiri/Tbp_file');
        $this->load->model('Settings/Tbs_program');
        $this->load->model('Settings/Tbs_tipe_ujian');
        $this->load->model('Settings/Views_jadwal');
        $dir = './upload/tmp/';
        if (!file_exists($dir)) {
            mkdir($dir, 0777, true);
        }
    }

    function index()
    {
        $hak_akses = $this->master->read('hak-akses/?ids_level=' . $this->jwt->ids_level . '&ids_modul=64&aksi_hak_akses=export');
        if ($hak_akses->code == 200) {
            $rules = array(
                'database'  => null, //Default database master
                'select'    => 'YEAR(date_created) as tahun', // not null
                'where'     => array(
                    'status' => 'YA'
                ),
                'or_where'  => null,
                'like'      => null,
                'or_like'   => null,
                'order'     => null,
                'group_by'  => null,
            );
            $data = array(
                'title' => 'Export ABHP | ' . $_ENV['APPLICATION_NAME'],
                'content' => 'Export/Mandiri/abhp/content',
                'css' => 'Export/Mandiri/abhp/css',
                'javascript' => 'Export/Mandiri/abhp/javascript',
                'modal' => 'Export/Mandiri/abhp/modal',
                'tahun' => $this->Views_jadwal->distinct($rules)->result(),
            );
            $this->load->view('index', $data);
        } else {
            $this->session->set_flashdata('message', 'Hak akses ditolak.');
            $this->session->set_flashdata('type_message', 'danger');
            redirect('dashboard/');
        }
    }

    function getTanggal()
    {
        $where = $data = array();
        if (!empty($this->input->post('tahun'))) {
            $where['YEAR(tanggal)'] = $this->input->post('tahun');
        }if (!empty($this->input->post('ids_tipe_ujian'))) {
            $where['ids_tipe_ujian'] = $this->input->post('ids_tipe_ujian');
        }
        $where['status'] = 'YA';

        $rules = array(
            'database'  => null, //Default database master
            'select'    => 'tanggal', // not null
            'where'     => $where,
            'or_where'  => null,
            'like'      => null,
            'or_like'   => null,
            'order'     => 'tanggal ASC',
            'group_by'  => null,
        );
        $viewsJadwal = $this->Views_jadwal->distinct($rules);
        if ($viewsJadwal->num_rows() > 0) {
            $viewsJadwal = $viewsJadwal->result();
            foreach ($viewsJadwal as $a) {
                $data[] = array(
                    'tahun' => $this->input->post('tahun'),
                    'tanggal' => $a->tanggal
                );
            }
            $response = array(
                'status' => 200,
                'message' => 'Berhasil',
                'data' => $data
            );
        } else {
            $response = array(
                'status' => 400,
                'message' => 'Data kosong.'
            );
        }
        echo json_encode($response);
    }

    function getJam()
    {
        $where = $data = array();
        if (!empty($this->input->post('tanggal'))) {
            $where['tanggal'] = $this->input->post('tanggal');
        }
        if (!empty($this->input->post('tahun'))) {
            $where['YEAR(date_created)'] = $this->input->post('tahun');
        }
        $where['status'] = 'YA';

        $rules = array(
            'database'  => null, //Default database master
            'select'    => 'jam_awal, jam_akhir', // not null
            'where'     => $where,
            'or_where'  => null,
            'like'      => null,
            'or_like'   => null,
            'order'     => null,
            'group_by'  => null,
        );
        $viewsJadwal = $this->Views_jadwal->distinct($rules);
        if ($viewsJadwal->num_rows() > 0) {
            $viewsJadwal = $viewsJadwal->result();
            foreach ($viewsJadwal as $a) {
                $data[] = array(
                    'tanggal' => $this->input->post('tanggal'),
                    'jam_awal' => $a->jam_awal,
                    'jam_akhir' => $a->jam_akhir,
                );
            }
            $response = array(
                'status' => 200,
                'message' => 'Berhasil',
                'data' => $data
            );
        } else {
            $response = array(
                'status' => 400,
                'message' => 'Data kosong.'
            );
        }
        echo json_encode($response);
    }

    function getProgram()
    {
        $where = $data = array();
        if (!empty($this->input->post('tahun'))) {
            $where['YEAR(tanggal)'] = $this->input->post('tahun');
        }
        if (!empty($this->input->post('tanggal'))) {
            $where['tanggal'] = $this->input->post('tanggal');
        }
        if (!empty($this->input->post('jam'))) {
            $jam = explode(',', $this->input->post('jam'));
            $where['jam_awal'] = $jam[0];
            $where['jam_akhir'] = $jam[1];
        }
        $where['status'] = 'YA';

        $rules = array(
            'database'  => null, //Default database master
            'select'    => 'ids_program, program', // not null
            'where'     => $where,
            'or_where'  => null,
            'like'      => null,
            'or_like'   => null,
            'order'     => 'ids_program ASC',
            'group_by'  => null,
        );
        $viewsJadwal = $this->Views_jadwal->distinct($rules);
        if ($viewsJadwal->num_rows() > 0) {
            $response = array(
                'status' => 200,
                'message' => 'Berhasil',
                'data' => $viewsJadwal->result()
            );
        } else {
            $response = array(
                'status' => 400,
                'message' => 'Data kosong.'
            );
        }
        echo json_encode($response);
    }

    function getTipeUjian()
    {
        $where = $data = array();
        if (!empty($this->input->post('tahun'))) {
            $where['YEAR(tanggal)'] = $this->input->post('tahun');
        }
        if (!empty($this->input->post('tanggal'))) {
            $where['tanggal'] = $this->input->post('tanggal');
        }
        if (!empty($this->input->post('jam'))) {
            $jam = explode(',', $this->input->post('jam'));
            $where['jam_awal'] = $jam[0];
            $where['jam_akhir'] = $jam[1];
        }
        if (!empty($this->input->post('ids_program'))) {
            $where['ids_program'] = $this->input->post('ids_program');
        }
        $where['status'] = 'YA';

        $rules = array(
            'database'  => null, //Default database master
            'select'    => 'ids_tipe_ujian, tipe_ujian', // not null
            'where'     => $where,
            'or_where'  => null,
            'like'      => null,
            'or_like'   => null,
            'order'     => 'ids_tipe_ujian ASC',
            'group_by'  => null,
        );
        $viewsJadwal = $this->Views_jadwal->distinct($rules);
        if ($viewsJadwal->num_rows() > 0) {
            $response = array(
                'status' => 200,
                'message' => 'Berhasil',
                'data' => $viewsJadwal->result()
            );
        } else {
            $response = array(
                'status' => 400,
                'message' => 'Data kosong.'
            );
        }
        echo json_encode($response);
    }

    function getArea()
    {
        $where = $data = array();
        if (!empty($this->input->post('tahun'))) {
            $where['YEAR(tanggal)'] = $this->input->post('tahun');
        }
        if (!empty($this->input->post('tanggal'))) {
            $where['tanggal'] = $this->input->post('tanggal');
        }
        if (!empty($this->input->post('jam'))) {
            $jam = explode(',', $this->input->post('jam'));
            $where['jam_awal'] = $jam[0];
            $where['jam_akhir'] = $jam[1];
        }
        if (!empty($this->input->post('ids_program'))) {
            $where['ids_program'] = $this->input->post('ids_program');
        }
        if (!empty($this->input->post('ids_tipe_ujian'))) {
            $where['ids_tipe_ujian'] = $this->input->post('ids_tipe_ujian');
        }
        $where['status'] = 'YA';

        $rules = array(
            'database'  => null, //Default database master
            'select'    => 'ids_area, area', // not null
            'where'     => $where,
            'or_where'  => null,
            'like'      => null,
            'or_like'   => null,
            'order'     => 'area ASC',
            'group_by'  => null,
        );
        $viewsJadwal = $this->Views_jadwal->distinct($rules);
        if ($viewsJadwal->num_rows() > 0) {
            $response = array(
                'status' => 200,
                'message' => 'Berhasil',
                'data' => $viewsJadwal->result()
            );
        } else {
            $response = array(
                'status' => 400,
                'message' => 'Data kosong.'
            );
        }
        echo json_encode($response);
    }

    function getGedung()
    {
        $where = $data = array();
        if (!empty($this->input->post('tahun'))) {
            $where['YEAR(tanggal)'] = $this->input->post('tahun');
        }
        if (!empty($this->input->post('tanggal'))) {
            $where['tanggal'] = $this->input->post('tanggal');
        }
        if (!empty($this->input->post('jam'))) {
            $jam = explode(',', $this->input->post('jam'));
            $where['jam_awal'] = $jam[0];
            $where['jam_akhir'] = $jam[1];
        }
        if (!empty($this->input->post('ids_program'))) {
            $where['ids_program'] = $this->input->post('ids_program');
        }
        if (!empty($this->input->post('ids_tipe_ujian'))) {
            $where['ids_tipe_ujian'] = $this->input->post('ids_tipe_ujian');
        }
        if (!empty($this->input->post('ids_area'))) {
            $where['ids_area'] = $this->input->post('ids_area');
        }
        $where['status'] = 'YA';

        $rules = array(
            'database'  => null, //Default database master
            'select'    => 'ids_gedung, gedung', // not null
            'where'     => $where,
            'or_where'  => null,
            'like'      => null,
            'or_like'   => null,
            'order'     => 'gedung ASC',
            'group_by'  => null,
        );
        $viewsJadwal = $this->Views_jadwal->distinct($rules);
        if ($viewsJadwal->num_rows() > 0) {
            $response = array(
                'status' => 200,
                'message' => 'Berhasil',
                'data' => $viewsJadwal->result()
            );
        } else {
            $response = array(
                'status' => 400,
                'message' => 'Data kosong.'
            );
        }
        echo json_encode($response);
    }

    function getRuangan()
    {
        $where = $data = array();
        if (!empty($this->input->post('tahun'))) {
            $where['YEAR(tanggal)'] = $this->input->post('tahun');
        }
        if (!empty($this->input->post('tanggal'))) {
            $where['tanggal'] = $this->input->post('tanggal');
        }
        if (!empty($this->input->post('jam'))) {
            $jam = explode(',', $this->input->post('jam'));
            $where['jam_awal'] = $jam[0];
            $where['jam_akhir'] = $jam[1];
        }
        if (!empty($this->input->post('ids_program'))) {
            $where['ids_program'] = $this->input->post('ids_program');
        }
        if (!empty($this->input->post('ids_tipe_ujian'))) {
            $where['ids_tipe_ujian'] = $this->input->post('ids_tipe_ujian');
        }
        if (!empty($this->input->post('ids_area'))) {
            $where['ids_area'] = $this->input->post('ids_area');
        }
        if (!empty($this->input->post('ids_gedung'))) {
            $where['ids_gedung'] = $this->input->post('ids_gedung');
        }
        $where['status'] = 'YA';

        $rules = array(
            'database'  => null, //Default database master
            'select'    => 'ids_ruangan, ruangan', // not null
            'where'     => $where,
            'or_where'  => null,
            'like'      => null,
            'or_like'   => null,
            'order'     => 'ruangan ASC',
            'group_by'  => null,
        );
        $viewsJadwal = $this->Views_jadwal->distinct($rules);
        if ($viewsJadwal->num_rows() > 0) {
            $response = array(
                'status' => 200,
                'message' => 'Berhasil',
                'data' => $viewsJadwal->result()
            );
        } else {
            $response = array(
                'status' => 400,
                'message' => 'Data kosong.'
            );
        }
        echo json_encode($response);
    }

    function Export()
    {
        $hak_akses = $this->master->read('hak-akses/?ids_level=' . $this->jwt->ids_level . '&ids_modul=64&aksi_hak_akses=export');
        if ($hak_akses->code == 200) {
            $tahun = $this->input->post('tahun');
            $tanggal = $this->input->post('tanggal');
            $jam = explode(',', $this->input->post('jam'));
            $ids_program = $this->input->post('ids_program');
            $ids_tipe_ujian = $this->input->post('ids_tipe_ujian');
            $ids_area = $this->input->post('ids_area');
            $ids_gedung = $this->input->post('ids_gedung');
            $ids_ruangan = $this->input->post('ids_ruangan');

            $rules = array(
                'database'  => null, //Default database master
                'select'    => null,
                'where'     => array(
                    'YEAR(tanggal)' => $tahun,
                    'tanggal' => $tanggal,
                    'jam_awal' => $jam[0],
                    'jam_akhir' => $jam[1],
                    'ids_program' => $ids_program,
                    'ids_tipe_ujian' => $ids_tipe_ujian,
                    'ids_area' => $ids_area,
                    'ids_gedung' => $ids_gedung,
                    'ids_ruangan' => $ids_ruangan,
                ),
                'or_where'  => null,
                'like'      => null,
                'or_like'   => null,
                'order'     => null,
                'limit'     => null,
                'group_by'  => null,
            );
            $viewsJadwal = $this->Views_jadwal->search($rules)->row();

            $rules = array(
                'database'  => null, //Default database master
                'select'    => null,
                'where'     => array(
                    'ids_jadwal' => $viewsJadwal->ids_jadwal,
                ),
                'or_where'  => null,
                'like'      => null,
                'or_like'   => null,
                'order'     => null,
                'limit'     => null,
                'group_by'  => null,
            );
            $viewpJadwal = $this->Viewp_jadwal->search($rules)->result();
            foreach ($viewpJadwal as $a) {
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
                $tbpFileFoto[$a->idp_formulir] = $this->Tbp_file->search($rules)->row();
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
                $viewpPilihan[$a->idp_formulir] = $this->Viewp_pilihan->search($rules)->result();
            }

            $data = array(
                'viewsJadwal' => $viewsJadwal,
                'viewpJadwal' => $viewpJadwal,
                'tbpFileFoto' => $tbpFileFoto,
                'viewpPilihan' => $viewpPilihan
            );

            $this->load->view('Export/Mandiri/pdf/abhp', $data);
        } else {
            $this->session->set_flashdata('message', 'Hak akses ditolak.');
            $this->session->set_flashdata('type_message', 'danger');
            redirect('dashboard/');
        }
    }
}
