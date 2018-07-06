<?php
/**
*
* Modify user form view
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
* @version $Id: edit.php 8768 2015-03-02 12:22:14Z Milbo $
*/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');
$user = JFactory::getUser();

if(!$user->guest) {
// Implement Joomla's form validation
JHtml::_('behavior.formvalidation');
JHtml::stylesheet('vmpanels.css', JURI::root().'components/com_virtuemart/assets/css/'); // VM_THEMEURL
?>
<style type="text/css">
.invalid {
	border-color: #f00;
	background-color: #ffd;
	color: #000;
}
label.invalid {
	background-color: #fff;
	color: #f00;
}
*[id^='fancybox-']{
	display: none;
}
</style>
<script>
	jQuery(function($){
		$('#tabs').tab();
	})
</script>

<?php
    $user = JFactory::getUser();
	$id = $user->id;
    $user = JFactory::getUser($id);
	$app = JFactory::getApplication();
	$postData = $app->input->getArray(array());
	if(isset($postData['btn-save-info']) && $postData['btn-save-info']){
		$db = JFactory::getDBO();
		$query = "Update #__users SET email = '{$postData['email']}'  WHERE id = " . $id;
		$db->setQuery($query);
        $db->execute();
		$query1 = "Update #__virtuemart_userinfos SET first_name = '{$postData['first_name']}', last_name = '{$postData['last_name']}', phone_1 = '{$postData['contact_number']}'  WHERE virtuemart_user_id = {$id}";
		$db->setQuery($query1);
		$db->execute();
	}

    $query = 'SELECT * FROM `#__virtuemart_userinfos` WHERE `virtuemart_user_id` = '. $id;
    $db = JFactory::getDBO();
    $db->setQuery($query);
    $userinfo = $db->loadAssoc();
    function getCountryByID ($id, $fld = 'country_name') {

		if (empty($id)) {
			return '';
		}

		$id = (int)$id;
		$db = JFactory::getDBO ();

		$q = 'SELECT `' . $db->escape ($fld) . '` AS fld FROM `#__virtuemart_countries` WHERE virtuemart_country_id = ' . (int)$id;
		$db->setQuery ($q);
		return $db->loadResult ();
	}
?>

<?php vmJsApi::vmValidator(); ?>

<div class="my-account">
	<h2 class="shop-heading"><?php echo $this->page_title ?></h2>
</div>

<div id="content" class="profile-tab">
	<div class="border-one">
    <ul id="tabs" class="nav nav-tabs" data-tabs="tabs">
        <li class="active"><a href="index.php?option=com_virtuemart&view=user&layout=edit">Personal Information</a></li>
        <li><a href="index.php?option=com_virtuemart&view=user&layout=list_address_user">Your Address</a></li>
        <li><a href="index.php?option=com_virtuemart&view=user&layout=list_order_user">My Order</a></li>
    </ul>
    </div>
    <div id="my-tab-content" class="tab-content">
        <div id="personal-info">

            <form method="post" id="adminForm" name="userForm personal_info_form" action="<?php echo JRoute::_('index.php?option=com_virtuemart&view=user',$this->useXHTML,$this->useSSL) ?>" class="form-validate">
			    <fieldset>
			    <div class="row">
					<div class="col-md-6">
						<label>First Name *:</label>
						<input type="text" id="first_name_field" name="first_name" size="30" value="<?php echo $userinfo['first_name']?$userinfo['first_name']:'' ?>" class="required" maxlength="32" aria-required="true" required="required" aria-invalid="false" placeholder="First Name*">
					</div>
					<div class="col-md-6">
						<label>Last Name *:</label>
						<input type="text" id="last_name_field" name="last_name" size="30" value="<?php echo $userinfo['last_name']?$userinfo['last_name']:'' ?>" class="required" maxlength="32" aria-required="true" required="required" aria-invalid="false" placeholder="Last Name*">
					</div>
					<div class="col-md-6">
						<label>Email Address *:</label>
						<input type="text" id="email_field" name="email" size="30" value="<?php echo $user->email ?>" class="required" maxlength="32" aria-required="true" required="required" aria-invalid="false" placeholder="Email*">
					</div>
					<div class="col-md-6">
						<label>Contact Number *:</label>
						<input type="text" id="contact_number" name="contact_number" size="30" value="<?php echo $userinfo['phone_1']?$userinfo['phone_1']:'' ?>" class="required" maxlength="32" aria-required="true" required="required" aria-invalid="false" placeholder="Contact Number (country code - area code - number)">
					</div>
				</div>
				</fieldset>

				<a href="<?php echo JRoute::_('index.php?option=com_users&view=profile&layout=edit&tmpl=component'); ?>" class="change-password" data-toggle="modal" data-target="#modal-change-password">Change password</a>

			    <div class="buttonBar-right">
					<input class="button button-2" type="submit" name = "btn-save-info" value="Save changes" onclick="javascript:return myValidator(userForm, true);" />
                    <input type="hidden" name="virtuemart_userinfo_id" value="<?php echo $userinfo['virtuemart_userinfo_id']?$userinfo['virtuemart_userinfo_id']:'' ?>" />
				</div>

				<?php // captcha addition
				if(VmConfig::get ('reg_captcha')){
					JHTML::_('behavior.framework');
					JPluginHelper::importPlugin('captcha');
					$dispatcher = JDispatcher::getInstance(); $dispatcher->trigger('onInit','dynamic_recaptcha_1');
					?>
					<div id="dynamic_recaptcha_1"></div>
				<?php
				}
				// end of captcha addition
				?>
				<input type="hidden" name="option" value="com_virtuemart" />
				<input type="hidden" name="controller" value="user" />

				<?php echo JHtml::_( 'form.token' ); ?>
			</form>
        </div>
    </div>
</div>
<!-- Modal -->
<div id="modal-change-password" class="modal fade" role="dialog">
    <div class="vertical-alignment-helper">
        <div class="modal-dialog vertical-align-center">
            <!-- Modal content-->
            <div class="modal-content">
                <button type="button" class="close" data-dismiss="modal"></button>
                <div class="modal-body">
                    <div class="te">Loading...</div>
                </div>
            </div>
        </div>
    </div>
</div><!--modal-->
<?php }//if !guest
else {
	$app = JFactory::getApplication();
	$app->redirect(JRoute::_('index.php?option=com_users&view=login&Itemid=123&redirect=editaddresscart', false), 'Please login first', 'message');
} ?>
