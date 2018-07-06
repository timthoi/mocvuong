<?php
/**
 * @package 	mod_bt_login - BT Login Module
 * @version		2.6.0
 * @created		April 2012
 * @author		BowThemes
 * @email		support@bowthems.com
 * @website		http://bowthemes.com
 * @support		Forum - http://bowthemes.com/forum/
 * @copyright	Copyright (C) 2011 Bowthemes. All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 *
 */

// no direct access
defined('_JEXEC') or die('Restricted access');
$app = JFactory::getApplication();
$com = $app->input->getCmd('option', '');
?>

<div id="btl">
	<!-- Panel top -->	
	<div class="btl-panel">
		<?php if($type == 'logout') : ?>
		<!-- Profile button -->
		<span id="btl-panel-profile" class="btl-dropdown">
			
			<?php
			echo "Welcome, ";
			if($params->get('name')) : {
				echo $user->get('name');
			} else : {
				echo $user->get('username');
			} endif;
			?>
			<i class="fa fa-chevron-down"></i>
		</span> 
		<?php else : ?>
			<!-- Login button -->
			<?php
			if($params->get('enabled_login_tab', 1)){
			?>
			<span id="btl-panel-login" class="<?php echo $effect;?>">Sign In
			<!-- Registration button -->
			<?php
			if($enabledRegistration && $params->get('enabled_registration_tab')){
				$option = JRequest::getCmd('option');
				$task = JRequest::getCmd('task');
				if($option!='com_user' && $task != 'register' ){
			?>
			<span id="btl-panel-registration" class="<?php echo $effect;?>">Register</span>
			<?php }
			} ?>
			</span>
			<?php }?>
			
			
			
		<?php endif; ?>
	</div>
	<!-- content dropdown/modal box -->
	<div id="btl-content">
		<?php if($type == 'logout') { ?>
		<!-- Profile module -->
		<div id="btl-content-profile" class="btl-content-block">
			<?php if($loggedInHtml): ?>
			<div id="module-in-profile">
				<?php echo $loggedInHtml; ?>
			</div>
			<?php endif; ?>
			<?php if($showLogout == 1):?>
			<ul class="dropdown-profile">
				<li><a href="<?php echo JRoute::_('index.php?option=com_virtuemart&view=user&layout=edit') ?>">My Account</a></li>
				<li>
				<form action="<?php echo JRoute::_('index.php', true, $params->get('usesecure')); ?>" method="post" name="logoutForm">
					<button name="Submit" class="btn-logout" onclick="document.logoutForm.submit();">Sign out</button>
					<input type="hidden" name="option" value="com_users" />
					<input type="hidden" name="task" value="user.logout" />
					<input type="hidden" name="return" value="<?php echo $return; ?>" />
					<?php echo JHtml::_('form.token'); ?>
				</form>
				</li>
			</ul>
			<?php endif;?>
		</div>
		<?php }else{ ?>	
		<!-- Form login -->	
		<div id="btl-content-login" class="btl-content-block">
			<a class="modalCloseImg" href="#"></a>
			<?php if(JPluginHelper::isEnabled('authentication', 'openid')) : ?>
				<?php JHTML::_('script', 'openid.js'); ?>
			<?php endif; ?>
			
			<div class="row">
				<div class="col-sm-6 login">
			<!--Login-->
			<!-- if not integrated any component -->
			<?php if($integrated_com==''|| $moduleRender == ''){?>
			<form name="btl-formlogin" class="btl-formlogin" action="<?php echo JRoute::_('index.php', true, $params->get('usesecure')); ?>" method="post">
				<div id="btl-login-in-process"></div>	
				<h3>Sign In</h3>
				<div class="btl-error" id="btl-login-error"></div>
				<div class="btl-field">
					<div class="btl-input">
						<input id="btl-input-username" type="text" name="name" placeholder="Email"	/>
					</div>
				</div>
				<div class="btl-field">
					<div class="btl-input">
						<input id="btl-input-password" type="password" name="password" alt="password" placeholder="Password" />
					</div>
				</div>
				<div class="clear"></div>
				<div class="btl-buttonsubmit">
					<input type="submit" name="Submit" class="btl-buttonsubmit" onclick="return loginAjax()" value="Sign in" /> 
					<input type="hidden" name="bttask" value="login" /> 
					<input type="hidden" name="return" id="btl-return"	value="<?php echo $return; ?>" />
					<?php echo JHtml::_('form.token');?>
				</div>
				<?php if (JPluginHelper::isEnabled('system', 'remember')) : ?>
				<div class="btl-field">				
					<div id="btl-input-remember">
						<input id="btl-checkbox-remember"  type="checkbox" name="remember"
							value="yes" />
						<label for="btl-checkbox-remember">Keep me logged in</a>
					</div>
					<div class="forgot">
						<a href="<?php echo JRoute::_('index.php?option=com_users&view=reset&Itemid=139'); ?>">Forgot Password?</a>
					</div>
				</div>
				<div class="clear"></div>
				<?php endif; ?>
				
				<?php
				//SLogin for social login
				jimport( 'joomla.application.module.helper' );
				$module = JModuleHelper::getModule( 'mod_slogin' );
				echo JModuleHelper::renderModule( $module );
				?>
				
			</form>	
			</div><!--col-->	

			
			<div class="col-sm-6 register">
				<!--Registrattion-->
				<!-- if not integrated any component -->
				<?php if($integrated_com==''){?>	
							
					<form name="btl-formregistration" class="btl-formregistration"  autocomplete="off">
						<div id="btl-register-in-process"></div>	
						<h3>Register</h3>
						<div id="btl-success"></div>
						<div id="btl-registration-error" class="btl-error"></div>
						<div class="btl-field">
							<div class="btl-input">
								<input id="btl-input-email1" type="email" name="jform[email1]" placeholder="Email" onchange="setUserNamebyEmail()" />
							</div>
						</div>
						
						<div class="btl-field" style="display: none">
							<div class="btl-input">
								<input id="btl-input-name" type="text" name="jform[name]" placeholder="<?php echo JText::_( 'MOD_BT_LOGIN_NAME' ); ?>" />
							</div>
						</div>			
						
						<div class="btl-field" style="display: none">
							<div class="btl-input">
								<input id="btl-input-username1" type="text" name="jform[username]" placeholder="<?php echo JText::_( 'MOD_BT_LOGIN_USERNAME' ); ?>"  />
							</div>
						</div>
						
						<div class="btl-field">
							<div class="btl-input">
								<input id="btl-input-password1" type="password" name="jform[password1]" placeholder="Password"  />
							</div>
						</div>		
						
						<div class="btl-field">
							<div class="btl-input">
								<input id="btl-input-password2" type="password" name="jform[password2]" placeholder="Retype Password"  />
							</div>
						</div>
	
						<div class="btl-field" style="display: none">
							<div class="btl-input">
								<input id="btl-input-email2" type="text" name="jform[email2]" placeholder="<?php echo JText::_( 'MOD_BT_VERIFY_EMAIL' ); ?>" />
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
							<button type="submit" class="btl-buttonsubmit" onclick="return registerAjax()" >
								<?php echo JText::_('JREGISTER');?>							
							</button>
							
							<input type="hidden" name="bttask" value="register" /> 
							<?php echo JHtml::_('form.token');?>
						</div>
								
						<!-- add captcha-->
						<?php if($enabledRecaptcha){?>
						<div class="btl-field">
							<div class="btl-label"><?php echo JText::_( 'MOD_BT_CAPTCHA' ); ?></div>
							<div id="recaptcha"><?php echo $reCaptcha;?></div>
						</div>
						<div id="btl-registration-captcha-error" class="btl-error-detail"></div>
						<div class="clear"></div>
						<!--  end add captcha -->
						<?php }?>
				
				</form>
				<!-- if  integrated any component -->
				<?php }else{ ?>
					<input type="hidden" name="integrated" value="<?php echo $linkOption?>" value="no" id="btl-integrated"/>		
				<?php }?>
			</div>
		</div>
			
		<!-- if integrated with one component -->
			<?php }else{ ?>
				<h3><?php echo JText::_('JLOGIN') ?></h3>
				<div id="btl-wrap-module"><?php  echo $moduleRender; ?></div>
				<?php }?>			
		</div>
	
	<?php } ?>
	
	</div>
