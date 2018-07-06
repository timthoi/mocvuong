<?php // no direct access
defined('_JEXEC') or die('Restricted access');
$tt_item=0;
$i = 0;
if($user->guest) {
   if (!isset($_COOKIE['virtuemart_wish_session'])) {
		$session = JFactory::getSession();
		setcookie('virtuemart_wish_session',$session->getId(),2592000 + time(),'/');
		$_COOKIE['virtuemart_wish_session'] = $session->getId();
			}
		$user_id = $_COOKIE['virtuemart_wish_session'];
}
else $user_id = $user->id;
$fav_products = mod_virtuemart_wishlist_products::getfavorites($user_id,$num_favorites);
if($guest_enabled || !$user->guest)
{
	//if (count($fav_products) == 0) { echo JText::_('VM_FAVORITE_NOFAV');}
	//else 
	{
	?>
	 <?php $count = count($fav_products); ?>
	
	 <a class="wishlist" href="<?php echo JRoute::_("index.php?option=com_wishlist&view=favoriteslist"); ?>"> 
		<i class="fa fa-star-o "></i>
		<div id="my-2" class="counter-2"><?php echo $count;?></div>
	</a>
	 <?php if($share_enabled && !$user->guest) { ?>
		 <br />
		 <a href="<?php echo JRoute::_("index.php?option=com_wishlist&view=favoritessh"); ?>"> <?php echo JText::_('VM_SHARE_FAVORITES') ?></a>
	<?php 
	}}
}
else
{
	$redirectUrl = JURI::current();
	$redirectUrl = urlencode(base64_encode($redirectUrl));
	$redirectUrl = '&return='.$redirectUrl;
	$joomlaLoginUrl = 'index.php?option=com_users&view=login';
    $finalUrl = $joomlaLoginUrl . $redirectUrl;
	$addtofavorites = '<a href="'.$finalUrl.'" alt="Login" title="Login">'.JText::_('VM_FAVORITE_LOGIN').'</a>';
	echo $addtofavorites;
}
?>
