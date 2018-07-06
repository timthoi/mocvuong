<?php
/**
 * @version		$Id: default.php 1 2014-02-19 16:44 sakis Terz $
 * @package		customfilters
 * @subpackage	mod_cf_filtering
 * @copyright	Copyright (C) 2010 - 2014 breakdesigns.net . All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC')or die;
JHtml::_('behavior.framework', true);
JHTML::_('behavior.calendar');

$document=JFactory::getDocument();
$jinput=JFactory::getApplication()->input;
$view=$jinput->get('view','products','cmd');
$component=$jinput->get('option','','cmd');
$direction=$document->getDirection();
$menu_params=cftools::getMenuparams();
$Itemid=$menu_params->get('cf_itemid','');
$results_trigger=$params->get('results_trigger','sel');
$results_loading_mode=$params->get('results_loading_mode','http');
$jconfig=JFactory::getConfig();
$issef=$jconfig->get('sef');
$filters_html_array=array();

$document->addScript(JURI::root().'modules/mod_cf_filtering/assets/general.js');
$document->addScript(JURI::root().'media/system/js/modal.js');
$document->addStyleSheet(JURI::root().'modules/mod_cf_filtering/assets/style.css');


/*CSS for RTL sites in  webkit browser*/
$browser = JBrowser::getInstance();
$browserType = $browser->getBrowser();
if(($browserType=='chrome' || $browserType=='safari') &&  $direction=='rtl'){
	$style='.knob_wrapper {margin-left:18px;}';
}
if(!empty($style))$document->addStyleDeclaration($style); 
if(!empty($filters_render_array['stylesDeclaration']))$document->addStyleDeclaration($filters_render_array['stylesDeclaration']); 



if(!empty($filters_render_array['html']))$filters_html_array=$filters_render_array['html'];
if(isset($filters_render_array['resetUri']))$resetUri=$filters_render_array['resetUri'];

$expanded_state=!isset($filters_render_array['expanded_state'])?true:$filters_render_array['expanded_state'];

