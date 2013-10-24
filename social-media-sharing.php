<?php
/**
 * Plugin Name: Social Media Sharing
 * Plugin URI: http://about.me/joverright
 * Description: Adds Facebook OpenGraph and Twitter Card support to your website.
 * Version: 0.5 beta
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

if ( !class_exists('Social_Media_Sharing') ) :

class Social_Media_Sharing {
    private static $instance;

    # enforce singleton design pattern
    public static function get_instance() {
        if ( !isset( self::$instance ) ) {
            $class = __CLASS__;
            self::$instance = new $class();
        }

        return self::$instance;
    }

    protected function __construct() {
        # define defaults
        define('TWITTER_HANDLE', '@YOUR_TWITTER_HANDLE');
        define('DEFAULT_SOCIAL_MEDIA_DESCRIPTION', 'DEFAULT DESCRIPTION');
        define('DEFAULT_SOCIAL_MEDIA_IMAGE', get_bloginfo('stylesheet_directory').'/images/DEFAULT_IMAGE.jpg' );

        # set up facebook thumbnail size
        add_image_size( 'facebook-thumbnail', 300, 300);

        # initialize the social media sharing display
        add_action( 'wp_head', array( $this, 'social_media_sharing_init') );
    }

    public function social_media_sharing_init() {
        global $post;

        if ( is_single() || is_page()) {
            if ( has_post_thumbnail($post->ID) ) { 
                $thumbnail = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID,'facebook-thumbnail') );
                $og_image = $thumbnail[0];
            } else {
                $og_image = DEFAULT_SOCIAL_MEDIA_IMAGE;
            }

            $settings =  array (
                'url'   => get_permalink(),
                'title' => wp_title( '|', false, 'right'),
                'image' => $og_image,
            );
        } else {
            $settings = array (
                'url'   => $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'],
                'title' => wp_title( '|', false, 'right'),
                'image' => DEFAULT_SOCIAL_MEDIA_IMAGE,
            );
        }

        $this->get_facebook_opengraph($settings);
        $this->get_twitter_card_meta($settings);
    }

    protected function get_facebook_opengraph($settings) { ?>
        <meta property="og:image" content="<?= $settings['image'] ?>" />
        <meta property="og:url" content="<?= $settings['url'] ?>" />
        <meta property="og:site_name" content="<?= site_url() ?>" />
        <meta property="og:type" content="article" />
        <meta property="og:title" content="<?= $settings['title'] ?>" />
        <meta property="og:description" content="<?= $this->get_socialmedia_excerpt(35) ?>" />
        <?php
    }

    protected function get_twitter_card_meta($settings) { ?>
        <meta name="twitter:card" content="summary" />
        <meta name="twitter:site" content="<?= TWITTER_HANDLE ?>" />
        <meta name="twitter:creator" content="<?= TWITTER_HANDLE ?>" />
        <meta name="twitter:url" content="<?= $settings['url'] ?>" />
        <meta name="twitter:title" content="<?= $settings['title'] ?>" />
        <meta name="twitter:description" content="<?= $this->get_socialmedia_excerpt(25) ?>" />
        <?php
    }

    protected function get_socialmedia_excerpt($excerpt_length = '20'){
        if ( is_single() || is_page() ) {
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
        }
        
        if ($the_excerpt == '') $the_excerpt = DEFAULT_SOCIAL_MEDIA_DESCRIPTION; 
        return $the_excerpt;
    }
}

Social_Media_Sharing::get_instance();

endif;