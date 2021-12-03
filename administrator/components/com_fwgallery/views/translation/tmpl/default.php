<?php
/**
 * FW Gallery 6.7.2
 * @copyright C 2019 Fastw3b
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.fastw3b.net/ Official website
 **/

defined( '_JEXEC' ) or die( 'Restricted access' );

JToolBarHelper::title(JText::_('FWMG_ADMIN_LANG_TOOLBAR_TITLE'), ' fal fa-globe');

fwgButtonsHelper::apply('appy', 'FWMG_DOC_ADMIN_LANG_TOOLBAR_BTN_SAVE');
fwgButtonsHelper::custom('fal fa-copy', '', 'FWMG_DOC_ADMIN_LANG_TOOLBAR_BTN_OVERRIDES', 'copy', true);
fwgButtonsHelper::custom('fal fa-download', '', 'FWMG_DOC_ADMIN_LANG_TOOLBAR_BTN_BACKUP', 'download', false);
if (!file_exists($this->path)) {
    fwgButtonsHelper::custom('fal fa-upload', '', 'FWMG_DOC_ADMIN_LANG_TOOLBAR_BTN_INSTALLNATIVELANGUAGE', 'request', false);
}

echo JLayoutHelper::render('common.menu_begin', array(
    'title' => JText::_('FWMG_DOC_ADMIN_LANG'),
    'title_hint' => JText::_('FWMG_DOC_ADMIN_LANG_HINT'),
	'view' => $this
), JPATH_COMPONENT);
?>
<form action="index.php?option=com_fwgallery&amp;view=translation" method="post" class="fwmg-translations form-validate" name="adminForm" id="adminForm" enctype="multipart/form-data">
	<div class="fwa-filter-bar clearfix">
		<div class="float-left w-25">
			<div class="input-group mb-2">
				<input class="form-control" placeholder="<?php echo esc_attr(JText::_('FWMG_DOC_ADMIN_LANG_FILTER_SEARCH')); ?>" type="text" name="search" value="<?php echo esc_attr($this->search); ?>"
				data-container="body" data-trigger="hover" data-toggle="popover" data-bs-toggle="popover" data-placement="top" data-content="<?php echo JText::_('FWMG_DOC_ADMIN_LANG_FILTER_SEARCH_HINT'); ?>">
				<span class="input-group-btn">
					<button class="btn" type="button" onclick="with(this.form){search.value='';task.value='';submit();}" title="<?php echo esc_attr(JText::_('FWMG_DOC_ADMIN_LANG_FILTER_SEARCH_BTN_CLEAR')); ?>" data-toggle="tooltip" data-bs-toggle="tooltip"><i class="fal fa-times"></i></button>
				</span>
				<span class="input-group-btn">
					<button class="btn btn-primary" type="submit" onclick="with(this.form){task.value='';submit();}" title="<?php echo esc_attr(JText::_('FWMG_DOC_ADMIN_LANG_FILTER_SEARCH_BTN_SEARCH')); ?>" data-toggle="tooltip" data-bs-toggle="tooltip"><i class="fal fa-search"></i></button>
				</span>
			</div>
		</div>
		<div class="float-left ml-3" data-container="body" data-trigger="hover" data-toggle="popover" data-bs-toggle="popover" data-placement="top" data-content="<?php echo JText::_('FWMG_DOC_ADMIN_LANG_FILTER_LANG_HINT'); ?>">
			<div class="select-style">
				<?php echo JHTML::_('select.genericlist', $this->languages, 'lang', 'class="form-control form-control-sm select-choices" onchange="this.form.task.value=\'\';if(this.form.limitstart){this.form.limitstart.value=0;}this.form.submit();" style="float:none;"', 'id', 'name', $this->language); ?>
			</div>
		</div>
		<div class="float-left ml-3">
			<div class="select-style" data-container="body" data-trigger="hover" data-toggle="popover" data-bs-toggle="popover" data-placement="top" data-content="<?php echo JText::_('FWMG_DOC_ADMIN_LANG_FILTER_ADDON_HINT'); ?>">
				<?php echo JHTML::_('select.genericlist', $this->extensions, 'extension', 'class="form-control form-control-sm select-choices" onchange="this.form.task.value=\'\';this.form.submit();"', 'element', 'name', $this->extension); ?>
			</div>
		</div>
