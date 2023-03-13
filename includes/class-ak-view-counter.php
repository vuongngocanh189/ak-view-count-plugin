<?php
/**
 * Register view count into database
 */

if ( ! defined( 'ABSPATH' ) ) exit;
if ( ! class_exists('AK_View_Counter_Db')) return;

class AK_View_Counter {

	public static function init() {
        add_action( 'wp', array( __CLASS__, 'count_up' ) );
        add_action( 'deleted_post', array( __CLASS__, 'remove_view_count' ) );
		add_action( 'init', array( __CLASS__, 'register_top_view_shortcode' ) );

		add_filter( 'the_content', array( __CLASS__, 'show_views' ) );
    }

	/**
	 * Increase views
     * Happens after WP environment has finished setup
     * Post ID and post type are accessible at this point
	 */
	public static function count_up() {
        // Do not count if this is admin area or not single page
        if(is_admin() || ! is_singular()) return;

        $post_id = get_the_ID();
        if(!$post_id) return;

        // Do not count if page author or admin is visiting
        $post_author = get_post_field('post_author', $post_id);
        $current_user_id = get_current_user_id();

        if($current_user_id == $post_author) return;

        // Do not count posts that are not published
        $post_status = get_post_status();

        if($post_status !== 'publish') return;

        $post_type = get_post_type();

        AK_View_Counter_Db::count_up($post_id, $post_type);
	}

    /**
     * Display html code to show view count in front end
     * @params $content
     */
	public static function show_views($content) {
		// Don't show if it is not a single page
        if(!is_singular()) return $content;

		// Don't show if user selects so
        $is_display = get_option('ak_view_count_display');
		if(!$is_display) return $content;

        $location = get_option('ak_view_count_location');
        $views = AK_View_Counter_Db::get_view_count(get_the_ID());

        $view_count_text = "<div class='ak-view-counter'>Views: " . $views . '</div>';

        if($location) {
           if($location === 'top') {
               return $view_count_text . $content;
           } else {
               return $content . $view_count_text;
           }
        }

        return $content;
	}

	/**
	 * Register a shortcode so that user can include it in for example widgets
	 * [ak-top-views]
	 */
	public static function register_top_view_shortcode() {
		add_shortcode( 'ak-top-views', array(__CLASS__, 'display_top_views') );
	}

	/**
	 * Display the list on front end
	 * @param $atts
	 * @return false|string
	 */
	public static function display_top_views($atts) {
		$atts = shortcode_atts( array(
			'post_type' => '',
		), $atts );

		$top_view = AK_View_Counter_Db::get_top_views($atts['post_type']);

		if(!$top_view) echo "No views.";

		// Setup the array which contain titles and links of selected top views
		$top_view_articles = [];
		foreach($top_view as $tv) {
			$top_view_articles[] = array(
				'title' => apply_filters('the_title', get_the_title($tv['post_id'])),
				'link' => esc_url(get_the_permalink($tv['post_id'])),
				'view' => (int) $tv['views']
			);
		}

		ob_start();

		include(AK_VIEW_COUNTER_DIR . '/templates/top-views.php');

		return ob_get_clean();
	}

    /**
     * Delete the view count data together with the deletion of a post
     * @params $post_id
     */
	public static function remove_view_count($post_id) {
        AK_View_Counter_Db::delete_view_count_row($post_id);
	}
}

AK_View_Counter::init();
