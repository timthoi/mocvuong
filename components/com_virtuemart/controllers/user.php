<?php
/**
 *
 * Controller for the front end User maintenance
 *
 * @package	VirtueMart
 * @subpackage User
 * @author Oscar van Eijk
 * @link http://www.virtuemart.net
 * @copyright Copyright (c) 2004 - 2010 VirtueMart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * VirtueMart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * @version $Id: user.php 8812 2015-03-31 18:48:39Z Milbo $
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

// Load the controller framework
jimport('joomla.application.component.controller');

/**
 * VirtueMart Component Controller
 *
 * @package		VirtueMart
 */
class VirtueMartControllerUser extends JControllerLegacy
{

	public function __construct()
	{
		parent::__construct();
		$this->useSSL = VmConfig::get('useSSL',0);
		$this->useXHTML = false;
		VmConfig::loadJLang('com_virtuemart_shoppers',TRUE);
	}

	/**
	 * Override of display to prevent caching
	 *
	 * @return  JController  A JController object to support chaining.
	 */
	public function display($cachable = false, $urlparams = array()){

		$document = JFactory::getDocument();
		$viewType = $document->getType();
		$viewName = vRequest::getCmd('view', 'user');
		$viewLayout = vRequest::getCmd('layout', 'default');

		$view = $this->getView($viewName, $viewType, '', array('layout' => $viewLayout));
		$view->assignRef('document', $document);

		if (!class_exists('VirtueMartCart')) require(VMPATH_SITE . DS . 'helpers' . DS . 'cart.php');
		$cart = VirtueMartCart::getCart();
		$cart->_fromCart = false;
		$cart->setCartIntoSession();
		$view->display();

		return $this;
	}


	function editAddressCart(){

		$view = $this->getView('user', 'html');
		$view->setLayout('edit_address');

		if (!class_exists('VirtueMartCart')) require(VMPATH_SITE . DS . 'helpers' . DS . 'cart.php');
		$cart = VirtueMartCart::getCart();
		$cart->_fromCart = true;
		$cart->setCartIntoSession();

		// Display it all
		$view->display();

	}


	/**
	 * This is the save function for the normal user edit.php layout.
	 *
	 * @author Max Milbers
	 */
	function saveUser(){

		if (!class_exists('VirtueMartCart')) require(VMPATH_SITE . DS . 'helpers' . DS . 'cart.php');
		$cart = VirtueMartCart::getCart();

		$layout = vRequest::getCmd('layout','edit');


		if($cart->_fromCart or $cart->getInCheckOut()){
			vmdebug('saveUser _fromCart',(int)$cart->_fromCart);
			$msg = $this->saveData($cart);
			$task = '';
			if ($cart->getInCheckOut()){
				$task = '&task=checkout';
				vmdebug('saveUser InCheckOut',(int)$cart->_fromCart);
			}
			$this->setRedirect(JRoute::_('index.php?option=com_virtuemart&view=cart'.$task, FALSE) , $msg);
		} else {
			$msg = $this->saveData(false);
			$this->setRedirect( JRoute::_('index.php?option=com_virtuemart&view=user&layout='.$layout, FALSE), $msg );
		}

	}

	function saveUserAddress(){

		if (!class_exists('VirtueMartCart')) require(VMPATH_SITE . DS . 'helpers' . DS . 'cart.php');
		$cart = VirtueMartCart::getCart();

		$layout = vRequest::getCmd('layout','edit');

		if($cart->_fromCart or $cart->getInCheckOut()){
			vmdebug('saveUser _fromCart',(int)$cart->_fromCart);
			$msg = $this->saveData($cart);
			$task = '';
			if ($cart->getInCheckOut()){
				$task = '&task=checkout';
				vmdebug('saveUser InCheckOut',(int)$cart->_fromCart);
			}
			$this->setRedirect(JRoute::_('index.php?option=com_virtuemart&view=cart'.$task, FALSE) , $msg);
		} else {
			$msg = $this->saveDataAddress(false);
			$this->setRedirect( JRoute::_('index.php?option=com_virtuemart&view=user&layout=list_address_user', FALSE), $msg );
		}

	}

