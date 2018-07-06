<?php
defined('_JEXEC') or die('');

/**
 * Renders the email for the shoppers from the waiting list, or who bought this product
 * @package	VirtueMart
 * @subpackage product details
 * @author Seyi
 * @author Valérie Isaksen
 * @link http://www.virtuemart.net
 * @copyright Copyright (c) 2004 - 2012 VirtueMart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * VirtueMart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * @version $Id: mail_raw_notify.php 8508 2014-10-22 18:57:14Z Milbo $
 */

echo 'Dear Customer,';
echo "<br>";
echo "<br>";
echo 'Good News!';
echo "<br>";
echo "<br>";
echo 'Due to popular demand, we are pleased to announce that the product '.$this->productName.' you have viewed previously is back in stock and available for ordering again.';
echo "<br>";
echo "<br>";
echo "<br>";
echo 'The product is available to be ordered from '.$this->link;
echo "<br>";
echo "<br>";
echo 'Do take your time and browse through with our latest collection, you might discover something you adore too!';
echo "<br>";
echo "<br>";
echo 'Thank you for shopping with Shevron and have a nice day!';

echo "<br>";
echo "<br>";
echo "<br>";
echo "<br>";
echo 'Regards,';
echo "<br>";
echo 'Shevron';
