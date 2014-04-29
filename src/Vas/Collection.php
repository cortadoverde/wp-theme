<?php
/**
 * @PSR-0: Vas\Collection
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

class Collection extends Object
{
    
   
    public $data = array();

    public function std_set( $k, $v )
    {
        $this->data[$k] = $v;
    }

    public function std_get( $k ) 
    {
        if( !isset ( $this->data[$k] ) ) {
            return false;
        }
        return $this->data[$k];
    }

    public function __set( $k, $v )
    {
        $this->std_set( $k, $v);
    }

    


    public function __get( $k ) 
    {
        return $this->std_get( $k );
    }

    
}
