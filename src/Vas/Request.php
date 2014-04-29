<?php
/**
 * @PSR-0: Vas\Request
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

class Request extends Object
{

    protected $_server;

    public $base;

    public function on_construct( $server = false )
    {
        $this->_server = ( $server === false ) ? $_SERVER : $server;
        $this->base = get_home_url();
    }

    public function base()
    {
        return $this->base;
    }

    public function theme()
    {
       return get_bloginfo('template_url');
    }

}
