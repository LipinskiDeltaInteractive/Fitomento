<?php
/**
 * @author      Lefteris Kavadas
 * @copyright   Copyright (c) 2016 - 2021 Lefteris Kavadas / firecoders.com
 * @license     GNU General Public License version 3 or later
 */
defined('_JEXEC') or die;

JFormHelper::loadFieldClass('radio');

class JFormFieldFcRadio extends JFormFieldRadio
{
	public $type = 'FcRadio';

	public function getInput()
	{
		if (version_compare(JVERSION, '4.0', 'lt'))
		{
			$this->layout = 'joomla.form.field.radio';
		}

		return parent::getInput();
	}
}
