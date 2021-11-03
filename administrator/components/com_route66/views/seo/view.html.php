<?php
/**
 * @author      Lefteris Kavadas
 * @copyright   Copyright (c) 2016 - 2021 Lefteris Kavadas / firecoders.com
 * @license     GNU General Public License version 3 or later
 */
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

class Route66ViewSeo extends JViewLegacy
{
	protected $items;
	protected $state;
	protected $pagination;

	public function display($tpl = null)
	{
		$this->items = $this->get('Items');
		$this->state = $this->get('State');
		$this->pagination = $this->get('Pagination');
		$this->filterForm = $this->get('FilterForm');
		$this->activeFilters = $this->get('ActiveFilters');

		if (count($errors = $this->get('Errors')))
		{
			JError::raiseError(500, implode("\n", $errors));

			return false;
		}

		JToolBarHelper::title(JText::_('COM_ROUTE66_SEO_SCORES_TITLE'), 'bars');
		$user = JFactory::getUser();

		if ($user->authorise('core.edit', 'com_plugins'))
		{
			if(version_compare(JVERSION, '4.0', 'ge'))
			{
				$customButton = '<joomla-toolbar-button><a class="btn btn-small" href="' . JRoute::_('index.php?option=com_plugins&filter[search]=Route+66+SEO') . '"><span class="fas fa-plug" aria-hidden="true"></span>' . JText::_('COM_ROUTE66_ROUTE66_SEO_PLUGINS') . '</a></joomla-toolbar-button>';
			}
			else
			{
				$customButton = '<a class="btn btn-small" href="' . JRoute::_('index.php?option=com_plugins&filter[search]=Route+66+SEO') . '"><i class="icon-power-cord plugin"></i>' . JText::_('COM_ROUTE66_ROUTE66_SEO_PLUGINS') . '</a>';
			}
			$toolbar = JToolBar::getInstance('toolbar');
			$toolbar->appendButton('Custom', $customButton);
		}

		$this->loadHelper('html');
		$this->sidebar = Route66HelperHtml::getSidebar('seo');
		Route66HelperHtml::addOptionsButton();

		if (version_compare(JVERSION, '4.0', 'lt'))
		{
			JHtml::_('formbehavior.chosen', 'select');
		}

		if (!JPluginHelper::isEnabled('content', 'route66seo') && !JPluginHelper::isEnabled('k2', 'route66seo'))
		{
			$application = JFactory::getApplication();
			$application->enqueueMessage(JText::_('COM_ROUTE66_SEO_PLUGINS_DISABLED_WARNING'), 'warning');
		}

		parent::display($tpl);
	}
}
