<?php
/**
 * Enhancement admin page template that contains all enhancements settings for multisite.
 */

// Check if form has been submitted and handle the form processing
if ( isset( $_POST['ampm_save_enhancements'] ) ) {
    // Verify the nonce to ensure the request is valid
    if ( ! isset( $_POST['ampm_enhancements_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( $_POST['ampm_enhancements_nonce']), 'ampm_save_enhancements_action' ) ) {
        // If nonce verification fails, display an error message
        echo '<div class="error notice"><p>' . esc_html__( 'Invalid form submission.', 'multiste-manager' ) . '</p></div>';
    } else {
        // Checkbox is checked if present in POST; otherwise set to 0 (unchecked)
        $show_site_id = isset( $_POST['ampm_show_site_id'] ) ? 1 : 0;
        
        // Update the site option with the checkbox value
        update_site_option( 'ampm_show_site_id', $show_site_id );

        // Show success message
        echo '<div class="updated notice is-dismissible"><p>' . esc_html__( 'Enhancements settings updated.', 'multiste-manager' ) . '</p></div>';
    }
}

// Retrieve the checkbox value (default is 0) AFTER form processing
$show_site_id = get_site_option( 'ampm_show_site_id', 0 );

?>

<div class="wrap">
    <h1><?php esc_html_e( 'Enhancements', 'multiste-manager' ); ?></h1>
    <form method="post" action="">
        <?php wp_nonce_field( 'ampm_save_enhancements_action', 'ampm_enhancements_nonce' ); // Add nonce field for security ?>
        <table class="form-table">
            <tr>
                <th scope="row"><?php esc_html_e( 'Show Site ID in Sites', 'multiste-manager' ); ?></th>
                <td>
                    <label for="ampm_show_site_id">
                        <input type="checkbox" name="ampm_show_site_id" id="ampm_show_site_id" value="1" <?php checked( 1, $show_site_id ); ?> />
                        <?php esc_html_e( 'Show Site ID in Sites List', 'multiste-manager' ); ?>
                    </label>
                </td>
            </tr>
        </table>
        <?php submit_button( esc_html__( 'Save Settings', 'multiste-manager' ), 'primary', 'ampm_save_enhancements' ); ?>
    </form>
</div>
