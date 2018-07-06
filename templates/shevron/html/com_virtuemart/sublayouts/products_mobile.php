<?php
/**
 * sublayout products for mobile with Load More button
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
$vmPagination = $viewData['vmPagination'];
$verticalseparator = " vertical-separator";
$tmpl = JRequest::getVar('tmpl');
echo shopFunctionsF::renderVmSubLayout('askrecomjs');

$ItemidStr = '';
/*$Itemid = shopFunctionsF::getLastVisitedItemId();
if(!empty($Itemid)){
	$ItemidStr = '&Itemid='.$Itemid;
}*/
?>
<?php if(!empty($tmpl) && $tmpl == 'component') { ?>
<!--Layout for ajax-->
	<?php foreach ($viewData['products'] as $type => $products ) {
		foreach ( $products as $product ) {
	
		// Show Products ?>
		<li class="item col-sm-4 col-xs-12">
				<a class="thumb-image" href="<?php echo $product->link.$ItemidStr; ?>" title="<?php echo $product->product_name ?>">
				<div class="product-image">
				<?php //echo $product->images[0]->displayMediaThumb('class="featuredProductImage img-responsive"', false); ?>
					<img src="<?php echo $product->images[0]->file_url; ?>" class="featuredProductImage"/>
				</div>
			</a>
				<div class="thumb-caption">
					<p class="thumb-caption-title">
						<a href="<?php echo $product->link.$ItemidStr; ?>" title="<?php echo $product->product_name ?>"><?php echo $product->product_name ?></a>
					</p>
					<?php echo shopFunctionsF::renderVmSubLayout('prices',array('product'=>$product,'currency'=>$currency)); ?>
				</div>
	
		</li><!--product-->
		<?php
		// }
		}
	}
	?>
<?php } 
else { ?>
<!--Default layout-->
	<?php foreach ($viewData['products'] as $type => $products ) {
		echo '<ul class="row product-grid">';
		foreach ( $products as $product ) {
	
		// Show Products ?>
		<li class="item col-sm-4 col-xs-12">
				<a class="thumb-image" href="<?php echo $product->link.$ItemidStr; ?>" title="<?php echo $product->product_name ?>">
				<div class="product-image">
				<?php //echo $product->images[0]->displayMediaThumb('class="featuredProductImage img-responsive"', false); ?>
					<img src="<?php echo $product->images[0]->file_url; ?>" class="featuredProductImage"/>
				</div>
				</a>
				<div class="thumb-caption">
					<p class="thumb-caption-title">
						<a href="<?php echo $product->link.$ItemidStr; ?>" title="<?php echo $product->product_name ?>"><?php echo $product->product_name ?></a>
					</p>
					<?php echo shopFunctionsF::renderVmSubLayout('prices',array('product'=>$product,'currency'=>$currency)); ?>
				</div>
	
		</li><!--product-->
		<?php
		// }
		}
		echo '<div id="loadmore_results"></div>';
		echo '</ul>';
		if( isset($vmPagination->pagesTotal) && $vmPagination->pagesTotal > 1) {
			echo '<a href="#loadmore" id="loadmore-btn" class="button-2">Load More</a>';
		}
	}
	?>
	<script>
		//Load more event
		jQuery(function($){
			
			var start = <?php echo $vmPagination->limit ?>;
			var total_page = <?php echo $vmPagination->pagesStop ?>;
			var current_page = 1;
			
			$('#loadmore-btn').on('click', function(){
				
				var $this = $(this);
				$this.html('Loading...');
				$.ajax({
					method: 'GET',
					data: {'limitstart' : start, 'tmpl' : 'component'},
					success: function(res) {
						$('#loadmore_results').append(res);
						$this.html('Load More');
						start += <?php echo $vmPagination->limit ?>;
						current_page += 1;
						if(current_page == total_page){
							$this.remove();
						}
					}
				});
			});
		});
	</script>
<?php } ?>
 
 
 
