<?php 

namespace Leadpipe\Vendors {

    include plugin_dir_path( __DIR__ ) . "design_patterns/AbstractSingleton.php";

    /**
     * AbstractVendor should be inhereted by all form vendors.
     *
     * @since 1.0.0
     */
    abstract class AbstractVendor extends \Leadpipe\DesignPatterns\AbstractSingleton {

        /**
         * Unique name of the vendor plugin.
         * 
         * Regex: (?P<vendor>[a-zA-Z0-9-]+)
         */
        public $name;

        /**
         * Creates logic how form submissions are forwarded to current CRM method on_form_submit(...).
         * 
         * This method should be called in constructor of Vendor class.
         * 
         * @since 1.0.0
         */
        abstract public function hook_submissions();

        /**
         * Returns all the forms of this vendor.
         * 
         * @since 1.0.0
         * @return Leadpipe\Core\Form[] Array of all the forms.
         */
        abstract public function get_all_forms();

        /**
         * Returns the Form object of given form id
         * 
         * @since 1.0.0
         * @param int $form_id Form id
         * @return Leadpipe\Core\Form 
         */
        abstract public function get_form($form_id);

        /**
         * Parses vendors form object into Leadpipe\Core\Form class object.
         * 
         * @since 1.0.0
         * @param mixed $form Vendor form object.
         * @return Leadpipe\Core\Form 
         */
        abstract public function parse_form($form);


        /**
         * Returns all the titles of existing forms.
         * 
         * @since 1.0.0
         * @return string[] Array of all the titles of existing forms.
         */
        public function get_all_form_titles() {

            $forms = $this->get_all_forms();
            $titles = [];
            foreach ($forms as $form)
                array_push($titles, [
                    "id" => $form->ID,
                    "title" => $form->title,
                    "vendor" => $this->name
                ]);

            return $titles;

        }



    }

}