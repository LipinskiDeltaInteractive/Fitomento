<?php
/**
 * @author      Lefteris Kavadas
 * @copyright   Copyright (c) 2016 - 2021 Lefteris Kavadas / firecoders.com
 * @license     GNU General Public License version 3 or later
 */
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

class Route66ViewSitemaps extends JViewLegacy
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

		JToolBarHelper::title(JText::_('COM_ROUTE66_SITEMAPS_TITLE'), 'tree-2');
		JToolBarHelper::addNew('sitemap.add');
		JToolbarHelper::publish('sitemaps.publish', 'JTOOLBAR_PUBLISH', true);
		JToolbarHelper::unpublish('sitemaps.unpublish', 'JTOOLBAR_UNPUBLISH', true);
		JToolBarHelper::deleteList('JGLOBAL_CONFIRM_DELETE', 'sitemaps.delete');

		$this->loadHelper('html');
		$this->sidebar = Route66HelperHtml::getSidebar('sitemaps');
		Route66HelperHtml::addOptionsButton();

		if (version_compare(JVERSION, '4.0', 'lt'))
		{
			JHtml::_('formbehavior.chosen', 'select');
		}
		else
		{
			$document = JFactory::getDocument();
			$document->addStyleDeclaration('body.com_route66.view-sitemaps a[target="_blank"]::before {content: "";}');
		}

		parent::display($tpl);
	}
}
