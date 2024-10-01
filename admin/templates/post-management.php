<?php
/**
 * Post Management admin page template that contains fetch subsite posts and give edit and delete options on each posts.
 */
?>

<div class="wrap">
    <h1><?php esc_html_e( 'Fetch Posts from Subsites', 'text-domain' ); ?></h1>
    <form method="post">
        <?php wp_nonce_field( 'ampm_fetch_posts_action', 'ampm_fetch_posts_nonce' ); ?>
        
        <label for="subsite_ids"><?php esc_html_e( 'Select Subsite:', 'text-domain' ); ?></label><br>
        <select name="subsite_ids[]" id="subsite_ids" multiple required>
            <?php
            $blogs = get_sites();
            $selected_subsite_ids = isset( $_POST['subsite_ids'] ) ? array_map( 'intval', $_POST['subsite_ids'] ) : [];

            foreach ( $blogs as $blog ) {
                $selected = in_array( $blog->blog_id, $selected_subsite_ids, true ) ? 'selected' : '';
                echo sprintf(
                    '<option value="%s" %s>%s</option>',
                    esc_attr( $blog->blog_id ),
                    esc_attr( $selected ),
                    esc_html( $blog->blogname )
                );
            }
            ?>
        </select><br><br>

        <input type="submit" name="fetch_posts" value="<?php esc_attr_e( 'Fetch Posts', 'text-domain' ); ?>" class="button button-primary">
    </form>

    <?php if ( isset( $_POST['fetch_posts'] ) ) : ?>
        <?php
        if ( ! isset( $_POST['ampm_fetch_posts_nonce'] ) || ! wp_verify_nonce( sanitize_key( $_POST['ampm_fetch_posts_nonce'] ), 'ampm_fetch_posts_action' ) ) {
            ?>
            <div class="error notice">
                <p><?php esc_html_e( 'Invalid form submission.', 'multiste-manager' ); ?></p>
            </div>
            <?php
            return;
        }

        $subsite_ids = isset( $_POST['subsite_ids'] ) ? array_map( 'intval', $_POST['subsite_ids'] ) : [];
        if ( empty( $subsite_ids ) ) {
            ?>
            <div class="error notice">
                <p><?php esc_html_e( 'Please select at least one subsite.', 'multiste-manager' ); ?></p>
            </div>
            <?php
        } else {
            $fetched_posts = ampm_fetch_latest_posts_from_subsites( $subsite_ids );

            if ( is_wp_error( $fetched_posts ) ) {
                ?>
                <div class="error notice">
                    <p><?php echo esc_html( $fetched_posts->get_error_message() ); ?></p>
                </div>
                <?php
            } else {
                ?>
                <h2><?php esc_html_e( 'Fetched Posts', 'multiste-manager' ); ?></h2>
                <table class="widefat">
                    <thead>
                        <tr>
                            <th><?php esc_html_e( 'Title', 'multiste-manager' ); ?></th>
                            <th><?php esc_html_e( 'Subsite', 'multiste-manager' ); ?></th>
                            <th><?php esc_html_e( 'Actions', 'multiste-manager' ); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ( $fetched_posts as $fetched_post ) : ?>
                            <tr>
                                <td>
                                    <a href="<?php echo esc_url( $fetched_post['link'] ); ?>">
                                        <?php echo esc_html( $fetched_post['title'] ); ?>
                                    </a>
                                </td>
                                <td><?php echo esc_html( $fetched_post['subsite_name'] ); ?></td>
                                <td>
                                    <?php 
                                    if ( isset( $fetched_post['blog_id'] ) ) {
                        
                                        switch_to_blog( $fetched_post['blog_id'] );  // phpcs:disable WordPressVIPMinimum.Functions.RestrictedFunctions.switch_to_blog

                                    ?>
                                        <a href="<?php echo esc_url( get_edit_post_link( $fetched_post['ID'] ) ); ?>" class="button">
                                            <?php esc_html_e( 'Edit', 'multiste-manager' ); ?>
                                        </a>
                                        <a href="<?php echo esc_url( get_delete_post_link( $fetched_post['ID'] ) ); ?>" class="button" onclick="return confirm('<?php echo esc_js( esc_html__( 'Are you sure you want to delete this post?', 'multiste-manager' ) ); ?>');">
                                            <?php esc_html_e( 'Delete', 'multiste-manager' ); ?>
                                        </a>
                                    <?php 
                                        restore_current_blog(); 
                                    } else {
                                        esc_html_e( 'Blog ID not found', 'multiste-manager' );
                                    }
                                    ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <?php
            }
        }
        ?>
    <?php endif; ?>
</div>