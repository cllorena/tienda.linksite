<?php
/**
 * FW Gallery 6.7.2
 * @copyright (C) 2020 Fastw3b
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.fastw3b.net/ Official website
 **/

defined('_JEXEC') or die('Restricted access');

JToolBarHelper::title(JText::_('FWMG_ADMIN_FILES_TOOLBAR_TITLE'), ' fal fa-photo-video');

if ($this->current_user->authorise('core.create', 'com_fwgallery')) {
	fwgButtonsHelper::addNew('add', 'FWMG_DOC_ADMIN_FILES_TOOLBAR_BTN_NEW');
	fwgButtonsHelper::custom('fal fa-upload', 'purple', 'FWMG_DOC_ADMIN_FILES_TOOLBAR_BTN_UPLOAD', 'import', false);
}
if ($this->current_user->authorise('core.edit', 'com_fwgallery')) {
	fwgButtonsHelper::editList('edit', 'FWMG_DOC_ADMIN_FILES_TOOLBAR_BTN_EDIT');
}
if ($this->current_user->authorise('core.delete', 'com_fwgallery')) {
	fwgButtonsHelper::deleteList(JText::_('FWMG_ADMIN_FILES_TOOLBAR_BTN_DELETE_MSG'), 'remove' , 'FWMG_DOC_ADMIN_FILES_TOOLBAR_BTN_DELETE');
}
if ($this->current_user->authorise('core.edit', 'com_fwgallery')) {
	fwgButtonsHelper::publish('publish', 'FWMG_DOC_ADMIN_FILES_TOOLBAR_BTN_PUBLISH');
	fwgButtonsHelper::unpublish('unpublish', 'FWMG_DOC_ADMIN_FILES_TOOLBAR_BTN_UNPUBLISH');
	fwgButtonsHelper::custom('fal fa-box-check', '', 'FWMG_DOC_ADMIN_FILES_TOOLBAR_BTN_BATCH', 'batch', true);
	fwgButtonsHelper::custom('fal fa-save', '', 'FWMG_DOC_ADMIN_FILES_TOOLBAR_BTN_SAVEALL', 'saveall', false);
}

fwgHelper::triggerEvent('ongetFilesListingAdditionalTopButtons', array('com_fwgallery'));

echo JLayoutHelper::render('common.menu_begin', array(
    'view' => $this,
    'title' => JText::_('FWMG_DOC_ADMIN_FILES'),
    'title_hint' => JText::_('FWMG_DOC_ADMIN_FILES_HINT')
), JPATH_COMPONENT);
?>
<form action="index.php?option=com_fwgallery&amp;view=file" id="adminForm" name="adminForm" method="post">
    <div class="fwa-filter-bar fwa-filter-bar-float clearfix">
        <div class="float-left" style="width:30%;">
            <div class="input-group">
                <input class="form-control" placeholder="<?php echo JText::_('FWMG_DOC_ADMIN_FILES_FILTER_SEARCH'); ?>" type="text" name="search" value="<?php echo esc_attr($this->search); ?>" data-trigger="hover" data-toggle="popover" data-bs-toggle="popover" data-placement="top" data-content="<?php echo JText::_('FWMG_DOC_ADMIN_FILES_FILTER_SEARCH_HINT'); ?>" />
                <span class="input-group-btn">
                    <button class="btn" type="button" onclick="with(this.form){if(this.form.limitstart){limitstart.value=0;}search.value='';submit();}"><i class="fal fa-times"></i></button>
                </span>
                <span class="input-group-btn">
                    <button class="btn btn-primary" type="submit"><i class="fal fa-search"></i></button>
                </span>
            </div>
        </div>
        <div class="float-left ml-3">
            <div class="input-group" placeholder="<?php echo JText::_('FWMG_DOC_ADMIN_FILES_FILTER_CATEGORY'); ?>" type="text" name="search" value="<?php echo esc_attr($this->search); ?>" data-trigger="hover" data-toggle="popover" data-bs-toggle="popover" data-placement="top" data-content="<?php echo JText::_('FWMG_DOC_ADMIN_FILES_FILTER_CATEGORY_HINT'); ?>">
                <?php echo JHTML::_('fwsgCategory.getCategories', 'category', $this->category, 'class="form-control form-control-sm select-choices" onchange="with(this.form){if(this.form.limitstart){limitstart.value=0;}submit();}"', false, 'FWMG_SELECT_GALLERY'); ?>
            </div>
        </div>
        <div class="float-left ml-3">
            <div class="input-group" placeholder="<?php echo JText::_('FWMG_DOC_ADMIN_FILES_FILTER_OWNER'); ?>" type="text" name="search" value="<?php echo esc_attr($this->search); ?>" data-trigger="hover" data-toggle="popover" data-bs-toggle="popover" data-placement="top" data-content="<?php echo JText::_('FWMG_DOC_ADMIN_FILES_FILTER_OWNER_HINT'); ?>">
                <?php echo JHTML::_('select.genericlist', array_merge(array(
                    JHTML::_('select.option', '', JText::_('FWMG_SELECT_OWNER'), 'id', 'name')
                ), $this->users), 'user', 'class="form-control form-control-sm select-choices" onchange="with(this.form){if(this.form.limitstart){limitstart.value=0;}submit();}"', 'id', 'name', $this->user); ?>
            </div>
        </div>
