<?php

/**
 *
 * Show Confirmation message from Offlien Payment
 *
 * @package	VirtueMart
 * @subpackage
 * @author Valerie Isaksen
 * @link http://www.virtuemart.net
 * @copyright Copyright (c) 2004 - 2010 VirtueMart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * VirtueMart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * @version $Id: default.php 3217 2011-05-12 15:51:19Z alatak $
 */
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

?>
<div style="display:none">
<?php
echo "<h3>" . $this->paymentResponse . "</h3>";
if ($this->paymentResponseHtml) {
    echo "<fieldset>";
    echo $this->paymentResponseHtml;
    echo "</fieldset>";
}

$app = JFactory::getApplication();
$tmp = JRoute::_("index.php?option=com_content&view=article&id=20&Itemid=249", false);
$app->redirect($tmp);
?>
</div>


<div class="order-done">
<div class="page-header">
<h2 itemprop="name" class="shop-heading">Thank you for your Order!</h2>
</div>

<p class="message"><Strong>We are honoured that when it comes to buying gifts, you think of Shevron!</strong></p>

<p class="message">We believe you will be very satisfied with the quality of our products. It is our superior quality that sets us apart from the competition. We hope that Shevron will continue to be the place you think of first, when you are looking for gifts that are truly special.</p>

<p class="message">For Order and Shipping information, please click <a class="btt-order-information" href="<?php echo JRoute::_("index.php?option=com_content&view=article&id=16&Itemid=210", false); ?>">here</a>. If you have other questions regarding your order, please quote your order number when you contact us on email <a href="mailto:info@shevron.com.sg" target="_top"><span class="email">info@shevron.com.sg</span></a> or telephone +65 6483 1726.</p>

<p class="message">Yours sincerely,<br>Shevron</p>
	
	
</div>

