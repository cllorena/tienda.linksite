<?php
/**
 * FW Gallery 6.7.2
 * @copyright (C) 2020 Fastw3b
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.fastw3b.net/ Official website
 **/

defined('_JEXEC') or die('Restricted access');

JHTML::stylesheet('components/com_fwgallery/assets/css/lightgallery.min.css');
JHTML::_('jquery.framework');
JHTML::script('components/com_fwgallery/assets/js/lightgallery.min.js');
JHTML::script('components/com_fwgallery/assets/js/lg-fullscreen.min.js');
JHTML::script('components/com_fwgallery/assets/js/lg-zoom.min.js');

$view = $displayData['view'];
?>
<div id="fwgallery" class="fwcss">
	<div class="fwmg-page-header">
    	<h2 itemprop="headline" data-title="<?php echo JText::_('FWMG_FRONT_END_MANAGER'); ?>"><?php echo JText::_('FWMG_FRONT_END_MANAGER'); ?></h2>
	</div>
	<div class="row no-gutters fwmg-management-body">
		<div class="col-lg-4 col-xl-3 fwmg-management-panel">
			<div class="fwmg-management-panel-wrapper">
				<nav class="navbar navbar-expand-lg text-center">
					<button class="navbar-toggler" type="button" data-toggle="collapse" data-bs-toggle="collapse" data-target="#navbarTogglerfwg" data-bs-target="#navbarTogglerfwg" aria-controls="navbarTogglerfwg" aria-expanded="false" aria-label="Toggle fwg navigation">
						<?php echo JText::_('FWMG_USER_MENU'); ?> <i class="fal fa-bars ml-3"></i>
					</button>
					<div class="collapse navbar-collapse text-left" id="navbarTogglerfwg">
						<?php echo fwgHelper::loadTemplate('user.left', $displayData); ?>
<?php
	if ($view->user->id and class_exists('JVersion')) {
?>
						<div class="fwmg-management-panel-section">
							<div class="fwmg-management-panel-header"><?php echo JText::_('FWMG_MANAGE_ACCOUNT'); ?></div>
							<ul class="nav">
								<li class="nav-item">
									<a class="nav-link" href="javascript:"><i class="far fa-lock-alt fa-fw"></i> <?php echo JText::_('FWMG_CHANGE_PASSWORD'); ?></a>
								</li>
								<li class="nav-item">
									<a class="nav-link" href="<?php echo fwgHelper::route('index.php?option=com_users&task=user.logout&'.JSession::getFormToken().'=1&return='.base64_encode(JURI::root(false))); ?>"><i class="far fa-sign-out-alt fa-fw"></i> <?php echo JText::_('FWMG_LOGOUT'); ?></a>
								</li>
							</ul>
						</div>
<?php
	}
?>
					</div>
				</nav>
			</div>
		</div>
		<div class="col-lg-8 col-xl-9 fwmg-management-section">
			<?php $view->app->triggerEvent('onshowUserSection', array('com_fwgallery', $view)); ?>
		</div>
	</div>
</div>
<?php
if ($view->user->id) {
?>
<div class="modal fade" id="fwmg-change-password" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel"><?php echo JText::_('FWMG_CHANGE_PASSWORD'); ?></h5>
				<button type="button" class="close" data-dismiss="modal" data-bs-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form>
				<div class="modal-body">
					<div class="form-group row">
						<label class="col-md-6">
							<?php echo JText::_('FWMG_CURRENT_PASSWORD'); ?>
						</label>
						<div class="col-md-6">
							<input type="password" class="form-control" name="password" />
						</div>
					</div>
					<div class="form-group row">
						<label class="col-md-6">
							<?php echo JText::_('FWMG_NEW_PASSWORD'); ?>
						</label>
						<div class="col-md-6">
							<input type="password" class="form-control" name="password1" />
						</div>
					</div>
					<div class="form-group row">
						<label class="col-md-6">
							<?php echo JText::_('FWMG_RETYPE_NEW_PASSWORD'); ?>
						</label>
						<div class="col-md-6">
							<input type="password" class="form-control" name="password2" />
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal" data-bs-dismiss="modal"><?php echo JText::_('FWMG_CLOSE'); ?></button>
					<button type="button" class="btn btn-primary"><?php echo JText::_('FWMG_CHANGE_PASSWORD'); ?></button>
				</div>
			</form>
		</div>
	</div>
</div>
<script>
var fwmg_alert_default_title = '<?php echo esc_js(JText::_('FWMG_NOTICE')); ?>';
document.addEventListener('DOMContentLoaded', function() {
	(function($) {
	$('#fwmg-change-password').on('show', function() {
		$(this).addClass('show');
	}).on('hide', function() {
		$(this).removeClass('show');
	});
	$('.fwmg-management-panel-section a:has(".fa-lock-alt")').click(function() {
		$('#fwmg-change-password').modal('show');
	});
	$('#fwmg-change-password button.btn-primary').click(function() {
		var form = this.form;
		var fields = ['password', 'password1', 'password2'];
		for (var i = 0; i < fields.length; i++) {
			if (form[fields[i]].value.trim() == '') {
				alert('<?php echo JText::_('FWMG_ALL_FIELDS_REQUIRED'); ?>');
				return;
			}
		}
		var $btn = $(this).attr('disabled', true);
		$.ajax({
			dataType: 'json',
			data: {
				format: 'json',
				view: 'usersection',
				layout: 'change_password',
				password: form.password.value,
				password1: form.password1.value,
				password2: form.password2.value,
              	is_site: 1
			}
		}).done(function(data) {
			$btn.attr('disabled', false);
			if (data.result) {
				var $popup = $('#fwmg-change-password');
				$popup.find('input').val('');
				$popup.modal('hide');
			}
			if (data.msg) {
				alert(data.msg);
			}
		});
	});
	})(jQuery);
});
</script>
<?php
}
