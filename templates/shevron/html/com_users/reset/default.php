<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_users
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

JHtml::_('behavior.keepalive');
JHtml::_('behavior.formvalidator');
?>
<div class="reset<?php echo $this->pageclass_sfx?>">
	<?php if ($this->params->get('show_page_heading')) : ?>
	<div class="page-header">
		<h1>
			<?php echo $this->escape($this->params->get('page_heading')); ?>
		</h1>
	</div>
	<?php endif; ?>

	<div class="border-one">
		<div class="page-header">
			<h2 class="shop-heading">Forgot Password</h2>
		</div>
	</div>

	<form id="user-registration" action="<?php echo JRoute::_('index.php?option=com_users&task=reset.request'); ?>" method="post" class="form-validate form-horizontal well">
		<fieldset>
		<?php foreach ($this->form->getFieldsets() as $fieldset) : ?>
			
				<p><?php echo JText::_($fieldset->label); ?></p>
				<?php foreach ($this->form->getFieldset($fieldset->name) as $name => $field) : ?>
					<?php if($field->input != null) { ?>
					<div class="control-group col-sm-7 col-xs-7">
						<div class="controls">
							<?php echo $field->input; ?>
						</div>
					</div>
					<?php } ?>
				<?php endforeach; ?>

			
		<?php endforeach; ?>
		<div class="control-group col-sm-5 col-xs-5">
			<div class="controls">
				<button type="submit" class="btn btn-primary validate button-2 btn-reset-pass">Send Reset Instructions</button>
			</div>
		</div>
		<?php echo JHtml::_('form.token'); ?>
		</fieldset>
	</form>
</div>
