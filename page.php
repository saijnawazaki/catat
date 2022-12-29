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
elseif($page == 'home')
{
?>
    <div class="container">
        <h1>Home</h1>
        <hr>
        <div class="row">
            <div class="col-6 col-lg-3">
                <a href="<?=APP_URL.'?page=book'?>">
                    <div class="bg-light p-3 br-2 mb-3">
                        <h1>Book</h1>
                    </div>
                </a>
            </div>
            <div class="col-6 col-lg-3">
                <a href="<?=APP_URL.'?page=restaurant'?>">
                    <div class="bg-light p-3 br-2 mb-3">
                        <h1>Restaurant</h1>
                    </div>
                </a>
            </div>
            <div class="col-6 col-lg-3">
                <a href="<?=APP_URL.'?page=person'?>">
                    <div class="bg-light p-3 br-2 mb-3">
                        <h1>Person</h1>
                    </div>
                </a>
            </div>
            <div class="col-6 col-lg-3">
                <a href="<?=APP_URL.'?page=summary&book_id=0'?>">
                    <div class="bg-light p-3 br-2 mb-3">
                        <h1>Summary</h1>
                    </div>
                </a>
            </div>
        </div>
    </div>
<?php    
}
elseif($page == 'book')
{
    $arr_data['list_book'] = array();
    $query = "
        select
            *
        from
            book
        where
            user_id = '".$ses['user_id']."'
        order by
            created_at DESC
    ";
    //echo "<pre>$query</pre>";
    $result = $db->query($query);    
    
    while($row = $result->fetchArray())
    {
        $arr_data['list_book'][$row['book_id']]['title'] = $row['book_title'];    
    }
?>
    <div class="container">
        <h1>
            <a href="<?=APP_URL.'?page=home'?>">
                <svg id="i-chevron-left" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32" width="32" height="32" fill="none" stroke="currentcolor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
                    <path d="M20 30 L8 16 20 2" />
                </svg>
            
                Book
            </a>
        </h1>
        <div class="text-right">
            <a href="<?=APP_URL.'?page=book_add_edit&book_id=0'?>" class="button bg-success color-white">New</a>
        </div>
        <hr>
        
        <div class="row">
            <?php
                if(count($arr_data['list_book']) == 0)
                {
                ?>
                    <div class="col-12 text-center">
                        No Data
                    </div>
                <?php
                }
                else
                {
                    foreach($arr_data['list_book'] as $book_id => $val)
                    {
                    ?>
                        <div class="col-6 col-lg-2 text-center mb-3">
                            <a href="<?=APP_URL.'?page=book_details&book_id='.$book_id?>" class="color-black">
                                <div class="bg-light br-t-r-2 br-b-r-2 text-left border-1 bc-muted position-relative" style="height: 250px;">
                                    <span class="position-absolute color-muted me-2 mb-2" style="bottom:0;right:0;"><?=$val['title']?></span>
                                </div>
                                <?=$val['title']?>
                                <div>
                                    <small>
                                        <a href="<?=APP_URL.'?page=book_add_edit&book_id='.$book_id?>">Edit</a>
                                    </small>
                                </div>
                            </a>
                        </div>
                    <?php    
                    }
                    
                }
            ?>
            
            
        </div>
    </div>
<?php    
}
elseif($page == 'book_add_edit')
{
    $g_book_id = isset($_GET['book_id']) ? $_GET['book_id'] : 0;
    if(! preg_match('/^[0-9]*$/', $g_book_id)) 
    {
        die('Book ID Invalid');        
    }
    
    if($g_book_id > 0)
    {
        $query = "
            select
                *
            from
                book
            where
                book_id = '".$_GET['book_id']."'
        ";
        $result = $db->query($query);    
        $data = $result->fetchArray();
        
        if($data['user_id'] != $ses['user_id'])
        {
            die('Book not yours!');    
        }
    }
?>
    <div class="container">
        <h1>
            <a href="<?=APP_URL.'?page=book'?>">
                <svg id="i-chevron-left" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32" width="32" height="32" fill="none" stroke="currentcolor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
                    <path d="M20 30 L8 16 20 2" />
                </svg>
            Book
            </a>
            <svg id="i-chevron-right" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32" width="32" height="32" fill="none" stroke="currentcolor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
                <path d="M12 30 L24 16 12 2" />
            </svg>
            Add / Edit
        </h1>
        <hr>
        <div class="row">
            <div class="col-12 col-lg-4">
                <form method="post" action="<?=APP_URL?>?page=book_add_edit&book_id=<?=$g_book_id?>" accept-charset="utf-8">
                    <label>Book Title</label>
                    <input type="text" name="book_title" value="<?=isset($data['book_title']) ? $data['book_title'] : ''?>">
                    <hr>
                    <input type="hidden" name="book_id" value="<?=$g_book_id?>">
                    <input type="submit" name="submit" class="bg-primary color-white" value="Submit">
                </form>   
            </div>
            
        </div>
    </div>
<?php    
}
elseif($page == 'book_details')
{
    $g_book_id = isset($_GET['book_id']) ? $_GET['book_id'] : 0;
    if(! preg_match('/^[0-9]*$/', $g_book_id)) 
    {
        die('Book ID Invalid');        
    }
    
    if($g_book_id > 0)
    {
        $query = "
            select
                *
            from
                book
            where
                book_id = '".$g_book_id."'
        ";
        $result = $db->query($query);    
        $data = $result->fetchArray();
        
        if($data['user_id'] != $ses['user_id'])
        {
            die('Book not yours!');    
        }
    }
    else
    {
        die('Book Invalid');
    }
?>
    <div class="container">
        <h1>
            <a href="<?=APP_URL.'?page=book'?>">
                <svg id="i-chevron-left" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32" width="32" height="32" fill="none" stroke="currentcolor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
                    <path d="M20 30 L8 16 20 2" />
                </svg>
            Book
            </a>
            <svg id="i-chevron-right" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32" width="32" height="32" fill="none" stroke="currentcolor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
                <path d="M12 30 L24 16 12 2" />
            </svg>
            <?=isset($data['book_title']) ? $data['book_title'] : ''?>
        </h1>
        <hr>
        <div class="row">
            <div class="col-6 col-lg-3">
                <a href="<?=APP_URL.'?page=invoice&book_id='.$g_book_id?>">
                    <div class="bg-light p-3 br-2 mb-3">
                        <h1>Invoice</h1>
                    </div>
                </a>
            </div>
            <div class="col-6 col-lg-3">
                <a href="<?=APP_URL.'?page=split_bill&book_id='.$g_book_id?>">
                    <div class="bg-light p-3 br-2 mb-3">
                        <h1>Split Bill</h1>
                    </div>
                </a>
            </div>
            <div class="col-6 col-lg-3">
                <a href="<?=APP_URL.'?page=payment&book_id='.$g_book_id?>">
                    <div class="bg-light p-3 br-2 mb-3">
                        <h1>Payment</h1>
                    </div>
                </a>
            </div>
            <div class="col-6 col-lg-3">
                <a href="<?=APP_URL.'?page=summary&book_id='.$g_book_id?>">
                    <div class="bg-light p-3 br-2 mb-3">
                        <h1>Summary</h1>
                    </div>
                </a>
            </div>
            <div class="col-6 col-lg-3">
                <a href="<?=APP_URL.'?page=prediction_board&book_id='.$g_book_id?>">
                    <div class="bg-light p-3 br-2 mb-3">
                        <h1>Prediction Board</h1>
                    </div>
                </a>
            </div>
        </div>
    </div>
<?php    
}
elseif($page == 'invoice')
{
    $g_book_id = isset($_GET['book_id']) ? $_GET['book_id'] : 0;
    if(! preg_match('/^[0-9]*$/', $g_book_id)) 
    {
        die('Book ID Invalid');        
    }
    
    if($g_book_id > 0)
    {
        $query = "
            select
                *
            from
                book
            where
                book_id = '".$g_book_id."'
        ";
        $result = $db->query($query);    
        $data = $result->fetchArray();
        
        if($data['user_id'] != $ses['user_id'])
        {
            die('Book not yours!');    
        }
    }
    else
    {
        die('Book Invalid');
    }
    
    $arr_data['list_invoice'] = array();
    $query = "
        select
            invoice.invoice_id,
            invoice.invoice_date,
            invoice.book_id,
            restaurant.restaurant_name,
            (
                res_inv_details.total_item
                + invoice.tax_amount
                - invoice.discount_amount
                + invoice.delivery_amount
                + invoice.adjustment_amount 
                + invoice.other_amount 
            ) as total_purchase,
            res_sb.total_sb
        from
            invoice
        inner join
            restaurant
            on restaurant.restaurant_id = invoice.restaurant_id
        left join
        (
            select
                invoice_id,
                sum(qty*price) as total_item
            from
                invoice_details
            group by
                invoice_id
        ) as res_inv_details
        on res_inv_details.invoice_id = invoice.invoice_id
        left join
        (
            select
                split_bill.invoice_id,
                SUM
                (
                    split_bill_details.item_amount
                    + split_bill_details.tax_amount 
                    - split_bill_details.discount_amount 
                    + split_bill_details.delivery_amount 
                    + split_bill_details.other_amount 
                    + split_bill_details.adjustment_amount 
                ) as total_sb
            from
                split_bill_details
            inner join
                split_bill
                on split_bill.sb_id = split_bill_details.sb_id
            group by
                split_bill.invoice_id 
        ) as res_sb
        on res_sb.invoice_id = invoice.invoice_id   
        where
            invoice.book_id = '".$g_book_id."'
        order by
            invoice.invoice_date DESC
    ";
    $result = $db->query($query);    
    
    while($row = $result->fetchArray())
    {
        $arr_data['list_invoice'][$row['invoice_date']][$row['invoice_id']]['title'] = 'INV/'.$row['book_id'].'/'.$row['invoice_id'];    
        $arr_data['list_invoice'][$row['invoice_date']][$row['invoice_id']]['restaurant_name'] = $row['restaurant_name'];    
        $arr_data['list_invoice'][$row['invoice_date']][$row['invoice_id']]['total_purchase'] = (int) $row['total_purchase'];    
        $arr_data['list_invoice'][$row['invoice_date']][$row['invoice_id']]['total_sb'] = (int) $row['total_sb'];    
    }
?>
    <div class="container">
        <h1>
            <a href="<?=APP_URL.'?page=book_details&book_id='.$g_book_id?>">
                <svg id="i-chevron-left" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32" width="32" height="32" fill="none" stroke="currentcolor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
                    <path d="M20 30 L8 16 20 2" />
                </svg>
            
                <?=isset($data['book_title']) ? $data['book_title'] : ''?>
            </a>
            <svg id="i-chevron-right" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32" width="32" height="32" fill="none" stroke="currentcolor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
                <path d="M12 30 L24 16 12 2" />
            </svg>
            Invoice
        </h1>
        <div class="text-right mb-3">
            <a href="<?=APP_URL.'?page=invoice_add_edit&book_id='.$g_book_id.'&invoice_id=0'?>" class="button bg-success color-white">New</a>
        </div>
        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Invoice ID</th>
                    <th>Restaurant Name</th>
                    <th>Total Purchase</th>
                    <th>Total Split Bill</th>
                    <th>Percentage</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    if(count($arr_data['list_invoice']) == 0)
                    {
                    ?>
                        <tr>
                            <td colspan="100">No Data</td>
                        </tr> 
                    <?php
                    }
                    else
                    {
                        foreach($arr_data['list_invoice'] as $invoice_date => $val)
                        {
                            $invoice_date_show = date('d-m-Y',$invoice_date);
                            foreach($arr_data['list_invoice'][$invoice_date] as $invoice_id => $val)
                            {
                            ?>
                                <tr>
                                    <td><?=$invoice_date_show?></td>
                                    <td><?=$val['title']?> </td>
                                    <td><?=$val['restaurant_name']?> </td>
                                    <td class="text-right"><?=parsenumber($val['total_purchase'],2)?> </td>
                                    <td class="text-right"><?=parsenumber($val['total_sb'],2)?> </td>
                                    <td class="text-right<?=($val['total_sb']/$val['total_purchase']*100 == 100 ? '' : ' bg-danger')?>"><?=parsenumber($val['total_sb']/$val['total_purchase']*100,2)?>%</td>
                                    <td>
                                        <a class="button bg-warning" href="<?=APP_URL.'?page=invoice_add_edit&book_id='.$g_book_id.'&invoice_id='.$invoice_id?>">Edit</a>
                                    </td>
                                </tr>
                            <?php
                                $invoice_date_show = '';    
                            }    
                        }
                            
                        
                    }
                ?>
            </tbody>  
        </table>
        
    </div>
<?php    
}
elseif($page == 'invoice_add_edit')
{
    $g_book_id = isset($_GET['book_id']) ? $_GET['book_id'] : 0;
    $g_invoice_id = isset($_GET['invoice_id']) ? $_GET['invoice_id'] : 0;
    
    if(! preg_match('/^[0-9]*$/', $g_book_id)) 
    {
        die('Book ID Invalid');        
    }
    
    if(! preg_match('/^[0-9]*$/', $g_invoice_id)) 
    {
        die('Invoice ID Invalid');        
    }
    
    if($g_book_id > 0)
    {
        $query = "
            select
                *
            from
                book
            where
                book_id = '".$_GET['book_id']."'
        ";
        $result = $db->query($query);    
        $data = $result->fetchArray();
        
        if($data['user_id'] != $ses['user_id'])
        {
            die('Book not yours!');    
        }
    }
    
    if($g_invoice_id > 0)
    {
        $query = "
            select
                *
            from
                invoice
            where
                invoice_id = '".$g_invoice_id."'
        ";
        $result = $db->query($query);    
        $data_invoice = $result->fetchArray();
        
        //load details
        $query = "
            select
                *
            from
                invoice_details
            inner join
                restaurant_menu
                on restaurant_menu.rm_id = invoice_details.rm_id
            where
                invoice_details.invoice_id = '".$g_invoice_id."'
            order by
                restaurant_menu.rm_name ASC
        ";
        $result = $db->query($query);
        $arr_data['list_product'] = array();
        $arr_data['list_product_tot']['qty'] = 0;
        $arr_data['list_product_tot']['total'] = 0;
        $no = 0;
        while($row = $result->fetchArray())
        {
            $no++;
            $arr_data['list_product'][$no]['id_id'] = $row['id_id'];    
            $arr_data['list_product'][$no]['rm_id'] = $row['rm_id'];    
            $arr_data['list_product'][$no]['rm_name'] = $row['rm_name'];    
            $arr_data['list_product'][$no]['qty'] = $row['qty'];    
            $arr_data['list_product'][$no]['price'] = $row['price'];

            $arr_data['list_product_tot']['qty'] += $row['qty'];    
            $arr_data['list_product_tot']['total'] += $row['qty']*$row['price'];    
        }
    }
    
    //load Res
    $query = "
        select
            *
        from
            restaurant
        order by
            restaurant.restaurant_name ASC
    ";
    $result = $db->query($query);
    $arr_data['list_book'] = array();
    while($row = $result->fetchArray())
    {
        $arr_data['list_restaurant'][$row['restaurant_id']]['name'] = $row['restaurant_name'];    
    }

    //load Platform
    $query = "
        select
            *
        from
            platform
        order by
            platform.platform_name ASC
    ";
    $result = $db->query($query);
    $arr_data['list_platform'] = array();
    while($row = $result->fetchArray())
    {
        $arr_data['list_platform'][$row['platform_id']]['name'] = $row['platform_name'];    
    }

    if(isset($data_invoice) && $data_invoice['restaurant_id'] > 0)
    {
        $query = "
            select
                *
            from
                restaurant_menu
            where
                restaurant_id = '".$data_invoice['restaurant_id']."'
            order by
                rm_name
        ";
        $result = $db->query($query);
        $arr_data['list_rm'] = array();
        while($row = $result->fetchArray())
        {
            $arr_data['list_rm'][$row['rm_id']]['name'] = $row['rm_name'];    
        }
    }
    
    $grand_total = 0;
    $grand_total += isset($arr_data['list_product_tot']['total']) ? $arr_data['list_product_tot']['total'] : 0;
    $grand_total += isset($data_invoice['tax_amount']) ? $data_invoice['tax_amount'] : 0;
    $grand_total -= isset($data_invoice['discount_amount']) ? $data_invoice['discount_amount'] : 0;
    $grand_total += isset($data_invoice['delivery_amount']) ? $data_invoice['delivery_amount'] : 0;
    $grand_total += isset($data_invoice['other_amount']) ? $data_invoice['other_amount'] : 0;
    $grand_total += isset($data_invoice['adjustment_amount']) ? $data_invoice['adjustment_amount'] : 0;
?>
    <div class="container">
        <h1>
            <a href="<?=APP_URL.'?page=invoice&book_id='.$g_book_id?>">
                <svg id="i-chevron-left" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32" width="32" height="32" fill="none" stroke="currentcolor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
                    <path d="M20 30 L8 16 20 2" />
                </svg>
            
                Invoice
            </a>
            <svg id="i-chevron-right" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32" width="32" height="32" fill="none" stroke="currentcolor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
                <path d="M12 30 L24 16 12 2" />
            </svg>
            Add / Edit
        </h1>
        <hr>
       
        <form method="post" target="iframe_post" action="<?=APP_URL?>?page=invoice_add_edit" accept-charset="utf-8">
            <div class="row">
                <div class="col-12 col-lg-4">
                        <label>Invoice Date</label>
                        <input type="date" name="invoice_date" value="<?=isset($data_invoice['invoice_date']) ? date('Y-m-d',$data_invoice['invoice_date']) : date('Y-m-d')?>">
                        <label>Restaurant</label>
                        <select id="restaurant_id" name="restaurant_id" onchange="getData({page:'getSelectRestaurantMenuByRestaurantID',restaurantID: this.value,targetSelect:document.getElementsByClassName('select__product')})">
                            <option value="0">-</option>
                            <?php
                            if(isset($arr_data['list_restaurant']))
                            {
                                if(count($arr_data['list_restaurant']) > 0)
                                {
                                    foreach($arr_data['list_restaurant'] as $restaurant_id => $val)
                                    {
                                    ?>
                                        <option value="<?=$restaurant_id?>"<?=isset($data_invoice['restaurant_id']) ? ($data_invoice['restaurant_id'] == $restaurant_id  ? ' selected' : '') : ''?>><?=$val['name']?></option>
                                    <?php
                                    }
                                }    
                            }   
                            ?>
                        </select>
                        <div>
                            <button type="button" onclick="window.open('<?=APP_URL?>?page=restaurant', '_blank')">New</button>
                            <button type="button" onchange="getData({page:'getSelectRestaurantMenuByRestaurantID',restaurantID: document.getElementById('restaurant_id').value,targetSelect:document.getElementsByClassName('select__product')})">Refresh</button>
                            <button type="button" onclick="window.open('<?=APP_URL?>?page=restaurant_menu&restaurant_id='+document.getElementById('restaurant_id').value, '_blank')">Menu</button>
                        </div>    
                                
                        <label>Platform</label>
                        <select name="platform_id">
                            <option value="0">-</option>
                            <?php
                            if(count($arr_data['list_platform']) > 0)
                            {
                                foreach($arr_data['list_platform'] as $platform_id => $val)
                                {
                                ?>
                                    <option value="<?=$platform_id?>"<?=isset($data_invoice['platform_id']) ? ($data_invoice['platform_id'] == $platform_id ? ' selected' : '') : ''?>><?=$val['name']?></option>
                                <?php
                                }
                            }
                            ?>
                        </select>
                        <?php
                        $js_tot = '
                            let data_list = document.getElementsByClassName(\'select__product\');
                            let tot_qty = 0;
                            let tot_total = 0;
                            for(let x = 0; x < data_list.length; x++)
                            {
                                let id = data_list[x].id;
                                let exp_id = id.split(\'__\');
                                tot_qty += Number(document.getElementById(\'input__\'+exp_id[1]+\'__qty\').value);
                                tot_total += Number(document.getElementById(\'input__\'+exp_id[1]+\'__totalhid\').value);
                            }

                            document.getElementById(\'info__tot__qty\').innerHTML = tot_qty;
                            document.getElementById(\'info__tot__total\').innerHTML = tot_total;
                            
                            let gt = tot_total;
                            gt += Number(document.getElementById(\'tax_amount\').value);
                            gt -= Number(document.getElementById(\'discount_amount\').value);
                            gt += Number(document.getElementById(\'delivery_amount\').value);
                            gt += Number(document.getElementById(\'other_amount\').value);
                            gt += Number(document.getElementById(\'adjustment_amount\').value);
                            
                            document.getElementById(\'grand_total\').innerHTML = gt.toFixed(2);
                        ';
                        ?>
                        <label>Tax</label>
                        <input onkeyup="<?=$js_tot?>" class="text-right" type="text" id="tax_amount" name="tax_amount" value="<?=isset($data_invoice['tax_amount']) && $data_invoice['tax_amount'] != 0 ? $data_invoice['tax_amount'] : ''?>">
                        <label>Discount</label>
                        <input onkeyup="<?=$js_tot?>" class="text-right" type="text" id="discount_amount" name="discount_amount" value="<?=isset($data_invoice['discount_amount']) && $data_invoice['discount_amount'] != 0 ? $data_invoice['discount_amount'] : ''?>">
                        <label>Delivery</label>
                        <input onkeyup="<?=$js_tot?>" class="text-right" type="text" id="delivery_amount" name="delivery_amount" value="<?=isset($data_invoice['delivery_amount']) && $data_invoice['delivery_amount'] != 0 ? $data_invoice['delivery_amount'] : ''?>">
                        
                        <label>Other</label>
                        <input onkeyup="<?=$js_tot?>" class="text-right" type="text" id="other_amount" name="other_amount" value="<?=isset($data_invoice['other_amount']) && $data_invoice['other_amount'] != 0 ? $data_invoice['other_amount'] : ''?>">
                        <label>Adjustment</label>
                        <input onkeyup="<?=$js_tot?>" class="text-right" type="text" id="adjustment_amount" name="adjustment_amount" value="<?=isset($data_invoice['adjustment_amount']) && $data_invoice['adjustment_amount'] != 0 ? $data_invoice['adjustment_amount'] : ''?>">
                        <hr>
                        <b>Grand Total</b>
                        <div id="grand_total" class="text-right"><?=parsenumber($grand_total,2)?></div>
                       
                    </div>
                    <div class="col-12 col-lg-8">
                        <h3>Product List</h3>
                        <hr>
                        <table>
                            <tr>
                                <th rowspan="2">No</th>
                                <th rowspan="2">Product</th>
                                <th>Qty</th>
                                <th rowspan="2">Price</th>
                                <th>Total</th>
                            </tr>
                            <tr>
                                <th>
                                    <span id="info__tot__qty"><?=isset($arr_data['list_product_tot']['qty']) ? parsenumber($arr_data['list_product_tot']['qty']) : ''?></span>
                                </th>
                                <th>
                                    <span id="info__tot__total"><?=isset($arr_data['list_product_tot']['total']) ? parsenumber($arr_data['list_product_tot']['total']) : ''?></span>
                                </th>
                            </tr>
                            <?php
                            for($x = 1; $x <= 10; $x++)
                            {
                            ?>
                                <tr>
                                    <td><?=$x?></td>
                                    <td>
                                        <?php
                                            
                                            $js_calc = '
                                                let res = Number(document.getElementById(\'input__'.$x.'__price\').value) * Number(document.getElementById(\'input__'.$x.'__qty\').value);
                                                document.getElementById(\'input__'.$x.'__total\').innerHTML = res;
                                                document.getElementById(\'input__'.$x.'__totalhid\').value = res;
                                                '.$js_tot.'
                                            ';
                                            $js = '
                                                if(this.value == 0)
                                                {
                                                    document.getElementById(\'input__'.$x.'__price\').value = \'\';
                                                    document.getElementById(\'input__'.$x.'__qty\').value = \'\';
                                                    document.getElementById(\'input__'.$x.'__total\').innerHTML = \'\';
                                                }
                                                else
                                                {
                                                    if(Number(document.getElementById(\'input__'.$x.'__price\').value) == 0)
                                                    {
                                                        document.getElementById(\'input__'.$x.'__price\').value = this.options[this.selectedIndex].getAttribute(\'data-price\');    
                                                        
                                                        if(document.getElementById(\'input__'.$x.'__price\').value == 0)
                                                        {
                                                            document.getElementById(\'input__'.$x.'__price\').value = \'\';
                                                        }
                                                    }
                                                }
                                                '.$js_calc.'    
                                            ';
                                        ?>
                                        <select onchange="<?=$js?>" class="select__product" name="product_list[<?=$x?>][product_id]" id="input__<?=$x?>__product_id">
                                            <option value="0">-</option>
                                            <?php
                                                if(isset($arr_data['list_rm']) &&count($arr_data['list_rm']) > 0)
                                                {
                                                    foreach($arr_data['list_rm'] as $rm_id => $val)
                                                    {
                                                    ?>
                                                        <option value="<?=$rm_id?>"<?=isset($arr_data['list_product'][$x]['rm_id']) ? ($arr_data['list_product'][$x]['rm_id'] == $rm_id ? ' selected' : '') : ''?>><?=$val['name']?></option>
                                                    <?php
                                                    }
                                                }
                                            ?>
                                        </select>
                                    </td>
                                    <td>
                                        <input onkeyup="<?=$js_calc?>" class="text-right" type="text" name="product_list[<?=$x?>][qty]" id="input__<?=$x?>__qty" value="<?=isset($arr_data['list_product'][$x]['qty']) ? $arr_data['list_product'][$x]['qty'] : ''?>">
                                    </td>
                                    <td>
                                        <input onkeyup="<?=$js_calc?>" class="text-right" type="text" name="product_list[<?=$x?>][price]" id="input__<?=$x?>__price" value="<?=isset($arr_data['list_product'][$x]['price']) ? $arr_data['list_product'][$x]['price'] : ''?>">
                                    </td>
                                        
                                    <td align="right">
                                        <span id="input__<?=$x?>__total"><?=isset($arr_data['list_product'][$x]['qty']) && isset($arr_data['list_product'][$x]['price']) ? parsenumber($arr_data['list_product'][$x]['qty']*$arr_data['list_product'][$x]['price'],2) : ''?></span>
                                        <input type="hidden" id="input__<?=$x?>__totalhid" value="<?=isset($arr_data['list_product'][$x]['qty']) && isset($arr_data['list_product'][$x]['price']) ? $arr_data['list_product'][$x]['qty']*$arr_data['list_product'][$x]['price'] : ''?>">   
                                    </td>
                                </tr>
                            <?php
                            }
                            ?>
                        </table>
                </div>
                <div class="col-12 col-lg-12">
                    <hr>
                    <input type="hidden" name="book_id" value="<?=$g_book_id?>">
                    <input type="hidden" name="invoice_id" value="<?=$g_invoice_id?>">
                    <input type="submit" name="submit" class="bg-primary color-white" value="Submit">
                </div>
            </div>    
        </form>
        <iframe style="width:100%;" class="border-1" name="iframe_post" src=""></iframe>
    </div>
<?php    
}
elseif($page == 'restaurant')
{
    $arr_data['list_restaurant'] = array();
    $query = "
        select
            *
        from
            restaurant
        order by
            created_at DESC
    ";
    //echo "<pre>$query</pre>";
    $result = $db->query($query) or die('ERROR|WQIEHQUIWEHUIQWHEUQWE');    
    
    while($row = $result->fetchArray())
    {
        $arr_data['list_restaurant'][$row['restaurant_id']]['title'] = $row['restaurant_name'];    
    }
?>
    <div class="container">
        <h1>
            <a href="<?=APP_URL.'?page=home'?>">
                <svg id="i-chevron-left" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32" width="32" height="32" fill="none" stroke="currentcolor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
                    <path d="M20 30 L8 16 20 2" />
                </svg>
            
                Restaurant
            </a>
        </h1>
        <div class="text-right">
            <a href="<?=APP_URL.'?page=restaurant_add_edit&restaurant_id=0'?>" class="button bg-success color-white">New</a>
        </div>
        <hr> 
        <div class="row">
            <?php
                if(count($arr_data['list_restaurant']) == 0)
                {
                ?>
                    <div class="col-12 text-center">
                        No Data
                    </div>
                <?php
                }
                else
                {
                    foreach($arr_data['list_restaurant'] as $restaurant_id => $val)
                    {
                        $title_mod = $val['title'];             
                        $title_len = strlen($val['title']);
                        $title_mod = substr($val['title'],0,30);
                        
                        if($title_len > 30)
                        {
                            $title_mod .= '...';   
                        } 
                    ?>
                        <div class="col-6 col-lg-2 text-center mb-3">
                            <a href="<?=APP_URL.'?page=restaurant_menu&restaurant_id='.$restaurant_id?>" class="color-black">
                                <div class="bg-light br-2 text-left" style="height: 200px;">
                                    <h4 class="p-3"><?=$title_mod?></h4>    
                                </div>
                                <?=$val['title']?>
                                <div>
                                    <small>
                                        <a href="<?=APP_URL.'?page=restaurant_add_edit&restaurant_id='.$restaurant_id?>">Edit</a>
                                    </small>
                                </div>
                            </a>
                        </div>
                    <?php    
                    }
                    
                }
            ?>
            
            
        </div>
    </div>
<?php    
}
elseif($page == 'restaurant_add_edit')
{
    $g_restaurant_id = isset($_GET['restaurant_id']) ? $_GET['restaurant_id'] : 0;
    if(! preg_match('/^[0-9]*$/', $g_restaurant_id)) 
    {
        die('Restaurant ID Invalid');        
    }
    
    if($g_restaurant_id > 0)
    {
        $query = "
            select
                *
            from
                restaurant
            where
                restaurant_id = '".$g_restaurant_id."'
        ";
        $result = $db->query($query);    
        $data = $result->fetchArray();
    }
?>
    <div class="container">
        <h1>
            <a href="<?=APP_URL.'?page=restaurant'?>">
                <svg id="i-chevron-left" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32" width="32" height="32" fill="none" stroke="currentcolor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
                    <path d="M20 30 L8 16 20 2" />
                </svg>
            Restaurant
            </a>
            <svg id="i-chevron-right" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32" width="32" height="32" fill="none" stroke="currentcolor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
                <path d="M12 30 L24 16 12 2" />
            </svg>
            Add / Edit
        </h1>
        <hr>
        <div class="row">
            <div class="col-12 col-lg-4">
                <form method="post" action="<?=APP_URL?>?page=restaurant_add_edit&restaurant_id=<?=$g_restaurant_id?>" accept-charset="utf-8">
                    <label>Restaurant Name</label>
                    <input type="text" name="restaurant_name" value="<?=isset($data['restaurant_name']) ? $data['restaurant_name'] : ''?>">
                    <hr>
                    <input type="hidden" name="restaurant_id" value="<?=$g_restaurant_id?>">
                    <input type="submit" name="submit" class="bg-primary color-white" value="Submit">
                </form>   
            </div>
            
        </div>
    </div>
<?php    
}
elseif($page == 'restaurant_menu')
{
    $g_id = isset($_GET['restaurant_id']) ? $_GET['restaurant_id'] : 0;
    if(! preg_match('/^[0-9]*$/', $g_id)) 
    {
        die('ID Invalid');        
    }
    
    if($g_id == 0)
    {
        die('Restaurant ID Invalid');
    }
    
    if($g_id > 0)
    {
        $query = "
            select
                *
            from
                restaurant
            where
                restaurant_id = '".$g_id."'
        ";
        $result = $db->query($query);    
        $data = $result->fetchArray();
        
    }
    
    $arr_data['list'] = array();
    $query = "
        select
            *
        from
            restaurant_menu
        where
            restaurant_id = '".$g_id."'
        order by
            created_at DESC
    ";
    //echo "<pre>$query</pre>";
    $result = $db->query($query) or die('ERROR|WQIEHQUIWEHUIQWHEUQWE');    
    
    while($row = $result->fetchArray())
    {
        $arr_data['list'][$row['rm_id']]['name'] = $row['rm_name'];    
    }
?>
    <div class="container">
        <h1>
            <a href="<?=APP_URL.'?page=restaurant'?>">
                <svg id="i-chevron-left" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32" width="32" height="32" fill="none" stroke="currentcolor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
                    <path d="M20 30 L8 16 20 2" />
                </svg>
            
                <?=$data['restaurant_name']?>
            </a>
        </h1>
        <div class="text-right">
            <a href="<?=APP_URL.'?page=restaurant_menu_add_edit&restaurant_id='.$g_id.'&rm_id=0'?>" class="button bg-success color-white">New</a>
        </div>
        <hr>
        
        <div class="row">
            <?php
                if(count($arr_data['list']) == 0)
                {
                ?>
                    <div class="col-12 text-center">
                        No Data
                    </div>
                <?php
                }
                else
                {
                    foreach($arr_data['list'] as $id => $val)
                    {
                        $title_mod = $val['name'];             
                        $title_len = strlen($val['name']);
                        $title_mod = substr($title_mod,0,30);
                        
                        if($title_len > 30)
                        {
                            $title_mod .= '...';   
                        }
                    ?>
                        <div class="col-6 col-lg-2 text-center mb-3">
                            <div class="bg-light br-2 text-left" style="height: 200px;">
                                <h4 class="p-3"><?=$title_mod?></h4>    
                            </div>
                            <?=$val['name']?>
                            <div>
                                <small>
                                    <a href="<?=APP_URL.'?page=restaurant_menu_add_edit&restaurant_id='.$g_id.'&rm_id='.$id?>">Edit</a>
                                </small>
                            </div>
                        </div>
                    <?php    
                    }
                    
                }
            ?>
            
            
        </div>
    </div>
<?php    
}
elseif($page == 'restaurant_menu_add_edit')
{
    $g_restaurant_id = isset($_GET['restaurant_id']) ? $_GET['restaurant_id'] : 0;
    if(! preg_match('/^[0-9]*$/', $g_restaurant_id)) 
    {
        die('Restaurant ID Invalid');        
    }
    
    $g_rm_id = isset($_GET['rm_id']) ? $_GET['rm_id'] : 0;
    if(! preg_match('/^[0-9]*$/', $g_restaurant_id)) 
    {
        die('Restaurant Menu ID Invalid');        
    }
    $rm_tag = '';    
        
    if($g_restaurant_id > 0)
    {
        $query = "
            select
                *
            from
                restaurant
            where
                restaurant_id = '".$g_restaurant_id."'
        ";
        $result = $db->query($query);    
        $data = $result->fetchArray();
    }
    
    if($g_rm_id > 0)
    {
        $query = "
            select
                *
            from
                restaurant_menu
            where
                rm_id = '".$g_rm_id."'
        ";
        $result = $db->query($query);    
        $data_menu = $result->fetchArray();
        
        $query = "
            select
                tag.tag_id,
                tag.tag_name
            from
                restaurant_menu_tag
            inner join
                tag
                on tag.tag_id = restaurant_menu_tag.tag_id  
            where
                restaurant_menu_tag.rm_id = '".$g_rm_id."'
            order by
                tag.tag_name
        ";
        $result = $db->query($query) or die('ERROR!|WQUIEHQWUIEHUQWE');
        $rm_tag = '';
        while($row = $result->fetchArray())
        {
            if($rm_tag != '')
            {
                $rm_tag .= ',';
            }
            $rm_tag .= $row['tag_name'];     
        }
    }
?>
    <div class="container">
        <h1>
            <a href="<?=APP_URL.'?page=restaurant_menu&restaurant_id='.$g_restaurant_id?>">
                <svg id="i-chevron-left" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32" width="32" height="32" fill="none" stroke="currentcolor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
                    <path d="M20 30 L8 16 20 2" />
                </svg>
                <?=$data['restaurant_name']?>
            </a>
            <svg id="i-chevron-right" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32" width="32" height="32" fill="none" stroke="currentcolor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
                <path d="M12 30 L24 16 12 2" />
            </svg>
            Menu
            <svg id="i-chevron-right" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32" width="32" height="32" fill="none" stroke="currentcolor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
                <path d="M12 30 L24 16 12 2" />
            </svg>
            <?=$g_rm_id > 0 ? 'Edit #'.$g_rm_id : 'Add'?>
        </h1>
        <hr>
        <div class="row">
            <div class="col-12 col-lg-4">
                <form method="post" action="<?=APP_URL?>?page=restaurant_menu_add_edit" accept-charset="utf-8">
                    <label>Restaurant Menu Name</label>
                    <input type="text" name="rm_name" value="<?=isset($data_menu['rm_name']) ? $data_menu['rm_name'] : ''?>">
                    <label>Restaurant Menu Category Tag</label>
                    <br><small class="color-muted">Spare with ",", for now</small>
                    <input type="text" name="rm_tag" value="<?=$rm_tag?>">
                    <hr>
                    <input type="hidden" name="restaurant_id" value="<?=$g_restaurant_id?>">
                    <input type="hidden" name="rm_id" value="<?=$g_rm_id?>">
                    <input type="submit" name="submit" class="bg-primary color-white" value="Submit">
                </form>   
            </div>
            
        </div>
    </div>
<?php    
}
elseif($page == 'person')
{
    $arr_data['list'] = array();
    $query = "
        select
            *
        from
            person
        order by
            person_name ASC
    ";
    //echo "<pre>$query</pre>";
    $result = $db->query($query) or die('ERROR|WQIEHQUIWEHUIQWHEUQWE');    
    
    while($row = $result->fetchArray())
    {
        $arr_data['list'][$row['person_id']]['name'] = $row['person_name'];    
        $arr_data['list'][$row['person_id']]['initial_name'] = $row['initial_name'];    
        $arr_data['list'][$row['person_id']]['remarks'] = $row['remarks'];    
    }
?>
    <div class="container">
        <h1>
            <a href="<?=APP_URL.'?page=home'?>">
                <svg id="i-chevron-left" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32" width="32" height="32" fill="none" stroke="currentcolor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
                    <path d="M20 30 L8 16 20 2" />
                </svg>
            
                Person
            </a>
        </h1>
        <div class="text-right">
            <a href="<?=APP_URL.'?page=person_add_edit&person_id=0'?>" class="button bg-success color-white">New</a>
        </div>
        <hr>
        <div class="row">
            <?php
                if(count($arr_data['list']) == 0)
                {
                ?>
                    <div class="col-12 text-center">
                        No Data
                    </div>
                <?php
                }
                else
                {
                    foreach($arr_data['list'] as $id => $val)
                    {
                    ?>
                        <div class="col-4 col-lg-2 text-center mb-3">
                            <div class="bg-light br-2 position-relative" style="height: 100px;">
                                <h1 class="position-absolute" style="bottom: 0; right: 0; margin: 10px;"><?=$val['initial_name']?></h1>    
                            </div>
                            <?=$val['name']?>
                            <br><small class="color-muted"><?=$val['remarks']?></small>
                            <div>
                                <small>
                                    <a href="<?=APP_URL.'?page=person_add_edit&person_id='.$id?>">Edit</a>
                                </small>
                            </div>
                        </div>
                    <?php    
                    }
                    
                }
            ?>
            
            
        </div>
    </div>
<?php    
}
elseif($page == 'person_add_edit')
{
    $g_id = isset($_GET['person_id']) ? $_GET['person_id'] : 0;
    if(! preg_match('/^[0-9]*$/', $g_id)) 
    {
        die('ID Invalid');        
    }
    
    if($g_id > 0)
    {
        $query = "
            select
                *
            from
                person
            where
                person_id = '".$g_id."'
        ";
        $result = $db->query($query);    
        $data = $result->fetchArray();
    }
?>
    <div class="container">
        <h1>
            <a href="<?=APP_URL.'?page=person'?>">
                <svg id="i-chevron-left" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32" width="32" height="32" fill="none" stroke="currentcolor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
                    <path d="M20 30 L8 16 20 2" />
                </svg>
                Person
            </a>
            <svg id="i-chevron-right" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32" width="32" height="32" fill="none" stroke="currentcolor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
                <path d="M12 30 L24 16 12 2" />
            </svg>
            Add / Edit
        </h1>
        <hr>
        <div class="row">
            <div class="col-12 col-lg-4">
                <form method="post" action="<?=APP_URL?>?page=person_add_edit" accept-charset="utf-8">
                    <label>Full Name</label>
                    <input type="text" name="person_name" value="<?=isset($data['person_name']) ? $data['person_name'] : ''?>">
                    <label>Initial Name</label>
                    <input type="text" name="initial_name" value="<?=isset($data['initial_name']) ? $data['initial_name'] : ''?>">
                    <label>Remarks</label>
                    <input type="text" name="remarks" value="<?=isset($data['remarks']) ? $data['remarks'] : ''?>">
                    <hr>
                    <input type="hidden" name="person_id" value="<?=$g_id?>">
                    <input type="submit" name="submit" class="bg-primary color-white" value="Submit">
                </form>   
            </div>
            
        </div>
    </div>
<?php    
}
elseif($page == 'split_bill')
{
    $g_book_id = isset($_GET['book_id']) ? $_GET['book_id'] : 0;
    if(! preg_match('/^[0-9]*$/', $g_book_id)) 
    {
        die('Book ID Invalid');        
    }
    
    if($g_book_id > 0)
    {
        $query = "
            select
                *
            from
                book
            where
                book_id = '".$g_book_id."'
        ";
        $result = $db->query($query);    
        $data = $result->fetchArray();
        
        if($data['user_id'] != $ses['user_id'])
        {
            die('Book not yours!');    
        }
    }
    else
    {
        die('Book Invalid');
    }
    
    $arr_data['list_invoice'] = array();
    $query = "
        select
            split_bill.*,
            invoice.book_id,
            invoice.created_at as inv_created_at,
            invoice.restaurant_id,
            invoice.invoice_date,
            restaurant.restaurant_name,
            res_sb.total_sb
        from
            split_bill
        inner join
            invoice
            on invoice.invoice_id = split_bill.invoice_id
            and invoice.book_id = '".$g_book_id."'
        inner join
            restaurant
            on restaurant.restaurant_id = invoice.restaurant_id
        left join
        (
            select
                split_bill_details.sb_id,
                SUM
                (
                    split_bill_details.item_amount
                    + split_bill_details.tax_amount 
                    - split_bill_details.discount_amount 
                    + split_bill_details.delivery_amount 
                    + split_bill_details.other_amount 
                    + split_bill_details.adjustment_amount 
                ) as total_sb
            from
                split_bill_details
            group by
                split_bill_details.sb_id 
        ) as res_sb
        on res_sb.sb_id = split_bill.sb_id 
        order by
            split_bill.sb_date DESC
    ";
    $result = $db->query($query);    
   
    while($row = $result->fetchArray())
    {
        $arr_data['list_invoice'][$row['sb_date']][$row['sb_id']]['inv_code'] = 'INV/'.$row['book_id'].'/'.$row['invoice_id'];    
        $arr_data['list_invoice'][$row['sb_date']][$row['sb_id']]['sb_code'] = 'SB/'.$row['book_id'].'/'.$row['invoice_id'].'/'.$row['sb_id'];    
        $arr_data['list_invoice'][$row['sb_date']][$row['sb_id']]['restaurant_name'] = $row['restaurant_name'];    
        $arr_data['list_invoice'][$row['sb_date']][$row['sb_id']]['total_sb'] = $row['total_sb'];    
    }
?>
    <div class="container">
        <h1>
            <a href="<?=APP_URL.'?page=book_details&book_id='.$g_book_id?>">
                <svg id="i-chevron-left" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32" width="32" height="32" fill="none" stroke="currentcolor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
                    <path d="M20 30 L8 16 20 2" />
                </svg>
            
                <?=isset($data['book_title']) ? $data['book_title'] : ''?>
            </a>
            <svg id="i-chevron-right" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32" width="32" height="32" fill="none" stroke="currentcolor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
                <path d="M12 30 L24 16 12 2" />
            </svg>
            Split Bill
        </h1>
        <div class="text-right mb-3">
            <a href="<?=APP_URL.'?page=split_bill_add_edit&book_id='.$g_book_id.'&sb_id=0'?>" class="button bg-success color-white">New</a>
        </div>
        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Split Bill ID</th>
                    <th>Invoice ID</th>
                    <th>Restaurant</th>
                    <th>Total</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    if(count($arr_data['list_invoice']) == 0)
                    {
                    ?>
                        <tr>
                            <td colspan="100">No Data</td>
                        </tr> 
                    <?php
                    }
                    else
                    {
                        foreach($arr_data['list_invoice'] as $invoice_date => $val)
                        {
                            $invoice_date_show = date('d-m-Y',$invoice_date);
                            foreach($arr_data['list_invoice'][$invoice_date] as $invoice_id => $val)
                            {
                            ?>
                                <tr>
                                    <td><?=$invoice_date_show?></td>
                                    <td><?=$val['sb_code']?> </td>
                                    <td><?=$val['inv_code']?> </td>
                                    <td><?=$val['restaurant_name']?> </td>
                                    <td class="text-right"><?=parsenumber($val['total_sb'],2)?></td>
                                    <td>
                                        <a class="button bg-warning" href="<?=APP_URL.'?page=split_bill_add_edit&book_id='.$g_book_id.'&sb_id='.$invoice_id?>">Edit</a>
                                        </a>
                                    </td>
                                </tr>
                            <?php
                                $invoice_date_show = '';    
                            }    
                        }
                            
                        
                    }
                ?>
            </tbody>  
        </table>
        
    </div>
<?php    
}
elseif($page == 'split_bill_add_edit')
{
    $g_book_id = isset($_GET['book_id']) ? $_GET['book_id'] : 0;
    $g_sb_id = isset($_GET['sb_id']) ? $_GET['sb_id'] : 0;
    $g_invoice_id = isset($_GET['invoice_id']) ? $_GET['invoice_id'] : 0;
    
    if(! preg_match('/^[0-9]*$/', $g_book_id)) 
    {
        die('Book ID Invalid');        
    }
    
    if(! preg_match('/^[0-9]*$/', $g_sb_id)) 
    {
        die('SB ID Invalid');        
    }
    
    if($g_book_id > 0)
    {
        $query = "
            select
                *
            from
                book
            where
                book_id = '".$g_book_id."'
        ";
        $result = $db->query($query);    
        $data = $result->fetchArray();
        
        if($data['user_id'] != $ses['user_id'])
        {
            die('Book not yours!');    
        }
    }

    $arr_data['list_invoice'] = array();
    $query = "
        select
            invoice.*,
            res_details.total,
            restaurant.restaurant_name
        from
            invoice
        inner join
        (
            select
                invoice_id,
                SUM(qty*price) as total
            from
                invoice_details
            group by
                invoice_id
        ) as res_details
        on res_details.invoice_id = invoice.invoice_id
        inner join
            restaurant
            on restaurant.restaurant_id = invoice.restaurant_id
        where
            invoice.book_id = '".$g_book_id."'
        order by
            invoice.invoice_date DESC,
            invoice.invoice_id DESC
    ";
    $result = $db->query($query);    

    while($row = $result->fetchArray())
    {
        $arr_data['list_invoice'][$row['invoice_date']][$row['invoice_id']]['title'] = 'INV/'.$row['book_id'].'/'.$row['invoice_id'];    
        $arr_data['list_invoice'][$row['invoice_date']][$row['invoice_id']]['tax_amount'] = $row['tax_amount'];    
        $arr_data['list_invoice'][$row['invoice_date']][$row['invoice_id']]['discount_amount'] = $row['discount_amount'];    
        $arr_data['list_invoice'][$row['invoice_date']][$row['invoice_id']]['delivery_amount'] = $row['delivery_amount'];    
        $arr_data['list_invoice'][$row['invoice_date']][$row['invoice_id']]['other_amount'] = $row['other_amount'];    
        $arr_data['list_invoice'][$row['invoice_date']][$row['invoice_id']]['adjustment_amount'] = $row['adjustment_amount'];    
        $arr_data['list_invoice'][$row['invoice_date']][$row['invoice_id']]['total'] = $row['total'];
        $arr_data['list_invoice'][$row['invoice_date']][$row['invoice_id']]['restaurant_name'] = $row['restaurant_name'];

        $arr_data['list_invoice_only'][$row['invoice_id']]['tax_amount'] = $row['tax_amount'];    
        $arr_data['list_invoice_only'][$row['invoice_id']]['discount_amount'] = $row['discount_amount'];    
        $arr_data['list_invoice_only'][$row['invoice_id']]['delivery_amount'] = $row['delivery_amount'];    
        $arr_data['list_invoice_only'][$row['invoice_id']]['other_amount'] = $row['other_amount'];    
        $arr_data['list_invoice_only'][$row['invoice_id']]['adjustment_amount'] = $row['adjustment_amount'];    
        $arr_data['list_invoice_only'][$row['invoice_id']]['item_amount'] = 0;    
        $arr_data['list_invoice_only'][$row['invoice_id']]['total'] = $row['total'];   
    }
    
    //load details
    $arr_data['list_invoice_details'] = array();
    $query = "
        select
            invoice_details.*,
            restaurant_menu.rm_name
        from
            invoice_details
        inner join
            invoice
            on invoice.invoice_id = invoice_details.invoice_id 
            and invoice.book_id = '".$g_book_id."'
        inner join
            restaurant_menu
            on restaurant_menu.rm_id = invoice_details.rm_id 
        order by
            restaurant_menu.rm_name ASC
    ";
    $result = $db->query($query);    

    while($row = $result->fetchArray())
    {
        $arr_data['list_invoice_details'][$row['invoice_id']][$row['rm_id']]['name'] = $row['rm_name'];    
        $arr_data['list_invoice_details'][$row['invoice_id']][$row['rm_id']]['qty'] = $row['qty'];    
        $arr_data['list_invoice_details'][$row['invoice_id']][$row['rm_id']]['price'] = $row['price'];

        $arr_data['list_invoice_only'][$row['invoice_id']]['item_amount'] += $row['qty']*$row['price'];    
    }
    
    if($g_sb_id > 0)
    {
        //load header
        $query = "
            select
                *
            from
                split_bill
            where
                split_bill.sb_id = '".$g_sb_id."'
        ";
        $result = $db->query($query);
        $arr_data['list_sb_header'] = array();
        while($row = $result->fetchArray())
        {
            $arr_data['list_sb_header']['invoice_id'] = $row['invoice_id'];  
            $arr_data['list_sb_header']['sb_date'] = $row['sb_date']; 
        }
        
        //load details
        $query = "
            select
                split_bill_details.*,
                person.person_name
            from
                split_bill_details
            inner join
                person
                on person.person_id = split_bill_details.person_id 
            where
                split_bill_details.sb_id = '".$g_sb_id."'
            order by
                person.person_name ASC
        ";
        $result = $db->query($query);
        $arr_data['list_sb_details'] = array();
        $no = 0;
        $arr_data['list_sb_header']['item_amount'] = 0;
        $arr_data['list_sb_header']['tax_amount'] = 0;
        $arr_data['list_sb_header']['discount_amount'] = 0;
        $arr_data['list_sb_header']['delivery_amount'] = 0;
        $arr_data['list_sb_header']['other_amount'] = 0;
        $arr_data['list_sb_header']['adjustment_amount'] = 0;
        $arr_data['list_sb_header']['person'] = 0;
        while($row = $result->fetchArray())
        {
            $no++;
            $arr_data['list_sb_details'][$no]['person_id'] = $row['person_id']*1;  
            $arr_data['list_sb_details'][$no]['person_name'] = $row['person_name'];  
            $arr_data['list_sb_details'][$no]['item_amount'] = (float) $row['item_amount'];  
            $arr_data['list_sb_details'][$no]['tax_amount'] = (float) $row['tax_amount'];  
            $arr_data['list_sb_details'][$no]['discount_amount'] = (float) $row['discount_amount'];  
            $arr_data['list_sb_details'][$no]['delivery_amount'] = (float) $row['delivery_amount'];  
            $arr_data['list_sb_details'][$no]['other_amount'] = (float) $row['other_amount'];  
            $arr_data['list_sb_details'][$no]['adjustment_amount'] = (float) $row['adjustment_amount'];  
            $arr_data['list_sb_details'][$no]['remarks'] = $row['remarks']; 
            $arr_data['list_sb_header']['item_amount'] += (float) $row['item_amount'];
            $arr_data['list_sb_header']['tax_amount'] += (float) $row['tax_amount'];
            $arr_data['list_sb_header']['discount_amount'] += (float) $row['discount_amount'];
            $arr_data['list_sb_header']['delivery_amount'] += (float) $row['delivery_amount'];
            $arr_data['list_sb_header']['other_amount'] += (float) $row['other_amount'];
            $arr_data['list_sb_header']['adjustment_amount'] += (float) $row['adjustment_amount']; 
            $arr_data['list_sb_header']['person'] += 1; 
        }
    }
    
    //print('<pre>'.print_r($arr_data['list_sb_header'],true).'</pre>');
   
    //load person
    $query = "
        select
            *
        from
            person
        order by
            person_name ASC
    ";
    $result = $db->query($query);
    $arr_data['list_person'] = array();
    while($row = $result->fetchArray())
    {
        $arr_data['list_person'][$row['person_id']]['name'] = $row['initial_name'].' - '.$row['person_name'];    
    }
?>
    <div class="container">
        <h1>
            <a href="<?=APP_URL.'?page=split_bill&book_id='.$g_book_id?>">
                <svg id="i-chevron-left" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32" width="32" height="32" fill="none" stroke="currentcolor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
                    <path d="M20 30 L8 16 20 2" />
                </svg>
            
                Split Bill
            </a>
            <svg id="i-chevron-right" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32" width="32" height="32" fill="none" stroke="currentcolor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
                <path d="M12 30 L24 16 12 2" />
            </svg>
            Add / Edit
        </h1>
        
        <form method="post" target="iframe_post" action="<?=APP_URL?>?page=split_bill_add_edit" accept-charset="utf-8">
            <div class="row">
                <div class="col-12 col-lg-12">
                    <div class="text-right">
                        <button type="button" onclick="reCalc()">ReCalc</button>
                    </div>
                    
                    <hr>
                    <label>Invoice</label>
                    <select id="invoice_id" name="invoice_id">
                        <option value="0">-</option>
                        <?php
                        if(count($arr_data['list_invoice']) > 0)
                        {
                            foreach($arr_data['list_invoice'] as $invoice_date => $val)
                            {
                                echo '<optgroup label="'.date('d-m-Y',$invoice_date).'">';
                                foreach($arr_data['list_invoice'][$invoice_date] as $invoice_id => $val)
                                {
                                    $list_menu = '';
                                    
                                    foreach($arr_data['list_invoice_details'][$invoice_id] as $rm_id => $value)
                                    {
                                        $list_menu .= '<tr>';
                                        $list_menu .= '<td>'.$value['name'].'</td>';
                                        $list_menu .= '<td align=right>'.parsenumber($value['qty']).'<br><input size=1 id=inputsub__##__qty type=text value=1><button type=button onclick=panel_menu_qty(##,'.($value['price']).');>PER</button></td>';
                                        $list_menu .= '<td align=right>'.parsenumber($value['price']).'<br><button type=button onclick=panel_menu(##,'.($value['price']).');>Once</button></td>';
                                        $list_menu .= '<td align=center>'.parsenumber($value['qty']*$value['price'],2).'<br><button type=button onclick=panel_menu(##,'.($value['qty']*$value['price']).');>ALL</button></td>';
                                        $list_menu .= '</tr>';     
                                    }
                                    
                                    if($list_menu != '')
                                    {
                                        $list_menu = '<table><tr><th>Product</th><th>Qty</th><th>Price</th><th>Total</th></tr>'.$list_menu.'</table>';
                                    }
                                    
                                    $js = '
                                        document.getElementById(\'inv__item\').innerHTML = \''.parsenumber($val['total']).'\';
                                        document.getElementById(\'inv__item_hid\').value = \''.$val['total'].'\';
                                        document.getElementById(\'inv__tax\').innerHTML = \''.parsenumber($val['tax_amount']).'\';
                                        document.getElementById(\'inv__tax_hid\').value = \''.$val['tax_amount'].'\';
                                        document.getElementById(\'inv__discount\').innerHTML = \''.parsenumber($val['discount_amount']).'\';
                                        document.getElementById(\'inv__discount_hid\').value = \''.$val['discount_amount'].'\';
                                        document.getElementById(\'inv__delivery\').innerHTML = \''.parsenumber($val['delivery_amount']).'\';
                                        document.getElementById(\'inv__delivery_hid\').value = \''.$val['delivery_amount'].'\';
                                        document.getElementById(\'inv__other\').innerHTML = \''.parsenumber($val['other_amount']).'\';
                                        document.getElementById(\'inv__other_hid\').value = \''.$val['other_amount'].'\';
                                        document.getElementById(\'inv__total\').innerHTML = \''.parsenumber($val['total']+$val['tax_amount']-$val['discount_amount']+$val['delivery_amount']+$val['other_amount']).'\';
                                        document.getElementById(\'inv__total_hid\').value = \''.($val['total']+$val['tax_amount']-$val['discount_amount']+$val['delivery_amount']+$val['other_amount']).'\';
                                        document.getElementById(\'sb_date\').value = \''.date('Y-m-d',$invoice_date).'\';
                                        
                                        let dl = document.getElementsByClassName(\'panel__menu\');
                                        for(let x = 0; x < dl.length; x++)
                                        {
                                            let id = dl[x].id;
                                            let id_split = id.split(\'__\')
                                            dl[x].innerHTML = (\''.$list_menu.'\').replaceAll(\'##\',id_split[1]);
                                        }
                                    ';
                                ?>
                                    <option onclick="<?=$js?>" value="<?=$invoice_id?>"<?=isset($arr_data['list_sb_header']) && $arr_data['list_sb_header']['invoice_id'] == $invoice_id ? ' selected' : ''?>><?=$val['title'].' - '.$val['restaurant_name']?></option>
                                <?php
                                } 
                                echo '</optgroup>';   
                            }
                                
                        }
                        ?>
                    </select>
                    <label>Split Bill Date</label>
                    <input type="date" id="sb_date" name="sb_date" value="<?=isset($arr_data['list_sb_header']['sb_date']) ? date('Y-m-d',$arr_data['list_sb_header']['sb_date']) : date('Y-m-d')?>">
                        
                    <table>
                        <tr>
                            <th colspan="2">Invoice</th>
                            <td align="right">
                                <span id="inv__item"><?=isset($arr_data['list_sb_header']['invoice_id']) && isset($arr_data['list_invoice_only'][$arr_data['list_sb_header']['invoice_id']]['item_amount']) ? parsenumber($arr_data['list_invoice_only'][$arr_data['list_sb_header']['invoice_id']]['item_amount']) : ''?></span>
                                <input type="hidden" id="inv__item_hid" value="<?=isset($arr_data['list_sb_header']['invoice_id']) && isset($arr_data['list_invoice_only'][$arr_data['list_sb_header']['invoice_id']]['item_amount']) ? $arr_data['list_invoice_only'][$arr_data['list_sb_header']['invoice_id']]['item_amount'] : ''?>">
                            </td>
                            <td align="right">
                                &nbsp;
                            </td>
                            <td align="right">
                                <span id="inv__tax"><?=isset($arr_data['list_sb_header']['invoice_id']) && isset($arr_data['list_invoice_only'][$arr_data['list_sb_header']['invoice_id']]['tax_amount']) ? parsenumber($arr_data['list_invoice_only'][$arr_data['list_sb_header']['invoice_id']]['tax_amount']) : ''?></span>
                                <input type="hidden" id="inv__tax_hid" value="<?=isset($arr_data['list_sb_header']['invoice_id']) && isset($arr_data['list_invoice_only'][$arr_data['list_sb_header']['invoice_id']]['tax_amount']) ? $arr_data['list_invoice_only'][$arr_data['list_sb_header']['invoice_id']]['tax_amount'] : ''?>">
                            </td>
                            <td align="right">
                                <span id="inv__discount"><?=isset($arr_data['list_sb_header']['invoice_id']) && isset($arr_data['list_invoice_only'][$arr_data['list_sb_header']['invoice_id']]['discount_amount']) ? parsenumber($arr_data['list_invoice_only'][$arr_data['list_sb_header']['invoice_id']]['discount_amount']) : ''?></span>
                                <input type="hidden" id="inv__discount_hid" value="<?=isset($arr_data['list_sb_header']['invoice_id']) && isset($arr_data['list_invoice_only'][$arr_data['list_sb_header']['invoice_id']]['discount_amount']) ? $arr_data['list_invoice_only'][$arr_data['list_sb_header']['invoice_id']]['discount_amount'] : ''?>">
                            </td>
                            <td align="right">
                                <span id="inv__delivery"><?=isset($arr_data['list_sb_header']['invoice_id']) && isset($arr_data['list_invoice_only'][$arr_data['list_sb_header']['invoice_id']]['delivery_amount']) ? parsenumber($arr_data['list_invoice_only'][$arr_data['list_sb_header']['invoice_id']]['delivery_amount']) : ''?></span>
                                <input type="hidden" id="inv__delivery_hid" value="<?=isset($arr_data['list_sb_header']['invoice_id']) && isset($arr_data['list_invoice_only'][$arr_data['list_sb_header']['invoice_id']]['delivery_amount']) ? $arr_data['list_invoice_only'][$arr_data['list_sb_header']['invoice_id']]['delivery_amount'] : ''?>">
                            </td>
                            <td align="right">
                                <span id="inv__other"><?=isset($arr_data['list_sb_header']['invoice_id']) && isset($arr_data['list_invoice_only'][$arr_data['list_sb_header']['invoice_id']]['other_amount']) ? parsenumber($arr_data['list_invoice_only'][$arr_data['list_sb_header']['invoice_id']]['other_amount']) : ''?></span>
                                <input type="hidden" id="inv__other_hid" value="<?=isset($arr_data['list_sb_header']['invoice_id']) && isset($arr_data['list_invoice_only'][$arr_data['list_sb_header']['invoice_id']]['other_amount']) ? $arr_data['list_invoice_only'][$arr_data['list_sb_header']['invoice_id']]['other_amount'] : ''?>">
                            </td>
                            <td align="right">
                                <span id="inv__adjustment"><?=isset($arr_data['list_sb_header']['invoice_id']) && isset($arr_data['list_invoice_only'][$arr_data['list_sb_header']['invoice_id']]['adjustment_amount']) ? parsenumber($arr_data['list_invoice_only'][$arr_data['list_sb_header']['invoice_id']]['adjustment_amount']) : ''?></span>
                                <input type="hidden" id="inv__adjustment_hid" value="<?=isset($arr_data['list_sb_header']['invoice_id']) && isset($arr_data['list_invoice_only'][$arr_data['list_sb_header']['invoice_id']]['adjustment_amount']) ? $arr_data['list_invoice_only'][$arr_data['list_sb_header']['invoice_id']]['adjustment_amount'] : ''?>">
                            </td>
                            <td align="right">
                                <span id="inv__total"><?=isset($arr_data['list_sb_header']['invoice_id']) && isset($arr_data['list_invoice_only'][$arr_data['list_sb_header']['invoice_id']]['item_amount']) ? parsenumber($arr_data['list_invoice_only'][$arr_data['list_sb_header']['invoice_id']]['item_amount']+$arr_data['list_invoice_only'][$arr_data['list_sb_header']['invoice_id']]['tax_amount']-$arr_data['list_invoice_only'][$arr_data['list_sb_header']['invoice_id']]['discount_amount']+$arr_data['list_invoice_only'][$arr_data['list_sb_header']['invoice_id']]['delivery_amount']+$arr_data['list_invoice_only'][$arr_data['list_sb_header']['invoice_id']]['other_amount']+$arr_data['list_invoice_only'][$arr_data['list_sb_header']['invoice_id']]['adjustment_amount']) : ''?></span>
                                <input type="hidden" id="inv__total_hid" value="<?=isset($arr_data['list_sb_header']['invoice_id']) && isset($arr_data['list_invoice_only'][$arr_data['list_sb_header']['invoice_id']]['item_amount']) ? $arr_data['list_invoice_only'][$arr_data['list_sb_header']['invoice_id']]['item_amount']+$arr_data['list_invoice_only'][$arr_data['list_sb_header']['invoice_id']]['tax_amount']-$arr_data['list_invoice_only'][$arr_data['list_sb_header']['invoice_id']]['discount_amount']+$arr_data['list_invoice_only'][$arr_data['list_sb_header']['invoice_id']]['delivery_amount']+$arr_data['list_invoice_only'][$arr_data['list_sb_header']['invoice_id']]['other_amount']+$arr_data['list_invoice_only'][$arr_data['list_sb_header']['invoice_id']]['adjustment_amount'] : ''?>">
                            </td>
                            <td align="right">
                                &nbsp;
                            </td>
                        </tr>
                        <tr>
                            <th rowspan="2">No</th>
                            <th>Person</th>
                            <th>Items</th>
                            <th>Items %</th>
                            <th>Tax / Items %</th>
                            <th>Discount / Items %</th>
                            <th>Delivery / Total Person</th>
                            <th>Other / Total Person</th>
                            <th>Adjustment</th>
                            <th>Total</th>
                            <th rowspan="2">Remarks</th>
                        </tr>
                        <tr>
                            <th>
                                <span id="info__tot__person"><?=isset($arr_data['list_sb_header']['person']) ? parsenumber($arr_data['list_sb_header']['person']) : ''?></span>
                                <input type="hidden" id="info__tot__person_hid" value="<?=isset($arr_data['list_sb_header']['person']) ? $arr_data['list_sb_header']['person'] : ''?>">
                            </th>
                            <th>
                                <span id="info__tot__items"><?=isset($arr_data['list_sb_header']['item_amount']) ? parsenumber($arr_data['list_sb_header']['item_amount']) : ''?></span>
                                <input type="hidden" id="info__tot__items_hid" value="<?=isset($arr_data['list_sb_header']['item_amount']) ? $arr_data['list_sb_header']['item_amount'] : ''?>">
                            </th>
                            <th>
                                <span id="info__tot__items_percent"><?=isset($arr_data['list_sb_header']['item_amount']) ? parsenumber($arr_data['list_sb_header']['item_amount']/$arr_data['list_sb_header']['item_amount']*100) : ''?></span>
                                <input type="hidden" id="info__tot__items_percent_hid" value="<?=isset($arr_data['list_sb_header']['item_amount']) ? $arr_data['list_sb_header']['item_amount']/$arr_data['list_sb_header']['item_amount']*100 : ''?>">
                            </th>
                            <th>
                                <span id="info__tot__tax"><?=isset($arr_data['list_sb_header']['tax_amount']) ? parsenumber($arr_data['list_sb_header']['tax_amount']) : ''?></span>
                                <input type="hidden" id="info__tot__tax_hid" value="<?=isset($arr_data['list_sb_header']['tax_amount']) ? $arr_data['list_sb_header']['tax_amount'] : ''?>">
                            </th>
                            <th>
                                <span id="info__tot__discount"><?=isset($arr_data['list_sb_header']['discount_amount']) ? parsenumber($arr_data['list_sb_header']['discount_amount']) : ''?></span>
                                <input type="hidden" id="info__tot__discount_hid" value="<?=isset($arr_data['list_sb_header']['discount_amount']) ? $arr_data['list_sb_header']['discount_amount'] : ''?>">
                            </th>
                            <th>
                                <span id="info__tot__delivery"><?=isset($arr_data['list_sb_header']['delivery_amount']) ? parsenumber($arr_data['list_sb_header']['delivery_amount']) : ''?></span>
                                <input type="hidden" id="info__tot__delivery_hid" value="<?=isset($arr_data['list_sb_header']['delivery_amount']) ? $arr_data['list_sb_header']['delivery_amount'] : ''?>">
                            </th>
                            <th>
                                <span id="info__tot__other"><?=isset($arr_data['list_sb_header']['other_amount']) ? parsenumber($arr_data['list_sb_header']['other_amount']) : ''?></span>
                                <input type="hidden" id="info__tot__other_hid" value="<?=isset($arr_data['list_sb_header']['other_amount']) ? $arr_data['list_sb_header']['other_amount'] : ''?>">
                            </th>
                            <th>
                                <span id="info__tot__adjustment"><?=isset($arr_data['list_sb_header']['adjustment_amount']) ? parsenumber($arr_data['list_sb_header']['adjustment_amount']) : ''?></span>
                                <input type="hidden" id="info__tot__adjustment_hid" value="<?=isset($arr_data['list_sb_header']['adjustment_amount']) ? $arr_data['list_sb_header']['adjustment_amount'] : ''?>">
                            </th>
                            <th>
                                <span id="info__tot__total"><?=isset($arr_data['list_sb_header']['item_amount']) ? parsenumber($arr_data['list_sb_header']['item_amount']+$arr_data['list_sb_header']['tax_amount']-$arr_data['list_sb_header']['discount_amount']+$arr_data['list_sb_header']['delivery_amount']+$arr_data['list_sb_header']['other_amount']+$arr_data['list_sb_header']['adjustment_amount']) : ''?></span>
                                <input type="hidden" id="info__tot__total_hid" value="<?=isset($arr_data['list_sb_header']['item_amount']) ? $arr_data['list_sb_header']['item_amount']+$arr_data['list_sb_header']['tax_amount']-$arr_data['list_sb_header']['discount_amount']+$arr_data['list_sb_header']['delivery_amount']+$arr_data['list_sb_header']['other_amount']+$arr_data['list_sb_header']['adjustment_amount'] : ''?>">
                            </th>
                        </tr>
                        <?php
                        for($x = 1; $x <= 10; $x++)
                        {
                        ?>
                            <tr>
                                <td><?=$x?></td>
                                <td>
                                    <select class="select__person" name="sb_list[<?=$x?>][person_id]" id="input__<?=$x?>__person_id">
                                        <option value="0">-</option>
                                        <?php
                                            if(isset($arr_data['list_person']) && count($arr_data['list_person']) > 0)
                                            {
                                                foreach($arr_data['list_person'] as $person_id => $val)
                                                {
                                                ?>
                                                    <option value="<?=$person_id?>"<?=isset($arr_data['list_sb_details'][$x]['person_id']) && $arr_data['list_sb_details'][$x]['person_id'] == $person_id ? ' selected' : ''?>><?=$val['name']?></option>
                                                <?php
                                                }
                                            }
                                        ?>
                                    </select>
                                </td>
                                <td align="right">
                                    <?php
                                        $js = '
                                            if(Number(document.getElementById(\'input__'.$x.'__items_amount_panel_tgg\').value) == 0)
                                            {
                                                document.getElementById(\'input__'.$x.'__items_amount_panel\').style.display = \'\';
                                                document.getElementById(\'input__'.$x.'__items_amount_panel_tgg\').value = 1;       
                                            }
                                            else
                                            {
                                                document.getElementById(\'input__'.$x.'__items_amount_panel\').style.display = \'none\';
                                                document.getElementById(\'input__'.$x.'__items_amount_panel_tgg\').value = 0;
                                            }
                                        ';
                                        
                                        $js_set = '
                                            document.getElementById(\'input__'.$x.'__items_amount\').value = document.getElementById(\'input__'.$x.'__items_amount_calc\').value; 
                                            document.getElementById(\'input__'.$x.'__items_amount_show\').innerHTML = document.getElementById(\'input__'.$x.'__items_amount_calc\').value; 
                                            document.getElementById(\'input__'.$x.'__items_amount_panel\').style.display = \'none\';
                                            document.getElementById(\'input__'.$x.'__items_amount_panel_tgg\').value = 0;
                                            reCalc();
                                        ';
                                    ?>
                                    <button onclick="<?=$js?>" type="button" id="input__<?=$x?>__items_amount_show"><?=isset($arr_data['list_sb_details'][$x]['item_amount']) ? parsenumber($arr_data['list_sb_details'][$x]['item_amount']) : 0?></button>
                                    <input class="data__loop" type="hidden" name="sb_list[<?=$x?>][items]" id="input__<?=$x?>__items_amount" value="<?=isset($arr_data['list_sb_details'][$x]['item_amount']) ? $arr_data['list_sb_details'][$x]['item_amount'] : ''?>">   
                                    <input type="hidden" id="input__<?=$x?>__items_amount_panel_tgg" value="0">
                                    <div id="input__<?=$x?>__items_amount_panel" class="position-absolute bg-light-lighten" style="display:none;;">
                                        <div class="panel__menu" id="input__<?=$x?>__items_amount_panel_sub"></div>
                                        <select id="input__<?=$x?>__items_amount_calc_mode">
                                            <option value="+">+ ADD</option>
                                            <option value="=">= SET</option>
                                            <option value="-">- MENUS</option>
                                        </select>
                                        <input type="text" id="input__<?=$x?>__items_amount_calc" value="">
                                        <button type="button" id="input__<?=$x?>__items_amount_calc_but" onclick="<?=$js_set?>">SET</button>    
                                    </div>
                                </td>
                                <td align="right">
                                    <span id="input__<?=$x?>__items_percent"><?=isset($arr_data['list_sb_details'][$x]['item_amount']) ? parsenumber($arr_data['list_sb_details'][$x]['item_amount']/$arr_data['list_sb_header']['item_amount']*100,2) : ''?></span>
                                    <input type="hidden" id="input__<?=$x?>__items_percent_hid" name="sb_list[<?=$x?>][items_percent]" value="<?=isset($arr_data['list_sb_details'][$x]['item_amount']) ? $arr_data['list_sb_details'][$x]['item_amount']/$arr_data['list_sb_header']['item_amount']*100 : ''?>">
                                </td>
                                <td align="right">
                                    <span id="input__<?=$x?>__items_tax"><?=isset($arr_data['list_sb_details'][$x]['tax_amount']) ? parsenumber($arr_data['list_sb_details'][$x]['tax_amount']) : ''?></span>
                                    <input type="hidden" id="input__<?=$x?>__items_tax_hid" name="sb_list[<?=$x?>][tax]" value="<?=isset($arr_data['list_sb_details'][$x]['tax_amount']) ? $arr_data['list_sb_details'][$x]['tax_amount'] : ''?>">
                                </td>
                                <td align="right">
                                    <span id="input__<?=$x?>__items_discount"><?=isset($arr_data['list_sb_details'][$x]['discount_amount']) ? parsenumber($arr_data['list_sb_details'][$x]['discount_amount']) : ''?></span>
                                    <input type="hidden" id="input__<?=$x?>__items_discount_hid" name="sb_list[<?=$x?>][discount]" value="<?=isset($arr_data['list_sb_details'][$x]['discount_amount']) ? $arr_data['list_sb_details'][$x]['discount_amount'] : ''?>">
                                </td>
                                <td align="right">
                                    <span id="input__<?=$x?>__items_delivery"><?=isset($arr_data['list_sb_details'][$x]['delivery_amount']) ? parsenumber($arr_data['list_sb_details'][$x]['delivery_amount']) : ''?></span>
                                    <input type="hidden" id="input__<?=$x?>__items_delivery_hid" name="sb_list[<?=$x?>][delivery]" value="<?=isset($arr_data['list_sb_details'][$x]['delivery_amount']) ? $arr_data['list_sb_details'][$x]['delivery_amount'] : ''?>">
                                </td>
                                <td align="right">
                                    <span id="input__<?=$x?>__items_other"><?=isset($arr_data['list_sb_details'][$x]['other_amount']) ? parsenumber($arr_data['list_sb_details'][$x]['other_amount']) : ''?></span>
                                    <input type="hidden" id="input__<?=$x?>__items_other_hid" name="sb_list[<?=$x?>][other]" value="<?=isset($arr_data['list_sb_details'][$x]['other_amount']) ? $arr_data['list_sb_details'][$x]['other_amount'] : ''?>">
                                </td>
                                <td align="right">
                                    <input onkeypress="reCalc()" onblur="reCalc()" type="text" id="input__<?=$x?>__items_adjustment" name="sb_list[<?=$x?>][adjustment]" value="<?=isset($arr_data['list_sb_details'][$x]['adjustment_amount']) ? $arr_data['list_sb_details'][$x]['adjustment_amount'] : ''?>">
                                </td>
                                <td align="right">
                                    <span id="input__<?=$x?>__items_total"><?=isset($arr_data['list_sb_details'][$x]['item_amount']) ? parsenumber($arr_data['list_sb_details'][$x]['item_amount']+$arr_data['list_sb_details'][$x]['tax_amount']-$arr_data['list_sb_details'][$x]['discount_amount']+$arr_data['list_sb_details'][$x]['delivery_amount']+$arr_data['list_sb_details'][$x]['other_amount']+$arr_data['list_sb_details'][$x]['adjustment_amount']) : ''?></span>
                                    <input type="hidden" id="input__<?=$x?>__items_total_hid" name="sb_list[<?=$x?>][total]" value="<?=isset($arr_data['list_sb_details'][$x]['item_amount']) ? $arr_data['list_sb_details'][$x]['item_amount']+$arr_data['list_sb_details'][$x]['tax_amount']-$arr_data['list_sb_details'][$x]['discount_amount']+$arr_data['list_sb_details'][$x]['delivery_amount']+$arr_data['list_sb_details'][$x]['other_amount']+$arr_data['list_sb_details'][$x]['adjustment_amount'] : ''?>">
                                </td>
                                <td>
                                    <textarea id="input__<?=$x?>__items_remarks" name="sb_list[<?=$x?>][remarks]"><?=isset($arr_data['list_sb_details'][$x]['remarks']) ? $arr_data['list_sb_details'][$x]['remarks'] : ''?></textarea>
                                </td>
                            </tr>
                        <?php
                        }
                        ?>
                    </table>
                        
                </div>
                <div class="col-12 col-lg-12">
                    <hr>
                    <input type="hidden" name="book_id" value="<?=$g_book_id?>">
                    <input type="hidden" name="sb_id" value="<?=$g_sb_id?>">
                    <input type="submit" name="submit" class="bg-primary color-white" value="Submit">
                </div>
            </div>    
        </form>
        <iframe style="width:100%;" class="border-1" name="iframe_post" src=""></iframe>
        <script>
            
            function panel_menu(count, val)
            {
                let mode = document.getElementById('input__'+count+'__items_amount_calc_mode').value;
                let final_val = val;
                 
                if(mode == '=')
                {
                    document.getElementById('input__'+count+'__items_amount_calc').value = Number(final_val);    
                }
                else if(mode == '+')
                {
                    document.getElementById('input__'+count+'__items_amount_calc').value = Number(document.getElementById('input__'+count+'__items_amount_calc').value) + Number(final_val);    
                }
                else if(mode == '-')
                {
                    document.getElementById('input__'+count+'__items_amount_calc').value = Number(document.getElementById('input__'+count+'__items_amount_calc').value) - Number(final_val);    
                }
                    
            }
            
            function panel_menu_qty(count, val)
            {
                let mode = document.getElementById('input__'+count+'__items_amount_calc_mode').value;
                let qty_sub = document.getElementById('inputsub__'+count+'__qty').value;
                
                let final_val = qty_sub*val;
                 
                if(mode == '=')
                {
                    document.getElementById('input__'+count+'__items_amount_calc').value = Number(final_val);    
                }
                else if(mode == '+')
                {
                    document.getElementById('input__'+count+'__items_amount_calc').value = Number(document.getElementById('input__'+count+'__items_amount_calc').value) + Number(final_val);    
                }
                else if(mode == '-')
                {
                    document.getElementById('input__'+count+'__items_amount_calc').value = Number(document.getElementById('input__'+count+'__items_amount_calc').value) - Number(final_val);    
                }
            }

            function reCalc()
            {
                let dl = document.getElementsByClassName('data__loop');

                let tot_item = 0;
                let tot_person = 0;
                for(let x = 0; x < dl.length; x++)
                {
                    var user_item = Number(dl[x].value);
                    
                    if(user_item > 0)
                    {
                        tot_item += user_item;
                        tot_person ++;
                    }
                }

                document.getElementById('info__tot__person').innerHTML = formatNumber(tot_person,2); 
                document.getElementById('info__tot__person_hid').value = tot_person; 
                document.getElementById('info__tot__items').innerHTML = formatNumber(tot_item,2); 
                document.getElementById('info__tot__items_hid').value = tot_item; 

                let sub_tot_user_item_percent = 0;
                let sub_tot_user_tax = 0;
                let sub_tot_user_discount = 0;
                let sub_tot_user_delivery = 0;
                let sub_tot_user_other = 0;
                let sub_tot_user_adjustment = 0;
                let sub_tot_user_total = 0;

                //loop 2
                for(let x = 0; x < dl.length; x++)
                {
                    var user_item = Number(dl[x].value);
                    var user_id = dl[x].id;
                    var exp_user_id = user_id.split('__');
                    var no = exp_user_id[1];
                    var inv_tax = Number(document.getElementById('inv__tax_hid').value);
                    var inv_discount = Number(document.getElementById('inv__discount_hid').value);
                    var inv_delivery = Number(document.getElementById('inv__delivery_hid').value);
                    var inv_other = Number(document.getElementById('inv__other_hid').value);

                    if(user_item > 0)
                    {
                        let user_item_percent = Number((user_item/tot_item*100));
                        let user_tax = Number(((user_item_percent/100)*inv_tax)); 
                        let user_discount = Number(((user_item_percent/100)*inv_discount)); 
                        let user_delivery = Number((inv_delivery/tot_person)); 
                        let user_other = Number((inv_other/tot_person));
                        let user_adjustment = Number((Number(document.getElementById('input__'+no+'__items_adjustment').value)));
                        let user_total = Number((user_item+user_tax-user_discount+user_delivery+user_other+user_adjustment)); 

                        sub_tot_user_item_percent += user_item_percent; 
                        sub_tot_user_tax += user_tax; 
                        sub_tot_user_discount += user_discount; 
                        sub_tot_user_delivery += user_delivery; 
                        sub_tot_user_other += user_other; 
                        sub_tot_user_adjustment += user_adjustment;
                        sub_tot_user_total += user_total; 

                        document.getElementById('input__'+no+'__items_percent').innerHTML = formatNumber(user_item_percent,2);
                        document.getElementById('input__'+no+'__items_percent_hid').value = user_item_percent;

                        //tax
                        document.getElementById('input__'+no+'__items_tax').innerHTML = formatNumber(user_tax, 2);
                        document.getElementById('input__'+no+'__items_tax_hid').value = user_tax;

                        //discount
                        document.getElementById('input__'+no+'__items_discount').innerHTML = formatNumber(user_discount,2);
                        document.getElementById('input__'+no+'__items_discount_hid').value = user_discount;

                        //Delivery
                        document.getElementById('input__'+no+'__items_delivery').innerHTML = formatNumber(user_delivery,2);
                        document.getElementById('input__'+no+'__items_delivery_hid').value = user_delivery;

                        //Other
                        document.getElementById('input__'+no+'__items_other').innerHTML = formatNumber(user_other,2);
                        document.getElementById('input__'+no+'__items_other_hid').value = user_other;

                        //Total
                        document.getElementById('input__'+no+'__items_total').innerHTML = formatNumber(user_total,2);
                        document.getElementById('input__'+no+'__items_total_hid').value = user_total;
                    }
                }

                document.getElementById('info__tot__items_percent').innerHTML = formatNumber(sub_tot_user_item_percent,2); 
                document.getElementById('info__tot__items_percent_hid').value = sub_tot_user_item_percent;
                document.getElementById('info__tot__tax').innerHTML = formatNumber(sub_tot_user_tax,2); 
                document.getElementById('info__tot__tax_hid').value = sub_tot_user_tax;
                document.getElementById('info__tot__discount').innerHTML = formatNumber(sub_tot_user_discount,2); 
                document.getElementById('info__tot__discount_hid').value = sub_tot_user_discount;
                document.getElementById('info__tot__delivery').innerHTML = formatNumber(sub_tot_user_delivery,2); 
                document.getElementById('info__tot__delivery_hid').value = sub_tot_user_delivery;
                document.getElementById('info__tot__other').innerHTML = formatNumber(sub_tot_user_other,2); 
                document.getElementById('info__tot__other_hid').value = sub_tot_user_other;
                document.getElementById('info__tot__adjustment').innerHTML = formatNumber(sub_tot_user_adjustment,2); 
                document.getElementById('info__tot__adjustment_hid').value = sub_tot_user_adjustment;
                document.getElementById('info__tot__total').innerHTML = formatNumber(sub_tot_user_total,2); 
                document.getElementById('info__tot__total_hid').value = sub_tot_user_total;
            }
        </script>
    </div>
<?php    
}
elseif($page == 'payment')
{
    $g_book_id = isset($_GET['book_id']) ? $_GET['book_id'] : 0;
    
    if(! preg_match('/^[0-9]*$/', $g_book_id)) 
    {
        die('Book ID Invalid');        
    }
    
    if($g_book_id > 0)
    {
        $query = "
            select
                *
            from
                book
            where
                book_id = '".$g_book_id."'
        ";
        $result = $db->query($query);    
        $data = $result->fetchArray();
        
        if($data['user_id'] != $ses['user_id'])
        {
            die('Book not yours!');    
        }
    }
    else
    {
        die('Book Invalid');
    }
    
    $arr_data['list'] = array();
    $query = "
        select
            payment.*,
            person.person_name,
            person.initial_name
        from
            payment
        inner join
            person
            on person.person_id = payment.person_id
        where
            payment.book_id = '".$g_book_id."'
        order by
            payment.payment_date DESC,
            payment.created_at DESC
    ";
    //echo "<pre>$query</pre>";
    $result = $db->query($query) or die('ERROR|WQIEHQUIWEHUIQWHEUQWE');    
    
    while($row = $result->fetchArray())
    {
        $arr_data['list'][$row['payment_date']][$row['payment_id']]['date'] = $row['payment_date'];    
        $arr_data['list'][$row['payment_date']][$row['payment_id']]['payment_type_id'] = $row['payment_type_id'];    
        $arr_data['list'][$row['payment_date']][$row['payment_id']]['amount'] = $row['amount'];    
        $arr_data['list'][$row['payment_date']][$row['payment_id']]['remarks'] = $row['remarks'];    
        $arr_data['list'][$row['payment_date']][$row['payment_id']]['person_name'] = $row['person_name'];    
        $arr_data['list'][$row['payment_date']][$row['payment_id']]['initial_name'] = $row['initial_name'];    
    }
?>
    <div class="container">
        <h1>
            <a href="<?=APP_URL.'?page=book_details&book_id='.$g_book_id?>">
                <svg id="i-chevron-left" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32" width="32" height="32" fill="none" stroke="currentcolor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
                    <path d="M20 30 L8 16 20 2" />
                </svg>
            
                Payment
            </a>
        </h1>
        <div class="text-right">
            <a href="<?=APP_URL.'?page=payment_add_edit&payment_id_id=0&book_id='.$g_book_id?>" class="button bg-success color-white">New</a>
        </div>
        <hr>
        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Person</th>
                    <th>Type</th>
                    <th>Amount</th>
                    <th>Remarks</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    if(count($arr_data['list']) == 0)
                    {
                    ?>
                        <tr>
                            <td colspan="100">No Data</td>
                        </tr> 
                    <?php
                    }
                    else
                    {
                        foreach($arr_data['list'] as $payment_date => $val)
                        {
                            $payment_date_show = date('d-m-Y',$payment_date);
                            foreach($arr_data['list'][$payment_date] as $payment_id => $val)
                            {
                            ?>
                                <tr>
                                    <td><?=$payment_date_show?></td>
                                    <td title="<?=$val['person_name']?>"><?=$val['initial_name']?> </td>
                                    <td><?=($val['payment_type_id'] == 1 ? '<span class="color-success">Debit</span>' : '<span class="color-danger">Kredit</span>')?></td>
                                    <td class="text-right"><?=($val['payment_type_id'] == 1 ? '<span class="color-success">+'.parsenumber($val['amount']).'<span>' : '<span class="color-danger">-'.parsenumber($val['amount']).'<span>')?></td>
                                    <td><?=$val['remarks']?> </td>
                                    <td>
                                        <a class="button bg-warning" href="<?=APP_URL.'?page=payment_add_edit&book_id='.$g_book_id.'&payment_id='.$payment_id?>">Edit</a>
                                        <a class="button bg-danger" href="<?=APP_URL.'?page=payment_delete&book_id='.$g_book_id.'&payment_id='.$payment_id?>">Delete</a>
                                    </td>
                                </tr>
                            <?php
                                $payment_date_show = '';    
                            }    
                        }
                            
                        
                    }
                ?>
            </tbody>  
        </table>
    </div>
<?php    
}
elseif($page == 'payment_delete')
{
    $g_payment_id = isset($_GET['payment_id']) ? $_GET['payment_id'] : 0;
    $g_book_id = isset($_GET['book_id']) ? $_GET['book_id'] : 0;
    
    if(! preg_match('/^[0-9]*$/', $g_payment_id)) 
    {
        die('Payment ID Invalid');        
    }
             
    if(! preg_match('/^[0-9]*$/', $g_book_id)) 
    {
        die('Book ID Invalid');        
    }
    
    if($g_book_id > 0)
    {
        $query = "
            select
                *
            from
                book
            where
                book_id = '".$g_book_id."'
        ";
        $result = $db->query($query);    
        $data = $result->fetchArray();
        
        if($data['user_id'] != $ses['user_id'])
        {
            die('Book not yours!');    
        }
    }
    
    if($g_payment_id > 0)
    {
        $query = "
            select
                *
            from
                payment
            where
                payment_id = '".$g_payment_id."'
        ";
        $result = $db->query($query);    
        $data = $result->fetchArray();
    }
    
    if(! $data)
    {
        die('Data not found');
    }
?>
<div class="container">
    <h1>
        <a href="<?=APP_URL.'?page=payment&book_id='.$g_book_id?>">
            <svg id="i-chevron-left" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32" width="32" height="32" fill="none" stroke="currentcolor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
                <path d="M20 30 L8 16 20 2" />
            </svg>
        Payment
        </a>
        <svg id="i-chevron-right" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32" width="32" height="32" fill="none" stroke="currentcolor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
            <path d="M12 30 L24 16 12 2" />
        </svg>
        Delete
    </h1>
    <hr>
    <div class="row">
        <div class="col-12 col-lg-4">
            <form method="post" action="<?=APP_URL?>?page=payment_delete" accept-charset="utf-8">
                <label>Are you sure delete this data?</label>
                <div class="bg-info-lighten p-2">
                    <p>Payment ID: <?=$data['payment_id']?></p>
                    <p>Payment Date: <?=date('d-m-Y',$data['payment_date'])?></p>
                    <p>Type ID: <?=$data['payment_type_id']?></p>
                    <p>Amount: <?=$data['amount']?></p>
                    <p>Person: <?=$data['person_id']?></p>
                    <p>Remarks: <?=$data['remarks']?></p>
                </div>
                <label><input type="checkbox" name="delete" value="1"> Yes</label>
                
                <hr>
                <input type="hidden" name="payment_id" value="<?=$g_payment_id?>">
                <input type="hidden" name="book_id" value="<?=$g_book_id?>">
                <input type="submit" name="submit" class="bg-primary color-white" value="Submit">
            </form>   
        </div>
        
    </div>
</div>
<?php
}
elseif($page == 'payment_add_edit')
{
    $g_payment_id = isset($_GET['payment_id']) ? $_GET['payment_id'] : 0;
    $g_book_id = isset($_GET['book_id']) ? $_GET['book_id'] : 0;
    
    if(! preg_match('/^[0-9]*$/', $g_payment_id)) 
    {
        die('Payment ID Invalid');        
    }
             
    if(! preg_match('/^[0-9]*$/', $g_book_id)) 
    {
        die('Book ID Invalid');        
    }
    
    if($g_book_id > 0)
    {
        $query = "
            select
                *
            from
                book
            where
                book_id = '".$g_book_id."'
        ";
        $result = $db->query($query);    
        $data = $result->fetchArray();
        
        if($data['user_id'] != $ses['user_id'])
        {
            die('Book not yours!');    
        }
    }
    
    if($g_payment_id > 0)
    {
        $query = "
            select
                *
            from
                payment
            where
                payment_id = '".$g_payment_id."'
        ";
        $result = $db->query($query);    
        $data = $result->fetchArray();
    }
    
    //load person
    $query = "
        select
            *
        from
            person
        order by
            person_name ASC
    ";
    $result = $db->query($query);
    $arr_data['list_person'] = array();
    while($row = $result->fetchArray())
    {
        $arr_data['list_person'][$row['person_id']]['name'] = $row['initial_name'].' - '.$row['person_name'];    
    }
?>
    <div class="container">
        <h1>
            <a href="<?=APP_URL.'?page=payment&book_id='.$g_book_id?>">
                <svg id="i-chevron-left" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32" width="32" height="32" fill="none" stroke="currentcolor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
                    <path d="M20 30 L8 16 20 2" />
                </svg>
            Payment
            </a>
            <svg id="i-chevron-right" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32" width="32" height="32" fill="none" stroke="currentcolor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
                <path d="M12 30 L24 16 12 2" />
            </svg>
            Add / Edit
        </h1>
        <hr>
        <div class="row">
            <div class="col-12 col-lg-4">
                <form method="post" action="<?=APP_URL?>?page=payment_add_edit" accept-charset="utf-8">
                    <label>Payment Date</label>
                    <input type="date" name="payment_date" value="<?=isset($data['payment_date']) ? date('Y-m-d',$data['payment_date']) : date('Y-m-d')?>">
                    <label>Payment Type</label>
                    <select name="payment_type_id">
                        <option value="1"<?=isset($data['payment_type_id']) && $data['payment_type_id'] == 1 ? ' selected' : ''?>>Debit</option>
                        <option value="2"<?=isset($data['payment_type_id']) && $data['payment_type_id'] == 2 ? ' selected' : ''?>>Kredit</option>
                    </select>
                    <label>Person</label>
                    <select name="person_id">
                        <option value="0">-</option>
                        <?php
                            foreach($arr_data['list_person'] as $person_id => $value)
                            {
                            ?>
                                <option value="<?=$person_id?>"<?=isset($data['person_id']) && $data['person_id'] == $person_id ? ' selected' : ''?>><?=$value['name']?></option>
                        
                            <?php    
                            }    
                        ?>
                    </select>     
                    <label>Amount</label>
                    <input type="text" name="amount" value="<?=isset($data['amount']) ? $data['amount'] : ''?>">
                    <label>Remarks</label>
                    <input type="text" name="remarks" value="<?=isset($data['remarks']) ? $data['remarks'] : ''?>">
                    
                    <hr>
                    <input type="hidden" name="payment_id" value="<?=$g_payment_id?>">
                    <input type="hidden" name="book_id" value="<?=$g_book_id?>">
                    <input type="submit" name="submit" class="bg-primary color-white" value="Submit">
                </form>   
            </div>
            
        </div>
    </div>
<?php    
}
elseif($page == 'summary')
{
    $g_book_id = isset($_GET['book_id']) ? $_GET['book_id'] : 0;
    
    if(! preg_match('/^[0-9]*$/', $g_book_id) && $ses['role_id'] != 2) 
    {
        die('Book ID Invalid');        
    }
    
    if($g_book_id > 0)
    {
        $query = "
            select
                *
            from
                book
            where
                book_id = '".$g_book_id."'
        ";
        $result = $db->query($query);    
        $data = $result->fetchArray();
        
        if($data['user_id'] != $ses['user_id'])
        {
            die('Book not yours!');    
        }
    }
    
    //load person
    $query = "
        select
            *
        from
            person
        order by
            person_name ASC
    ";
    $result = $db->query($query);
    $arr_data['list_person'] = array();
    while($row = $result->fetchArray())
    {
        $arr_data['list_person'][$row['person_id']]['name'] = $row['initial_name'].' - '.$row['person_name'];
        $arr_data['list_person'][$row['person_id']]['initial_name'] = $row['initial_name'];
            
    }
    
    //load person
    $query = "
        select
            book.*,
            user.person_id
        from
            book
        inner join
            user
            on user.user_id = book.user_id
        order by
            book.book_title ASC
    ";
    $result = $db->query($query);
    $arr_data['list_book'] = array();
    while($row = $result->fetchArray())
    {
        $arr_data['list_book'][$row['book_id']]['user_id'] = $row['user_id'];
        $arr_data['list_book'][$row['book_id']]['person_id'] = $row['person_id'];
        $arr_data['list_book'][$row['book_id']]['name'] = $row['book_title'];
            
    }
    
    ?>
    <div class="container">
    <h1>
        <a href="<?=($g_book_id == 0 ? APP_URL.'?page=home&book_id=0' : APP_URL.'?page=book_details&book_id='.$g_book_id)?>">
            <svg id="i-chevron-left" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32" width="32" height="32" fill="none" stroke="currentcolor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
                <path d="M20 30 L8 16 20 2" />
            </svg>
        Summary
        </a>
    </h1>
    <form method="get" action="<?=APP_URL?>" accept-charset="utf-8">
        <?php
        if(empty($_GET['v_type_report']))
        {
            $_GET['v_type_report'] = 'summary_per_person';    
            $_GET['v_date_from'] = date('Y-m-d');    
            $_GET['v_date_to'] = date('Y-m-d');    
            $_GET['v_person_id'] = array('all');    
            $_GET['v_sort_by'] = 'remaining';    
            $_GET['v_sort_by_type'] = 'desc';  
            $_GET['v_opt_initial_only'] = 1;
            if($g_book_id == 0)
            {
                if($ses['role_id'] == 2)
                {
                    $_GET['v_book_id'] = array('all');
                }    
            }    
            else
            {
                $_GET['v_book_id'] = array($g_book_id);
            }    
        }
        ?>
        <table>
            <tr>
                <th colspan="100">Summary</th>
            </tr>
            <tr>
                <td>Type Report</td>
                <td>
                    <select name="v_type_report" id="v_type_report">
                        <option value="summary_per_person"<?=isset($_GET['v_type_report']) && $_GET['v_type_report'] == 'summary_per_person' ? ' selected' : ''?>>Summary Per Person</option>
                        <option value="details_per_date"<?=isset($_GET['v_type_report']) && $_GET['v_type_report'] == 'details_per_date' ? ' selected' : ''?>>Details Per Date</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Date From</td>
                <td>
                    <input type="date" name="v_date_from" value="<?=isset($_GET['v_date_from']) ? $_GET['v_date_from'] : date('Y-m-d')?>">
                </td>
            </tr>
            <tr>
                <td>Date To</td>
                <td>
                    <input type="date" name="v_date_to" value="<?=isset($_GET['v_date_to']) ? $_GET['v_date_to'] : date('Y-m-d')?>">
                </td>
            </tr>
            <tr>
                <td>Person</td>
                <td>
                    <select name="v_person_id[]" style="height: 160px;" multiple="">
                        <option value="all"<?=(isset($_GET['v_person_id']) && in_array('all',$_GET['v_person_id']) ? ' selected' : '')?>>All</option>
                        <?php
                        foreach($arr_data['list_person'] as $person_id => $value)
                        {
                        ?>
                            <option value="<?=$person_id?>"<?=(isset($_GET['v_person_id']) && in_array($person_id,$_GET['v_person_id']) ? ' selected' : '')?>><?=$value['name'].' :: '.$person_id?></option>
                        <?php
                        }
                        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Book</td>
                <td>
                    <select name="v_book_id[]" style="height: 160px;" multiple="">
                        <option value="all"<?=(isset($_GET['v_book_id']) && in_array('all',$_GET['v_book_id']) ? ' selected' : '')?>>All</option>
                        <?php
                        foreach($arr_data['list_book'] as $book_id => $value)
                        {
                        ?>
                            <option value="<?=$book_id?>"<?=(isset($_GET['v_book_id']) && in_array($book_id,$_GET['v_book_id']) ? ' selected' : '')?>><?=$value['name'].' :: '.$book_id?></option>
                        <?php
                        }
                        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Sort By</td>
                <td>
                    <select name="v_sort_by">
                        <option value="person_name"<?=isset($_GET['v_sort_by']) && $_GET['v_sort_by'] == 'person_name' ? ' selected' : ''?>>Person Name</option>
                        <option value="remaining"<?=isset($_GET['v_sort_by']) && $_GET['v_sort_by'] == 'remaining' ? ' selected' : ''?>>Remaining</option>
                    </select>
                    <select name="v_sort_by_type">
                        <option value="asc"<?=isset($_GET['v_sort_by_type']) && $_GET['v_sort_by_type'] == 'asc' ? ' selected' : ''?>>ASC</option>
                        <option value="desc"<?=isset($_GET['v_sort_by_type']) && $_GET['v_sort_by_type'] == 'desc' ? ' selected' : ''?>>DESC</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Options</td>
                <td>
                    <label class="d-block">
                        <input type="checkbox" value="1" name="v_opt_initial_only"<?=isset($_GET['v_opt_initial_only']) && $_GET['v_opt_initial_only'] ? ' checked' : ''?>> Show Initial Only
                    </label>
                </td>
            </tr>
            <tr>
                <th colspan="100">
                    <input type="hidden" name="book_id" value="<?=$g_book_id?>">
                    <input type="hidden" name="page" value="<?=$page?>">
                    <input type="submit" name="v_show" value="Show">
                </th>
            </tr>
        </table>
        <?php
            if(isset($_GET['v_show']))
            {
                $v_type_report = trim($_GET['v_type_report']);
                $v_date_from = parsedate($_GET['v_date_from']);
                $v_date_to = parsedate($_GET['v_date_to']);
                $v_person_id = isset($_GET['v_person_id']) ? $_GET['v_person_id'] : array();
                $v_book_id = isset($_GET['v_book_id']) ? $_GET['v_book_id'] : array();
                $book_id = (int) $_GET['book_id'];
                $page = trim($_GET['page']);
                $v_sort_by = trim($_GET['v_sort_by']);
                $v_sort_by_type = trim($_GET['v_sort_by_type']);          
                $v_opt_initial_only = (int) $_GET['v_opt_initial_only'];
                
                $where_book_id = '';
                if(in_array('all',$v_book_id))
                {
                    if($ses['role_id'] != 2)
                    {
                        die('No Auth');    
                    }
                    
                }
                else
                {
                    if(count($v_book_id) == 0)
                    {
                        die('Book Empty');    
                    }
                    else
                    {
                        foreach($v_book_id as $index => $f_book_id)
                        {
                            if($arr_data['list_book'][$f_book_id]['user_id'] == $ses['user_id'])
                            {
                                if($where_book_id != '')
                                {
                                    $where_book_id .= ',';
                                }   
                                $where_book_id .= $f_book_id;
                            }
                        }
                    }
                        
                }
                
                if($where_book_id != '')
                {
                    $where_book_id = "AND book.book_id in (".$where_book_id.")";
                }
                                        
                $where_person_id = '';

                if(!in_array('all',$v_person_id))
                {
                    foreach($v_person_id as $person_id)
                    {
                        if($where_person_id != '')
                        {
                            $where_person_id .= ',';
                        }

                        $where_person_id .= "'".$person_id."'";
                    }    
                }

                if($where_person_id != '')
                {
                    $where_person_id = "AND person.person_id in (".$where_person_id.")";
                }
                    
                
                if($v_type_report == 'summary_per_person')
                {
                    $arr_data['data_loop'] = array();
                    
                    $arr_data['split_bill_details'] = array();
                    $arr_data['payment'] = array();
                    foreach($arr_data['list_person'] as $person_id => $value)
                    {   
                        if(!in_array('all',$v_person_id) && !in_array($person_id,$v_person_id))
                        {
                            continue;
                        }
                        $arr_data['split_bill_details'][$person_id]['item_amount'] = 0;    
                        $arr_data['split_bill_details'][$person_id]['tax_amount'] = 0;    
                        $arr_data['split_bill_details'][$person_id]['discount_amount'] = 0;    
                        $arr_data['split_bill_details'][$person_id]['delivery_amount'] = 0;    
                        $arr_data['split_bill_details'][$person_id]['other_amount'] = 0;    
                        $arr_data['split_bill_details'][$person_id]['adjustment_amount'] = 0;
                        $arr_data['payment'][$person_id][1]['amount'] = 0;    
                        $arr_data['payment'][$person_id][2]['amount'] = 0;
                        $arr_data['data_loop'][$person_id]['data'] = 0;
                        $arr_data['data_loop'][$person_id]['total'] = 0;
                        $arr_data['data_loop'][$person_id]['item_amount'] = 0;
                        $arr_data['data_loop'][$person_id]['tax_amount'] = 0;
                        $arr_data['data_loop'][$person_id]['discount_amount'] = 0;
                        $arr_data['data_loop'][$person_id]['delivery_amount'] = 0;
                        $arr_data['data_loop'][$person_id]['other_amount'] = 0;
                        $arr_data['data_loop'][$person_id]['adjustment_amount'] = 0; 
                        
                        if($v_opt_initial_only)
                        {
                            $arr_data['data_loop'][$person_id]['person_name'] = $value['initial_name'];   
                        }   
                        else
                        {
                            $arr_data['data_loop'][$person_id]['person_name'] = $value['name'];
                        }
                        
                        $arr_data['data_loop'][$person_id]['person_name_title'] = $value['name'].' :: '.$person_id;    
                    }
                     
                    //Load Split BIll
                    $query = "
                        select
                            split_bill_details.*
                        from
                            split_bill_details
                        inner join
                            split_bill
                            on split_bill.sb_id = split_bill_details.sb_id
                        inner join
                            invoice
                            on invoice.invoice_id = split_bill.invoice_id
                        inner join
                            book
                            on book.book_id = invoice.book_id
                            ".$where_book_id."
                        inner join
                            person
                            on person.person_id = split_bill_details.person_id
                            ".$where_person_id." 
                    ";
                    $result = $db->query($query) or die('error');
                    
                    while($row = $result->fetchArray())
                    {
                        $arr_data['data_loop'][$row['person_id']]['data'] = 1;

                        $arr_data['split_bill_details'][$row['person_id']]['item_amount'] += (float) $row['item_amount'];    
                        $arr_data['split_bill_details'][$row['person_id']]['tax_amount'] += (float) $row['tax_amount'];    
                        $arr_data['split_bill_details'][$row['person_id']]['discount_amount'] += (float) $row['discount_amount'];    
                        $arr_data['split_bill_details'][$row['person_id']]['delivery_amount'] += (float) $row['delivery_amount'];    
                        $arr_data['split_bill_details'][$row['person_id']]['other_amount'] += (float) $row['other_amount'];    
                        $arr_data['split_bill_details'][$row['person_id']]['adjustment_amount'] += (float) $row['adjustment_amount'];    
                    }
                    
                    //Load Payment
                    $query = "
                        select
                            payment.*
                        from
                            payment
                        inner join
                            person
                            on person.person_id = payment.person_id
                            ".$where_person_id."
                        inner join
                            book
                            on book.book_id = payment.book_id
                            ".$where_book_id." 
                    ";
                    //echo $query;
                    $result = array();
                    $row = array();
                    $result = $db->query($query) or die('error2');
                    //print_r($result);
                    while($row = $result->fetchArray())
                    {
                        //print_r($row);
                        //echo '||<hr>';
                        $arr_data['data_loop'][$row['person_id']]['data'] = 1;
                        
                        $arr_data['payment'][$row['person_id']][$row['payment_type_id']]['amount'] += $row['amount'];    
                    }
                    
                    //print_r($arr_data['payment']);
                    
                    //Calc
                    foreach($arr_data['data_loop'] as $person_id => $value)
                    {
                        $total_amount = 0;
                        //split_bill_details
                        $total_amount += $arr_data['split_bill_details'][$person_id]['item_amount'];   
                        $total_amount += $arr_data['split_bill_details'][$person_id]['tax_amount'];   
                        $total_amount -= $arr_data['split_bill_details'][$person_id]['discount_amount'];   
                        $total_amount += $arr_data['split_bill_details'][$person_id]['delivery_amount'];   
                        $total_amount += $arr_data['split_bill_details'][$person_id]['other_amount'];   
                        $total_amount += $arr_data['split_bill_details'][$person_id]['adjustment_amount'];
                        $pure_sb = $total_amount;    
                        $total_amount -= $arr_data['payment'][$person_id][1]['amount'];   
                        $total_amount += $arr_data['payment'][$person_id][2]['amount'];
                        
                        $arr_data['data_loop'][$person_id]['item_amount'] = $arr_data['split_bill_details'][$person_id]['item_amount'];   
                        $arr_data['data_loop'][$person_id]['tax_amount'] = $arr_data['split_bill_details'][$person_id]['tax_amount'];   
                        $arr_data['data_loop'][$person_id]['discount_amount'] = $arr_data['split_bill_details'][$person_id]['discount_amount'];   
                        $arr_data['data_loop'][$person_id]['delivery_amount'] = $arr_data['split_bill_details'][$person_id]['delivery_amount'];   
                        $arr_data['data_loop'][$person_id]['other_amount'] = $arr_data['split_bill_details'][$person_id]['other_amount'];   
                        $arr_data['data_loop'][$person_id]['adjustment_amount'] = $arr_data['split_bill_details'][$person_id]['adjustment_amount'];   
                        $arr_data['data_loop'][$person_id]['total_sb'] = $pure_sb;   
                        $arr_data['data_loop'][$person_id]['total_payment'] = $arr_data['payment'][$person_id][1]['amount']-$arr_data['payment'][$person_id][2]['amount'];   
                        $arr_data['data_loop'][$person_id]['total_payment_debit'] = $arr_data['payment'][$person_id][1]['amount'];
                        $arr_data['data_loop'][$person_id]['total_payment_kredit'] = $arr_data['payment'][$person_id][2]['amount'];   
                        $arr_data['data_loop'][$person_id]['total'] = $total_amount;   
                    }
                      
                    
                    //pre
                    $arr_data['data_loop_tot']['item_amount'] = 0;   
                    $arr_data['data_loop_tot']['tax_amount'] = 0;   
                    $arr_data['data_loop_tot']['discount_amount'] = 0;   
                    $arr_data['data_loop_tot']['delivery_amount'] = 0;   
                    $arr_data['data_loop_tot']['other_amount'] = 0;   
                    $arr_data['data_loop_tot']['adjustment_amount'] = 0;   
                    $arr_data['data_loop_tot']['total_sb'] = 0;   
                    $arr_data['data_loop_tot']['total_payment'] = 0;
                    $arr_data['data_loop_tot']['total_payment_debit'] = 0;
                    $arr_data['data_loop_tot']['total_payment_kredit'] = 0;   
                    $arr_data['data_loop_tot']['total'] = 0;
                    
                    $forsor = array();
                    
                    //Remove
                    foreach($arr_data['data_loop'] as $person_id => $value)
                    {
                        if($value['total'] == 0)
                        {
                            unset($arr_data['data_loop'][$person_id]);
                        }
                        
                        $arr_data['data_loop_tot']['item_amount'] += $value['item_amount'];   
                        $arr_data['data_loop_tot']['tax_amount'] += $value['tax_amount'];   
                        $arr_data['data_loop_tot']['discount_amount'] += $value['discount_amount'];   
                        $arr_data['data_loop_tot']['delivery_amount'] += $value['delivery_amount'];   
                        $arr_data['data_loop_tot']['other_amount'] += $value['other_amount'];   
                        $arr_data['data_loop_tot']['adjustment_amount'] += $value['adjustment_amount'];   
                        $arr_data['data_loop_tot']['total_sb'] += $value['total_sb'];   
                        $arr_data['data_loop_tot']['total_payment'] += $value['total_payment'];   
                        $arr_data['data_loop_tot']['total_payment_debit'] += $value['total_payment_debit'];
                        $arr_data['data_loop_tot']['total_payment_kredit'] += $value['total_payment_kredit'];   
                        $arr_data['data_loop_tot']['total'] += $value['total'];
                        
                        if($value['total'] != 0)
                        {
                            if($v_sort_by == 'remaining')
                            {
                                $forsor[$person_id] = $value['total'];    
                            }
                            else
                            {
                                $forsor[$person_id] = $value['person_name'];    
                            }    
                        }
                            
                                 
                    }
                    
                    //sort
                    if($v_sort_by_type == 'desc')
                    {
                        arsort($forsor);     
                    }
                    else
                    {
                        asort($forsor);    
                    }
                   
                        
                    
                    //print('<pre>'.print_r($arr_data['data_loop'],true).'</pre>');
                    ?>
                    <table>
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Item</th>
                                <th>Tax</th>
                                <th>Discount</th>
                                <th>Delivery</th>
                                <th>Other</th>
                                <th>Adjustment</th>
                                <th>Total</th>
                                <th>Pay Debit</th>
                                <th>Pay Kredit</th>
                                <th>Remaining</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                            if(count($arr_data['data_loop']) == 0)
                            {
                            ?>
                                <tr>
                                    <td colspan="100">No Data</td>
                                </tr>
                            <?php
                            }
                            else
                            {
                                foreach($forsor as $person_id => $value)
                                {
                                ?>
                                    <tr>
                                        <td title="<?=$arr_data['data_loop'][$person_id]['person_name_title']?>"><?=$arr_data['data_loop'][$person_id]['person_name']?></td>
                                        <td class="text-right"><?=parsenumber($arr_data['data_loop'][$person_id]['item_amount'],2)?></td>
                                        <td class="text-right"><?=parsenumber($arr_data['data_loop'][$person_id]['tax_amount'],2)?></td>
                                        <td class="text-right"><?=parsenumber($arr_data['data_loop'][$person_id]['discount_amount'],2)?></td>
                                        <td class="text-right"><?=parsenumber($arr_data['data_loop'][$person_id]['delivery_amount'],2)?></td>
                                        <td class="text-right"><?=parsenumber($arr_data['data_loop'][$person_id]['other_amount'],2)?></td>
                                        <td class="text-right"><?=parsenumber($arr_data['data_loop'][$person_id]['adjustment_amount'],2)?></td>
                                        <td class="text-right"><?=parsenumber($arr_data['data_loop'][$person_id]['total_sb'],2)?></td>
                                        <td class="text-right"><?=parsenumber($arr_data['data_loop'][$person_id]['total_payment_debit'],2)?></td>
                                        <td class="text-right"><?=parsenumber($arr_data['data_loop'][$person_id]['total_payment_kredit'],2)?></td>
                                        <td class="text-right"><?=parsenumber($arr_data['data_loop'][$person_id]['total'],2)?></td>
                                        <td class="text-center">
                                            <a target="_blank" href="<?=APP_URL?>?page=personal_report&manager_initial=<?=$ses['initial_name']?>&initial=<?=$arr_data['list_person'][$person_id]['initial_name']?>" class="button bg-success color-white">Share</a>
                                        </td>
                                    </tr>
                                <?php    
                                }
                            }
                        ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td class="text-right">Total</td>
                                <td class="text-right"><?=parsenumber($arr_data['data_loop_tot']['item_amount'])?></td>
                                <td class="text-right"><?=parsenumber($arr_data['data_loop_tot']['tax_amount'])?></td>
                                <td class="text-right"><?=parsenumber($arr_data['data_loop_tot']['discount_amount'])?></td>
                                <td class="text-right"><?=parsenumber($arr_data['data_loop_tot']['delivery_amount'])?></td>
                                <td class="text-right"><?=parsenumber($arr_data['data_loop_tot']['other_amount'])?></td>
                                <td class="text-right"><?=parsenumber($arr_data['data_loop_tot']['adjustment_amount'])?></td>
                                <td class="text-right"><?=parsenumber($arr_data['data_loop_tot']['total_sb'])?></td>
                                <td class="text-right"><?=parsenumber($arr_data['data_loop_tot']['total_payment_debit'])?></td>
                                <td class="text-right"><?=parsenumber($arr_data['data_loop_tot']['total_payment_kredit'])?></td>
                                <td class="text-right"><?=parsenumber($arr_data['data_loop_tot']['total'])?></td>
                                <td>&nbsp;</td>
                            </tr>
                        </tfoot>
                    </table>
                    <?php            
                }
                elseif($v_type_report == 'details_per_date')
                {
                    $arr_data['data_loop'] = array();
                    
                    //Load Split BIll
                    $query = "
                        select
                            split_bill_details.*,
                            split_bill.sb_date,
                            restaurant.restaurant_name
                        from
                            split_bill_details
                        inner join
                            split_bill
                            on split_bill.sb_id = split_bill_details.sb_id
                        inner join
                            invoice
                            on invoice.invoice_id = split_bill.invoice_id
                            and invoice.book_id = '".$g_book_id."'
                        inner join
                            restaurant
                            on restaurant.restaurant_id = invoice.restaurant_id 
                        inner join
                            person
                            on person.person_id = split_bill_details.person_id
                            ".$where_person_id."
                        order by
                            split_bill.sb_date DESC 
                    ";
                    $result = $db->query($query);
                    
                    while($row = $result->fetchArray())
                    {
                        $total_amount = 0;
                        //split_bill_details
                        $total_amount += (float) $row['item_amount'];   
                        $total_amount += (float) $row['tax_amount'];   
                        $total_amount -= (float) $row['discount_amount'];   
                        $total_amount += (float) $row['delivery_amount'];   
                        $total_amount += (float) $row['other_amount'];   
                        $total_amount += (float) $row['adjustment_amount'];  
                        $arr_data['data_loop'][$row['sb_date']][$row['restaurant_name'].' - SPLIT_BILL:'.$row['sb_id']][$row['person_id']]['amount'] = $total_amount; 
                    }
                    
                    //Load Payment
                    $query = "
                        select
                            *
                        from
                            payment
                        inner join
                            person
                            on person.person_id = payment.person_id
                            ".$where_person_id." 
                        where
                            payment.book_id = '".$g_book_id."' 
                    ";
                    $result = $db->query($query);
                    
                    while($row = $result->fetchArray())
                    {
                        if($row['payment_type_id'] == 1)
                        {
                            $arr_data['data_loop'][$row['payment_date']]['Debit - PAYMENT:'.$row['payment_id']][$row['person_id']]['amount'] = $row['amount']*-1;   
                        }
                        else
                        {
                            $arr_data['data_loop'][$row['payment_date']]['Kredit - PAYMENT:'.$row['payment_id']][$row['person_id']]['amount'] = $row['amount'];   
                        }
                            
                    }
                    
                    //krsort($arr_data['data_loop']);
                    ksort($arr_data['data_loop']);
                    //foreach($arr_data['data_loop'])

                    //print('<pre>'.print_r($arr_data['data_loop'],true).'</pre>');
                  
                    //print('<pre>'.print_r($arr_data['data_loop'],true).'</pre>');
                    ?>
                    <table>
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Type</th>
                                <th>Name</th>
                                <th>Amount</th>
                                <th>End Balance</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                            if(count($arr_data['data_loop']) == 0)
                            {
                            ?>
                                <tr>
                                    <td colspan="100">No Data</td>
                                </tr>
                            <?php
                            }
                            else
                            {
                                $end_balance = 0;
                                foreach($arr_data['data_loop'] as $date => $value)
                                {
                                    $show_date = date('Y-m-d', $date);
                                    foreach($arr_data['data_loop'][$date] as $type_id => $value)
                                    {
                                        $show_type = $type_id;
                                        foreach($arr_data['data_loop'][$date][$type_id] as $person_id => $value)
                                        {
                                            $end_balance += $value['amount'];
                                        ?>
                                            <tr>
                                                <td><?=$show_date?></td>
                                                <td><?=$show_type?></td>
                                                <td><?=$v_opt_initial_only ? $arr_data['list_person'][$person_id]['initial_name'] : $arr_data['list_person'][$person_id]['name'].' :: '.$person_id?></td>
                                                <td class="text-right"><?=parsenumber($value['amount'],2)?></td>
                                                <td class="text-right"><?=parsenumber($end_balance,2)?></td>

                                            </tr>
                                        <?php 
                                            $show_date = ''; 
                                            $show_type = '';  
                                        }   
                                    }
                                }
                                    
                            }
                        ?>
                        </tbody>
                    </table>
                    <?php    
                }
                else
                {
                    echo 'Invalid Report Type';
                }
            }
        ?>
    </form>
    </div>
    <?php
        
}
elseif($page == 'personal_report')
{
    $g_initial = isset($_GET['initial']) ? trim(strtoupper($_GET['initial'])) : '';
    $g_manager_initial = isset($_GET['manager_initial']) ? trim(strtoupper($_GET['manager_initial'])) : '';
    $g_share_code = isset($_GET['share_code']) ? trim(strtolower($_GET['share_code'])) : '';
    $g_backstep = isset($_GET['backstep']) ? (int) $_GET['backstep'] : 0;
    
    if($g_share_code == '')
    {
        if($g_initial == '' || $g_manager_initial == '')
        {
            die('Data Invalid');
        }
        else
        {
            
        
            if(! isset($ses['user_id']))
            {
                die('Need Login');    
            }
            else
            {
                if($ses['user_id'] == 0)
                {
                    die('Login Invalid');    
                }
                else
                {
                    //load person target initial
                    $query = "
                        select
                            person_id
                        from
                            person
                        where
                            initial_name = '".$g_initial."'
                    ";
                    $result = $db->query($query) or die('QWUIEHUQWEHIUQWHEUHQWEQWE');
                    $row = $result->fetchArray();
                    $target_person_id = (int) $row['person_id'];
                    $result = array();
                    $row = array();
                    
                    $token = bin2hex(random_bytes(4));
                    $query = "
                        insert into
                            personal_report_share
                            (
                                token_code,
                                manager_person_id,
                                person_id,
                                token_exp_at
                            )
                        values
                            (
                                '".$token."',
                                '".$ses['person_id']."',
                                '".$target_person_id."',
                                '".(time()+1800)."'
                            )
                    ";
                    
                    if(! $db->query($query))
                    {
                        die('ERROR');
                    }
                    else
                    {
                        header('location: '.APP_URL.'?page=personal_report&share_code='.$token);
                    }
                        
                }        
            }
        } 
    }
    else
    {
        //check code
        $query = "
            select
                personal_report_share.*,
                m_person.initial_name as m_initial_name,
                person.initial_name
            from
                personal_report_share
            left join
                person as m_person
                on m_person.person_id = personal_report_share.manager_person_id
            left join
                person
                on person.person_id = personal_report_share.person_id 
            where
                personal_report_share.token_code = '".$g_share_code."'
        ";
        //echo $query;
        $result = $db->query($query) or die('QWUIEHUQWEHIUQWHEUHQWEQWE');
        $row = $result->fetchArray();
        //print_r($row);
        if(! $row)
        {
            die('Data Invalid');
        }
        else
        {
            if(time() > $row['token_exp_at'])
            {
                die('Expired');
            }               
            else
            {
                $g_manager_initial = $row['m_initial_name'];    
                $g_initial = $row['initial_name'];    
            }
                   
        }
        $result = array();
        $row = array();
        
    }
    
    if($g_initial == '')
    {
        die('Initial Name Invalid');    
    }
    else
    {
        if(! preg_match('/^[A-Za-z]*$/', $g_initial)) 
        {
            die('Initial Name Invalid (2)');        
        }    
    }
    
    if($g_manager_initial == '')
    {
        die('MGR Initial Name Invalid');    
    }
    else
    {
        if(! preg_match('/^[A-Za-z]*$/', $g_manager_initial)) 
        {
            die('MGR Initial Name Invalid (2)');        
        }    
    }
    
    //load profile
    $query = "
        select
            person_id,
            person_name
        from
            person
        where
            initial_name = '".$g_initial."'
    ";
    $result = $db->query($query) or die('QWUIEHUQWEHIUQWHEUHQWEQWE');
    $row = $result->fetchArray();
    
    $db_person_id = isset($row['person_id']) ? (int) $row['person_id'] : 0;        
    $db_person_name = isset($row['person_name']) ? trim($row['person_name']) : '';
    
    if($db_person_id == 0)
    {
        die('Data Person Invalid');
    }
    
    //load profile
    $query = "
        select
            person_id,
            person_name
        from
            person
        where
            initial_name = '".$g_manager_initial."'
    ";
    $result = $db->query($query) or die('QWUIEHUQWEHIUQWHEUHQWEQWE');
    $row = $result->fetchArray();
    
    $db_mgr_person_id = isset($row['person_id']) ? (int) $row['person_id'] : 0;        
    $db_mgr_person_name = isset($row['person_name']) ? trim($row['person_name']) : '';
    
    if($db_mgr_person_id == 0)
    {
        die('Data Person Invalid');
    }
    
    //Init
    $arr_data['data'] = array();
    $arr_data['book'] = array();
    
    //this week
    /*if(date('w') >= 5)
    {
        $today = strtotime('monday this week');
    }
    else
    {
        $today = strtotime('monday last week');
    }*/
    
    $today = strtotime('monday this week');
    $today -= ($g_backstep*86400)*7;
    
    $from_date_week = $today;
    $to_date_week = $from_date_week+(86400*6); 
    
      
    //Load Split BIll
    $query = "
        select
            book.book_id,
            book.book_title,
            split_bill.sb_date,
            sum(split_bill_details.item_amount) as item_amount,
            sum(split_bill_details.tax_amount) as tax_amount,
            sum(split_bill_details.discount_amount) as discount_amount,
            sum(split_bill_details.delivery_amount) as delivery_amount,
            sum(split_bill_details.other_amount) as other_amount,
            sum(split_bill_details.adjustment_amount) as adjustment_amount
        from
            split_bill_details
        inner join
            split_bill
            on split_bill.sb_id = split_bill_details.sb_id
        inner join
            invoice
            on invoice.invoice_id = split_bill.invoice_id
        inner join
            book
            on book.book_id = invoice.book_id
            and book.user_id = '".$db_mgr_person_id."'
        inner join
            person
            on person.person_id = split_bill_details.person_id
            and person.person_id = '".$db_person_id."'
        where
            split_bill.sb_date between '".$from_date_week."' AND '".$to_date_week."' 
        group by
            book.book_id,
            book.book_title,
            split_bill.sb_date
    ";
    $result = $db->query($query) or die('error');
    
    while($row = $result->fetchArray())
    {
        $arr_data['book'][$row['book_id']]['name'] = $row['book_title'];    
        
        $arr_data['data'][$row['sb_date']][$row['book_id']]['split_bill']['item_amount'] = (float) $row['item_amount'];    
        $arr_data['data'][$row['sb_date']][$row['book_id']]['split_bill']['tax_amount'] = (float) $row['tax_amount'];    
        $arr_data['data'][$row['sb_date']][$row['book_id']]['split_bill']['discount_amount'] = (float) $row['discount_amount'];    
        $arr_data['data'][$row['sb_date']][$row['book_id']]['split_bill']['delivery_amount'] = (float) $row['delivery_amount'];    
        $arr_data['data'][$row['sb_date']][$row['book_id']]['split_bill']['other_amount'] = (float) $row['other_amount'];    
        $arr_data['data'][$row['sb_date']][$row['book_id']]['split_bill']['adjustment_amount'] = (float) $row['adjustment_amount'];    
    }
    
    //Load Split BIll DET
    $query = "
        select
            book.book_id,
            book.book_title,
            split_bill.sb_date,
            restaurant.restaurant_id,
            restaurant.restaurant_name,
            sum(split_bill_details.item_amount) as item_amount,
            sum(split_bill_details.tax_amount) as tax_amount,
            sum(split_bill_details.discount_amount) as discount_amount,
            sum(split_bill_details.delivery_amount) as delivery_amount,
            sum(split_bill_details.other_amount) as other_amount,
            sum(split_bill_details.adjustment_amount) as adjustment_amount
        from
            split_bill_details
        inner join
            split_bill
            on split_bill.sb_id = split_bill_details.sb_id
        inner join
            invoice
            on invoice.invoice_id = split_bill.invoice_id
        inner join
            restaurant
            on restaurant.restaurant_id = invoice.restaurant_id 
        inner join
            book
            on book.book_id = invoice.book_id
            and book.user_id = '".$db_mgr_person_id."'
        inner join
            person
            on person.person_id = split_bill_details.person_id
            and person.person_id = '".$db_person_id."'
        where
            split_bill.sb_date between '".$from_date_week."' AND '".$to_date_week."' 
        group by
            book.book_id,
            book.book_title,
            split_bill.sb_date,
            restaurant.restaurant_id,
            restaurant.restaurant_name
    ";
    $result = $db->query($query) or die('error');
    
    while($row = $result->fetchArray())
    {
        $arr_data['book'][$row['book_id']]['name'] = $row['book_title'];    
        $arr_data['restaurant'][$row['restaurant_id']]['name'] = $row['restaurant_name'];    
        
        $arr_data['data_det'][$row['sb_date']][$row['book_id']][$row['restaurant_id']]['item_amount'] = (float) $row['item_amount'];    
        $arr_data['data_det'][$row['sb_date']][$row['book_id']][$row['restaurant_id']]['tax_amount'] = (float) $row['tax_amount'];    
        $arr_data['data_det'][$row['sb_date']][$row['book_id']][$row['restaurant_id']]['discount_amount'] = (float) $row['discount_amount'];    
        $arr_data['data_det'][$row['sb_date']][$row['book_id']][$row['restaurant_id']]['delivery_amount'] = (float) $row['delivery_amount'];    
        $arr_data['data_det'][$row['sb_date']][$row['book_id']][$row['restaurant_id']]['other_amount'] = (float) $row['other_amount'];    
        $arr_data['data_det'][$row['sb_date']][$row['book_id']][$row['restaurant_id']]['adjustment_amount'] = (float) $row['adjustment_amount'];    
    }
    
    //Load Payment
    $query = "
        select
            book.book_id,
            book.book_title,
            payment.payment_date,
            payment.payment_type_id,
            sum(payment.amount) as amount
        from
            payment
        inner join
            person
            on person.person_id = payment.person_id
            and person.person_id = '".$db_person_id."'
        inner join
            book
            on book.book_id = payment.book_id
            and book.user_id = '".$db_mgr_person_id."'
        where
            payment.payment_date between '".$from_date_week."' AND '".$to_date_week."'
        group by
            book.book_id,
            book.book_title,
            payment.payment_date,
            payment.payment_type_id 
    ";
    //echo $query; 
    //$result = null;
    //$row = null;
    $result = $db->query($query) or die('error2');
    while($row = $result->fetchArray())
    {
        $arr_data['book'][$row['book_id']]['name'] = $row['book_title'];
        $arr_data['data'][$row['payment_date']][$row['book_id']]['payment'][$row['payment_type_id']]['amount'] = $row['amount'];    
    }
    
    //total
    $total_total = 0;
    $total_payment = 0;
    $total_payment_count = 0;
    
    //Load Split BIll
    $query = "
        select
            sum(split_bill_details.item_amount) as item_amount,
            sum(split_bill_details.tax_amount) as tax_amount,
            sum(split_bill_details.discount_amount) as discount_amount,
            sum(split_bill_details.delivery_amount) as delivery_amount,
            sum(split_bill_details.other_amount) as other_amount,
            sum(split_bill_details.adjustment_amount) as adjustment_amount,
            count(split_bill.sb_id) as total_count
        from
            split_bill_details
        inner join
            split_bill
            on split_bill.sb_id = split_bill_details.sb_id
        inner join
            invoice
            on invoice.invoice_id = split_bill.invoice_id
        inner join
            book
            on book.book_id = invoice.book_id
            and book.user_id = '".$db_mgr_person_id."'
        inner join
            person
            on person.person_id = split_bill_details.person_id
            and person.person_id = '".$db_person_id."'
    ";
    $result = array();
    $row = array();
    $result = $db->query($query) or die('error');
    $row = $result->fetchArray();
      
    $total_total += (float) $row['item_amount'];    
    $total_total += (float) $row['tax_amount'];    
    $total_total -= (float) $row['discount_amount'];    
    $total_total += (float) $row['delivery_amount'];    
    $total_total += (float) $row['other_amount'];    
    $total_total += (float) $row['adjustment_amount'];
    
    $avg_total = 0;
    if($total_total > 0 && $row['total_count'] > 0)
    {
        $avg_total = $total_total/$row['total_count'];    
    }
        
    
    //Load Payment
    $query = "
        select
            payment.payment_type_id,
            sum(payment.amount) as amount,
            count(payment.payment_type_id) as total_count
        from
            payment
        inner join
            person
            on person.person_id = payment.person_id
            and person.person_id = '".$db_person_id."'
        inner join
            book
            on book.book_id = payment.book_id
            and book.user_id = '".$db_mgr_person_id."'
        group by
            payment.payment_type_id 
    ";
    //echo $query; 
    //$result = null;
    //$row = null;
    $result = $db->query($query) or die('error2');
    while($row = $result->fetchArray())
    {
        if($row['payment_type_id'] == 1)
        {
            $total_payment += $row['amount'];
        }
        else
        {
            $total_payment -= $row['amount'];    
        }
        
        $total_payment_count += $row['total_count'];
    }
    
    $avg_payment = 0;
    if($total_payment > 0 && $total_payment_count > 0)
    {
        $avg_payment = $total_payment/$total_payment_count;   
    }   
    
    //total book
    //Load Split BIll
    $query = "
        select
            book.book_id,
            book.book_title
        from
            split_bill_details
        inner join
            split_bill
            on split_bill.sb_id = split_bill_details.sb_id
        inner join
            invoice
            on invoice.invoice_id = split_bill.invoice_id
        inner join
            book
            on book.book_id = invoice.book_id
            and book.user_id = '".$db_mgr_person_id."'
        inner join
            person
            on person.person_id = split_bill_details.person_id
            and person.person_id = '".$db_person_id."'
        group by
            book.book_id,
            book.book_title
    ";
    $result = array();
    $row = array();
    $result = $db->query($query) or die('error');
    $arr_data['book_list'] = array();
    while($row = $result->fetchArray())
    {
        $arr_data['book_list'][$row['book_id']]['name'] = $row['book_title'];
    }
    $total_book = count($arr_data['book_list']);
    
    //print_r($arr_data['data']);
    
    //Load Split BIll  - Resto
    $query = "
        select
            restaurant.restaurant_id,
            restaurant.restaurant_name,
            SUM(split_bill_details.item_amount) as amount
        from
            split_bill_details
        inner join
            split_bill
            on split_bill.sb_id = split_bill_details.sb_id
        inner join
            invoice
            on invoice.invoice_id = split_bill.invoice_id
        inner join
            book
            on book.book_id = invoice.book_id
            and book.user_id = '".$db_mgr_person_id."'
        inner join
            person
            on person.person_id = split_bill_details.person_id
            and person.person_id = '".$db_person_id."'
        inner join
            restaurant
            on restaurant.restaurant_id = invoice.restaurant_id 
        group by
            restaurant.restaurant_id,
            restaurant.restaurant_name
        order by
            SUM(split_bill_details.item_amount) DESC
        limit
            5
    ";
    $result = array();
    $row = array();
    $result = $db->query($query) or die('WQEUIHQWIUEHUIWQEHQWE');
    $arr_data['resto_list'] = array();
    while($row = $result->fetchArray())
    {
        $arr_data['resto_list'][$row['restaurant_id']]['amount'] = $row['amount'];
        $arr_data['resto_list'][$row['restaurant_id']]['name'] = $row['restaurant_name'];
    }
    
    ?>
    <div class="container">
        <div class="row">
            <div class="col-12 col-lg-3">
                <div class="bg-light br-2 p-2 mt-3">
                    PIC: <?=$db_mgr_person_name?>
                </div>
                <div class="bg-light br-3 p-3 mt-3">
                    <div class="row">
                        <div class="col-4">
                            <img src="<?=APP_URL?>/assets/kirbo_184x184.jpg" class="br-round border-2 bc-muted img-fluid">
                        </div>
                        <div class="col-8">
                            Puyo, <?=$db_person_name.' ('.$g_initial.')'?>
                        </div> 
                    </div>
                </div>
                <div class="row">
                    <div class="col-5">
                        <div class="bg-light br-3 p-3 mt-3">
                            <b>Book</b>
                            <h4 class="text-right"><?=parsenumber($total_book,0)?></h4>
                        </div>
                    </div>
                    <div class="col-7">
                        <div class="bg-light br-3 p-3 mt-3">
                            <b>AVG Pay</b>
                            <h4 class="text-right"><?=parsenumber($avg_payment,2)?></h4>
                        </div>
                    </div>
                    <div class="col-12 col-sm-12">
                        <div class="bg-light br-3 p-3 mt-3">
                            <b>AVG Buy</b>
                            <h4 class="text-right"><?=parsenumber($avg_total,2)?></h4>
                        </div>
                    </div>
                    <div class="col-12 col-sm-12">
                        <div class="bg-light br-3 p-3 mt-3">
                            <b>Top 5 Favorite Restaurant</b>
                            <hr>
                            <?php
                                $ke = 0;
                                foreach($arr_data['resto_list'] as $restaurant_id => $value)
                                {
                                    $ke++;
                                ?>
                                    <h6 class="text-right bg-light-lighten br-2 p-2">
                                        <small class="color-muted float-left"><?=$ke?></small>
                                        <?=$value['name']?>
                                    </h6>    
                                <?php
                                }
                            ?>
                        </div>
                    </div>
                     
                </div>
                         
            </div>
            <div class="col-12 col-lg-9">
                <div class="row">
                    <div class="col-6 col-sm-3">
                        <div class="bg-light br-3 p-3 mt-3">
                            <b>Total</b>
                            <h4 class="text-right">IDR<br><?=parsenumber($total_total,2)?></h4>
                        </div>
                    </div>
                    <div class="col-6 col-sm-3">
                        <div class="br-3 p-3 mt-3" style="background-color: #9fe092;">
                            <b>Payment</b>
                            <h4 class="text-right">IDR<br><?=parsenumber($total_payment,2)?></h4>
                        </div>
                    </div>
                    <div class="col-6 col-sm-3">
                        <div class="br-3 p-3 mt-3" style="background-color: #e0db92;">
                            <b>Remaining</b>
                            <h4 class="text-right">IDR<br><?=parsenumber($total_total-$total_payment,2)?></h4>
                        </div>
                    </div>
                    <div class="col-6 col-sm-3">
                        <div class="br-3 p-3 mt-3" style="background-color: #faf6bf;">
                            <b>Percentage</b>      
                            <h4 class="text-right"><br><?=($total_payment > 0 && $total_total > 0 ? parsenumber($total_payment/$total_total*100,1) : 0)?> %</h4>
                        </div>
                    </div>   
                </div>
                <div class="col-12">
                    <div class="bg-light br-3 p-3 mt-3">
                        <b>History</b><br>
                        <small class="color-muted">
                            <?=date('D, d-m-Y',$from_date_week)?> - <?=date('D, d-m-Y',$to_date_week)?>
                        </small>
                        <div class="overflow-auto">
                            <table>
                                <tr>
                                    <th>Date</th>
                                    <th>Book</th>
                                    <th>Item</th>
                                    <th>Debit</th>
                                    <th>Kredit</th>
                                    <th>Total</th>
                                </tr>
                                <?php
                                    $tot_item = 0;
                                    $tot_others = 0;
                                    $tot_total = 0;
                                    $tot_debit = 0;
                                    $tot_kredit = 0;
                                    $tot_total_total = 0;
                                    if(count($arr_data['data']) == 0)
                                    {
                                    ?>
                                        <tr>
                                            <td colspan="100">No Data</td>
                                        </tr>
                                    <?php
                                    }
                                    else
                                    {
                                        foreach($arr_data['data'] as $date => $value)
                                        {
                                            $show_date = date('D, d-m-Y',$date);
                                            foreach($arr_data['data'][$date] as $book_id => $value)
                                            {
                                                $total_sb = 0;
                                                $total_others = 0;
                                                $total_sb += isset($arr_data['data'][$date][$book_id]['split_bill']['item_amount']) ? $arr_data['data'][$date][$book_id]['split_bill']['item_amount'] : 0;
                                                $total_sb += isset($arr_data['data'][$date][$book_id]['split_bill']['tax_amount']) ? $arr_data['data'][$date][$book_id]['split_bill']['tax_amount'] : 0;
                                                $total_others += isset($arr_data['data'][$date][$book_id]['split_bill']['tax_amount']) ? $arr_data['data'][$date][$book_id]['split_bill']['tax_amount'] : 0;
                                                $total_sb -= isset($arr_data['data'][$date][$book_id]['split_bill']['discount_amount']) ? $arr_data['data'][$date][$book_id]['split_bill']['discount_amount'] : 0;
                                                $total_others -= isset($arr_data['data'][$date][$book_id]['split_bill']['discount_amount']) ? $arr_data['data'][$date][$book_id]['split_bill']['discount_amount'] : 0;
                                                $total_sb += isset($arr_data['data'][$date][$book_id]['split_bill']['delivery_amount']) ? $arr_data['data'][$date][$book_id]['split_bill']['delivery_amount'] : 0;
                                                $total_others += isset($arr_data['data'][$date][$book_id]['split_bill']['delivery_amount']) ? $arr_data['data'][$date][$book_id]['split_bill']['delivery_amount'] : 0;
                                                $total_sb += isset($arr_data['data'][$date][$book_id]['split_bill']['other_amount']) ? $arr_data['data'][$date][$book_id]['split_bill']['other_amount'] : 0;
                                                $total_others += isset($arr_data['data'][$date][$book_id]['split_bill']['other_amount']) ? $arr_data['data'][$date][$book_id]['split_bill']['other_amount'] : 0;
                                                $total_sb += isset($arr_data['data'][$date][$book_id]['split_bill']['adjustment_amount']) ? $arr_data['data'][$date][$book_id]['split_bill']['adjustment_amount'] : 0;
                                                $total_others += isset($arr_data['data'][$date][$book_id]['split_bill']['adjustment_amount']) ? $arr_data['data'][$date][$book_id]['split_bill']['adjustment_amount'] : 0;
                                                
                                                $total_remaining = 0;
                                                $total_remaining += $total_sb;
                                                $total_remaining -= isset($arr_data['data'][$date][$book_id]['payment'][1]['amount']) ? $arr_data['data'][$date][$book_id]['payment'][1]['amount'] : 0;
                                                $total_remaining += isset($arr_data['data'][$date][$book_id]['payment'][2]['amount']) ? $arr_data['data'][$date][$book_id]['payment'][2]['amount'] : 0; 
                                                
                                                $tot_item += isset($arr_data['data'][$date][$book_id]['split_bill']['item_amount']) ? $arr_data['data'][$date][$book_id]['split_bill']['item_amount'] : 0;
                                                $tot_others += $total_others;
                                                $tot_total += $total_sb;
                                                $tot_debit += isset($arr_data['data'][$date][$book_id]['payment'][1]['amount']) ? $arr_data['data'][$date][$book_id]['payment'][1]['amount'] : 0;
                                                $tot_kredit += isset($arr_data['data'][$date][$book_id]['payment'][2]['amount']) ? $arr_data['data'][$date][$book_id]['payment'][2]['amount'] : 0;
                                                $tot_total_total += $total_remaining;
                                            ?>
                                                <tr>
                                                    <td><?=$show_date?></td>
                                                    <td><?=$arr_data['book'][$book_id]['name']?></td>
                                                    <td class="text-right">
                                                        <?php
                                                            $js = '
                                                                if(document.getElementById(\'data_other_panel__'.$date.'__'.$book_id.'\').style.display == \'none\')
                                                                {
                                                                    document.getElementById(\'data_other_panel__'.$date.'__'.$book_id.'\').style.display = \'\';
                                                                }
                                                                else
                                                                {
                                                                    document.getElementById(\'data_other_panel__'.$date.'__'.$book_id.'\').style.display = \'none\';
                                                                }
                                                            ';
                                                        ?>
                                                        <u onclick="<?=$js?>" class="cursor-pointer"><?=parsenumber($total_sb,2)?></u>
                                                        <div id="data_other_panel__<?=$date?>__<?=$book_id?>" style="display: none;">
                                                            <div class="row">
                                                                <div class="col-4">
                                                                    <b>Total</b>
                                                                    <br><?=isset($arr_data['data'][$date][$book_id]['split_bill']['item_amount']) ? parsenumber($arr_data['data'][$date][$book_id]['split_bill']['item_amount'],2) : ''?>
                                                                </div>
                                                                <div class="col-4">
                                                                    <b>Tax</b>
                                                                    <br><?=isset($arr_data['data'][$date][$book_id]['split_bill']['tax_amount']) ? parsenumber($arr_data['data'][$date][$book_id]['split_bill']['tax_amount'],2) : ''?>
                                                                </div>
                                                                <div class="col-4">
                                                                    <b>Discount</b>
                                                                    <br><?=isset($arr_data['data'][$date][$book_id]['split_bill']['discount_amount']) ? parsenumber($arr_data['data'][$date][$book_id]['split_bill']['discount_amount'],2) : ''?>
                                                                </div>
                                                                <div class="col-4">
                                                                    <b>Delivery</b>
                                                                    <br><?=isset($arr_data['data'][$date][$book_id]['split_bill']['delivery_amount']) ? parsenumber($arr_data['data'][$date][$book_id]['split_bill']['delivery_amount'],2) : ''?>
                                                                </div>
                                                                <div class="col-4">
                                                                    <b>Others</b>
                                                                    <br><?=isset($arr_data['data'][$date][$book_id]['split_bill']['other_amount']) ? parsenumber($arr_data['data'][$date][$book_id]['split_bill']['other_amount'],2) : ''?>
                                                                </div>
                                                                <div class="col-4">
                                                                    <b>Adjustment</b>
                                                                    <br><?=isset($arr_data['data'][$date][$book_id]['split_bill']['adjustment_amount']) ? parsenumber($arr_data['data'][$date][$book_id]['split_bill']['adjustment_amount'],2) : ''?>
                                                                </div>
                                                                <div class="col-4">
                                                                    <a href="javascript:void(0)" class="button" data-toggle="show" aria-controls="modal_details_<?=$date?>_<?=$book_id?>">Details</a>
                                                                </div>    
                                                            </div>
                                                        </div>
                                                        <div role="alert" id="modal_details_<?=$date?>_<?=$book_id?>" class="position-fixed top-0 left-0 width-fluid height-vh hide">
                                                          <div class="position-relative width-fluid height-vh d-flex justify-content-center">
                                                            <div class="bg-dark position-absolute width-fluid height-vh top-0 left-0 opacity-3" data-toggle="hide" aria-controls="modal_details_<?=$date?>_<?=$book_id?>"></div>
                                                            <div class="text-left bg-light bc-muted color-dark br-2 p-3 position-absolute mt-3 ms-auto me-auto mb-auto">
                                                              <button type="button" class="button-close float-right" data-toggle="hide" aria-controls="modal_details_<?=$date?>_<?=$book_id?>"></button>
                                                              <h4>Details</h4>
                                                              <hr>
                                                              <div class="overflow-auto">
                                                              <table>
                                                                <tr>
                                                                    <th>Restaurant</th>
                                                                    <th>Item</th>
                                                                    <th>Tax</th>
                                                                    <th>Disc</th>
                                                                    <th>Del</th>
                                                                    <th>Other</th>
                                                                    <th>Adj</th>
                                                                    <th>Total</th>
                                                                </tr>
                                                                <?php
                                                                
                                                                foreach($arr_data['data_det'][$date][$book_id] as $restaurant_id => $val)
                                                                {
                                                                    $det_item = $val['item_amount'];
                                                                    $det_tax = $val['tax_amount'];
                                                                    $det_disc = $val['discount_amount'];
                                                                    $det_del = $val['delivery_amount'];
                                                                    $det_other = $val['other_amount'];
                                                                    $det_adj = $val['adjustment_amount'];
                                                                    $det_sb = $det_item+$det_tax-$det_disc+$det_del+$det_other+$det_adj;
                                                                    ?>
                                                                    <tr>
                                                                        <td><?=$arr_data['restaurant'][$restaurant_id]['name'].' :: '.$restaurant_id?></td>
                                                                        <td><?=parsenumber($det_item,2)?></td>
                                                                        <td><?=parsenumber($det_tax,2)?></td>
                                                                        <td><?=parsenumber($det_disc,2)?></td>
                                                                        <td><?=parsenumber($det_del,2)?></td>
                                                                        <td><?=parsenumber($det_other,2)?></td>
                                                                        <td><?=parsenumber($det_adj,2)?></td>
                                                                        <td><?=parsenumber($det_sb,2)?></td>
                                                                    </tr>
                                                                    <?php
                                                                }
                                                              ?>
                                                              </table>
                                                              </div>    
                                                                
                                                            </div>
                                                          </div>
                                                        </div>
                                                    </td>
                                                    <td class="text-right"><?=isset($arr_data['data'][$date][$book_id]['payment'][1]['amount']) ? '<span class="color-success">-'.parsenumber($arr_data['data'][$date][$book_id]['payment'][1]['amount'],2).'</span>' : ''?></td>
                                                    <td class="text-right"><?=isset($arr_data['data'][$date][$book_id]['payment'][2]['amount']) ? '<span class="color-danger">+'.parsenumber($arr_data['data'][$date][$book_id]['payment'][2]['amount'],2).'</span>' : ''?></td>
                                                    <td class="text-right"><?=parsenumber($total_remaining,2)?></td>
                                                </tr>
                                            <?php
                                                $show_date = '';    
                                            }    
                                        }
                                            
                                    }
                                ?>
                                <tr>
                                    <td class="text-right" colspan="2">Total</td>
                                    <td class="text-right"><?=parsenumber($tot_total,2)?></td>
                                    <td class="text-right"><?=parsenumber($tot_debit,2)?></td>
                                    <td class="text-right"><?=parsenumber($tot_kredit,2)?></td>
                                    <td class="text-right"><?=parsenumber($tot_total_total,2)?></td>
                                </tr>
                            </table>
                        </div>
                        <div class="row">
                            <div class="col-6 text-left">
                                <a href="<?=APP_URL.'?page=personal_report&share_code='.$g_share_code.'&backstep='.($g_backstep+1)?>">Show Prev Week</a>
                        
                            </div>
                            <div class="col-6 text-right">
                            <?php
                            if($g_backstep > 0)
                            {
                            ?>
                                <a href="<?=APP_URL.'?page=personal_report&share_code='.$g_share_code.'&backstep='.($g_backstep-1)?>">Show Next Week</a>
                            
                            <?php    
                            }
                        ?>
                            </div>
                        </div>
                        
                    </div>
                </div>
            </div>
            
            <div class="col-12">
                <small class="color-muted">*) Kirbo: https://terminalmontage.fandom.com/wiki/Kirbo</small>
            </div>
        </div>
    </div>
    <?php       
}
elseif($page == 'prediction_board')
{
    $g_book_id = isset($_GET['book_id']) ? $_GET['book_id'] : 0;
    
    if(! preg_match('/^[0-9]*$/', $g_book_id)) 
    {
        die('Book ID Invalid');        
    }
    
    if($g_book_id > 0)
    {
        $query = "
            select
                *
            from
                book
            where
                book_id = '".$g_book_id."'
        ";
        $result = $db->query($query);    
        $data = $result->fetchArray();
        
        if($data['user_id'] != $ses['user_id'])
        {
            die('Book not yours!');    
        }
    }
    else
    {
        die('Book Invalid');
    }
    
    /*
    * Just Simple Prediction Board Weekly 
    */
    
    //Load Data -> Link with Price Split Bill <---> Invoice
    $query = "
        select
            split_bill.sb_id,
            split_bill.sb_date,
            split_bill_details.item_amount,
            person.person_id,
            person.person_name,
            person.initial_name,
            res_ref.rm_id,
            res_ref.rm_name,
            res_ref.restaurant_id,
            res_ref.restaurant_name
        from
            split_bill_details
        inner join
            split_bill
            on split_bill.sb_id = split_bill_details.sb_id 
        inner join
            person
            on person.person_id = split_bill_details.person_id
        inner join
        (
            select
                invoice_details.invoice_id,
                invoice_details.rm_id,
                restaurant_menu.rm_name,
                restaurant.restaurant_id,
                restaurant.restaurant_name,
                invoice_details.price/invoice_details.qty as price
            from
                invoice_details
            inner join
                invoice
                on invoice.invoice_id = invoice_details.invoice_id
                and invoice.book_id = '".$g_book_id."' 
            inner join
                restaurant_menu
                on restaurant_menu.rm_id = invoice_details.rm_id
            inner join
                restaurant
                on restaurant.restaurant_id = restaurant_menu.restaurant_id
        ) as res_ref
        on res_ref.invoice_id = split_bill.invoice_id
        and res_ref.price = split_bill_details.item_amount
    ";
    $result = $db->query($query);
    //$data = $result->fetchArray(); 
    //print('<pre>'.print_r($data,true).'</pre>');
    $data_person_item = array();
    while($row = $result->fetchArray())
    {
        $day = date('D',$row['sb_date']);
        $arr_data['rm_list'][$row['rm_id']]['name'] = $row['rm_name'];
        $data_person_item[$day][$row['person_id']][$row['sb_id']][$row['rm_id']] = $row['rm_id'];    
        $data_item[$day][$row['rm_id']][$row['sb_id'].'_'.$row['person_id']] = $row['sb_id'];    
    }
    
    $arr_list['day']['Mon']['name'] = 'Mon';
    $arr_list['day']['Tue']['name'] = 'Tue';
    $arr_list['day']['Wed']['name'] = 'Wed';
    $arr_list['day']['Thu']['name'] = 'Thu';
    $arr_list['day']['Fri']['name'] = 'Fri';
    $arr_list['day']['Sat']['name'] = 'Sat';
    $arr_list['day']['Sun']['name'] = 'Sun';
    
    //get day most buy
    foreach($data_item as $day => $value)
    {
        foreach($data_item[$day] as $rm_id => $value)
        {
            $arr_data['finalmix'][$day][count($data_item[$day][$rm_id])][$rm_id] = 1;   
        }    
    }
    
    foreach($arr_data['finalmix'] as $day => $value) 
    {
        krsort($arr_data['finalmix'][$day]);    
    }
    
    
    
    //print('<pre>'.print_r($data_person_item,true).'</pre>');
?>
    <div class="container">
        <h1>
            <a href="<?=APP_URL.'?page=book_details&book_id='.$g_book_id?>">
                <svg id="i-chevron-left" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32" width="32" height="32" fill="none" stroke="currentcolor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
                    <path d="M20 30 L8 16 20 2" />
                </svg>
            
                Prediction Board
            </a>
        </h1>
        <hr>
        <table>
            <tr>
                <?php
                foreach($arr_list['day'] as $day => $value)
                {
                ?>
                    <td><?=$day?></td>
                <?php    
                }
                ?>
            </tr>
            <tr>
                <?php
                foreach($arr_list['day'] as $day => $value)
                {
                    ?>
                        <td>
                            <?php
                            if(isset($arr_data['finalmix'][$day]))
                            {   
                                foreach($arr_data['finalmix'][$day] as $score => $value) 
                                {
                                    foreach($arr_data['finalmix'][$day][$score] as $rm_id => $value) 
                                    {
                                        echo '<div class="card border-0 bg-light p-2 mb-2 br-2">'.$arr_data['rm_list'][$rm_id]['name'].'</div>';       
                                    }    
                                }    
                            }
                            ?>
                        </td>
                    <?php
                    
                            
                }
                ?>
            </tr>
        </table>
<?php    
}