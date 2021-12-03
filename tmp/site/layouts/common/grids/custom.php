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
<div class="fwmg-grid-container">
<?php
}
$num = 0;
foreach ($list as $sc) {
    echo fwgHelper::loadTemplate('listing.'.$type.'_custom', array_merge($displayData, array(
        'num' => $num,
        'row' => $sc
    )));
    $num++;
}
if (empty($view->no_grid_wrap)) {
?>
</div>
<?php
}