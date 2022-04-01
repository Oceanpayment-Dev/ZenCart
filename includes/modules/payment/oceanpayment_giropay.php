<?php
class oceanpayment_giropay{
	
	const PUSH 			= "[PUSH]";
	const BrowserReturn = "[Browser Return]";
	
	
	var $code, $title, $description, $enabled;
	
	/**
	 * order status setting for pending orders
	 *
	 * @var int
	 */
	var $order_pending_status = 1;
	
	/**
	 * order status setting for completed orders
	 *
	 * @var int
	 */	
	var $order_status = DEFAULT_ORDERS_STATUS_ID;
	
	
	function oceanpayment_giropay() {	
		global $order;
		
		$this->code = 'oceanpayment_giropay';
		
		if ($_GET['main_page'] != '') {		
			$this->title = MODULE_PAYMENT_OCEANPAYMENT_GIROPAY_TEXT_CATALOG_TITLE; // Payment Module title in Catalog		
		} else {		
			$this->title = MODULE_PAYMENT_OCEANPAYMENT_GIROPAY_TEXT_ADMIN_TITLE; // Payment Module title in Admin		
		}
		
		$this->description = MODULE_PAYMENT_OCEANPAYMENT_GIROPAY_TEXT_DESCRIPTION;		
		$this->sort_order = MODULE_PAYMENT_OCEANPAYMENT_GIROPAY_SORT_ORDER;		
		$this->enabled = ((MODULE_PAYMENT_OCEANPAYMENT_GIROPAY_STATUS == 'True') ? true : false);
		
		if ((int)MODULE_PAYMENT_OCEANPAYMENT_GIROPAY_PENDING_STATUS_ID > 0) {		
			$this->order_status = MODULE_PAYMENT_OCEANPAYMENT_GIROPAY_PENDING_STATUS_ID;		
		}		
		
		if (is_object($order)) $this->update_status();
		
		$this->form_action_url = MODULE_PAYMENT_OCEANPAYMENT_GIROPAY_HANDLER;		
		
	}
	
	
	function update_status() {	
		global $order, $db;
	
		if ( ($this->enabled == true) && ((int)MODULE_PAYMENT_OCEANPAYMENT_GIROPAY_ZONE > 0) ) {
			$check_flag = false;	
			$check_query = $db->Execute("select zone_id from " . TABLE_ZONES_TO_GEO_ZONES . " where geo_zone_id = '" . MODULE_PAYMENT_OCEANPAYMENT_GIROPAY_ZONE . "' and zone_country_id = '" . $order->billing['country']['id'] . "' order by zone_id");
	
			while (!$check_query->EOF) {	
				if ($check_query->fields['zone_id'] < 1) {	
					$check_flag = true;	
					break;	
				} elseif ($check_query->fields['zone_id'] == $order->billing['zone_id']) {	
					$check_flag = true;
					break;	
				}
	
				$check_query->MoveNext();
			}
	
			if ($check_flag == false) {
				$this->enabled = false;	
			}
	
		}
	
	}
	
	
	function javascript_validation() {	
		return false;	
	}
	
	
	function selection() {	
		return array('id' => $this->code,
				'module' => MODULE_PAYMENT_OCEANPAYMENT_GIROPAY_TEXT_CATALOG_LOGO,
				'icon' => MODULE_PAYMENT_OCEANPAYMENT_GIROPAY_TEXT_CATALOG_LOGO
		);	
	}	

	
	function pre_confirmation_check() {
		return false;
	}	
	
	
	function confirmation() {	
		return array('title' => MODULE_PAYMENT_OCEANPAYMENT_GIROPAY_TEXT_DESCRIPTION);	
	}
	
	
	function process_button() {	
		global $db, $order, $currencies,$order_totals;
	
		require_once(DIR_WS_CLASSES . 'order.php');	
			 
		if ( isset($_SESSION['order_id']) && ($_SESSION['cart']->cartID == $_SESSION['old_cart_id']) && ($_SESSION['old_cur'] == $_SESSION['currency'])) {
			$order_id = $_SESSION['order_id'];
		} else {
			$order = new order();
			$order->info['order_status'] = $this->order_status;//init status,pending
			require_once(DIR_WS_CLASSES . 'order_total.php');
			$order_total_modules = new order_total();
			$order_totals = $order_total_modules->process();
			$order_id = $order->create($order_totals);
			$order->create_add_products($order_id, 2);
			$_SESSION['order_id'] = $order_id;
			$_SESSION['old_cart_id'] = $_SESSION['cart']->cartID;//if customer add or remove item,update qty
			$_SESSION['old_cur'] = $_SESSION['currency']; //if the customer swich the currency
		}
		 
		//获取订单详情
		$productDetails = $this->getProductItems($order->products);

		//账户
		$account           = MODULE_PAYMENT_OCEANPAYMENT_GIROPAY_ACCOUNT;				
		//终端号
		$terminal          = MODULE_PAYMENT_OCEANPAYMENT_GIROPAY_TERMINAL;		
		//securecode
		$secureCode        = MODULE_PAYMENT_OCEANPAYMENT_GIROPAY_SCODE;				
		//返回地址
		$backUrl           = zen_href_link('checkout_oceanpayment_giropay', '', 'SSL');  // $backUrl = zen_href_link(FILENAME_CHECKOUT_PROCESS, '', 'SSL');
		//服务器响应地址
		$noticeUrl         = zen_href_link('checkout_oceanpayment_giropay', '', 'SSL');
		//支付方式
		$methods           = 'Giropay';
		//订单号
		$order_number      = $order_id;
		//支付币种
		$order_currency    = $_SESSION['currency'];        // $order_currency = $order->info['currency'];
		//金额
		$order_amount      = number_format(($order->info['total']) * $currencies->get_value($order_currency), 2, '.', '');
		//账单人名
		$billing_firstName = $this->OceanHtmlSpecialChars($order->billing['firstname']);
		//账单人姓
		$billing_lastName  = $this->OceanHtmlSpecialChars($order->billing['lastname']);
		//账单人email
		$billing_email     = $this->OceanHtmlSpecialChars($order->customer['email_address']);
		//账单人电话
		$billing_phone     = $order->customer['telephone'];
		//账单人国家
		$billing_country   = $order->billing['country']['iso_code_2'];
		//账单人州
		$billing_state     = $order->billing['state'];
		//账单人城市
		$billing_city      = $order->billing['city'];
		//账单人地址
		$billing_address   = $order->billing['street_address'];
		//账单人邮编,如果邮编为空，就默认999999
		$billing_zip       = !empty($order->billing['postcode']) ? $order->billing['postcode'] : '999999';
		//备注
		$order_notes       = '';
		//sha256加密结果
		$signValue         = hash("sha256",$account.$terminal.$backUrl.$order_number.$order_currency.$order_amount.$billing_firstName.$billing_lastName.$billing_email.$secureCode);
		//收货人地址信息
		//收货人名
		$ship_firstName    = $order->delivery['firstname'];		
		//收货人姓
		$ship_lastName	   = $order->delivery['lastname'];		
		//收货人手机
		$ship_phone 	   = $order->customer['telephone'];			
		//收货人国家
		$ship_country 	   = $order->delivery['country']['iso_code_2'];			
		//收货人州
		$ship_state   	   = $order->delivery['state'];			
		//收货人城市
		$ship_city   	   = $order->delivery['city'];			
		//收货人地址
		$ship_addr		   = $order->delivery['street_address'] ;				
		//收货人邮编
		$ship_zip 		   = $order->delivery['postcode'];
		//产品名称
		$productName	   = $productDetails['productName'];
		//产品SKU
		$productSku		   = $productDetails['productSku'];
		//产品数量
		$productNum		   = $productDetails['productNum'];
		//购物车系统类型
		$cart_info         = 'zencart';
		//插件版本
		$cart_api          = 'V1.7.1';
		
		//记录发送到oceanpayment的post log
		$filedate = date('Y-m-d');
		$postdate = date('Y-m-d H:i:s');
		$newfile  = fopen( "oceanpayment_log/" . $filedate . ".log", "a+" );
		$post_log = $postdate."[POST to Oceanpayment]\r\n" .
				"account = "           .$account . "\r\n".
				"terminal = "          .$terminal . "\r\n".
				"backUrl = "           .$backUrl . "\r\n".
				"noticeUrl = "         .$noticeUrl . "\r\n".
				"order_number = "      .$order_number . "\r\n".
				"order_currency = "    .$order_currency . "\r\n".
				"order_amount = "      .$order_amount . "\r\n".
				"billing_firstName = " .$billing_firstName . "\r\n".
				"billing_lastName = "  .$billing_lastName . "\r\n".
				"billing_email = "     .$billing_email . "\r\n".
				"billing_phone = "     .$billing_phone . "\r\n".
				"billing_country = "   .$billing_country . "\r\n".
				"billing_state = "     .$billing_state . "\r\n".
				"billing_city = "      .$billing_city . "\r\n".
				"billing_address = "   .$billing_address . "\r\n".
				"billing_zip = "       .$billing_zip . "\r\n".
				"ship_firstName = "    .$ship_firstName . "\r\n".
				"ship_lastName = "     .$ship_lastName . "\r\n".
				"ship_phone = "        .$ship_phone . "\r\n".
				"ship_country = "      .$ship_country . "\r\n".
				"ship_state = "        .$ship_state . "\r\n".
				"ship_city = "     	   .$ship_city . "\r\n".
				"ship_addr = "   	   .$ship_addr . "\r\n".
				"ship_zip = "     	   .$ship_zip . "\r\n".
				"methods = "           .$methods . "\r\n".
				"signValue = "         .$signValue . "\r\n".
				"productName = "       .$productName . "\r\n".
				"productSku = "        .$productSku . "\r\n".
				"productNum = "        .$productNum . "\r\n".
				"cart_info = "         .$cart_info . "\r\n".
				"cart_api = "          .$cart_api . "\r\n".
				"order_notes = "       .$order_notes . "\r\n";
		 
		$post_log = $post_log . "*************************************\r\n";
		$post_log = $post_log.file_get_contents( "oceanpayment_log/" . $filedate . ".log");
		$filename = fopen( "oceanpayment_log/" . $filedate . ".log", "r+" );
		fwrite($filename,$post_log);
		fclose($filename);
		fclose($newfile);
		
		$process_button_string = zen_draw_hidden_field('account', $account) .
		                         zen_draw_hidden_field('terminal', $terminal) .
		                         zen_draw_hidden_field('order_number', $order_number) .
		                         zen_draw_hidden_field('order_currency', $order_currency) .
		                         zen_draw_hidden_field('order_amount', $order_amount) .
		                         zen_draw_hidden_field('backUrl', $backUrl).
		                         zen_draw_hidden_field('noticeUrl', $noticeUrl).
		                         zen_draw_hidden_field('methods', $methods).
		                         zen_draw_hidden_field('order_notes',$order_notes).
		                         zen_draw_hidden_field('signValue',$signValue).
		                         zen_draw_hidden_field('billing_firstName', $billing_firstName) .
		                         zen_draw_hidden_field('billing_lastName', $billing_lastName) .
		                         zen_draw_hidden_field('billing_email', $billing_email) .
		                         zen_draw_hidden_field('billing_phone', $billing_phone).
		                         zen_draw_hidden_field('billing_country', $billing_country).
		                         zen_draw_hidden_field('billing_state', $billing_state).
		                         zen_draw_hidden_field('billing_city',$billing_city).
		                         zen_draw_hidden_field('billing_address',$billing_address).
		                         zen_draw_hidden_field('billing_zip',$billing_zip).
		                         zen_draw_hidden_field('ship_firstName', $ship_firstName) .
		                         zen_draw_hidden_field('ship_lastName', $ship_lastName) .
		                         zen_draw_hidden_field('ship_phone', $ship_phone).
		                         zen_draw_hidden_field('ship_country', $ship_country).
		                         zen_draw_hidden_field('ship_state', $ship_state).
		                         zen_draw_hidden_field('ship_city',$ship_city).
		                         zen_draw_hidden_field('ship_addr',$ship_addr).
		                         zen_draw_hidden_field('ship_zip',$ship_zip).
		                         zen_draw_hidden_field('productName',$productName).
		                         zen_draw_hidden_field('productSku',$productSku).
		                         zen_draw_hidden_field('productNum',$productNum).
		                         zen_draw_hidden_field('cart_info',$cart_info).
		                         zen_draw_hidden_field('cart_api',$cart_api);
	
		return $process_button_string;
	}
	
	
	function before_process() {	
		global $_POST, $order, $currencies, $messageStack,$db;
		//返回商户号
		$account          = $_REQUEST['account'];
		//返回终端号
		$terminal         = $_REQUEST['terminal'];
		//返回Oceanpayment 的支付唯一号
		$payment_id       = $_REQUEST['payment_id'];
		//返回网站订单号
		$order_number     = $_REQUEST['order_number'];
		//返回交易币种
		$order_currency   = $_REQUEST['order_currency'];
		//返回支付金额
		$order_amount     = $_REQUEST['order_amount'];
		//返回支付状态
		$payment_status   = $_REQUEST['payment_status'];
		//返回支付详情
		$payment_details  = $_REQUEST['payment_details'];
		//返回交易安全签名
		$back_signValue   = $_REQUEST['signValue'];
		//支付方式
		$methods   		  = $_REQUEST['methods'];
		//返回备注
		$order_notes      = $_REQUEST['order_notes'];
		//未通过的风控规则
		$payment_risk     = $_REQUEST['payment_risk'];
		//消费者所在国
		$payment_country  = $_REQUEST['payment_country'];
		//获取本地的code值
		$secureCode       = MODULE_PAYMENT_OCEANPAYMENT_GIROPAY_SCODE;
		//返回支付信用卡卡号
		$card_number      = $_REQUEST['card_number'];
		//返回交易类型
		$payment_authType = $_REQUEST['payment_authType'];
	
		$local_signValue  = hash("sha256",$account.$terminal.$order_number.$order_currency.$order_amount.$order_notes.$card_number.
				$payment_id.$payment_authType.$payment_status.$payment_details.$payment_risk.$secureCode);

		//用于支付结果页面显示
		$_SESSION['payment_details'] = $payment_details;
		//响应代码
		//用于支付结果页面显示响应代码
		$getErrorCode 	 		  	 = explode(':', $payment_details);	
		$_SESSION['errorCode']       = $getErrorCode[0];
		
		if(isset($_REQUEST['notice_type'])){
			$this->notice_process();
		}
		

		//加密串校验
		if (strtolower($local_signValue) == strtolower($back_signValue)) {
	
			if ($payment_status == 1) {				 
				//支付成功
				$this->order_status = MODULE_PAYMENT_OCEANPAYMENT_GIROPAY_SUCCESS_STATUS_ID;
				$comments           = 'Success.';
				$messageStack->add_session('checkout_success', $comments . $payment_details, 'success');
				$customer_notified  = 1;			 
			}elseif($payment_status == -1){			 
				//待处理
				
				//是否预授权交易
				if($payment_authType == 1){
					$this->order_status = MODULE_PAYMENT_OCEANPAYMENT_GIROPAY_SUCCESS_STATUS_ID;
					$comments           = 'Success.';
					$messageStack->add_session('checkout_success', $comments . $payment_details, 'success');
					$customer_notified  = 1;
				}else{
					$this->order_status = MODULE_PAYMENT_OCEANPAYMENT_GIROPAY_WAITING_PROCESS_STATUS_ID;
					$comments           = 'Waiting for the bank processing.';
					$messageStack->add_session('checkout_waiting', $comments . $payment_details, 'caution');
					$customer_notified  = 0;
				}
				
			}elseif($payment_status == 0){				 
				//支付失败
				$this->order_status = MODULE_PAYMENT_OCEANPAYMENT_GIROPAY_FAILURE_STATUS_ID;
				$comments           = 'Failure.';
				$messageStack->add_session('checkout_failure', $comments . $payment_details, 'error');
				$customer_notified  = 0;				 
			}
			 
		}else{			 
			//支付失败
			$this->order_status = MODULE_PAYMENT_OCEANPAYMENT_GIROPAY_FAILURE_STATUS_ID;
			$comments           = 'Failure.';
			$messageStack->add_session('checkout_failure', $comments . $payment_details, 'error');
			$customer_notified  = 0;			 
		}
		
		//检测是否推送 1为推送  0为正常浏览器跳转
		if($_REQUEST['response_type'] == 1){
			
			$logtype = self::PUSH;
			
			if(substr($payment_details,0,5) == 20061){	 //排除订单号重复(20061)的交易
				//获取原本的订单状态
				$thisOrderStatus = $db->Execute("SELECT orders_status FROM  " . TABLE_ORDERS  . " WHERE orders_id = '" . $order_number . "'");
				$original_orderStatus = $thisOrderStatus->fields['orders_status'];	
				$this->order_status   = $original_orderStatus;
			}else{
				$db->Execute("UPDATE " . TABLE_ORDERS  . " SET orders_status = " . $this->order_status . " WHERE orders_id = '" . $order_number . "'");
			}
		}elseif($_REQUEST['response_type'] == 0){
			//正常 POST返回
			
			$logtype = self::BrowserReturn;
			
			if(substr($payment_details,0,5) == 20061){	 //排除订单号重复(20061)的交易
				//获取原本的订单状态
				$thisOrderStatus = $db->Execute("SELECT orders_status FROM  " . TABLE_ORDERS  . " WHERE orders_id = '" . $order_number . "'");
				$original_orderStatus = $thisOrderStatus->fields['orders_status'];
				$this->order_status   = $original_orderStatus;
			}else{
				$db->Execute("UPDATE " . TABLE_ORDERS  . " SET orders_status = " . $this->order_status . " WHERE orders_id = '" . $order_number . "'");
			}
		}
		
		$this->returnLog($logtype);
		
		$comments = $logtype . '(' .$comments . ')' . 'payment_id: ' . $payment_id . ' | order_number: ' . $order_number . ' | ' . $order_currency . ':' . $order_amount . ' | payment_details: ' . $payment_details;
		$sql_data_array = array('orders_id'         => (int)$order_number,
				'orders_status_id'  => (int)$this->order_status,
				'date_added'        => 'now()',
				'comments'          => $comments,
				'customer_notified' => $customer_notified
		);
		zen_db_perform(TABLE_ORDERS_STATUS_HISTORY, $sql_data_array);
		
		if($_REQUEST['response_type'] == 1){
			echo "receive-ok";
			exit;
		}
		 
	}
	

