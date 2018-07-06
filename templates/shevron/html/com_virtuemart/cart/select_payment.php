<?php
/**
 *
 * Layout for the payment selection
 *
 * @package	VirtueMart
 * @subpackage Cart
 * @author Max Milbers
 *
 * @link http://www.virtuemart.net
 * @copyright Copyright (c) 2004 - 2010 VirtueMart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * VirtueMart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * @version $Id: select_payment.php 8847 2015-05-06 12:22:37Z Milbo $
 */
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');
$addClass="";

?>
	

<div class="border-one">
<h2 class="shop-heading">Payment Method</h2>
<a class="back-link" href="<?php echo JRoute::_('index.php?com=com_virtuemart&view=user&task=editAddressCart') ?>"><i class="fa fa-angle-left"></i> Back to cart</a>
</div>

<div class="row">
	
<div class="col-sm-5 col-xs-12 checkout-cart pull-right">
	<?php 
		$cart = VirtueMartCart::getCart();
		$this->cart->prepareVendor();
		$this->cart->prepareCartData();
	?>
	<h2>Your Order</h2>
	<?php if(empty($this->cart->products)){
		echo "Your cart is currently empty";
	} else { ?>
	<div class="head">
		<div class="row">
			<div class="col-sm-9 col-xs-9">
				<p>Product</p>
			</div>
			<div class="col-sm-3 col-xs-3">
				<p style="text-align: right">Total</p>
			</div>
		</div>
	</div><!--head-->
	<div class="body">
	<?php foreach($this->cart->products as $pkey => $prow) { ?>
		<div class="item">
			<div class="row">
				<div class="col-sm-4 col-xs-5">
					<div class="cart-images hidden-xs">
					   <a href="<?php echo $prow->url?>">
						<img class="img-responsive" src="
							<?php
							if (!empty($prow->images[0])) {
								echo ( $prow->images[0]->file_name!='' )?$prow->images[0]->file_url:"images/imageNotAvailable.jpg";
							}
							?>
						" >
					   </a>
					</div>
				</div>
				<div class="col-sm-4 col-xs-3 no-padding order-item">
					<p class="product-name">
						<span class="product-quantity"><?php echo $prow->quantity ?></span> x <?php echo JHtml::link ($prow->url, $prow->product_name); ?>
					</p>
				</div>
				<div class="col-sm-4 col-xs-4 order-item">
					<?php
					if (!class_exists ('CurrencyDisplay'))
						require(VMPATH_ADMIN . DS . 'helpers' . DS . 'currencydisplay.php');
					$currencyDisplay = CurrencyDisplay::getInstance($this->cart->pricesCurrency);	
					
					if (VmConfig::get ('checkout_show_origprice', 1) && !empty($prow->prices['basePriceWithTax']) && $prow->prices['basePriceWithTax'] != $prow->prices['salesPrice']) {
						echo '<span class="line-through">' . $currencyDisplay->createPriceDiv ('basePriceWithTax', '', $prow->prices, TRUE, FALSE, $prow->quantity) . '</span>';
					}
					elseif (VmConfig::get ('checkout_show_origprice', 1) && empty($prow->prices['basePriceWithTax']) && $prow->prices['basePriceVariant'] != $prow->prices['salesPrice']) {
						echo '<span class="line-through">' . $currencyDisplay->createPriceDiv ('basePriceVariant', '', $prow->prices, TRUE, FALSE, $prow->quantity) . '</span>';
					}
					echo $currencyDisplay->createPriceDiv ('salesPrice', '', $prow->prices, FALSE, FALSE, $prow->quantity);
					?>
				</div>
			</div>
		</div>
	<?php } ?>
	</div><!--body-->
	<div class="subtotal">
		<div class="row">
			<!--Subtotal-->
			<div class="col-sm-8 col-xs-6">
				<p>Subtotal</p>
			</div>
			
			<div class="col-sm-4 col-xs-6">
				<?php echo $currencyDisplay->createPriceDiv ('salesPrice', '', $this->cart->cartPrices, FALSE) ?>
			</div>
			<!--Subtotal-->
			
			<!--PromoCode-->
			<div class="col-sm-8 col-xs-7">
				<p>Promo Code</p>
			</div>
			
			<div class="col-sm-4 col-xs-5">
				<span class='coupon-text'>
				<?php $coupon = $currencyDisplay->createPriceDiv ('salesPriceCoupon', '', $this->cart->cartPrices['salesPriceCoupon'], FALSE); ?>
				<?php if(!empty($coupon)) 
					    echo $coupon;
					 else
						 echo 'S$ 0';
				?>
				</span>
			</div>
			<!--PromoCode-->
			
			<!--Shipment-->
			<div class="col-sm-6 col-xs-4">
				<p>Shipping</p>
			</div>
			
			<div class="col-sm-6 col-xs-8">
				<?php
				//Get default shipping method
				$selectedShipment = (empty($this->cart->virtuemart_shipmentmethod_id) ? 0 : $this->cart->virtuemart_shipmentmethod_id);
				$query = 'SELECT * FROM `#__virtuemart_shipmentmethods`
						WHERE `virtuemart_shipmentmethod_id` = '.$selectedShipment;
				$db = JFactory::getDBO();
				$db->setQuery($query);
				$shipment = $db->loadAssoc();
				if($shipment){
					$arr = explode('|', $shipment['shipment_params']);
					$shipment_cost = str_replace('shipment_cost=', '', $arr[12]);
					$shipment_cost = str_replace('"', '', $shipment_cost);
					echo $currencyDisplay->createPriceDiv (' shipmentCost', '', $shipment_cost);
				}
				else {
					echo '<div class="Price shipmentCost vm-display vm-price-value"><span class="Price shipmentCost"></span></div>';
				}
				?>
			</div>
			<!--Shipment-->
		</div>
	</div><!--subtotal-->
	<div class="total">
		<div class="row">
			<div class="col-sm-7 col-xs-7">
				<p>Order Total</p>
			</div>
			<div class="col-sm-5 col-xs-5">
				<?php echo $currencyDisplay->createPriceDiv ('billTotal', '', $this->cart->cartPrices['billTotal'], FALSE); ?>
				
			</div>
		</div>
	</div><!--subtotal-->
	<?php if (VmConfig::get ('coupons_enable')) { ?>
	<div class="coupon">
		<div class="row">
			<div class="col-sm-12">
				<p>Have a Promo Code? <span class="toggle-coupon">Click here to enter your code <i class="fa fa-angle-down"></i></span></p>
				<form style="display:none" method="post" id="userForm" name="enterCouponCode" action="<?php echo JRoute::_('index.php'); ?>">
					<input type="text" name="coupon_code" placeholder="Promo Code"  />
					<input class="details-button button-2" type="submit" name="setcoupon" title="Apply Coupon" value="Apply Coupon"/>
					<input type="hidden" name="option" value="com_virtuemart" />
					<input type="hidden" name="view" value="cart" />
					<input type="hidden" name="task" value="setcoupon" />
					<input type="hidden" name="return" value="payment" />
					<input type="hidden" name="controller" value="cart" />
				</form>
				
				<?php if (!empty($this->cart->cartData['couponCode'])) :?>
				<p>
					<?php 
						echo $this->cart->cartData['couponCode'];
						echo $this->cart->cartData['couponDescr'] ? (' (' . $this->cart->cartData['couponDescr'] . ')') : '';
					?>
				</p>
				<?php endif; ?>
			</div>
		</div>
	</div>
	<?php }//coupon ?>
	
	<div class="note">
		<div class="row">
			<div class="col-sm-12">
				<textarea id="customer_note_field" name="customer_note" maxlength="2500" placeholder="Order Notes"></textarea>
			</div>
		</div>
	</div>
	
	<?php }//else ?>
