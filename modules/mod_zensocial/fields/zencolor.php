<?php
/**
 * @package     Zen Social
 * @subpackage  Zen Social
 * @author      Joomla Bamboo - design@joomlabamboo.com
 * @copyright   Copyright (c) 2013 Joomla Bamboo. All rights reserved.
 * @license     GNU General Public License version 2 or later
 * @version     1.1.1
 */

// no direct access
defined('_JEXEC') or die('Restricted index access');

/**
 * Renders a editors element
 *
 * @package 	Joomla.Framework
 * @subpackage	Parameter
 * @since		1.5
 */

class JFormFieldZencolor extends JFormField
{

	protected  $type = 'Zencolor';

	 protected function getInput()
	{
		$zgfEnabled = JPluginHelper::isEnabled ('system', 'zengridframework')	;
		if ($zgfEnabled) {

			$desc           = (string) $this->element['desc'];
			$class          = (string) $this->element['class'];

			ob_start();
			$img=JUri::root()."modules/mod_zensocial/assets/colorpicker/images/select.png";
			static $embedded;
			if (!$embedded)
			{
				$css2=JUri::root()."modules/mod_zensocial/assets/colorpicker/css/colorpicker.css";
				$jspath1=JUri::root()."modules/mod_zensocial/assets/colorpicker/js/colorpicker.js";
			?>

				<link href="<?php echo $css2;?>" type="text/css" rel="stylesheet" />
				<script src="<?php echo $jspath1;?>"></script>
					<?php $embedded=true; ?>
				<script>
				jQuery(document).ready(function(){

					jQuery('.rainbowbtn').each(function(){
						startCol = jQuery(this).prev('input').val();
						jQuery(this).ColorPicker({
							color: startCol,
							onSubmit: function(hsb, hex, rgb, el) {
								jQuery(el).prev('input').val(hex);
								jQuery(el).val(hex);
								jQuery(el).ColorPickerHide();
								jQuery(el).css('backgroundColor', '#'+ hex);
							},
							onBeforeShow: function () {
								jQuery(this).css('backgroundColor', '#'+ startCol);
							}
						});
						jQuery(this).prev('input').bind('keyup', function(){
							jQuery(this).next('.rainbowbtn').ColorPickerSetColor(jQuery(this).val());
							jQuery(this).next('.rainbowbtn').css('backgroundColor', '#'+ jQuery(this).val());
						});
					});

				});
				</script>
				<?php
			}
				?>

			<input name="<?php echo $this->name ?>" type="text" class="inputbox colorselect <?php echo $class ?>" id="<?php echo $this->name ?>"	value="<?php echo $this->value;?>" size="10" />
			<img src="<?php echo $img;?>" id="img<?php echo $desc; ?>" alt="[r]" class="rainbowbtn" width="28" height="28" />

			<?php $content=ob_get_contents();
			ob_end_clean();
			return $content;
		}
	}
}
