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
<div class="reset-complete<?php echo $this->pageclass_sfx?>">
	<h1>Reset your password</h1>
	<form action="<?php echo JRoute::_('index.php?option=com_users&task=reset.complete'); ?>" method="post" class="form-validate form-horizontal">
		<?php foreach ($this->form->getFieldsets() as $fieldset) : ?>
			<fieldset>
				<?php foreach ($this->form->getFieldset($fieldset->name) as $name => $field) : ?>
					<div class="control-group">
						<div class="controls">
							<?php echo $field->input; ?>
						</div>
					</div>
				<?php endforeach; ?>
			</fieldset>
		<?php endforeach; ?>

		<div class="control-group">
			<div class="controls">
				<button type="submit" class="button-2 validate"><?php echo JText::_('JSUBMIT'); ?></button>
			</div>
		</div>
		<?php echo JHtml::_('form.token'); ?>
	</form>
</div>
