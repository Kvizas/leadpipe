<?php 

namespace Leadpipe\CRM {

    include_once 'AbstractCRM.php';
    include_once 'CRMRegistry.php';

    /**
     * Pipedrive integration to interact with this CRM's API.
     *
     * @since 1.0.0
     */
    class PipedriveCRM extends AbstractCRM {

        public $name = "Pipedrive";

        public $authFields = ["Pipedrive URL", "Pipedrive API Token"];

        /**
         * Initialize class.
         *
         * @since 1.0.0
         */
        public function __construct() {
        
            CRMRegistry::get_instance()->register($this);

        }
        
        
        /**
        * Returns this class singleton instance.
        *
        * @since 1.0.0
        * @return Leadpipe\CRM\PipedriveCRM
        */
        public static function get_instance() {
        
            static $instance;

            if ( ! $instance ) {
                $instance = new self();
            }

            return $instance;
        }

        /**
         * Sends request to CRM API to check if given auth data is valid.
         * 
         * @since 1.0.0
         * @param object $authData Data needed to get access to the CRM.
         * @return bool Success or not.
         */
        public function authenticate($crmMetadata) {

            $result = $this->pipedrive_get('recents', '', '&since_timestamp=2000-01-01%2000:00:00&limit=1', $crmMetadata['authData']);
            return $result->success;

        }

        /**
         * HTTP Get requests to Pipedrive API.
         * 
         * @since 1.0.0
         * @param string $endpoint Pipedrive API endpoint.
         * @param string $endpointInstance Id of the specific instance. (Optional)
         * @param string $urlParams Pipedrive API url parameters. (Optional)
         * @param string $authData Authentification data. (Optional)
         * @return object Response.
         */
        public function pipedrive_get($endpoint, $endpointInstance = '', $urlParams='', $authData = null) {

            if ($authData == null)
                $authData = $this->get_auth_data();

            $url = $authData["Pipedrive URL"] . "/" . $endpoint . "/" . $endpointInstance . "?api_token=" . $authData["Pipedrive API Token"] . "&" . $urlParams;
        
            $resp = httpGet($url);
            return json_decode($resp);

        }

        /**
         * Handles form submission and sends data to CRM.
         * 
         * @since 1.0.0
         * @param Leadpipe\Core\FilledForm $filledForm Submission data from vendor.
         */
        public function on_form_submit($filledForm) {
            
        }

        /**
         * Returns an array of SchemaObject that is mapped to the given form.
         * 
         * @since 1.0.0
         * @param Leadpipe\Core\Form $form Form of which mapping should be returned.
         * @return Leadpipe\Core\SchemaObject[]
         */
        public function get_schema_map($form) {
            
            global $wpdb;

            $schema = $this->get_default_schema();
            
            $tableName = $wpdb->prefix . "leadpipe_mappings";

            $sql = $wpdb->prepare(
                "
                SELECT filled_data FROM $tableName
                WHERE form_vendor = %s AND form_id = %d
                ",
                [$form->vendor, $form->ID]
            );

            $row = $wpdb->get_row($sql);

            if ($row == null)
                return $schema;

            $filledObjects = json_decode($row->filled_data);

            // $filledObjects should be of the same size as $schema or less.
            // And in the most cases it doesn't contain a lot of iteratables, usually no more than 5.
            // Therefore a 4-deep foreach loop nesting should perform with reasonable speed.
            foreach ($filledObjects as $filledObj) {
                foreach ($schema as $schemaObj) {
                    
                    if ($schemaObj->title != $filledObj->title)
                        continue;

                    foreach ($filledObj->fields as $filledField) {
                        foreach ($schemaObj->fields as $schemaField) {

                            if ($schemaField->key != $filledField->key)
                                continue;

                            $schemaField->source = $filledField->source;
                            $schemaField->valueField = $filledField->valueField;
                            
                            break;
                        }
                    }
                    break;
                }
            }

            return $schema;
        }

