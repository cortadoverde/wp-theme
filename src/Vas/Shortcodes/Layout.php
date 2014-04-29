<?php
/**
 * @PSR-0: Vas\Shortcodes\Layout
 *
 * (c) Pablo Adrian Samudia <p.a.samu@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * @author Pablo Samudia <p.a.samu@gmail.com>
 * 
 */

namespace Vas\Shortcodes;

class Layout
{

    protected static $options = array (
        'column_size' => 12
    );

    public static function init()
    {
        add_shortcode('column', array('\\Vas\Shortcodes\\Layout', 'column') );
        add_shortcode('row', array('\\Vas\Shortcodes\\Layout', 'row') );
    }

    /**
     * Crear una columna dentro del contenido.
     * ejemplo
     * 
     * [col size=6] contenido [/col]
     * 
     * @param  [type] $attr    [description]
     * @param  [type] $content [description]
     * @return [type]          [description]
     */
    public static function column( $attr, $content )
    {
        $options = array_merge( array('size' => self::$options['column_size'] ), $attr );

        return '<div class="' . self::_class_size( $attr['size'] ) .'">'. do_shortcode($content) .'</div>';
       
    } 

    public static function row( $attr, $content )
    {
        return '<div class="row"> ' . do_shortcode($content) . '</div>';
    }

    /**
     * Helper, en caso de que se defina el nombre de la clase
     * en el shortcode, para evitar inconsistencias
     * @param  [type] $size [description]
     * @return [type]       [description]
     */
    private static function _class_size( $size ) 
    {
        if ( is_numeric( $size ) ) {
            return 'col-'. $size;
        }
        return $size;
    }
    
}
