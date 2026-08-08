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

    bender_add_body_class('recover');
    osc_current_web_theme_path('header.php');
?>
<div class="form-container form-horizontal form-container-box">
    <div class="header">
        <h1><?php _e('Recover your password', 'bender'); ?></h1>
    </div>
    <div class="resp-wrapper">
        <form action="<?php echo osc_base_url(true); ?>" method="post" >
        <input type="hidden" name="page" value="login" />
        <input type="hidden" name="action" value="recover_post" />
        <div class="control-group">
            <label class="control-label" for="email"><?php _e('E-mail', 'bender'); ?></label>
            <div class="controls">
                <?php UserForm::email_text(); ?>
                <?php osc_show_captcha('recover_password'); ?>
            </div>
        </div>
            <div class="control-group">
                <div class="controls">
                    <button type="submit" class="ui-button ui-button-middle ui-button-main"><?php _e("Send me a new password", 'bender');?></button>
                </div>
            </div>
        </form>
    </div>
</div>
<?php osc_current_web_theme_path('footer.php') ; ?>