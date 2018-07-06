<?php
/**
*
* My Address page
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

<div class=" my-account">
	<h2 class="shop-heading"><?php echo $this->page_title ?></h2>
</div>

<div id="content" class="profile-tab">
	<div class="border-one">
    <ul id="tabs" class="nav nav-tabs" data-tabs="tabs">
        <li><a href="index.php?option=com_virtuemart&view=user&layout=edit">Personal Information</a></li>
        <li class="active"><a href="index.php?option=com_virtuemart&view=user&layout=list_address_user">Your Address</a></li>
        <li><a href="index.php?option=com_virtuemart&view=user&layout=list_order_user">My Order</a></li>
    </ul>
    </div>
    <div id="my-tab-content" class="tab-content">
        <div class="" id="your-address">
            <?php
                if(!empty($this->userDetails->userInfo)) {
                ?>
        	<a class="button button-2 btn-edit-address" href="<?php echo JRoute::_('index.php?option=com_virtuemart&view=user&layout=edit_address_user') ?>" >Edit Your Address</a>
            <?php } else { ?>
            <a class="button button-2 btn-edit-address" href="<?php echo JRoute::_('index.php?option=com_virtuemart&view=user&layout=edit_address_user') ?>" >Add New Address</a>
            <?php } ?>
            <h2>Billing Address</h2>
           
                <?php 
                if(!empty($this->userDetails->userInfo)) {
                	foreach($this->userDetails->userInfo as $address) { 
                    if($address->address_type == 'BT') { 
                    	$country = getCountryByID($address->virtuemart_country_id);
                    ?>
                     <div class="profile-address">
                        <div class="billing-address">
                            <div class="row">
	                            <div class="col-sm-3">
	                            	<p>First Name</p>
	                            </div>
	                            <div class="col-sm-9">
	                            	<p><?php echo $address->first_name; ?></p>
	                            </div>
                            </div>
                            <div class="row">
	                            <div class="col-sm-3">
	                            	<p>Last Name</p>
	                            </div>
	                            <div class="col-sm-9">
	                            	<p><?php echo $address->last_name; ?></p>
	                            </div>
                            </div>
                            <div class="row">
	                            <div class="col-sm-3">
	                            	<p>Address</p>
	                            </div>
	                            <div class="col-sm-9">
	                            	<p><?php echo $address->address_1; ?></p>
	                            </div>
                            </div>
                            <div class="row">
	                            <div class="col-sm-3">
	                            	<p>Postal code / Zip code</p>
	                            </div>
	                            <div class="col-sm-9">
	                            	<p><?php echo $address->zip; ?></p>
	                            </div>
                            </div>
                            <div class="row">
	                            <div class="col-sm-3">
	                            	<p>City</p>
	                            </div>
	                            <div class="col-sm-9">
	                            	<p><?php echo $address->city; ?></p>
	                            </div>
                            </div>
                            <div class="row">
	                            <div class="col-sm-3">
	                            	<p>Country</p>
	                            </div>
	                            <div class="col-sm-9">
	                            	<p><?php echo $country; ?></p>
	                            </div>
                            </div>
                            <div class="row">
	                            <div class="col-sm-3">
	                            	<p>Contact Number</p>
	                            </div>
	                            <div class="col-sm-9">
	                            	<p><?php echo $address->phone_1; ?></p>
	                            </div>
                            </div>
                        </div>
                    </div>
                    <?php 
                    }//if
                }//foreach
                } else { ?>
               	<div class="profile-address">
                	<div class="billing-address">
	                    Please add a billing address
	                </div>
	            </div>
                <?php } ?>
                <h2>Shipping Address</h2>
                <?php 
                if(!empty($this->userDetails->userInfo)) {
                	foreach($this->userDetails->userInfo as $address) { 
                    if($address->address_type == 'ST') { 
                    	$country = getCountryByID($address->virtuemart_country_id);
                    ?>
                    <div class="profile-address">
                        <div class="shipping-address">
                            <div class="row">
	                            <div class="col-sm-3">
	                            	<p>First Name</p>
	                            </div>
	                            <div class="col-sm-9">
	                            	<p><?php echo $address->first_name; ?></p>
	                            </div>
                            </div>
                            <div class="row">
	                            <div class="col-sm-3">
	                            	<p>Last Name</p>
	                            </div>
	                            <div class="col-sm-9">
	                            	<p><?php echo $address->last_name; ?></p>
	                            </div>
                            </div>
                            <div class="row">
	                            <div class="col-sm-3">
	                            	<p>Address</p>
	                            </div>
	                            <div class="col-sm-9">
	                            	<p><?php echo $address->address_1; ?></p>
	                            </div>
                            </div>
                            <div class="row">
	                            <div class="col-sm-3">
	                            	<p>Postal code / Zip code</p>
	                            </div>
	                            <div class="col-sm-9">
	                            	<p><?php echo $address->zip; ?></p>
	                            </div>
                            </div>
                            <div class="row">
	                            <div class="col-sm-3">
	                            	<p>City</p>
	                            </div>
	                            <div class="col-sm-9">
	                            	<p><?php echo $address->city; ?></p>
	                            </div>
                            </div>
                            <div class="row">
	                            <div class="col-sm-3">
	                            	<p>Country</p>
	                            </div>
	                            <div class="col-sm-9">
	                            	<p><?php echo $country; ?></p>
	                            </div>
                            </div>
                            <div class="row">
	                            <div class="col-sm-3">
	                            	<p>Contact Number</p>
	                            </div>
	                            <div class="col-sm-9">
	                            	<p><?php echo $address->phone_1; ?></p>
	                            </div>
                            </div>
                        </div>
                        <a class="delete-address" href="#" data-addrtype="<?php echo $address->address_type ?>" data-id="<?php echo $address->virtuemart_userinfo_id  ?>" data-toggle="modal" data-target="#modal-confirm-del-address" ></a>
                    </div>
                    <?php 
                    }//if
                }//foreach
                } else { ?>
                <div class="profile-address">
                <div class="shipping-address">
                    Please add a shipping address
                </div>
                </div>
                <?php }
            ?>
        </div>
    </div>
</div>
<div id="modal-confirm-del-address" tabindex="-1" class="modal fade" role="dialog">
    <div class="vertical-alignment-helper">
        <div class="modal-dialog vertical-align-center" role="document">
            <!-- Modal content-->
            <div class="modal-content">
                <button type="button" class="close" data-dismiss="modal"></button>
                <div class="modal-body">
                    <h4>DELETE CONFIRMATION</h2>
                    <p>This action cannot be undone</p>
                    <form action="index.php" method="post">
                        <input type="hidden" name="option" value="com_virtuemart"/>
                        <input type="hidden" name="view" value="user"/>
                        <input type="hidden" name="task" value="removeAddressST"/>
                        <input type="hidden" id="addrtype" name="addrtype" value=""/>
                        <input type="hidden" id="virtuemart_userinfo_id" name="virtuemart_userinfo_id[]" value=""/>
                        <input type="hidden" name="virtuemart_user_id" value="<?=$address->virtuemart_user_id ?>"/>
                        <button data-dismiss="modal" style="border: 2px solid #A7A7A7; color: #A7A7A7;" type="submit" class="delete-address button-2">Cancel</button>
                        <button type="submit" class="delete-address button-2">Yes, delete it!</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div><!--modal-->
