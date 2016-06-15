<?php
/**
 * Custom Post Type
 *
 * @package   ng-portfolio
 * @copyright Copyright (c) 2016, Ashley Fae
 * @license   GPL2+
 */

/**
 * Register 'portfolio' CPT
 *
 * @since 1.0
 * @return void
 */
function ng_portfolio_register_cpt() {

	$labels = array(
		'name'                  => _x( 'Works', 'Post Type General Name', 'ng-portfolio' ),
		'singular_name'         => _x( 'Work', 'Post Type Singular Name', 'ng-portfolio' ),
		'menu_name'             => __( 'Portfolio', 'ng-portfolio' ),
		'name_admin_bar'        => __( 'Portfolio', 'ng-portfolio' ),
		'archives'              => __( 'Portfolio Archives', 'ng-portfolio' ),
		'parent_item_colon'     => __( 'Parent Work:', 'ng-portfolio' ),
		'all_items'             => __( 'All Works', 'ng-portfolio' ),
		'add_new_item'          => __( 'Add New Work', 'ng-portfolio' ),
		'add_new'               => __( 'Add New', 'ng-portfolio' ),
		'new_item'              => __( 'New Work', 'ng-portfolio' ),
		'edit_item'             => __( 'Edit Work', 'ng-portfolio' ),
		'update_item'           => __( 'Update Work', 'ng-portfolio' ),
		'view_item'             => __( 'View Work', 'ng-portfolio' ),
		'search_items'          => __( 'Search Work', 'ng-portfolio' ),
		'not_found'             => __( 'Not found', 'ng-portfolio' ),
		'not_found_in_trash'    => __( 'Not found in Trash', 'ng-portfolio' ),
		'featured_image'        => __( 'Featured Image', 'ng-portfolio' ),
		'set_featured_image'    => __( 'Set featured image', 'ng-portfolio' ),
		'remove_featured_image' => __( 'Remove featured image', 'ng-portfolio' ),
		'use_featured_image'    => __( 'Use as featured image', 'ng-portfolio' ),
		'insert_into_item'      => __( 'Insert into work', 'ng-portfolio' ),
		'uploaded_to_this_item' => __( 'Uploaded to this work', 'ng-portfolio' ),
		'items_list'            => __( 'Portfolio list', 'ng-portfolio' ),
		'items_list_navigation' => __( 'Portfolio list navigation', 'ng-portfolio' ),
		'filter_items_list'     => __( 'Filter portfolio list', 'ng-portfolio' ),
	);

	$args   = array(
		'label'               => __( 'Work', 'ng-portfolio' ),
		'labels'              => apply_filters( 'ng-portfolio/cpt/labels', $labels ),
		'supports'            => array( 'title', 'editor', 'thumbnail', ),
		'hierarchical'        => true,
		'public'              => true,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'menu_position'       => 25,
		'menu_icon'           => 'dashicons-format-gallery',
		'show_in_admin_bar'   => true,
		'show_in_nav_menus'   => false,
		'can_export'          => true,
		'has_archive'         => false, // @todo check
		'exclude_from_search' => true,
		'publicly_queryable'  => true,
		'capability_type'     => 'post',
	);

	register_post_type( 'portfolio', apply_filters( 'ng-portfolio/cpt/args', $args ) );

}

add_action( 'init', 'ng_portfolio_register_cpt' );