	function notice_process() {
		global $_POST, $order,$db;
		//返回商户号
		$account           = $_REQUEST['account'];	
		//返回终端号
		$terminal          = $_REQUEST['terminal'];		
		//返回Oceanpayment的支付唯一号
		$payment_id        = $_REQUEST['payment_id'];		
		//网站订单号
		$order_number      = $_REQUEST['order_number'];		
		//异常交易ID
		$push_id 	 	   = $_REQUEST['push_id'];		
		//异常订单结果状态
		$push_status 	   = $_REQUEST['push_status'];		
		//具体详细信息
		$push_details  	   = $_REQUEST['push_details'];		
		//获取securecode
		$securecode        = MODULE_PAYMENT_OCEANPAYMENT_GIROPAY_SCODE;		
		//订单支付时间
		$payment_dateTime  = $_REQUEST['payment_dateTime'];		
		//推送时间
		$push_dateTime     = $_REQUEST['push_dateTime'];		
		//异常推送类型
		$notice_type       = $_REQUEST['notice_type'];		
		//返回数据签名
		$back_signValue    = $_REQUEST['signValue'];		
		//SHA256加密
		$local_signValue   = hash("sha256",$account.$terminal.$order_number.$payment_id.$push_id.$push_status.$push_details.$securecode);
		
		//异常交易推送类型
		$this->abnormalLog(self::Abnormal);
		if(isset($_REQUEST['notice_type'])){
			//加密串校验
			if (strtolower($local_signValue) == strtolower($back_signValue)) {

				switch($_REQUEST['notice_type']){
					case 'refund':
						//退款
						if($_REQUEST['push_status'] == 1){
							$AbnormalResult = 'Refund Successful';
						}elseif($_REQUEST['push_status'] == 0){
							$AbnormalResult = 'Refund Failed';
						}
				
						break;
					case 'chargeBack':
						//拒付
						if($_REQUEST['push_status'] == 1){
							$AbnormalResult = 'ChargeBack!';
						}
				
						break;
					case 're-presentment':
						//申诉
						if($_REQUEST['push_status'] == 1){
							$AbnormalResult = 'Re-presentment Successful';
						}elseif($_REQUEST['push_status'] == 0){
							$AbnormalResult = 'Re-presentment Failed';
						}elseif($_REQUEST['push_status'] == 2){
							$AbnormalResult = 'Re-presentment Pending';
						}
				
						break;
					case 'retrieval':
						//调单
						if($_REQUEST['push_status'] == 1){
							$AbnormalResult = 'Retrieval!';
						}
							
						break;
					case 'reversal-retrieval':
						//调单变正常
						if($_REQUEST['push_status'] == 1){
							$AbnormalResult = 'Reversal-retrieval!';
						}
							
						break;
					default:
				}
			}
			
			
			//获取原本的订单状态
			$thisOrderStatus = $db->Execute("SELECT orders_status FROM  " . TABLE_ORDERS  . " WHERE orders_id = '" . $order_number . "'");
			$original_orderStatus = $thisOrderStatus->fields['orders_status'];
			
			$comments = self::Abnormal . $AbnormalResult . '(' . 'payment_id: ' . $payment_id . ' | push_id: ' . $push_id . ' | push_dateTime: ' . $push_dateTime . ' | push_details: ' . $push_details . ')';
			$sql_data_array = array('orders_id'         => (int)$order_number,
					'orders_status_id'  => (int)$original_orderStatus,
					'date_added'        => 'now()',
					'comments'          => $comments,
					'customer_notified' => 1
			);
			zen_db_perform(TABLE_ORDERS_STATUS_HISTORY, $sql_data_array);
		}
// 		exit;		
	}
	
	
	function after_process() {	
	}
		
	
	function output_error() {	
		return false;	
	}
		
	
	function check() {	
		global $db;
		if (!isset($this->_check)) {	
			$check_query = $db->Execute("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_PAYMENT_OCEANPAYMENT_GIROPAY_STATUS'");	
			$this->_check = $check_query->RecordCount();	
		}	
		return $this->_check;	
	}
	
	
	function install() {	
		global $db, $language, $module_type;
		if (!defined('MODULE_PAYMENT_OCEANPAYMENT_GIROPAY_TEXT_CONFIG_1_1')) include(DIR_FS_CATALOG_LANGUAGES . $_SESSION['language'] . '/modules/' . $module_type . '/' . $this->code . '.php');
		//在数据库中插入模块设置(是否启用插件)
		$db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('" . MODULE_PAYMENT_OCEANPAYMENT_GIROPAY_TEXT_CONFIG_1_1 . "', 'MODULE_PAYMENT_OCEANPAYMENT_GIROPAY_STATUS', 'True', '" . MODULE_PAYMENT_OCEANPAYMENT_GIROPAY_TEXT_CONFIG_1_2 . "', '6', '0', 'zen_cfg_select_option(array(\'True\', \'False\'), ', now())");
		//在数据库中插入模块设置(填写账户)
		$db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('" . MODULE_PAYMENT_OCEANPAYMENT_GIROPAY_TEXT_CONFIG_3_1 . "', 'MODULE_PAYMENT_OCEANPAYMENT_GIROPAY_ACCOUNT', '', '" . MODULE_PAYMENT_OCEANPAYMENT_GIROPAY_TEXT_CONFIG_3_2 . "', '6', '2', now())");
		//在数据库中插入模块设置(填写终端号)
		$db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('" . MODULE_PAYMENT_OCEANPAYMENT_GIROPAY_TEXT_CONFIG_4_1 . "', 'MODULE_PAYMENT_OCEANPAYMENT_GIROPAY_TERMINAL', '', '" . MODULE_PAYMENT_OCEANPAYMENT_GIROPAY_TEXT_CONFIG_4_2 . "', '6', '4', now())");
		//在数据库中插入模块设置(终端安全码)
		$db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('" . MODULE_PAYMENT_OCEANPAYMENT_GIROPAY_TEXT_CONFIG_5_1 . "', 'MODULE_PAYMENT_OCEANPAYMENT_GIROPAY_SCODE', '', '" . MODULE_PAYMENT_OCEANPAYMENT_GIROPAY_TEXT_CONFIG_5_2 . "', '6', '6', now())");
		//在数据库中插入模块设置(支付地区)
		$db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('" . MODULE_PAYMENT_OCEANPAYMENT_GIROPAY_TEXT_CONFIG_6_1 . "', 'MODULE_PAYMENT_OCEANPAYMENT_GIROPAY_ZONE', '0', '" . MODULE_PAYMENT_OCEANPAYMENT_GIROPAY_TEXT_CONFIG_6_2 . "', '6', '8', 'zen_get_zone_class_title', 'zen_cfg_pull_down_zone_classes(', now())");
		//在数据库中插入模块设置(默认订单状态)
		$db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('" . MODULE_PAYMENT_OCEANPAYMENT_GIROPAY_TEXT_CONFIG_7_1 . "', 'MODULE_PAYMENT_OCEANPAYMENT_GIROPAY_PENDING_STATUS_ID', '1', '" . MODULE_PAYMENT_OCEANPAYMENT_GIROPAY_TEXT_CONFIG_7_2 . "', '6', '10', 'zen_get_order_status_name', 'zen_cfg_pull_down_order_statuses(', now())");
		//在数据库中插入模块设置(成功订单状态)
		$db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('" . MODULE_PAYMENT_OCEANPAYMENT_GIROPAY_TEXT_CONFIG_8_1 . "', 'MODULE_PAYMENT_OCEANPAYMENT_GIROPAY_SUCCESS_STATUS_ID', '2', '" . MODULE_PAYMENT_OCEANPAYMENT_GIROPAY_TEXT_CONFIG_8_2 . "', '6', '12', 'zen_get_order_status_name', 'zen_cfg_pull_down_order_statuses(', now())");
		//在数据库中插入模块设置(失败订单状态)
		$db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('" . MODULE_PAYMENT_OCEANPAYMENT_GIROPAY_TEXT_CONFIG_9_1 . "', 'MODULE_PAYMENT_OCEANPAYMENT_GIROPAY_FAILURE_STATUS_ID', '1', '" . MODULE_PAYMENT_OCEANPAYMENT_GIROPAY_TEXT_CONFIG_9_2 . "', '6', '14', 'zen_get_order_status_name', 'zen_cfg_pull_down_order_statuses(', now())");
		//在数据库中插入模块设置(待处理订单状态)
		$db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('" . MODULE_PAYMENT_OCEANPAYMENT_GIROPAY_TEXT_CONFIG_10_1 . "', 'MODULE_PAYMENT_OCEANPAYMENT_GIROPAY_WAITING_PROCESS_STATUS_ID', '1', '" . MODULE_PAYMENT_OCEANPAYMENT_GIROPAY_TEXT_CONFIG_10_2 . "', '6', '16', 'zen_get_order_status_name', 'zen_cfg_pull_down_order_statuses(', now())");
		//在数据库中插入模块设置(支付模块排序)
		$db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('" . MODULE_PAYMENT_OCEANPAYMENT_GIROPAY_TEXT_CONFIG_11_1 . "', 'MODULE_PAYMENT_OCEANPAYMENT_GIROPAY_SORT_ORDER', '0', '" . MODULE_PAYMENT_OCEANPAYMENT_GIROPAY_TEXT_CONFIG_11_2 . "', '6', '18', now())");
		//在数据库中插入模块设置(支付提交地址)
		$db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('" . MODULE_PAYMENT_OCEANPAYMENT_GIROPAY_TEXT_CONFIG_12_1 . "', 'MODULE_PAYMENT_OCEANPAYMENT_GIROPAY_HANDLER', 'https://secure.oceanpayment.com/gateway/service/test', '" . MODULE_PAYMENT_OCEANPAYMENT_GIROPAY_TEXT_CONFIG_12_2 . "', '6', '20', '', now())");
		//在数据库中插入模块设置(响应代码)
		$db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('" . MODULE_PAYMENT_OCEANPAYMENT_GIROPAY_TEXT_CONFIG_14_1 . "', 'MODULE_PAYMENT_OCEANPAYMENT_GIROPAY_RESPONSE_CODE', 'false', '" . MODULE_PAYMENT_OCEANPAYMENT_GIROPAY_TEXT_CONFIG_14_2 . "', '6', '22', 'zen_cfg_select_option(array(\'Online\', \'Local\'), ', now())");
		
	}
	
	
	function remove() {	
		global $db;	
		$db->Execute("delete from " . TABLE_CONFIGURATION . " where configuration_key LIKE  'MODULE_PAYMENT_OCEANPAYMENT_GIROPAY%'");	
	}
	
	
	function keys() {	
		return array(	
				'MODULE_PAYMENT_OCEANPAYMENT_GIROPAY_STATUS',
				'MODULE_PAYMENT_OCEANPAYMENT_GIROPAY_ACCOUNT',
				'MODULE_PAYMENT_OCEANPAYMENT_GIROPAY_TERMINAL',
				'MODULE_PAYMENT_OCEANPAYMENT_GIROPAY_SCODE',
				'MODULE_PAYMENT_OCEANPAYMENT_GIROPAY_ZONE',
				'MODULE_PAYMENT_OCEANPAYMENT_GIROPAY_PENDING_STATUS_ID',
				'MODULE_PAYMENT_OCEANPAYMENT_GIROPAY_SUCCESS_STATUS_ID',
				'MODULE_PAYMENT_OCEANPAYMENT_GIROPAY_FAILURE_STATUS_ID',
				'MODULE_PAYMENT_OCEANPAYMENT_GIROPAY_WAITING_PROCESS_STATUS_ID',
				'MODULE_PAYMENT_OCEANPAYMENT_GIROPAY_SORT_ORDER',
				'MODULE_PAYMENT_OCEANPAYMENT_GIROPAY_HANDLER',
				'MODULE_PAYMENT_OCEANPAYMENT_GIROPAY_RESPONSE_CODE',
		);	
	}
	
	
	/**
	 * return log
	 *
	 */
	public function returnLog($logtype){
	
		$filedate   = date('Y-m-d');
		$returndate = date('Y-m-d H:i:s');
		$newfile    = fopen( "oceanpayment_log/" . $filedate . ".log", "a+" );
		$return_log = $returndate . $logtype . "\r\n".
				"response_type = "       . $_REQUEST['response_type'] . "\r\n".
				"account = "             . $_REQUEST['account'] . "\r\n".
				"terminal = "            . $_REQUEST['terminal'] . "\r\n".
				"payment_id = "          . $_REQUEST['payment_id'] . "\r\n".
				"order_number = "        . $_REQUEST['order_number'] . "\r\n".
				"order_currency = "      . $_REQUEST['order_currency'] . "\r\n".
				"order_amount = "        . $_REQUEST['order_amount'] . "\r\n".
				"payment_status = "      . $_REQUEST['payment_status'] . "\r\n".
				"payment_details = "     . $_REQUEST['payment_details'] . "\r\n".
				"signValue = "           . $_REQUEST['signValue'] . "\r\n".
				"order_notes = "         . $_REQUEST['order_notes'] . "\r\n".
				"card_number = "         . $_REQUEST['card_number'] . "\r\n".
				"methods = "         	 . $_REQUEST['methods'] . "\r\n".
				"payment_country = "     . $_REQUEST['payment_country'] . "\r\n".
				"payment_authType = "    . $_REQUEST['payment_authType'] . "\r\n".
				"payment_risk = "        . $_REQUEST['payment_risk'] . "\r\n";
	
		$return_log = $return_log . "*************************************\r\n";
		$return_log = $return_log.file_get_contents( "oceanpayment_log/" . $filedate . ".log");
		$filename   = fopen( "oceanpayment_log/" . $filedate . ".log", "r+" );
		fwrite($filename,$return_log);
		fclose($filename);
		fclose($newfile);
	
	}
	
	
	/**
	 * Abnormal log
	 */
	public function abnormalLog($logType){
		$filedate   = $logType . date('Y-m-d');
		$returndate = date('Y-m-d H:i:s');
		$newfile    = fopen( "oceanpayment_log/" . $filedate . ".log", "a+" );
		$return_log = $returndate . $logType . "\r\n".
				"notice_type = "       	 . $_REQUEST['notice_type'] . "\r\n".
				"account = "             . $_REQUEST['account'] . "\r\n".
				"terminal = "            . $_REQUEST['terminal'] . "\r\n".
				"payment_id = "          . $_REQUEST['payment_id'] . "\r\n".
				"order_number = "        . $_REQUEST['order_number'] . "\r\n".
				"push_id = "      		 . $_REQUEST['push_id'] . "\r\n".
				"push_status = "         . $_REQUEST['push_status'] . "\r\n".
				"payment_dateTime = "    . $_REQUEST['payment_dateTime'] . "\r\n".
				"push_dateTime = "       . $_REQUEST['push_dateTime'] . "\r\n".
				"push_details = "        . $_REQUEST['push_details'] . "\r\n".
				"signValue = "        	 . $_REQUEST['signValue'] . "\r\n";
	
		$return_log = $return_log . "*************************************\r\n";
		$return_log = $return_log.file_get_contents( "oceanpayment_log/" . $filedate . ".log");
		$filename   = fopen( "oceanpayment_log/" . $filedate . ".log", "r+" );
		fwrite($filename,$return_log);
		fclose($filename);
		fclose($newfile);
		
	}
	
	
	/**
	 * 获取订单详情
	 */
	function getProductItems($AllItems){
	
		$productDetails = array();
		$productName = array();
		$productSku = array();
		$productNum = array();
		 
		foreach ($AllItems as $item) {
			$productName[] = $item['name'];
			$productSku[] = $item['id'];
			$productNum[] = $item['qty'];
		}
		
		$productDetails['productName'] = implode(';', $productName);
		$productDetails['productSku'] = implode(';', $productSku);
		$productDetails['productNum'] = implode(';', $productNum);
	
		return $productDetails;
		
	}
	
	
	
	
	/**
	 * 钱海支付Html特殊字符转义
	 */
	function OceanHtmlSpecialChars($parameter){
		//去除前后空格
		$parameter = trim($parameter);
		//转义"双引号,<小于号,>大于号,'单引号
		$parameter = str_replace(array("<",">","'","\""),array("&lt;","&gt;","&#039;","&quot;"),$parameter);
		return $parameter;
	}
	
	
}
