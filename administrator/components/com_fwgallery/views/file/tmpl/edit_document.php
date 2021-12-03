<?php
/**
 * FW Gallery 6.7.2
 * @copyright (C) 2020 Fastw3b
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.fastw3b.net/ Official website
 **/

defined('_JEXEC') or die('Restricted access');

JToolBarHelper :: title(JText::_('FWMG_FWGALLERY'));

echo JLayoutHelper::render('common.menu_begin', array(
    'view' => $this,
    'title' => JText::_('FWMG_VIDEOS'),
    'title_hint' => JText::_('FWMG_VIDEOS_HINT')
), JPATH_COMPONENT);

$plugin_installed = fwgHelper::pluginInstalled('document', 'fwgallerytype');
$plugin_enabled = fwgHelper::pluginEnabled('document', 'fwgallerytype');

if (!$plugin_installed or !$plugin_enabled) {
?>
<div class="alert alert-danger">
<?php
    if (!$plugin_installed) {
        echo JText::_('FWMG_DOCUMENTS_PLUGIN_NOT_INSTALLED');
    } else {
        echo JText::_('FWMG_DOCUMENTS_PLUGIN_NOT_ENABLED');
    }
?>
</div>
<?php
} else {
    echo JLayoutHelper::render('admin.edit', array('view'=>$this), JPATH_SITE.'/plugins/fwgallerytype/document/layouts');
}

echo JLayoutHelper::render('common.menu_end', array(), JPATH_COMPONENT);
