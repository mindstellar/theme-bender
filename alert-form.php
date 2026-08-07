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
?>
<script type="text/javascript">
(function () {
    "use strict";
    function ready(fn) {
        if (document.readyState !== 'loading') { fn(); }
        else { document.addEventListener('DOMContentLoaded', fn); }
    }
    ready(function () {
        var subButton = document.querySelector('.sub_button');
        if (subButton) {
            subButton.addEventListener('click', function (e) {
                e.preventDefault();
                var email = document.getElementById('alert_email');
                var userId = document.getElementById('alert_userId');
                var alertField = document.getElementById('alert');
                var body = new URLSearchParams({
                    email: email ? email.value : '',
                    userid: userId ? userId.value : '',
                    alert: alertField ? alertField.value : '',
                    page: 'ajax',
                    action: 'alerts'
                });
                fetch('<?php echo osc_base_url(true); ?>', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: body.toString()
                }).then(function (r) { return r.text(); }).then(function (data) {
                    if (data == 1) { alert('<?php echo osc_esc_js(__('You have sucessfully subscribed to the alert', 'bender')); ?>'); }
                    else if (data == -1) { alert('<?php echo osc_esc_js(__('Invalid email address', 'bender')); ?>'); }
                    else { alert('<?php echo osc_esc_js(__('There was a problem with the alert', 'bender')); ?>'); }
                });
            });
        }

        var sQuery = '<?php echo osc_esc_js(AlertForm::default_email_text()); ?>';
        var emailInput = document.querySelector('input[name=alert_email]');
        if (emailInput) {
            if (emailInput.value === sQuery) { emailInput.style.color = 'gray'; }
            emailInput.addEventListener('click', function () {
                if (emailInput.value === sQuery) {
                    emailInput.value = '';
                    emailInput.style.color = '';
                }
            });
            emailInput.addEventListener('blur', function () {
                if (emailInput.value === '') {
                    emailInput.value = sQuery;
                    emailInput.style.color = 'gray';
                }
            });
            emailInput.addEventListener('keypress', function () {
                emailInput.style.background = '';
            });
        }
    });
})();
</script>

<div class="alert_form">
    <?php if(function_exists('osc_search_alert_subscribed') && osc_search_alert_subscribed()) { ?>
        <h3>
            <strong><?php _e('Already subscribed to this search', 'bender'); ?></strong>
        </h3>
    <?php } else { ?>
        <h3>
            <strong><?php _e('Subscribe to this search', 'bender'); ?></strong>
        </h3>
        <form action="<?php echo osc_base_url(true); ?>" method="post" name="sub_alert" id="sub_alert" class="nocsrf">
                <?php AlertForm::page_hidden(); ?>
                <?php AlertForm::alert_hidden(); ?>

                <?php if(osc_is_web_user_logged_in()) { ?>
                    <?php AlertForm::user_id_hidden(); ?>
                    <?php AlertForm::email_hidden(); ?>

                <?php } else { ?>
                    <?php AlertForm::user_id_hidden(); ?>
                    <?php AlertForm::email_text(); ?>

                <?php }; ?>
                <button type="submit" class="sub_button" ><?php _e('Subscribe now', 'bender'); ?>!</button>
        </form>
    <?php } ?>
</div>