<?php 

namespace Leadpipe\Vendors {
    
    include_once 'AbstractVendor.php';
    include_once 'VendorsRegistry.php';

    /**
     * Vendor class WPForms to interact with this plugin.
     *
     * @since 1.0.0
     */
    class WPForms extends AbstractVendor {

        public $name = "WPForms";

        /**
         * Initialize class.
         *
         * @since 1.0.0
         */
        public function __construct() {
        
            VendorsRegistry::get_instance()->register($this);

            // $this.hook_submissions();

        }
        
        /**
        * Creates logic how form submissions are forwarded to current CRM method on_form_submit(...).
        * 
        * This method should be called in constructor of Vendor class.
        * 
        * @since 1.0.0
        */
        public function hook_submissions() {
            
        }

        /**
         * Returns this class singleton instance.
         *
         * @since 1.0.0
         * @return Leadpipe\Vendors\WPForms
         */
        public static function get_instance() {
        
            static $instance;

            if ( ! $instance ) {
                $instance = new self();
            }

            return $instance;
        }

        /**
         * Returns all the forms of this vendor.
         * 
         * @since 1.0.0
         * @return Leadpipe\Core\Form[] Array of all the forms.
         */
        public function get_all_forms() {

            return array_map(
                [$this, 'parse_form'],
                wpforms()->get( 'form' )->get( '' )
            );

        }

        /**
         * Returns the Form object of given form id
         * 
         * @since 1.0.0
         * @param int $form_id Form id
         * @return Form 
         */
        public function get_form($form_id) {

            return $this->parse_form( 
                wpforms()->get( 'form' )->get( (int) $form_id )
            );

        }

        /**
         * Parses vendors form object into Leadpipe\Core\Form class object.
         * 
         * @since 1.0.0
         * @param mixed $form Vendor form object.
         * @return Leadpipe\Core\Form 
         */
        public function parse_form($form) {

            require_once plugin_dir_path( __DIR__ ) . "Form.php";

            $form->post_content = json_decode($form->post_content);

            $result_form = new \Leadpipe\Core\Form();
            $result_form->ID = $form->ID;
            $result_form->vendor = $this->name;
            $result_form->title = $form->post_title;
            $result_form->fields = array_values(array_map(
                fn($field): string => strval($field->label ?? ""),
                get_object_vars($form->post_content->fields)
            ));

            return $result_form;

        }

    }

    WPForms::get_instance();

}