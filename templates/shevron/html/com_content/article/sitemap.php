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

jimport('joomla.application.module.helper');
$module = JModuleHelper::getModule('mod_menu','Sitemap');
echo JModuleHelper::renderModule($module);

?>

