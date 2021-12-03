<?php
/**
 * FW Gallery 6.7.2
 * @copyright (C) 2020 Fastw3b
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.fastw3b.net/ Official website
 **/

defined('_JEXEC') or die('Restricted access');

JToolBarHelper::title(JText::_('FWMG_ADMIN_FILEEDIT_TOOLBAR_TITLE'), ' fal fa-image');

if ($this->current_user->authorise('core.edit', 'com_fwgallery')) {
	fwgButtonsHelper::apply('apply', 'FWMG_DOC_ADMIN_FILEEDIT_TOOLBAR_BTN_SAVE');
}
fwgButtonsHelper::save('save', 'FWMG_DOC_ADMIN_FILEEDIT_TOOLBAR_BTN_SAVECLOSE');
fwgButtonsHelper::cancel('cancel', 'FWMG_DOC_ADMIN_FILEEDIT_TOOLBAR_BTN_CANCEL');

JHTML::stylesheet('components/com_fwgallery/assets/css/jquery.datetimepicker.min.css');
JHTML::_('jquery.framework');
JHTML::script('components/com_fwgallery/assets/js/jquery.datetimepicker.full.min.js');

$editor = fwgHelper::getEditor();

$max_size = fwgHelper::getIniSize('upload_max_filesize');
$post_size = fwgHelper::getIniSize('post_max_size');

echo JLayoutHelper::render('common.menu_begin', array(
    'view' => $this,
    'title' => $this->object->id?(JText::sprintf('FWMG_ADMIN_FILEEDIT_EDIT', $this->object->name)):JText::_('FWMG_ADMIN_FILEEDIT_NEW'),
    'title_hint' => JText::_('FWMG_DOC_ADMIN_FILEEDIT_HINT')
), JPATH_COMPONENT);

?>
<form action="index.php?option=com_fwgallery&amp;view=file" id="adminForm" name="adminForm" method="post" enctype="multipart/form-data">
	<div class="tab-pane active" id="fwmg-image-general" role="tabpanel" aria-expanded="true">
		<div class="container-fluid fwa-main-body">
			<div class="row fwa-mb-cardbox">
				<div class="col-lg-6 col-sm-12">
					<!-- Genral -->
					<div class="card">
						<div class="card-header">
							<h4 class="card-title"><?php echo JText::_('FWMG_DOC_ADMIN_FILEEDIT_SECTION_GENERAL'); ?></h4>
							<div class="card-subtitle"><?php echo JText::_('FWMG_DOC_ADMIN_FILEEDIT_SECTION_GENERAL_HINT'); ?></div>
						</div>
						<div class="card-body">
							<div class="form-group row">
								<label class="col-sm-5 col-form-label clearfix">
									<?php echo JText::_('FWMG_DOC_ADMIN_FILEEDIT_SECTION_GENERAL_PUBLISHED'); ?>
									<i class="pull-right fa fa-question-circle" data-container="body" data-trigger="hover" data-toggle="popover" data-bs-toggle="popover" data-placement="top" data-content="<?php echo esc_attr(JText::_('FWMG_DOC_ADMIN_FILEEDIT_SECTION_GENERAL_PUBLISHED_HINT')); ?>"></i>
								</label>
								<div class="col-sm-7">
									<?php echo JHTMLfwView::radioGroup('published', $this->object->id?$this->object->published:1, array(
										'wrapper_class' => 'mr-2',
										'buttons' => array(array(
											'active_class' => 'btn-success',
											'title' => JText::_('JYES'),
											'value' => 1
										), array(
											'active_class' => 'btn-danger',
											'title' => JText::_('JNO'),
											'value' => 0
										))
									)); ?>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-sm-5 col-form-label clearfix">
									<?php echo JText::_('FWMG_DOC_ADMIN_FILEEDIT_SECTION_GENERAL_NAME'); ?>
									<i class="pull-right fa fa-question-circle" data-container="body" data-trigger="hover" data-toggle="popover" data-bs-toggle="popover" data-placement="top" data-content="<?php echo esc_attr(JText::_('FWMG_DOC_ADMIN_FILEEDIT_SECTION_GENERAL_NAME_HINT')); ?>"></i>
								</label>
								<div class="col-sm-7">
									<input class="form-control" name="name" value="<?php echo esc_attr($this->object->name); ?>" type="text">
								</div>
							</div>
							<div class="form-group row">
								<label class="col-sm-5 col-form-label clearfix">
									<?php echo JText::_('FWMG_DOC_ADMIN_FILEEDIT_SECTION_GENERAL_CATEGORY'); ?>
									<i class="pull-right fa fa-question-circle" data-container="body" data-trigger="hover" data-toggle="popover" data-bs-toggle="popover" data-placement="top" data-content="<?php echo esc_attr(JText::_('FWMG_DOC_ADMIN_FILEEDIT_SECTION_GENERAL_CATEGORY_HINT')); ?>"></i>
								</label>
								<div class="col-sm-7">
									<?php echo JHTML::_('fwsgCategory.getCategories', 'category_id', $this->object->category_id?$this->object->category_id:$this->category, 'class="form-control select-choices"', false, null); ?>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-sm-5 col-form-label clearfix">
									<?php echo JText::_('FWMG_DOC_ADMIN_FILEEDIT_SECTION_GENERAL_COPYRIGHT'); ?>
									<i class="pull-right fa fa-question-circle" data-container="body" data-trigger="hover" data-toggle="popover" data-bs-toggle="popover" data-placement="top" data-content="<?php echo esc_attr(JText::_('FWMG_DOC_ADMIN_FILEEDIT_SECTION_GENERAL_COPYRIGHT_HINT')); ?>"></i>
								</label>
								<div class="col-sm-7">
									<textarea class="form-control" name="copyright" rows="2"><?php echo esc_textarea($this->object->copyright); ?></textarea>
								</div>
							</div>
