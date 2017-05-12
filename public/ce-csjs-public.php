<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://www.connectingelement.co.uk
 * @since      1.0.0
 *
 * @package    CE_CSJS
 * @subpackage CE_CSJS/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    CE_CSJS
 * @subpackage CE_CSJS/public
 * @author     Christopher Scarre <a@b.c>
 */
class CE_CSJS_Public {

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
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
        $this->ce_csjs_options = get_option($this->plugin_name);
	}
    
    /**
     * Add the Custom action to the array of available options
     * 
     * @param array $types
     * @return \NF_Actions_Custom_CE_CSJS
     */
    function ninjaforms_action_subscribe($types) {
        if (get_option('ninja_forms_load_deprecated', true)) {
            require_once(plugin_dir_path(__DIR__) . 'classes/integrations/ninjaforms_deprecated.php');
            $types['ce_csjs_action_subscribe'] = new NF_Action_Custom_CE_CSJS();
        } /*else {
            require_once(WP_PLUGIN_DIR  . '/ninja-forms/ninja-forms.php');
            require_once(plugin_dir_path(__DIR__) . 'classes/integrations/NF_Actions_Custom_CE_CSJS.php');
            $types['ce_csjs_action_subscribe'] = new NF_Actions_Custom_CE_CSJS();
        }*/
        return $types;
    }
}
