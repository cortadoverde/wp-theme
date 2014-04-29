<?php
/**
 * @PSR-0: Vas\Model\Resultado
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

class Resultado extends Post
{
    private static $type = 'vas_resultados';

    public static function find()
    {
        $results = array();

        $args = array( 
                    'post_type' => self::$type, 
                    'orderby'   => 'title', 
                    'order'     => 'ASC' 
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
            'Thumbnail' => isset ( $this->attachments['main_image']['original'] ) ? $this->attachments['main_image']['original'][0] : null,
            'Link'      => $this->post->permalink
        );
    }
}
