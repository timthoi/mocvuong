<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_users
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
header('Content-Type: application/json');

$user = JFactory::getUser();

$app = JFactory::getApplication();
$postData = $app->input->getArray(array());

if(isset($postData['old_password'])){
	$result = JUserHelper::verifyPassword($postData['old_password'], $user->password, $user->id);
	if($result == 1) 
		return json_encode(['success' => true]);
	return json_encode(['success' => false]);
}

return json_encode(['success' => false]);