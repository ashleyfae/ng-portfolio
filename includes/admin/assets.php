<?php
/**
 * Load Admin Assets
 *
 * @package   ng-portfolio
 * @copyright Copyright (c) 2016, Ashley Fae
 * @license   GPL2+
 */

/**
 * Load Admin Assests
 *
 * @param string $hook
 *
 * @since 1.0
 * @return void
 */
function ng_portfolio_admin_assets( $hook ) {
	$screen = get_current_screen();

	if ( $screen->post_type != 'portfolio' ) {
		return;
	}

	if ( $screen->base != 'post' ) {
		return;
	}

	/*
	 * CSS
	 */

	wp_enqueue_style( 'ng-portfolio-admin', NG_PORTFOLIO_URL . 'assets/css/admin.css', array(), NG_PORTFOLIO_VERSION );

	/*
	 * JavaScript
	 */

	$deps = array( 'jquery', 'jquery-ui-sortable' );

	wp_enqueue_media();
	wp_enqueue_script( 'ng-portfolio-admin', NG_PORTFOLIO_URL . 'assets/js/admin.js', $deps, NG_PORTFOLIO_VERSION, true );
}

add_action( 'admin_enqueue_scripts', 'ng_portfolio_admin_assets' );