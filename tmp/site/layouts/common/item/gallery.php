<?php
/**
 * FW Gallery 6.7.2
 * @copyright (C) 2020 Fastw3b
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.fastw3b.net/ Official website
 **/

defined('_JEXEC') or die('Restricted access');

$view = $displayData['view'];

if ($view->files) {
?>
<div class="fwmg-file-gallery">
    <div class="fwmg-file-gallery-btn-left">
        <button type="button" class="btn"><i class="fal fa-long-arrow-left"></i></button>
    </div>
    <div class="fwmg-file-gallery-thumbs-wrapper" data-start="<?php echo (int)$view->img_limitstart; ?>" data-end="<?php echo (int)$view->img_limitstart + $view->img_limit; ?>" data-limit="<?php echo (int)$view->img_limit; ?>" data-qty="<?php echo (int)$view->img_position->total_qty; ?>">
        <ul class="fwmg-file-gallery-thumbs">
			<?php echo fwgHelper::loadTemplate('item.gallery_items', $displayData); ?>
        </ul>
    </div>
    <div class="fwmg-file-gallery-btn-right text-right">
        <button type="button" class="btn"><i class="fal fa-long-arrow-right"></i></button>
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function() {
	(function($) {
        $('.fwmg-file-gallery-thumbs li.active').click();
    })(jQuery);
});
</script>
<?php
}
