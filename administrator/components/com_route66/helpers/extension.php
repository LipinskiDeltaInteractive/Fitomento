<?php
/**
 * @author      Lefteris Kavadas
 * @copyright   Copyright (c) 2016 - 2021 Lefteris Kavadas / firecoders.com
 * @license     GNU General Public License version 3 or later
 */
defined('_JEXEC') or die;

class Route66HelperExtension
{
	public static function getVersion()
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select($db->qn('manifest_cache'))->from($db->qn('#__extensions'))->where($db->qn('element') . ' = ' . $db->q('pkg_route66'));
		$db->setQuery($query);
		$manifest = json_decode($db->loadResult());

		return $manifest->version;
	}

	public static function fixPluginsOrdering()
	{
		if (JPluginHelper::isEnabled('system', 'sh404sef'))
		{
			return;
		}

		$db = JFactory::getDbo();

		$query = $db->getQuery(true);
		$query->update($db->qn('#__extensions'));
		$query->set($db->qn('ordering') . ' = ' . $db->q('100'));
		$query->where($db->qn('folder') . ' = ' . $db->q('system'));
		$query->where($db->qn('element') . ' = ' . $db->q('route66pagespeed'));
		$db->setQuery($query);
		$db->execute();

		$query = $db->getQuery(true);
		$query->update($db->qn('#__extensions'));
		$query->set($db->qn('ordering') . ' = ' . $db->q('101'));
		$query->where($db->qn('folder') . ' = ' . $db->q('system'));
		$query->where($db->qn('element') . ' = ' . $db->q('route66'));
		$db->setQuery($query);
		$db->execute();

		$query = $db->getQuery(true);
		$query->update($db->qn('#__extensions'));
		$query->set($db->qn('ordering') . ' = ' . $db->q('102'));
		$query->where($db->qn('folder') . ' = ' . $db->q('system'));
		$query->where($db->qn('element') . ' = ' . $db->q('languagefilter'));
		$db->setQuery($query);
		$db->execute();
	}
}
