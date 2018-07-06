<?php // no direct access
$style="";
defined('_JEXEC') or die('Restricted access');
# Raindrops Shopping Cart Module for Virtuemart                                         
# Copyright (C) 2015 by Raindropsinfotech
# Homepage   : www.raindropsinfotech.com               
# Author     : raindropsinfotech                       
# Email      : raindropsinfotech@gmail.com             
# Version    : 1.0                                    
# license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL


 ?>

<!-- Virtuemart 2 Ajax Card -->

<!-- scroll -->
	<style>

/*
#cart_list::-webkit-scrollbar{
width:10px;
background-color:#cccccc;
} 
#table::-webkit-scrollbar-thumb{

<?php echo $params->get('scroll');?>
}
#table::-webkit-scrollbar-thumb:hover{
background-color:gray;

}

#table::-webkit-scrollbar-track{
border:1px gray solid;
border-radius:10px;
-webkit-box-shadow:0 0 6px gray inset;
} */
</style>

<!-- end scroll -->

<?php
	
	$mainstyle=$params->get('maincss');
	$cartlbl=$params->get('cartlbl');
	$incart=$params->get('incart');
	$totalstyle=$params->get('total');
	$subtotal=$params->get('subtotal');
	$bt=$params->get('button');
	
?>


<link rel="stylesheet" type="text/css" href="<?php echo JURI::root()?>modules/mod_shoppingcart/assets/style.css">
<script>var json;

var root='<?php echo JURI::root();?>';
var totaldes="<?php echo $totalstyle;?>";
var substyle="<?php echo $subtotal;?>";
var button="<?php echo $bt;?>";
var isGuest="<?php echo JFactory::getUser()->guest;?>";
jQuery(document).ready(function(){
    jQuery(".addtocart-button").click(function(){
var ajaxurl = "index.php?option=com_virtuemart&view=category&virtuemart_category_id=0&virtuemart_manufacturer_id=0&categorylayout=0&showcategory=1&showproducts=1&productsublayout=0";
    			
setTimeout(function(){ 

	jQuery.ajax({
  type: "GET",
  url: ajaxurl+'&vmcartmodajax=1',
  dataType:'json',
  
  cache: false,
  success: function(data){
  
      json = data; 

     var i=0;
     var st='';
     var total1;
     var total;
     var subtotal=0;
     var cart=json.products.length;
     var val=0;
     var ans;
     if(cart>1) ans=" Items";else ans=" Item";
     var value="<b> IN MY CART ("+cart+ans+")"; 
     for ( var i = 0; i < json.products.length; i++) {
     	var obj1=json.products[i].product_image;
     	//var  obj= "<img src='"+root+"images/stories/virtuemart/product/"+obj1+"' class='img-responsive'>";
		var url_non_image = "images/imageNotAvailable.jpg";
		if (typeof obj1[0] != 'undefined' && obj1[0].file_url) 
			var  obj= "<img src='" + root + obj1[0].file_url + "' class='img-responsive'>";
		else
			var  obj= "<img src='" + root + url_non_image + "' class='img-responsive'>";
		
     	var name = json.products[i].product_name;
     	var quantity = json.products[i].quantity;
     	var product_price = json.products[i].product_price;
     	var product_desc = json.products[i].product_desc;
     	var sy = json.products[i].product_currency;
		var product_link = json.products[i].product_link;
     	total= product_price*quantity;
     	subtotal= subtotal+total;
     	val= val+json.products[i].quantity;
		
		product_link = '<a href="'+product_link+'" >';
		
		st += '<div class="row item"><div class="product-image">'+product_link+obj+'</a></div><div class="product-info"><p>'+quantity+' x <span class="product-name">'+name+'</span></p><p class="total-price">'+sy+total+'</p></div></div>';
     }	
	 
	 //var val="<?php echo $params->get('carttext');?>"+cart; 
     

jQuery("#newdata").html(st);
if ( isGuest ) 
	jQuery('.cart-bottom').html('<a href="index.php?option=com_virtuemart&view=cart"> <button style="margin-right: 4px" class="btn">View Cart</button></a><a href="index.php?option=com_users&view=login&Itemid=123&return=1"><button style="<?php echo $params->get('button');?>" class="btn">Checkout</button></a>');
else
	jQuery('.cart-bottom').html('<a href="index.php?option=com_virtuemart&view=cart"> <button style="margin-right: 4px" class="btn">View Cart</button></a><a href="index.php?option=com_virtuemart&view=user&task=editaddresscart&addrtype=BT"><button style="<?php echo $params->get('button');?>" class="btn">Checkout</button></a>');
jQuery("#my").html(val);
jQuery("#myvalue").html(value);
    }
   
     
});
 }, 1500);
	
    });
});
</script>


