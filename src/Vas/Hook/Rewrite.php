<?php
/**
 * @PSR-0: Vas\Hook\Rewrite
 *
 * (c) Pablo Adrian Samudia <p.a.samu@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * @author Pablo Samudia <p.a.samu@gmail.com>
 * 
 */

namespace Vas\Hook;
use Vas\Object;

class Rewrite extends Object
{
    public static function init()
    {   

        add_action(' admin_init', array( '\\Vas\\Hook\\Rewrite', 'cd_flush_rewrites') );
        add_filter( 'query_vars', array( '\\Vas\\Hook\\Rewrite', 'query_vars') );
        if (!is_multisite() && !is_child_theme()) {

            add_action( 'generate_rewrite_rules', array( '\\Vas\\Hook\\Rewrite', 'cd_add_rewrites') );


            if (!is_admin()) {
                $tags = array(
                    'plugins_url',
                    'bloginfo',
                    'stylesheet_directory_uri',
                    'template_directory_uri',
                    'script_loader_src',
                    'style_loader_src'
                );
                foreach($tags as $tag) {
                    add_filter( $tag, array( '\\Vas\\Hook\\Rewrite', 'cd_clean_urls') );
                }
            }
        }

    }

    public static function cd_clean_urls($content) {
        $theme_name = next(explode('/themes/', get_stylesheet_directory()));  

        if (strpos($content, '/wp-content/plugins') === 0) {
            return str_replace('/wp-content/plugins', wp_base_dir() . '/plugins', $content);
        } else {
            return str_replace('/wp-content/themes/' . $theme_name, '', $content);
        }
    }

    public static function cd_add_rewrites($content)
    {
        global $wp_rewrite;

        $theme_name = next(explode('/themes/', get_stylesheet_directory()));  
        $cd_new_non_wp_rules = array(
            'css/(.*)'      => 'wp-content/themes/' . $theme_name . '/asset/css/$1',
            'js/(.*)'       => 'wp-content/themes/' . $theme_name . '/asset/js/$1',
            'img/(.*)'      => 'wp-content/themes/' . $theme_name . '/asset/img/$1',
            'font/(.*)'     => 'wp-content/themes/' . $theme_name . '/asset/font/$1',
            'fonts/(.*)'    => 'wp-content/themes/' . $theme_name . '/asset/fonts/$1'
        );

        $wp_rewrite->non_wp_rules = array_merge($wp_rewrite->non_wp_rules, $cd_new_non_wp_rules);

        return $content;
    }


    public function extra_urls()
    {
        add_rewrite_rule(
            'endermolaser/?.?',
            'index.php?section=servicios',
            'top' 
        );
    }


    public static function query_vars($public_query_vars) {
        $public_query_vars[] = "portfolio_item";

        return $public_query_vars;
    }

    public static function cd_flush_rewrites()
    {
        global $wp_rewrite;
        $wp_rewrite->flush_rules();
    }
}
