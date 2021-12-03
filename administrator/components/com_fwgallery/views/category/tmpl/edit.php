<?php
/**
 * FW Gallery 6.7.2
 * @copyright (C) 2020 Fastw3b
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.fastw3b.net/ Official website
 **/

defined('_JEXEC') or die('Restricted access');

JToolBarHelper::title(JText::_('FWMG_ADMIN_CATEGORYEDIT_TOOLBAR_TITLE'), ' fal fa-folders');

if ($this->current_user->authorise('core.edit', 'com_fwgallery')) {
	fwgButtonsHelper::apply('apply','FWMG_DOC_ADMIN_CATEGORYEDIT_TOOLBAR_BTN_SAVE');
}
fwgButtonsHelper::save('save', 'FWMG_DOC_ADMIN_CATEGORYEDIT_TOOLBAR_BTN_SAVECLOSE');
if ($this->object->id) {
	fwgButtonsHelper::custom('fal fa-upload', 'purple', 'FWMG_DOC_ADMIN_CATEGORYEDIT_TOOLBAR_BTN_UPLOAD', 'import', false);
}
fwgButtonsHelper::cancel('cancel', 'FWMG_DOC_ADMIN_CATEGORYEDIT_TOOLBAR_BTN_CANCEL');

JHTML::stylesheet('components/com_fwgallery/assets/css/jquery.datetimepicker.min.css');
JHTML::_('jquery.framework');
JHTML::script('components/com_fwgallery/assets/js/jquery.datetimepicker.full.min.js');

$editor = fwgHelper::getEditor();

echo JLayoutHelper::render('common.menu_begin', array(
    'view' => $this,
    'title' => $this->object->id?(JText::sprintf('FWMG_ADMIN_CATEGORYEDIT_EDIT', $this->object->name)):JText::_('FWMG_ADMIN_CATEGORYEDIT_NEW'),
    'title_hint' => JText::_('FWMG_DOC_ADMIN_CATEGORYEDIT_HINT')
), JPATH_COMPONENT);
?>
<form action="index.php?option=com_fwgallery&amp;view=category" id="adminForm" name="adminForm" method="post" enctype="multipart/form-data">
	<div class="fwa-filter-bar">
		<ul class="nav nav-tabs" role="tablist">
	        <li class="nav-item">
	            <a class="nav-link active" data-toggle="tab" data-bs-toggle="tab" href="#fwmg-gallery-general" role="tab"><?php echo JText::_('FWMG_DOC_ADMIN_CATEGORYEDIT_TAB_GENERAL'); ?></a>
	        </li>
	        <li class="nav-item">
	            <a class="nav-link" data-toggle="tab" data-bs-toggle="tab" href="#fwmg-gallery-category-design" role="tab"><?php echo JText::_('FWMG_DOC_ADMIN_CATEGORYEDIT_TAB_CATEGORY'); ?></a>
	        </li>
	        <li class="nav-item">
	            <a class="nav-link" data-toggle="tab" data-bs-toggle="tab" href="#fwmg-gallery-file-design" role="tab"><?php echo JText::_('FWMG_DOC_ADMIN_CATEGORYEDIT_TAB_FILES'); ?></a>
	        </li>
	    </ul>
	</div>
    <div class="tab-content">
        <div class="tab-pane active" id="fwmg-gallery-general" role="tabpanel">
            <div class="container-fluid fwa-main-body">
                <div class="row fwa-mb-cardbox">
                    <div class="col-lg-6 col-sm-12">
                        <!-- Genral -->
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title"><?php echo JText::_('FWMG_DOC_ADMIN_CATEGORYEDIT_TAB_GENERAL_SECTION_GENERAL'); ?></h4>
                                <div class="card-subtitle"><?php echo JText::_('FWMG_DOC_ADMIN_CATEGORYEDIT_TAB_GENERAL_SECTION_GENERAL_HINT'); ?></div>
                            </div>
                            <div class="card-body">
                                <div class="form-group row">
                                    <label class="col-sm-5 col-form-label clearfix">
                                        <?php echo JText::_('FWMG_DOC_ADMIN_CATEGORYEDIT_TAB_GENERAL_SECTION_GENERAL_PUBLISHED'); ?>
                                        <i class="pull-right fa fa-question-circle" data-container="body" data-trigger="hover" data-toggle="popover" data-bs-toggle="popover" data-placement="top" data-content="<?php echo JText::_('FWMG_DOC_ADMIN_CATEGORYEDIT_TAB_GENERAL_SECTION_GENERAL_PUBLISHED_HINT'); ?>"></i>
                                    </label>
                                    <div class="col-sm-7">
                                        <?php echo JHTMLfwView::radioGroup('published', $this->object->id?$this->object->published:1, array(
                                            'wrapper_class' => 'mr-2',
                                            'buttons' => array(array(
                                                'active_class' => 'btn-success',
                                                'title' => JText::_('JYES'),
                                                'value' => '1'
                                            ), array(
                                                'active_class' => 'btn-danger',
                                                'title' => JText::_('JNO'),
                                                'value' => '0'
                                            ))
                                        )); ?>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5 col-form-label clearfix">
                                        <?php echo JText::_('FWMG_DOC_ADMIN_CATEGORYEDIT_TAB_GENERAL_SECTION_GENERAL_NAME'); ?>
                                        <i class="pull-right fa fa-question-circle" data-container="body" data-trigger="hover" data-toggle="popover" data-bs-toggle="popover" data-placement="top" data-content="<?php echo esc_attr(JText::_('FWMG_DOC_ADMIN_CATEGORYEDIT_TAB_GENERAL_SECTION_GENERAL_NAME_HINT')); ?>"></i>
                                    </label>
                                    <div class="col-sm-7">
                                        <input class="form-control mb-0" type="text" name="name" value="<?php echo esc_attr($this->object->name); ?>">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5 col-form-label clearfix">
                                        <?php echo JText::_('FWMG_DOC_ADMIN_CATEGORYEDIT_TAB_GENERAL_SECTION_GENERAL_CATEGORY'); ?>
                                        <i class="pull-right fa fa-question-circle" data-container="body" data-trigger="hover" data-toggle="popover" data-bs-toggle="popover" data-placement="top" data-content="<?php echo esc_attr(JText::_('FWMG_DOC_ADMIN_CATEGORYEDIT_TAB_GENERAL_SECTION_GENERAL_CATEGORY_HINT')); ?>"></i>
                                    </label>
                                    <div class="col-sm-7">
                                        <?php echo JHTML::_('fwsgCategory.parent', $this->object, 'parent', 'class="form-control select-choices"'); ?>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5 col-form-label clearfix">
                                        <?php echo JText::_('FWMG_DOC_ADMIN_CATEGORYEDIT_TAB_GENERAL_SECTION_GENERAL_DATE'); ?>
                                        <i class="pull-right fa fa-question-circle" data-container="body" data-trigger="hover" data-toggle="popover" data-bs-toggle="popover" data-placement="top" data-content="<?php echo esc_attr(JText::_('FWMG_DOC_ADMIN_CATEGORYEDIT_TAB_GENERAL_SECTION_GENERAL_DATE_HINT')); ?>"></i>
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
                                        <?php echo JText::_('FWMG_DOC_ADMIN_CATEGORYEDIT_TAB_GENERAL_SECTION_GENERAL_OWNER'); ?>
                                        <i class="pull-right fa fa-question-circle" data-container="body" data-trigger="hover" data-toggle="popover" data-bs-toggle="popover" data-placement="top" data-content="<?php echo esc_attr(JText::_('FWMG_DOC_ADMIN_CATEGORYEDIT_TAB_GENERAL_SECTION_GENERAL_OWNER_HINT')); ?>"></i>
                                    </label>
                                    <div class="col-sm-7">
                                        <?php echo JHTML::_('select.genericlist', fwgHelper::loadUsers(), 'user_id', 'class="form-control select-choices"', 'id', 'name', $this->object->id?$this->object->user_id:$this->current_user->id); ?>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5 col-form-label clearfix">
                                        <?php echo JText::_('FWMG_DOC_ADMIN_CATEGORYEDIT_TAB_GENERAL_SECTION_GENERAL_ACCESS'); ?>
                                        <i class="pull-right fa fa-question-circle" data-container="body" data-trigger="hover" data-toggle="popover" data-bs-toggle="popover" data-placement="top" data-content="<?php echo esc_attr(JText::_('FWMG_DOC_ADMIN_CATEGORYEDIT_TAB_GENERAL_SECTION_GENERAL_ACCESS_HINT')); ?>"></i>
                                    </label>
                                    <div class="col-sm-7">
                                        <?php echo JHTML::_('select.genericlist', fwgHelper::loadviewlevels(), 'access', 'class="form-control select-choices"', 'id', 'name', $this->object->access); ?>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5 col-form-label clearfix">
                                        <?php echo JText::_('FWMG_DOC_ADMIN_CATEGORYEDIT_TAB_GENERAL_SECTION_GENERAL_WATERMARK'); ?>
                                        <i class="pull-right fa fa-question-circle" data-container="body" data-trigger="hover" data-toggle="popover" data-bs-toggle="popover" data-placement="top" data-content="<?php echo esc_attr(JText::_('FWMG_DOC_ADMIN_CATEGORYEDIT_TAB_GENERAL_SECTION_GENERAL_WATERMARK_HINT')); ?>"></i>
                                    </label>
                                    <div class="col-sm-7">
										<?php echo JHTMLfwView::radioGroup('params[use_watermark]', $this->object->params->get('use_watermark', 'def'), array(
											'wrapper_class' => 'mr-2',
											'buttons' => array(array(
												'active_class' => 'btn-success',
												'title' => JText::_('FWMG_DEFAULT'),
												'value' => 'def'
											), array(
												'active_class' => 'btn-success',
												'title' => JText::_('JYES'),
												'value' => '1'
											), array(
												'active_class' => 'btn-danger',
												'title' => JText::_('JNO'),
												'value' => '0'
											))
										)); ?>
                                    </div>
                                </div>
<?php
		fwgHelper::triggerEvent('onshowCategoryEditExtraFields', array('com_fwgallery', $this->object));
?>
                            </div>
                        </div>
<?php
        fwgHelper::triggerEvent('onshowCategoryEditExtraCards', array('com_fwgallery', $this->object));
