<?php
/**
 * @PSR-0: Vas\Hook\ThemeSuport
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

class ThemeSupport
{
    public static function init()
    {
        add_theme_support( 'post-formats',
            array(
                'aside',             // title less blurb
                'gallery',           // gallery of images
                'link',              // quick link to other site
                'image',             // an image
                'quote',             // a quick quote
                'status',            // a Facebook like status update
                'video',             // video
                'audio',             // audio
                'chat'               // chat transcript
            )
        );

        add_theme_support( 'post-thumbnails' );
        add_theme_support( 'menus' );
        add_theme_support( 'automatic-feed-links' );

        
        remove_filter('the_content', 'wpautop');
        remove_filter('the_content', 'wptexturize');

        register_nav_menu( 'primary', __( 'Primary Menu' ) );

        \Vas\Shortcodes\Layout::init();

        self::setting_editor();
    }

    public function setting_editor()
    {
        
        add_filter('mce_buttons_2', array('\\Vas\\Hook\\ThemeSupport', 'mce_buttons_2') );
        add_filter('tiny_mce_before_init', array('\\Vas\\Hook\\ThemeSupport', 'style_formats') );
    }

    public static function mce_buttons_2($buttons)
    {
        array_unshift($buttons, 'styleselect');
        return $buttons;
    }

    public function style_formats($settings)
    {
       $settings['theme_advanced_blockformats'] = 'p,address,blockquote,h1,h2,h3,h4,h5,h6';

        $style_formats = array(
            array('title' => 'Precio', 'inline' => 'strong', 'classes' => 'price')
        );

        $settings['style_formats'] = json_encode( $style_formats );
        return $settings;
    }


    public static function register_post_types()
    {
        register_post_type( 'vas_servicios',
            array(
                'labels' => array(
                    'name' => __( 'Servicios' ),
                    'singular_name' => __( 'Servicio' )
                ),
                'show_in_menu'       => 'edit.php',
                'rewrite'            => false,
                'query_var'          => false,
                'publicly_queryable' => false,
                'public'             => true,
                'exclude_from_searc' => true,
                'has_archive'        => false,
                'supports' => array(
                    'title',
                    'thumbnail',
                    'editor'
                )
            )
        );

        register_post_type( 'vas_contacto',
            array(
                'labels' => array(
                    'name' => __( 'Contacto' ),
                    'singular_name' => __( 'Contacto' )
                ),
                'show_in_menu'       => 'edit.php',
                'rewrite'            => false,
                'query_var'          => false,
                'publicly_queryable' => false,
                'public'             => true,
                'exclude_from_searc' => true,
                'has_archive'        => false,
            )
        );

        register_post_type( 'vas_mensajes',
            array(
                'labels' => array(
                    'name' => __( 'Mensajes' ),
                    'singular_name' => __( 'Mensaje' )
                ),
                'show_in_menu'       => 'edit.php',
                'rewrite'            => false,
                'query_var'          => false,
                'publicly_queryable' => false,
                'public'             => true,
                'exclude_from_searc' => true,
                'has_archive'        => false,
            )
        );

        register_post_type( 'vas_resultados', array(
            'labels' => array(
                'name'          => __('Resultados'),
                'singular_name' => __('Resultado')
            ),

            'show_in_menu'       => 'edit.php',
                'rewrite'            => array('slug'=>'resultados'),
                'query_var'          => true,
                'publicly_queryable' => true,
                'public'             => true,
                'exclude_from_searc' => true,
                'has_archive'        => false,

            'supports' => array(
                'title',
                'editor',
                'thumbnail',
                'excerpt'
            )
        ) );

        self::contact_settings();
    }

    public static function contact_settings()
    {
       add_filter( 'manage_edit-vas_contacto_columns', array('\\Vas\\Hook\\ThemeSupport', 'vas_contacto_columns') ) ;
       add_filter( 'manage_edit-vas_mensajes_columns', array('\\Vas\\Hook\\ThemeSupport', 'vas_mensajes_columns') ) ;
       add_action( 'manage_posts_custom_column', array('\\Vas\\Hook\\ThemeSupport', 'vas_contacto_show_column'), 10, 2 ) ;
    }

    public static function vas_contacto_columns( $columns )
    {
        $columns = array(
            'Nombre' => __( 'Nombre' ),
            'Ciudad' => __( 'Ciudad' ),
            'Correo' => __( 'Correo' ),
            'Celular' => __( 'Celular' )
        );

        return $columns;
    }

    public static function vas_mensajes_columns( $columns )
    {
        $columns = array(
            'Nombre' => __( 'Nombre' ),
            'Correo' => __( 'Correo' ),
            'Mensaje' => __( 'Mensaje' )
        );

        return $columns;
    }

    public static function vas_contacto_show_column( $column, $post_id )
    {
        switch ($column) {
            case 'Nombre':
            case 'Ciudad':
            case 'Correo':
            case 'Celular':
            case 'Mensaje':
                echo get_post_meta( $post_id, $column, true );
                break;
            
            default:
                break;
        }
    }


}
