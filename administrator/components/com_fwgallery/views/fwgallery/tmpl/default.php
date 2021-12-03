<?php
/**
 * FW Gallery 6.7.2
 * @copyright (C) 2020 Fastw3b
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.fastw3b.net/ Official website
 **/

defined('_JEXEC') or die('Restricted access');

JToolBarHelper::title(JText::_('FWMG_ADMIN_DASHBOARD_TOOLBAR_TITLE'), ' fal fa-tachometer-alt');

echo JLayoutHelper::render('common.menu_begin', array(
    'view' => $this,
    'title' => JText::_('FWMG_DOC_ADMIN_DASHBOARD'),
    'title_hint' => JText::_('FWMG_DOC_ADMIN_DASHBOARD_HINT')
), JPATH_COMPONENT);

$update_code = $this->obj->params->get('update_code');
$verified_code = $this->obj->params->get('verified_code');
$auto_update = $this->obj->params->get('auto_update');
?>
<div class="row fwa-mb-cardbox">
	<div class="col-lg-6 col-sm-12">
        <div class="card">
			<div class="card-header">
                <h4 class="card-title"><?php echo JText::_('FWMG_ADMIN_DASHBOARD_COMPONENT_NAME'); ?></h4>
                <div class="card-subtitle"><?php echo JText::_('FWMG_DOC_ADMIN_DASHBOARD_COMPONENT_HINT'); ?></div>
			</div>
			<div class="card-body">
