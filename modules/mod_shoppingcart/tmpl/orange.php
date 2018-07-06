<?php // no direct access
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
<style>
.p_name_cls a{color: #000000;
text-decoration:none;font-size:16px;
}
</style>

<script>var json;

var root='<?php echo JURI::root();?>';
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
     var val="My Cart("+cart+")"; 
     var ans;
     if(cart>1) ans=" Items";else ans=" Item";
     var value="<b> IN MY CART ("+cart+ans+")"; 
     for ( var i = 0; i < json.products.length; i++) {
     	var obj1=json.products[i].product_image;
     	var  obj= "<img src='"+root+"images/stories/virtuemart/product/"+obj1+"' height='100px' width='100px'>";
     	var name = json.products[i].product_name;
     	var quantity = json.products[i].quantity;
     	var product_price = json.products[i].product_price;
     	var product_desc = json.products[i].product_desc;
     	var sy = json.products[i].product_currency;
     	total= product_price*quantity;
     	subtotal= subtotal+total;
     	 
     	st += "<table><tr><td colspan='2' class='p_name_cls a' style='text-decoration:none;font-size:16px;color:black;'> "+name+"</td></tr><tr><td rowspan='4'>"+obj+"</td></tr><tr><td> Product Quantity<b>: "+quantity+"</td></tr><tr><td> Product Price<b>: "+sy+product_price+"</b></td></tr><tr><td> Product Description<b>: "+product_desc+"</td></tr><tr><td align=right colspan=2 style=background-color:#FF4D4D;> Total Price<b> : "+sy+total+"</b></td></tr>";
			
     	

     }	
     

jQuery("#newdata").html(st+"<tr><td style='background-color:#3D0F00;color:white 'colspan=4 align=right><h4>SubTotal : "+sy+subtotal+"</td></tr><tr><td align=left ><a style=text-decoration:none;font-size:17px;color:black; href='index.php?option=com_virtuemart&view=cart'>ShowCart</a></td><td align=right><a href=index.php?option=com_virtuemart&view=user&task=editaddresscart&addrtype=BT style=text-decoration:none;font-size:17px;color:black;>Check Out Now</td></tr></table>");
jQuery("#my").html(val);
jQuery("#myvalue").html(value);
    }
   
     
});
 }, 1500);
	
    });
});
</script>


<script> /*location.reload();*/


	var $j = jQuery.noConflict();

$j(document).ready(function() {




$j('#vmCartModule').mouseenter(

	   
 function(){ $j('#cart_list').slideDown(1000) 
	 

	

});
$j('#vmCartModule').mouseleave(

	     function(){ $j('#cart_list').slideUp(1000) 

	  

	

});


	/*$j('#vmCartModule').hover(

	   function(){ $j('#cart_list').slideDown(1000) },

	   function(){ $j('#cart_list').slideUp(1000) }

	)
*/
});

</script>

<Body >

<div class="vmCartModule <?php  ?>"  id="vmCartModule" >
<div id="my" style="background:url(<?php echo $params->get('cart');?>) no-repeat;background-size: 40px 40px;background-color:#3D0F00 ;font-size:18px;padding:12px;padding-right:65px;width:200px;color:white;"align="right"> My Cart(<?php echo count($data->products);?>)&nbsp;&nbsp;&nbsp;&nbsp;</div> 
<?php

if ($show_product_list) {
	?>
	<div id="hiddencontainer" style="display: none; ">
		<div class="vmcontainer">
			<div class="product_row">
				

			
			<div class="customProductData"></div><br>
			</div>
		</div>
	</div>
	<div id="cart_list" class="show_products" style="border-radius: 0px 0px 10px 10px;
    box-shadow: 0 0 20px #4C0000;display:none;position:absolute;background-color:#D9B2B2;width: 277px;">
	<div >
		<div id="myvalue" style="background-color:#993333;color:#1A0000;padding:5px;padding-left:15px;">
				<b>IN MY CART (<?php if((count($data->products))>1)echo count($data->products)." Items)";else echo count($data->products)." Item)";?> </b>

		</div>	
<div id="newdata" >

		<?php
			$i=0;
			/*$d=$data->products;
			echo"<pre>".count($d);exit;*/
			foreach ($data as $k=>$products){
			/*echo"<pre>";print_r($i);exit;*/
			foreach ($products as $key=>$product){
				/*echo"<pre>";print_r(count($products));exit;*/
					$sy=$products[$key]['product_currency'];
				
				?>
				<table >
					
					
					<tr>
							<td colspan="2" >
									<span class="p_name_cls" ><?php echo $temp->products[$key]['product_name']; ?></span>
							</td>
					</tr>
					<tr>
						<td rowspan="4" >

							<img  src="/joomla4/images/stories/virtuemart/product/<?php echo $temp->products[$key]['product_image'];?>" height="100px" width="100px">
						</td>
					</tr>
					<tr>	
							<td>
									<?php echo "Quantity :<b>".$product['quantity']; ?>
							</td>
					</tr>
					<tr>	
							<td>
									<?php if($params->get('price')==1) echo "Price :<b>".$sy.$price=$products[$key]['product_price'];else $price=$products[$key]['product_price']; ?>
							</td>
					</tr>
					<tr>	
							<td>
									<?php if($params->get('description')==1) echo "Description :<b>". $products[$key]['product_desc']; ?>
							</td>
					</tr>
					<tr>
							<td colspan="6" style="background-color:#FF4D4D;text-align:right;width:290px !important;" align="right">
									<?php echo"Total Price <b>: ".$sy.$total=$price*$product['quantity'];$total1=$total1+$total;?>

							</td>
					</tr>
					
					
					
			
				
				<?php $i++;?>

			
		<?php }}
		?>
	
<?php } ?>
					<tr>
							<td style="background-color:#3D0F00;color:white; " colspan="2" align="right"><?php if((count($data->products))<1) echo "<h4>Not Any Product In Your Cart";?></td>
					</tr>
					<tr>
							<td style="background-color:#3D0F00;color:white; font-size:17px;" colspan="2" align="right"><h4><?php echo "<h4>SubTotal : ".$sy.$total1; ?></td>
					</tr>
					<tr>
							<td  align="left"><a style="text-decoration:none;font-size:15px;color:black;" href="index.php?option=com_virtuemart&view=cart"> Show Cart</td>
							<td align="right"><a style="text-decoration:none;font-size:15px;color:black;" href="index.php?option=com_virtuemart&view=user&task=editaddresscart&addrtype=BT">Check Out Now</td>
					</tr>

				</table> 

	</div>

	</div>
</div>





<div style="clear:both;"></div>
	<div class="payments_signin_button" ></div>
<noscript></div>
<?php echo vmText::_('MOD_VIRTUEMART_CART_AJAX_CART_PLZ_JAVASCRIPT') ?>
</noscript>
</div>

