<?php
/**
 *
 * Layout for the shopping cart
 *
 * @package    VirtueMart
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
 * @version $Id: cart.php 2551 2010-09-30 18:52:40Z milbo $
 */

// Check to ensure this file is included in Joomla!
defined ('_JEXEC') or die('Restricted access');

JHtml::_ ('behavior.formvalidation');
vmJsApi::addJScript('vm.STisBT',"
	jQuery(document).ready(function($) {

		if ( $('#STsameAsBTjs').is(':checked') ) {
			$('#output-shipto-display').hide();
		} else {
			$('#output-shipto-display').show();
		}
		$('#STsameAsBTjs').click(function(event) {
			if($(this).is(':checked')){
				$('#STsameAsBT').val('1') ;
				$('#output-shipto-display').hide();
			} else {
				$('#STsameAsBT').val('0') ;
				$('#output-shipto-display').show();
			}
			var form = jQuery('#checkoutFormSubmit');
			document.checkoutForm.submit();
		});
	});
");

vmJsApi::addJScript('vm.checkoutFormSubmit','
	jQuery(document).ready(function($) {
		jQuery(this).vm2front("stopVmLoading");
		jQuery("#checkoutFormSubmit").bind("click dblclick", function(e){
			jQuery(this).vm2front("startVmLoading");
			e.preventDefault();
			jQuery(this).attr("disabled", "true");
			jQuery(this).removeClass( "vm-button-correct" );
			jQuery(this).addClass( "vm-button" );
			jQuery(this).fadeIn( 400 );
			var name = jQuery(this).attr("name");
			$("#checkoutForm").append("<input name=\""+name+"\" value=\"1\" type=\"hidden\">");
			$("#checkoutForm").submit();
		});
	});
');

vmJsApi::addJScript('vmprices');

$this->addCheckRequiredJs();
 ?>
 
<?php if(!empty($this->cart->products)){ ?>
<div class="cart-view">
	<div class="vm-cart-header-container">
		<div class="border-one">
			<h2 class="shop-heading"><?php echo vmText::_ ('COM_VIRTUEMART_CART_TITLE'); ?></h2>
		</div>
		<?php if (VmConfig::get ('oncheckout_show_steps', 1) && $this->checkout_task === 'confirm') {
		echo '<div class="checkoutStep" id="checkoutStep4">' . vmText::_ ('COM_VIRTUEMART_USER_FORM_CART_STEP4') . '</div>';
	} ?>
	</div>
	<?php $taskRoute = (isset($taskRoute))?$taskRoute:"";?>
	<form method="post" id="checkoutForm" name="checkoutForm" action="<?php echo JRoute::_ ('index.php?option=com_virtuemart&view=cart' . $taskRoute, $this->useXHTML, $this->useSSL); ?>">
		<?php
		if(VmConfig::get('multixcart')=='byselection'){
			if (!class_exists('ShopFunctions')) require(VMPATH_ADMIN . DS . 'helpers' . DS . 'shopfunctions.php');
			echo shopFunctions::renderVendorFullVendorList($this->cart->vendorId);
			?><input type="submit" name="updatecart" title="<?php echo vmText::_('COM_VIRTUEMART_SAVE'); ?>" value="<?php echo vmText::_('COM_VIRTUEMART_SAVE'); ?>" class="button"  style="margin-left: 10px;"/><?php
		}

		// This displays the pricelist MUST be done with tables, because it is also used for the emails
		echo $this->loadTemplate ('pricelist');

		if (!empty($this->checkoutAdvertise)) {
			?> <div id="checkout-advertise-box"> <?php
			foreach ($this->checkoutAdvertise as $checkoutAdvertise) {
				?>
				<div class="checkout-advertise">
					<?php echo $checkoutAdvertise; ?>
				</div>
				<?php
			}
			?></div><?php
		}

		?>
		<div class="checkout-button-top">
			<a href="<?php echo JRoute::_("index.php?option=com_content&view=article&id=11&Itemid=213", false);?>" id="continue-btt" class="vm-button-correct button-2"><span>CONTINUE SHOPPING</span></a>
		</div>
		
		<div class="checkout-button-top">
			<?php if(JFactory::getUser()->guest):?>
				<a href="<?php echo JRoute::_('index.php?option=com_users&view=login&Itemid=123&return=1', false)?>" class="checkoutFormSubmit vm-button-correct button-2"><span>Checkout</span></a>
			<?php else:  ?>
				<button type="submit" id="checkoutFormSubmit" name="checkout" value="1" class="checkoutFormSubmit vm-button-correct button-2"><span>Checkout</span> </button>
			<?php endif;?>
			
			
		</div>
		

		<?php // Continue and Checkout Button END ?>
		<input type='hidden' name='order_language' value='<?php echo $this->order_language; ?>'/>
		<input type='hidden' id="task" name='task' value='updatecart'/>
		<input type='hidden' name='option' value='com_virtuemart'/>
		<input type='hidden' id="view" name='view' value='cart'/>
		<input type='hidden' id="addrtype" name='addrtype' value=''/>
		<?php
		$user = JFactory::getUser();
		?>
		<input type='hidden' id="guest" name='guest' value='<?php echo $user->guest ?>'/>
	</form>
</div>
<?php } else {
?>
<div class="cart-view">
	<h2 class="shop-heading"><?php echo vmText::_ ('COM_VIRTUEMART_CART_TITLE'); ?></h2>
	<div class="cart-empty-container">
		<div class="cart-empty"></div>
		<div class="cart-empty-text">
			<h4>Your cart is currently empty.</h4>
			<a href="<?php echo JRoute::_('index.php?option=com_content&view=article&id=11',false) ?> " rel="nofollow">
				<button type="button" class="button-2">Shop Now</button>
			</a>
		</div>
	</div>
</div>
<?php	
}
?>
<script>
	jQuery(document).ready(function() {
		
		//get view port
		function viewport() {
			var e = window, a = 'inner';
			if (!('innerWidth' in window )) {
				a = 'client';
				e = document.documentElement || document.body;
			}
			return { width : e[ a+'Width' ] , height : e[ a+'Height' ] };
		}
		
		var win_width = viewport().width;
		var guest = jQuery('#guest').val();
		
		//on desktop, if user is guest and checkout then show the login modal 
		if(win_width > 991) 
		{
			if(guest == '1') {
				jQuery('#checkoutFormSubmit-custom').on('click', function(e) {
					jQuery('#btl-panel-login').trigger('click');
					jQuery('#checkoutFormSubmit-custom').removeAttr('disabled');
					return false;
				})
			}
		}
		
		//on mobile, if user is guest and checkout then redirect to login/register page
		if(win_width <= 991)
		{
			if(guest == '1') {
				jQuery('#checkoutFormSubmit-custom').on('click', function(e) {
					e.preventDefault();
					window.location.href = "<?php echo JRoute::_('index.php?option=com_users&view=login&Itemid=123', false) ?>";
				})
			}
		}
		
		jQuery("#task").val("updatecart");
		jQuery("#view").val("cart");
		jQuery("#addrtype").val("");
		
		jQuery('#checkoutFormSubmit').bind('click',function(){
			jQuery("#task").val("editaddresscart");
			jQuery("#view").val("user");
			jQuery("#addrtype").val("BT");
		});
		
		
	})
</script>
