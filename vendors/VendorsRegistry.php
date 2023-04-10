<?php 

namespace Leadpipe\Vendors {

    /**
     * Singleton class VendorsRegistry adds action to register form vendors and provides methods to interact with them.
     *
     * @since 1.0.0
     */
    class VendorsRegistry extends \Leadpipe\DesignPatterns\AbstractSingleton {

        /**
         * @var Leadpipe\Vendors\AbstractVendor[] All the active vendor instances.
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
         * Returns VendorRegistry singleton instance.
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
         * Adds vendor to registry
         *
         * @since 1.0.0
         * @param Leadpipe\Vendors\AbstractVendor $vendor Singleton vendor instance which inherits from AbstractVendor.
         */
        public function register($vendor) {
        
            $this->registry[$vendor->name] = $vendor;

        }

        /**
         * Returns all the vendor instances.
         * 
         * @since 1.0.0
         * @return \Leadpipe\Vendors\AbstractVendor[] Instances of all the vendors.
         */
        public function get_vendors() {
            return $this->registry;
        }

        /**
         * Returns vendor by it's name.
         * 
         * @since 1.0.0
         * @param string $vendor_name Name of the form vendor.
         * @return \Leadpipe\Vendors\AbstractVendor Instance of the vendor.
         */
        public function get_vendor_by_name($vendor_name) {

            foreach ($this->registry as $vendor)
                if ($vendor->name == $vendor_name)
                    return $vendor;

            return null;
        }

    }

}