	function saveUserCart(){

		if (!class_exists('VirtueMartCart')) require(VMPATH_SITE . DS . 'helpers' . DS . 'cart.php');
		$cart = VirtueMartCart::getCart();

		$layout = vRequest::getCmd('layout','edit');


		if($cart->_fromCart or $cart->getInCheckOut()){
			vmdebug('saveUser _fromCart',(int)$cart->_fromCart);
			$msg = $this->saveData($cart);
			$task = '&task=checkout';
			$this->setRedirect(JRoute::_('index.php?option=com_virtuemart&view=cart'.$task, FALSE) , $msg);
		} else {
			$msg = $this->saveData(false);
			$this->setRedirect( JRoute::_('index.php?option=com_virtuemart&view=user&layout='.$layout, FALSE), $msg );
		}

	}

	function saveAddressST(){

		$msg = $this->saveData(false);
		$layout = 'edit';// vRequest::getCmd('layout','edit');
		$this->setRedirect( JRoute::_('index.php?option=com_virtuemart&view=user&layout='.$layout, FALSE), $msg );

	}

	/**
	 * Save the user info. The saveData function don't use the userModel store function for anonymous shoppers, because it would register them.
	 * We make this function private, so we can do the tests in the tasks.
	 *
	 * @author Max Milbers
	 * @author Valérie Isaksen
	 *
	 * @param boolean Defaults to false, the param is for the userModel->store function, which needs it to determine how to handle the data.
	 * @return String it gives back the messages.
	 */
	private function saveData($cartObj) {

		$mainframe = JFactory::getApplication();

		$msg = '';
		$data = vRequest::getPost(FILTER_SANITIZE_STRING);
		$register = isset($_REQUEST['register']);

		$userModel = VmModel::getModel('user');
		$currentUser = JFactory::getUser();

		if(empty($data['address_type'])){
			$data['address_type'] = vRequest::getCmd('addrtype','BT');
		}

		if($cartObj){
			if($cartObj->_fromCart or $cartObj->getInCheckOut()){
				if(!class_exists('VirtueMartCart')) require(VMPATH_SITE.DS.'helpers'.DS.'cart.php');
				$cart = VirtueMartCart::getCart();
				$prefix= '';
				if ($data['address_type'] == 'STaddress' || $data['address_type'] =='ST') {
					$prefix = 'shipto_';
					vmdebug('Storing user ST prefix '.$prefix);
				}
				$cart->saveAddressInCart($data, $data['address_type'],true,$prefix);
			}
		}

		if(isset($data['vendor_accepted_currencies'])){
			// Store multiple selectlist entries as a ; separated string
			if (array_key_exists('vendor_accepted_currencies', $data) && is_array($data['vendor_accepted_currencies'])) {
				$data['vendor_accepted_currencies'] = implode(',', $data['vendor_accepted_currencies']);
			}

			$data['vendor_store_name'] = vRequest::getHtml('vendor_store_name');
			$data['vendor_store_desc'] = vRequest::getHtml('vendor_store_desc');
			$data['vendor_terms_of_service'] = vRequest::getHtml('vendor_terms_of_service');
			$data['vendor_letter_css'] = vRequest::getHtml('vendor_letter_css');
			$data['vendor_letter_header_html'] = vRequest::getHtml('vendor_letter_header_html');
			$data['vendor_letter_footer_html'] = vRequest::getHtml('vendor_letter_footer_html');
		}

		if($data['address_type'] == 'ST' and !$currentUser->guest){
			$ret = $userModel->storeAddress($data);
			if($cartObj and !empty($ret)){
				$cartObj->selected_shipto = $ret;
				$cartObj->setCartIntoSession();
			}
		} else {

			if($currentUser->guest==1 and ($register or !$cartObj )){
				if($this->checkCaptcha('index.php?option=com_virtuemart&view=user&task=editaddresscart&addrtype=BT') == FALSE) {
					$msg = vmText::_('PLG_RECAPTCHA_ERROR_INCORRECT_CAPTCHA_SOL');
					if($cartObj and $cartObj->_fromCart) {
						$this->redirect( JRoute::_('index.php?option=com_virtuemart&view=user&task=editaddresscart&addrtype=BT'), $msg );
					} else if($cartObj and $cartObj->getInCheckOut()) {
						$this->redirect( JRoute::_('index.php?option=com_virtuemart&view=user&task=editaddresscheckout&addrtype=BT'), $msg );
					} else {
						$this->redirect( JRoute::_('index.php?option=com_virtuemart&view=user&task=edit&addrtype=BT'), $msg );
					}
					return $msg;
				}
			}

			if($currentUser->guest!=1 or !$cartObj or ($currentUser->guest==1 and $register) ){

				$switch = false;
				if($currentUser->guest==1 and $register){
					$userModel->setId(0);
					$adminID = JFactory::getSession()->get('vmAdminID',false);
					if($adminID){
						if(!class_exists('vmCrypt'))
							require(VMPATH_ADMIN.DS.'helpers'.DS.'vmcrypt.php');
						$adminID = vmCrypt::decrypt($adminID);
						$adminIdUser = JFactory::getUser($adminID);
						if($adminIdUser->authorise('core.admin', 'com_virtuemart') or $adminIdUser->authorise('vm.user', 'com_virtuemart')){
							$superUser = VmConfig::isSuperVendor($adminID);
							if($superUser>1){
								$data['vendorId'] = $superUser;
							}
							$switch = true;
						}
					}
				}

				if(!class_exists('VirtueMartCart')) require(VMPATH_SITE.DS.'helpers'.DS.'cart.php');
				$cart = VirtueMartCart::getCart();
				if(!empty($cart->vendorId) and $cart->vendorId!=1){
					$data['vendorId'] = $cart->vendorId;
				}
				$ret = $userModel->store($data);

				if($switch){ //and VmConfig::get ('oncheckout_change_shopper')){
					//update session
					$current = JFactory::getUser($ret['newId']);
					$session = JFactory::getSession();
					$session->set('user', $current);
				}
			}


			if($currentUser->guest==1 and ($register or !$cartObj )){
				$msg = (is_array($ret)) ? $ret['message'] : $ret;
				$usersConfig = JComponentHelper::getParams( 'com_users' );
				$useractivation = $usersConfig->get( 'useractivation' );

				if (is_array($ret) and $ret['success'] and !$useractivation) {
					// Username and password must be passed in an array
					$credentials = array('username' => $ret['user']->username,
						'password' => $ret['user']->password_clear
					);
					$return = $mainframe->login($credentials);
				} else if(VmConfig::get('oncheckout_only_registered',0)){
					$layout = vRequest::getCmd('layout','edit');
					$this->redirect( JRoute::_('index.php?option=com_virtuemart&view=user&layout='.$layout, FALSE), $msg );
				}
			}
		}

		return $msg;
	}

