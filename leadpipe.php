<?php
/**
 * Plugin Name:       Leadpipe
 * Description:       Plugin to send forms (WPForms, Contact Form 7) to CRMs (Pipedrive) and GA4
 * Requires at least: 5.8
 * Requires PHP:      7.0
 * Version:           0.1.0
 * Author:            RAIBEC
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       leadpipe
 */

namespace Leadpipe {

    include 'includes.php';

    function leadpipe_crm_post_type() {
        register_post_type('leadpipe_crm',
            array(
                'labels'      => array(
                    'name'          => __('CRM Settings', 'textdomain'),
                    'singular_name' => __('CRM Setting', 'textdomain'),
                ),
                'public'      => false,
            )
        );
    }
    add_action('init', 'Leadpipe\leadpipe_crm_post_type');


    add_action('init', 'Leadpipe\db_setup_tables'); // TODO change 'init' to on plugin activate
    function db_setup_tables() {

        global $wpdb;

        $table_name = $wpdb->prefix . "leadpipe_mappings";

        $charset_collate = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE IF NOT EXISTS $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT PRIMARY KEY,
        crm varchar(64) NOT NULL,
        form_vendor varchar(64) NOT NULL,
        form_id mediumint(9) NOT NULL,
        filled_data text DEFAULT NULL
        ) $charset_collate;";

        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql );

    }






    add_action( 'admin_menu', 'Leadpipe\init_menu' );

    /**
     * Init Admin Menu.
     *
     * @return void
     */
    function init_menu() {
        add_menu_page( __( 'Leadpipe', 'leadpipe'), __( 'Leadpipe', 'leadpipe'), 'manage_options', 'leadpipe', 'Leadpipe\admin_page', 'dashicons-database-export', '30' );
    }


    /**
     * Init Admin Page.
     *
     * @return void
     */
    function admin_page() {
        require_once plugin_dir_path( __FILE__ ) . '/frontend/templates/app.php';
    }


    add_action( 'admin_enqueue_scripts', 'Leadpipe\admin_enqueue_scripts' );
    /**
     * Enqueue scripts and styles.
     *
     * @return void
     */
    function admin_enqueue_scripts() {
        wp_enqueue_style( 'leadpipe-style', plugin_dir_url( __FILE__ ) . 'frontend/build/index.css' );
        wp_enqueue_script( 'leadpipe-script', plugin_dir_url( __FILE__ ) . 'frontend/build/index.js', array( 'wp-element' ), false, true );
    }

}