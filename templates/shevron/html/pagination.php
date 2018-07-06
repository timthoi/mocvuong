<?php

defined('JPATH_PLATFORM') or die;

   /**
	 * HTML Output
	 */

	function pagination_list_render($list)
	{
		// Reverse output rendering for right-to-left display.
		$html = '<ul class="pagination">';
		$html.= '<div class="pagination-list" style="text-align:center">';
		//$html .= '<li class="pagination-start">' . $list['start']['data'] . '</li>';
		$html .= '<li class="prev">' . $list['previous']['data'] . '</li>';
		foreach ($list['pages'] as $page)
		{
			$html .= '<li>' . $page['data'] . '</li>';
		}
		$html .= '<li class="next">' . $list['next']['data'] . '</li>';
		$html .= '<li class="total_page" style="display:none">' . count($list['pages']) . '</li>';
		//$html .= '<li class="pagination-end">' . $list['end']['data'] . '</li>';
		$html.= '</div>';
		$html .= '</ul>';

		return $html;
	}

	/**
	 * Method to create the new active pagination link to the item
	 */
	 
	function pagination_item_active(&$item)
	{
		$app = JFactory::getApplication();
		if ($app->isAdmin())
		{
			if ($item->base > 0)
			{
				return "<a title=\"" . $item->text . "\" onclick=\"document.adminForm." . $this->prefix . "limitstart.value=" . $item->base
					. "; Joomla.submitform();return false;\">" . $item->text . "</a>";
			}
			else
			{
				return "<a title=\"" . $item->text . "\" onclick=\"document.adminForm." . $this->prefix
					. "limitstart.value=0; Joomla.submitform();return false;\">" . $item->text . "</a>";
			}
		}
		else
		{
			if ($item->text == (JText::_('JLIB_HTML_START')))
			{
			return "<a title=\"" . $item->text . "\" href=\"" . $item->link . "\" class=\"pagenavstart\">" . (JText::_('JLIB_HTML_START_IMG')) . "</a>";
		  }
			elseif ($item->text == (JText::_('JPREV')))
			{
			return "<a title=\"" . $item->text . "\" href=\"" . $item->link . "\" class=\"pagenavback\"><i class='fa fa-angle-left'></i></a>";
		  }
		  elseif ($item->text == (JText::_('JNEXT')))
		  {
			return "<a title=\"" . $item->text . "\" href=\"" . $item->link . "\" class=\"pagenavnext\"><i class='fa fa-angle-right'></i></a>";
		  }
		  elseif ($item->text == (JText::_('JLIB_HTML_END')))
		  {
			return "<a title=\"" . $item->text . "\" href=\"" . $item->link . "\" class=\"pagenavend\">" . (JText::_('JLIB_HTML_END_IMG')) . "</a>";
		  }
			return "<a title=\"" . $item->text . "\" href=\"" . $item->link . "\" class=\"active\">" . $item->text . "</a>";
		}
	}

		/**
	 * Method to create the new inactive pagination link to the item
	 */
	 
	function pagination_item_inactive(&$item)
	{
		$app = JFactory::getApplication();
		if ($app->isAdmin())
		{
			return "<span>" . $item->text . "</span>";
		}
		else
		{
			if ($item->text == (JText::_('JLIB_HTML_START')))
			{
				return "<span class=\"pagenavstart\">" . (JText::_('JLIB_HTML_START_IMG')) . "</span>";
		  }
		  elseif ($item->text == (JText::_('JPREV')))
		  {
		    return "";
		  }
		  elseif ($item->text == (JText::_('JNEXT')))
		  {
		    return "";
		  }
		  elseif ($item->text == (JText::_('JLIB_HTML_END')))
		  {
		    return "<span class=\"pagenavend\">" . (JText::_('JLIB_HTML_END_IMG')) . "</span>";
		  }		  
			return "<span class=\"active\">" . $item->text . "</span>";
		}
	}