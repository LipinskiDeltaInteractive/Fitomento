<?php
/**
 * @author      Lefteris Kavadas
 * @copyright   Copyright (c) 2016 - 2021 Lefteris Kavadas / firecoders.com
 * @license     GNU General Public License version 3 or later
 */
defined('_JEXEC') or die;

class Route66ModelSeo extends JModelList
{
	protected $contextTableName;
	protected $contextTitleField;
	protected $contextEditLink;

	public function __construct()
	{
		if (empty($config['filter_fields']))
		{
			$config['filter_fields'] = array('id', 'title', 'context', 'score');
		}
		parent::__construct($config);
	}

	protected function populateState($ordering = null, $direction = null)
	{
		parent::populateState('id', 'DESC');

		$context = $this->getUserStateFromRequest($this->context . '.filter.context', 'filter_context', 'com_content.article');
		$this->setContext($context);

		$search = $this->getUserStateFromRequest($this->context . '.filter.search', 'filter_search');
		$this->setState('filter.search', $search);

		$score = $this->getUserStateFromRequest($this->context . '.filter.score', 'filter_score');
		$this->setState('filter.score', $score);

		$limit = $this->getUserStateFromRequest($this->context . '.list.limit', 'limit', 20, 'int');
		$this->setState('list.limit', $limit);
		$params = JComponentHelper::getParams('com_route66');
		$this->setState('params', $params);
	}

	protected function getListQuery()
	{
		$db = $this->getDbo();
		$query = $db->getQuery(true);
		$query->select($this->getState('list.select', $db->qn('seo') . '.*'));
		$query->from($db->qn('#__route66_seo', 'seo'));

		if ($context = $this->getState('filter.context'))
		{
			$query->where($db->qn('context') . ' = ' . $db->q($context));
			$query->leftJoin($db->qn($this->contextTableName, 'resource') . ' ON ' . $db->qn('seo.resourceId') . ' = ' . $db->qn('resource.id'));
			$query->select($db->qn('resource.' . $this->contextTitleField));
		}

		if ($this->getState('filter.search'))
		{
			$search = $db->q('%' . $db->escape(trim($this->getState('filter.search')), true) . '%');
			$conditions = array();
			$conditions[] = $db->qn('resource.' . $this->contextTitleField) . ' LIKE ' . $search;
			$conditions[] = $db->qn('seo.keyword') . ' LIKE ' . $search;
			$query->where('(' . implode(' OR ', $conditions) . ')');
		}

		if ($score = $this->getState('filter.score'))
		{
			$parts = explode('-', $score);
			list($minimum, $maximum) = $parts;
			$query->where($db->qn('score') . ' >= ' . (int) $minimum);
			$query->where($db->qn('score') . ' <= ' . (int) $maximum);
		}
		$query->order($db->escape($this->state->get('list.ordering', $db->qn('seo.id')) . ' ' . $this->state->get('list.direction', 'DESC')));

		return $query;
	}

	protected function getStoreId($id = '')
	{
		$id .= ':' . $this->getState('filter.context');
		$id .= ':' . $this->getState('filter.search');
		$id .= ':' . $this->getState('filter.score');

		return parent::getStoreId($id);
	}

	public function getItems()
	{
		$this->clean();
		$items = parent::getItems();

		$j4 = version_compare(JVERSION, '4.0', 'ge');

		foreach ($items as $key => $item)
		{
			$item->editLink = JRoute::_($this->contextEditLink . $item->resourceId);

			if ($item->score > 70)
			{
				$item->badgeClass = 'success';
			}
			elseif ($item->score > 40)
			{
				$item->badgeClass = 'warning';
			}
			else
			{
				$item->badgeClass = $j4 ? 'danger': 'important';
			}
		}

		return $items;
	}

	public function fetch($context, $resourceId)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('*');
		$query->from($db->qn('#__route66_seo'));
		$query->where($db->qn('context') . ' = ' . $db->q($context));
		$query->where($db->qn('resourceId') . ' = ' . (int) $resourceId);
		$db->setQuery($query);
		$result = $db->loadObject();

		return $result;
	}

	public function save($context, $resourceId, $keyword, $score)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->insert($db->qn('#__route66_seo'));
		$values = array($db->q('0'), $db->q($context), (int) $resourceId, $db->q($keyword), (int) $score);
		$query->values(implode(',', $values));
		$db->setQuery($query);
		$db->execute();
	}

	public function delete($context, $resourceId)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->delete($db->qn('#__route66_seo'));
		$query->where($db->qn('context') . ' = ' . $db->q($context));
		$query->where($db->qn('resourceId') . ' = ' . (int) $resourceId);
		$db->setQuery($query);
		$db->execute();
	}

	private function setContext($context)
	{
		$parts = explode('.', $context);
		$option = $parts[0];
		$component = JComponentHelper::getComponent($option);

		if (!$component->id)
		{
			$context = 'com_content.article';
			$application = JFactory::getApplication();
			$application->enqueueMessage(JText::_('COM_ROUTE66_SEO_THIRD_PARTY_NOT_INSTALLED'), 'warning');
			$application->setUserState($this->context . '.filter.context', $context);
		}
		$this->setState('filter.context', $context);
		$this->setContextVars($context);
	}

	private function setContextVars($context)
	{
		switch ($context)
			{
				case 'com_content.article':
					$this->contextTableName = '#__content';
					$this->contextTitleField = 'title';
					$this->contextEditLink = 'index.php?option=com_content&task=article.edit&id=';

					break;
				case 'com_k2.item':
					$this->contextTableName = '#__k2_items';
					$this->contextTitleField = 'title';
					$this->contextEditLink = 'index.php?option=com_k2&view=item&cid=';

					break;
			}
	}

	private function clean()
	{
		$db = $this->getDbo();
		$query = $db->getQuery(true);
		$query->delete($db->qn('#__route66_seo'));

		if ($context = $this->getState('filter.context'))
		{
			$query->where($db->qn('context') . ' = ' . $db->q($context));
			$query->where('NOT EXISTS (SELECT * FROM ' . $db->qn($this->contextTableName) . ' WHERE ' . $db->qn($this->contextTableName) . '.' . $db->qn('id') . ' = ' . $db->qn('#__route66_seo') . '.' . $db->qn('resourceId') . ')');
		}
		$db->setQuery($query);
		$db->execute();
	}
}
