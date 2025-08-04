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
 * @version		    1.2.0
 * @update		    2025-07-03
 */

defined('BASEPATH') or exit('No direct script access allowed');

use Aws\Result;
use Aws\S3\S3Client;
use Aws\S3\Exception\S3Exception;

class Aws
{
    protected $CI;

    public function __construct()
    {
        // Assign the CodeIgniter super-object
        $this->CI = &get_instance();
    }

    public function checkConnection()
    {
        // Konfigurasi S3
        if ($_ENV['CI_ENV'] == 'development') {
            $s3 = new S3Client(array(
                'version' => $_ENV['AWS_VERSION'],
                'region'  => $_ENV['AWS_REGION'],
                'endpoint' => $_ENV['AWS_URL'],
                'scheme' => 'http',
                'credentials' => array(
                    'key'    => $_ENV['AWS_KEY'],
                    'secret' => $_ENV['AWS_SECRET'],
                ),
                'use_path_style_endpoint' => true,
                'http' => [
                    'verify' => false // Nonaktifkan verifikasi SSL jika menggunakan sertifikat self-signed
                ]
            ));
        } else {
            $s3 = new S3Client(array(
                'version' => $_ENV['AWS_VERSION'],
                'region'  => $_ENV['AWS_REGION'],
                'endpoint' => $_ENV['AWS_URL'],
                'credentials' => array(
                    'key'    => $_ENV['AWS_KEY'],
                    'secret' => $_ENV['AWS_SECRET'],
                ),
                'use_path_style_endpoint' => true,
                'http' => [
                    'verify' => false // Nonaktifkan verifikasi SSL jika menggunakan sertifikat self-signed
                ]
            ));
        }

        try {
            // Cek koneksi dengan mendapatkan daftar bucket
            $result = $s3->listBuckets();
            return [
                'status' => 'success',
                'buckets' => $result['Buckets'] // Daftar bucket
            ];
        } catch (S3Exception $e) {
            return [
                'status' => 'error',
                'message' => $e->getMessage()
            ];
        }
    }

    /*
    Upload S3
    $rules = array(
        'localFilePath' => null, //not null
        'S3FilePath' => null, //not null
        'Content-Type' => null
    );
    */
    public function putobject($rules)
    {
        // Konfigurasi S3
        if ($_ENV['CI_ENV'] == 'development') {
            $s3 = new S3Client(array(
                'version' => $_ENV['AWS_VERSION'],
                'region'  => $_ENV['AWS_REGION'],
                'endpoint' => $_ENV['AWS_URL'],
                'scheme' => 'http',
                'credentials' => array(
                    'key'    => $_ENV['AWS_KEY'],
                    'secret' => $_ENV['AWS_SECRET'],
                ),
                'use_path_style_endpoint' => true,
                'http' => [
                    'verify' => false // Nonaktifkan verifikasi SSL jika menggunakan sertifikat self-signed
                ]
            ));
        } else {
            $s3 = new S3Client(array(
                'version' => $_ENV['AWS_VERSION'],
                'region'  => $_ENV['AWS_REGION'],
                'endpoint' => $_ENV['AWS_URL'],
                'credentials' => array(
                    'key'    => $_ENV['AWS_KEY'],
                    'secret' => $_ENV['AWS_SECRET'],
                ),
                'use_path_style_endpoint' => true,
                'http' => [
                    'verify' => false // Nonaktifkan verifikasi SSL jika menggunakan sertifikat self-signed
                ]
            ));
        }

        try {
            // Mengunggah file ke S3
            $option = array();
            $option['Bucket'] = $_ENV['AWS_BUCKET'];
            $option['SourceFile'] = $rules['localFilePath'];
            $option['Key'] = $rules['S3FilePath'];
            if ($rules['Content-Type'] == null) {
                $option['Content-Type'] = mime_content_type($rules['localFilePath']);
            } else {
                $option['Content-Type'] = $rules['Content-Type'];
            }
            $result = $s3->putObject($option);
            return $result;
        } catch (S3Exception $e) {
            return $e->getMessage();
        }
    }

    /*
    Delete S3
    $rules = array(
        'S3FilePath' => null, //not null
        'versionId' => null
    );
    */
    public function deleteobject($rules)
    {
        // Konfigurasi S3
        if ($_ENV['CI_ENV'] == 'development') {
            $s3 = new S3Client(array(
                'version' => $_ENV['AWS_VERSION'],
                'region'  => $_ENV['AWS_REGION'],
                'endpoint' => $_ENV['AWS_URL'],
                'scheme' => 'http',
                'credentials' => array(
                    'key'    => $_ENV['AWS_KEY'],
                    'secret' => $_ENV['AWS_SECRET'],
                ),
                'use_path_style_endpoint' => true,
                'http' => [
                    'verify' => false // Nonaktifkan verifikasi SSL jika menggunakan sertifikat self-signed
                ]
            ));
        } else {
            $s3 = new S3Client(array(
                'version' => $_ENV['AWS_VERSION'],
                'region'  => $_ENV['AWS_REGION'],
                'endpoint' => $_ENV['AWS_URL'],
                'credentials' => array(
                    'key'    => $_ENV['AWS_KEY'],
                    'secret' => $_ENV['AWS_SECRET'],
                ),
                'use_path_style_endpoint' => true,
                'http' => [
                    'verify' => false // Nonaktifkan verifikasi SSL jika menggunakan sertifikat self-signed
                ]
            ));
        }

        try {
            // Menghapus file dari S3
            $option = array();
            $option['Bucket'] = $_ENV['AWS_BUCKET'];
            $option['Key'] = $rules['S3FilePath'];
            if ($rules['versionId'] != null) {
                $option['versionId'] = $rules['versionId'];
            }
            $result = $s3->deleteObject($option);
            return $result;
        } catch (S3Exception $e) {
            return $e->getMessage();
        }
    }
}
