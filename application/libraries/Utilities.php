<?php

/**
 * PHP Utilities porting for Codeigniter
 *
 * @package        	CodeIgniter
 * @subpackage    	Libraries
 * @category    	Libraries
 * @porting author	alfi.gusman.9f@gmail.com
 * @original author	http://alfi-gusman.web.id
 *
 * @version		1.3
 */

defined('BASEPATH') or exit('No direct script access allowed');

class Utilities
{

    protected $CI;

    public function __construct()
    {
        // Assign the CodeIgniter super-object
        $this->CI = &get_instance();
    }

    /* Get IP Address */
    public function getIPAddress()
    {
        $ipaddress = '';
        if (isset($_SERVER['HTTP_CLIENT_IP']))
            $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
        else if (isset($_SERVER['HTTP_X_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
        else if (isset($_SERVER['HTTP_X_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
        else if (isset($_SERVER['HTTP_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
        else if (isset($_SERVER['HTTP_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_FORWARDED'];
        else if (isset($_SERVER['REMOTE_ADDR']))
            $ipaddress = $_SERVER['REMOTE_ADDR'];
        else
            $ipaddress = 'UNKNOWN';
        return $ipaddress;
    }

    /* Security reCAPCHA */
    public function reCAPTCHA($send)
    {
        $url = 'https://www.google.com/recaptcha/api/siteverify';
        $secret = '6LcznyQUAAAAAAkK6IEHZ6gU5SRs2Vkvndn_5Wi0';
        $response = file_get_contents($url . '?secret=' . $secret . '&response=' . $send . '&remoteip-' . $_SERVER['REMOTE_ADDR']);
        $data = json_decode($response);
        return $data->success;
    }

    /* CURL
    $request = array(
        "url" => null, //Not Null
        "method" => null, // GET, POST, PUT, PATCH, DELETE
        "header" => null,
        "request" => null,
    );
    */

    public function curl($data)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_URL, $data['url']);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $data['method']);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data['request']);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        if ($data['header'] != null) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $data['header']);
        }
        $respond = curl_exec($ch); //print_r($data);
        curl_close($ch);
        $respond = json_decode($respond);
        return $respond;
    }

    public function getPagination($num_row, $limit, $currentpage)
    {
        $limitdata = ($limit !== null) ? $limit : $_ENV['LIMIT_DATA'];
        $totalpage = ceil(($num_row / $limitdata));
        $pagination = array();
        if ($num_row > $limitdata) {
            for ($i = 1; $i <= $totalpage; $i++) {
                $pagination[] = $i;
            }
        }
        return array(
            'totaldata' => $num_row,
            'totalpagination' => $totalpage,
            'currentpage' => ($currentpage !== null) ? $currentpage : 0,
        );
    }

    public function Rupiah($angka)
    {

        $hasil_rupiah = "Rp " . number_format($angka, 2, ',', '.');
        return $hasil_rupiah;
    }

    public function convertDateTime($date, $format = 'Y-m-d H:i:s')
    {
        $tz1 = 'UTC';
        $tz2 = 'Asia/Jakarta'; // UTC +7
        $d = new DateTime($date, new DateTimeZone($tz1));
        $d->setTimeZone(new DateTimeZone($tz2));
        return $d->format($format);
    }

    public function hari_ini($tanggal)
    {
        $hari = date('D', strtotime($tanggal));
        switch ($hari) {
            case 'Sun':
                $hari_ini = "Minggu";
                break;
            case 'Mon':
                $hari_ini = "Senin";
                break;
            case 'Tue':
                $hari_ini = "Selasa";
                break;
            case 'Wed':
                $hari_ini = "Rabu";
                break;
            case 'Thu':
                $hari_ini = "Kamis";
                break;
            case 'Fri':
                $hari_ini = "Jumat";
                break;
            case 'Sat':
                $hari_ini = "Sabtu";
                break;
            default:
                $hari_ini = "Tidak di ketahui";
                break;
        }
        return $hari_ini;
    }

    public function tgl_indo($tanggal)
    {
        $bulan = array(
            1 =>   'Januari',
            'Februari',
            'Maret',
            'April',
            'Mei',
            'Juni',
            'Juli',
            'Agustus',
            'September',
            'Oktober',
            'November',
            'Desember'
        );
        $pecahkan = explode('-', $tanggal);
        // variabel pecahkan 0 = tanggal
        // variabel pecahkan 1 = bulan
        // variabel pecahkan 2 = tahun
        return $pecahkan[2] . ' ' . $bulan[(int)$pecahkan[1]] . ' ' . $pecahkan[0];
    }

    public function uktToRomawi($kategori)
    {
        if ($kategori == 'K1') {
            $romawi = 'I';
        } else if ($kategori == "K2") {
            $romawi = 'II';
        } else if ($kategori == "K3") {
            $romawi = 'III';
        } else if ($kategori == "K4") {
            $romawi = 'IV';
        } else if ($kategori == "K5") {
            $romawi = 'V';
        } else if ($kategori == "K6") {
            $romawi = 'VI';
        } else if ($kategori == "K7") {
            $romawi = 'VII';
        } else if ($kategori == "K8") {
            $romawi = 'VIII';
        } else if ($kategori == "K9") {
            $romawi = 'IX';
        }

        return $romawi;
    }

    public function NPForVA($np)
    {
        $num = strlen($np);
        if ($num == 10) {
            return $np;
        } else if ($num >= 5 && $num < 10) {
            $nol = '';
            $nums = (10 - $num);
            for ($i = 1; $i <= $nums; $i++) {
                $nol .= '0';
            }
            return $np . $nol;
        } else if ($num > 10) {
            return substr($np, -10);
        } else {
            return false;
        }
    }
}