        /**
         * Returns an array of SchemaObject that is considered as default schema.
         * 
         * @since 1.0.0
         * @return Leadpipe\Core\SchemaObject[]
         */
        public function get_default_schema() {

            require_once plugin_dir_path( __DIR__ ) . "SchemaObject.php";
            require_once plugin_dir_path( __DIR__ ) . "SchemaField.php";

            $orgSchemaObj = new \Leadpipe\Core\SchemaObject("Pipedrive Organization", [
                new \Leadpipe\Core\SchemaField("Name", "name", true, false)
            ], true, false);

            $orgSchemaObj = $this->append_custom_fields("organizationFields", $orgSchemaObj);

            $personSchemaObj = new \Leadpipe\Core\SchemaObject("Pipedrive Person", [
                new \Leadpipe\Core\SchemaField("Name", "name", true, false),
                new \Leadpipe\Core\SchemaField("Email", "email", false, false),
                new \Leadpipe\Core\SchemaField("Phone", "phone", false, false),
                // new \Leadpipe\Core\SchemaField("GA Client ID", "ga_client_id", false, false, "internal", "GA Client ID") // TODO Auto add ga_client_id to pipedrive
            ], true, true);

            $personSchemaObj = $this->append_custom_fields("personFields", $personSchemaObj);

            $leadSchemaObj = new \Leadpipe\Core\SchemaObject("Pipedrive Lead", [
                new \Leadpipe\Core\SchemaField("Title", "title", true, false),
                new \Leadpipe\Core\SchemaField("Potential value", "value", false, false),
                // new \Leadpipe\Core\SchemaField("GA Session ID", "ga_session_id", false, false, "internal", "GA Session ID") // TODO Auto add ga_session_id to pipedrive
            ], true, true);

            $leadSchemaObj = $this->append_custom_fields("dealFields", $leadSchemaObj);

            $noteSchemaObj = new \Leadpipe\Core\SchemaObject("Pipedrive Note", [
                new \Leadpipe\Core\SchemaField("Content", "content", true, false)
            ], false, false);

            return [
                $orgSchemaObj,
                $personSchemaObj,
                $leadSchemaObj,
                $noteSchemaObj
            ];
        }

        /**
         * Returns the same given schema but with custom CRM fields appended.
         * 
         * @since 1.0.0
         * @param string $endpoint Endpoint to access CRM object fields.
         * @param Leadpipe\Core\SchemaObject $schemaObj Default object schema of CRM object.
         * @return Leadpipe\Core\SchemaObject
         */
        public function append_custom_fields($endpoint, $schemaObj) {
            
            require_once plugin_dir_path( __DIR__ ) . "SchemaField.php";

            $all_fields = $this->pipedrive_get($endpoint)->data;

            $filter_function = function ($field) {
                return $field->edit_flag;
            };

            $custom_fields = array_filter($all_fields, $filter_function);

            foreach ($custom_fields as $field) {
                array_push(
                    $schemaObj->fields,
                    new \Leadpipe\Core\SchemaField(
                        $field->name,
                        $field->key,
                        false,
                        true
                    )
                );
            }

            return $schemaObj;
        }

        /**
         * Saves SchemaObject[] of the given form (field values only).
         * 
         * @since 1.0.0
         * @param string $formVendor Form vendor name.
         * @param int $formId Form id.
         * @param Leadpipe\Core\SchemaObject[] $schema Schema object received from frontend.
         */
        public function set_schema_map($formVendor, $formId, $schema) {
   
            global $wpdb;

            $table_name = $wpdb->prefix . "leadpipe_mappings";

            $wpdb->delete(
                $table_name,
                [
                    'crm' => $this->name, 
                    'form_vendor' => $formVendor, 
                    'form_id' => $formId
                ]
            );

            $wpdb->insert(
                $table_name, 
                [
                    'crm' => $this->name, 
                    'form_vendor' => $formVendor, 
                    'form_id' => $formId, 
                    'filled_data' => json_encode($schema),
                ]
            );

        }

        /**
         * Returns schema of the CRM.
         * 
         * @since 1.0.0
         * @return object $schema Schema object of the CRM.
         */
        public function get_fields() {
            
        }

        /**
         * Adds field to the CRM object.
         * 
         * @since 1.0.0
         * @param string $objectName Name of the CRM object.
         * @param string $fieldId ID of the CRM object field.
         */
        public function add_field($objectName, $fieldId) {

        }

        /**
         * Deletes field of the CRM object.
         * 
         * @since 1.0.0
         * @param string $objectName Name of the CRM object.
         * @param string $fieldId ID of the CRM object field.
         */
        public function delete_field($objectName, $fieldId) {

        }



    }

    PipedriveCRM::get_instance();

}