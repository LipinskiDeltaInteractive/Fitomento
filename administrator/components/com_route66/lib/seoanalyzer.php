<?php
/**
 * @author      Lefteris Kavadas
 * @copyright   Copyright (c) 2016 - 2021 Lefteris Kavadas / firecoders.com
 * @license     GNU General Public License version 3 or later
 */
defined('_JEXEC') or die;

use Joomla\CMS\Filesystem\File;
use Joomla\CMS\Application\CMSApplication;

class Route66SeoAnalyzer
{
	private $message = null;
	private $data = array();
	private $options = array();
	private $menu = null;
	private $position = 'form';
	private $i18n = null;

	public function __construct($data, $options, $position = 'form')
	{
		$this->loadLanguage();

		$this->setData($data);

		$this->setPosition($position);

		$this->setOptions($options);

		$this->setMenu();

		$this->setUrlOption();

		$this->setOverridesOptions();

		$this->setMessage();

		$this->loadAssets();
	}

	private function loadLanguage()
	{
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
				$this->i18n = json_decode($buffer);

				break;
			}
		}
	}

	private function setData($data)
	{
		$this->data = $data;
	}

	private function setPosition($position)
	{
		$application = JFactory::getApplication();

		if ($application->isClient('site'))
		{
			$position = 'form';
		}
		elseif ($position === 'sidebar')
		{
			$db = JFactory::getDbo();
			$query = $db->getQuery(true);
			$query->update($db->qn('#__modules'));
			$query->set($db->qn('published') . ' = 1');
			$query->set($db->qn('position') . ' = ' . $db->q('submenu'));
			$query->where($db->qn('module') . ' = ' . $db->q('mod_route66seo'));
			$query->where($db->qn('client_id') . ' = 1');
			$db->setQuery($query);
			$db->execute();
		}
		$this->position = $position;
	}

	private function setOptions($extend)
	{
		$application = JFactory::getApplication();
		$this->options['site'] = JUri::root(false);
		$this->options['sitename'] = $application->get('sitename');
		$this->options['sitename_in_title'] = $application->get('sitename_pagetitles');
		$this->options['editor'] = $application->get('editor');
		$this->options['url'] = '';
		$this->options['aliasToken'] = '';
		$this->options['overrides'] = array();
		$this->options['fields'] = array();
		$this->options['split'] = false;
		$this->options = array_merge($this->options, $extend);

		if (!isset($this->options['fields']['keyword']))
		{
			$this->options['fields']['keyword'] = '#jform_route66seo_keyword';
		}

		if (!isset($this->options['fields']['score']))
		{
			$this->options['fields']['score'] = '#jform_route66seo_score';
		}

		if ($this->position == 'toolbar' || $this->position == 'sidebar')
		{
			$this->options['scoreSize'] = 80;
		}
		$this->options['position'] = $this->position;
		$this->options['keywordValue'] = isset($this->data['keyword']) ? $this->data['keyword'] : '';
		$this->options['scoreValue'] = isset($this->data['score']) ? $this->data['score'] : 0;
		$this->options['i18n'] = $this->i18n;
		$this->options['worker'] = JUri::root(false) . 'media/route66/js/seo/worker.min.js';
		$this->options['language'] = str_replace('-', '_', $this->options['language']);
	}

	private function setMenu()
	{
		$option = $this->options['option'];
		$view = $this->options['view'];
		$id = $this->options['id'];

		$application = CMSApplication::getInstance('site');
		$menu = $application->getMenu();
		$component = JComponentHelper::getComponent($option);
		$conditions = array('component_id', 'language');
		$values = array($component->id);
		$languages = JLanguageHelper::getLanguages('lang_code');
		$values[] = array_merge(array('*'), array_keys($languages));
		$items = $menu->getItems($conditions, $values);
		$match = null;

		foreach ($items as $item)
		{
			if (isset($item->query['view']) && $item->query['view'] == $view && isset($item->query['id']) && $item->query['id'] == $id)
			{
				$match = $item;

				break;
			}
		}
		$this->menu = $match;

		if ($this->menu)
		{
			$this->menu->options = $this->menu->getParams();
		}
	}

	private function setUrlOption()
	{
		if ($this->menu)
		{
			$application = JFactory::getApplication();
			$this->options['url'] = $application->get('sef_rewrite') ? $this->menu->route : 'index.php/' . $this->menu->route;
		}
		else
		{
			$this->options['url'] = $this->getUrlFromRoute($this->options['route']);
		}
	}

	private function setOverridesOptions()
	{
		if ($this->menu)
		{
			$this->options['overrides']['title'] = $this->menu->options->get('page_title', $this->menu->title);
			$description = $this->menu->options->get('menu-meta_description');

			if ($description)
			{
				$this->options['overrides']['description'] = $description;
			}
		}
	}

	private function setMessage()
	{
		if ($this->menu)
		{
			$link = JRoute::_('index.php?option=com_menus&task=item.edit&id=' . $this->menu->id);
			$this->message = JText::sprintf('COM_ROUTE66_MENU_WARNING', $link);

			if ($this->menu->options->get('menu-meta_description'))
			{
				$this->message = JText::sprintf('COM_ROUTE66_MENU_DESCRIPTION_WARNING', $link);
			}
		}
	}

	private static function getUrlFromRoute($route)
	{
		$site = CMSApplication::getInstance('site');
		$router = $site->getRouter('site');

		if (version_compare(JVERSION, '4', 'lt'))
		{
			$router->setMode(JROUTER_MODE_SEF);
		}
		$uri = $router->build($route);
		$url = $uri->toString(array('path', 'query', 'fragment'));
		$url = substr($url, strlen(JUri::root(true)) + 1);

		return (string) $url;
	}

	private function loadAssets()
	{
		require_once JPATH_ADMINISTRATOR . '/components/com_route66/helpers/extension.php';
		$version = Route66HelperExtension::getVersion();

		JHtml::_('jquery.framework');
		$document = JFactory::getDocument();
		$document->addStyleSheet(JUri::root(true) . '/media/route66/css/route66seo.css', array('version' => $version));
		$document->addScriptOptions('Route66SeoAnalyzerOptions', $this->options);
		$document->addScript(JUri::root(true) . '/media/route66/js/seo/main.min.js', array('version' => $version));
		$document->addScript(JUri::root(true) . '/media/route66/js/route66seo.js', array('version' => $version));
	}

	private function displayMessage(&$form)
	{
		if ($this->message)
		{
			$form->setFieldAttribute('message', 'title', 'NOTICE', 'route66seo');
			$form->setFieldAttribute('message', 'description', $this->message, 'route66seo');
			$form->setFieldAttribute('message', 'class', 'alert alert-warning', 'route66seo');
			$form->setFieldAttribute('message', 'type', 'note', 'route66seo');
		}
	}

	public function render(&$form = null)
	{
		if ($this->position == 'form')
		{
			if ($form)
			{
				$this->attachToForm($form);

				return true;
			}
			else
			{
				return $this->renderForm();
			}
		}
		elseif ($this->position == 'toolbar')
		{
			return $this->renderInToolbar();
		}
		elseif ($this->position == 'sidebar')
		{
			return $this->renderInSidebar();
		}
	}

	public function renderForm()
	{
		$form = JForm::getInstance('route66seo', JPATH_ADMINISTRATOR . '/components/com_route66/forms/route66seo.xml', array('control' => 'jform'));
		$data = array(array('route66seo' => $this->data));
		$form->bind($data);
		$this->displayMessage($form);

		return $form->renderFieldSet('route66seo');
	}

	private function attachToForm(&$form)
	{
		JForm::addFormPath(JPATH_ADMINISTRATOR . '/components/com_route66/forms');
		$form->loadFile('route66seo', false);
		$this->displayMessage($form);
	}

	private function renderInToolbar()
	{
		$form = JForm::getInstance('route66seo', JPATH_ADMINISTRATOR . '/components/com_route66/forms/route66seo.xml', array('control' => 'jform'));
		$data = array(array('route66seo' => $this->data));
		$form->bind($data);

		$form->setFieldAttribute('preview', 'hiddenLabel', 'true', 'route66seo');
		$form->setFieldAttribute('score', 'hiddenLabel', 'true', 'route66seo');
		$form->setFieldAttribute('analysis', 'hiddenLabel', 'true', 'route66seo');
		$form->setFieldAttribute('keyword', 'hiddenLabel', 'true', 'route66seo');
		$form->setFieldAttribute('keyword', 'hint', 'COM_ROUTE66_FOCUS_KEYWORD_LABEL', 'route66seo');
		$form->setFieldAttribute('keyword', 'class', 'input-block-level', 'route66seo');
		$this->displayMessage($form);

		$className = version_compare(JVERSION, '4', 'lt') ? 'j3' : 'j4';

		$html = '<div id="route66-seo-dropdown-container" class="' . $className . '"><a id="route66-seo-dropdown-button" class="btn"><span class="icon-arrow-down"></span>' . JText::_("COM_ROUTE66_SEO_BADGE") . '</a><div id="route66-seo-dropdown"><form>' . $form->renderFieldSet('route66seo') . '</form></div></div><div id="route66-seo-dropdown-overlay"></div>';
		$toolbar = JToolBar::getInstance('toolbar');
		$toolbar->appendButton('Custom', $html);
	}

	private function renderInSidebar()
	{
		$form = JForm::getInstance('route66seo', JPATH_ADMINISTRATOR . '/components/com_route66/forms/route66seo.xml', array('control' => 'jform'));
		$data = array(array('route66seo' => $this->data));
		$form->bind($data);

		$form->removeField('preview', 'route66seo');
		$form->setFieldAttribute('score', 'hiddenLabel', 'true', 'route66seo');
		$form->setFieldAttribute('analysis', 'hiddenLabel', 'true', 'route66seo');
		$form->setFieldAttribute('keyword', 'hiddenLabel', 'true', 'route66seo');
		$form->setFieldAttribute('keyword', 'hint', 'COM_ROUTE66_FOCUS_KEYWORD_LABEL', 'route66seo');
		$form->setFieldAttribute('keyword', 'class', 'input-block-level', 'route66seo');
		$this->displayMessage($form);

		define('ROUTE66_SEO_MODULE', '<div id="route66-seo-sidebar">' . $form->renderFieldSet('route66seo') . '</div>');
	}
}
