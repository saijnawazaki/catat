<?php
defined('APP_PATH') OR exit('No direct script access allowed');

if($_SERVER['REQUEST_METHOD'] === 'POST') 
{
    if($page == 'login')
    {
        if(isset($_POST['login']))
        {
            $username = isset($_POST['username']) ? $_POST['username'] : '';
            $password = isset($_POST['password']) ? $_POST['password'] : '';
            $_SESSION['mess'] = '';
            
            if(! preg_match('/^[a-z0-9-_]{3,20}$/', $username)) 
            {
                $_SESSION['mess'] .= 'Username Invalid<br>';    
            }
            
            if($_SESSION['mess'] == '')
            {
                $query = "select * from account where username = '".$username."'";
                $result = $db->query($query);
                $data = $result->fetchArray();
                //print('<pre>'.print_r($data,true).'</pre>');
                
                if(! $data)
                {
                    $_SESSION['mess'] .= 'Username / Password Invalid<br>';   
                }
                else
                {
                    if($data['status_id'] == 0)
                    {
                        $_SESSION['mess'] .= 'Account InActive<br>';
                    }
                    elseif($data['status_id'] == 1)
                    {
                        if(! password_verify($password, $data['password']))
                        {
                            $_SESSION['mess'] .= 'Username / Password Invalid<br>';     
                        }
                        else
                        {
                            //check auth with KEY
                            //print_r(array($data['check_auth'],$username.':'.$password));
                            $decode_auth = data_decrypt($data['check_auth'],$username.':'.$password);
                            
                            if($decode_auth != 'ok')
                            {
                                $_SESSION['mess'] .= 'AUTH Invalid<br>';    
                            }    
                        }
                        
                                
                    }
                }
                        
            }
            
            if($_SESSION['mess'] == '')
            {
                $_SESSION['ses_username'] = $username; 
                $_SESSION['ses_user_id'] = $data['account_id'];
                $_SESSION['ses_display_name'] = $data['full_name'];
                $_SESSION['ses_auth'] = data_decrypt($username.':'.$password,APP_KEY); 
                
                header('location: '.APP_URL.'?page=note');
            }
        }    
    }
    elseif($page == 'note')
    {
        if(isset($_POST['v_save']))
        {
            $v_title = isset($_POST['v_title']) ? $_POST['v_title'] : '';    
            $v_content = isset($_POST['v_content']) ? $_POST['v_content'] : '';
            $_SESSION['mess'] = '';
            
            if($_SESSION['mess'] == '')
            {
                //New
                $query = "select max(id) as last_id from note";
                $result = $db->query($query);
                $data = $result->fetchArray();
                $final_id = ((int) $data['last_id'])+1;
                
                $query = "
                    insert into
                        note
                        (
                            id,
                            account_id,
                            title,
                            content,
                            status_id,
                            created_at
                        )
                    values
                        (
                            '".$final_id."',
                            '".$ses['user_id']."',
                            '".data_encrypt($v_title,$ses['auth_key'])."',
                            '".data_encrypt($v_content,$ses['auth_key'])."',
                            '1',
                            '".time()."'
                        )
                ";

                
                if($db->query($query))
                {
                    $_SESSION['mess'] .= 'Add Successfully';
                    header('location: '.APP_URL.'?page=note');
                     
                }
                else
                {
                    $_SESSION['mess'] .= 'Added Failed';
                  
                }    
            }
                    
        }    
    }
    elseif($page == 'note_details_edit')
    {
        if(isset($_POST['v_save']))
        {
            $v_id = isset($_POST['v_id']) ? $_POST['v_id'] : '';    
            $v_title = isset($_POST['v_title']) ? $_POST['v_title'] : '';    
            $v_content = isset($_POST['v_content']) ? $_POST['v_content'] : '';
            $_SESSION['mess'] = '';
            
            if($_SESSION['mess'] == '')
            {
                $query = "
                    insert into
                        note
                        (
                            id,
                            account_id,
                            title,
                            content,
                            status_id,
                            created_at
                        )
                    values
                        (
                            '".$v_id."',
                            '".$ses['user_id']."',
                            '".data_encrypt($v_title,$ses['auth_key'])."',
                            '".data_encrypt($v_content,$ses['auth_key'])."',
                            '1',
                            '".time()."'
                        )
                ";

                
                if($db->query($query))
                {
                    $_SESSION['mess'] .= 'Update Successfully';
                    header('location: '.APP_URL.'?page=note_details_edit&id='.$v_id);
                     
                }
                else
                {
                    $_SESSION['mess'] .= 'Update Failed';
                  
                }    
            }
                    
        }    
    }
    else
    {
        header('location: '.APP_URL.'?page=fatal_error');
    }   
}
elseif($_SERVER['REQUEST_METHOD'] === 'GET')
{
    if($page == 'logout')
    {
        session_destroy();
        
        $_SESSION['mess'] = 'Logout Successfully';
        header('location: '.APP_URL.'?page=login');
        
    }  
}