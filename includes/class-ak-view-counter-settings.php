<?php
/**
 * Handling user settings / options
 */

if ( ! defined( 'ABSPATH' ) ) exit;

class AK_View_Counter_Settings {

    public static function init() {
        add_action('admin_menu', array(__CLASS__, 'add_view_count_option_page'));
	    add_action('admin_init', array(__CLASS__, 'register_options'));
    }

    /**
     * Add an option page that user can select the location of the view counter
     * Page can be access as a sub-menu under Tools Menu
     */
    public static function add_view_count_option_page() {
        add_submenu_page(
            'tools.php',
            'AK View Counter',
            'AVC Settings',
            'administrator',
            'ak-view-counter-settings',
	        array(__CLASS__, 'register_counter_settings')
        );
    }

    /**
     * Include settings page template
     */
    public static function register_counter_settings() {
        include_once(AK_VIEW_COUNTER_DIR . '/templates/settings.php');
    }

	/**
	 * Add recognization for setting fields that will be added to options
	 */
	public static function register_options() {
		register_setting('ak-view-count', 'ak_view_count_display');
		register_setting('ak-view-count', 'ak_view_count_location');
	}
}

AK_View_Counter_Settings::init();
