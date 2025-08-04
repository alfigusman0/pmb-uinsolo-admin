<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Cronejob extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        date_default_timezone_set("Asia/Jakarta");
        $this->load->model('Daftar/Tbd_kelulusan');
        $this->load->model('Daftar/Tbd_pembayaran');
        $this->load->model('Daftar/Viewd_kelulusan');
        $this->load->model('Daftar/Viewd_pembayaran');
        $this->load->model('Daftar/Viewd_kelulusan2');
        $this->load->model('Mandiri/Tbp_formulir');
        $this->load->model('Mandiri/Tbp_jadwal');
        $this->load->model('Mandiri/Tbp_kelulusan');
        $this->load->model('Mandiri/Tbp_nilai');
        $this->load->model('Mandiri/Tbp_pembayaran');
        $this->load->model('Mandiri/Tbp_setting');
        $this->load->model('Mandiri/Tbp_file');
        $this->load->model('Mandiri/Viewp_formulir');
        $this->load->model('Mandiri/Viewp_biodata');
        $this->load->model('Mandiri/Viewp_kelulusan');
        $this->load->model('Mandiri/Viewp_sekolah');
        $this->load->model('Mandiri/Viewp_pilihan');
        $this->load->model('Mandiri/Viewp_pembayaran');
        $this->load->model('Settings/Tbs_bobot_jurusan');
        $this->load->model('Settings/Tbs_jadwal');
        $this->load->model('Settings/Tbs_daya_tampung');
        $this->load->model('Settings/Tbs_sub_daya_tampung');
        $this->load->model('Settings/Tbs_tipe_ujian');
        $this->load->model('Settings/Views_daya_tampung');
        $this->load->model('Settings/Views_jadwal');
        $this->load->model('Settings/Views_tipe_ujian');
        $this->load->model('Tbl_notif');
        $this->load->model('Tbl_users');
        $this->load->model('View_notif');
    }

    // Update Tipe Ujian
    function CJ1()
    {
        $updated = $error = 0;
        $rules = array(
            'where' => array(
                'status' => 'YA',
                'YEAR(date_created) <' => date('Y'),
            ),
        );
        if ($this->Tbs_jadwal->search($rules)->num_rows() > 0) {
            $rules['data'] = array('status' => 'TIDAK', 'quota' => 0);
            $this->Tbs_jadwal->update($rules);
        }
        $rules = array(
            'database'  => null, //Database master
            'select'    => null,
            'order'     => null,
            'limit'     => null,
            'group_by'  => null,
        );
        $tbsTipeUjian = $this->Tbs_tipe_ujian->read($rules)->result();
        foreach ($tbsTipeUjian as $value) {
            $rules = null;
            if ($value->status_jadwal == 'YA') {
                $quota = 0;
                $rules = array(
                    'database'  => null, //Default database master
                    'select'    => null,
                    'where'     => array(
                        'ids_tipe_ujian' => $value->ids_tipe_ujian,
                        'status' => 'YA',
                        'quota >' => 0,
                    ),
                    'or_where'  => null,
                    'like'      => null,
                    'or_like'   => null,
                    'order'     => null,
                    'limit'     => null,
                    'group_by'  => null,
                );
                $tblSJadwal = $this->Tbs_jadwal->search($rules)->result();
                foreach ($tblSJadwal as $value2) {
                    $quota += $value2->quota;
                }
                $rules = array(
                    'where'     => array('ids_tipe_ujian' => $value->ids_tipe_ujian),
                    'data'      => array(
                        'quota' => $quota,
                        'status' => ($quota > 0) ? 'YA' : 'TIDAK',
                    ), // not null
                );
            } else {
                $rules = array(
                    'where'     => array('ids_tipe_ujian' => $value->ids_tipe_ujian),
                    'data'      => array(
                        'status' => ($value->quota > 0) ? 'YA' : 'TIDAK',
                    ), // not null
                );
            }

            $fb = $this->Tbs_tipe_ujian->update($rules);
            if (!$fb['status']) {
                $updated++;
            } else {
                echo $fb['message'] . " \r\n";
                $error++;
            }
        }
        $keterangan = "Update Tipe Ujian Success. Updated: $updated, Error: $error";
        echo date('Y-m-d H:i:s') . ' CJ1: ' . $keterangan . " \r\n";
    }

    // Expired Pembayaran Formulir
    function CJ2()
    {
        $updated = $error = 0;
        $rules = array(
            'database'  => null,
            'select'    => null,
            'where'     => array(
                'pembayaran'    => 'BELUM'
            ),
            'or_where'  => null,
            'like'      => null,
            'or_like'   => null,
            'order'     => null,
            'limit'     => null,
            'group_by'  => null,
        );
        $tbpPembayaran = $this->Tbp_pembayaran->search($rules)->result();
        foreach ($tbpPembayaran as $value) {
            $tgl_skrng = date('Y-m-d H:i:s');
            if ($tgl_skrng >= $value->expire_at) {
                $rules = array(
                    'where'     => array(
                        'idp_pembayaran' => $value->idp_pembayaran
                    ),
                    'or_where'  => null,
                    'like'      => null,
                    'or_like'   => null,
                    'data'      => array('pembayaran' => 'EXPIRED'), // not null
                );
                $fb = $this->Tbp_pembayaran->update($rules);
                if (!$fb['status']) {
                    $updated++;
                } else {
                    echo $fb['message'] . " \r\n";
                    $error++;
                }
            }
        }
        $keterangan = "Update Expired Pembayaran. Updated: $updated, Error: $error";
        echo date('Y-m-d H:i:s') . ' Pembayaran CJ2: ' . $keterangan . " \r\n";
    }

    // Update Daya Tampung & Kuota
    function CJ3($ids_jalur_masuk = null)
    {
        $keterangan = null;
        $created = $updated = $error = 0;
        $jurusan = $this->master->read("jurusan/?status=YA&page=1&limit=100");
        if ($jurusan->code != 200) {
            $keterangan = "Update Daya Tampung & Kuota. Error Code: $jurusan->code. Error Message: $jurusan->message";
        } else {
            foreach ($jurusan->data->data as $a) {
                $where = array();
                $daya_tampung = $afirmasi = $kuota = 0;
                $where['kode_jurusan'] = $a->kode_jurusan;
                $where['YEAR(date_created)'] = date('Y');
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
                foreach ($this->Tbs_sub_daya_tampung->search($rules)->result() as $b) {
                    $daya_tampung += $b->daya_tampung;
                    if ($ids_jalur_masuk != null) {
                        if ($b->ids_jalur_masuk == $ids_jalur_masuk) {
                            $kuota += $b->daya_tampung;
                        }
                    }
                }
                if ($ids_jalur_masuk != null) {
                    $where['ids_jalur_masuk'] = $ids_jalur_masuk;
                } else {
                    if ($a->jenjang == 'S1') {
                        $afirmasi = floor(($daya_tampung * 0.1));
                    }
                }
                $where['pembayaran'] = 'SUDAH';
                if ($a->jenjang == 'S1') {
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
                    $pembayaran = $this->Tbd_kelulusan->search($rules)->num_rows();
                } else {
                    $pembayaran = 0;
                }
                $rules = array(
                    'database'  => null, //Default database master
                    'select'    => null,
                    'where'     => array(
                        'kode_jurusan' => $a->kode_jurusan,
                        'YEAR(date_created)' => date('Y')
                    ),
                    'or_where'  => null,
                    'like'      => null,
                    'or_like'   => null,
                    'order'     => null,
                    'limit'     => null,
                    'group_by'  => null,
                );
                $num = $this->Tbs_daya_tampung->search($rules)->num_rows();
                if ($num == 0) {
                    $rules = array(
                        'kode_jurusan' => $a->kode_jurusan,
                        'kelas' => 1,
                        'daya_tampung' => $daya_tampung,
                        'dt_awal' => $daya_tampung,
                        'afirmasi' => $afirmasi,
                        'kuota' => ($ids_jalur_masuk == null) ? ($daya_tampung - $afirmasi - $pembayaran) : ($kuota - $pembayaran),
                        'grade' => 0,
                        'status' => 'YA',
                        'created_by' => '1',
                        'updated_by' => '1',
                    );
                    $fb = $this->Tbs_daya_tampung->create($rules);
                    if (!$fb['status']) {
                        $created++;
                    } else {
                        $error++;
                    }
                } else {
                    $rules = array(
                        'where'     => array(
                            'kode_jurusan' => $a->kode_jurusan,
                            'YEAR(date_created)' => date('Y')
                        ),
                        'or_where'  => null,
                        'like'      => null,
                        'or_like'   => null,
                        'data'      => array(
                            'daya_tampung' => $daya_tampung,
                            'afirmasi' => $afirmasi,
                            'kuota' => ($ids_jalur_masuk == null) ? ($daya_tampung - $afirmasi - $pembayaran) : ($kuota - $pembayaran)
                        ), // not null
                    );
                    $fb = $this->Tbs_daya_tampung->update($rules);
                    if (!$fb['status']) {
                        $updated++;
                    } else {
                        $error++;
                    }
                }
            }
            $keterangan = "Daya Tampung dan Kuota. Created: $created, Updated: $updated, Error: $error";
        }
        echo date('Y-m-d H:i:s') . ' CJ3: ' . $keterangan . " \r\n";
    }

    // Perhitungan bobot sekolah
    function CJ4()
    {
        $keterangan = null;
        $created = $updated = $error = 0;
        $rules = array(
            'database'  => null,
            'select'    => null,
            'where'     => array(
                'nomor_peserta !=' => null,
                'YEAR(date_created)' => date('Y')
            ),
            'or_where'  => null,
            'like'      => null,
            'or_like'   => null,
            'order'     => null,
            'limit'     => null,
            'group_by'  => null,
        );
        $viewPSekolah = $this->Viewp_sekolah->search($rules)->result();
        foreach ($viewPSekolah as $a) {
            $bobot = 0;
            if ($a->ids_jenis_sekolah == 1) {
                $bobot = 85;
            } else if ($a->ids_jenis_sekolah == 4 && $a->akreditasi_sekolah == 'A') {
                $bobot = 100;
            } else if ($a->ids_jenis_sekolah == 4 && $a->akreditasi_sekolah == 'B') {
                $bobot = 95;
            } else if ($a->ids_jenis_sekolah == 4 && $a->akreditasi_sekolah == 'C') {
                $bobot = 90;
            } else if ($a->ids_jenis_sekolah == 5 && $a->akreditasi_sekolah == 'A') {
                $bobot = 85;
            } else if ($a->ids_jenis_sekolah == 5 && $a->akreditasi_sekolah == 'B') {
                $bobot = 80;
            } else if ($a->ids_jenis_sekolah == 5 && $a->akreditasi_sekolah == 'C') {
                $bobot = 75;
            } else if ($a->ids_jenis_sekolah == 2 && $a->akreditasi_sekolah == 'A') {
                $bobot = 95;
            } else if ($a->ids_jenis_sekolah == 2 && $a->akreditasi_sekolah == 'B') {
                $bobot = 90;
            } else if ($a->ids_jenis_sekolah == 2 && $a->akreditasi_sekolah == 'C') {
                $bobot = 85;
            } else if ($a->ids_jenis_sekolah == 3 && $a->akreditasi_sekolah == 'A') {
                $bobot = 80;
            } else if ($a->ids_jenis_sekolah == 3 && $a->akreditasi_sekolah == 'B') {
                $bobot = 75;
            } else if ($a->ids_jenis_sekolah == 3 && $a->akreditasi_sekolah == 'C') {
                $bobot = 70;
            } else if ($a->ids_jenis_sekolah == 6 && $a->akreditasi_sekolah == 'A') {
                $bobot = 95;
            } else if ($a->ids_jenis_sekolah == 6 && $a->akreditasi_sekolah == 'B') {
                $bobot = 90;
            } else if ($a->ids_jenis_sekolah == 6 && $a->akreditasi_sekolah == 'C') {
                $bobot = 85;
            } else if ($a->ids_jenis_sekolah == 7 && $a->akreditasi_sekolah == 'A') {
                $bobot = 80;
            } else if ($a->ids_jenis_sekolah == 7 && $a->akreditasi_sekolah == 'B') {
                $bobot = 75;
            } else if ($a->ids_jenis_sekolah == 7 && $a->akreditasi_sekolah == 'C') {
                $bobot = 70;
            } else {
                $bobot = 0;
            }

            $rules = array(
                'database'  => null,
                'select'    => null,
                'where'     => array(
                    'idp_formulir' => $a->idp_formulir,
                    'keterangan' => 'BOBOT',
                ),
                'or_where'  => null,
                'like'      => null,
                'or_like'   => null,
                'order'     => null,
                'limit'     => null,
                'group_by'  => null,
            );
            $num = $this->Tbp_nilai->search($rules)->num_rows();
            if ($num > 0) {
                $rules = array(
                    'where'     => array(
                        'idp_formulir' => $a->idp_formulir,
                        'keterangan' => 'BOBOT',
                    ),
                    'or_where'  => null,
                    'like'      => null,
                    'or_like'   => null,
                    'data'      => array(
                        'nilai' => $bobot,
                    ), // not null
                );
                if ($this->Tbp_nilai->update($rules)) {
                    $updated++;
                } else {
                    $error++;
                }
            } else {
                $rules = array(
                    'idp_formulir' => $a->idp_formulir,
                    'keterangan' => 'BOBOT',
                    'nilai' => $bobot,
                    'created_by' => '1',
                    'updated_by' => '1',
                );
                if ($this->Tbp_nilai->create($rules)) {
                    $created++;
                } else {
                    $error++;
                }
            }
        }
        $keterangan = "Perhitungan Bobot. Created: $created, Updated: $updated, Error: $error";
        echo date('Y-m-d H:i:s') . ' CJ4: ' . $keterangan . " \r\n";
    }

    // Akumulasi Penilaian
    function CJ5()
    {
        $created = $updated = $error = 0;
        $rules = array(
            'database'  => null,
            'select'    => null,
            'where'     => array(
                'YEAR(date_created)' => date('Y'),
                'pembayaran' => 'SUDAH'
            ),
            'or_where'  => null,
            'like'      => null,
            'or_like'   => null,
            'order'     => null,
            'limit'     => null,
            'group_by'  => null,
        );
        $viewPFormulir = $this->Viewp_formulir->search($rules)->result();
        foreach ($viewPFormulir as $a) {
            $rules = array(
                'database'  => null,
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
            $tbPNilai = $this->Tbp_nilai->search($rules);
            $rules = array(
                'database'  => null,
                'select'    => null,
                'where'     => array(
                    'idp_formulir' => $a->idp_formulir,
                    'keterangan' => 'KRITERIA TERTENTU',
                ),
                'or_where'  => null,
                'like'      => null,
                'or_like'   => null,
                'order'     => null,
                'limit'     => null,
                'group_by'  => null,
            );
            $ket = $this->Tbp_nilai->search($rules)->num_rows();
            if ($tbPNilai->num_rows() > 1) {
                $tbPNilai = $tbPNilai->result();
                $tot = $temp_wawancara = $num = 0;
                foreach ($tbPNilai as $b) {
                    if ($a->jenjang == "S1") {
                        if ($a->ids_tipe_ujian == 30) {
                            if ($b->keterangan == 'RAPOR') {
                                $tot += ($b->nilai * 0.5);
                            } else if ($b->keterangan == 'PORTOFOLIO') {
                                $tot += ($b->nilai * 0.4);
                            } else if ($b->keterangan == 'BOBOT') {
                                $tot += ($b->nilai * 0.1);
                            }
                        } else {
                            if ($ket > 0) {
                                if ($b->keterangan == 'CBT') {
                                    $tot += ($b->nilai * 0.9);
                                } else if ($b->keterangan == 'BOBOT') {
                                    $tot += ($b->nilai * 0.1);
                                } else if ($b->keterangan == 'KRITERIA TERTENTU') {
                                    $tot += ($b->nilai * 0.1);
                                }
                            } else {
                                if ($b->keterangan == 'CBT') {
                                    $tot += ($b->nilai * 0.9);
                                } else if ($b->keterangan == 'BOBOT') {
                                    $tot += ($b->nilai * 0.1);
                                }
                            }
                        }
                    } else {
                        if ($b->keterangan == 'CBT') {
                            $tot += ($b->nilai);
                        } else if ($b->keterangan == 'STUDI NASKAH' || $b->keterangan == 'PROPOSAL' || $b->keterangan == 'MODERASI BERAGAMA') {
                            $temp_wawancara += $b->nilai;
                            $num++;
                            if ($num == 3) {
                                $tot += ($temp_wawancara * 4);
                            }
                        }
                    }
                }
                $rules = array(
                    'where'     => array(
                        'idp_formulir' => $a->idp_formulir
                    ),
                    'or_where'  => null,
                    'like'      => null,
                    'or_like'   => null,
                    'data'      => array('total' => $tot), // not null
                );
                $fb = $this->Tbp_kelulusan->update($rules);
                if (!$fb['status']) {
                    $updated++;
                } else {
                    $error++;
                }
            }
        }
        $keterangan = "Akumulasi Penilaian. Created: $created, Updated: $updated, Error: $error";
        echo date('Y-m-d H:i:s') . ' CJ5: ' . $keterangan . " \r\n";
    }

    // Generate Passing Grade Jurusan - Sarjana
    function CJ6($tahun = null)
    {
        $keterangan = null;
        $updated = $error = 0;
        $jurusan = $this->master->read("jurusan/?status=YA&jenjang=S1&page=1&limit=100");
        if ($jurusan->code != 200) {
            $keterangan = "Update Daya Tampung & Kuota. Error Code: $jurusan->code. Error Message: $jurusan->message";
        } else {
            if ($tahun == null) {
                $tahun = date('Y');
            }
            foreach ($jurusan->data->data as $a) {
                $rules = array(
                    'database'  => null,
                    'select'    => null,
                    'where'     => array(
                        'ids_tipe_ujian' => '1',
                        'kode_jurusan' => $a->kode_jurusan,
                        'pilihan' => '1',
                        'YEAR(date_created)' => $tahun
                    ),
                    'or_where'  => null,
                    'like'      => null,
                    'or_like'   => null,
                    'order'     => null,
                    'limit'     => null,
                    'group_by'  => null,
                );
                $viewPPilihan = $this->Viewp_pilihan->search($rules)->result();
                $total = $jumlah = $rata = 0;
                foreach ($viewPPilihan as $b) {
                    $rules = array(
                        'database'  => null,
                        'select'    => null,
                        'where'     => array(
                            'nomor_peserta' => $b->nomor_peserta,
                        ),
                        'or_where'  => null,
                        'like'      => null,
                        'or_like'   => null,
                        'order'     => null,
                        'limit'     => null,
                        'group_by'  => null,
                    );
                    $viewPKelulusan = $this->Viewp_kelulusan->search($rules);
                    if ($viewPKelulusan->num_rows() == 0) {
                        $total += 0;
                    } else {
                        $viewPKelulusan = $viewPKelulusan->row();
                        $total += $viewPKelulusan->total;
                    }
                    $jumlah++;
                }
                if ($total != 0) {
                    $rata = $total / $jumlah;
                }
                $rules = array(
                    'where'     => array(
                        'kode_jurusan' => $a->kode_jurusan,
                        'YEAR(date_created)' => $tahun
                    ),
                    'or_where'  => null,
                    'like'      => null,
                    'or_like'   => null,
                    'data'      => array(
                        'grade' => $rata
                    ), // not null
                );
                $fb = $this->Tbs_daya_tampung->update($rules);
                if (!$fb['status']) {
                    $updated++;
                } else {
                    $error++;
                }
            }
            $keterangan = "Update Passing Grade Jurusan - Sarjana. Updated: $updated, Error: $error";
        }
        echo date('Y-m-d H:i:s') . ' CJ6: ' . $keterangan . " \r\n";
    }

    // Generate NIM
    function CJ7($kode_jurusan = null)
    {
        $keterangan = null;
        $updated = $error = 0;
        $params = "jurusan/?status=YA&page=1&limit=100";
        if ($kode_jurusan != null) {
            $params .= "&kode_jurusan=$kode_jurusan";
        }
        $jurusan = $this->master->read($params);

        if ($jurusan->code != 200) {
            $keterangan = "Generate NIM. Jurusan Error Code: $jurusan->code. Error Message: $jurusan->message";
            echo date('Y-m-d H:i:s') . ' CJ7: ' . $keterangan . " \r\n<br>";
            return;  // Return to stop further execution if there's an error
        }

        // Ensure jurusan data exists and is iterable
        if (empty($jurusan->data->data)) {
            echo date('Y-m-d H:i:s') . ' CJ7: ' . "No data found for jurusan." . " \r\n<br>";
            return;
        }

        foreach ($jurusan->data->data as $b) {
            $jenjang = ($b->jenjang == 'S1') ? 1 : (($b->jenjang == 'S2') ? 2 : 3);
            $nim = $jenjang . substr(date('Y'), 2, 2);

            if ($b->jenjang == 'S3') {
                $params = "konsentrasi/?status=YA&kode_jurusan=$b->kode_jurusan&page=1&limit=1000";
                $konsentrasi = $this->master->read($params);
                if ($konsentrasi->code != 200) {
                    $keterangan = "Generate NIM. Konsentrasi Error Code: $konsentrasi->code. Error Message: $konsentrasi->message";
                    echo date('Y-m-d H:i:s') . ' CJ7: ' . $keterangan . " \r\n<br>";
                    return;  // Return to stop further execution if there's an error
                }

                // Ensure konsentrasi data exists and is iterable
                if (empty($konsentrasi->data->data)) {
                    echo date('Y-m-d H:i:s') . ' CJ7: ' . "No data found for konsentrasi." . " \r\n<br>";
                    return;
                }

                // Process each konsentrasi separately
                foreach ($konsentrasi->data->data as $kons) {
                    $nim_konsentrasi = $nim . '0' . $kons->kode_konsentrasi . '0001';

                    // Check if NIM already exists
                    $rules = array(
                        'where' => array('nim' => $nim_konsentrasi)
                    );
                    $num = $this->Viewd_kelulusan->search($rules)->num_rows();

                    // If NIM doesn't exist
                    if ($num == 0) {
                        $this->process_nim($b, $nim_konsentrasi, $updated, $error, $kons->kode_konsentrasi);
                    } else {
                        // Get the last NIM for this year and department
                        $rules = array(
                            'select' => 'nim',
                            'where' => array(
                                'YEAR(date_created)' => date('Y'),
                                'kode_jurusan' => $b->kode_jurusan,
                                'kode_konsentrasi' => $kons->kode_konsentrasi
                            ),
                            'order' => 'nim DESC',
                            'limit' => 1
                        );
                        $cek = $this->Viewd_kelulusan->search($rules)->row();

                        // Ensure $cek is not null before accessing $cek->nim
                        if ($cek && isset($cek->nim)) {
                            $nim_konsentrasi = $cek->nim;
                            $nim_konsentrasi++; // Increment the NIM here, outside of the function call
                            $this->process_nim($b, $nim_konsentrasi, $updated, $error, $kons->kode_konsentrasi);
                        } else {
                            echo date('Y-m-d H:i:s') . ' CJ7: ' . "No NIM found for jurusan: " . $b->kode_jurusan . " (NIM: $nim_konsentrasi) \r\n<br>";
                        }
                    }
                }
            } else {
                $nim .= $b->kode_jurusan . '0001';

                // Check if NIM already exists
                $rules = array(
                    'where' => array('nim' => $nim)
                );
                $num = $this->Viewd_kelulusan->search($rules)->num_rows();

                // If NIM doesn't exist
                if ($num == 0) {
                    $this->process_nim($b, $nim, $updated, $error);
                } else {
                    // Get the last NIM for this year and department
                    $rules = array(
                        'select' => 'nim',
                        'where' => array(
                            'YEAR(date_created)' => date('Y'),
                            'kode_jurusan' => $b->kode_jurusan
                        ),
                        'order' => 'nim DESC',
                        'limit' => 1
                    );
                    $cek = $this->Viewd_kelulusan->search($rules)->row();

                    // Ensure $cek is not null before accessing $cek->nim
                    if ($cek && isset($cek->nim)) {
                        $nim = $cek->nim;
                        $nim++; // Increment the NIM here, outside of the function call
                        $this->process_nim($b, $nim, $updated, $error);
                    } else {
                        echo date('Y-m-d H:i:s') . ' CJ7: ' . "No NIM found for jurusan: " . $b->kode_jurusan . " \r\n<br>";
                    }
                }
            }
        }

        $keterangan = "Generate NIM. Updated: $updated, Error: $error";
        echo date('Y-m-d H:i:s') . ' CJ7: ' . $keterangan . " \r\n<br>";
    }

    private function process_nim($b, &$nim, &$updated, &$error, $kode_konsentrasi = null)
    {
        $where = array(
            'YEAR(date_created)' => date('Y'),
            'kode_jurusan' => $b->kode_jurusan,
            'nim IS NULL' => null,
            'daftar' => "SUDAH",
            'submit' => "SUDAH",
            'pembayaran' => "SUDAH"
        );

        if ($kode_konsentrasi != null) {
            $where['kode_konsentrasi'] = $kode_konsentrasi;
        }

        if ($b->jenjang == 'S3') {
            $rules = array(
                'where' => $where,
                'order' => 'nama ASC'
            );
        } else {
            $rules = array(
                'where' => $where,
                'order' => 'tgl_pembayaran ASC'
            );
        }
        $viewdKelulusan = $this->Viewd_kelulusan->search($rules)->result();

        // Ensure $viewdKelulusan is iterable
        if (empty($viewdKelulusan)) {
            echo date('Y-m-d H:i:s') . ' CJ7: ' . "No eligible students found for jurusan: " . $b->kode_jurusan . ($kode_konsentrasi != null ? " (konsentrasi: $kode_konsentrasi)" : "") . " \r\n<br>";
            return;
        }

        foreach ($viewdKelulusan as $c) {
            $update_rules = array(
                'where' => array('idd_kelulusan' => $c->idd_kelulusan),
                'data' => array('nim' => $nim)
            );

            $fb = $this->Tbd_kelulusan->update($update_rules);
            if ($fb['status']) {
                $error++;
            } else {
                $updated++;
                $nim++;  // Increment NIM for next student
            }
        }
    }

    // Generate Kelas
    function CJ8($kode_jurusan = null)
    {
        $keterangan = null;
        $updated = $error = 0;
        $where = array();
        $huruf = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J');

        if ($kode_jurusan != null) {
            $where['kode_jurusan'] = $kode_jurusan;
        }

        $where['status'] = 'YA';
        $where['YEAR(date_created)'] = date('Y');

        $rules = array(
            'database'  => null,
            'select'    => null,
            'where'     => $where,
            'order'     => null,
            'limit'     => null
        );

        $viewsDayaTampung = $this->Views_daya_tampung->search($rules);
        if ($viewsDayaTampung->num_rows() > 0) {
            foreach ($viewsDayaTampung->result() as $a) {
                if ($a->jenjang == 'S3') {
                    $params = "konsentrasi/?status=YA&kode_jurusan=$a->kode_jurusan&page=1&limit=1000";
                    $konsentrasi = $this->master->read($params);

                    if ($konsentrasi->code != 200 || empty($konsentrasi->data->data)) {
                        echo date('Y-m-d H:i:s') . ' CJ8: ' . "Failed to get konsentrasi or no konsentrasi found for jurusan: " . $a->kode_jurusan . " \r\n<br>";
                        continue;  // Skip to the next jurusan if there's an error
                    }

                    // Process each konsentrasi separately
                    foreach ($konsentrasi->data->data as $kons) {
                        // Calculate the number of students for each gender
                        list($total, $pria, $wanita) = $this->getStudentCountsByKonsentrasi($a->kode_jurusan, $kons->kode_konsentrasi);
                        $lpria = floor($pria / $a->kelas);
                        $lwanita = floor($wanita / $a->kelas);

                        // Remaining students after division
                        $remainingPria = $pria % $a->kelas;
                        $remainingWanita = $wanita % $a->kelas;

                        echo "Total mahasiswa $a->kode_jurusan (Konsentrasi $kons->kode_konsentrasi) : $total. Jumlah Laki-laki: $pria. Jumlah Perempuan: $wanita. Dibagi menjadi $a->kelas Kelas. Pembagian Laki-Laki Perkelas: $lpria. Pembagian Perempuan Perkelas: $lwanita. \r\n<br>";

                        // Assign students to classes
                        for ($i = 0; $i < $a->kelas; $i++) {
                            list($up, $err) = $this->assignStudentsToClassByKonsentrasi($a->kode_jurusan, $kons->kode_konsentrasi, $huruf[$i], 'LAKI-LAKI', $lpria + ($remainingPria > 0 ? 1 : 0));
                            $updated += $up;
                            $error += $err;
                            list($updated, $error) = $this->assignStudentsToClassByKonsentrasi($a->kode_jurusan, $kons->kode_konsentrasi, $huruf[$i], 'PEREMPUAN', $lpria + ($remainingPria > 0 ? 1 : 0));
                            $updated += $up;
                            $error += $err;
                            $remainingPria--;
                            $remainingWanita--;
                        }
                    }
                } else {
                    // Calculate the number of students for each gender
                    list($total, $pria, $wanita) = $this->getStudentCounts($a->kode_jurusan);
                    $lpria = floor($pria / $a->kelas);
                    $lwanita = floor($wanita / $a->kelas);

                    // Remaining students after division
                    $remainingPria = $pria % $a->kelas;
                    $remainingWanita = $wanita % $a->kelas;

                    echo "Total mahasiswa $a->kode_jurusan : $total. Jumlah Laki-laki: $pria. Jumlah Perempuan: $wanita. Dibagi menjadi $a->kelas Kelas. Pembagian Laki-Laki Perkelas: $lpria. Pembagian Perempuan Perkelas: $lwanita. \r\n<br>";

                    // Assign students to classes
                    for ($i = 0; $i < $a->kelas; $i++) {
                        list($up, $err) = $this->assignStudentsToClass($a->kode_jurusan, $huruf[$i], 'LAKI-LAKI', $lpria + ($remainingPria > 0 ? 1 : 0));
                        $updated += $up;
                        $error += $err;
                        list($up, $err) = $this->assignStudentsToClass($a->kode_jurusan, $huruf[$i], 'PEREMPUAN', $lwanita + ($remainingWanita > 0 ? 1 : 0));
                        $updated += $up;
                        $error += $err;
                        $remainingPria--;
                        $remainingWanita--;
                    }
                }
            }
            $keterangan = "Generate Kelas. Updated: $updated, Error: $error";
        } else {
            $keterangan = "Tidak ada data daya tampung yang ditemukan.";
        }

        echo date('Y-m-d H:i:s') . ' CJ8: ' . $keterangan . " \r\n<br>";
    }

    // Fungsi untuk mendapatkan jumlah total, laki-laki, dan perempuan berdasarkan konsentrasi
    private function getStudentCountsByKonsentrasi($kode_jurusan, $kode_konsentrasi)
    {
        $where = array(
            'YEAR(date_created)' => date('Y'),
            'kode_jurusan' => $kode_jurusan,
            'kode_konsentrasi' => $kode_konsentrasi,
            'nim IS NOT NULL' => null
        );

        // Total students
        $total = $this->Viewd_kelulusan2->search(array('where' => $where))->num_rows();

        // Male students
        $where['jenis_kelamin'] = "LAKI-LAKI";
        $pria = $this->Viewd_kelulusan2->search(array('where' => $where))->num_rows();

        // Female students
        $where['jenis_kelamin'] = "PEREMPUAN";
        $wanita = $this->Viewd_kelulusan2->search(array('where' => $where))->num_rows();

        return array($total, $pria, $wanita);
    }

    // Fungsi untuk mengassign siswa ke kelas berdasarkan konsentrasi
    private function assignStudentsToClassByKonsentrasi($kode_jurusan, $kode_konsentrasi, $kelas, $jenis_kelamin, $limit)
    {
        $updated = $error = 0;
        $where = array(
            'YEAR(date_created)' => date('Y'),
            'kode_jurusan' => $kode_jurusan,
            'kode_konsentrasi' => $kode_konsentrasi,
            'jenis_kelamin' => $jenis_kelamin,
            'kelas IS NULL' => null
        );

        $rules = array(
            'where'     => $where,
            'order'     => 'nim ASC',
            'limit'     => $limit
        );

        $students = $this->Viewd_kelulusan2->search($rules)->result();
        foreach ($students as $student) {
            $update_rules = array(
                'where' => array('idd_kelulusan' => $student->idd_kelulusan),
                'data'  => array('kelas' => $kelas)
            );

            $fb = $this->Tbd_kelulusan->update($update_rules);
            if (!$fb['status']) {  // Add to updated count if update is successful
                $updated++;
            } else {
                $error++;  // Track errors
            }
        }
        return array($updated, $error);
    }

    // Fungsi untuk mendapatkan jumlah total, laki-laki, dan perempuan
    private function getStudentCounts($kode_jurusan)
    {
        $where = array(
            'YEAR(date_created)' => date('Y'),
            'kode_jurusan' => $kode_jurusan,
            'nim IS NOT NULL' => null
        );

        // Total students
        $total = $this->Viewd_kelulusan2->search(array('where' => $where))->num_rows();

        // Male students
        $where['jenis_kelamin'] = "LAKI-LAKI";
        $pria = $this->Viewd_kelulusan2->search(array('where' => $where))->num_rows();

        // Female students
        $where['jenis_kelamin'] = "PEREMPUAN";
        $wanita = $this->Viewd_kelulusan2->search(array('where' => $where))->num_rows();

        return array($total, $pria, $wanita);
    }

    // Fungsi untuk mengassign siswa ke kelas
    private function assignStudentsToClass($kode_jurusan, $kelas, $jenis_kelamin, $limit)
    {
        $updated = $error = 0;
        $where = array(
            'YEAR(date_created)' => date('Y'),
            'kode_jurusan' => $kode_jurusan,
            'jenis_kelamin' => $jenis_kelamin,
            'kelas IS NULL' => null
        );

        $rules = array(
            'where'     => $where,
            'order'     => 'nim ASC',
            'limit'     => $limit
        );

        $students = $this->Viewd_kelulusan2->search($rules)->result();
        foreach ($students as $student) {
            $update_rules = array(
                'where' => array('idd_kelulusan' => $student->idd_kelulusan),
                'data'  => array('kelas' => $kelas)
            );

            $fb = $this->Tbd_kelulusan->update($update_rules);
            if (!$fb['status']) {  // Add to updated count if update is successful
                $updated++;
            } else {
                $error++;  // Track errors
            }
        }
        return array($updated, $error);
    }

    // Generate Create Sub Daya Tampung
    function CJ9($ids_jalur_masuk = null)
    {
        $keterangan = null;
        $tahun = date('Y');
        $created = $error = 0;
        $params = "jalur-masuk/?status=YA&page=1&limit=100";
        if ($ids_jalur_masuk != null) {
            $params .= "&ids_jalur_masuk=$ids_jalur_masuk";
        }
        $jalur_masuk = $this->master->read($params);
        if ($jalur_masuk->code != 200) {
            $keterangan = "Generate NIM. Jalur Masuk Error Code: $jalur_masuk->code. Error Message: $jalur_masuk->message";
        }

        $jurusan = $this->master->read("jurusan/?status=YA&page=1&limit=100");
        if ($jurusan->code != 200) {
            $keterangan = "Generate NIM. Jurusan Error Code: $jurusan->code. Error Message: $jurusan->message";
        }

        if ($jalur_masuk->code == 200 && $jurusan->code == 200) {
            foreach ($jalur_masuk->data->data as $a) {
                foreach ($jurusan->data->data as $b) {
                    $rules = array(
                        'database'  => null, //Default database master
                        'select'    => null,
                        'where'     => array(
                            'ids_jalur_masuk' => $a->ids_jalur_masuk,
                            'kode_jurusan' => $b->kode_jurusan,
                            'YEAR(date_created)' => $tahun
                        ),
                        'or_where'  => null,
                        'like'      => null,
                        'or_like'   => null,
                        'order'     => null,
                        'limit'     => null,
                        'group_by'  => null,
                    );
                    $num = $this->Tbs_sub_daya_tampung->search($rules)->num_rows();
                    if ($num == 0) {
                        $rules = array(
                            'ids_jalur_masuk' => $a->ids_jalur_masuk,
                            'kode_jurusan' => $b->kode_jurusan,
                            'daya_tampung' => 0,
                            'status' => 'YA',
                            'created_by' => '1',
                            'updated_by' => '1',
                        );
                        $fb = $this->Tbs_sub_daya_tampung->create($rules);
                        if (!$fb['status']) {
                            $created++;
                        } else {
                            $error++;
                        }
                    }
                }
            }
            $keterangan = "Generate Create Sub Daya Tampung. Created: $created, Error: $error";
        }
        echo date('Y-m-d H:i:s') . ' CJ7: ' . $keterangan . " \r\n";
    }

    // Generate Create Jadwal
    function CJ10($tanggal, $ids_gedung, $ids_tipe_ujian, $kuota)
    {
        $created = $error = $exist = 0;
        $sesi_1_awal = '07:30:00';
        $sesi_1_akhir = '09:30:00';
        $sesi_2_awal = '10:00:00';
        $sesi_2_akhir = '12:00:00';
        $sesi_3_awal = '13:00:00';
        $sesi_3_akhir = '15:00:00';

        $ruangan = $this->master->read("/ruangan/?ids_gedung=$ids_gedung&status=YA&page=1&limit=1000");
        if ($ruangan->code != 200) {
            $keterangan = "Generate Create Jadwal. Ruangan Error Code: $ruangan->code. Error Message: $ruangan->message";
        } else {
            foreach ($ruangan->data->data as $a) {
                // check jadwal sesi 1
                $rules = array(
                    'database'  => null,
                    'select'    => null,
                    'where'     => array(
                        'tanggal' => $tanggal,
                        'ids_ruangan' => $a->ids_ruangan,
                        'ids_tipe_ujian' => $ids_tipe_ujian,
                        'jam_awal' => $sesi_1_awal,
                        'jam_akhir' => $sesi_1_akhir,
                    ),
                    'or_where'  => null,
                    'like'      => null,
                    'or_like'   => null,
                    'order'     => null,
                    'limit'     => null,
                    'group_by'  => null,
                );
                $num = $this->Tbs_jadwal->search($rules)->num_rows();
                if ($num == 0) {
                    $rules = array(
                        'tanggal' => $tanggal,
                        'ids_ruangan' => $a->ids_ruangan,
                        'ids_tipe_ujian' => $ids_tipe_ujian,
                        'jam_awal' => $sesi_1_awal,
                        'jam_akhir' => $sesi_1_akhir,
                        'quota' => $kuota,
                        'status' => 'YA',
                        'created_by' => '1',
                        'updated_by' => '1',
                    );
                    $fb = $this->Tbs_jadwal->create($rules);
                    if (!$fb['status']) {
                        $created++;
                    } else {
                        $error++;
                    }
                } else {
                    $exist++;
                }

                // check jadwal sesi 2
                $rules = array(
                    'database'  => null,
                    'select'    => null,
                    'where'     => array(
                        'tanggal' => $tanggal,
                        'ids_ruangan' => $a->ids_ruangan,
                        'ids_tipe_ujian' => $ids_tipe_ujian,
                        'jam_awal' => $sesi_2_awal,
                        'jam_akhir' => $sesi_2_akhir,
                    ),
                    'or_where'  => null,
                    'like'      => null,
                    'or_like'   => null,
                    'order'     => null,
                    'limit'     => null,
                    'group_by'  => null,
                );
                $num = $this->Tbs_jadwal->search($rules)->num_rows();
                if ($num == 0) {
                    $rules = array(
                        'tanggal' => $tanggal,
                        'ids_ruangan' => $a->ids_ruangan,
                        'ids_tipe_ujian' => $ids_tipe_ujian,
                        'jam_awal' => $sesi_2_awal,
                        'jam_akhir' => $sesi_2_akhir,
                        'quota' => $kuota,
                        'status' => 'YA',
                        'created_by' => '1',
                        'updated_by' => '1',
                    );
                    $fb = $this->Tbs_jadwal->create($rules);
                    if (!$fb['status']) {
                        $created++;
                    } else {
                        $error++;
                    }
                } else {
                    $exist++;
                }

                // check jadwal sesi 3
                $rules = array(
                    'database'  => null,
                    'select'    => null,
                    'where'     => array(
                        'tanggal' => $tanggal,
                        'ids_ruangan' => $a->ids_ruangan,
                        'ids_tipe_ujian' => $ids_tipe_ujian,
                        'jam_awal' => $sesi_3_awal,
                        'jam_akhir' => $sesi_3_akhir,
                    ),
                    'or_where'  => null,
                    'like'      => null,
                    'or_like'   => null,
                    'order'     => null,
                    'limit'     => null,
                    'group_by'  => null,
                );
                $num = $this->Tbs_jadwal->search($rules)->num_rows();
                if ($num == 0) {
                    $rules = array(
                        'tanggal' => $tanggal,
                        'ids_ruangan' => $a->ids_ruangan,
                        'ids_tipe_ujian' => $ids_tipe_ujian,
                        'jam_awal' => $sesi_3_awal,
                        'jam_akhir' => $sesi_3_akhir,
                        'quota' => $kuota,
                        'status' => 'YA',
                        'created_by' => '1',
                        'updated_by' => '1',
                    );
                    $fb = $this->Tbs_jadwal->create($rules);
                    if (!$fb['status']) {
                        $created++;
                    } else {
                        $error++;
                    }
                } else {
                    $exist++;
                }
            }
        }


        $keterangan = "Generate Create Jadwal. Created: $created, Error: $error, Exist: $exist";
        echo date('Y-m-d H:i:s') . ' CJ10: ' . $keterangan . " \r\n";
    }

    // Update Status Pembayaran Double
    function CJ11()
    {
        $updated = $error = 0;
        $rules = array(
            'database'  => null,
            'select'    => null,
            'where'     => array(
                'pembayaran' => 'SUDAH'
            ),
            'or_where'  => null,
            'like'      => null,
            'or_like'   => null,
            'order'     => null,
            'limit'     => null,
            'group_by'  => null,
        );
        $viewdKelulusan = $this->Viewd_kelulusan->search($rules)->result();
        foreach ($viewdKelulusan as $a) {
            $rules = array(
                'database'  => null,
                'select'    => null,
                'where'     => array(
                    'idd_kelulusan' => $a->idd_kelulusan,
                    'pembayaran' => 'SUDAH'
                ),
                'or_where'  => null,
                'like'      => null,
                'or_like'   => null,
                'order'     => 'date_created ASC',
                'limit'     => null,
                'group_by'  => null,
            );
            $viewdPembayaran = $this->Viewd_pembayaran->search($rules);
            $first = true;
            foreach ($viewdPembayaran->result() as $b) {
                if ($viewdPembayaran->num_rows() > 1) {
                    if ($first) {
                        // do something
                        // echo $a->idd_kelulusan . ' - ' . $b->pembayaran . ' - ' .$b->date_updated .'<br>';
                        $rules = array(
                            'where'     => array(
                                'idd_pembayaran' => $b->idd_pembayaran,
                            ),
                            'or_where'  => null,
                            'like'      => null,
                            'or_like'   => null,
                            'data'      => array(
                                'pembayaran' => 'EXPIRED',
                            ), // not null
                        );
                        $fb = $this->Tbd_pembayaran->update($rules);
                        if (!$fb['status']) {
                            $updated++;
                        } else {
                            $error++;
                        }
                        $first = false;
                    }
                }
            }
        }
        $keterangan = "Generate Update Status Pembayaran Double. Updated: $updated, Error: $error";
        echo date('Y-m-d H:i:s') . ' CJ11: ' . $keterangan . " \r\n";
    }

    // Update Tanggal Pembayaran ke Kelulusan
    function CJ12()
    {
        $created = $updated = $error = 0;
        $rules = array(
            'database'  => null,
            'select'    => null,
            'where'     => array(
                'pembayaran' => 'SUDAH'
            ),
            'or_where'  => null,
            'like'      => null,
            'or_like'   => null,
            'order'     => null,
            'limit'     => null,
            'group_by'  => null,
        );
        $viewdPembayaran = $this->Viewd_pembayaran->search($rules)->result();
        foreach ($viewdPembayaran as $a) {
            $rules = array(
                'where'     => array(
                    'idd_kelulusan' => $a->idd_kelulusan,
                ),
                'or_where'  => null,
                'like'      => null,
                'or_like'   => null,
                'data'      => array(
                    'tgl_pembayaran' => $a->date_updated,
                ), // not null
            );
            $fb = $this->Tbd_kelulusan->update($rules);
            if (!$fb['status']) {
                $updated++;
            } else {
                $error++;
            }
        }
        $keterangan = "Generate Update Tanggal Pembayaran Ke Kelulusan. Updated: $updated, Error: $error";
        echo date('Y-m-d H:i:s') . ' CJ12: ' . $keterangan . " \r\n";
    }

    // Generate Setting Berdasarkan Tipe Ujian
    function CJ13()
    {
        $created = $updated = $error = 0;
        $rules = array(
            'database'  => null,
            'select'    => null,
            'where'     => array(
                'status' => 'YA'
            ),
            'or_where'  => null,
            'like'      => null,
            'or_like'   => null,
            'order'     => null,
            'limit'     => null,
            'group_by'  => null,
        );
        $tbsTipeUjian = $this->Tbs_tipe_ujian->search($rules)->result();
        foreach ($tbsTipeUjian as $a) {
            $rules = array(
                'database'  => null,
                'select'    => null,
                'where'     => array(
                    'ids_tipe_ujian' => $a->ids_tipe_ujian
                ),
                'or_where'  => null,
                'like'      => null,
                'or_like'   => null,
                'order'     => null,
                'limit'     => null,
                'group_by'  => null,
            );
            $tbpSetting = $this->Tbp_setting->search($rules);
            if ($tbpSetting->num_rows() == 0) {
                $rules = array(
                    'database'  => null,
                    'select'    => null,
                    'where'     => array(
                        'ids_tipe_ujian' => 2
                    ),
                    'or_where'  => null,
                    'like'      => null,
                    'or_like'   => null,
                    'order'     => null,
                    'limit'     => null,
                    'group_by'  => null,
                );
                $tbpSettingCBT = $this->Tbp_setting->search($rules)->result();
                foreach ($tbpSettingCBT as $b) {
                    $data = array(
                        'ids_tipe_ujian' => $a->ids_tipe_ujian,
                        'nama_setting' => $b->nama_setting,
                        'setting' => $b->setting,
                        'created_by' => 1,
                        'updated_by' => 1
                    );
                    $fb = $this->Tbp_setting->create($data);
                    if (!$fb['status']) {
                        $created++;
                    } else {
                        $error++;
                    }
                }
            }
        }
        $keterangan = "Generate Setting Berdasarkan Tipe Ujian. Create: $created, Error: $error";
        echo date('Y-m-d H:i:s') . ' CJ13: ' . $keterangan . " \r\n";
    }

    // Generate Tipe File Berdasarkan Jalur Masuk atau Tipe Ujian
    function CJ14()
    {
        $created = $updated = $error = 0;
        $rules = array(
            'database'  => null,
            'select'    => null,
            'where'     => array(
                'status' => 'YA'
            ),
            'or_where'  => null,
            'like'      => null,
            'or_like'   => null,
            'order'     => null,
            'limit'     => null,
            'group_by'  => null,
        );
        $tbsTipeUjian = $this->Tbs_tipe_ujian->search($rules)->result();
        foreach ($tbsTipeUjian as $a) {
            $rules = array(
                'database'  => null,
                'select'    => null,
                'where'     => array(
                    'ids_tipe_ujian' => 1
                ),
                'or_where'  => null,
                'like'      => null,
                'or_like'   => null,
                'order'     => null,
                'limit'     => null,
                'group_by'  => null,
            );
            $tbpSettingCBT = $this->Tbp_setting->search($rules)->result();
            foreach ($tbpSettingCBT as $b) {
                $rules = array(
                    'database'  => null,
                    'select'    => null,
                    'where'     => array(
                        'ids_tipe_ujian' => $a->ids_tipe_ujian,
                        'nama_setting' => $b->nama_setting,
                    ),
                    'or_where'  => null,
                    'like'      => null,
                    'or_like'   => null,
                    'order'     => null,
                    'limit'     => null,
                    'group_by'  => null,
                );
                $tbpSetting2 = $this->Tbp_setting->search($rules)->num_rows();
                if ($tbpSetting2 == 0) {
                    $data = array(
                        'ids_tipe_ujian' => $a->ids_tipe_ujian,
                        'nama_setting' => $b->nama_setting,
                        'setting' => $b->setting,
                        'created_by' => 1,
                        'updated_by' => 1
                    );
                    $fb = $this->Tbp_setting->create($data);
                    if (!$fb['status']) {
                        $created++;
                    } else {
                        $error++;
                    }
                } else {
                    // $error++;
                }
            }
        }
        $keterangan = "Generate Setting Berdasarkan Tipe Ujian CBT. Create: $created, Error: $error";
        echo date('Y-m-d H:i:s') . ' CJ14: ' . $keterangan . " \r\n";
    }

    // Kirim Email
    function CJ15()
    {
        $success = $error = $err_update = 0;
        $keterangan = null;
        $rules = array(
            'database'  => null,
            'select'    => null,
            'where'     => array(
                'semail' => 'YA',
                'status_email' => 'TIDAK',
            ),
            'or_where'  => null,
            'like'      => null,
            'or_like'   => null,
            'order'     => null,
            'limit'     => array('awal' => 0, 'akhir' => 50),
            'group_by'  => null,
        );
        $viewNotif = $this->View_notif->search($rules);
        if ($viewNotif->num_rows() == 0) {
            $keterangan = 'Tidak ada data yang akan dikirim. \r\n';
        } else {
            foreach ($viewNotif->result() as $a) {
                $judul = $a->judul;
                $isi = $a->isi;
                $data = array(
                    'nama' => $a->nama,
                    'email' => $a->email,
                    'nmr_tlpn' => $a->nmr_tlpn
                );
                if (preg_match_all("/{{(.*?)}}/", $judul, $m)) {
                    foreach ($m[1] as $i => $varname) {
                        $judul = str_replace($m[0][$i], sprintf('%s', $data[$varname]), $judul);
                    }
                }
                if (preg_match_all("/{{(.*?)}}/", $isi, $m)) {
                    foreach ($m[1] as $i => $varname) {
                        $isi = str_replace($m[0][$i], sprintf('%s', $data[$varname]), $isi);
                    }
                }
                $rules = array(
                    'to' => $a->email,
                    'cc' => "appsptipd@uinsgd.ac.id, contact.pmb@uinsgd.ac.id", //optional
                    'sender' => "contact.pmb@uinsgd.ac.id", //optional
                    'replyTo' => "contact.pmb@uinsgd.ac.id", //optional
                    'subject' => $judul,
                    'html' => $isi
                );
                $response = $this->master->sendEmail($rules);
                if ($response->code == 200) {
                    $success++;
                    $rules = array(
                        'where'     => array(
                            'id_notif' => $a->id_notif
                        ),
                        'or_where'  => null,
                        'like'      => null,
                        'or_like'   => null,
                        'data'      => array(
                            'email' => 'YA',
                        ), // not null
                    );
                    $fb = $this->Tbl_notif->update($rules);
                    if ($fb['status']) {
                        $err_update++;
                    }
                } else {
                    $error++;
                    $rules = array(
                        'where'     => array(
                            'id_notif' => $a->id_notif
                        ),
                        'or_where'  => null,
                        'like'      => null,
                        'or_like'   => null,
                        'data'      => array(
                            'email' => 'ERROR',
                        ), // not null
                    );
                    $fb = $this->Tbl_notif->update($rules);
                    if ($fb['status']) {
                        $err_update++;
                    }
                }
            }
        }
        $keterangan = "Generate Kirim Email. Sukses: $success, Gagal Kirim: $error, Error Update: $err_update";
        echo date('Y-m-d H:i:s') . ' CJ15: ' . $keterangan . " \r\n";
    }

    // Generate Create Bobot Nilai Jurusan
    function CJ16()
    {
        $keterangan = null;
        $created = $error = 0;
        $jurusan = $this->master->read("jurusan/?status=YA&page=1&limit=100");
        if ($jurusan->code != 200) {
            $keterangan = "Generate Create Bobot Nilai Jurusan. Error Code: $jurusan->code. Error Message: $jurusan->message";
        } else {
            foreach ($jurusan->data->data as $a) {
                $rules = array(
                    'database'  => null,
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
                $num = $this->Tbs_bobot_jurusan->search($rules)->num_rows();
                if ($num == 0) {
                    $rules = array(
                        'kode_jurusan' => $a->kode_jurusan,
                        'tpa' => 0,
                        'ips' => 0,
                        'ipa' => 0,
                        'btq' => 0,
                        'tkd' => 0,
                        'keislaman' => 0,
                        'bhs_arab' => 0,
                        'bhs_inggris' => 0,
                        'bhs_indonesia' => 0,
                        'pembagi' => 0,
                        'created_by' => 1,
                        'updated_by' => 1,
                    );
                    $fb = $this->Tbs_bobot_jurusan->create($rules);
                    if (!$fb['status']) {
                        $created++;
                    } else {
                        $error++;
                    }
                }
            }
            $keterangan = "Generate Create Bobot Nilai Jurusan. Created: $created, Error: $error";
        }
        echo date('Y-m-d H:i:s') . ' CJ16: ' . $keterangan . " \r\n";
    }

    // Generate Data Awal Kelulusan (Status Belum Lulus)
    function CJ17($tahun = null)
    {
        if ($tahun == null) {
            $tahun = date('Y');
        }
        $created = $error = 0;
        // Get data formuir
        $rules = array(
            'database'  => null, //Default database master
            'select'    => null,
            'where'     => array(
                'formulir' => 'SUDAH',
                'pembayaran' => 'SUDAH',
                'YEAR(date_created)' => $tahun,
            ),
            'or_where'  => null,
            'like'      => null,
            'or_like'   => null,
            'order'     => null,
            'limit'     => null,
            'group_by'  => null,
        );
        $tbpFormulir = $this->Tbp_formulir->search($rules)->result();
        foreach ($tbpFormulir as $a) {
            // Check data kelulusan
            $rules = array(
                'database'  => null, //Default database master
                'select'    => null,
                'where'     => array(
                    'idp_formulir' => $a->idp_formulir,
                ),
                'or_where'  => null,
                'like'      => null,
                'or_like'   => null,
                'order'     => null,
                'limit'     => null,
                'group_by'  => null,
            );
            $num = $this->Tbp_kelulusan->search($rules)->num_rows();
            if ($num == 0) {
                // Create data kelulusan
                $rules = array(
                    'idp_formulir' => $a->idp_formulir,
                    'kode_jurusan' => '000',
                    'total' => '0',
                    'lulus' => 'BELUM',
                    'nama_penitip' => '-',
                    'keterangan' => '-',
                    'created_by' => 1,
                    'updated_by' => 1
                );
                $fb = $this->Tbp_kelulusan->create($rules);
                if (!$fb['status']) {
                    $created++;
                } else {
                    $error++;
                }
            }
        }
        $keterangan = "Generate Data Awal Kelulusan (Status Belum Lulus). Created: $created, Error: $error";
        echo date('Y-m-d H:i:s') . ' CJ17: ' . $keterangan . " \r\n";
    }

    //cek file tidak ada
    function CJ18($ids_tipe_ujian)
    {
        $rules = array(
            'database'  => null,
            'select'    => null,
            'where'     => array(
                'pembayaran' => 'SUDAH',
                'formulir' => 'SUDAH',
                'YEAR(date_created)' => date('Y'),
                'ids_tipe_ujian' => $ids_tipe_ujian
            ),
            'or_where'  => null,
            'like'      => null,
            'or_like'   => null,
            'order'     => null,
            'limit'     => null,
            'group_by'  => null,
        );
        $tbpFormulir = $this->Viewp_formulir->search($rules)->result();
        $num = 0;
        foreach ($tbpFormulir as $a) {
            $rules = array(
                'database'  => null,
                'select'    => null,
                'where'     => array(
                    'id_user' => $a->created_by
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
                'database'  => null,
                'select'    => null,
                'where'     => array(
                    'idp_formulir' => $a->idp_formulir,
                ),
                'or_where'  => null,
                'like'      => null,
                'or_like'   => null,
                'order'     => null,
                'limit'     => null,
                'group_by'  => null,
            );
            $tbpFile = $this->Tbp_file->search($rules);
            if ($tbpFile->num_rows() == 0) {
                $num++;
                echo $a->nomor_peserta . ',' . $a->nama . ',' . $tblUsers->nmr_tlpn . '<br>';
            }
        }
        echo $num;
    }

    // Kirim Whatsapp
    function CJ19()
    {
        $success = $error = $err_update = 0;
        $keterangan = null;
        $rules = array(
            'database'  => null,
            'select'    => null,
            'where'     => array(
                'swhatsapp' => 'YA',
                'whatsapp' => 'TIDAK',
            ),
            'or_where'  => null,
            'like'      => null,
            'or_like'   => null,
            'order'     => null,
            'limit'     => array('awal' => 0, 'akhir' => 200),
            'group_by'  => null,
        );
        $viewNotif = $this->View_notif->search($rules);
        if ($viewNotif->num_rows() == 0) {
            $keterangan = 'Tidak ada data yang akan dikirim. \r\n';
        } else {
            foreach ($viewNotif->result() as $a) {
                $isi = $a->isi;
                $request = array(
                    'target' => $a->nmr_tlpn, // Required
                    'message' => $isi, // Optional
                    'url' => null, // Optional
                    'filename' => null, // Optional
                    'schedule' => null, // Optional
                    'delay' => rand(120, 360),
                    'countryCode' => null, // Optional, Default 62
                    'buttonJSON' => null, // Optional
                    'templateJSON' => null, // Optional
                    'listJSON' => null, // Optional
                );
                $fb = $this->whatsapp->send($request);
                $response = json_decode($fb['response']);
                if ($response->status) {
                    $success++;
                    $rules = array(
                        'where'     => array(
                            'id_notif' => $a->id_notif
                        ),
                        'or_where'  => null,
                        'like'      => null,
                        'or_like'   => null,
                        'data'      => array(
                            'whatsapp' => 'YA',
                        ), // not null
                    );
                    $fb = $this->Tbl_notif->update($rules);
                    if ($fb['status']) {
                        $err_update++;
                    }
                } else {
                    $error++;
                    $rules = array(
                        'where'     => array(
                            'id_notif' => $a->id_notif
                        ),
                        'or_where'  => null,
                        'like'      => null,
                        'or_like'   => null,
                        'data'      => array(
                            'whatsapp' => 'ERROR',
                        ), // not null
                    );
                    $fb = $this->Tbl_notif->update($rules);
                    if ($fb['status']) {
                        $err_update++;
                    }
                }
            }
        }
        $keterangan = "Generate Kirim Whatsapp. Sukses: $success, Gagal Kirim: $error, Error Update: $err_update";
        echo date('Y-m-d H:i:s') . ' CJ19: ' . $keterangan . " \r\n";
    }

    // cek biodata tidak ada
    function CJ20($tabel = null)
    {
        if ($tabel == 'pembayaran') {
            $tabel2 = $this->Viewp_pembayaran;
        } else {
            $tabel2 = $this->Viewp_biodata;
        }
        $rules = array(
            'database'  => null,
            'select'    => null,
            'where'     => array(
                'pembayaran' => 'SUDAH',
                'formulir' => 'SUDAH',
                'YEAR(date_created)' => date('Y'),
            ),
            'or_where'  => null,
            'like'      => null,
            'or_like'   => null,
            'order'     => null,
            'limit'     => null,
            'group_by'  => null,
        );
        $tbpFormulir = $tabel2->search($rules)->result();
        $num = 0;
        foreach ($tbpFormulir as $a) {
            $rules = array(
                'database'  => null,
                'select'    => null,
                'where'     => array(
                    'idp_formulir' => $a->idp_formulir,
                ),
                'or_where'  => null,
                'like'      => null,
                'or_like'   => null,
                'order'     => null,
                'limit'     => null,
                'group_by'  => null,
            );
            $tbpBiodata = $this->Viewp_biodata->search($rules);
            if ($tbpBiodata->num_rows() == 0) {
                $num++;
                echo $a->nomor_peserta . ',' . $a->nama . '<br>';
            }
        }
        echo $num;
    }

    // Generate nilai min dan max dari total kelulusan disimpan pada tabel daya tampung
    function CJ21($tahun = null)
    {
        $updated = $error = 0;
        if ($tahun == null) {
            $tahun = date('Y');
        }

        $rules = array(
            'database'  => null,
            'select'    => null,
            'where'     => array(
                'YEAR(date_created)' => $tahun,
            ),
            'or_where'  => null,
            'like'      => null,
            'or_like'   => null,
            'order'     => null,
            'limit'     => null,
            'group_by'  => null,
        );
        $viewDayaTampung = $this->Tbs_daya_tampung->search($rules)->result();
        foreach ($viewDayaTampung as $a) {
            // Nilai Min
            $rules = array(
                'database'  => null,
                'select'    => "MIN(total) as nilai_min",
                'where'     => array(
                    'kode_jurusan' => $a->kode_jurusan,
                    'ids_tipe_ujian' => 1,
                    'lulus' => 'YA',
                    'YEAR(date_created)' => $tahun,
                ),
                'or_where'  => null,
                'like'      => null,
                'or_like'   => null,
                'order'     => null,
                'limit'     => null,
                'group_by'  => null,
            );
            $nmin = $this->Viewp_kelulusan->search($rules)->row();
            // Nilai Max
            $rules = array(
                'database'  => null,
                'select'    => "MAX(total) as nilai_max",
                'where'     => array(
                    'kode_jurusan' => $a->kode_jurusan,
                    'ids_tipe_ujian' => 1,
                    'lulus' => 'YA',
                    'YEAR(date_created)' => $tahun,
                ),
                'or_where'  => null,
                'like'      => null,
                'or_like'   => null,
                'order'     => null,
                'limit'     => null,
                'group_by'  => null,
            );
            $nmax = $this->Viewp_kelulusan->search($rules)->row();
            // Update Daya Tampung
            $rules = array(
                'where'     => array(
                    'ids_daya_tampung' => $a->ids_daya_tampung
                ),
                'or_where'  => null,
                'like'      => null,
                'or_like'   => null,
                'data'      => array(
                    'nilai_min' => $nmin->nilai_min,
                    'nilai_max' => $nmax->nilai_max,
                ), // not null
            );
            $fb = $this->Tbs_daya_tampung->update($rules);
            if (!$fb['status']) {
                $updated++;
            } else {
                $error++;
            }
        }
        $keterangan = "Generate Nilai Min dan Max. Sukses: $updated, Gagal: $error";
        echo date('Y-m-d H:i:s') . ' CJ21: ' . $keterangan . " \r\n";
    }

    // Generate Plot Peserta Ujian berdasarkan Tipe Ujian per 25 orang setiap jadwal dan ruangan
    function CJ22($ids_tipe_ujian, $tahun = null)
    {
        $created = $error = $count = 0;
        if ($tahun == null) {
            $tahun = date('Y');
        }

        // Get Formulir
        $rules = array(
            'where'     => array(
                'ids_tipe_ujian' => $ids_tipe_ujian,
                'YEAR(date_created)' => $tahun,
                'formulir' => 'SUDAH',
                'pembayaran' => 'SUDAH',
            ),
        );
        $viewsFormulir = $this->Tbp_formulir->search($rules)->result();
        foreach ($viewsFormulir as $a) {
            // Get data jadwal ujian
            $rules = array(
                'where'     => array(
                    'ids_tipe_ujian' => $ids_tipe_ujian,
                    'YEAR(date_created)' => $tahun,
                    'status' => 'YA',
                    'quota >' => 0,
                ),
                'order'     => 'tanggal ASC, ruangan ASC, jam_awal ASC, quota ASC',
                'limit'     => 1,
            );
            $viewsJadwal = $this->Views_jadwal->search($rules);

            //Update jadwal ujian
            if ($viewsJadwal->num_rows() > 0) {
                $jadwal = $viewsJadwal->row();
                $rules = array(
                    'where'     => array(
                        'idp_formulir' => $a->idp_formulir,
                    ),
                    'data'      => array(
                        'ids_jadwal' => $jadwal->ids_jadwal,
                    ), // not null
                );
                $fb = $this->Tbp_jadwal->update($rules);
                if (!$fb['status']) {
                    $created++;
                    // Update quota
                    $rules = array(
                        'where'     => array(
                            'ids_jadwal' => $jadwal->ids_jadwal,
                        ),
                        'data'      => array(
                            'quota' => $jadwal->quota - 1,
                        ), // not null
                    );
                    $this->Tbs_jadwal->update($rules);
                } else {
                    $error++;
                }
            } else {
                $count++;
            }
        }
        $keterangan = "Generate Plot Peserta Ujian. Created: $created, Error: $error, Jadwal Tidak Tersedia: $count";
        echo date('Y-m-d H:i:s') . ' CJ22: ' . $keterangan . " \r\n";
    }
}
