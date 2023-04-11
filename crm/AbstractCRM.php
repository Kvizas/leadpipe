<?php 

namespace Leadpipe\CRM {

    include_once plugin_dir_path( __DIR__ ) . "design_patterns/AbstractSingleton.php";

    /**
     * AbstractCRM should be inhereted by all CRM classes.
     *
     * @since 1.0.0
     */
    abstract class AbstractCRM extends \Leadpipe\DesignPatterns\AbstractSingleton {

        /**
         * Unique name of the CRM.
         * 
         * Regex: (?P<crm>[a-zA-Z0-9-]+)
         */
        public $name;

        /**
         * @var string[] Array of authentification fields which have to be provided by the user.
         */
        public $authFields;

        /**
         * Sends request to CRM API to check if given auth data is valid.
         * 
         * @since 1.0.0
         * @param object $authData Data needed to get access to the CRM.
         * @return bool True on success and false on error.
         */
        abstract public function authenticate($authData);

        /**
         * Returns authentication data.
         * 
         * @since 1.0.0
         * @return object Returns object with $this->$authFields values.
         */
        protected function get_auth_data() {

            require_once "CRMRegistry.php";
            $registry = CRMRegistry::get_instance();

            $crmMetadata = $registry->get_current_crm_metadata();

            return $crmMetadata['authData'];
        }

        /**
         * Handles form submission and sends data to CRM.
         * 
         * @since 1.0.0
         * @param Leadpipe\Core\FilledForm $filledForm Submission data from vendor.
         * @param Leadpipe\Core\Form|object $form Form data or an object with ['vendor' => '', 'ID' => 00] fields describing the form.
         */
        abstract public function on_form_submit($filledForm, $form);

        /**
         * Returns an array of SchemaObject that is considered as default schema.
         * 
         * @since 1.0.0
         * @return Leadpipe\Core\SchemaObject[]
         */
        abstract public function get_default_schema();

        /**
         * Returns an array of SchemaObject that is mapped to the given form.
         * 
         * @since 1.0.0
         * @param Leadpipe\Core\Form $form Form of which mapping should be returned.
         * @return Leadpipe\Core\SchemaObject[]
         */
        abstract public function get_schema_map($formId);

        /**
         * Validates that every required field of every SchemaObject is filled in correct manner.
         * 
         * @since 1.0.0
         * @param Leadpipe\Core\SchemaObject[] $schema Schema object array received from frontend.
         * @return string|null Returns null if all fields are valid and returns string of the error message if there's any
         */
        public function validate_schema_map($schema) {

            $defaultSchema = $this->get_default_schema();

            $requiredObjects = []; // SchemaObject[]
            $requiredFields = []; // {'schema_object1': ['required_field_key1', ...], ...}

            // Collect keys of all required fields from default schema
            foreach ($defaultSchema as $schemaObj) {
                $objTitle = $schemaObj->title;

                if ($schemaObj->required)
                    array_push($requiredObjects, $objTitle);

                foreach ($schemaObj->fields as $schemaField)
                    if ($schemaField->required) {
                        if (array_key_exists($objTitle, $requiredFields))
                            array_push($requiredFields[$objTitle], $schemaField->key);
                        else $requiredFields[$objTitle] = [$schemaField->key];
                    }
            }

            // Check wheather schema required fields are filled or not
            foreach ($schema as $schemaObj) {
                $schemaTitle = $schemaObj['title'];
                $objReqFields = $requiredFields[$schemaTitle];
                $filledReqFieldsNumber = 0;
                
                $hasFilledFields = false;

                foreach ($schemaObj['fields'] as $schemaField) {
                    if ($schemaField['valueField'] != "") {
                        $hasFilledFields = true;
                        if (in_array($schemaField['key'], $objReqFields))
                            $filledReqFieldsNumber += 1;
                    }
                }
                
                if (
                    (in_array($schemaTitle, $requiredObjects) && $filledReqFieldsNumber < count($objReqFields))
                    ||
                    ($hasFilledFields && $filledReqFieldsNumber < count($objReqFields))
                ) return sprintf("Please fill all required fields of %s.", $schemaTitle);
            }

            return null;
        }

        /**
         * Saves SchemaObject[] of the given form (field values only).
         * 
         * @since 1.0.0
         * @param string $formVendor Form vendor name.
         * @param int $formId Form id.
         * @param Leadpipe\Core\SchemaObject[] $schema Schema object array received from frontend.
         */
        abstract public function set_schema_map($formVendor, $formId, $schema);

        /**
         * Returns schema of the CRM.
         * 
         * @since 1.0.0
         * @return object $schema Schema object of the CRM.
         */
        abstract public function get_fields();

        /**
         * Adds field to the CRM object.
         * 
         * @since 1.0.0
         * @param string $objectName Name of the CRM object.
         * @param string $fieldId ID of the CRM object field.
         */
        abstract public function add_field($objectName, $fieldId);

        /**
         * Deletes field of the CRM object.
         * 
         * @since 1.0.0
         * @param string $objectName Name of the CRM object.
         * @param string $fieldId ID of the CRM object field.
         */
        abstract public function delete_field($objectName, $fieldId);



    }

}