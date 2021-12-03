<?php
/**
 * FW Gallery 6.7.2
 * @copyright (C) 2020 Fastw3b
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.fastw3b.net/ Official website
 **/

defined('_JEXEC') or die('Restricted access');

$view = $displayData['view'];
foreach ($view->files as $row) {
?>
            <li data-id="<?php echo (int)$row->id; ?>"<?php if ($view->item->id == $row->id) { ?> class="active"<?php } ?>>
                <img src="<?php echo fwgHelper::route('index.php?option=com_fwgallery&view=item&layout=img&format=raw&w=96&h=96&id='.$row->id.':'.JFilterOutput::stringURLSafe($row->name)); ?>" />
            </li>
<?php
}
