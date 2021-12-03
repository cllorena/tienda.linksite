<?php
/**
 * FW Gallery 6.7.2
 * @copyright (C) 2020 Fastw3b
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.fastw3b.net/ Official website
 **/

defined('_JEXEC') or die('Restricted access');

JToolBarHelper::title(JText::_('FWMG_ADMIN_ADDONS_TOOLBAR_TITLE'), ' fal fa-puzzle-piece');

JHTML::stylesheet('https://fastw3b.net/images/fwsales/css/addons.css');
echo JLayoutHelper::render('common.menu_begin', array(
    'view' => $this,
    'title' => JText::_('FWMG_DOC_ADMIN_ADDONS'),
    'title_hint' => JText::_('FWMG_DOC_ADMIN_ADDONS_HINT')
), JPATH_COMPONENT);
?>
<form action="index.php?option=com_fwgallery&amp;view=addon" id="adminForm" name="adminForm" method="post">
	<div class="fwa-filter-bar fwa-filter-bar-addons row no-gutters">
		<div class="col-md-8 col-lg-9">
            <div class="d-inline-block" data-container="body" data-trigger="hover" data-toggle="popover" data-bs-toggle="popover" data-placement="top" data-content="<?php echo JText::_('FWMG_DOC_ADMIN_ADDONS_FILTER_STATUS_HINT'); ?>">
				<?php echo JHTMLfwView::radioGroup('status', 'all', array(
					'wrapper_class' => 'mr-2',
					'buttons' => array(array(
						'active_class' => 'btn-success',
						'title' => JText::_('FWMG_DOC_ADMIN_ADDONS_FILTER_STATUS_ALL'),
						'value' => 'all'
					), array(
						'active_class' => 'btn-success',
						'title' => JText::_('FWMG_DOC_ADMIN_ADDONS_FILTER_STATUS_INSTALLED'),
						'value' => 'status-installed'
					), array(
						'active_class' => 'btn-success',
						'title' => JText::_('FWMG_DOC_ADMIN_ADDONS_FILTER_STATUS_PURCHASED'),
						'value' => 'status-owned'
					), array(
						'active_class' => 'btn-success',
						'title' => JText::_('FWMG_DOC_ADMIN_ADDONS_FILTER_STATUS_AVAILABLE'),
						'value' => 'status-unowned'
					))
				)); ?>
            </div>
		</div>
		<div class="col-md-4 col-lg-3 text-center" style="display:none;">
			<i class="fal fa-shopping-cart"></i>
			<span class="fw-cart-total">$0 USD</span>
			<span class="fw-cart-items">(0 items)</span>
			<a class="btn btn-success ml-2" href="<?php echo FWMG_UPDATE_SERVER; ?>?access_code=<?php echo urlencode($this->params->get('update_code')) ?>#cart" target="_blank"><?php echo JText::_('FWMG_CHECKOUT'); ?></a>
			<button type="button" class="btn btn-danger ml-2" id="fwmg-clear-cart"><?php echo JText::_('FWMG_CLEAR_CART'); ?></button>
		</div>
	</div>
<script>
document.addEventListener('DOMContentLoaded', function() {
	(function($) {
	$.ajax({
		type: 'post',
		dataType: 'json',
		data: {
			format: 'json',
			view: 'addon',
			layout: 'get_cart'
		}
	}).done(function(data) {
		var $total = $('.fw-cart-total').html('');
		var $qty = $('.fw-cart-items').html('');
		var $wrp = $total.parent().hide();
		if (data.result && data.result.list) {
			$total.html(data.result.total_formatted);
			$qty.html('('+data.result.list.length+')');
			$wrp.show();
		}
		if (data.msg) {
			fwmg_alert(data.msg);
		}
	});
	$('#fwmg-clear-cart').click(function() {
		$.ajax({
			dataType: 'json',
			data: {
				format: 'json',
				view: 'addon',
				layout: 'clear_cart'
			}
		}).done(function(data) {
			var $total = $('.fw-cart-total').html('');
			var $qty = $('.fw-cart-items').html('');
			var $wrp = $total.parent().hide();
			if (data.result && data.result.list) {
				$total.html(data.result.total_formatted);
				$qty.html('('+data.result.list.length+')');
				$wrp.show();
			}
			if (data.msg) {
				fwmg_alert(data.msg);
			}
		});
	});
	})(jQuery);
});
</script>
    <div class="fwshop-items-wrapper">
		<h2><?php echo JText::_('FWMG_PRODUCT_ADDONS'); ?></h2>
		<div class="row fwa-mb-cardbox fwshop-items">
