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
			if ( !isset($item->flink) )  $item->flink = '';
			if ( !isset($item->title) )  $item->title = '';
			if ( !isset($item->query["filter_category_id"]) )  $item->query["filter_category_id"] = '';
			$childs[] = array('id'=>$item->id, 'title'=>$item->title, 'flink'=>$item->flink,"filter_category_id"=>$item->query["filter_category_id"]);
		}
	}
	return $childs;
}

$flag = true;
$count_column =0;
foreach ( $menu_1 as $child ){
	if( $child['title'] != '') $count_column++;
}
$colm = "col-sm-12";
switch ($count_column){
	case 1: $colm = "col-sm-12";
			break;
	case 2: $colm = "col-sm-6";
			break;
	case 3: $colm = "col-sm-4";
			break;
	case 4: $colm = "col-sm-3";
			break;
	default: $colm = "col-sm-12";
		break;
}

?>
<div class="row mod-product-menu">
<?php foreach ( $menu_1 as $child ):?>
	<div class="<?php echo ($colm) ?> tall" >
		<h3 class="title"><a class="" href="<?php echo $child['flink'];?>"><?php echo $child['title'];?></a></h3>
		<?php if ( !empty($child['childrent']) ): ?>
			<?php foreach ( $child['childrent'] as $c ):?>
				<p class="child-text">
					<a class="" href="<?php echo $c['flink'];?>"><?php echo $c['title'];?></a>
				</p>
			<?php endforeach;?>
		<?php endif;?>
	</div>
<?php endforeach;?>
</div>

<script>
jQuery(function($){
$(document).ready(function(){
	var win_width = viewport().width;
	var max_height = 0;
	
	//get view port
	function viewport() {
		var e = window, a = 'inner';
		if (!('innerWidth' in window )) {
			a = 'client';
			e = document.documentElement || document.body;
		}
		return { width : e[ a+'Width' ] , height : e[ a+'Height' ] };
	}
	
	if(win_width > 767) {
		$(".mod-product-menu .tall").each(function(){
			if ( max_height < $(this).height() ) max_height = $(this).height()
		});
		max_height = max_height + 20;
		$(".mod-product-menu .tall").each(function(){
			$(this).css('height', max_height);
		});
	}
});
});
</script>