<?php
/*
Plugin Name: Crafted Software Standard Slider Plugin
Plugin URI: http://www.craftedsoftware.co.za/
Version: v1.00
Author: Craig Williams
Description: A simple slider plugin
 */

/*
Standard Slider (Wordpress Plugin)
Copyright (C) 2014 Craig Williams
Contact me at http://www.craftedsoftware.co.za

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program. If not, see <http://www.gnu.org/licenses/>.
*/


if (!class_exists('CraftedSoftwareStandardSlider')) {
    class CraftedSoftwareStandardSlider
    {

        public function __construct()
        {
            add_shortcode('crafted-software-standard-slider', array($this, 'slider_handler'));

            add_action('wp_enqueue_scripts', array($this, 'style'));
            add_action('wp_enqueue_scripts', array($this, 'scripts'));

            add_action('admin_enqueue_scripts', array($this, 'admin_scripts'));

            add_action('admin_menu', array($this, 'standard_slider_menu_pages'));

            add_filter('flash_uploader', create_function('$a', 'return false;'), 5); //disable the Flash uploader
            add_filter('media_upload_tabs', create_function('$a', 'return array(\'type\' => __(\'From Computer\'), \'gallery\' => __(\'Gallery\'));'));

        }


        public static function activate()
        {

        }


        public static function deactivate()
        {

        }

        function slider_handler($attributes)
        {

            $a = shortcode_atts(array(
                'id' => '-1',
                'style' => 'slider'
            ), $attributes);

            $output = "<div class=\"standard_slider_" . $a['style'] . " slider" . $a['id'] . " StandardSlider\">";
            $output .= "<ul class=\"slider-holder\">";

            $post = get_post($a['id']);

            if ($post != null) {
                $images =& get_children(array(
                    'post_parent' => $post->ID,
                    'post_type' => 'attachment',
                    'post_mime_type' => 'image'
                ));

                if (!empty($images)) {
                    $count = 1;
                    foreach ($images as $attachment_id => $attachment) {
                        $output .= "<li id=\"no" . $count . "e\" class=\"standard_slider_image\">";
                        $output .= wp_get_attachment_image($attachment_id, 'full');
                        $output .= "</li>";
                        $count += 1;
                    }
                }
                $output .= "</ul>";
                $output .= "<ul class=\"slider-nav\">";
                if (!empty($images)) {
                    $count = 1;
                    foreach ($images as $attachment_id => $attachment) {
                        $title = esc_html($attachment->post_title, 1);
                        $output .= "<li><a href=\"#no" . $count . "e\"><i class=\"fa fa-caret-left slider-arrow-left ";
                        if ($count == 1) {
                            $output .= "active_arrow";
                        }
                        $output .= "\"></i>" . $title . "</a></li>";
                        $count += 1;
                    }
                }
                $output .= "</ul>";
            }

            $output .= "<div class=\"slider_shadow\">&nbsp;</div>";
            $output .= "</div>";

            return $output;
        }

        function style()
        {
            wp_register_style('standard-slider-style', plugins_url('/css/standard_slider_style.css', __FILE__), array(), '20140616', 'all');
            wp_enqueue_style('standard-slider-style');
            wp_enqueue_style('prefix-font-awesome', '//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.min.css', array(), '4.0.3');


        }

        function scripts()
        {
            wp_deregister_script('jquery');
            wp_register_script('jquery', 'http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js', array(), null, false);
            wp_enqueue_script('jquery-ui', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.2/jquery-ui.min.js', array('jquery'), '1.10.2');
            wp_register_script('slider-easing-script', plugins_url('/js/jquery.easing.1.3.js', __FILE__), array('jquery'));
            wp_enqueue_script('slider-easing-script');
            wp_register_script('standard-slider-jquery-script', plugins_url('/js/standard_slider_script.js', __FILE__), array('jquery'));
            wp_enqueue_script('standard-slider-jquery-script');
            wp_register_script('standard-slider-script', plugins_url('/js/slider.js', __FILE__), array('jquery'));
            wp_enqueue_script('standard-slider-script');
        }

        function admin_scripts()
        {
            wp_enqueue_script('media-upload');
            wp_enqueue_script('thickbox');
            wp_register_script('standard_slider_admin_script', WP_PLUGIN_URL . '/standard_slider/admin/js/standard_slider_admin.js', array('jquery', 'media-upload', 'thickbox'));
            wp_enqueue_script('standard_slider_admin_script');
            wp_enqueue_style('thickbox'); // call to media files in wp

            wp_register_style('standard-slider-admin-style', plugins_url('/admin/css/standard_slider_admin_style.css', __FILE__), array(), '20140616', 'all');
            wp_enqueue_style('standard-slider-admin-style');
        }

        function standard_slider_menu_pages()
        {
            $page_title = 'Standard Slider Admin';
            $menu_title = 'Standard Slider';
            $capability = 'manage_options';
            $menu_slug = 'standard-slider-admin-menu';
            $function = 'standard_slider_settings';
            add_menu_page($page_title, $menu_title, $capability, $menu_slug, array($this, $function));

            $sub_menu_title = 'Settings';
            add_submenu_page($menu_slug, $page_title, $sub_menu_title, $capability, $menu_slug, array($this, $function));

            $submenu_page_title = 'Standard Slider Help';
            $submenu_title = 'Help';
            $submenu_slug = 'standard-slider-help';
            $submenu_function = 'standard_slider_help';
            add_submenu_page($menu_slug, $submenu_page_title, $submenu_title, $capability, $submenu_slug, array($this, $submenu_function));
        }

        function standard_slider_settings()
        {
            if (!current_user_can('manage_options')) {
                wp_die('You do not have sufficient permissions to access this page.');
            }
            include('admin/standard_slider_admin.php');

        }

        function standard_slider_help()
        {
            if (!current_user_can('manage_options')) {
                wp_die('You do not have sufficient permissions to access this page.');
            }

            include('admin/standard_slider_help.php');
        }


    }
}

new CraftedSoftwareStandardSlider;









