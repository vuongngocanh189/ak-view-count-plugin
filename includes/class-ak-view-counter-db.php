<?php
/**
 * Handling view count data from custom table
 */
if (!defined('ABSPATH')) exit;

class AK_View_Counter_Db
{
    const VIEW_COUNT_TABLE = 'ak_viewcount';

    /**
     * Counts up
     * @param int $post_id
     * @param string $post_type
     * @return false|int
     */
    public static function count_up(int $post_id, string $post_type)
    {
        if(empty($post_id) || empty($post_type)) return false;

        global $wpdb;

        $sql = "INSERT INTO " . $wpdb->prefix . self::VIEW_COUNT_TABLE . " (post_id, post_type, views) 
                VALUES (%d, %s, %d) ON DUPLICATE KEY UPDATE views = views + %d";

        return $wpdb->query( $wpdb->prepare($sql, $post_id, $post_type, 1, 1));
    }

    /**
     * Get the view count of a specific post
     * @param int $post_id
     * @return false|int
     */
    public static function get_view_count(int $post_id) {
        if(empty($post_id)) return false;

        global $wpdb;

        $query = $wpdb->get_results($wpdb->prepare("SELECT `views` FROM " . $wpdb->base_prefix . self::VIEW_COUNT_TABLE . " WHERE `post_id` = %d",
            $post_id
        ), ARRAY_A);

        if(empty($query)) return 0;

        return (int) wp_list_pluck($query, 'views')[0];
    }

    /**
     * Get top five most-viewed by post type
     * @param string $post_type
     * @return array
     */
    public static function get_top_views(string $post_type = '') {
	    global $wpdb;

		$table = $wpdb->base_prefix . self::VIEW_COUNT_TABLE;

		if($post_type) {
			$top_views_query =
				$wpdb->prepare("SELECT post_id, views FROM " . $table . " 
									  WHERE post_type = %s
									  ORDER BY views DESC
									  LIMIT 5",
					$post_type);
		} else {
			$top_views_query = "SELECT post_id, views FROM " . $table . "
							    ORDER BY views DESC
							    LIMIT 5";
		}

	    return $wpdb->get_results($top_views_query, ARRAY_A);
    }

    /**
     * Remove the row of view count by post id
     * @param int $post_id
     * @return bool|int
     */
    public static function delete_view_count_row(int $post_id)
    {
        if (empty($post_id)) return false;

        global $wpdb;

        return $wpdb->delete( $wpdb->base_prefix . self::VIEW_COUNT_TABLE, ['post_id' => $post_id], ['%d']);
    }
}
