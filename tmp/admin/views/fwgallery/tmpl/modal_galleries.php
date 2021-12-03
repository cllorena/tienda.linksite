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
<form action="<?php echo fwgHelper::checkLink(JRoute::_('index.php?option=com_fwgallery&view=fwgallery&layout=modal&sublayout=galleries&tmpl=component&function='.$this->function)); ?>" method="post" name="adminForm" id="adminForm" class="form-inline">
    <div class="row-fluid">
        <div class="btn-toolbar">
            <div class="filter-search btn-group pull-left">
                <label class="element-invisible" for="search"><?php echo JText::_('FWMG_SEARCH_BY_GALLERY_NAME') ?></label>
                <input id="search" type="text" name="search" placeholder="<?php echo esc_attr(JText::_('FWMG_SEARCH_BY_GALLERY_NAME')); ?>" value="<?php echo esc_attr($input->getString('search')); ?>" />
            </div>
            <div class="btn-group pull-left hidden-phone">
                <button type="submit" class="btn" onclick="with(this.form){task.value='';category_id.value=0;if(this.form.limitstart){limitstart.value=0;}submit();}" title="<?php echo esc_attr(JText::_('FWMG_SEARCH')); ?>"><i class="icon-search"></i></button>
                <button type="button" class="btn" onclick="with(this.form){search.value='';task.value='';category_id.value='';if(this.form.limitstart){limitstart.value=0;}submit();}" title="<?php echo esc_attr(JText::_('FWMG_CLEAR')); ?>"><i class="icon-remove"></i></button>
            </div>
            <div class="btn-group pull-right hidden-phone">
                <a class="btn btn-primary" href="<?php echo fwgHelper::checkLink(JRoute::_('index.php?option=com_fwgallery&view=fwgallery&layout=modal_image_add&tmpl=component&function=' . $this->function.($this->category_id?('&category_id='.$this->category_id):''))); ?>"><?php echo JText::_('FWMG_ADD_NEW_IMAGE'); ?></a>
            </div>
            <div class="btn-group pull-right hidden-phone">
                <a class="btn btn-primary" href="<?php echo fwgHelper::checkLink(JRoute::_('index.php?option=com_fwgallery&view=fwgallery&layout=modal_add&tmpl=component&function=' . $this->function)); ?>"><?php echo JText::_('FWMG_ADD_NEW_GALLERY'); ?></a>
            </div>
        </div>
    </div>
    <table class="table table-striped">
        <thead>
            <tr>
                <th class="hidden-phone"><?php echo JText::_('FWMG_ID'); ?></th>
                <th><?php echo JText::_('FWMG_NAME'); ?></th>
                <th><?php echo JText::_('FWMG_VIEW_ACCESS'); ?></th>
                <th class="hidden-phone" style="width:5%;"><?php echo JText::_('FWMG_Images_Qty'); ?></th>
                <th><?php echo JText::_('FWMG_PUBLISHED'); ?></th>
            </tr>
        </thead>
        <tbody>
<?php
if ($this->galleries) {
    foreach ($this->galleries as $gallery) {
?>
            <tr>
                <td class="center hidden-phone"><?php echo (int)$gallery->id; ?></td>
                <td>
                    <a href="javascript:" onclick="if (window.parent) window.parent.<?php echo esc_attr($this->function); ?>('<?php echo (int)$gallery->id; ?>');">
                        <?php echo esc_html($gallery->treename); ?>
                    </a>
                </td>
                <td>
                    <?php echo esc_html($gallery->_group_name?$gallery->_group_name:JText::_('FWMG_PUBLIC')); ?>
                </td>
                <td class="hidden-phone" style="text-align:center;"><?php echo (int)$gallery->_qty; ?></td>
                <td style="text-align:center;"><i class="fa fa-<?php if ($gallery->published) { ?>check<?php } else { ?>times<?php } ?>"></i></td>
            </tr>
<?php
    }
} else {
?>
            <tr>
                <td colspan="5"><?php echo JText::_('FWMG_NO_GALLERIES'); ?></td>
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
