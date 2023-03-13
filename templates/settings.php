<?php
/**
 * Settings page UI
 * Let user turns on/off view counter from front end and selects the location of view counter
 */
if (!defined('ABSPATH')) exit;

$is_display = get_option('ak_view_count_display');
$display_position = get_option('ak_view_count_location');
?>
<div class="wrap">
    <h1>AK View Counter Settings</h1>
    <form method="post" action="options.php">

	    <?php settings_fields('ak-view-count'); ?>
	    <?php do_settings_sections('ak-view-counter-settings'); ?>

        <table class="form-table">
            <tr valign="top">
                <th scope="row">Display</th>
                <td>
                    <input type="checkbox"
                           name="ak_view_count_display"
                           value="1"
                           <?php if($is_display) echo 'checked'; ?> />
                </td>
            </tr>
            <tr valign="top">
                <th scope="row">Location</th>
                <td>
                    <input type="radio"
                           id="ak_view_count_location"
                           name="ak_view_count_location"
                           value="top"
                           <?php if($display_position === 'top') echo 'checked'; ?> />
                    <label for="ak_view_count_location">Top</label><br>

                    <input type="radio"
                           id="ak_view_count_location"
                           name="ak_view_count_location"
                           value="bottom"
                           <?php if($display_position === 'bottom') echo 'checked'; ?> />
                    <label for="ak_view_count_location">Bottom</label>
                </td>
            </tr>
        </table>

        <?php submit_button(); ?>

    </form>
</div>
