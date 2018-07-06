<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_content
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;



$id = "";
foreach ( $product_menu as $item ){
	if ( $item->id == 203 ){
		//echo $item->title." ".$item->flink."<br>";
		//var_dump($item);
		$id = $item->id;
		break;
	} 

		
} 
$menu_1 = array();

foreach ( $product_menu as $item ){
	if ( count($item->tree) == 1 && $item->tree[0] == $id ){
		$level = 2;
		$menu_1 = getChildCategories( $product_menu, $item->id, $level );
		
	}
}


$menu_2 = array();
$i = 0;
foreach ( $menu_1 as $child ){
	foreach ( $product_menu as $item ){
		if ( count($item->tree) == 2 && $item->tree[1] == $child['id'] ){
			$level = 3;
			$menu_2[] = getChildCategories( $product_menu, $item->id, $level );
			$menu_1[$i]["childrent"] = getChildCategories( $product_menu, $item->id, $level );
		}
	}
	$i++;
}
//var_dump($product_menu );echo "xxx<br>";

function getChildCategories( $menus, $activeId,$level )
{
	$childs = array();
	foreach( $menus as $item )
	{
		if (in_array($activeId, $item->tree) && $item->level == $level) 
		{
			if ( !isset($item->flink) )  $item->flink = '';
			if ( !isset($item->title) )  $item->title = '';
			if ( !isset($item->query["filter_category_id"]) )  $item->query["filter_category_id"] = '';
			$childs[] = array('id'=>$item->id, 'title'=>$item->title, 'flink'=>$item->flink,"filter_category_id"=>$item->query["filter_category_id"]);
		}
	}
	return $childs;
}
$jinput = JFactory::getApplication()->input;

$itemId = $jinput->get('Itemid', '', 'string');
//find parent of this
$parent_id = "";
$parent_title = "";
foreach ( $menu_1 as $child ){
	if ( !empty($child['childrent']) )
		foreach ( $child['childrent'] as $c )
			if ( $c['id'] == $itemId ){
				$parent_id    = $child['id'];
				$parent_title = $child['title'];
				break;
			} 
	if ( $parent_id != "" ) break;
}
$flag = 0;
if ( $parent_id == "" ){
	$parent_id = $itemId ;
	$flag = 1;
	foreach ( $menu_1 as $child ){
		if ( $child['id'] == $parent_id ) $parent_title = $child['title'];
	}
} 
$count = 0;
foreach ( $menu_1 as $child ) 
	if ( !empty($child['childrent']) && $child['id'] == $parent_id ) $count++;

if ( $count == 0 ):
	$menu = $app->getMenu();
	$menuname = $menu->getActive()->title;

?>

<div class="page-header">
	<h2 class="shop-heading">
		<?php echo $menuname; ?>
	</h2>
</div>

<?php
else:

?>
<div class="">
	<h2 class="shop-heading">
		<?php echo $parent_title; ?>
	</h2>
</div>
<div class="border-one">
<div class="row mod-product-menu-article">
<?php 


?>
<?php foreach ( $menu_1 as $child ):?>
	<?php if ( !empty($child['childrent']) && $child['id'] == $parent_id ): 
		  $first = "";
		 
          /* if ( $flag == 1 ){
			reset($child['childrent']);
			$first = current($child['childrent'] ); 
		  } */

		
	?>
		<a class="link <?php echo ($itemId == $child['id'] || $first == $child )?"active":"";?>" href="<?php echo $child['flink'];?>">
			<span class="title">All</span>
		</a>
		<span class="divider"><?php echo ' |'; ?>
		</span>
		<?php foreach ( $child['childrent'] as $c ):?>
			<a class="link <?php echo ($itemId == $c['id'] || $first == $c )?"active":"";?>" href="<?php echo $c['flink'];?>">
				<span class="title"><?php echo $c['title'];?></span>
				
			</a>
			<span class="divider">
				<?php if ( end($child['childrent']) !== $c )
					echo ' |'; // not the last element
				
				?>
			</span>
		
		<?php endforeach;?>
	<?php endif;?>
	
<?php endforeach;?>
</div>
</div>


<?php endif;?>


<script type="text/javascript">
jQuery(document).ready(function ($) {
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