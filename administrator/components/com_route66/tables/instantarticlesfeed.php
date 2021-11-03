<?php
/**
 * @author      Lefteris Kavadas
 * @copyright   Copyright (c) 2016 - 2021 Lefteris Kavadas / firecoders.com
 * @license     GNU General Public License version 3 or later
 */
defined('_JEXEC') or die;

class Route66TableInstantArticlesFeed extends JTable
{
	protected $_jsonEncode = array('sources', 'settings');

	public function __construct($db)
	{
		parent::__construct('#__route66_instant_articles_feeds', 'id', $db);
		$this->setColumnAlias('published', 'state');
	}
}
