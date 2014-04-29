<?php
/**
 * @PSR-0: Vas\Hook\Images
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
use Vas\Collection\Config;

class Images
{
    public static function init() {
        $config = Config::getInstance();
        
        $sizes = $config->thumbnail_size;
        if ( is_array ( $sizes ) ) {
            foreach ( $sizes AS $thumb_size ) {
                list($name, $width, $height, $crop) = $thumb_size;
                add_image_size( $name, $width, $height, $crop );
            }
        }
    }
}