<?php
$addons_installed = $addons_owned = $addons_total = $component_has_update = 0;
$addons_have_update = $addons_have_install = array();
$comp_version = '';
if ($this->addons) {
 	foreach ($this->addons as $row) {
        if (!empty($row->installable) and $row->update_name != FWMG_UPDATE_NAME) {
            $addons_owned++;
        }
        if (!$row->installed and !empty($row->installable)) {
            $addons_have_install[] = $row;
        }
        if ($row->subtype != 'service' and $row->update_name != FWMG_UPDATE_NAME) {
            $addons_total++;
        }
        if ($row->loc_version and $row->rem_version and $row->loc_version != 'x.x.x' and $row->loc_version != $row->rem_version) {
            $addons_have_update[] = $row;
        }
        if ($row->installed and $row->update_name != FWMG_UPDATE_NAME) {
            $addons_installed++;
        }
    }
	foreach ($this->addons as $row) {
		if ($row->update_name == FWMG_UPDATE_NAME) {
            $comp_version = $row->loc_version;
            $component_has_update = ($row->loc_version and $row->rem_version and $row->loc_version != 'x.x.x' and $row->loc_version != $row->rem_version);
?>
<div class="row fws-extensions-item-info" data-ext="<?php echo esc_attr($row->update_name); ?>">
	<div class="col-md-3 p-0 fws-ext-item-img">
		<img src="<?php echo esc_attr($row->image); ?>" />
	</div>
	<div class="col-md-6 fws-ext-stats">
		<div class="fws-ext-latest">
			<i class="fa-fw fal fa-code-branch mr-1"></i>
			<?php echo JText::_('FWMG_ADMIN_DASHBOARD_COMPONENT_VERSION'); ?>
			<span class="badge badge-<?php if ($component_has_update) { ?>warning<?php } else { ?>success<?php } ?>"><?php echo esc_html($row->loc_version); ?></span>
		</div>
		<div class="fws-show-addons-owned">
			<i class="fa-fw fal fa-plug mr-1"></i>
			<?php echo JText::sprintf('FWMG_ADMIN_DASHBOARD_COMPONENT_ADDONS_OWNED_OF', $addons_owned, $addons_total); ?>
		</div>
	</div>
    <div class="col-md-3 p-0 fws-ext-links">
        <div>
    		<a href="https://fastw3b.net/joomla-extensions/product/2-fw-real-estate" target="_blank">
    			<i class="fa-fw fal fa-cube mr-1"></i>
    			<?php echo JText::_('FWMG_ADMIN_DASHBOARD_COMPONENT_PAGE') ?>
    		</a>
        </div>
        <div>
            <a href="javascript:">
                <i class="fa-fw fal fa-file-alt mr-1"></i>
                <?php echo JText::_('FWMG_ADMIN_DASHBOARD_COMPONENT_LOG'); ?>
            </a>
        </div>
        <div>
            <a href="https://fastw3b.net/documentation/fw-real-estate" target="_blank">
                <i class="fa-fw fal fa-book mr-1"></i>
                <?php echo JText::_('FWMG_ADMIN_DASHBOARD_COMPONENT_DOC'); ?>
            </a>
        </div>
	</div>
</div>
<?php
			break;
		}
	}
?>
            </div>
        </div>
<?php
    if ($this->qch->orphans) {
?>
		<div class="card">
			<div class="card-header">
				<h4 class="card-title"><?php echo JText::_('FWMG_ORPHAN_FILES_DETECTED'); ?></h4>
			</div>
			<div class="card-body">
                <div class="row">
                    <div class="col">
                        <div class="text-center">
                            <?php echo JText::_('FWMG_UNREGISTERED_FILES_TAKE_UP'); ?> <strong><?php echo fwgHelper::humanFileSize($this->qch->orphans); ?></strong>
                            <br/>
                            <button type="button" class="btn btn-danger ml-2 mt-2" id="fwmg-delete-unregistered-files">
                                <i class="fal fa-trash-alt mr-2"></i>
                                <?php echo JText::_('FWMG_DELETE_FILES'); ?></button>
                        </div>
                    </div>
                    <div class="col">
                        <div class="text-center">
                            <div class="small text-muted">
                                <i class="fal fa-question-circle fa-2x"></i> <br/>
                                <?php echo JText::_('FWMG_UNREGISTERED_FILES_HINT'); ?>
                            </div>
                        </div>
                    </div>
                </div>
			</div>
		</div>
<script>
document.addEventListener('DOMContentLoaded', function() {
    (function($) {
    $('#fwmg-delete-unregistered-files').click(function() {
        var $btn = $(this);
        if (confirm('<?php echo JText::_('FWMG_ARE_YOU_SURE', true); ?>')) {
            var $wait = $('<i class="fas fa-sync fa-spin"></i>');
            $btn.attr('disabled', true).after($wait);
            $.ajax({
                type: 'post',
                dataType: 'json',
                data: {
                    format: 'json',
	    			view: 'fwgallery',
                    layout: 'delete_unregistered_files'
                }
            }).done(function(data) {
                $btn.attr('disabled', false);
                $wait.remove();
                if (data.result) {
                    $btn.closest('.card').remove();
                }
                if (data.msg) {
                    fwmg_alert(data.msg);
                }
            });
        }
    });
    })(jQuery);
});
</script>
<?php
    }
?>
		<div class="card">
			<div class="card-header">
				<h4 class="card-title"><?php echo JText::_('FWMG_DOC_ADMIN_DASHBOARD_CACHE'); ?></h4>
			</div>
			<div class="card-body">
                <div class="row">
                    <div class="col">
                        <div class="text-center">
                            <button type="button" class="btn btn-warning ml-2 mt-4" id="fwmg-clear-cached-files">
                                <i class="fal fa-trash mr-2"></i>
                                <?php echo JText::_('FWMG_ADMIN_DASHBOARD_CACHE_BTN'); ?></button>
                        </div>
                    </div>
                    <div class="col">
                        <div class="text-center">
                            <div class="small text-muted">
                                <i class="fal fa-question-circle fa-2x"></i> <br/>
                                <?php echo JText::_('FWMG_DOC_ADMIN_DASHBOARD_CACHE_HINT'); ?>
                            </div>
                        </div>
                    </div>
                </div>
			</div>
		</div>
<script>
document.addEventListener('DOMContentLoaded', function() {
    (function($) {
    $('#fwmg-clear-cached-files').click(function() {
        var $btn = $(this);
        var $wait = $('<i class="fas fa-sync fa-spin"></i>');
        $btn.attr('disabled', true).after($wait);
        $.ajax({
            type: 'post',
            dataType: 'json',
            data: {
                format: 'json',
                view: 'fwgallery',
                layout: 'clear_cached_files'
            }
        }).done(function(data) {
            $btn.attr('disabled', false);
            $wait.remove();
            if (data.msg) {
                fwmg_alert(data.msg);
            }
        });
    });
    })(jQuery);
});
</script>
        <div class="card fwa-stats">
            <div class="card-header">
                <h4 class="card-title"><?php echo JText::_('FWMG_DOC_ADMIN_DASHBOARD_STATS'); ?></h4>
                <div class="card-subtitle"><?php echo JText::_('FWMG_DOC_ADMIN_DASHBOARD_STATS_HINT'); ?></div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col text-center" data-container="body" data-trigger="hover" data-toggle="popover" data-bs-toggle="popover" data-placement="top" data-content="<?php echo JText::_('FWMG_DOC_ADMIN_DASHBOARD_STATS_CATEGORIES_HINT'); ?>">
                        <i class="fal fa-folder fa-3x"></i>
                        <br><a href="index.php?option=com_fwgallery&view=category">
                            <?php echo JText::_('FWMG_DOC_ADMIN_DASHBOARD_STATS_CATEGORIES'); ?></a>
                        <br> <?php echo (int)$this->locstat->cp; ?> / <?php echo (int)$this->locstat->ct; ?>
                    </div>
                    <div class="col text-center" data-container="body" data-trigger="hover" data-toggle="popover" data-bs-toggle="popover" data-placement="top" data-content="<?php echo JText::_('FWMG_DOC_ADMIN_DASHBOARD_STATS_IMAGES_HINT'); ?>">
                        <i class="fal fa-image fa-3x"></i>
                        <br><a href="index.php?option=com_fwgallery&view=file&type=image"><?php echo JText::_('FWMG_DOC_ADMIN_DASHBOARD_STATS_IMAGES'); ?></a>
                        <br> <?php echo (int)$this->locstat->ip; ?> / <?php echo (int)$this->locstat->it; ?>
                    </div>
<?php
    fwgHelper::triggerEvent('onshowStatExtraFields', array('com_fwgallery', $this->locstat));
?>
                    <div class="col text-center" data-container="body" data-trigger="hover" data-toggle="popover" data-bs-toggle="popover" data-placement="top" data-content="<?php echo JText::_('FWMG_DOC_ADMIN_DASHBOARD_STATS_FILES_HINT'); ?>">
                        <i class="fal fa-hdd fa-3x"></i>
                        <br><?php echo JText::_('FWMG_DOC_ADMIN_DASHBOARD_STATS_FILES'); ?>
                        <br><?php echo fwgHelper::humanFileSize($this->locstat->fs); ?>
                    </div>
                </div>
            </div>
        </div>
<?php
    if ($this->prev_data) {
?>
		<div class="card">
			<div class="card-header">
				<h4 class="card-title"><?php echo JText::_('FWMG_DATA_IMPORT'); ?></h4>
			</div>
			<div class="card-body">
                <div class="row">
                    <div class="col-2">
                        <i class="fal fa-info-circle fa-5x text-success"></i>
                    </div>
                    <div class="col-10">
                        <?php echo JText::_('FWMG_DATA_IMPORT_TEXT'); ?>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col">
                        <div class="text-center">
                            <button class="btn btn-success text-white pt-2 fwmg-import-prev-data" data-move="0">
                                <i class="fal fa-copy mr-2"></i>
                                <?php echo JText::_('FWMG_DATA_COPY_BUTTON'); ?>
                            </button>
                            <div class="alert alert-warning mt-2">
<?php
        echo JText::_('FWMG_DATA_COPY_TEXT_BEGIN');
        echo fwgHelper::humanFileSize($this->prev_data);
        echo JText::_('FWMG_DATA_COPY_TEXT_END');
?>
        					</div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="text-center">
                            <button class="btn btn-success text-white pt-2 fwmg-import-prev-data" data-move="1">
                                <i class="fal fa-file-import mr-2"></i>
                                <?php echo JText::_('FWMG_DATA_MOVE_BUTTON'); ?>
                            </button>
                            <div class="alert alert-warning mt-2">
        						<?php echo JText::_('FWMG_DATA_MOVE_TEXT'); ?>
        					</div>
                        </div>
                    </div>
                </div>
			</div>
		</div>
<script>
document.addEventListener('DOMContentLoaded', function() {
    (function($) {
	$('.fwmg-import-prev-data').click(function() {
		var $btn = $(this);
		if (confirm('<?php echo JText::_('FWMG_ARE_YOU_SURE', true); ?>')) {
			var $img = $('<i class="fal fa-sync fa-spin ml-2"></i>');
			$('.fwmg-import-prev-data').attr('disabled', true);
			$btn.after($img);
			$.ajax({
				dataType: 'json',
                type: 'post',
				data: {
					format: 'json',
	    			view: 'fwgallery',
					layout: 'import_prev_data',
					remove_previous_data: $btn.data('move')
				}
			}).done(function(data) {
				$('.fwmg-import-prev-data').attr('disabled', false);
				$img.remove();
				if (data.result && $btn.data('move') == 1) {
					$btn.closest('.card').remove();
				}
				if (data.msg) {
					fwmg_alert(data.msg);
				}
			}).error(function(xhr, ajaxOptions, thrownError) {
				$('.fwmg-import-prev-data').attr('disabled', false);
				$img.remove();
				fwmg_alert('Error '+xhr.status+' - '+thrownError);
			});
		}
	});
    })(jQuery);
});
</script>
<?php
    }
?>
        <a name="quick-check"></a>
		<div class="card fwa-check check-<?php if ($this->qch->test_passed) { ?>success<?php } else { ?>fail<?php } ?>">
			<div class="card-header">
				<h4 class="card-title"><?php echo JText::_('FWMG_DOC_ADMIN_DASHBOARD_CHECK'); ?> - <?php echo JText::_($this->qch->test_passed?'FWMG_PASSED':'FWMG_FAILED'); ?></h4>
				<div class="card-subtitle"><?php echo JText::_('FWMG_DOC_ADMIN_DASHBOARD_CHECK_HINT'); ?></div>
			</div>
			<div class="card-body">
				<table class="table table-striped">
					<tbody><tr>
						<td><?php echo JText::_('FWMG_JOOMLA_MEDIA_FOLDER'); ?></td>
						<td>
<?php
    if ($this->qch->media_folder_exists) {
?>
							<i class="fa fa-check-circle"></i> <?php echo JText::_('FWMG_EXISTS_SMALL'); ?>
<?php
    } else {
?>
							<i class="fa fa-times-circle"></i> <?php echo JText::_('FWMG_NOT_EXISTS_SMALL'); ?>
<?php
    }
?>
						</td>
					</tr>
					<tr>
						<td><?php echo JText::_('FWMG_IMAGES_FOLDER'); ?></td>
						<td>
<?php
    if ($this->qch->gallery_folder_exists) {
?>
							<i class="fa fa-check-circle"></i> <?php echo JText::_('FWMG_EXISTS_SMALL'); ?>
<?php
    } else {
?>
							<i class="fa fa-times-circle"></i> <?php echo JText::_('FWMG_NOT_EXISTS_SMALL'); ?>
<?php
    }
?>
						</td>
					</tr>
					<tr>
						<td><?php echo JText::_('FWMG_IMAGES_FOLDER'); ?></td>
						<td>
<?php
    if ($this->qch->gallery_folder_writeable) {
?>
							<i class="fa fa-check-circle"></i> <?php echo JText::_('FWMG_WRITABLE_SMALL'); ?>
<?php
    } else {
?>
							<i class="fa fa-times-circle"></i> <?php echo JText::_('FWMG_NOT_WRITABLE_SMALL'); ?>
<?php
    }
?>
						</td>
					</tr>
					<tr>
						<td><?php echo JText::_('FWMG_IMAGES_CACHE_FOLDER'); ?></td>
						<td>
<?php
    if ($this->qch->cache_folder_exists) {
?>
							<i class="fa fa-check-circle"></i> <?php echo JText::_('FWMG_EXISTS_SMALL'); ?>
<?php
    } else {
?>
							<i class="fa fa-times-circle"></i> <?php echo JText::_('FWMG_NOT_EXISTS_SMALL'); ?>
<?php
    }
?>
						</td>
					</tr>
					<tr>
						<td><?php echo JText::_('FWMG_IMAGES_CACHE_FOLDER'); ?></td>
						<td>
<?php
    if ($this->qch->cache_folder_writeable) {
?>
							<i class="fa fa-check-circle"></i> <?php echo JText::_('FWMG_WRITABLE_SMALL'); ?>
<?php
    } else {
?>
							<i class="fa fa-times-circle"></i> <?php echo JText::_('FWMG_NOT_WRITABLE_SMALL'); ?>
<?php
    }
?>
						</td>
					</tr>
					<tr>
						<td><?php echo JText::_('FWMG_GD2_PHP_EXTENSION'); ?></td>
						<td>
<?php
    if ($this->qch->gd_installed) {
?>
							<i class="fa fa-check-circle"></i> <?php echo JText::_('FWMG_INSTALLED_SMALL'); ?>
<?php
    } else {
?>
							<i class="fa fa-times-circle"></i> <?php echo JText::_('FWMG_NOT_INSTALLED_SMALL'); ?>
<?php
    }
?>
						</td>
					</tr>
					<tr>
						<td><?php echo JText::_('FWMG_EXIF_PHP_EXTENSION'); ?></td>
						<td>
<?php
    if ($this->qch->exif_installed) {
?>
							<i class="fa fa-check-circle"></i> <?php echo JText::_('FWMG_INSTALLED_SMALL'); ?>
<?php
    } else {
?>
							<i class="fa fa-times-circle"></i> <?php echo JText::_('FWMG_NOT_INSTALLED_SMALL'); ?>
<?php
    }
?>
						</td>
					</tr>
                    <tr>
                        <td colspan="2">
                            <div class="font-weight-normal text-danger">
                                <?php echo JText::_('FWMG_ADMIN_DASHBOARD_CHECK_PHPINFO'); ?>
                            </div>
                        </td>
                    </tr>
					<tr>
						<td><?php echo JText::_('FWMG_MAX_TOTAL_POST_SIZE'); ?></td>
						<td><?php echo fwgHelper::humanFileSize(fwgHelper::getIniSize('post_max_size')); ?></td>
					</tr>
					<tr>
						<td><?php echo JText::_('FWMG_MAX_FILE_SIZE'); ?></td>
						<td><?php echo fwgHelper::humanFileSize(fwgHelper::getIniSize('upload_max_filesize')); ?></td>
					</tr>
					<tr>
						<td><?php echo JText::_('FWMG_MAX_FILES_PER_POST'); ?></td>
						<td><?php echo ini_get('max_file_uploads'); ?></td>
					</tr>
				</tbody></table>
			</div>
		</div>
		<div class="card card-review">
			<div class="card-header">
				<h4 class="card-title"><?php echo JText::_('FWMG_DOC_ADMIN_DASHBOARD_REVIEW'); ?></h4>
				<div class="card-subtitle"><?php echo JText::_('FWMG_DOC_ADMIN_DASHBOARD_REVIEW_HINT'); ?></div>
			</div>
			<div class="card-body">
				<?php echo JText::_('FWMG_ADMIN_DASHBOARD_REVIEW_TEXT'); ?>
				<div class="text-center">
                    <a href="https://extensions.joomla.org/extensions/extension/photos-a-images/galleries/fw-gallery/" target="_blank" class="btn btn-info">
                        <i class="fa fa-heart mr-1"></i> <?php echo JText::_('FWMG_DOC_ADMIN_DASHBOARD_REVIEW_LEAVE'); ?></a>
				</div>
			</div>
		</div>
	</div>
	<div class="col-lg-6 col-sm-12">
<?php
    $count_addons = 0;
    if ($this->addons) {
        $have_services = false;
        $count_services = 0;
        foreach ($this->addons as $row) {
            if ($row->subtype == 'service') {
                $have_services = true;
                $count_services++;
            } elseif (!((!$row->installed and !$row->installable) or $row->update_name == FWMG_UPDATE_NAME))  { $count_addons++ ;}
        }
        if ($have_services) {
?>
        <div class="card">
			<div class="card-header">
                <h4 class="card-title">
                    <?php echo JText::_('FWMG_DOC_ADMIN_DASHBOARD_SERVICES'); ?>
                    <span class="float-right badge badge-default"><i class="fal fa-cog mr-1"></i>  <?php echo (int)$count_services.' '.JText::_('FWMG_ADMIN_DASHBOARD_SERVICES_AVAILABLE'); ?></span>
                </h4>
                <div class="card-subtitle"><?php echo JText::_('FWMG_DOC_ADMIN_DASHBOARD_SERVICES_HINT'); ?></div>
			</div>
			<div class="card-body fwa-services">
                <table class="table table-striped">
<?php
            foreach ($this->addons as $row) {
                if ($row->subtype == 'service') {
                    $image = $row->image?$row->image:(FWMG_ADMIN_ASSETS_URI.'images/'.$row->update_name.'.jpg');
?>
                    <tr>
                        <td>
                            <img class="img-thumbnail" src="<?php echo esc_attr($image); ?>" alt="<?php echo esc_attr($row->name); ?>">
                        </td>
                        <td>
                            <div class="fwa-service-name"><?php echo esc_attr($row->name); ?></div>
                            <div class="text-muted fwa-service-description"><?php echo fwgHelper::escPluginsOutput($row->description); ?></div>
                        </td>
                        <td class="text-center">
                            <div>
                                <a class="btn btn-sm btn-info" href="<?php echo esc_attr($row->link); ?>" target="_blank">
                                    <i class="fal fa-cog mr-1"></i> <?php echo JText::_('FWMG_ADMIN_DASHBOARD_PREORDER_SERVICE'); ?>
                                </a>
                            </div>
                            <div class="mt-1">
                                <span class="font-weight-bold">$<?php echo ((int)$row->price) ? number_format($row->price, 2) : 'TBD'; ?></span>,
                                <?php echo ($row->days) ? $row->days.' '.JText::_('FWA_DAYS') : 'TBD'; ?>
                            </div>
                        </td>
                    </tr>
				
<?php
                }
            }
?>
                </table>
            </div>
        </div>
<?php
        }
    }
?>
        <div class="card">
			<div class="card-header">
				<h4 class="card-title">
                    <?php echo JText::_('FWMG_DOC_ADMIN_DASHBOARD_ADDONS'); ?>
                    <span class="float-right badge badge-default"><i class="fal fa-puzzle-piece mr-1"></i>  <?php echo (count($addons_have_install) + $addons_installed).' '.JText::_('FWMG_ADMIN_DASHBOARD_ADDONS_ADDONS'); ?></span>
                </h4>
				<div class="card-subtitle"><?php echo JText::_('FWMG_DOC_ADMIN_DASHBOARD_ADDONS_HINT'); ?></div>
			</div>
			<div class="card-body">
        <form action="">
<table class="table table-striped">
    <tr>
    	<th data-container="body" data-trigger="hover" data-toggle="popover" data-bs-toggle="popover" data-placement="top" data-content="<?php echo JText::_('FWMG_DOC_ADMIN_DASHBOARD_ADDONS_COLUMN_IMAGE_HINT'); ?>">
            <?php echo JText::_('FWMG_DOC_ADMIN_DASHBOARD_ADDONS_COLUMN_IMAGE'); ?>
        </th>
        <th data-container="body" data-trigger="hover" data-toggle="popover" data-bs-toggle="popover" data-placement="top" data-content="<?php echo JText::_('FWMG_DOC_ADMIN_DASHBOARD_ADDONS_COLUMN_ADDON_HINT'); ?>">
            <?php echo JText::_('FWMG_DOC_ADMIN_DASHBOARD_ADDONS_COLUMN_ADDON'); ?>
        </th>
        <th class="text-center" data-container="body" data-trigger="hover" data-toggle="popover" data-bs-toggle="popover" data-placement="top" data-content="<?php echo JText::_('FWMG_DOC_ADMIN_DASHBOARD_ADDONS_COLUMN_ACTIONS_HINT'); ?>">
            <?php echo JText::_('FWMG_DOC_ADMIN_DASHBOARD_ADDONS_COLUMN_ACTIONS'); ?>
        </th>
        <th data-container="body" data-trigger="hover" data-toggle="popover" data-bs-toggle="popover" data-placement="top" data-content="<?php echo JText::_('FWMG_DOC_ADMIN_DASHBOARD_ADDONS_COLUMN_TYPE_HINT'); ?>">
            <?php echo JText::_('FWMG_DOC_ADMIN_DASHBOARD_ADDONS_COLUMN_TYPE'); ?>
        </th>
    </tr>
<?php
    foreach ($this->addons as $row) {
        if ((!$row->installed and !$row->installable) or $row->update_name == FWMG_UPDATE_NAME) continue;
        $addon_type = strtolower(str_replace(' ', '-', $row->subtype));
        $has_update = (!empty($row->rem_version) and $row->loc_version != 'x.x.x' and $row->rem_version != $row->loc_version);
?>
<tr class="fws-extension-addon fwshop-item type-<?php echo esc_attr($addon_type); ?>" data-ext="<?php echo esc_attr($row->update_name); ?>">
	<td>
		<a href=""
			data-lightbox="html" data-lightbox-title="<?php echo esc_attr($row->name); ?> <?php echo esc_attr(JText::_('FWA_ADDON')); ?>">
			<img class="img-thumbnail" src="<?php echo esc_attr($row->image); ?>" alt="<?php echo esc_attr($row->name); ?>" />
		</a>
	</td>
	<td>
		<div class="fws-extension-addon-title" title="<?php echo esc_attr($row->name); ?>"><?php echo esc_html($row->name); ?></div>
<?php
        if (!empty($row->_required)) {
?>
		<div class="fws-extension-addon-required small text-danger"><i class="fal fa-exclamation-triangle"></i> <?php echo esc_html(implode(', ', $row->_required)); ?> <?php echo JText::_('FWMG_REQUIRED'); ?></div>
<?php
        }
        if ($row->installed) {
?>
		<div class="fws-extension-addon-version text-muted">
			<i class="fal fa-code-branch mr-1"></i>
			<?php echo JText::_('FWMG_ADMIN_DASHBOARD_ADDONS_INSTALLED_VERSION'); ?>
			<span class="badge badge-<?php if ($has_update) { ?>warning<?php } else { ?>success<?php } ?>"><?php echo esc_html($row->loc_version); ?></span>
		</div>
<?php
        }
        if (!empty($row->_comp_version)) {
            $version_match = true;
            $buff1 = explode('.', $comp_version, 3);
            $buff2 = explode('.', $row->_comp_version, 3);
            if (count($buff1) > 2 and count($buff2) > 2 and $buff1[0] != 'x' and ($buff1[0] < $buff2[0] or ($buff1[0] == $buff2[0] and $buff1[1] < $buff2[1]))) {
                $version_match = false;
            }
?>
        <div class="text-muted">
            <?php echo JText::_('FWMG_ADMIN_DASHBOARD_ADDONS_COMPONENT_VERSION'); ?> <span class="badge badge-<?php if ($version_match) { ?>success<?php } else { ?>danger<?php } ?>"><?php echo esc_html($row->_comp_version); ?></span>
        </div>
<?php
        }
        if (!empty($row->doc_link) or !empty($row->frontend_demo_link)) {
?>
		<div class="fws-extension-addon-links">
<?php
            if (!empty($row->doc_link)) {
?>
    			<a href="<?php echo esc_attr($row->doc_link); ?>" target="_blank">
    				<i class="fal fa-book mr-1"></i><?php echo JText::_('FWMG_ADMIN_DASHBOARD_ADDONS_DOC'); ?>
    			</a>
<?php
            }
            if (!empty($row->frontend_demo_link)) {
?>
                <a href="<?php echo esc_attr($row->frontend_demo_link); ?>" target="_blank">
                    <i class="fal fa-presentation <?php if (!empty($row->doc_link)) echo 'ml-3'; ?> mr-1"></i><?php echo JText::_('FWMG_ADMIN_DASHBOARD_ADDONS_DEMO'); ?>
                </a>
<?php
            }
?>
		</div>
<?php
        }
?>
	</td>
	<td class="text-center">
<?php
        if ($has_update) {
            if ($row->installed) {
?>
        <button type="button" class="btn btn-warning fwmg-addon-update"><?php echo JText::_('FWMG_UPDATE_TO'); ?> <?php echo esc_html($row->rem_version); ?></button>
<?php
            } else {
?>
        <button type="button" class="btn btn-warning fwmg-addon-install"><?php echo JText::_('FWMG_INSTALL_VERSION'); ?> <?php echo esc_html($row->rem_version); ?></button>
<?php
            }
        }
        if ($row->installed) {
            if ($row->enabled) {
?>
        <button type="button" class="btn btn-danger fwmg-addon-disable"><?php echo JText::_('FWMG_DISABLE'); ?></button>
<?php
            } else {
?>
        <button type="button" class="btn btn-success fwmg-addon-enable"><?php echo JText::_('FWMG_ENABLE'); ?></button>
<?php
            }
        }
?>
	</td>
	<td>
        <div class="fwshop-item-type" title="<?php echo esc_attr($row->subtype); ?>" data-trigger="hover" data-toggle="tooltip" data-placement="top">
            <?php echo ($row->subtype=='data type')?'T':substr($row->subtype, 0, 1); ?></div>
	</td>
</tr>
<?php
    }
?>
</table>
        </form>
            </div>
        </div>
<?php
}
?>
	</div>