<?php
		fwgHelper::triggerEvent('onshowFileEditExtraFields', array('com_fwgallery', $this->object));
?>
							<div class="form-group row">
								<label class="col-sm-5 col-form-label clearfix">
									<?php echo JText::_('FWMG_DOC_ADMIN_FILEEDIT_SECTION_GENERAL_DATE'); ?>
									<i class="pull-right fa fa-question-circle" data-container="body" data-trigger="hover" data-toggle="popover" data-bs-toggle="popover" data-placement="top" data-content="<?php echo esc_attr(JText::_('FWMG_DOC_ADMIN_FILEEDIT_SECTION_GENERAL_DATE_HINT')); ?>"></i>
								</label>
								<div class="col-sm-7">
									<div class="input-group">
										<input class="form-control" type="text" name="created" value="<?php echo esc_attr($this->object->id?substr($this->object->created, 0, 16):date('Y-m-d H:i')); ?>">
										<span class="input-group-btn">
											<a class="btn" type="button"><i class="fal fa-calendar-alt"></i></a>
										</span>
									</div>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-sm-5 col-form-label clearfix">
									<?php echo JText::_('FWMG_DOC_ADMIN_FILEEDIT_SECTION_GENERAL_OWNER'); ?>
									<i class="pull-right fa fa-question-circle" data-container="body" data-trigger="hover" data-toggle="popover" data-bs-toggle="popover" data-placement="top" data-content="<?php echo esc_attr(JText::_('FWMG_DOC_ADMIN_FILEEDIT_SECTION_GENERAL_OWNER_HINT')); ?>"></i>
								</label>
								<div class="col-sm-7">
									<?php echo JHTML::_('select.genericlist', fwgHelper::loadUsers(), 'user_id', 'class="form-control select-choices"', 'id', 'name', $this->object->id?$this->object->user_id:$this->current_user->id); ?>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-sm-5 col-form-label clearfix">
									<?php echo JText::_('FWMG_DOC_ADMIN_FILEEDIT_SECTION_GENERAL_ACCESS'); ?>
									<i class="pull-right fa fa-question-circle" data-container="body" data-trigger="hover" data-toggle="popover" data-bs-toggle="popover" data-placement="top" data-content="<?php echo esc_attr(JText::_('FWMG_DOC_ADMIN_FILEEDIT_SECTION_GENERAL_ACCESS_HINT')); ?>"></i>
								</label>
								<div class="col-sm-7">
									<?php echo JHTML::_('select.genericlist', fwgHelper::loadviewlevels(), 'access', 'class="form-control select-choices"', 'id', 'name', $this->object->access); ?>
								</div>
							</div>
						</div>
					</div>
<?php
		fwgHelper::triggerEvent('onshowFileEditExtraCards', array('com_fwgallery', $this->object));
