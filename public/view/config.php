<?php

$localhost = array(
    '127.0.0.1',
    '::1'
);


global $ffmpegConfig;

if(in_array($_SERVER['REMOTE_ADDR'], $localhost)){

    define("MODE", "dev");
    $ffmpegConfig = [
        'ffmpeg.binaries'  => 'D:\Program Files\ffmpeg\bin\ffmpeg.exe',
        'ffprobe.binaries' => 'D:\Program Files\ffmpeg\bin\ffprobe.exe',
        'timeout'          => 3600, // The timeout for the underlying process
        'ffmpeg.threads'   => 12,   // The number of threads that FFmpeg should use
    ];
}else{

    define("MODE", "live");
    $ffmpegConfig = [
        'ffmpeg.binaries'  => '/usr/bin/ffmpeg',
        'ffprobe.binaries' => '/usr/bin/ffprobe',
        'timeout'          => 3600, // The timeout for the underlying process
        'ffmpeg.threads'   => 12,   // The number of threads that FFmpeg should use
    ];
}








function str_encryptaesgcm($plaintext, $password, $encoding = null) {
    if ($plaintext != null && $password != null) {
        $keysalt = openssl_random_pseudo_bytes(16);
        $key = hash_pbkdf2("sha512", $password, $keysalt, 20000, 32, true);
        $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length("aes-256-gcm"));
        $tag = "";
        $encryptedstring = openssl_encrypt($plaintext, "aes-256-gcm", $key, OPENSSL_RAW_DATA, $iv, $tag, "", 16);
        return $encoding == "hex" ? bin2hex($keysalt.$iv.$encryptedstring.$tag) : ($encoding == "base64" ? base64_encode($keysalt.$iv.$encryptedstring.$tag) : $keysalt.$iv.$encryptedstring.$tag);
    }
}

function str_decryptaesgcm($encryptedstring, $password, $encoding = null) {
    if ($encryptedstring != null && $password != null) {
        $encryptedstring = $encoding == "hex" ? hex2bin($encryptedstring) : ($encoding == "base64" ? base64_decode($encryptedstring) : $encryptedstring);
        $keysalt = substr($encryptedstring, 0, 16);
        $key = hash_pbkdf2("sha512", $password, $keysalt, 20000, 32, true);
        $ivlength = openssl_cipher_iv_length("aes-256-gcm");
        $iv = substr($encryptedstring, 16, $ivlength);
        $tag = substr($encryptedstring, -16);
        return openssl_decrypt(substr($encryptedstring, 16 + $ivlength, -16), "aes-256-gcm", $key, OPENSSL_RAW_DATA, $iv, $tag);
    }
}


/*
    function generateURL
    @param url data query

*/
function generateURL($data){

    if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
        $url = "https://"; 
    }
    else  {
        $url = "http://";   
    }  
    $url.= $_SERVER['HTTP_HOST']."/?d="; 
    return $url.urlencode(str_encryptaesgcm($data, "\$abhi%@2bc/" , "base64"));

    // $url.= $_SERVER['HTTP_HOST'].$data; 
    // return $url;
}


/*
    function decryptURLData
    @param url data query

*/
function decryptURLData($data){
    $data = str_replace(" ", "+", urldecode($data));
    $path = str_decryptaesgcm($data, "\$abhi%@2bc/", "base64");
    return $path;
    // return $data;
}
