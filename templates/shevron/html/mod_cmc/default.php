<?php
/**
 * @package    CMC
 * @author     Compojoom <contact-us@compojoom.com>
 * @date       2016-04-15
 *
 * @copyright  Copyright (C) 2008 - 2016 compojoom.com - Daniel Dimitrov, Yves Hoppe. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die('Restricted access');

$moduleId = $module->id;

if ($params->get('jquery', 1))
{
	CompojoomHtmlBehavior::jquery();
}

JHtml::_('behavior.formvalidation');
JHtml::script('media/mod_cmc/js/cmc.js');
JHtml::_('stylesheet', 'media/mod_cmc/css/cmc.css');

if ($params->get('bootstrap_form', 1))
{
	JHtml::_('stylesheet', 'media/mod_cmc/css/bootstrap-form.css');
}

$document = JFactory::getDocument();
$script = 'jQuery(document).ready(function() {
    new cmc("#cmc-signup-' . $moduleId . '");
});';

$document->addScriptDeclaration($script);
?>

<div id="cmc-signup-<?php echo $moduleId; ?>"
     class="cmc-signup <?php echo $params->get('moduleclass_sfx', ''); ?>">

	<?php if($status->status == 'subscribed') : ?>
		<div class="alert alert-info">
			<?php echo JText::_('MOD_CMC_ALREADY_ON_THE_LIST'); ?>
		</div>

		<div class="cmc-margin-bottom">
		<button class="btn cmc-toggle-sub">
			<?php echo JText::_('MOD_CMC_CHANGE_SUB'); ?>
		</button>
		</div>

	<?php endif; ?>

	<div class="cmc-error alert alert-error" style="display:none"></div>
	<div class="cmc-saved alert alert-success" style="display:none">
		<?php echo JText::_($params->get('thankyou')); ?>
	</div>
	<div class="cmc-updated" style="display:none">
		<?php echo JText::_('MOD_CMC_SUBSCRIPTION_UPDATED'); ?>
	</div>
	<?php if($status->status == 'subscribed') : ?>
		<div class="cmc-existing hide">
	<?php endif; ?>
	<form action="<?php echo JRoute::_('index.php?option=com_cmc&format=raw&task=subscription.save'); ?>" method="post"
	      id="cmc-signup-form-<?php echo $moduleId; ?>"
	      class=""
	      name="cmc<?php echo $moduleId; ?>">


		<?php $fieldsets = $form->getFieldsets('cmc'); ?>

		<?php foreach ($fieldsets as $key => $value): ?>
				<?php $fields = $form->getFieldset($key); ?>

				<?php foreach ($fields as $field) : ?>
					<div class="control-group">
						<div class="controls">
							<?php echo $field->input; ?>
						</div>
					</div>
				<?php endforeach; ?>
		<?php endforeach; ?>


		<?php $fieldsets = $form->getFieldsets('cmc_groups'); ?>
		<?php foreach ($fieldsets as $key => $value) : ?>

				<?php $fields = $form->getFieldset($key); ?>

				<?php foreach ($fields as $field) : ?>
					<div class="control-group css<?php echo $field->fieldname?>">
						<div class="controls">
							<?php echo $field->input; ?>
							<?php if ($field->fieldname == 'EMAIL') : ?>
								<div class="help-inline alert alert-error cmc-exist hide">
									<?php echo JText::sprintf('MOD_CMC_YOU_ARE_ALREADY_SUBSCRIBED', ''); ?>
									<a href=""><?php echo JText::_('MOD_CMC_CLICK_HERE_TO_UPDATE'); ?></a>
								</div>
							<?php endif; ?>
						</div>
					</div>
				<?php endforeach; ?>

		<?php endforeach; ?>


		<?php $fieldsets = $form->getFieldsets('cmc_interests'); ?>
		<?php foreach ($fieldsets as $key => $value) : ?>

				<?php $fields = $form->getFieldset($key); ?>

				<?php foreach ($fields as $field) : ?>
					<dl class="dropdown interests"> 
						<dt>
                            <a>
                                <?php //echo $field->label; ?> 
                                <label id="jform_cmc_interests_c225bed90b-lbl" for="jform_cmc_interests_c225bed90b" class="form-label cmc-label">
	                               Select Your Interest
                                </label>
                                <p class="multiSel"></p>  
                            </a>
                        </dt>
						<dd>
                            <div class="mutliSelect">
                                <?php echo $field->input; ?>
                            </div>
						</dd>
					</dl>
				<?php endforeach; ?>
		<?php endforeach; ?>


		<input type="hidden" class="cmc_exist" name="<?php echo $form->getFormControl(); ?>[exists]" value="0" />

		<?php echo JHTML::_('form.token'); ?>

		<?php if ($params->get('outro-text-1')) : ?>
			<div id="outro1_<?php echo $moduleId; ?>" class="outro1">
				<p class="outro"><?php echo JText::_($params->get('outro-text-1')); ?></p>
			</div>
		<?php endif; ?>

		<button class="btn btn-primary validate" type="submit">
			<?php if($status->status == 'subscribed') : ?>
				<?php echo JText::_('MOD_CMC_UPDATE_SUB'); ?>
			<?php else : ?>
				<?php echo JText::_('MOD_CMC_SUBSCRIBE'); ?>
			<?php endif; ?>
			<img width="16" height="16" class="cmc-spinner" style="display: none;"
			     src="<?php echo JURI::root(); ?>media/mod_cmc/images/loading-bubbles.svg"/>
		</button>

		<?php if ($params->get('outro-text-2')) : ?>
			<div id="outro2_<?php echo $moduleId; ?>" class="outro2">
				<p class="outro"><?php echo JText::_($params->get('outro-text-2')); ?></p>
			</div>
		<?php endif; ?>
	</form>

	<?php /*if($status->status == 'subscribed') : ?>

		</div>
	<div class="cmc-margin-bottom text-center">
		<a class="btn" href="<?php echo JRoute::_('index.php?option=com_cmc&task=subscription.delete&listid=' . $params->get('listid') . '&' . JFactory::getSession()->getFormToken() . '=1'); ?>">
			<?php echo JText::_('MOD_CMC_UNSUBSCRIBE'); ?>
		</a>
		</div>

<?php endif; */?>
</div>

