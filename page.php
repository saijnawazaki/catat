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
            *
        from
            note
        where
            account_id = '".$ses['user_id']."'
        order by
            created_at DESC
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
                            <div class="border-1 bc-light bg-warning p-2" style="height: 100%;">
                                <?php
                                    if(isset($value['title']))
                                    {
                                        echo '<b>'.$value['title'].'</b>';
                                    }
                                    
                                    if(isset($value['content']))
                                    {
                                        echo '<p>'.$value['content'].'</p>';
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
                 height: "random",
                 margin: "5",
                 minHeight: "120", // if height is "random"
                 maxHeight: "200"  // if height is "random"
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