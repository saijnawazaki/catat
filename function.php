<?php
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
	return bin2hex($enc_dec->encrypt($pure_string, $encryption_key));
}

function data_decrypt($encrypted_string, $encryption_key)
{
	$enc_dec = new Openssl_EncryptDecrypt();
	return bin2hex($enc_dec->decrypt($encrypted_string, $encryption_key));
}