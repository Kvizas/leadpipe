<?php 

namespace Leadpipe\Core;

class SchemaField {

    /**
     * @var string Label of the field.
     */
    public $label;

    /**
     * @var string Key of the field (API field key).
     */
    public $key;
    
    /**
     * @var bool True if this field is compulsory to fill in.
     */
    public $required;
    
    /**
     * @var bool True if it's possible to remove this field from the schema.
     */
    public $deletable;
    
    /**
     * @var string Source of the value field. Eg. "form" or "internal"
     */
    public $source;

    /**
     * @var string Field name in the source which will be used as the value.
     */
    public $valueField;

    /**
     * Constructor of the class.
     * 
     * @since 1.0.0
     * @param string $label
     * @param string $key
     * @param bool $required
     * @param bool $deletable
     */
    public function __construct($label, $key, $required, $deletable, $source = "", $valueField = "") {
        $this->label = $label;
        $this->key = $key;
        $this->required = $required;
        $this->deletable = $deletable;
        $this->source = $source;
        $this->valueField = $valueField;
    }

}