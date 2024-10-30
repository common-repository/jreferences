<?php

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

class Jref_Reference {
    
    /**
     * 
     * Class that instanciates the references.
     * 
     * @since 4.7.1
     * 
     * Properties (from an attribute of the shortcode)
     * -----
     * @var string  $_name      The name of the reference. It will be used when reusing the reference.
     * 
     * Properties for internal usage
     * -----
     * @var int     $_post_id   The ID of the post using the reference.
     * @var int     $ref_num    Internal reference number. Begins at 1 and is incremented for every NEW reference.
     * @var string  $_anchor    HTML anchor used to navigate to and from the reference
     * @var int     $_occ_nb    Number of uccurrences of the reference
     * 
     * Properties of the reference
     * -----
     * @var string  $_title     Title of the reference or document
     * @var string  $_author    Author
     * @var string  $_date      Optional. Date of the reference or document
     * @var string  $_url       Optional. URL of the reference (a string, but in real it's an url)
     * @var string  $_editor    Optional. Editor
     * @var string  $_read      Optional. Date of consultation (=when the blogger read the reference or document)
     * @var string  $_lang      Optional. Language
     * @var string  $_format    Optional. Format (PDF, Office, etc)
     */
     
    private $_name;

    private $_post_id;
    private $_ref_num;
    private $_anchor;
    private $_occ_nb = 1;

    private $_title;
    private $_author;
    private $_date;
    private $_url;
    private $_editor;
    private $_page;
    private $_readon;
    private $_lang;
    private $_format;
    
    /**
     * Gets the post ID
     */
     
    public function get_post_id() {
        return $this->_post_id;
    }    
    
    /**
     * Gets the name of the reference. If it exists, it comes from an attribute 'name' in the shortcode
     */
     
    public function get_name() {
        return $this->_name;
    }
    
    /**
     * Gets the reference number (unique within a post)
     */
     
    public function get_ref_num() {
        return $this->_ref_num;
    }   
    
    /**
     * Gets the counter of usage (=how many times the reference is used in the post)
     */
     
    public function get_occ_nb() {
        return $this->_occ_nb;
    }

    /**
     * Gets the anchor
     */
     
    public function get_anchor() {
        return $this->_anchor;
    }

    /**
     * Sets the reference number
     */
     
    public function set_ref_num( $ref_num ) {
        $this->_ref_num = $ref_num;
    }
    
    /**
     * Sets the HTML anchor
     */
     
    public function set_anchor( $anchor ) {
        $this->_anchor = $anchor;
    }
    
    /**
     * Adds one the number of occurrences
     */
     
    public function add_count() {
        $this->_occ_nb++;
    }
    
    /**
     * Ugly function that displays a letter corresponding to the number of occurence
     * No more than 26. If you use the same reference more than this, you're a copycat.
     */
     
    public function display_letter_level( $cnt ) {

        $res = "";
        $cnt = min( 26, $cnt ); 
        for ($i = 0; $i < $cnt; $i++ )
            $res .= "<a href='#note-jref-p" . $this->_post_id . "-r" . $this->_ref_num . "-o" . ($i+1) . "'>" . chr(97+$i) . "</a> ";

        return "<sup>" . $res . "</sup>";

    }
    
    /**
     * Displays the HTML for the reference footer
     */
     
    public function display_html() {

        $html = "<p id='jref_footer' class='reference-footnote'>";
        $html .= "<span id='jref-p" . $this->get_post_id() . "-r" . $this->get_ref_num() . "'>" . $this->_ref_num . ". ";
        // Multiple refs
        if ( $this->_occ_nb > 1 )
            $html .= "↑ ".$this->display_letter_level($this->_occ_nb);
        else
            $html .= "<a style='border-bottom: 1px solid white;' href='#note-jref-p".$this->get_post_id()."-r".$this->get_ref_num()."-o1'>↑ </a>";
        if ( isset($this->_title) ) {
            if ( isset($this->_lang) ) {
                $html .= " (".$this->_lang.")";
            }
            if ( isset($this->_author) ) {
                $html .= " ".$this->_author.", ";
            }
            if ( isset($this->_url) ) {
                $html .= " &laquo; <a href=".$this->_url.">".$this->_title."</a> &raquo;";
            } else {
                $html .= " &laquo; ".$this->_title." &raquo;";
            }
            if ( isset($this->_date) ) {
                $html .= ", ".$this->_date;
            }
            if ( isset($this->_editor) ) {
                $html .= ", ".$this->_editor;
            }
            if ( isset($this->_page) ) {
                $html .= ", ".$this->_page;
            }
            if ( isset($this->_readon) ) {
                $html .= ", ".__('read on','jref')." ".$this->_readon;
            }
        }
        else {
            $html .= "#err";
        }
        $html .= "</span></p>";

        return $html;

    }

    
    /**
     * Constructor
     */
     
    public function __construct( $atts, $content ) {

        static $patt = "((date|author|title|format|editor|page|readon|lang)=(.+))";
        static $patt_url = "((url)=((http)(s)?(.+)))";

        // Add post ID
        $this->_post_id = get_the_ID();

        // Has some attribute ?
        if ( isset( $atts['name'] ) ) {
            $this->_name = htmlspecialchars( $atts['name'] );
        }
        
        // What about the content ?
        $splitted_args = preg_split('/\|/', $content);

        foreach ( $splitted_args as $param ) {
            if ( preg_match( $patt_url, $param, $rech ) ) {
                $this->_url = esc_url( $rech[2] );
            }
            elseif (preg_match($patt, $param, $rech)) {  
                $arg_name = $rech[1];
                $arg_value = $rech[2];
                switch ($arg_name) {
                    case 'date':
                        $this->_date = $arg_value;
                        break;
                    case 'author':
                        $this->_author = $arg_value;
                        break;
                    case 'title':
                        $this->_title = $arg_value;
                        break;
                    case 'format':
                        $this->_format = $arg_value;
                        break;
                    case 'editor':
                        $this->_editor = $arg_value;
                        break;
                    case 'page':
                        $this->_page = $arg_value;
                        break;
                    case 'lang':
                        $this->_lang = $arg_value;
                        break;
                    case 'readon':
                        $this->_readon = $arg_value;
                        break;
                }
            }
        }
        
    }
}    