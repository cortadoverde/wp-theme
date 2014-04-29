<?php
/**
 * @PSR-0: Vas\Hook\Admin
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

class Admin
{
    public static function init()
    {
       // add_action( 'admin_init', array( '\Vas\Hook\Admin', 'register_meta_box' ) );
        add_action('add_meta_boxes', array( '\Vas\Hook\Admin', 'register_meta_box' ));
        add_action('save_post',  array( '\Vas\Hook\Admin', 'save_custom_meta_data'));
        add_action('post_edit_form_tag', array( '\Vas\Hook\Admin', 'update_edit_form'));
    }

    public static function register_meta_box()
    {
        add_meta_box( 
            'wp_custom_attachment', 
            'Imagen Principal ( Solo para el full view )', 
            array('\Vas\Hook\Admin', 'post_image_main'),
            'post',
            'side'
        );
    }

    public static function post_image_main()
    {
        wp_nonce_field(plugin_basename(__FILE__), 'wp_custom_attachment_nonce');
         
        $html = '<p class="description">';
            $html .= 'Upload your PDF here.';
        $html .= '</p>';
        $html .= '<input id="wp_custom_attachment" name="wp_custom_attachment" value="" size="25" type="file">';
         
        echo $html;


    }

    function update_edit_form() {
        echo ' enctype="multipart/form-data"';
    } // end update_edit_form
    

    function save_custom_meta_data($id) {
     
        /* --- security verification --- */
        if(!wp_verify_nonce($_POST['wp_custom_attachment_nonce'], plugin_basename(__FILE__))) {
          return $id;
        } // end if
           
        if(defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
          return $id;
        } // end if
           
        if('page' == $_POST['post_type']) {
          if(!current_user_can('edit_page', $id)) {
            return $id;
          } // end if
        } else {
            if(!current_user_can('edit_page', $id)) {
                return $id;
            } // end if
        } // end if
        
        if(!empty($_FILES['wp_custom_attachment']['name'])) {
             
            $supported_types = array('image/jpeg', 'image/png', 'image/gif');
             
            // Get the file type of the upload
            $arr_file_type = wp_check_filetype(basename($_FILES['wp_custom_attachment']['name']));
            $uploaded_type = $arr_file_type['type'];
             
            // Check if the type is supported. If not, throw an error.
            if(in_array($uploaded_type, $supported_types)) {
     
                // Use the WordPress API to upload the file
                $upload = wp_upload_bits($_FILES['wp_custom_attachment']['name'], null, file_get_contents($_FILES['wp_custom_attachment']['tmp_name']));
         
                if(isset($upload['error']) && $upload['error'] != 0) {
                    wp_die('There was an error uploading your file. The error is: ' . $upload['error']);
                } else {
                    add_post_meta($id, 'wp_custom_attachment', $upload);
                    update_post_meta($id, 'wp_custom_attachment', $upload);    
                } // end if/else
     
            } else {
                wp_die("The file type that you've uploaded is not a PDF.");
            } // end if/else
             
        } // end if 
    } 


}
