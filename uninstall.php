<?php

if ( !defined( 'WP_UNINSTALL_PLUGIN' ) ) exit();

delete_option( 'social_media_sharing_twitter_handle' );

delete_option( 'social_media_sharing_default_description' );

delete_option( 'social_media_sharing_default_image_id' );