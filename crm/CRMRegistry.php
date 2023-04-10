<?php 

namespace Leadpipe\CRM {

    /**
     * Singleton class CRMRegistry adds action to register CRMs and provides methods to interact with them.
     *
     * @since 1.0.0
     */
    class CRMRegistry extends \Leadpipe\DesignPatterns\AbstractSingleton {

        /**
         * @var Leadpipe\CRM\AbstractCRM[] All active CRM instances.
         */
        private $registry;

        /**
         * Initialize class.
         *
         * @since 1.0.0
         */
        public function __construct() {
            $this->registry = [];
        }

        /**
         * Returns CRMRegistry singleton instance.
         *
         * @since 1.0.0
         */
        public static function get_instance() {
        
            static $instance;

            if ( ! $instance ) {
                $instance = new self();
            }
    
            return $instance;
        }

        /**
         * Adds CRM to registry.
         *
         * @since 1.0.0
         * @param Leadpipe\CRM\AbstractCRM $crm Singleton CRM instance which inherits from AbstractCRM.
         */
        public function register($crm) {
        
            $this->registry[$crm->name] = $crm;

        }

        /**
         * Returns currently used CRM metadata.
         * 
         * @since 1.0.0
         * @return object Current CRM metadata including authentication data.
         */
        public function get_current_crm_metadata() {
            return get_option('leadpipe_crm');
        }

        /**
         * Sets currently used CRM metadata.
         * 
         * @since 1.0.0
         * @param object Current CRM metadata including authentication data.
         */
        public function set_current_crm_metadata($crmMetadata) {
            return update_option('leadpipe_crm', $crmMetadata);
        }

        /**
         * Returns currently used CRM instance.
         * 
         * @since 1.0.0
         * @return \Leadpipe\CRM\AbstractCRM Instance the current CRM.
         */
        public function get_current_crm() {

            $crmMetadata = $this->get_current_crm_metadata();

            if (!isset($crmMetadata))
                return null;
            
            $name = $crmMetadata['name'];

            return $this->get_crm_by_name($name);
        }

        /**
         * Returns all the CRM instances.
         * 
         * @since 1.0.0
         * @return \Leadpipe\CRM\AbstractCRM[] Instances of all the CRMs.
         */
        public function get_crms() {
            return $this->registry;
        }

        /**
         * Returns CRM instance by it's name.
         * 
         * @since 1.0.0
         * @param string $name Name of the CRM.
         * @return \Leadpipe\CRM\AbstractCRM[] Instance of the CRM.
         */
        public function get_crm_by_name($name) {

            foreach ($this->registry as $crm)
                if ($crm->name == $name)
                    return $crm;

            return null;
        }

    }

}