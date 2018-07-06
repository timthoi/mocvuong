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
<div class="row login-form" id="btl-content-login">
	<!-- Form login -->	
	<div class="col-sm-6 login">
		<!--Login-->
		<form class="btl-formlogin form-validate" action="<?php echo JRoute::_('index.php?option=com_users&task=user.login'); ?>" method="post">
			<h3>Sign In</h3>

			<fieldset>
				<div class="btl-field">
					<div class="btl-input">
						<input id="username" type="email" name="username" required aria-required="true" autofocus class="validate-username required" placeholder="Email"	/>
					</div>
				</div>
				<div class="btl-field">
					<div class="btl-input">
						<input id="password" type="password" name="password" alt="password" class="validate-password required" required aria-required="true" placeholder="Password" />
					</div>
				</div>
				
				<div class="btl-buttonsubmit">
					<input type="submit" name="Submit" class="btl-buttonsubmit" value="Sign in"  /> 
					
					<?php 
						$jinput = JFactory::getApplication()->input;
						$return = $jinput->get->get('return', '', 'STR');
						$redirect_url = "";
						if ($return) $redirect_url =  base64_encode(JRoute::_('index.php?option=com_virtuemart&view=user&task=editaddresscart&addrtype=BT'));
					?>
					<input type="hidden" name="return" value="<?php echo $redirect_url; ?>" />
					<?php echo JHtml::_('form.token'); ?>
				</div>
	
				<?php if ($this->tfa): ?>
					<div class="control-group">
						<div class="control-label">
							<?php echo $this->form->getField('secretkey')->label; ?>
						</div>
						<div class="controls">
							<?php echo $this->form->getField('secretkey')->input; ?>
						</div>
					</div>
				<?php endif; ?>
	
				<div class="btl-field">				
					<div id="btl-input-remember">
						<input id="remember"  type="checkbox" name="remember" value="yes" />
						<label for="remember">Keep me logged in</a>
					</div>
					<div class="forgot">
						<a href="<?php echo JRoute::_('index.php?option=com_users&view=reset'); ?>">Forgot Password?</a>
					</div>
				</div>

			</fieldset>
			
			<?php
			jimport( 'joomla.application.module.helper' );
			$module = JModuleHelper::getModule( 'mod_slogin' );
			echo JModuleHelper::renderModule( $module );
			?>
			
		</form>	
	</div><!--login-->
	
	<div class="col-sm-6 register">
	<!--Registrattion-->	
		<form name="btl-formregistration" class="btl-formregistration form-validate" action="<?php echo JRoute::_('index.php?option=com_users&task=registration.register'); ?>" method="post" enctype="multipart/form-data">
			<div id="btl-register-in-process"></div>	
			<h3>Register</h3>
			<!--
			<div id="btl-success"></div>
			<div id="btl-registration-error" class="btl-error"></div>
			-->
			<div class="btl-field">
				<div class="btl-input">
					<input id="jform_email1" type="email" name="jform[email1]" placeholder="Email" onchange="setUserNamebyEmail()" />
				</div>
			</div>
			
			<div class="btl-field" style="display: none">
				<div class="btl-input">
					<input id="jform_name" type="text" name="jform[name]" placeholder="<?php echo JText::_( 'MOD_BT_LOGIN_NAME' ); ?>" />
				</div>
			</div>			
			
			<div class="btl-field" style="display: none">
				<div class="btl-input">
					<input id="jform_username" type="text" name="jform[username]" placeholder="<?php echo JText::_( 'MOD_BT_LOGIN_USERNAME' ); ?>"  />
				</div>
			</div>
			
			<div class="btl-field">
				<div class="btl-input">
					<input id="jform_password1" type="password" name="jform[password1]" placeholder="Password"  />
				</div>
			</div>		
			
			<div class="btl-field">
				<div class="btl-input">
					<input id="jform_password2" type="password" name="jform[password2]" placeholder="Retype Password"  />
				</div>
			</div>

			<div class="btl-field" style="display: none">
				<div class="btl-input">
					<input id="jform_email2" type="text" name="jform[email2]" placeholder="<?php echo JText::_( 'MOD_BT_VERIFY_EMAIL' ); ?>" />
				</div>
			</div>
			
			<script>
			//Set username = email address
			function setUserNamebyEmail() {
				var email = jQuery('input[name="jform[email1]"]').val();
				jQuery('input[name="jform[username]"], input[name="jform[name]"], input[name="jform[email2]"]').val(email);
			}
			</script>
			
			<div class="btl-buttonsubmit">						
				<button type="submit" class="btl-buttonsubmit validate">
					<?php echo JText::_('JREGISTER');?>							
				</button>
				
				<input type="hidden" name="option" value="com_users" />
				<input type="hidden" name="task" value="registration.register" />
				<?php echo JHtml::_('form.token');?>
			</div>
					
			</form>
		</div>
	</div><!--register-->
	
</div>
