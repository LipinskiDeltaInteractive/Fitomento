<?php
/**
 * @author      Lefteris Kavadas
 * @copyright   Copyright (c) 2016 - 2021 Lefteris Kavadas / firecoders.com
 * @license     GNU General Public License version 3 or later
 */
defined('_JEXEC') or die;

class JFormFieldRoute66SeoAnalysis extends JFormField
{
	public $type = 'Route66SeoAnalysis';

	public function getInput()
	{
		$className = version_compare(JVERSION, '4.0', 'ge') ? 'j4':'j3';
		$html = '<div id="route66-seo-analysis" class="' . $className . '"></div>';

		return $html;
	}
}
