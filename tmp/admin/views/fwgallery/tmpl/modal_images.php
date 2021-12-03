<?php
/**
 * FW Gallery 6.7.2
 * @copyright (C) 2020 Fastw3b
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.fastw3b.net/ Official website
 **/

defined( '_JEXEC' ) or die( 'Restricted access' );

$input = JFactory::getApplication()->input;
?>
<form action="<?php echo fwgHelper::checkLink(JRoute::_('index.php?option=com_fwgallery&view=fwgallery&layout=modal&sublayout=images&tmpl=component&function=' . $this->function . '&' . JSession::getFormToken() . '=1')); ?>" method="post" name="adminForm" id="adminForm" class="form-inline">
    <div class="row-fluid">
        <div class="btn-toolbar">
            <div class="filter-search btn-group pull-left">
                <label class="element-invisible" for="search"><?php echo JText::_('FWMG_SEARCH_BY_IMAGE_NAME') ?></label>
                <input id="search" type="text" name="search" placeholder="<?php echo esc_attr(JText::_('FWMG_SEARCH_BY_IMAGE_NAME')); ?>" value="<?php echo esc_attr($input->getString('search')); ?>" />
            </div>
            <div class="btn-group pull-left hidden-phone">
                <button type="submit" class="btn" onclick="with(this.form){task.value='';category_id.value=0;if(this.form.limitstart){limitstart.value=0;}submit();}" title="<?php echo esc_attr(JText::_('FWMG_SEARCH')); ?>"><i class="icon-search"></i></button>
                <button type="button" class="btn" onclick="with(this.form){search.value='';task.value='';category_id.value='';if(this.form.limitstart){limitstart.value=0;}submit();}" title="<?php echo esc_attr(JText::_('FWMG_CLEAR')); ?>"><i class="icon-remove"></i></button>
            </div>
            <div class="btn-group pull-right hidden-phone">
                <a class="btn btn-primary" href="<?php echo fwgHelper::checkLink(JRoute::_('index.php?option=com_fwgallery&view=fwgallery&layout=modal_image_add&tmpl=component&function=' . $this->function.($this->category_id?('&category_id='.$this->category_id):''))); ?>"><?php echo JText::_('FWMG_ADD_NEW_IMAGE'); ?></a>
            </div>
            <div class="btn-group pull-right hidden-phone">
                <?php echo JHTML::_('fwsgCategory.getCategories', 'category_id', $this->category_id, 'onchange="with(this.form){if(this.form.limitstart){limitstart.value=0;}submit();}"', false, JText::_('FWMG_SELECT_GALLERY')); ?>
            </div>
        </div>
    </div>
    <table class="table table-striped">
        <thead>
            <tr>
                <th class="hidden-phone"><?php echo JText::_('FWMG_ID'); ?></th>
                <th><?php echo JText::_('FWMG_IMAGE_PREVIEW'); ?></th>
                <th class="hidden-phone"><?php echo JText::_('FWMG_DATE'); ?></th>
                <th class="hidden-phone"><?php echo JText::_('FWMG_NAME'); ?></th>
                <th><?php echo JText::_('FWMG_PUBLISHED'); ?></th>
            </tr>
        </thead>
        <tbody>
<?php
if ($this->files) {
    $num = 0;
    foreach ($this->files as $file) {
?>
            <tr>
                <td class="center hidden-phone"><?php echo (int)$file->id; ?></td>
                <td>
                    <div class="row center">
                        <a href="javascript:" onclick="if (window.parent) window.parent.<?php echo esc_attr($this->function); ?>('<?php echo (int)$file->id; ?>');">
							<img src="<?php echo JURI::root(true); ?>/index.php?option=com_fwgallery&view=item&layout=img&format=raw&id=<?php echo (int)$file->id; ?>&w=80&h=60" />
                        </a>
                    </div>
                </td>
                <td class="center hidden-phone">
                    <?php echo substr($file->created, 0, 10); ?>
                </td>
                <td>
                    <a href="javascript:" onclick="if (window.parent) window.parent.<?php echo esc_attr($this->function); ?>('<?php echo (int)$file->id; ?>');">
                        <?php echo esc_html($file->name); ?>
                    </a>
                </td>
                <td style="text-align:center;"><i class="fa fa-<?php if ($file->published) { ?>check<?php } else { ?>times<?php } ?>"></i></td>
            </tr>
<?php
        $num++;
    }
} else {
?>
            <tr>
                <td colspan="5"><?php echo JText::_('FWMG_NO_IMAGES'); ?></td>
            </tr>
<?php
}
?>
        </tbody>
    </table>
    <?php echo $this->pagination->getListFooter(); ?>
    <input type="hidden" name="task" value="" />
    <input type="hidden" name="boxchecked" value="0" />
</form>
