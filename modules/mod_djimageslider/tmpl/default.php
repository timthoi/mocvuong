<?php
/**
 * @version $Id: default.php 23 2015-05-11 11:23:25Z szymon $
 * @package DJ-ImageSlider
 * @subpackage DJ-ImageSlider Component
 * @copyright Copyright (C) 2012 DJ-Extensions.com, All rights reserved.
 * @license http://www.gnu.org/licenses GNU/GPL
 * @author url: http://dj-extensions.com
 * @author email contact@dj-extensions.com
 * @developer Szymon Woronowski - szymon.woronowski@design-joomla.eu
 *
 *
 * DJ-ImageSlider is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * DJ-ImageSlider is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with DJ-ImageSlider. If not, see <http://www.gnu.org/licenses/>.
 *
 */

// no direct access
defined('_JEXEC') or die ('Restricted access'); ?>
<div style="border: 0px !important;">
<div id="djslider-loader<?php echo $mid; ?>" class="djslider-loader djslider-loader-<?php echo $theme ?>" data-animation='<?php echo $animationOptions ?>' data-djslider='<?php echo $moduleSettings ?>'>
    <div id="djslider<?php echo $mid; ?>" class="djslider djslider-<?php echo $theme; echo $params->get('image_centering', 0) ? ' img-vcenter':'' ?>" style="<?php echo $style['slider'] ?>">
        <div id="slider-container<?php echo $mid; ?>" class="slider-container">
        	<ul id="slider<?php echo $mid; ?>" class="djslider-in">
          		<?php foreach ($slides as $slide) { ?>
          			<li style="<?php echo $style['slide'] ?>">
          				<?php if($slide->image) { 
          					$action = $params->get('link_image',1);
          					$attr = '';
          					if($action > 1) {
	          					if($jquery) {
	          						$attr = 'class="image-link"';
	          					} else {
	          						$attr = 'rel="lightbox-slider'.$mid.'"';
	          					}
	          					if($params->get('show_desc')) $attr.= ' title="'.(!empty($slide->title) ? htmlspecialchars($slide->title.' ') : '').htmlspecialchars('<small>'.strip_tags($slide->description,"<p><a><b><strong><em><i><u>").'</small>').'"';
							}				
          					?>
	            			<?php if (($slide->link && $action==1) || $action>1) { ?>
								<a <?php echo $attr; ?> href="<?php echo ($action>1 ? $slide->image : $slide->link); ?>" target="<?php echo $slide->target; ?>">
							<?php } ?>
								<img class="dj-image" src="<?php echo $slide->image; ?>" alt="<?php echo $slide->alt; ?>" style="<?php echo $style['image'] ?>"/>
							<?php if (($slide->link && $action==1) || $action>1) { ?>
								</a>
							<?php } ?>
						<?php } ?>
						<?php if($params->get('slider_source') && ($params->get('show_title') || ($params->get('show_desc') && !empty($slide->description) || ($params->get('show_readmore') && $slide->link)))) { ?>
						<!-- Slide description area: START -->
						<div class="slide-desc" style="<?php echo $style['desc'] ?>">
						  <div class="slide-desc-in">	
							<div class="slide-desc-bg slide-desc-bg-<?php echo $theme ?>"></div>
							<div class="slide-desc-text slide-desc-text-<?php echo $theme ?>">
							<?php if($params->get('show_title')) { ?>
								<div class="slide-title">
									<?php if($params->get('link_title') && $slide->link) { ?><a href="<?php echo $slide->link; ?>" target="<?php echo $slide->target; ?>"><?php } ?>
										<?php echo $slide->title; ?>
									<?php if($params->get('link_title') && $slide->link) { ?></a><?php } ?>
								</div>
							<?php } ?>
							
							<?php if($params->get('show_desc')) { ?>
								<div class="slide-text">
									<?php if($params->get('link_desc') && $slide->link) { ?>
									<a href="<?php echo $slide->link; ?>" target="<?php echo $slide->target; ?>">
										<?php echo strip_tags($slide->description,"<br><span><em><i><b><strong><small><big>"); ?>
									</a>
									<?php } else { ?>
										<?php echo $slide->description; ?>
									<?php } ?>
								</div>
							<?php } ?>
							
							<?php if($params->get('show_readmore') && $slide->link) { ?>
								<a href="<?php echo $slide->link; ?>" target="<?php echo $slide->target; ?>" class="readmore"><?php echo ($params->get('readmore_text',0) ? $params->get('readmore_text') : JText::_('MOD_DJIMAGESLIDER_READMORE')); ?></a>
							<?php } ?>
							<div style="clear: both"></div>
							</div>
						  </div>
						</div>
						<!-- Slide description area: END -->
						<?php } ?>						
						
					</li>
                <?php } ?>
        	</ul>
        </div>
        <?php if($show->arr || $show->btn) { ?>
        <div id="navigation<?php echo $mid; ?>" class="navigation-container" style="<?php echo $style['navi'] ?>">
        	<?php if($show->arr) { ?>
        	<img id="prev<?php echo $mid; ?>" class="prev-button <?php echo $show->arr==1 ? 'showOnHover':'' ?>" src="images/img-btn-prev.png" alt="<?php echo $direction == 'rtl' ? JText::_('MOD_DJIMAGESLIDER_NEXT') : JText::_('MOD_DJIMAGESLIDER_PREVIOUS'); ?>" />
			<img id="next<?php echo $mid; ?>" class="next-button <?php echo $show->arr==1 ? 'showOnHover':'' ?>" src="images/img-btn-next.png" alt="<?php echo $direction == 'rtl' ? JText::_('MOD_DJIMAGESLIDER_PREVIOUS') : JText::_('MOD_DJIMAGESLIDER_NEXT'); ?>" />
			<?php } ?>
			<?php if($show->btn) { ?>
			<img id="play<?php echo $mid; ?>" class="play-button <?php echo $show->btn==1 ? 'showOnHover':'' ?>" src="<?php echo $navigation->play; ?>" alt="<?php echo JText::_('MOD_DJIMAGESLIDER_PLAY'); ?>" />
			<img id="pause<?php echo $mid; ?>" class="pause-button <?php echo $show->btn==1 ? 'showOnHover':'' ?>" src="<?php echo $navigation->pause; ?>" alt="<?php echo JText::_('MOD_DJIMAGESLIDER_PAUSE'); ?>" />
			<?php } ?>
        </div>
        <?php } ?>
        <?php if($show->idx) { ?>
		<div id="cust-navigation<?php echo $mid; ?>" class="<?php echo $params->get('idx_style', 0) ? 'navigation-numbers' : 'navigation-container-custom' ?> <?php echo $show->idx==2 ? 'showOnHover':'' ?>">
			<?php $i = 0; foreach ($slides as $slide) { 
				?><span class="load-button<?php if ($i == 0) echo ' load-button-active'; ?>"><?php if($params->get('idx_style')) echo ($i+1) ?></span><?php 
			$i++; } ?>
        </div>
        <?php } ?>
    </div>
</div>
</div>
<div style="clear: both"></div>