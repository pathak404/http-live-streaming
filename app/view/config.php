<?php

$localhost = array(
    '127.0.0.1',
    '::1'
);


if(in_array($_SERVER['REMOTE_ADDR'], $localhost)){

    define("MODE", "dev");

}else{

    define("MODE", "live");
}








function str_encryptaesgcm($plaintext, $password, $encoding = "hex") {
    if ($plaintext != null && $password != null) {
        $keysalt = openssl_random_pseudo_bytes(16);
        $key = hash_pbkdf2("sha512", $password, $keysalt, 20000, 32, true);
        $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length("aes-256-gcm"));
        $tag = "";
        $encryptedstring = openssl_encrypt($plaintext, "aes-256-gcm", $key, OPENSSL_RAW_DATA, $iv, $tag, "", 16);
        return $encoding == "hex" ? bin2hex($keysalt.$iv.$encryptedstring.$tag) : ($encoding == "base64" ? base64_encode($keysalt.$iv.$encryptedstring.$tag) : $keysalt.$iv.$encryptedstring.$tag);
    }
}

function str_decryptaesgcm($encryptedstring, $password, $encoding = "hex") {
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
    return $url.urlencode(str_encryptaesgcm($data, "\$abhi%@2bc/"));

    // $url.= $_SERVER['HTTP_HOST'].$data; 
    // return $url;
}


/*
    function decryptURLData
    @param url data query

*/
function decryptURLData($data){
    $data = str_replace(" ", "+", urldecode($data));
    $path = str_decryptaesgcm($data, "\$abhi%@2bc/");

    if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
        $url = "https://"; 
    }
    else  {
        $url = "http://";   
    }  
    $url.= $_SERVER['HTTP_HOST'].$path;
    return $path;
    // return $data;
}
