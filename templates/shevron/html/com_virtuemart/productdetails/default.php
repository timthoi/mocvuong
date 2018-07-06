<?php
/**
 *
 * Show the product details page
 *
 * @package	VirtueMart
 * @subpackage
 * @author Max Milbers, Eugen Stranz, Max Galt
 * @link http://www.virtuemart.net
 * @copyright Copyright (c) 2004 - 2014 VirtueMart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * VirtueMart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * @version $Id: default.php 8842 2015-05-04 20:34:47Z Milbo $
 */
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');
/* require(JPATH_BASE."/components/com_wishlist/template/addtofavorites_form.tpl.php");  */
/* Let's see if we found the product */
if (empty($this->product)) {
	echo vmText::_('COM_VIRTUEMART_PRODUCT_NOT_FOUND');
	echo '<br /><br />  ' . $this->continue_link_html;
	return;
}

echo shopFunctionsF::renderVmSubLayout('askrecomjs',array('product'=>$this->product));


//modulue menu
jimport('joomla.application.module.helper');
$module = JModuleHelper::getModule('mod_menu','Products Menu Article');

if(vRequest::getInt('print',false)){ ?>
<body onload="javascript:print();">
<?php } ?>
<?php echo JModuleHelper::renderModule($module);?>

<?php
$doc = JFactory::getDocument(); 

$doc->addCustomTag('<meta property="og:type" content="article"/>');
$doc->addCustomTag('<meta property="og:site_name" content="shevron.com.sg"/>');
$doc->addCustomTag('<meta property="article:author" content="shevron"/>');
$doc->addCustomTag('<meta property="og:url" content="'.JURI::current().'"/>');
$doc->addCustomTag('<meta property="og:title" content="'.$this->product->product_name.'"/>');
$doc->addCustomTag('<meta property="og:description" content="'.htmlentities($this->product->product_s_desc).'"/>');
$doc->addCustomTag('<meta property="og:image:width" content="400"/>');
$doc->addCustomTag('<meta property="og:image:height" content="400"/>');
	
foreach($this->product->images as $image):
	$file_url_nonimage = "images/imageNotAvailable.jpg";
	$file_url_image =  $image->file_url;
	$og_image = JURI::root().((@getimagesize($file_url_image))?$file_url_image:$file_url_nonimage);
	$doc->addCustomTag('<meta property="og:image" content="'.$og_image.'"/>');
endforeach;

$doc->addCustomTag('<meta property="fb:app_id" content="302549220127217"/>');




?>

<div class="productdetails-view productdetails">

	<div class="row">
		<div class="col-sm-12 col-md-6 colm-left">
			<?php
			echo $this->loadTemplate('images');
			?>
			<div class="share-social pull-left">
				<p>Share <div class="addthis_inline_share_toolbox"></div></p>
			</div>
		</div>
		
		<div class="col-sm-12 col-md-6  colm-right">
				<h2 class="heading">
					<?php // Product Title   ?>
					<?php echo $this->product->product_name ?>
					<?php // Product Title END   ?>
				</h2>
				<p class="des"><?php echo $this->product->intnotes ?></p>
				
				<?php // afterDisplayTitle Event
				echo $this->product->event->afterDisplayTitle ?>
		
				<!--group button Add cart - Aff favorite - Customeize -->
				<!-- customzie is Enquire -->
				<?php 
				
				$flag = isIncorporateGift($this->product->categories);
				if ( $flag):
				?>
				<div  class="btt-fav-2">					
					<a href="<?php echo JRoute::_('index.php?option=com_rsform&view=rsform&formId=1&Itemid=118&product='.$this->product->product_name) ?>">
						<button class="button-2 customise " type="button">Enquire</button>
					</a>
				</div>
				
				<?php else:?>
				<?php
					//In case you are not happy using everywhere the same price display fromat, just create your own layout
					//in override /html/fields and use as first parameter the name of your file
					echo shopFunctionsF::renderVmSubLayout('prices',array('product'=>$this->product,'currency'=>$this->currency));
				?>
				<div  class="btt-fav">
					<?php echo shopFunctionsF::renderVmSubLayout('addtocart',array('product'=>$this->product)); ?>
					<?php require(JPATH_BASE."/components/com_wishlist/template/addtofavorites_form.tpl.php");  ?>
					<a href="<?php echo JRoute::_('index.php?option=com_rsform&view=rsform&formId=1&Itemid=118&product='.$this->product->product_name) ?>">
						<button class="button-2 customise pull-right" type="button">Customise</button>
					</a>
				</div>
				
				<?php endif;?>
			<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
				<div class="panel panel-default">
					<div class="panel-heading" role="tab" id="headingOne">
					<h4 class="panel-title">
						<a role="button" class="accordion-toggle " data-toggle="collapse" data-parent="#accordion" href="#description" aria-expanded="true" aria-controls="description">
						Description
						</a>
					</h4>
					</div>
					<div id="description" class="panel-collapse  in" role="tabpanel" aria-labelledby="description">
					<div class="panel-body">
						<?php
						// Product Short Description
						if (!empty($this->product->product_s_desc)) {
						?>
							<div class="product-short-description">
							<?php
							/** @todo Test if content plugins modify the product description */
							echo ($this->product->product_s_desc);
							?>
							</div>
						<?php
						} // Product Short Description END
					
						echo shopFunctionsF::renderVmSubLayout('customfields',array('product'=>$this->product,'position'=>'ontop'));
						?>
					</div>
					</div>
				</div>
				<div class="panel panel-default">
					<div class="panel-heading" role="tab" id="headingTwo">
					<h4 class="panel-title">
						<a class="accordion-toggle collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#additional" aria-expanded="true" aria-controls="#additional">
						Additional Information
						</a>
					</h4>
					</div>
					<div id="additional" class="panel-collapse collapse " role="tabpanel" aria-labelledby="additional">
					<div class="panel-body">
						<?php
							// Product Description
							if (!empty($this->product->product_s_desc)) {
							?>
							<div class="product-short-description">
								<?php
									/** @todo Test if content plugins modify the product description */
									echo ($this->product->product_desc);
								?>
							</div>
							<?php
							} // Product Short Description END
							
							echo shopFunctionsF::renderVmSubLayout('customfields',array('product'=>$this->product,'position'=>'ontop'));
						?>
					</div>
					</div>
				</div>
			</div>
			<!--
			<div class="share-social pull-left">
				<p>Share <div class="addthis_sharing_toolbox"></div></p>
			</div>
			-->
			
		</div>
	
	<?php

	// event onContentBeforeDisplay
	echo $this->product->event->beforeDisplayContent; ?>
	<!--
	<?php
	// Product Description
	if (!empty($this->product->product_desc)) {
	    ?>
        <div class="product-description">
	<?php /** @todo Test if content plugins modify the product description */ ?>
    	<span class="title"><?php echo vmText::_('COM_VIRTUEMART_PRODUCT_DESC_TITLE') ?></span>
	<?php echo $this->product->product_desc; ?>
        </div>
	<?php
    } // Product Description END
	?>
	-->
	<?php
	//echo shopFunctionsF::renderVmSubLayout('customfields',array('product'=>$this->product,'position'=>'normal'));

    // Product Packaging
    $product_packaging = '';
    if ($this->product->product_box) {
	?>
        <div class="product-box">
	    <?php
	        echo vmText::_('COM_VIRTUEMART_PRODUCT_UNITS_IN_BOX') .$this->product->product_box;
	    ?>
        </div>
    <?php } // Product Packaging END ?>

    <?php 
	echo shopFunctionsF::renderVmSubLayout('customfields',array('product'=>$this->product,'position'=>'onbot'));

    //echo shopFunctionsF::renderVmSubLayout('customfields',array('product'=>$this->product,'position'=>'related_products','class'=> 'product-related-products','customTitle' => true ));

	//echo shopFunctionsF::renderVmSubLayout('customfields',array('product'=>$this->product,'position'=>'related_categories','class'=> 'product-related-categories'));

	?>

