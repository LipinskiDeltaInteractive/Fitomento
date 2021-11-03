<?php
/**
 * @author      Lefteris Kavadas
 * @copyright   Copyright (c) 2016 - 2021 Lefteris Kavadas / firecoders.com
 * @license     GNU General Public License version 3 or later
 */
defined('_JEXEC') or die;
?>

<form action="<?php echo JRoute::_('index.php?option=com_route66&view=seo'); ?>" method="post" name="adminForm" id="adminForm">

	<?php if(version_compare(JVERSION, '4', 'lt')): ?>
	<div id="j-sidebar-container" class="span2">
			<?php echo $this->sidebar; ?>
	</div>
	<?php endif; ?>
	<div id="j-main-container" class="span10">

		<?php echo JLayoutHelper::render('joomla.searchtools.default', array('view' => $this)); ?>

		<div class="clearfix"> </div>

		<table class="table<?php if(version_compare(JVERSION, '4', '<')): ?> table-striped<?php endif; ?>" id="seoList">
				<thead>
						<tr>
								<th width="1%" class="nowrap center text-center">
									<?php echo JHtml::_('searchtools.sort', 'COM_ROUTE66_SCORE', 'score', $this->escape($this->state->get('list.direction')), $this->escape($this->state->get('list.ordering'))); ?>
								</th>
								<th width="1%" class="nowrap">
									<?php echo JText::_('COM_ROUTE66_FOCUS_KEYWORD'); ?>
								</th>
								<th>
										<?php echo JHtml::_('searchtools.sort', 'JGLOBAL_TITLE', 'title', $this->escape($this->state->get('list.direction')), $this->escape($this->state->get('list.ordering'))); ?>
								</th>
								<th width="1%" class="nowrap hidden-phone">
										<?php echo JHtml::_('searchtools.sort', 'JGRID_HEADING_ID', 'id', $this->escape($this->state->get('list.direction')), $this->escape($this->state->get('list.ordering'))); ?>
								</th>
						</tr>
				</thead>
				<tbody>
				<?php foreach ($this->items as $i => $item): ?>
						<tr class="row<?php echo $i % 2; ?>">
								<td class="center text-center">
									<?php if($item->keyword): ?>
									<span class="badge badge-<?php echo $item->badgeClass; ?> bg-<?php echo $item->badgeClass; ?>"><?php echo $item->score; ?></span>
									<?php else: ?>
									<span class="badge badge-light bg-secondary"><?php echo JText::_('COM_ROUTE66_NA'); ?></span>
									<?php endif; ?>
								</td>
								<td class="nowrap"><?php echo $item->keyword; ?></td>
								<td>
										<a class="hasTooltip" href="<?php echo $item->editLink; ?>" title="<?php echo JText::_('JACTION_EDIT'); ?>"><?php echo $this->escape($item->title); ?></a>
								</td>
								<td class="small center hidden-phone">
										<?php echo $item->id; ?>
								</td>
						</tr>
						<?php endforeach; ?>
				</tbody>
				<tfoot>
						<tr>
								<td colspan="4">
										<?php echo $this->pagination->getListFooter(); ?>
								</td>
						</tr>
				</tfoot>
		</table>
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="boxchecked" value="0" />
		<?php echo JHtml::_('form.token'); ?>
		<?php echo Route66HelperHtml::copyrights(); ?>
		</div>
</form>
