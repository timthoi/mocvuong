<?php
/**
 *
 * Order detail view
 *
 * @package	VirtueMart
 * @subpackage Orders
 * @author Oscar van Eijk, Valerie Isaksen
 * @link http://www.virtuemart.net
 * @copyright Copyright (c) 2004 - 2010 VirtueMart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * VirtueMart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * @version $Id: details_order.php 7499 2013-12-18 15:11:51Z Milbo $
 */
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

?>

<div class="row order-info">
	<div class="col-sm-12">
		<p class="order_no"><?php echo vmText::_('COM_VIRTUEMART_ORDER_PRINT_PO_NUMBER') . ': #' . $this->orderdetails['details']['BT']->order_number; ?></p>
	</div>
	<div class="col-sm-5">
		<p class="billing_address title">Billing Address</p>
		<p><?php echo $this->userfields['fields']['address_1']['value']; ?></p>
	</div>
	<div class="col-sm-5">
		<p class="shipping_address title">Shipping Address</p>
		<p><?php echo $this->shipmentfields['fields']['address_1']['value']; ?></p>
	</div>
	<div class="col-sm-2 col_order_date">
		<p class="order_date title"><?php echo vmText::_('COM_VIRTUEMART_ORDER_PRINT_PO_DATE') ?></p>
		<p><?php echo vmJsApi::date($this->orderdetails['details']['BT']->created_on, 'LC4', true); ?></p>
	</div>
</div>