<?php
$months = $this->params->get('subscription_months', 12);
$days = $this->params->get('subscription_days', 30);
$design_addons_exists = false;
if ($this->list) {
	foreach ($this->list as $row) {
		if ($row->subtype == 'design') {
			$design_addons_exists = true;
		}
		if (in_array(strtoupper($row->update_name), array('GRAND GALLERY', 'GALLERY LIGHT', 'FW GALLERY', 'COM_FWGALLERY')) or $row->subtype == 'service' or $row->subtype == 'design') {
			continue;
		}
        $subtype = str_replace(' ', '-', $row->subtype);
		$classes = array('col-md-6 fwshop-item filtr-item');
		$classes[] = strtolower($subtype);

		$image = $row->image?$row->image:(FWMG_ADMIN_ASSETS_URI.'images/'.$row->update_name.'.jpg');

		$action = $action_button = '';
		$category = array('type-'.$subtype);
		if (!empty($this->deals)) {
			foreach ($this->deals as $deal) {
				if (!empty($deal->_products)) {
					foreach ($deal->_products as $p) {
						if ($p->name == $row->name) {
							$category[] = 'deal-'.$deal->id;
						}
					}
				}
			}
		}
		if ($row->subtype == 'service') {
            $category[] = 'status-service';
			$category[] = 'status-buy';
			$action = 'view';
			$action_button = 'fa-search';
        } elseif ($row->installed) {
			$category[] = 'status-installed';
			if (!empty($row->installable)) {
				$category[] = 'status-owned';
			}
			if ($row->loc_version and $row->rem_version and $row->loc_version != 'x.x.x' and $row->loc_version != $row->rem_version) {
				$category[] = 'status-update';
				$action = 'update';
				$action_button = 'fa-sync-alt';
			} elseif ($row->enabled) {
				$category[] = 'status-enabled';
				$action = 'disable';
				$action_button = 'fa-trash-alt';
			} else {
				$category[] = 'status-disabled';
				$action = 'enable';
				$action_button = 'fa-check';
			}
		} else {
			if (!empty($row->installable)) {
				$category[] = 'status-available';
				$category[] = 'status-owned';
				$action = 'install';
				$action_button = 'fa-download';
			} else {
				$category[] = 'status-buy';
				$category[] = 'status-unowned';
				$action = 'buy';
				$action_button = 'fa-shopping-cart';
			}
		}
        $addon_buy = ($action == 'buy' and in_array($subtype, array('plugin', 'module', 'data-type', 'design')));
?>
			<div class="<?php echo esc_attr(implode(' ', $classes)); ?> type-<?php echo str_replace('datatype', 'data-type', $row->subtype); ?>" data-category="<?php echo esc_attr(implode(',', $category)); ?>" data-ext="<?php echo esc_attr($row->update_name); ?>">
				<div class="row no-gutters">
					<div class="col-4 fwshop-addon-img">
						<a href="<?php if ($row->subtype == 'service') echo esc_attr($row->link); else { ?>javascript:<?php } ?>" <?php if ($row->subtype == 'service') { ?>target="_blank"<?php } else { ?>class="fwa-modal"<?php } ?>>
							<img class="img-thumbnail" src="<?php echo esc_attr($image); ?>" alt="<?php echo esc_attr($row->name); ?>">
						</a>
					</div>
					<div class="col-8 pl-3">
						<div class="fwshop-addon-title">
							<a href="<?php if ($row->subtype == 'service') echo esc_attr($row->link); else { ?>javascript:<?php } ?>" <?php if ($row->subtype == 'service') { ?>target="_blank"<?php } else { ?>class="fwa-modal"<?php } ?>>
								<?php echo esc_attr($row->name); ?>
							</a>
                        </div>
						<div class="fwshop-addon-description">
							<?php echo fwgHelper::stripTags($row->description, 147); ?>
						</div>
						<div class="float-left fwshop-addon-badges">
<?php
			/* addon badges */
			if (isset($row->price)) {
				if ($row->price > 0) {
?>
							<span class="badge badge-paid" data-toggle="tooltip" data-bs-toggle="tooltip" title="" data-original-title="<?php echo JText::_('FWMG_PAID'); ?>"><i class="fad fa-usd-circle"></i></span>
<?php
				} else {
?>
							<span class="badge badge-free" data-toggle="tooltip" data-bs-toggle="tooltip" title="" data-original-title="<?php echo JText::_('FWMG_FREE_EXCL'); ?>"><i class="fad fa-download"></i></span>
<?php
				}
			}
			if (in_array('badge_hot', $row->_badges)) {
?>
							<span class="badge badge-hot" data-toggle="tooltip" data-bs-toggle="tooltip" title="<?php echo JText::_('FWA_HOT') ?>"><i class="fad fa-fire-alt"></i></span>
<?php
			}
			if (in_array('badge_recommended', $row->_badges)) {
?>
							<span class="badge badge-recommended" data-toggle="tooltip" data-bs-toggle="tooltip" title="<?php echo JText::_('FWA_RECOMMENDED') ?>"><i class="fad fa-thumbs-up"></i></span>
<?php
			}
			if (in_array('badge_new', $row->_badges)) {
?>
							<span class="badge badge-new" data-toggle="tooltip" data-bs-toggle="tooltip" title="<?php echo JText::_('FWA_NEW') ?>"><i class="fad fa-star"></i></span>
<?php
			}
			if (in_array('badge_updated', $row->_badges)) {
?>
							<span class="badge badge-updated" data-toggle="tooltip" data-bs-toggle="tooltip" title="<?php echo JText::_('FWA_RECENTLY_UPDATED') ?>"><i class="fad fa-sync"></i></span>
<?php
			}
?>
						</div>
						<div class="float-right fwshop-addon-btn">
							<a href="<?php if ($row->subtype == 'service') echo esc_attr($row->link); else { ?>javascript:<?php } ?>" <?php if ($row->subtype == 'service') { ?>target="_blank"<?php } else { ?>class="fwa-modal"<?php } ?>>
								<i class="fal fa-puzzle-piece"></i>
								<?php echo JText::_('FWMG_ADDON_DETAILS'); ?>
							</a>
						</div>
					</div>
				</div>
			</div>

<?php
	}
}
?>
		</div>
