<?php
/* --------------------------------------------------------------
   orders.php 2010-08-17 gm
   Gambio GmbH
   http://www.gambio.de
   Copyright (c) 2010 Gambio GmbH
   Released under the GNU General Public License (Version 2)
   [http://www.gnu.org/licenses/gpl-2.0.html]
   --------------------------------------------------------------


   based on:
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(orders.php,v 1.109 2003/05/28); www.oscommerce.com
   (c) 2003	 nextcommerce (orders.php,v 1.19 2003/08/24); www.nextcommerce.org
   (c) 2003 XT-Commerce - community made shopping http://www.xt-commerce.com ($Id: orders.php 1189 2005-08-28 15:27:00Z hhgag $)

   Released under the GNU General Public License
   --------------------------------------------------------------
   Third Party contribution:
   OSC German Banktransfer v0.85a       	Autor:	Dominik Guder <osc@guder.org>
   Customers Status v3.x  (c) 2002-2003 Copyright Elari elari@free.fr | www.unlockgsm.com/dload-osc/ | CVS : http://cvs.sourceforge.net/cgi-bin/viewcvs.cgi/elari/?sortby=date#dirlist

   credit card encryption functions for the catalog module
   BMC 2003 for the CC CVV Module

   Released under the GNU General Public License
   --------------------------------------------------------------*/

require ('includes/application_top.php');
require_once (DIR_FS_CATALOG.DIR_WS_CLASSES.'class.phpmailer.php');
require_once (DIR_FS_INC.'xtc_php_mail.inc.php');
require_once (DIR_FS_INC.'xtc_add_tax.inc.php');
require_once (DIR_FS_INC.'changedataout.inc.php');
require_once (DIR_FS_INC.'xtc_validate_vatid_status.inc.php');
require_once (DIR_FS_INC.'xtc_get_attributes_model.inc.php');
require_once (DIR_FS_CATALOG . 'gm/inc/gm_prepare_number.inc.php');

// initiate template engine for mail
$smarty = new Smarty;
// bof gm
$gm_logo_mail = MainFactory::create_object('GMLogoManager', array("gm_logo_mail"));
if($gm_logo_mail->logo_use == '1') {
	$smarty->assign('gm_logo_mail', $gm_logo_mail->get_logo());
}
require (DIR_WS_CLASSES.'currencies.php');
$currencies = new currencies();


if ((($_GET['action'] == 'edit') || ($_GET['action'] == 'update_order')) && ($_GET['oID'])) {
	$oID = xtc_db_prepare_input($_GET['oID']);

	$orders_query = xtc_db_query("select orders_id from ".TABLE_ORDERS." where orders_id = '".xtc_db_input($oID)."'");
	$order_exists = true;
	if (!xtc_db_num_rows($orders_query)) {
		$order_exists = false;
		$messageStack->add(sprintf(ERROR_ORDER_DOES_NOT_EXIST, $oID), 'error');
	}
}

require (DIR_WS_CLASSES.'order.php');
if ((($_GET['action'] == 'edit') || ($_GET['action'] == 'update_order')) && ($order_exists)) {
	$order = new order($oID);
}

  $lang_query = xtc_db_query("select languages_id from " . TABLE_LANGUAGES . " where directory = '" . $order->info['language'] . "'");
  $lang = xtc_db_fetch_array($lang_query);
  $lang=$lang['languages_id'];

