<?php 

$account =  $_POST['account'];
$terminal =  $_POST['terminal'];
$order_number =  $_POST['order_number'];
$order_currency =  $_POST['order_currency'];
$order_amount =  $_POST['order_amount'];
$backUrl =  $_POST['backUrl'];
$noticeUrl =  $_POST['noticeUrl'];
$methods =  $_POST['methods'];
$order_notes =  $_POST['order_notes'];
$signValue =  $_POST['signValue'];
$billing_firstName =  $_POST['billing_firstName'];
$billing_lastName =  $_POST['billing_lastName'];
$billing_email =  $_POST['billing_email'];
$billing_phone =  $_POST['billing_phone'];
$billing_country =  $_POST['billing_country'];
$billing_state =  $_POST['billing_state'];
$billing_city =  $_POST['billing_city'];
$billing_address =  $_POST['billing_address'];
$billing_zip =  $_POST['billing_zip'];
$ship_firstName =  $_POST['ship_firstName'];
$ship_lastName =  $_POST['ship_lastName'];
$ship_phone =  $_POST['ship_phone'];
$ship_country =  $_POST['ship_country'];
$ship_state =  $_POST['ship_state'];
$ship_city =  $_POST['ship_city'];
$ship_addr =  $_POST['ship_addr'];
$ship_zip =  $_POST['ship_zip'];
$productName =  $_POST['productName'];
$productSku =  $_POST['productSku'];
$productNum =  $_POST['productNum'];
$cart_info =  $_POST['cart_info'];
$cart_api =  $_POST['cart_api'];
$pages =  $_POST['pages'];
$action_url =  $_POST['action_url'];
$iframe_height = $_POST['height'];

?>

<div class="centerColumn" id="checkoutCtopayDefault" >
    <div id="loading" style="position: relative;">
       <div style="position:absolute; top:100px; left:45%; z-index:3;" >
           <img src="<?php echo DIR_WS_MODULES . 'payment/oceanpayment_creditcard/loading.gif';?>"  />
       </div>
    </div>   
    <div>
         <iframe width="100%" height="<?php echo $iframe_height.'px';?>" scrolling="auto" style="border:none; margin: 0 auto; overflow:auto;" id="ifrm_creditcard_checkout" name ="ifrm_creditcard_checkout" ></iframe>
    </div>
    <div>
          <form action="<?php echo $action_url;?>" name="creditcard_checkout" target="ifrm_creditcard_checkout" >
               <input type="hidden" name="account" value="<?php echo $account;?>">
               <input type="hidden" name="terminal" value="<?php echo $terminal;?>">
               <input type="hidden" name="order_number" value="<?php echo $order_number;?>">
               <input type="hidden" name="order_currency" value="<?php echo $order_currency;?>">
               <input type="hidden" name="order_amount" value="<?php echo $order_amount;?>">
               <input type="hidden" name="backUrl" value="<?php echo $backUrl;?>">
               <input type="hidden" name="noticeUrl" value="<?php echo $noticeUrl;?>">
               <input type="hidden" name="methods" value="<?php echo $methods;?>">
               <input type="hidden" name="order_notes" value="<?php echo $order_notes;?>">
               <input type="hidden" name="signValue" value="<?php echo $signValue;?>">
               <input type="hidden" name="billing_firstName" value="<?php echo $billing_firstName;?>">
               <input type="hidden" name="billing_lastName" value="<?php echo $billing_lastName;?>">
               <input type="hidden" name="billing_email" value="<?php echo $billing_email;?>">
               <input type="hidden" name="billing_phone" value="<?php echo $billing_phone;?>">
               <input type="hidden" name="billing_country" value="<?php echo $billing_country;?>">
               <input type="hidden" name="billing_state" value="<?php echo $billing_state;?>">
               <input type="hidden" name="billing_city" value="<?php echo $billing_city;?>">
               <input type="hidden" name="billing_address" value="<?php echo $billing_address;?>">
               <input type="hidden" name="billing_zip" value="<?php echo $billing_zip;?>">
               <input type="hidden" name="ship_firstName" value="<?php echo $ship_firstName;?>">
               <input type="hidden" name="ship_lastName" value="<?php echo $ship_lastName;?>">
               <input type="hidden" name="ship_phone" value="<?php echo $ship_phone;?>">
               <input type="hidden" name="ship_country" value="<?php echo $ship_country;?>">
               <input type="hidden" name="ship_state" value="<?php echo $ship_state;?>">
               <input type="hidden" name="ship_city" value="<?php echo $ship_city;?>">
               <input type="hidden" name="ship_addr" value="<?php echo $ship_addr;?>">
               <input type="hidden" name="ship_zip" value="<?php echo $ship_zip;?>">
               <input type="hidden" name="productName" value="<?php echo $productName;?>">
               <input type="hidden" name="productSku" value="<?php echo $productSku;?>">
               <input type="hidden" name="productNum" value="<?php echo $productNum;?>">
               <input type="hidden" name="cart_info" value="<?php echo $cart_info;?>">
               <input type="hidden" name="cart_api" value="<?php echo $cart_api;?>">
               <input type="hidden" name="pages" value="<?php echo $pages;?>">
          </form>
    </div>
    <script type="text/javascript">
        var ifrm_cc  = document.getElementById("ifrm_creditcard_checkout");
        var loading  = document.getElementById("loading");
        document.creditcard_checkout.submit();
        if (ifrm_cc.attachEvent){
        	ifrm_cc.attachEvent("onload", function(){
                loading.style.display = 'none';
            });
        } else {
        	ifrm_cc.onload = function(){
                loading.style.display = 'none';
            };
        }
       
    </script>
	<br class="clearBoth" />
</div>