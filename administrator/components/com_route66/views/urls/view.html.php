<?php
/**
 * @author      Lefteris Kavadas
 * @copyright   Copyright (c) 2016 - 2021 Lefteris Kavadas / firecoders.com
 * @license     GNU General Public License version 3 or later
 */
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

class Route66ViewUrls extends JViewLegacy
{
	public function display($tpl = null)
	{
		$application = JFactory::getApplication();
		$language = JFactory::getLanguage();
		$this->items = array();
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('*')->from($db->qn('#__extensions'))->where($db->qn('type') . ' = ' . $db->q('plugin'))->where($db->qn('folder') . ' = ' . $db->q('route66'));
		$db->setQuery($query);
		$extensions = $db->loadObjectList();

		foreach ($extensions as $extension)
		{
			$language->load('plg_route66_' . $extension->element . '.sys', JPATH_SITE . '/plugins/route66/' . $extension->element);
			$extension->form = new JForm($extension->name);
			$extension->form->loadFile(JPATH_SITE . '/plugins/route66/' . $extension->element . '/' . $extension->element . '.xml', false, '//config');
			$params = (array) json_decode($extension->params);

			foreach ($params as $key => $param)
			{
				$params[$key] = (array) $param;
			}
			$data = array('params' => $params);
			$extension->form->bind($data);
			$extension->rules = array();

			foreach ($extension->form->getFieldset() as $field)
			{
				if ($field->type == 'Route66Pattern')
				{
					$field->readonly = true;
					$field->disabled = true;
					$rule = new stdClass();
					$rule->label = JText::_($extension->form->getFieldAttribute($field->fieldname, 'label', null, 'params'));
					$rule->input = $field->input;
					$extension->rules[] = $rule;
				}
			}

			if (count($extension->rules))
			{
				$this->items[] = $extension;
			}
		}
		$this->canEditPlugins = JFactory::getUser()->authorise('core.edit', 'com_plugins');
		$document = JFactory::getDocument();
		$document->addStyleDeclaration('#patternsList .muted { display: none;} #patternsList .control-group { margin: 4px 0; } #patternsList td {vertical-align: middle} #patternsList input {cursor: unset;box-shadow: none;}');

		JToolBarHelper::title(JText::_('COM_ROUTE66_URLS_TITLE'), 'link');

		if ($this->canEditPlugins)
		{
			if(version_compare(JVERSION, '4.0', 'ge'))
			{
				$customButton = '<joomla-toolbar-button><a class="btn btn-small" href="' . JRoute::_('index.php?option=com_plugins&filter[folder]=route66') . '"><span class="fas fa-plug" aria-hidden="true"></span>' . JText::_('COM_ROUTE66_ROUTE66_PLUGINS') . '</a></joomla-toolbar-button>';
			}
			else
			{
				$customButton = '<a class="btn btn-small" href="' . JRoute::_('index.php?option=com_plugins&filter[folder]=route66') . '"><i class="icon-power-cord plugin"></i>' . JText::_('COM_ROUTE66_ROUTE66_PLUGINS') . '</a>';
			}
			$toolbar = JToolBar::getInstance('toolbar');
			$toolbar->appendButton('Custom', $customButton);
		}
		$this->loadHelper('html');
		$this->loadHelper('extension');
		$this->sidebar = Route66HelperHtml::getSidebar('urls');
		Route66HelperHtml::addOptionsButton();
		JToolBarHelper::help(null, false, 'https://www.firecoders.com/documentation/route-66');

		if (!JPluginHelper::isEnabled('system', 'route66'))
		{
			$application->enqueueMessage(JText::_('COM_ROUTE66_SYSTEM_PLUGIN_DISABLED_WARNING'), 'warning');
		}
		else
		{
			Route66HelperExtension::fixPluginsOrdering();
		}
		$this->params = JComponentHelper::getParams('com_route66');

		parent::display($tpl);
	}
}
