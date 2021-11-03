<?php
/**
 * @author      Lefteris Kavadas
 * @copyright   Copyright (c) 2016 - 2021 Lefteris Kavadas / firecoders.com
 * @license     GNU General Public License version 3 or later
 */
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

class Route66ViewGooglePageSpeed extends JViewLegacy
{
	public function display($tpl = null)
	{
		$application = JFactory::getApplication();

		$this->loadHelper('html');
		$this->loadHelper('extension');
		$this->sidebar = Route66HelperHtml::getSidebar('googlepagespeed');

		JToolBarHelper::title(JText::_('COM_ROUTE66_GOOGLE_PAGESPEED'), 'dashboard');
		$toolbar = JToolBar::getInstance('toolbar');

		if (version_compare(JVERSION, '4', '>='))
		{
			$customButton = '<joomla-toolbar-button><a class="btn btn-primary" href="https://developers.google.com/speed/pagespeed/insights/?url=' . urlencode(JUri::root(false)) . '"><span class="fa fa-link" aria-hidden="true"></span>' . JText::_('COM_ROUTE66_ANALYZE_YOUR_SITE') . '</a></joomla-toolbar-button>';
		}
		else
		{
			$customButton = '<a class="btn btn-small" target="_blank" href="https://developers.google.com/speed/pagespeed/insights/?url=' . urlencode(JUri::root(false)) . '"><i class="icon-new-tab"></i>' . JText::_('COM_ROUTE66_ANALYZE_YOUR_SITE') . '</a>';
		}
		$toolbar->appendButton('Custom', $customButton);

		JToolBarHelper::help(null, false, 'https://www.firecoders.com/documentation/route-66');
		Route66HelperHtml::addOptionsButton();

		if (JPluginHelper::isEnabled('system', 'route66pagespeed'))
		{
			Route66HelperExtension::fixPluginsOrdering();
		}
		else
		{
			$application->enqueueMessage(JText::_('COM_ROUTE66_PAGESPEED_PLUGIN_DISABLED_WARNING'), 'warning');
		}

		$this->params = JComponentHelper::getParams('com_route66');

		parent::display($tpl);
	}
}
