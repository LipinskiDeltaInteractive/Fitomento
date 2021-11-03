<?php
/**
 * @author      Lefteris Kavadas
 * @copyright   Copyright (c) 2016 - 2021 Lefteris Kavadas / firecoders.com
 * @license     GNU General Public License version 3 or later
 */
defined('_JEXEC') or die;

use Joomla\CMS\Filesystem\File;

jimport('joomla.application.component.view');

class Route66ViewAnalysis extends JViewLegacy
{
	public function display($tpl = null)
	{
		$this->isPro = File::exists(JPATH_SITE . '/plugins/k2/route66seo/media/js/route66analyzer.js');

		$this->loadHelper('html');
		$this->loadHelper('extension');
		$version = Route66HelperExtension::getVersion();

		JToolBarHelper::title(JText::_('COM_ROUTE66_SEO_ANALYSIS_TITLE'), 'search');
		$toolbar = JToolbar::getInstance('toolbar');
		$toolbar->appendButton('Link', 'arrow-left', 'JTOOLBAR_BACK', 'index.php?option=com_route66&view=seo');

		if ($this->isPro)
		{
			JHtml::_('jquery.framework');
			JHtml::_('behavior.formvalidator');
			$document = JFactory::getDocument();
			$document->addStyleSheet(JUri::root(true) . '/media/route66/css/route66seo.css', array('version' => $version));
			$this->loadLanguage();
			$document->addScript(JUri::root(true).'/media/route66/js/seo/main.min.js', array('version' => $version));
			$document->addScript(JUri::root(true) . '/plugins/k2/route66seo/media/js/route66analyzer.js', array('version' => $version));
			JText::script('ERROR');
			JText::script('COM_ROUTE66_YOU_CAN_USE_ONLY_INTERNAL_URLS');

			$this->form = JForm::getInstance('route66seoanalysis', JPATH_SITE . '/administrator/components/com_route66/forms/route66seoanalysis.xml', array('control' => 'jform'));

			if(version_compare(JVERSION, '4.0', 'ge'))
			{
				$button = '<joomla-toolbar-button><button onclick="Joomla.submitbutton(\'seo.fetchPage\');" class="btn btn-small btn-success"><span class="fa fa-play" title="' . JText::_('COM_ROUTE66_ANALYZE') . '"></span>' . JText::_('COM_ROUTE66_ANALYZE') . '</button></joomla-toolbar-button>';
			}
			else
			{
				$button = '<button onclick="Joomla.submitbutton(\'seo.fetchPage\');" class="btn btn-small btn-success"><span class="icon-play icon-white" title="' . JText::_('COM_ROUTE66_ANALYZE') . '"></span>' . JText::_('COM_ROUTE66_ANALYZE') . '</button>';
			}

			$toolbar->appendButton('Custom', $button, 'generate');
		}
		else
		{
			$application = JFactory::getApplication();
			$application->enqueueMessage(JText::_('COM_ROUTE66_PAGE_CONTENT_ANALYSIS_PRO_FEATURE'), 'warning');
		}

		parent::display($tpl);
	}

	private function loadLanguage()
	{
		$i18n = '';
		$language = JFactory::getLanguage();
		$language->load('com_route66', JPATH_ADMINISTRATOR . '/components/com_route66');
		$tag = $language->getTag();
		$locale = str_replace('-', '_', $tag);

		$path = JPATH_ADMINISTRATOR . '/components/com_route66/lib/translations';
		$files = array('wordpress-seo-' . $locale . '.json');

		if (strpos($locale, '_'))
		{
			$parts = explode('_', $locale);
			$files[] = 'wordpress-seo-' . $parts[0] . '.json';
		}

		foreach ($files as $file)
		{
			if (File::exists($path . '/' . $file))
			{
				$buffer = file_get_contents($path . '/' . $file);
				$buffer = str_replace('wordpress-seo', 'js-text-analysis', $buffer);
				$i18n = json_decode($buffer);

				break;
			}
		}

		$document = JFactory::getDocument();
		$document->addScriptOptions('Route66UrlAnalyzerOptions', array('i18n' => $i18n, 'worker' => JUri::root(false).'media/route66/js/seo/worker.min.js', 'site' => JUri::root(false)));
	}
}