<script> /*location.reload();*/

var delay = 100;
var timer;
	
var $j = jQuery.noConflict();

$j(document).ready(function() {




   
$j('#vmCartModule').click(function() {
    if( $j('#cart_list').is(':hidden') ){
		$j('#cart_list').show();
	}
	else {
		$j('#cart_list').hide();
	}
});});
	



</script>

<body >
	
<?php 
//sum product item
$sum_product_item = 0;
foreach ( $data->products as $p){
	$sum_product_item += $p[quantity];
}
?>
	

<div class="vmCartModule <?php  ?>"  id="vmCartModule" >
<div id="my-cart" style="<?php echo $cartlbl; ?>"><img src="<?php echo $params->get('cart');?>"> <div id="my" class="counter"><?php echo ($sum_product_item);?></div></div>
<?php

if ($show_product_list) {
	?>
	<div id="hiddencontainer" style="display: none; ">
		<div class="vmcontainer"  >
			<div class="product_row" >
				
			<div class="customProductData" ></div><br>
			</div>
		</div>
	</div>
	<div id="cart_list" style="<?php echo $mainstyle; ?>">
	<div >
		<!--<div id="myvalue" style="<?php echo $incart; ?>">
			<b>IN MY CART (<?php if((count($data->products))>1)echo count($data->products)." Items)";else echo count($data->products)." Item)";?> </b>
		</div>-->
<div id="newdata" class="scrollbar-inner">
	<!--<h5>My Cart</h5>-->
		<?php
			$i=0;
			/*$d=$data->products;
			echo"<pre>".count($d);exit;*/
			foreach ($data as $k=>$products){
			/*echo"<pre>";print_r($i);exit;*/
			foreach ($products as $key=>$product){
				/*echo"<pre>";print_r($products);exit;*/
					$sy=$products[$key]['product_currency'];
				
				?>
				<?php 
				$product_image =  $temp->products[$key]['product_image'];
				$product_image_url = "";
				if ( isset($product_image[0]->file_url_thumb) && $product_image[0]->file_url_thumb!="" ) $product_image_url =  $product_image[0]->file_url_thumb;
				elseif ( isset($product_image[0]->file_url) && $product_image[0]->file_url!="" ) $product_image_url =  $product_image[0]->file_url;
				else $product_image_url = "images/imageNotAvailable.jpg";
				 ?>
				<div class="row item">
					<div class="product-image">
						<a href="<?php echo $temp->products[$key]['product_link'];?>" >
							<img src="<?php echo $product_image_url;?>" class="img-responsive" >
						</a>
					</div>
					<div class="product-info">
						<p><?php echo $product['quantity'].' x '.'<span class="product-name">'.$temp->products[$key]['product_name'].'</span>'; ?></p>
						<?php if($params->get('price')==1) echo $sy.$price=$products[$key]['product_price']; else $price=$products[$key]['product_price']; ?>
						<p class="total-price"><?php echo $sy.$total=$price*$product['quantity']; $total1=$total1+$total; ?></p>
					</div>
					
				</div>
			
				
				<?php $i++;?>

			
		<?php }}
		?>
	
<?php } ?>
	</div><!--newdata-->

	<div class="cart-bottom">
		<?php if((count($data->products))<1) { ?>
		<div class="cart-empty" /></div>
		<div class="cart-empty-text">
			<h4><?php echo $params->get('message'); ?></h4>
			<a href="<?php echo JRoute::_('index.php?option=com_content&view=article&id=11&Itemid=213') ?>"> <button class="btn">Shop Now</button></a>
		</div>
		<?php } else { ?>  
		<a href="index.php?option=com_virtuemart&view=cart"> <button style="<?php echo $params->get('button');?>" class="btn">View Cart</button></a>
		<?php 
			if(JFactory::getUser()->guest) {
				$url_redirect = JRoute::_('index.php?option=com_users&view=login&Itemid=123&return=1', false);
			}
			else { 
				$url_redirect = JRoute::_('index.php?option=com_virtuemart&view=user&task=editaddresscart&addrtype=BT', false);
			}   
		
		
		?>
		
		
			
		<a href="<?php echo $url_redirect;?>"><button style="<?php echo $params->get('button');?>" class="btn">Checkout</button></a>
		<?php } ?>
	</div> 

	</div>
</div>





<div style="clear:both;"></div>
	<div class="payments_signin_button" ></div>
<noscript></div>
<?php echo vmText::_('MOD_VIRTUEMART_CART_AJAX_CART_PLZ_JAVASCRIPT') ?>
</noscript>
</div>