<script>
jQuery(".dropdown dt a").on('click', function() {
	jQuery(".dropdown dd ul").slideToggle('fast');
});

jQuery(".dropdown dd ul li a").on('click', function() {
	jQuery(".dropdown dd ul").hide();
});
jQuery(".interests").on('mouseleave', function() {
	jQuery(".dropdown dd ul").hide();
});

function getSelectedValue(id) {
	return jQuery("#" + id).find("dt a span.value").html();
}

jQuery(document).bind('click', function(e) {
	var $clicked = jQuery(e.target);
	if (!$clicked.parents().hasClass("dropdown")) 
		if ( typeof($(".dropdown dd ul")) != 'undefined' &&  $(".dropdown dd ul") != null )  
			$(".dropdown dd ul").hide();
});

jQuery('.mutliSelect input[type="checkbox"]').on('click', function() {

	//var title = jQuery(this).closest('.mutliSelect').find('input[type="checkbox"]').val(),
	//  title = jQuery(this).val() + ",";
	var title = jQuery(this).parent().find('label').html()+ ",";

	if (jQuery(this).is(':checked')) {
		var html = '<span title="' + title + '">' + title + '</span>';
		jQuery('.multiSel').append(html);
		jQuery(".cmc-label").hide();
	} else {
		jQuery('span[title="' + title + '"]').remove();
		var ret = jQuery(".cmc-label");
		jQuery('.dropdown dt a').append(ret);

	}
});
</script>
