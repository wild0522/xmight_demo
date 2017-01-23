<?php

namespace App\Libraries;

use Illuminate\Support\Facades\Storage;

/**
 * Created by PhpStorm.
 * User: wild
 * Date: 2017/1/21
 * Time: 下午1:15
 */
class CallApiLibrary
{
    private static function _token()
    {
        return 'Authorization: Bearer ' . Storage::disk('local')->get('file.txt');
    }
    
    static function post($url, $data = array())
    {
        $json_data = json_encode($data);

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($json_data),
                self::_token()
            )
        );
        $result = json_decode(curl_exec($ch), TRUE);
        curl_close($ch);
        
        return $result;
    }
    
    static function get($url)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                self::_token()
            )
        );
        $result = json_decode(curl_exec($ch), TRUE);
        curl_close($ch);
        
        return $result;
    }
    
    static function patch($url, $data)
    {
        $json_data = json_encode($data);

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PATCH');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($json_data),
                self::_token()
            )
        );
        $result = json_decode(curl_exec($ch), TRUE);
        curl_close($ch);

        return $result;
    }
    
    static function delete($url)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                self::_token()
            )
        );
        $result = json_decode(curl_exec($ch), TRUE);
        curl_close($ch);

        return $result;
    }
}