<?php
if (count($this->types) > 1) { 
?>
        <div class="float-left ml-3">
            <div class="input-group" data-trigger="hover" data-toggle="popover" data-bs-toggle="popover" data-placement="top" data-content="<?php echo JText::_('FWMG_DOC_ADMIN_FILES_FILTER_TYPES_HINT'); ?>">
                <?php echo JHTMLfwView::radioGroup('tab', $this->tab, array(
                    'wrapper_class' => 'mb-0',
                    'buttons' => $this->types
                )); ?>
            </div>
        </div>
<?php
}
?>
    </div>
    <table class="table table-striped fwmg-admin-images">
        <thead>
            <tr>
                <th><input name="toggle" value="" onclick="Joomla.checkAll(this);" type="checkbox"></th>
                <th data-trigger="hover" data-toggle="popover" data-bs-toggle="popover" data-placement="top" data-content="<?php echo JText::_('FWMG_DOC_ADMIN_FILES_COLUMN_PREVIEW_HINT'); ?>"><?php echo JText::_('FWMG_DOC_ADMIN_FILES_COLUMN_PREVIEW'); ?></th>
                <th data-trigger="hover" data-toggle="popover" data-bs-toggle="popover" data-placement="top" data-content="<?php echo JText::_('FWMG_DOC_ADMIN_FILES_COLUMN_FILE_HINT'); ?>">
                    <a href="index.php?option=com_fwgallery&view=file<?php echo str_replace(array('&amp;ordering=created', '&amp;ordering=name', '&amp;ordering=ordering'), '', $this->extra_link).'&amp;ordering=created'; ?>">
                        <?php echo JText::_('FWMG_DOC_ADMIN_FILES_COLUMN_FILE'); ?>
                    </a>
                </th>
                <th width="25%" data-trigger="hover" data-toggle="popover" data-bs-toggle="popover" data-placement="top" data-content="<?php echo JText::_('FWMG_DOC_ADMIN_FILES_COLUMN_DESCRIPTION_HINT'); ?>"><?php echo JText::_('FWMG_DOC_ADMIN_FILES_COLUMN_DESCRIPTION'); ?></th>
                <th data-trigger="hover" data-toggle="popover" data-bs-toggle="popover" data-placement="top" data-content="<?php echo JText::_('FWMG_DOC_ADMIN_FILES_COLUMN_CATEGORY_HINT'); ?>"><?php echo JText::_('FWMG_DOC_ADMIN_FILES_COLUMN_CATEGORY'); ?></th>
<?php
fwgHelper::triggerEvent('onshowFileListExtraHeaders', array('com_fwgallery', $this));
?>
                <th data-trigger="hover" data-toggle="popover" data-bs-toggle="popover" data-placement="top" data-content="<?php echo JText::_('FWMG_DOC_ADMIN_FILES_COLUMN_ORDER_HINT'); ?>">
                    <a href="index.php?option=com_fwgallery&view=file<?php echo str_replace(array('&amp;ordering=created', '&amp;ordering=name', '&amp;ordering=ordering'), '', $this->extra_link).'&amp;ordering=ordering'; ?>">
                    	<?php echo JText::_('FWMG_DOC_ADMIN_FILES_COLUMN_ORDER'); ?>
                    </a>
