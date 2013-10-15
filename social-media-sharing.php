<?php
/**
 * Plugin Name: Social Media Sharing
 * Plugin URI: http://about.me/joverright
 * Description: Adds Facebook OpenGraph and Twitter Card support to your website.
 * Version: 0.1 beta
 * Author: Jesse Overright
 * Author URI: http://about.me/joverright
 * License: GPL2
 */

/*  Copyright 2013  Jesse Overright  (email : jesseoverright@gmail.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

define('TWITTER_HANDLE', '@YOUR_TWITTER_HANDLE');
define('DEFAULT_SOCIAL_MEDIA_DESCRIPTION', 'DEFAULT DESCRIPTION');
define('DEFAULT_SOCIAL_MEDIA_IMAGE', get_bloginfo('stylesheet_directory').'/images/DEFAULT_IMAGE.jpg' );

add_image_size( 'facebook-thumbnail', 300, 300);

function get_facebook_opengraph() {
    global $post;
    if (has_post_thumbnail($post->ID)) { 
        $thumbnail = simplexml_load_string(get_the_post_thumbnail($post->ID,'facebook-thumbnail'));
        $thumbnail_url = $thumbnail->attributes()->src;
    ?>
    <?php } else { ?>
    <meta property="og:image" content="<?php echo DEFAULT_SOCIAL_MEDIA_IMAGE;  ?>" />
    <?php } ?>
    <meta property="og:url" content="<?php echo get_permalink(); ?>" />
    <meta property="og:site_name" content="<?php echo site_url(); ?>" />
    <meta property="og:type" content="article" />
    <meta property="og:title" content="<?php echo get_the_title(); ?>" />
    <?php if (is_single() || is_page()) { ?>
    <meta property="og:description" content="<?php echo get_socialmedia_excerpt(35); ?>" />
    <?php } else { ?>
    <meta property="og:description" content="<?php echo DEFAULT_SOCIAL_MEDIA_DESCRIPTION ?>" />
    <?php }
}

function get_twitter_card_meta() { ?>
    <meta name="twitter:card" content="summary" />
    <meta name="twitter:site" content="<?php echo TWITTER_HANDLE ?>" />
    <meta name="twitter:creator" content="<?php echo TWITTER_HANDLE ?>" />
    <meta name="twitter:url" content="<?php echo get_permalink(); ?>" />
    <meta name="twitter:title" content="<?php echo get_the_title(); ?>" />
    <?php if (is_single() || is_page()) { ?>
    <meta name="twitter:description" content="<?php echo get_socialmedia_excerpt(25); ?>" />
    <?php } else { ?>
    <meta name="twitter:description" content="<?php echo DEFAULT_SOCIAL_MEDIA_DESCRIPTION ?>" />  
    <?php }
}

function get_socialmedia_excerpt($excerpt_length = '20'){
    global $post;
    $the_excerpt = $post->post_content; //Gets post_content to be used as a basis for the excerpt
    $the_excerpt = strip_tags(strip_shortcodes($the_excerpt)); //Strips tags and images
    $words = explode(' ', $the_excerpt, $excerpt_length + 1);
    if(count($words) > $excerpt_length) :
        array_pop($words);
        array_push($words, '…');
        $the_excerpt = implode(' ', $words);
    endif;
    // remove any quotes
    $the_excerpt = str_replace('"','',$the_excerpt);
    if ($the_excerpt == '') $the_excerpt = "DEFAULT MESSAGE"; 
    return $the_excerpt;
}

add_action( 'wp_head', 'social_media_sharing_head_action');

function social_media_sharing_head_action() {
    global $post;

    get_facebook_opengraph();
    get_twitter_card_meta();
}