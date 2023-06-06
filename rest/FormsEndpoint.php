<?php

namespace Leadpipe\REST {

  /**
   * Class Forms_Endpoint registers Leadpipe REST API routes related to Forms.
   *
   * @since 1.0.0
   */
  class Forms_Endpoint {

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
        register_rest_route( 'leadpipe/v1', '/forms', array(
          'methods' => 'GET',
          'callback' => array( $this, 'http_get_all' ),
        ) );
      } ); 

      add_action( 'rest_api_init', function () {
        register_rest_route( 'leadpipe/v1', '/forms/(?P<vendor>[a-zA-Z0-9-]+)/(?P<id>[\d]+)', array(
          'methods' => 'GET',
          'callback' => array( $this, 'http_get_one' ),
        ) );
      } ); 

    }

    /**
     * GET Method of this endpoint to return all forms.
     * 
     * @since 1.0.0
     */
    public function http_get_all() {

      require_once plugin_dir_path( __DIR__ ) . "vendors/VendorsRegistry.php";

      $vendors = \Leadpipe\Vendors\VendorsRegistry::get_instance()->get_vendors();

      $response = [];

      foreach ($vendors as $vendor) {
        $response[$vendor->name] = $vendor->get_all_form_titles();
      }

      return $response;

    }


    /**
     * GET Method of this endpoint to return fields of one specific form.
     * 
     * @since 1.0.0
     */
    public function http_get_one($data) {

      require_once plugin_dir_path( __DIR__ ) . "vendors/VendorsRegistry.php";

      $vendor = \Leadpipe\Vendors\VendorsRegistry::get_instance()->get_vendor_by_name($data['vendor']);

      if ($vendor == null) 
        return new \WP_Error(400, 'Vendor not found.');

      $form = $vendor->get_form($data['id']);

      if ($form == null)
        return new \WP_Error(400, 'Form not found.');

      return $form;
    }
  }

  new Forms_Endpoint();

}