<?php
if ($this->current_user->authorise('core.edit', 'com_fwgallery') and $this->order == 'ordering') {
?>
                	<a class="btn" href="javascript:"  onclick="javascript:saveorder(<?php echo (int)count($this->list)-1; ?>, 'saveorder')"><i class="fa fa-save"></i></a>
<?php
}
?>
                </th>
                <th data-trigger="hover" data-toggle="popover" data-bs-toggle="popover" data-placement="top" data-content="<?php echo JText::_('FWMG_DOC_ADMIN_FILES_COLUMN_STATS_HINT'); ?>"><?php echo JText::_('FWMG_DOC_ADMIN_FILES_COLUMN_STATS'); ?></th>
            </tr>
        </thead>
        <tbody>
<?php
if ($this->list) {
    $num = 0;
    foreach ($this->list as $row) {
        if ($row->type == 'image') {
?>

            <tr>
                <td><?php echo JHTML::_('grid.id', $num, $row->id); ?></td>
                <td>
                    <img class="img-thumbnail" src="<?php echo JURI::root(true); ?>/index.php?option=com_fwgallery&amp;view=item&amp;layout=img&amp;format=raw&amp;w=90&amp;h=60&amp;id=<?php echo (int)$row->id; ?>">
<?php
		if ($this->current_user->authorise('core.edit', 'com_fwgallery')) {
?>
                    <div class="input-group input-group-sm mt-1">
                        <!-- <span class="input-group-btn">
                            <a href="index.php?option=com_fwgallery&amp;view=file&amp;task=counterclockwise&amp;cid[]=<?php echo esc_attr($row->id.$this->extra_link); ?>" class="btn"><i class="fal fa-undo"></i></a>
                        </span>
                        <span class="input-group-btn">
                            <a href="index.php?option=com_fwgallery&amp;view=file&amp;task=clockwise&amp;cid[]=<?php echo esc_attr($row->id.$this->extra_link); ?>" class="btn"><i class="fal fa-redo"></i></a>
                        </span> -->
                        <span class="input-group-btn">
                            <a href="index.php?option=com_fwgallery&amp;view=file&amp;task=<?php if ($row->published) { ?>un<?php } ?>publish&amp;cid[]=<?php echo esc_attr($row->id.$this->extra_link); ?>" class="btn<?php if ($row->published) { ?> text-success<?php } else { ?> text-danger<?php } ?>"><i class="fas fa-<?php if ($row->published) { ?>check<?php } else { ?>times<?php } ?>"></i></a>
                        </span>
<?php
			        fwgHelper::triggerEvent('ongetFilesListingAdditionalListingButtons', array('com_fwgallery', $row, $this->tab, $this->extra_link));
?>

                    </div>
<?php
		}
?>
                </td>
                <td>

                    
<?php
		if ($this->current_user->authorise('core.edit', 'com_fwgallery')) {
?>
                    <a href="index.php?option=com_fwgallery&amp;view=file&amp;task=edit&amp;cid[]=<?php echo esc_attr($row->id.$this->extra_link); ?>"><?php echo esc_html($row->name); ?></a>
<?php
		} else {
?>
					<?php echo esc_html($row->name); ?>
<?php
		}
?>
                    <div class="mt-1">
                        <span class="small text-muted mr-2">ID: <?php echo (int)$row->id; ?></span>
                        <?php if ($row->_user_name) : ?>
                            <span class="small mr-2"><i class="fal fa-user mr-1"></i> <?php echo esc_html($row->_user_name); ?></span>
                        <?php endif; ?>
                        <span class="small mr-2"><i class="fal fa-calendar-alt mr-1"></i> <?php echo JHTML::date($row->created, 'd M y, H:i'); ?></span>
                        <span class="small"><i class="fal fa-lock mr-1"></i> <?php echo esc_html($row->access?$row->_group_name:JText::_('FWMG_PUBLIC')); ?></span>
                    </div>
                    
                    <div class="">
                        <div class="small"><i class="fal fa-image text-muted mr-1"></i> <?php echo esc_html($row->_sys_filename); ?> </div>
                        <div class="small">
                        </div>
<?php
        fwgHelper::triggerEvent('onshowFileListExtraFields', array('com_fwgallery', $row));
?>
                    </div>
<?php
		if ($this->current_user->authorise('core.edit', 'com_fwgallery')) {
?>
                    <div class="mt-1">
                        <div class="input-group input-group-sm">
                            <input class="form-control" style="max-width:none;text-align:left;" type="text" name="alt_texts[<?php echo (int)$row->id; ?>]" value="<?php echo esc_attr($row->_alt); ?>">
                            <div class="input-group-append">
                                <span class="input-group-text small"><?php echo esc_attr(JText::_('FWMG_ALT_TEXT')); ?></span>
                            </div>
                        </div>
                    </div>
<?php
		}
?>
                </td>
                <td><?php echo fwgHelper::stripTags($row->descr, 200, $show_counter=true); ?></td>
                <td><?php echo esc_html($row->_category_name); ?></td>
<?php
		fwgHelper::triggerEvent('onshowFileListExtraColumns', array('com_fwgallery', $this, $num, $row));
?>
                <td>
<?php
        if ($this->order == 'ordering') {
?>
                    <?php echo JHTMLfwView::orderingListLinks(array(
                        'num' => $num,
                        'value' => $row->ordering,
                        'display_up' => ($num > 0),
                        'display_down' => (($num + 1) < count($this->list))
                    )); ?>
<?php
        } else echo (int)$row->ordering;
?>
                </td>
                <td>
<?php
        if ($row->hits) {
?>
					<div><i class="fa-fw fal fa-eye"></i> <?php echo (int)$row->hits; ?></div><?php
        }
        if ($row->downloads) {
?>
					<div><i class="fa-fw fal fa-download"></i> <?php echo (int)$row->downloads; ?></div><?php
        }
		fwgHelper::triggerEvent('onshowFileListHitsExtraFields', array('com_fwgallery', $row, $this->params));
?>
                </td>
            </tr>
<?php
        } else {
            fwgHelper::triggerEvent('onshowFileListRow', array('com_fwgallery.'.$row->type, $this, $num, $row));
        }
        $num++;
    }
} else {
?>
            <tr>
                <td colspan="8">
<?php
    echo JText::_($this->search?'FWMG_NO_FILES_FOUND':'FWMG_NO_FILES');
?>
                </td>
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
    <input type="hidden" name="type" value="image" />
    <input type="hidden" name="ordering" value="<?php echo esc_attr($this->order); ?>" />
    <input type="hidden" name="boxchecked" value="" />
</form>
<script>
document.addEventListener('DOMContentLoaded', function() {
    (function($) {
    Joomla.submitbutton = function(pressbutton) {
        if (pressbutton) {
            if (pressbutton == 'import') {
                $('#fwmg-batch-upload').modal('show');
            } else if (pressbutton == 'batch') {
                $('#fwmg-batch-operations').modal('show');
            } else {
                if (pressbutton == 'saveall') {
                    $('.fwmg-admin-images input[name="cid[]"]').prop('checked', true);
                }
                document.adminForm.task.value=pressbutton;
                document.adminForm.submit();
            }
        }
    }
    $('input[name="tab"]').change(function() {
        with(this.form) {
            limitstart.value = 0;
            submit();
        }
    });
<?php
if (count($this->types) > 2) {
?>
    var $btn = $('#fwmg-add-button').attr('onclick', '');
    $btn.wrap('<div class="d-inline-block" style="position:relative;"></div>');
    var $dropdown = $('<div class="dropdown-menu"></div>');
    $btn.after($dropdown);
    $btn.attr('data-toggle', 'dropdown').attr('data-bs-toggle', 'dropdown').addClass('dropdown-toggle');
<?php
    for ($i = 1; $i < count($this->types); $i++) {
        $value = $this->types[$i]['value'];
?>
    var $row = '<button type="button" class="btn btn-green dropdown-item type-<?php echo esc_js($value); ?>" style="display:block;" onclick="document.adminForm.type.value=\'<?php echo esc_js($value); ?>\';Joomla.submitbutton(\'add\');"><i class="fal fa-image"></i> <?php echo JText::_('FWMG_ADD_'.$value, true); ?></button>';
    $dropdown.append($row);
<?php
    }
?>
<?php
}
?>
    })(jQuery);
});
</script>
<?php
echo JLayoutHelper::render('utilites.batchoperations', array(
	'view'=>$this
), JPATH_COMPONENT);
echo JLayoutHelper::render('utilites.batchupload', array(
	'allusers'=>$this->allusers,
	'current_user'=>$this->current_user,
	'category'=>$this->category,
	'reload'=>true
), JPATH_COMPONENT);
echo JLayoutHelper::render('utilites.quickcategories', array(), JPATH_COMPONENT);
echo JLayoutHelper::render('common.menu_end', array(), JPATH_COMPONENT);
