<?php
/**
 * This plugin is based on vm plugin "weight_country.php" 
 * 
 * Developed/Modified by Stergios Zgouletas / www.web-expert.gr
 * Shipping by State Plugin for VM 2.x /VM 3.x
 * @copyright Copyright (C) 2012-13 - All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * =======================================================================
 * @subpackage Plugins - shipment
 * @copyright Copyright (C) 2004-2012 VirtueMart Team - All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * http://virtuemart.org
 * @author Valerie Isaksen
 */

//vmdebug('we have here ',$viewData['product']->prices,$viewData['method']);
$currency = $viewData['currency'];
if(!empty($viewData['method']->countries) and is_array($viewData['method']->countries) and count($viewData['method']->countries)>0){
	$countryM = VmModel::getModel('country');
	echo Jtext::_('VMSHIPMENT_WEIGHT_COUNTRIES_SHIP_TO');
	foreach($viewData['method']->countries as $virtuemart_country_id){
		$country = $countryM->getData($virtuemart_country_id);
		echo $country->country_name;
		//vmdebug('my country ',$country);
	}
}
echo '</br>';
echo vmtext::sprintf('VMSHIPMENT_WEIGHT_COUNTRIES_WITH_SHIPMENT', $viewData['method']->shipment_name, $currency->priceDisplay($viewData['product']->prices['shipmentPrice']));
?>