</div>

<script type="text/javascript">
/*<![CDATA[*/
var btlOpt = 
{
	BT_AJAX					:'<?php echo ($com == 'com_shevron' ? JURI::root() : addslashes(JURI::getInstance()->toString())); ?>', //dirty fix for com_shevron
	BT_RETURN				:'<?php echo rawurlencode(addslashes($return_decode)); ?>',
	RECAPTCHA				:'<?php echo $enabledRecaptcha ;?>',
	LOGIN_TAGS				:'<?php echo $loginTag?>',
	REGISTER_TAGS			:'<?php echo $registerTag?>',
	EFFECT					:'<?php echo $effect?>',
	ALIGN					:'<?php echo $align?>',
	BG_COLOR				:'<?php echo $bgColor ;?>',
	MOUSE_EVENT				:'<?php echo $params->get('mouse_event','click') ;?>',
	TEXT_COLOR				:'<?php echo $textColor;?>',
	MESSAGES 				: {
		E_LOGIN_AUTHENTICATE 		: '<?php echo addslashes(JText::_('E_LOGIN_AUTHENTICATE'))?>',
		REQUIRED_NAME				: '<?php echo addslashes(JText::_('REQUIRED_EMAIL'))?>',
		REQUIRED_USERNAME			: '<?php echo addslashes(JText::_('REQUIRED_EMAIL'))?>',
		REQUIRED_PASSWORD			: '<?php echo addslashes(JText::_('REQUIRED_PASSWORD'))?>',
		REQUIRED_VERIFY_PASSWORD	: '<?php echo addslashes(JText::_('REQUIRED_VERIFY_PASSWORD'))?>',
		PASSWORD_NOT_MATCH			: '<?php echo addslashes(JText::_('PASSWORD_NOT_MATCH'))?>',
		REQUIRED_EMAIL				: '<?php echo addslashes(JText::_('REQUIRED_EMAIL'))?>',
		EMAIL_INVALID				: '<?php echo addslashes(JText::_('EMAIL_INVALID'))?>',
		REQUIRED_VERIFY_EMAIL		: '<?php echo addslashes(JText::_('REQUIRED_EMAIL'))?>',
		EMAIL_NOT_MATCH				: '<?php echo addslashes(JText::_('EMAIL_NOT_MATCH'))?>',
		CAPTCHA_REQUIRED			: '<?php echo addslashes(JText::_('CAPTCHA_REQUIRED'))?>'
	}
}
if(btlOpt.ALIGN == "center"){
	BTLJ(".btl-panel").css('textAlign','center');
}else{
	BTLJ(".btl-panel").css('float',btlOpt.ALIGN);
}
BTLJ("input.btl-buttonsubmit,button.btl-buttonsubmit").css({"color":btlOpt.TEXT_COLOR,"background":btlOpt.BG_COLOR});
BTLJ("#btl .btl-panel > span").css({"color":btlOpt.TEXT_COLOR,"background-color":btlOpt.BG_COLOR,"border":btlOpt.TEXT_COLOR});
/*]]>*/

</script>
