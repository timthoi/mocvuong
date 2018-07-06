<?php
/**
 * sublayout products
 *
 * @package	VirtueMart
 * @author Max Milbers
 * @link http://www.virtuemart.net
 * @copyright Copyright (c) 2014 VirtueMart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL2, see LICENSE.php
 * @version $Id: cart.php 7682 2014-02-26 17:07:20Z Milbo $
 */

defined('_JEXEC') or die('Restricted access');
$products_per_row = $viewData['products_per_row'];
$currency = $viewData['currency'];
$showRating = $viewData['showRating'];
$verticalseparator = " vertical-separator";
echo shopFunctionsF::renderVmSubLayout('askrecomjs');

$ItemidStr = '';
/*$Itemid = shopFunctionsF::getLastVisitedItemId();
if(!empty($Itemid)){
	$ItemidStr = '&Itemid='.$Itemid;
}*/

foreach ($viewData['products'] as $type => $products ) {

	$rowsHeight = shopFunctionsF::calculateProductRowsHeights($products,$currency,$products_per_row);

	if(!empty($type) and count($products)>0){
		$productTitle = vmText::_('COM_VIRTUEMART_'.strtoupper($type).'_PRODUCT'); ?>
<div class="<?php echo $type ?>-view">

  <h4><?php echo $productTitle ?></h4>
		<?php // Start the Output
    }

	// Calculating Products Per Row
	$cellwidth = ' width'.floor ( 100 / $products_per_row );

	$BrowseTotalProducts = count($products);

	$col = 1;
	$nb = 1;
	$row = 1;

	echo '<ul class="row product-grid">';

	foreach ( $products as $product ) {		
		$flag = isIncorporateGift($product->categories);
		
		// Show the vertical seperator
		if ($nb == $products_per_row or $nb % $products_per_row == 0) {
			$show_vertical_separator = ' ';
		} else {
			$show_vertical_separator = $verticalseparator;
		}

    // Show Products ?>
	<li class="item col-sm-4 col-xs-12">
		<a class="thumb-image" href="<?php echo $product->link.$ItemidStr; ?>" title="<?php echo $product->product_name ?>">
			<div class="product-image">
				<?php //echo $product->images[0]->displayMediaThumb('class="featuredProductImage img-responsive"', false); ?>
				<?php 
					$file_url_nonimage = "images/imageNotAvailable.jpg";
					$file_url_image = $product->images[0]->file_url;
				?>
				<img src="<?php echo (@getimagesize($file_url_image))?$file_url_image:$file_url_nonimage; ?>" class="featuredProductImage"/>
			</div>
		</a>
		<div class="thumb-caption">
			<p class="thumb-caption-title">
				<a href="<?php echo $product->link.$ItemidStr; ?>" title="<?php echo $product->product_name ?>"><?php echo $product->product_name ?></a>
			</p>			
			<?php 
			if ( !$flag ){
				/* if (!class_exists ('CurrencyDisplay'))
					require(VMPATH_ADMIN . DS . 'helpers' . DS . 'currencydisplay.php');
				$currencyDisplay = CurrencyDisplay::getInstance();
				
				echo "price only:". $currencyDisplay->createPriceDiv ('salesPrice', '', $product->prices['product_price'], TRUE, FALSE).'<br>';				
				echo "discountedPriceWithoutTax".$currencyDisplay->createPriceDiv ('discountedPriceWithoutTax', '', $product->prices, FALSE, FALSE).'<br>'; */
				
				echo shopFunctionsF::renderVmSubLayout('prices',array('product'=>$product,'currency'=>$currency)); 
			}
			?>
		</div>

	</li><!--product-->

	<?php
    $nb ++;

      // Do we need to close the current row now?
      if ($col == $products_per_row || $nb>$BrowseTotalProducts) { ?>
    <div class="clear"></div>
      <?php
      	$col = 1;
		$row++;
    } else {
      $col ++;
    }
  }

  echo '</ul>';

      if(!empty($type)and count($products)>0){
        // Do we need a final closing row tag?
        //if ($col != 1) {
      ?>
  </div>
    <?php
    // }
    }
  }
?>

<?php 

function isIncorporateGift($categories){
	$flag = false;
	foreach ( $categories as $category ){
		if ( $category == 5) return true;
	}
	return $flag;
}
?>