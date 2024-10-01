<?php
/**
 * 
 * Plugin's main Admin page (Analytics Dashboard) that shows Subsite-wise Metrics between post content and users
 * 
 **/ 

$analytics_data = get_analytics_data(); // Assuming there's a method to get analytics data
?>
<div class="wrap">
    <h1><?php esc_html_e( 'Analytics Dashboard', 'text-domain' ); ?></h1>
    <h2><?php esc_html_e( 'Subsite-wise Metrics', 'text-domain' ); ?></h2>
    <table class="wp-list-table widefat fixed striped">
        <thead>
            <tr>
                <th scope="col"><?php esc_html_e( 'Subsite', 'multiste-manager' ); ?></th>
                <th scope="col"><?php esc_html_e( 'Total Posts', 'multiste-manager' ); ?></th>
                <th scope="col"><?php esc_html_e( 'Total Users', 'multiste-manager' ); ?></th>
                <th scope="col"><?php esc_html_e( 'Published Posts', 'multiste-manager' ); ?></th>
                <th scope="col"><?php esc_html_e( 'Draft Posts', 'multiste-manager' ); ?></th>
                <th scope="col"><?php esc_html_e( 'Top Post', 'multiste-manager' ); ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ( $analytics_data as $data ) : // Removed $subsite_id ?>
                <tr>
                    <td><?php echo esc_html( $data['subsite_name'] ); ?></td>
                    <td><?php echo esc_html( $data['total_posts'] ); ?></td>
                    <td><?php echo esc_html( $data['total_users'] ); ?></td>
                    <td><?php echo esc_html( $data['post_status']['published'] ); ?></td>
                    <td><?php echo esc_html( $data['post_status']['draft'] ); ?></td>
                    <td>
                        <?php if ( ! empty( $data['top_post'] ) ) : ?>
                            <?php if ( ! empty( $data['top_post']['permalink'] ) ) : ?>
                                <a href="<?php echo esc_url( $data['top_post']['permalink'] ); ?>" target="_blank">
                                    <?php echo esc_html( $data['top_post']['title'] ); ?>
                                </a>
                            <?php else : ?>
                                <?php echo esc_html( $data['top_post']['title'] ); ?>
                            <?php endif; ?>
                        <?php else : ?>
                            <?php esc_html_e( 'No Posts Available', 'multiste-manager' ); ?>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>