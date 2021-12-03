<?php
/**
 * @package     Joomla.Site
 * @subpackage  Layout
 *
 * @copyright   Copyright (C) 2005 - 2019 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('JPATH_BASE') or die;

$list = $displayData['list'];
$pages = $list['pages'];

$options = new JRegistry($displayData['options']);

$showLimitBox   = $options->get('showLimitBox', true);
$showPagesLinks = $options->get('showPagesLinks', true);
$showLimitStart = $options->get('showLimitStart', true);

// Calculate to display range of pages
$currentPage = 1;
$range = 1;
$step = 5;

if (!empty($pages['pages']))
{
	foreach ($pages['pages'] as $k => $page)
	{
		if (!$page['active'])
		{
			$currentPage = $k;
		}
	}
}

if ($currentPage >= $step)
{
	if ($currentPage % $step === 0)
	{
		$range = ceil($currentPage / $step) + 1;
	}
	else
	{
		$range = ceil($currentPage / $step);
	}
}
?>

<?php if ($showLimitBox) : ?>
	<div class="limit pull-right">
		<?php echo JText::_('JGLOBAL_DISPLAY_NUM') . $list['limitfield']; ?>
	</div>
<?php endif; ?>

<?php if ($showPagesLinks && (!empty($pages))) : ?>
	<div class="btn-group" role="group">
		<?php
			echo fwgHelper::loadTemplate('pagination.link', array_merge($displayData, $pages['start']));
			echo fwgHelper::loadTemplate('pagination.link', array_merge($displayData, $pages['previous'])); ?>
		<?php foreach ($pages['pages'] as $k => $page) : ?>

			<?php $output = fwgHelper::loadTemplate('pagination.link', array_merge($displayData, $page)); ?>
			<?php if (in_array($k, range($range * $step - ($step + 1), $range * $step), true)) : ?>
				<?php if (($k % $step === 0 || $k === $range * $step - ($step + 1)) && $k !== $currentPage && $k !== $range * $step - $step) : ?>
					<?php $output = preg_replace('#(<a.*?>).*?(</a>)#', '$1...$2', $output); ?>
				<?php endif; ?>
			<?php endif; ?>

			<?php echo fwgHelper::escPluginsOutput($output); ?>
		<?php endforeach; ?>
		<?php
			echo fwgHelper::loadTemplate('pagination.link', array_merge($displayData, $pages['next']));
			echo fwgHelper::loadTemplate('pagination.link', array_merge($displayData, $pages['end'])); ?>
	</div>
<?php endif; ?>

<?php if ($showLimitStart) : ?>
	<input type="hidden" name="<?php echo esc_attr($list['prefix']); ?>limitstart" value="<?php echo (int)$list['limitstart']; ?>" />
<?php endif; ?>
