<fieldset class="vm-fieldset-pricelist">
<div class="cart-summary">
<div class="header row">
	<div class="first col-sm-4 col-xs-5">Product</div>
	<!--<div class="col-sm-2 hidden-xs"><?php echo vmText::_ ('COM_VIRTUEMART_CART_PRICE') ?></div>-->
	<div class="col-sm-3 hidden-xs"><?php  ?></div>
	<div class="col-sm-3 col-xs-3"><?php echo vmText::_ ('COM_VIRTUEMART_CART_QUANTITY') ?></div>

	<?php if (VmConfig::get ('show_tax')) {
		$tax = vmText::_ ('COM_VIRTUEMART_CART_SUBTOTAL_TAX_AMOUNT');
		if(!empty($this->cart->cartData['VatTax'])){
			reset($this->cart->cartData['VatTax']);
			$taxd = current($this->cart->cartData['VatTax']);
			$tax = $taxd['calc_name'] .' '. rtrim(trim($taxd['calc_value'],'0'),'.').'%';
		}
		?>
	<?php } ?>
	<div class="col-sm-2 col-xs-4"><?php echo vmText::_ ('COM_VIRTUEMART_CART_TOTAL') ?></div>
	<!--<div class="col-sm-1 col-xs-2"></div>-->
</div>

<?php
$i = 1;
$j = 0;

foreach ($this->cart->products as $pkey => $prow) { ?>

<div class="row item <?php echo ($j==count($this->cart->products)-1)?"last":""?>">
	<input type="hidden" name="cartpos[]" value="<?php echo $pkey ?>">
	<div class="col-sm-4 col-xs-5 first">
		<?php if ($prow->virtuemart_media_id):?>
		<!--product-image-->
			<!--delete-from-cart-->
		<button type="submit" class="cart-checkout vmicon vm2-remove_from_cart" name="delete.<?php echo $pkey ?>" title="<?php echo vmText::_ ('COM_VIRTUEMART_CART_DELETE') ?>"><i class="fa fa-times"></i></button>
		
		<span class="cart-images">
		<a href="<?php echo $prow->url?>">
			<?php
			if (!empty($prow->images[0])) {
				echo $prow->images[0]->displayMediaThumb ('', FALSE);
			}
			?>
		</a>
		</span>
		
		<?php else: ?>
		
		<button type="submit" class="cart-checkout vmicon vm2-remove_from_cart" name="delete.<?php echo $pkey ?>" title="<?php echo vmText::_ ('COM_VIRTUEMART_CART_DELETE') ?>"><i class="fa fa-times"></i></button>
		<a href="<?php echo $prow->url?>">
			<span class="cart-images">
				<img src="images/imageNotAvailable.jpg" alt="ComingSoon">
			</span>
		</a>
		<?php endif; ?>
		
	</div>
	
	<div class="col-sm-3 hidden-xs">
		<!--product-name-->
		<?php echo '<a href="'.$prow->url.'" class="product-name">'.$prow->product_name.'</a>';
			echo $this->customfieldsModel->CustomsFieldCartDisplay ($prow);
		?>
		<!--product-name-->
	</div>
	<?php /*
	<!--product-unit-price-->
	<div class="col-sm-2 hidden-xs">
		<?php
		if (VmConfig::get ('checkout_show_origprice', 1) && $prow->prices['discountedPriceWithoutTax'] != $prow->prices['priceWithoutTax']) {
			echo '<span class="line-through">' . $this->currencyDisplay->createPriceDiv ('basePriceVariant', '', $prow->prices, TRUE, FALSE) . '</span>';
		}

		if ($prow->prices['discountedPriceWithoutTax']) {
			echo $this->currencyDisplay->createPriceDiv ('discountedPriceWithoutTax', '', $prow->prices, FALSE, FALSE);
		} else {
			echo $this->currencyDisplay->createPriceDiv ('basePriceVariant', '', $prow->prices, FALSE, FALSE);
		}
		?>
	</div>
	<!--product-unit-price-->
	*/ ?>
	
	
	<!--product-quantity-desktop-->
	<div class="col-sm-3 col-xs-3">
		<?php
		if ($prow->step_order_level)
			$step=$prow->step_order_level;
		else
			$step=1;
		if($step==0)
			$step=1;
		?>
		<div class="quantity-container">
		<?php /* 
			<span class="quantity-controls js-recalculate">
				<button type="button" class="quantity-controls quantity-minus"><i class="fa fa-minus"></i></button>
			</span>
		
			<span class="quantity-box">
			<input type="text"
				onblur="Virtuemart.checkQuantity(this,<?php echo $step?>,'<?php echo vmText::_ ('COM_VIRTUEMART_WRONG_AMOUNT_ADDED')?>');"
				onclick="Virtuemart.checkQuantity(this,<?php echo $step?>,'<?php echo vmText::_ ('COM_VIRTUEMART_WRONG_AMOUNT_ADDED')?>');"
				onchange="Virtuemart.checkQuantity(this,<?php echo $step?>,'<?php echo vmText::_ ('COM_VIRTUEMART_WRONG_AMOUNT_ADDED')?>');"
				onsubmit="Virtuemart.checkQuantity(this,<?php echo $step?>,'<?php echo vmText::_ ('COM_VIRTUEMART_WRONG_AMOUNT_ADDED')?>');"
				title="<?php echo  vmText::_('COM_VIRTUEMART_CART_UPDATE') ?>" class="datalist-quantities quantity-input js-recalculate" size="3" maxlength="4" name="quantity[<?php echo $pkey; ?>]" value="<?php echo $prow->quantity ?>" />
			</span>
		
			<span class="quantity-controls js-recalculate">
				<button type="button" class="quantity-controls quantity-plus"><i class="fa fa-plus"></i></button>
			</span>
		*/?>	
			<input type="text"
				onblur="Virtuemart.checkQuantity(this,<?php echo $step?>,'<?php echo vmText::_ ('COM_VIRTUEMART_WRONG_AMOUNT_ADDED')?>');"
				onclick="Virtuemart.checkQuantity(this,<?php echo $step?>,'<?php echo vmText::_ ('COM_VIRTUEMART_WRONG_AMOUNT_ADDED')?>');"
				onchange="Virtuemart.checkQuantity(this,<?php echo $step?>,'<?php echo vmText::_ ('COM_VIRTUEMART_WRONG_AMOUNT_ADDED')?>');"
				onsubmit="Virtuemart.checkQuantity(this,<?php echo $step?>,'<?php echo vmText::_ ('COM_VIRTUEMART_WRONG_AMOUNT_ADDED')?>');"
				title="<?php echo  vmText::_('COM_VIRTUEMART_CART_UPDATE') ?>" class="datalist-quantities quantity-input js-recalculate" size="3" maxlength="4" name="quantity[<?php echo $pkey; ?>]" value="<?php echo $prow->quantity ?>" />
			
		
		</div>
	</div>
	<!--product-quantity-desktop-->

	<!--price-total-->
	<div class="col-sm-2 col-xs-4">
		<?php
		if (VmConfig::get ('checkout_show_origprice', 1) && !empty($prow->prices['basePriceWithTax']) && $prow->prices['basePriceWithTax'] != $prow->prices['salesPrice']) {
			echo '<span class="line-through">' . $this->currencyDisplay->createPriceDiv ('basePriceWithTax', '', $prow->prices, TRUE, FALSE, $prow->quantity) ;
		}
		elseif (VmConfig::get ('checkout_show_origprice', 1) && empty($prow->prices['basePriceWithTax']) && $prow->prices['basePriceVariant'] != $prow->prices['salesPrice']) {
			echo '<span class="line-through">' . $this->currencyDisplay->createPriceDiv ('basePriceVariant', '', $prow->prices, TRUE, FALSE, $prow->quantity) ;
		}
		echo $this->currencyDisplay->createPriceDiv ('salesPrice', '', $prow->prices, FALSE, FALSE, $prow->quantity) ?>
	</div>
	<!--price-total-->
	
	
</div>
	<?php
	$i = ($i==1) ? 2 : 1;
	$j++;
} ?>

