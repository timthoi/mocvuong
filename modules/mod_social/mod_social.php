<?php
/**
 * @copyright	Copyright © 2016 - All rights reserved.
 * @license		GNU General Public License v2.0
 * @generator	http://xdsoft/joomla-module-generator/
 */
defined('_JEXEC') or die;

$doc = JFactory::getDocument();
/* Available fields:"facebook","twitter","youtube","linkedln", */
// Include assets
$doc->addStyleSheet(JURI::root()."modules/mod_social/assets/css/style.css");
$doc->addScript(JURI::root()."modules/mod_social/assets/js/script.js");
// $width 			= $params->get("width");

/**
	$db = JFactory::getDBO();
	$db->setQuery("SELECT * FROM #__mod_social where del=0 and module_id=".$module->id);
	$objects = $db->loadAssocList();
*/
require JModuleHelper::getLayoutPath('mod_social', $params->get('layout', 'default'));