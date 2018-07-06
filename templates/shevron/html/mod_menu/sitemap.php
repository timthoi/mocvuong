<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_content
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/* JHtml::addIncludePath(JPATH_COMPONENT . '/helpers');

// Create shortcuts to some parameters.
$params  = $this->item->params;
$images  = json_decode($this->item->images);
$urls    = json_decode($this->item->urls);
$canEdit = $params->get('access-edit');
$user    = JFactory::getUser();
$info    = $params->get('info_block_position', 0);
$attribs = json_decode($this->item->attribs);
$download_link = JUri::base().$attribs->download_link;
JHtml::_('behavior.caption'); */

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
			if ( !isset($item->flink) )  $item->flink = $item->route;
			if ( !isset($item->title) )  $item->title = '';
			if ( !isset($item->query["filter_category_id"]) )  $item->query["filter_category_id"] = '';
			$childs[] = array('id'=>$item->id, 'title'=>$item->title, 'flink'=>$item->flink,"filter_category_id"=>$item->query["filter_category_id"]);
		}
	}
	return $childs;
}
?>

<?php
//main menu
$product_menu = $menu_1;
//var_dump($product_menu[0] );
$menu_1 = array();

foreach ( $list as $item ){
	
	if ( count($item->tree) == 1){
		$menu_1[] = $item;	
		/* $level = 2;
		$menu_1 = getChildCategories( $list, $item->id, $level ); */
		
	}
}
$i = 0;

foreach ( $menu_1 as $child ){
	foreach ( $list as $item ){
		if ( count($item->tree) == 2 && $item->tree[0] == $child->id ){
			$menu_1[$i]->childrent[] = $item;
		}
	}
	$i++;
}

//var_dump($menu_1);
	
/* foreach ( $menu_1 as $item ) {
		
	echo $item->title.' '.$item->id."<br>"; 	
	if (isset($item->childrent)) 
		foreach ( $item->childrent as $child ) {
			echo $child->title."<br>"; 		
		}
	//shop	
	if ( $item->id == 213 ){
		foreach ( $product_menu as $product ) {
			echo $product['title']."<br>"; 		
			if ( !empty($product['childrent']) ): 
				foreach ( $product['childrent'] as $c ):
					echo $c['title']."<br>";
				endforeach;
			 endif;
		}
	}	
	echo "<br><br><br>"; 	
	
}  */

?>
<div class="page-header">
	<h2 itemprop="name" class="shop-heading">Site Map</h2>
</div>
<div class="row mod-site-map mod-product-menu">
<?php foreach ( $menu_1 as $item ):?>
	<div class="col-sm-3 tall">
		<h3 class="title"><a class="" href="<?php echo $item->flink;?>"><?php echo $item->title;?></a></h3>
		<?php if (isset($item->childrent)) : ?>
			<?php foreach ( $item->childrent as $child ):?>
				<p class="child-text">
					<a class="" href="<?php echo $child->flink;?>"><?php echo $child->title;?></a>
				</p>
			<?php endforeach;?>
		<?php endif;?>
		
		<?php if ($item->id == 213) : ?>
			<?php foreach ( $product_menu as $product ):?>
				<h3 class="title_2"><a class="" href="<?php echo $product['flink'];?>"><?php echo $product['title'];?></a></h3>
				<?php if (!empty($product['childrent'])) : ?>
					<?php foreach ( $product['childrent'] as $c ):?>
						<p class="child-text">
							<a class="" href="<?php echo $c['flink'];?>"><?php echo $c['title'];?></a>
						</p>
					<?php endforeach;?>
				<?php endif;?>
			<?php endforeach;?>
		<?php endif;?>
	</div>
<?php endforeach;?>
</div>
