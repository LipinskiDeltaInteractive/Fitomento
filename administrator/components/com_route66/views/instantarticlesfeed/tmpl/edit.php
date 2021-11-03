<?php
/**
 * @author      Lefteris Kavadas
 * @copyright   Copyright (c) 2016 - 2021 Lefteris Kavadas / firecoders.com
 * @license     GNU General Public License version 3 or later
 */
defined('_JEXEC') or die;

use Joomla\CMS\HTML\HTMLHelper;

$tabs = version_compare(JVERSION, '4.0', 'ge') ? 'uitab' : 'bootstrap';
?>

<script type="text/javascript">
window.addEventListener('DOMContentLoaded', function(event) {
	Joomla.submitbutton = function(task)
	{
		if (task == 'instantarticlesfeed.cancel' || document.formvalidator.isValid(document.getElementById('adminForm'))) {
			Joomla.submitform(task, document.getElementById('adminForm'));
		}
	}
});
</script>

<form action="<?php echo JRoute::_('index.php?option=com_route66&view=instantarticlesfeed&layout=edit&id='.$this->item->id); ?>" method="post" name="adminForm" id="adminForm" class="form-validate form-horizontal">

	<div class="row-fluid">

			<div class="span12">

				 <?php echo HTMLHelper::_($tabs.'.startTabSet', 'route66Tabs', array('active' => 'details')); ?>

				 <?php echo HTMLHelper::_($tabs.'.addTab', 'route66Tabs', 'details', JText::_('COM_ROUTE66_DETAILS')); ?>

					<div class="control-group">
						<div class="control-label"><?php echo $this->form->getLabel('title'); ?></div>
						<div class="controls">
							<?php echo $this->form->getInput('title'); ?>
						</div>
					</div>

					<div class="control-group">
						<div class="control-label"><?php echo $this->form->getLabel('state'); ?></div>
						<div class="controls">
							<?php echo $this->form->getInput('state'); ?>
						</div>
					</div>

					<?php foreach ($this->form->getGroup('sources') as $field) : ?>
						<?php echo $field->renderField(); ?>
					<?php endforeach; ?>

					<?php echo HTMLHelper::_($tabs.'.endTab'); ?>

					<?php echo HTMLHelper::_($tabs.'.addTab', 'route66Tabs', 'analytics', JText::_('COM_ROUTE66_GOOGLE_ANALYTICS')); ?>
					<div class="control-group">
						<div class="control-label"><?php echo $this->form->getLabel('gaTrackingId', 'settings'); ?></div>
						<div class="controls">
							<?php echo $this->form->getInput('gaTrackingId', 'settings'); ?>
						</div>
					</div>
					<div class="control-group">
						<div class="control-label"><?php echo $this->form->getLabel('gaCampaignSource', 'settings'); ?></div>
						<div class="controls">
							<?php echo $this->form->getInput('gaCampaignSource', 'settings'); ?>
						</div>
					</div>
					<div class="control-group">
						<div class="control-label"><?php echo $this->form->getLabel('gaCampaignMedium', 'settings'); ?></div>
						<div class="controls">
							<?php echo $this->form->getInput('gaCampaignMedium', 'settings'); ?>
						</div>
					</div>
					<?php echo HTMLHelper::_($tabs.'.endTab'); ?>

					<?php echo HTMLHelper::_($tabs.'.addTab', 'route66Tabs', 'ads', JText::_('COM_ROUTE66_GOOGLE_DFP')); ?>
					<div class="control-group">
						<div class="control-label"><?php echo $this->form->getLabel('dfpNetwork', 'settings'); ?></div>
						<div class="controls">
							<?php echo $this->form->getInput('dfpNetwork', 'settings'); ?>
						</div>
					</div>
					<div class="control-group">
						<div class="control-label"><?php echo $this->form->getLabel('dfpSlots', 'settings'); ?></div>
						<div class="controls">
							<?php echo $this->form->getInput('dfpSlots', 'settings'); ?>
						</div>
					</div>
					<?php echo HTMLHelper::_($tabs.'.endTab'); ?>

					<?php echo HTMLHelper::_($tabs.'.endTabSet'); ?>

			</div>

	</div>

	<input type="hidden" name="task" value="" />
	<?php echo HTMLHelper::_('form.token'); ?>
	<?php echo Route66HelperHtml::copyrights(); ?>

</form>
