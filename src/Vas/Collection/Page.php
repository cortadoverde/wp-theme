<?php
/**
 * @PSR-0: Vas\Collection\Page
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

class Page extends \Vas\Collection
{

  public $Config;

  public $base;

  public function __construct()
  {

    $this->Config = Config::getInstance(); 
    
  }      


}
