<?php    

	//输入流
	$xml_str = file_get_contents("php://input");

	//判断返回的输入流是否为xml
	if(xml_parser($xml_str)){
		$xml = new DOMDocument();		
		$xml = simplexml_load_string($xml_str);
		
		//把推送参数赋值到$_REQUEST
		$_REQUEST['response_type']	  = (string)$xml->response_type;
		$_REQUEST['account']		  = (string)$xml->account;
		$_REQUEST['terminal'] 	      = (string)$xml->terminal;
		$_REQUEST['payment_id'] 	  = (string)$xml->payment_id;
		$_REQUEST['order_number']     = (string)$xml->order_number;
		$_REQUEST['order_currency']   = (string)$xml->order_currency;
		$_REQUEST['order_amount']     = (string)$xml->order_amount;
		$_REQUEST['payment_status']   = (string)$xml->payment_status;
		$_REQUEST['payment_details']  = (string)$xml->payment_details;
		$_REQUEST['signValue'] 	      = (string)$xml->signValue;
		$_REQUEST['order_notes']	  = (string)$xml->order_notes;
		$_REQUEST['card_number']	  = (string)$xml->card_number;
		$_REQUEST['payment_authType'] = (string)$xml->payment_authType;
		$_REQUEST['payment_risk'] 	  = (string)$xml->payment_risk;
		$_REQUEST['methods'] 	  	  = (string)$xml->methods;
		$_REQUEST['payment_country']  = (string)$xml->payment_country;
		$_REQUEST['payment_solutions']= (string)$xml->payment_solutions;				
	}
	

	if($_REQUEST['response_type'] == 1 || isset($_REQUEST['notice_type'])){             //检测是否推送 1为推送 0为正常浏览器跳转
		$_SESSION['order_number_created'] = $_REQUEST['order_number'];   //订单号
		$_SESSION['payment'] = 'oceanpayment_oxxo';     //支付方式
	
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
				$back_url = zen_href_link('checkout_oceanpayment_oxxo_pending', '', 'SSL');
			}
			
		} else {
			//支付失败
			$back_url = zen_href_link('checkout_oceanpayment_oxxo_failure', '', 'SSL');
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