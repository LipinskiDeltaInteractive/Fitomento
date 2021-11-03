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
	var menus = jQuery('#jform_sources_menus').parents('.control-group')
	var menusCategories = jQuery('#jform_sources_menusCategories').parents('.control-group');
	var virtuemart = jQuery('#jform_sources_virtuemart').parents('.control-group')
	var virtuemartCategories = jQuery('#jform_sources_virtuemartCategories').parents('.control-group');
	var hikashop = jQuery('#jform_sources_hikashop').parents('.control-group')
	var hikashopCategories = jQuery('#jform_sources_hikashopCategories').parents('.control-group');
	var typeField = jQuery('select[name="jform[settings][type]"]');
	var type = typeField.val();
	if(type === 'news') {
		menus.slideUp();
		menusCategories.slideUp();
		virtuemart.slideUp();
		virtuemartCategories.slideUp();
		hikashop.slideUp();
		hikashopCategories.slideUp();
	}
	typeField.on('change', function() {
		type = typeField.val();
		if(type === 'news') {
			menus.slideUp();
			menusCategories.slideUp();
			virtuemart.slideUp();
			virtuemartCategories.slideUp();
			hikashop.slideUp();
			hikashopCategories.slideUp();
		} else {
			menus.slideDown();
			menusCategories.slideDown();
			virtuemart.slideDown();
			virtuemartCategories.slideDown();
			hikashop.slideDown();
			hikashopCategories.slideDown();
		}
	});

	Joomla.submitbutton = function(task)
	{
		if (task == 'sitemap.cancel' || document.formvalidator.isValid(document.getElementById('adminForm'))) {
			Joomla.submitform(task, document.getElementById('adminForm'));
		}
	}
});
</script>

<form action="<?php echo JRoute::_('index.php?option=com_route66&view=sitemap&layout=edit&id='.$this->item->id); ?>" method="post" name="adminForm" id="adminForm" class="form-validate form-horizontal">

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

					<?php foreach ($this->form->getGroup('settings') as $field) : ?>
						<?php echo $field->renderField(); ?>
					<?php endforeach; ?>

					<?php foreach ($this->form->getGroup('sources') as $field) : ?>
						<?php echo $field->renderField(); ?>
					<?php endforeach; ?>

					<?php echo HTMLHelper::_($tabs.'.endTab'); ?>

					<?php echo HTMLHelper::_($tabs.'.endTabSet'); ?>

			</div>

	</div>

	<input type="hidden" name="task" value="" />
	<?php echo HTMLHelper::_('form.token'); ?>
	<?php echo Route66HelperHtml::copyrights(); ?>
</form>
