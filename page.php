<?php
defined('APP_PATH') OR exit('No direct script access allowed');

if($page == 'fatal_error')
{
?>
    <div class="container">
        <h1>Fatal Error</h1>
    </div>
<?php    
}
elseif($page == 'login')
{   
?>
    <div class="container">
        <form method="post" action="<?=APP_URL?>?page=login" accept-charset="utf-8">
            <div class="row">
                <div class="col-12 col-lg-3">
                    <div class="border-1 bc-muted p-2 br-2 mt-2">
                        <label>Username</label>
                        <input type="text" name="username" value="" autofocus="">
                        <label>Password</label>
                        <input type="password" name="password" value="">
                        <hr>
                        <input type="submit" name="login" class="bg-primary color-white" value="Login">
                    </div>
                </div>
                <div class="col-12 col-lg-9">
                    <img src="<?=APP_URL?>/assets/anjing.jpg" style="width: 210px;" class="mt-2">
                </div>
                
            </div>
        </form>
    </div>
<?php    
}
elseif($page == 'note')
{
    $query = "
        select
            note.*
        from
            note
        inner join
        (
            select
                id,
                max(created_at) as created_at
            from
                note
            where
                account_id = '".$ses['user_id']."'
            group by
                id
        ) as res_max
        on res_max.id = note.id
        and res_max.created_at = note.created_at 
        where
            note.account_id = '".$ses['user_id']."'
        
        order by
            note.created_at DESC
    ";
    $result = $db->query($query);
    while($row = $result->fetchArray())
    {
        $arr_data['note'][$row['id']]['title'] = data_decrypt($row['title'],$ses['auth_key']);    
        $arr_data['note'][$row['id']]['content'] = data_decrypt($row['content'],$ses['auth_key']);     
    }    
    
?>
    <div class="container">
        <h1>Note</h1>
        <div class="text-right">
            <?php
                $js = '
                    if(document.getElementById(\'div_add\').style.display == \'\')
                    {
                        document.getElementById(\'div_add\').style.display = \'none\';
                        this.innerHTML = \'Add\';   
                    }
                    else
                    {
                        document.getElementById(\'div_add\').style.display = \'\';
                        this.innerHTML = \'Hide\';
                    }
                ';
            ?>
            <button onclick="<?=$js?>" class="bg-success color-white" type="button">Add</button>
            <div id="div_add" style="display: none;">
                <hr>
                <form method="post" action="<?=APP_URL?>?page=note" accept-charset="utf-8">
                    <div class="text-left">
                        <label>Title</label>
                    </div>
                    <input type="text" name="v_title" value="">
                    <div class="text-left">
                        <label>Content</label>
                    </div>
                    <textarea name="v_content" style="width: 100%; height: 120px;"></textarea>
                    <input type="submit" name="v_save" class="bg-success color-white" value="Save">
                </form>
            </div>
        </div>
        <hr>
        
    </div>
    <div class="container">
        <div id="grid" class="easygrid_bvgrid" style="width:100%;">
            <?php
                foreach($arr_data['note'] as $note_id => $value)
                {
                ?>
                    <div class="easygrid_fetch">
                        <a class="color-black" href="<?=APP_URL.'?page=note_details&id='.$note_id?>">
                            <div class="border-1 bc-light p-2" style="height: 100%; background-color: #feefc3;">
                                <?php
                                    if(isset($value['title']))
                                    {
                                        echo '<b>'.$value['title'].'</b>';
                                    }
                                    
                                    if(isset($value['content']))
                                    {
                                        $mod_con = explode("\n", $value['content']);
                                        $final_content = '';
                                        $limit = 5;
                                        $now = 0;
                                        
                                        if(count($mod_con) <= 8)
                                        {
                                            $final_content = nl2br($value['content']);    
                                        }
                                        else
                                        {
                                            foreach($mod_con as $val)
                                            {
                                                $now++;
                                                
                                                if($now >= $limit)
                                                {
                                                    break;    
                                                }
                                                
                                                $final_content .= $val.'<br>';    
                                            }
                                            $final_content .= '...';    
                                        }
                                        
                                        echo '<p>'.$final_content.'</p>';
                                    }
                                ?>
                            </div>
                        </a>
                    </div>
                <?php    
                } 
            ?>  
        </div>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
             var demo1 = new EasyGrid({
               selector: "#grid",
               dimensions: {
                 width: "150",
                 margin: "5",
                 minHeight: "120", // if height is "random"
                 
               },
               animations: {
                 fadeInSpeed: "100",
                 addItemSpeed: "100"
               },
               style: {
                 background: "transparent",
                 borderRadius: "5"
               },
               responsive: [
                    {
                      breakpoint: 300,
                      columns: 1
                    }
                ]
             }); 
        });
    </script>
<?php    
}
elseif($page == 'note_details')  
{
    $id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
    
    if($id == 0)
    {
        die('Note ID Invalid');
    }
    
    $query = "
        select
            note.*
        from
            note
        inner join
        (
            select
                id,
                max(created_at) as created_at
            from
                note
            where
                account_id = '".$ses['user_id']."'
                and id = '".$id."'
            group by
                id
        ) as res_max
        on res_max.id = note.id
        and res_max.created_at = note.created_at
        where
            note.account_id = '".$ses['user_id']."'
            and note.id = '".$id."'
    ";
    $result = $db->query($query);
    $row = $result->fetchArray();
    
    if(! $row)
    {
        die('Note Invalid / No Forbiden');
    }    
    
    $note_title = data_decrypt($row['title'],$ses['auth_key']); 
    $note_content = data_decrypt($row['content'],$ses['auth_key']); 
    ?>
    <div class="container">
        <h1><a href="<?=APP_URL?>?page=note">< Note</a> > <?=$note_title?></h1>
        <div class="text-right">
            <a href="<?=APP_URL?>?page=note_details_edit&id=<?=$id?>" class="button bg-light">Edit</a>
        </div>
        <hr>
        <?=nl2br($note_content)?>
    </div>
    <?php
}
elseif($page == 'note_details_edit')  
{
    $id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
    
    if($id == 0)
    {
        die('Note ID Invalid');
    }
    
    $query = "
        select
            note.*
        from
            note
        inner join
        (
            select
                id,
                max(created_at) as created_at
            from
                note
            where
                account_id = '".$ses['user_id']."'
                and id = '".$id."'
            group by
                id
        ) as res_max
        on res_max.id = note.id
        and res_max.created_at = note.created_at
        where
            note.account_id = '".$ses['user_id']."'
            and note.id = '".$id."'
    ";
    $result = $db->query($query);
    $row = $result->fetchArray();
    
    if(! $row)
    {
        die('Note Invalid / No Forbiden');
    }    
    
    $note_title = data_decrypt($row['title'],$ses['auth_key']); 
    $note_content = data_decrypt($row['content'],$ses['auth_key']); 
    ?>
    <div class="container">
        <h1><a href="<?=APP_URL?>?page=note">< Note</a> > <?=$note_title?></h1>
        <hr>
        <form method="post" action="<?=APP_URL?>?page=note_details_edit&id=<?=$id?>" accept-charset="utf-8">
            <label>Title</label>
            <input type="text" name="v_title" value="<?=$note_title?>">
            <label>Content</label>
            <textarea name="v_content" style="height: 300px;"><?=$note_content?></textarea>
            <hr>
            <div class="text-right">
                <input type="hidden" name="v_id" value="<?=$id?>">
                <input type="submit" name="v_save" class="button bg-success color-white" value="Save">
            </div>
        </form>
    </div>
    <?php
}