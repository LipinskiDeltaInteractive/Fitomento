<?php
/**
 * @author      Lefteris Kavadas
 * @copyright   Copyright (c) 2016 - 2021 Lefteris Kavadas / firecoders.com
 * @license     GNU General Public License version 3 or later
 */
defined('_JEXEC') or die;

class Route66HelperHtml
{
	public static function getSidebar($view)
	{
		JHtmlSidebar::addEntry(JText::_('COM_ROUTE66_URLS'), 'index.php?option=com_route66&view=urls', $view == 'urls');
		JHtmlSidebar::addEntry(JText::_('COM_ROUTE66_SEO_SCORES'), 'index.php?option=com_route66&view=seo', $view == 'seo');
		JHtmlSidebar::addEntry(JText::_('COM_ROUTE66_SEO_ANALYSIS'), 'index.php?option=com_route66&view=analysis', $view == 'analysis');
		JHtmlSidebar::addEntry(JText::_('COM_ROUTE66_SITEMAPS'), 'index.php?option=com_route66&view=sitemaps', $view == 'sitemaps');
		JHtmlSidebar::addEntry(JText::_('COM_ROUTE66_FACEBOOK_INSTANT_ARTICLES'), 'index.php?option=com_route66&view=instantarticlesfeeds', $view == 'instantarticlesfeeds');
		JHtmlSidebar::addEntry(JText::_('COM_ROUTE66_GOOGLE_PAGESPEED'), 'index.php?option=com_route66&view=googlepagespeed', $view == 'googlepagespeed');
		JHtmlSidebar::setAction('index.php?option=com_route66&view=' . $view);

		return JHtmlSidebar::render();
	}

	public static function addOptionsButton()
	{
		$user = JFactory::getUser();

		if ($user->authorise('core.admin', 'com_route66') || $user->authorise('core.options', 'com_route66'))
		{
			JToolbarHelper::preferences('com_route66');
		}
	}

	public static function copyrights()
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select($db->qn('manifest_cache'))->from($db->qn('#__extensions'))->where($db->qn('element') . ' = ' . $db->q('pkg_route66'));
		$db->setQuery($query);
		$manifest = json_decode($db->loadResult());
		$date = JFactory::getDate();
		$link = $manifest->name == 'Route 66' ? 'https://extensions.joomla.org/extension/route-66/' : 'https://extensions.joomla.org/extension/route-66-pro/';
		$html = '<div class="text-center help-block"><a target="_blank" href="https://www.firecoders.com/joomla-extensions/route-66">' . $manifest->name . ' v' . $manifest->version . '</a> | Copyright &copy; 2016 - ' . $date->format('Y') . ' <a target="_blank" href="https://www.firecoders.com">Firecoders</a></div>';
		$html .= '<div class="text-center help-block">If you use ' . $manifest->name . ', please post a review at the <a href="' . $link . '" target="_blank">Joomla Extensions Directory</a>.</div>';

		return $html;
	}
}