<?php // onContentAfterDisplay event
echo $this->product->event->afterDisplayContent;

echo $this->loadTemplate('reviews');

// Show child categories
if (VmConfig::get('showCategory', 1)) {
	echo $this->loadTemplate('showcategory');
}

$j = 'jQuery(document).ready(function($) {
	Virtuemart.product(jQuery("form.product"));

	$("form.js-recalculate").each(function(){
		if ($(this).find(".product-fields").length && !$(this).find(".no-vm-bind").length) {
			var id= $(this).find(\'input[name="virtuemart_product_id[]"]\').val();
			Virtuemart.setproducttype($(this),id);

		}
	});
});';
//vmJsApi::addJScript('recalcReady',$j);

/** GALT
	 * Notice for Template Developers!
	 * Templates must set a Virtuemart.container variable as it takes part in
	 * dynamic content update.
	 * This variable points to a topmost element that holds other content.
	 */
$j = "Virtuemart.container = jQuery('.productdetails-view');
Virtuemart.containerSelector = '.productdetails-view';";

vmJsApi::addJScript('ajaxContent',$j);

echo vmJsApi::writeJS();
?>
    <script src="templates/shevron/js/bootstrap.min.js" type="text/javascript"></script>
</div>

<!--not in corporate gifts-->
<?php //if (!$flag): ?>
<div class="row">
	
	<div class="recommend_product">
	
	<?php
	//load categoy
	if ( isset($this->product->customfieldsSorted['related_categories']) && count ($this->product->customfieldsSorted['related_categories']) )
		echo shopFunctionsF::renderVmSubLayout('customfields',array('product'=>$this->product,'position'=>'related_categories','class'=> 'category-related-products','customTitle' => true ));	
	//load product
	elseif ( isset($this->product->customfieldsSorted['related_products']) && count ($this->product->customfieldsSorted['related_products']) )
		echo shopFunctionsF::renderVmSubLayout('customfields',array('product'=>$this->product,'position'=>'related_products','class'=> 'product-related-products','customTitle' => true ));	
		//load current category
		else
			echo shopFunctionsF::renderVmSubLayout('customfields',array('product'=>$this->product,'position'=>'related_products','class'=> 'category-current-related-products','customTitle' => true ));	
	
	?>	
	</div>
</div>
<?php //endif;?>	
</div>
</p>

<script>
//is in corporate gifts
var flag = <?php echo json_encode($flag); ?>;
jQuery(document).ready(function ($) {
	$("span.PricesalesPrice").prepend("SGD ");	
	if (flag){
		$(".recommend_product .PricediscountedPriceWithoutTax").hide();
	}
})

</script>

<?php 

function isIncorporateGift($categories){
	$flag = false;
	foreach ( $categories as $category ){
		if ( $category == 5) return true;
	}
	return $flag;
}
?>