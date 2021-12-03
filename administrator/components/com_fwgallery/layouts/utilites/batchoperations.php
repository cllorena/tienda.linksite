<?php
/**
 * FW Gallery 6.7.2
 * @copyright (C) 2020 Fastw3b
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.fastw3b.net/ Official website
 **/

defined('_JEXEC') or die('Restricted access');

$view = $displayData['view'];
?>
<div class="modal fade" id="fwmg-batch-operations">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title"><?php echo JText::_('FWMG_BATCH_OPERATIONS'); ?></h5>
				<button type="button" class="close" data-dismiss="modal" data-bs-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form action="index.php?option=com_fwgallery&amp;view=file&amp;tab=<?php echo esc_attr($view->tab); ?>" method="post" name="operationsForm" id="operationsForm">
				<div class="modal-body">
					<div class="row mx-0">
						<div class="col-md-6">
							<p>
								<label for="fwmg-category-batch"><?php echo JText::_('FWMG_BATCH_CATEGORY'); ?></label>
								<?php echo JHTML :: _('fwsgCategory.getCategories', 'category_id', 0, 'id="fwmg-category-batch" class="form-control"', $multiple=false, $first_option=JText::_('FWMG_BATCH_KEEP_THE_SAME')); ?>
							</p>
						</div>
						<div class="col-md-6">
							<p>
								<label for="fwmg-access-batch"><?php echo JText::_('FWMG_BATCH_ACCESS_LEVEL'); ?></label>
								<?php echo JHTML::_('select.genericlist', array_merge(array(
									JHTML::_('select.option', '-1', JText::_('FWMG_BATCH_KEEP_THE_SAME'), 'id', 'name')
								), fwgHelper::loadviewlevels()), 'access', 'class="form-control"', 'id', 'name', -1, 'fwmg-access-batch'); ?>
							</p>
						</div>
						<div class="col-md-6">
							<p>
								<label for="fwmg-user-batch"><?php echo JText::_('FWMG_BATCH_OWNER'); ?></label>
								<?php echo JHTML::_('select.genericlist', array_merge(array(
									JHTML::_('select.option', '-1', JText::_('FWMG_BATCH_KEEP_THE_SAME'), 'id', 'name')
								), fwgHelper::loadUsers()), 'user_id', 'class="form-control"', 'id', 'name', -1, 'fwmg-user-batch'); ?>
							</p>
						</div>
						<div class="col-md-6">
							<p>
								<label for="fwmg-copyright-batch" data-toggle="tooltip" data-bs-toggle="tooltip" title="<?php echo JText::_('FWMG_COPYRIGHT_BATCH_HINT'); ?>"><?php echo JText::_('FWMG_BATCH_COPYRIGHT'); ?></label>
								<input type="text" class="form-control" id="fwmg-copyright-batch" name="copyright" />
							</p>
						</div>
<?php
$view->app->triggerEvent('onshowBachProcessingExtraFields', array('com_fwgallery', $view));
?>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn" data-dismiss="modal" data-bs-dismiss="modal"><?php echo JText::_('FWMG_CANCEL'); ?></button>
					<button type="submit" class="btn btn-success"><?php echo JText::_('FWMG_PROCESS'); ?></button>
				</div>
				<input type="hidden" name="task" value="dobatch" />
<?php
foreach ($view->fields as $field) {
	if ($view->$field) {
?>
				<input type="hidden" name="<?php echo esc_attr($field); ?>" value="<?php echo esc_attr($view->$field); ?>" />
<?php
	}
}
?>
			</form>
		</div>
	</div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function() {
	(function($) {
	$('#fwmg-batch-operations').on('show show.bs.modal', function() {
		var $form = $(this).find('form');
		$form.find('input[name="cid[]"]').remove();
		var $cids = $('#adminForm input[name="cid[]"]:checked').each(function() {
			var $inp = $('<input />', {
				type: 'hidden',
				name: 'cid[]',
				'value': this.value
			});
			$form.append($inp);
		});
	}).on('show', function() {
		$(this).addClass('show');
	}).on('hide', function() {
		$(this).removeClass('show');
	});
	$('#fwmg-batch-operations')[0].addEventListener('show.bs.modal', function (event) {
		$(this).trigger('show.bs.modal');
	});
	})(jQuery);
});
</script>