<?php
if ($this->obj->type == 'component') {
?>
		<div class="float-left ml-3" data-container="body" data-trigger="hover" data-toggle="popover" data-bs-toggle="popover" data-placement="top" data-content="<?php echo JText::_('FWMG_DOC_ADMIN_LANG_FILTER_ADMINSITE_HINT'); ?>">
			<?php echo JHTMLfwView::radioGroup('type', $this->type, array(
				'wrapper_class' => 'mr-2',
				'buttons' => array(array(
					'active_class' => 'btn-success',
					'title' => JText::_('FWMG_FRONTEND'),
					'value' => 1
				), array(
					'active_class' => 'btn-success',
					'title' => JText::_('FWMG_BACKEND'),
					'value' => 0
				))
			)); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
	(function($) {
		$('input[name="type"]').change(function() {
			this.form.submit();
		});
	})(jQuery);
});
</script>
		</div>
<?php
}
?>
	</div>
    <div class="pt-3">
    <div class="alert alert-info">
        <?php echo JText::_('FWMG_ADMIN_LANG_HINT_MAIN'); ?>: <?php echo esc_html($this->path); ?>
    </div>
    <div class="alert alert-danger">
    <?php echo JText::_('FWMG_ADMIN_LANG_HINT_OVERRIDE'); ?>: <?php echo esc_html($this->path_overrides); ?>
    </div>
	<table class="table table-striped">
		<thead>
			<tr>
                <th width="20px"><input name="toggle" value="" onclick="Joomla.checkAll(this);" type="checkbox" /></th>
				<th width="25%" data-container="body" data-trigger="hover" data-toggle="popover" data-bs-toggle="popover" data-placement="top" data-content="<?php echo JText::_('FWMG_DOC_ADMIN_LANG_COLUMN_SOURCE_HINT'); ?>"><?php echo JText::_('FWMG_DOC_ADMIN_LANG_COLUMN_SOURCE'); ?> (en-GB)</th>
                <th width="30px" data-container="body" data-trigger="hover" data-toggle="popover" data-bs-toggle="popover" data-placement="top" data-content="<?php echo JText::_('FWMG_DOC_ADMIN_LANG_COLUMN_OVERRIDES_HINT'); ?>"><?php echo JText::_('FWMG_DOC_ADMIN_LANG_COLUMN_OVERRIDES'); ?></th>
				<th data-container="body" data-trigger="hover" data-toggle="popover" data-bs-toggle="popover" data-placement="top" data-content="<?php echo JText::_('FWMG_DOC_ADMIN_LANG_COLUMN_OUTCOME_HINT'); ?>"><?php echo JText::_('FWMG_DOC_ADMIN_LANG_COLUMN_OUTCOME'); ?> (<?php echo esc_html($this->languages[$this->language]->tag); ?>)</th>
                <th width="10%" class="text-center" data-container="body" data-trigger="hover" data-toggle="popover" data-bs-toggle="popover" data-placement="top" data-content="<?php echo JText::_('FWMG_DOC_ADMIN_LANG_COLUMN_TOOVERRIDES_HINT'); ?>"><?php echo JText::_('FWMG_DOC_ADMIN_LANG_COLUMN_TOOVERRIDES') ?></th>
			</tr>
		</thead>
		<tbody id="fwre-translations-list">
