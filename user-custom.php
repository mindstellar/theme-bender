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

    bender_add_body_class('user user-custom');
    osc_add_hook('before-main','sidebar');
    function sidebar(){
        osc_current_web_theme_path('user-sidebar.php');
    }
    // Core's credits pages and plugin account pages use core's oe-* classes, so they need its stylesheet.
    if (function_exists('osc_gui_print_style')) {
        osc_add_hook('header', function () { osc_gui_print_style(); });
    }
    osc_current_web_theme_path('header.php') ;
?>
<div class="oe-page">
    <div class="oe-account-main">
        <?php osc_run_hook('account_page_before', 'user-custom'); ?>
        <?php osc_render_file(); ?>
        <?php osc_run_hook('account_page_after', 'user-custom'); ?>
    </div>
</div>
<?php
    osc_current_web_theme_path('footer.php');
?>