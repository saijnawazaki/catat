<?php
defined('APP_PATH') OR exit('No direct script access allowed');
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="<?=MAFURA_URL?>mafura.css" rel="stylesheet">
    <title><?=APP_TITLE?></title>
  </head>
  <body>
    <header class="nav bg-black color-white">
        <div class="container">
            <div class="row">
                <div class="col-9">
                    <h1><?=APP_TITLE?></h1>
                </div>
                <div class="col-3">
                    <?php
                        if(isset($ses['user_id']))
                        {
                        ?>
                            <div class="bg-light color-black p-1 text-right">
                                <?=$ses['display_name'].' (@'.$ses['username'].')'?>
                                <div>
                                    <a href="<?=APP_URL.'?page=logout'?>">Logout</a>
                                </div>
                            </div>
                        <?php
                        }
                    ?>
                    
                </div>
            </div> 
        </div>
    </header>
    <?php
        if(isset($_SESSION['mess']) && $_SESSION['mess'] != '')
        {
            ?>
            <div class="bg-warning color-black">
                <?=$_SESSION['mess']?>
            </div>
            <?php
            $_SESSION['mess'] = '';
        }
        require 'page.php';    
    ?>
    <footer>
        <div class="container">
            <hr> 
            <small>2022 saijnawazaki. Version: <?=APP_VERSION?> / Designed with Mafura</small>
        </div> 
    </footer>
    <div role="alert" id="alert_mess" class="position-fixed top-0 left-0 width-fluid height-vh" style="display: none;">
      <div class="position-relative width-fluid height-vh d-flex justify-content-center">
        <div class="bg-dark position-absolute width-fluid height-vh top-0 left-0 opacity-3" data-toggle="hide" aria-controls="alert_open_dismiss"></div>
        <div class="bg-light bc-muted color-dark br-2 p-3 position-absolute mt-3 ms-auto me-auto mb-auto" style="width: 500px;">
          <div id="alert_mess_content"></div>
          <div class="text-right">
            <a href="javascript:void(0)" class="me-2 link-no-underline" onclick="document.getElementById('alert_mess').style.display = 'none';">
              OK
            </a>
          </div>
        </div>
      </div>
    </div>

    <script src="<?=MAFURA_URL?>mafura.js"></script>
    <script>
        function createRequestObject()
        {
            var ro;
            var browser = navigator.appName;
            if(browser == 'Microsoft Internet Explorer'){
                ro = new ActiveXObject('Microsoft.XMLHTTP');
            }else{
                ro = new XMLHttpRequest();
            }
            return ro;
        }
        
        var xmlhttp = createRequestObject();
        var url = '<?=APP_URL?>';
        
        function getData(setting)
        {
            let mess = '';
            if(setting.page == undefined)
            {
                mess += "Page Invalid\n";         
            }    
            else
            {
                if(setting.page == 'getSelectRestaurantMenuByRestaurantID')
                {
                    xmlhttp.open('get', url+'?page='+setting.page+'&restaurant_id='+setting.restaurantID, true);
                    xmlhttp.onreadystatechange = function()
                    {
                        if((xmlhttp.readyState == 4) && (xmlhttp.status == 200))
                        {
                            let res = JSON.parse(xmlhttp.responseText);
                            
                            setting.targetSelect[0].innerHTML = '<option value="0" data-price="">-</option>';
                            
                            if(res.status == 'ok')
                            {
                                if(res.data.length > 0)
                                {
                                    for(let x = 0; x < res.data.length; x++)
                                    {
                                        setting.targetSelect[0].innerHTML += '<option value="'+res.data[x].id+'" data-price="'+res.data[x].last_price+'">'+res.data[x].name+'</option>';    
                                    }
                                }    
                            }
                            else
                            {
                                mess += "ERROR: "+res.status_mess+"\n";    
                            }
                            
                            //one for all
                            let master = setting.targetSelect[0].innerHTML;
                            for(let x = 0; x < setting.targetSelect.length; x++)
                            {
                                setting.targetSelect[x].innerHTML = master;
                                
                                //price qty total empty
                                let id = setting.targetSelect[x].id;
                                let exp_id = id.split('__');
                                
                                document.getElementById('input__'+exp_id[1]+'__qty').value = '';     
                                document.getElementById('input__'+exp_id[1]+'__price').value = '';     
                                document.getElementById('input__'+exp_id[1]+'__total').innerHTML = '';     
                            }
                            
                        }
                        return false;
                    }
                    xmlhttp.send(null);     
                }
                else
                {
                    mess += "Page Invalid - Not Defined\n";    
                }   
            }
            
            if(mess != '')
            {
                console.log(mess);
            }
        }
        
        /*https://stackoverflow.com/a/149099*/
        function formatNumber(number, decPlaces, decSep, thouSep) {
            decPlaces = isNaN(decPlaces = Math.abs(decPlaces)) ? 2 : decPlaces,
            decSep = typeof decSep === "undefined" ? "." : decSep;
            thouSep = typeof thouSep === "undefined" ? "," : thouSep;
            var sign = number < 0 ? "-" : "";
            var i = String(parseInt(number = Math.abs(Number(number) || 0).toFixed(decPlaces)));
            var j = (j = i.length) > 3 ? j % 3 : 0;

            return sign +
                (j ? i.substr(0, j) + thouSep : "") +
                i.substr(j).replace(/(\decSep{3})(?=\decSep)/g, "$1" + thouSep) +
                (decPlaces ? decSep + Math.abs(number - i).toFixed(decPlaces).slice(2) : "");
        }
        
    </script>
  </body>
</html>