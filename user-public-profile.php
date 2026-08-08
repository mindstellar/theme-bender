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
    osc_add_hook('header','bender_follow_construct');

    $address = '';
    if(osc_user_address()!='') {
        if(osc_user_city_area()!='') {
            $address = osc_user_address().", ".osc_user_city_area();
        } else {
            $address = osc_user_address();
        }
    } else {
        $address = osc_user_city_area();
    }
    $location_array = array();
    if(trim(osc_user_city()." ".osc_user_zip())!='') {
        $location_array[] = trim(osc_user_city()." ".osc_user_zip());
    }
    if(osc_user_region()!='') {
        $location_array[] = osc_user_region();
    }
    if(osc_user_country()!='') {
        $location_array[] = osc_user_country();
    }
    $location = implode(", ", $location_array);
    unset($location_array);

    bender_add_body_class('user-public-profile');
    osc_add_hook('after-main','sidebar');
    function sidebar(){
        osc_current_web_theme_path('user-public-sidebar.php');
    }

    osc_current_web_theme_path('header.php');
    $bender_profile_uid = osc_user_id();
?>
<div id="item-content">
    <div class="user-card">
        <?php if( bender_has_avatar($bender_profile_uid) ) { ?>
        <img src="<?php echo osc_esc_html(osc_user_avatar_url($bender_profile_uid, 'normal')); ?>" alt="<?php echo osc_esc_html(osc_user_name()); ?>" />
        <?php } else { ?>
        <?php bender_avatar_style(); ?>
        <span class="avatar-monogram avatar-monogram--lg"><?php echo osc_esc_html(bender_user_monogram(osc_user_name())); ?></span>
        <?php } ?>
        <ul id="user_data">
            <li class="name"><?php echo osc_user_name(); ?></li>
            <?php if( osc_user_website() !== '' ) { ?>
            <li class="website"><a href="<?php echo osc_user_website(); ?>"><?php echo osc_user_website(); ?></a></li>
            <?php } ?>
            <?php if( $address !== '' ) { ?>
            <li class="adress"><?php printf(__('<strong>Address:</strong> %1$s'), $address); ?></li>
            <?php } ?>
            <?php if( $location !== '' ) { ?>
            <li class="location"><?php printf(__('<strong>Location:</strong> %1$s'), $location); ?></li>
            <?php } ?>
        </ul>
    </div>
    <?php if( osc_user_info() !== '' ) { ?>
    <h2><?php _e('User description', 'bender'); ?></h2>
    <?php } ?>
    <?php echo nl2br(osc_user_info()); ?>
    <?php if( osc_count_items() > 0 ) { ?>
    <div class="similar_ads">
        <h2><?php _e('Latest listings', 'bender'); ?></h2>
        <?php osc_current_web_theme_path('loop.php'); ?>
        <div class="paginate"><?php echo osc_pagination_items(); ?></div>
        <div class="clear"></div>
    </div>
    <?php } ?>
</div>
<?php osc_current_web_theme_path('footer.php') ; ?>