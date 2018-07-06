<?php
/**
* @package ZenSocial
* @author Joomla Bamboo
* @website www.joomlabamboo.com
* @email design@joomlabamboo.com
* @copyright Copyright (c) 2013 Joomla Bamboo. All rights reserved.
* @license GNU General Public License version 2 or later
**/

// no direct access
defined('_JEXEC') or die('Restricted access');

require_once(dirname(__FILE__).DIRECTORY_SEPARATOR.'helper.php');


// Get the parameters
$socialiconstitle	= $params->get('socialiconstitle', '');
$socialalign	= $params->get('socialalign', '');
$socialfonticons	= $params->get('socialfonticons', '');
$id = $module->id;
// Icons
$iconsize	= $params->get('iconsize', '');
$iconcss	= $params->get('iconcss', '');
$iconcolor	= $params->get('iconcolor', '');
$iconcssbreakpoint	= $params->get('iconcssbreakpoint', '');
$iconcssmedia	= $params->get('iconcssmedia', '');
$icon1Font	= $params->get('icon1Font', '');
$icon1FontLink	= $params->get('icon1FontLink', '');
$icon2Font	= $params->get('icon2Font', '');
$icon2FontLink	= $params->get('icon2FontLink', '');
$icon3Font	= $params->get('icon3Font', '');
$icon3FontLink	= $params->get('icon3FontLink', '');
$icon4Font	= $params->get('icon4Font', '');
$icon4FontLink	= $params->get('icon4FontLink', '');
$icon5Font	= $params->get('icon5Font', '');
$icon5FontLink	= $params->get('icon5FontLink', '');
$icon6Font	= $params->get('icon6Font', '');
$icon6FontLink	= $params->get('icon6FontLink', '');
// Images
$icon1Image	= $params->get('icon1Image', '');
$icon1Link	= $params->get('icon1Link', '');
$icon2Image	= $params->get('icon2Image', '');
$icon2Link	= $params->get('icon2Link', '');
$icon3Image	= $params->get('icon3Image', '');
$icon3Link	= $params->get('icon3Link', '');
$icon4Image	= $params->get('icon4Image', '');
$icon4Link	= $params->get('icon4Link', '');
$icon5Image	= $params->get('icon5Image', '');
$icon5Link	= $params->get('icon5Link', '');
$icon6Image	= $params->get('icon6Image', '');
$icon6Link	= $params->get('icon6Link', '');
// Advanced
$moduleclass_sfx = $params->get('moduleclass_sfx', '');

// Load the css
$doc = JFactory::getDocument();
$doc->addStyleSheet('modules/mod_zensocial/css/social.css');
// Add icon css if enabled
 if($socialfonticons == 1){
$style = '#socialicons.mid'.$id.' span {font-size:'.$iconsize.';color:'.$iconcolor.'}';
if($iconcss != ''){
    $style .= $iconcss;
}
if (isset($iconcssbreakpoint) && isset($iconcssmedia)){
$style .= '@media all and (max-width:'.$iconcssbreakpoint.') {'.$iconcssmedia.'}';
}
$doc->addStyleDeclaration( $style );
}

require(JModuleHelper::getLayoutPath('mod_zensocial'));

?>
