<?php
/**
 * FW Gallery 6.7.2
 * @copyright (C) 2020 Fastw3b
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.fastw3b.net/ Official website
 **/

defined('_JEXEC') or die('Restricted access');

JToolBarHelper::title(JText::_('FWMG_ADMIN_CATEGORIES_TOOLBAR_TITLE'), ' fal fa-folders');

if ($this->current_user->authorise('core.create', 'com_fwgallery')) { 
	fwgButtonsHelper::addNew('add', 'FWMG_DOC_ADMIN_CATEGORIES_TOOLBAR_BTN_NEW');
    fwgButtonsHelper::custom('fal fa-folder-tree', 'purple', 'FWMG_DOC_ADMIN_CATEGORIES_TOOLBAR_BTN_QUICKCATEGORIES', 'quick', false);
}
if ($this->current_user->authorise('core.edit', 'com_fwgallery')) {
	fwgButtonsHelper::editList('edit', 'FWMG_DOC_ADMIN_CATEGORIES_TOOLBAR_BTN_EDIT');
}
if ($this->current_user->authorise('core.delete', 'com_fwgallery')) {
	fwgButtonsHelper::deleteList(JText::_('FWMG_ADMIN_CATEGORIES_TOOLBAR_BTN_DELETE_MSG'), 'remove', 'FWMG_DOC_ADMIN_CATEGORIES_TOOLBAR_BTN_DELETE');
}
if ($this->current_user->authorise('core.edit', 'com_fwgallery')) {
	fwgButtonsHelper::publish('publish', 'FWMG_DOC_ADMIN_CATEGORIES_TOOLBAR_BTN_PUBLISH');
	fwgButtonsHelper::unpublish('unpublish', 'FWMG_DOC_ADMIN_CATEGORIES_TOOLBAR_BTN_UNPUBLISH');
}