	private function addAddressBT($virtuemart_userinfo_id, $user_id, $first_name, $last_name, $address_1, $zip, $city, $virtuemart_country_id, $phone_1) {
		$dbo = Jfactory::getDbo();
		if(empty($virtuemart_userinfo_id)) {
			$query = "INSERT INTO #__virtuemart_userinfos(`virtuemart_user_id`,`address_type`, `first_name`,`last_name`,`address_1`, `zip`, `city`, `virtuemart_country_id`, `phone_1`) VALUES('{$user_id}', 'BT', '{$first_name}', '{$last_name}', '{$address_1}', '{$zip}', '{$city}', '{$virtuemart_country_id}', '{$phone_1}')";
			$dbo->setQuery($query);
			$dbo->execute();
		} else {
			$query = "UPDATE #__virtuemart_userinfos SET `first_name` = '{$first_name}',`last_name` = '{$last_name}',`address_1` = '{$address_1}', `zip` = '{$zip}', `city` = '{$city}', `virtuemart_country_id` = '{$virtuemart_country_id}', `phone_1` = '{$phone_1}' WHERE `virtuemart_userinfo_id` = {$virtuemart_userinfo_id}";
			$dbo->setQuery($query);
			$dbo->execute();
		}
	}

	private function addAddressST($virtuemart_userinfo_id, $user_id, $address_type, $address_type_name, $first_name, $last_name, $address_1, $zip, $city, $virtuemart_country_id, $phone_1) {
		$dbo = Jfactory::getDbo();
		if($virtuemart_userinfo_id == '0') {
			$query = "INSERT INTO #__virtuemart_userinfos(`virtuemart_user_id`,`address_type`, `address_type_name`,`first_name`,`last_name`,`address_1`, `zip`, `city`, `virtuemart_country_id`, `phone_1`) VALUES('{$user_id}', '{$address_type}', '{$address_type_name}', '{$first_name}', '{$last_name}', '{$address_1}', '{$zip}', '{$city}', '{$virtuemart_country_id}', '{$phone_1}')";
			$dbo->setQuery($query);
			$dbo->execute();
		} else {
			$query = "UPDATE #__virtuemart_userinfos SET `address_type` = '{$address_type}', `address_type_name` = '{$address_type_name}', `first_name` = '{$first_name}',`last_name` = '{$last_name}',`address_1` = '{$address_1}', `zip` = '{$zip}', `city` = '{$city}', `virtuemart_country_id` = '{$virtuemart_country_id}', `phone_1` = '{$phone_1}' WHERE `virtuemart_userinfo_id` = {$virtuemart_userinfo_id}";
			$dbo->setQuery($query);
			$dbo->execute();
		}
	}

