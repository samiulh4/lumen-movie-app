<?php
namespace App\Traits;

trait helperTrait{

    function customEncode($data)
    {
        if(empty($data)){
            return '';
        }
        //$encryptionKey = "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W10.vGltQ-FTHLlAMrO-yxb23XKv5gzpu2mjgcalZSeHLvY";
        //$iv = openssl_random_pseudo_bytes(16);
        //$encryptedData = openssl_encrypt($data, 'AES-256-CBC', $encryptionKey, 0, $iv);
        //return base64_encode($iv . $encryptedData); // Combine IV and encrypted data
        return base64_encode($data);
    }
    function customDecode($data)
    {
        if(empty($data)){
            return '';
        }
        //$encryptionKey = "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W10.vGltQ-FTHLlAMrO-yxb23XKv5gzpu2mjgcalZSeHLvY";
        //$data = base64_decode($data);
        //$iv = substr($data, 0, 16); // Extract IV from the data
        //$encryptedData = substr($data, 16); // Extract encrypted data
        // return openssl_decrypt($encryptedData, 'AES-256-CBC', $encryptionKey, 0, $iv);
        return base64_decode($data);
    }
}// end -:-  helperTrait


