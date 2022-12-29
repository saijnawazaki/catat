<?php
require 'config.php';
require 'config_engine.php';

session_start();

$ses_username = isset($_SESSION['ses_username']) ? $_SESSION['ses_username'] : '';
$ses = array();

if($ses_username != '')
{
    $ses['user_id'] = $_SESSION['ses_user_id'];
    $ses['username'] = $_SESSION['ses_username'];
    $ses['display_name'] = $_SESSION['ses_display_name'];
    $ses['role_id'] = $_SESSION['ses_role_id'];    
    $ses['person_id'] = $_SESSION['ses_person_id'];    
    $ses['initial_name'] = $_SESSION['ses_initial_name'];    
}


$page = isset($_GET['page']) ? $_GET['page'] : '';

//check
if($ses_username == '')
{
    if($page != 'login' && $page != 'personal_report')
    {
        header('location: '.APP_URL.'?page=login');    
    }
    else
    {   
        //$_SESSION['mess'] = 'Need Login';
    }    
}
else
{
    if(! preg_match('/^[a-z0-9-_]{3,20}$/', $ses_username))
    {
        if($page != 'fatal_error')
        {
            header('location: '.APP_URL.'?page=fatal_error');    
        }
                
    }
    else
    {
        if($page == '')
        {
            header('location: '.APP_URL.'?page=home');    
        }    
    }    
}
    
//Connect SQLite
$db = new SQLite3(DB_PATH) or die('Error Connect DB');
//print_r($_SESSION);
require 'base.php';