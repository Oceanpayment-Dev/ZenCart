<?php
/**
 * @package languageDefines
 * @copyright Copyright 2003-2009 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: checkout_failure.php 14198 2009-08-18 22:32:11Z drbyte $
 */

define('NAVBAR_TITLE_1', 'Checkout');
define('NAVBAR_TITLE_2', 'Failure - Oops! We\'re sorry');
define('HEADING_TITLE', 'Oops! We\'re sorry!');
define('TEXT_SEE_ORDERS', 'You can view your order history by going to the <a href="' . zen_href_link(FILENAME_ACCOUNT, '', 'SSL') . '" name="linkMyAccount">My Account</a> page and by clicking on "View All Orders".');
define('TEXT_CONTACT_STORE_OWNER', 'Please direct any questions you have to <a href="' . zen_href_link(FILENAME_CONTACT_US) . '" name="linkContactUs">customer service</a>.');


define('TEXT_YOUR_ORDER_NUMBER', '<strong>Your Order Number is:</strong> ');
define('TEXT_CHECKOUT_LOGOFF_GUEST', 'NOTE: To complete your order, a temporary account was created. You may close this account by clicking Log Off. Clicking Log Off also ensures that your receipt and purchase information is not visible to the next person using this computer. If you wish to continue shopping, feel free! You may log off at anytime using the link at the top of the page.');
define('TEXT_CHECKOUT_LOGOFF_CUSTOMER', 'Thank you for shopping. Please click the Log Off link to ensure that your receipt and purchase information is not visible to the next person using this computer.');

define('LOGO_SRC', DIR_WS_MODULES . 'payment/oceanpayment_creditcard/failure.png');
define('LOGO', '<img src="' . LOGO_SRC . '" width="110px"/>');


//钱海信用卡响应代码解决方案,更新日期2015-04-12
define('CODE_ACTION_TEXT_1', '1. Try to Pay again, your card issuer may accept your payment.<br>2. Call the 800 number on the back of the card, ask your card issuer accept your payment, then pay again.<br>3. Change another card to pay.');
define('CODE_ACTION_TEXT_2', 'Contact the merchant website to confirm the transactions.');
define('CODE_ACTION_TEXT_3', 'Contact the merchant website to confirm the transaction amount.');
define('CODE_ACTION_TEXT_4', '1. Please confirm the card number is right.<br>2. Change another card to pay.');
define('CODE_ACTION_TEXT_5', '1. Please confirm the card has enough money or use another card.<br>2. Change another card to pay.');
define('CODE_ACTION_TEXT_6', 'Please confirm the card period of use.');
define('CODE_ACTION_TEXT_7', 'Please input the right PIN.');
define('CODE_ACTION_TEXT_8', 'Please input the right 3-digital CVV2/CSC.');
define('CODE_ACTION_TEXT_9', '1. Please contact your card issuer to fix the problem.<br>2. Change another card to pay.');
define('CODE_ACTION_TEXT_10', '1. Please call your card issuer to confirm your account is valid.<br>2. Change another card to pay.');
define('CODE_ACTION_TEXT_11', '1. Contact the merchant website to confirm the payment result.<br>2. Try to pay again.');
define('CODE_ACTION_TEXT_12', 'Please contact the merchant website.');
define('CODE_ACTION_TEXT_13', 'Change another card to pay.');
define('CODE_ACTION_TEXT_14', 'Please contact the payment service company.');
define('CODE_ACTION_TEXT_15', 'Try to pay again.');
define('CODE_ACTION_TEXT_16', 'Cannot Start 3D Authorized Service.');
define('CODE_ACTION_TEXT_17', 'Input right 3D secure code or change another card to pay.');
define('CODE_ACTION_TEXT_18', 'Fail to process the 3D transaction techonolly.');
define('CODE_ACTION_TEXT_19', '1. Try again, finish the transaction correctly.<br>2. Contact website with screenshot if it has error.');
define('CODE_ACTION_TEXT_20', 'Pay again or change another bank account to pay.');
define('CODE_ACTION_TEXT_21', 'Try again later or to  choose different payment scheme or bank account.');
define('CODE_ACTION_TEXT_22', 'Try to pay again and finish the payment.');








