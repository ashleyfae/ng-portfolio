<?php
/**
 * functions.php
 *
 * @package   ng-portfolio
 * @copyright Copyright (c) 2016, Ashley Fae
 * @license   GPL2+
 */

/**
 * Get Gallery
 *
 * @param int   $post_id
 * @param array $size
 *
 * @since 1.0
 * @return string
 */
function ng_portfolio_get_gallery( $post_id = 0, $size = array( 150, 150, true ) ) {
	$images = get_post_meta( $post_id, 'ng_portfolio_gallery', true );

	if ( empty( $images ) ) {
		return false;
	}

	$attachments = explode( ',', $images );

	ob_start();
	?>
	<div class="ng-portfolio-gallery">
		<?php foreach ( $attachments as $attachment_id ) : ?>
			<?php
			$image_url         = wp_get_attachment_image_url( $attachment_id, 'full' );
			$image_url_resized = aq_resize( $image_url, $size[0], $size[1], $size[2] );
			$image_url_final   = $image_url_resized ? $image_url_resized : $image_url;
			$alt               = get_post_meta( $attachment_id, '_wp_attachment_image_alt', true );
			?>
			<a href="<?php echo esc_url( $image_url ); ?>">
				<img src="<?php echo esc_url( $image_url_final ); ?>" alt="<?php echo esc_attr( wp_strip_all_tags( $alt ) ); ?>">
			</a>
		<?php endforeach; ?>
	</div>
	<?php
	$output = ob_get_clean();

	return apply_filters( 'ng-portfolio/get-gallery/output', $output );
}

/**
 * Append Gallery to Portfolio Content
 *
 * @param string $content
 *
 * @since 1.0
 * @return string
 */
function ng_portfolio_append_gallery( $content ) {
	if ( get_post_type() != 'portfolio' ) {
		return $content;
	}

	return $content . ng_portfolio_get_gallery( get_the_ID() );
}

add_filter( 'the_content', 'ng_portfolio_append_gallery' );