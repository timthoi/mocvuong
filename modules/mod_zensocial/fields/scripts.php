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

/**
 * Form Field class for the Joomla Framework.
 *
 * @package		Mod_zensocial
 * @subpackage	Form
 * @since		1.6
 */

class JFormFieldScripts extends JFormField
{
	protected $type = 'scripts';

	protected function getInput()
	{

		$document = JFactory::getDocument();
		$root = JURI::root();


		if (version_compare(JVERSION, '3.0', '<'))
		{
			$document->addScript(''.$root.'modules/mod_zensocial/js/admin/jquery-1.8.3.min.js');
			$document->addScript(''.$root.'modules/mod_zensocial/js/admin/jquery-ui-1.8.23.custom.min.js');
			$document->addScript(''.$root.'modules/mod_zensocial/js/admin/jquery.noconflict.js');
		}
		else
		{
			$document->addScript(''.$root.'modules/mod_zensocial/js/admin/jquery-ui-1.8.23.custom.min.js');
		}

		ob_start();

		if (version_compare(JVERSION, '3.0', '<'))
		{
			?>
			<script type="text/javascript">
			jQuery(function(panels) {
				// Hide / Show relevant panels on page load
				jQuery('#jform_params_socialfonticons').bind('change', function(event) {
					var i = jQuery('#jform_params_socialfonticons').val();

					if(i==1){
						jQuery('#icons-options').parent('div').show();
						jQuery('#images-options').parent('div').hide();
					} else {
						jQuery('#icons-options').parent('div').hide();
						jQuery('#images-options').parent('div').show();
					}
				});
			});
			jQuery(document).ready(function() {
				jQuery('#jform_params_socialfonticons').change();
			});
			</script>
			<?php
		}
		else
		{
			?>
			<script type="text/javascript">
			jQuery(function(panels) {
				// Hide / Show relevant panels on page load
				jQuery('#jform_params_socialfonticons').bind('change', function(event) {
					var i = jQuery('#jform_params_socialfonticons').val();

					if(i==1){
						jQuery('#collapse1').parent('div.accordion-group').show();
						jQuery('#collapse2').parent('div.accordion-group').hide();
					} else {
						jQuery('#collapse1').parent('div.accordion-group').hide();
						jQuery('#collapse2').parent('div.accordion-group').show();
					}
				});
			});
			jQuery(document).ready(function() {
				jQuery('#jform_params_socialfonticons').change();
			});
			</script>
			<?php
		}

		return ob_get_clean();
	}
}
