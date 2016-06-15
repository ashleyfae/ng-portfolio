<?php
/**
 * Install
 *
 * @package   ng-portfolio
 * @copyright Copyright (c) 2016, Ashley Fae
 * @license   GPL2+
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Install
 *
 * Runs on plugin install. Sets up the custom post types and
 * flushes the rewrite rules to initiate the new 'portfolio' slug.
 *
 * @see   ng_portfolio_run_install()
 *
 * @param bool $network_wide
 *
 * @global     $wpdb
 *
 * @since 1.0
 * @return void
 */
function ng_portfolio_install( $network_wide = false ) {
	global $wpdb;

	if ( is_multisite() && $network_wide ) {

		foreach ( $wpdb->get_col( "SELECT blog_id FROM $wpdb->blogs LIMIT 100" ) as $blog_id ) {
			switch_to_blog( $blog_id );
			ng_portfolio_run_install();
			restore_current_blog();
		}

	} else {

		ng_portfolio_run_install();

	}
}

register_activation_hook( NG_PORTFOLIO_FILE, 'ng_portfolio_install' );

/**
 * Runs Installation Process
 *
 * @since 1.0
 * @return void
 */
function ng_portfolio_run_install() {
	// Register CPT.
	ng_portfolio_register_cpt();

	// Clear the permalinks
	flush_rewrite_rules( false );
}