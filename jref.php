<?php
/*
Plugin Name: JReferences
Description: Footnotes references and citations management in a mediawiki-like style
Version: 1.0.3
Author: Janiko  
Author URI: http://geba.fr
License: GPL2
*/

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

class Jref_Plugin {

    /**
     * Initialisation of the plugin. The Jref_Citation is an object used for storing and organizing references.
     */

     
    public function __construct() {

        /** Add settings at activation */
        register_activation_hook( __FILE__, 'jref_defaults');   
        
        /**
         * Default settings, at activation
         */
        function jref_defaults() {    
            $jref_options = get_option( 'jref_option_name' );
            if ($jref_options == false) {
                $res = add_option( 'jref_option_name', array('code_name' => 'jref') );
            }
        }
        
        include_once plugin_dir_path( __FILE__ ).'class.jref-citation.php';
        include_once plugin_dir_path( __FILE__ ).'class.jref-admin.php';

        new Jref_Citation();
    }
}

new Jref_Plugin();
