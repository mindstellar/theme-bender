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