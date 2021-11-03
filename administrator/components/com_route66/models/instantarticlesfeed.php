<?php
/**
 * @author      Lefteris Kavadas
 * @copyright   Copyright (c) 2016 - 2021 Lefteris Kavadas / firecoders.com
 * @license     GNU General Public License version 3 or later
 */
defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Plugin\PluginHelper;
use Joomla\Registry\Registry;

class Route66ModelInstantArticlesFeed extends JModelAdmin
{
	protected $text_prefix = 'COM_ROUTE66';

	public function getTable($type = 'InstantArticlesFeed', $prefix = 'Route66Table', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}

	public function getForm($data = array(), $loadData = true)
	{
		$form = $this->loadForm('com_route66.instantarticlesfeed', 'instantarticlesfeed', array('control' => 'jform', 'load_data' => false));

		if (empty($form))
		{
			return false;
		}

		PluginHelper::importPlugin('route66');
		$application = Factory::getApplication();
		$application->triggerEvent('onRoute66LoadExtensionForm', array(&$form, 'instantarticles'));

		$data = $this->loadFormData();
		$form->bind($data);

		return $form;
	}

	protected function loadFormData()
	{
		$data = JFactory::getApplication()->getUserState('com_route66.edit.instantarticlesfeed.data', array());

		if (empty($data))
		{
			$data = $this->getItem();
		}

		return $data;
	}

	public function getItem($pk = null)
	{
		$item = parent::getItem($pk);

		if ($item)
		{
			$registry = new Registry();
			$item->sources = $registry->loadString($item->sources);

			$registry = new Registry();
			$item->settings = $registry->loadString($item->settings);
			$item->dfpSlots = array();

			if ($item->settings->get('dfpNetwork') && $item->settings->get('dfpSlots'))
			{
				$dfpSlots = json_decode($item->settings->get('dfpSlots'));

				foreach ($dfpSlots->name as $key => $value)
				{
					$slot = new stdClass();
					$slot->name = $dfpSlots->name[$key];
					$slot->width = $dfpSlots->width[$key];
					$slot->height = $dfpSlots->height[$key];
					$item->dfpSlots[] = $slot;
				}
			}
			$application = JFactory::getApplication();

			if ($application->isClient('site'))
			{
				$item->siteName = $application->get('sitename');
				$item->siteDescription = $application->get('MetaDesc');
				$item->siteLink = JUri::root(false);
				$document = JFactory::getDocument();
				$item->siteLanguage = $document->getLanguage();

				$timezone = new DateTimeZone($application->get('offset'));
				$date = JFactory::getDate();
				$date->setTimeZone($timezone);
				$item->lastBuildDate = $date->toISO8601(true);
			}
		}

		return $item;
	}

	public function getInstantArticlesFeedItems($feed)
	{
		$items = array();
		PluginHelper::importPlugin('route66');
		$application = Factory::getApplication();
		$results = $application->triggerEvent('onRoute66GetInstantArticles', array($feed));

		foreach ($results as $result)
		{
			$items = array_merge($items, $result);
		}

		return $items;
	}
}
