<?php
/**
 * @PSR-0: Vas\Model\Servicio\Servicio
 *
 * (c) Pablo Adrian Samudia <p.a.samu@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * @author Pablo Samudia <p.a.samu@gmail.com>
 * 
 */

namespace Vas\Model;

class Servicio extends \Vas\Model\Post
{
    private static $type = 'vas_servicios';

    public static function find( $extdend = array() )
    {
        $results = array();

        $args = array_merge(
                    array( 
                        'post_type' => self::$type, 
                        'orderby'   => 'title', 
                        'order'     => 'ASC' 
                    ), 
                    $extdend 
                );

        $loop = new \WP_Query( $args );
        
        while ( $loop->have_posts() ) : $loop->the_post();
            $data = new self( $loop->post );
            $results[] = $data->ajax();
        endwhile;
        
        return $results;
    }

    
    public function ajax()
    {
        return array(
            'Titulo' => $this->post->post_title,
            'Descripcion' => $this->post->post_content,
            'Thumbnail' => isset ( $this->attachments['main_image']['original'] ) ? $this->attachments['main_image']['original'][0] : null
        );
    }
}
