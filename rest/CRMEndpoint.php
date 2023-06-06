<?php

namespace Leadpipe\REST {

  /**
   * Class CRMEndpoint registers Leadpipe REST API routes related to CRMs.
   *
   * @since 1.0.0
   */
  class CRMEndpoint {

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
        register_rest_route( 'leadpipe/v1', '/crms', array(
          'methods' => 'GET',
          'callback' => array( $this, 'http_get' ),
        ) );
      } ); 

      add_action( 'rest_api_init', function () {
        register_rest_route( 'leadpipe/v1', '/crms', array(
          'methods' => 'POST',
          'callback' => array( $this, 'http_post' ),
        ) );
      } ); 

      add_action( 'rest_api_init', function () {
        register_rest_route( 'leadpipe/v1', '/crms/add_field', array(
          'methods' => 'POST',
          'callback' => array( $this, 'http_post_add_field' ),
        ) );
      } ); 

      add_action( 'rest_api_init', function () {
        register_rest_route( 'leadpipe/v1', '/crms/delete_field', array(
          'methods' => 'POST',
          'callback' => array( $this, 'http_post_delete_field' ),
        ) );
      } ); 

    }

    /**
     * GET Method of this endpoint to return all CRMs.
     * 
     * @since 1.0.0
     */
    public function http_get() {

      require_once plugin_dir_path( __DIR__ ) . "crm/CRMRegistry.php";

      $registry = \Leadpipe\CRM\CRMRegistry::get_instance();

      $response = [
        'current' => $registry->get_current_crm_metadata(),
        'all' => $registry->get_crms()
      ];

      return $response;

    }

    /**
     * POST Method of this endpoint to set a new current CRM.
     * 
     * @since 1.0.0
     * @param WP_REST_Request $request
     */
    public function http_post($request) {

      require_once plugin_dir_path( __DIR__ ) . "crm/CRMRegistry.php";

      $data = $request->get_json_params();

      $registry = \Leadpipe\CRM\CRMRegistry::get_instance();

      $crm = $registry->get_crm_by_name($data['name']);

      if ($crm == null) 
        return new \WP_Error(400, 'CRM not found.');

      $auth_success = $crm->authenticate($data);

      if (!$auth_success)
        return new \WP_Error(400, 'Authentication failed.');

      $registry->set_current_crm_metadata($data);

      return new \WP_HTTP_Response(["success" => true]);
    }

    

  /**
   * POST Method of this endpoint to set current CRM's schema mapping for a specified form.
   * 
   * @since 1.0.0
   * @param WP_REST_Request $request
   */
  public function http_post_add_field($request) {

    require_once plugin_dir_path( __DIR__ ) . "crm/CRMRegistry.php";
    require_once plugin_dir_path( __DIR__ ) . "vendors/VendorsRegistry.php";

    $data = $request->get_json_params();

    $registry = \Leadpipe\CRM\CRMRegistry::get_instance();

    $crm = $registry->get_current_crm();

    if ($crm == null) 
      return new \WP_Error(400, 'CRM not set.');

    $fieldKey = $crm->add_field(
      $data['objectKey'],
      $data['label'],
    );

    if ($fieldKey)
      return new \WP_HTTP_Response(["success" => true, "key" => $fieldKey]);
    else
      return new \WP_Error(400, 'An error has been encountered while adding the field.');
  }

  

  /**
   * POST Method of this endpoint to set current CRM's schema mapping for a specified form.
   * 
   * @since 1.0.0
   * @param WP_REST_Request $request
   */
  public function http_post_delete_field($request) {

    require_once plugin_dir_path( __DIR__ ) . "crm/CRMRegistry.php";
    require_once plugin_dir_path( __DIR__ ) . "vendors/VendorsRegistry.php";

    $data = $request->get_json_params();

    $registry = \Leadpipe\CRM\CRMRegistry::get_instance();

    $crm = $registry->get_current_crm();

    if ($crm == null) 
      return new \WP_Error(400, 'CRM not set.');

    
    
    return new \WP_HTTP_Response(["success" => true]);
  }
  
}

  new CRMEndpoint();

}