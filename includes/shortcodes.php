<?php
/**
 * Shortcodes
 *
 * @package   ng-portfolio
 * @copyright Copyright (c) 2016, Ashley Fae
 * @license   GPL2+
 */

/**
 * Portfolio Grid
 *
 * @param array  $atts
 * @param string $content
 *
 * @since 1.0
 * @return string
 */
function ng_portfolio_grid( $atts, $content = '' ) {

	$args = array(
		'order'     => 'ASC',
		'orderby'   => 'menu_order date',
		'post_type' => 'portfolio'
	);

	$portfolio_query = new WP_Query( apply_filters( 'ng-portfolio/shortcode/query-args', $args ) );

	if ( ! $portfolio_query->have_posts() ) {
		return '';
	}

	$output = '<div id="ng-portfolio-grid">';

	while ( $portfolio_query->have_posts() ) : $portfolio_query->the_post();

		if ( ! has_post_thumbnail( get_the_ID() ) ) {
			continue;
		}

		$size = apply_filters( 'ng-portfolio/shortcode/image-size', array( 550, 450, true ) );

		$image_url         = wp_get_attachment_image_url( get_post_thumbnail_id(), 'full' );
		$image_url_resized = aq_resize( $image_url, $size[0], $size[1], $size[2] );
		$image_url_final   = $image_url_resized ? $image_url_resized : $image_url;

		$output .= '<a href="' . esc_url( get_permalink() ) . '">';

		$output .= '<img src="' . esc_url( apply_filters( 'ng-portfolio/shortcode/image-url', $image_url_final, get_the_ID() ) ) . '" alt="' . esc_attr( wp_strip_all_tags( get_the_title() ) ) . '">';

		$output .= '</a>';

	endwhile;

	wp_reset_postdata();

	$output .= '</div>';

	return apply_filters( 'ng-portfolio/shortcode/output', $output );

}

add_shortcode( 'ng-portfolio', 'ng_portfolio_grid' );