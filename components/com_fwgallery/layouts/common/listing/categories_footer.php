<?php
/**
 * FW Gallery 6.7.2
 * @copyright (C) 2020 Fastw3b
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.fastw3b.net/ Official website
 **/

defined('_JEXEC') or die('Restricted access');

$view = $displayData['view'];
$pagination_type = $view->params->get('gallery_pagination_type', 'standard');
?>
<div class="fwmg-galleries-footer">
    <div class="row">
		<div class="col-md fwmg-galleries-footer-pagination">
<?php
if ($pagination_type and $view->subcategoriesPagination->total > $view->subcategoriesPagination->limit) {
	if ($pagination_type != 'ajax') {
?>
			<?php echo $view->getPaginationLinks($view->subcategoriesPagination, array('showLimitBox'=>false, 'showLimitStart'=>false)); ?>
<?php
	} else {
		echo fwgHelper::loadTemplate('listing.pagination_scripts', $displayData);
?>
			<div class="fwmg-more-block">
				<a href="javascript:" class="ml-2" data-layout="categories" data-target=".fwmg-galleries .fwmg-grid-container:last-child" data-selector=".fwmg-grid-item" data-items-per-page="<?php echo (int)$view->subcategoriesPagination->limit; ?>" data-total="<?php echo (int)$view->subcategoriesPagination->total; ?>" data-order="<?php echo esc_attr($view->subcategories_order); ?>" data-tmpl="<?php echo esc_attr($view->params->get('template', 'common')); ?>"><i class="fa fa-arrow-circle-down"></i> <?php echo JText::_('FWMG_LOAD'); ?> <span></span> <?php echo JText::_('FWMG_MORE'); ?></a>
				<span class="fwmg-gf-counter"><span><?php echo count($view->subcategories); ?></span> <?php echo JText::_('FWMG_OF'); ?> <?php echo (int)$view->subcategoriesPagination->total; ?></span>
				<span class="loading-spinner" style="display:none;"></span>
			</div>
<?php
	}
}
?>
        </div>
<?php
if ($view->params->get('show_gallery_ordering', 0) or $view->params->get('show_gallery_limit', 0)) {
	$opts = fwgHelper::getLimitOptions(
		$view->params->get('gallery_columns', 4),
		$view->params->get('gallery_rows', 4),
		'standard',
		$view->subcategoriesPagination->limit,
		$view->subcategoriesPagination->total
	);
?>
        <div class="col-md fwmg-galleries-filters">
            <form action="<?php echo JURI::getInstance()->toString(); ?>" method="post">
<?php
	if ($view->params->get('show_gallery_ordering', 0)) {
?>
    			<div class="btn-group">
    				<?php echo JHTML::_('select.genericlist', array(
    					JHTML::_('select.option', 'ordering', JText::_('FWMG_ORDERING'), 'id', 'name'),
    					JHTML::_('select.option', 'new', JText::_('FWMG_NEWEST_FIRST'), 'id', 'name'),
    					JHTML::_('select.option', 'old', JText::_('FWMG_OLDEST_FIRST'), 'id', 'name'),
    					JHTML::_('select.option', 'alpha', JText::_('FWMG_ALPHABETICALLY'), 'id', 'name'),
    				), 'subcategories_order', 'class="form-control" onchange="this.form.submit();"', 'id', 'name', $view->subcategories_order); ?>
    			</div>
<?php
	}
	if ($view->params->get('show_gallery_limit', 0) and $opts) {
?>
                <div class="btn-group ml-4">
                    <?php echo JHTML::_('select.genericlist', $opts, 'subcategorieslimit', 'class="form-control" data-toggle="popover" data-bs-toggle="popover" data-title="'.htmlspecialchars(JText::_('FWMG_LIMIT')).'" onchange="this.form.submit();"', 'id', 'name', $view->subcategoriesPagination->limit); ?>
                </div>
<?php
	}
?>
            </form>
		</div>
<?php
}
?>
    </div>
</div>
