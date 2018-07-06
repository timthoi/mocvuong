<?php
/**
 * @package     Joomla.Plugin
 * @subpackage  Search.shevron_product
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * shevron_product search plugin.
 *
 * @since  1.6
 */
class PlgSearchShevron_product extends JPlugin
{
	/**
	 * Load the language file on instantiation.
	 *
	 * @var    boolean
	 * @since  3.1
	 */
	protected $autoloadLanguage = true;

	/**
	 * Determine areas searchable by this plugin.
	 *
	 * @return  array  An array of search areas.
	 *
	 * @since   1.6
	 */
	public function onContentSearchAreas()
	{
		static $areas = array(
			'shevron_product' => 'PLG_SEARCH_SHEVRON_PRODUCT_SHEVRON_PRODUCT'
		);

		return $areas;
	}

	/**
	 * Search content (shevron_product).
	 *
	 * The SQL must return the following fields that are used in a common display
	 * routine: href, title, section, created, text, browsernav.
	 *
	 * @param   string  $text      Target search string.
	 * @param   string  $phrase    Matching option (possible values: exact|any|all).  Default is "any".
	 * @param   string  $ordering  Ordering option (possible values: newest|oldest|popular|alpha|category).  Default is "newest".
	 * @param   string  $areas     An array if the search is to be restricted to areas or null to search all areas.
	 *
	 * @return  array  Search results.
	 *
	 * @since   1.6
	 */
	public function onContentSearch($text, $phrase = '', $ordering = '', $areas = null)
	{
		require_once JPATH_SITE . '/components/com_shevron/helpers/route.php';

		$db = JFactory::getDbo();
		$app = JFactory::getApplication();
		$user = JFactory::getUser();
		$groups = implode(',', $user->getAuthorisedViewLevels());

		if (is_array($areas))
		{
			if (!array_intersect($areas, array_keys($this->onContentSearchAreas())))
			{
				return array();
			}
		}

		$sContent = $this->params->get('search_content', 1);
		$sArchived = $this->params->get('search_archived', 1);
		$limit = $this->params->def('search_limit', 50);
		$state = array();

		if ($sContent)
		{
			$state[] = 1;
		}

		if ($sArchived)
		{
			$state[] = 2;
		}

		if (empty($state))
		{
			return array();
		}

		$text = trim($text);

		if ($text == '')
		{
			return array();
		}

		$section = JText::_('PLG_SEARCH_SHEVRON_PRODUCT_SHEVRON_PRODUCT');

		switch ($ordering)
		{
			case 'alpha':
			case 'category':
			case 'popular':
			case 'newest':
			case 'oldest':
			default:
				$order = 'a.name DESC';
		}

		$text = $db->quote('%' . $db->escape($text, true) . '%', false);

		$query = $db->getQuery(true);


		$query->select(
			'a.id, a.name AS title, \'\' AS created, a.category_id, a.creation_date,'
				. $query->concatenate(array("a.name"), ",") . ' AS text,'
				. '\'2\' AS browsernav'
		);
		$query->from('#__shevron_product AS a')
			->where(
				'(a.name LIKE ' .$text. ' ) AND a.published IN (' . implode(',', $state) . ') AND a.published=1 '
			)
			->group('a.id')
			->order($order);

		$db->setQuery($query, 0, $limit);
		$rows = $db->loadObjectList();

		if ($rows)
		{
			foreach ($rows as $key => $row)
			{
				$rows[$key]->href = ShevronHelperRoute::getProductRoute($row->id, $row->category_id);
				$rows[$key]->text = $row->title;
				$rows[$key]->created .= ($row->creation_date) ? ', ' . $row->creation_date : '';
			}
		}

		return $rows;
	}
}
