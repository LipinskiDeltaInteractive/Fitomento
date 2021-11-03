<?php
/**
 * @author      Lefteris Kavadas
 * @copyright   Copyright (c) 2016 - 2021 Lefteris Kavadas / firecoders.com
 * @license     GNU General Public License version 3 or later
 */
defined('_JEXEC') or die;

use Joomla\CMS\Factory;

class Route66ViewSitemap extends JViewLegacy
{
	protected $form;
	protected $state;
	protected $item;

	public function display($tpl = null)
	{
		$this->form = $this->get('Form');
		$this->item = $this->get('Item');
		$this->state = $this->get('State');
		$this->params = JComponentHelper::getParams('com_route66');

		if (count($errors = $this->get('Errors')))
		{
			JError::raiseError(500, implode("\n", $errors));

			return false;
		}

		$this->loadHelper('html');

		JToolBarHelper::title(JText::_('COM_ROUTE66_SITEMAP_TITLE'), 'tree-2');
		Factory::getApplication()->input->set('hidemainmenu', true);
		JToolBarHelper::apply('sitemap.apply');
		JToolBarHelper::save('sitemap.save');
		JToolbarHelper::save2copy('sitemap.save2copy');
		JToolBarHelper::cancel('sitemap.cancel');

		JHtml::_('jquery.framework');
		JHtml::_('behavior.formvalidator');
		JHtml::_('behavior.keepalive');

		if (version_compare(JVERSION, '4.0', 'lt'))
		{
			JHtml::_('behavior.tooltip');
			JHtml::_('formbehavior.chosen', 'select');
		}

		parent::display($tpl);
	}
}
