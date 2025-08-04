<?php

/**
 * PHP Json Web Token porting for Codeigniter
 *
 * @package        	CodeIgniter
 * @subpackage    	Libraries
 * @category    	Libraries
 * @porting author	alfi.gusman.9f@gmail.com
 * @original author	http://alfi-gusman.web.id
 * @updated			2025-07-09 16:06
 *
 * @version         4.1.0
 */

defined('BASEPATH') or exit('No direct script access allowed');

use \Firebase\JWT\JWT;
use Firebase\JWT\Key;

class Jsonwebtoken
{
    protected $CI;

    public function __construct()
    {
        // Assign the CodeIgniter super-object
        $this->CI = &get_instance();
        $this->CI->load->helper('cookie');
    }

    private function clearSessionAndCookie($cookieName)
    {
        $this->CI->session->sess_destroy();
        delete_cookie($cookieName);
    }

    private function createResponse($status, $message = null, $data = null)
    {
        return array(
            'status' => $status,
            'message' => $message,
            'data' => $data
        );
    }

    public function jwtDecodeSSO($token = null)
    {
        if (empty($token)) {
            $token = $this->CI->input->cookie($_ENV['COOKIE_NAME'], TRUE);
        }

        if (empty($token)) {
            $this->clearSessionAndCookie($_ENV['COOKIE_NAME']);
            return $this->createResponse('error', 'Token tidak boleh kosong.');
        }

        $response = $this->CI->master->CekToken($token);
        if (!$response || $response->code != 200) {
            $this->clearSessionAndCookie($_ENV['COOKIE_NAME']);
            $message = $response ? $response->message : 'Tidak menerima response dari server.';
            return $this->createResponse('error', $message);
        }

        try {
            $decode = JWT::decode($token, new Key($_ENV['MASTER_KEY'], 'HS256'));
            return $this->createResponse('success', null, $decode);
        } catch (Exception $e) {
            $this->clearSessionAndCookie($_ENV['COOKIE_NAME']);
            return $this->createResponse('error', 'Token tidak valid: ' . $e->getMessage());
        }
    }

    public function jwtEncode($cookie_name, $payload)
    {
        $jwt_expires = $_ENV['MASTER_EXPIRED_IN'] || 1;
        $expire_at = time() + (24 * 60 * 60 * $jwt_expires);

        $feedback = $this->CI->pmb->CreateToken($payload);
        if (!$feedback || $feedback->code != 200) {
            $this->clearSessionAndCookie($cookie_name);
            $message = $feedback ? $feedback->message : 'Tidak menerima response dari server.';
            return $this->createResponse('error', $message);
        }

        $cookieJWT = array(
            'name'   => $cookie_name,
            'value'  => $feedback->data->token,
            'expire' => $expire_at,
        );
        $this->CI->input->set_cookie($cookieJWT);

        return $this->createResponse('success', null, $feedback->data->token);
    }

    public function jwtDecode($cookie_name)
    {
        $token = $this->CI->input->cookie($cookie_name, TRUE);

        if (empty($token)) {
            $this->clearSessionAndCookie($cookie_name);
            return $this->createResponse('error', 'Token tidak ditemukan.');
        }

        $feedback = $this->CI->pmb->CekToken($token);
        if (!$feedback || $feedback->code != 200) {
            $this->clearSessionAndCookie($cookie_name);
            $message = $feedback ? $feedback->message : 'Tidak menerima response dari server.';
            return $this->createResponse('error', $message);
        }

        try {
            $decode = JWT::decode($token, new Key($_ENV['MASTER_KEY'], 'HS256'));
            return $this->createResponse('success', null, $decode);
        } catch (Exception $e) {
            $this->clearSessionAndCookie($cookie_name);
            return $this->createResponse('error', 'Token tidak valid: ' . $e->getMessage());
        }
    }

    public function jwtUpdate($cookie_name, $payload)
    {
        $jwt_expires = $_ENV['MASTER_EXPIRED_IN'] || 1;
        $expire_at = time() + (24 * 60 * 60 * $jwt_expires);
        $token = $this->CI->input->cookie($cookie_name, TRUE);

        if (empty($token)) {
            $this->clearSessionAndCookie($cookie_name);
            return $this->createResponse('error', 'Token tidak ditemukan.');
        }

        $feedback = $this->CI->pmb->CekToken($token);
        if (!$feedback || $feedback->code != 200) {
            $this->clearSessionAndCookie($cookie_name);
            $message = $feedback ? $feedback->message : 'Tidak menerima response dari server.';
            return $this->createResponse('error', $message);
        }

        $feedback = $this->CI->pmb->RefreshToken($token, $payload);
        if (!$feedback || $feedback->code != 200) {
            $this->clearSessionAndCookie($cookie_name);
            $message = $feedback ? $feedback->message : 'Tidak menerima response dari server.';
            return $this->createResponse('error', $message);
        }

        $this->clearSessionAndCookie($cookie_name);
        $cookieJWT = array(
            'name'   => $cookie_name,
            'value'  => $token,
            'expire' => $expire_at,
        );
        $this->CI->input->set_cookie($cookieJWT);

        // Delete old token if exists
        $feedback = $this->CI->pmb->DeleteToken($token, 'Update Token');
        if (!$feedback || $feedback->code != 200) {
            $this->clearSessionAndCookie($cookie_name);
            $message = $feedback ? $feedback->message : 'Tidak menerima response dari server.';
            return $this->createResponse('error', $message);
        }

        return true;
    }

    public function jwtDelete($cookie_name, $keterangan)
    {
        $token = $this->CI->input->cookie($cookie_name, TRUE);

        if (empty($token)) {
            $this->clearSessionAndCookie($cookie_name);
            return $this->createResponse('error', 'Token tidak ditemukan.');
        }

        $feedback = $this->CI->pmb->DeleteToken($token, $keterangan);
        if (!$feedback || $feedback->code != 200) {
            $this->clearSessionAndCookie($cookie_name);
            $message = $feedback ? $feedback->message : 'Tidak menerima response dari server.';
            return $this->createResponse('error', $message);
        }

        $this->clearSessionAndCookie($cookie_name);
        return $this->createResponse('success');
    }
}
