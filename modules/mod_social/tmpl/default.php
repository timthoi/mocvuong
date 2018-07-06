<?php
defined('_JEXEC') or die;

$facebook = $params->get('facebook','');
$twitter = $params->get('twitter','');
$linkedin = $params->get('linkedln','');
$youtube = $params->get('youtube','');
$instagram = $params->get('instagram','');
$pinterest = $params->get('pinterest','');
$carousell  = $params->get('carousell','');

?>
<div class="social-div">
	<?php if ($facebook!=""): ?>	
		<a href="<?php echo $facebook ?>" target="_blank"><i class="fa fa-facebook-square"></i></a>
	<?php endif; ?>	

	<?php if ($instagram!=""): ?>
		<a href="<?php echo $instagram ?>" target="_blank"><i class="fa fa-instagram"></i></a>
	<?php endif; ?>

	<?php if ($pinterest!=""): ?>
		<a href="<?php echo $pinterest ?>" target="_blank"><i class="fa fa-pinterest-square"></i></a>
	<?php endif; ?>

	<?php if ($linkedin!=""): ?>
		<a href="<?php echo $linkedin ?>" target="_blank"><i class="fa fa-linkedin-square"></i></a>
	<?php endif; ?>

	<?php if ($carousell !=""): ?>
		<a href="<?php echo $carousell ?>" target="_blank"><img src="images/caroulse.png"></a>
	<?php endif; ?>

	<?php if ($twitter!=""): ?>
		<a href="<?php echo $twitter ?>" target="_blank"><i class="fa fa-twitter-square"></i></a>
	<?php endif; ?>



	<?php if ($youtube!=""): ?>
		<a href="<?php echo $youtube ?>" target="_blank"><i class="fa fa-youtube-square"></i></a>
	<?php endif; ?>
</div>