<?php

namespace Leadpipe\Core;

/**
 * Class Form defines structure to which each vendor has to parse its forms.
 * 
 * @since 1.0.0
 */
class Form {

    /**
     * Unique ID of the form
     * 
     * @var int
     * @since 1.0.0
     */
    public $ID;

    /**
     * Vendor name of the form.
     * 
     * @var string
     * @since 1.0.0
     */
    public $vendor;

    /**
     * Title of the form
     * 
     * @var string
     * @since 1.0.0
     */
    public $title;

    /**
     * Labels of all fields
     * 
     * @var string[]
     * @since 1.0.0
     */
    public $fields = [];

}