if (!isset($lang)) $lang=$_SESSION['languages_id'];
$orders_statuses = array ();
$orders_status_array = array ();
$orders_status_query = xtc_db_query("select orders_status_id, orders_status_name from ".TABLE_ORDERS_STATUS." where language_id = '".$lang."'");
while ($orders_status = xtc_db_fetch_array($orders_status_query)) {
	$orders_statuses[] = array ('id' => $orders_status['orders_status_id'], 'text' => $orders_status['orders_status_name']);
	$orders_status_array[$orders_status['orders_status_id']] = $orders_status['orders_status_name'];
}
switch ($_GET['action']) {

	// bof gm
	case 'gm_multi_status':

			$order_updated = false;
			$gm_status = xtc_db_prepare_input($_POST['gm_status']);
			$gm_comments = xtc_db_prepare_input($_POST['gm_comments']);

			for($i = 0; $i < count($_POST['gm_multi_status']); $i++) {
				$oID = xtc_db_prepare_input($_POST['gm_multi_status'][$i]);

				$check_status_query = xtc_db_query("
													SELECT
														customers_name,
														customers_email_address,
														orders_status,
														language,
														date_purchased
													FROM " .
														TABLE_ORDERS . "
													WHERE
														orders_id = '" . xtc_db_input($oID) . "'
													");

				$check_status = xtc_db_fetch_array($check_status_query);

				if ($check_status['orders_status'] != $gm_status && $check_status['orders_status'] != gm_get_conf('GM_ORDER_STATUS_CANCEL_ID') || $comments != '') {

					if($gm_status == gm_get_conf('GM_ORDER_STATUS_CANCEL_ID')) {
						$gm_update = "gm_cancel_date = now(),";
					}

					xtc_db_query("
								UPDATE " .
									TABLE_ORDERS . "
								SET
									" . $gm_update . "
									orders_status = '" . xtc_db_input($gm_status)."',
									last_modified = now()
								WHERE
									orders_id = '" . xtc_db_input($oID) . "'
								");


					// cancel order
					if(xtc_db_input($gm_status) == gm_get_conf('GM_ORDER_STATUS_CANCEL_ID')) {
						xtc_remove_order(xtc_db_input($oID), true, true);
					}

					$customer_notified = '0';
					if($_POST['gm_notify'] == 'on') {
						$notify_comments = '';
						if ($_POST['gm_notify_comments'] == 'on') {
							$notify_comments = $gm_comments;
						} else {
							$notify_comments = '';
						}

						// assign language to template for caching
						$smarty->assign('language', $_SESSION['language']);
						$smarty->caching = false;

						// set dirs manual
						$smarty->template_dir = DIR_FS_CATALOG.'templates';
						$smarty->compile_dir = DIR_FS_CATALOG.'templates_c';
						$smarty->config_dir = DIR_FS_CATALOG.'lang';

						$smarty->assign('tpl_path', 'templates/'.CURRENT_TEMPLATE.'/');
						$smarty->assign('logo_path', HTTP_SERVER.DIR_WS_CATALOG.'templates/'.CURRENT_TEMPLATE.'/img/');

						$smarty->assign('NAME', $check_status['customers_name']);
						$smarty->assign('ORDER_NR', $oID);
						$smarty->assign('ORDER_LINK', xtc_catalog_href_link(FILENAME_CATALOG_ACCOUNT_HISTORY_INFO, 'order_id='.$oID, 'SSL'));
						$smarty->assign('ORDER_DATE', xtc_date_long($check_status['date_purchased']));
						$smarty->assign('ORDER_STATUS', $orders_status_array[$gm_status]);

						$smarty->assign('NOTIFY_COMMENTS', nl2br($notify_comments));
						$html_mail = $smarty->fetch(CURRENT_TEMPLATE.'/admin/mail/'.$check_status['language'].'/change_order_mail.html');
						$smarty->assign('NOTIFY_COMMENTS', $notify_comments);
						$txt_mail = $smarty->fetch(CURRENT_TEMPLATE.'/admin/mail/'.$check_status['language'].'/change_order_mail.txt');

						// BOF GM_MOD
						if($_SESSION['language'] == 'german') xtc_php_mail(EMAIL_BILLING_ADDRESS, EMAIL_BILLING_NAME, $check_status['customers_email_address'], $check_status['customers_name'], '', EMAIL_BILLING_REPLY_ADDRESS, EMAIL_BILLING_REPLY_ADDRESS_NAME, '', '', 'Ihre Bestellung '.$oID.', '.xtc_date_long($check_status['date_purchased']).', '.$check_status['customers_name'], $html_mail, $txt_mail);

						else xtc_php_mail(EMAIL_BILLING_ADDRESS, EMAIL_BILLING_NAME, $check_status['customers_email_address'], $check_status['customers_name'], '', EMAIL_BILLING_REPLY_ADDRESS, EMAIL_BILLING_REPLY_ADDRESS_NAME, '', '', 'Your Order '.$oID.', '.xtc_date_long($check_status['date_purchased']).', '.$check_status['customers_name'], $html_mail, $txt_mail);
						// EOF GM_MOD
						$customer_notified = '1';
					}

					xtc_db_query("INSERT INTO " . TABLE_ORDERS_STATUS_HISTORY . " (orders_id, orders_status_id, date_added, customer_notified, comments) values ('".xtc_db_input($oID)."', '".xtc_db_input($gm_status)."', now(), '".$customer_notified."', '".xtc_db_input($gm_comments)."')");

					$order_updated = true;
				}
			}

			if ($order_updated) {
				$messageStack->add_session(SUCCESS_ORDER_UPDATED, 'success');
			} else {
				$messageStack->add_session(WARNING_ORDER_NOT_UPDATED, 'warning');
			}

			xtc_redirect(xtc_href_link(FILENAME_ORDERS, xtc_get_all_get_params(array ('action')).'action=edit'));

	break;

	case 'update_order' :
		$oID = xtc_db_prepare_input($_GET['oID']);
		$status = xtc_db_prepare_input($_POST['status']);
		$comments = xtc_db_prepare_input($_POST['comments']);
		//	$order = new order($oID);
		$order_updated = false;
		$check_status_query = xtc_db_query("select customers_name, customers_email_address, orders_status, date_purchased from ".TABLE_ORDERS." where orders_id = '".xtc_db_input($oID)."'");
		$check_status = xtc_db_fetch_array($check_status_query);

		if (($check_status['orders_status'] != $status && $check_status['orders_status'] != gm_get_conf('GM_ORDER_STATUS_CANCEL_ID')) || $comments != '') {

			if(xtc_db_input($status) == gm_get_conf('GM_ORDER_STATUS_CANCEL_ID')) {
				$gm_update = "gm_cancel_date = now(),";
			}

			xtc_db_query("
							UPDATE " .
								TABLE_ORDERS . "
							SET
								" . $gm_update . "
								orders_status = '".xtc_db_input($status)."',
								last_modified = now()
							WHERE
								orders_id = '".xtc_db_input($oID)."'
							");

			$customer_notified = '0';
			if($_POST['notify'] == 'on') {
				$notify_comments = '';
				if ($_POST['notify_comments'] == 'on') {
					//$notify_comments = sprintf(EMAIL_TEXT_COMMENTS_UPDATE, $comments)."\n\n";
					$notify_comments = $comments;
				} else {
					$notify_comments = '';
				}

				// assign language to template for caching
				$smarty->assign('language', $_SESSION['language']);
				$smarty->caching = false;

				// set dirs manual
				$smarty->template_dir = DIR_FS_CATALOG.'templates';
				$smarty->compile_dir = DIR_FS_CATALOG.'templates_c';
				$smarty->config_dir = DIR_FS_CATALOG.'lang';

				$smarty->assign('tpl_path', 'templates/'.CURRENT_TEMPLATE.'/');
				$smarty->assign('logo_path', HTTP_SERVER.DIR_WS_CATALOG.'templates/'.CURRENT_TEMPLATE.'/img/');

				$smarty->assign('NAME', $check_status['customers_name']);
				$smarty->assign('ORDER_NR', $oID);
				$smarty->assign('ORDER_LINK', xtc_catalog_href_link(FILENAME_CATALOG_ACCOUNT_HISTORY_INFO, 'order_id='.$oID, 'SSL'));
				$smarty->assign('ORDER_DATE', xtc_date_long($check_status['date_purchased']));

				$smarty->assign('ORDER_STATUS', $orders_status_array[$status]);

				$smarty->assign('NOTIFY_COMMENTS', nl2br($notify_comments));
				$html_mail = $smarty->fetch(CURRENT_TEMPLATE.'/admin/mail/'.$order->info['language'].'/change_order_mail.html');
				$smarty->assign('NOTIFY_COMMENTS', $notify_comments);
				$txt_mail = $smarty->fetch(CURRENT_TEMPLATE.'/admin/mail/'.$order->info['language'].'/change_order_mail.txt');

				// BOF GM_MOD

				if($_SESSION['language'] == 'german') xtc_php_mail(EMAIL_BILLING_ADDRESS, EMAIL_BILLING_NAME, $check_status['customers_email_address'], $check_status['customers_name'], '', EMAIL_BILLING_REPLY_ADDRESS, EMAIL_BILLING_REPLY_ADDRESS_NAME, '', '', 'Ihre Bestellung '.$oID.', '.xtc_date_long($check_status['date_purchased']).', '.$check_status['customers_name'], $html_mail, $txt_mail);



				else xtc_php_mail(EMAIL_BILLING_ADDRESS, EMAIL_BILLING_NAME, $check_status['customers_email_address'], $check_status['customers_name'], '', EMAIL_BILLING_REPLY_ADDRESS, EMAIL_BILLING_REPLY_ADDRESS_NAME, '', '', 'Your Order '.$oID.', '.xtc_date_long($check_status['date_purchased']).', '.$check_status['customers_name'], $html_mail, $txt_mail);

				// EOF GM_MOD



				$customer_notified = '1';
			}

			xtc_db_query("insert into " . TABLE_ORDERS_STATUS_HISTORY . " (orders_id, orders_status_id, date_added, customer_notified, comments) values ('".xtc_db_input($oID)."', '".xtc_db_input($status)."', now(), '".$customer_notified."', '".xtc_db_input($comments)."')");

			$order_updated = true;
		}

		if ($order_updated) {
			$messageStack->add_session(SUCCESS_ORDER_UPDATED, 'success');
		} else {
			$messageStack->add_session(WARNING_ORDER_NOT_UPDATED, 'warning');
		}

		xtc_redirect(xtc_href_link(FILENAME_ORDERS, xtc_get_all_get_params(array ('action')).'action=edit'));
		break;
	case 'resendordermail':
		break;
	case 'deleteconfirm' :

		$oID = xtc_db_prepare_input($_GET['oID']);

		xtc_remove_order($oID, $_POST['restock']);

		xtc_redirect(xtc_href_link(FILENAME_ORDERS, xtc_get_all_get_params(array ('oID', 'action'))));
		break;
		// BMC Delete CC info Start
		// Remove CVV Number
	case 'deleteccinfo' :
		$oID = xtc_db_prepare_input($_GET['oID']);

		xtc_db_query("update ".TABLE_ORDERS." set cc_cvv = null where orders_id = '".xtc_db_input($oID)."'");
		xtc_db_query("update ".TABLE_ORDERS." set cc_number = '0000000000000000' where orders_id = '".xtc_db_input($oID)."'");
		xtc_db_query("update ".TABLE_ORDERS." set cc_expires = null where orders_id = '".xtc_db_input($oID)."'");
		xtc_db_query("update ".TABLE_ORDERS." set cc_start = null where orders_id = '".xtc_db_input($oID)."'");
		xtc_db_query("update ".TABLE_ORDERS." set cc_issue = null where orders_id = '".xtc_db_input($oID)."'");

		xtc_redirect(xtc_href_link(FILENAME_ORDERS, 'oID='.$_GET['oID'].'&action=edit'));
		break;

	case 'afterbuy_send' :
		$oID = xtc_db_prepare_input($_GET['oID']);
		require_once (DIR_FS_CATALOG.'includes/classes/afterbuy.php');
		$aBUY = new xtc_afterbuy_functions($oID);
		if ($aBUY->order_send())
			$aBUY->process_order();

		break;

		// BMC Delete CC Info End
}
?>

<?php 
// BOF GM_MOD GX-Customizer
if($_GET['action'] == 'edit')
{
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php echo HTML_PARAMS; ?>>
<?php 
}
else
{
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<?php 
}
// EOF GM_MOD GX-Customizer
?>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $_SESSION['language_charset']; ?>">
		<title><?php echo TITLE; ?></title>
		<script type="text/javascript">
			var oID = "<?php echo $_GET['oID']; ?>";
		</script>
		<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
		<?php 
		// BOF GM_MOD GX-Customizer:
		include_once('../gm/modules/gm_gprint_admin_orders_css.php');
		?>
	</head>
	<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
		<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
		<?php 
		// BOF GM_MOD GX-Customizer:
		include_once('../gm/modules/gm_gprint_admin_orders_js.php');
		?>
		<table border="0" width="100%" cellspacing="2" cellpadding="2">
			<tr>
				<td class="columnLeft2" width="<?php echo BOX_WIDTH; ?>" valign="top">
					<table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="1" cellpadding="1" class="columnLeft">
						<?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
					</table>
				</td>
				<!-- body_text //-->

			<?php if (($_GET['action'] == 'edit') && ($order_exists)) { ?>

				<td class="boxCenter" width="100%" valign="top">
					<table border="0" width="100%" cellspacing="0" cellpadding="0">
						<tr>
							<td>
								<div class="pageHeading" style="background-image:url(images/gm_icons/kunden.png)">
								<div style="float:left">
									 <?php /* BOF GM_MOD */ echo GM_ORDERS_NUMBER . $oID . ' - ' . xtc_date_short($order->info['date_purchased']) . ' ' . date("H:i", strtotime($order->info['date_purchased'])) . GM_ORDERS_EDIT_CLOCK; /* EOF GM_MOD */?>
								</div>
								<div>
                                                                    <!-- Paymill begin -->
                                                                    <?php if ($order->info['payment_method'] == 'paymill_cc' || $order->info['payment_method'] == 'paymill_elv') { ?>
                                                                       <?php include(dirname(__FILE__) . '/../lang/' . $_SESSION['language'] . '/modules/payment/' . $order->info['payment_method'] . '.php'); ?>
                                                                       <a class="button float_right" href="<?php echo xtc_href_link('paymill_refund.php','oID=' . $_GET['oID']); ?>"><?php echo PAYMILL_REFUND_BUTTON_TEXT; ?></a>
                                                                    <?php } ?>
                                                                    <!-- Paymill end -->
									<?php echo '<a class="button float_right" href="' . xtc_href_link(FILENAME_ORDERS, xtc_get_all_get_params(array('action'))) . '">' . BUTTON_BACK . '</a>'; ?>
									<a class="button float_right" href="<?php echo xtc_href_link(FILENAME_ORDERS_EDIT, 'oID='.$_GET['oID'].'&cID=' . $order->customer['ID']);?>"><?php echo BUTTON_EDIT ?></a>
								</div>
							</td>
						</tr>
					</table>
					<br />
<!-- ORDERS - OVERVIEW -->
					<table border="0" width="100%" cellspacing="0" cellpadding="0" class="pdf_menu">
						<tr>
							<td width="120" class="dataTableHeadingContent" style="border-right: 0px;">
								<?php echo HEADING_TITLE; ?>
							</td>
						</tr>
					</table>
					<table border="0" width="100%" cellspacing="0" cellpadding="2" class="gm_border dataTableRow">
						<tr>
							<td width="80" class="main gm_strong" valign="top">
								<?php echo ENTRY_CUSTOMER; ?>
							</td>
							<td class="main" valign="top">
								<?php
									// BOF GM_MOD
									$gm_get_gender = xtc_db_query("SELECT customers_gender
																									FROM customers
																									WHERE customers_id = '" . $order->customer['ID'] . "'");
									if(xtc_db_num_rows($gm_get_gender) == 1){
										$row = xtc_db_fetch_array($gm_get_gender);
										if($row['customers_gender'] == 'm') echo MALE . '<br />';
										elseif($row['customers_gender'] == 'f') echo FEMALE . '<br />';
									}
									// EOF GM_MOD
								?>
								<?php echo xtc_address_format($order->customer['format_id'], $order->customer, 1, '', '<br />'); ?>
							</td>
							<td width="80" class="main gm_strong" valign="top">
								<?php echo ENTRY_SHIPPING_ADDRESS; ?>
							</td>
							<td class="main" valign="top">
								<?php echo xtc_address_format($order->delivery['format_id'], $order->delivery, 1, '', '<br />'); ?>
							</td>
							<td width="80" class="main gm_strong" valign="top">
								<?php echo ENTRY_BILLING_ADDRESS; ?>
							</td>
							<td class="main" valign="top">
								<?php echo xtc_address_format($order->billing['format_id'], $order->billing, 1, '', '<br />'); ?>
							</td>
						</tr>

						<tr><td colspan="6" class="main" valign="top">&nbsp;</td></tr>

						<?php if ($order->customer['csID']!='') { ?>
						<tr>
							<td width="80" class="main gm_strong" valign="top">
								<?php echo TITLE_CUSTOMER_ID; ?>
							</td>
							<td colspan="5" class="main" valign="top">
								<?php echo $order->customer['csID']; ?>
							</td>
						</tr>
						<?php } ?>

						<?php if ($order->customer['telephone']!='') { ?>
						<tr>
							<td width="80" class="main gm_strong" valign="top">
								<?php echo ENTRY_TELEPHONE; ?>
							</td>
							<td colspan="5" class="main" valign="top">
								<?php echo $order->customer['telephone']; ?>
							</td>
						</tr>
						<?php } ?>

						<tr>
							<td width="80" class="main gm_strong" valign="top">
								<?php echo GM_MAIL; ?>
							</td>
							<td colspan="5" class="main" valign="top">
								<?php echo '<a href="mailto:' . $order->customer['email_address'] . '"><u>' . $order->customer['email_address'] . '</u></a>'; ?>
							</td>
						</tr>

						<?php if ($order->customer['vat_id']!='') { ?>
						<tr>
							<td width="80" class="main gm_strong" valign="top">
								<?php echo ENTRY_CUSTOMERS_VAT_ID; ?>
							</td>
							<td colspan="5" class="main" valign="top">
								<?php echo $order->customer['vat_id']; ?>
							</td>
						</tr>
						<?php } ?>

						<?php if ( $order->customer['cIP']!='') { ?>
						<tr>
							<td width="80" class="main gm_strong" valign="top">
								<?php echo IP; ?>
							</td>
							<td colspan="5" class="main" valign="top">
								<?php echo $order->customer['cIP']; ?>
							</td>
						</tr>
						<?php } ?>

						<tr>
							<td width="80" class="main gm_strong" valign="top">
								<?php echo ENTRY_LANGUAGE; ?>
							</td>
							<td colspan="5" class="main" valign="top">
								<?php echo $order->info['language']; ?>
							</td>
						</tr>

						<tr>
							<td width="80" class="main gm_strong" valign="top">
								<?php echo ENTRY_PAYMENT_METHOD; ?>
							</td>
							<td colspan="5" class="main" valign="top">
								<?php echo $order->info['payment_method']; ?>
							</td>
						</tr>

						<?php
						$memo_query = xtc_db_query("SELECT count(*) as count FROM ".TABLE_CUSTOMERS_MEMO." where customers_id='".$order->customer['ID']."'");
						$memo_count = xtc_db_fetch_array($memo_query);
						?>

						<tr>
							<td width="80" class="main gm_strong" valign="top">
								<?php echo CUSTOMERS_MEMO; ?>
							</td>
							<td colspan="5" class="main" valign="top">
								<?php echo $memo_count['count'].'</b>'; ?>  <span style="cursor:pointer" onClick="javascript:window.open('<?php echo xtc_href_link(FILENAME_POPUP_MEMO,'ID='.$order->customer['ID']); ?>', 'popup', 'scrollbars=yes, width=500, height=500')">(<?php echo DISPLAY_MEMOS; ?>)</span>
							</td>
						</tr>
					</table>

<!-- ORDERS - CC PAYMENT -->
					<?php
						if ((($order->info['cc_type']) || ($order->info['cc_owner']) || ($order->info['cc_number']))) {
					?>

					<table border="0" width="100%" cellspacing="0" cellpadding="0" class="pdf_menu">
						<tr>
							<td class="dataTableHeadingContent" style="border-right: 0px;">
								<?php echo TITLE_CC_INFO; ?>
							</td>
						</tr>
					</table>
					<table border="0" width="100%" cellspacing="0" cellpadding="2" class="gm_border dataTableRow">

					<?php
							// BMC CC Mod Start
							if ($order->info['cc_number'] != '0000000000000000') {
								if (strtolower(CC_ENC) == 'true') {
									$cipher_data = $order->info['cc_number'];
									$order->info['cc_number'] = changedataout($cipher_data, CC_KEYCHAIN);
								}
							}
							// BMC CC Mod End
					?>
						<tr>
							<td width="80" class="main gm_strong" valign="top">
								<?php echo ENTRY_CREDIT_CARD_NUMBER; ?>
							</td>
							<td colspan="5" class="main" valign="top">
								<?php echo $order->info['cc_number']; ?>
							</td>
						</tr>
						<tr>
							<td width="80" class="main gm_strong" valign="top">
								<?php echo ENTRY_CREDIT_CARD_CVV; ?>
							</td>
							<td colspan="5" class="main" valign="top">
								<?php echo $order->info['cc_cvv']; ?>
							</td>
						</tr>
						<tr>
							<td width="80" class="main gm_strong" valign="top">
								<?php echo ENTRY_CREDIT_CARD_EXPIRES; ?>
							</td>
							<td colspan="5" class="main" valign="top">
								<?php echo $order->info['cc_expires']; ?>
							</td>
						</tr></table>
					<?php
						}
     // Start Sofort�berweisung
	if (MODULE_PAYMENT_SOFORTUEBERWEISUNG_DIRECT_STATUS == 'True' ) {
      $sofortueberweisung_check_query = xtc_db_query("SELECT sofortueberweisung_transaktions_details FROM " . TABLE_ORDERS . " WHERE orders_id = '" . xtc_db_input($_GET['oID']). "'");
      if ($sofortueberweisung_check = xtc_db_fetch_array($sofortueberweisung_check_query)) {
        if (!empty($sofortueberweisung_check['sofortueberweisung_transaktions_details'])) {
          $hidden_trigger_post_vars = unserialize($sofortueberweisung_check['sofortueberweisung_transaktions_details']);
	?>
	 	<table border="0" width="100%" cellspacing="0" cellpadding="0" class="pdf_menu">
	 		<tr>
	 			<td class="dataTableHeadingContent" style="border-right: 0px;">
	 				sofort&uuml;berweisung.de
	 			</td>
	 		</tr>
	 	</table>
	 	<table border="0" width="100%" cellspacing="0" cellpadding="2" class="gm_border dataTableRow">
	 		<tr>
	 			<td class="main" valign="top">
	 				<table border="0" cellspacing="0" cellpadding="2">
	 					<tr>
	             			<td>
	             				<table width="100%" border="0" cellspacing="0" cellpadding="2">
	 								<tr>
										<td class="main" valign="top">TranscactionID:</td>
										<td class="main" valign="top"><?php echo (!empty($hidden_trigger_post_vars['post']['transaction']) ? $hidden_trigger_post_vars['post']['transaction'] : $hidden_trigger_post_vars['get']['transaction']); ?></td>
									</tr>
									<tr>
										<td class="main" valign="top">Betrag:</td>
										<td class="main" valign="top"><?php echo (!empty($hidden_trigger_post_vars['post']['amount']) ? $hidden_trigger_post_vars['post']['amount'] . ' ' . $hidden_trigger_post_vars['post']['currency_id'] : $hidden_trigger_post_vars['get']['amount'] . ' ' . $hidden_trigger_post_vars['get']['currency_id']); ?></td>
									</tr>
									<tr>
										<td class="main" valign="top">Verwendungszweck 1:</td>
										<td class="main" valign="top"><?php echo $hidden_trigger_post_vars['post']['reason_1'] ; ?></td>
									</tr>
									<tr>
										<td class="main" valign="top">Verwendungszweck 2:</td>
										<td class="main" valign="top"><?php echo $hidden_trigger_post_vars['post']['reason_2'] ; ?></td>
									</tr>
									<tr>
										<td class="main" valign="top">Sicherheits-Kriterien erf&uuml;llt:</td>
										<td class="main" valign="top"><?php echo  ($hidden_trigger_post_vars['post']['security_criteria'] == 1 ? 'Ja' : 'Nein') ; ?></td>
									</tr>
								</table>
							</td>
						</tr>
						<tr>
							<td>
								<table border="0" cellspacing="0" cellpadding="2">
									<tr>
										<td colspan="2" class="main" valign="top"><b>Absender der &Uuml;berweisung:</b></td>
									</tr>
									<tr>
										<td class="main" valign="top">Inhaber:</td>
										<td class="main"><?php echo $hidden_trigger_post_vars['post']['sender_holder'] ; ?></td>
									</tr>
									<tr>
										<td class="main" valign="top">Konto:</td>
										<td class="main"><?php echo $hidden_trigger_post_vars['post']['sender_account_number'] ; ?></td>
									</tr>
									<tr>
										<td class="main" valign="top">BLZ:</td>
										<td class="main"><?php echo $hidden_trigger_post_vars['post']['sender_bank_code'] ; ?></td>
									</tr>
									<tr>
										<td class="main" valign="top">Bank:</td>
										<td class="main"><?php echo $hidden_trigger_post_vars['post']['sender_bank_name'] ; ?></td>
									</tr>
									<tr>
										<td class="main" valign="top">BIC:</td>
										<td class="main"><?php echo $hidden_trigger_post_vars['post']['sender_bank_bic'] ; ?></td>
									</tr>
									<tr>
										<td class="main" valign="top">IBAN:</td>
										<td class="main"><?php echo $hidden_trigger_post_vars['post']['sender_iban'] ; ?></td>
									</tr>
									<tr>
										<td class="main" valign="top">Land:</td>
										<td class="main"><?php echo $hidden_trigger_post_vars['post']['sender_country_id'] ; ?></td>
									</tr>
								</table>
							</td>
							<td>&nbsp;&nbsp;&nbsp;</td>
							<td>
								<table border="0" cellspacing="0" cellpadding="2">
									<tr>
										<td colspan="2" class="main" valign="top"><b>Empf&auml;nger der &Uuml;berweisung:</b></td>
									</tr>
									<tr>
										<td class="main" valign="top">Inhaber:</td>
										<td class="main"><?php echo $hidden_trigger_post_vars['post']['recipient_holder'] ; ?></td>
									</tr>
									<tr>
										<td class="main" valign="top">Konto:</td>
										<td class="main"><?php echo $hidden_trigger_post_vars['post']['recipient_account_number'] ; ?></td>
									</tr>
									<tr>
										<td class="main" valign="top">BLZ:</td>
										<td class="main"><?php echo $hidden_trigger_post_vars['post']['recipient_bank_code'] ; ?></td>
									</tr>
									<tr>
										<td class="main" valign="top">Bank:</td>
										<td class="main"><?php echo $hidden_trigger_post_vars['post']['recipient_bank_name'] ; ?></td>
									</tr>
									<tr>
										<td class="main" valign="top">BIC:</td>
										<td class="main"><?php echo $hidden_trigger_post_vars['post']['recipient_bank_bic'] ; ?></td>
									</tr>
									<tr>
										<td class="main" valign="top">IBAN:</td>
										<td class="main"><?php echo $hidden_trigger_post_vars['post']['recipient_iban'] ; ?></td>
									</tr>
									<tr>
										<td class="main" valign="top">Land:</td>
										<td class="main"><?php echo $hidden_trigger_post_vars['post']['recipient_country_id'] ; ?></td>
									</tr>
	 							</table>
	 						</td>
	 				    </tr>
	             	</table>
	             </td>
	 		</tr>
	 	</table>
	 <?php
	         }
	       }
	     }

  // End Sofort�berweisung


					// begin modification for banktransfer
					$banktransfer_query = xtc_db_query("select banktransfer_prz, banktransfer_status, banktransfer_owner, banktransfer_number, banktransfer_bankname, banktransfer_blz, banktransfer_fax from banktransfer where orders_id = '".xtc_db_input($_GET['oID'])."'");
					$banktransfer = xtc_db_fetch_array($banktransfer_query);
					if (($banktransfer['banktransfer_bankname']) || ($banktransfer['banktransfer_blz']) || ($banktransfer['banktransfer_number'])) {

					?>

					<table border="0" width="100%" cellspacing="0" cellpadding="0" class="pdf_menu">
						<tr>
							<td class="dataTableHeadingContent" style="border-right: 0px;">
								<?php echo TITLE_BANK_INFO; ?>
							</td>
						</tr>
					</table>
					<table border="0" width="100%" cellspacing="0" cellpadding="2" class="gm_border dataTableRow">
						<tr>
							<td width="80" class="main gm_strong" valign="top">
								<?php echo TEXT_BANK_NAME; ?>
							</td>
							<td colspan="5" class="main" valign="top">
								<?php echo $banktransfer['banktransfer_bankname']; ?>
							</td>
						</tr>
						<tr>
							<td width="80" class="main gm_strong" valign="top">
								<?php echo TEXT_BANK_BLZ; ?>
							</td>
							<td colspan="5" class="main" valign="top">
								<?php echo $banktransfer['banktransfer_blz']; ?>
							</td>
						</tr>
						<tr>
							<td width="80" class="main gm_strong" valign="top">
								<?php echo TEXT_BANK_NUMBER; ?>
							</td>
							<td colspan="5" class="main" valign="top">
								<?php echo $banktransfer['banktransfer_number']; ?>
							</td>
						</tr>
						<tr>
							<td width="80" class="main gm_strong" valign="top">
								<?php echo TEXT_BANK_OWNER; ?>
							</td>
							<td colspan="5" class="main" valign="top">
								<?php echo $banktransfer['banktransfer_owner']; ?>
							</td>
						</tr>

					<?php
						if ($banktransfer['banktransfer_status'] == 0) {
					?>
						<tr>
							<td width="80" class="main gm_strong" valign="top">
								<?php echo TEXT_BANK_STATUS; ?>
							</td>
							<td colspan="5" class="main" valign="top">
								<?php echo "OK"; ?>
							</td>
						</tr>
					<?php
						} else {
					?>
						<tr>
							<td width="80" class="main gm_strong" valign="top">
								<?php echo TEXT_BANK_STATUS; ?>
							</td>
							<td colspan="5" class="main" valign="top">
								<?php echo $banktransfer['banktransfer_status']; ?>
							</td>
						</tr>
					<?php
						switch ($banktransfer['banktransfer_status']) {
							case 1 :
								$error_val = TEXT_BANK_ERROR_1;
								break;
							case 2 :
								$error_val = TEXT_BANK_ERROR_2;
								break;
							case 3 :
								$error_val = TEXT_BANK_ERROR_3;
								break;
							case 4 :
								$error_val = TEXT_BANK_ERROR_4;
								break;
							case 5 :
								$error_val = TEXT_BANK_ERROR_5;
								break;
							case 8 :
								$error_val = TEXT_BANK_ERROR_8;
								break;
							case 9 :
								$error_val = TEXT_BANK_ERROR_9;
								break;
						}
					?>
						<tr>
							<td width="80" class="main gm_strong" valign="top">
								<?php echo TEXT_BANK_ERRORCODE; ?>
							</td>
							<td colspan="5" class="main" valign="top">
								<?php echo $error_val; ?>
							</td>
						</tr>
						<tr>
							<td width="80" class="main gm_strong" valign="top">
								<?php echo TEXT_BANK_PRZ; ?>
							</td>
							<td colspan="5" class="main" valign="top">
								<?php echo $banktransfer['banktransfer_prz']; ?>
							</td>
						</tr>
					<?php
						}
					}
					if ($banktransfer['banktransfer_fax']) {
					?>
						<tr>
							<td width="80" class="main gm_strong" valign="top">
								<?php echo TEXT_BANK_FAX; ?>
							</td>
							<td colspan="5" class="main" valign="top">
								<?php echo $banktransfer['banktransfer_fax']; ?>
							</td>
						</tr>
					<?php
						echo "</table>";
					}
					// end modification for banktransfer
					?>

<!-- ORDERS - LUUPAY -->
					<?php

					if ($order->info['payment_method'] == 'luupws') {
						echo'
							<table border="0" width="100%" cellspacing="0" cellpadding="0">
								<tr>
									<td width="30%" class="dataTableHeadingContent"><?php echo TABLE_HEADING_LUUPAY; ?></td>
								</tr>
							</table>
							<table border="0" width="100%" cellspacing="0" cellpadding="2" class="gm_border dataTableRow">
							';

						include( DIR_FS_CATALOG.DIR_WS_INCLUDES.'nusoap/luup_orders.php' );
						echo "</table>";
					}
					?>


<!-- ORDERS - PAYPAL -->
					<?php
						if(strstr($order->info['payment_method'], 'paypal')) {
					?>

					<table border="0" width="100%" cellspacing="0" cellpadding="0">
						<tr>
							<td width="30%" class="dataTableHeadingContent"><?php echo TABLE_HEADING_PAYPAL; ?></td>
						</tr>
					</table>
					<table border="0" width="100%" cellspacing="0" cellpadding="2" class="gm_border dataTableRow">

				<?php

					define('TABLE_PAYPAL','paypal');
					define('FILENAME_PAYPAL','paypal.php');
					if ($order->info['payment_method']=='paypal_ipn' or $order->info['payment_method']=='paypal_directpayment' or $order->info['payment_method']=='paypal' or $order->info['payment_method']=='paypalexpress') {
						require('../includes/classes/paypal_checkout.php');
						require('includes/classes/class.paypal.php');
						$paypal = new paypal_admin();
						$paypal->admin_notification((int)$_GET['oID']);
					}
						echo "</table>";
					}
					?>

<!-- ORDERS - DATA -->
					<table border="0" width="100%" cellspacing="0" cellpadding="0">
						<tr>
							<td width="30%" class="dataTableHeadingContent"><?php echo TABLE_HEADING_PRODUCTS; ?></td>
							<td width="10%" class="dataTableHeadingContent"><?php echo TABLE_HEADING_PRODUCTS_MODEL; ?></td>
							<td width="20%" class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_PRICE_EXCLUDING_TAX; ?></td>
							<?php if ($order->products[0]['allow_tax'] == 1) { ?>
							<td width="10%" class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_TAX; ?></td>
							<td width="15%" class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_PRICE_INCLUDING_TAX; ?></td>
							<?php } ?>
							<td width="15%" class="dataTableHeadingContent" align="right" style="border-right: 0px;"><?php echo TABLE_HEADING_TOTAL_INCLUDING_TAX;
							if ($order->products[$i]['allow_tax'] == 1) {
								echo ' (excl.)';
							}
							?>
							</td>
						</tr>
					</table>
					<table style="background-color:#d6e6f3" border="0" width="100%" cellspacing="0" cellpadding="2" class="gm_border dataTableRow">
					<?php
						for ($i = 0, $n = sizeof($order->products); $i < $n; $i ++) {

						echo '<tr style="background-color:#d6e6f3" class="dataTableRow">'."\n".'
							<td style="border-right: 0px;" width="30%" class="dataTableContent" valign="top">' . gm_prepare_number($order->products[$i]['qty']).'&nbsp;' . ((!empty($order->products[$i]['quantity_unit_id'])) ? $order->products[$i]['unit_name'] : 'x') . '&nbsp;' . $order->products[$i]['name'];
							if (sizeof($order->products[$i]['attributes']) > 0) {
								for ($j = 0, $k = sizeof($order->products[$i]['attributes']); $j < $k; $j ++) {
									// BOF GM_MOD GX-Customizer
									if(!empty($order->products[$i]['attributes'][$j]['option']) || !empty($order->products[$i]['attributes'][$j]['value']))
									{
										echo '<br /><nobr><small>&nbsp;<i> - '.$order->products[$i]['attributes'][$j]['option'].': '.$order->products[$i]['attributes'][$j]['value'].'</i></small></nobr>';
									}
									// EOF GM_MOD GX-Customizer
								}
															
								// BOF GM_MOD GX-Customizer:
								include(DIR_FS_CATALOG . 'gm/modules/gm_gprint_admin_orders.php');				
							}
							// BOF GM_MOD GX-Customizer:
							echo '</td>'."\n".'<td class="dataTableContent" valign="top" style="border-right: 0px; vertical-align: top" width="10%" >';
							if ($order->products[$i]['model'] != '') {
								echo $order->products[$i]['model'];
							} else {
								echo '<br />';
							}

							// attribute models
							if (sizeof($order->products[$i]['attributes']) > 0) {
								for ($j = 0, $k = sizeof($order->products[$i]['attributes']); $j < $k; $j ++) {
									$model = xtc_get_attributes_model($order->products[$i]['id'], $order->products[$i]['attributes'][$j]['value'],$order->products[$i]['attributes'][$j]['option']);
									if ($model != '') {
										echo $model.'<br />';
									} else {
										echo '<br />';
									}
								}
							}

							echo '&nbsp;</td>'."\n".'
								<td style="border-right: 0px;" width="20%" class="dataTableContent" align="right" valign="top">'.format_price($order->products[$i]['final_price'] / $order->products[$i]['qty'], 1, $order->info['currency'], $order->products[$i]['allow_tax'], $order->products[$i]['tax']).'</td>'."\n";

								if ($order->products[$i]['allow_tax'] == 1) {
									echo '<td style="border-right: 0px;" width="10%" class="dataTableContent" align="right" valign="top">';
									echo xtc_display_tax_value($order->products[$i]['tax']).'%';
									echo '</td>'."\n";
									echo '<td style="border-right: 0px;" width="15%"  class="dataTableContent" align="right" valign="top"><b>';
									echo format_price($order->products[$i]['final_price'] / $order->products[$i]['qty'], 1, $order->info['currency'], 0, 0);
									echo '</b></td>'."\n";
								}
									echo '<td style="border-right: 0px;" width="15%" class="dataTableContent" align="right" valign="top"><b>'.format_price(($order->products[$i]['final_price']), 1, $order->info['currency'], 0, 0).'</b></td>'."\n";
									echo '</tr>'."\n";
							}
						?>
							<?php
								for ($i = 0, $n = sizeof($order->totals); $i < $n; $i ++) {
									if ($order->products[0]['allow_tax'] == 1) {
										echo '<tr>'."\n".'<td colspan="5" align="right" class="smallText">'.$order->totals[$i]['title'].'</td>'."\n".'
										<td align="right" class="smallText">'.$order->totals[$i]['text'].'</td>'."\n".'</tr>'."\n";
									} else {
										echo '<tr>'."\n".'<td colspan="3" align="right" class="smallText">'.$order->totals[$i]['title'].'</td>'."\n".'
										<td align="right" class="smallText">'.$order->totals[$i]['text'].'</td>'."\n".'</tr>'."\n";
									}
								}
							?>
					</table>

<!-- ORDERS - STATUS -->
					<table border="0" width="100%" cellspacing="0" cellpadding="0" class="pdf_menu">
						<tr>
							<td width="25%" class="dataTableHeadingContent">
								<?php echo TABLE_HEADING_DATE_ADDED; ?>
							</td>
							<td width="25%" class="dataTableHeadingContent">
								<?php echo TABLE_HEADING_CUSTOMER_NOTIFIED; ?>
							</td>
							<td width="25%"class="dataTableHeadingContent">
								<?php echo TABLE_HEADING_STATUS; ?>
							</td>
							<td width="25%" class="dataTableHeadingContent" style="border-right: 0px;">
								<?php echo TABLE_HEADING_COMMENTS; ?>
							</td>
						</tr>
					</table>

					<table border="0" width="100%" cellspacing="0" cellpadding="2" class="gm_border dataTableRow">
					<?php

					$orders_history_query = xtc_db_query("select orders_status_id, date_added, customer_notified, comments from ".TABLE_ORDERS_STATUS_HISTORY." where orders_id = '".xtc_db_input($oID)."' order by date_added");
					if (xtc_db_num_rows($orders_history_query)) {
						while ($orders_history = xtc_db_fetch_array($orders_history_query)) {
							echo '<tr>'."\n".'
							<td width="25%" class="smallText" align="left">'.xtc_datetime_short($orders_history['date_added']).'</td>'."\n".'
							<td width="25%" class="smallText" align="left">';
							if ($orders_history['customer_notified'] == '1') {
								echo xtc_image(DIR_WS_ICONS.'tick.gif', ICON_TICK)."</td>\n";
							} else {
								echo xtc_image(DIR_WS_ICONS.'cross.gif', ICON_CROSS)."</td>\n";
							}

							echo '<td width="25%" class="smallText">';
							if($orders_history['orders_status_id']!='0') {
								echo $orders_status_array[$orders_history['orders_status_id']];
							} else {
								echo '<font color="#FF0000">'.TEXT_VALIDATING.'</font>';
							}
							echo '</td>'."\n".'<td width="25%" align="left" class="smallText">'.nl2br(xtc_db_output($orders_history['comments'])).'&nbsp;</td>'."\n".'</tr>'."\n";
							}
						} else {
							echo '<tr>'."\n".'<td class="smallText" colspan="4">'.TEXT_NO_ORDER_HISTORY.'</td>'."\n".'</tr>'."\n";
					}
					?>
					</table>

					<!-- zmb clickandbuy begin -->
					<?php
					// BOF GM_MOD
					$t_gm_check = xtc_db_query("SHOW TABLES LIKE 'orders_clickandbuy_ems'");
					if(xtc_db_num_rows($t_gm_check) == 1 && strpos(strtolower($order->info['payment_class']), 'clickandbuy') !== false)
					{
					?>
					<table><tr>
					<td width="50%" class="main">
							  <h4><?php echo HEADING_CLICKANDBUY_V2_EMS; ?></h4>
					<?php
					$qr = xtc_db_query(sprintf("SELECT oce.* FROM orders_clickandbuy oc LEFT JOIN orders_clickandbuy_ems oce ON oc.f_transactionID = oce.`BDRID` WHERE oc.orders_id = %d ORDER BY oce.`datetime` DESC, oce.`action` DESC", $_GET['oID']));
					$ems_events = array();
					while ($qa = xtc_db_fetch_array($qr)) {
					  $ems_events[] = $qa;
					}
					?>
					<?php if ($ems_events): ?>
							  <table border="1" cellspacing="0" cellpadding="5">
								<tr>
								  <td class="smallText" align="center"><b><?php echo TABLE_HEADING_CLICKANDBUY_V2_EMS_TIMESTAMP; ?></b></td>
								  <td class="smallText" align="center"><b><?php echo TABLE_HEADING_CLICKANDBUY_V2_EMS_TYPE; ?></b></td>
								  <td class="smallText" align="center"><b><?php echo TABLE_HEADING_CLICKANDBUY_V2_EMS_ACTION; ?></b></td>
								</tr>
					<?php foreach ($ems_events as $e): ?>
								<tr>
								  <td class="smallText" align="center"><?php echo $e['datetime']; ?></td>
								  <td class="smallText" align="center"><?php echo $e['type']; ?></td>
								  <td class="smallText" align="center"><?php echo $e['action']; ?></td>
								</tr>
					<?php endforeach; ?>
							  </table>
					<?php else: ?>
							  <p><?php echo TEXT_CLICKANDBUY_V2_EMS_NO_EVENTS; ?></p>
					<?php endif; ?>
							</td>
					</tr></table>
					<?php
					}
					// EOF GM_MOD
					?>
					<!-- zmb clickandbuy end -->

<!-- ORDERS - STATUS SEND -->
					<table border="0" width="100%" cellspacing="0" cellpadding="0" class="pdf_menu">
						<tr>
							<td class="dataTableHeadingContent" style="border-right: 0px;">
								<?php echo TABLE_HEADING_STATUS; ?>
							</td>
						</tr>
					</table>
					<?php echo xtc_draw_form('status', FILENAME_ORDERS, xtc_get_all_get_params(array('action')) . 'action=update_order'); ?>
					<table border="0" width="100%" cellspacing="0" cellpadding="2" class="gm_border dataTableRow">
						<tr>
							<td width="160" class="main" valign="top">
								<?php echo TABLE_HEADING_COMMENTS; ?>
							</td>
							<td class="main" valign="top">
								<?php echo xtc_draw_textarea_field('comments', 'soft', '60', '3', $order->info['comments']); ?>
							</td>
						</tr>
						<tr>
							<td width="160" class="main" valign="top">
								<?php echo ENTRY_STATUS; ?>
							</td>
							<td class="main" valign="top">
								<?php echo xtc_draw_pull_down_menu('status', $orders_statuses, $order->info['orders_status']); ?>
							</td>
						</tr>
						<tr>
							<td width="160" class="main" valign="top">
								<?php echo ENTRY_NOTIFY_CUSTOMER; ?>
							</td>
							<td class="main" valign="top">
								<?php echo xtc_draw_checkbox_field('notify', '', true); ?>
							</td>
						</tr>
						<tr>
							<td width="160" class="main" valign="top">
								<?php echo ENTRY_NOTIFY_COMMENTS; ?>
							</td>
							<td class="main" valign="top">
								<?php echo xtc_draw_checkbox_field('notify_comments', '', true); ?>
							</td>
						</tr>
						<tr>
							<td colspan="2" class="main" valign="top">
								&nbsp;
							</td>
						</tr>
						<tr>
							<td colspan="2" class="main" valign="top">
								<input type="submit" class="button" value="<?php echo BUTTON_UPDATE; ?>">
							</td>
						</tr>
					</table>
				</form>
<!-- ORDERS - BUTTONS -->
					<a style="width:170px;" class="button float_right" href=<?php echo '"' . xtc_href_link(FILENAME_ORDERS, 'oID='.$_GET['oID'].'&action=deleteccinfo').'">'.BUTTON_REMOVE_CC_INFO;?></a>
					<?php
						echo '<input type="hidden" value="' . $_GET['oID'] .'" id="gm_order_id">';

						echo '<span style="width:170px;float:right;" class="GM_SEND_ORDER button" href="' . xtc_href_link('gm_send_order.php', 'oID=' . $_GET['oID'] . '&type=send_order') . '" target="_blank">' . TITLE_SEND_ORDER . '</span>';
					if(gm_pdf_is_installed()) {
						echo '<a style="width:85px;" class="button float_right" href="' . xtc_href_link('gm_pdf_order.php', 'oID=' . $_GET['oID'] . '&type=packingslip') . '" target="_blank">' . TITLE_PACKINGSLIP	. '</a> ';
						echo '<span style="width:110px;float:right" class="GM_INVOICE_MAIL button">' . TITLE_INVOICE_MAIL  . '</span> ';
						echo '<a style="width:85px;" class="button float_right" href="' . xtc_href_link('gm_pdf_order.php', 'oID=' . $_GET['oID'] . '&type=invoice') . '" target="_blank">' . TITLE_INVOICE	. '</a> ';
					}
						//echo '<a class="button float_right" href="' . xtc_href_link('gm_send_order.php', 'oID=' . $_GET['oID'] . '&type=order') . '" target="_blank">' . TITLE_ORDER . '</a>';
					?>

					<?php
						if (ACTIVATE_GIFT_SYSTEM == 'true') {
							echo '<a style="width:110px;" class="button float_right" href="'.xtc_href_link(FILENAME_GV_MAIL, xtc_get_all_get_params(array ('cID', 'action')).'cID='.$order->customer['ID']).'">'.TITLE_GIFT_MAIL.'</a>';
						}
					?>
					<br />
					<br />
					<a style="float:right" class="button" href=<?php echo '"' . xtc_href_link(FILENAME_ORDERS, 'page='.$_GET['page'].'&oID='.$_GET['oID']).'">'.BUTTON_BACK;?></a>
				</td>
			</tr>
		</table>
<?php

} elseif ($_GET['action'] == 'custom_action') {
	echo '<td  class="boxCenter" width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">';
	include ('orders_actions.php');
} else {
?>
	<td  class="boxCenter" width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
      <tr>
        <td width="100%">
			<div class="pageHeading" style="float:left; background-image:url(images/gm_icons/kunden.png)">
				<?php echo HEADING_TITLE; ?>
			</div>
			<div class="pageHeading orders_form">
				<?php echo xtc_draw_form('orders', FILENAME_ORDERS, '', 'get'); ?>
				<?php echo HEADING_TITLE_SEARCH . ' ' . xtc_draw_input_field('oID', '', 'size="12"') . xtc_draw_hidden_field('action', 'edit').xtc_draw_hidden_field(xtc_session_name(), xtc_session_id()); ?>
				</form>
				<?php echo xtc_draw_form('status', FILENAME_ORDERS, '', 'get'); ?>
				<?php echo HEADING_TITLE_STATUS . ' ' . xtc_draw_pull_down_menu('status', array_merge(array(array('id' => '', 'text' => TEXT_ALL_ORDERS)),array(array('id' => '0', 'text' => TEXT_VALIDATING)), $orders_statuses), '', 'onChange="this.form.submit();"').xtc_draw_hidden_field(xtc_session_name(), xtc_session_id()); ?>
				</form>
			</div>
			<br>

			<!-- bof gm -->
			<?php
				if($_GET['action'] != "delete") {
					echo xtc_draw_form('gm_multi_status', FILENAME_ORDERS, xtc_get_all_get_params(array('action')) . 'action=gm_multi_status', 'post');
				}
			?>
			<!-- eof gm -->
        </td>
      </tr>
      <tr>
        <td class="main gm_strong">
		<!-- bof gm send_order status -->
		<?php
			$gm_send_order_status = array();
			$gm_query = xtc_db_query("
									SELECT
										orders_id
									FROM
										orders
									WHERE
										gm_send_order_status = '0'
									");
			while($row = xtc_db_fetch_array($gm_query)) {
				$gm_send_order_status[] = $row['orders_id'];
			}

			if(count($gm_send_order_status) == 1) {
				echo GM_SEND_ORDER_STATUS_MONO . "<br /><br />";
			} elseif(count($gm_send_order_status) > 1) {
				echo GM_SEND_ORDER_STATUS_STEREO . "<br /><br />";
			}

		?>
		<!-- eof gm send_order status -->
		<table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent"><input type="checkbox" id="gm_check"></td>
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_CUSTOMERS; ?></td>
                <td class="dataTableHeadingContent" align="left"><?php echo 'Nr'; ?></td>
                <td class="dataTableHeadingContent" align="left"><?php echo TABLE_HEADING_ORDER_TOTAL; ?></td>
                <td class="dataTableHeadingContent" align="left"><?php echo TABLE_HEADING_DATE_PURCHASED; ?></td>
                <td class="dataTableHeadingContent" align="left"><?php echo TABLE_HEADING_STATUS; ?></td>
                <?php if (AFTERBUY_ACTIVATED=='true') { ?>
                <td class="dataTableHeadingContent" align="left"><?php echo TABLE_HEADING_AFTERBUY; ?></td>
                <?php } ?>
                <td class="dataTableHeadingContent" align="right">&nbsp;</td>
              </tr>
<?php

// bof gm
	// prepare GET-data
	if(isset($_GET['gm_status'])) {

		$oID = xtc_db_prepare_input($_GET['oID']);
		$status = xtc_db_prepare_input($_GET['gm_status']);
		$order_updated = false;

		// check status
		$check_status_query = xtc_db_query("select orders_status from ".TABLE_ORDERS." where orders_id = '".xtc_db_input($oID)."'");
		$check_status = xtc_db_fetch_array($check_status_query);

		// proceed
		if ($check_status['orders_status'] != $status || $comments != '') {
			xtc_db_query("update ".TABLE_ORDERS." set orders_status = '".xtc_db_input($status)."', last_modified = now() where orders_id = '".xtc_db_input($oID)."'");
		}
		unset($_GET['gm_status']);
	}
// eof gm

	if ($_GET['cID']) {
		$cID = xtc_db_prepare_input($_GET['cID']);
		$orders_query_raw = "select customers_email_address, o.orders_id, o.afterbuy_success, o.afterbuy_id, o.customers_name, o.customers_id, o.payment_method, o.date_purchased, o.last_modified, o.currency, o.currency_value, o.orders_status, s.orders_status_name, ot.text as order_total from ".TABLE_ORDERS." o left join ".TABLE_ORDERS_TOTAL." ot on (o.orders_id = ot.orders_id), ".TABLE_ORDERS_STATUS." s where o.customers_id = '".xtc_db_input($cID)."' and (o.orders_status = s.orders_status_id and s.language_id = '".$_SESSION['languages_id']."' and ot.class = 'ot_total') or (o.orders_status = '0' and ot.class = 'ot_total' and  s.orders_status_id = '1' and s.language_id = '".$_SESSION['languages_id']."') order by orders_id DESC";
	}
	elseif ($_GET['status']=='0') {
			$orders_query_raw = "select customers_email_address, o.orders_id, o.afterbuy_success, o.afterbuy_id, o.customers_name, o.payment_method, o.date_purchased, o.last_modified, o.currency, o.currency_value, o.orders_status, ot.text as order_total from ".TABLE_ORDERS." o left join ".TABLE_ORDERS_TOTAL." ot on (o.orders_id = ot.orders_id) where o.orders_status = '0' and ot.class = 'ot_total' order by o.orders_id DESC";
	}
	elseif ($_GET['status']) {
			$status = xtc_db_prepare_input($_GET['status']);
			$orders_query_raw = "select customers_email_address, o.orders_id, o.afterbuy_success, o.afterbuy_id, o.customers_name, o.payment_method, o.date_purchased, o.last_modified, o.currency, o.currency_value, s.orders_status_name, ot.text as order_total from ".TABLE_ORDERS." o left join ".TABLE_ORDERS_TOTAL." ot on (o.orders_id = ot.orders_id), ".TABLE_ORDERS_STATUS." s where o.orders_status = s.orders_status_id and s.language_id = '".$_SESSION['languages_id']."' and s.orders_status_id = '".xtc_db_input($status)."' and ot.class = 'ot_total' order by o.orders_id DESC";
	} else {
		$orders_query_raw = "select customers_email_address, o.orders_id, o.orders_status, o.afterbuy_success, o.afterbuy_id, o.customers_name, o.payment_method, o.date_purchased, o.last_modified, o.currency, o.currency_value, s.orders_status_name, ot.text as order_total from ".TABLE_ORDERS." o left join ".TABLE_ORDERS_TOTAL." ot on (o.orders_id = ot.orders_id), ".TABLE_ORDERS_STATUS." s where (o.orders_status = s.orders_status_id and s.language_id = '".$_SESSION['languages_id']."' and ot.class = 'ot_total') or (o.orders_status = '0' and ot.class = 'ot_total' and  s.orders_status_id = '1' and s.language_id = '".$_SESSION['languages_id']."') order by o.orders_id DESC";
	}
	$orders_split = new splitPageResults($_GET['page'], '20', $orders_query_raw, $orders_query_numrows);
	$orders_query = xtc_db_query($orders_query_raw);

	//bof gm
	while ($orders = xtc_db_fetch_array($orders_query)) {
		if (((!$_GET['oID']) || ($_GET['oID'] == $orders['orders_id'])) && (!$oInfo)) {
			$oInfo = new objectInfo($orders);
		}

		// row is selected
		if ((is_object($oInfo)) && ($orders['orders_id'] == $oInfo->orders_id)) {
			$gm_tr_class	= "dataTableRowSelected";
			$gm_td_action	= 'onclick="document.location.href=\''.xtc_href_link(FILENAME_ORDERS, xtc_get_all_get_params(array ('oID', 'action')).'oID='.$oInfo->orders_id.'&action=edit').'\'"';

		// row is not selected
		} else {
			$gm_tr_class	= "dataTableRow";
			$gm_td_action	= 'onclick="document.location.href=\''.xtc_href_link(FILENAME_ORDERS, xtc_get_all_get_params(array ('oID')).'oID='.$orders['orders_id']).'\'"';
		}
/*
			echo '              <tr class="dataTableRowSelected" onmouseover="this.style.cursor=\'hand\'" onclick="document.location.href=\''.xtc_href_link(FILENAME_ORDERS, xtc_get_all_get_params(array ('oID', 'action')).'oID='.$oInfo->orders_id.'&action=edit').'\'">'."\n";
		} else {
			echo '              <tr class="dataTableRow" onmouseover="this.className=\'dataTableRowOver\';this.style.cursor=\'hand\'" onmouseout="this.className=\'dataTableRow\'" onclick="document.location.href=\''.xtc_href_link(FILENAME_ORDERS, xtc_get_all_get_params(array ('oID')).'oID='.$orders['orders_id']).'\'">'."\n";
		}

*/
?>
		<tr class="<?php echo $gm_tr_class; ?>"<?php if(in_array($orders['orders_id'], $gm_send_order_status)) {echo ' style="font-weight:bold"'; }?>>
			<td class="dataTableContent"><input type="checkbox" class="checkbox" value="<?php echo $orders['orders_id']; ?>" name="gm_multi_status[]"></td>
			<td class="dataTableContent" <?php echo $gm_td_action; ?>><?php echo '<a href="' . xtc_href_link(FILENAME_ORDERS, xtc_get_all_get_params(array('oID', 'action')) . 'oID=' . $orders['orders_id'] . '&action=edit') . '">' . xtc_image(DIR_WS_ICONS . 'preview.gif', ICON_PREVIEW) . '</a>&nbsp;' . $orders['customers_name']; ?></td>
			<td class="dataTableContent" <?php echo $gm_td_action; ?> align="left"><?php echo $orders['orders_id']; ?></td>
			<td class="dataTableContent" <?php echo $gm_td_action; ?> align="left"><?php echo strip_tags($orders['order_total']); ?></td>
			<td class="dataTableContent" <?php echo $gm_td_action; ?> align="left"><?php echo xtc_datetime_short($orders['date_purchased']); ?></td>
			<td class="dataTableContent" <?php echo $gm_td_action; ?> align="left"><?php if($orders['orders_status']!='0') { echo $orders['orders_status_name']; }else{ echo '<font color="#FF0000">'.TEXT_VALIDATING.'</font>';}?></td>


			<?php
				/*
					-> afterbuy
				*/
				if (AFTERBUY_ACTIVATED=='true') {
			?>
				<td class="dataTableContent" align="right">
					<?php
						if ($orders['afterbuy_success'] == 1) {
							echo $orders['afterbuy_id'];
						} else {
							echo 'TRANSMISSION_ERROR';
						}
					?>
				</td>
				<?php } ?>

				<td class="dataTableContent" align="right"><?php if ( (is_object($oInfo)) && ($orders['orders_id'] == $oInfo->orders_id) ) { echo xtc_image(DIR_WS_IMAGES . 'icon_arrow_right.gif', ''); } else { echo '<a href="' . xtc_href_link(FILENAME_ORDERS, xtc_get_all_get_params(array('oID')) . 'oID=' . $orders['orders_id']) . '">' . xtc_image(DIR_WS_IMAGES . 'icon_info.gif', IMAGE_ICON_INFO) . '</a>'; } ?>&nbsp;</td>
			</tr>
<?php

	} // -> close while

//eof gm
?>
            </table>
		</td>
<?php

	$heading = array ();
	$contents = array ();
	switch ($_GET['action']) {
		case 'delete' :
			$heading[] = array ('text' => '<b>'.TEXT_INFO_HEADING_DELETE_ORDER.'</b>');

			$contents = array ('form' => xtc_draw_form('orders', FILENAME_ORDERS, xtc_get_all_get_params(array ('oID', 'action')).'oID='.$oInfo->orders_id.'&action=deleteconfirm'));
			$contents[] = array ('text' => TEXT_INFO_DELETE_INTRO.'<br /><br /><b>'.$cInfo->customers_firstname.' '.$cInfo->customers_lastname.'</b>');


			if($oInfo->orders_status != gm_get_conf('GM_ORDER_STATUS_CANCEL_ID')) {
				// BOF GM_MOD
				$t_gm_restock_checked = true;
				if(STOCK_LIMITED == 'false')
				{
					$t_gm_restock_checked = false;
				}
				$contents[] = array ('text' => '<br />'.xtc_draw_checkbox_field('restock', '', $t_gm_restock_checked).' '.TEXT_INFO_RESTOCK_PRODUCT_QUANTITY);
				// EOF GM_MOD
			}

			$contents[] = array ('align' => 'center', 'text' => '<div align="center"><input type="submit" class="button" value="'. BUTTON_DELETE .'"></div>');
			$contents[] = array ('align' => 'center', 'text' => '<div align="center"><a class="button" href="'.xtc_href_link(FILENAME_ORDERS, xtc_get_all_get_params(array ('oID', 'action')).'oID='.$oInfo->orders_id).'">' . BUTTON_CANCEL . '</a></div>');
			$contents[] = array ('text' => '</form><br />');
			break;

		default:
			if (is_object($oInfo)) {

				$heading[] = array ('text' => '<b>['.$oInfo->orders_id.']&nbsp;&nbsp;'.xtc_datetime_short($oInfo->date_purchased).'</b>');
				$contents[] = array ('align' => 'center', 'text' => '<div style="padding-top: 5px; font-weight: bold; ">' . TEXT_MARKED_ELEMENTS . '</div><br />');
				$contents[] = array ('align' => 'left', 'text' => '<div align="center"><a class="button" href="'.xtc_href_link(FILENAME_ORDERS, xtc_get_all_get_params(array ('oID', 'action')).'oID='.$oInfo->orders_id.'&action=edit').'">'.BUTTON_EDIT.'</a></div>');
				$contents[] = array ('align' => 'left', 'text' => '<div align="center"><a class="button" href="'.xtc_href_link(FILENAME_ORDERS, xtc_get_all_get_params(array ('oID', 'action')).'oID='.$oInfo->orders_id.'&action=delete').'">'.BUTTON_DELETE.'</a></div>');
				// bof
				$contents[] = array ('align' => 'left', 'text' => '<div align="center"><span class="GM_CANCEL button">'.BUTTON_GM_CANCEL.'</span></div>');
				// eof gm
				if (AFTERBUY_ACTIVATED == 'true') {
					$contents[] = array ('align' => 'center', 'text' => '<div align="center"><a class="button" href="'.xtc_href_link(FILENAME_ORDERS, xtc_get_all_get_params(array ('oID', 'action')).'oID='.$oInfo->orders_id.'&action=afterbuy_send').'">'.BUTTON_AFTERBUY_SEND.'</a></div>');

				}

				// bof gm
				if(gm_pdf_is_installed()) {
					$contents[] = array ('align' => 'left', 'text' => '<div align="center"><input type="hidden" value="' . $oInfo->orders_id .'" id="gm_order_id"><a class="button" href="' . xtc_href_link('gm_pdf_order.php', 'oID=' . $oInfo->orders_id . '&type=invoice') . '" target="_blank">' . TITLE_INVOICE . '</a></div>');
					$contents[] = array ('align' => 'left', 'text' => '<div align="center"><span class="GM_INVOICE_MAIL button">' . TITLE_INVOICE_MAIL  . '</div></span>');
					$contents[] = array ('align' => 'left', 'text' => '<div align="center"><a class="button" href="' . xtc_href_link('gm_pdf_order.php', 'oID=' . $oInfo->orders_id . '&type=packingslip')	. '" target="_blank">' . TITLE_PACKINGSLIP . '</a></div>');
				}
				// eof gm
				$contents[] = array ('align' => 'left', 'text' => '<div align="center"><a class="button" href="' . xtc_href_link('gm_send_order.php', 'oID=' . $oInfo->orders_id . '&type=order') . '" target="_blank">' . TITLE_ORDER . '</a></div>');

				//BOF GM ORDER RECREATE
				$contents[] = array ('align' => 'left', 'text' => '<div align="center"><a class="button" href="' . xtc_href_link('gm_send_order.php', 'oID=' . $oInfo->orders_id . '&type=recreate_order') . '" target="_blank">' . TITLE_RECREATE_ORDER . '</a></div>');
				//EOF GM ORDER RECREATE

				$contents[] = array ('align' => 'left', 'text' => '<div align="center"><span class="GM_SEND_ORDER button" href="' . xtc_href_link('gm_send_order.php', 'oID=' . $oInfo->orders_id . '&type=send_order') . '" target="_blank">' . TITLE_SEND_ORDER . '</span></div>');
				//$gm_quick_status = '<form method="get" action="'.FILENAME_ORDERS.'" ' . xtc_draw_pull_down_menu('gm_status', array_merge(array(array('id' => '', 'text' => TEXT_GM_STATUS)),array(array('id' => '0', 'text' => TEXT_VALIDATING)), $orders_statuses), '', 'onChange="this.form.submit();"').xtc_draw_hidden_field('oID', $oInfo->orders_id) . xtc_draw_hidden_field(xtc_session_name(), xtc_session_id()) . '</form>';


				$contents[] = array ('text' => '<br />'.TEXT_DATE_ORDER_CREATED.' '.xtc_date_short($oInfo->date_purchased));
				if (xtc_not_null($oInfo->last_modified))
					$contents[] = array ('text' => TEXT_DATE_ORDER_LAST_MODIFIED.' '.xtc_date_short($oInfo->last_modified));
				$contents[] = array ('text' => '<br />'.TEXT_INFO_PAYMENT_METHOD.' '.$oInfo->payment_method);
        if ($oInfo->payment_method == 'clickandbuy_v2') {
          if ($qr = xtc_db_query(sprintf("SELECT * FROM orders_clickandbuy WHERE `orders_id` = %d", $oInfo->orders_id))) {
            $qa = xtc_db_fetch_array($qr);
            $text = sprintf('CRN: %d<br />BDRID: %s<br />externalBDRID: %s', $qa['f_userid'], $qa['f_transactionID'], $qa['f_externalBDRID']);
            $qr_ems = xtc_db_query(sprintf("SELECT * FROM orders_clickandbuy_ems WHERE `BDRID` = %d AND `type` = 'bdr' ORDER BY `datetime` DESC, action DESC LIMIT 1", $qa['f_transactionID']));
            if ($qr_ems && mysql_num_rows($qr_ems) > 0) {
              $qa_ems = xtc_db_fetch_array($qr_ems);
              $text .= sprintf('<br />Status: %s (%s)', $qa_ems['action'], $qa_ems['datetime']);
            }
            else {
              $text .= '<br />No EMS info.';
            }
            $contents[] = array('text' => $text);
          }
        }
        				// elari added to display product list for selected order
				$order = new order($oInfo->orders_id);
				$contents[] = array ('text' => '<br /><br />'.sizeof($order->products).' '.GM_PRODUCTS); // BOF GM_MOD EOF
				for ($i = 0; $i < sizeof($order->products); $i ++) {
					$contents[] = array ('text' => gm_prepare_number($order->products[$i]['qty']).'&nbsp;' . ((!empty($order->products[$i]['quantity_unit_id'])) ? $order->products[$i]['unit_name'] : 'x') . '&nbsp;'.$order->products[$i]['name']); // BOF GM_MOD EOF

					if (sizeof($order->products[$i]['attributes']) > 0) {
						for ($j = 0; $j < sizeof($order->products[$i]['attributes']); $j ++) {
							$contents[] = array ('text' => '<small>&nbsp;<i> - '.$order->products[$i]['attributes'][$j]['option'].': '.$order->products[$i]['attributes'][$j]['value'].'</i></small></nobr>');
						}
						// BOF GM_MOD GX-Customizer:
						include(DIR_FS_CATALOG . 'gm/modules/gm_gprint_admin_orders_2.php');
					}
				}
				// elari End add display products
				$contents[] = array ('text' => '<br />'); // BOF GM_MOD EOF
			}

			// bof gm
			$gm_heading_multi_status[]		= array ('text' => '<b>'.HEADING_GM_STATUS.'</b>');
			$content_multi_order_status[]	= array ('text' => xtc_draw_hidden_field(xtc_session_name(), xtc_session_id()));
			$content_multi_order_status[]	= array ('text' => xtc_draw_hidden_field('action', 'gm_multi_status').xtc_draw_hidden_field('page', $_GET['page']));
			$content_multi_order_status[]	= array ('text' => xtc_draw_pull_down_menu('gm_status', array_merge(array(array('id' => '', 'text' => TEXT_GM_STATUS)),array(array('id' => '0', 'text' => TEXT_VALIDATING)), $orders_statuses)));
			$content_multi_order_status[]	= array ('text' => xtc_draw_checkbox_field('gm_notify', 'on')			. ENTRY_NOTIFY_CUSTOMER);
			$content_multi_order_status[]	= array ('text' => xtc_draw_checkbox_field('gm_notify_comments', 'on')	. ENTRY_NOTIFY_COMMENTS);
			$content_multi_order_status[]	= array ('text' => TABLE_HEADING_COMMENTS.'<br>'.xtc_draw_textarea_field('gm_comments', '', 24, 5, $_GET['comments'],'',false).'<br>');
			$content_multi_order_status[]	= array ('align' => 'left', 'text' => '<div align="center"><input type="submit" class="button" value="'. BUTTON_CONFIRM .'"></form></div>');
			$content_multi_order_status[]	= array ('align' => 'left', 'text' => '<br />');
			// eof gm
			break;
	}

	if ((xtc_not_null($heading)) && (xtc_not_null($contents))) {
		echo '            <td width="25%" valign="top" id="gm_orders">'."\n";

		$box = new box;
		echo $box->infoBox($heading, $contents);
		echo "<br />";
		$box = new box;
		echo $box->infoBox($gm_heading_multi_status, $content_multi_order_status);

		echo '            </td>'."\n";
	}
?>
          </tr>
        </table>
		<!-- bof gambio -->
		<table border="0" cellspacing="3" cellpadding="3">
			<tr>
				<td class="smallText" valign="middle" align="right"><?php echo $orders_split->display_count($orders_query_numrows, '20', $_GET['page'], TEXT_DISPLAY_NUMBER_OF_ORDERS); ?></td>
				<td class="smallText" valign="middle" align="right"><?php echo $orders_split->display_links($orders_query_numrows, '20', MAX_DISPLAY_PAGE_LINKS, $_GET['page'], xtc_get_all_get_params(array('page', 'oID', 'action'))); ?></td>
			</tr>
		</table>
		<!-- eof gambio -->
	</td>
</tr>
<?php

}
?>
    </table></td>
<!-- body_text_eof //-->
  </tr>
</table>
<!-- body_eof //-->

<!-- footer //-->
<?php

require (DIR_WS_INCLUDES.'footer.php');
?>
<!-- footer_eof //-->
<br />
<div id="GM_CANCEL_BOX"></div>;
<div id="GM_ORDERS_MAIL_BOX"></div>;
<div id="GM_INVOICE_MAIL_BOX"></div>;
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>