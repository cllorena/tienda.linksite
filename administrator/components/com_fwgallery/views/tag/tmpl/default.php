<?php
/**
 * FW Gallery 6.7.2
 * @copyright (C) 2020 Fastw3b
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.fastw3b.net/ Official website
 **/

defined('_JEXEC') or die('Restricted access');

JToolBarHelper::title(JText::_('FWMG_ADMIN_TAGS_TOOLBAR_TITLE'), ' fal fa-tags');

echo JLayoutHelper::render('common.menu_begin', array(
    'view' => $this,
    'title' => JText::_('FWMG_DOC_ADMIN_TAGS'),
    'title_hint' => JText::_('FWMG_DOC_ADMIN_TAGS_HINT')
), JPATH_COMPONENT);

if (!$this->plugin_installed or !$this->plugin_enabled) {
?>
<div class="alert alert-danger">
<?php
    if (!$this->plugin_installed) {
        echo JText::_('FWMG_TAGS_PLUGIN_NOT_INSTALLED');
    } else {
        echo JText::_('FWMG_TAGS_PLUGIN_NOT_ENABLED');
    }
?>
</div>
<?php
} else {
    echo JLayoutHelper::render('admin.tag.list', array('view'=>$this), JPATH_SITE.'/plugins/fwgallery/tag/layouts');
}
echo JLayoutHelper::render('utilites.batchupload', array(
	'allusers'=>$this->users,
	'current_user'=>$this->current_user,
	'category'=>'',
	'reload'=>false
), JPATH_COMPONENT);
echo JLayoutHelper::render('utilites.quickcategories', array(), JPATH_COMPONENT);
echo JLayoutHelper::render('common.menu_end', array(), JPATH_COMPONENT);