echo JLayoutHelper::render('common.menu_begin', array(
    'view' => $this,
    'title' => JText::_('FWMG_DOC_ADMIN_CATEGORIES'),
    'title_hint' => JText::_('FWMG_DOC_ADMIN_CATEGORIES_HINT')
), JPATH_COMPONENT);
?>
<form action="index.php?option=com_fwgallery&amp;view=category" id="adminForm" name="adminForm"
    method="post">
    <div class="fwa-filter-bar fwa-filter-bar-float clearfix">
        <div class="float-left" style="width:30%;">
            <div class="input-group">
                <input class="form-control"
                    placeholder="<?php echo JText::_('FWMG_DOC_ADMIN_CATEGORIES_FILTER_SEARCH'); ?>"
                    name="search" value="<?php echo esc_attr($this->search); ?>" type="text"
                    data-container="body" data-trigger="hover" data-toggle="popover" data-bs-toggle="popover" data-placement="top"
                    data-content="<?php echo JText::_('FWMG_DOC_ADMIN_CATEGORIES_FILTER_SEARCH_HINT'); ?>" />
                <span class="input-group-btn">
                    <button class="btn" type="button"
                        onclick="with(this.form){if(this.form.limitstart){limitstart.value=0;}search.value='';submit();}"><i
                            class="fal fa-times"></i></button>
                </span>
                <span class="input-group-btn">
                    <button class="btn btn-primary" type="submit"><i class="fal fa-search"></i></button>
                </span>
            </div>
        </div>
        <div class="float-left ml-3">
            <div class="input-group" data-container="body" data-trigger="hover" data-toggle="popover" data-bs-toggle="popover"
                data-placement="top"
                data-content="<?php echo JText::_('FWMG_DOC_ADMIN_CATEGORIES_FILTER_PARENT_CATEGORY_HINT'); ?>">
                <?php echo JHTML::_('fwsgCategory.parent', (object)array('category'=>$this->category), 'category', 'class="form-control form-control-sm select-choices" onchange="with(this.form){if(this.form.limitstart){limitstart.value=0;}submit();}"', 'FWMG_DOC_ADMIN_CATEGORIES_FILTER_PARENT_CATEGORY'); ?>
            </div>
        </div>
        <div class="float-left ml-3">
            <div class="input-group" data-container="body" data-trigger="hover" data-toggle="popover" data-bs-toggle="popover"
                data-placement="top"
                data-content="<?php echo JText::_('FWMG_DOC_ADMIN_CATEGORIES_FILTER_OWNER_HINT'); ?>">
                <?php echo JHTML::_('select.genericlist', array_merge(array(
                    JHTML::_('select.option', '', JText::_('FWMG_DOC_ADMIN_CATEGORIES_FILTER_OWNER'), 'id', 'name')
                ), $this->users), 'user', 'class="form-control form-control-sm select-choices" onchange="with(this.form){if(this.form.limitstart){limitstart.value=0;}submit();}"', 'id', 'name', $this->user); ?>
            </div>
        </div>
    </div>
    <table class="table table-striped fwmg-galleries-table">
        <thead>
            <tr>
                <th><input name="toggle" value="" onclick="Joomla.checkAll(this);" type="checkbox"></th>
                <th data-trigger="hover" data-toggle="popover" data-bs-toggle="popover" data-placement="top"
                    data-content="<?php echo JText::_('FWMG_DOC_ADMIN_CATEGORIES_COLUMN_ID_HINT'); ?>">
                    <?php echo JText::_('FWMG_DOC_ADMIN_CATEGORIES_COLUMN_ID'); ?></th>
                <th data-trigger="hover" data-toggle="popover" data-bs-toggle="popover" data-placement="top"
                    data-content="<?php echo JText::_('FWMG_DOC_ADMIN_CATEGORIES_COLUMN_COVER_HINT'); ?>">
                    <?php echo JText::_('FWMG_DOC_ADMIN_CATEGORIES_COLUMN_COVER'); ?></th>
                <th data-trigger="hover" data-toggle="popover" data-bs-toggle="popover" data-placement="top" data-content="<?php echo JText::_('FWMG_DOC_ADMIN_CATEGORIES_COLUMN_NAMEINFO_HINT'); ?>">
                    <a href="index.php?option=com_fwgallery&view=category<?php echo str_replace(array('&amp;ordering=created', '&amp;ordering=name', '&amp;ordering=ordering'), '', $this->extra_link).'&amp;ordering=created'; ?>">
                        <?php echo JText::_('FWMG_DOC_ADMIN_CATEGORIES_COLUMN_NAMEINFO'); ?>
                    </a>
                </th>
                <th width="40%" data-trigger="hover" data-toggle="popover" data-bs-toggle="popover" data-placement="top"
                    data-content="<?php echo JText::_('FWMG_DOC_ADMIN_CATEGORIES_COLUMN_DESCRIPTION_HINT'); ?>">
                    <?php echo JText::_('FWMG_DOC_ADMIN_CATEGORIES_COLUMN_DESCRIPTION'); ?></th>
                <th data-trigger="hover" data-toggle="popover" data-bs-toggle="popover" data-placement="top"
                    data-content="<?php echo JText::_('FWMG_DOC_ADMIN_CATEGORIES_COLUMN_ACCESS_HINT'); ?>">
                    <?php echo JText::_('FWMG_DOC_ADMIN_CATEGORIES_COLUMN_ACCESS'); ?></th>
                <th data-trigger="hover" data-toggle="popover" data-bs-toggle="popover" data-placement="top"
                    data-content="<?php echo JText::_('FWMG_DOC_ADMIN_CATEGORIES_COLUMN_FILES_HINT'); ?>">
                    <?php echo JText::_('FWMG_DOC_ADMIN_CATEGORIES_COLUMN_FILES'); ?></th>
                <th data-trigger="hover" data-toggle="popover" data-bs-toggle="popover" data-placement="top" data-content="<?php echo JText::_('FWMG_DOC_ADMIN_CATEGORIES_COLUMN_ORDER_HINT'); ?>">
                    <a href="index.php?option=com_fwgallery&view=category<?php echo str_replace(array('&amp;ordering=created', '&amp;ordering=name', '&amp;ordering=ordering'), '', $this->extra_link).'&amp;ordering=ordering'; ?>">
                        <?php echo JText::_('FWMG_DOC_ADMIN_CATEGORIES_COLUMN_ORDER'); ?>
                    </a>
