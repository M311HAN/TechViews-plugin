<?php
/*
Plugin Name: TechViews
Plugin URI: http://localhost/cooltech
Description: A plugin that tracks and displays view counts for posts.
Version: 1.0
Author: Melihhan
Author URI: http://localhost/cooltech
License: UNLICENSED
*/

// Track the number of views each post gets.
function techviews_track_view() {
    if (!is_single()) return;

    global $post;
    $current_hour = date('Y-m-d H');
    $last_viewed_hour = get_post_meta($post->ID, 'techviews_last_viewed_hour', true);
    $total_views = (int) get_post_meta($post->ID, 'techviews_total_views', true); // Retrieve total views

    // Check if the last viewed hour is the current hour.
    if ($last_viewed_hour === $current_hour) {
        // Increment the view count for the current hour.
        $views = (int) get_post_meta($post->ID, 'techviews_hourly_views', true);
        $views++;
    } else {
        $views = 1;
    }
    $total_views++; // Increment total views

    update_post_meta($post->ID, 'techviews_hourly_views', $views);
    update_post_meta($post->ID, 'techviews_last_viewed_hour', $current_hour);
    // Update total views
    update_post_meta($post->ID, 'techviews_total_views', $total_views); 
}

add_action('wp_head', 'techviews_track_view');

// Shortcode to display the "Hot Right Now" posts list.
add_shortcode('techviews_hot_right_now', 'techviews_hot_right_now_list');

// Query parameters to get top 10 posts by hourly view count, excluding those with 0 views.
function techviews_hot_right_now_list() {
    $searchParams = [
        'posts_per_page' => 10,
        'post_type' => 'post',
        'post_status' => 'publish',
        'meta_key' => 'techviews_hourly_views',
        'orderby' => 'meta_value_num',
        'order' => 'DESC',
        'meta_query' => [
            [
                'key' => 'techviews_hourly_views',
                'value' => '0',
                'compare' => '>',
                'type' => 'NUMERIC'
            ],
        ],
    ];

    $list = new WP_Query($searchParams);

    // Generate the list of top posts.
    if ($list->have_posts()) {
        echo '<ol>';
        while ($list->have_posts()) {
            $list->the_post();
            // Retrieve hourly view count for each post.
            $views = (int) get_post_meta(get_the_ID(), 'techviews_hourly_views', true);
            // Display the post title and view count, correctly handling singular and plural.
            echo '<li><a href="' . get_permalink() . '">' . get_the_title() . '</a> - ' . $views . ' ' . ($views === 1 ? 'view' : 'views') . ' this hour</li>';
        }
        // Output the list.
        echo '</ol>';
    } else {
        echo 'Stay tuned for this hours trending posts!';
    }
}

// Function to display the view count.
function techviews_display_views() {
    global $post;
    $views = get_post_meta($post->ID, 'techviews_total_views', true);
    $views = empty($views) ? 0 : (int)$views;

    // Format views for thousands (K) and millions (M)
    if ($views >= 1000000) {
        $views_formatted = round($views / 1000000, 1);
        $views_formatted = ($views_formatted == intval($views_formatted)) ? intval($views_formatted) : $views_formatted;
        $views_formatted .= 'M';
    } elseif ($views >= 1000) {
        $views_formatted = round($views / 1000, 1);
        $views_formatted = ($views_formatted == intval($views_formatted)) ? intval($views_formatted) : $views_formatted;
        $views_formatted .= 'K';
    } else {
        $views_formatted = $views;
    }
    // Choose the correct singular or plural form of "view"
    $view_text = ($views === 1) ? 'view' : 'views';
    return "View count: " . $views_formatted . ' ' . $view_text;
}

// Activation hook to set up a scheduled event.
function techviews_activate() {
    if (!wp_next_scheduled('techviews_reset_hourly_views')) {
        wp_schedule_event(time(), 'hourly', 'techviews_reset_hourly_views');
    }
}
register_activation_hook(__FILE__, 'techviews_activate');

// Deactivation hook to remove the scheduled event.
function techviews_deactivate() {
    $timestamp = wp_next_scheduled('techviews_reset_hourly_views');
    if ($timestamp) {
        wp_unschedule_event($timestamp, 'techviews_reset_hourly_views');
    }
}
// Clear the scheduled cron event upon plugin deactivation.
register_deactivation_hook(__FILE__, 'techviews_deactivate');

// Callback for the scheduled event to reset hourly views.
function techviews_reset_hourly_views_callback() {
    $args = array(
        'post_type' => 'post',
        'post_status' => 'publish',
        'posts_per_page' => -1, // Retrieve all posts
        'fields' => 'ids', // Only get post IDs to improve performance
    );
    $posts = get_posts($args);
    // Loop through all posts and reset their hourly views count
    foreach ($posts as $post_id) {
        update_post_meta($post_id, 'techviews_hourly_views', 0);
    }
}
add_action('techviews_reset_hourly_views', 'techviews_reset_hourly_views_callback');

// Add custom interval for the cron event.
function techviews_cron_add_hourly($schedules) {
    $schedules['hourly'] = array(
        'interval' => 3600, // Number of seconds in an hour
        'display' => __('Once Hourly')
    );
    // Add 'hourly' schedule to the existing schedules.
    return $schedules;
}
add_filter('cron_schedules', 'techviews_cron_add_hourly');

// Append view count to the post content.
function append_view_count_to_content($content) {
    if (is_single() && in_the_loop() && is_main_query()) {
        // Check if we're inside the main loop in a single Post.
        global $post;
        $views = (int) get_post_meta($post->ID, 'techviews_total_views', true);  // Use total views
        $view_count_text = 'View count: ' . $views . ' ' . ($views === 1 ? 'view' : 'views');
        $content = $view_count_text . "\n" . $content;  // Prepend view count to the content
    }
    return $content;
}
// Append view count if on a single post page, within the main query.
add_filter('the_content', 'append_view_count_to_content');
