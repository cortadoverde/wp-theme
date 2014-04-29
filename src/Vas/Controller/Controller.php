<?php
/**
 * @PSR-0: Vas\Controller\Controller
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

class Controller extends \Vas\Object
{       

    public $data;

    public static function _capture( $callback, $args = false, $print = false )
    {
        if ( !is_callable( $callback ) ) return;
        ob_start();
            if ( $args ) {
                if( $print ) {
                    echo call_user_func_array ( $callback, $args );
                } else {
                    call_user_func_array ( $callback, $args );
                }
            } else {
                if( $print ) {
                    echo call_user_func( $callback );
                } else {
                    call_user_func( $callback );
                } 
            }
        $buffer = ob_get_clean();
        return $buffer;
    }

    public function sidebar( )
    {
        return function ( $content, $m ) 
        {
            $content = trim ( $content );
            return \Vas\Controller\Controller::_capture( 'dynamic_sidebar', array( $content ) );
        };
    }

    public function widget(){

        return function($content, $m) {
            $content = trim($content);
            return \Vas\Controller\Controller::_capture( 'the_widget', array( $content ) );
        };

    }

    public function mainMenu()
    {
        $content = array( 'theme_location' => 'primary' );
        return \Vas\Controller\Controller::_capture( 'wp_nav_menu', array( $content ) );  
    }

    public function addAlias( $name , $value = false )
    {
        if( is_array( $name ) ) {
            \Vas\Adapter\AliasLoader::getInstance()->setAliases( $name, $value );
            return;
        }

        \Vas\Adapter\AliasLoader::getInstance()->set($name, $value);
    }

    public function set( $k, $v ) {
        return \Vas\Object::getInstance()->engine->addHelper( $k, $v);
    }


    public function render( $layout = 'Layout/Default')
    {
        if( isset( $this->alias ) ) {
            $this->addAlias( $this->alias );
        }
        return \Vas\Object::getInstance()->engine->render($layout, $this);

    }

    public function title()
    {
        return function(){
            $title = '';
            if(is_home()) { 
                $title .= \Vas\Controller\Controller::_capture('bloginfo', array("name")) . " - " 
                        . \Vas\Controller\Controller::_capture('bloginfo', array("description")); 
            } else { 
                $title .= wp_title(" - ", false, right)
                . \Vas\Controller\Controller::_capture('bloginfo', array("name") ); 
            } 



            return $title;
        };
    }

    public function  pagination() 
    {
        return function(){
            global $wp_query, $wp_rewrite;

            if( \Vas\Collection\Config::getInstance()->hidde_paginate ) {
                return '';
            }

            if( $wp_query->is_single() ) {
                return '';
            }
            
            $pages = '';
            
            $max = $wp_query->max_num_pages;
            
            if (!$current = get_query_var('paged')) $current = 1;
            $a['base'] = str_replace(999999999, '%#%', get_pagenum_link(999999999));
            $a['total'] = $max;
            $a['current'] = $current;

            $total = 1; //1 – muestra el texto "Página N de N", 0 – para no mostrar nada
            $a['mid_size'] = 5; //cuantos enlaces a mostrar a izquierda y derecha del actual
            $a['end_size'] = 1; //cuantos enlaces mostrar al comienzo y al fin
            $a['prev_text'] = '&laquo; Anterior'; //texto para el enlace "Página siguiente"
            $a['next_text'] = 'Siguiente &raquo;'; //texto para el enlace "Página anterior"


            ob_start();

            if ($max > 1) echo '<div class="navigation">';
           // if ($total == 1 && $max > 1) $pages = '<span class="pages">Página ' . $current . ' de ' . $max . '</span>'."\r\n";
            echo $pages . paginate_links($a);
            if ($max > 1) echo '</div>';

            return ob_get_clean();
        };
        
    }
}

