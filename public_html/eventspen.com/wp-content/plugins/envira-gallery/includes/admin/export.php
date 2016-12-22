<?php
/**
 * Export class.
 *
 * @since 1.0.0
 *
 * @package Envira_Gallery
 * @author  Thomas Griffin
 */
class Envira_Gallery_Export {

    /**
     * Holds the class object.
     *
     * @since 1.0.0
     *
     * @var object
     */
    public static $instance;

    /**
     * Path to the file.
     *
     * @since 1.0.0
     *
     * @var string
     */
    public $file = __FILE__;

    /**
     * Holds the base class object.
     *
     * @since 1.0.0
     *
     * @var object
     */
    public $base;

    /**
     * Primary class constructor.
     *
     * @since 1.0.0
     */
    public function __construct() {

        // Load the base class object.
        $this->base = Envira_Gallery::get_instance();

        // Export a gallery.
        $this->export_gallery();

    }

    /**
     * Exports an Envira gallery.
     *
     * @since 1.0.0
     *
     * @return null Return early if failing proper checks to export the gallery.
     */
    public function export_gallery() {

        if ( ! $this->has_exported_gallery() ) {
            return;
        }

        if ( ! $this->verify_exported_gallery() ) {
            return;
        }

        if ( ! $this->can_export_gallery() ) {
            return;
        }

        // Ignore the user aborting the action.
        ignore_user_abort( true );

        // Grab the proper data.
        $post_id = absint( $_POST['envira_post_id'] );
        $data    = get_post_meta( $post_id, '_eg_gallery_data', true );

        // Append the in_gallery data checker to the data array.
        $data['in_gallery'] = get_post_meta( $post_id, '_eg_in_gallery', true );

        // Allow Addons to add to the Gallery export
        $data = apply_filters( 'envira_gallery_export_gallery_data', $data, $post_id );

        // Set the proper headers.
        nocache_headers();
        header( 'Content-Type: application/json; charset=utf-8' );
        header( 'Content-Disposition: attachment; filename=envira-gallery-' . $post_id . '-' . date( 'm-d-Y' ) . '.json' );
        header( 'Expires: 0' );

        // Make the settings downloadable to a JSON file and die.
        die( json_encode( $data ) );

    }

    /**
     * Helper method to determine if a gallery export is available.
     *
     * @since 1.0.0
     *
     * @return bool True if an exported gallery is available, false otherwise.
     */
    public function has_exported_gallery() {

        return ! empty( $_POST['envira_export'] );

    }

    /**
     * Helper method to determine if a gallery export nonce is valid and verified.
     *
     * @since 1.0.0
     *
     * @return bool True if the nonce is valid, false otherwise.
     */
    public function verify_exported_gallery() {

        return isset( $_POST['envira-gallery-export'] ) && wp_verify_nonce( $_POST['envira-gallery-export'], 'envira-gallery-export' );

    }

    /**
     * Helper method to determine if the user can actually export the gallery.
     *
     * @since 1.0.0
     *
     * @return bool True if the user can export the gallery, false otherwise.
     */
    public function can_export_gallery() {

        $manage_options = current_user_can( 'manage_options' );
        return apply_filters( 'envira_gallery_export_cap', $manage_options );

    }

    /**
     * Returns the singleton instance of the class.
     *
     * @since 1.0.0
     *
     * @return object The Envira_Gallery_Export object.
     */
    public static function get_instance() {

        if ( ! isset( self::$instance ) && ! ( self::$instance instanceof Envira_Gallery_Export ) ) {
            self::$instance = new Envira_Gallery_Export();
        }

        return self::$instance;

    }

}

// Load the export class.
$envira_gallery_export = Envira_Gallery_Export::get_instance();