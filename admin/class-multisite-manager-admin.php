<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @since      1.0.0
 *
 * @package    Multisite_Manager
 * @subpackage Multisite_Manager/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Multisite_Manager
 * @subpackage Multisite_Manager/admin
 * @author     Manthan Parmar <manthansparmar7@gmail.com>
 */
class Multisite_Manager_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Multisite_Manager_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Multisite_Manager_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/multisite-manager-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Multisite_Manager_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Multisite_Manager_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/multisite-manager-admin.js', array( 'jquery' ), $this->version, false );

	}

		/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function register_multisite_manager_network_admin_menu() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Multisite_Manager_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Multisite_Manager_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		add_menu_page(
            'Multisite Manager',         // Page title
            'Multisite Manager',         // Menu title
            'manage_network',            // Capability
            'multisite-manager',         // Menu slug
            [$this, 'multisite_manager_main_callback'], // Callback function
            'dashicons-networking',      // Icon
            6                            // Position
        );


		add_submenu_page(
			'multisite-manager', 
			'Post Management', 
			'Post Management', 
			'manage_options', 
			'msm-post-management', 
			[$this, 'multisite_manager_post_management_callback'], // Callback function
		);
		
		// Add the Enhancements submenu page
		add_submenu_page(
			'multisite-manager', 
			'Enhancements', 
			'Enhancements', 
			'manage_options', 
			'msm-enhancements', 
			[$this, 'multisite_manager_enhancement_callback'], // Callback function
		);
	}

	//Template of Main admin page
	public function multisite_manager_main_callback() {
		
		include MULTISITE_MANAGEMENT_DIR_PATH . 'admin/templates/main.php';
	}
	
	//Template of Post management admin page
	public function multisite_manager_post_management_callback() {

		include MULTISITE_MANAGEMENT_DIR_PATH . 'admin/templates/post-management.php';

	}
	
	//Template of enhancement admin page
	public function multisite_manager_enhancement_callback(){	

		include MULTISITE_MANAGEMENT_DIR_PATH . 'admin/templates/enhancement.php';
	}
	
}