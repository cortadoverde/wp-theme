<?php
/**
 * @PSR-0: Vas\File
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

class File
{
    public static function partial( $name, $recursive = true )
    {

        $tpl = \Vas\Object::getInstance()->engine->getLoader()->load($name);
        $tpl = preg_replace_callback('#{{>\s?([\w\\\\/]+)\s?}}#', array('\\Vas\\File', 'replace_callback'), $tpl);
        // $path = \Vas\Collection\Config::getInstance()->paths['theme_dir'] . '/asset/templates';
        // $filename = $path . '/' . $name . '.mustache';
        return $tpl;

    }

    public static function replace_callback( $p )
    {
        try {
            $tpl = \Vas\Object::getInstance()->engine->getLoader()->load($p[1]);
            $tpl = preg_replace_callback('#{{>\s?([\w\\\\/]+)\s?}}#', array('\\Vas\\File', 'replace_callback'), $tpl);

        }catch (\Exception $e) {
            return 'Error al cargar el template ( ' . $p[0] . ')';
        }
        
        return $tpl;
    }


    public static function getFile( $filename )
    {
        if( file_exists( $filename ) ) {
            return file_get_contents( $filename );
        }
    }
}
