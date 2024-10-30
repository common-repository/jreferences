<?php

    function jref_add_help() {
        
        $screen = get_current_screen();
        $screen->add_help_tab( array(
        	'id'      => 'overview',
        	'title'   => __('Overview'),
        	'content' => '<p>'.__('This extension helps you to include footnotes references in your posts. ','jref').'</p><p>'.
        	__('You can change in this page the shortcode and the attributes used to construct references. ','jref').
        	__('But you should keep in mind the posts written with the old codes won\'t be parsed anymore! ','jref').
        	__('Consequently, you should only change these settings before using this extension. ','jref').'</p><p>'.
        	__('With the <i>defaults</i> settings, your shortcode should look like this: ','jref').'</p><p>'.
        	__('<code>...blabla[jref]author=Clint EASTWOOD|title=Magnum Force|url=http://someurl[/jref]blabla...</code>','jref').'</p><p>'.
        	__('A footnote will be  added (automatically) at the end of the post.','jref').
        	'</p>'
        ) );

    }


class JrefSettingsPage
{
    /**
     * Holds the values to be used in the fields callbacks
     */
    private $options;

    /**
     * Start up
     */
    public function __construct()
    {
        add_action( 'admin_menu', array( $this, 'add_plugin_page' ) );
        add_action( 'admin_init', array( $this, 'page_init' ) );
    }


    /**
     * Add options page
     */
    public function add_plugin_page()
    {
        // This page will be under "Settings"
        //global $jref_admin;
        
        $jref_admin = add_options_page(
            'JREF Settings Admin', 
            'JREF References', 
            'manage_options', 
            'jref-setting-admin', 
            array( $this, 'create_admin_page' )
        );
        
        if ( $jref_admin )
            add_action( 'load-' . $jref_admin, 'jref_add_help' );
    }

    /**
     * Options page callback
     */
    public function create_admin_page()
    {
        // Get class property
        $this->options = get_option( 'jref_option_name' );
        ?>
        <div class="wrap">
            <h1><?php _e( 'JReferences plugin Page', 'jref' ) ?></h1>
            <form method="post" action="options.php">
            <?php
                // This prints out all hidden setting fields
                settings_fields( 'jref_option_group' );
                do_settings_sections( 'jref-setting-admin' );
                submit_button();
            ?>
            </form>
        </div>
        <?php
        
    }
    

    /**
     * Register and add settings
     */
    public function page_init()
    {        
        register_setting(
            'jref_option_group', // Option group
            'jref_option_name', // Option name
            array( $this, 'sanitize' ) // Sanitize
        );

        add_settings_section(
            'setting_section_id', // ID
            __( 'JREF Plugin Settings', 'jref' ), // Title
            array( $this, 'print_section_info' ), // Callback
            'jref-setting-admin' // Page
        );  

        add_settings_field(
            'code_name', // ID
            __( 'JREF Shortcode Name', 'jref' ), // Shortcode name
            array( $this, 'code_name_callback' ), // Callback
            'jref-setting-admin', // Page
            'setting_section_id' // Section           
        );      

    }

    /**
     * Sanitize each setting field as needed
     *
     * @param array $input Contains all settings fields as array keys
     */
    public function sanitize( $input )
    {
        $new_input = array();
        if( isset( $input['code_name'] ) ) {
            $new_input['code_name'] = sanitize_text_field( $input['code_name'] );
            /** Checks if the value has changed */
            $old_options = get_option( 'jref_option_name' );
            $old_code    = $old_options['code_name'];
            if ( strcmp( $old_code, $new_input['code_name'] ) != 0)
                if ( shortcode_exists( $new_input['code_name'] ) ) {
                    add_settings_error( 'ExistingShortcode', 'jref-e01', 'Shortcode <i>'.$new_input['code_name'].'</i> already set somewhere. Please choose another one.', 'error' );
                    $new_input['code_name'] = sanitize_text_field( $old_code );  // Yes, I'm paranoid
                }
                else
                    add_settings_error( 'ModifiedShortcode', 'jref-w01', 'Shortcode updated and modified. Take care if you already used the old one ('.$old_code.')!', 'updated' );
        }

        return $new_input;
    }

    /** 
     * Print the Section text
     */
    public function print_section_info()
    {
        print __( 'Enter your settings below:', 'jref' );
    }

    /** 
     * Get the settings option array and print one of its values
     */
    public function code_name_callback()
    {
        printf(
            '<input type="text" id="code_name" name="jref_option_name[code_name]" value="%s" />',
            isset( $this->options['code_name'] ) ? esc_attr( $this->options['code_name']) : ''
        );
    }


}

if( is_admin() )
    $my_settings_page = new JrefSettingsPage();