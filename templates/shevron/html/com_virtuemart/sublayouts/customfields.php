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

$product = $viewData['product'];
$position = $viewData['position'];
$customTitle = isset($viewData['customTitle'])? $viewData['customTitle']: false;;
if(isset($viewData['class'])){
	$class = $viewData['class'];
} else {
	$class = 'product-fields';
}

//product
if ( isset($viewData['class']) && $viewData['class'] == 'product-related-products' ):

if (!empty($product->customfieldsSorted[$position])) {
	?>
	<div class="<?php echo $class?>">
		<?php
		if($customTitle and isset($product->customfieldsSorted[$position][0])){
			$field = $product->customfieldsSorted[$position][0]; ?>
		<div class="product-fields-title-wrapper"><span class="product-fields-title"><?php echo vmText::_ ($field->custom_title) ?></span>
			<!--<?php if ($field->custom_tip) {
				echo JHtml::tooltip (vmText::_($field->custom_tip), vmText::_ ($field->custom_title), 'tooltip.png');
			} ?>-->
		</div> 
		<div class="product-grid row">
		<?php
		}
		$custom_title = null; ?>
		<?php 
		$i = 0;
		foreach ($product->customfieldsSorted[$position] as $field) {
			if ( $field->is_hidden ) //OSP http://forum.virtuemart.net/index.php?topic=99320.0
			continue;
			?>
				<?php if (!empty($field->display)){
					$pro_display = $field->display;
					$pos = strpos($pro_display, "<a");
					$str_tmp = substr($pro_display, $pos);
					$str_img = substr($pro_display, 0, $pos-1);
					$str_img = str_replace("resized/", "", $str_img);
					$str_img = str_replace("_300x0", "", $str_img);
					$pro_display = '<div class="product-image">' . $str_img . '</div>' . $str_tmp;
				?>
				<!--<?php echo $field->display ?>-->
				<div class="col-sm-4 col-xs-12 item"> <?php echo $pro_display; ?> </div>
				<?php } ?>
		<?php
			$i++;
			$custom_title = $field->custom_title;
			if ( $i==3 ) break;
		} ?>
		</div>
	</div>
<?php
} ?>
<?php  //category
elseif ( isset($viewData['class']) && $viewData['class'] == 'category-related-products' ):?>

<?php 

//var_dump($product->customfieldsSorted[$position]);
//customfield_value
$i=0;
foreach ( $product->customfieldsSorted[$position] as $category):

$productModel= VmModel::getModel('product');
$products = $productModel->getProductsInCategory($category->customfield_value);
//load images
$productModel->addImages($products);
?>
<div class="product-related-products">
<div class="product-fields-title-wrapper"><span class="product-fields-title">Recommended Items</span></div> 
	<div class="product-grid row no-margin">
<?php
	foreach ( $products as $tttt ) {
	
    // Show Products ?>
	<div class="item col-sm-4 col-xs-12">
		<div a class="thumb-image" href="<?php echo $tttt->link ?>" title="<?php echo $tttt->product_name ?>">
			<div class="product-image">
					<?php //echo $tttt->images[0]->displayMediaThumb('class="featuredProductImage img-responsive"', false); ?>
				<?php 
					$file_url_nonimage = "images/imageNotAvailable.jpg";
					$file_url_image = $tttt->images[0]->file_url;
				?>
				<a href="<?php echo $tttt->link ?>" title="<?php echo $tttt->product_name ?>">
					<img src="<?php echo (@getimagesize($file_url_image))?$file_url_image:$file_url_nonimage; ?>" class="featuredProductImage" nopin = "nopin"/>
				</a>
			</div>
		</a>
		<div class="thumb-caption">
			<p class="thumb-caption-title">
				<a href="<?php echo $tttt->link ?>" title="<?php echo $tttt->product_name ?>"><?php echo $tttt->product_name ?></a>
			</p>
			<?php echo shopFunctionsF::renderVmSubLayout('prices',array('product'=>$tttt,'currency'=>CurrencyDisplay::getInstance())); ?>
		</div>
		
	</div><!--product-->
	</div>
	<?php
		if ($i==2) break;
		$i++;	
	}
	
?>
	</div>
</div>
<?php endforeach; ?>


<?php  //category
elseif ( isset($viewData['class']) && $viewData['class'] == 'category-current-related-products' ):?>

<?php 
//customfield_value
if (!empty($product->categoryItem)):