?>

                    </div>
                    <div class="col-lg-6 col-sm-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title"><?php echo JText::_('FWMG_DOC_ADMIN_CATEGORYEDIT_TAB_GENERAL_SECTION_COVER'); ?></h4>
                                <div class="card-subtitle"><?php echo JText::_('FWMG_DOC_ADMIN_CATEGORYEDIT_TAB_GENERAL_SECTION_COVER_HINT'); ?></div>
                            </div>
                            <div class="card-body">
								<div class="form-group row">
									<label class="col-sm-5 col-form-label clearfix">
										<?php echo JText::_('FWMG_DOC_ADMIN_CATEGORYEDIT_TAB_GENERAL_SECTION_COVER_TYPE'); ?>
										<i class="pull-right fa fa-question-circle" data-container="body" data-trigger="hover" data-toggle="popover" data-bs-toggle="popover" data-placement="top" data-content="<?php echo esc_attr(JText::_('FWMG_DOC_ADMIN_CATEGORYEDIT_TAB_GENERAL_SECTION_COVER_TYPE_HINT')); ?>"></i>
									</label>
									<div class="col-sm-7">
										<?php echo JHTMLfwView::radioGroup('media', $this->object->media?$this->object->media:'none', array(
											'wrapper_class' => 'mr-2',
											'buttons' => array(array(
												'active_class' => 'btn-success',
												'title' => JText::_('FWMG_IMAGE'),
												'value' => 'none'
											), array(
												'active_class' => 'btn-success',
												'title' => JText::_('FWMG_SELF_HOSTED'),
												'value' => 'mp4'
											), array(
												'active_class' => 'btn-success',
												'title' => 'YouTube',
												'value' => 'youtube'
											), array(
												'active_class' => 'btn-success',
												'title' => 'Vimeo',
												'value' => 'vimeo'
											))
										)); ?>
									</div>
								</div>
								<div class="form-group row fwmg-media fwmg-vimeo fwmg-youtube" style="display:none;">
									<label class="col-sm-5 col-form-label clearfix">
										<?php echo JText::_('FWMG_VIDEO_ID'); ?>
										<i class="pull-right fa fa-question-circle" data-container="body" data-trigger="hover" data-toggle="popover" data-bs-toggle="popover" data-placement="top" title="<?php echo esc_attr(JText::_('FWMG_VIDEO_ID_TITLE')); ?>" data-content="<?php echo esc_attr(JText::_('FWMG_VIDEO_ID_DESCR')); ?>"></i>
									</label>
									<div class="col-sm-7">
										<input class="form-control" type="text" name="remote_code" value="<?php if ($this->object->media != 'mp4') echo esc_attr($this->object->media_code); ?>" />
									</div>
								</div>
                                <div class="form-group fwmg-media fwmg-mp4 fwmg-none">
                                    <label class="col-form-label clearfix">
                                        <?php echo JText::_('FWMG_DOC_ADMIN_CATEGORYEDIT_TAB_GENERAL_SECTION_COVER_FILE'); ?>
                                        <i class="ml-2 fa fa-question-circle" data-container="body" data-trigger="hover" data-toggle="popover" data-bs-toggle="popover" data-placement="top" data-content="<?php echo esc_attr(JText::_('FWMG_DOC_ADMIN_CATEGORYEDIT_TAB_GENERAL_SECTION_COVER_FILE_HINT')); ?>"></i>
                                    </label>
                                    <div class="row no-gutters" id="fwmg-image-container">
                                        <div class="col">
                                            <img class="img-thumbnail" src="<?php echo JURI::root(true); ?>/index.php?option=com_fwgallery&amp;view=fwgallery&amp;layout=img&amp;format=raw&amp;w=266&amp;h=200&amp;id=<?php echo (int)$this->object->id; ?>" />
                                        </div>
                                        <div class="col">
                                            <div class="input-group mb-3">
                                                <div class="custom-file">
                                                    <input type="file" class="custom-file-input" id="fwmg-image" name="image" />
                                                    <label class="custom-file-label" for="fwmg-image"><?php echo JText::_('FWMG_CHOOSE_FILE'); ?></label>
                                                </div>
                                                <div class="input-group-append">
                                                    <button type="button" class="btn btn-default btn-success fwmg-upload"><i class="fa fa-upload mr-2"></i><?php echo JText::_('FWMG_UPLOAD'); ?></button>
                                                </div>
                                            </div>
                                            <div class="text-muted"><small><?php echo JText::sprintf('FWMG_MAX_IMAGE_SIZE_LONG', ini_get('post_max_size')); ?></small></div>
                                            <div class="mt-5">
                                                <button type="button" class="btn btn-default btn-danger fwmg-remove"><i class="fal fa-trash-alt mr-2"></i><?php echo JText::_('FWMG_REMOVE'); ?></button>
                                                <?php echo JHTML::_('fwView.handleRemoveButton', array(
                                                    'button' => '#fwmg-image-container .fwmg-remove',
                                                    'image' => '#fwmg-image-container img',
                                                    'view' => 'category',
                                                    'layout' => 'delete_image'
                                                )); ?>
                                            </div>
                                        </div>
                                        <?php echo JHTML::_('fwView.handleUploadButton', array(
                                                'button' => '#fwmg-image-container .fwmg-upload',
                                                'input' => '#fwmg-image',
                                                'image' => '#fwmg-image-container img',
                                                'exts' => array('gif', 'jpg', 'jpeg', 'png'),
                                                'view' => 'category',
                                                'layout' => 'upload'
                                            )); ?>
                                    </div>
                                </div>
								<div class="form-group row fwmg-media fwmg-mp4" style="display:none;">
									<label class="col-sm-5 col-form-label clearfix">
										<?php echo JText::_('FWMG_VIDEO_FILE'); ?>
										<i class="pull-right fa fa-question-circle" data-container="body" data-trigger="hover" data-toggle="popover" data-bs-toggle="popover" data-placement="top" data-content="<?php echo esc_attr(JText::_('FWMG_VIDEO_FILE_DESCR')); ?>"></i>
									</label>
									<div class="col-sm-7" id="fwmg-video-container">
										<div class="mb-3">
