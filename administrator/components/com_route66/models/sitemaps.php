<?php
/**
 * @author      Lefteris Kavadas
 * @copyright   Copyright (c) 2016 - 2021 Lefteris Kavadas / firecoders.com
 * @license     GNU General Public License version 3 or later
 */
defined('_JEXEC') or die;

use Joomla\CMS\Application\CMSApplication;
use Joomla\CMS\Factory;

class Route66ModelSitemaps extends JModelList
{
	public function __construct()
	{
		if (empty($config['filter_fields']))
		{
			$config['filter_fields'] = array('id', 'title', 'state');
		}
		parent::__construct($config);
	}

	protected function populateState($ordering = null, $direction = null)
	{
		parent::populateState('id', 'DESC');

		$published = $this->getUserStateFromRequest($this->context . '.filter.published', 'filter_published');
		$this->setState('filter.published', $published);

		$search = $this->getUserStateFromRequest($this->context . '.filter.search', 'filter_search');
		$this->setState('filter.search', $search);

		$limit = $this->getUserStateFromRequest($this->context . '.list.limit', 'limit', 20, 'int');
		$this->setState('list.limit', $limit);
		$params = JComponentHelper::getParams('com_route66');
		$this->setState('params', $params);
	}

	protected function getListQuery()
	{
		$db = $this->getDbo();
		$query = $db->getQuery(true);
		$query->select($this->getState('list.select', '*'));
		$query->from($db->qn('#__route66_sitemaps'));

		if ($this->getState('filter.search'))
		{
			$query->where($db->qn('title') . ' LIKE ' . $db->q('%' . $db->escape(trim($this->getState('filter.search')), true) . '%'));
		}

		if (is_numeric($this->getState('filter.published')))
		{
			$query->where($db->qn('state') . ' = ' . (int) $this->getState('filter.published'));
		}

		$query->order($db->escape($this->state->get('list.ordering', 'id') . ' ' . $this->state->get('list.direction', 'DESC')));

		return $query;
	}

	protected function getStoreId($id = '')
	{
		$id .= ':' . $this->getState('filter.search');
		$id .= ':' . $this->getState('filter.published');

		return parent::getStoreId($id);
	}

	public function getItems()
	{
		$application = Factory::getApplication();
		$suffix = $application->get('sef') && $application->get('sef_suffix') ? '?format=xml': '';

		$items = parent::getItems();

		foreach ($items as $key => $item)
		{
			$item->previewLink = $this->getPreviewLink('index.php?option=com_route66&view=sitemapindex&id=' . $item->id . '&format=xml') . $suffix;
			$item->editLink = JRoute::_('index.php?option=com_route66&task=sitemap.edit&id=' . $item->id);
		}

		return $items;
	}

	public function getPreviewLink($route)
	{
		$site = CMSApplication::getInstance('site');
		$router = $site->getRouter();
		$uri = $router->build($route);
		$link = $uri->toString(array('path', 'query', 'fragment'));
		$link = JUri::root(false) . substr($link, strlen(JUri::root(true)) + 1);

		return $link;
	}
}
