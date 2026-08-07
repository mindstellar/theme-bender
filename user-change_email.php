<?php
    /*
     *      Shopclass – software for creating and publishing online classified
     *                           advertising platforms
     *
     *                        Copyright (C) 2014 OSCLASS
     *
     *       This program is free software: you can redistribute it and/or
     *     modify it under the terms of the GNU Affero General Public License
     *     as published by the Free Software Foundation, either version 3 of
     *            the License, or (at your option) any later version.
     *
     *     This program is distributed in the hope that it will be useful, but
     *         WITHOUT ANY WARRANTY; without even the implied warranty of
     *        MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
     *             GNU Affero General Public License for more details.
     *
     *      You should have received a copy of the GNU Affero General Public
     * License along with this program.  If not, see <http://www.gnu.org/licenses/>.
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