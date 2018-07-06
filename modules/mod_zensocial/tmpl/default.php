<?php
/**
* @package Author
* @author Joomla Bamboo
* @website www.joomlabamboo.com
* @email design@joomlabamboo.com
* @copyright Copyright (c) 2013 Joomla Bamboo. All rights reserved.
* @license GNU General Public License version 2 or later
**/

// no direct access
defined('_JEXEC') or die('Restricted access');

if ($socialfonticons == 0) {
?>
<div id="socialicons" class="<?php echo $socialalign.' mid'.$id; if($moduleclass_sfx) {echo $moduleclass_sfx;} ?>">
		<?php if ($socialiconstitle !="") {?>
			<h3><span><?php echo $socialiconstitle ?></span></h3>
		<?php } ?>
		<ul>
		<!-- Social Icons -->
		<?php if ($icon1Image !="-1") {?>
		<li><a class="icon1" target="_blank" href="<?php echo $icon1Link ?>">
			<img src="modules/mod_zensocial/icons/<?php echo $icon1Image ?>"  title="<?php echo $icon1Image ?>" alt="<?php echo $icon1Image ?>"/>
		</a></li>
		<?php } ?>

		<?php if ($icon2Image !="-1") {?>
		<li><a class="icon2" target="_blank" href="<?php echo $icon2Link ?>">
				<img src="modules/mod_zensocial/icons/<?php echo $icon2Image ?>" title="<?php echo $icon2Image ?>" alt="<?php echo $icon2Image ?>"/>
		</a></li>
		<?php } ?>

		<?php if ($icon3Image !="-1") {?>
		<li><a class="icon3" target="_blank" href="<?php echo $icon3Link ?>">
				<img src="modules/mod_zensocial/icons/<?php echo $icon3Image ?>" title="<?php echo $icon3Image ?>"  alt="<?php echo $icon3Image ?>"/>
		</a></li>
		<?php } ?>

		<?php if ($icon4Image !="-1") {?>
		<li><a class="icon4" target="_blank" href="<?php echo $icon4Link ?>">
				<img src="modules/mod_zensocial/icons/<?php echo $icon4Image ?>" title="<?php echo $icon4Image ?>"  alt="<?php echo $icon4Image ?>"/>
		</a></li>
		<?php } ?>

		<?php if ($icon5Image !="-1") {?>
		<li><a class="icon5" target="_blank" href="<?php echo $icon5Link ?>">
				<img src="modules/mod_zensocial/icons/<?php echo $icon5Image ?>" title="<?php echo $icon5Image ?>"  alt="<?php echo $icon5Image ?>"/>
		</a></li>
		<?php } ?>

		<?php if ($icon6Image !="-1") {?>
		<li><a class="icon6" target="_blank" href="<?php echo $icon6Link ?>">
				<img src="modules/mod_zensocial/icons/<?php echo $icon6Image ?>" title="<?php echo $icon6Image ?>"  alt="<?php echo $icon6Image ?>"/>
		</a></li>
		<?php } ?>
		</ul>
	</div>
<?php } else { ?>
<div id="socialicons" class="<?php echo $socialalign.' mid'.$id; if($moduleclass_sfx) {echo $moduleclass_sfx;} ?>">
		<?php if ($socialiconstitle !="") {?>
			<h3><span><?php echo $socialiconstitle ?></span></h3>
		<?php } ?>
		<ul>
		<!-- Social Icons -->
		<?php if ($icon1Font[0] !="-1") {?>
		<li>
			<a class="icon1" target="_blank" href="<?php echo $icon1FontLink ?>">
				<span class="<?php echo $icon1Font[0]; ?>"></span>
			</a>
		</li>
		<?php } ?>

		<?php if ($icon2Font[0] !="-1") {?>
		<li>
			<a class="icon2" target="_blank" href="<?php echo $icon2FontLink ?>">
				<span class="<?php echo $icon2Font[0]; ?>"></span>
			</a>
		</li>
		<?php } ?>

		<?php if ($icon3Font[0] !="-1") {?>
		<li>
			<a class="icon3" target="_blank" href="<?php echo $icon3FontLink ?>">
				<span class="<?php echo $icon3Font[0]; ?>"></span>
			</a>
		</li>
		<?php } ?>

		<?php if ($icon4Font[0] !="-1") {?>
		<li>
			<a class="icon4" target="_blank" href="<?php echo $icon4FontLink ?>">
				<span class="<?php echo $icon4Font[0]; ?>"></span>
			</a>
		</li>
		<?php } ?>

		<?php if ($icon5Font[0] !="-1") {?>
		<li>
			<a class="icon5" target="_blank" href="<?php echo $icon5FontLink ?>">
				<span class="<?php echo $icon5Font[0]; ?>"></span>
			</a>
		</li>
		<?php } ?>

		<?php if ($icon6Font[0] !="-1") {?>
		<li>
			<a class="icon6" target="_blank" href="<?php echo $icon6FontLink ?>">
				<span class="<?php echo $icon6Font[0]; ?>"></span>
			</a>
		</li>
		<?php } ?>
		</ul>
	</div>
	<?php }