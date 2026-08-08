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
        return __('Change username', 'bender');;
    }
    osc_current_web_theme_path('header.php') ;
    $osc_user = osc_user();
?>
<h1><?php _e('Change username', 'bender'); ?></h1>
<script type="text/javascript">
(function () {
    "use strict";
    var form = document.getElementById('change-username');
    var errorList = document.getElementById('error_list');
    var username = document.getElementById('s_username');
    var available = document.getElementById('available');
    if (!form) { return; }

    form.addEventListener('submit', function (e) {
        errorList && (errorList.textContent = '');
        if (!form.elements.s_username.value.trim()) {
            e.preventDefault();
            if (errorList) {
                var li = document.createElement('li');
                li.textContent = '<?php echo osc_esc_js(__("Username: this field is required", "bender")); ?>.';
                errorList.appendChild(li);
            }
            var h1 = document.querySelector('h1');
            if (h1) { h1.scrollIntoView({ behavior: 'smooth' }); }
            return;
        }
        form.querySelectorAll('button[type=submit], input[type=submit]').forEach(function (btn) {
            btn.disabled = true;
        });
    });

    if (username && available) {
        var timer = null;
        username.addEventListener('keydown', function () {
            clearTimeout(timer);
            if (!username.value) { return; }
            timer = setTimeout(function () {
                var url = "<?php echo osc_base_url(true); ?>?page=ajax&action=check_username_availability"
                    + "&s_username=" + encodeURIComponent(username.value);
                fetch(url, { credentials: 'same-origin', headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                    .then(function (r) { return r.json(); })
                    .then(function (data) {
                        available.textContent = data.exists == 0
                            ? '<?php echo osc_esc_js(__("The username is available", "bender")); ?>'
                            : '<?php echo osc_esc_js(__("The username is NOT available", "bender")); ?>';
                    });
            }, 1000);
        });
    }
})();
</script>
<div class="form-container form-horizontal">
    <div class="resp-wrapper">
        <ul id="error_list"></ul>
        <form action="<?php echo osc_base_url(true); ?>" method="post" id="change-username">
            <input type="hidden" name="page" value="user" />
            <input type="hidden" name="action" value="change_username_post" />
            <div class="control-group">
                <label class="control-label" for="s_username"><?php _e('Username', 'bender'); ?></label>
                <div class="controls">
                    <input type="text" name="s_username" id="s_username" value="" />
                    <div id="available"></div>
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
<?php osc_current_web_theme_path('footer.php') ; ?>