if(count($filters_html_array)>0){
/*
 * view == module is used only when the module is loaded with ajax. 
 * We want only the form to be loaded with ajax requests. 
 * The cf_wrapp_all of the primary module, will be used as the container of the ajax response   
 */
	if($view!='module'){?>
	<div id="cf_wrapp_all_<?php echo $module->id ?>" class="cf_wrapp_all">
	<?php } 
?>
<div id="cf_ajax_loader_<?php echo $module->id?>"></div>

<?php
$data = JRequest::getVar('parent_id');
$dbo = JFactory::getDbo();
$query = $dbo->getQuery(true);
$query->select('b.virtuemart_category_id, b.category_name')
	->from('#__virtuemart_category_categories as a')
	->join('LEFT', '#__virtuemart_categories_en_gb as b on a.category_child_id = b.virtuemart_category_id')
	->where('a.category_parent_id = 0');
$categories = $dbo->setQuery($query)->loadObjectList();
 ?>
<div class="row filter-section">
<form method="get" action="<?php echo JRoute::_('index.php?option=com_customfilters&view=products&Itemid='.$Itemid)?>" class="cf_form<?php echo $moduleclass_sfx;?> form-filter" id="cf_form_<?php echo $module->id?>">
<div class="cf_flt_wrapper_cat cf_flt_wrapper col-sm-12 col-xs-12" role="presentation">

		<ul id="select_category">
			<?php foreach($categories as $category) {?>
				<li class="category" id="<?php echo $category->virtuemart_category_id ?>"><a href="#"><?php echo $category->category_name ?></a></li>
			<?php } ?>
		</ul>
		<input type="hidden" name="virtuemart_category_id" id="virtuemart_category_id">

</div>

	<?php 
	foreach($filters_html_array as $key=>$flt_html){?> 
	<div class="cf_flt_wrapper  cf_flt_wrapper_id_<?php echo $module->id?> cf_flt_wrapper_<?php echo $direction ?> col-sm-4 col-xs-9" id="cf_flt_wrapper_<?php echo $key?>" role="presentation">
	
	

	</div>
	<?php
	}
	unset($flt_html);
	
	//reset all link
	if(!empty($resetUri)){?>
	<a class="cf_resetAll_link" rel="nofollow" data-module-id="<?php echo $module->id?>" href="<?php echo JRoute::_($resetUri)?>">
		<span class="cf_resetAll_label"><?php echo JText::_('MOD_CF_RESET_ALL')?></span>
	</a>
	<?php 
	}?>
					
		<?php 
		//if no category filter and category var. It means that we are in a category page and the category id should be kept

		
		//if no manufacturer filter and manufact. var. It means that we are in a manufact page and the manufact id should be kept
		if(empty($filters_html_array['virtuemart_manufacturer_id_'.$module->id]) && !empty($filters_render_array['selected_flt']['virtuemart_manufacturer_id'])):
			foreach($filters_render_array['selected_flt']['virtuemart_manufacturer_id'] as $key=>$id){?>
				<input type="hidden" name="virtuemart_manufacturer_id[<?php echo $key?>]" value="<?php echo $id?>"/>
			<?php 
			}
		endif;		
				
		
		//if the keyword search does not exist we have to add it as hidden, because it may added by the search mod
		 if(empty($filters_html_array['q_'.$module->id])): 
		 	$query=!empty($filters_render_array['selected_flt']['q'])?$filters_render_array['selected_flt']['q']:'';?>
		 	<input name="q" type="hidden" value="<?php echo $query;?>"/>
		 <?php 
		 endif;
		
		if(!$issef && $results_loading_mode!='ajax'):?> 	
			<input type="hidden" name="option" value="com_customfilters"/>
			<input type="hidden" name="view" value="products"/>
			<?php 		
			if($Itemid):?><input type="hidden" name="Itemid" value="<?php echo $Itemid?>"/>
			<?php 
			endif;
		endif;
				
		//in case of button add some extra vars to the form
		if($results_trigger=='btn'):?>
		<div class="col-sm-2 col-xs-3 cf_flt_apply">	
		<!--<input type="submit" class="button-2"  id="cf_apply_button_<?php echo $module->id?>" value="Apply"/>-->
		</div>
		<?php 
		endif;
		?>
		
		
</form>
</div>
<?php 
if($view!='module'){?>
	</div>
	<?php }

	
	//Scripts
	//load the VM scripts and styles in pages other than VM and CF when ajax is used
	if($params->get('results_loading_mode','ajax')=='ajax' && $component!='com_customfilters' || $component!='com_virtuemart' || ($component=='com_virtuemart' && $view!='category')){
		cftools::loadScriptsNstyles();
	}
	
	if(!empty($filters_render_array['scriptVars'])){
		$script_var_counter=count($filters_render_array['scriptVars']);
		$j=1;
		$script='
		if(typeof customFiltersProp=="undefined")customFiltersProp=new Array();
		customFiltersProp['.$module->id.']={';
		foreach($filters_render_array['scriptVars'] as $varName=>$value){
			$script.="$varName:'$value'";			
			if($j<$script_var_counter)$script.=','; //add a comma
			$j++;
		}
		$script.='};';
		$document->addScriptDeclaration($script);
	}
	
	if(!empty($filters_render_array['scriptProcesses'])){
		$script="window.addEvent('domready',function(){";
		foreach($filters_render_array['scriptProcesses'] as $process){
			$script.=$process;
		}
		$script.="});";	
		
		if($view=='module' && $component=='com_customfilters')	echo '<script type="text/javascript">'.$script.'</script>';
		else $document->addScriptDeclaration($script);		
	}
	
	

	//add some script files if exist
	if(!empty($filters_render_array['scriptFiles'])){
		foreach($filters_render_array['scriptFiles'] as $file){
			$document->addScript($file);
		}		
	}


} ?>
<script type="text/javascript">
jQuery('#select_category li').click(function(){
	var val = jQuery(this).attr('id');
	jQuery('#virtuemart_category_id').val(val);
	jQuery('.form-filter').submit();
});
</script>