</div>
<div class="modal" tabindex="-1" role="dialog" id="fwmg-changelog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"><?php echo JText::_('FWMG_CHANGELOG'); ?></h5>
        <button type="button" class="close" data-dismiss="modal" data-bs-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn" data-dismiss="modal" data-bs-dismiss="modal" aria-label="Close"><?php echo JText::_('FWMG_CLOSE'); ?></button>
      </div>
    </div>
  </div>
</div>
<div id="fwa-addon-description" class="modal fade">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"></h5>
        <button type="button" class="close" data-dismiss="modal" data-bs-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body"></div>
    </div>
  </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function() {
    (function($) {
    $('#fwa-addon-description').on('show', function() {
        $(this).addClass('show');
    }).on('hide', function() {
        $(this).removeClass('show');
    });
	$('#fwmg-changelog').on('show', function() {
		$(this).addClass('show');
	}).on('hide', function() {
		$(this).removeClass('show');
	});
    $('#fwmg-install-all').click(function() {
        var $btn = $(this);
        var $wrp = $btn.parent();
        $btn.remove();
        if (!$wrp.find('button').length) {
            $wrp.remove();
        }
        $('.fwmg-addon-install').click();
    });
    $('#fwmg-update-all').click(function() {
        var $btn = $(this);
        var $wrp = $btn.parent();
        $btn.remove();
        if (!$wrp.find('button').length) {
            $wrp.remove();
        }
        $('.fwmg-addon-update').click();
    });
	$('.fws-extensions-item-info a:has(".fa-file-alt")').click(function(ev) {
        ev.stopPropagation();
		var $btn = $(this).attr('disabled', true);
		$.ajax({
			'url': '',
			'type': 'post',
			dataType: 'json',
			'data': {
				'format': 'json',
				'view': 'fwgallery',
				'layout': 'get_changelog'
			}
		}).done(function(data) {
			$btn.attr('disabled', false);
			var $popup = $('#fwmg-changelog');
			$popup.find('.modal-body').html(data.result?data.result:data.msg?data.msg:'<?php echo JText::_('FWMG_NO_CHANGELOG', true); ?>');
			$popup.modal('show');
		});
	});
    $('.fwshop-item a[data-lightbox="html"]').click(function(ev) {
        ev.preventDefault();
        var $link = $(this);
        var $wrapper = $link.closest('.fwshop-item');
        var name = $wrapper.data('ext');
        var $popup = $('#fwa-addon-description');
        $popup.find('.modal-title').html($link.data('lightbox-title'));
        $popup.find('.modal-body').html('');
        $popup.modal('show');

        $.ajax({
            url: '',
            type: 'post',
            dataType: 'json',
            data: {
                format: 'json',
                view: 'addon',
                layout: 'load_addon_description',
                update_name: name
            }
        }).done(function(data) {
            if (data.html) {
                $popup.find('.modal-body').html(data.html);
            } else {
                $popup.modal('hide');
            }
            if (data.msg) {
                fwmg_alert(data.msg)
            }
        });
    });
    $('.fws-extension-addon,.fws-ext-update').on('click', '.fwmg-addon-install,.fwmg-addon-update,.fwmg-addon-enable,.fwmg-addon-disable', function() {
        var $btn = $(this);
        if ($btn.attr('disabled')) return;

        var action = '';
        if ($btn.hasClass('fwmg-addon-install')) {
            action = 'install';
        } else if ($btn.hasClass('fwmg-addon-enable')) {
            action = 'enable';
        } else if ($btn.hasClass('fwmg-addon-disable')) {
            action = 'disable';
        } else if ($btn.hasClass('fwmg-addon-update')) {
            action = 'update';
        }

        if (action == '') return;

        $btn.attr('disabled', true);

        var $wrp = $btn.closest('*[data-ext]');
		var $img = $('<img/>', {
			'src' : '<?php echo JURI::root(true); ?>/components/com_fwgallery/assets/images/ajax-loader.gif',
			'style': 'height:auto;width:auto;position:absolute;margin:9px -65px;'
		});
		$btn.after($img);
		$.ajax({
			dataType: 'json',
            type: 'post',
			data: {
				format: 'json',
				view: 'addon',
				layout: action,
				ext: [$wrp.data('ext')]
			}
		}).done(function(data) {
			$btn.attr('disabled', false);
			$img.remove();
			if (data.msgs) {
                for (var i = 0; i < data.msgs.length; i++) {
                    fwmg_alert(data.msgs[i]);
                }
			}
			if (data.result) {
				var row = data.result;
                if (row.button) {
                    $btn.html(row.button);
                }
                switch (action) {
                    case 'install' :
                    $btn.removeClass('fwmg-addon-install btn-warning').addClass('fwmg-addon-disable btn-danger').html('<?php echo JText::_('FWMG_DISABLE', true); ?>');
                    location = location.toString().replace(/#.*$/, '');
                    break;
                    case 'enable' :
                    $btn.removeClass('fwmg-addon-enable').addClass('fwmg-addon-disable').removeClass('btn-success').addClass('btn-danger').html('<?php echo JText::_('FWMG_DISABLE', true); ?>');
                    break;
                    case 'disable' :
                    $btn.removeClass('fwmg-addon-disable').addClass('fwmg-addon-enable').removeClass('btn-danger').addClass('btn-success').html('<?php echo JText::_('FWMG_ENABLE', true); ?>');
                    break;
                    case 'update' :
                    $btn.remove();
                    location = location.toString().replace(/#.*$/, '');
                    break;
                }
			}
		});
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
