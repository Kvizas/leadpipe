<?php

namespace Leadpipe\Core;


/**
 * Class FilledForm defines structure to which each vendor has to parse its submitted forms that will be sent to CRM.
 * 
 * @since 1.0.0
 */
class FilledForm {

    /**
     * Fields and answers.
     * 
     * @var array Where key is field name and value is value.
     * @since 1.0.0
     */
    private $fields = [];

    /**
     * Field setter.
     * 
     * @since 1.0.0
     */
    public function set_field($fieldName, $value) {
        $fields[$fieldName] = $value;
    }

    /**
     * Field getter.
     * 
     * @since 1.0.0
     */
    public function get_field($fieldName) {
        return $fields[$fieldName];
    }

    /**
     * Field getter.
     * 
     * @since 1.0.0
     */
    public function get_all_fields() {
        return $fields;
    }
}