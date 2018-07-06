<?php
defined ('_JEXEC') or die();

/**
 * @author Valérie Isaksen
 * @version $Id$
 * @package VirtueMart
 * @subpackage payment
 * @copyright Copyright (C) 2004-Copyright (C) 2004-2015 Virtuemart Team. All rights reserved.   - All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * VirtueMart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See /administrator/components/com_virtuemart/COPYRIGHT.php for copyright notices and details.
 *
 * http://virtuemart.net
 */
 
 $user = JFactory::getUser();

?>

<div class="order-done">
	<!--<h2>Order No. <?php echo  $viewData["order_number"]; ?></h2>-->
	
	
	<p class="message"><Strong>We are honoured that when it comes to buying gifts, you think of Shevron!</strong></p>
	
	<p class="message">We believe you will be very satisfied with the quality of our products. It is our superior quality that sets us apart from the competition. We hope that Shevron will continue to be the place you think of first, when you are looking for gifts that are truly special.</p>
	
	<p class="message">For Order and Shipping information, please click <a href="<?php echo JRoute::_("index.php?option=com_content&view=article&id=16&Itemid=210", false); ?>">here</a>. If you have other questions regarding your order, please quote your order number when you contact us on email <a href="mailto:info@shevron.com.sg" target="_top"><span class="email">info@shevron.com.sg</span></a> or telephone +65 6483 1726.</p>
	
	<p class="message">Yours sincerely,<br>Shevron</p>
	
	<!-- 
	<a class="btt-order-information" href="<?php echo JRoute::_('index.php?option=com_virtuemart&view=orders&layout=details&order_number='.$viewData["order_number"].'&order_pass='.$viewData["order_pass"], false)?>"><button class="button-2">View Order Information</button></a>
	<div class="share-social float-center">
		<p>Share <div class="addthis_inline_share_toolbox"></div></p>
	</div><a class="btt-order-information" href="<?php echo JRoute::_('index.php?option=com_virtuemart&view=orders&layout=details&order_number='.$viewData["order_number"].'&order_pass='.$viewData["order_pass"], false)?>">
	-->
</div>







