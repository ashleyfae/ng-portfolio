<?php
/**
 * Portfolio Meta Boxes
 *
 * @package   ng-portfolio
 * @copyright Copyright (c) 2016, Ashley Fae
 * @license   GPL2+
 */

/**
 * Register Meta Boxes
 *
 * @since 1.0
 * @return void
 */
function ng_portfolio_register_meta_boxes() {
	$post_types = array( 'portfolio' );

	foreach ( apply_filters( 'ng-portfolio/meta-box/cpts', $post_types ) as $cpt ) {
		// Project Details
		add_meta_box( 'ng-portfolio-details', esc_html__( 'Project Details', 'ng-portfolio' ), 'ng_portfolio_render_details_meta_box', $cpt, 'normal', 'default' );

		// Gallery
		add_meta_box( 'ng-portfolio-gallery', esc_html__( 'Gallery', 'ng-portfolio' ), 'ng_portfolio_render_gallery_meta_box', $cpt, 'side', 'low' );

		do_action( 'ng-portfolio/meta-box/register', $cpt );
	}
}

add_action( 'add_meta_boxes', 'ng_portfolio_register_meta_boxes' );

/**
 * Render: Project Details
 *
 * @param WP_Post $post
 *
 * @since 1.0
 * @return void
 */
function ng_portfolio_render_details_meta_box( $post ) {

	$project_type = get_post_meta( $post->ID, 'ng_portfolio_project_type', true );
	$project_url  = get_post_meta( $post->ID, 'ng_portfolio_project_url', true );

	do_action( 'ng-portfolio/meta-box/details/before', $post );
	?>
	<div class="ng-portfolio-field">
		<label for="ng_portfolio_project_type"><?php esc_html_e( 'Project Type', 'ng-portfolio' ); ?></label>
		<div>
			<input type="text" id="ng_portfolio_project_type" name="ng_portfolio_project_type" value="<?php echo esc_attr( $project_type ); ?>">
		</div>
	</div>

	<div class="ng-portfolio-field">
		<label for="ng_portfolio_project_url"><?php esc_html_e( 'Project URL', 'ng-portfolio' ); ?></label>
		<div>
			<input type="url" id="ng_portfolio_project_url" name="ng_portfolio_project_url" value="<?php echo esc_attr( $project_url ); ?>">
		</div>
	</div>
	<?php
	do_action( 'ng-portfolio/meta-box/details/after', $post );

	wp_nonce_field( 'save_ng_portfolio_meta', 'ng_portfolio_nonce' );

}

/**
 * Render: Gallery Box
 *
 * @param WP_Post $post
 *
 * @since 1.0
 * @return void
 */
function ng_portfolio_render_gallery_meta_box( $post ) {

	$saved_images = get_post_meta( $post->ID, 'ng_portfolio_gallery', true );
	$attachments  = explode( ',', $saved_images );

	do_action( 'ng-portfolio/meta-box/gallery/before-gallery', $attachments, $post );
	?>
	<div id="portfolio-gallery-container">
		<ul id="portfolio-images">
			<?php
			if ( $attachments && is_array( $attachments ) ) {
				foreach ( $attachments as $attachment_id ) {
					$attachment = wp_get_attachment_image( $attachment_id, 'thumbnail' );

					if ( empty( $attachment ) ) {
						continue;
					}

					do_action( 'ng-portfolio/meta-box/gallery/before-image', $attachment_id, $post );
					?>
					<li class="image" data-attachment_id="<?php echo esc_attr( $attachment_id ); ?>">
						<?php echo $attachment; ?>
						<div class="actions">
							<a href="#" class="delete" title="<?php esc_attr_e( 'Delete image', 'ng-portfolio' ); ?>"><?php esc_html_e( 'Delete', 'ng-portfolio' ); ?></a>
						</div>
					</li>
					<?php
					do_action( 'ng-portfolio/meta-box/gallery/after-image', $attachment_id, $post );
				}
			}
			?>
		</ul>

		<?php do_action( 'ng-portfolio/meta-box/gallery/after-gallery', $attachments, $post ); ?>

		<input type="hidden" id="ng_portfolio_gallery" name="ng_portfolio_gallery" value="<?php echo esc_attr( $saved_images ); ?>">
	</div>

	<p id="add-portfolio-images" class="hide-if-no-js">
		<a href="#" data-choose="<?php esc_attr_e( 'Add Gallery Images', 'ng-portfolio' ); ?>" data-update="<?php esc_attr_e( 'Add to gallery', 'ng-portfolio' ); ?>" data-delete="<?php esc_attr_e( 'Delete image', 'ng-portfolio' ); ?>" data-text="<?php esc_attr_e( 'Delete', 'ng-portfolio' ); ?>"><?php esc_html_e( 'Add gallery images', 'ng-portfolio' ); ?></a>
	</p>
	<?php
	do_action( 'ng-portfolio/meta-box/gallery/after-add-link', $attachments, $post );

}

/**
 * Save Portfolio Meta
 *
 * @param int $post_id
 *
 * @since 1.0
 * @return void
 */
function ng_portfolio_save_meta( $post_id, $post ) {

	// Missing or invalid nonce.
	if ( ! isset( $_POST['ng_portfolio_nonce'] ) || ! wp_verify_nonce( $_POST['ng_portfolio_nonce'], 'save_ng_portfolio_meta' ) ) {
		return;
	}

	// No save on auto save.
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	// Check permissions.
	if ( ! current_user_can( 'edit_page', $post_id ) ) {
		return;
	}

	// Let's save, biatch!

	// Save project details fields.
	$fields = array(
		'ng_portfolio_project_type' => 'sanitize_text_field',
		'ng_portfolio_project_url'  => 'esc_url_raw'
	);

	foreach ( apply_filters( 'ng-portfolio/meta-box/save/fields', $fields ) as $field => $callback ) {

		// Field doesn't exist - delete it.
		if ( ! isset( $_POST[ $field ] ) ) {
			delete_post_meta( $post_id, $field );
		}

		$sanitized_field = call_user_func( $callback, $_POST[ $field ] );

		update_post_meta( $post_id, $field, $sanitized_field );
	}

	// Save gallery.
	$attachment_ids = isset( $_POST['ng_portfolio_gallery'] ) ? array_map( 'absint', explode( ',', $_POST['ng_portfolio_gallery'] ) ) : array();
	update_post_meta( $post_id, 'ng_portfolio_gallery', apply_filters( 'ng-portfolio/meta-box/save/gallery', implode( ',', $attachment_ids ), $post ) );


}

add_action( 'save_post', 'ng_portfolio_save_meta', 10, 2 );