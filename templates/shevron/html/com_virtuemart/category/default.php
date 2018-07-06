<?php
/**
 *
 * Show the products in a category
 *
 * @package    VirtueMart
 * @subpackage
 * @author RolandD
 * @author Max Milbers
 * @todo add pagination
 * @link http://www.virtuemart.net
 * @copyright Copyright (c) 2004 - 2010 VirtueMart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * VirtueMart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * @version $Id: default.php 8811 2015-03-30 23:11:08Z Milbo $
 */

defined ('_JEXEC') or die('Restricted access');
$app = JFactory::getApplication();
//create mobile instance using Simple Mobile Detect plugin
$mobile = $app->getUserState('cmobile.ismobile', false);
$tmpl = JRequest::getVar('tmpl');
/* require(JPATH_BASE."/components/com_wishlist/template/addtofavorites_form.tpl.php");  */

jimport('joomla.application.module.helper');
$module = JModuleHelper::getModule('mod_menu','Products Menu Article');


?> 
<?php echo JModuleHelper::renderModule($module);?>
<div class="category-view"> <?php
$js = "
jQuery(document).ready(function () {
	jQuery('.orderlistcontainer').hover(
		function() { jQuery(this).find('.orderlist').stop().show()},
		function() { jQuery(this).find('.orderlist').stop().hide()}
	)
});
";
vmJsApi::addJScript('vm.hover',$js);

if($this->showproducts){
?>
<div class="browse-view">
<?php

if (!empty($this->keyword)) {
	//id taken in the view.html.php could be modified
	$category_id  = vRequest::getInt ('virtuemart_category_id', 0); ?>
	<h3>Search result for '<?php echo $this->keyword; ?>'</h3>
<?php  } ?>
<?php if(empty($tmpl)) { ?>
<!--
<h1 class="category-name">

</h1>
-->
<?php 
	if(empty($this->keyword) && !empty($this->products)) {
?>
<!--Filters-->
	<div class="orderby-displaynumber">
		<div class="floatleft vm-order-list">
			<?php echo $this->orderByList['orderby']; ?>
			
		</div>
	</div> <!-- end of orderby-displaynumber -->
	
	<div class="clear"></div>
<!--Filters-->
<?php 
	}//displaynumber
} ?>

<?php
if (!empty($this->products)) {
	$products = array();
	$products[0] = $this->products;
	//Layout for mobile product grid
	//if($mobile) {
		//echo shopFunctionsF::renderVmSubLayout('products_mobile',array('products'=>$products,'currency'=>$this->currency,'products_per_row'=>$this->perRow,'showRating'=>$this->showRating, 'vmPagination' => $this->vmPagination));
	?>
	<?php
	//}
	//else 
	{
		//Layout for desktop product grid
		echo shopFunctionsF::renderVmSubLayout($this->productsLayout,array('products'=>$products,'currency'=>$this->currency,'products_per_row'=>$this->perRow,'showRating'=>$this->showRating));
	?>
		<!--Pagination-->
		<div style="display:none" class="vm-pagination vm-pagination-bottom"><?php echo $this->vmPagination->getPagesLinks (); ?></div>
		<!--Pagination-->
		
		<!--New Pagination-->
		<div class="vm-new-pagination">
			<a class="btt " title="Previous" href="#" id="btt_previous">PREVIOUS</a>
			<span class="input">
				<input id="input_field" class="page_num" type="text" value="" >
				<span >of </span><span id="total_page"> </span>
			</span>
			<a class="btt " title="Next" href="#" id="btt_next">NEXT</a>
		</div>
		<!--ENd Pagination-->
	
	
	<?php }
	
	?>

<?php
} elseif (!empty($this->keyword)) {
	echo vmText::_ ('COM_VIRTUEMART_NO_RESULT') . ($this->keyword ? ' : (' . $this->keyword . ')' : '');
}
?>
</div>

<?php } ?>
</div>

<?php
$j = "Virtuemart.container = jQuery('.category-view');
Virtuemart.containerSelector = '.category-view';";

vmJsApi::addJScript('ajaxContent',$j);
?>
<!-- end browse-view -->




<link rel="stylesheet" href="media/jui/css/chosen.css" type="text/css" />
<script src="media/jui/js/chosen.jquery.min.js" type="text/javascript"></script>
	
<script type="text/javascript">

jQuery(document).ready(function ($) {
	
	//chosen	
	jQuery(".select-style select").chosen({
		"disable_search": true
	});	
		
	//solve pagenation
	var active_page = $(".pagination span.active").text();
	var total_page = $(".pagination .total_page").text();
	
	//just one page
	if ( active_page == "" ){
		$(".vm-new-pagination").css("display","none");
	}
 	//alert(active_page);
	$("#input_field").val(active_page);
	$("#total_page").text(total_page);
	
	
	$( '#btt_next' ).click( function(e) {
	     e.preventDefault();	
		 var item = $(".pagination span.active").parent().next().find("a");
		 if ( item.length > 0 )
			window.location.href = item.attr('href');
		 else
			 return false; 
	});
	
	$( '#btt_previous' ).click( function(e) {
		e.preventDefault();
		var item = $(".pagination span.active").parent().prev().find("a");
		if ( item.length > 0 )
			window.location.href = item.attr('href');
		else
			return false;
		
	});

	$.fn.pressEnter = function(fn) {  
		
		return this.each(function() {  
			$(this).bind('enterPress', fn);
			$(this).keyup(function(e){
				if(e.keyCode == 13)
				{
					$(this).trigger("enterPress");
				}
			})
		});  
	}; 

	$('.page_num').pressEnter(function(e){
		e.preventDefault();
		var item = $(".pagination .active[title='"+$(this).val()+"']");
		if ( item.length > 0 )
			window.location.href = item.attr('href');
		else
			return false;
	})
	//move 
	if ( $(".mod-product-menu-article").height() > 50 ){
			$(".mod-product-menu-article a.link").hover(function() { $( this ).addClass('no-after'); });
			$(".mod-product-menu-article a.active").addClass('no-after');
	}
	else{
		$(".mod-product-menu-article a.link").hover(function() { $( this ).removeClass('no-after'); });
		$(".mod-product-menu-article a.active").removeClass('no-after');
	
	}
	//resize
	jQuery(window).resize(function () {
		if ( $(".mod-product-menu-article").height() > 50 ){
			$(".mod-product-menu-article a.link").hover(function() { $( this ).addClass('no-after'); });
			$(".mod-product-menu-article a.active").addClass('no-after');
		}
		else{
			$(".mod-product-menu-article a.link").hover(function() { $( this ).removeClass('no-after'); });
			$(".mod-product-menu-article a.active").removeClass('no-after');
			
		}	
	})
 });
     
</script>
