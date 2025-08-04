<?php

/**
 * PHP Master porting for Codeigniter
 *
 * @package        	CodeIgniter
 * @subpackage    	Libraries
 * @category    	Libraries
 * @porting author	alfi.gusman.9f@gmail.com
 * @original author	http://alfi-gusman.web.id
 * @updated			2025-08-04 15:11
 *
 * @version		    2.2.0
 */

defined('BASEPATH') or exit('No direct script access allowed');
class Master
{
    protected $CI;
    public function __construct()
    {
        // Assign the CodeIgniter super-object
        $this->CI = &get_instance();
        $this->CI->load->helper('cookie');
    }

    /* Auth Login */
    public function Login($username, $password)
    {
        $parrams = array(
            'url' => $_ENV['MASTER_HOST'] . 'auth/login',
            'method' => 'POST',
            "header" => array(
                'Content-Type: application/x-www-form-urlencoded',
                'User-Agent: UINSAID-Api'
            ),
            "request" => http_build_query(array(
                'username' => $username,
                'password' => $password,
            )),
        );
        return $this->CI->utilities->curl($parrams);
    }

    /* Auth Logout */
    public function Logout($token)
    {
        $parrams = array(
            'url' => $_ENV['MASTER_HOST'] . 'auth/logout',
            'method' => 'GET',
            "header" => array(
                "Authorization: Bearer $token",
                'User-Agent: UINSAID-Api'
            ),
            "request" => null,
        );
        return $this->CI->utilities->curl($parrams);
    }

    /* Auth Create Token */
    public function CreateToken($payload)
    {
        $parrams = array(
            'url' => $_ENV['MASTER_HOST'] . 'auth/token/create',
            'method' => 'POST',
            "header" => array(
                'Content-Type: application/json',
                'User-Agent: UINSAID-Api'
            ),
            "request" => json_encode(array(
                'payload' => $payload
            )),
        );
        return $this->CI->utilities->curl($parrams);
    }

    /* Auth Refresh Token */
    public function RefreshToken($token, $payload = null)
    {
        $parrams = array(
            'url' => $_ENV['MASTER_HOST'] . 'auth/token',
            'method' => 'POST',
            "header" => array(
                "Authorization: Bearer $token",
                'Content-Type: application/json',
                'User-Agent: UINSAID-Api'
            ),
            "request" => json_encode(array(
                'payload' => $payload
            )),
        );
        return $this->CI->utilities->curl($parrams);
    }

    /* Auth Cek Token */
    public function CekToken($token)
    {
        $parrams = array(
            'url' => $_ENV['MASTER_HOST'] . 'auth/cek',
            'method' => 'GET',
            "header" => array(
                "Authorization: Bearer $token",
                'User-Agent: UINSAID-Api'
            ),
            "request" => null,
        );
        return $this->CI->utilities->curl($parrams);
    }

    /* Auth Delete Token */
    public function DeleteToken($token, $keterangan)
    {
        $parrams = array(
            'url' => $_ENV['MASTER_HOST'] . 'auth/token',
            'method' => 'DELETE',
            "header" => array(
                "Authorization: Bearer $token",
                'Content-Type: application/json',
                'User-Agent: UINSAID-Api'
            ),
            "request" => json_encode(array(
                'keterangan' => $keterangan
            )),
        );
        return $this->CI->utilities->curl($parrams);
    }

    /* Create
    $rules = array(
        'url' => "",
        'data'=> "",
    );
    */
    public function create($rules)
    {
        $token = $this->CI->input->cookie($_ENV['COOKIE_NAME'], TRUE);
        $parrams = array(
            'url' => $_ENV['MASTER_HOST'] . $rules['url'],
            'method' => 'POST',
            "header" => array(
                "Authorization: Bearer $token",
                'Content-Type: application/x-www-form-urlencoded',
                'User-Agent: UINSAID-Api'
            ),
            "request" => http_build_query($rules['data']),
        );
        return $this->CI->utilities->curl($parrams);
    }

    /* Read
    $url = "";
    */
    public function read($url)
    {
        $token = $this->CI->input->cookie($_ENV['COOKIE_NAME'], TRUE);
        if (empty($token)) {
            $token = $_ENV['MASTER_TOKEN'];
        }
        $parrams = array(
            'url' => $_ENV['MASTER_HOST'] . $url,
            'method' => 'GET',
            "header" => array(
                "Authorization: Bearer $token",
                'User-Agent: UINSAID-Api'
            ),
            "request" => null,
        );
        return $this->CI->utilities->curl($parrams);
    }

    /* Update
    $rules = array(
        'url' => "",
        'data'=> "",
    );
    */
    public function update($rules)
    {
        $token = $this->CI->input->cookie($_ENV['COOKIE_NAME'], TRUE);
        $parrams = array(
            'url' => $_ENV['MASTER_HOST'] . $rules['url'],
            'method' => 'PUT',
            "header" => array(
                "Authorization: Bearer $token",
                'Content-Type: application/x-www-form-urlencoded',
                'User-Agent: UINSAID-Api'
            ),
            "request" => http_build_query($rules['data']),
        );
        return $this->CI->utilities->curl($parrams);
    }

    /* Delete
    $url = "";
    */
    public function delete($url)
    {
        $token = $this->CI->input->cookie($_ENV['COOKIE_NAME'], TRUE);
        $parrams = array(
            'url' => $_ENV['MASTER_HOST'] . $url,
            'method' => 'DELETE',
            "header" => array(
                "Authorization: Bearer $token",
                'User-Agent: UINSAID-Api'
            ),
            "request" => null,
        );
        return $this->CI->utilities->curl($parrams);
    }

    /* Single
    $url = "";
    */
    public function single($url)
    {
        $token = $this->CI->input->cookie($_ENV['COOKIE_NAME'], TRUE);
        if (empty($token)) {
            $token = $_ENV['MASTER_TOKEN'];
        }
        $parrams = array(
            'url' => $_ENV['MASTER_HOST'] . $url,
            'method' => 'GET',
            "header" => array(
                "Authorization: Bearer $token",
                'User-Agent: UINSAID-Api'
            ),
            "request" => null,
        );
        return $this->CI->utilities->curl($parrams);
    }

    /* Send Email
    $rules = array(
        'to' => "",
        'cc' => "",
        'subject' => "",
        'html' => ""
    );
    */
    public function sendEmail($rules)
    {
        $token = $this->CI->input->cookie($_ENV['COOKIE_NAME'], TRUE);
        if (empty($token)) {
            $token = $_ENV['MASTER_TOKEN'];
        }
        $parrams = array(
            'url' => $_ENV['MASTER_HOST'] . 'gmail/send',
            'method' => 'POST',
            "header" => array(
                "Authorization: Bearer $token",
                'Content-Type: application/x-www-form-urlencoded',
                'User-Agent: UINSAID-Api'
            ),
            "request" => http_build_query($rules),
        );
        return $this->CI->utilities->curl($parrams);
    }
}
