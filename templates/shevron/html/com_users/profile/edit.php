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
JHtml::_('formbehavior.chosen', 'select');

// Load user_profile plugin language
$lang = JFactory::getLanguage();
$lang->load('plg_user_profile', JPATH_ADMINISTRATOR);


$user = JFactory::getUser();

$app = JFactory::getApplication();
$postData = $app->input->getArray(array());

if(isset($postData['old_password'])){
	$result = JUserHelper::verifyPassword($postData['old_password'], $user->password, $user->id);
	if($result == 1) 
		return json_encode(['success' => true]);
	return json_encode(['success' => false]);
}


?>
<div class="profile-edit<?php echo $this->pageclass_sfx?>">
	<?php if ($this->params->get('show_page_heading')) : ?>
		<div class="page-header">
			<h1><?php echo $this->escape($this->params->get('page_heading')); ?></h1>
		</div>
	<?php endif; ?>

	<script type="text/javascript">
		Joomla.twoFactorMethodChange = function(e)
		{
			var selectedPane = 'com_users_twofactor_' + jQuery('#jform_twofactor_method').val();

			jQuery.each(jQuery('#com_users_twofactor_forms_container>div'), function(i, el) {
				if (el.id != selectedPane)
				{
					jQuery('#' + el.id).hide(0);
				}
				else
				{
					jQuery('#' + el.id).show(0);
				}
			});
		}

		jQuery(function($){
			$('#jform_password1').attr("placeholder", "New Password");
			$('#jform_password2').attr("placeholder", "Retype Password");

			/*$('.profile-edit').on('click', 'button[type=button]', function(e){
				e.preventDefault();
				var old_password = $('#member-profile input[name=old-password]').serialize();
				$.ajax({
					method: 'POST',
					url: '<?php echo JUri::base().'index.php?option=com_users&view=profile&layout=changepassword&tmpl=component' ?>',
					data: old_password,
					success: function(result) {
						console.log(result);
						alert(result.success);
					},
					error: function(error){
						console.log(error);
					}
				});
			});*/

		});
	</script>

	<form id="member-profile" action="<?php echo JRoute::_('index.php?option=com_users&task=profile.save'); ?>" method="post" class="form-validate form-horizontal well" enctype="multipart/form-data">
	<?php // Iterate through the form fieldsets and display each one. ?>
	<?php foreach ($this->form->getFieldsets() as $group => $fieldset) : ?>
		<?php $fields = $this->form->getFieldset($group); ?>
		<?php if (count($fields)) : ?>
		<fieldset>
			<?php // If the fieldset has a label set, display it as the legend. ?>
			<?php if (isset($fieldset->label)) : ?>
			<legend class="title_change_pass">
				<?php echo JText::_($fieldset->label); ?>
			</legend>
			<input type="password" name="jform[old_password]" id="jform_old_password" placeholder="Old Password">
			<a href="<?php echo JRoute::_('index.php?option=com_users&view=reset&Itemid=139') ?>">Forgot Password ?</a>
			<?php endif;?>
			<?php // Iterate through the fields in the set and display them. ?>
			<?php foreach ($fields as $field) : ?>
			<?php // If the field is hidden, just display the input. ?>
				<?php if ($field->hidden) : ?>
					<?php echo $field->input; ?>
				<?php else : ?>
					<?php if($field->type == 'Password') { ?>
					<div class="control-group">
						<div class="control-label" style="display:none;">
							<?php echo $field->label; ?>
							<?php if (!$field->required && $field->type != 'Spacer') : ?>
								<span class="optional"><?php echo JText::_('COM_USERS_OPTIONAL'); ?></span>
							<?php endif; ?>
						</div>
						<div class="controls">
							<?php if ($field->fieldname == 'password1') : ?>
								<?php // Disables autocomplete ?> <input type="text" style="display:none">
							<?php endif; ?>
							<?php echo $field->input; ?>
						</div>
					</div>
					<?php } else { ?>
						<div class="control-group" style="display: none;">
							<div class="control-label" style="display:none;">
								<?php echo $field->label; ?>
								<?php if (!$field->required && $field->type != 'Spacer') : ?>
									<span class="optional"><?php echo JText::_('COM_USERS_OPTIONAL'); ?></span>
								<?php endif; ?>
							</div>
							<div class="controls">
								<?php if ($field->fieldname == 'password1') : ?>
									<?php // Disables autocomplete ?> <input type="text" style="display:none">
								<?php endif; ?>
								<?php echo $field->input; ?>
							</div>
						</div>
					<?php } ?>
				<?php endif;
				?>
			<?php endforeach;
			?>
		</fieldset>
		<?php endif;
		break;?>
	<?php endforeach;?>

	<?php if (count($this->twofactormethods) > 1) : ?>
		<fieldset>
			<legend><?php echo JText::_('COM_USERS_PROFILE_TWO_FACTOR_AUTH'); ?></legend>

			<div class="control-group">
				<div class="control-label">
					<label id="jform_twofactor_method-lbl" for="jform_twofactor_method" class="hasTooltip"
						   title="<?php echo '<strong>' . JText::_('COM_USERS_PROFILE_TWOFACTOR_LABEL') . '</strong><br />' . JText::_('COM_USERS_PROFILE_TWOFACTOR_DESC'); ?>">
						<?php echo JText::_('COM_USERS_PROFILE_TWOFACTOR_LABEL'); ?>
					</label>
				</div>
				<div class="controls">
					<?php echo JHtml::_('select.genericlist', $this->twofactormethods, 'jform[twofactor][method]', array('onchange' => 'Joomla.twoFactorMethodChange()'), 'value', 'text', $this->otpConfig->method, 'jform_twofactor_method', false); ?>
				</div>
			</div>
			<div id="com_users_twofactor_forms_container">
				<?php foreach($this->twofactorform as $form) : ?>
				<?php $style = $form['method'] == $this->otpConfig->method ? 'display: block' : 'display: none'; ?>
				<div id="com_users_twofactor_<?php echo $form['method']; ?>" style="<?php echo $style; ?>">
					<?php echo $form['form']; ?>
				</div>
				<?php endforeach; ?>
			</div>
		</fieldset>

		<fieldset>
			<legend>
				<?php echo JText::_('COM_USERS_PROFILE_OTEPS'); ?>
			</legend>
			<div class="alert alert-info">
				<?php echo JText::_('COM_USERS_PROFILE_OTEPS_DESC'); ?>
			</div>
			<?php if (empty($this->otpConfig->otep)) : ?>
			<div class="alert alert-warning">
				<?php echo JText::_('COM_USERS_PROFILE_OTEPS_WAIT_DESC'); ?>
			</div>
			<?php else : ?>
			<?php foreach ($this->otpConfig->otep as $otep) : ?>
			<span class="span3">
				<?php echo substr($otep, 0, 4); ?>-<?php echo substr($otep, 4, 4); ?>-<?php echo substr($otep, 8, 4); ?>-<?php echo substr($otep, 12, 4); ?>
			</span>
			<?php endforeach; ?>
			<div class="clearfix"></div>
			<?php endif; ?>
		</fieldset>
	<?php endif; ?>

		<div class="control-group">
			<div class="controls">
				<button type="submit" class="btn btn-primary validate button-2 btn-save-changes">Save Changes</button>
				<input type="hidden" name="option" value="com_users" />
				<input type="hidden" name="task" value="profile.save" />
			</div>
		</div>
		<?php echo JHtml::_('form.token'); ?>
	</form>
</div>
