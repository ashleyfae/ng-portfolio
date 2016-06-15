<?php
/**
 * Load Front-End Assets
 *
 * @package   ng-portfolio
 * @copyright Copyright (c) 2016, Ashley Fae
 * @license   GPL2+
 */

/**
 * Enqueue Assets
 *
 * @since 1.0
 * @return void
 */
function ng_portfolio_assets() {
	if ( apply_filters( 'ng-portfolio/disable-assets', false ) ) {
		return;
	}

	wp_enqueue_style( 'ng-portfolio', NG_PORTFOLIO_URL . 'assets/css/ng-portfolio.css', array(), NG_PORTFOLIO_VERSION );
}

add_action( 'wp_enqueue_scripts', 'ng_portfolio_assets' );