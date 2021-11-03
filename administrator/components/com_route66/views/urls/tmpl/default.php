<?php
/**
 * @author      Lefteris Kavadas
 * @copyright   Copyright (c) 2016 - 2021 Lefteris Kavadas / firecoders.com
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
	<p class="lead"><?php echo JText::_('COM_ROUTE66_GETTING_STARTED_MESSAGE'); ?></p>
	<h3><?php echo JText::_('COM_ROUTE66_PATTERNS'); ?></h3>
	<table class="table table-bordered<?php if(version_compare(JVERSION, '4', '<')): ?> table-striped<?php endif; ?>" id="patternsList">
			<thead>
				<tr>
					<th width="1%" class="center text-center"><?php echo JText::_('COM_ROUTE66_COMPONENT'); ?></th>
					<th width="1%" class="center text-center"><?php echo JText::_('JSTATUS'); ?></th>
					<th class="center"><?php echo JText::_('JOPTIONS'); ?></th>
					<th><?php echo JText::_('COM_ROUTE66_PATTERNS'); ?></th>
			</thead>
			<tbody>
				<?php foreach ($this->items as $i => $item): ?>
				<tr>
					<td class="center text-center">
						<?php echo ucfirst($item->element); ?>
					</td>
					<td class="center text-center">
						<?php if($item->enabled): ?>
							<span class="icon-publish" title="<?php echo JText::_('JENABLED'); ?>"></span>
						<?php else: ?>
							<span class="icon-unpublish" title="<?php echo JText::_('JDISABLED'); ?>"></span>
						<?php endif; ?>
					</td>
					<td class="center text-center">
						<?php if($this->canEditPlugins): ?>
						<a class="btn btn-small" href="<?php echo JRoute::_('index.php?option=com_plugins&task=plugin.edit&extension_id='.$item->extension_id); ?>"><span class="icon-options"></span></a>
						<?php endif; ?>
					</td>
					<td class="nowrap">
					<?php foreach ($item->rules as $rule): ?>
					<div class="control-group">
					<div class="control-label"><?php echo $rule->label; ?></div><div class="controls"><?php echo $rule->input; ?></div>
					</div>
					<?php endforeach; ?>
					</td>
				</tr>
				<?php endforeach; ?>
			</tbody>
	</table>
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
					<td width="1%" ><?php echo JText::_('COM_ROUTE66_CANONICAL_LINKS'); ?></td>
					<td width="1%" ><?php echo $this->params->get('canonical', 1) ? '<span class="icon-publish" title="'.JText::_('JENABLED').'"></span>': '<span class="icon-unpublish" title="'.JText::_('JDISABLED').'"></span>'; ?></td>
				</tr>
				<tr>
					<td width="1%" ><?php echo JText::_('COM_ROUTE66_REDIRECTS'); ?></td>
					<td width="1%" ><?php echo $this->params->get('redirect', 1) ? '<span class="icon-publish" title="'.JText::_('JENABLED').'"></span>': '<span class="icon-unpublish" title="'.JText::_('JDISABLED').'"></span>'; ?></td>
				</tr>
				<tr>
					<td width="1%" ><?php echo JText::_('COM_ROUTE66_EXCLUSIONS'); ?></td>
					<td width="1%" ><?php echo $this->params->get('exclusions', array()) ? implode(', ', $this->params->get('exclusions')) : JText::_('JNONE'); ?></td>
				</tr>
				<tr>
					<td width="1%" ><?php echo JText::_('COM_ROUTE66_ADD_SUFFIX_TO_MENU_LINKS'); ?></td>
					<td width="1%" ><?php echo $this->params->get('menuLinksSuffix') ? '<span class="icon-publish" title="'.JText::_('JENABLED').'"></span>': '<span class="icon-unpublish" title="'.JText::_('JDISABLED').'"></span>'; ?></td>
				</tr>
				<tr>
					<td width="1%" ><?php echo JText::_('COM_ROUTE66_ADD_TRAILING_SLASH_TO_MENU_LINKS'); ?></td>
					<td width="1%" ><?php echo $this->params->get('menuLinksTrailingSlash') ? '<span class="icon-publish" title="'.JText::_('JENABLED').'"></span>': '<span class="icon-unpublish" title="'.JText::_('JDISABLED').'"></span>'; ?></td>
				</tr>
			</tbody>
	</table>
	<?php echo Route66HelperHtml::copyrights(); ?>
</div>
