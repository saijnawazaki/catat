<?php
defined('APP_PATH') or exit('No direct script access allowed');

require 'Openssl_EncryptDecrypt.php';


function parsedate($string)
{
	$exp_string = explode('-',$string);
	$ut = mktime(0, 0, 0, $exp_string[1], $exp_string[2], $exp_string[0]);

	return $ut;
}

function parsenumber($number,$decimal = 2)
{
    return number_format($number,$decimal,'.',',');
}

function data_encrypt($pure_string, $encryption_key)
{
	$enc_dec = new Openssl_EncryptDecrypt();
    $data = $enc_dec->encrypt($pure_string, $encryption_key);
	//return bin2hex($enc_dec->encrypt($pure_string, $encryption_key));
    return $data !== null ? bin2hex($data) : '';
}

function data_decrypt($encrypted_string, $encryption_key)
{
    //print_r(array($encrypted_string, $encryption_key));
	$enc_dec = new Openssl_EncryptDecrypt();
    $data = $enc_dec->decrypt(hex2bin($encrypted_string), $encryption_key);
    //var_dump($data);
	return $data !== null ? $data : '';
}