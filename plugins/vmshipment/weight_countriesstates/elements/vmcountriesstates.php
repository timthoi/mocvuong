<?php
/**
* Developed by Stergios Zgouletas / www.web-expert.gr
* Shipping by State Plugin for VM 3.x
* @copyright Copyright (C) 2012-13 - All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
*/

defined('_JEXEC') or die();
JFormHelper::loadFieldClass('list');
jimport('joomla.form.formfield');

class JFormFieldVmCountriesstates extends JFormFieldList {

	var $type = 'vmCountriesstates';

	protected function getInput() {
		$this->multiple=true;
		return parent::getInput();
	}
	
	protected function getOptions() {
		$select_array = array();
		$this->multiple=true;

        $db =  JFactory::getDBO();
        $query = 'SELECT `country_3_code` as code,`virtuemart_country_id` AS value, `country_name` AS text FROM `#__virtuemart_countries` WHERE `published` = 1 ORDER BY `country_name` ASC;';
        $db->setQuery($query);
        $counties = $db->loadObjectList();
		
		foreach($counties as $country){
			$db->setQuery("SELECT `virtuemart_state_id` AS value, `state_name` AS text FROM `#__virtuemart_states` WHERE `virtuemart_country_id`=".$country->value." AND `published` = 1 ORDER BY `state_name` ASC;");
			$states = $db->loadObjectList();
			if(count($states)>0){
				$select_array[] = JHtml::_('select.option','<OPTGROUP>',$country->text); // start of option group
				$select_array[] = JHtml::_('select.option',$country->value.'_all'," -All States:".$country->text);
				foreach($states as $state)  $select_array[] = JHtml::_('select.option',$country->value."_state_".$state->value," -".$state->text.' ['.$country->code.']');
				$select_array[] = JHtml::_('select.option','</OPTGROUP>'); // start of option group
			}else{
				 $select_array[] = JHtml::_('select.option',$country->value,$country->text);
			}
		}

		//BAD $class = 'multiple="true" size="10"';
		// Merge any additional options in the XML definition.
		$select_array = array_merge(parent::getOptions(), $select_array);

		return $select_array;
	}
}