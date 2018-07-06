<?php
/**
 *
 * Enter address data for the cart, when anonymous users checkout
 *
 * @package    VirtueMart
 * @subpackage User
 * @author Oscar van Eijk, Max Milbers
 * @link http://www.virtuemart.net
 * @copyright Copyright (c) 2004 - 2010 VirtueMart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * VirtueMart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * @version $Id: edit_address.php 8768 2015-03-02 12:22:14Z Milbo $
 */
// Check to ensure this file is included in Joomla!
defined ('_JEXEC') or die('Restricted access');
$user = JFactory::getUser();

if(!$user->guest) {
// Implement Joomla's form validation
JHtml::_ ('behavior.formvalidation');
JHtml::stylesheet ('vmpanels.css', JURI::root () . 'components/com_virtuemart/assets/css/');


if (!class_exists('VirtueMartCart')) require(VMPATH_SITE . DS . 'helpers' . DS . 'cart.php');
$this->cart = VirtueMartCart::getCart();
$url = 0;
if ($this->cart->_fromCart or $this->cart->getInCheckOut()) {
	$rview = 'cart';
}
else {
	$rview = 'user';
}

?>


<?php
function renderControlButtons($view,$rview, $products){
	?>
<div class="control-buttons">
	<?php


	if ($view->cart->getInCheckOut() || $view->address_type == 'ST') {
		$buttonclass = 'default';
	}
	else {
		$buttonclass = 'button vm-button-correct';
	}


	if (VmConfig::get ('oncheckout_show_register', 1) && $view->userDetails->JUser->id == 0 && !VmConfig::get ('oncheckout_only_registered', 0) && $view->address_type == 'BT' and $rview == 'cart') {
		echo '<div id="reg_text">'.vmText::sprintf ('COM_VIRTUEMART_ONCHECKOUT_DEFAULT_TEXT_REGISTER', vmText::_ ('COM_VIRTUEMART_REGISTER_AND_CHECKOUT'), vmText::_ ('COM_VIRTUEMART_CHECKOUT_AS_GUEST')).'</div>';			}
	else {
		//echo vmText::_('COM_VIRTUEMART_REGISTER_ACCOUNT');
	}
	if (VmConfig::get ('oncheckout_show_register', 1) && $view->userDetails->JUser->id == 0 && $view->address_type == 'BT' and $rview == 'cart') {
		?>
		<button name="register" class="<?php echo $buttonclass ?>" type="submit" onclick="javascript:return myValidator(userForm,true);"
				title="<?php echo vmText::_ ('COM_VIRTUEMART_REGISTER_AND_CHECKOUT'); ?>"><?php echo vmText::_ ('COM_VIRTUEMART_REGISTER_AND_CHECKOUT'); ?></button>
		<?php if (!VmConfig::get ('oncheckout_only_registered', 0)) { ?>
			<button name="save" class="<?php echo $buttonclass ?>" title="<?php echo vmText::_ ('COM_VIRTUEMART_CHECKOUT_AS_GUEST'); ?>" type="submit"
					onclick="javascript:return myValidator(userForm, false);"><?php echo vmText::_ ('COM_VIRTUEMART_CHECKOUT_AS_GUEST'); ?></button>
		<?php } ?>
		<button class="default" type="reset"
				onclick="window.location.href='<?php echo JRoute::_ ('index.php?option=com_virtuemart&view=' . $rview.'&task=cancel'); ?>'"><?php echo vmText::_ ('COM_VIRTUEMART_CANCEL'); ?></button>
	<?php
	}
	else {
		?>
		<!-- <button type="submit" id="checkoutFormSubmit" name="confirm" value="1" class="vm-button-correct button-2 button checkout-button" onclick="javascript:return myValidator(userForm,true);"><span>Confirm Purchase</span> </button>-->
		<button type="submit" id="checkoutFormSubmit" class="vm-button-correct button-2 button checkout-button"><span>Proceed to payment</span> </button>
		<?php foreach($products as $pkey => $prow) { ?>
		<input type="hidden" name="cartpos[]" value="<?php echo $pkey ?>">
		<input type="hidden" name="quantity[<?php echo $pkey; ?>]" value="<?php echo $prow->quantity ?>"/>
		<?php } }?>
</div>
<?php
}

?>

<?php

$fields = $this->userFields['fields'];

$query = 'SELECT `virtuemart_country_id` AS value, `country_name` AS text FROM `#__virtuemart_countries`
               		WHERE `published` = 1 ORDER BY `country_name` ASC ';
$db = JFactory::getDBO();
$db->setQuery($query);
$countries = $db->loadObjectList();

if ($this->cart->getInCheckOut()){
	$task = '&task=checkout';
}
else {
	$task = '';
}
$url = JRoute::_ ('index.php?option=com_virtuemart&view='.$rview.$task, $this->useXHTML, $this->useSSL);

echo shopFunctionsF::getLoginForm (TRUE, FALSE, $url);

?>

<div class="border-one">
<h2 class="shop-heading">Checkout</h2>
<a class="back-link" href="<?php echo JRoute::_('index.php?com=com_virtuemart&view=cart') ?>"><i class="fa fa-angle-left"></i> Back to cart</a>
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
								echo ( $prow->images[0]->file_name )?$prow->images[0]->file_url:"images/imageNotAvailable.jpg";
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
			<div class="col-sm-8 col-xs-7">
				<p>Order Total</p>
			</div>
			<div class="col-sm-4 col-xs-5">
				<?php echo $currencyDisplay->createPriceDiv ('billTotal', '', $this->cart->cartPrices['billTotal'], FALSE); ?>
			</div>
		</div>
	</div><!--subtotal-->
	<?php if (VmConfig::get ('coupons_enable')) { ?>
	<div class="coupon">
		<div class="row">
			<div class="col-sm-12">
				<p>Have a Promo Code? <span class="toggle-coupon">Click here to enter your code <i class="fa fa-angle-down"></i></span></p>
				
				<form style="display:none" method="post" id="enterCouponCodeForm" name="enterCouponCode" action="<?php echo JRoute::_('index.php'); ?>">
					<input type="text" name="coupon_code" placeholder="Promo Code"  />
					<input class="details-button button-2" type="submit" name="setcoupon" title="Apply Coupon" value="Apply Coupon"/>
					
					<input type="hidden" name="option" value="com_virtuemart" />
					<input type="hidden" name="view" value="cart" />
					<input type="hidden" name="task" value="setcoupon" />
					<input type="hidden" name="controller" value="cart" />
					
				</form>
				
				<?php if (!empty($this->cart->cartData['couponCode'])) :?>
				<p class="box">
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
	<?php if(!empty($this->userFieldsCart['fields'])) { ?>
	<div class="note">
		<div class="row">
			<div class="col-sm-12">
				<textarea id="customer_note_field" name="customer_note" maxlength="2500" placeholder="Order Notes"></textarea>
			</div>
		</div>
	</div>
	<?php } ?>
	<?php }//else ?>
</div><!--cart-->
	
<div class="col-sm-7 col-xs-12">
<form method="post" id="userForm" name="userForm" action="<?php echo JRoute::_('index.php?option=com_virtuemart&view=user',$this->useXHTML,$this->useSSL) ?>" >
<div class="checkout-form">
<fieldset>
	<h2><?php
			echo vmText::_ ('COM_VIRTUEMART_USER_FORM_EDIT_BILLTO_LBL');
		?>
	</h2>

	<!--<form method="post" id="userForm" name="userForm" action="<?php echo JRoute::_ ('index.php'); ?>" class="form-validate">-->

<?php // captcha addition
	if(VmConfig::get ('reg_captcha')){
		JHTML::_('behavior.framework');
		JPluginHelper::importPlugin('captcha');
		$captcha_visible = vRequest::getVar('captcha');
		$dispatcher = JDispatcher::getInstance(); $dispatcher->trigger('onInit','dynamic_recaptcha_1');
		$hide_captcha = (VmConfig::get ('oncheckout_only_registered') or $captcha_visible) ? '' : 'style="display: none;"';
		?>
		<fieldset id="recaptcha_wrapper" <?php echo $hide_captcha ?>>
			<?php if(!VmConfig::get ('oncheckout_only_registered')) { ?>
				<span class="userfields_info"><?php echo vmText::_ ('COM_VIRTUEMART_USER_FORM_CAPTCHA'); ?></span>
			<?php } ?>
			<div id="dynamic_recaptcha_1"></div>
		</fieldset>
<?php }
	// end of captcha addition

	if (!class_exists ('VirtueMartCart')) {
		require(VMPATH_SITE . DS . 'helpers' . DS . 'cart.php');
	}

	if (count ($this->userFields['functions']) > 0) {
		echo '<script language="javascript">' . "\n";
		echo join ("\n", $this->userFields['functions']);
		echo '</script>' . "\n";
	}
?>
	
	<div class="row">
		<?php
		$bt_address = array();
		if($this->userDetails->JUser->get('id')) {
			$query = 'SELECT userinfos.*, states.state_name FROM #__virtuemart_userinfos as userinfos, #__virtuemart_states as states
						WHERE userinfos.address_type = "BT" AND userinfos.virtuemart_user_id = '.$this->userDetails->JUser->get ('id').' AND (states.virtuemart_state_id = userinfos.virtuemart_state_id OR userinfos.virtuemart_state_id = 0)';
			$db = JFactory::getDBO();
			$db->setQuery($query);
			$bt_address = $db->loadAssoc();
			$email = $this->userDetails->JUser->get('email');
			/* var_dump($this->userDetails->JUser->get ('id'));
			var_dump($bt_address); */
		}
		?>
		<div class="col-sm-6">
			<label for="first_name_field">First Name</label>
			<input type="text" id="first_name_field" name="first_name" size="30" placeholder="First Name" value="<?php if(isset($bt_address['first_name']) && $bt_address['first_name']) echo $bt_address['first_name']; ?>" required maxlength="32" />
		</div>
		<div class="col-sm-6">
			<label for="last_name_field">Last Name</label>
			<input type="text" id="last_name_field" name="last_name" size="30" placeholder="Last Name" value="<?php if(isset($bt_address['last_name']) && $bt_address['last_name']) echo $bt_address['last_name']; ?>" required maxlength="32"/>
		</div>
		<div class="col-sm-6">
			<label for="email_field">Email Address</label>
			<input type="email" id="email_field" name="email" size="30" placeholder="Email Address" value="<?php if(isset($email) && $email) echo $email; ?>"  required maxlength="100" />
		</div>
		<div class="col-sm-6">
			<label for="phone_1_field">Contact Phone</label>
			<input type="text" id="phone_1_field" name="phone_1" size="30" value="<?php if(isset($bt_address['phone_1']) && $bt_address['phone_1']) echo $bt_address['phone_1']; ?>"  placeholder="Contact No." required maxlength="32" />
		</div>
		<div class="col-sm-12">
			<label for="address_1_field">Address</label>
			<textarea style="overflow:auto;resize:none" id="address_1_field" name="address_1" placeholder="Address" required><?php if(isset($bt_address['address_1']) && $bt_address['address_1']) echo $bt_address['address_1']; ?></textarea>
		</div>
		<div class="col-sm-6">
			<label for="virtuemart_country_id">Country</label>
			<div class="select-style">
				<select id="virtuemart_country_id" name="virtuemart_country_id" class="vm-chzn-select" required>
					<option value="">Country</option>
					<?php foreach ($countries as $country) { ?>
					<option value="<?php echo $country->value ?>" <?php if(isset($bt_address['virtuemart_country_id']) && $bt_address['virtuemart_country_id']) { echo ($country->value == $bt_address['virtuemart_country_id']) ? 'selected' : ''; }?> ><?php echo $country->text ?></option>
					<?php } ?>
				</select>
			</div>
		</div>
		<div class="col-sm-6">
			<label for="city_field">City</label>
			<input type="text" id="city_field" name="city" size="30" placeholder="City" value="<?php if(isset($bt_address['city']) && $bt_address['city']) echo $bt_address['city']; ?>" required maxlength="32" />
		</div>
		<div class="col-sm-6">
			<label for="zip_field">Postal Code</label>
			<input type="text" id="zip_field" name="zip" size="30" placeholder="Postal Code" value="<?php echo $bt_address['zip'] ?>" required  maxlength="32" />
		</div>
		<div class="col-sm-6">
			<label for="virtuemart_state_id">State/Province/Region</label>
			<div class="select-style">
				<select  id="virtuemart_state_id" class="vm-chzn-select" name="virtuemart_state_id" >
					<option value="">State/Province/Region</option>
				</select>
			</div>
		</div>
	</div>

<?php
	if ($this->userDetails->JUser->get ('id')): ?>
	<h2><?php echo vmText::_ ('COM_VIRTUEMART_USER_FORM_ADD_SHIPTO_LBL'); ?></h2>
	
	<div class="row">
		<?php
			//Render shipping address list
				echo "<div class='col-sm-12'>";
				echo "<div class='select-style select-shipping'>";
				echo "<select required id='select-shipping'>";
				echo '<option value="">Select Shipping Address</option>';
				foreach($this->userDetails->userInfo as $address) {
					if($address->address_type == 'ST') {	
				?>
					<option data-userid = "<?php echo $this->userDetails->JUser->get ('id') ?>" data-userinfoid = "<?php echo $address->virtuemart_userinfo_id ?>" value="<?php echo $address->virtuemart_userinfo_id ?>" name="shipto">
						<?php echo $address->address_type_name ?>
					</option>
		<?php 
					}//if
				}
				echo "</select>";
				echo "</div>";
				echo "</div>";
		?>

		<div class="col-sm-12">
			<a class="add_shipping_address" href="#add_shipping_address"><i class="fa fa-plus"></i> Add Shipping Address</a>
		</div>
		
		<div style="display:none" id="shipto-form" >
			<div class="col-sm-12">
				<h5>New shipping address</h5>
				<p class="cancel"><i class="fa fa-times"></i> Cancel</p>
			</div>
			<div class="col-sm-6">
				<label for="shipto_address_type_name_field">Address Title</label>
				<input type="text" id="shipto_address_type_name_field" name="shipto_address_type_name" size="30" aria-required="true" aria-invalid="true" placeholder="Address Title" class="required" required="required" aria-required="true" aria-invalid="true" maxlength="32" />
			</div>
			<div class="col-sm-6">
				<label for="shipto_phone_1">Contact Phone</label>
				<input type="text" id="shipto_phone_1" name="shipto_phone_1" size="30" value="" placeholder="Contact No." aria-required="true" aria-invalid="true" class="required" required="required" aria-required="true" aria-invalid="true" maxlength="32">
			</div>
			<div class="col-sm-6">
				<label for="shipto_first_name_field">First Name</label>
				<input type="text" id="shipto_first_name_field" name="shipto_first_name" size="30" placeholder="First Name" class="required" required="required" aria-required="true" aria-invalid="true" maxlength="32" />
			</div>
			<div class="col-sm-6">
				<label for="shipto_last_name_field">Last Name</label>
				<input type="text" id="shipto_last_name_field" name="shipto_last_name" size="30" placeholder="Last Name" class="required" required="required" aria-required="true" aria-invalid="true" maxlength="32" />
			</div>
			<div class="col-sm-12">
				<label for="shipto_address_1_field">Address</label>
				<textarea style="overflow:auto;resize:none" id="shipto_address_1_field" name="shipto_address_1" placeholder="Address" required="required" aria-required="true" aria-invalid="true" class="required"></textarea>
			</div>
			<div class="col-sm-6">
				<label for="shipto_virtuemart_country_id">Country</label>
				<div class="select-style">
					<select id="shipto_virtuemart_country_id" name="shipto_virtuemart_country_id" aria-required="true" class="required vm-chzn-select" required="required" aria-required="true" aria-invalid="true">
						<option value="">Country</option>
						<?php foreach ($countries as $country) { ?>
						<option value="<?php echo $country->value ?>"><?php echo $country->text ?></option>
						<?php } ?>
					</select>
				</div>
			</div>
			<div class="col-sm-6">
				<label for="shipto_city_field">City</label>
				<input type="text" id="shipto_city_field" name="shipto_city" size="30" placeholder="City" class="required" required="required" aria-required="true" aria-invalid="true" maxlength="32" />
			</div>
			<div class="col-sm-6">
				<label for="shipto_zip_field">Postal Code</label>
				<input type="text" id="shipto_zip_field" name="shipto_zip" size="30" placeholder="Postal Code" class="required" required="required" aria-required="true" aria-invalid="true" maxlength="32" />
			</div>
			<div class="col-sm-6">
				<label for="shipto_virtuemart_state_id">State/Province/Region</label>
				<div class="select-style">
					<select  id="shipto_virtuemart_state_id" class="vm-chzn-select" name="shipto_virtuemart_state_id" disabled="disabled">
						<option value="">State/Province/Region</option>
					</select>
				</div>
			</div>
			<div class="col-sm-12 button-save">
				<button type="submit" class="button-2 save-address">Save Address</button>
			</div>
			<div class="inputs"></div>
		</div>
	</div>
	
	<?php
	endif; ?>
</div><!--checkout-form-->
	<?php
	renderControlButtons($this,$rview, $this->cart->products);
	?>
	
	<input type="hidden" name="option" value="com_virtuemart"/>
	<input type="hidden" name="view" value="cart"/>
	<input type="hidden" name="controller" value="cart"/>
	<input type="hidden" name="task" value="updatecart"/>
	<input type="hidden" name="layout" value="<?php echo $this->getLayout (); ?>"/>
	<input type="hidden" name="return" value="payment"/>
	<input type="hidden" name="virtuemart_user_id" value="<?php echo $this->userDetails->JUser->get ('id')?>"/>
	<input type="hidden" name="virtuemart_userinfo_id" value="<?php if(isset($bt_address['virtuemart_userinfo_id']) && $bt_address['virtuemart_userinfo_id']) echo $bt_address['virtuemart_userinfo_id']; ?>"/>
	
	<!--call ajax to update shipto-->
	<input type="hidden" name="shipto" id="shipto" value="">
	<!--<input type="hidden" name="address_type" value="<?php echo $this->address_type; ?>"/>-->
	
	<!--<input type="hidden" name="virtuemart_shipmentmethod_id" id="virtuemart_shipmentmethod_id" value="5">-->
	
	<?php
	echo JHtml::_ ('form.token');
	?>

</fieldset>
</form>
</div><!--col-->

</div><!--row-->
<link rel="stylesheet" href="media/jui/css/chosen.css" type="text/css" />
<script src="media/jui/js/chosen.jquery.min.js" type="text/javascript"></script>
<style>
	.select-style .chzn-container-single .chzn-single{
		width: 100%;
	}
	.select-style .chzn-container .chzn-results,.select-style .chzn-container .chzn-drop{
		width: 100%;
	}
	.col-sm-6 .select-style .chzn-container .chzn-results,.col-sm-6 .select-style .chzn-container .chzn-dro{
		width: 320px;	
	}
</style>
<script>
	jQuery(function($){
		
		//Display new shipping address form
		$('a.add_shipping_address').on('click', function(e){
			e.preventDefault();
			$("#select_shipping_chzn .chzn-single").removeClass("error");		
			$('#shipto-form').css('display', 'block');
			$('#shipto-form h5').html('New Shipping Address');
			$('.button-save').css('display', 'inline');
			$('.save-address').html('Save Address');
			$('#shipto_address_type_name_field').val(null);
			$('#shipto_address_phone').val(null);
			$('#shipto_first_name_field').val(null);
			$('#shipto_last_name_field').val(null);
			$('#shipto_address_1_field').val(null);
			$('#shipto_virtuemart_country_id option[value=""]').prop('selected', true);
			$('#shipto_zip_field').val(null);
			$('#shipto_city_field').val(null);
			$('.inputs').empty();
			$('#shipto_virtuemart_state_id').find('option, optgroup').remove();
			$('#shipto_virtuemart_state_id').append('<option>State/Province/Region</option>');
			$(this).hide();
			
		});
		var isMethodCountry = false;
		//Get shipping method
		function getShippingMethodByCountry(callback)
		{
			var virtuemart_country_id = $('#shipto_virtuemart_country_id').val();
			var virtuemart_state_id = $('#shipto_virtuemart_state_id').val();
			$pricebillTotal = $('span.PricebillTotal');
			$shipment = $('span.shipmentCost');
			$shipment.html('Loading...');
			$pricebillTotal.html('Loading...');
			$.ajax({
				method: 'get',
				url: '<?php echo JRoute::_('index.php?option=com_virtuemart&view=user&task=getShippingMethodByCountry&tmpl=component') ?>',
				data: {virtuemart_country_id: virtuemart_country_id, virtuemart_state_id: virtuemart_state_id},
				dataType: 'html',
				success: function(res)
				{
					//console.log(JSON.stringify(res));
					var $data = jQuery.parseJSON($(res).find('div.result').html());
				
					//alert(JSON.stringify($data));
					if($data.length != 0)
					{
						var shipment_params = $data.shipment_params.split('|');
						var shipment_cost = shipment_params[12].replace('shipment_cost=', '');
						var currency = $('span.PricebillTotal').text().split(' ');
						shipment_cost = shipment_cost.replace(/"/g, "");
						if(shipment_cost == null || shipment_cost == 0){
							$shipment.html('Free Shipping');
							var billTotal= parseFloat(<?php echo $this->cart->cartPrices['salesPrice'] ?>);
							var promo_cost = parseFloat(<?php echo $this->cart->cartPrices['salesPriceCoupon'] ?>);
							var total = parseFloat(billTotal + promo_cost).toFixed(2);
							if ( parseFloat(total) < 0 ) total = 0;
							$('span.PricebillTotal').html("S$ "+total);
						}
						else {
							//.replace('.', ',')
							$shipment.html("S$ "+parseFloat(shipment_cost).toFixed(2));
							
							//var total = parseFloat(parseFloat(<?php echo $this->cart->cartPrices['billTotal'] ?>) + parseFloat(shipment_cost)).toFixed(2).replace('.', ',');
							//salesPrice
							var billTotal= parseFloat(<?php echo $this->cart->cartPrices['salesPrice'] ?>);
							//shipment_cost
							var shipment_cost = parseFloat(shipment_cost);
							//promocode
							var promo_cost = parseFloat(<?php echo $this->cart->cartPrices['salesPriceCoupon'] ?>);
							
							//.replace('.', ',')
							var total = parseFloat(billTotal + promo_cost + parseFloat(shipment_cost)).toFixed(2);
							
							if ( parseFloat(total) < 0 ) total = 0;
							
							$('span.PricebillTotal').html("S$ "+total);
							
						}
						isMethodCountry = true;
						callback && callback($data);
					}
					else {
						isMethodCountry = false;	
						$shipment.html('');
						var country = $('#shipto_virtuemart_country_id option:selected').text();
						alert('We don\'t currently ship to '+country+', please choose another country.');
					}
				}
			});
		}
		//shipto
		jQuery(".select-shipping select").change(function(){
			$(".add_shipping_address").css("display","block");
			 $("#userForm").validate().resetForm();
			 $("#shipto_virtuemart_country_id_chzn .chzn-single").removeClass("error");	
			var shipto = $( ".select-shipping select option:selected" ).val();
			$("#shipto").val(shipto);						
			//run to choose shipto
			$.ajax({
				type: "POST",
				url: '<?php echo JRoute::_('index.php?option=com_virtuemart&view=cart&task=updateShipTo_ajax&tmpl=component') ?>',
				data: {shipto: shipto},
				success: function(res) {}
			});
		
		});
		
		//inital when not chose shipment
		//same as bill
		
		if ( !$('.select-shipping select').val() ) {
			//alert( $('.select-shipping select').val() );
			var virtuemart_user_id =  0;
			var virtuemart_userinfo_id = 0;
			$.ajax({
				type: "POST",
				url: '<?php echo JRoute::_('index.php?option=com_virtuemart&view=cart&task=updateShipTo_ajax&tmpl=component') ?>',
				data: {shipto: 0},
				success: function(res) 
				{
					//calculate again
					//shipping = 0
					//if exist  promo code  +=
					$shipment = $('span.shipmentCost');
					shipment_cost = 0;
					$shipment.html("S$ "+ shipment_cost);
						
					//var promocode =  $('span.PricesalesPriceCoupon');
					
					var promo_cost = parseFloat(<?php echo $this->cart->cartPrices['salesPriceCoupon'] ?>);
					var subtotal = parseFloat(<?php echo $this->cart->cartPrices['salesPrice'] ?>);
					//.replace('.', ',')
					var total = parseFloat(promo_cost + subtotal).toFixed(2);
					if ( parseFloat(total) < 0 ) total = 0;
					
					$('span.PricebillTotal').html("S$ "+total);
				}
			});
		}
		//Load shipping address
		
		$('.select-shipping select').change(function(){
			var virtuemart_user_id = $('option:selected', this).attr('data-userid');
			var virtuemart_userinfo_id = $('option:selected', this).attr('data-userinfoid');
			$('#shipto_virtuemart_state_id').find('option, optgroup').remove();
			$('#shipto_virtuemart_state_id').append('<option selected value="">State/Province/Region</option>');
			$("#select_shipping_chzn .chzn-single").removeClass("error");	
			//alert(virtuemart_user_id);
			if(virtuemart_user_id != null && virtuemart_userinfo_id != null) {
				$.ajax({
					method: 'get',
					url: '<?php echo JRoute::_('index.php?option=com_virtuemart&view=user&task=getSTAddress&tmpl=component') ?>',
					data: {virtuemart_user_id: virtuemart_user_id, virtuemart_userinfo_id: virtuemart_userinfo_id},
					dataType: 'html',
					success: function(res) 
					{
						var $data = jQuery.parseJSON($(res).find('div.result').html());
						$('a.add_shipping_address').hide();
						$('#shipto-form').css('display', 'block');
						$('.button-save').css('display', 'none');
						$('.save-address').html('Save Address');
						$('#shipto-form h5').html('Your shipping address');
						$('#shipto_address_type_name_field').val($data.address_type_name);
						$('#shipto_phone_1').val($data.phone_1);
						$('#shipto_first_name_field').val($data.first_name);
						$('#shipto_last_name_field').val($data.last_name);
						$('#shipto_address_1_field').val($data.address_1);
						$('#shipto_virtuemart_country_id option[value='+$data.virtuemart_country_id+']').prop('selected', true);
						
						//update chosen
						$("#shipto_virtuemart_country_id").val($data.virtuemart_country_id);
                        $("#shipto_virtuemart_country_id").trigger("liszt:updated");
						
						
						$('#shipto_zip_field').val($data.zip);
						$('#shipto_city_field').val($data.city);
						if($data.virtuemart_state_id != 0){
							$('#shipto_virtuemart_state_id').removeAttr('disabled');
							$('#shipto_virtuemart_state_id').parent('.select-style').css('background-color', '#FFF');
							$('#shipto_virtuemart_state_id').append('<option selected value = "'+$data.virtuemart_state_id+'">'+$data.state_name+'</option>');
							//update chosen
							$("#shipto_virtuemart_state_id").val($data.virtuemart_state_id);
							$("#shipto_virtuemart_state_id").trigger("liszt:updated");
						}
						$('.inputs').html('<input type="hidden" name="shipto_virtuemart_userinfo_id" value="'+virtuemart_userinfo_id+'" />');		
						getShippingMethodByCountry(function(data){
							var virtuemart_shipmentmethod_id = data.virtuemart_shipmentmethod_id;
							//alert(virtuemart_shipmentmethod_id);
							$.ajax({
								method: 'get',
								data: {virtuemart_shipmentmethod_id: data.virtuemart_shipmentmethod_id},
								url: '<?php echo JRoute::_('index.php?option=com_virtuemart&view=user&task=saveShippingMethod&tmpl=component') ?>',
							
							});
						});
					},
					error: function(error) 
					{
						$("#select_shipping_chzn .chzn-single").addClass("error");		
						alert('Something went wrong, please try again.');
					}
				});
			}
			else{
				$('#shipto-form').css('display', 'none');	
				var virtuemart_user_id =  0;
				var virtuemart_userinfo_id = 0;
				$.ajax({
					type: "POST",
					url: '<?php echo JRoute::_('index.php?option=com_virtuemart&view=cart&task=updateShipTo_ajax&tmpl=component') ?>',
					data: {shipto: 0},
					success: function(res) 
					{
						//calculate again
						//shipping = 0
						//if exist  promo code  +=
						$shipment = $('span.shipmentCost');
						shipment_cost = 0;
						$shipment.html("S$ "+ shipment_cost);
							
						//var promocode =  $('span.PricesalesPriceCoupon');
						
						var promo_cost = parseFloat(<?php echo $this->cart->cartPrices['salesPriceCoupon'] ?>);
						var subtotal = parseFloat(<?php echo $this->cart->cartPrices['salesPrice'] ?>);
						//.replace('.', ',')
						var total = parseFloat(promo_cost + subtotal).toFixed(2);
						if ( parseFloat(total) < 0 ) total = 0;
						
						$('span.PricebillTotal').html("S$ "+total);
					}
				});
			}
		});
	
		//Save shipping address
		$('.save-address').on('click', function(e){
			e.preventDefault();
			
			//validate only for shipping adress
			//remove validate of billing
			$("#first_name_field").removeAttr("required");
			$("#last_name_field").removeAttr("required");
			$("#phone_1_field").removeAttr("required");
			$("#virtuemart_country_id").removeAttr("required");
			$("#address_1_field").removeAttr("required");
			$("#city_field").removeAttr("required");
			$("#zip_field").removeAttr("required");
			
			var validate = false;
			$("#userForm").validate({
				rules: {
					shipto_zip: {
						required: true,
						number: true
					}
				}
			});
			if (!$("#userForm").validate().form()) {
				if ( $("#shipto_virtuemart_country_id").chosen().val().length <= 0 ) $("#shipto_virtuemart_country_id_chzn .chzn-single").addClass("error");
				return false; //doesn't validate
			}
			else{
				validate = true;
			}
			
			if ( $("#shipto_virtuemart_country_id").chosen().val().length > 0 ){
				validate = true;
				$("#shipto_virtuemart_country_id_chzn .chzn-single").removeClass("error");	
			}	
			else{
				$("#shipto_virtuemart_country_id_chzn .chzn-single").addClass("error");
				return  false;
			}
			
			//alert(validate);
			if ( validate == true){
				
				
				var data = $('#shipto-form :input').serialize();
				
				$this = $(this);
				$this.html('Saving...');
				$.ajax({
					method: 'post',
					data: data+'&address_type=ST',
					url: '<?php echo JRoute::_('index.php?option=com_virtuemart&view=user&task=saveUserAddress') ?>',
					success: function(res)
					{
						$.ajax({
							method: 'get',
							url: '<?php echo JRoute::_('index.php?option=com_virtuemart&view=user&task=loadSTAddress&tmpl=component') ?>',
							data: {virtuemart_user_id: <?php echo $this->userDetails->JUser->get ('id') ?>},
							dataType: 'html',
							success: function(res)
							{
								getShippingMethodByCountry(function(data){
									var virtuemart_shipmentmethod_id = data.virtuemart_shipmentmethod_id;
									$.ajax({
										method: 'get',
										data: {virtuemart_shipmentmethod_id: data.virtuemart_shipmentmethod_id},
										url: '<?php echo JRoute::_('index.php?option=com_virtuemart&view=user&task=saveShippingMethod&tmpl=component') ?>',
									});
								});
								var $data = jQuery.parseJSON($(res).find('div.result').html());
								$('.select-shipping select').find('option').remove();
								$('.select-shipping select').append('<option value="">Select Shipping Address</option>');
								$.each($data, function(i, val){
									$('.select-shipping select').append('<option data-userid = "'+val.virtuemart_user_id+'" data-userinfoid = "'+val.virtuemart_userinfo_id+'">'+val.address_type_name+'</option>');
								});
								var lastValue = $('#select-shipping option:nth-child(2)').val();
								$("#select-shipping").val(lastValue);
								$("#select-shipping").trigger("liszt:updated");
								$('.cancel').trigger('click');
							},
							error: function(error) {
								alert('Cannot save your shipping address, please try again.');
							}
						});
					}
				});	
			}
			
		});
		
		$('#shipto_virtuemart_country_id').on('change', function(){
			$("#shipto_virtuemart_country_id_chzn .chzn-single").removeClass("error");	
			setTimeout(getShippingMethodByCountry, 1000);
		});
		$('#shipto_virtuemart_state_id').on('change', function(){
			setTimeout(getShippingMethodByCountry, 1000);
		});
		
		//if input and select onchange, display save address button
		$('#shipto-form input').on('input', function(){
			if($('.button-save').css('display') == 'none') {
				$('.button-save').css('display', 'inline');
			}
		});
		
		$('#shipto-form select').on('change', function(){
			if($('.button-save').css('display') == 'none') {
				$('.button-save').css('display', 'inline');
			}
		});
	
		$('.cancel').on('click', function(){
			$('#shipto-form').css('display', 'none');
			$('a.add_shipping_address').show();
		});
		
		$('.toggle-coupon').toggle(function () {
			$(".coupon form").css({display: "block"});
		}, function () {
			$(".coupon form").css({display: "none"});
		});
		
		//check validate
		$('#checkoutFormSubmit').on('click', function(e){
			$("#first_name_field").attr("required");
			$("#last_name_field").attr("required");
			$("#phone_1_field").attr("required");
			$("#virtuemart_country_id").attr("required");
			$("#address_1_field").attr("required");
			$("#city_field").attr("required");
			$("#zip_field").attr("required");
			$("#userForm").validate({
				  rules: {
					phone_1: {
						required: true,
						number: true
					},
					zip: {
						required: true,
						number: true
					},
					shipto_zip: {
						required: true,
						number: true
					 }
				  }
			});
		
			if ( $('.select-shipping select').val() != "" ){
				var shipto = $( ".select-shipping select option:selected" ).val();
				$("#shipto").val(shipto);
			}
			
			//validate chosen
			if ( $("#virtuemart_country_id").chosen().val().length > 0 ){
				$("#virtuemart_country_id_chzn .chzn-single").removeClass("error");	
			}	
			else{
				$("#virtuemart_country_id_chzn .chzn-single").addClass("error");
				return  false;
			} 
			
			if ( $("#select-shipping").chosen().val().length > 0 ){
				$("#select_shipping_chzn .chzn-single").removeClass("error");	
			}	
			else{
				alert("Please enter Shipping Address");
				$("#select_shipping_chzn .chzn-single").addClass("error");
				return  false;
			}
			
			if ( $("#shipto_virtuemart_country_id").chosen().val().length > 0 ){
				$("#shipto_virtuemart_country_id_chzn .chzn-single").removeClass("error");	
			}	
			else{
				$("#shipto_virtuemart_country_id_chzn .chzn-single").addClass("error");
				return  false;
			}
			
			if ( !isMethodCountry ) return false;
		});
		
		//chosen	
		$.validator.setDefaults({ ignore: ":hidden:not(.chosen-select)" });
		$(".select-style select").chosen({
			"disable_search": true
		});	
		
		//Change color for disabled select, other events for state select are in vmsite.js
		$('#virtuemart_state_id[disabled="disabled"], #shipto_virtuemart_state_id[disabled="disabled"]').parent('.select-style').css('background-color', '#F8F8F8');
	
	});
</script>

<?php }//if !guest
else {
	$app = JFactory::getApplication();
	$app->redirect(JRoute::_('index.php?option=com_users&view=login&Itemid=123', false), 'Please login first', 'message');
} ?>