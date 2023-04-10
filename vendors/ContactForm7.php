<?php 

namespace Leadpipe\Vendors {
    
    include_once 'AbstractVendor.php';
    include_once 'VendorsRegistry.php';

    /**
     * Vendor class ContactForm7 to interact with this plugin.
     *
     * @since 1.0.0
     */
    class ContactForm7 extends AbstractVendor {

        public $name = "ContactForm7";

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
         * @return Leadpipe\Vendors\ContactForm7
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

            $posts = \WPCF7_ContactForm::find( [ 'posts_per_page' => - 1 ] );

            return array_map(
                [$this, 'parse_form'],
                $posts
            );

        }

        /**
         * Returns the Form object of given form id
         * 
         * @since 1.0.0
         * @param int $form_id Form id
         * @return Leadpipe\Core\Form 
         */
        public function get_form($form_id) {

            return $this->parse_form(
                \WPCF7_ContactForm::get_instance( $form_id )
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
            $result_form->ID = $form->id();
            $result_form->vendor = $this->name;
            $result_form->title = $form->title();
            
            $result_form->fields = [];
            foreach ($form->scan_form_tags() as $field)
                if ($field->name != "")
                    array_push($result_form->fields, $field->name);

            return $result_form;

        }

    }

    ContactForm7::get_instance();

}