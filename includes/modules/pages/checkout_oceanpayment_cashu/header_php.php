<?php    

	//输入流
	$xml_str = file_get_contents("php://input");

	//判断返回的输入流是否为xml
	if(xml_parser($xml_str)){
		$xml = new DOMDocument();
		$xml->loadXML($xml_str);
			
		$_REQUEST['response_type']	  = $xml->getElementsByTagName("response_type")->item(0)->nodeValue;
		$_REQUEST['account']		  = $xml->getElementsByTagName("account")->item(0)->nodeValue;
		$_REQUEST['terminal'] 	      = $xml->getElementsByTagName("terminal")->item(0)->nodeValue;
		$_REQUEST['payment_id'] 	  = $xml->getElementsByTagName("payment_id")->item(0)->nodeValue;
		$_REQUEST['order_number']     = $xml->getElementsByTagName("order_number")->item(0)->nodeValue;
		$_REQUEST['order_currency']   = $xml->getElementsByTagName("order_currency")->item(0)->nodeValue;
		$_REQUEST['order_amount']     = $xml->getElementsByTagName("order_amount")->item(0)->nodeValue;
		$_REQUEST['payment_status']   = $xml->getElementsByTagName("payment_status")->item(0)->nodeValue;
		$_REQUEST['payment_details']  = $xml->getElementsByTagName("payment_details")->item(0)->nodeValue;
		$_REQUEST['signValue'] 	      = $xml->getElementsByTagName("signValue")->item(0)->nodeValue;
		$_REQUEST['order_notes']	  = $xml->getElementsByTagName("order_notes")->item(0)->nodeValue;
		$_REQUEST['card_number']	  = $xml->getElementsByTagName("card_number")->item(0)->nodeValue;
		$_REQUEST['methods']	      = $xml->getElementsByTagName("methods")->item(0)->nodeValue;
		$_REQUEST['payment_country']  = $xml->getElementsByTagName("payment_country")->item(0)->nodeValue;
		$_REQUEST['payment_authType'] = $xml->getElementsByTagName("payment_authType")->item(0)->nodeValue;
		$_REQUEST['payment_risk'] 	  = $xml->getElementsByTagName("payment_risk")->item(0)->nodeValue;
		
		$_REQUEST['notice_type'] 	  = $xml->getElementsByTagName("notice_type")->item(0)->nodeValue;
		$_REQUEST['payment_dateTime'] = $xml->getElementsByTagName("payment_dateTime")->item(0)->nodeValue;
		$_REQUEST['push_dateTime'] 	  = $xml->getElementsByTagName("push_dateTime")->item(0)->nodeValue;
		$_REQUEST['push_id'] 	  	  = $xml->getElementsByTagName("push_id")->item(0)->nodeValue;
		$_REQUEST['push_status'] 	  = $xml->getElementsByTagName("push_status")->item(0)->nodeValue;
		$_REQUEST['push_details'] 	  = $xml->getElementsByTagName("push_details")->item(0)->nodeValue;
	
	}
	

	if($_REQUEST['response_type'] == 1 || isset($_REQUEST['notice_type'])){             //检测是否推送 1为推送 0为正常浏览器跳转
		$_SESSION['order_number_created'] = $_REQUEST['order_number'];   //订单号
		$_SESSION['payment'] = 'oceanpayment_cashu';     //支付方式
	
	}else{
		if (!$_SESSION['customer_id']) {
			zen_redirect(zen_href_link(FILENAME_TIME_OUT));
		}
	}
	
	
	
	

	require(DIR_WS_MODULES . zen_get_module_directory('require_languages.php'));
	if (isset($_REQUEST['payment_status']) || isset($_REQUEST['notice_type'])) {
		// load selected payment module
		require(DIR_WS_CLASSES . 'payment.php');
		
		$payment_modules = new payment($_SESSION['payment']); 
		
		$payment_modules->before_process();
		

		// unregister session variables used during checkout
		unset($_SESSION['sendto']);
		unset($_SESSION['billto']);
		unset($_SESSION['shipping']);
		unset($_SESSION['payment']);
		unset($_SESSION['comments']);
		

		if($_REQUEST['payment_status'] == 1){
			//支付成功
			$_SESSION['cart']->reset(true);
			$back_url = zen_href_link(FILENAME_CHECKOUT_SUCCESS, '', 'SSL');
		} elseif($_REQUEST['payment_status'] == -1 ) {
			//待处理
			$_SESSION['cart']->reset(true);
			//是否预授权交易
			if($_REQUEST['payment_authType'] == 1){
				$back_url = zen_href_link(FILENAME_CHECKOUT_SUCCESS, '', 'SSL');
			}else{
				$back_url = zen_href_link('checkout_oceanpayment_cashu_pending', '', 'SSL');
			}
			
		} else {
			//支付失败
			$back_url = zen_href_link('checkout_oceanpayment_cashu_failure', '', 'SSL');
		}

		
		echo '<script type="text/javascript">parent.location.replace("' . $back_url . '");</script>';
		
		exit();
	} else {
		
	}
	
	
	/**
	 *  判断是否为xml
	 *
	 */
	function xml_parser($str){
		$xml_parser = xml_parser_create();
		if(!xml_parse($xml_parser,$str,true)){
			xml_parser_free($xml_parser);
			return false;
		}else {
			return true;
		}
	}
	
	
	$breadcrumb->add(NAVBAR_TITLE);
?>