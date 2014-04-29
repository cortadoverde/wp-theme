<?php
/**
 * @PSR-0: Vas\Object
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

/**
 * Un Objeto representa un punto en comun entre todos los demas objetos,
 * permite mantener un acceso global durante toda la herencia
 */
class Object
{
    public static function getInstance()
    {
        static $instance = null;
        
        if (null === $instance) {
            $instance = new static();
            if( method_exists( $instance, 'on_construct' ) ) {
                $data = func_get_args();
                call_user_func_array( array( $instance, 'on_construct'), $data );
            }
        }

        return $instance;
    }


    public function debug()
    {
        ob_start();
        debug_backtrace();
        $trace = ob_get_clean();
        ob_end_flush();

        $data = '<pre>';
        $data .= print_r($this,true) ."\n\n" ;
        $data .= $trace;
        $data .= '</pre>';
        return $data;
    }
}
