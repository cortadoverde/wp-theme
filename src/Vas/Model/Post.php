<?php

/**
 * @PSR-0: Vas\Model\Post
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

class Post
{
    /**
     * Id del post
     * @var int
     */
    public $id;

    /**
     * Archivos adjuntos, thumbnail, galerias, etc
     * @var mixed
     */
    public $attachments;

    /**
     * Taxonomias de wp asignadas al post
     * @var mixed|array
     */
    public $taxonomies;

    /**
     * Tags de wp asignadas al post
     * @var array|null
     */
    public $tags;

    /**
     * Objeto WP_Post contiene los datos del post   
     * 
     * @see  WP_POST
     * @var [type]
     */
    public $post;

    public $custom_fields;

    /**
     * Crea y setea el objeto para poder utilizarlo
     * con todas los datos ya capturados, permitiendo utilizar
     * una logica de template mas abstracta
     *  
     * @param int|WP_Post $post el contructor puede recibir tanto un id, como un objeto obtenido previamente por get_post
     */
    public function __construct( $post )
    {
        global $more;    
        $more = 0;       

        if( is_object( $post ) ) {
            $this->id = absint( $post->ID );
            $this->post = $post;
        } else {
            $this->id = absint( $post );
            $this->post = get_post( $this->id );
        }

        $this->post->post_content = apply_filters('the_content', $this->post->post_content, 'ver más');
        $this->post->post_excerpt = get_the_excerpt( $this->id );
        $this->post->more = $this->_get_content();


        // Agregar el tipo de formato para el tipo de vista que va a tener en el template
        $this->post->post_format = $this->getFormat();

        $this->post->permalink = get_permalink( $this->id );
        
        // Obtiene las taxonomias del tipo de post (post, page, etc)
        $this->getTaxonomies($this->post->post_type);

        // Obtener las imagenes
        $this->getAttachments();

        $this->getTags();

        $this->getCategories();

        // $this->Page = Luxor_Controller_Component_Page::getInstance();
        // $this->Embed = new Luxor_Controller_Component_Embed;

        //$this->dbg = print_r($this,true);
    }

    private function _get_content()
    {
        ob_start();
        the_content('ver más');
        $content = ob_get_clean();
        return $content;
    }

    public function getFormat( )
    {
        return get_post_format( $this->id );
    }

    public function getPost()
    {
        return $this->post;
    }

    public function getTags()
    {
        $this->tags = wp_get_post_tags( $this->id );
    }

    public function getCategories()
    {
        $categories = get_the_category( $this->id );
        $this->categories = array();

        foreach( $categories AS $category ) {
             $this->categories[] = array(
                'name' => $category->name,
                'slug' => $category->slug,
                'link' => get_category_link( $category->term_id ),
                'count' => $category->category_count
            );
        }
        
    }

    public function has_tags()
    {
        return ( !empty ( $this->tags ) );
    }

    public function getTaxonomies( $type ) 
    {
        $taxonomies = get_taxonomies(array(
             'object_type' => array($type),
          'public'   => true,
          '_builtin' => false
        ), 'objects');
        
        foreach( $taxonomies AS $taxonomy_id => $taxonomy ) {
            $tx_key = $taxonomy_id;
            

            $terms = get_the_terms( $this->post->ID, $taxonomy_id );

            if( ! ( empty( $terms ) ) ) {

                $this->taxonomies[$tx_key] = array();
                
                $taxonomy_terms = array();
                foreach( $terms AS $term ) {
                    $taxonomy_terms[] = $term;
                }

                $this->taxonomies[$tx_key] = $taxonomy_terms;
            }
        }
    }

    public function getAttachments()
    {
        global $_wp_additional_image_sizes; 
        
        if ( has_post_thumbnail( $this->id ) ) {

            $thumbnail_id = get_post_thumbnail_id( $this->id );

            $this->attachments['main_image'] = array(
                'original' => wp_get_attachment_image_src( $thumbnail_id, 'full' )
            );
            
            // Buscamos los estilos adicionales
            foreach ( $_wp_additional_image_sizes AS $_thumbnail_name => $_thumbnail_format ) {
                $this->attachments['main_image'][$_thumbnail_name] = wp_get_attachment_image_src( $thumbnail_id, $_thumbnail_name );
            }

        }

        $custom = get_post_meta($this->id, 'wp_custom_attachment', true);
        if( !empty( $custom ) ) {
            $this->attachments['full_image'] = $custom['url'];
        }
    }

    public function getCustomFields()
    {
        $customFieldsKeys = get_post_custom_keys( $this->id );
        if( !empty ( $customFieldsKeys ) ) {
            foreach ( $customFieldsKeys AS $customField) {
                $customFieldValue = get_post_custom_values( $customField, $this->id );
                if ( !empty ($customFieldValue ) ) {
                    $this->custom_fields[$customField] = $customFieldValue;
                }
            }
        }
    }



}
