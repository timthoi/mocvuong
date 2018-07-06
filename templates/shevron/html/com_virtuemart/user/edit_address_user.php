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
        <li><a href="index.php?option=com_virtuemart&view=user&layout=edit">Personal Information</a></li>
        <li class="active"><a href="index.php?option=com_virtuemart&view=user&layout=edit_address_user">Your Address</a></li>
        <li><a href="index.php?option=com_virtuemart&view=user&layout=list_order_user">My Order</a></li>
    </ul>
    </div>
    <div id="my-tab-content" class="tab-content">
        <div id="your-address">
            <?php
            $fields = $this->userFields['fields'];
            
            $query = 'SELECT `virtuemart_country_id` AS value, `country_name` AS text FROM `#__virtuemart_countries`
                                WHERE `published` = 1 ORDER BY `country_name` ASC ';
            $db = JFactory::getDBO();
            $db->setQuery($query);
            $countries = $db->loadObjectList();
            ?>
            
            <form method="post" id="adminForm" name="userForm personal_info_form" action="<?php echo JRoute::_('index.php?option=com_virtuemart&view=user',$this->useXHTML,$this->useSSL) ?>" class="form-validate">
                <h2>Billing Address</h2>
                <div class="row">
                    <?php
                    $bt_address = array();
                    if($this->userDetails->JUser->get('id')) {
                        $query = 'SELECT * FROM `#__virtuemart_userinfos`
                                    WHERE `address_type` = "BT" AND `virtuemart_user_id` = '.$this->userDetails->JUser->get ('id');
                        $db = JFactory::getDBO();
                        $db->setQuery($query);
                        $bt_address = $db->loadAssoc();
                        $email = $this->userDetails->JUser->get('email');
                    }
                    ?>
                    <div class="col-sm-6">
                        <input type="text" id="first_name_field" name="first_name" size="30" placeholder="First Name*" value="<?php echo $bt_address['first_name'] ?>" class="required" maxlength="32" />
                    </div>
                    <div class="col-sm-6">
                        <input type="text" id="last_name_field" name="last_name" size="30" placeholder="Last Name*" value="<?php echo $bt_address['last_name'] ?>" class="required" maxlength="32" />
                    </div>
                    <div class="col-sm-12">
                        <textarea style="overflow:auto;resize:none" id="address_1_field" name="address_1" placeholder="Address*" class="required"><?php echo $bt_address['address_1'] ?></textarea>
                    </div>
                    <div class="col-sm-6">
                        <input type="text" id="zip_field" name="zip" size="30" placeholder="Postal code/Zip code*" value="<?php echo $bt_address['zip'] ?>" class="required" maxlength="32" />
                    </div>
                    <div class="col-sm-6">
                        <input type="text" id="city_field" name="city" size="30" placeholder="City*" value="<?php echo $bt_address['city'] ?>" class="required" maxlength="32" />
                    </div>
                    <div class="col-sm-6">
                        <div class="select-style">
                            <select id="virtuemart_country_id" name="virtuemart_country_id" class="required">
                                <option value="">Country</option>
                                <?php foreach ($countries as $country) { ?>
                                <option value="<?php echo $country->value ?>" <?=($country->value == $bt_address['virtuemart_country_id']) ? 'selected' : '' ?> ><?php echo $country->text ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <input type="text" id="phone_1_field" name="phone_1" size="30" value="<?php echo $bt_address['phone_1'] ?>" placeholder="Contact Number*" maxlength="32" />
                        <input type="hidden" name="virtuemart_userinfo_id" value="<?php echo $bt_address['virtuemart_userinfo_id'] ?>"/>
                    </div>
                </div><!--row-->
                
                <?php
                if ($this->userDetails->JUser->get ('id')): ?>
                <h2 style="margin-top: 30px">Shipping Address</h2>
                
                <?php
                $st_address = array();
                $this->virtuemart_userinfo_id = (int)JRequest::getVar('virtuemart_userinfo_id');
                if(!empty($this->virtuemart_userinfo_id)) {
                    $query = 'SELECT * FROM `#__virtuemart_userinfos`
                                WHERE `address_type` = "ST" AND `virtuemart_user_id` = '.$this->userDetails->JUser->get ('id').' AND `virtuemart_userinfo_id` = '.(int)$this->virtuemart_userinfo_id;
                    $db = JFactory::getDBO();
                    $db->setQuery($query);
                    $st_address = $db->loadAssoc();
                }
                ?>

                    <?php if(empty($this->virtuemart_userinfo_id)){ ?>
                    
                    <?php } ?>
                    <?php 
                        foreach($this->userDetails->userInfo as $st_address) {
                                if($st_address->address_type == 'ST') {
                    ?>
                    <div style="" class="row" id="shipto-form">
                        <div class="col-sm-6">
                            <input type="text" id="shipto_address_type_name_field" name="shipto_address_type_name[<?php echo $st_address->virtuemart_userinfo_id?>]" size="30" value="<?php echo $st_address->address_type_name ?>" placeholder="Address Title*" class="required" maxlength="32" />
                        </div>
                        <div class="col-sm-6 col-sm-offset-6"></div>
                        <div class="col-sm-6">
                            <input type="text" id="shipto_first_name_field" name="shipto_first_name[<?php echo $st_address->virtuemart_userinfo_id?>]" size="30" placeholder="First Name*" value="<?php echo $st_address->first_name ?>" class="required" maxlength="32" />
                        </div>
                        <div class="col-sm-6">
                            <input type="text" id="shipto_last_name_field" name="shipto_last_name[<?php echo $st_address->virtuemart_userinfo_id?>]" size="30" placeholder="Last Name*" value="<?php echo $st_address->last_name ?>" class="required" maxlength="32" />
                        </div>
                        <div class="col-sm-12">
                            <textarea style="overflow:auto;resize:none" id="shipto_address_1_field" name="shipto_address_1[<?php echo $st_address->virtuemart_userinfo_id?>]" placeholder="Address*" class="required"><?php echo $st_address->address_1 ?></textarea>
                        </div>
                        <div class="col-sm-6">
                            <input type="text" id="shipto_zip_field" name="shipto_zip[<?php echo $st_address->virtuemart_userinfo_id?>]" size="30" placeholder="Postal code/Zip code*" value="<?php echo $st_address->zip ?>" class="required" maxlength="32" />
                        </div>
                        <div class="col-sm-6">
                            <input type="text" id="shipto_city_field" name="shipto_city[<?php echo $st_address->virtuemart_userinfo_id?>]" size="30" placeholder="City*" value="<?php echo $st_address->city ?>" class="required" maxlength="32" />
                        </div>
                        <div class="col-sm-6">
                            <div class="select-style">
                                <select id="shipto_virtuemart_country_id" name="shipto_virtuemart_country_id[<?php echo $st_address->virtuemart_userinfo_id?>]" class="required">
                                    <option value="">Country</option>
                                    <?php foreach ($countries as $country) { ?>
                                    <option value="<?php echo $country->value ?>" <?=($country->value == $st_address->virtuemart_country_id) ? 'selected' : '' ?>><?php echo $country->text ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <input type="text" id="shipto_phone_1_field" name="shipto_phone_1[<?php echo $st_address->virtuemart_userinfo_id?>]" size="30" value="<?php echo $st_address->phone_1 ?>" placeholder="Contact Number*" maxlength="32" />
                        </div>
                        <input type="hidden" name="shipto_virtuemart_userinfo_id[]" value="<?php echo $st_address->virtuemart_userinfo_id?>" />
                        <input type="hidden" name="address_type[<?php echo $st_address->virtuemart_userinfo_id?>]" value="<?php echo $st_address->address_type; ?>"/>
                        <!-- <div class="col-sm-12">
                            <div class="default-address">
                                <input name="default_address" type="checkbox" value="1" id="default_address">
                                <label for="default_address">Default Shipping Address</label>
                            </div>
                        </div> -->
                        
                    </div>
                    <?php } } ?>
                    <div class="row">
                    <div class="col-sm-12">
                        <a class="add_shipping_address" href="#add_shipping_address"><i class="fa fa-plus"></i> Add Shipping Address</a>
                    </div>
                    <div style="" id="new-shipto-form">
                        <div class="col-sm-12">
                            <p class="cancel"><i class="fa fa-times"></i> Cancel</p>
                        </div>
                        <div class="col-sm-6">
                            <input type="text" id="shipto_address_type_name_field" name="shipto_address_type_name[0]" size="30" value="" placeholder="Address Title*" class="required" maxlength="32" />
                        </div>
                        <div class="col-sm-6 col-sm-offset-6"></div>
                        <div class="col-sm-6">
                            <input type="text" id="shipto_first_name_field" name="shipto_first_name[0]" size="30" placeholder="First Name*" value="" class="required" maxlength="32" />
                        </div>
                        <div class="col-sm-6">
                            <input type="text" id="shipto_last_name_field" name="shipto_last_name[0]" size="30" placeholder="Last Name*" value="" class="required" maxlength="32" />
                        </div>
                        <div class="col-sm-12">
                            <textarea style="overflow:auto;resize:none" id="shipto_address_1_field" name="shipto_address_1[0]" placeholder="Address*" class="required"></textarea>
                        </div>
                        <div class="col-sm-6">
                            <input type="text" id="shipto_zip_field" name="shipto_zip[0]" size="30" placeholder="Postal code/Zip code*" value="" class="required" maxlength="32" />
                        </div>
                        <div class="col-sm-6">
                            <input type="text" id="shipto_city_field" name="shipto_city[0]" size="30" placeholder="City*" value="" class="required" maxlength="32" />
                        </div>
                        <div class="col-sm-6">
                            <div class="select-style">
                                <select id="shipto_virtuemart_country_id" name="shipto_virtuemart_country_id[0]" class="required">
                                    <option value="">Country</option>
                                    <?php foreach ($countries as $country) { ?>
                                    <option value="<?php echo $country->value ?>"><?php echo $country->text ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <input type="text" id="shipto_phone_1_field" name="shipto_phone_1[0]" size="30" value="" placeholder="Contact Number*" maxlength="32" />
                        </div>
                        <input type="hidden" name="shipto_virtuemart_userinfo_id[]" value="0" />
                        <input type="hidden" name="address_type[0]" value="ST"/>
                        <!-- <div class="col-sm-12">
                            <div class="default-address">
                                <input name="default_address" type="checkbox" value="1" id="default_address">
                                <label for="default_address">Default Shipping Address</label>
                            </div>
                        </div> -->
                    </div>
                    </div>
                <?php
                endif; ?>
			    <div class="buttonBar-right">
                    <?php if(!empty($this->virtuemart_userinfo_id)){
                            echo '<input type="hidden" name="shipto_virtuemart_userinfo_id[]" value="'.(int)$this->virtuemart_userinfo_id.'" />';
                        }
                    ?>
                    <input type="hidden" name="task" value="saveUserAddress" />
                    <input type="hidden" name="option" value="com_virtuemart" />
                    <input type="hidden" name="controller" value="user" />
                    <?php echo JHtml::_( 'form.token' ); ?>
					<input class="button button-2" type="submit" name = "btn-save-info" value="Save changes" onclick="javascript:return myValidator(userForm, true);" />
				</div>
            </form>
        </div>
    </div>
</div>
<script>
	jQuery(function($){
        $('#new-shipto-form').css('display', 'none');
        $('#new-shipto-form').find('input').attr('disabled','disabled');
        $('#new-shipto-form').find('textarea').attr('disabled','disabled');
        $('#new-shipto-form').find('select').attr('disabled','disabled');
		$('a.add_shipping_address').on('click', function(){
			$('#new-shipto-form').css('display', 'block');
            $('#new-shipto-form').find('input').removeAttr('disabled');
            $('#new-shipto-form').find('textarea').removeAttr('disabled');
            $('#new-shipto-form').find('select').removeAttr('disabled');
			$(this).hide();
		});
		$('.cancel').on('click', function(){
			$('#new-shipto-form').css('display', 'none');
            $('#new-shipto-form').find('input').attr('disabled','disabled');
            $('#new-shipto-form').find('textarea').attr('disabled','disabled');
            $('#new-shipto-form').find('select').attr('disabled','disabled');
			$('a.add_shipping_address').show();
		});
        
        // Javascript to enable link to tab
        var url = document.location.toString();
        if (url.match('#')) {
            $('.nav-tabs a[href=#'+url.split('#')[1]+']').tab('show') ;
        } 
        
        // Change hash for page-reload
        $('.nav-tabs a').on('shown.bs.tab', function (e) {
            window.location.hash = e.target.hash;
        })
	});
</script>
