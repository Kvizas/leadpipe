<?php 

namespace Leadpipe\Core;

class SchemaObject {

    /**
     * @var string Title of the schema object.
     */
    public $title;

    /**
     * @var string Title of the schema object.
     */
    public $key;

    /**
     * @var bool True if this object can have custom fields.
     */
    public $customizableFields;

    /**
     * @var bool True if this object can have custom fields.
     */
    public $required;

    /**
     * @var SchemaField[] Array of fields which this object contains.
     */
    public $fields;

    /**
     * Constructor of the class.
     * 
     * @since 1.0.0
     * @param string $title
     * @param SchemaField[] $fields
     */
    public function __construct($title, $key, $fields, $customizableFields = true, $required = false) {
        $this->title = $title;
        $this->key = $key;
        $this->fields = $fields;
        $this->customizableFields = $customizableFields;
        $this->required = $required;
    }

}