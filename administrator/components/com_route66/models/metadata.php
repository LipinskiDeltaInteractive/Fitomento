<?php
/**
 * @author      Lefteris Kavadas
 * @copyright   Copyright (c) 2016 - 2021 Lefteris Kavadas / firecoders.com
 * @license     GNU General Public License version 3 or later
 */
defined('_JEXEC') or die;

class Route66ModelMetadata extends JModelList
{
	public function fetch($context, $resourceId)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('*');
		$query->from($db->qn('#__route66_metadata'));
		$query->where($db->qn('context') . ' = ' . $db->q($context));
		$query->where($db->qn('resourceId') . ' = ' . (int) $resourceId);
		$db->setQuery($query);
		$result = $db->loadObject();

		return $result;
	}

	public function save($context, $resourceId, $metadata)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->insert($db->qn('#__route66_metadata'));
		$values = array($db->q('0'), $db->q($context), (int) $resourceId, $db->q(json_encode($metadata)));
		$query->values(implode(',', $values));
		$db->setQuery($query);
		$db->execute();
	}

	public function delete($context, $resourceId)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->delete($db->qn('#__route66_metadata'));
		$query->where($db->qn('context') . ' = ' . $db->q($context));
		$query->where($db->qn('resourceId') . ' = ' . (int) $resourceId);
		$db->setQuery($query);
		$db->execute();
	}
}