	private function saveDataAddress($cartObj) {
		$userModel = VmModel::getModel('user');
		$mainframe = JFactory::getApplication();
		$user = JFactory::getUser();
		$msg = '';
		$data = vRequest::getPost(FILTER_SANITIZE_STRING);
		$this->addAddressBT($data['virtuemart_userinfo_id'], $user->id, $data['first_name'], $data['last_name'], $data['address_1'], $data['zip'], $data['city'], $data['virtuemart_country_id'], $data['phone_1']);
		foreach($data['shipto_virtuemart_userinfo_id'] as $id) {
			$tmp = array();
			$tmp['shipto_first_name'] = $data['shipto_first_name'][$id];
			$tmp['shipto_last_name'] = $data['shipto_last_name'][$id];
			$tmp['shipto_address_type_name'] = $data['shipto_address_type_name'][$id];
			$tmp['shipto_address_1'] = $data['shipto_address_1'][$id];
			$tmp['shipto_zip'] = $data['shipto_zip'][$id];
			$tmp['shipto_city'] = $data['shipto_city'][$id];
			$tmp['shipto_virtuemart_country_id'] = $data['shipto_virtuemart_country_id'][$id];
			$tmp['shipto_phone_1'] = $data['shipto_phone_1'][$id];
			$tmp['address_type'] = $data['address_type'][$id];
			$this->addAddressST($id, $user->id, $tmp['address_type'], $tmp['shipto_address_type_name'], $tmp['shipto_first_name'], $tmp['shipto_last_name'], $tmp['shipto_address_1'], $tmp['shipto_zip'], $tmp['shipto_city'], $tmp['shipto_virtuemart_country_id'], $tmp['shipto_phone_1']);
		}
		$msg = (is_array($ret)) ? $ret['message'] : $ret;
		$this->redirect( JRoute::_('index.php?option=com_virtuemart&view=user&layout=list_address_user', FALSE), $msg );
		return $msg;
	}

	/**
	 * Action cancelled; return to the previous view
	 *
	 * @author Max Milbers
	 */
	function cancel()
	{
		if(!class_exists('VirtueMartCart')) require(VMPATH_SITE.DS.'helpers'.DS.'cart.php');
		$cart = VirtueMartCart::getCart();
		if($cart->_fromCart){
			$cart->setOutOfCheckout();
			$this->setRedirect( JRoute::_('index.php?option=com_virtuemart&view=cart', FALSE)  );
		} else {
			$return = JURI::base();
			$this->setRedirect( $return );
		}

	}


	function removeAddressST(){

		$virtuemart_userinfo_id = vRequest::getInt('virtuemart_userinfo_id');
		$virtuemart_user_id = vRequest::getInt('virtuemart_user_id');
		$data = JRequest::get('post');
		//Lets do it dirty for now
		$userModel = VmModel::getModel('user');
		vmdebug('removeAddressST',$data['virtuemart_user_id'],$data['virtuemart_userinfo_id'][0]);
		$userModel->setId($data['virtuemart_user_id']);
		$userModel->removeAddress($data['virtuemart_userinfo_id'][0]);
		$layout = vRequest::getCmd('layout','edit');
		$this->setRedirect( JRoute::_('index.php?option=com_virtuemart&view=user&layout=list_address_user&virtuemart_user_id[]='.$data['virtuemart_user_id'], $this->useXHTML,$this->useSSL) );
	}

