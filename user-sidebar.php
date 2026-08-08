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
<div class="actions">
  <a href="#" data-bclass-toggle="display-filters" class="resp-toggle show-filters-btn"><?php _e('Display menu','bender'); ?></a>
</div>
<div id="sidebar">
    <?php if( bender_has_avatar() ) { bender_avatar_style(); ?>
    <div class="account-avatar"><img class="avatar-thumb" src="<?php echo osc_esc_html(osc_user_avatar_url( osc_logged_user_id(), 'thumbnail' )); ?>" alt="" /></div>
    <?php } ?>
    <?php echo osc_private_user_menu( get_user_menu() ); ?>
</div>
<div id="dialog-delete-account" title="<?php echo osc_esc_html(__('Delete account', 'bender')); ?>">
<?php _e('Are you sure you want to delete your account?', 'bender'); ?>
</div>