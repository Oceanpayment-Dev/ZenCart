<?php


  define('MODULE_PAYMENT_OCEANPAYMENT_CREDITCARD_TEXT_ADMIN_TITLE', 'Oceanpayment Credit Card Payment Gateway');
  define('MODULE_PAYMENT_OCEANPAYMENT_CREDITCARD_TEXT_CATALOG_TITLE', 'Oceanpayment Credit Card Payment');
  define('MODULE_PAYMENT_OCEANPAYMENT_CREDITCARD_TEXT_DESCRIPTION', 'Oceanpayment Credit Card Payment');

  define('MODULE_PAYMENT_OCEANPAYMENT_CREDITCARD_MARK_BUTTON_IMG', DIR_WS_MODULES . '/payment/oceanpayment_creditcard/op_creditcard.png');
  define('MODULE_PAYMENT_OCEANPAYMENT_CREDITCARD_MARK_BUTTON_ALT', '');
  define('MODULE_PAYMENT_OCEANPAYMENT_CREDITCARD_ACCEPTANCE_MARK_TEXT', '');

  define('MODULE_PAYMENT_OCEANPAYMENT_CREDITCARD_TEXT_CATALOG_LOGO', '<img src="' . MODULE_PAYMENT_OCEANPAYMENT_CREDITCARD_MARK_BUTTON_IMG . '" alt="' . MODULE_PAYMENT_OCEANPAYMENT_CREDITCARD_MARK_BUTTON_ALT . '" title="' . MODULE_PAYMENT_OCEANPAYMENT_CREDITCARD_MARK_BUTTON_ALT . '" /> &nbsp;' .  '<span class="smallText">' . MODULE_PAYMENT_OCEANPAYMENT_CREDITCARD_ACCEPTANCE_MARK_TEXT . '</span>');

  define('MODULE_PAYMENT_OCEANPAYMENT_CREDITCARD_TEXT_CONFIG_1_1', 'Enable Oceanpayment Module');  
  define('MODULE_PAYMENT_OCEANPAYMENT_CREDITCARD_TEXT_CONFIG_1_2', 'Do you want to accept Oceanpayment Credit Card payments?');  
  define('MODULE_PAYMENT_OCEANPAYMENT_CREDITCARD_TEXT_CONFIG_2_1', 'Enable embedded mode');
  define('MODULE_PAYMENT_OCEANPAYMENT_CREDITCARD_TEXT_CONFIG_2_2', 'Do you want to enable embedded mode?');  
  define('MODULE_PAYMENT_OCEANPAYMENT_CREDITCARD_TEXT_CONFIG_3_1', 'Oceanpayment account ID');  
  define('MODULE_PAYMENT_OCEANPAYMENT_CREDITCARD_TEXT_CONFIG_3_2', 'Oceanpayment account ID');  
  define('MODULE_PAYMENT_OCEANPAYMENT_CREDITCARD_TEXT_CONFIG_4_1', 'Oceanpayment terminal');
  define('MODULE_PAYMENT_OCEANPAYMENT_CREDITCARD_TEXT_CONFIG_4_2', 'Oceanpayment terminal');   
  define('MODULE_PAYMENT_OCEANPAYMENT_CREDITCARD_TEXT_CONFIG_5_1', 'Oceanpayment secureCode');  
  define('MODULE_PAYMENT_OCEANPAYMENT_CREDITCARD_TEXT_CONFIG_5_2', 'Oceanpayment secureCode');
  define('MODULE_PAYMENT_OCEANPAYMENT_CREDITCARD_TEXT_CONFIG_6_1', 'Payment Zone');
  define('MODULE_PAYMENT_OCEANPAYMENT_CREDITCARD_TEXT_CONFIG_6_2', 'If a zone is selected, only enable this payment method for that zone.');
  define('MODULE_PAYMENT_OCEANPAYMENT_CREDITCARD_TEXT_CONFIG_7_1', 'Set Pending Notification Status');
  define('MODULE_PAYMENT_OCEANPAYMENT_CREDITCARD_TEXT_CONFIG_7_2', 'Set the status of orders made with this payment module to this value<br />(Processing recommended)');
  define('MODULE_PAYMENT_OCEANPAYMENT_CREDITCARD_TEXT_CONFIG_8_1', 'Successfully set the order status');
  define('MODULE_PAYMENT_OCEANPAYMENT_CREDITCARD_TEXT_CONFIG_8_2', 'Set the status of orders made with this payment module to this value');
  define('MODULE_PAYMENT_OCEANPAYMENT_CREDITCARD_TEXT_CONFIG_9_1', 'Failure to set the order status');
  define('MODULE_PAYMENT_OCEANPAYMENT_CREDITCARD_TEXT_CONFIG_9_2', 'Set the status of orders made with this payment module to this value');
  define('MODULE_PAYMENT_OCEANPAYMENT_CREDITCARD_TEXT_CONFIG_10_1', 'Pending set the order status');
  define('MODULE_PAYMENT_OCEANPAYMENT_CREDITCARD_TEXT_CONFIG_10_2', 'Set the status of orders made with this payment module to this value');
  define('MODULE_PAYMENT_OCEANPAYMENT_CREDITCARD_TEXT_CONFIG_11_1', 'Sort order of display');  
  define('MODULE_PAYMENT_OCEANPAYMENT_CREDITCARD_TEXT_CONFIG_11_2', 'Sort order of display. Lowest is displayed first.');  
  define('MODULE_PAYMENT_OCEANPAYMENT_CREDITCARD_TEXT_CONFIG_12_1', 'Oceanpayment Transaction URL');  
  define('MODULE_PAYMENT_OCEANPAYMENT_CREDITCARD_TEXT_CONFIG_12_2', 'Oceanpayment Transaction URL');  
  define('MODULE_PAYMENT_OCEANPAYMENT_CREDITCARD_TEXT_CONFIG_13_1', 'Set iframe height');
  define('MODULE_PAYMENT_OCEANPAYMENT_CREDITCARD_TEXT_CONFIG_13_2', 'Set iframe height');
  define('MODULE_PAYMENT_OCEANPAYMENT_CREDITCARD_TEXT_CONFIG_14_1', 'Enable 3D Secure mode');
  define('MODULE_PAYMENT_OCEANPAYMENT_CREDITCARD_TEXT_CONFIG_14_2', 'Enable 3D Secure mode');
  define('MODULE_PAYMENT_OCEANPAYMENT_CREDITCARD_TEXT_CONFIG_15_1', 'Oceanpayment 3D Secure Terminal');
  define('MODULE_PAYMENT_OCEANPAYMENT_CREDITCARD_TEXT_CONFIG_15_2', 'Oceanpayment 3D Secure Terminal');
  define('MODULE_PAYMENT_OCEANPAYMENT_CREDITCARD_TEXT_CONFIG_16_1', 'Oceanpayment 3D Secure SecureCode');
  define('MODULE_PAYMENT_OCEANPAYMENT_CREDITCARD_TEXT_CONFIG_16_2', 'Oceanpayment 3D Secure SecureCode');
  define('MODULE_PAYMENT_OCEANPAYMENT_CREDITCARD_TEXT_CONFIG_17_1', '3D Secure Currency');
  define('MODULE_PAYMENT_OCEANPAYMENT_CREDITCARD_TEXT_CONFIG_17_2', 'such as: USD;EUR;AUD;');
  define('MODULE_PAYMENT_OCEANPAYMENT_CREDITCARD_TEXT_CONFIG_18_1', '3D Secure Amount');
  define('MODULE_PAYMENT_OCEANPAYMENT_CREDITCARD_TEXT_CONFIG_18_2', 'such as: 200;300;150;');
  
  
  //邮件文字内容
  define('NAVBAR_TITLE', 'Online Payment');
  define('HEADING_TITLE', 'Online Payment');
  define('TEXT_INFORMATION', 'online payment information goes here.');
  
  define('EMAIL_TEXT_SUBJECT', 'Order Confirmation');
  define('EMAIL_TEXT_HEADER', 'Order Confirmation');
  define('EMAIL_TEXT_FROM',' from ');  //added to the EMAIL_TEXT_HEADER, above on text-only emails
  define('EMAIL_THANKS_FOR_SHOPPING','Thanks for shopping with us today!');
  define('EMAIL_DETAILS_FOLLOW','The following are the details of your order.');
  define('EMAIL_TEXT_ORDER_NUMBER', 'Order Number:');
  define('EMAIL_TEXT_INVOICE_URL', 'Detailed Invoice:');
  define('EMAIL_TEXT_INVOICE_URL_CLICK', 'Click here for a Detailed Invoice');
  define('EMAIL_TEXT_DATE_ORDERED', 'Date Ordered:');
  define('EMAIL_TEXT_PRODUCTS', 'Products');
  define('EMAIL_TEXT_SUBTOTAL', 'Sub-Total:');
  define('EMAIL_TEXT_TAX', 'Tax:        ');
  define('EMAIL_TEXT_SHIPPING', 'Shipping: ');
  define('EMAIL_TEXT_TOTAL', 'Total:    ');
  define('EMAIL_TEXT_DELIVERY_ADDRESS', 'Delivery Address');
  define('EMAIL_TEXT_BILLING_ADDRESS', 'Billing Address');
  define('EMAIL_TEXT_PAYMENT_METHOD', 'Payment Method');
  
  define('EMAIL_SEPARATOR', '------------------------------------------------------');
  define('TEXT_EMAIL_VIA', 'via');
  
  // suggest not using # vs No as some spamm protection block emails with these subjects
  define('EMAIL_ORDER_NUMBER_SUBJECT', ' No: ');
  define('HEADING_ADDRESS_INFORMATION','Address Information');
  define('HEADING_SHIPPING_METHOD','Shipping Method');
  
?>