	/**
	 * Check the Joomla ReCaptcha Plg
	 *
	 * @author Maik Künnemann
	 */
	function checkCaptcha($retUrl){
		if(JFactory::getUser()->guest==1 and VmConfig::get ('reg_captcha')){
			$recaptcha = vRequest::getVar ('recaptcha_response_field');
			JPluginHelper::importPlugin('captcha');
			$dispatcher = JDispatcher::getInstance();
			$res = $dispatcher->trigger('onCheckAnswer',$recaptcha);
			if(!$res[0]){
				$data = vRequest::getPost();
				$data['address_type'] = vRequest::getVar('addrtype','BT');
				if(!class_exists('VirtueMartCart')) require(VMPATH_SITE.DS.'helpers'.DS.'cart.php');
				$cart = VirtueMartCart::getCart();
				$prefix= '';
				if ($data['address_type'] == 'STaddress' || $data['address_type'] =='ST') {
					$prefix = 'shipto_';
				}
				$cart->saveAddressInCart($data, $data['address_type'],true,$prefix);
				$errmsg = vmText::_('PLG_RECAPTCHA_ERROR_INCORRECT_CAPTCHA_SOL');
				$this->setRedirect (JRoute::_ ($retUrl . '&captcha=1', FALSE), $errmsg);
				return FALSE;
			} else {
				return TRUE;
			}
		} else {
			return TRUE;
		}
	}
	
	//Get ST Address by id
	public function getSTAddress()
	{
		$st_address = array();
		$virtuemart_user_id = JRequest::getInt('virtuemart_user_id');
		$virtuemart_userinfo_id = JRequest::getInt('virtuemart_userinfo_id');
		
		if(isset($virtuemart_user_id) && isset($virtuemart_userinfo_id)) {
			$query = 'SELECT userinfos.*, states.state_name FROM #__virtuemart_userinfos as userinfos, #__virtuemart_states as states
						WHERE userinfos.address_type = "ST" AND userinfos.virtuemart_user_id = '.$virtuemart_user_id.' AND userinfos.virtuemart_userinfo_id = '.$virtuemart_userinfo_id.' AND (states.virtuemart_state_id = userinfos.virtuemart_state_id OR userinfos.virtuemart_state_id = 0)';
			$db = JFactory::getDBO();
			$db->setQuery($query);
			$st_address = $db->loadAssoc();
			
			echo '<div class="result">'.json_encode($st_address).'</div>';
		}
		else{
			echo '<div class="result">'.json_encode(array('success' => 'fail')).'</div>';
		}
	}
	
	public function getLatestAddress()
	{
		$st_address = array();
		$virtuemart_user_id = JRequest::getInt('virtuemart_user_id');
		$virtuemart_userinfo_id = JRequest::getInt('virtuemart_userinfo_id');
		
		if(isset($virtuemart_user_id) && isset($virtuemart_userinfo_id)) {
			$query = 'SELECT * FROM `#__virtuemart_userinfos`
						WHERE `address_type` = "ST" AND `virtuemart_user_id` = '.$virtuemart_user_id.' ORDER BY created_on DESC';
			$db = JFactory::getDBO();
			$db->setQuery($query);
			$st_address = $db->loadAssoc();
			
			echo '<div class="result">'.json_encode($st_address).'</div>';
		}
		else{
			echo '<div class="result">'.json_encode(array('success' => 'fail')).'</div>';
		}
	}
	
	//load all ST addresses
	public function loadSTAddress()
	{
		$st_address = array();
		$virtuemart_user_id = JRequest::getInt('virtuemart_user_id');
		
		if(isset($virtuemart_user_id)) {
			$query = 'SELECT * FROM `#__virtuemart_userinfos`
						WHERE `address_type` = "ST" AND `virtuemart_user_id` = '.$virtuemart_user_id.' ORDER BY modified_on DESC';
			$db = JFactory::getDBO();
			$db->setQuery($query);
			$st_address = $db->loadObjectList();
			
			echo '<div class="result">'.json_encode($st_address).'</div>';
		}
		else{
			echo '<div class="result">'.json_encode(array('success' => 'fail')).'</div>';
		}
	}
	
