<?php
/**
 * FW Gallery 6.7.2
 * @copyright (C) 2020 Fastw3b
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.fastw3b.net/ Official website
 **/

defined('_JEXEC') or die('Restricted access');

$view = $displayData['view'];

$type = $displayData['return'];
$cols = $displayData['cols'];
$list = $displayData['list'];

if (empty($view->no_grid_wrap)) {
?>
<div class="fwmg-grid-container fwmg-grid-columns-<?php echo (int)$cols; ?>">
<?php
}
?>
	<div class="fwmg-grid-column">
<?php
$num = 0;
$cols_displayed = 1;
$qty_per_column = max(1, round(count($list) / $cols));
$num -= max(0, count($list) - $qty_per_column * $cols);

foreach ($list as $sc) {
	if ($num > 0 and $num % $qty_per_column == 0) {
?>
	</div>
	<div class="fwmg-grid-column">
<?php
		$cols_displayed++;
	}
    echo fwgHelper::loadTemplate('listing.'.$type, array_merge($displayData, array(
        'num' => $num,
        'row' => $sc
    )));
    $num++;
}
if ($cols_displayed < $cols) {
	for ($k = $cols_displayed; $k < $cols; $k++) {
?>
	</div>
	<div class="fwmg-grid-column">
<?php
	}
}
?>
	</div>
<?php
if (empty($view->no_grid_wrap)) {
?>
</div>
<?php
}