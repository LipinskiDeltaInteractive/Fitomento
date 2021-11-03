<?php
/**
 * @author      Lefteris Kavadas
 * @copyright   Copyright (c) 2016 - 2021 Lefteris Kavadas / firecoders.com
 * @license     GNU General Public License version 3 or later
 */
defined('_JEXEC') or die;
?>

<form action="<?php echo JRoute::_('index.php?option=com_route66&view=sitemaps'); ?>" method="post" name="adminForm" id="adminForm">

	<?php if(version_compare(JVERSION, '4', 'lt')): ?>
	<div id="j-sidebar-container" class="span2">
			<?php echo $this->sidebar; ?>
	</div>
	<?php endif; ?>
	<div id="j-main-container" class="span10">

		<?php echo JLayoutHelper::render('joomla.searchtools.default', array('view' => $this)); ?>

		<div class="clearfix"> </div>

		<table class="table<?php if(version_compare(JVERSION, '4', '<')): ?> table-striped<?php endif; ?>" id="sitemapsList">
				<thead>
						<tr>
								<th width="1%" class="center text-center">
									<?php echo JHtml::_('grid.checkall'); ?>
								</th>
								<th width="1%" class="center text-center">
									<?php echo JText::_('JGLOBAL_PREVIEW'); ?>
								</th>
								<th width="1%" class="nowrap center text-center">
									<?php echo JHtml::_('searchtools.sort', 'JSTATUS', 'state', $this->escape($this->state->get('list.direction')), $this->escape($this->state->get('list.ordering'))); ?>
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
									<?php echo JHtml::_('grid.id', $i, $item->id); ?>
								</td>
								<td class="center text-center">
									<a class="btn btn-micro" href="<?php echo $item->previewLink;?>" target="_blank"><span class="icon-feed"></span></a>
								</td>
								<td class="center text-center">
									<?php echo JHtml::_('jgrid.published', $item->state, $i, 'sitemaps.', true, 'cb'); ?>
								</td>
								<td>
										<a class="hasTooltip" href="<?php echo $item->editLink; ?>" title="<?php echo JText::_('JACTION_EDIT'); ?>"><?php echo $this->escape($item->title); ?></a>
										<div class="small"><?php echo $item->previewLink; ?></div>
								</td>
								<td class="small center text-center hidden-phone">
										<?php echo $item->id; ?>
								</td>
						</tr>
						<?php endforeach; ?>
				</tbody>
				<tfoot>
						<tr>
								<td colspan="5">
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
