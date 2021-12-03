<?php
/**
 * FW Gallery 6.7.2
 * @copyright (C) 2020 Fastw3b
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.fastw3b.net/ Official website
 **/

defined('_JEXEC') or die('Restricted access');

?>
            </div>
            <div id="fwaSidebarRight" class="fwa-sidebar-right collapsed">
                <div class="fwa-sidebar-right-header">
                    <i class="fal fa-magic"></i>
                    <?php echo JText::_('FWMG_PRODUCT_WIZARD'); ?>
                </div>
                <div id="fwaSidebarRightBody" class="fwa-sidebar-right-body"></div>
            </div>
        </div>
    </div>

    <div id="fwmg-toast-stack"></div>

    <div class="modal fade" id="fwmg-login-register">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><?php echo JText::_('FWMG_LOGIN_REGISTER'); ?></h5>
                    <button type="button" class="close" data-dismiss="modal" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <ul class="nav nav-tabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-toggle="tab" data-bs-toggle="tab" href="#fwmg-login-tab" role="tab" aria-controls="fwmg-login-tab" aria-selected="true"><?php echo JText::_('FWMG_LOGIN'); ?></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" data-bs-toggle="tab" href="#fwmg-register-tab" role="tab" aria-controls="fwmg-register-tab" aria-selected="false"><?php echo JText::_('FWMG_REGISTER'); ?></a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="fwmg-login-tab" role="tabpanel" aria-labelledby="fwmg-login-tab">
                            <div class="">
                                <input type="text" name="login_email" class="form-control" placeholder="<?php echo JText::_('FWMG_EMAIL'); ?>" />
                            </div>
                            <div class="mt-3">
                                <input type="password" name="login_password" class="form-control" placeholder="<?php echo JText::_('FWMG_PASSWORD'); ?>" />
                            </div>
                            <div class="text-right mt-3">
                                <a href="javascript:" class="fwmg-reset-password">
                                    <?php echo JText::_('FWMG_RESTORE_PASSWORD'); ?><i class="fal fa-envelope ml-1"></i>
                                </a>
                            </div>
                            <div class="text-center mt-4">
                                <button type="button" class="btn btn-primary"><?php echo JText::_('FWMG_LOGIN'); ?></button>
                            </div>
                        </div>
                        <div class="tab-pane" id="fwmg-register-tab" role="tabpanel" aria-labelledby="fwmg-register-tab">
                            <div class="fwmg-registration">
                                <input type="text" name="name" class="form-control" placeholder="<?php echo JText::_('FWMG_FULL_NAME'); ?>" />
                            </div>
                            <div class="mt-3 fwmg-registration">
                                <input type="text" name="email" class="form-control" placeholder="<?php echo JText::_('FWMG_EMAIL'); ?>" />
                            </div>
                            <div class="mt-4 text-center fwmg-registration">
                                <button type="button" class="btn btn-primary"><?php echo JText::_('FWMG_REGISTER_BTN'); ?></button>
                            </div>
                            <div class="mt-3 fwmg-confirmation" style="display:none">
                                <input type="text" name="confirmation" class="form-control" placeholder="<?php echo JText::_('FWMG_CONFIRMATION_CODE'); ?>" />
                            </div>
                            <div class="mt-3 text-center fwmg-confirmation" style="display:none">
                                <button type="button" class="btn btn-primary"><?php echo JText::_('FWMG_CONFIRM'); ?></button>
                            </div>
                            <!-- <div class="alert mb-3n" style="display:none"><?php echo JText::_('FWMG_CONFIRMATION_HINT') ?></div> -->
                        </div>
                    </div>
                    <div class="alert mt-3 mb-0"><?php echo JText::_('FWMG_LOGIN_HINT') ?></div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="fwmg-logout">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><?php echo JText::_('FWMG_PROFILE_TITLE'); ?></h5>
                    <button type="button" class="close" data-dismiss="modal" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row mt-3 mb-3">
                        <div class="col-6 text-center"><a href="https://fastw3b.net/client-section" class="btn btn-primary" target="_blank"><?php echo JText::_('FWMG_PROFILE'); ?></a></div>
                        <div class="col-6 text-center"><button type="button" class="btn btn-secondary"><?php echo JText::_('FWMG_LOGOUT'); ?></button></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="fwmg-install">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><?php echo JText::_('FWMG_INSTALL'); ?></h5>
                    <button type="button" class="close" data-dismiss="modal" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <table class="table"><tbody></tbody></table>
                </div>
                <div class="modal-footer">
					<button type="button" class="btn" data-dismiss="modal" data-bs-dismiss="modal"><?php echo JText::_('FWMG_CANCEL'); ?></button>
					<button type="button" class="btn btn-success"><?php echo JText::_('FWMG_INSTALL'); ?></button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="fwmg-update">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><?php echo JText::_('FWMG_UPDATE'); ?></h5>
                    <button type="button" class="close" data-dismiss="modal" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <table class="table"><tbody></tbody></table>
                </div>
                <div class="modal-footer">
					<button type="button" class="btn" data-dismiss="modal" data-bs-dismiss="modal"><?php echo JText::_('FWMG_CANCEL'); ?></button>
					<button type="button" class="btn btn-success"><?php echo JText::_('FWMG_UPDATE'); ?></button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    (function($) {
        window.fwmg_wizard_alert = function (msg, title = '<?php echo JText::_('FWMG_NOTIFICATION', true); ?>', icon = '', action_button='') {
            var $el;

            if (icon) icon = '<i class="'+icon+'"></i>';
            actions = action_button?('<div class="fwa-sidebar-message-actions">'+action_button+'</div>'):'';
            
            $el = $('<div class="fwa-sidebar-message" style="opacity:0">'+icon+'<div class="fwa-sidebar-message-header">' + title + '</div><div class="fwa-sidebar-message-body">'+ msg +'</div>' + actions + '</div>');
            $('#fwaSidebarRightBody').append($el);
            $('#fwaSidebarRightBtn').addClass('active');

            $el.animate({
                'opacity': 1
            }, 500);
        };
        window.fwmg_alert = function (msg, title = '<?php echo JText::_('FWMG_NOTIFICATION', true); ?>', status = 'info') {
            var $el = $('<div role="alert" aria-live="assertive" aria-atomic="true" class="toast toast-' +
                status + '" style="opacity:0">\
    <div class="toast-header">\
        <i class="far fa-bell"></i>\
        <strong>' + title + '</strong>\
    </div>\
    <div class="toast-body">' + msg + '</div>\
    </div>');
            $('#fwmg-toast-stack').append($el);
            $el.animate({
                'opacity': 1
            }, 500);
            setTimeout(function () {
                $el.animate({
                    'opacity': 0
                }, 500, function () {
                    $el.remove();
                });
            }, 5000);
        }
        if (window.bootstrap) {
            $('[data-toggle="tooltip"]').attr('data-bs-toggle', 'tooltip');
            $('[data-toggle="popover"]').each(function () {
                var $el = $(this);
                $el.attr('data-bs-toggle', 'popover');
                if ($el.data('content')) {
                    $el.attr('data-bs-content', $el.data('content'));
                }
                if ($el.data('trigger')) {
                    $el.attr('data-bs-trigger', $el.data('trigger'));
                }
            });

            var popoverTriggerList = [].slice.call(document.querySelectorAll(
                '[data-bs-toggle="popover"]'))
            var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
                return new bootstrap.Popover(popoverTriggerEl, {
                    container: 'body',
                    trigger: 'hover',
                    placement: 'top',
                    html: true
                });
            })
        } else {
            $('[data-toggle="tooltip"]').tooltip();
            $('[data-toggle="popover"]').popover({
                container: 'body'
            });
        }
        $('#fwmg-login-register,#fwmg-logout,#fwmg-install,#fwmg-update').on('show', function() {
            $(this).addClass('show');
        }).on('hide', function() {
            $(this).removeClass('show');
        });

        $('#fwmg-install .modal-footer .btn-success').click(function() {
            var ext = [];
            var $loc_btn = $(this);
            var $popup = $loc_btn.closest('.modal');
            $popup.find('input[name="ext[]"]:checked').each(function() {
                ext.push(this.value);
            });
            if (!ext.length) {
                fwmg_alert('<?php echo JText::_('FWMG_SELECT_ADDONS_TO_INSTALL', true); ?>');
                return;
            }
            var $wait = $('<i class="fas fa-sync fa-spin"></i>');
            $loc_btn.attr('disabled', true).after($wait);

            $.ajax({
                url: '',
                type: 'post',
                dataType: 'json',
                data: {
                    format: 'json',
                    view: 'addon',
                    layout: 'install',
                    ext: ext
                }
            }).done(function(data) {
                $wait.remove();
                $loc_btn.attr('disabled', false);
                if (data.result) {
                    $popup.modal('hide');
                    for (var i = 0; i < data.result.length; i++) {
                        var row = data.result[i];
                        var $btn = $('tr[data-ext="'+row.update_name+'"] button');
                        $btn.removeClass('fwmg-addon-install btn-warning').addClass('fwmg-addon-disable btn-danger').html('<?php echo JText::_('FWMG_DISABLE', true); ?>');
                    }
                    location = location.toString().replace(/#.*$/, '');
                }
                if (data.msgs) {
                    for (var i = 0; i < data.msgs.length; i++) {
                        fwmg_alert(data.msgs[i]);
                    }
                }
            });
        });
        $('#fwmg-update .modal-footer .btn-success').click(function() {
            var ext = [];
            var $btn = $(this);
            var $popup = $btn.closest('.modal');
            $popup.find('input[name="ext[]"]:checked').each(function() {
                ext.push(this.value);
            });
            if (!ext.length) {
                fwmg_alert('<?php echo JText::_('FWMG_SELECT_ADDONS_TO_UPDATE', true); ?>');
                return;
            }
            var $wait = $('<i class="fas fa-sync fa-spin"></i>');
            $btn.attr('disabled', true).after($wait);

            $.ajax({
                url: '',
                type: 'post',
                dataType: 'json',
                data: {
                    format: 'json',
                    view: 'addon',
                    layout: 'update',
                    ext: ext
                }
            }).done(function(data) {
                $wait.remove();
                $btn.attr('disabled', false);
                if (data.result) {
                    $popup.modal('hide');
                    for (var i = 0; i < data.result.length; i++) {
                        var row = data.result[i];
                        $('tr[data-ext="'+row.update_name+'"] button').remove();
                    }
                    location = location.toString().replace(/#.*$/, '');
                }
                if (data.msgs) {
                    for (var i = 0; i < data.msgs.length; i++) {
                        fwmg_alert(data.msgs[i]);
                    }
                }
            });
        });
        $('.fwmg-reset-password').click(function() {
            var $btn = $(this);
            if ($btn.data('in-progress')) return;

            var $wrp = $btn.closest('.tab-pane');
            var $email = $wrp.find('input[name="login_email"]');
            if ($email.val().trim() == '') {
                fwmg_alert('<?php echo JText::_('FWMG_NO_LOGIN_EMAIL', true); ?>');
                $email.focus();
                return;
            }

            $btn.data('in-progress', true);

            var $wait = $('<i class="fas fa-sync fa-spin ml-2"></i>');
            $btn.after($wait);

            $.ajax({
                url: '',
                type: 'post',
                dataType: 'json',
                data: {
                    format: 'json',
                    view: 'addon',
                    layout: 'reset_password',
                    email: $email.val()
                }
            }).done(function(data) {
                $btn.data('in-progress', false);
                $wait.remove();
                if (data.msg) {
                    fwmg_alert(data.msg);
                }
            });
        });
        $('#fwmg-login-tab button').click(function() {
            var $btn = $(this);
            var $wrp = $('#fwmg-login-tab');
            var $email = $wrp.find('input[name="login_email"]');
            var $passwd = $wrp.find('input[name="login_password"]');
            if ($email.val().trim() == '') {
                fwmg_alert('<?php echo JText::_('FWMG_NO_LOGIN_EMAIL', true); ?>');
                $email.focus();
                return;
            }
            if ($passwd.val().trim() == '') {
                fwmg_alert('<?php echo JText::_('FWMG_NO_LOGIN_PASSWORD', true); ?>');
                $passwd.focus();
                return;
            }
            var $wait = $('<i class="fas fa-sync fa-spin ml-2"></i>');
            $btn.attr('disabled', true).after($wait);
            $.ajax({
                type: 'post',
                dataType: 'json',
                data: {
                    format: 'json',
                    view: 'addon',
                    layout: 'login',
                    email: $email.val(),
                    passwd: $passwd.val()
                }
            }).done(function(data) {
                $btn.attr('disabled', false);
                $wait.remove();
                if (data.result) {
                    fwmg_reload_wizard();
                    if (data.result.user_avatar) {
                        $('.fwa-account-photo img').attr('src', data.result.user_avatar);
                    }
                    if (data.result.user_name) {
                        $('.fwa-account-user-info-name a').html(data.result.user_name);
                    }
                    if (data.result.access_code) {
                        $('.fwa-user-no-login').hide(300, function () {
                            $('.fwa-user-logged-in').show(300);
                        });
                        $('#fwmg-login-register').modal('hide');
                    }
                }
                if (data.msg) fwmg_alert(data.msg);
            });
        });
        $('#fwmg-register-tab .fwmg-registration button').click(function() {
            var $btn = $(this);
            var $wrp = $('#fwmg-register-tab');
            var $email = $wrp.find('input[name="email"]');
            var $name = $wrp.find('input[name="name"]');
            if ($email.val().trim() == '') {
                fwmg_alert('<?php echo JText::_('FWMG_NO_REGISTER_EMAIL', true); ?>');
                $email.focus();
                return;
            }
            if ($name.val().trim() == '') {
                fwmg_alert('<?php echo JText::_('FWMG_NO_FULLNAME', true); ?>');
                $name.focus();
                return;
            }
            var $wait = $('<i class="fas fa-sync fa-spin ml-2"></i>');
            $btn.attr('disabled', true).after($wait);
            $.ajax({
                type: 'post',
                dataType: 'json',
                data: {
                    format: 'json',
                    view: 'addon',
                    layout: 'register',
                    email: $email.val(),
                    name: $name.val()
                }
            }).done(function(data) {
                $btn.attr('disabled', false);
                $wait.remove();
                if (data.result) {
                    var $popup = $('#fwmg-login-register');
                    $popup.find('.fwmg-registration').hide();
                    $popup.find('.fwmg-confirmation').show();
                }
                if (data.msg) fwmg_alert(data.msg);
            });
        });
        $('#fwmg-register-tab .fwmg-confirmation button').click(function() {
            var $btn = $(this);
            var $wrp = $('#fwmg-register-tab');
            var $code = $wrp.find('input[name="confirmation"]');
            if ($code.val().trim() == '') {
                fwmg_alert('<?php echo JText::_('FWMG_NO_CONFIRMATION_CODE', true); ?>');
                $code.focus();
                return;
            }
            var $wait = $('<i class="fas fa-sync fa-spin ml-2"></i>');
            $btn.attr('disabled', true).after($wait);
            $.ajax({
                type: 'post',
                dataType: 'json',
                data: {
                    format: 'json',
                    view: 'addon',
                    layout: 'confirm',
                    code: $code.val()
                }
            }).done(function(data) {
                $btn.attr('disabled', false);
                $wait.remove();
                if (data.result) {
                    fwmg_reload_wizard();
                    if (data.result.user_avatar) {
                        $('.fwa-account-photo img').attr('src', data.result.user_avatar);
                    }
                    if (data.result.user_name) {
                        $('.fwa-account-user-info-name a').html(data.result.user_name);
                    }
                    if (data.result.access_code) {
                        $('.fwa-user-no-login').hide(300, function () {
                            $('.fwa-user-logged-in').show(300);
                        });
                        $('#fwmg-login-register').modal('hide');
                    }
                }
                if (data.msg) fwmg_alert(data.msg);
            });
        });
        $('#fwmg-logout .modal-body button').click(function() {
            var $wait = $('<i class="fas fa-sync fa-spin ml-2"></i>');
            var $btn = $(this).attr('disabled', true).after($wait);
            $.ajax({
                'url': '',
                'method': 'post',
                dataType: 'json',
                'data': {
                    'format': 'json',
                    'view': 'fwgallery',
                    'layout': 'revoke_code'
                }
            }).done(function (data) {
                $btn.attr('disabled', false);
                $wait.remove();
                if (data.result) {
                    fwmg_reload_wizard();
                    $('input[name="update_code"]').val('');
                    $('.fwa-user-logged-in').hide(300, function () {
                        $('.fwa-user-logged-in').attr('style', 'display:none!important');
                        $('#fwmg-logout').modal('hide');
                        $('.fwa-user-no-login').show(300);
                    });
                }
                if (data.msg) fwmg_alert(data.msg);
            });
        });
        $('a[data-toggle="tab"]').on('show', function() {
            var $link = $(this);
            $link.closest('ul').find('.active').removeClass('active');
            $link.addClass('active');
        });
        $('#toggleSidebarMenu').click(function () {
            setCookie('fwmg_hide_left_sidebar', $('#fwaSidebarWrapper').hasClass('collapsed')?0:1);
            $(this).removeClass('active');
            $('#fwaSidebarWrapper').toggleClass('collapsed');
            $('#toggleSidebarIcon').toggleClass('fa-bars fa-arrow-left');
        });
        $('#fwaSidebarRightBtn').click(function () {
            setCookie('fwmg_hide_right_sidebar', $('#fwaSidebarRight').hasClass('collapsed')?0:1);
            $('#fwaSidebarRight').toggleClass('collapsed');
        });
        $('#isisJsData').hide();
        fwmg_reload_wizard();
<?php
if ($messages = fwgHelper::getMessages()) {
    foreach($messages as $message) {
?>
        fwmg_alert('<?php echo fwgHelper::messageEscape($message->msg); ?>',
            '<?php echo JText::_('FWMG_NOTIFICATION', true) ?>',
            '<?php echo esc_js($message->status); ?>'
        );
<?php
    }
    fwgHelper::clearMessages();
}
?>
    })(jQuery);
});
function fwmg_dismiss_wizard(num) {
    setCookie('fwmg_dismissed_'+num, 1);
    fwmg_reload_wizard();
}
function fwmg_reload_wizard() {
    jQuery('#fwaSidebarRightBtn').removeClass('active');
    jQuery('#fwaSidebarRightBody').html('');
    var $counter = jQuery('#fwaSidebarRightBtn span.badge').html('').hide();
    jQuery.ajax({
        url: '',
        type: 'post',
        dataType: 'json',
        data: {
            format: 'json',
            view: 'fwgallery',
            layout: 'load_wizards'
        }
    }).done(function(data) {
        if (data.result) {
            var $install = jQuery('#fwmg-install .modal-body tbody').html('');
            if (data.result.install) {
                for (var i = 0; i < data.result.install.length; i++) {
                    var row = data.result.install[i];
                    var $row = jQuery('<tr>\
    <td><input type="checkbox" name="ext[]" value="'+row.update_name+'" checked="checked" /></td>\
    <td>'+row.name+'</td>\
</tr>');
                    $install.append($row);
                }
            }
            var $update = jQuery('#fwmg-update .modal-body tbody').html('');
            if (data.result.update) {
                for (var i = 0; i < data.result.update.length; i++) {
                    var row = data.result.update[i];
                    var $row = jQuery('<tr>\
    <td><input type="checkbox" name="ext[]" value="'+row.update_name+'" checked="checked" /></td>\
    <td>'+row.name+'</td>\
</tr>');
                    $update.append($row);
                }
            }
            if (data.result.wizards) {
                var cnt = 0;
                for (var i = 0; i < data.result.wizards.length; i++) {
                    var row = data.result.wizards[i];
                    fwmg_wizard_alert(row.text, row.title, row.icon, row.buttons);
                    if (row.active) {
                        cnt++;
                    }
                }
                if (cnt) {
                    $counter.html(cnt).addClass('active').show();
                }
            }
        }
    });
}
function getCookie(name) {
    let matches = document.cookie.match(new RegExp(
        "(?:^|; )" + name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, '\\$1') + "=([^;]*)"
    ));
    return matches ? decodeURIComponent(matches[1]) : undefined;
}
function setCookie(name, value, options = {}) {

    if (options.expires instanceof Date) {
        options.expires = options.expires.toUTCString();
    }

    let updatedCookie = encodeURIComponent(name) + "=" + encodeURIComponent(value);

    for (let optionKey in options) {
        updatedCookie += "; " + optionKey;
        let optionValue = options[optionKey];
        if (optionValue !== true) {
            updatedCookie += "=" + optionValue;
        }
    }

    document.cookie = updatedCookie;
}
</script>