<?php
/**
 * FW Gallery 6.7.2
 * @copyright (C) 2020 Fastw3b
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.fastw3b.net/ Official website
 **/

defined('_JEXEC') or die('Restricted access');

JToolBarHelper::title(JText::_('FWMG_ADMIN_DATA_TOOLBAR_TITLE'), ' fal fa-database');

echo JLayoutHelper::render('common.menu_begin', array(
    'view' => $this,
    'title' => JText::_('FWMG_DOC_ADMIN_DATA'),
    'title_hint' => JText::_('FWMG_DOC_ADMIN_DATA_HINT')
), JPATH_COMPONENT);
?>
<div class="fwa-filter-bar">
<ul class="nav nav-tabs" role="tablist">
<?php
$tab_displayed = false;
if ($this->current_user->authorise('core.create', 'com_fwgallery')) {
	$tab_displayed = true;
?>
	<li class="nav-item">
		<a class="nav-link active" data-toggle="tab" data-bs-toggle="tab" href="#fwmg-restore" role="tab"><?php echo JText::_('FWMG_DOC_ADMIN_DATA_TAB_RESTORE'); ?></a>
	</li>
<?php
}
?>
	<li class="nav-item">
		<a class="nav-link<?php if (!$tab_displayed) { ?> active<?php } ?>" data-toggle="tab" data-bs-toggle="tab" href="#fwmg-backup" role="tab"><?php echo JText::_('FWMG_DOC_ADMIN_DATA_TAB_BACKUP'); ?></a>
	</li>
