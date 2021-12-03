<?php
/**
 * FW Gallery 6.7.2
 * @copyright (C) 2020 Fastw3b
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.fastw3b.net/ Official website
 **/

defined('_JEXEC') or die('Restricted access');

$view = $displayData['view'];

$cols = $view->params->get('gallery_columns', 4);
$rows = $view->params->get('gallery_rows', 4);

$actual_rows = is_array($view->subcategories)?ceil(count($view->subcategories)/$cols):0;
if ($actual_rows < $rows) $rows = $actual_rows;

?>
<style type="text/css">
#fwgallery .fwmg-galleries #fwmg-grid.fwmg-grid-standard .fwmg-grid-container {
    grid-template-columns: repeat(<?php echo (int)$cols; ?>, calc((100% - <?php echo (int)$cols - 1; ?>rem)/<?php echo (int)$cols; ?>));
    grid-template-rows: repeat(<?php echo (int)$rows; ?>, auto);
}
</style>
<?php
echo fwgHelper::loadTemplate('grids.standard', array_merge($displayData, array(
	'return' => 'category',
	'list' => $view->subcategories,
	'cols' => $cols
)));
