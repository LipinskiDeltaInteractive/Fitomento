<?php
/**
 * @author      Lefteris Kavadas
 * @copyright   Copyright (c) 2016 - 2021 Lefteris Kavadas / firecoders.com
 * @license     GNU General Public License version 3 or later
 */
defined('_JEXEC') or die;
?>
<?php if($this->isPro): ?>
<script type="text/javascript">
window.addEventListener('DOMContentLoaded', function(event) {
	Route66UrlAnalyzer.start();
	Joomla.submitbutton = function(task){
		if(!document.formvalidator.isValid(document.getElementById('adminForm'))) {
			return false;
		}
		Route66UrlAnalyzer.fetchPage();
	}
});
</script>
<?php endif; ?>
<form action="<?php echo JRoute::_('index.php'); ?>" method="post" name="adminForm" id="adminForm" class="form form-horizontal">
		<div id="j-main-container"<?php if(version_compare(JVERSION, '4', '>=')): ?> class="bg-white p-4"<?php endif; ?>>
			<?php if($this->isPro): ?>
			<?php echo $this->form->renderField('url', 'route66seo'); ?>
			<?php echo $this->form->renderField('keyword', 'route66seo'); ?>
			<div id="route66-seo-analysis-results-container">
				<?php echo $this->form->renderField('preview', 'route66seo'); ?>
				<?php echo $this->form->renderField('score', 'route66seo'); ?>
				<?php echo $this->form->renderField('analysis', 'route66seo'); ?>
			</div>
			<?php else: ?>
				<img src="<?php echo JUri::root(true) ;?>/media/route66/images/seo-analysis-screenshot.jpg" class="img-polaroid"/>
			<?php endif; ?>
			<input type="hidden" name="task" value="" />
			<?php echo JHtml::_('form.token'); ?>
			<?php echo Route66HelperHtml::copyrights(); ?>
		</div>
</form>