<?php
if ($this->current_user->authorise('core.create', 'com_fwgallery')) {
?>
	<li class="nav-item">
		<a class="nav-link" data-toggle="tab" data-bs-toggle="tab" href="#fwmg-data" role="tab"><?php echo JText::_('FWMG_ADMIN_DATA_TAB_ADDONS'); ?></a>
	</li>
<?php
}
?>
</ul>
</div>
<div class="tab-content">
<?php
$tab_displayed = false;
if ($this->current_user->authorise('core.create', 'com_fwgallery')) {
	$tab_displayed = true;
?>
	<div class="tab-pane active" id="fwmg-restore" role="tabpanel">
		<div class="fwa-main-body">
			<form action="index.php?option=com_fwgallery&amp;view=data" method="post" name="adminForm">
				<div class="row fwa-mb-cardbox">
					<div class="col-lg-6 col-sm-12">
						<div class="card">
							<div class="card-header">
								<h4 class="card-title"><?php echo JText::_('FWMG_DOC_ADMIN_DATA_TAB_RESTORE_RESTORE'); ?></h4>
								<div class="card-subtitle"><?php echo JText::_('FWMG_DOC_ADMIN_DATA_TAB_RESTORE_RESTORE_HINT'); ?></div>
								<div class="mt-1 text-muted"><em><?php echo JText::_('FWMG_ADMIN_DATA_TAB_RESTORE_RESTORE_HINT'); ?> <?php echo str_replace('\\', '/', JPATH_SITE); ?>/media/com_fwgallery/backups</em></div>
							</div>
							<div class="card-body">
								<table class="table">
									<tbody id="fwmg-import-files">
<?php
if ($this->backups) {
	foreach ($this->backups as $file) {
?>
										<tr>
											<td><?php echo esc_html($file); ?></td>
											<td>
												<button type="button" class="btn btn-primary"><?php echo JText::_('FWMG_RESTORE'); ?></button>
												<button type="button" class="btn btn-danger"><?php echo JText::_('FWMG_DELETE'); ?></button>
											</td>
										</tr>
<?php
	}
}
?>
									</tbody>
								</table>

								<div class="mt-5"><?php echo JText::_('FWMG_IMPORT_UPLOAD_HINT'); ?></div>
								<div class="input-group mb-3">
									<div class="custom-file">
										<input type="file" class="custom-file-input" id="fwmg-input-import" name="upload" />
										<label class="custom-file-label" for="fwmg-input-import"><?php echo JText::_('FWMG_CHOOSE_FILE'); ?></label>
									</div>
									<div class="input-group-append">
										<button type="button" class="btn btn-success" id="fwmg-import-upload"><i class="fa fa-upload mr-2"></i><?php echo JText::_('FWMG_UPLOAD'); ?></button>
									</div>
								</div>
								<div class="text-muted"><small><?php echo JText::sprintf('FWMG_MAX_ZIP_SIZE_LONG', ini_get('post_max_size')); ?></small></div>

								<div class="mt-2 fwmg-data-import-result"></div>
								<?php echo JHTML::_('fwView.handleUploadButton', array(
									'button' => '#fwmg-import-upload',
									'input' => '#fwmg-input-import',
									'image' => '',
									'exts' => array('zip'),
									'view' => 'data',
									'layout' => 'backup_upload',
									'callback' => 'function(html) {
	$progress_bar.remove();
	$button.attr(\'disabled\', false);
	$input.next().html(\''.JText::_('FWMG_CHOOSE_FILE', false).'\');

	var $parent = $input.parent();
	var input_html = $parent.html();
	$parent.html(input_html);

	var $cont = $(\'#fwmg-import-files\');
	var data = $.parseJSON(html);
	if (data) {
		if (data.result) {
			$(\'.fwmg-data-import-result\').html(\''.JText::_('FWMG_EXPORT_FILE_CREATED', true).'\'+data.result);
			var $row = $(\'<tr>\\
	<td>\'+data.result+\'</td>\\
	<td>\\
		<button type="button" class="btn btn-primary">'.JText::_('FWMG_RESTORE', true).'</button>\\
		<button type="button" class="btn btn-danger">'.JText::_('FWMG_DELETE', true).'</button>\\
	</td>\\
</tr>\');
			$(\'#fwmg-import-files\').append($row);
		}
		if (data.msg) fwmg_alert(data.msg);
	}
}'
								)); ?>
							</div>
						</div>
					</div>
				</div>
			</form>
		</div>
	</div>
<?php
}
?>
	<div class="tab-pane<?php if (!$tab_displayed) { ?> active<?php } ?>" id="fwmg-backup" role="tabpanel">
		<div class="fwa-main-body">
			<form action="index.php?option=com_fwgallery&amp;view=data" method="post" name="adminForm">
				<div class="row fwa-mb-cardbox">
					<div class="col-lg-6 col-sm-12">
						<div class="card">
							<div class="card-header">
								<h4 class="card-title"><?php echo JText::_('FWMG_DOC_ADMIN_DATA_TAB_BACKUP_BACKUP'); ?></h4>
								<div class="card-subtitle"><?php echo JText::_('FWMG_DOC_ADMIN_DATA_TAB_BACKUP_BACKUP_HINT'); ?></div>
								<div class="mt-1 text-muted"><em><?php echo JText::_('FWMG_ADMIN_DATA_TAB_BACKUP_BACKUP_HINT'); ?> <?php echo str_replace('\\', '/', JPATH_SITE); ?>/media/com_fwgallery/backups</em></div>
							</div>
							<div class="card-body">
								<div class="form-group row">
									<label class="col-sm-5 col-form-label clearfix">
										<?php echo JText::_('FWMG_DOC_ADMIN_DATA_TAB_BACKUP_BUCONTENT'); ?>
										<i class="pull-right fa fa-question-circle" data-container="body" data-trigger="hover" data-toggle="popover" data-bs-toggle="popover" data-placement="top" data-content="<?php echo esc_attr(JText::_('FWMG_DOC_ADMIN_DATA_TAB_BACKUP_BUCONTENT_HINT')); ?>"></i>
									</label>
									<div class="col-sm-7">
										<div class="form-group">
											<input type="checkbox" id="fwmg-pdata" name="pdata" value="1" checked="checked" disabled="disabled" />
											<label for="fwmg-pdata"><?php echo JText::_('FWMG_ADMIN_DATA_TAB_BACKUP_DATA'); ?></label>
										</div>
										<div class="form-group">
											<input type="checkbox" id="fwmg-pimages" name="pimages" value="1" checked="checked" />
											<label for="fwmg-pimages"><?php echo JText::_('FWMG_ADMIN_DATA_TAB_BACKUP_FILES'); ?></label>
										</div>
									</div>
								</div>
								<button type="button" id="fwmg-data-export" class="btn btn-success"><i class="fa fa-upload mr-2"></i><?php echo JText::_('FWMG_BACKUP'); ?></button>
								<div class="mt-2 fwmg-data-export-result"></div>
							</div>
						</div>
					</div>
				</div>
			</form>
		</div>
	</div>
<?php
if ($this->current_user->authorise('core.create', 'com_fwgallery')) {
?>
	<div class="tab-pane" id="fwmg-data" role="tabpanel">
		<div class=" fwa-main-body fwa-mb-cardbox">
<?php
if ($this->plugins) {
	foreach ($this->plugins as $i=>$plugin) {
		if ($plugin) echo fwgHelper::escPluginsOutput($plugin);
	}
}
?>
		</div>
	</div>
<?php
}
?>
</div>
<script>
document.addEventListener('DOMContentLoaded', function() {
	(function($) {
	var hash = location.hash;
	if (hash != '') {
		if (hash == '#_=_') {
			$('a[href="#fwmg-data"').click();
		} else {
			$('a[href="'+hash+'"').click();
		}
	}
	$('.fwa-filter-bar a.nav-link').click(function() {
		if ($(this).attr('href') == '#fwmg-data') {
			location.hash = '#_=_';
		} else {
			location.hash = $(this).attr('href');
		}
	});
	$('#fwmg-import-files').on('click', '.btn-primary', function() {
		var $row = $(this).closest('tr');
		var file = $(this).closest('td').prev().html();
		var $output = $('.fwmg-data-import-result').html('');
		if (confirm('<?php echo JText::_('FWMG_ARE_YOU_SURE_BACKUP_RESTORE', true); ?> '+file)) {
			$('#fwmg-import-files').find('button').attr('disabled', true);
			fwmg_alert('<?php echo JText::_('FWMG_BACKUP_UNPACKING_AND_APPLYING', true); ?>');
			$.ajax({
				dataType: 'json',
				data: {
					format: 'json',
	    			view: 'data',
					layout: 'backup_restore',
					filename: file
				}
			}).done(function (data) {
				$('#fwmg-import-files').find('button').attr('disabled', false);
				if (data.result) {
					fwmg_alert('<?php echo JText::_('FWMG_BACKUP_SUCCESFULLY_INSTALLED', true); ?>');
				}
				if (data.msg) {
					fwmg_alert(data.msg);
				}
			});
		}
	}).on('click', '.btn-danger', function() {
		var $row = $(this).closest('tr');
		var file = $(this).closest('td').prev().html();
		var $output = $('.fwmg-data-import-result').html('');
		if (confirm('<?php echo JText::_('FWMG_ARE_YOU_SURE_BACKUP_DELETE', true); ?> '+file)) {
			$row.find('button').attr('disabled', true);
			$.ajax({
				dataType: 'json',
				data: {
					format: 'json',
	    			view: 'data',
					layout: 'backup_delete',
					filename: file
				}
			}).done(function (data) {
				$row.find('button').attr('disabled', false);
				if (data.result) {
					$row.remove();
				}
				if (data.msg) {
					fwmg_alert(data.msg);
				}
			});
		}
	});
	$('#fwmg-data-export').click(function() {
		var $btn = $(this);
		var $form = $(this.form);

		var $output = $('.fwmg-data-export-result').html('');
		if ($form.find('input:checked').length) {
			$btn.attr('disabled', true);
			fwmg_alert('<?php echo JText::_('FWMG_EXPORT_DATABASE_DATA', true); ?>');
			$.ajax({
				dataType: 'json',
				data: {
					format: 'json',
	    			view: 'data',
					layout: 'export_data'
				}
			}).done(function(data) {
				if (data.result) {
					var images = $form.find('input[name="pimages"]:checked').length;
					fwmg_alert(images?'<?php echo JText::_('FWMG_ZIPPING_DATA_IMAGES', true); ?>':'<?php echo JText::_('FWMG_ZIPPING_DATA', true); ?>');
					$.ajax({
						dataType: 'json',
						data: {
							format: 'json',
							view: 'data',
							layout: 'store_export_data',
							images: images,
							folder: data.result
						}
					}).done(function(data) {
						$btn.attr('disabled', false);
						if (data.result) {
							fwmg_alert('<?php echo JText::_('FWMG_EXPORT_FILE_CREATED', true); ?> '+data.result);
							var $raw = $('<tr>\
	<td>'+data.result+'</td>\
	<td>\
		<button type="button" class="btn btn-primary"><?php echo JText::_('FWMG_RESTORE', true); ?></button>\
		<button type="button" class="btn btn-danger"><?php echo JText::_('FWMG_DELETE', true); ?></button>\
	</td>\
</tr>');
							$('#fwmg-import-files').append($raw);
						}
						if (data.msg) {
							fwmg_alert(data.msg);
						}
					});
				}
				if (data.msg) {
					fwmg_alert(data.msg);
				}
			});
		} else {
			fwmg_alert('<?php echo JText::_('FWMG_SELECT_WHAT_TO_EXPORT', true); ?>');
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
