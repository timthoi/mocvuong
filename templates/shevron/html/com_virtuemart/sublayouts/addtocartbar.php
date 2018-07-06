<?php
/**
 *
 * Show the product details page
 *
 * @package    VirtueMart
 * @subpackage
 * @author Max Milbers
 * @todo handle child products
 * @link http://www.virtuemart.net
 * @copyright Copyright (c) 2015 VirtueMart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * VirtueMart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * @version $Id: default_addtocart.php 7833 2014-04-09 15:04:59Z Milbo $
 */
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');
$product = $viewData['product'];

if(isset($viewData['rowHeights'])){
	$rowHeights = $viewData['rowHeights'];
} else {
	$rowHeights['customfields'] = TRUE;
}

$init = 1;
if(isset($viewData['init'])){
	$init = $viewData['init'];
}

if(!empty($product->min_order_level) and $init<$product->min_order_level){
	$init = $product->min_order_level;
}

$step=1;
if (!empty($product->step_order_level)){
	$step=$product->step_order_level;
	if(empty($product->min_order_level) and !isset($viewData['init'])){
		$init = $step;
	}
}

$maxOrder= '';
if (!empty($product->max_order_level)){
	$maxOrder = ' max="'.$product->max_order_level.'" ';
}

$addtoCartButton = '';
if(!VmConfig::get('use_as_catalog', 0)){
	if(!$product->addToCartButton and $product->addToCartButton!==''){
		$addtoCartButton = shopFunctionsF::getAddToCartButton ($product->orderable);
	} else {
		$addtoCartButton = $product->addToCartButton;
	}

}
$position = 'addtocart';
//if (!empty($product->customfieldsSorted[$position]) or !empty($addtoCartButton)) {


if (!VmConfig::get('use_as_catalog', 0)  ) { 
$stockhandle = VmConfig::get ('stockhandle', 'none');

$addional_class = '';

if ( !$product->low_stock_notification ) $product->low_stock_notification = 5;

if (($stockhandle == 'disableit' or $stockhandle == 'disableadd') and ($product->product_in_stock - $product->product_ordered) < 1) $addional_class  = 'outstock';
else
	if ($product->product_in_stock - $product->product_ordered <= $product->low_stock_notification) $addional_class  = 'lowstock'; 
?>

	<div class="addtocart-bar">
<?php if ( $addional_class == 'lowstock'): ?>
	<span style="width:100%;display:block;font-size: 18px;margin-bottom: 15px;">Product availability: Low in stock</span>
<?php endif; ?>
<div class="col-md-5 nopadding <?php echo $addional_class ?>">
		<div class="quantity-box-container">

	<?php
	// Display the quantity box	
	if ( $addional_class == 'outstock') { ?>
		<!--<span class="outstockstyle"><span class="button ribbon">OUT OF STOCK</span></span>-->
		<span>Product availability: Out of stock</span>
		<a href="<?php echo JRoute::_ ('index.php?option=com_virtuemart&view=productdetails&layout=notify&virtuemart_product_id=' . $product->virtuemart_product_id); ?>" class="notify"><?php echo vmText::_ ('COM_VIRTUEMART_CART_NOTIFY') ?></a>
		
		<?php		
	} else {
		$tmpPrice = (float) $product->prices['costPrice'];
		if (!( VmConfig::get('askprice', true) and empty($tmpPrice) ) ) { ?>			
			<?php if ($product->orderable) { ?>
			<label for="quantity<?php echo $product->virtuemart_product_id; ?>" class="quantity_box"><?php echo vmText::_('COM_VIRTUEMART_CART_QUANTITY'); ?> </label> 

			<input type="text" 	onblur="Virtuemart.checkQuantity(this,<?php echo $step?>,'<?php echo vmText::_ ('COM_VIRTUEMART_WRONG_AMOUNT_ADDED')?>');"
				onclick="Virtuemart.checkQuantity(this,<?php echo $step?>,'<?php echo vmText::_ ('COM_VIRTUEMART_WRONG_AMOUNT_ADDED')?>');"
				onchange="Virtuemart.checkQuantity(this,<?php echo $step?>,'<?php echo vmText::_ ('COM_VIRTUEMART_WRONG_AMOUNT_ADDED')?>');"
				onsubmit="Virtuemart.checkQuantity(this,<?php echo $step?>,'<?php echo vmText::_ ('COM_VIRTUEMART_WRONG_AMOUNT_ADDED')?>');"
				title="<?php echo  vmText::_('COM_VIRTUEMART_CART_UPDATE') ?>" class="datalist-quantities quantity-input js-recalculate" size="3" maxlength="4" name="quantity[<?php echo (isset($pkey))?$pkey:""; ?>]" value="<?php  if ( isset($prow->quantity) ) echo ($prow->quantity==null )?"1":$prow->quantity; else echo "1";  ?>" />
				
			<?php }
	?>
	</div>
</div>
<div class="col-md-7 nopadding">
	<?php
			if(!empty($addtoCartButton)){
				?><div class="addtocart-button">
				<?php echo $addtoCartButton ?>
				</div> <?php
			} ?>
			<input type="hidden" name="virtuemart_product_id[]" value="<?php echo $product->virtuemart_product_id ?>"/>
			<noscript><input type="hidden" name="task" value="add"/></noscript> <?php
		}
	} ?>
</div>	
</div><?php
} ?>




<script type="text/javascript">
	jQuery(document).ready(function ($) {
		/* var source = [
			"1",
			"2",
			"3",
			"4",
			"5",
			"6",
			"7",
			"8",
			"9",
			"10",
		];
		// Create a jqxComboBox
		
		
		$("#jqxcombobox").jqxComboBox({ source: source, selectedIndex: 0, width: '120px', height: '35px' });
		var a =  $(".datalist-quantities").val();
		if ( a.length != 0	)
			$("#jqxcombobox").jqxComboBox('val', a );
		else{
			$("#jqxcombobox").jqxComboBox('val', "1" );
			$(".datalist-quantities").val(1);
		}
		// bind to 'select' event.
		$('#jqxcombobox').bind('select', function (event) {
			var args = event.args;
			var item = $('#jqxcombobox').jqxComboBox('getItem', args.index);
			$(".datalist-quantities").val(item.label);
		});
		
		// bind to 'change' event.
		$('#jqxcombobox').on('change', function (event) {
			var args = event.args;
			var value = $("#jqxcombobox").val();
			$(".datalist-quantities").val(value);
		});  */
		
		//$(".btt-fav").detach().insertBefore("input.addtocart-button");
	});
</script>
       
   