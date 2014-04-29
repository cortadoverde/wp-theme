<?php
/**
 * @PSR-0: Vas\Controller\Page  
 *
 * (c) Pablo Adrian Samudia <p.a.samu@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * @author Pablo Samudia <p.a.samu@gmail.com>
 * 
 */

namespace Vas\Controller;

class Page extends Controller
{
   public $alias;
    
   public function __construct()
   {
        global $wp_query;


        if( $wp_query->is_search() ) {
           $this->alias['Content'] = 'Search/Content';
        }

        if( $wp_query->is_single() ) {
            $this->alias['Article/Item'] = 'Article/Item/Full';
        }
   }
}
