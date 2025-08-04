<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Penetapan extends CI_Controller
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

        $this->load->model('Daftar/Tbd_ukt');
        $this->load->model('Settings/Tbs_bobot_range_ukt');
        $this->load->model('Settings/Tbs_bobot_nilai_ukt');
        $this->load->model('Daftar/Viewd_kelulusan');
        $this->load->model('Daftar/Viewd_mahasiswa');
        $this->load->model('Daftar/Viewd_orangtua');
        $this->load->model('Daftar/Viewd_rumah');
        $this->load->model('Daftar/Viewd_ukt');
    }

    function index()
    {
        $hak_akses = $this->master->read('hak-akses/?ids_level=' . $this->jwt->ids_level . '&ids_modul=55&aksi_hak_akses=read');
        if ($hak_akses->code == 200) {
            $rules = array(
                'database'      => null, //Default database master
                'select'        => "tahun as tahun", // not null
                'where'         => null,
                'or_where'      => null,
                'like'          => null,
                'or_like'       => null,
                'order'         => 'tahun DESC',
                'group_by'      => null,
            );
            $data = array(
                'title'         => 'Penetapan UKT | ' . $_ENV['APPLICATION_NAME'],
                'content'       => 'ukt/penetapan/content',
                'css'           => 'ukt/penetapan/css',
                'javascript'    => 'ukt/penetapan/javascript',
                'modal'         => 'ukt/penetapan/modal',
                'tbsJalurMasuk' => $this->master->read('jalur-masuk/?status=YA'),
                'tahun'         => $this->Viewd_mahasiswa->distinct($rules)->result(),
            );
            $this->load->view('index', $data);
        } else {
            $this->session->set_flashdata('message', 'Hak akses ditolak.');
            $this->session->set_flashdata('type_message', 'danger');
            redirect('dashboard/');
        }
    }

    function SWA()
    {
        $response = null;
        $hak_akses = $this->master->read('hak-akses/?ids_level=' . $this->jwt->ids_level . '&ids_modul=55&aksi_hak_akses=create');

        if ($hak_akses->code != 200) {
            echo json_encode([
                'status' => 400,
                'message' => 'Hak akses ditolak.'
            ]);
            return;
        }

        $this->_validate();
        $ids_jalur_masuk = $this->input->post('ids_jalur_masuk');
        $tahun = $this->input->post('tahun');

        $common_bobot_where = [
            'ids_jalur_masuk' => $ids_jalur_masuk,
            'tahun' => $tahun
        ];

        $bobot_fields = [
            'nilai_daya_listrik',
            'nilai_kepemilikan_mobil',
            'nilai_kepemilikan_motor',
            'nilai_kepemilikan_rumah',
            'nilai_lktl',
            'nilai_njop',
            'nilai_pajak_mobil',
            'nilai_pajak_motor',
            'nilai_penghasilan_ayah',
            'nilai_penghasilan_ibu',
            'nilai_penghasilan_wali',
            'nilai_rekening_listrik',
            'nilai_sktm',
            'nilai_tanggungan'
        ];

        $bobot_values = [];
        foreach ($bobot_fields as $field) {
            $rules = ['where' => array_merge(['nama_field' => $field], $common_bobot_where)];
            $result = $this->Tbs_bobot_nilai_ukt->search($rules)->row();
            $bobot_values[$field] = $result ?: (object)['nilai_max' => 0, 'bobot' => 0];
        }

        $kategori_list = ['K1', 'K2', 'K3', 'K4', 'K5', 'K6', 'K7'];
        $kategori_ranges = [];
        foreach ($kategori_list as $kategori) {
            $rules = ['where' => array_merge(['kategori' => $kategori], $common_bobot_where)];
            $result = $this->Tbs_bobot_range_ukt->search($rules)->row();
            $kategori_ranges[$kategori] = $result ?: (object)['nilai_min' => 0, 'nilai_max' => 0];
        }

        $kelulusan_rules = [
            'where' => ['tahun' => $tahun, 'ids_jalur_masuk' => $ids_jalur_masuk]
        ];
        $kelulusan = $this->Viewd_kelulusan->search($kelulusan_rules)->result();

        $data_insert = [];
        $data_update = [];
        $error = 0;

        foreach ($kelulusan as $value) {
            if (!is_object($value)) {
                $error++;
                continue;
            }

            $score = 0;
            $kategori = "K7";

            $ukt_rules = ['where' => ['nomor_peserta' => $value->nomor_peserta ?? '']];
            $ukt = $this->Viewd_ukt->search($ukt_rules);

            if (($value->jenjang ?? '') == "S1" && ($value->submit ?? '') == "SUDAH") {
                $mahasiswa = $this->Viewd_mahasiswa->search($ukt_rules)->row();
                $orangtua = $this->Viewd_orangtua->search($ukt_rules)->result();
                $rumah = $this->Viewd_rumah->search($ukt_rules)->row();

                $components = [
                    'daya_listrik' => $rumah->nilai_daya_listrik ?? 0,
                    'kepemilikan_mobil' => $rumah->nilai_kepemilikan_mobil ?? 0,
                    'kepemilikan_motor' => $rumah->nilai_kepemilikan_motor ?? 0,
                    'kepemilikan_rumah' => $rumah->nilai_kepemilikan_rumah ?? 0,
                    'lktl' => $rumah->nilai_lktl ?? 0,
                    'njop' => $rumah->nilai_njop ?? 0,
                    'pajak_mobil' => $rumah->nilai_pajak_mobil ?? 0,
                    'pajak_motor' => $rumah->nilai_pajak_motor ?? 0,
                    'rekening_listrik' => $rumah->nilai_rekening_listrik ?? 0,
                    'sktm' => $mahasiswa->nilai_sktm ?? 0,
                    'tanggungan' => $rumah->nilai_tanggungan ?? 0,
                ];

                $scores = [];
                foreach ($components as $key => $val) {
                    $field = "nilai_$key";
                    $scores[$key] = ($val != 0 && isset($bobot_values[$field])) ? ($val / $bobot_values[$field]->nilai_max) * $bobot_values[$field]->bobot : 0;
                }

                $penghasilan_scores = ['ayah' => 0, 'ibu' => 0, 'wali' => 0];
                foreach ($orangtua as $a) {
                    $role = strtolower($a->orangtua ?? '');
                    $field = "nilai_penghasilan_$role";
                    if (isset($a->nilai_penghasilan) && $a->nilai_penghasilan != 0 && isset($bobot_values[$field])) {
                        $penghasilan_scores[$role] = ($a->nilai_penghasilan / $bobot_values[$field]->nilai_max) * $bobot_values[$field]->bobot;
                    }
                }

                $score = array_sum($scores) + array_sum($penghasilan_scores);

                foreach ($kategori_ranges as $cat => $range) {
                    if ($score > $range->nilai_min && $score <= $range->nilai_max) {
                        $kategori = $cat;
                        break;
                    }
                }
            } else {
                $score = 1;
            }

            if (empty($value->kode_jurusan)) {
                $error++;
                continue;
            }

            $tbsUKT = $this->master->read("ukt/?kode_jurusan=" . ($value->kode_jurusan ?? '') . "&kategori=$kategori");
            if ($tbsUKT->code != 200 || empty($tbsUKT->data->data)) {
                $error++;
                continue;
            }

            $ukt_data = json_decode(json_encode($tbsUKT->data->data[0]), true);
            $jumlah = $this->determineUktAmount($value->ids_jalur_masuk ?? 0, $ukt_data);

            if ($ukt->num_rows() == 0) {
                $data_insert[] = [
                    'idd_kelulusan' => $value->idd_kelulusan ?? 0,
                    'score' => $score,
                    'kategori' => $kategori,
                    'jumlah' => $jumlah,
                    'potongan' => 0,
                    'created_by' => $this->jwt->ids_user,
                    'updated_by' => $this->jwt->ids_user,
                ];
            } else {
                $ukt_row = $ukt->row();
                $data_update[] = [
                    'idd_ukt' => $ukt_row->idd_ukt ?? 0,
                    'score' => $score,
                    'kategori' => $kategori,
                    'jumlah' => $jumlah,
                    'updated_by' => $this->jwt->ids_user,
                ];
            }
        }

        $created = $updated = 0;
        if (!empty($data_insert)) {
            $res_insert = $this->Tbd_ukt->create_bulk($data_insert);
            $created = $res_insert['status'] ? 0 : $res_insert['inserted_rows'];
        }

        if (!empty($data_update)) {
            $res_update = $this->Tbd_ukt->update_bulk($data_update, 'idd_ukt');
            $updated = $res_update['status'] ? 0 : $res_update['updated_rows'];
        }

        echo json_encode([
            'status' => 200,
            'message' => "Penetapan data berhasil. Created: $created. Updated: $updated. Error: $error",
            'error' => $error
        ]);
    }

    function SWA_OLD()
    {
        $response = null;
        $hak_akses = $this->master->read('hak-akses/?ids_level=' . $this->jwt->ids_level . '&ids_modul=55&aksi_hak_akses=create');

        if ($hak_akses->code != 200) {
            echo json_encode([
                'status' => 400,
                'message' => 'Hak akses ditolak.'
            ]);
            return;
        }

        $this->_validate();
        $ids_jalur_masuk = $this->input->post('ids_jalur_masuk');
        $tahun = $this->input->post('tahun');

        // Common where clause for bobot nilai queries
        $common_bobot_where = [
            'ids_jalur_masuk' => $ids_jalur_masuk,
            'tahun' => $tahun
        ];

        // Get all bobot nilai in a single query
        $bobot_fields = [
            'nilai_daya_listrik',
            'nilai_kepemilikan_mobil',
            'nilai_kepemilikan_motor',
            'nilai_kepemilikan_rumah',
            'nilai_lktl',
            'nilai_njop',
            'nilai_pajak_mobil',
            'nilai_pajak_motor',
            'nilai_penghasilan_ayah',
            'nilai_penghasilan_ibu',
            'nilai_penghasilan_wali',
            'nilai_rekening_listrik',
            'nilai_sktm',
            'nilai_tanggungan'
        ];

        $bobot_values = [];
        foreach ($bobot_fields as $field) {
            $rules = [
                'database' => null,
                'select' => null,
                'where' => array_merge(['nama_field' => $field], $common_bobot_where),
                'or_where' => null,
                'like' => null,
                'or_like' => null,
                'order' => null,
                'limit' => null,
                'group_by' => null,
            ];
            $result = $this->Tbs_bobot_nilai_ukt->search($rules)->row();
            $bobot_values[$field] = $result ?: (object)['nilai_max' => 0, 'bobot' => 0];
        }

        // Get all range UKT categories in a single query
        $kategori_ranges = [];
        $kategori_list = ['K1', 'K2', 'K3', 'K4', 'K5', 'K6', 'K7'];

        foreach ($kategori_list as $kategori) {
            $rules = [
                'database' => null,
                'select' => null,
                'where' => array_merge(['kategori' => $kategori], $common_bobot_where),
                'or_where' => null,
                'like' => null,
                'or_like' => null,
                'order' => null,
                'limit' => null,
                'group_by' => null,
            ];
            $result = $this->Tbs_bobot_range_ukt->search($rules)->row();
            $kategori_ranges[$kategori] = $result ?: (object)['nilai_min' => 0, 'nilai_max' => 0];
        }

        // Get kelulusan data
        $kelulusan_rules = [
            'database' => null,
            'select' => null,
            'where' => [
                'tahun' => $tahun,
                'ids_jalur_masuk' => $ids_jalur_masuk,
            ],
            'or_where' => null,
            'like' => null,
            'or_like' => null,
            'order' => null,
            'limit' => null,
            'group_by' => null,
        ];
        $kelulusan = $this->Viewd_kelulusan->search($kelulusan_rules)->result();

        $created = $updated = $error = 0;

        foreach ($kelulusan as $value) {
            if (!is_object($value)) {
                $error++;
                continue;
            }

            $score = 0;
            $kategori = "K7";

            $ukt_rules = [
                'database' => null,
                'select' => null,
                'where' => ['nomor_peserta' => $value->nomor_peserta ?? ''],
                'or_where' => null,
                'like' => null,
                'or_like' => null,
                'order' => null,
                'limit' => null,
                'group_by' => null,
            ];
            $ukt = $this->Viewd_ukt->search($ukt_rules);

            if (($value->jenjang ?? '') == "S1" && ($value->submit ?? '') == "SUDAH") {
                $mahasiswa = $this->Viewd_mahasiswa->search($ukt_rules)->row();
                $orangtua = $this->Viewd_orangtua->search($ukt_rules)->result();
                $rumah = $this->Viewd_rumah->search($ukt_rules)->row();

                // Calculate scores for each component
                $scores = [];
                $components = [
                    'daya_listrik' => $rumah->nilai_daya_listrik ?? 0,
                    'kepemilikan_mobil' => $rumah->nilai_kepemilikan_mobil ?? 0,
                    'kepemilikan_motor' => $rumah->nilai_kepemilikan_motor ?? 0,
                    'kepemilikan_rumah' => $rumah->nilai_kepemilikan_rumah ?? 0,
                    'lktl' => $rumah->nilai_lktl ?? 0,
                    'njop' => $rumah->nilai_njop ?? 0,
                    'pajak_mobil' => $rumah->nilai_pajak_mobil ?? 0,
                    'pajak_motor' => $rumah->nilai_pajak_motor ?? 0,
                    'rekening_listrik' => $rumah->nilai_rekening_listrik ?? 0,
                    'sktm' => $mahasiswa->nilai_sktm ?? 0,
                    'tanggungan' => $rumah->nilai_tanggungan ?? 0,
                ];

                foreach ($components as $key => $val) {
                    $field = "nilai_$key";
                    if ($val != 0 && isset($bobot_values[$field])) {
                        $scores[$key] = ($val / $bobot_values[$field]->nilai_max) * $bobot_values[$field]->bobot;
                    } else {
                        $scores[$key] = 0;
                    }
                }

                // Calculate penghasilan scores
                $penghasilan_scores = [
                    'ayah' => 0,
                    'ibu' => 0,
                    'wali' => 0
                ];

                foreach ($orangtua as $a) {
                    if (!is_object($a)) continue;

                    $role = strtolower($a->orangtua ?? '');
                    $field = "nilai_penghasilan_$role";
                    if (isset($a->nilai_penghasilan) && $a->nilai_penghasilan != 0 && isset($bobot_values[$field])) {
                        $penghasilan_scores[$role] = ($a->nilai_penghasilan / $bobot_values[$field]->nilai_max) * $bobot_values[$field]->bobot;
                    }
                }

                // Calculate total score
                $score = array_sum($scores) + array_sum($penghasilan_scores);

                // Determine UKT category based on score
                foreach ($kategori_ranges as $cat => $range) {
                    if ($score > $range->nilai_min && $score <= $range->nilai_max) {
                        $kategori = $cat;
                        break;
                    }
                }
            } else {
                $score = 1;
            }

            // Skip if required properties are missing
            if (empty($value->kode_jurusan)) {
                $error++;
                continue;
            }

            // Get UKT amount based on category and jurusan
            $tbsUKT = $this->master->read("ukt/?kode_jurusan=" . ($value->kode_jurusan ?? '') . "&kategori=$kategori");

            if ($tbsUKT->code != 200 || empty($tbsUKT->data->data)) {
                $error++;
                continue;
            }

            $ukt_data = json_decode(json_encode($tbsUKT->data->data[0]), true);
            $jumlah = $this->determineUktAmount($value->ids_jalur_masuk ?? 0, $ukt_data);

            if ($ukt->num_rows() == 0) {
                $rules = [
                    'idd_kelulusan' => $value->idd_kelulusan ?? 0,
                    'score' => $score,
                    'kategori' => $kategori,
                    'jumlah' => $jumlah,
                    'potongan' => 0,
                    'created_by' => $this->jwt->ids_user,
                    'updated_by' => $this->jwt->ids_user,
                ];
                $fb = $this->Tbd_ukt->create($rules);
                $fb['status'] ? $error++ : $created++;
            } else {
                $ukt = $ukt->row();
                $rules = [
                    'where' => ['idd_ukt' => $ukt->idd_ukt ?? 0],
                    'or_where' => null,
                    'like' => null,
                    'or_like' => null,
                    'data' => [
                        'score' => $score,
                        'kategori' => $kategori,
                        'jumlah' => $jumlah,
                        'updated_by' => $this->jwt->ids_user,
                    ],
                ];
                $fb = $this->Tbd_ukt->update($rules);
                $fb['status'] ? $error++ : $updated++;
            }
        }

        echo json_encode([
            'status' => 200,
            'message' => "Penetapan data berhasil. Created: $created. Updated: $updated. Error: $error",
            'error' => $error
        ]);
    }

    private function determineUktAmount($jalur_masuk, $ukt_data)
    {
        switch ($jalur_masuk) {
            case 1:
            case 7:
                return $ukt_data['snbp'] ?? 0;
            case 2:
                return $ukt_data['spanptkin'] ?? 0;
            case 3:
            case 8:
                return $ukt_data['snbt'] ?? 0;
            case 4:
                return $ukt_data['umptkin'] ?? 0;
            default:
                return $ukt_data['mandiri'] ?? 0;
        }
    }

    /* function SWA()
    {
        $response = null;
        $hak_akses = $this->master->read('hak-akses/?ids_level=' . $this->jwt->ids_level . '&ids_modul=55&aksi_hak_akses=create');
        if ($hak_akses->code == 200) {
            $this->_validate();
            $ids_jalur_masuk = $this->input->post('ids_jalur_masuk');
            $tahun = $this->input->post('tahun');

            $rules = array(
                'database'  => null, //Default database master
                'select'    => null,
                'where'     => array(
                    'nama_field' => 'nilai_daya_listrik',
                    'ids_jalur_masuk' => $ids_jalur_masuk,
                    'tahun' => $tahun
                ),
                'or_where'  => null,
                'like'      => null,
                'or_like'   => null,
                'order'     => null,
                'limit'     => null,
                'group_by'  => null,
            );
            $nilai_daya_listrik = $this->Tbs_bobot_nilai_ukt->search($rules)->row();
            $rules = array(
                'database'  => null, //Default database master
                'select'    => null,
                'where'     => array(
                    'nama_field' => 'nilai_kepemilikan_mobil',
                    'ids_jalur_masuk' => $ids_jalur_masuk,
                    'tahun' => $tahun
                ),
                'or_where'  => null,
                'like'      => null,
                'or_like'   => null,
                'order'     => null,
                'limit'     => null,
                'group_by'  => null,
            );
            $nilai_kepemilikan_mobil = $this->Tbs_bobot_nilai_ukt->search($rules)->row();
            $rules = array(
                'database'  => null, //Default database master
                'select'    => null,
                'where'     => array(
                    'nama_field' => 'nilai_kepemilikan_motor',
                    'ids_jalur_masuk' => $ids_jalur_masuk,
                    'tahun' => $tahun
                ),
                'or_where'  => null,
                'like'      => null,
                'or_like'   => null,
                'order'     => null,
                'limit'     => null,
                'group_by'  => null,
            );
            $nilai_kepemilikan_motor = $this->Tbs_bobot_nilai_ukt->search($rules)->row();
            $rules = array(
                'database'  => null, //Default database master
                'select'    => null,
                'where'     => array(
                    'nama_field' => 'nilai_kepemilikan_rumah',
                    'ids_jalur_masuk' => $ids_jalur_masuk,
                    'tahun' => $tahun
                ),
                'or_where'  => null,
                'like'      => null,
                'or_like'   => null,
                'order'     => null,
                'limit'     => null,
                'group_by'  => null,
            );
            $nilai_kepemilikan_rumah = $this->Tbs_bobot_nilai_ukt->search($rules)->row();
            $rules = array(
                'database'  => null, //Default database master
                'select'    => null,
                'where'     => array(
                    'nama_field' => 'nilai_lktl',
                    'ids_jalur_masuk' => $ids_jalur_masuk,
                    'tahun' => $tahun
                ),
                'or_where'  => null,
                'like'      => null,
                'or_like'   => null,
                'order'     => null,
                'limit'     => null,
                'group_by'  => null,
            );
            $nilai_lktl = $this->Tbs_bobot_nilai_ukt->search($rules)->row();
            $rules = array(
                'database'  => null, //Default database master
                'select'    => null,
                'where'     => array(
                    'nama_field' => 'nilai_njop',
                    'ids_jalur_masuk' => $ids_jalur_masuk,
                    'tahun' => $tahun
                ),
                'or_where'  => null,
                'like'      => null,
                'or_like'   => null,
                'order'     => null,
                'limit'     => null,
                'group_by'  => null,
            );
            $nilai_njop = $this->Tbs_bobot_nilai_ukt->search($rules)->row();
            $rules = array(
                'database'  => null, //Default database master
                'select'    => null,
                'where'     => array(
                    'nama_field' => 'nilai_pajak_mobil',
                    'ids_jalur_masuk' => $ids_jalur_masuk,
                    'tahun' => $tahun
                ),
                'or_where'  => null,
                'like'      => null,
                'or_like'   => null,
                'order'     => null,
                'limit'     => null,
                'group_by'  => null,
            );
            $nilai_pajak_mobil = $this->Tbs_bobot_nilai_ukt->search($rules)->row();
            $rules = array(
                'database'  => null, //Default database master
                'select'    => null,
                'where'     => array(
                    'nama_field' => 'nilai_pajak_motor',
                    'ids_jalur_masuk' => $ids_jalur_masuk,
                    'tahun' => $tahun
                ),
                'or_where'  => null,
                'like'      => null,
                'or_like'   => null,
                'order'     => null,
                'limit'     => null,
                'group_by'  => null,
            );
            $nilai_pajak_motor = $this->Tbs_bobot_nilai_ukt->search($rules)->row();
            $rules = array(
                'database'  => null, //Default database master
                'select'    => null,
                'where'     => array(
                    'nama_field' => 'nilai_penghasilan_ayah',
                    'ids_jalur_masuk' => $ids_jalur_masuk,
                    'tahun' => $tahun
                ),
                'or_where'  => null,
                'like'      => null,
                'or_like'   => null,
                'order'     => null,
                'limit'     => null,
                'group_by'  => null,
            );
            $nilai_penghasilan_ayah = $this->Tbs_bobot_nilai_ukt->search($rules)->row();
            $rules = array(
                'database'  => null, //Default database master
                'select'    => null,
                'where'     => array(
                    'nama_field' => 'nilai_penghasilan_ibu',
                    'ids_jalur_masuk' => $ids_jalur_masuk,
                    'tahun' => $tahun
                ),
                'or_where'  => null,
                'like'      => null,
                'or_like'   => null,
                'order'     => null,
                'limit'     => null,
                'group_by'  => null,
            );
            $nilai_penghasilan_ibu = $this->Tbs_bobot_nilai_ukt->search($rules)->row();
            $rules = array(
                'database'  => null, //Default database master
                'select'    => null,
                'where'     => array(
                    'nama_field' => 'nilai_penghasilan_wali',
                    'ids_jalur_masuk' => $ids_jalur_masuk,
                    'tahun' => $tahun
                ),
                'or_where'  => null,
                'like'      => null,
                'or_like'   => null,
                'order'     => null,
                'limit'     => null,
                'group_by'  => null,
            );
            $nilai_penghasilan_wali = $this->Tbs_bobot_nilai_ukt->search($rules)->row();
            $rules = array(
                'database'  => null, //Default database master
                'select'    => null,
                'where'     => array(
                    'nama_field' => 'nilai_rekening_listrik',
                    'ids_jalur_masuk' => $ids_jalur_masuk,
                    'tahun' => $tahun
                ),
                'or_where'  => null,
                'like'      => null,
                'or_like'   => null,
                'order'     => null,
                'limit'     => null,
                'group_by'  => null,
            );
            $nilai_rekening_listrik = $this->Tbs_bobot_nilai_ukt->search($rules)->row();
            $rules = array(
                'database'  => null, //Default database master
                'select'    => null,
                'where'     => array(
                    'nama_field' => 'nilai_sktm',
                    'ids_jalur_masuk' => $ids_jalur_masuk,
                    'tahun' => $tahun
                ),
                'or_where'  => null,
                'like'      => null,
                'or_like'   => null,
                'order'     => null,
                'limit'     => null,
                'group_by'  => null,
            );
            $nilai_sktm = $this->Tbs_bobot_nilai_ukt->search($rules)->row();
            $rules = array(
                'database'  => null, //Default database master
                'select'    => null,
                'where'     => array(
                    'nama_field' => 'nilai_tanggungan',
                    'ids_jalur_masuk' => $ids_jalur_masuk,
                    'tahun' => $tahun
                ),
                'or_where'  => null,
                'like'      => null,
                'or_like'   => null,
                'order'     => null,
                'limit'     => null,
                'group_by'  => null,
            );
            $nilai_tanggungan = $this->Tbs_bobot_nilai_ukt->search($rules)->row();

            $rules = array(
                'database'  => null, //Default database master
                'select'    => null,
                'where'     => array(
                    'kategori' => 'K1',
                    'ids_jalur_masuk' => $ids_jalur_masuk,
                    'tahun' => $tahun
                ),
                'or_where'  => null,
                'like'      => null,
                'or_like'   => null,
                'order'     => null,
                'limit'     => null,
                'group_by'  => null,
            );
            $k1 = $this->Tbs_bobot_range_ukt->search($rules)->row();
            $rules = array(
                'database'  => null, //Default database master
                'select'    => null,
                'where'     => array(
                    'kategori' => 'K2',
                    'ids_jalur_masuk' => $ids_jalur_masuk,
                    'tahun' => $tahun
                ),
                'or_where'  => null,
                'like'      => null,
                'or_like'   => null,
                'order'     => null,
                'limit'     => null,
                'group_by'  => null,
            );
            $k2 = $this->Tbs_bobot_range_ukt->search($rules)->row();
            $rules = array(
                'database'  => null, //Default database master
                'select'    => null,
                'where'     => array(
                    'kategori' => 'K3',
                    'ids_jalur_masuk' => $ids_jalur_masuk,
                    'tahun' => $tahun
                ),
                'or_where'  => null,
                'like'      => null,
                'or_like'   => null,
                'order'     => null,
                'limit'     => null,
                'group_by'  => null,
            );
            $k3 = $this->Tbs_bobot_range_ukt->search($rules)->row();
            $rules = array(
                'database'  => null, //Default database master
                'select'    => null,
                'where'     => array(
                    'kategori' => 'K4',
                    'ids_jalur_masuk' => $ids_jalur_masuk,
                    'tahun' => $tahun
                ),
                'or_where'  => null,
                'like'      => null,
                'or_like'   => null,
                'order'     => null,
                'limit'     => null,
                'group_by'  => null,
            );
            $k4 = $this->Tbs_bobot_range_ukt->search($rules)->row();
            $rules = array(
                'database'  => null, //Default database master
                'select'    => null,
                'where'     => array(
                    'kategori' => 'K5',
                    'ids_jalur_masuk' => $ids_jalur_masuk,
                    'tahun' => $tahun
                ),
                'or_where'  => null,
                'like'      => null,
                'or_like'   => null,
                'order'     => null,
                'limit'     => null,
                'group_by'  => null,
            );
            $k5 = $this->Tbs_bobot_range_ukt->search($rules)->row();
            $rules = array(
                'database'  => null, //Default database master
                'select'    => null,
                'where'     => array(
                    'kategori' => 'K6',
                    'ids_jalur_masuk' => $ids_jalur_masuk,
                    'tahun' => $tahun
                ),
                'or_where'  => null,
                'like'      => null,
                'or_like'   => null,
                'order'     => null,
                'limit'     => null,
                'group_by'  => null,
            );
            $k6 = $this->Tbs_bobot_range_ukt->search($rules)->row();
            $rules = array(
                'database'  => null, //Default database master
                'select'    => null,
                'where'     => array(
                    'kategori' => 'K7',
                    'ids_jalur_masuk' => $ids_jalur_masuk,
                    'tahun' => $tahun
                ),
                'or_where'  => null,
                'like'      => null,
                'or_like'   => null,
                'order'     => null,
                'limit'     => null,
                'group_by'  => null,
            );
            $k7 = $this->Tbs_bobot_range_ukt->search($rules)->row();

            $rules = array(
                'database'  => null, //Default database master
                'select'    => null,
                'where'     => array(
                    'tahun' => $tahun,
                    'ids_jalur_masuk' => $ids_jalur_masuk,
                ),
                'or_where'  => null,
                'like'      => null,
                'or_like'   => null,
                'order'     => null,
                'limit'     => null,
                'group_by'  => null,
            );
            $kelulusan = $this->Viewd_kelulusan->search($rules)->result();
            $created = $updated = $error = 0;
            foreach ($kelulusan as $value) {
                $score = 0;
                $kategori = "K7";
                $rules = array(
                    'database'  => null, //Default database master
                    'select'    => null,
                    'where'     => array(
                        'nomor_peserta' => $value->nomor_peserta,
                    ),
                    'or_where'  => null,
                    'like'      => null,
                    'or_like'   => null,
                    'order'     => null,
                    'limit'     => null,
                    'group_by'  => null,
                );
                $ukt = $this->Viewd_ukt->search($rules);
                if ($value->jenjang == "S1") {
                    $mahasiswa = $this->Viewd_mahasiswa->search($rules)->row();
                    $orangtua = $this->Viewd_orangtua->search($rules)->result();
                    $rumah = $this->Viewd_rumah->search($rules)->row();
                    if ($value->submit == "SUDAH") {
                        $score_daya_listrik = $score_kepemilikan_mobil = $score_kepemilikan_motor = $score_kepemilikan_rumah = $score_lktl = $score_njop = $score_pajak_mobil = $score_pajak_motor = $score_rekening_listrik = $score_sktm = $score_tanggungan = $score_penghasilan_ayah = $score_penghasilan_ibu = $score_penghasilan_wali = $score = 0;
                        if ($rumah->nilai_daya_listrik != 0)
                            $score_daya_listrik = ($rumah->nilai_daya_listrik / $nilai_daya_listrik->nilai_max) * $nilai_daya_listrik->bobot;
                        if ($rumah->nilai_kepemilikan_mobil != 0)
                            $score_kepemilikan_mobil = ($rumah->nilai_kepemilikan_mobil / $nilai_kepemilikan_mobil->nilai_max) * $nilai_kepemilikan_mobil->bobot;
                        if ($rumah->nilai_kepemilikan_motor != 0)
                            $score_kepemilikan_motor = ($rumah->nilai_kepemilikan_motor / $nilai_kepemilikan_motor->nilai_max) * $nilai_kepemilikan_motor->bobot;
                        if ($rumah->nilai_kepemilikan_rumah != 0)
                            $score_kepemilikan_rumah = ($rumah->nilai_kepemilikan_rumah / $nilai_kepemilikan_rumah->nilai_max) * $nilai_kepemilikan_rumah->bobot;
                        if ($rumah->nilai_lktl != 0)
                            $score_lktl = ($rumah->nilai_lktl / $nilai_lktl->nilai_max) * $nilai_lktl->bobot;
                        if ($rumah->nilai_njop != 0)
                            $score_njop = ($rumah->nilai_njop / $nilai_njop->nilai_max) * $nilai_njop->bobot;
                        if ($rumah->nilai_pajak_mobil != 0)
                            $score_pajak_mobil = ($rumah->nilai_pajak_mobil / $nilai_pajak_mobil->nilai_max) * $nilai_pajak_mobil->bobot;
                        if ($rumah->nilai_pajak_motor != 0)
                            $score_pajak_motor = ($rumah->nilai_pajak_motor / $nilai_pajak_motor->nilai_max) * $nilai_pajak_motor->bobot;
                        if ($rumah->nilai_rekening_listrik != 0)
                            $score_rekening_listrik = ($rumah->nilai_rekening_listrik / $nilai_rekening_listrik->nilai_max) * $nilai_rekening_listrik->bobot;
                        if ($mahasiswa->nilai_sktm != 0)
                            $score_sktm = ($mahasiswa->nilai_sktm / $nilai_sktm->nilai_max) * $nilai_sktm->bobot;
                        if ($rumah->nilai_tanggungan != 0)
                            $score_tanggungan = ($rumah->nilai_tanggungan / $nilai_tanggungan->nilai_max) * $nilai_tanggungan->bobot;
                        foreach ($orangtua as $a) {
                            if ($a->orangtua == 'Ayah') {
                                if ($a->nilai_penghasilan != 0)
                                    $score_penghasilan_ayah = ($a->nilai_penghasilan / $nilai_penghasilan_ayah->nilai_max) * $nilai_penghasilan_ayah->bobot;
                            }
                            if ($a->orangtua == 'Ibu') {
                                if ($a->nilai_penghasilan != 0)
                                    $score_penghasilan_ibu = ($a->nilai_penghasilan / $nilai_penghasilan_ibu->nilai_max) * $nilai_penghasilan_ibu->bobot;
                            }
                            if ($a->orangtua == 'Wali') {
                                if ($a->nilai_penghasilan != 0)
                                    $score_penghasilan_wali = ($a->nilai_penghasilan / $nilai_penghasilan_wali->nilai_max) * $nilai_penghasilan_wali->bobot;
                            }
                        }

                        $score =
                            $score_daya_listrik +
                            $score_kepemilikan_mobil +
                            $score_kepemilikan_motor +
                            $score_kepemilikan_rumah +
                            $score_lktl +
                            $score_njop +
                            $score_pajak_mobil +
                            $score_pajak_motor +
                            $score_rekening_listrik +
                            $score_sktm +
                            $score_tanggungan +
                            $score_penghasilan_ayah +
                            $score_penghasilan_ibu +
                            $score_penghasilan_wali;

                        if ($score > $k1->nilai_min && $score <= $k1->nilai_max) {
                            $kategori = 'K1';
                        } else if ($score > $k2->nilai_min && $score <= $k2->nilai_max) {
                            $kategori = 'K2';
                        } else if ($score > $k3->nilai_min && $score <= $k3->nilai_max) {
                            $kategori = 'K3';
                        } else if ($score > $k4->nilai_min && $score <= $k4->nilai_max) {
                            $kategori = 'K4';
                        } else if ($score > $k5->nilai_min && $score <= $k5->nilai_max) {
                            $kategori = 'K5';
                        } else if ($score > $k6->nilai_min && $score <= $k6->nilai_max) {
                            $kategori = 'K6';
                        } else if ($score > $k7->nilai_min && $score <= $k7->nilai_max) {
                            $kategori = 'K7';
                        } else {
                            $kategori = 'K7';
                        }
                    }
                } else {
                    $score = 1;
                }

                $tbsUKT = $this->master->read("ukt/?kode_jurusan=$value->kode_jurusan&kategori=$kategori");
                if ($tbsUKT->code == 200) {
                    $jumlah = 0;
                    $tbsUKT = json_decode(json_encode($tbsUKT->data->data), true);
                    if ($value->ids_jalur_masuk == 1 || $value->ids_jalur_masuk == 7) {
                        $jumlah = $tbsUKT[0]['snbp'];
                    } else if ($value->ids_jalur_masuk == 2) {
                        $jumlah = $tbsUKT[0]['spanptkin'];
                    } else if ($value->ids_jalur_masuk == 3 || $value->ids_jalur_masuk == 8) {
                        $jumlah = $tbsUKT[0]['snbt'];
                    } else if ($value->ids_jalur_masuk == 4) {
                        $jumlah = $tbsUKT[0]['umptkin'];
                    } else {
                        $jumlah = $tbsUKT[0]['mandiri'];
                    }

                    if ($ukt->num_rows() == 0) {
                        $rules = array(
                            'idd_kelulusan' => $value->idd_kelulusan,
                            'score' => $score,
                            'kategori' => $kategori,
                            'jumlah' => $jumlah,
                            'potongan' => 0,
                            'created_by' => $this->jwt->ids_user,
                            'updated_by' => $this->jwt->ids_user,
                        );
                        $fb = $this->Tbd_ukt->create($rules);
                        if (!$fb['status']) {
                            $created++;
                        } else {
                            $error++;
                        }
                    } else {
                        $ukt = $ukt->row();
                        $rules = array(
                            'where'     => array('idd_ukt' => $ukt->idd_ukt),
                            'or_where'  => null,
                            'like'      => null,
                            'or_like'   => null,
                            'data'      => array(
                                'score' => $score,
                                'kategori' => $kategori,
                                'jumlah' => $jumlah,
                                'updated_by' => $this->jwt->ids_user,
                            ), // not null
                        );
                        $fb = $this->Tbd_ukt->update($rules);
                        if (!$fb['status']) {
                            $updated++;
                        } else {
                            $error++;
                        }
                    }
                } else {
                    $error++;
                }
            }
            $response = array(
                'status' => 200,
                'message' => "Penetapan data berhasil. Created: $created. Updated: $updated. Error: $error",
                'error' => $error
            );
        } else {
            $response = array(
                'status' => 400,
                'message' => 'Hak akses ditolak.'
            );
        }
        echo json_encode($response);
    } */

    private function _validate()
    {
        $data = array();
        $data['error_string'] = array();
        $data['inputerror'] = array();
        $data['status'] = TRUE;

        if ($this->input->post('tahun') == '') {
            $data['inputerror'][] = 'tahun';
            $data['error_string'][] = 'Tahun wajib diisi.';
            $data['status'] = FALSE;
        }

        if ($this->input->post('ids_jalur_masuk') == '') {
            $data['inputerror'][] = 'ids_jalur_masuk';
            $data['error_string'][] = 'Jalur masuk wajib diisi.';
            $data['status'] = FALSE;
        }

        if ($data['status'] === FALSE) {
            echo json_encode($data);
            exit();
        }
    }
}
