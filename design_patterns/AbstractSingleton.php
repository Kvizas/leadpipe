<?php 

namespace Leadpipe\DesignPatterns {

    /**
     * AbstractSingleton should be inhereted by all singleton classes.
     *
     * @since 1.0.0
     */
    abstract class AbstractSingleton {

        /**
         * Returns or creates instance of the class.
         * 
         * @since 1.0.0
         * @return AbstractSingleton The singleton instance of this class.
         */
        abstract public static function get_instance();

    }

}