<?php
defined('_JEXEC') or  die( 'Direct Access to '.basename(__FILE__).' is not allowed.' );
/**
 * Raindrops Shopping Cart Module for Virtuemart
 * @package     Joomla.Site
 * @subpackage  mod_shoppingcart
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

error_reporting(0);
@ini_set('display_errors', 0);

defined('DS') or define('DS', DIRECTORY_SEPARATOR);
if (!class_exists( 'VmConfig' )) require(JPATH_ROOT.DS.'administrator'.DS.'components'.DS.'com_virtuemart'.DS.'helpers'.DS.'config.php');
VmConfig::loadConfig();

VmConfig::loadJLang('mod_shoppingcart', true);
VmConfig::loadJLang('com_virtuemart', true);
vmJsApi::jQuery();

//$doc = JFactory::getDocument();
vmJsApi::addJScript("/modules/mod_shoppingcart/assets/js/update_cart.js",false,false);
$js = '
jQuery(document).ready(function(){
    jQuery("body").on("click", "updateVirtueMartCartModule", function(e) {
        jQuery("#vmCartModule").updateVirtueMartCartModule();
    });
});
';
vmJsApi::addJScript('vm.CartModule.UpdateModule',$js);

$jsVars  = ' jQuery(document).ready(function(){
    jQuery(".vmCartModule").productUpdate();
});' ;
//vmJsApi::addJScript('vm.CartModule.UpdateProduct',$jsVars);


//This is strange we have the whole thing again in controllers/cart.php public function viewJS()
if(!class_exists('VirtueMartCart')) require(VMPATH_SITE.DS.'helpers'.DS.'cart.php');
require_once(VMPATH_ADMIN . DS . 'helpers' . DS . 'currencydisplay.php');
  //get virtualmart symbol
  $obj = CurrencyDisplay::getInstance();
 $symbol= $obj->getSymbol();

$cart = VirtueMartCart::getCart(false);
/*echo"<pre>";print_r($cart);   exit;*/
       
require_once __DIR__ . '/helper.php';
$i=0;
/*exit(print_r($cart->cartProductsData));*/


$viewName = vRequest::getString('view',0);
if($viewName=='cart'){
    $checkAutomaticPS = true;
} else {
    $checkAutomaticPS = false;
}
$data = $cart->prepareAjaxData();/*$data->products[0]["dec"]=$cart->products[0]->product_desc;*/
 /*$data['product_desc']=$cart->products[0]->product_desc;*/
/*echo"<pre>";print_r($cart->products[0]->virtuemart_media_id[0]);exit;$products[0]->virtuemart_media_id[0];*/
$temp = $data;
/*exit( $temp->currencyDisplay->createPriceDiv ('basePriceVariant', '', $prow->prices));*/
foreach ($data as $k => $products) {
    foreach ($products as $key => $product) {
       /* echo"<pre>";print_r($cart->products[$key]->allPrices[0]['basePriceVariant']);exit;*/
    $desc=$cart->products[$key]->product_desc;   
     $de = substr($desc,0,35);/*exit($de."...");*/
    $temp->products[$key]['product_desc']  = "<pre style='background-color:transparent;border:none;'>".$de."...</pre>";
    $temp->products[$key]['product_price'] =$cart->products[$key]->allPrices[0]['salesPrice'];
    $id=$cart->products[$key]->virtuemart_media_id[0];
    /*exit($image);*/
     
     $temp->products[$key]['product_image']=modshoppingcartHelper::imagecall($id);
    $temp->products[$key]['product_cart_id1']=$key;
    $temp->products[$key]['product_currency']=$symbol;
/*    echo"<pre>";print_r($temp->products[$key]['product_image']);exit;*/
    //$temp->products[$key]['product_images']=$cart->products[$key]->images[0]->displayMediaThumb ('', FALSE);
   }   
}

$data = $temp;
/*echo"<pre>";print_r($temp);exit;*/
if(JRequest::getVar('vmcartmodajax',0)==1){
      echo json_encode($data);exit;
}
/*echo"<pre>";print_r($data);exit;*/
if (!class_exists('CurrencyDisplay')) require(VMPATH_ADMIN . DS. 'helpers' . DS . 'currencydisplay.php');
$currencyDisplay = CurrencyDisplay::getInstance( );

vmJsApi::cssSite();
$layout = $params->get('layout','Default');
$moduleclass_sfx = $params->get('moduleclass_sfx', '');
$show_price = (bool)$params->get( 'show_price', 1 ); // Display the Product Price?
$show_product_list = (bool)$params->get( 'show_product_list', 1 ); // Display the Product Price?

require(JModuleHelper::getLayoutPath('mod_shoppingcart',$layout));
echo vmJsApi::writeJS();
 
 ?>


 <script>


</script>
