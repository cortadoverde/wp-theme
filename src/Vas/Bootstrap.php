<?php
/**
 * @PSR-0: Vas\Bootstrap
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

class Bootstrap extends Object
{
    public static function _init()
    {
        
        Request::getInstance();

        Hook::init();

        // Config

        $Config = Collection\Config::getInstance();

        $Config->system = 'Wp';

        $Config->paths = array(
            'base'      => get_home_url(),
            'theme_dir' => get_stylesheet_directory(),
            'css'       => get_home_url() . '/css',
            'js'        => get_home_url() . '/js', 
        );

        $Config->thumbnail_size = array(
            array('vas_thumb_smallest', '200', '200', true),
            array('vas_thumb_small', '217', '217', true),
            array('vas_thumb_small_1', '232', '232', true),
            array('vas_thumb_357', '357', 0, false),
            array('vas_thumb_242', '242', 0, false)
        );


        // Set Global Data transport
        $Transport = Object::getInstance();
        $Transport->engine = new \Mustache_Engine(array(
            'loader'            => new \Mustache_Loader_FilesystemLoader( \Vas\Collection\Config::getInstance()->paths['theme_dir'] . '/asset/templates' ),
            'partials_loader'   => Adapter\AliasLoader::getInstance( __DIR__ . '/../../asset/templates', array() )
        ));
    }
}
