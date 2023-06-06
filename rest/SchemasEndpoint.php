<?php

namespace Leadpipe\REST {

  /**
   * Class SchemasEndpoint registers Leadpipe REST API routes related to Schemas.
   *
   * @since 1.0.0
   */
  class SchemasEndpoint {

    /**
     * Initialize class.
     *
     * @since 1.0.0
     */
    public function __construct() {
      
      $this->register_routes();

    }

    /**
     * Register all routes used in this endpoint.
     * 
     * @since 1.0.0
     */
    public function register_routes() {

      add_action( 'rest_api_init', function () {
        register_rest_route( 'leadpipe/v1', '/schemas/(?P<form_vendor>[a-zA-Z0-9-]+)/(?P<form_id>[\d]+)', array(
          'methods' => 'GET',
          'callback' => array( $this, 'http_get' ),
        ) );
      } ); 

      add_action( 'rest_api_init', function () {
        register_rest_route( 'leadpipe/v1', '/schemas/(?P<form_vendor>[a-zA-Z0-9-]+)/(?P<form_id>[\d]+)', array(
          'methods' => 'POST',
          'callback' => array( $this, 'http_post' ),
        ) );
      } );

    }


    /**
     * GET Method of this endpoint to return schema of given CRM.
     * 
     * @since 1.0.0
     */
    public function http_get($data) {

      require_once plugin_dir_path( __DIR__ ) . "crm/CRMRegistry.php";
      require_once plugin_dir_path( __DIR__ ) . "vendors/VendorsRegistry.php";

      $crm = \Leadpipe\CRM\CRMRegistry::get_instance()->get_current_crm();

      if ($crm == null) 
        return new \WP_Error(400, 'No CRM authorized.');

      $formVendor = \Leadpipe\Vendors\VendorsRegistry::get_instance()->get_vendor_by_name($data['form_vendor']);

      if ($formVendor == null) 
        return new \WP_Error(400, 'Form vendor not found.');

      $form = $formVendor->get_form($data['form_id']);

      if ($form == null)
        return new \WP_Error(400, 'Form not found.');


      return $crm->get_schema_map($form);
    }


    /**
     * POST Method of this endpoint to set current CRM's schema mapping for a specified form.
     * 
     * @since 1.0.0
     * @param WP_REST_Request $request
     */
    public function http_post($request) {

      require_once plugin_dir_path( __DIR__ ) . "crm/CRMRegistry.php";
      require_once plugin_dir_path( __DIR__ ) . "vendors/VendorsRegistry.php";

      $data = $request->get_json_params();

      $registry = \Leadpipe\CRM\CRMRegistry::get_instance();

      $crm = $registry->get_current_crm();

      if ($crm == null) 
        return new \WP_Error(400, 'CRM not set.');

      $vendor = \Leadpipe\Vendors\VendorsRegistry::get_instance()->get_vendor_by_name($request['form_vendor']);

      if (!$vendor)
        return new \WP_Error(400, 'Form vendor not found.');

      $form = $vendor->get_form($request['form_id']);

      if (!$form)
        return new \WP_Error(400, 'Form not found.');

      $validation_errors = $crm->validate_schema_map($data);
      if ($validation_errors)
        return new \WP_Error(400, $validation_errors);

      $crm->set_schema_map($request['form_vendor'], $request['form_id'], $data);

      return new \WP_HTTP_Response(["success" => true]);
    }
  }

  new SchemasEndpoint();

}