<?php
if ($this->current_user->authorise('core.edit', 'com_fwgallery') and $this->order == 'ordering') {
?>
                    <a class="btn" href="javascript:" onclick="javascript:saveorder(<?php echo (int)count($this->list)-1; ?>, 'saveorder')">
                        <i class="fa fa-save"></i>
                    </a>
<?php
}
?>
                </th>
                <th data-trigger="hover" data-toggle="popover" data-bs-toggle="popover" data-placement="top"
                    data-content="<?php echo JText::_('FWMG_DOC_ADMIN_CATEGORIES_COLUMN_PUBLISHED_HINT'); ?>">
                    <?php echo JText::_('FWMG_DOC_ADMIN_CATEGORIES_COLUMN_PUBLISHED'); ?></th>
            </tr>
        </thead>
        <tbody>
<?php
if ($this->list) {
    $num = 0;
    foreach ($this->list as $row) {
?>
            <tr>
                <td><?php echo JHTML::_('grid.id', $num, $row->id ); ?></td>
                <td><?php echo (int)$row->id; ?></td>
                <td><img class="img-thumbnail"
                        src="<?php echo JURI::root(true); ?>/index.php?option=com_fwgallery&amp;view=fwgallery&amp;layout=img&amp;format=raw&amp;w=80&amp;h=60&amp;id=<?php echo (int)$row->id; ?>">
                </td>
                <td>
<?php
		if ($this->current_user->authorise('core.edit', 'com_fwgallery')) {
?>
                <a href="index.php?option=com_fwgallery&amp;view=category&amp;task=edit&amp;cid[]=<?php echo esc_attr($row->id.$this->extra_link); ?>"><?php echo esc_html($row->treename); ?></a>
<?php
		} else {
            echo esc_html($row->treename);
		}
?>

                    <div><small><?php echo JText::_('FWMG_OWNER'); ?>:
                        <?php echo esc_html($row->_user_name); ?></small></div>
                    <div><small><?php echo JText::_('FWMG_CREATED'); ?>:
                        <?php echo JHTML::date($row->created, 'd M Y H:i'); ?></small></div>
<?php
		fwgHelper::triggerEvent('onshowCategoryListExtraFields', array('com_fwgallery', $row));
?>
                </td>
                <td><?php echo fwgHelper::stripTags($row->descr, 200, $show_counter=true); ?></td>
                <td><?php echo esc_html($row->_group_name); ?></td>
                <td>
                    <?php if ($row->_images_qty) : ?>
                    <div><i class="fal fa-image"></i> <?php echo (int)$row->_images_qty; ?></div>
                    <?php endif; ?>
                    <?php echo fwgHelper::escPluginsOutput(implode('', fwgHelper::triggerEvent('ongetCategoryAdminListExtraCounters', array('com_fwgallery', $row)))); ?>
                </td>
                <td>
<?php
        if ($this->order == 'ordering') {
?>
                    <?php echo JHTMLfwView::orderingListLinks(array(
                        'num' => $num,
                        'value' => $row->ordering,
                        'display_up' => fwgHelper::checkPrevCategory($row),
                        'display_down' => fwgHelper::checkNextCategory($row)
                    )); ?>
<?php
        } else echo (int)$row->ordering;
?>
                </td>
                <td>
                    <?php echo JHTMLfwView::booleanListLink(array(
                        'num' => $num,
                        'value' => $row->published,
                        'task_on' => 'publish',
                        'task_off' => 'unpublish',
                        'title_on' => JText::_('FWMG_PUBLISH'),
                        'title_off' => JText::_('FWMG_UNPUBLISH'),
                        'class_on' => 'text-success',
                        'class_off' => 'text-danger'
                    )); ?>
                </td>
            </tr>
<?php
        $num++;
    }
} else {
?>
            <tr>
                <td colspan="9">
<?php
    echo JText::_($this->search?'FWMG_NO_GALLERIES_FOUND':'FWMG_NO_GALLERIES');
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
    <input type="hidden" name="ordering" value="<?php echo esc_attr($this->order); ?>" />
    <input type="hidden" name="boxchecked" value="" />
</form>

<script>
document.addEventListener('DOMContentLoaded', function () {
    (function ($) {
        Joomla.submitbutton = function (pressbutton) {
            if (pressbutton == 'quick') {
                $('#fwmg-quick-categories').modal('show');
                return;
            }
            document.adminForm.task.value = pressbutton;
            document.adminForm.submit();
        }
        $('#toolbar-upload button').addClass('btn-primary');
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
echo JLayoutHelper::render('utilites.quickcategories', array('extra_link' => $this->extra_link), JPATH_COMPONENT);
echo JLayoutHelper::render('common.menu_end', array(), JPATH_COMPONENT);