$products_recommended = array();
for ( $j=count($product->categoryItem)-1;$j>=0;$j--){
	$productModel= VmModel::getModel('product');
	$products = $productModel->getProductsInCategory($product->categoryItem[$j]['virtuemart_category_id']);
	//load images	
	$productModel->addImages($products);
	if ( count($products_recommended) < 3 ) 
		foreach ( $products as $tttt ) {				
			if ( count($products_recommended) < 3 ){
				if ( $tttt->product_recommend && !in_array($tttt, $products_recommended) ) {
					$products_recommended[] = $tttt;
					//var_dump($tttt->virtuemart_product_id);
				}		
			} 			
			else break;
		}
	else break;
}

if ( count($products_recommended) < 3 ) {
	for ( $j=count($product->categoryItem)-1;$j>=0;$j--){
	$productModel= VmModel::getModel('product');
	$products = $productModel->getProductsInCategory($product->categoryItem[$j]['virtuemart_category_id']);
	//load images	
	$productModel->addImages($products);
	if ( count($products_recommended) < 3 ) 
		foreach ( $products as $tttt ) {				
			if ( count($products_recommended) < 3 ){
				if ( !in_array($tttt, $products_recommended) ) {
					$products_recommended[] = $tttt;
					//var_dump($tttt->virtuemart_product_id);
				}		
			} 			
			else break;
		}
	else break;
}
}
//var_dump(count($products_recommended));

?>

<div class="product-related-products">
<div class="product-fields-title-wrapper"><span class="product-fields-title">Recommended Items</span></div>
<div class="product-grid row no-margin">

<?php
	foreach ( $products_recommended as $tttt ) {
	
    // Show Products ?>
	<div class="item col-sm-4 col-xs-12">
		<div a class="thumb-image" href="<?php echo $tttt->link ?>" title="<?php echo $tttt->product_name ?>">
			<div class="product-image">
				<?php //echo $tttt->images[0]->displayMediaThumb('class="featuredProductImage img-responsive"', false); ?>
				<?php 
					$file_url_nonimage = "images/imageNotAvailable.jpg";
					$file_url_image = $tttt->images[0]->file_url;
				?>
				<a href="<?php echo $tttt->link ?>" title="<?php echo $tttt->product_name ?>">
					<img src="<?php echo (@getimagesize($file_url_image))?$file_url_image:$file_url_nonimage; ?>" class="featuredProductImage" nopin = "nopin"/>
				</a>
			</div>
		</a>
		<div class="thumb-caption">
			<p class="thumb-caption-title">
				<a href="<?php echo $tttt->link ?>" title="<?php echo $tttt->product_name ?>"><?php echo $tttt->product_name ?></a>
			</p>
			<?php echo shopFunctionsF::renderVmSubLayout('prices',array('product'=>$tttt,'currency'=>CurrencyDisplay::getInstance())); ?>
		</div>
		
	</div><!--product-->
	</div>
<?php } ?>

</div>
</div>
<?php endif; ?>

<?php //default
else:?>
<?php 
if (!empty($product->customfieldsSorted[$position])) {
	?>
	<div class="<?php echo $class?>">
		<?php
		if($customTitle and isset($product->customfieldsSorted[$position][0])){
			$field = $product->customfieldsSorted[$position][0]; ?>
		<div class="product-fields-title-wrapper"><span class="product-fields-title"><?php echo vmText::_ ($field->custom_title) ?></span>
			<!--<?php if ($field->custom_tip) {
				echo JHtml::tooltip (vmText::_($field->custom_tip), vmText::_ ($field->custom_title), 'tooltip.png');
			} ?>-->
		</div> 
		<div class="product-grid row no-margin">
		<?php
		}
		$custom_title = null; ?>
		<?php foreach ($product->customfieldsSorted[$position] as $field) {
			if ( $field->is_hidden ) //OSP http://forum.virtuemart.net/index.php?topic=99320.0
			continue;
			?>
				<?php if (!empty($field->display)){
					$pro_display = $field->display;
					$pos = strpos($pro_display, "<a");
					$str_tmp = substr($pro_display, $pos);
					$str_img = substr($pro_display, 0, $pos-1);
					$str_img = str_replace("resized/", "", $str_img);
					$str_img = str_replace("_300x0", "", $str_img);
					$pro_display = '<div class="product-image">' . $str_img . '</div>' . $str_tmp;
				?>
				<!--<?php echo $field->display ?>-->
				<div class="col-sm-4 col-xs-12 item"> <?php echo $pro_display; ?> </div>
				<?php } ?>
		<?php
			$custom_title = $field->custom_title;
		} ?>
		</div>
	</div>
<?php
} ?>


<?php endif;?>