	public function getShippingMethodByCountry()
	{
		if (!class_exists('VirtueMartCart')) require(VMPATH_SITE . DS . 'helpers' . DS . 'cart.php');
		$cart = VirtueMartCart::getCart();
		$cart->prepareCartData();
		
		$total_weight = $this->getOrderWeight($cart, 'KG');
		$shipping_method = array();
		$virtuemart_country_id = JRequest::getInt('virtuemart_country_id');
		$virtuemart_state_id = JRequest::getInt('virtuemart_state_id');
		$prices = $cart->getCartPrices();
		$basePrice = $prices['basePrice'];
		
		if(!empty($virtuemart_country_id) || !empty($virtuemart_state_id))
		{
			$query = 'SELECT a.*, b.shipment_name FROM `#__virtuemart_shipmentmethods` as a, 
			`#__virtuemart_shipmentmethods_en_gb` as b WHERE a.published = 1 AND a.virtuemart_shipmentmethod_id = b.virtuemart_shipmentmethod_id ORDER BY a.ordering DESC';
			$db = JFactory::getDBO();
			$db->setQuery($query);
			$shipping_methods = $db->loadObjectList();
			
			if(!empty($virtuemart_state_id)) $virtuemart_state_id = $virtuemart_country_id."_state_".$virtuemart_state_id;
			
			foreach($shipping_methods as $item)
			{
				$countries = $this->explodeShipmentParams($item->shipment_params, 'countriesstates=', 2);
				$countries = str_replace("countries=","",$countries);
				$countries = str_replace(array('[',']'), '', $countries);
				$countries = explode(',', $countries);
	
				$max_weight = $this->explodeShipmentParams($item->shipment_params, 'weight_stop=', 6);
				$orderamount_stop =  $this->explodeShipmentParams($item->shipment_params, 'orderamount_stop=', 11);
				$orderamount_start =  $this->explodeShipmentParams($item->shipment_params, 'orderamount_start=', 10);
				//if countries contains [country_id]_all
				if(in_array($virtuemart_country_id."_all", $countries))
				{
					//check the country only
					$condition = in_array($virtuemart_country_id, $countries);
				}
				else
				{
					//if countries contains states id
					if(!empty($virtuemart_state_id)){
						$condition = in_array($virtuemart_state_id, $countries);
					}
					else {
						$condition = in_array($virtuemart_country_id, $countries);
					}
				}
				
				if ($max_weight){
					if($condition && $total_weight <= $max_weight)
					{
						$shipping_method = $item;
						break;
					}
				}
				if ($orderamount_start){
					if($condition && $orderamount_start < $basePrice)
					{
						$shipping_method = $item;
						break;
					}	
				}
				if ($orderamount_stop){
					if($condition && $orderamount_stop >= $basePrice)
					{
						$shipping_method = $item;
						break;
					}	
				}
				
			}
	
			echo '<div class="result">'.json_encode($shipping_method).'</div>'; 
		}
		//echo json_encode($basePrice);die;
		return $shipping_method; 
	}
	
	public function saveShippingMethod()
	{
		if (!class_exists('VirtueMartCart')) require(VMPATH_SITE . DS . 'helpers' . DS . 'cart.php');
		$cart = VirtueMartCart::getCart();
		$virtuemart_shipmentmethod_id = JRequest::getInt('virtuemart_shipmentmethod_id');
		$cart->virtuemart_shipmentmethod_id = $virtuemart_shipmentmethod_id;
		$cart->setCartIntoSession();
	}
	
	//countriesstates=["123", "456"] will return ["123", "456"]
	private function explodeShipmentParams($item, $str, $pos)
	{
		$arr = explode('|', $item);
		$result = str_replace($str, '', $arr[$pos]);
		$result = str_replace('"', '', $result);
		
		return $result;
	}
	
	/**
	 * Get the total weight for the order, based on which the proper shipping rate
	 * can be selected.
	 *
	 * @param object $cart Cart object
	 * @return float Total weight for the order
	 * @author Oscar van Eijk
	 */
	protected function getOrderWeight (VirtueMartCart $cart, $to_weight_unit) {

		static $weight = 0.0;
		if(count($cart->products)>0 and empty($weight)){
			foreach ($cart->products as $product) {
				vmdebug('getOrderWeight',$product->product_weight);
				$weight += (ShopFunctions::convertWeigthUnit ($product->product_weight, $product->product_weight_uom, $to_weight_unit) * $product->quantity);
			}
		}

		return $weight;
	}
	
}
// No closing tag
