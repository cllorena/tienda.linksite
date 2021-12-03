<?php
/**
 * FW Gallery 6.7.2
 * @copyright (C) 2020 Fastw3b
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.fastw3b.net/ Official website
 **/

defined('_JEXEC') or die('Restricted access');

JToolBarHelper::title(JText::_('FWMG_ADMIN_FILEEDIT_TOOLBAR_TITLE'), ' fal fa-music');


echo JLayoutHelper::render('common.menu_begin', array(
    'view' => $this,
    'title' => $this->object->id?(JText::sprintf('FWMG_ADMIN_FILEEDIT_EDIT', $this->object->name)):JText::_('FWMG_ADMIN_FILEEDIT_NEW'),
    'title_hint' => JText::_('FWMG_DOC_ADMIN_FILEEDIT_HINT')
), JPATH_COMPONENT);

$plugin_installed = fwgHelper::pluginInstalled('audio', 'fwgallerytype');
$plugin_enabled = fwgHelper::pluginEnabled('audio', 'fwgallerytype');

if (!$plugin_installed or !$plugin_enabled) {
?>
<div class="alert alert-danger">
<?php
    if (!$plugin_installed) {
        echo JText::_('FWMG_AUDIO_PLUGIN_NOT_INSTALLED');
    } else {
        echo JText::_('FWMG_AUDIO_PLUGIN_NOT_ENABLED');
    }
?>
</div>
<?php
} else {
    echo JLayoutHelper::render('admin.audio.edit', array('view'=>$this), JPATH_SITE.'/plugins/fwgallerytype/audio/layouts');
}

echo JLayoutHelper::render('common.menu_end', array(), JPATH_COMPONENT);
