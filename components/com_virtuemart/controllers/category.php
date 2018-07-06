<?php
/**
*
* Description
*
* @package	VirtueMart
* @subpackage
* @author Max Milbers
* @link http://www.virtuemart.net
* @copyright Copyright (c) 2004 - 2014 VirtueMart Team. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* VirtueMart is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* @version $Id: category.php 8597 2014-11-28 17:58:27Z Milbo $
*/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

// Load the controller framework
jimport('joomla.application.component.controller');

/**
* Class Description
*
* @package VirtueMart
*/
class VirtueMartControllerCategory extends JControllerLegacy {

    public function __construct() {
     	 parent::__construct();

     	 $this->registerTask('browse','category','getSubCategory');
   	}

	public function display($cachable = false, $urlparams = false)  {

		if (vRequest::getvar('search')) {
			$view = $this->getView('category', 'html');
			$view->display();
		} else {
			// Display it all
			$document = JFactory::getDocument();
			$viewType = $document->getType();
			$viewName = vRequest::getCmd('view', $this->default_view);
			$viewLayout = vRequest::getCmd('layout', 'default');

			$view = $this->getView($viewName, $viewType, '', array('layout' => $viewLayout));

			$view->assignRef('document', $document);

			$view->display();
		}
		if($categoryId = vRequest::getInt('virtuemart_category_id',0)){
			shopFunctionsF::setLastVisitedCategoryId($categoryId);
		}
		return $this;
	}
	
	public function getSubCategory() {
		$data = JRequest::getVar('parent_id');
		$dbo = JFactory::getDbo();
		$query = $dbo->getQuery(true);
		$query->select('b.virtuemart_category_id, b.category_name')
			->from('#__virtuemart_category_categories as a')
			->join('LEFT', '#__virtuemart_categories_en_gb as b on a.category_child_id = b.virtuemart_category_id')
			->where('a.category_parent_id = ' . $data);
		$rs = $dbo->setQuery($query)->loadObjectList();
		echo json_encode($rs); die;
	}
}
// pure php no closing tag
