<?php
/**
 * @author      Lefteris Kavadas
 * @copyright   Copyright (c) 2016 - 2021 Lefteris Kavadas / firecoders.com
 * @license     GNU General Public License version 3 or later
 */
defined('_JEXEC') or die;

use Joomla\CMS\Factory;

class Route66ViewInstantArticlesFeed extends JViewLegacy
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

		JToolBarHelper::title(JText::_('COM_ROUTE66_FACEBOOK_INSTANT_ARTICLES_FEED_TITLE'), 'lightning');
		Factory::getApplication()->input->set('hidemainmenu', true);

		JToolBarHelper::apply('instantarticlesfeed.apply');
		JToolBarHelper::save('instantarticlesfeed.save');
		JToolbarHelper::save2copy('instantarticlesfeed.save2copy');
		JToolBarHelper::cancel('instantarticlesfeed.cancel');

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
