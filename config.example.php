<?php
defined('APP_START') or exit('No direct script access allowed');
//Init
define('APP_PATH', dirname(__FILE__));
define('DB_PATH', APP_PATH.'/assets/');
define('APP_URL', 'http://localhost/berbagi/');
define('APP_KEY', '');
define('MAFURA_URL', 'https://manastudio.id/repo/public/manastudio/mafura/');

date_default_timezone_set('Asia/Jakarta');

error_reporting(E_ALL);
ini_set('display_errors', '1');