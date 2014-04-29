<?php
/**
 * @PSR-0: Vas\Hook\Ajax
 *
 * (c) Pablo Adrian Samudia <p.a.samu@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * @author Pablo Samudia <p.a.samu@gmail.com>
 * 
 */

namespace Vas\Hook;

class Ajax
{
    public static function init()
    {
        self::add('dump', array('\\Vas\\Hook\\Ajax', 'dump') );
        self::add('servicios', array('\\Vas\\Hook\\Ajax', 'servicios') );
        self::add('contact', array('\\Vas\\Hook\\Ajax', 'save_contact') );
        self::add('resultados', array('\\Vas\\Hook\\Ajax', 'resultados') );
        self::add('contacto', array('\\Vas\\Hook\\Ajax', 'contacto') );
    }

    private static function add( $name, $callback )
    {
        add_action( 'wp_ajax_' . $name , $callback );
        add_action( 'wp_ajax_nopriv_' . $name , $callback );
    }

    public static function servicios()
    {
        $json['template'] = \Vas\File::partial('Servicios/Servicio');

        $json['data']['Post'] = \Vas\Model\Servicio::find();
       
        self::jclose( $json );
    }

    public static function resultados()
    {
        $json['template'] = \Vas\File::partial('Resultados/Slider');

        $json['data']['Post'] = \Vas\Model\Resultado::find();

        self::jclose($json);

    }

    public static function contacto()
    {
        $json['template'] = \Vas\File::partial('Contacto/Form');

        self::jclose($json);

    }


    public static function save_contact()
    {   
        if( isset( $_POST['mensaje'] ) ) {
            self::save_contact_msj();
            die;
        }
        
        $Contact = array(
          'post_title'    => 'Registro::' . time(),
          'post_type' => 'vas_contacto',
          'post_content'  => 
            'Copia de seguridad<br>'
            . 'Nombre: '  . $_POST['nombre']  . '<br>'
            . 'Ciudad: '  . $_POST['ciudad']  . '<br>'
            . 'Correo: '  . $_POST['correo']  . '<br>'
            . 'Celular: ' . $_POST['celular'] . '<br>',
          'post_status'   => 'publish'
        );

        $post_id = wp_insert_post($Contact);
        add_post_meta($post_id, 'Nombre', $_POST['nombre'], false);
        add_post_meta($post_id, 'Ciudad', $_POST['ciudad'], false);
        add_post_meta($post_id, 'Correo', $_POST['correo'], false);
        add_post_meta($post_id, 'Celular', $_POST['celular'], false);

        die;
    }

    public static function save_contact_msj()
    {   
       
        $Contact = array(
          'post_title'    => 'Registro::' . time(),
          'post_type' => 'vas_mensajes',
          'post_content'  => 
            'Copia de seguridad<br>'
            . 'Nombre: '  . $_POST['nombre']  . '<br>'
            . 'Correo: '  . $_POST['correo']  . '<br>'
            . 'Mensaje: ' . $_POST['mensaje'] . '<br>',
          'post_status'   => 'publish'
        );

        $post_id = wp_insert_post($Contact);
        add_post_meta($post_id, 'Nombre', $_POST['nombre'], false);
        add_post_meta($post_id, 'Correo', $_POST['correo'], false);
        add_post_meta($post_id, 'Mensaje', $_POST['mensaje'], false);

        die;
    }


    public static function jclose( $json )
    {
        header('Content-type: application/json');
        echo json_encode($json);
        die;
    }

}
