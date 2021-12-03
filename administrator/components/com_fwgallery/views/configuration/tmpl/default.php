<?php
/**
 * FW Gallery 6.7.2
 * @copyright (C) 2020 Fastw3b
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.fastw3b.net/ Official website
 **/

defined('_JEXEC') or die('Restricted access');

JToolBarHelper::title(JText::_('FWMG_ADMIN_SETTINGS_TOOLBAR_TITLE'), ' fal fa-sliders-h');
fwgButtonsHelper::apply('save', 'FWMG_DOC_ADMIN_SETTINGS_TOOLBAR_BTN_SAVE');

echo JLayoutHelper::render('common.menu_begin', array(
    'view' => $this,
    'title' => JText::_('FWMG_DOC_ADMIN_SETTINGS'),
    'title_hint' => JText::_('FWMG_DOC_ADMIN_SETTINGS_HINT')
), JPATH_COMPONENT);
?>
<form action="index.php?option=com_fwgallery&amp;view=configuration" id="adminForm" name="adminForm" method="post" enctype="multipart/form-data">
    <div class="fwa-filter-bar">
    <ul class="nav nav-tabs" role="tablist">
        <li class="nav-item" data-container="body" data-trigger="hover" data-toggle="popover" data-bs-toggle="popover" data-placement="top" data-content="<?php echo JText::_('FWMG_DOC_ADMIN_SETTINGS_TAB_GENERAL_HINT'); ?>" >
            <a class="nav-link active" data-toggle="tab" data-bs-toggle="tab" href="#fwmg-settings-general" role="tab"><?php echo JText::_('FWMG_DOC_ADMIN_SETTINGS_TAB_GENERAL'); ?></a>
        </li>
        <li class="nav-item" data-container="body" data-trigger="hover" data-toggle="popover" data-bs-toggle="popover" data-placement="top" data-content="<?php echo JText::_('FWMG_DOC_ADMIN_SETTINGS_TAB_CATEGORY_HINT'); ?>" >
            <a class="nav-link" data-toggle="tab" data-bs-toggle="tab" href="#fwmg-settings-galleries" role="tab"><?php echo JText::_('FWMG_DOC_ADMIN_SETTINGS_TAB_CATEGORY'); ?></a>
        </li>
        <li class="nav-item" data-container="body" data-trigger="hover" data-toggle="popover" data-bs-toggle="popover" data-placement="top" data-content="<?php echo JText::_('FWMG_DOC_ADMIN_SETTINGS_TAB_FILES_HINT'); ?>" >
            <a class="nav-link" data-toggle="tab" data-bs-toggle="tab" href="#fwmg-settings-items" role="tab"><?php echo JText::_('FWMG_DOC_ADMIN_SETTINGS_TAB_FILES'); ?></a>
        </li>
        <li class="nav-item" data-container="body" data-trigger="hover" data-toggle="popover" data-bs-toggle="popover" data-placement="top" data-content="<?php echo JText::_('FWMG_DOC_ADMIN_SETTINGS_TAB_ADDONS_HINT'); ?>" >
            <a class="nav-link" data-toggle="tab" data-bs-toggle="tab" href="#fwmg-settings-plugins" role="tab"><?php echo JText::_('FWMG_DOC_ADMIN_SETTINGS_TAB_ADDONS'); ?></a>
        </li>
    </ul>
    </div>
    <div class="tab-content">
        <div class="tab-pane active" id="fwmg-settings-general" role="tabpanel">
            <div class="fwmg-settings-general">
                <div class="row fwa-mb-cardbox">
                    <div class="col-lg-6 col-sm-12">
                        <!-- Genral -->
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title"><?php echo JText::_('FWMG_DOC_ADMIN_SETTINGS_TAB_GENERAL_SECTION_COMMON'); ?></h4>
                                <div class="card-subtitle"><?php echo JText::_('FWMG_DOC_ADMIN_SETTINGS_TAB_GENERAL_SECTION_COMMON_HINT'); ?></div>
                            </div>
                            <div class="card-body">
                                <div class="form-group row">
                                    <label class="col-sm-5 col-form-label clearfix">
                                        <?php echo JText::_('FWMG_DOC_ADMIN_SETTINGS_TAB_GENERAL_SECTION_COMMON_DATE'); ?>
                                        <i class="pull-right fa fa-question-circle" data-container="body" data-trigger="hover" data-toggle="popover" data-bs-toggle="popover" data-placement="top" data-content="<?php echo esc_attr(JText::_('FWMG_DOC_ADMIN_SETTINGS_TAB_GENERAL_SECTION_COMMON_DATE_HINT')); ?>"></i>
                                    </label>
                                    <div class="col-sm-3">
                                        <input class="form-control mb-0" type="text" name="config[date_format]" value="<?php echo esc_attr($this->object->params->get('date_format', 'F d, Y')); ?>" />
                                    </div>
                                    <div class="col-sm-4"><a href="https://www.php.net/manual/en/datetime.format.php#refsect1-datetime.format-parameters" target="_blank"><?php echo JText::_('FWMG_DATE_FORMAT_HINT'); ?> <i class="fal fa-external-link"></i></a></div>
                                </div>

								<div class="form-group row">
									<label class="col-sm-5 col-form-label clearfix">
										<?php echo JText::_('FWMG_DOC_ADMIN_SETTINGS_TAB_GENERAL_SECTION_COMMON_BOOTSTRAP'); ?>
										<i class="pull-right fa fa-question-circle" data-container="body" data-trigger="hover" data-toggle="popover" data-bs-toggle="popover" data-placement="top" data-content="<?php echo esc_attr(JText::_('FWMG_DOC_ADMIN_SETTINGS_TAB_GENERAL_SECTION_COMMON_BOOTSTRAP_HINT')); ?>"></i>
									</label>
									<div class="col-sm-7">
										<?php echo JHTMLfwView::radioGroup('config[do_not_load_bootstrap]', (int)$this->object->params->get('do_not_load_bootstrap'), array(
											'wrapper_class' => 'mr-2',
											'buttons' => array(array(
												'active_class' => 'btn-success',
												'title' => JText::_('JYES'),
												'value' => 0
											), array(
												'active_class' => 'btn-danger',
												'title' => JText::_('JNO'),
												'value' => 1
											))
										)); ?>
									</div>
								</div>
								<div class="form-group row">
									<label class="col-sm-5 col-form-label clearfix">
										<?php echo JText::_('FWMG_DOC_ADMIN_SETTINGS_TAB_GENERAL_SECTION_COMMON_FA'); ?>
										<i class="pull-right fa fa-question-circle" data-container="body" data-trigger="hover" data-toggle="popover" data-bs-toggle="popover" data-placement="top" data-content="<?php echo esc_attr(JText::_('FWMG_DOC_ADMIN_SETTINGS_TAB_GENERAL_SECTION_COMMON_FA_HINT')); ?>"></i>
									</label>
									<div class="col-sm-7">
										<?php echo JHTMLfwView::radioGroup('config[do_not_load_awesome]', (int)$this->object->params->get('do_not_load_awesome'), array(
											'wrapper_class' => 'mr-2',
											'buttons' => array(array(
												'active_class' => 'btn-success',
												'title' => JText::_('JYES'),
												'value' => 0
											), array(
												'active_class' => 'btn-danger',
												'title' => JText::_('JNO'),
												'value' => 1
											))
										)); ?>
									</div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-5">
                                        <?php echo JText::_('FWMG_DOC_ADMIN_SETTINGS_TAB_GENERAL_SECTION_COMMON_FONT'); ?>
                                        <i class="pull-right fa fa-question-circle" data-container="body" data-trigger="hover" data-toggle="popover" data-bs-toggle="popover" data-placement="top" data-content="<?php echo esc_attr(JText::_('FWMG_DOC_ADMIN_SETTINGS_TAB_GENERAL_SECTION_COMMON_FONT_HINT')); ?>"></i>
                                    </div>
                                    <div class="col-3">
                                        <div class="input-group input-group-sm">
                                            <input class="form-control" value="<?php echo esc_attr($this->object->params->get('font_size', 16)); ?>" type="text" name="config[font_size]" />
                                            <div class="input-group-append">
                                                <span class="input-group-text" id="basic-addon2"><?php echo JText::_('FWMG_ADMIN_PX'); ?></span>
                                            </div>
                                        </div>								
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-5">
                                        <?php echo JText::_('FWMG_DOC_ADMIN_SETTINGS_TAB_GENERAL_SECTION_COMMON_ADDONS'); ?>
                                        <i class="pull-right fa fa-question-circle" data-container="body" data-trigger="hover" data-toggle="popover" data-bs-toggle="popover" data-placement="top" data-content="<?php echo esc_attr(JText::_('FWMG_DOC_ADMIN_SETTINGS_TAB_GENERAL_SECTION_COMMON_ADDONS_HINT')); ?>"></i>
                                    </div>
                                    <div class="col-sm-7">
                                        <?php echo JHTMLfwView::radioGroup('config[show_addons_menu_settings]', (int)$this->object->params->get('show_addons_menu_settings'), array(
                                            'wrapper_class' => 'mr-2',
                                            'buttons' => array(array(
                                                'active_class' => 'btn-success',
                                                'title' => JText::_('FWMG_SHOW'),
                                                'value' => 1
                                            ), array(
                                                'active_class' => 'btn-danger',
                                                'title' => JText::_('FWMG_HIDE'),
                                                'value' => 0
                                            ))
                                        )); ?>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-5">
                                        <?php echo JText::_('FWMG_DOC_ADMIN_SETTINGS_TAB_GENERAL_SECTION_COMMON_AUTOUPDATE'); ?>
                                        <i class="pull-right fa fa-question-circle" data-container="body" data-trigger="hover" data-toggle="popover" data-bs-toggle="popover" data-placement="top" data-content="<?php echo esc_attr(JText::_('FWMG_DOC_ADMIN_SETTINGS_TAB_GENERAL_SECTION_COMMON_AUTOUPDATE_HINT')); ?>"></i>
                                    </div>
                                    <div class="col-sm-7">
                                        <?php echo JHTMLfwView::radioGroup('config[auto_update]', (int)$this->object->params->get('auto_update'), array(
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
                            </div>
                        </div>
                        <!-- Comments -->
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title"><?php echo JText::_('FWMG_DOC_ADMIN_SETTINGS_TAB_GENERAL_SECTION_COMMENTS'); ?></h4>
                                <div class="card-subtitle"><?php echo JText::_('FWMG_DOC_ADMIN_SETTINGS_TAB_GENERAL_SECTION_COMMENTS_HINT'); ?></div>
                            </div>
                            <div class="card-body">
                                <div class="form-group row">
                                    <label class="col-sm-5 col-form-label clearfix">
                                        <?php echo JText::_('FWMG_DOC_ADMIN_SETTINGS_TAB_GENERAL_SECTION_COMMENTS_SYSTEM'); ?>
                                        <i class="pull-right fa fa-question-circle" data-container="body" data-trigger="hover" data-toggle="popover" data-bs-toggle="popover" data-placement="top" data-content="<?php echo esc_attr(JText::_('FWMG_DOC_ADMIN_SETTINGS_TAB_GENERAL_SECTION_COMMENTS_SYSTEM_HINT')); ?>"></i>
                                    </label>
                                    <div class="col-sm-7">
										<?php echo JHTML::_('select.genericlist', array(
											JHTML::_('select.option', '', JText::_('FWMG_None'), 'id', 'name'),
											JHTML::_('select.option', 'disqus', 'Disqus', 'id', 'name'),
											JHTML::_('select.option', 'komento', 'Komento', 'id', 'name')
										), 'config[comments_type]', 'class="form-control select-choices"', 'id', 'name', $this->object->params->get('comments_type'), 'fwmg-comments'); ?>
                                    </div>
                                </div>
                                <div class="form-group row comments_type comments_type-disqus" style="display:none;">
                                    <label class="col-sm-5 col-form-label clearfix">
                                        <?php echo JText::_('FWMG_DOC_ADMIN_SETTINGS_TAB_GENERAL_SECTION_COMMENTS_DISQUS'); ?>
                                        <i class="pull-right fa fa-question-circle" data-container="body" data-trigger="hover" data-toggle="popover" data-bs-toggle="popover" data-placement="top" data-content="<?php echo esc_attr(JText::_('FWMG_DOC_ADMIN_SETTINGS_TAB_GENERAL_SECTION_COMMENTS_DISQUS_HINT')); ?>"></i>
                                    </label>
                                    <div class="col-sm-7">
										<input type="text" class="form-control" name="config[disqus_domain]" value="<?php echo esc_attr($this->object->params->get('disqus_domain')); ?>" size="20"/>
                                    </div>
                                </div>
                                <script>
document.addEventListener('DOMContentLoaded', function() {
    (function($) {
        $('#fwmg-comments').change(function() {
            var $wrp = $(this).closest('.card-body');
            var class_name = 'comments_type-'+this.value;
            $wrp.find('.comments_type').each(function() {
                var $el = $(this);
                if ($el.hasClass(class_name)) {
                    $el.show();
                } else {
                    $el.hide();
                }
            });
        }).change();
    })(jQuery);
});                                </script>
                            </div>
                        </div>
<?php
		fwgHelper::triggerEvent('onshowConfigGeneralLeftExtraCards', array('com_fwgallery', $this->object));
?>
                    </div>
                    <div class="col-lg-6 col-sm-12">
                        <!-- Images Sizes -->
                        <div class="card">
                            <div class="card-header clearfix">
                                <h4 class="card-title"><?php echo JText::_('FWMG_DOC_ADMIN_SETTINGS_TAB_GENERAL_SECTION_SIZES'); ?></h4>
                                <div class="card-subtitle"><?php echo JText::_('FWMG_DOC_ADMIN_SETTINGS_TAB_GENERAL_SECTION_SIZES_HINT'); ?></div>
                            </div>
                            <div class="card-body">
                                <div class="form-group row">
                                    <label class="col-sm-5 col-form-label clearfix">
                                        <?php echo JText::_('FWMG_DOC_ADMIN_SETTINGS_TAB_GENERAL_SECTION_SIZES_MAX'); ?>
                                        <i class="pull-right fa fa-question-circle" data-container="body" data-trigger="hover" data-toggle="popover" data-bs-toggle="popover" data-placement="top" data-content="<?php echo esc_attr(JText::_('FWMG_DOC_ADMIN_SETTINGS_TAB_GENERAL_SECTION_SIZES_MAX_HINT')); ?>"></i>
                                    </label>
                                    <div class="col-sm-7">
                                        <div class="input-group input-group-sm float-left mr-4" style="max-width:130px;">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text small"><?php echo esc_attr(JText::_('FWMG_ADMIN_WIDTH')); ?></span>
                                            </div>
                                            <input type="text" name="config[image_width]" value="<?php echo esc_attr($this->object->params->get('image_width', 2048)); ?>" class="form-control text-center" size="4" />
                                            <div class="input-group-append">
                                                <span class="input-group-text small"><?php echo esc_attr(JText::_('FWMG_ADMIN_PX')); ?></span>
                                            </div>
                                        </div>
                                        <div class="input-group input-group-sm" style="max-width:130px;">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text small"><?php echo esc_attr(JText::_('FWMG_ADMIN_HEIGHT')); ?></span>
                                            </div>
                                            <input type="text" name="config[image_height]" value="<?php echo esc_attr($this->object->params->get('image_height', 2048)); ?>" class="form-control text-center" size="4" />
                                            <div class="input-group-append">
                                                <span class="input-group-text small"><?php echo esc_attr(JText::_('FWMG_ADMIN_PX')); ?></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5 col-form-label clearfix">
                                        <?php echo JText::_('FWMG_DOC_ADMIN_SETTINGS_TAB_GENERAL_SECTION_SIZES_COVER'); ?>
                                        <i class="pull-right fa fa-question-circle" data-container="body" data-trigger="hover" data-toggle="popover" data-bs-toggle="popover" data-placement="top" data-content="<?php echo esc_attr(JText::_('FWMG_DOC_ADMIN_SETTINGS_TAB_GENERAL_SECTION_SIZES_COVER_HINT')); ?>"></i>
                                    </label>
                                    <div class="col-sm-7">
                                    <div class="input-group input-group-sm float-left mr-4" style="max-width:130px;">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text small"><?php echo esc_attr(JText::_('FWMG_ADMIN_WIDTH')); ?></span>
                                            </div>
                                            <input type="text" name="config[image_width_cover]" value="<?php echo esc_attr($this->object->params->get('image_width_cover', 600)); ?>" class="form-control text-center" size="4" />
                                            <div class="input-group-append">
                                                <span class="input-group-text small"><?php echo esc_attr(JText::_('FWMG_ADMIN_PX')); ?></span>
                                            </div>
                                        </div>
                                        <div class="input-group input-group-sm" style="max-width:130px;">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text small"><?php echo esc_attr(JText::_('FWMG_ADMIN_HEIGHT')); ?></span>
                                            </div>
                                            <input type="text" name="config[image_height_cover]" value="<?php echo esc_attr($this->object->params->get('image_height_cover', 400)); ?>" class="form-control text-center" size="4" />
                                            <div class="input-group-append">
                                                <span class="input-group-text small"><?php echo esc_attr(JText::_('FWMG_ADMIN_PX')); ?></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5 col-form-label clearfix">
                                        <?php echo JText::_('FWMG_DOC_ADMIN_SETTINGS_TAB_GENERAL_SECTION_SIZES_THUMB'); ?>
                                        <i class="pull-right fa fa-question-circle" data-container="body" data-trigger="hover" data-toggle="popover" data-bs-toggle="popover" data-placement="top" data-content="<?php echo esc_attr(JText::_('FWMG_DOC_ADMIN_SETTINGS_TAB_GENERAL_SECTION_SIZES_THUMB_HINT')); ?>"></i>
                                    </label>
                                    <div class="col-sm-7">
                                    <div class="input-group input-group-sm float-left mr-4" style="max-width:130px;">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text small"><?php echo esc_attr(JText::_('FWMG_ADMIN_WIDTH')); ?></span>
                                            </div>
                                            <input type="text" name="config[image_width_listing]" value="<?php echo esc_attr($this->object->params->get('image_width_listing', 600)); ?>" class="form-control text-center" size="4" />
                                            <div class="input-group-append">
                                                <span class="input-group-text small"><?php echo esc_attr(JText::_('FWMG_ADMIN_PX')); ?></span>
                                            </div>
                                        </div>
                                        <div class="input-group input-group-sm" style="max-width:130px;">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text small"><?php echo esc_attr(JText::_('FWMG_ADMIN_HEIGHT')); ?></span>
                                            </div>
                                            <input type="text" name="config[image_height_listing]" value="<?php echo esc_attr($this->object->params->get('image_height_listing', 400)); ?>" class="form-control text-center" size="4" />
                                            <div class="input-group-append">
                                                <span class="input-group-text small"><?php echo esc_attr(JText::_('FWMG_ADMIN_PX')); ?></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5 col-form-label clearfix">
                                        <?php echo JText::_('FWMG_DOC_ADMIN_SETTINGS_TAB_GENERAL_SECTION_SIZES_FILE'); ?>
                                        <i class="pull-right fa fa-question-circle" data-container="body" data-trigger="hover" data-toggle="popover" data-bs-toggle="popover" data-placement="top" data-content="<?php echo esc_attr(JText::_('FWMG_DOC_ADMIN_SETTINGS_TAB_GENERAL_SECTION_SIZES_FILE_HINT')); ?>"></i>
                                    </label>
                                    <div class="col-sm-7">
                                    <div class="input-group input-group-sm float-left mr-4" style="max-width:130px;">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text small"><?php echo esc_attr(JText::_('FWMG_ADMIN_WIDTH')); ?></span>
                                            </div>
                                            <input type="text" name="config[image_width_lightbox]" value="<?php echo esc_attr($this->object->params->get('image_width_lightbox', 1200)); ?>" class="form-control text-center" size="4" />
                                            <div class="input-group-append">
                                                <span class="input-group-text small"><?php echo esc_attr(JText::_('FWMG_ADMIN_PX')); ?></span>
                                            </div>
                                        </div>
                                        <div class="input-group input-group-sm" style="max-width:130px;">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text small"><?php echo esc_attr(JText::_('FWMG_ADMIN_HEIGHT')); ?></span>
                                            </div>
                                            <input type="text" name="config[image_height_lightbox]" value="<?php echo esc_attr($this->object->params->get('image_height_lightbox', 800)); ?>" class="form-control text-center" size="4" />
                                            <div class="input-group-append">
                                                <span class="input-group-text small"><?php echo esc_attr(JText::_('FWMG_ADMIN_PX')); ?></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Watermark -->
                        <div class="card">
                            <div class="card-header clearfix">
                                <button type="button" onclick="Joomla.submitbutton('save');" class="btn active float-right"><i class="fal fa-save mr-1"></i> <?php echo JText::_('FWMG_SAVE_APPLY'); ?></button>
                                <h4 class="card-title"><?php echo JText::_('FWMG_DOC_ADMIN_SETTINGS_TAB_GENERAL_SECTION_WATERMARK'); ?></h4>
                                <div class="card-subtitle"><?php echo JText::_('FWMG_DOC_ADMIN_SETTINGS_TAB_GENERAL_SECTION_WATERMARK_HINT'); ?></div>
                            </div>
                            <div class="card-body">
                                <div class="form-group row">
                                    <label class="col-sm-5 col-form-label clearfix">
                                        <?php echo JText::_('FWMG_DOC_ADMIN_SETTINGS_TAB_GENERAL_SECTION_WATERMARK_USE'); ?>
                                        <i class="pull-right fa fa-question-circle" data-container="body" data-trigger="hover" data-toggle="popover" data-bs-toggle="popover" data-placement="top" data-content="<?php echo esc_attr(JText::_('FWMG_DOC_ADMIN_SETTINGS_TAB_GENERAL_SECTION_WATERMARK_USE_HINT')); ?>"></i>
                                    </label>
                                    <div class="col-sm-7">
										<?php echo JHTMLfwView::radioGroup('config[use_watermark]', $this->object->params->get('use_watermark'), array(
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
                                        <?php echo JText::_('FWMG_DOC_ADMIN_SETTINGS_TAB_GENERAL_SECTION_WATERMARK_POSITION'); ?>
                                        <i class="pull-right fa fa-question-circle" data-container="body" data-trigger="hover" data-toggle="popover" data-bs-toggle="popover" data-placement="top" data-content="<?php echo esc_attr(JText::_('FWMG_DOC_ADMIN_SETTINGS_TAB_GENERAL_SECTION_WATERMARK_POSITION_HINT')); ?>"></i>
                                    </label>
                                    <div class="col-sm-7">
										<?php echo JHTML::_('select.genericlist', array(
											JHTML::_('select.option', 'center', JText::_('FWMG_CENTER'), 'id', 'name'),
											JHTML::_('select.option', 'left top', JText::_('FWMG_LEFT_TOP'), 'id', 'name'),
											JHTML::_('select.option', 'right top', JText::_('FWMG_RIGHT_TOP'), 'id', 'name'),
											JHTML::_('select.option', 'left bottom', JText::_('FWMG_LEFT_BOTTOM'), 'id', 'name'),
											JHTML::_('select.option', 'right bottom', JText::_('FWMG_RIGHT_BOTTOM'), 'id', 'name')
										), 'config[watermark_position]', 'class="form-control select-choices"', 'id', 'name', $this->object->params->get('watermark_position', 'left bottom')); ?>
                                    </div>
                                </div>
                                <div class="form-group row fwmg-watermark">
                                    <label class="col-sm-5 col-form-label clearfix">
                                        <?php echo JText::_('FWMG_DOC_ADMIN_SETTINGS_TAB_GENERAL_SECTION_WATERMARK_IMAGE'); ?>
                                        <i class="pull-right fa fa-question-circle" data-container="body" data-trigger="hover" data-toggle="popover" data-bs-toggle="popover" data-placement="top" data-content="<?php echo esc_attr(JText::_('FWMG_DOC_ADMIN_SETTINGS_TAB_GENERAL_SECTION_WATERMARK_IMAGE_HINT')); ?>"></i>
                                    </label>
                                    <div class="col-sm-7">
<?php
$wmf = $this->object->params->get('watermark_file');
if ($wmf) {
	if ($path = fwgHelper::getWatermarkFilename()) {
?>
										<img src="<?php echo esc_attr(JURI::root(true)); ?>/<?php echo esc_attr($path); ?>" /><br/>
										<input type="checkbox" name="delete_watermark" value="1" /> <?php echo JText::_('FWMG_REMOVE_WATERMARK'); ?><br/>
<?php
	} else {
?>
										<p style="color:#f00;"><?php echo JText::sprintf('FWMG_WATERMARK_FILE_NOT_FOUND_', $wmf); ?></p>
<?php
	}
}
?>
                                        <div class="input-group">
                                            <div class="custom-file">
                                                <input id="watermark_file" class="custom-file-input" type="file" name="watermark_file" />
                                                <label class="custom-file-label" for="fwmg-image"><?php echo JText::_('FWMG_SELECT_IMAGE'); ?></label>
                                            </div>
                                        </div>
                                        <div class="text-muted"><small><?php echo JText::_('FWMG_MAXIMUM_FILE_SIZE'); ?> <?php echo fwgHelper::humanFileSize(fwgHelper::getIniSize('upload_max_filesize')); ?></small></div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5 col-form-label clearfix">
                                        <?php echo JText::_('FWMG_DOC_ADMIN_SETTINGS_TAB_GENERAL_SECTION_WATERMARK_TEXT'); ?>
                                        <i class="pull-right fa fa-question-circle" data-container="body" data-trigger="hover" data-toggle="popover" data-bs-toggle="popover" data-placement="top" data-content="<?php echo esc_attr(JText::_('FWMG_DOC_ADMIN_SETTINGS_TAB_GENERAL_SECTION_WATERMARK_TEXT_HINT')); ?>"></i>
                                    </label>
                                    <div class="col-sm-7">
										<input type="text" class="form-control mb-0" name="config[watermark_text]" value="<?php echo esc_attr($this->object->params->get('watermark_text')); ?>" />
                                        <div class="text-muted"><small><?php echo JText::_('FWMG_WATERMARK_TEXT_HINT'); ?></small></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- FWG Settings Galleries -->
        <div class="tab-pane" id="fwmg-settings-galleries" role="tabpanel">
            <div class="fwmg-settings-galleries">
                <div class="row fwa-mb-cardbox">
                    <div class="col-lg-6 col-sm-12">
                        <!-- Gallery Header -->
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title"><?php echo JText::_('FWMG_DOC_ADMIN_SETTINGS_TAB_CATEGORY_SECTION_HEADER'); ?></h4>
                                <div class="card-subtitle"><?php echo JText::_('FWMG_DOC_ADMIN_SETTINGS_TAB_CATEGORY_SECTION_HEADER_HINT'); ?></div>
                            </div>
                            <div class="card-body">
                                <div class="form-group row">
                                    <label class="col-sm-5 col-form-label clearfix">
                                        <?php echo JText::_('FWMG_DOC_ADMIN_SETTINGS_TAB_CATEGORY_SECTION_HEADER_NAME'); ?>
                                        <i class="pull-right fa fa-question-circle" data-container="body" data-trigger="hover" data-toggle="popover" data-bs-toggle="popover" data-placement="top" data-content="<?php echo esc_attr(JText::_('FWMG_DOC_ADMIN_SETTINGS_TAB_CATEGORY_SECTION_HEADER_NAME_HINT')); ?>"></i>
                                    </label>
                                    <div class="col-sm-7">
                                        <?php echo JHTMLfwView::radioGroup('config[show_gallery_name]', $this->object->params->get('show_gallery_name', 1), array(
                                            'wrapper_class' => 'mr-2',
                                            'buttons' => array(array(
                                                'active_class' => 'btn-success',
                                                'title' => JText::_('FWMG_SHOW'),
                                                'value' => 1
                                            ), array(
                                                'active_class' => 'btn-danger',
                                                'title' => JText::_('FWMG_HIDE'),
                                                'value' => 0
                                            ))
                                        )); ?>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5 col-form-label clearfix">
                                        <?php echo JText::_('FWMG_DOC_ADMIN_SETTINGS_TAB_CATEGORY_SECTION_HEADER_UPDATE'); ?>
                                        <i class="pull-right fa fa-question-circle" data-container="body" data-trigger="hover" data-toggle="popover" data-bs-toggle="popover" data-placement="top" data-content="<?php echo esc_attr(JText::_('FWMG_DOC_ADMIN_SETTINGS_TAB_CATEGORY_SECTION_HEADER_UPDATE_HINT')); ?>"></i>
                                    </label>
                                    <div class="col-sm-7">
                                        <?php echo JHTMLfwView::radioGroup('config[show_gallery_update_date]', $this->object->params->get('show_gallery_update_date', 1), array(
                                            'wrapper_class' => 'mr-2',
                                            'buttons' => array(array(
                                                'active_class' => 'btn-success',
                                                'title' => JText::_('FWMG_SHOW'),
                                                'value' => 1
                                            ), array(
                                                'active_class' => 'btn-danger',
                                                'title' => JText::_('FWMG_HIDE'),
                                                'value' => 0
                                            ))
                                        )); ?>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5 col-form-label clearfix">
                                        <?php echo JText::_('FWMG_DOC_ADMIN_SETTINGS_TAB_CATEGORY_SECTION_HEADER_OWNER'); ?>
                                        <i class="pull-right fa fa-question-circle" data-container="body" data-trigger="hover" data-toggle="popover" data-bs-toggle="popover" data-placement="top" data-content="<?php echo esc_attr(JText::_('FWMG_DOC_ADMIN_SETTINGS_TAB_CATEGORY_SECTION_HEADER_OWNER_HINT')); ?>"></i>
                                    </label>
                                    <div class="col-sm-7">
                                        <?php echo JHTMLfwView::radioGroup('config[show_gallery_owner]', $this->object->params->get('show_gallery_owner', 1), array(
                                            'wrapper_class' => 'mr-2',
                                            'buttons' => array(array(
                                                'active_class' => 'btn-success',
                                                'title' => JText::_('FWMG_SHOW'),
                                                'value' => 1
                                            ), array(
                                                'active_class' => 'btn-danger',
                                                'title' => JText::_('FWMG_HIDE'),
                                                'value' => 0
                                            ))
                                        )); ?>
                                    </div>
                                </div>
<?php
		fwgHelper::triggerEvent('onshowConfigCategoryDesignExtraFields', array('com_fwgallery', $this->object));
?>
                                <div class="form-group row">
                                    <label class="col-sm-5 col-form-label clearfix">
                                        <?php echo JText::_('FWMG_DOC_ADMIN_SETTINGS_TAB_CATEGORY_SECTION_HEADER_COUNTER'); ?>
                                        <i class="pull-right fa fa-question-circle" data-container="body" data-trigger="hover" data-toggle="popover" data-bs-toggle="popover" data-placement="top" data-content="<?php echo esc_attr(JText::_('FWMG_DOC_ADMIN_SETTINGS_TAB_CATEGORY_SECTION_HEADER_COUNTER_HINT')); ?>"></i>
                                    </label>
                                    <div class="col-sm-7">
                                        <?php echo JHTMLfwView::radioGroup('config[show_gallery_counter]', $this->object->params->get('show_gallery_counter', 1), array(
                                            'wrapper_class' => 'mr-2',
                                            'buttons' => array(array(
                                                'active_class' => 'btn-success',
                                                'title' => JText::_('FWMG_SHOW'),
                                                'value' => 1
                                            ), array(
                                                'active_class' => 'btn-danger',
                                                'title' => JText::_('FWMG_HIDE'),
                                                'value' => 0
                                            ))
                                        )); ?>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5 col-form-label clearfix">
                                        <?php echo JText::_('FWMG_DOC_ADMIN_SETTINGS_TAB_CATEGORY_SECTION_HEADER_DESCRIPTION'); ?>
                                        <i class="pull-right fa fa-question-circle" data-container="body" data-trigger="hover" data-toggle="popover" data-bs-toggle="popover" data-placement="top" data-content="<?php echo esc_attr(JText::_('FWMG_DOC_ADMIN_SETTINGS_TAB_CATEGORY_SECTION_HEADER_DESCRIPTION_HINT')); ?>"></i>
                                    </label>
                                    <div class="col-sm-7">
                                        <?php echo JHTMLfwView::radioGroup('config[show_gallery_description]', $this->object->params->get('show_gallery_description', 1), array(
                                            'wrapper_class' => 'mr-2',
                                            'buttons' => array(array(
                                                'active_class' => 'btn-success',
                                                'title' => JText::_('FWMG_SHOW'),
                                                'value' => 1
                                            ), array(
                                                'active_class' => 'btn-danger',
                                                'title' => JText::_('FWMG_HIDE'),
                                                'value' => 0
                                            ))
                                        )); ?>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5 col-form-label clearfix">
                                        <?php echo JText::_('FWMG_DOC_ADMIN_SETTINGS_TAB_CATEGORY_SECTION_HEADER_DESCPOSITION'); ?>
                                        <i class="pull-right fa fa-question-circle" data-container="body" data-trigger="hover" data-toggle="popover" data-bs-toggle="popover" data-placement="top" data-content="<?php echo esc_attr(JText::_('FWMG_DOC_ADMIN_SETTINGS_TAB_CATEGORY_SECTION_HEADER_DESCPOSITION_HINT')); ?>"></i>
                                    </label>
                                    <div class="col-sm-7">
                                        <?php echo JHTMLfwView::radioGroup('config[gallery_descr_position]', $this->object->params->get('gallery_descr_position', 'top'), array(
                                            'wrapper_class' => 'mr-2',
                                            'buttons' => array(array(
                                                'active_class' => 'btn-success',
                                                'title' => JText::_('FWMG_TOP'),
                                                'value' => 'top'
                                            ), array(
                                                'active_class' => 'btn-warning',
                                                'title' => JText::_('FWMG_RIGHT'),
                                                'value' => 'right'
                                            ))
                                        )); ?>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5 col-form-label clearfix">
                                        <?php echo JText::_('FWMG_DOC_ADMIN_SETTINGS_TAB_CATEGORY_SECTION_HEADER_BREADCRUMBS'); ?>
                                        <i class="pull-right fa fa-question-circle" data-container="body" data-trigger="hover" data-toggle="popover" data-bs-toggle="popover" data-placement="top" data-content="<?php echo esc_attr(JText::_('FWMG_DOC_ADMIN_SETTINGS_TAB_CATEGORY_SECTION_HEADER_BREADCRUMBS_HINT')); ?>"></i>
                                    </label>
                                    <div class="col-sm-7">
                                        <?php echo JHTMLfwView::radioGroup('config[show_gallery_breadcrumbs]', $this->object->params->get('show_gallery_breadcrumbs', 1), array(
                                            'wrapper_class' => 'mr-2',
                                            'buttons' => array(array(
                                                'active_class' => 'btn-success',
                                                'title' => JText::_('FWMG_SHOW'),
                                                'value' => 1
                                            ), array(
                                                'active_class' => 'btn-danger',
                                                'title' => JText::_('FWMG_HIDE'),
                                                'value' => 0
                                            ))
                                        )); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Gallery Layout -->
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title"><?php echo JText::_('FWMG_DOC_ADMIN_SETTINGS_TAB_CATEGORY_SECTION_GRID'); ?></h4>
                                <div class="card-subtitle"><?php echo JText::_('FWMG_DOC_ADMIN_SETTINGS_TAB_CATEGORY_SECTION_GRID_HINT'); ?></div>
                            </div>
                            <div class="card-body">
                                <div class="form-group row">
                                    <label class="col-sm-5 col-form-label clearfix">
                                        <?php echo JText::_('FWMG_DOC_ADMIN_SETTINGS_TAB_CATEGORY_SECTION_GRID_COLUMNS'); ?>
                                        <i class="pull-right fa fa-question-circle" data-container="body" data-trigger="hover" data-toggle="popover" data-bs-toggle="popover" data-placement="top" data-content="<?php echo esc_attr(JText::_('FWMG_DOC_ADMIN_SETTINGS_TAB_CATEGORY_SECTION_GRID_COLUMNS_HINT')); ?>"></i>
                                    </label>
                                    <div class="col-sm-7">
                                        <?php echo JHTMLfwView::radioGroup('config[gallery_columns]', $this->object->params->get('gallery_columns', 4), array(
                                            'wrapper_class' => 'mr-2',
                                            'buttons' => array(array(
                                                'active_class' => 'btn-success',
                                                'title' => 1,
                                                'value' => 1
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
                                        <?php echo JText::_('FWMG_DOC_ADMIN_SETTINGS_TAB_CATEGORY_SECTION_GRID_ROWS'); ?>
                                        <i class="pull-right fa fa-question-circle" data-container="body" data-trigger="hover" data-toggle="popover" data-bs-toggle="popover" data-placement="top" data-content="<?php echo esc_attr(JText::_('FWMG_DOC_ADMIN_SETTINGS_TAB_CATEGORY_SECTION_GRID_ROWS_HINT')); ?>"></i>
                                    </label>
                                    <div class="col-sm-7">
                                        <input class="form-control col-3" type="text" name="config[gallery_rows]" value="<?php echo esc_attr($this->object->params->get('gallery_rows', 4)); ?>" />
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5 col-form-label clearfix">
                                        <?php echo JText::_('FWMG_DOC_ADMIN_SETTINGS_TAB_CATEGORY_SECTION_GRID_LIMIT'); ?>
                                        <i class="pull-right fa fa-question-circle" data-container="body" data-trigger="hover" data-toggle="popover" data-bs-toggle="popover" data-placement="top" data-content="<?php echo esc_attr(JText::_('FWMG_DOC_ADMIN_SETTINGS_TAB_CATEGORY_SECTION_GRID_LIMIT_HINT')); ?>"></i>
                                    </label>
                                    <div class="col-sm-7">
                                        <?php echo JHTMLfwView::radioGroup('config[show_gallery_limit]', $this->object->params->get('show_gallery_limit', 1), array(
                                            'wrapper_class' => 'mr-2',
                                            'buttons' => array(array(
                                                'active_class' => 'btn-success',
                                                'title' => JText::_('FWMG_SHOW'),
                                                'value' => 1
                                            ), array(
                                                'active_class' => 'btn-danger',
                                                'title' => JText::_('FWMG_HIDE'),
                                                'value' => 0
                                            ))
                                        )); ?>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5 col-form-label clearfix">
                                        <?php echo JText::_('FWMG_DOC_ADMIN_SETTINGS_TAB_CATEGORY_SECTION_GRID_ORDERING'); ?>
                                        <i class="pull-right fa fa-question-circle" data-container="body" data-trigger="hover" data-toggle="popover" data-bs-toggle="popover" data-placement="top" data-content="<?php echo esc_attr(JText::_('FWMG_DOC_ADMIN_SETTINGS_TAB_CATEGORY_SECTION_GRID_ORDERING_HINT')); ?>"></i>
                                    </label>
                                    <div class="col-sm-7">
                                        <?php echo JHTMLfwView::radioGroup('config[show_gallery_ordering]', $this->object->params->get('show_gallery_ordering', 1), array(
                                            'wrapper_class' => 'mr-2',
                                            'buttons' => array(array(
                                                'active_class' => 'btn-success',
                                                'title' => JText::_('FWMG_SHOW'),
                                                'value' => 1
                                            ), array(
                                                'active_class' => 'btn-danger',
                                                'title' => JText::_('FWMG_HIDE'),
                                                'value' => 0
                                            ))
                                        )); ?>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5 col-form-label clearfix">
                                        <?php echo JText::_('FWMG_DOC_ADMIN_SETTINGS_TAB_CATEGORY_SECTION_GRID_ORDERINGDEFAULT'); ?>
                                        <i class="pull-right fa fa-question-circle" data-container="body" data-trigger="hover" data-toggle="popover" data-bs-toggle="popover" data-placement="top" data-content="<?php echo esc_attr(JText::_('FWMG_DOC_ADMIN_SETTINGS_TAB_CATEGORY_SECTION_GRID_ORDERINGDEFAULT_HINT')); ?>"></i>
                                    </label>
                                    <div class="col-sm-7">
                                        <?php echo JHTML::_('select.genericlist', array(
                                            JHTML::_('select.option', 'ordering', JText::_('FWMG_ORDERING'), 'id', 'name'),
                                            JHTML::_('select.option', 'alpha', JText::_('FWMG_APLHABETICALLY'), 'id', 'name'),
                                            JHTML::_('select.option', 'new', JText::_('FWMG_NEWEST_FIRST'), 'id', 'name'),
                                            JHTML::_('select.option', 'old', JText::_('FWMG_OLDEST_FIRST'), 'id', 'name'),
                                        ), 'config[gallery_default_ordering]', 'class="form-control select-choices"', 'id', 'name', $this->object->params->get('gallery_default_ordering', 'ordering')); ?>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5 col-form-label clearfix">
                                        <?php echo JText::_('FWMG_DOC_ADMIN_SETTINGS_TAB_CATEGORY_SECTION_GRID_PAGINATION'); ?>
                                        <i class="pull-right fa fa-question-circle" data-container="body" data-trigger="hover" data-toggle="popover" data-bs-toggle="popover" data-placement="top" data-content="<?php echo esc_attr(JText::_('FWMG_DOC_ADMIN_SETTINGS_TAB_CATEGORY_SECTION_GRID_PAGINATION_HINT')); ?>"></i>
                                    </label>
                                    <div class="col-sm-7">
                                        <?php echo JHTMLfwView::radioGroup('config[gallery_pagination_type]', $this->object->params->get('gallery_pagination_type', 'standard'), array(
                                            'wrapper_class' => 'mr-2',
                                            'buttons' => array(array(
                                                'active_class' => 'btn-success',
                                                'title' => JText::_('FWMG_STANDARD'),
                                                'value' => 'standard'
                                            ), array(
                                                'active_class' => 'btn-warning',
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
                        <!-- Gallery Listing Layout -->
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title"><?php echo JText::_('FWMG_DOC_ADMIN_SETTINGS_TAB_CATEGORY_SECTION_GRIDITEM'); ?></h4>
                                <div class="card-subtitle"><?php echo JText::_('FWMG_DOC_ADMIN_SETTINGS_TAB_CATEGORY_SECTION_GRIDITEM_HINT'); ?></div>
                            </div>
                            <div class="card-body">
                                <div class="form-group row">
                                    <label class="col-sm-5 col-form-label clearfix">
                                        <?php echo JText::_('FWMG_DOC_ADMIN_SETTINGS_TAB_CATEGORY_SECTION_GRIDITEM_DESIGN'); ?>
                                        <i class="pull-right fa fa-question-circle" data-container="body" data-trigger="hover" data-toggle="popover" data-bs-toggle="popover" data-placement="top" data-content="<?php echo esc_attr(JText::_('FWMG_DOC_ADMIN_SETTINGS_TAB_CATEGORY_SECTION_GRIDITEM_DESIGN_HINT')); ?>"></i>
                                    </label>
                                    <div class="col-sm-7">
                                        <?php echo JHTML::_('select.genericlist', fwgHelper::getThemesList(), 'config[template]', 'class="form-control select-choices"', 'id', 'name', $this->object->params->get('template', 'common')); ?>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5 col-form-label clearfix">
                                        <?php echo JText::_('FWMG_DOC_ADMIN_SETTINGS_TAB_CATEGORY_SECTION_GRIDITEM_SHOWINFO'); ?>
                                        <i class="pull-right fa fa-question-circle" data-container="body" data-trigger="hover" data-toggle="popover" data-bs-toggle="popover" data-placement="top" data-content="<?php echo esc_attr(JText::_('FWMG_DOC_ADMIN_SETTINGS_TAB_CATEGORY_SECTION_GRIDITEM_SHOWINFO_HINT')); ?>"></i>
                                    </label>
                                    <div class="col-sm-7">
                                        <?php echo JHTML::_('select.genericlist', fwgHelper::getLayoutsList(), 'config[gallery_layout]', 'class="form-control select-choices"', 'id', 'name', $this->object->params->get('gallery_layout', 'bottom')); ?>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5 col-form-label clearfix">
                                        <?php echo JText::_('FWMG_DOC_ADMIN_SETTINGS_TAB_CATEGORY_SECTION_GRIDITEM_HOVER'); ?>
                                        <i class="pull-right fa fa-question-circle" data-container="body" data-trigger="hover" data-toggle="popover" data-bs-toggle="popover" data-placement="top" data-content="<?php echo esc_attr(JText::_('FWMG_DOC_ADMIN_SETTINGS_TAB_CATEGORY_SECTION_GRIDITEM_HOVER_HINT')); ?>"></i>
                                    </label>
                                    <div class="col-sm-7">
                                        <?php echo JHTML::_('select.genericlist', array(
                                            JHTML::_('select.option', 'none', JText::_('FWMG_NONE'), 'id', 'name'),
                                            JHTML::_('select.option', 'kenburns', JText::_('FWMG_KENBURNS'), 'id', 'name'),
                                            JHTML::_('select.option', 'linear', JText::_('FWMG_ENLARGE'), 'id', 'name'),
                                        ), 'config[gallery_hover]', 'class="form-control select-choices"', 'id', 'name', $this->object->params->get('gallery_hover', 'none')); ?>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5 col-form-label clearfix">
                                        <?php echo JText::_('FWMG_DOC_ADMIN_SETTINGS_TAB_CATEGORY_SECTION_GRIDITEM_NAME'); ?>
                                        <i class="pull-right fa fa-question-circle" data-container="body" data-trigger="hover" data-toggle="popover" data-bs-toggle="popover" data-placement="top" data-content="<?php echo esc_attr(JText::_('FWMG_DOC_ADMIN_SETTINGS_TAB_CATEGORY_SECTION_GRIDITEM_NAME_HINT')); ?>"></i>
                                    </label>
                                    <div class="col-sm-7">
                                        <?php echo JHTMLfwView::radioGroup('config[show_gallery_item_name]', $this->object->params->get('show_gallery_item_name', 1), array(
                                            'wrapper_class' => 'mr-2',
                                            'buttons' => array(array(
                                                'active_class' => 'btn-success',
                                                'title' => JText::_('FWMG_SHOW'),
                                                'value' => 1
                                            ), array(
                                                'active_class' => 'btn-danger',
                                                'title' => JText::_('FWMG_HIDE'),
                                                'value' => 0
                                            ))
                                        )); ?>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5 col-form-label clearfix">
                                        <?php echo JText::_('FWMG_DOC_ADMIN_SETTINGS_TAB_CATEGORY_SECTION_GRIDITEM_UPDATE'); ?>
                                        <i class="pull-right fa fa-question-circle" data-container="body" data-trigger="hover" data-toggle="popover" data-bs-toggle="popover" data-placement="top" data-content="<?php echo esc_attr(JText::_('FWMG_DOC_ADMIN_SETTINGS_TAB_CATEGORY_SECTION_GRIDITEM_UPDATE_HINT')); ?>"></i>
                                    </label>
                                    <div class="col-sm-7">
                                        <?php echo JHTMLfwView::radioGroup('config[show_gallery_item_update_date]', $this->object->params->get('show_gallery_item_update_date', 1), array(
                                            'wrapper_class' => 'mr-2',
                                            'buttons' => array(array(
                                                'active_class' => 'btn-success',
                                                'title' => JText::_('FWMG_SHOW'),
                                                'value' => 1
                                            ), array(
                                                'active_class' => 'btn-danger',
                                                'title' => JText::_('FWMG_HIDE'),
                                                'value' => 0
                                            ))
                                        )); ?>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5 col-form-label clearfix">
                                        <?php echo JText::_('FWMG_DOC_ADMIN_SETTINGS_TAB_CATEGORY_SECTION_GRIDITEM_OWNER'); ?>
                                        <i class="pull-right fa fa-question-circle" data-container="body" data-trigger="hover" data-toggle="popover" data-bs-toggle="popover" data-placement="top" data-content="<?php echo esc_attr(JText::_('FWMG_DOC_ADMIN_SETTINGS_TAB_CATEGORY_SECTION_GRIDITEM_OWNER_HINT')); ?>"></i>
                                    </label>
                                    <div class="col-sm-7">
                                        <?php echo JHTMLfwView::radioGroup('config[show_gallery_item_owner]', $this->object->params->get('show_gallery_item_owner', 1), array(
                                            'wrapper_class' => 'mr-2',
                                            'buttons' => array(array(
                                                'active_class' => 'btn-success',
                                                'title' => JText::_('FWMG_SHOW'),
                                                'value' => 1
                                            ), array(
                                                'active_class' => 'btn-danger',
                                                'title' => JText::_('FWMG_HIDE'),
                                                'value' => 0
                                            ))
                                        )); ?>
                                    </div>
                                </div>
<?php
		fwgHelper::triggerEvent('onshowConfigCategoryListingDesignExtraFields', array('com_fwgallery', $this->object));
?>
                                <div class="form-group row">
                                    <label class="col-sm-5 col-form-label clearfix">
                                        <?php echo JText::_('FWMG_DOC_ADMIN_SETTINGS_TAB_CATEGORY_SECTION_GRIDITEM_COUNTER'); ?>
                                        <i class="pull-right fa fa-question-circle" data-container="body" data-trigger="hover" data-toggle="popover" data-bs-toggle="popover" data-placement="top" data-content="<?php echo esc_attr(JText::_('FWMG_DOC_ADMIN_SETTINGS_TAB_CATEGORY_SECTION_GRIDITEM_COUNTER_HINT')); ?>"></i>
                                    </label>
                                    <div class="col-sm-7">
                                        <?php echo JHTMLfwView::radioGroup('config[show_gallery_item_counter]', $this->object->params->get('show_gallery_item_counter', 1), array(
                                            'wrapper_class' => 'mr-2',
                                            'buttons' => array(array(
                                                'active_class' => 'btn-success',
                                                'title' => JText::_('FWMG_SHOW'),
                                                'value' => 1
                                            ), array(
                                                'active_class' => 'btn-danger',
                                                'title' => JText::_('FWMG_HIDE'),
                                                'value' => 0
                                            ))
                                        )); ?>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5 col-form-label clearfix">
                                        <?php echo JText::_('FWMG_DOC_ADMIN_SETTINGS_TAB_CATEGORY_SECTION_GRIDITEM_DESCRIPTION'); ?>
                                        <i class="pull-right fa fa-question-circle" data-container="body" data-trigger="hover" data-toggle="popover" data-bs-toggle="popover" data-placement="top" data-content="<?php echo esc_attr(JText::_('FWMG_DOC_ADMIN_SETTINGS_TAB_CATEGORY_SECTION_GRIDITEM_DESCRIPTION_HINT')); ?>"></i>
                                    </label>
                                    <div class="col-sm-7">
                                        <?php echo JHTMLfwView::radioGroup('config[show_gallery_item_description]', $this->object->params->get('show_gallery_item_description', 1), array(
                                            'wrapper_class' => 'mr-2',
                                            'buttons' => array(array(
                                                'active_class' => 'btn-success',
                                                'title' => JText::_('FWMG_SHOW'),
                                                'value' => 1
                                            ), array(
                                                'active_class' => 'btn-danger',
                                                'title' => JText::_('FWMG_HIDE'),
                                                'value' => 0
                                            ))
                                        )); ?>
                                    </div>
                                </div>
								<div class="form-group row">
									<label class="col-sm-5 col-form-label clearfix">
										<?php echo JText::_('FWMG_DOC_ADMIN_SETTINGS_TAB_CATEGORY_SECTION_GRIDITEM_DESCLENGTH'); ?>
										<i class="pull-right fa fa-question-circle" data-container="body" data-trigger="hover" data-toggle="popover" data-bs-toggle="popover" data-placement="top"  data-content="<?php echo esc_attr(JText::_('FWMG_DOC_ADMIN_SETTINGS_TAB_CATEGORY_SECTION_GRIDITEM_DESCLENGTH_HINT')); ?>"></i>
									</label>
									<div class="col-sm-7">
										<input type="text" class="form-control" name="config[description_length]" value="<?php echo esc_attr($this->object->params->get('description_length', 100)); ?>" size="20"/>
									</div>
								</div>
                                <div class="form-group row">
                                    <label class="col-sm-5 col-form-label clearfix">
                                        <?php echo JText::_('FWMG_DOC_ADMIN_SETTINGS_TAB_CATEGORY_SECTION_GRIDITEM_VIDEOPLAY'); ?>
                                        <i class="pull-right fa fa-question-circle" data-container="body" data-trigger="hover" data-toggle="popover" data-bs-toggle="popover" data-placement="top" data-content="<?php echo esc_attr(JText::_('FWMG_DOC_ADMIN_SETTINGS_TAB_CATEGORY_SECTION_GRIDITEM_VIDEOPLAY_HINT')); ?>"></i>
                                    </label>
                                    <div class="col-sm-7">
                                        <?php echo JHTMLfwView::radioGroup('config[dont_gallery_item_video_autoplay]', $this->object->params->get('dont_gallery_item_video_autoplay', 0), array(
                                            'wrapper_class' => 'mr-2',
                                            'buttons' => array(array(
                                                'active_class' => 'btn-success',
                                                'title' => JText::_('JYES'),
                                                'value' => 0
                                            ), array(
                                                'active_class' => 'btn-danger',
                                                'title' => JText::_('JNO'),
                                                'value' => 1
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

        <!-- FWG Settings Items -->
        <div class="tab-pane" id="fwmg-settings-items" role="tabpanel">
            <div class="fwmg-settings-items">
                <div class="row fwa-mb-cardbox">
                    <div class="col-lg-6 col-sm-12">
                        <!-- Items Listing Layout -->
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title"><?php echo JText::_('FWMG_DOC_ADMIN_SETTINGS_TAB_FILES'); ?></h4>
                                <div class="card-subtitle"><?php echo JText::_('FWMG_DOC_ADMIN_SETTINGS_TAB_FILES_HINT'); ?></div>
                            </div>
                            <div class="card-body">
                                <div class="form-group row">
                                    <label class="col-sm-5 col-form-label clearfix">
                                        <?php echo JText::_('FWMG_DOC_ADMIN_SETTINGS_TAB_FILES_SECTION_GRID_GRID'); ?>
                                        <i class="pull-right fa fa-question-circle" data-container="body" data-trigger="hover" data-toggle="popover" data-bs-toggle="popover" data-placement="top" data-content="<?php echo esc_attr(JText::_('FWMG_DOC_ADMIN_SETTINGS_TAB_FILES_SECTION_GRID_GRID_HINT')); ?>"></i>
                                    </label>
                                    <div class="col-sm-7">
                                        <?php echo JHTML::_('select.genericlist', fwgHelper::getGridsList(), 'config[files_grid]', 'class="form-control select-choices"', 'id', 'name', $this->object->params->get('files_grid', 'standard')); ?>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5 col-form-label clearfix">
                                        <?php echo JText::_('FWMG_DOC_ADMIN_SETTINGS_TAB_FILES_SECTION_GRID_COLUMNS'); ?>
                                        <i class="pull-right fa fa-question-circle" data-container="body" data-trigger="hover" data-toggle="popover" data-bs-toggle="popover" data-placement="top" data-content="<?php echo esc_attr(JText::_('FWMG_DOC_ADMIN_SETTINGS_TAB_FILES_SECTION_GRID_COLUMNS_HINT')); ?>"></i>
                                    </label>
                                    <div class="col-sm-7">
                                        <?php echo JHTMLfwView::radioGroup('config[files_columns]', $this->object->params->get('files_columns', 4), array(
                                            'wrapper_class' => 'mr-2',
                                            'buttons' => array(array(
                                                'active_class' => 'btn-success',
                                                'title' => 1,
                                                'value' => 1
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
                                        <?php echo JText::_('FWMG_DOC_ADMIN_SETTINGS_TAB_FILES_SECTION_GRID_ROWS'); ?>
                                        <i class="pull-right fa fa-question-circle" data-container="body" data-trigger="hover" data-toggle="popover" data-bs-toggle="popover" data-placement="top" data-content="<?php echo esc_attr(JText::_('FWMG_DOC_ADMIN_SETTINGS_TAB_FILES_SECTION_GRID_ROWS_HINT')); ?>"></i>
                                    </label>
                                    <div class="col-sm-7">
                                        <input class="form-control col-3" type="text" name="config[files_rows]" value="<?php echo esc_attr($this->object->params->get('files_rows', 4)); ?>" />
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5 col-form-label clearfix">
                                        <?php echo JText::_('FWMG_DOC_ADMIN_SETTINGS_TAB_FILES_SECTION_GRID_OPEN'); ?>
                                        <i class="pull-right fa fa-question-circle" data-container="body" data-trigger="hover" data-toggle="popover" data-bs-toggle="popover" data-placement="top" data-content="<?php echo esc_attr(JText::_('FWMG_DOC_ADMIN_SETTINGS_TAB_FILES_SECTION_GRID_OPEN_HINT')); ?>"></i>
                                    </label>
                                    <div class="col-sm-7">
<?php
$file_open_as = array(array(
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
                                        <?php echo JHTMLfwView::radioGroup('config[file_open_as]', $this->object->params->get('file_open_as', 'link'), array(
                                            'wrapper_class' => 'mr-2',
                                            'buttons' => $file_open_as
                                        )); ?>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5 col-form-label clearfix">
                                        <?php echo JText::_('FWMG_DOC_ADMIN_SETTINGS_TAB_FILES_SECTION_GRID_LIMIT'); ?>
                                        <i class="pull-right fa fa-question-circle" data-container="body" data-trigger="hover" data-toggle="popover" data-bs-toggle="popover" data-placement="top" data-content="<?php echo esc_attr(JText::_('FWMG_DOC_ADMIN_SETTINGS_TAB_FILES_SECTION_GRID_LIMIT_HINT')); ?>"></i>
                                    </label>
                                    <div class="col-sm-7">
                                        <?php echo JHTMLfwView::radioGroup('config[show_files_limit]', $this->object->params->get('show_files_limit', 1), array(
                                            'wrapper_class' => 'mr-2',
                                            'buttons' => array(array(
                                                'active_class' => 'btn-success',
                                                'title' => JText::_('FWMG_SHOW'),
                                                'value' => 1
                                            ), array(
                                                'active_class' => 'btn-danger',
                                                'title' => JText::_('FWMG_HIDE'),
                                                'value' => 0
                                            ))
                                        )); ?>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5 col-form-label clearfix">
                                        <?php echo JText::_('FWMG_DOC_ADMIN_SETTINGS_TAB_FILES_SECTION_GRID_ORDERING'); ?>
                                        <i class="pull-right fa fa-question-circle" data-container="body" data-trigger="hover" data-toggle="popover" data-bs-toggle="popover" data-placement="top" data-content="<?php echo esc_attr(JText::_('FWMG_DOC_ADMIN_SETTINGS_TAB_FILES_SECTION_GRID_ORDERING_HINT')); ?>"></i>
                                    </label>
                                    <div class="col-sm-7">
                                        <?php echo JHTMLfwView::radioGroup('config[show_files_ordering]', $this->object->params->get('show_files_ordering', 1), array(
                                            'wrapper_class' => 'mr-2',
                                            'buttons' => array(array(
                                                'active_class' => 'btn-success',
                                                'title' => JText::_('FWMG_SHOW'),
                                                'value' => 1
                                            ), array(
                                                'active_class' => 'btn-danger',
                                                'title' => JText::_('FWMG_HIDE'),
                                                'value' => 0
                                            ))
                                        )); ?>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5 col-form-label clearfix">
                                        <?php echo JText::_('FWMG_DOC_ADMIN_SETTINGS_TAB_FILES_SECTION_GRID_ORDERINGDEFAULT'); ?>
                                        <i class="pull-right fa fa-question-circle" data-container="body" data-trigger="hover" data-toggle="popover" data-bs-toggle="popover" data-placement="top" data-content="<?php echo esc_attr(JText::_('FWMG_DOC_ADMIN_SETTINGS_TAB_FILES_SECTION_GRID_ORDERINGDEFAULT_HINT')); ?>"></i>
                                    </label>
                                    <div class="col-sm-7">
                                        <?php echo JHTML::_('select.genericlist', array(
                                            JHTML::_('select.option', 'ordering', JText::_('FWMG_ORDERING'), 'id', 'name'),
                                            JHTML::_('select.option', 'alpha', JText::_('FWMG_APLHABETICALLY'), 'id', 'name'),
                                            JHTML::_('select.option', 'new', JText::_('FWMG_NEWEST_FIRST'), 'id', 'name'),
                                            JHTML::_('select.option', 'old', JText::_('FWMG_OLDEST_FIRST'), 'id', 'name'),
                                        ), 'config[files_default_ordering]', 'class="form-control select-choices"', 'id', 'name', $this->object->params->get('files_default_ordering', 'ordering')); ?>
                                    </div>
                                </div>
<?php
		fwgHelper::triggerEvent('onshowConfigFileListingOrderingExtraFields', array('com_fwgallery', $this->object));
?>
                                <div class="form-group row">
                                    <label class="col-sm-5 col-form-label clearfix">
                                        <?php echo JText::_('FWMG_DOC_ADMIN_SETTINGS_TAB_FILES_SECTION_GRID_PAGINATION'); ?>
                                        <i class="pull-right fa fa-question-circle" data-container="body" data-trigger="hover" data-toggle="popover" data-bs-toggle="popover" data-placement="top" data-content="<?php echo esc_attr(JText::_('FWMG_DOC_ADMIN_SETTINGS_TAB_FILES_SECTION_GRID_PAGINATION_HINT')); ?>"></i>
                                    </label>
                                    <div class="col-sm-7">
                                        <?php echo JHTMLfwView::radioGroup('config[files_pagination_type]', $this->object->params->get('files_pagination_type', 'standard'), array(
                                            'wrapper_class' => 'mr-2',
                                            'buttons' => array(array(
                                                'active_class' => 'btn-success',
                                                'title' => JText::_('FWMG_STANDARD'),
                                                'value' => 'standard'
                                            ), array(
                                                'active_class' => 'btn-warning',
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
                        <!-- Items Listing Design -->
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title"><?php echo JText::_('FWMG_DOC_ADMIN_SETTINGS_TAB_FILES_SECTION_GRIDITEM'); ?></h4>
                                <div class="card-subtitle"><?php echo JText::_('FWMG_DOC_ADMIN_SETTINGS_TAB_FILES_SECTION_GRIDITEM_HINT'); ?></div>
                            </div>
                            <div class="card-body">
                                <div class="form-group row">
                                    <label class="col-sm-5 col-form-label clearfix">
                                        <?php echo JText::_('FWMG_DOC_ADMIN_SETTINGS_TAB_FILES_SECTION_GRIDITEM_INFO'); ?>
                                        <i class="pull-right fa fa-question-circle" data-container="body" data-trigger="hover" data-toggle="popover" data-bs-toggle="popover" data-placement="top" data-content="<?php echo esc_attr(JText::_('FWMG_DOC_ADMIN_SETTINGS_TAB_FILES_SECTION_GRIDITEM_INFO_HINT')); ?>"></i>
                                    </label>
                                    <div class="col-sm-7">
                                        <?php echo JHTML::_('select.genericlist', fwgHelper::getLayoutsList(), 'config[file_layout]', 'class="form-control select-choices"', 'id', 'name', $this->object->params->get('file_layout', 'bottom')); ?>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5 col-form-label clearfix">
                                        <?php echo JText::_('FWMG_DOC_ADMIN_SETTINGS_TAB_FILES_SECTION_GRIDITEM_HOVER'); ?>
                                        <i class="pull-right fa fa-question-circle" data-container="body" data-trigger="hover" data-toggle="popover" data-bs-toggle="popover" data-placement="top" data-content="<?php echo esc_attr(JText::_('FWMG_DOC_ADMIN_SETTINGS_TAB_FILES_SECTION_GRIDITEM_HOVER_HINT')); ?>"></i>
                                    </label>
                                    <div class="col-sm-7">
                                        <?php echo JHTML::_('select.genericlist', array(
                                            JHTML::_('select.option', 'none', JText::_('FWMG_NONE'), 'id', 'name'),
                                            JHTML::_('select.option', 'kenburns', JText::_('FWMG_KENBURNS'), 'id', 'name'),
                                            JHTML::_('select.option', 'linear', JText::_('FWMG_ENLARGE'), 'id', 'name'),
                                        ), 'config[file_hover]', 'class="form-control select-choices"', 'id', 'name', $this->object->params->get('file_hover', 'none')); ?>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5 col-form-label clearfix">
                                        <?php echo JText::_('FWMG_DOC_ADMIN_SETTINGS_TAB_FILES_SECTION_GRIDITEM_NAME'); ?>
                                        <i class="pull-right fa fa-question-circle" data-container="body" data-trigger="hover" data-toggle="popover" data-bs-toggle="popover" data-placement="top" data-content="<?php echo esc_attr(JText::_('FWMG_DOC_ADMIN_SETTINGS_TAB_FILES_SECTION_GRIDITEM_NAME_HINT')); ?>"></i>
                                    </label>
                                    <div class="col-sm-7">
                                        <?php echo JHTMLfwView::radioGroup('config[show_files_name]', $this->object->params->get('show_files_name', 1), array(
                                            'wrapper_class' => 'mr-2',
                                            'buttons' => array(array(
                                                'active_class' => 'btn-success',
                                                'title' => JText::_('FWMG_SHOW'),
                                                'value' => 1
                                            ), array(
                                                'active_class' => 'btn-danger',
                                                'title' => JText::_('FWMG_HIDE'),
                                                'value' => 0
                                            ))
                                        )); ?>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5 col-form-label clearfix">
                                        <?php echo JText::_('FWMG_DOC_ADMIN_SETTINGS_TAB_FILES_SECTION_GRIDITEM_UPDATE'); ?>
                                        <i class="pull-right fa fa-question-circle" data-container="body" data-trigger="hover" data-toggle="popover" data-bs-toggle="popover" data-placement="top" data-content="<?php echo esc_attr(JText::_('FWMG_DOC_ADMIN_SETTINGS_TAB_FILES_SECTION_GRIDITEM_UPDATE_HINT')); ?>"></i>
                                    </label>
                                    <div class="col-sm-7">
                                        <?php echo JHTMLfwView::radioGroup('config[show_files_update]', $this->object->params->get('show_files_update', 1), array(
                                            'wrapper_class' => 'mr-2',
                                            'buttons' => array(array(
                                                'active_class' => 'btn-success',
                                                'title' => JText::_('FWMG_SHOW'),
                                                'value' => 1
                                            ), array(
                                                'active_class' => 'btn-danger',
                                                'title' => JText::_('FWMG_HIDE'),
                                                'value' => 0
                                            ))
                                        )); ?>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5 col-form-label clearfix">
                                        <?php echo JText::_('FWMG_DOC_ADMIN_SETTINGS_TAB_FILES_SECTION_GRIDITEM_OWNER'); ?>
                                        <i class="pull-right fa fa-question-circle" data-container="body" data-trigger="hover" data-toggle="popover" data-bs-toggle="popover" data-placement="top" data-content="<?php echo esc_attr(JText::_('FWMG_DOC_ADMIN_SETTINGS_TAB_FILES_SECTION_GRIDITEM_OWNER_HINT')); ?>"></i>
                                    </label>
                                    <div class="col-sm-7">
                                        <?php echo JHTMLfwView::radioGroup('config[show_files_owner]', $this->object->params->get('show_files_owner', 1), array(
                                            'wrapper_class' => 'mr-2',
                                            'buttons' => array(array(
                                                'active_class' => 'btn-success',
                                                'title' => JText::_('FWMG_SHOW'),
                                                'value' => 1
                                            ), array(
                                                'active_class' => 'btn-danger',
                                                'title' => JText::_('FWMG_HIDE'),
                                                'value' => 0
                                            ))
                                        )); ?>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5 col-form-label clearfix">
                                        <?php echo JText::_('FWMG_DOC_ADMIN_SETTINGS_TAB_FILES_SECTION_GRIDITEM_DESCRIPTION'); ?>
                                        <i class="pull-right fa fa-question-circle" data-container="body" data-trigger="hover" data-toggle="popover" data-bs-toggle="popover" data-placement="top" data-content="<?php echo esc_attr(JText::_('FWMG_DOC_ADMIN_SETTINGS_TAB_FILES_SECTION_GRIDITEM_DESCRIPTION_HINT')); ?>"></i>
                                    </label>
                                    <div class="col-sm-7">
                                        <?php echo JHTMLfwView::radioGroup('config[show_files_description]', $this->object->params->get('show_files_description', 1), array(
                                            'wrapper_class' => 'mr-2',
                                            'buttons' => array(array(
                                                'active_class' => 'btn-success',
                                                'title' => JText::_('FWMG_SHOW'),
                                                'value' => 1
                                            ), array(
                                                'active_class' => 'btn-danger',
                                                'title' => JText::_('FWMG_HIDE'),
                                                'value' => 0
                                            ))
                                        )); ?>
                                    </div>
                                </div>
								<div class="form-group row">
									<label class="col-sm-5 col-form-label clearfix">
										<?php echo JText::_('FWMG_DOC_ADMIN_SETTINGS_TAB_FILES_SECTION_GRIDITEM_DESCLENGTH'); ?>
										<i class="pull-right fa fa-question-circle" data-container="body" data-trigger="hover" data-toggle="popover" data-bs-toggle="popover" data-placement="top"  data-content="<?php echo esc_attr(JText::_('FWMG_DOC_ADMIN_SETTINGS_TAB_FILES_SECTION_GRIDITEM_DESCLENGTH_HINT')); ?>"></i>
									</label>
									<div class="col-sm-7">
										<input type="text" class="form-control" name="config[image_description_length]" value="<?php echo esc_attr($this->object->params->get('image_description_length', 100)); ?>" size="20"/>
									</div>
								</div>
<?php
		fwgHelper::triggerEvent('onshowConfigFileListingDesignExtraFields', array('com_fwgallery', $this->object));
?>
                                <div class="form-group row">
                                    <label class="col-sm-5 col-form-label clearfix">
                                        <?php echo JText::_('FWMG_DOC_ADMIN_SETTINGS_TAB_FILES_SECTION_GRIDITEM_DOWNLOAD'); ?>
                                        <i class="pull-right fa fa-question-circle" data-container="body" data-trigger="hover" data-toggle="popover" data-bs-toggle="popover" data-placement="top" data-content="<?php echo esc_attr(JText::_('FWMG_DOC_ADMIN_SETTINGS_TAB_FILES_SECTION_GRIDITEM_DOWNLOAD_HINT')); ?>"></i>
                                    </label>
                                    <div class="col-sm-7">
                                        <?php echo JHTMLfwView::radioGroup('config[show_files_download]', $this->object->params->get('show_files_download', 1), array(
                                            'wrapper_class' => 'mr-2',
                                            'buttons' => array(array(
                                                'active_class' => 'btn-success',
                                                'title' => JText::_('FWMG_SHOW'),
                                                'value' => 1
                                            ), array(
                                                'active_class' => 'btn-danger',
                                                'title' => JText::_('FWMG_HIDE'),
                                                'value' => 0
                                            ))
                                        )); ?>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5 col-form-label clearfix">
                                        <?php echo JText::_('FWMG_DOC_ADMIN_SETTINGS_TAB_FILES_SECTION_GRIDITEM_DOWNLOADACCESSLEVEL'); ?>
                                        <i class="pull-right fa fa-question-circle" data-container="body" data-trigger="hover" data-toggle="popover" data-bs-toggle="popover" data-placement="top" data-content="<?php echo esc_attr(JText::_('FWMG_DOC_ADMIN_SETTINGS_TAB_FILES_SECTION_GRIDITEM_DOWNLOADACCESSLEVEL_HINT')); ?>"></i>
                                    </label>
                                    <div class="col-sm-7">
                                        <?php echo JHTML::_('select.genericlist', fwgHelper::loadviewlevels(), 'config[download_access]', 'class="form-control select-choices"', 'id', 'name', $this->object->params->get('download_access')); ?>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5 col-form-label clearfix">
                                        <?php echo JText::_('FWMG_DOC_ADMIN_SETTINGS_TAB_FILES_SECTION_GRIDITEM_DOWNLOADFILE'); ?>
                                        <i class="pull-right fa fa-question-circle" data-container="body" data-trigger="hover" data-toggle="popover" data-bs-toggle="popover" data-placement="top" data-content="<?php echo esc_attr(JText::_('FWMG_DOC_ADMIN_SETTINGS_TAB_FILES_SECTION_GRIDITEM_DOWNLOADFILE_HINT')); ?>"></i>
                                    </label>
                                    <div class="col-sm-7">
                                        <?php echo JHTMLfwView::radioGroup('config[download_with_watermark]', $this->object->params->get('download_with_watermark', '0'), array(
                                            'wrapper_class' => 'mr-2',
                                            'buttons' => array(array(
                                                'active_class' => 'btn-success',
                                                'title' => JText::_('FWMG_ORIGINAL'),
                                                'value' => 0
                                            ), array(
                                                'active_class' => 'btn-danger',
                                                'title' => JText::_('FWMG_ORIGINAL_WITH_WATERMARK'),
                                                'value' => 1
                                            ))
                                        )); ?>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5 col-form-label clearfix">
                                        <?php echo JText::_('FWMG_ITEM_INFO'); ?>
                                        <i class="pull-right fa fa-question-circle" data-container="body" data-trigger="hover" data-toggle="popover" data-bs-toggle="popover" data-placement="top" title="<?php echo esc_attr(JText::_('FWMG_ITEM_INFO_TITLE')); ?>" data-content="<?php echo esc_attr(JText::_('FWMG_ITEM_INFO_CONTENT')); ?>"></i>
                                    </label>
                                    <div class="col-sm-7">
                                        <?php echo JHTMLfwView::radioGroup('config[show_files_info]', $this->object->params->get('show_files_info', '1'), array(
                                            'wrapper_class' => 'mr-2',
                                            'buttons' => array(array(
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
                                        <?php echo JText::_('FWMG_DOC_ADMIN_SETTINGS_TAB_FILES_SECTION_GRIDITEM_ICON'); ?>
                                        <i class="pull-right fa fa-question-circle" data-container="body" data-trigger="hover" data-toggle="popover" data-bs-toggle="popover" data-placement="top" data-content="<?php echo esc_attr(JText::_('FWMG_DOC_ADMIN_SETTINGS_TAB_FILES_SECTION_GRIDITEM_ICON_HINT')); ?>"></i>
                                    </label>
                                    <div class="col-sm-7">
                                        <?php echo JHTMLfwView::radioGroup('config[show_file_icon]', $this->object->params->get('show_file_icon', 1), array(
                                            'wrapper_class' => 'mr-2',
                                            'buttons' => array(array(
                                                'active_class' => 'btn-success',
                                                'title' => JText::_('FWMG_SHOW'),
                                                'value' => 1
                                            ), array(
                                                'active_class' => 'btn-danger',
                                                'title' => JText::_('FWMG_HIDE'),
                                                'value' => 0
                                            ))
                                        )); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-sm-12">
                        <!-- Single Item Design -->
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title"><?php echo JText::_('FWMG_DOC_ADMIN_SETTINGS_TAB_FILES_SECTION_FILE'); ?></h4>
                                <div class="card-subtitle"><?php echo JText::_('FWMG_DOC_ADMIN_SETTINGS_TAB_FILES_SECTION_FILE_HINT'); ?></div>
                            </div>
                            <div class="card-body">
                                <div class="form-group row">
                                    <label class="col-sm-5 col-form-label clearfix">
                                        <?php echo JText::_('FWMG_DOC_ADMIN_SETTINGS_TAB_FILES_SECTION_FILE_FULLSCREEN'); ?>
                                        <i class="pull-right fa fa-question-circle" data-container="body" data-trigger="hover" data-toggle="popover" data-bs-toggle="popover" data-placement="top" data-content="<?php echo esc_attr(JText::_('FWMG_DOC_ADMIN_SETTINGS_TAB_FILES_SECTION_FILE_FULLSCREEN_HINT')); ?>"></i>
                                    </label>
                                    <div class="col-sm-7">
                                        <?php echo JHTMLfwView::radioGroup('config[show_file_fullscreen]', $this->object->params->get('show_file_fullscreen', 1), array(
                                            'wrapper_class' => 'mr-2',
                                            'buttons' => array(array(
                                                'active_class' => 'btn-success',
                                                'title' => JText::_('FWMG_SHOW'),
                                                'value' => 1
                                            ), array(
                                                'active_class' => 'btn-danger',
                                                'title' => JText::_('FWMG_HIDE'),
                                                'value' => 0
                                            ))
                                        )); ?>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5 col-form-label clearfix">
                                        <?php echo JText::_('FWMG_DOC_ADMIN_SETTINGS_TAB_FILES_SECTION_FILE_THUMBS'); ?>
                                        <i class="pull-right fa fa-question-circle" data-container="body" data-trigger="hover" data-toggle="popover" data-bs-toggle="popover" data-placement="top" data-content="<?php echo esc_attr(JText::_('FWMG_DOC_ADMIN_SETTINGS_TAB_FILES_SECTION_FILE_THUMBS_HINT')); ?>"></i>
                                    </label>
                                    <div class="col-sm-7">
                                        <?php echo JHTMLfwView::radioGroup('config[show_file_slideshow]', $this->object->params->get('show_file_slideshow', 1), array(
                                            'wrapper_class' => 'mr-2',
                                            'buttons' => array(array(
                                                'active_class' => 'btn-success',
                                                'title' => JText::_('FWMG_SHOW'),
                                                'value' => 1
                                            ), array(
                                                'active_class' => 'btn-danger',
                                                'title' => JText::_('FWMG_HIDE'),
                                                'value' => 0
                                            ))
                                        )); ?>
                                    </div>
                                </div>
                                <div class="form-group row">
									<label class="col-sm-5 col-form-label clearfix">
										<?php echo JText::_('FWMG_DOC_ADMIN_SETTINGS_TAB_FILES_SECTION_FILE_CATGEORY'); ?>
										<i class="pull-right fa fa-question-circle" data-container="body" data-trigger="hover" data-toggle="popover" data-bs-toggle="popover" data-placement="top"  data-content="<?php echo esc_attr(JText::_('FWMG_DOC_ADMIN_SETTINGS_TAB_FILES_SECTION_FILE_CATGEORY_HINT')); ?>"></i>
									</label>
									<div class="col-sm-7">
										<?php echo JHTMLfwView::radioGroup('config[show_back_link]', $this->object->params->get('show_back_link'), array(
											'wrapper_class' => 'mr-2',
											'buttons' => array(array(
												'active_class' => 'btn-success',
												'title' => JText::_('FWMG_SHOW'),
												'value' => 1
											), array(
												'active_class' => 'btn-danger',
												'title' => JText::_('FWMG_HIDE'),
												'value' => 0
											))
										)); ?>
									</div>
								</div>
                                <div class="form-group row">
                                    <label class="col-sm-5 col-form-label clearfix">
                                        <?php echo JText::_('FWMG_DOC_ADMIN_SETTINGS_TAB_FILES_SECTION_FILE_INFO'); ?>
                                        <i class="pull-right fa fa-question-circle" data-container="body" data-trigger="hover" data-toggle="popover" data-bs-toggle="popover" data-placement="top" data-content="<?php echo esc_attr(JText::_('FWMG_DOC_ADMIN_SETTINGS_TAB_FILES_SECTION_FILE_INFO_HINT')); ?>"></i>
                                    </label>
                                    <div class="col-sm-7">
                                        <?php echo JHTMLfwView::radioGroup('config[show_file_info]', $this->object->params->get('show_file_info', 1), array(
                                            'wrapper_class' => 'mr-2',
                                            'buttons' => array(array(
                                                'active_class' => 'btn-success',
                                                'title' => JText::_('FWMG_SHOW'),
                                                'value' => 1
                                            ), array(
                                                'active_class' => 'btn-danger',
                                                'title' => JText::_('FWMG_HIDE'),
                                                'value' => 0
                                            ))
                                        )); ?>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5 col-form-label clearfix">
                                        <?php echo JText::_('FWMG_DOC_ADMIN_SETTINGS_TAB_FILES_SECTION_FILE_NAME'); ?>
                                        <i class="pull-right fa fa-question-circle" data-container="body" data-trigger="hover" data-toggle="popover" data-bs-toggle="popover" data-placement="top" data-content="<?php echo esc_attr(JText::_('FWMG_DOC_ADMIN_SETTINGS_TAB_FILES_SECTION_FILE_NAME_HINT')); ?>"></i>
                                    </label>
                                    <div class="col-sm-7">
                                        <?php echo JHTMLfwView::radioGroup('config[show_file_name]', $this->object->params->get('show_file_name', 1), array(
                                            'wrapper_class' => 'mr-2',
                                            'buttons' => array(array(
                                                'active_class' => 'btn-success',
                                                'title' => JText::_('FWMG_SHOW'),
                                                'value' => 1
                                            ), array(
                                                'active_class' => 'btn-danger',
                                                'title' => JText::_('FWMG_HIDE'),
                                                'value' => 0
                                            ))
                                        )); ?>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5 col-form-label clearfix">
                                        <?php echo JText::_('FWMG_DOC_ADMIN_SETTINGS_TAB_FILES_SECTION_FILE_UPDATE'); ?>
                                        <i class="pull-right fa fa-question-circle" data-container="body" data-trigger="hover" data-toggle="popover" data-bs-toggle="popover" data-placement="top" data-content="<?php echo esc_attr(JText::_('FWMG_DOC_ADMIN_SETTINGS_TAB_FILES_SECTION_FILE_UPDATE_HINT')); ?>"></i>
                                    </label>
                                    <div class="col-sm-7">
                                        <?php echo JHTMLfwView::radioGroup('config[show_file_update]', $this->object->params->get('show_file_update', 1), array(
                                            'wrapper_class' => 'mr-2',
                                            'buttons' => array(array(
                                                'active_class' => 'btn-success',
                                                'title' => JText::_('FWMG_SHOW'),
                                                'value' => 1
                                            ), array(
                                                'active_class' => 'btn-danger',
                                                'title' => JText::_('FWMG_HIDE'),
                                                'value' => 0
                                            ))
                                        )); ?>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5 col-form-label clearfix">
                                        <?php echo JText::_('FWMG_DOC_ADMIN_SETTINGS_TAB_FILES_SECTION_FILE_OWNER'); ?>
                                        <i class="pull-right fa fa-question-circle" data-container="body" data-trigger="hover" data-toggle="popover" data-bs-toggle="popover" data-placement="top" data-content="<?php echo esc_attr(JText::_('FWMG_DOC_ADMIN_SETTINGS_TAB_FILES_SECTION_FILE_OWNER_HINT')); ?>"></i>
                                    </label>
                                    <div class="col-sm-7">
                                        <?php echo JHTMLfwView::radioGroup('config[show_file_owner]', $this->object->params->get('show_file_owner', 1), array(
                                            'wrapper_class' => 'mr-2',
                                            'buttons' => array(array(
                                                'active_class' => 'btn-success',
                                                'title' => JText::_('FWMG_SHOW'),
                                                'value' => 1
                                            ), array(
                                                'active_class' => 'btn-danger',
                                                'title' => JText::_('FWMG_HIDE'),
                                                'value' => 0
                                            ))
                                        )); ?>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5 col-form-label clearfix">
                                        <?php echo JText::_('FWMG_DOC_ADMIN_SETTINGS_TAB_FILES_SECTION_FILE_DESCRIPTION'); ?>
                                        <i class="pull-right fa fa-question-circle" data-container="body" data-trigger="hover" data-toggle="popover" data-bs-toggle="popover" data-placement="top" data-content="<?php echo esc_attr(JText::_('FWMG_DOC_ADMIN_SETTINGS_TAB_FILES_SECTION_FILE_DESCRIPTION_HINT')); ?>"></i>
                                    </label>
                                    <div class="col-sm-7">
                                        <?php echo JHTMLfwView::radioGroup('config[show_file_description]', $this->object->params->get('show_file_description', 1), array(
                                            'wrapper_class' => 'mr-2',
                                            'buttons' => array(array(
                                                'active_class' => 'btn-success',
                                                'title' => JText::_('FWMG_SHOW'),
                                                'value' => 1
                                            ), array(
                                                'active_class' => 'btn-danger',
                                                'title' => JText::_('FWMG_HIDE'),
                                                'value' => 0
                                            ))
                                        )); ?>
                                    </div>
                                </div>
<?php
		fwgHelper::triggerEvent('onshowConfigFileDesignExtraFields', array('com_fwgallery', $this->object));
?>
                                <div class="form-group row">
                                    <label class="col-sm-5 col-form-label clearfix">
                                        <?php echo JText::_('FWMG_DOC_ADMIN_SETTINGS_TAB_FILES_SECTION_FILE_DOWNLOAD'); ?>
                                        <i class="pull-right fa fa-question-circle" data-container="body" data-trigger="hover" data-toggle="popover" data-bs-toggle="popover" data-placement="top" data-content="<?php echo esc_attr(JText::_('FWMG_DOC_ADMIN_SETTINGS_TAB_FILES_SECTION_FILE_DOWNLOAD_HINT')); ?>"></i>
                                    </label>
                                    <div class="col-sm-7">
                                        <?php echo JHTMLfwView::radioGroup('config[show_file_download]', $this->object->params->get('show_file_download', 1), array(
                                            'wrapper_class' => 'mr-2',
                                            'buttons' => array(array(
                                                'active_class' => 'btn-success',
                                                'title' => JText::_('FWMG_SHOW'),
                                                'value' => 1
                                            ), array(
                                                'active_class' => 'btn-danger',
                                                'title' => JText::_('FWMG_HIDE'),
                                                'value' => 0
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

        <!-- FWG Settings Plugins -->
        <div class="tab-pane" id="fwmg-settings-plugins" role="tabpanel">
            <div class="fwmg-settings-plugins">
                <div class="row fwa-mb-cardbox fwa-settings-plugins-list">

<?php
ob_start();
$buff = fwgHelper::triggerEvent('onshowConfigExtraCards', array('com_fwgallery', $this->object, $this));
$output = ob_get_clean();
foreach ($buff as $data) {
	echo fwgHelper::escPluginsOutput($data);
}
echo fwgHelper::escPluginsOutput($output);
?>
                </div>
            </div>
        </div>
    </div>

    <input type="hidden" name="id" value="" />
    <input type="hidden" name="task" value="" />
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
	$('#configfiles_grid').change(function() {
		$('input[name="config[files_rows]"]').change();
		$('input[name="config[files_columns]"]').change();
	});
	$('input[name="config[files_rows]"]').change(function() {
		var $inp = $(this);
		var grid = $('#configfiles_grid').val();
		for (var i = 0; i < size_limits.length; i++) {
			if (size_limits[i].name == grid) {
				if ($inp.val() * 1 < size_limits[i].sizes.min_rows) {
					$inp.val(size_limits[i].sizes.min_rows);
				}
				break;
			}
		}
	});
	$('input[name="config[files_columns]"]').change(function() {
		var $inp = $(this);
		var grid = $('#configfiles_grid').val();
		for (var i = 0; i < size_limits.length; i++) {
			if (size_limits[i].name == grid) {
				if ($inp.val() * 1 < size_limits[i].sizes.min_cols) {
					$inp.parent().find('button[data-value="'+size_limits[i].sizes.min_cols+'"]').click();
				}
				break;
			}
		}
	});
    })(jQuery);
});
</script>
<?php
echo JLayoutHelper::render('utilites.batchupload', array(
	'allusers'=>$this->users,
	'current_user'=>$this->current_user,
	'category'=>'',
	'reload'=>false
), JPATH_COMPONENT);
echo JLayoutHelper::render('utilites.quickcategories', array(), JPATH_COMPONENT);
echo JLayoutHelper::render('common.menu_end', array(), JPATH_COMPONENT);
