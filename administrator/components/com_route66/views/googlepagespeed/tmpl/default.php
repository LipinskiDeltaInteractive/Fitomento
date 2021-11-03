<?php
/**
 * @author      Lefteris Kavadas
 * @copyright   Copyright (c) 2016 - 2019 Lefteris Kavadas / firecoders.com
 * @license     GNU General Public License version 3 or later
 */
defined('_JEXEC') or die;
?>
<?php if(version_compare(JVERSION, '4', 'lt')): ?>
<div id="j-sidebar-container" class="span2">
		<?php echo $this->sidebar; ?>
</div>
<?php endif; ?>
<div id="j-main-container" class="span10 form-horizontal">
	<p class="lead"><?php echo JText::_('COM_ROUTE66_GETTING_STARTED_MESSAGE_GOOGLE_PAGESPEED'); ?></p>
	<h3><?php echo JText::_('JOPTIONS'); ?></h3>
	<table class="table table-bordered<?php if(version_compare(JVERSION, '4', '<')): ?> table-striped<?php endif; ?>">
			<thead>
				<tr>
					<th width="1%" ><?php echo JText::_('COM_ROUTE66_OPTION'); ?></th>
					<th width="1%" ><?php echo JText::_('JSTATUS'); ?></th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td width="1%" ><?php echo JText::_('COM_ROUTE66_OPTIMIZE_JAVASCRIPT'); ?></td>
					<td width="1%" ><?php echo $this->params->get('optimizeJs') ? '<span class="icon-publish" title="'.JText::_('JENABLED').'"></span>': '<span class="icon-unpublish" title="'.JText::_('JDISABLED').'"></span>'; ?></td>
				</tr>
				<tr>
					<td width="1%" ><?php echo JText::_('COM_ROUTE66_OPTIMIZE_JAVASCRIPT_METHOD'); ?></td>
					<td width="1%" ><?php echo $this->params->get('optimizeJsMethod', 'file') == 'file' ? JText::_('COM_ROUTE66_FILE'): JText::_('COM_ROUTE66_INLINE'); ?></td>
				</tr>
				<tr>
					<td width="1%" ><?php echo JText::_('COM_ROUTE66_OPTIMIZE_CSS'); ?></td>
					<td width="1%" ><?php echo $this->params->get('optimizeCss') ? '<span class="icon-publish" title="'.JText::_('JENABLED').'"></span>': '<span class="icon-unpublish" title="'.JText::_('JDISABLED').'"></span>'; ?></td>
				</tr>
				<tr>
					<td width="1%" ><?php echo JText::_('COM_ROUTE66_MINIFY_CSS'); ?></td>
					<td width="1%" ><?php echo $this->params->get('minifyCss') ? '<span class="icon-publish" title="'.JText::_('JENABLED').'"></span>': '<span class="icon-unpublish" title="'.JText::_('JDISABLED').'"></span>'; ?></td>
				</tr>
				<tr>
					<td width="1%" ><?php echo JText::_('COM_ROUTE66_CACHE_TIME'); ?></td>
					<td width="1%" ><?php echo $this->params->get('optimizeCacheTime'); ?></td>
				</tr>
				<tr>
					<td width="1%" ><?php echo JText::_('COM_ROUTE66_EXCLUSIONS'); ?></td>
					<td width="1%" ><?php echo $this->params->get('optimizeExclusions', array()) ? implode(', ', $this->params->get('optimizeExclusions')) : JText::_('JNONE'); ?></td>
				</tr>
				<tr>
					<td width="1%" ><?php echo JText::_('COM_ROUTE66_DEFER_OFFSCREEN_IMAGES'); ?></td>
					<td width="1%" ><?php echo $this->params->get('lazyloadImages') ? '<span class="icon-publish" title="'.JText::_('JENABLED').'"></span>': '<span class="icon-unpublish" title="'.JText::_('JDISABLED').'"></span>'; ?></td>
				</tr>
				<tr>
					<td width="1%" ><?php echo JText::_('COM_ROUTE66_DEFER_OFFSCREEN_IFRAMES'); ?></td>
					<td width="1%" ><?php echo $this->params->get('lazyloadIframes') ? '<span class="icon-publish" title="'.JText::_('JENABLED').'"></span>': '<span class="icon-unpublish" title="'.JText::_('JDISABLED').'"></span>'; ?></td>
				</tr>
			</tbody>
	</table>
	<?php echo Route66HelperHtml::copyrights(); ?>
</div>