<div class="row subtotal">
	<div class="col-sm-12">
	<button type="submit" class="vmicon vm2-add_quantity_cart button-2 hidden-xs" id="updateall">Update Cart</button>
	<span class="subtotal-text">Subtotal</span>
	<?php if (VmConfig::get ('show_tax')) { ?>
	<?php echo "<span  class='priceColor2'>" . $this->currencyDisplay->createPriceDiv ('taxAmount', '', $this->cart->cartPrices, FALSE) . "</span>" ?>
	<?php } ?>
	<?php echo "<span  class='priceColor2'>" . $this->currencyDisplay->createPriceDiv ('discountAmount', '', $this->cart->cartPrices, FALSE) . "</span>" ?>
	<?php echo $this->currencyDisplay->createPriceDiv ('salesPrice', '', $this->cart->cartPrices, FALSE) ?>
	</div>
</div>

</div>
</fieldset>

<?php
$js = "
jQuery(document).ready(function($) {
//Update all quantities
$('#updateall').click(function() {
//Find order item lines forms
$('form').has('input[name=task][value=update]').each(function() {
var editForm = $(this);
$.ajax(editForm.attr('action'), 
{
//we need to submit these forms asynchronously
async: false,
data:editForm.serialize()
});
//Finaly relad the page to see the changes
location.reload();
});
})
});
";
JFactory::getDocument()->addScriptDeclaration($js);
?>

<script type="text/javascript">
	var sum_j = <?php echo json_encode($j); ?>;
	jQuery(document).ready(function ($) {
		$('.datalist-quantities').on('keypress', function (e) {
			if(e.which === 13){
				//Disable button delete to prevent multiple submit
				$('.vm2-remove_from_cart').attr('disabled', 'disabled');
				$('#updateall').trigger( "click" );
			}
		});
		
		
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
		
		for (i = 0; i < sum_j; i++) {
			(function(i){ 
				setTimeout(function() { 
					t = "#jqxcombobox"+i;
					$(t).jqxComboBox({ source: source, selectedIndex: 0, width: '120px', height: '35px' });
					
					var a =  $(t).parent().find(".datalist-quantities").val();
					if ( a.length != 0	)
					$(t).jqxComboBox('val', a );
					else{
						$(t).jqxComboBox('val', "1" );
						$(t).parent().find(".datalist-quantities").val(1);
					}
					
					
					// bind to 'select' event.
					$(t).bind('select', function (event) {
						var args = event.args;
						var item = $(t).jqxComboBox('getItem', args.index);
						$(this).parent().find(".datalist-quantities").val(item.label);
					});
					
					// bind to 'change' event.
					$(t).on('change', function (event) {
						var args = event.args;
						var value = $(this).val();
						$(this).parent().find(".datalist-quantities").val(value);
					}); 
					
					
					
				}, 100);
			})(i);
		} */
	});
</script>