</div><!--cart-->


	<div class="col-sm-7 col-xs-12">
	
		<form method="post" id="paymentForm" name="choosePaymentRate" action="<?php echo JRoute::_('index.php'); ?>" class="form-validate <?php echo $addClass ?>">
		<div class="paypal-form checkout-form ">
				<h2>Choose your payment method</h2>
				<?php
					if ($this->found_payment_method ) {
						
						
						echo '<fieldset class="vm-payment-shipment-select vm-payment-select">';
						foreach ($this->paymentplugins_payments as $paymentplugin_payments) {
							if (is_array($paymentplugin_payments)) {
								foreach ($paymentplugin_payments as $paymentplugin_payment) {
									echo '<div class="vm-payment-plugin-single">'.$paymentplugin_payment.'</div>';
								}
							}
						}
						echo '</fieldset>';
						
					} else {
						echo '<h1>'.$this->payment_not_found_text.'</h1>';
					}
					
					if ($this->layoutName!='default') {
					?>    <input type="hidden" name="option" value="com_virtuemart" />
					<input type="hidden" name="view" value="cart" />
					<input type="hidden" name="task" value="confirm" />
					<input type="hidden" name="controller" value="cart" />
		</div>
		<button type="submit" id="checkoutFormSubmit" name="confirm" value="1" class="vm-button-correct button-2 button checkout-button" ><span>PROCEED TO PAYPAL</span> </button>
		</form>
		<?php
		}
	?>
	
	</div><!--col-->
	
</div><!--row-->


	
<script>
jQuery(function($){
	
	
	$('.toggle-coupon').toggle(function () {
			$(".coupon form").css({display: "block"});
		}, function () {
			$(".coupon form").css({display: "none"});
	});
		
	$('.vmpayment_name').each(function() {
		var isPaypal = $(this).html(); 
		
		if ( isPaypal == "Pay Pal" ) {
			$(this).html(""); 
			$(this).next().hide();
			
			$(this).prepend('<img class="paypal_img" src="images/paypal.png" />')				
		}
	});
});
</script>