<?php
/**
 * checkout_failure header_php.php
 *
 * @package page
 * @copyright Copyright 2003-2007 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: header_php.php 6373 2007-05-25 20:22:34Z drbyte $
 */


// if the customer is not logged on, redirect them to the shopping cart page
if (!$_SESSION['customer_id']) {
  zen_redirect(zen_href_link(FILENAME_TIME_OUT));
}


require(DIR_WS_MODULES . zen_get_module_directory('require_languages.php'));
$breadcrumb->add(NAVBAR_TITLE_1);
$breadcrumb->add(NAVBAR_TITLE_2);

// find out the last order number generated for this customer account
$orders_query = "SELECT * FROM " . TABLE_ORDERS . "
                 WHERE customers_id = :customersID
                 ORDER BY date_purchased DESC LIMIT 1";
$orders_query = $db->bindVars($orders_query, ':customersID', $_SESSION['customer_id'], 'integer');
$orders = $db->Execute($orders_query);
$orders_id = $orders->fields['orders_id'];

// use order-id generated by the actual order process
// this uses the SESSION orders_id, or if doesn't exist, grabs most recent order # for this cust (needed for paypal et al).
// Needs reworking in v1.4 for checkout-rewrite
$zv_orders_id = (isset($_SESSION['order_number_created']) && $_SESSION['order_number_created'] >= 1) ? $_SESSION['order_number_created'] : $orders_id;
$orders_id = $zv_orders_id;
$order_summary = $_SESSION['order_summary'];
unset($_SESSION['order_summary']);
unset($_SESSION['order_number_created']);

$payment_details = isset($_SESSION['payment_details']) ? $_SESSION['payment_details'] : '';
$payment_solutions = isset($_SESSION['payment_solutions']) ? $_SESSION['payment_solutions'] : '';


// This should be last line of the script:
$zco_notifier->notify('NOTIFY_HEADER_END_CHECKOUT_SUCCESS');
?>