<?php
$num = 0;
foreach ($this->data as $const=>$row) {
?>
			<tr>
                <td><?php echo JHTML::_('grid.id', $num, $const); ?></td>
				<td>
                    <?php echo fwgHelper::escPluginsOutput($row['src']); ?>
                    <div class="small text-muted mt-1">
                        <?php echo esc_html($const); ?>
                    </div>
                </td>
                <td class="text-center"><?php if (isset($row['override'])) { ?><span data-trigger="hover" data-toggle="tooltip" data-bs-toggle="tooltip" data-placement="top" title="<?php echo esc_attr(JText::_('FWMG_OVERRIDES_HINT')); ?>"><i class="text-danger fas fa-exclamation-triangle"></i></span><?php } ?></td>
				<td><input type="text" class="form-control" style="width:100%" name="lang_data[<?php echo esc_attr($const); ?>]" value="<?php echo esc_attr($row['trg']); ?>" /></td>
                <td class="text-center"><button class="btn btn-danger" type="button"><i class="far fa-copy mr-1"></i> <?php echo JText::_('FWMG_DOC_ADMIN_LANG_COLUMN_TOOVERRIDES_BTN_COPY') ?></button></td>
			</tr>
<?php
}
?>
		</tbody>
	</table>
	<div class="row">
		<div class="col-md-2 mt-4 text-center">
			<?php echo $this->pagination->getResultsCounter(); ?>
		</div>
		<div class="col-md-8">
			<?php echo $this->pagination->getListFooter(); ?>
		</div>
		<div class="col-md-2 mt-4 text-center pagination-limit">
			<div class="d-inline-block mr-3">
				<?php echo $this->pagination->getLimitBox(); ?>
			</div>
		</div>
	</div>
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="format" value="html" />
	<input type="hidden" name="layout" value="default" />
	<input type="hidden" name="boxchecked" value="0" />
    </div>
</form>
<script>
document.addEventListener('DOMContentLoaded', function() {
	(function($) {
	Joomla.submitbutton = function(pressbutton) {
		if (pressbutton) {
			with (document.adminForm) {
				if (pressbutton == 'download') {
					layout.value = 'download';
					format.value = 'raw';
					task.value = '';
					document.adminForm.submit();
					layout.value = 'deafult';
					format.value = 'html';
				} else if (pressbutton == 'request') {
					var $wait = $('<i class="fas fa-sync fa-spin" style="font-size:1rem;vertical-align:middle;"></i>');
					var $btn = $('#toolbar-upload .button-upload').attr('disabled', true).after($wait);
					$.ajax({
						dataType: 'json',
						data: {
							format: 'json',
							view: 'translation',
							layout: 'request_translation',
							extension: '<?php echo esc_js($this->extension); ?>',
							lang: '<?php echo esc_js($this->language); ?>',
							type: $('input[name="type"]').val()
						}
					}).done(function(data) {
						$btn.attr('disabled', false);
						$wait.remove();
						if (data.msg) {
							fwmg_alert(data.msg);
						}
						if (data.result) {
							location = 'index.php?option=com_fwgallery&view=translation&extension=<?php echo esc_js($this->extension); ?>&type='+$('input[name="type"]').val();
						}
					});
				} else {
					layout.value = 'deafult';
					format.value = 'html';
					task.value = pressbutton;
					document.adminForm.submit();
				}
			}
		}
	}
    $('#fwre-translations-list button:has(".fa-copy")').click(function() {
        var $btn = $(this).attr('disabled', true);
        var $row = $btn.closest('tr');

        var id = $row.find('input[type="text"]').attr('name').replace('lang_data[', '').replace(']', '');
        var lang_data = {};
        lang_data[id] = $row.find('input[type="text"]').val();
        $.ajax({
            dataType: 'json',
            data: {
                format: 'json',
                view: 'translation',
                layout: 'copy_to_overrides',
                lang: '<?php echo esc_js($this->language); ?>',
                type: $('input[name="type"]').val(),
                cid: [id],
                lang_data: lang_data
            }
        }).done(function(data) {
            $btn.attr('disabled', false);
            if (data.result) {
                var $icon = $('<i class="text-danger fal fa-exclamation-triangle"></i>');
                $btn.parent().prev().prev().html('<span data-trigger="hover" data-toggle="tooltip" data-bs-toggle="tooltip" data-placement="top" title="<?php echo JText::_('FWMG_OVERRIDES_HINT', true); ?>"><i class="text-danger fas fa-exclamation-triangle"></i></span>');
            }
            if (data.msg) {
                fwmg_alert(data.msg);
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
