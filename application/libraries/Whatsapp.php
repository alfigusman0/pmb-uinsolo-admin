<?php

/**
 * PHP Fonnte Api porting for Codeigniter
 *
 * @package        	CodeIgniter
 * @subpackage    	Libraries
 * @category    	Libraries
 * @porting author	alfi.gusman.9f@gmail.com
 * @original author	http://alfi-gusman.web.id
 * 
 * @version		1.0
 */

defined('BASEPATH') or exit('No direct script access allowed');

class Whatsapp
{
    protected $CI;

    public function __construct()
    {
        // Assign the CodeIgniter super-object
        $this->CI = &get_instance();
        $this->CI->load->model('Ciresponses');
    }

    /* Send Message
    $request = array(
        'target' => null, // Required
        'message' => null, // Optional
        'url' => null, // Optional
        'filename' => null, // Optional
        'schedule' => null, // Optional
        'delay' => rand(60, 120),
        'countryCode' => null, // Optional, Default 62
        'buttonJSON' => null, // Optional
        'templateJSON' => null, // Optional
        'listJSON' => null, // Optional
    );
    */
    public function Send($request)
    {
        $parrams = array(
            'url' => $_ENV['WA_URL'] . 'send',
            'method' => 'POST',
            'header' => array(
                "Authorization: " . $_ENV['WA_TOKEN'],
            ),
            'request' => $request,
        );
        $data = array(
            'tipe' => 'Whatsapp - Send Message',
            'headers' => json_encode($this->CI->input->request_headers()),
            'request' => json_encode($parrams),
            'response' => json_encode($this->CI->utilities->curl($parrams))
        );
        $this->CI->Ciresponses->create($data);
        return $data;
    }

    /* Device Profile */
    public function DeviceProfile()
    {
        $parrams = array(
            'url' => $_ENV['WA_URL'] . 'device',
            'method' => 'POST',
            'header' => array(
                "Authorization: " . $_ENV['WA_TOKEN'],
            ),
            'request' => null,
        );
        $data = array(
            'tipe' => 'Whatsapp - Device Profile',
            'headers' => json_encode($this->CI->input->request_headers()),
            'request' => json_encode($parrams),
            'response' => json_encode($this->CI->utilities->curl($parrams))
        );
        $this->CI->Ciresponses->create($data);
        return $data;
    }

    /* Get QR */
    public function GetQR()
    {
        $parrams = array(
            'url' => $_ENV['WA_URL'] . 'qr',
            'method' => 'POST',
            'header' => array(
                "Authorization: " . $_ENV['WA_TOKEN'],
            ),
            'request' => null,
        );
        $data = array(
            'tipe' => 'Whatsapp - Create VA BJBS',
            'headers' => json_encode($this->CI->input->request_headers()),
            'request' => json_encode($parrams),
            'response' => json_encode($this->CI->utilities->curl($parrams))
        );
        $this->CI->Ciresponses->create($data);
        return $data;
    }

    /* Validate Number 
    $request = array(
        'target' => , // Required
        'countryCode' => , // Optional, Default 62
    );
    */
    public function ValidateNumber($request)
    {
        $parrams = array(
            'url' => $_ENV['WA_URL'] . 'validate',
            'method' => 'POST',
            'header' => array(
                "Authorization: " . $_ENV['WA_TOKEN'],
            ),
            'request' => $request,
        );
        $data = array(
            'tipe' => 'Whatsapp - Validate Number',
            'headers' => json_encode($this->CI->input->request_headers()),
            'request' => json_encode($parrams),
            'response' => json_encode($this->CI->utilities->curl($parrams))
        );
        $this->CI->Ciresponses->create($data);
        return $data;
    }

    /* Message Status 
    $request = array(
        'id' => , // Required
    );
    */
    public function Status($request)
    {
        $parrams = array(
            'url' => $_ENV['WA_URL'] . 'status',
            'method' => 'POST',
            'header' => array(
                "Authorization: " . $_ENV['WA_TOKEN'],
            ),
            'request' => $request,
        );
        $data = array(
            'tipe' => 'Whatsapp - Message Status',
            'headers' => json_encode($this->CI->input->request_headers()),
            'request' => json_encode($parrams),
            'response' => json_encode($this->CI->utilities->curl($parrams))
        );
        $this->CI->Ciresponses->create($data);
        return $data;
    }

    /* Disconnect */
    public function Disconnect()
    {
        $parrams = array(
            'url' => $_ENV['WA_URL'] . 'disconnect',
            'method' => 'POST',
            'header' => array(
                "Authorization: " . $_ENV['WA_TOKEN'],
            ),
            'request' => null,
        );
        $data = array(
            'tipe' => 'Whatsapp - Disconnect',
            'headers' => json_encode($this->CI->input->request_headers()),
            'request' => json_encode($parrams),
            'response' => json_encode($this->CI->utilities->curl($parrams))
        );
        $this->CI->Ciresponses->create($data);
        return $data;
    }

    /* Messages */
    public function Messages()
    {
        $parrams = array(
            'url' => $_ENV['WA_URL'] . 'messages',
            'method' => 'POST',
            'header' => array(
                "Authorization: " . $_ENV['WA_TOKEN'],
            ),
            'request' => null,
        );
        $data = array(
            'tipe' => 'Whatsapp - Messages',
            'headers' => json_encode($this->CI->input->request_headers()),
            'request' => json_encode($parrams),
            'response' => json_encode($this->CI->utilities->curl($parrams))
        );
        $this->CI->Ciresponses->create($data);
        return $data;
    }
}
