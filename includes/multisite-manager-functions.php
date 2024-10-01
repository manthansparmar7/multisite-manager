<?php
/**
 * Implement all common functions used for the plugin
 *
 */
/**
 * Fetch analytics data for each subsite.
 *
 * This function loops through all subsites, switches to each subsite, and gathers
 * analytics data such as the total number of posts, users, and the top post with the most comments.
 *
 * @return array Analytics data for all subsites.
 */
function get_analytics_data() {
    $analytics_data = [];
    $sites = get_sites(); // Get all subsites.

    foreach ( $sites as $site ) {
        switch_to_blog( $site->blog_id ); // phpcs:disable WordPressVIPMinimum.Functions.RestrictedFunctions.switch_to_blog

        // Gather analytics data for the current subsite.
        $analytics_data[ $site->blog_id ] = [
            'subsite_name'  => get_bloginfo( 'name' ),
            'total_posts'   => wp_count_posts()->publish,
            'total_users'   => count_users()['total_users'],
            'post_status'   => [
                'published' => wp_count_posts()->publish,
                'draft'     => wp_count_posts()->draft,
            ],
            'top_post'      => get_top_post(), // Get the top post based on comment count.
        ];

        restore_current_blog(); // Restore to the main site.
    }

    return $analytics_data; // Return analytics data for all subsites.
}

/**
 * Fetch the top post with the highest comment count.
 *
 * This function fetches the post with the most comments for the current subsite.
 *
 * @return array The top post's title and permalink.
 */
function get_top_post() {
    $args = [
        'posts_per_page' => 1,
        'orderby'        => 'comment_count', // Order by the number of comments.
        'order'          => 'DESC',           // Ensure it orders in descending order.
        'post_status'    => 'publish',        // Only get published posts.
    ];

    $top_post_query = new WP_Query( $args ); // Create a new WP_Query instance.

    // Check if there is a top post and return its data.
    if ( $top_post_query->have_posts() ) {
        $top_post_query->the_post(); // Set up the post data.

        return [
            'title'     => get_the_title(), // Get the title of the post.
            'permalink' => get_permalink(),  // Get the permalink of the post.
        ];
    }

    wp_reset_postdata(); // Reset post data.

    return []; // Return an empty array if no top post is found.
}

/**
 * Fetch subsite-wise analytics data, including plugin information.
 *
 * This function loops through all subsites, gathers analytics data (total posts, users, plugin counts),
 * and includes details like installed and active plugins.
 *
 * @return array Analytics data for all subsites.
 */
function ampm_get_subsite_analytics_data() {
    $subsite_ids = get_sites( array( 'fields' => 'ids' ) ); // Get all subsite IDs.
    $analytics_data = array();

    foreach ( $subsite_ids as $subsite_id ) {
        switch_to_blog( $subsite_id ); // phpcs:disable WordPressVIPMinimum.Functions.RestrictedFunctions.switch_to_blog

        // Gather the analytics data for the subsite.
        $installed_plugins = get_plugins(); // Get all installed plugins.
        $active_plugins = get_option( 'active_plugins', array() ); // Get active plugins.

        $analytics_data[ $subsite_id ] = array(
            'subsite_name'        => get_bloginfo( 'name' ),
            'total_posts'         => wp_count_posts()->publish,
            'total_users'         => count_users()['total_users'],
            'post_status'         => array(
                'published'        => wp_count_posts()->publish,
                'draft'            => wp_count_posts()->draft,
            ),
            'total_page_views'    => 0, // Placeholder for total page views.
            'installed_plugins'   => count( $installed_plugins ), // Count of installed plugins.
            'active_plugins'      => count( $active_plugins ), // Count of active plugins.
            'deactivated_plugins' => count( $installed_plugins ) - count( $active_plugins ), // Deactivated plugins count.
            'top_post'            => ampm_get_top_post(), // Fetch the top post.
        );

        restore_current_blog(); // Restore to the main site.
    }

    return $analytics_data; // Return analytics data for all subsites.
}

/**
 * Get the top post based on page views.
 *
 * This function fetches the post with the most page views for the current subsite.
 *
 * @return array The top post's title and permalink.
 */
function ampm_get_top_post() {
    $args = array(
        'posts_per_page' => 1,
        'meta_key'       => 'page_views', //phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
        'orderby'        => 'meta_value_num', // Order by page views in descending order.
        'order'          => 'DESC',
    );

    $top_post_query = new WP_Query( $args ); // Query for the top post.
    
    // Check if there are posts and return the top post's title and permalink.
    if ( $top_post_query->have_posts() ) {
        $top_post_query->the_post();
        return array(
            'title'     => get_the_title(),
            'permalink' => get_permalink(),
        );
    }

    // Return 'No Posts' if no top post is found.
    return array( 'title' => 'No Posts', 'permalink' => '' );
}

/**
 * Fetch the latest posts from specified subsites.
 *
 * This function fetches the latest published posts from each of the specified subsites.
 *
 * @param array $subsite_ids Array of subsite IDs.
 * @return array|WP_Error List of posts or WP_Error if an error occurs.
 */
function ampm_fetch_latest_posts_from_subsites( $subsite_ids ) {
    $all_posts = array();

    foreach ( $subsite_ids as $subsite_id ) {
        $blog_details = get_blog_details( $subsite_id ); // Get details for each subsite.

        // Check if the blog exists, if not return an error.
        if ( ! $blog_details ) {
            return new WP_Error( 'invalid_blog', sprintf( __( 'Invalid blog ID: %d', 'text-domain' ), $subsite_id ) );
        }

        $subsite_name = $blog_details->blogname; // Get the subsite name.

        switch_to_blog( $subsite_id ); // Switch to each subsite.

        $args = array(
            'post_type'   => 'post',
            'post_status' => 'publish', // Fetch only published posts.
            'numberposts' => -1, // Fetch all published posts.
        );

        $query = new WP_Query( $args ); // Query for posts.

        // Loop through posts and gather the required data.
        if ( $query->have_posts() ) {
            while ( $query->have_posts() ) {
                $query->the_post();
                $all_posts[] = array(
                    'ID'           => get_the_ID(),
                    'title'        => get_the_title(),
                    'link'         => get_permalink(),
                    'subsite_name' => $subsite_name, // Add subsite name to the array.
                    'blog_id'      => $subsite_id, // Include blog ID for reference.
                );
            }
        }

        wp_reset_postdata(); // Reset post data.
        restore_current_blog(); // Restore to the main site.
    }

    // Return an error if no posts are found.
    if ( empty( $all_posts ) ) {
        return new WP_Error( 'no_posts', __( 'No posts found for the specified subsites.', 'text-domain' ) );
    }

    return $all_posts; // Return the list of posts.
}

// Display the Site ID in the custom column
// Add Site ID column to Network Admin > Sites
function ampm_add_site_id_column($columns) {
    // Retrieve the option to see if the column should be added
    $show_site_id = get_site_option('ampm_show_site_id', false);

    if ($show_site_id) {
        $columns['site_id'] = 'ID'; // Add Site ID column
    }

    return $columns;
}
add_filter('wpmu_blogs_columns', 'ampm_add_site_id_column');
// Display the Site ID in the custom column
function ampm_display_site_id_column($column_name, $blog_id) {
    if ('site_id' === $column_name) {
        echo esc_html($blog_id); // Output the blog/site ID
    }
}
add_action('manage_sites_custom_column', 'ampm_display_site_id_column', 10, 2);