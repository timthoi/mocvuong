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
     var val="<?php echo $params->get('carttext');?>("+cart+")"; 
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
     	 
     	st += "<table><tr><td colspan='2' class='p_name_cls a' > "+name+"</td></tr><tr><td rowspan='4'>"+obj+"</td></tr><tr><td> Product Quantity<b>: "+quantity+"</td></tr><tr><td> Product Price<b>: "+sy+product_price+"</b></td></tr><tr><td> Product Description<b>: "+product_desc+"</td></tr><tr><td align=right colspan=2 style='"+totaldes+"'> Total Price<b> : "+sy+total+"</b></td></tr></table>";
			
     	

     }	
     

jQuery("#newdata").html(st+"<table width='100%'><tr><td style='"+substyle+"' colspan=4 align=right><h4>SubTotal : "+sy+subtotal+"</td></tr><tr><td align=left ><a  href='index.php?option=com_virtuemart&view=cart'> <button style='"+button+"''>Show Cart</button></a></td><td align=right><a href=index.php?option=com_virtuemart&view=user&task=editaddresscart&addrtype=BT> <button style='"+button+"''>Check Out Now</button></td></tr></table>");
jQuery("#my").html(val);
jQuery("#myvalue").html(value);
    }
   
     
});
 }, 1500);
	
    });
});
</script>


<script> /*location.reload();*/

var delay = 1000;
var timer;
	
var $j = jQuery.noConflict();

$j(document).ready(function() {





$j('#vmCartModule').hover(function() {
    // on mouse in, start a timeout
 
    timer = setTimeout(function() { $j('#cart_list').slideDown(1000) ;
    }, delay);
}, function() {
    // on mouse out, cancel the timer
    clearTimeout(timer);
});



$j('#vmCartModule').mouseleave(

	     function(){ $j('#cart_list').slideUp(1000) 

});});
	



</script>

<Body >

<div class="vmCartModule <?php  ?>"  id="vmCartModule" >
<div id="my1" style="<?php echo $cartlbl; ?>" align="right"><img src="<?php echo $params->get('cart');?>" height="35px"width="35px" align="left"style="margin:7px;"> <div style="padding-top:14px" id="my"><?php echo $params->get('carttext')."(".count($data->products).")";?></div>&nbsp;&nbsp;&nbsp;&nbsp;</div> 
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
		<div id="myvalue" style="<?php echo $incart; ?>">
				<b>IN MY CART (<?php if((count($data->products))>1)echo count($data->products)." Items)";else echo count($data->products)." Item)";?> </b>

		</div>	
<div id="newdata"style="overflow-y:scroll;height:200px;" >

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
				<table width="100%" >
					
					
					<tr>
							<td colspan="2" >
									<span class="p_name_cls" ><?php echo $temp->products[$key]['product_name']; ?></span>
							</td>
					</tr>
					<tr>
						<td rowspan="4" class="td">

							<img  src="images/stories/virtuemart/product/<?php echo $temp->products[$key]['product_image'];?>" class="image" >
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
							<td >
									<?php if($params->get('description')==1) echo "Description :<b>". $products[$key]['product_desc']; ?>
							</td>
					</tr>
					<tr>
							<td colspan="6" style="<?php echo $totalstyle; ?>" align="right">
									<?php echo"Total Price <b>: ".$sy.$total=$price*$product['quantity'];$total1=$total1+$total;?>

							</td>
					</tr>
					
					
					
			
				
				<?php $i++;?>

			
		<?php }}
		?>
	
<?php } ?></table>
				<table width="100%" >
					<tr>
							<td style="" colspan="2" align="center"><?php if((count($data->products))<1)  echo "<h4>".$params->get('message');?></td>
					</tr>
					<tr>
							<td style="<?php if(isset($total1)) echo $subtotal; ?>" colspan="2" align="right"><h4><?php if(isset($total1)) echo "<h4>SubTotal : ".$sy.$total1; ?></td>
					</tr>
					<tr>
							<td  align="left" width="200px" ><a class="footer" href="index.php?option=com_virtuemart&view=cart"> <button style="<?php echo $params->get('button');?>">Show Cart</button></td>
							<td align="right" width="200px"><a class="footer" href="index.php?option=com_virtuemart&view=user&task=editaddresscart&addrtype=BT"><button style="<?php echo $params->get('button');?>">Check Out Now</button></td>
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

