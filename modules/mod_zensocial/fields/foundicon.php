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

// Create a select list of icons so we don't have to repeat it in the xml
class JFormFieldFoundicon extends JFormField
{

	protected $type = 'foundicon';

	protected function getInput()
	{
		// List options

		$icons = array(
			"-1" => "No Icon",
			"fa fa-dribbble" => "Dribbble",
			"fa fa-rss" => "Rss",
			"fa fa-facebook-square" => "Facebook",
			"fa fa-twitter" => "Twitter",
			"fa fa-pinterest" => "Pinterest",
			"fa fa-github" => "Github",
			"fa fa-path" => "Path",
			"fa fa-linkedin" => "Linked In",
			"fa fa-stumble-upon" => "Stumble Upon",
			"fa fa-behance" => "Behance",
			"fa fa-reddit" => "Reddit",
			"fa fa-google-plus" => "Google Plus",
			"fa fa-youtube-square" => "Youtube",
			"fa fa-vimeo" => "Vimeo",
			"fa fa-flickr" => "Flickr",
			"fa fa-slideshare" => "Slideshare",
			"fa fa-skype" => "Skype",
			"fa fa-steam" => "Steam",
			"fa fa-instagram" => "Instagram",
			"fa fa-foursquare" => "Four Square",
			"fa fa-delicious" => "Delicious",
			"fa fa-chat" => "Chat",
			"fa fa-torso" => "Torso",
			"fa fa-tumblr" => "Tumblr",
			"fa fa-video-chat" => "Video Chat",
			"fa fa-digg" => "Digg",
			"fa fa-wordpress" => "Wordpress",
			);

		return JHTML::_('select.genericlist',  $icons, ''.$this->formControl.'[params]['.$this->fieldname.'][]',
			'class="inputbox" style="" ', 'id', 'title', $this->value, $this->id
		);
	}

}

?>