?>
				</div>
				<div class="col-lg-6 col-sm-12">
					<!-- Image -->
					<div class="card">
						<div class="card-header">
							<h4 class="card-title"><?php echo JText::_('FWMG_DOC_ADMIN_FILEEDIT_SECTION_IMAGE_IMAGE'); ?></h4>
							<div class="card-subtitle"><?php echo JText::_('FWMG_DOC_ADMIN_FILEEDIT_SECTION_IMAGE_IMAGE_HINT'); ?>, <?php echo JText :: _('FWMG_FILE_UPLOAD_SIZE_LIMIT', true).' '.fwgHelper::humanFileSize($max_size); ?>, <?php echo JText :: _('FWMG_POST_SIZE_LIMIT', true).' '.fwgHelper::humanFileSize($post_size); ?></div>
						</div>
						<div class="card-body" id="fwmg-image-container">
							<div class="mb-2">
								<img class="img-thumbnail" src="<?php echo JURI::root(true); ?>/index.php?option=com_fwgallery&amp;view=item&amp;layout=img&amp;format=raw&amp;w=491&amp;h=300&amp;id=<?php echo (int)$this->object->id; ?>" />
								<button type="button" class="btn btn-default btn-danger fwmg-remove"><i class="fal fa-trash-alt mr-2"></i><?php echo JText::_('FWMG_REMOVE'); ?></button>
								<?php echo JHTML::_('fwView.handleRemoveButton', array(
									'button' => '#fwmg-image-container .fwmg-remove',
									'image' => '#fwmg-image-container img',
									'view' => 'file',
									'layout' => 'delete_image'
								)); ?>
							</div>
							<div class="input-group mb-3">
								<div class="custom-file">
									<input type="file" class="custom-file-input" id="fwmg-image" name="image" />
									<label class="custom-file-label" for="fwmg-image"><?php echo JText::_('FWMG_CHOOSE_FILE'); ?></label>
								</div>
								<div class="input-group-append">
									<button type="button" class="btn btn-default btn-success fwmg-upload"><i class="fa fa-upload mr-2"></i><?php echo JText::_('FWMG_UPLOAD'); ?></button>
								</div>
							</div>
<?php
$response_code = '$(\'input[name="name"]\').val(data.name);$(\'input[name="alias"]\').val(data.alias);$(\'textarea[name="copyright"]\').val(data.copyright);$(\'textarea[name="metakey"]\').val(data.metakey);$(\'textarea[name="metadescr"]\').val(data.metadescr);$(\'textarea[name="descr"]\').val(data.descr);Joomla.editors.instances[\'descr\'].setValue(data.descr);';
fwgHelper::triggerEvent('ongetResponseJsForUploadedImage', array('com_fwgallery', &$response_code));
?>
							<?php echo JHTML::_('fwView.handleUploadButton', array(
								'button' => '#fwmg-image-container .fwmg-upload',
								'input' => '#fwmg-image',
								'image' => '#fwmg-image-container img',
								'exts' => array('gif', 'jpg', 'jpeg', 'png'),
								'view' => 'file',
								'layout' => 'upload',
								'response_code' => $response_code
							)); ?>
						</div>
					</div>
					<!-- Descripton -->
					<div class="card">
						<div class="card-header">
							<h4 class="card-title"><?php echo JText::_('FWMG_DOC_ADMIN_FILEEDIT_SECTION_DESCRIPTION_DESCRIPTION'); ?></h4>
							<div class="card-subtitle"><?php echo JText::_('FWMG_DOC_ADMIN_FILEEDIT_SECTION_DESCRIPTION_DESCRIPTION_HINT'); ?></div>
						</div>
						<div class="card-body">
							<?php echo $editor->display('descr', $this->object->descr, '100%', 150, 60, 7); ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
    <input type="hidden" name="id" value="<?php echo (int)$this->object->id; ?>" />
    <input type="hidden" name="type" value="image" />
    <input type="hidden" name="task" value="" />
<?php
foreach ($this->fields as $field) {
	if ($this->$field) {
?>
    <input type="hidden" name="<?php echo esc_attr($field); ?>" value="<?php echo esc_attr($this->$field); ?>" />
<?php
	}
}
?>
</form>
<script>
document.addEventListener('DOMContentLoaded', function() {
	(function($) {
    $('input[name="created"]').datetimepicker({
        format: 'Y-m-d H:i'
    });
    $('input[name="created"]').next().click(function() {
        $('input[name="created"]').focus();
    });
	})(jQuery);
});
</script>
<?php
echo JLayoutHelper::render('common.menu_end', array(), JPATH_COMPONENT);
