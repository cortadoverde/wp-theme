<?php
/**
 * @PSR-0: Vas\Collection\Config
 *
 * (c) Pablo Adrian Samudia <p.a.samu@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * @author Pablo Samudia <p.a.samu@gmail.com>
 * 
 */

namespace Vas\Collection;

class Config extends \Vas\Collection
{
    public function find( $index ) {
        return $this->std_get($index);
    }    
}