<?php
if ($design_addons_exists) {
?>
		<h2><?php echo JText::_('FWMG_PRODUCT_DESIGNS'); ?></h2>
		<div class="row fwa-mb-cardbox fwshop-items">
<?php
	foreach ($this->list as $row) {
		if ($row->subtype != 'design') {
			continue;
		}
        $subtype = str_replace(' ', '-', $row->subtype);
		$classes = array('col-md-6 fwshop-item filtr-item');
		$classes[] = strtolower($subtype);

		$image = $row->image?$row->image:(FWMG_ADMIN_ASSETS_URI.'images/'.$row->update_name.'.jpg');

		$action = $action_button = '';
		$category = array('type-'.$subtype);
		if (!empty($this->deals)) {
			foreach ($this->deals as $deal) {
				if (!empty($deal->_products)) {
					foreach ($deal->_products as $p) {
						if ($p->name == $row->name) {
							$category[] = 'deal-'.$deal->id;
						}
					}
				}
			}
		}
		if ($row->subtype == 'service') {
            $category[] = 'status-service';
			$category[] = 'status-buy';
			$action = 'view';
			$action_button = 'fa-search';
        } elseif ($row->installed) {
			$category[] = 'status-installed';
			if (!empty($row->installable)) {
				$category[] = 'status-owned';
			}
			if ($row->loc_version and $row->rem_version and $row->loc_version != 'x.x.x' and $row->loc_version != $row->rem_version) {
				$category[] = 'status-update';
				$action = 'update';
				$action_button = 'fa-sync-alt';
			} elseif ($row->enabled) {
				$category[] = 'status-enabled';
				$action = 'disable';
				$action_button = 'fa-trash-alt';
			} else {
				$category[] = 'status-disabled';
				$action = 'enable';
				$action_button = 'fa-check';
			}
		} else {
			if (!empty($row->installable)) {
				$category[] = 'status-available';
				$category[] = 'status-owned';
				$action = 'install';
				$action_button = 'fa-download';
			} else {
				$category[] = 'status-buy';
				$category[] = 'status-unowned';
				$action = 'buy';
				$action_button = 'fa-shopping-cart';
			}
		}
        $addon_buy = ($action == 'buy' and in_array($subtype, array('plugin', 'module', 'data-type', 'design')));
?>
			<div class="<?php echo esc_attr(implode(' ', $classes)); ?> type-<?php echo str_replace('datatype', 'data-type', $row->subtype); ?>" data-category="<?php echo esc_attr(implode(',', $category)); ?>" data-ext="<?php echo esc_attr($row->update_name); ?>">
				<div class="row no-gutters">
					<div class="col-4 fwshop-addon-img">
						<a href="<?php if ($row->subtype == 'service') echo esc_attr($row->link); else { ?>javascript:<?php } ?>" <?php if ($row->subtype == 'service') { ?>target="_blank"<?php } else { ?>class="fwa-modal"<?php } ?>>
							<img class="img-thumbnail" src="<?php echo esc_attr($image); ?>" alt="<?php echo esc_attr($row->name); ?>">
						</a>
					</div>
					<div class="col-8 pl-3">
						<div class="fwshop-addon-title">
							<a href="<?php if ($row->subtype == 'service') echo esc_attr($row->link); else { ?>javascript:<?php } ?>" <?php if ($row->subtype == 'service') { ?>target="_blank"<?php } else { ?>class="fwa-modal"<?php } ?>>
								<?php echo esc_html($row->name); ?>
							</a>
                        </div>
						<div class="fwshop-addon-description">
							<?php echo fwgHelper::stripTags($row->description, 147); ?>
						</div>
						<div class="float-left fwshop-addon-badges">
<?php
			/* addon badges */
			if (isset($row->price)) {
				if ($row->price > 0) {
?>
							<span class="badge badge-paid" data-toggle="tooltip" data-bs-toggle="tooltip" title="" data-original-title="<?php echo JText::_('FWMG_PAID'); ?>"><i class="fad fa-usd-circle"></i></span>
<?php
				} else {
?>
							<span class="badge badge-free" data-toggle="tooltip" data-bs-toggle="tooltip" title="" data-original-title="<?php echo JText::_('FWMG_FREE_EXCL'); ?>"><i class="fad fa-download"></i></span>
<?php
				}
			}
			if (in_array('badge_hot', $row->_badges)) {
?>
							<span class="badge badge-hot" data-toggle="tooltip" data-bs-toggle="tooltip" title="<?php echo JText::_('FWA_HOT') ?>"><i class="fad fa-fire-alt"></i></span>
<?php
			}
			if (in_array('badge_recommended', $row->_badges)) {
?>
							<span class="badge badge-recommended" data-toggle="tooltip" data-bs-toggle="tooltip" title="<?php echo JText::_('FWA_RECOMMENDED') ?>"><i class="fad fa-thumbs-up"></i></span>
<?php
			}
			if (in_array('badge_new', $row->_badges)) {
?>
							<span class="badge badge-new" data-toggle="tooltip" data-bs-toggle="tooltip" title="<?php echo JText::_('FWA_NEW') ?>"><i class="fad fa-star"></i></span>
<?php
			}
			if (in_array('badge_updated', $row->_badges)) {
?>
							<span class="badge badge-updated" data-toggle="tooltip" data-bs-toggle="tooltip" title="<?php echo JText::_('FWA_RECENTLY_UPDATED') ?>"><i class="fad fa-sync"></i></span>
<?php
			}
?>
						</div>
						<div class="float-right fwshop-addon-btn">
							<a href="<?php if ($row->subtype == 'service') echo esc_attr($row->link); else { ?>javascript:<?php } ?>" <?php if ($row->subtype == 'service') { ?>target="_blank"<?php } else { ?>class="fwa-modal"<?php } ?>>
								<i class="fal fa-puzzle-piece"></i>
								<?php echo JText::_('FWMG_ADDON_DETAILS'); ?>
							</a>
						</div>
					</div>
				</div>
			</div>

<?php
	}
?>
		</div>
<?php
}
if (false and !empty($this->deals)) {
?>
		<div class="row fwa-mb-cardbox fwshop-items">
<?php
	foreach ($this->deals as $deal) {
		if ($deal->has_version) {
?>
			<div class="col-md-6 col-lg-3 fwshop-item filtr-item type-deal" data-category="status-buy,type-deal" data-id="<?php echo (int)$deal->id; ?>">
				<div class="card">
					<div class="card-img-top">
						<a href="javascript:" class="fwa-deal-modal">
							<img class="card-img-top" src="<?php echo esc_attr($deal->image); ?>" />
							<div class="fwshop-item-type">
								<?php echo JText::_('FWMG_DEAL_VERSION'); ?>
							</div>
<?php
            if ($deal->version_amount > 0) {
?>
							<span class="badge badge-discount">
							<?php echo round($deal->version_amount); ?> <i class="fal fa-percent"></i> <?php echo JText::_('FWA_OFF'); ?>
							</span>
<?php
            }
?>
							<div class="card-item-details">
								<i class="far fa-search"></i>
								<span><?php echo JText::_('FWA_VIEW_DETAILS') ?></span>
							</div>
						</a>
					</div>
					<div class="card-body">
						<div class="card-title">
							<?php echo JText::_($deal->name); ?>
						</div>
						<div class="fwshop-item-purchase clearfix">
							<span class="btn">
								$<?php echo number_format($deal->_version_discount_price, 2); ?>
							</span>
						</div>
					</div>
				</div>
			</div>

<?php
		} elseif ($deal->has_subscription) {
?>
			<div class="col-md-6 col-lg-3 fwshop-item filtr-item type-deal" data-category="status-buy,type-deal" data-id="<?php echo (int)$deal->id; ?>">
				<div class="card">
					<div class="card-img-top">
						<a href="javascript:" class="fwa-deal-modal">
							<img class="card-img-top" src="<?php echo esc_attr($deal->image); ?>" />
							<div class="fwshop-item-type">
								<?php echo JText::_('FWMG_DEAL_SUBSCRIPTION'); ?>
							</div>
<?php
            if ($deal->amount > 0) {
?>
							<span class="badge badge-discount">
							<?php echo round($deal->amount); ?> <i class="fal fa-percent"></i> <?php echo JText::_('FWA_OFF'); ?>
							</span>
<?php
            }
?>
							<div class="card-item-details">
								<i class="far fa-search"></i>
								<span><?php echo JText::_('FWA_VIEW_DETAILS') ?></span>
							</div>
						</a>
					</div>
					<div class="card-body">
						<div class="card-title">
							<?php echo JText::_($deal->name); ?>
						</div>
						<div class="fwshop-item-purchase clearfix">
							<span class="btn">
								$<?php echo number_format($deal->_discount_price, 2); ?>
							</span>
						</div>
					</div>
				</div>
			</div>

<?php
		}
	}
?>
		</div>
<?php
}
?>
    </div>
    <input type="hidden" name="task" value="" />
    <input type="hidden" name="boxchecked" value="" />
