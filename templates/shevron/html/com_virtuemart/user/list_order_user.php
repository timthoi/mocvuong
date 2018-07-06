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
        <li><a href="index.php?option=com_virtuemart&view=user&layout=list_address_user">Your Address</a></li>
        <li class="active"><a href="index.php?option=com_virtuemart&view=user&layout=list_order_user">My Order</a></li>
    </ul>
    </div>
    <div id="my-tab-content" class="tab-content">
        <div id="my-order">
            <div class="my-order">
                    <input name="form_key" type="hidden" value="">
                    <div id="responsiveTable" class="table-responsive">
                        <table class="table table-bordered table-striped table-condensed data-table">
                            <thead>
                                <tr class="first last">
                                    <th>Order Number</th>
                                    <th>Date</th>
                                    <th>Shipping Address</th>
                                    <th>Amount</th>
                                    <th class="hidden-xs hidden-sm">Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                         <?php
                            $k = 0;
                            foreach ($this->orderlist as $row) {
                                $editlink = JRoute::_('index.php?option=com_virtuemart&view=orders&layout=details&order_number=' . $row->order_number, FALSE);
                                ?>
                                <tr class="<?php echo "row$k"; ?>">
                                    <td align="center">
                                        <strong><a href="<?php echo $editlink; ?>" rel="nofollow" class="txt_order_number"><?php echo $row->order_number; ?></a>
                                        <?php echo shopFunctionsF::getInvoiceDownloadButton($row) ?></strong>
                                    </td>
                                    <td align="center">
                                        <?php echo vmJsApi::date($row->created_on,'LC4',true); ?>
                                    </td>
                                    <!--td align="left">
                                        <?php //echo vmJsApi::date($row->modified_on,'LC3',true); ?>
                                    </td -->
                                    <td align="center">
                                        <?php
                                        $db = JFactory::getDBO ();
                                        $q = 'SELECT * FROM #__virtuemart_order_userinfos WHERE virtuemart_order_id ='.$row->virtuemart_order_id.' AND address_type = "ST"';
                                        $db->setQuery ($q);
                                        $st_address = $db->loadAssoc();
                                        echo $st_address['address_1'];
                                        ?>
                                    </td>
                                    <td align="center">
                                        <?php $currency = CurrencyDisplay::getInstance (); 
											if (!isset($row->currency)) $row->currency ="";
										?>
                                        <?php echo $currency->priceDisplay($row->order_total, $row->currency); ?>
                                    </td>
                                    <td class="hidden-xs hidden-sm" align="center">
                                        <?php 
                                        $db = JFactory::getDBO ();
                                        $q = 'SELECT * FROM #__virtuemart_orderstates WHERE order_status_code = "'.$row->order_status.'"';
                                        $db->setQuery ($q);
                                        $status = $db->loadAssoc();
                                        echo VmText::_($status['order_status_name']);
                                        ?>
                                    </td>
                                    <td>
                                        <a href="<?php echo $editlink; ?>" rel="nofollow" class="button button-2 btn_view_order">View Order</a>
                                    </td>
                                </tr>
                        <?php
                                $k = 1 - $k;
                            }
                        ?>
                            </tbody>
                        </table>
                    </div>
            </div>
        </div>
    </div>
</div>
<script>
	jQuery(function($){
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
