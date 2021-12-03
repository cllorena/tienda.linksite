<?php
/**
 * FW Gallery 6.7.2
 * @copyright (C) 2020 Fastw3b
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.fastw3b.net/ Official website
 **/

defined('_JEXEC') or die('Restricted access');

$view = $displayData['view'];
$pagination_type = $view->params->get('files_pagination_type', 'standard');
?>
<div class="fwmg-files-footer">
    <div class="row">
        <div class="col-md fwmg-files-pagination">
<?php
if ($pagination_type and $view->filesPagination->total > $view->filesPagination->limit) {
	if ($pagination_type != 'ajax') {
?>
            <?php echo $view->getPaginationLinks($view->filesPagination, array('showLimitBox'=>false, 'showLimitStart'=>false)); ?>
<?php
	} else {
		echo fwgHelper::loadTemplate('listing.pagination_scripts', $displayData);
?>
			<div class="fwmg-more-block">
				<a href="javascript:" class="ml-2" data-layout="files" data-target=".fwmg-files .fwmg-grid-container:last-child" data-selector=".fwmg-grid-item" data-items-per-page="<?php echo (int)$view->filesPagination->limit; ?>" data-total="<?php echo (int)$view->filesPagination->total; ?>" data-order="<?php echo esc_attr($view->files_order); ?>" data-gids="<?php echo esc_attr(implode(',', (array)$view->params->get('gids'))); ?>" data-tmpl="<?php echo esc_attr($view->params->get('template')); ?>"><i class="fa fa-arrow-circle-down"></i> <?php echo JText::_('FWMG_LOAD'); ?> <span></span> <?php echo JText::_('FWMG_MORE'); ?></a>
				<span class="fwmg-gf-counter"><span><?php echo count($view->files); ?></span> <?php echo JText::_('FWMG_OF'); ?> <?php echo (int)$view->filesPagination->total; ?></span>
				<span class="loading-spinner" style="display:none;"></span>
			</div>
<?php
	}
}
?>
        </div>
<?php
if ($view->params->get('show_files_ordering', 0) or $view->params->get('show_files_limit', 0)) {
?>
        <div class="col-md fwmg-files-filters">
            <form action="<?php echo JURI::getInstance()->toString(); ?>" method="post">
<?php
    if ($view->params->get('show_files_ordering', 0)) {
?>
    			<div class="btn-group">
    				<?php echo JHTML::_('select.genericlist', array(
    					JHTML::_('select.option', 'ordering', JText::_('FWMG_ORDERING'), 'id', 'name'),
    					JHTML::_('select.option', 'new', JText::_('FWMG_NEWEST_FIRST'), 'id', 'name'),
    					JHTML::_('select.option', 'old', JText::_('FWMG_OLDEST_FIRST'), 'id', 'name'),
    					JHTML::_('select.option', 'alpha', JText::_('FWMG_ALPHABETICALLY'), 'id', 'name'),
    				), 'files_order', 'class="form-control" onchange="this.form.submit();"', 'id', 'name', $view->files_order); ?>
    			</div>
<?php
    }
    $opts = fwgHelper::getLimitOptions(
    	$view->params->get('files_columns', 4),
    	$view->params->get('files_rows', 4),
    	$view->params->get('files_grid', 'standard'),
    	$view->filesPagination->limit,
    	$view->filesPagination->total
    );
    if ($view->params->get('show_files_limit', 0) and $opts) {
?>
                <div class="btn-group ml-4">
                    <?php echo JHTML::_('select.genericlist', $opts, 'fileslimit', 'class="form-control" data-toggle="popover" data-bs-toggle="popover" data-title="'.htmlspecialchars(JText::_('FWMG_LIMIT')).'" onchange="this.form.submit();"', 'id', 'name', $view->filesPagination->limit); ?>
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
