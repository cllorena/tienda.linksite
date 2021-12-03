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

if (!defined('FWS_MASONRY_LOADED')) {
    define('FWS_MASONRY_LOADED', true);
    JHTML::_('jquery.framework');
    JHTML::script('components/com_fwgallery/assets/js/macy.js');
?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var gmi = Macy({
        container: '.fwmg-masonry',
        trueOrder: false,
        waitForImages: false,
        margin: 24,
        columns: <?php echo (int)$cols; ?>,
        breakAt: {
            1200: <?php echo (int)$cols; ?>,
            940: 3,
            520: 2,
            400: 1
        }
    });
});
</script>
<?php
}
if (empty($view->no_grid_wrap)) {
?>
<div class="fwmg-grid-container fwmg-masonry fwmg-grid-columns-<?php echo (int)$cols; ?>">
<?php
}
$num = 0;
foreach ($list as $sc) {
    echo fwgHelper::loadTemplate('listing.'.$type, array_merge($displayData, array(
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