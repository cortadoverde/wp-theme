<?php
/**
 * @PSR-0: Vas\Hook
 *
 * (c) Pablo Adrian Samudia <p.a.samu@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * @author Pablo Samudia <p.a.samu@gmail.com>
 * 
 */

namespace Vas;

class Hook extends Object
{
    public static function init()
    {

        add_action('init', array('Vas\\Hook', 'on_init') );
        add_action('setup_theme', array('Vas\\Hook', 'on_setup_theme') );
        add_action('after_setup_theme', array('\\Vas\\Hook', 'on_after_setup_theme'));
        add_action( 'after_switch_theme', array('Vas\\Hook', 'on_switch') );
    }

    public function on_init()
    {

        Hook\Ajax::init();
        Hook\Rewrite::init();
        Hook\Rewrite::extra_urls();
        Hook\Images::init();
        Hook\ThemeSupport::init();
        Hook\Admin::init();
        Hook\ThemeSupport::register_post_types();

    }

    public function on_after_setup_theme()
    {
        add_editor_style();
    }

    public function on_setup_theme()
    {
       
    }

    public static function pages_rewrite_flush() {
        rglp_cpt();

        flush_rewrite_rules();
    }

    public static function on_switch()
    {
        flush_rewrite_rules();
    }
}