<?php
if (fwgHelper::fileExists($this->object->media_code)) {
?>
											<strong><?php echo esc_html($this->object->media_code); ?></strong> <span><?php echo fwgHelper::humanFileSize($this->object->_video_size); ?></span>
<?php
} else {
?>
											<strong></strong> <span></span>
<?php
}
?>
										</div>
                                        <div class="input-group mb-3">
                                            <div class="custom-file">
                                                <input type="file" class="custom-file-input" id="fwmg-video" name="file" />
                                                <label class="custom-file-label" for="fwmg-video"><?php echo JText::_('FWMG_CHOOSE_FILE'); ?></label>
                                            </div>
                                            <div class="input-group-append">
                                                <button type="button" class="btn btn-success fwmg-upload"><i class="fa fa-upload mr-2"></i><?php echo JText::_('FWMG_UPLOAD'); ?></button>
                                            </div>
                                        </div>
										<div class="text-muted"><small><?php echo JText::sprintf('FWMG_MAX_VIDEO_SIZE_LONG', ini_get('post_max_size')); ?></small></div>
										<?php echo JHTML::_('fwView.handleUploadButton', array(
											'button' => '#fwmg-video-container .fwmg-upload',
											'input' => '#fwmg-video',
											'image' => '#fwmg-video-container img',
											'exts' => array('mp4'),
                                            'view' => 'category',
											'layout' => 'upload',
											'callback' => 'function(result) {
	$progress_bar.remove();
	$button.attr(\'disabled\', false);

	var $parent = $input.parent();
	$input.next().html(\''.JText::_('FWMG_CHOOSE_FILE', false).'\');

	var input_html = $parent.html();
	$parent.html(input_html);

	var data = $.parseJSON(result);
	if (data) {
		if (data.id) {
			$(\'input[name="id"]\').val(data.id);
		}
        if (data.media_code) {
            $(\'#fwmg-video-container strong:eq(0)\').html(data.media_code);
        }
        if (data._video_size) {
            $(\'#fwmg-video-container span:eq(0)\').html(data._video_size);
        }

		if (data.msg) {
			var $msg = $(\'<div class="alert alert-warning alert-dismissible fade show" role="alert">\
<button type="button" class="close" data-dismiss="alert" aria-label="Close">\
<span aria-hidden="true">&times;</span>\
</button>\
\'+data.msg+\'</div>\');
			$button.before($msg);
			$(\'button\', $msg).click(function() {
				$msg.remove();
			});
			setTimeout(function() {
				$msg.remove();
			}, 3000);
		}
	}
}'
										)); ?>
                                        <button type="button" class="btn btn-danger"><i class="fal fa-trash-alt mr-2"></i><?php echo JText::_('FWMG_REMOVE'); ?></button>
                                        <?php echo JHTML::_('fwView.handleRemoveButton', array(
                                            'button' => '#fwmg-video-container .btn-danger',
                                            'image' => '#fwmg-video-container img',
                                            'view' => 'category',
                                            'layout' => 'delete_video',
                                            'callback' => 'function(html) {
	$img.remove();
	$button.attr(\'disabled\', false);
	var data = $.parseJSON(html);
	if (data) {
		if (data.id) {
			$(\'#fwmg-video-container .mb-3\').find(\'span,strong\').html(\'\');
		}
		if (data.msg) fwmg_alert(data.msg);
	}
}'
                                        )); ?>
									</div>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title"><?php echo JText::_('FWMG_DOC_ADMIN_CATEGORYEDIT_TAB_GENERAL_SECTION_DESCRIPTION_DESCRIPTION'); ?></h4>
                                <div class="card-subtitle"><?php echo JText::_('FWMG_DOC_ADMIN_CATEGORYEDIT_TAB_GENERAL_SECTION_DESCRIPTION_DESCRIPTION_HINT'); ?></div>
                            </div>
                            <div class="card-body">
                                <?php echo $editor->display('descr', $this->object->descr, '100%', 150, 60, 7); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- FWSG Gallery Design -->
        <div class="tab-pane" id="fwmg-gallery-category-design" role="tabpanel">
            <div class="container-fluid fwa-main-body">
                <div class="row fwa-mb-cardbox">
                    <div class="col-lg-6 col-sm-12">
                        <!-- Gallery Layout -->
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title"><?php echo JText::_('FWMG_DOC_ADMIN_CATEGORYEDIT_TAB_CATEGORY_SECTION_HEADER'); ?></h4>
                                <div class="card-subtitle"><?php echo JText::_('FWMG_DOC_ADMIN_CATEGORYEDIT_TAB_CATEGORY_SECTION_HEADER_HINT'); ?></div>
                            </div>
                            <div class="card-body">
                                <div class="form-group row">
                                    <label class="col-sm-5 col-form-label clearfix">
                                        <?php echo JText::_('FWMG_DOC_ADMIN_CATEGORYEDIT_TAB_CATEGORY_SECTION_HEADER_NAME'); ?>
                                        <i class="pull-right fa fa-question-circle" data-container="body" data-trigger="hover" data-toggle="popover" data-bs-toggle="popover" data-placement="top" data-content="<?php echo esc_attr(JText::_('FWMG_DOC_ADMIN_CATEGORYEDIT_TAB_CATEGORY_SECTION_HEADER_NAME_HINT')); ?>"></i>
                                    </label>
                                    <div class="col-sm-7">
                                        <?php echo JHTMLfwView::radioGroup('params[show_gallery_name]', $this->object->params->get('show_gallery_name', 'def'), array(
                                            'wrapper_class' => 'mr-2',
                                            'buttons' => array(array(
                                                'active_class' => 'btn-success',
                                                'title' => JText::_('FWMG_DEFAULT'),
                                                'value' => 'def'
                                            ), array(
                                                'active_class' => 'btn-success',
                                                'title' => JText::_('FWMG_SHOW'),
                                                'value' => '1'
                                            ), array(
                                                'active_class' => 'btn-danger',
                                                'title' => JText::_('FWMG_HIDE'),
                                                'value' => '0'
                                            ))
                                        )); ?>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5 col-form-label clearfix">
                                        <?php echo JText::_('FWMG_DOC_ADMIN_CATEGORYEDIT_TAB_CATEGORY_SECTION_HEADER_UPDATE'); ?>
                                        <i class="pull-right fa fa-question-circle" data-container="body" data-trigger="hover" data-toggle="popover" data-bs-toggle="popover" data-placement="top" data-content="<?php echo esc_attr(JText::_('FWMG_DOC_ADMIN_CATEGORYEDIT_TAB_CATEGORY_SECTION_HEADER_UPDATE_HINT')); ?>"></i>
                                    </label>
                                    <div class="col-sm-7">
                                        <?php echo JHTMLfwView::radioGroup('params[show_gallery_update_date]', $this->object->params->get('show_gallery_update_date', 'def'), array(
                                            'wrapper_class' => 'mr-2',
                                            'buttons' => array(array(
                                                'active_class' => 'btn-success',
                                                'title' => JText::_('FWMG_DEFAULT'),
                                                'value' => 'def'
                                            ), array(
                                                'active_class' => 'btn-success',
                                                'title' => JText::_('FWMG_SHOW'),
                                                'value' => '1'
                                            ), array(
                                                'active_class' => 'btn-danger',
                                                'title' => JText::_('FWMG_HIDE'),
                                                'value' => '0'
                                            ))
                                        )); ?>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5 col-form-label clearfix">
                                        <?php echo JText::_('FWMG_DOC_ADMIN_CATEGORYEDIT_TAB_CATEGORY_SECTION_HEADER_OWNER'); ?>
                                        <i class="pull-right fa fa-question-circle" data-container="body" data-trigger="hover" data-toggle="popover" data-bs-toggle="popover" data-placement="top" data-content="<?php echo esc_attr(JText::_('FWMG_DOC_ADMIN_CATEGORYEDIT_TAB_CATEGORY_SECTION_HEADER_OWNER_HINT')); ?>"></i>
                                    </label>
                                    <div class="col-sm-7">
                                        <?php echo JHTMLfwView::radioGroup('params[show_gallery_owner]', $this->object->params->get('show_gallery_owner', 'def'), array(
                                            'wrapper_class' => 'mr-2',
                                            'buttons' => array(array(
                                                'active_class' => 'btn-success',
                                                'title' => JText::_('FWMG_DEFAULT'),
                                                'value' => 'def'
                                            ), array(
                                                'active_class' => 'btn-success',
                                                'title' => JText::_('FWMG_SHOW'),
                                                'value' => '1'
                                            ), array(
                                                'active_class' => 'btn-danger',
                                                'title' => JText::_('FWMG_HIDE'),
                                                'value' => '0'
                                            ))
                                        )); ?>
                                    </div>
                                </div>
<?php
		fwgHelper::triggerEvent('onshowCategoryEditCategoryDesignExtraFields', array('com_fwgallery', $this->object));
?>
                                <div class="form-group row">
                                    <label class="col-sm-5 col-form-label clearfix">
                                        <?php echo JText::_('FWMG_DOC_ADMIN_CATEGORYEDIT_TAB_CATEGORY_SECTION_HEADER_COUNTER'); ?>
                                        <i class="pull-right fa fa-question-circle" data-container="body" data-trigger="hover" data-toggle="popover" data-bs-toggle="popover" data-placement="top" data-content="<?php echo esc_attr(JText::_('FWMG_DOC_ADMIN_CATEGORYEDIT_TAB_CATEGORY_SECTION_HEADER_COUNTER_HINT')); ?>"></i>
                                    </label>
                                    <div class="col-sm-7">
                                        <?php echo JHTMLfwView::radioGroup('params[show_gallery_counter]', $this->object->params->get('show_gallery_counter', 'def'), array(
                                            'wrapper_class' => 'mr-2',
                                            'buttons' => array(array(
                                                'active_class' => 'btn-success',
                                                'title' => JText::_('FWMG_DEFAULT'),
                                                'value' => 'def'
                                            ), array(
                                                'active_class' => 'btn-success',
                                                'title' => JText::_('FWMG_SHOW'),
                                                'value' => '1'
                                            ), array(
                                                'active_class' => 'btn-danger',
                                                'title' => JText::_('FWMG_HIDE'),
                                                'value' => '0'
                                            ))
                                        )); ?>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5 col-form-label clearfix">
                                        <?php echo JText::_('FWMG_DOC_ADMIN_CATEGORYEDIT_TAB_CATEGORY_SECTION_HEADER_DESCRIPTION'); ?>
                                        <i class="pull-right fa fa-question-circle" data-container="body" data-trigger="hover" data-toggle="popover" data-bs-toggle="popover" data-placement="top" data-content="<?php echo esc_attr(JText::_('FWMG_DOC_ADMIN_CATEGORYEDIT_TAB_CATEGORY_SECTION_HEADER_DESCRIPTION_HINT')); ?>"></i>
                                    </label>
                                    <div class="col-sm-7">
                                        <?php echo JHTMLfwView::radioGroup('params[show_gallery_description]', $this->object->params->get('show_gallery_description', 'def'), array(
                                            'wrapper_class' => 'mr-2',
                                            'buttons' => array(array(
                                                'active_class' => 'btn-success',
                                                'title' => JText::_('FWMG_DEFAULT'),
                                                'value' => 'def'
                                            ), array(
                                                'active_class' => 'btn-success',
                                                'title' => JText::_('FWMG_SHOW'),
                                                'value' => '1'
                                            ), array(
                                                'active_class' => 'btn-danger',
                                                'title' => JText::_('FWMG_HIDE'),
                                                'value' => '0'
                                            ))
                                        )); ?>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5 col-form-label clearfix">
                                        <?php echo JText::_('FWMG_DOC_ADMIN_CATEGORYEDIT_TAB_CATEGORY_SECTION_HEADER_DESCPOSITION'); ?>
                                        <i class="pull-right fa fa-question-circle" data-container="body" data-trigger="hover" data-toggle="popover" data-bs-toggle="popover" data-placement="top" data-content="<?php echo esc_attr(JText::_('FWMG_DOC_ADMIN_CATEGORYEDIT_TAB_CATEGORY_SECTION_HEADER_DESCPOSITION_HINT')); ?>"></i>
                                    </label>
                                    <div class="col-sm-7">
                                        <?php echo JHTMLfwView::radioGroup('params[gallery_descr_position]', $this->object->params->get('gallery_descr_position', 'def'), array(
                                            'wrapper_class' => 'mr-2',
                                            'buttons' => array(array(
                                                'active_class' => 'btn-success',
                                                'title' => JText::_('FWMG_DEFAULT'),
                                                'value' => 'def'
                                            ), array(
                                                'active_class' => 'btn-success',
                                                'title' => JText::_('FWMG_TOP'),
                                                'value' => 'top'
                                            ), array(
                                                'active_class' => 'btn-success',
                                                'title' => JText::_('FWMG_RIGHT'),
                                                'value' => 'right'
                                            ))
                                        )); ?>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5 col-form-label clearfix">
                                        <?php echo JText::_('FWMG_DOC_ADMIN_CATEGORYEDIT_TAB_CATEGORY_SECTION_HEADER_BREADCRUMBS'); ?>
                                        <i class="pull-right fa fa-question-circle" data-container="body" data-trigger="hover" data-toggle="popover" data-bs-toggle="popover" data-placement="top" data-content="<?php echo esc_attr(JText::_('FWMG_DOC_ADMIN_CATEGORYEDIT_TAB_CATEGORY_SECTION_HEADER_BREADCRUMBS_HINT')); ?>"></i>
                                    </label>
                                    <div class="col-sm-7">
                                        <?php echo JHTMLfwView::radioGroup('params[show_gallery_breadcrumbs]', $this->object->params->get('show_gallery_breadcrumbs', 'def'), array(
                                            'wrapper_class' => 'mr-2',
                                            'buttons' => array(array(
                                                'active_class' => 'btn-success',
                                                'title' => JText::_('FWMG_DEFAULT'),
                                                'value' => 'def'
                                            ), array(
                                                'active_class' => 'btn-success',
                                                'title' => JText::_('FWMG_SHOW'),
                                                'value' => '1'
                                            ), array(
                                                'active_class' => 'btn-danger',
                                                'title' => JText::_('FWMG_HIDE'),
                                                'value' => '0'
                                            ))
                                        )); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Gallery Layout -->
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title"><?php echo JText::_('FWMG_DOC_ADMIN_CATEGORYEDIT_TAB_CATEGORY_SECTION_GRID'); ?></h4>
                                <div class="card-subtitle"><?php echo JText::_('FWMG_DOC_ADMIN_CATEGORYEDIT_TAB_CATEGORY_SECTION_GRID_HINT'); ?></div>
                            </div>
                            <div class="card-body">
                                <div class="form-group row">
                                    <label class="col-sm-5 col-form-label clearfix">
                                        <?php echo JText::_('FWMG_DOC_ADMIN_CATEGORYEDIT_TAB_CATEGORY_SECTION_GRID_COLUMNS'); ?>
                                        <i class="pull-right fa fa-question-circle" data-container="body" data-trigger="hover" data-toggle="popover" data-bs-toggle="popover" data-placement="top" data-content="<?php echo esc_attr(JText::_('FWMG_DOC_ADMIN_CATEGORYEDIT_TAB_CATEGORY_SECTION_GRID_COLUMNS_HINT')); ?>"></i>
                                    </label>
                                    <div class="col-sm-7">
                                        <?php echo JHTMLfwView::radioGroup('params[gallery_columns]', $this->object->params->get('gallery_columns', 'def'), array(
                                            'wrapper_class' => 'mr-2',
                                            'buttons' => array(array(
                                                'active_class' => 'btn-success',
                                                'title' => JText::_('FWMG_DEFAULT'),
                                                'value' => 'def'
                                            ), array(
                                                'active_class' => 'btn-success',
                                                'title' => '1',
                                                'value' => '1'
                                            ), array(
                                                'active_class' => 'btn-success',
                                                'title' => 2,
                                                'value' => 2
                                            ), array(
                                                'active_class' => 'btn-success',
                                                'title' => 3,
                                                'value' => 3
                                            ), array(
                                                'active_class' => 'btn-success',
                                                'title' => 4,
                                                'value' => 4
                                            ), array(
                                                'active_class' => 'btn-success',
                                                'title' => 5,
                                                'value' => 5
                                            ), array(
                                                'active_class' => 'btn-success',
                                                'title' => 6,
                                                'value' => 6
                                            ))
                                        )); ?>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5 col-form-label clearfix">
                                        <?php echo JText::_('FWMG_DOC_ADMIN_CATEGORYEDIT_TAB_CATEGORY_SECTION_GRID_ROWS'); ?>
                                        <i class="pull-right fa fa-question-circle" data-container="body" data-trigger="hover" data-toggle="popover" data-bs-toggle="popover" data-placement="top" data-content="<?php echo esc_attr(JText::_('FWMG_DOC_ADMIN_CATEGORYEDIT_TAB_CATEGORY_SECTION_GRID_ROWS_HINT')); ?>"></i>
                                    </label>
                                    <div class="col-sm-7">
                                        <input class="form-control" type="text" name="params[gallery_rows]" value="<?php echo esc_attr($this->object->params->get('gallery_rows', 4)); ?>" />
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5 col-form-label clearfix">
                                        <?php echo JText::_('FWMG_DOC_ADMIN_CATEGORYEDIT_TAB_CATEGORY_SECTION_GRID_LIMIT'); ?>
                                        <i class="pull-right fa fa-question-circle" data-container="body" data-trigger="hover" data-toggle="popover" data-bs-toggle="popover" data-placement="top" data-content="<?php echo esc_attr(JText::_('FWMG_DOC_ADMIN_CATEGORYEDIT_TAB_CATEGORY_SECTION_GRID_LIMIT_HINT')); ?>"></i>
                                    </label>
                                    <div class="col-sm-7">
                                        <?php echo JHTMLfwView::radioGroup('params[show_gallery_limit]', $this->object->params->get('show_gallery_limit', 'def'), array(
                                            'wrapper_class' => 'mr-2',
                                            'buttons' => array(array(
                                                'active_class' => 'btn-success',
                                                'title' => JText::_('FWMG_DEFAULT'),
                                                'value' => 'def'
                                            ), array(
                                                'active_class' => 'btn-success',
                                                'title' => JText::_('FWMG_SHOW'),
                                                'value' => '1'
                                            ), array(
                                                'active_class' => 'btn-danger',
                                                'title' => JText::_('FWMG_HIDE'),
                                                'value' => '0'
                                            ))
                                        )); ?>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5 col-form-label clearfix">
                                        <?php echo JText::_('FWMG_DOC_ADMIN_CATEGORYEDIT_TAB_CATEGORY_SECTION_GRID_ORDERING'); ?>
                                        <i class="pull-right fa fa-question-circle" data-container="body" data-trigger="hover" data-toggle="popover" data-bs-toggle="popover" data-placement="top" data-content="<?php echo esc_attr(JText::_('FWMG_DOC_ADMIN_CATEGORYEDIT_TAB_CATEGORY_SECTION_GRID_ORDERING_HINT')); ?>"></i>
                                    </label>
                                    <div class="col-sm-7">
                                        <?php echo JHTMLfwView::radioGroup('params[show_gallery_ordering]', $this->object->params->get('show_gallery_ordering', 'def'), array(
                                            'wrapper_class' => 'mr-2',
                                            'buttons' => array(array(
                                                'active_class' => 'btn-success',
                                                'title' => JText::_('FWMG_DEFAULT'),
                                                'value' => 'def'
                                            ), array(
                                                'active_class' => 'btn-success',
                                                'title' => JText::_('FWMG_SHOW'),
                                                'value' => '1'
                                            ), array(
                                                'active_class' => 'btn-danger',
                                                'title' => JText::_('FWMG_HIDE'),
                                                'value' => '0'
                                            ))
                                        )); ?>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5 col-form-label clearfix">
                                        <?php echo JText::_('FWMG_DOC_ADMIN_CATEGORYEDIT_TAB_CATEGORY_SECTION_GRID_ORDERINGDEFAULT'); ?>
                                        <i class="pull-right fa fa-question-circle" data-container="body" data-trigger="hover" data-toggle="popover" data-bs-toggle="popover" data-placement="top" data-content="<?php echo esc_attr(JText::_('FWMG_DOC_ADMIN_CATEGORYEDIT_TAB_CATEGORY_SECTION_GRID_ORDERINGDEFAULT_HINT')); ?>"></i>
                                    </label>
                                    <div class="col-sm-7">
                                        <?php echo JHTML::_('select.genericlist', array(
                                            JHTML::_('select.option', 'def', JText::_('FWMG_DEFAULT'), 'id', 'name'),
                                            JHTML::_('select.option', 'ordering', JText::_('FWMG_ORDERING'), 'id', 'name'),
                                            JHTML::_('select.option', 'alpha', JText::_('FWMG_APLHABETICALLY'), 'id', 'name'),
                                            JHTML::_('select.option', 'new', JText::_('FWMG_NEWEST_FIRST'), 'id', 'name'),
                                            JHTML::_('select.option', 'old', JText::_('FWMG_OLDEST_FIRST'), 'id', 'name'),
                                        ), 'params[gallery_default_ordering]', 'class="form-control select-choices"', 'id', 'name', $this->object->params->get('gallery_default_ordering', 'def')); ?>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5 col-form-label clearfix">
                                        <?php echo JText::_('FWMG_DOC_ADMIN_CATEGORYEDIT_TAB_CATEGORY_SECTION_GRID_PAGINATION'); ?>
                                        <i class="pull-right fa fa-question-circle" data-container="body" data-trigger="hover" data-toggle="popover" data-bs-toggle="popover" data-placement="top" data-content="<?php echo esc_attr(JText::_('FWMG_DOC_ADMIN_CATEGORYEDIT_TAB_CATEGORY_SECTION_GRID_PAGINATION_HINT')); ?>"></i>
                                    </label>
                                    <div class="col-sm-7">
                                        <?php echo JHTMLfwView::radioGroup('params[gallery_pagination_type]', $this->object->params->get('gallery_pagination_type', 'def'), array(
                                            'wrapper_class' => 'mr-2',
                                            'buttons' => array(array(
                                                'active_class' => 'btn-success',
                                                'title' => JText::_('FWMG_DEFAULT'),
                                                'value' => 'def'
                                            ), array(
                                                'active_class' => 'btn-success',
                                                'title' => JText::_('FWMG_STANDARD'),
                                                'value' => 'standard'
                                            ), array(
                                                'active_class' => 'btn-success',
                                                'title' => JText::_('FWMG_AJAX_LOAD_MORE'),
                                                'value' => 'ajax'
                                            ), array(
                                                'active_class' => 'btn-danger',
                                                'title' => JText::_('FWMG_HIDE'),
                                                'value' => ''
                                            ))
                                        )); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
					</div>
                    <div class="col-lg-6 col-sm-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title"><?php echo JText::_('FWMG_DOC_ADMIN_CATEGORYEDIT_TAB_CATEGORY_SECTION_GRIDITEM'); ?></h4>
                                <div class="card-subtitle"><?php echo JText::_('FWMG_DOC_ADMIN_CATEGORYEDIT_TAB_CATEGORY_SECTION_GRIDITEM_HINT'); ?></div>
                            </div>
                            <div class="card-body">
                                <div class="form-group row">
                                    <label class="col-sm-5 col-form-label clearfix">
                                        <?php echo JText::_('FWMG_DOC_ADMIN_CATEGORYEDIT_TAB_CATEGORY_SECTION_GRIDITEM_DESIGN'); ?>
                                        <i class="pull-right fa fa-question-circle" data-container="body" data-trigger="hover" data-toggle="popover" data-bs-toggle="popover" data-placement="top" data-content="<?php echo esc_attr(JText::_('FWMG_DOC_ADMIN_CATEGORYEDIT_TAB_CATEGORY_SECTION_GRIDITEM_DESIGN_HINT')); ?>"></i>
                                    </label>
                                    <div class="col-sm-7">
                                        <?php echo JHTML::_('select.genericlist', array_merge(array(
											JHTML::_('select.option', 'def', JText::_('FWMG_DEFAULT'), 'id', 'name')
										), fwgHelper::getThemesList()), 'params[template]', 'class="form-control select-choices"', 'id', 'name', $this->object->params->get('template', 'def')); ?>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5 col-form-label clearfix">
                                        <?php echo JText::_('FWMG_DOC_ADMIN_CATEGORYEDIT_TAB_CATEGORY_SECTION_GRIDITEM_SHOWINFO'); ?>
                                        <i class="pull-right fa fa-question-circle" data-container="body" data-trigger="hover" data-toggle="popover" data-bs-toggle="popover" data-placement="top" data-content="<?php echo esc_attr(JText::_('FWMG_DOC_ADMIN_CATEGORYEDIT_TAB_CATEGORY_SECTION_GRIDITEM_SHOWINFO_HINT')); ?>"></i>
                                    </label>
                                    <div class="col-sm-7">
                                        <?php echo JHTML::_('select.genericlist', array_merge(array(
											JHTML::_('select.option', 'def', JText::_('FWMG_DEFAULT'), 'id', 'name')
										), fwgHelper::getLayoutsList()), 'params[gallery_layout]', 'class="form-control select-choices"', 'id', 'name', $this->object->params->get('gallery_layout', 'def')); ?>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5 col-form-label clearfix">
                                        <?php echo JText::_('FWMG_DOC_ADMIN_CATEGORYEDIT_TAB_CATEGORY_SECTION_GRIDITEM_HOVER'); ?>
                                        <i class="pull-right fa fa-question-circle" data-container="body" data-trigger="hover" data-toggle="popover" data-bs-toggle="popover" data-placement="top" data-content="<?php echo esc_attr(JText::_('FWMG_DOC_ADMIN_CATEGORYEDIT_TAB_CATEGORY_SECTION_GRIDITEM_HOVER_HINT')); ?>"></i>
                                    </label>
                                    <div class="col-sm-7">
                                        <?php echo JHTML::_('select.genericlist', array(
                                            JHTML::_('select.option', 'def', JText::_('FWMG_DEFAULT'), 'id', 'name'),
                                            JHTML::_('select.option', 'none', JText::_('FWMG_NONE'), 'id', 'name'),
                                            JHTML::_('select.option', 'kenburns', JText::_('FWMG_KENBURNS'), 'id', 'name'),
                                            JHTML::_('select.option', 'linear', JText::_('FWMG_LINEAR'), 'id', 'name'),
                                        ), 'params[gallery_hover]', 'class="form-control select-choices"', 'id', 'name', $this->object->params->get('gallery_hover', 'def')); ?>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5 col-form-label clearfix">
                                        <?php echo JText::_('FWMG_DOC_ADMIN_CATEGORYEDIT_TAB_CATEGORY_SECTION_GRIDITEM_NAME'); ?>
                                        <i class="pull-right fa fa-question-circle" data-container="body" data-trigger="hover" data-toggle="popover" data-bs-toggle="popover" data-placement="top" data-content="<?php echo esc_attr(JText::_('FWMG_DOC_ADMIN_CATEGORYEDIT_TAB_CATEGORY_SECTION_GRIDITEM_NAME_HINT')); ?>"></i>
                                    </label>
                                    <div class="col-sm-7">
                                        <?php echo JHTMLfwView::radioGroup('params[show_gallery_item_name]', $this->object->params->get('show_gallery_item_name', 'def'), array(
                                            'wrapper_class' => 'mr-2',
                                            'buttons' => array(array(
                                                'active_class' => 'btn-success',
                                                'title' => JText::_('FWMG_DEFAULT'),
                                                'value' => 'def'
                                            ), array(
                                                'active_class' => 'btn-success',
                                                'title' => JText::_('FWMG_SHOW'),
                                                'value' => '1'
                                            ), array(
                                                'active_class' => 'btn-danger',
                                                'title' => JText::_('FWMG_HIDE'),
                                                'value' => '0'
                                            ))
                                        )); ?>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5 col-form-label clearfix">
                                        <?php echo JText::_('FWMG_DOC_ADMIN_CATEGORYEDIT_TAB_CATEGORY_SECTION_GRIDITEM_UPDATE'); ?>
                                        <i class="pull-right fa fa-question-circle" data-container="body" data-trigger="hover" data-toggle="popover" data-bs-toggle="popover" data-placement="top" data-content="<?php echo esc_attr(JText::_('FWMG_DOC_ADMIN_CATEGORYEDIT_TAB_CATEGORY_SECTION_GRIDITEM_UPDATE_HINT')); ?>"></i>
                                    </label>
                                    <div class="col-sm-7">
                                        <?php echo JHTMLfwView::radioGroup('params[show_gallery_item_update_date]', $this->object->params->get('show_gallery_item_update_date', 'def'), array(
                                            'wrapper_class' => 'mr-2',
                                            'buttons' => array(array(
                                                'active_class' => 'btn-success',
                                                'title' => JText::_('FWMG_DEFAULT'),
                                                'value' => 'def'
                                            ), array(
                                                'active_class' => 'btn-success',
                                                'title' => JText::_('FWMG_SHOW'),
                                                'value' => '1'
                                            ), array(
                                                'active_class' => 'btn-danger',
                                                'title' => JText::_('FWMG_HIDE'),
                                                'value' => '0'
                                            ))
                                        )); ?>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5 col-form-label clearfix">
                                        <?php echo JText::_('FWMG_DOC_ADMIN_CATEGORYEDIT_TAB_CATEGORY_SECTION_GRIDITEM_OWNER'); ?>
                                        <i class="pull-right fa fa-question-circle" data-container="body" data-trigger="hover" data-toggle="popover" data-bs-toggle="popover" data-placement="top" data-content="<?php echo esc_attr(JText::_('FWMG_DOC_ADMIN_CATEGORYEDIT_TAB_CATEGORY_SECTION_GRIDITEM_OWNER_HINT')); ?>"></i>
                                    </label>
                                    <div class="col-sm-7">
                                        <?php echo JHTMLfwView::radioGroup('params[show_gallery_item_owner]', $this->object->params->get('show_gallery_item_owner', 'def'), array(
                                            'wrapper_class' => 'mr-2',
                                            'buttons' => array(array(
                                                'active_class' => 'btn-success',
                                                'title' => JText::_('FWMG_DEFAULT'),
                                                'value' => 'def'
                                            ), array(
                                                'active_class' => 'btn-success',
                                                'title' => JText::_('FWMG_SHOW'),
                                                'value' => '1'
                                            ), array(
                                                'active_class' => 'btn-danger',
                                                'title' => JText::_('FWMG_HIDE'),
                                                'value' => '0'
                                            ))
                                        )); ?>
                                    </div>
                                </div>
<?php
		fwgHelper::triggerEvent('onshowCategoryEditCategoryListingDesignExtraFields', array('com_fwgallery', $this->object));
?>
                                <div class="form-group row">
                                    <label class="col-sm-5 col-form-label clearfix">
                                        <?php echo JText::_('FWMG_DOC_ADMIN_CATEGORYEDIT_TAB_CATEGORY_SECTION_GRIDITEM_COUNTER'); ?>
                                        <i class="pull-right fa fa-question-circle" data-container="body" data-trigger="hover" data-toggle="popover" data-bs-toggle="popover" data-placement="top" data-content="<?php echo esc_attr(JText::_('FWMG_DOC_ADMIN_CATEGORYEDIT_TAB_CATEGORY_SECTION_GRIDITEM_COUNTER_HINT')); ?>"></i>
                                    </label>
                                    <div class="col-sm-7">
                                        <?php echo JHTMLfwView::radioGroup('params[show_gallery_item_counter]', $this->object->params->get('show_gallery_item_counter', 'def'), array(
                                            'wrapper_class' => 'mr-2',
                                            'buttons' => array(array(
                                                'active_class' => 'btn-success',
                                                'title' => JText::_('FWMG_DEFAULT'),
                                                'value' => 'def'
                                            ), array(
                                                'active_class' => 'btn-success',
                                                'title' => JText::_('FWMG_SHOW'),
                                                'value' => '1'
                                            ), array(
                                                'active_class' => 'btn-danger',
                                                'title' => JText::_('FWMG_HIDE'),
                                                'value' => '0'
                                            ))
                                        )); ?>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5 col-form-label clearfix">
                                        <?php echo JText::_('FWMG_DOC_ADMIN_CATEGORYEDIT_TAB_CATEGORY_SECTION_GRIDITEM_DESCRIPTION'); ?>
                                        <i class="pull-right fa fa-question-circle" data-container="body" data-trigger="hover" data-toggle="popover" data-bs-toggle="popover" data-placement="top" data-content="<?php echo esc_attr(JText::_('FWMG_DOC_ADMIN_CATEGORYEDIT_TAB_CATEGORY_SECTION_GRIDITEM_DESCRIPTION_HINT')); ?>"></i>
                                    </label>
                                    <div class="col-sm-7">
                                        <?php echo JHTMLfwView::radioGroup('params[show_gallery_item_description]', $this->object->params->get('show_gallery_item_description', 'def'), array(
                                            'wrapper_class' => 'mr-2',
                                            'buttons' => array(array(
                                                'active_class' => 'btn-success',
                                                'title' => JText::_('FWMG_DEFAULT'),
                                                'value' => 'def'
                                            ), array(
                                                'active_class' => 'btn-success',
                                                'title' => JText::_('FWMG_SHOW'),
                                                'value' => '1'
                                            ), array(
                                                'active_class' => 'btn-danger',
                                                'title' => JText::_('FWMG_HIDE'),
                                                'value' => '0'
                                            ))
                                        )); ?>
                                    </div>
                                </div>
								<div class="form-group row">
									<label class="col-sm-5 col-form-label clearfix">
										<?php echo JText::_('FWMG_DOC_ADMIN_CATEGORYEDIT_TAB_CATEGORY_SECTION_GRIDITEM_DESCLENGTH'); ?>
										<i class="pull-right fa fa-question-circle" data-container="body" data-trigger="hover" data-toggle="popover" data-bs-toggle="popover" data-placement="top"  data-content="<?php echo esc_attr(JText::_('FWMG_DOC_ADMIN_CATEGORYEDIT_TAB_CATEGORY_SECTION_GRIDITEM_DESCLENGTH_HINT')); ?>"></i>
									</label>
									<div class="col-sm-7">
										<input type="text" class="form-control" name="params[description_length]" value="<?php echo esc_attr($this->object->params->get('description_length', 20)); ?>" size="20"/>
									</div>
								</div>
                                <div class="form-group row">
                                    <label class="col-sm-5 col-form-label clearfix">
                                        <?php echo JText::_('FWMG_DOC_ADMIN_CATEGORYEDIT_TAB_CATEGORY_SECTION_GRIDITEM_VIDEOPLAY'); ?>
                                        <i class="pull-right fa fa-question-circle" data-container="body" data-trigger="hover" data-toggle="popover" data-bs-toggle="popover" data-placement="top" data-content="<?php echo esc_attr(JText::_('FWMG_DOC_ADMIN_CATEGORYEDIT_TAB_CATEGORY_SECTION_GRIDITEM_VIDEOPLAY_HINT')); ?>"></i>
                                    </label>
                                    <div class="col-sm-7">
                                        <?php echo JHTMLfwView::radioGroup('params[dont_gallery_item_video_autoplay]', $this->object->params->get('dont_gallery_item_video_autoplay', 'def'), array(
                                            'wrapper_class' => 'mr-2',
                                            'buttons' => array(array(
                                                'active_class' => 'btn-success',
                                                'title' => JText::_('FWMG_DEFAULT'),
                                                'value' => 'def'
                                            ), array(
                                                'active_class' => 'btn-success',
                                                'title' => JText::_('JYES'),
                                                'value' => '0'
                                            ), array(
                                                'active_class' => 'btn-danger',
                                                'title' => JText::_('JNO'),
                                                'value' => '1'
                                            ))
                                        )); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
				</div>
			</div>
		</div>

        <div class="tab-pane" id="fwmg-gallery-file-design" role="tabpanel">
            <div class="container-fluid fwa-main-body">
                <div class="row fwa-mb-cardbox">
                    <div class="col-lg-6 col-sm-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title"><?php echo JText::_('FWMG_DOC_ADMIN_CATEGORYEDIT_TAB_FILES_SECTION_GRID'); ?></h4>
                                <div class="card-subtitle"><?php echo JText::_('FWMG_DOC_ADMIN_CATEGORYEDIT_TAB_FILES_SECTION_GRID_HINT'); ?></div>
                            </div>
                            <div class="card-body">
                                <div class="form-group row">
                                    <label class="col-sm-5 col-form-label clearfix">
                                        <?php echo JText::_('FWMG_GRID'); ?>
                                        <i class="pull-right fa fa-question-circle" data-container="body" data-trigger="hover" data-toggle="popover" data-bs-toggle="popover" data-placement="top" title="<?php echo esc_attr(JText::_('FWMG_GRID_TITLE')); ?>" data-content="<?php echo esc_attr(JText::_('FWMG_GRID_CONTENT')); ?>"></i>
                                    </label>
                                    <div class="col-sm-7">
                                        <?php echo JHTML::_('select.genericlist', array_merge(array(
											JHTML::_('select.option', 'def', JText::_('FWMG_DEFAULT'), 'id', 'name')
										), fwgHelper::getGridsList()), 'params[files_grid]', 'class="form-control select-choices"', 'id', 'name', $this->object->params->get('files_grid', 'def')); ?>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5 col-form-label clearfix">
                                        <?php echo JText::_('FWMG_DOC_ADMIN_CATEGORYEDIT_TAB_FILES_SECTION_GRID_COLUMNS'); ?>
                                        <i class="pull-right fa fa-question-circle" data-container="body" data-trigger="hover" data-toggle="popover" data-bs-toggle="popover" data-placement="top" data-content="<?php echo esc_attr(JText::_('FWMG_DOC_ADMIN_CATEGORYEDIT_TAB_FILES_SECTION_GRID_COLUMNS_HINT')); ?>"></i>
                                    </label>
                                    <div class="col-sm-7">
                                        <?php echo JHTMLfwView::radioGroup('params[files_columns]', $this->object->params->get('files_columns', 'def'), array(
                                            'wrapper_class' => 'mr-2',
                                            'buttons' => array(array(
                                                'active_class' => 'btn-success',
                                                'title' => JText::_('FWMG_DEFAULT'),
                                                'value' => 'def'
                                            ), array(
                                                'active_class' => 'btn-success',
                                                'title' => '1',
                                                'value' => '1'
                                            ), array(
                                                'active_class' => 'btn-success',
                                                'title' => 2,
                                                'value' => 2
                                            ), array(
                                                'active_class' => 'btn-success',
                                                'title' => 3,
                                                'value' => 3
                                            ), array(
                                                'active_class' => 'btn-success',
                                                'title' => 4,
                                                'value' => 4
                                            ), array(
                                                'active_class' => 'btn-success',
                                                'title' => 5,
                                                'value' => 5
                                            ), array(
                                                'active_class' => 'btn-success',
                                                'title' => 6,
                                                'value' => 6
                                            ))
                                        )); ?>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5 col-form-label clearfix">
                                        <?php echo JText::_('FWMG_DOC_ADMIN_CATEGORYEDIT_TAB_FILES_SECTION_GRID_ROWS'); ?>
                                        <i class="pull-right fa fa-question-circle" data-container="body" data-trigger="hover" data-toggle="popover" data-bs-toggle="popover" data-placement="top" data-content="<?php echo esc_attr(JText::_('FWMG_DOC_ADMIN_CATEGORYEDIT_TAB_FILES_SECTION_GRID_ROWS_HINT')); ?>"></i>
                                    </label>
                                    <div class="col-sm-7">
                                        <input class="form-control" type="text" name="params[files_rows]" value="<?php echo esc_attr($this->object->params->get('files_rows', 4)); ?>" />
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5 col-form-label clearfix">
                                        <?php echo JText::_('FWMG_DOC_ADMIN_CATEGORYEDIT_TAB_FILES_SECTION_GRID_OPEN'); ?>
                                        <i class="pull-right fa fa-question-circle" data-container="body" data-trigger="hover" data-toggle="popover" data-bs-toggle="popover" data-placement="top" data-content="<?php echo esc_attr(JText::_('FWMG_DOC_ADMIN_CATEGORYEDIT_TAB_FILES_SECTION_GRID_OPEN_HINT')); ?>"></i>
                                    </label>
                                    <div class="col-sm-7">
<?php
$file_open_as = array(array(
    'active_class' => 'btn-success',
    'title' => JText::_('FWMG_DEFAULT'),
    'value' => 'def'
), array(
    'active_class' => 'btn-success',
    'title' => JText::_('FWMG_PAGE'),
    'value' => 'link'
), array(
    'active_class' => 'btn-warning',
    'title' => JText::_('FWMG_LIGHTBOX'),
    'value' => 'popup'
));
fwgHelper::triggerEvent('onFileOpenAsGetVariants', array('com_fwgallery', &$file_open_as, $this));
?>
                                        <?php echo JHTMLfwView::radioGroup('params[file_open_as]', $this->object->params->get('file_open_as', 'def'), array(
                                            'wrapper_class' => 'mr-2',
                                            'buttons' => $file_open_as
                                        )); ?>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5 col-form-label clearfix">
                                        <?php echo JText::_('FWMG_DOC_ADMIN_CATEGORYEDIT_TAB_FILES_SECTION_GRID_LIMIT'); ?>
                                        <i class="pull-right fa fa-question-circle" data-container="body" data-trigger="hover" data-toggle="popover" data-bs-toggle="popover" data-placement="top" data-content="<?php echo esc_attr(JText::_('FWMG_DOC_ADMIN_CATEGORYEDIT_TAB_FILES_SECTION_GRID_LIMIT_HINT')); ?>"></i>
                                    </label>
                                    <div class="col-sm-7">
                                        <?php echo JHTMLfwView::radioGroup('params[show_files_limit]', $this->object->params->get('show_files_limit', 'def'), array(
                                            'wrapper_class' => 'mr-2',
                                            'buttons' => array(array(
                                                'active_class' => 'btn-success',
                                                'title' => JText::_('FWMG_DEFAULT'),
                                                'value' => 'def'
                                            ), array(
                                                'active_class' => 'btn-success',
                                                'title' => JText::_('FWMG_SHOW'),
                                                'value' => '1'
                                            ), array(
                                                'active_class' => 'btn-danger',
                                                'title' => JText::_('FWMG_HIDE'),
                                                'value' => '0'
                                            ))
                                        )); ?>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5 col-form-label clearfix">
                                        <?php echo JText::_('FWMG_DOC_ADMIN_CATEGORYEDIT_TAB_FILES_SECTION_GRID_ORDERING'); ?>
                                        <i class="pull-right fa fa-question-circle" data-container="body" data-trigger="hover" data-toggle="popover" data-bs-toggle="popover" data-placement="top" data-content="<?php echo esc_attr(JText::_('FWMG_DOC_ADMIN_CATEGORYEDIT_TAB_FILES_SECTION_GRID_ORDERING_HINT')); ?>"></i>
                                    </label>
                                    <div class="col-sm-7">
                                        <?php echo JHTMLfwView::radioGroup('params[show_files_ordering]', $this->object->params->get('show_files_ordering', 'def'), array(
                                            'wrapper_class' => 'mr-2',
                                            'buttons' => array(array(
                                                'active_class' => 'btn-success',
                                                'title' => JText::_('FWMG_DEFAULT'),
                                                'value' => 'def'
                                            ), array(
                                                'active_class' => 'btn-success',
                                                'title' => JText::_('FWMG_SHOW'),
                                                'value' => '1'
                                            ), array(
                                                'active_class' => 'btn-danger',
                                                'title' => JText::_('FWMG_HIDE'),
                                                'value' => '0'
                                            ))
                                        )); ?>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5 col-form-label clearfix">
                                        <?php echo JText::_('FWMG_DOC_ADMIN_CATEGORYEDIT_TAB_FILES_SECTION_GRID_ORDERINGDEFAULT'); ?>
                                        <i class="pull-right fa fa-question-circle" data-container="body" data-trigger="hover" data-toggle="popover" data-bs-toggle="popover" data-placement="top" data-content="<?php echo esc_attr(JText::_('FWMG_DOC_ADMIN_CATEGORYEDIT_TAB_FILES_SECTION_GRID_ORDERINGDEFAULT_HINT')); ?>"></i>
                                    </label>
                                    <div class="col-sm-7">
                                        <?php echo JHTML::_('select.genericlist', array(
                                            JHTML::_('select.option', 'def', JText::_('FWMG_DEFAULT'), 'id', 'name'),
                                            JHTML::_('select.option', 'ordering', JText::_('FWMG_ORDERING'), 'id', 'name'),
                                            JHTML::_('select.option', 'alpha', JText::_('FWMG_APLHABETICALLY'), 'id', 'name'),
                                            JHTML::_('select.option', 'new', JText::_('FWMG_NEWEST_FIRST'), 'id', 'name'),
                                            JHTML::_('select.option', 'old', JText::_('FWMG_OLDEST_FIRST'), 'id', 'name'),
                                        ), 'params[files_default_ordering]', 'class="form-control select-choices"', 'id', 'name', $this->object->params->get('files_default_ordering', 'def')); ?>
                                    </div>
                                </div>
<?php
		fwgHelper::triggerEvent('onshowCategoryEditFileListingOrderingExtraFields', array('com_fwgallery', $this->object));
?>
                                <div class="form-group row">
                                    <label class="col-sm-5 col-form-label clearfix">
                                        <?php echo JText::_('FWMG_DOC_ADMIN_CATEGORYEDIT_TAB_FILES_SECTION_GRID_PAGINATION'); ?>
                                        <i class="pull-right fa fa-question-circle" data-container="body" data-trigger="hover" data-toggle="popover" data-bs-toggle="popover" data-placement="top" data-content="<?php echo esc_attr(JText::_('FWMG_DOC_ADMIN_CATEGORYEDIT_TAB_FILES_SECTION_GRID_PAGINATION_HINT')); ?>"></i>
                                    </label>
                                    <div class="col-sm-7">
                                        <?php echo JHTMLfwView::radioGroup('params[files_pagination_type]', $this->object->params->get('files_pagination_type', 'def'), array(
                                            'wrapper_class' => 'mr-2',
                                            'buttons' => array(array(
                                                'active_class' => 'btn-success',
                                                'title' => JText::_('FWMG_DEFAULT'),
                                                'value' => 'def'
                                            ), array(
                                                'active_class' => 'btn-success',
                                                'title' => JText::_('FWMG_STANDARD'),
                                                'value' => 'standard'
                                            ), array(
                                                'active_class' => 'btn-success',
                                                'title' => JText::_('FWMG_AJAX_LOAD_MORE'),
                                                'value' => 'ajax'
                                            ))
                                        )); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Items Listing Design -->
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title"><?php echo JText::_('FWMG_DOC_ADMIN_CATEGORYEDIT_TAB_FILES_SECTION_GRIDITEM'); ?></h4>
                                <div class="card-subtitle"><?php echo JText::_('FWMG_DOC_ADMIN_CATEGORYEDIT_TAB_FILES_SECTION_GRIDITEM_HINT'); ?></div>
                            </div>
                            <div class="card-body">
                                <div class="form-group row">
                                    <label class="col-sm-5 col-form-label clearfix">
                                        <?php echo JText::_('FWMG_DOC_ADMIN_CATEGORYEDIT_TAB_FILES_SECTION_GRIDITEM_INFO'); ?>
                                        <i class="pull-right fa fa-question-circle" data-container="body" data-trigger="hover" data-toggle="popover" data-bs-toggle="popover" data-placement="top" data-content="<?php echo esc_attr(JText::_('FWMG_DOC_ADMIN_CATEGORYEDIT_TAB_FILES_SECTION_GRIDITEM_INFO_HINT')); ?>"></i>
                                    </label>
                                    <div class="col-sm-7">
                                        <?php echo JHTML::_('select.genericlist', array_merge(array(
											JHTML::_('select.option', 'def', JText::_('FWMG_DEFAULT'), 'id', 'name')
										), fwgHelper::getLayoutsList()), 'params[file_layout]', 'class="form-control select-choices"', 'id', 'name', $this->object->params->get('file_layout', 'def')); ?>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5 col-form-label clearfix">
                                        <?php echo JText::_('FWMG_DOC_ADMIN_CATEGORYEDIT_TAB_FILES_SECTION_GRIDITEM_HOVER'); ?>
                                        <i class="pull-right fa fa-question-circle" data-container="body" data-trigger="hover" data-toggle="popover" data-bs-toggle="popover" data-placement="top" data-content="<?php echo esc_attr(JText::_('FWMG_DOC_ADMIN_CATEGORYEDIT_TAB_FILES_SECTION_GRIDITEM_HOVER_HINT')); ?>"></i>
                                    </label>
                                    <div class="col-sm-7">
                                        <?php echo JHTML::_('select.genericlist', array(
                                            JHTML::_('select.option', 'def', JText::_('FWMG_DEFAULT'), 'id', 'name'),
                                            JHTML::_('select.option', 'none', JText::_('FWMG_NONE'), 'id', 'name'),
                                            JHTML::_('select.option', 'kenburns', JText::_('FWMG_KENBURNS'), 'id', 'name'),
                                            JHTML::_('select.option', 'linear', JText::_('FWMG_LINEAR'), 'id', 'name'),
                                        ), 'params[file_hover]', 'class="form-control select-choices"', 'id', 'name', $this->object->params->get('file_hover', 'def')); ?>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5 col-form-label clearfix">
                                        <?php echo JText::_('FWMG_DOC_ADMIN_CATEGORYEDIT_TAB_FILES_SECTION_GRIDITEM_NAME'); ?>
                                        <i class="pull-right fa fa-question-circle" data-container="body" data-trigger="hover" data-toggle="popover" data-bs-toggle="popover" data-placement="top" data-content="<?php echo esc_attr(JText::_('FWMG_DOC_ADMIN_CATEGORYEDIT_TAB_FILES_SECTION_GRIDITEM_NAME_HINT')); ?>"></i>
                                    </label>
                                    <div class="col-sm-7">
                                        <?php echo JHTMLfwView::radioGroup('params[show_files_name]', $this->object->params->get('show_files_name', 'def'), array(
                                            'wrapper_class' => 'mr-2',
                                            'buttons' => array(array(
                                                'active_class' => 'btn-success',
                                                'title' => JText::_('FWMG_DEFAULT'),
                                                'value' => 'def'
                                            ), array(
                                                'active_class' => 'btn-success',
                                                'title' => JText::_('FWMG_SHOW'),
                                                'value' => '1'
                                            ), array(
                                                'active_class' => 'btn-danger',
                                                'title' => JText::_('FWMG_HIDE'),
                                                'value' => '0'
                                            ))
                                        )); ?>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5 col-form-label clearfix">
                                        <?php echo JText::_('FWMG_DOC_ADMIN_CATEGORYEDIT_TAB_FILES_SECTION_GRIDITEM_UPDATE'); ?>
                                        <i class="pull-right fa fa-question-circle" data-container="body" data-trigger="hover" data-toggle="popover" data-bs-toggle="popover" data-placement="top" data-content="<?php echo esc_attr(JText::_('FWMG_DOC_ADMIN_CATEGORYEDIT_TAB_FILES_SECTION_GRIDITEM_UPDATE_HINT')); ?>"></i>
                                    </label>
                                    <div class="col-sm-7">
                                        <?php echo JHTMLfwView::radioGroup('params[show_files_update]', $this->object->params->get('show_files_update', 'def'), array(
                                            'wrapper_class' => 'mr-2',
                                            'buttons' => array(array(
                                                'active_class' => 'btn-success',
                                                'title' => JText::_('FWMG_DEFAULT'),
                                                'value' => 'def'
                                            ), array(
                                                'active_class' => 'btn-success',
                                                'title' => JText::_('FWMG_SHOW'),
                                                'value' => '1'
                                            ), array(
                                                'active_class' => 'btn-danger',
                                                'title' => JText::_('FWMG_HIDE'),
                                                'value' => '0'
                                            ))
                                        )); ?>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5 col-form-label clearfix">
                                        <?php echo JText::_('FWMG_DOC_ADMIN_CATEGORYEDIT_TAB_FILES_SECTION_GRIDITEM_OWNER'); ?>
                                        <i class="pull-right fa fa-question-circle" data-container="body" data-trigger="hover" data-toggle="popover" data-bs-toggle="popover" data-placement="top" data-content="<?php echo esc_attr(JText::_('FWMG_DOC_ADMIN_CATEGORYEDIT_TAB_FILES_SECTION_GRIDITEM_OWNER_HINT')); ?>"></i>
                                    </label>
                                    <div class="col-sm-7">
                                        <?php echo JHTMLfwView::radioGroup('params[show_files_owner]', $this->object->params->get('show_files_owner', 'def'), array(
                                            'wrapper_class' => 'mr-2',
                                            'buttons' => array(array(
                                                'active_class' => 'btn-success',
                                                'title' => JText::_('FWMG_DEFAULT'),
                                                'value' => 'def'
                                            ), array(
                                                'active_class' => 'btn-success',
                                                'title' => JText::_('FWMG_SHOW'),
                                                'value' => '1'
                                            ), array(
                                                'active_class' => 'btn-danger',
                                                'title' => JText::_('FWMG_HIDE'),
                                                'value' => '0'
                                            ))
                                        )); ?>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5 col-form-label clearfix">
                                        <?php echo JText::_('FWMG_DOC_ADMIN_CATEGORYEDIT_TAB_FILES_SECTION_GRIDITEM_DESCRIPTION'); ?>
                                        <i class="pull-right fa fa-question-circle" data-container="body" data-trigger="hover" data-toggle="popover" data-bs-toggle="popover" data-placement="top" data-content="<?php echo esc_attr(JText::_('FWMG_DOC_ADMIN_CATEGORYEDIT_TAB_FILES_SECTION_GRIDITEM_DESCRIPTION_HINT')); ?>"></i>
                                    </label>
                                    <div class="col-sm-7">
                                        <?php echo JHTMLfwView::radioGroup('params[show_files_description]', $this->object->params->get('show_files_description', 'def'), array(
                                            'wrapper_class' => 'mr-2',
                                            'buttons' => array(array(
                                                'active_class' => 'btn-success',
                                                'title' => JText::_('FWMG_DEFAULT'),
                                                'value' => 'def'
                                            ), array(
                                                'active_class' => 'btn-success',
                                                'title' => JText::_('FWMG_SHOW'),
                                                'value' => '1'
                                            ), array(
                                                'active_class' => 'btn-danger',
                                                'title' => JText::_('FWMG_HIDE'),
                                                'value' => '0'
                                            ))
                                        )); ?>
                                    </div>
                                </div>
								<div class="form-group row">
									<label class="col-sm-5 col-form-label clearfix">
										<?php echo JText::_('FWMG_DOC_ADMIN_CATEGORYEDIT_TAB_FILES_SECTION_GRIDITEM_DESCLENGTH'); ?>
										<i class="pull-right fa fa-question-circle" data-container="body" data-trigger="hover" data-toggle="popover" data-bs-toggle="popover" data-placement="top"  data-content="<?php echo esc_attr(JText::_('FWMG_DOC_ADMIN_CATEGORYEDIT_TAB_FILES_SECTION_GRIDITEM_DESCLENGTH_HINT')); ?>"></i>
									</label>
									<div class="col-sm-7">
										<input type="text" class="form-control" name="params[image_description_length]" value="<?php echo esc_attr($this->object->params->get('image_description_length', 100)); ?>" size="20"/>
									</div>
								</div>
<?php
		fwgHelper::triggerEvent('onshowCategoryEditFileListingDesignExtraFields', array('com_fwgallery', $this->object));
?>
                                <div class="form-group row">
                                    <label class="col-sm-5 col-form-label clearfix">
                                        <?php echo JText::_('FWMG_DOC_ADMIN_CATEGORYEDIT_TAB_FILES_SECTION_GRIDITEM_DOWNLOAD'); ?>
                                        <i class="pull-right fa fa-question-circle" data-container="body" data-trigger="hover" data-toggle="popover" data-bs-toggle="popover" data-placement="top" data-content="<?php echo esc_attr(JText::_('FWMG_DOC_ADMIN_CATEGORYEDIT_TAB_FILES_SECTION_GRIDITEM_DOWNLOAD_HINT')); ?>"></i>
                                    </label>
                                    <div class="col-sm-7">
                                        <?php echo JHTMLfwView::radioGroup('params[show_files_download]', $this->object->params->get('show_files_download', 'def'), array(
                                            'wrapper_class' => 'mr-2',
                                            'buttons' => array(array(
                                                'active_class' => 'btn-success',
                                                'title' => JText::_('FWMG_DEFAULT'),
                                                'value' => 'def'
                                            ), array(
                                                'active_class' => 'btn-success',
                                                'title' => JText::_('FWMG_SHOW'),
                                                'value' => '1'
                                            ), array(
                                                'active_class' => 'btn-danger',
                                                'title' => JText::_('FWMG_HIDE'),
                                                'value' => '0'
                                            ))
                                        )); ?>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5 col-form-label clearfix">
                                        <?php echo JText::_('FWMG_DOC_ADMIN_CATEGORYEDIT_TAB_FILES_SECTION_GRIDITEM_DOWNLOADACCESSLEVEL'); ?>
                                        <i class="pull-right fa fa-question-circle" data-container="body" data-trigger="hover" data-toggle="popover" data-bs-toggle="popover" data-placement="top" data-content="<?php echo esc_attr(JText::_('FWMG_DOC_ADMIN_CATEGORYEDIT_TAB_FILES_SECTION_GRIDITEM_DOWNLOADACCESSLEVEL_HINT')); ?>"></i>
                                    </label>
                                    <div class="col-sm-7">
                                        <?php echo JHTML::_('select.genericlist', array_merge(array(
                                            JHTML::_('select.option', 'def', JText::_('FWMG_DEFAULT'), 'id', 'name')
                                        ), fwgHelper::loadviewlevels()), 'params[download_access]', 'class="form-control select-choices"', 'id', 'name', $this->object->params->get('download_access', 'def')); ?>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5 col-form-label clearfix">
                                        <?php echo JText::_('FWMG_DOC_ADMIN_CATEGORYEDIT_TAB_FILES_SECTION_GRIDITEM_FILEINFO'); ?>
                                        <i class="pull-right fa fa-question-circle" data-container="body" data-trigger="hover" data-toggle="popover" data-bs-toggle="popover" data-placement="top" data-content="<?php echo esc_attr(JText::_('FWMG_DOC_ADMIN_CATEGORYEDIT_TAB_FILES_SECTION_GRIDITEM_FILEINFO_HINT')); ?>"></i>
                                    </label>
                                    <div class="col-sm-7">
                                        <?php echo JHTMLfwView::radioGroup('params[show_files_info]', $this->object->params->get('show_files_info', 'def'), array(
                                            'wrapper_class' => 'mr-2',
                                            'buttons' => array(array(
                                                'active_class' => 'btn-success',
                                                'title' => JText::_('FWMG_DEFAULT'),
                                                'value' => 'def'
                                            ), array(
                                                'active_class' => 'btn-success',
                                                'title' => JText::_('FWMG_SHOW'),
                                                'value' => '1'
                                            ), array(
                                                'active_class' => 'btn-danger',
                                                'title' => JText::_('FWMG_HIDE'),
                                                'value' => '0'
                                            ))
                                        )); ?>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5 col-form-label clearfix">
                                        <?php echo JText::_('FWMG_DOC_ADMIN_CATEGORYEDIT_TAB_FILES_SECTION_GRIDITEM_ICON'); ?>
                                        <i class="pull-right fa fa-question-circle" data-container="body" data-trigger="hover" data-toggle="popover" data-bs-toggle="popover" data-placement="top" data-content="<?php echo esc_attr(JText::_('FWMG_DOC_ADMIN_CATEGORYEDIT_TAB_FILES_SECTION_GRIDITEM_ICON_HINT')); ?>"></i>
                                    </label>
                                    <div class="col-sm-7">
                                        <?php echo JHTMLfwView::radioGroup('params[show_file_icon]', $this->object->params->get('show_file_icon', 'def'), array(
                                            'wrapper_class' => 'mr-2',
                                            'buttons' => array(array(
                                                'active_class' => 'btn-success',
                                                'title' => JText::_('FWMG_DEFAULT'),
                                                'value' => 'def'
                                            ), array(
                                                'active_class' => 'btn-success',
                                                'title' => JText::_('FWMG_SHOW'),
                                                'value' => '1'
                                            ), array(
                                                'active_class' => 'btn-danger',
                                                'title' => JText::_('FWMG_HIDE'),
                                                'value' => '0'
                                            ))
                                        )); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
					</div>
                    <div class="col-lg-6 col-sm-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title"><?php echo JText::_('FWMG_DOC_ADMIN_CATEGORYEDIT_TAB_FILES_SECTION_FILE'); ?></h4>
                                <div class="card-subtitle"><?php echo JText::_('FWMG_DOC_ADMIN_CATEGORYEDIT_TAB_FILES_SECTION_FILE_HINT'); ?></div>
                            </div>
                            <div class="card-body">
                                <div class="form-group row">
                                    <label class="col-sm-5 col-form-label clearfix">
                                        <?php echo JText::_('FWMG_DOC_ADMIN_CATEGORYEDIT_TAB_FILES_SECTION_FILE_FULLSCREEN'); ?>
                                        <i class="pull-right fa fa-question-circle" data-container="body" data-trigger="hover" data-toggle="popover" data-bs-toggle="popover" data-placement="top" data-content="<?php echo esc_attr(JText::_('FWMG_DOC_ADMIN_CATEGORYEDIT_TAB_FILES_SECTION_FILE_FULLSCREEN_HINT')); ?>"></i>
                                    </label>
                                    <div class="col-sm-7">
                                        <?php echo JHTMLfwView::radioGroup('params[show_file_fullscreen]', $this->object->params->get('show_file_fullscreen', 'def'), array(
                                            'wrapper_class' => 'mr-2',
                                            'buttons' => array(array(
                                                'active_class' => 'btn-success',
                                                'title' => JText::_('FWMG_DEFAULT'),
                                                'value' => 'def'
                                            ), array(
                                                'active_class' => 'btn-success',
                                                'title' => JText::_('FWMG_SHOW'),
                                                'value' => '1'
                                            ), array(
                                                'active_class' => 'btn-danger',
                                                'title' => JText::_('FWMG_HIDE'),
                                                'value' => '0'
                                            ))
                                        )); ?>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5 col-form-label clearfix">
                                        <?php echo JText::_('FWMG_DOC_ADMIN_CATEGORYEDIT_TAB_FILES_SECTION_FILE_THUMBS'); ?>
                                        <i class="pull-right fa fa-question-circle" data-container="body" data-trigger="hover" data-toggle="popover" data-bs-toggle="popover" data-placement="top" data-content="<?php echo esc_attr(JText::_('FWMG_DOC_ADMIN_CATEGORYEDIT_TAB_FILES_SECTION_FILE_THUMBS_HINT')); ?>"></i>
                                    </label>
                                    <div class="col-sm-7">
                                        <?php echo JHTMLfwView::radioGroup('params[show_file_slideshow]', $this->object->params->get('show_file_slideshow', 'def'), array(
                                            'wrapper_class' => 'mr-2',
                                            'buttons' => array(array(
                                                'active_class' => 'btn-success',
                                                'title' => JText::_('FWMG_DEFAULT'),
                                                'value' => 'def'
                                            ), array(
                                                'active_class' => 'btn-success',
                                                'title' => JText::_('FWMG_SHOW'),
                                                'value' => '1'
                                            ), array(
                                                'active_class' => 'btn-danger',
                                                'title' => JText::_('FWMG_HIDE'),
                                                'value' => '0'
                                            ))
                                        )); ?>
                                    </div>
                                </div>
								<div class="form-group row">
									<label class="col-sm-5 col-form-label clearfix">
										<?php echo JText::_('FWMG_DOC_ADMIN_CATEGORYEDIT_TAB_FILES_SECTION_FILE_CATGEORY'); ?>
										<i class="pull-right fa fa-question-circle" data-container="body" data-trigger="hover" data-toggle="popover" data-bs-toggle="popover" data-placement="top"  data-content="<?php echo esc_attr(JText::_('FWMG_DOC_ADMIN_CATEGORYEDIT_TAB_FILES_SECTION_FILE_CATGEORY_HINT')); ?>"></i>
									</label>
									<div class="col-sm-7">
										<?php echo JHTMLfwView::radioGroup('params[show_back_link]', $this->object->params->get('show_back_link', 'def'), array(
											'wrapper_class' => 'mr-2',
											'buttons' => array(array(
                                                'active_class' => 'btn-success',
                                                'title' => JText::_('FWMG_DEFAULT'),
                                                'value' => 'def'
                                            ), array(
												'active_class' => 'btn-success',
												'title' => JText::_('JYES'),
												'value' => '1'
											), array(
												'active_class' => 'btn-danger',
												'title' => JText::_('JNO'),
												'value' => '0'
											))
										)); ?>
									</div>
								</div>
                                <div class="form-group row">
                                    <label class="col-sm-5 col-form-label clearfix">
                                        <?php echo JText::_('FWMG_DOC_ADMIN_CATEGORYEDIT_TAB_FILES_SECTION_FILE_INFO'); ?>
                                        <i class="pull-right fa fa-question-circle" data-container="body" data-trigger="hover" data-toggle="popover" data-bs-toggle="popover" data-placement="top" data-content="<?php echo esc_attr(JText::_('FWMG_DOC_ADMIN_CATEGORYEDIT_TAB_FILES_SECTION_FILE_INFO_HINT')); ?>"></i>
                                    </label>
                                    <div class="col-sm-7">
                                        <?php echo JHTMLfwView::radioGroup('params[show_file_info]', $this->object->params->get('show_file_info', 'def'), array(
                                            'wrapper_class' => 'mr-2',
                                            'buttons' => array(array(
                                                'active_class' => 'btn-success',
                                                'title' => JText::_('FWMG_DEFAULT'),
                                                'value' => 'def'
                                            ), array(
                                                'active_class' => 'btn-success',
                                                'title' => JText::_('FWMG_SHOW'),
                                                'value' => '1'
                                            ), array(
                                                'active_class' => 'btn-danger',
                                                'title' => JText::_('FWMG_HIDE'),
                                                'value' => '0'
                                            ))
                                        )); ?>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5 col-form-label clearfix">
                                        <?php echo JText::_('FWMG_DOC_ADMIN_CATEGORYEDIT_TAB_FILES_SECTION_FILE_NAME'); ?>
                                        <i class="pull-right fa fa-question-circle" data-container="body" data-trigger="hover" data-toggle="popover" data-bs-toggle="popover" data-placement="top" data-content="<?php echo esc_attr(JText::_('FWMG_DOC_ADMIN_CATEGORYEDIT_TAB_FILES_SECTION_FILE_NAME_HINT')); ?>"></i>
                                    </label>
                                    <div class="col-sm-7">
                                        <?php echo JHTMLfwView::radioGroup('params[show_file_name]', $this->object->params->get('show_file_name', 'def'), array(
                                            'wrapper_class' => 'mr-2',
                                            'buttons' => array(array(
                                                'active_class' => 'btn-success',
                                                'title' => JText::_('FWMG_DEFAULT'),
                                                'value' => 'def'
                                            ), array(
                                                'active_class' => 'btn-success',
                                                'title' => JText::_('FWMG_SHOW'),
                                                'value' => '1'
                                            ), array(
                                                'active_class' => 'btn-danger',
                                                'title' => JText::_('FWMG_HIDE'),
                                                'value' => '0'
                                            ))
                                        )); ?>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5 col-form-label clearfix">
                                        <?php echo JText::_('FWMG_DOC_ADMIN_CATEGORYEDIT_TAB_FILES_SECTION_FILE_UPDATE'); ?>
                                        <i class="pull-right fa fa-question-circle" data-container="body" data-trigger="hover" data-toggle="popover" data-bs-toggle="popover" data-placement="top" data-content="<?php echo esc_attr(JText::_('FWMG_DOC_ADMIN_CATEGORYEDIT_TAB_FILES_SECTION_FILE_UPDATE_HINT')); ?>"></i>
                                    </label>
                                    <div class="col-sm-7">
                                        <?php echo JHTMLfwView::radioGroup('params[show_file_update]', $this->object->params->get('show_file_update', 'def'), array(
                                            'wrapper_class' => 'mr-2',
                                            'buttons' => array(array(
                                                'active_class' => 'btn-success',
                                                'title' => JText::_('FWMG_DEFAULT'),
                                                'value' => 'def'
                                            ), array(
                                                'active_class' => 'btn-success',
                                                'title' => JText::_('FWMG_SHOW'),
                                                'value' => '1'
                                            ), array(
                                                'active_class' => 'btn-danger',
                                                'title' => JText::_('FWMG_HIDE'),
                                                'value' => '0'
                                            ))
                                        )); ?>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5 col-form-label clearfix">
                                        <?php echo JText::_('FWMG_DOC_ADMIN_CATEGORYEDIT_TAB_FILES_SECTION_FILE_OWNER'); ?>
                                        <i class="pull-right fa fa-question-circle" data-container="body" data-trigger="hover" data-toggle="popover" data-bs-toggle="popover" data-placement="top" data-content="<?php echo esc_attr(JText::_('FWMG_DOC_ADMIN_CATEGORYEDIT_TAB_FILES_SECTION_FILE_OWNER_HINT')); ?>"></i>
                                    </label>
                                    <div class="col-sm-7">
                                        <?php echo JHTMLfwView::radioGroup('params[show_file_owner]', $this->object->params->get('show_file_owner', 'def'), array(
                                            'wrapper_class' => 'mr-2',
                                            'buttons' => array(array(
                                                'active_class' => 'btn-success',
                                                'title' => JText::_('FWMG_DEFAULT'),
                                                'value' => 'def'
                                            ), array(
                                                'active_class' => 'btn-success',
                                                'title' => JText::_('FWMG_SHOW'),
                                                'value' => '1'
                                            ), array(
                                                'active_class' => 'btn-danger',
                                                'title' => JText::_('FWMG_HIDE'),
                                                'value' => '0'
                                            ))
                                        )); ?>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5 col-form-label clearfix">
                                        <?php echo JText::_('FWMG_DOC_ADMIN_CATEGORYEDIT_TAB_FILES_SECTION_FILE_DESCRIPTION'); ?>
                                        <i class="pull-right fa fa-question-circle" data-container="body" data-trigger="hover" data-toggle="popover" data-bs-toggle="popover" data-placement="top" data-content="<?php echo esc_attr(JText::_('FWMG_DOC_ADMIN_CATEGORYEDIT_TAB_FILES_SECTION_FILE_DESCRIPTION_HINT')); ?>"></i>
                                    </label>
                                    <div class="col-sm-7">
                                        <?php echo JHTMLfwView::radioGroup('params[show_file_description]', $this->object->params->get('show_file_description', 'def'), array(
                                            'wrapper_class' => 'mr-2',
                                            'buttons' => array(array(
                                                'active_class' => 'btn-success',
                                                'title' => JText::_('FWMG_DEFAULT'),
                                                'value' => 'def'
                                            ), array(
                                                'active_class' => 'btn-success',
                                                'title' => JText::_('FWMG_SHOW'),
                                                'value' => '1'
                                            ), array(
                                                'active_class' => 'btn-danger',
                                                'title' => JText::_('FWMG_HIDE'),
                                                'value' => '0'
                                            ))
                                        )); ?>
                                    </div>
                                </div>
<?php
		fwgHelper::triggerEvent('onshowCategoryEditFileDesignExtraFields', array('com_fwgallery', $this->object));
?>

                                <div class="form-group row">
                                    <label class="col-sm-5 col-form-label clearfix">
                                        <?php echo JText::_('FWMG_DOC_ADMIN_CATEGORYEDIT_TAB_FILES_SECTION_FILE_DOWNLOAD'); ?>
                                        <i class="pull-right fa fa-question-circle" data-container="body" data-trigger="hover" data-toggle="popover" data-bs-toggle="popover" data-placement="top" data-content="<?php echo esc_attr(JText::_('FWMG_DOC_ADMIN_CATEGORYEDIT_TAB_FILES_SECTION_FILE_DOWNLOAD_HINT')); ?>"></i>
                                    </label>
                                    <div class="col-sm-7">
                                        <?php echo JHTMLfwView::radioGroup('params[show_file_download]', $this->object->params->get('show_file_download', 'def'), array(
                                            'wrapper_class' => 'mr-2',
                                            'buttons' => array(array(
                                                'active_class' => 'btn-success',
                                                'title' => JText::_('FWMG_DEFAULT'),
                                                'value' => 'def'
                                            ), array(
                                                'active_class' => 'btn-success',
                                                'title' => JText::_('FWMG_SHOW'),
                                                'value' => '1'
                                            ), array(
                                                'active_class' => 'btn-danger',
                                                'title' => JText::_('FWMG_HIDE'),
                                                'value' => '0'
                                            ))
                                        )); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <input type="hidden" name="id" value="<?php echo (int)$this->object->id; ?>" />
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
var size_limits = [];
<?php
$sizes = fwgHelper::getGridsSizeLimits();
foreach ($sizes as $name=>$size) {
?>
size_limits.push({name:'<?php echo esc_js($name); ?>',sizes:{min_cols: <?php echo (int)$size['min_cols']; ?>, min_rows: <?php echo (int)$size['min_rows']; ?>}});
<?php
}
?>
document.addEventListener('DOMContentLoaded', function() {
    (function($) {
	$('#paramsfiles_grid').change(function() {
		$('input[name="params[files_rows]"]').change();
		$('input[name="params[files_columns]"]').change();
	});
	$('input[name="params[files_rows]"]').change(function() {
		var $inp = $(this);
		var grid = $('#paramsfiles_grid').val();
		for (var i = 0; i < size_limits.length; i++) {
			if (size_limits[i].name == grid) {
				if ($inp.val() * 1 < size_limits[i].sizes.min_rows) {
					$inp.val(size_limits[i].sizes.min_rows);
				}
				break;
			}
		}
	});
	$('input[name="params[files_columns]"]').change(function() {
		var $inp = $(this);
		var grid = $('#paramsfiles_grid').val();
		for (var i = 0; i < size_limits.length; i++) {
			if (size_limits[i].name == grid) {
				if ($inp.val() * 1 < size_limits[i].sizes.min_cols) {
					$inp.parent().find('button[data-value="'+size_limits[i].sizes.min_cols+'"]').click();
				}
				break;
			}
		}
	});
    $('input[name="media"]').change(function() {
        var media = this.value;
        $('.fwmg-media').hide();
        $('.fwmg-'+media).show();
    }).change();
    $('input[name="created"]').datetimepicker({
        format: 'Y-m-d H:i'
    });
    $('input[name="created"]').next().click(function() {
        $('input[name="created"]').focus();
    })
    Joomla.submitbutton = function(pressbutton) {
        if (pressbutton) {
            if (pressbutton == 'import') {
                $('#fwmg-batch-upload').modal('show');
            } else {
                document.adminForm.task.value=pressbutton;
                document.adminForm.submit();
            }
        }
    }
    })(jQuery);
});
</script>
<?php
if ($this->object->id) {
	echo JLayoutHelper::render('utilites.batchupload', array(
		'allusers'=>$this->users,
		'current_user'=>$this->current_user,
		'category'=>$this->object->id,
		'reload'=>false
	), JPATH_COMPONENT);
}
echo JLayoutHelper::render('common.menu_end', array(), JPATH_COMPONENT);
