<?php
    /*
     * This file is part of the Bender theme for Shopclass (Mindstellar).
     * Copyright (c) 2014 Osclass (original work, licensed under the Apache License 2.0)
     * Copyright (c) 2021-2026 Mindstellar Community
     *
     * Distributed under the GNU General Public License v3.0 or later. The original
     * Osclass code it derives from was licensed under the Apache License 2.0.
     * See LICENSE for the full GPL-3.0 text.
     *
     * SPDX-License-Identifier: GPL-3.0-or-later
     */

    // meta tag robots
    osc_add_hook('header','bender_nofollow_construct');

    bender_add_body_class('user user-profile');
    osc_add_hook('before-main','sidebar');
    function sidebar(){
        osc_current_web_theme_path('user-sidebar.php');
    }
    osc_add_filter('meta_title_filter','custom_meta_title');
    function custom_meta_title($data){
        return __('Change e-mail', 'bender');;
    }
    osc_current_web_theme_path('header.php') ;
    $osc_user = osc_user();
?>
<h1><?php _e('Change e-mail', 'bender'); ?></h1>
<div class="form-container form-horizontal">
    <div class="resp-wrapper">
        <ul id="error_list"></ul>
        <form id="change-email" action="<?php echo osc_base_url(true); ?>" method="post">
            <input type="hidden" name="page" value="user" />
            <input type="hidden" name="action" value="change_email_post" />
            <div class="control-group">
                <label for="email"><?php _e('Current e-mail', 'bender'); ?></label>
                <div class="controls">
                    <?php echo osc_logged_user_email(); ?>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="new_email"><?php _e('New e-mail', 'bender'); ?> *</label>
                <div class="controls">
                    <input type="text" name="new_email" id="new_email" value="" />
                </div>
            </div>
            <div class="control-group">
                <div class="controls">
                    <button type="submit" class="ui-button ui-button-middle ui-button-main"><?php _e("Update", 'bender');?></button>
                </div>
            </div>
        </form>
    </div>
</div>
<script type="text/javascript">
(function () {
    "use strict";
    var form = document.getElementById('change-email');
    var errorList = document.getElementById('error_list');
    if (!form) { return; }

    var emailRe = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

    form.addEventListener('submit', function (e) {
        var field = form.elements.new_email;
        var errors = [];
        if (!field.value.trim()) {
            errors.push('<?php echo osc_esc_js(__("Email: this field is required", "bender")); ?>.');
        } else if (!emailRe.test(field.value.trim())) {
            errors.push('<?php echo osc_esc_js(__("Invalid email address", "bender")); ?>.');
        }

        if (errorList) { errorList.textContent = ''; }
        if (errors.length) {
            e.preventDefault();
            if (errorList) {
                errors.forEach(function (msg) {
                    var li = document.createElement('li');
                    li.textContent = msg;
                    errorList.appendChild(li);
                });
            }
            var h1 = document.querySelector('h1');
            if (h1) { h1.scrollIntoView({ behavior: 'smooth' }); }
            return;
        }

        form.querySelectorAll('button[type=submit], input[type=submit]').forEach(function (btn) {
            btn.disabled = true;
        });
    });
})();
</script>
<?php osc_current_web_theme_path('footer.php') ; ?>