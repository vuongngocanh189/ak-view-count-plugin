<?php
/**
 * Handling admin area
 */
if ( ! defined( 'ABSPATH' ) ) exit;
if ( ! class_exists('AK_View_Counter_Db')) return;

class AK_View_Counter_Admin {

	public static function init() {
		add_filter( 'manage_posts_columns', array(__CLASS__, 'add_view_count_column') );
		add_filter( 'manage_page_posts_columns', array(__CLASS__, 'add_view_count_column') );

		add_action( 'manage_posts_custom_column', array(__CLASS__, 'view_count_column_content'), 10, 2 );
		add_action( 'manage_page_posts_custom_column', array(__CLASS__, 'view_count_column_content'), 10, 2 );
	}

	/**
	 * Register view count column in admin view
	 */
	public static function add_view_count_column( $columns ) {
		$columns['view_count'] = 'Views';
		return $columns;
	}

	/**
	 * Put values to view count column
	 */
	public static function view_count_column_content( $column, $post_id ) {
		if ( 'view_count' === $column) {
			echo AK_View_Counter_Db::get_view_count($post_id);
		}
	}
}

AK_View_Counter_Admin::init();