</form>
<div id="fwa-addon-description" class="modal fade">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"></h5>
        <button type="button" class="close" data-dismiss="modal" data-bs-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body"></div>
    </div>
  </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function() {
	(function($) {
    $('#fwa-addon-description').on('show', function() {
        $(this).addClass('show');
    }).on('hide', function() {
        $(this).removeClass('show');
    }).on('click', '.fws-addon .fws-addon-cart-buttons button', function() {
		var $btn = $(this).attr('disabled', true);
		var $img = $('<img/>', {
			'src' : '<?php echo JURI::root(true); ?>/components/com_fwgallery/assets/images/ajax-loader.gif',
			'style': 'height:auto;width:auto;position:absolute;margin:9px -65px;'
		});
		$btn.after($img);
        var $wrp = $btn.closest('.fws-addon');
		$.ajax({
			dataType: 'json',
            type: 'post',
			data: {
				format: 'json',
				view: 'addon',
				layout: 'buy',
                is_version: $btn.data('version'),
				ext: [$wrp.data('ext')]
			}
		}).done(function(data) {
			$img.remove();
			$btn.attr('disabled', false);

			var $total = $('.fw-cart-total').html('');
			var $qty = $('.fw-cart-items').html('');
			var $wrp = $total.parent().hide();
			if (data.result && data.result.list) {
				$total.html(data.result.total_formatted);
				$qty.html('('+data.result.list.length+')');
				$wrp.show();
			}
			if (data.msg) {
				fwmg_alert(data.msg);
			}
		});
    }).on('click', '.fws-deal .fws-addon-cart-button-version button', function() {
		var $btn = $(this).attr('disabled', true);
		var $img = $('<img/>', {
			'src' : '<?php echo JURI::root(true); ?>/components/com_fwgallery/assets/images/ajax-loader.gif',
			'style': 'height:auto;width:auto;position:absolute;margin:9px -65px;'
		});
		$btn.after($img);
        var $wrp = $btn.closest('.fws-deal');
		$.ajax({
			dataType: 'json',
            type: 'post',
			data: {
				format: 'json',
				view: 'addon',
				layout: 'buy',
                is_version: 1,
				ext: ['deal-'+$wrp.data('id')+'-version']
			}
		}).done(function(data) {
			$img.remove();
			$btn.attr('disabled', false);

			var $total = $('.fw-cart-total').html('');
			var $qty = $('.fw-cart-items').html('');
			var $wrp = $total.parent().hide();
			if (data.result && data.result.list) {
				$total.html(data.result.total_formatted);
				$qty.html('('+data.result.list.length+')');
				$wrp.show();
			}
			if (data.msg) {
				fwmg_alert(data.msg);
			}
		});
    }).on('click', '.fws-deal .fws-addon-cart-button-subscription button', function() {
		var $btn = $(this).attr('disabled', true);
		var $img = $('<img/>', {
			'src' : '<?php echo JURI::root(true); ?>/components/com_fwgallery/assets/images/ajax-loader.gif',
			'style': 'height:auto;width:auto;position:absolute;margin:9px -65px;'
		});
		$btn.after($img);
        var $wrp = $btn.closest('.fws-deal');
		$.ajax({
			dataType: 'json',
            type: 'post',
			data: {
				format: 'json',
				view: 'addon',
				layout: 'buy',
                is_version: 0,
				ext: ['deal-'+$wrp.data('id')+'-subscription']
			}
		}).done(function(data) {
			$img.remove();
			$btn.attr('disabled', false);

			var $total = $('.fw-cart-total').html('');
			var $qty = $('.fw-cart-items').html('');
			var $wrp = $total.parent().hide();
			if (data.result && data.result.list) {
				$total.html(data.result.total_formatted);
				$qty.html('('+data.result.list.length+')');
				$wrp.show();
			}
			if (data.msg) {
				fwmg_alert(data.msg);
			}
		});
    });
    $('.fwshop-item a.fwa-modal').click(function(ev) {
        ev.preventDefault();
        var $wrapper = $(this).closest('.fwshop-item');
        var name = $wrapper.data('ext');
        var $popup = $('#fwa-addon-description');
        $popup.find('.modal-title').html($wrapper.find('.fwshop-addon-title a').html() + ' <?php echo JText::_('FWA_ADDON', true); ?>');
        $popup.find('.modal-body').html('');
        $popup.modal('show');

        $.ajax({
            url: '',
            type: 'post',
            dataType: 'json',
            data: {
                format: 'json',
				view: 'addon',
                layout: 'load_addon_description',
                update_name: name
            }
        }).done(function(data) {
            if (data.html) {
                $popup.find('.modal-body').html(data.html);
            } else {
                $popup.modal('hide');
            }
            if (data.msg) {
                fwmg_alert(data.msg)
            }
        });
    });
    $('.card-img-top a.fwa-deal-modal').click(function(ev) {
        ev.preventDefault();
        var $wrapper = $(this).closest('.fwshop-item');
        var $popup = $('#fwa-addon-description');
        $popup.find('.modal-title').html($wrapper.find('.card-title').html() + ' <?php echo JText::_('FWA_DEAL', true); ?>');
        $popup.find('.modal-body').html('');
        $popup.modal('show');

        $.ajax({
            url: '',
            type: 'post',
            dataType: 'json',
            data: {
                format: 'json',
				view: 'addon',
                layout: 'load_deal_description',
                id: $wrapper.data('id')
            }
        }).done(function(data) {
            if (data.html) {
                $popup.find('.modal-body').html(data.html);
            } else {
                $popup.modal('hide');
            }
            if (data.msg) {
                fwmg_alert(data.msg)
            }
        });
	});
	$('.fwshop-item span.btn').click(function() {
		$(this).closest('.fwshop-item').find('a').click();
	});
	$('button.fwmg-action').click(function() {
		var $btn = $(this);
		if ($btn.attr('disabled')) return;

		var action = $btn.data('action');
		if (action == 'buy') {
			$btn.attr('disabled', true);
			var $img = $('<img/>', {
				'src' : '<?php echo JURI::root(true); ?>/components/com_fwgallery/assets/images/ajax-loader.gif',
				'style': 'height:auto;width:auto;position:absolute;margin:9px -65px;'
			});
			$btn.after($img);
			$.ajax({
				dataType: 'json',
				data: {
					format: 'json',
					view: 'addon',
					layout: action,
					ext: [$btn.data('ext')]
				}
			}).done(function(data) {
				$img.remove();
				$btn.attr('disabled', false);

				var $total = $('.fw-cart-total').html('');
				var $qty = $('.fw-cart-items').html('');
				var $wrp = $total.parent().hide();
				if (data.result && data.result.list) {
					$total.html(data.result.total_formatted);
					$qty.html('('+data.result.list.length+')');
					$wrp.show();
				}
				if (data.msg) {
					fwmg_alert(data.msg);
				}

			});
			return;
		} else if (action == 'disable') {
			var $wrapper = $btn.closest('.card-body');
			var name = $wrapper.find('.fwshop-addon-title a').html().trim();
			if (!confirm('<?php echo JText::_('FWMG_DO_YOU_REALLY_WANT_DISABLE', true); ?> "'+name+'"')) return;
		} else if (action == 'view') {
            $btn.closest('.card').find('.card-img-top a')[0].click();
            return;
		}

		$btn.attr('disabled', true);
		var $img = $('<img/>', {
			'src' : '<?php echo JURI::root(true); ?>/components/com_fwgallery/assets/images/ajax-loader.gif',
			'style': 'height:auto;width:auto;position:absolute;margin:9px -65px;'
		});
		$btn.after($img);
		$.ajax({
			dataType: 'json',
			data: {
				format: 'json',
				view: 'addon',
				layout: action,
				ext: [$btn.data('ext')]
			}
		}).done(function(data) {
			$btn.attr('disabled', false);
			$img.remove();
			if (data.msg) {
				fwmg_alert(data.msg);
			}
			if (data.result) {
				var row = data.result[0];
				$btn.data('action', row.action);

				var $alert = $btn.closest('.alert-notice');
				if ($alert.length) {
					$alert.remove();
				} else {
					var $wrapper = $btn.closest('.fwshop-item');
					$btn.parent().find('.fwmg-action-text').html(row.title);
					$btn.find('i').attr('class', 'fal fa-'+row.icon);
					if (row.remove_class) {
						var category = $wrapper.attr('data-category').toString().replace(','+row.remove_class, '');
						$wrapper.attr('data-category', category).data('category', category);
					}
					if (row.add_class) {
						var category = $wrapper.attr('data-category').toString()+','+row.add_class;
						$wrapper.attr('data-category', category).data('category', category);
					}
				}
			}
		});
	});
	$('.fwa-filter-bar-addons input').change(function() {
		var filters = [];

		$('.fwa-filter-bar-addons').find('select,input').each(function() {
			if (this.value != 'all' && this.value != '') filters.push(this.value);
		});
		if (filters) {
			$('.fwshop-items .fwshop-item').each(function() {
				var $el = $(this);
				var cats = $el.data('category').split(',');
				var hide_el = false;
				for (var i = 0; i < filters.length; i++) {
					if (cats.indexOf(filters[i]) == -1) {
						hide_el = true;
						break;
					}
				}
				if (hide_el) {
					$el.hide(300);
				} else {
					$el.show(300);
				}
			});
		} else {
			$('.fwshop-items .fwshop-item').show(300);
		}
	});
	$('.fwa-filter-bar-addons button').click(function() {
		$('.fwa-filter-bar-addons input:eq(0)').change();
	});
    $('.fwa-filter-bar .btn-primary').click();
    $('.fwa-filter-bar-addons select').change(function() {
        $('.fwa-filter-bar-addons button').click();
    });
	$('.fwa-filter-bar-addons').on('click', 'button:has(".fa-download")', function() {
		$('button.fwmg-action:has(".fa-download")').click();
	});
	$('.fwa-filter-bar-addons').on('click', 'button:has(".fa-sync-alt")', function() {
		$('button.fwmg-action:has(".fa-sync-alt")').click();
	});
	$('.fwa-sidebar-menu li a.fw-admin-addons-buy').click(function() {
		$('select[name="type"]').val('all');
		$('select[name="status"]').val('status-buy');
		$('select[name="deal"]').val('all');
		$('.fwa-filter-bar .btn-primary').click();
	});
	$('.fwa-sidebar-menu li a.fw-admin-addons-deals').click(function() {
		$('select[name="type"]').val('type-deal');
		$('select[name="status"]').val('all');
		$('select[name="deal"]').val('all');
		$('.fwa-filter-bar .btn-primary').click();
	});
	if (location.hash.length) {
		var buff = location.hash.toString().split('=');
		if (buff.length == 2) {
			switch (buff[0]) {
			case '#type' :
				$('select[name="type"]').val('type-'+buff[1]);
				$('.fwa-filter-bar .btn-primary').click();
				break;
			case '#status' :
				$('button[data-value="status-'+buff[1]+'"]').click();
				break;
			}
		}
	}
	})(jQuery);
});
</script>
<?php
echo JLayoutHelper::render('utilites.batchupload', array(
	'allusers'=>$this->users,
	'current_user'=>$this->current_user,
	'category'=>'',
	'reload'=>false
), JPATH_COMPONENT);
echo JLayoutHelper::render('utilites.quickcategories', array(), JPATH_COMPONENT);
echo JLayoutHelper::render('common.menu_end', array(), JPATH_COMPONENT);
