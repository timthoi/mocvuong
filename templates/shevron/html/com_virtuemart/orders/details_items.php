<?php
/**
*
* Order items view
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
* @version $Id: details_items.php 8310 2014-09-21 17:51:47Z Milbo $
*/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

if($this->format == 'pdf'){
	$widthTable = '100';
	$widthTitle = '27';
} else {
	$widthTable = '100';
	$widthTitle = '49';
}

?>

<div class="row order_item">
	<div class="col-sm-8 col-xs-12">
		<table class="hidden-xs">
			<thead>
				<tr>
					<th style="width: 40%;">Ordered items</th>
					<th style="width: 20%;">Price</th>
					<th style="width: 20%;">Quantity</th>
					<th style="width: 20%;">Total</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach($this->orderdetails['items'] as $item) { 
						$id = $item->virtuemart_product_id;
						$sql="select i.file_url as image from #__virtuemart_medias as i,
							#__virtuemart_product_medias as mi where
							i.virtuemart_media_id = mi.virtuemart_media_id and
							mi.virtuemart_product_id =".$id."  limit 0,1";

						$db = JFactory::getDBO();
						$db->setQuery($sql);
						$results = $db->loadObjectList();
						$image_link = JURI::root () . $results[0]->image;
						$qtt = $item->product_quantity;
					?>
				<tr>
					<td>
						<div class="col-sm-4 col-xs-4">
							<div class="img-product">
								<img src="<?php echo $image_link; ?>" alt="">
							</div>
						</div>
						<div class="col-sm-8 product-name">
							<a href="<?php echo JRoute::_('index.php?option=com_virtuemart&view=productdetails&virtuemart_category_id=' . $item->virtuemart_category_id . '&virtuemart_product_id=' . $item->virtuemart_product_id, FALSE); ?>"><?php echo $item->order_item_name; ?></a>
						</div>
					</td>
					<td>
						<?php
							$item->product_discountedPriceWithoutTax = (float) $item->product_discountedPriceWithoutTax;
							if (!empty($item->product_priceWithoutTax) && $item->product_discountedPriceWithoutTax != $item->product_priceWithoutTax) {
								echo '<span class="line-through">'.$this->currency->priceDisplay($item->product_item_price, $this->currency) .'</span><br />';
								echo '<span >'.$this->currency->priceDisplay($item->product_discountedPriceWithoutTax, $this->currency) .'</span><br />';
							} else {
								//echo '<span >'.$this->currency->priceDisplay($item->product_item_price, $this->currency) .'</span><br />'; 
								echo '<span >'.$this->currency->priceDisplay($item->product_priceWithoutTax, $this->currency) .'</span><br />'; 
								
							}
						?>
					</td>
					<td>
						<?php echo $item->product_quantity; ?>
					</td>
					<td>
						<?php
							$item->product_basePriceWithTax = (float) $item->product_basePriceWithTax;
							$class = '';
							/* if(!empty($item->product_basePriceWithTax) && $item->product_basePriceWithTax != $item->product_final_price ) {
								echo '<span class="line-through" >'.$this->currency->priceDisplay($item->product_basePriceWithTax,$this->currency,$qtt) .'</span><br />' ;
							}
							elseif (empty($item->product_basePriceWithTax) && $item->product_item_price != $item->product_final_price) {
								echo '<span class="line-through">' . $this->currency->priceDisplay($item->product_item_price,$this->currency,$qtt) . '</span><br />';
							} */

							echo $this->currency->priceDisplay(  $item->product_subtotal_with_tax ,$this->currency); 
						?>
					</td>
				</tr>
				<?php }?>
			</tbody>
		</table>
		<table class="visible-xs">
			<thead>
				<tr>
					<th style="width: 100%;">Ordered items</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach($this->orderdetails['items'] as $item) { 
					$id = $item->virtuemart_product_id;
					$sql="select i.file_url as image from #__virtuemart_medias as i,
						#__virtuemart_product_medias as mi where
						i.virtuemart_media_id = mi.virtuemart_media_id and
						mi.virtuemart_product_id =".$id."  limit 0,1";

					$db = JFactory::getDBO();
					$db->setQuery($sql);
					$results = $db->loadObjectList();
					$image_link = JURI::root () . $results[0]->image;
				?>
				<tr>
					<td>
						<div class="col-xs-4">
							<div class="img-product">
								<img src="<?php echo $image_link; ?>" alt="">
							</div>
						</div>
						<div class="col-xs-8">
							<p><?php echo $item->product_quantity . ' x ' . $item->order_item_name; ?></p>
							<p class="price_product">
								<?php
									$item->product_basePriceWithTax = (float) $item->product_basePriceWithTax;
									$class = '';
									if(!empty($item->product_basePriceWithTax) && $item->product_basePriceWithTax != $item->product_final_price ) {
										echo '<span class="line-through" >'.$this->currency->priceDisplay($item->product_basePriceWithTax,$this->currency,$qtt) .'</span><br />' ;
									}
									elseif (empty($item->product_basePriceWithTax) && $item->product_item_price != $item->product_final_price) {
										echo '<span class="line-through">' . $this->currency->priceDisplay($item->product_item_price,$this->currency,$qtt) . '</span><br />';
									}

									echo $this->currency->priceDisplay(  $item->product_subtotal_with_tax ,$this->currency); 
								?>
							</p>
						</div>
					</td>
				</tr>
				<?php } ?>
			</tbody>
		</table>
	</div>
	<div class="col-sm-4 col-xs-12 col-cart-totals">
		<div class="cart-totals">
			<div class="row">
				<div class="col-sm-12 col-xs-12">
					<h2 class="title_cart_totals cart_title">Cart totals</h2>
				</div>
				<div class="col-sm-6 col-xs-6">
					<p class="cart_title">Subtotal</p>
				</div>
				<div class="col-sm-6 col-xs-6">
					<p class="cart_info price_cart"><?php echo $this->currency->priceDisplay($this->orderdetails['details']['BT']->order_salesPrice,$this->currency); ?><p>
				</div>
			</div>
			<div class="shipping">
				<div class="row">
					<div class="col-sm-6 col-xs-6">
						<p class="cart_title"><?php echo vmText::_('COM_VIRTUEMART_ORDER_PRINT_SHIPMENT_LBL') ?></p>
					</div>
					<div class="col-sm-6 col-xs-6">
						<?php
						//Get shipping cost
						$order_number = JRequest::getString('order_number');
						$query = 'SELECT b.* FROM #__virtuemart_orders as a, #__virtuemart_shipmentmethods as b
								WHERE order_number = "'.$order_number.'" AND a.virtuemart_shipmentmethod_id = b.virtuemart_shipmentmethod_id';
						$db = JFactory::getDBO();
						$db->setQuery($query);
						$shipment = $db->loadAssoc();
						if($shipment){
							$arr = explode('|', $shipment['shipment_params']);
							$shipment_cost = str_replace('shipment_cost=', '', $arr[12]);
							$shipment_cost = str_replace('"', '', $shipment_cost);
							echo $this->currency->createPriceDiv (' cart_info price_cart', '', $shipment_cost);
						}
						else {
							echo '<div class="Price shipmentCost vm-display vm-price-value"><span class="Price shipmentCost"></span></div>';
						}
						?>
					</div>
				</div>
			</div>
			<div class="order_total">
				<div class="row">
					<div class="col-sm-6 col-xs-6">
						<p class="cart_title">Order total</p>
					</div>
					<div class="col-sm-6 col-xs-6">
						<p class="cart_info price_cart"><?php echo $this->currency->priceDisplay($this->orderdetails['details']['BT']->order_total, $this->currency); ?></p>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
