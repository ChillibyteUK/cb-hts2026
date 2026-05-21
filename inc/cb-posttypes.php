<?php
/**
 * Custom Post Types Registration
 *
 * This file contains the code to register custom post types for the theme.
 *
 * @package cb-hts2026
 */

/**
 * Register custom post types for the theme.
 *
 * @return void
 */
function cb_register_post_types() {

	register_post_type(
		'application',
		array(
			'labels'              => array(
				'name'               => 'Applications',
				'singular_name'      => 'Application',
				'add_new_item'       => 'Add New Application',
				'edit_item'          => 'Edit Application',
				'new_item'           => 'New Application',
				'view_item'          => 'View Application',
				'search_items'       => 'Search Applications',
				'not_found'          => 'No applications found',
				'not_found_in_trash' => 'No applications in trash',
			),
			'has_archive'         => false,
			'public'              => true,
			'publicly_queryable'  => true,
			'exclude_from_search' => true,
			'show_in_nav_menus'   => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_rest'        => true,
			'menu_position'       => 26,
			'menu_icon'           => 'dashicons-portfolio',
			'supports'            => array( 'title', 'editor', 'thumbnail' ),
			'capability_type'     => 'post',
			'map_meta_cap'        => true,
			'rewrite'             => array( 'slug' => 'applications' ),
		)
	);

	register_post_type(
		'product',
		array(
			'labels'              => array(
				'name'               => 'Products',
				'singular_name'      => 'Product',
				'add_new_item'       => 'Add New Product',
				'edit_item'          => 'Edit Product',
				'new_item'           => 'New Product',
				'view_item'          => 'View Product',
				'search_items'       => 'Search Products',
				'not_found'          => 'No products found',
				'not_found_in_trash' => 'No products in trash',
			),
			'has_archive'         => false,
			'public'              => true,
			'publicly_queryable'  => true,
			'exclude_from_search' => true,
			'show_in_nav_menus'   => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_rest'        => true,
			'menu_position'       => 26,
			'menu_icon'           => 'dashicons-cart',
			'supports'            => array( 'title', 'editor', 'thumbnail' ),
			'capability_type'     => 'post',
			'map_meta_cap'        => true,
			'rewrite'             => array( 'slug' => 'products' ),
		)
	);

	register_post_type(
		'project',
		array(
			'labels'              => array(
				'name'               => 'Projects',
				'singular_name'      => 'Project',
				'add_new_item'       => 'Add New Project',
				'edit_item'          => 'Edit Project',
				'new_item'           => 'New Project',
				'view_item'          => 'View Project',
				'search_items'       => 'Search Projects',
				'not_found'          => 'No projects found',
				'not_found_in_trash' => 'No projects in trash',
			),
			'has_archive'         => false,
			'public'              => true,
			'publicly_queryable'  => true,
			'exclude_from_search' => true,
			'show_in_nav_menus'   => false,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_rest'        => true,
			'menu_position'       => 26,
			'menu_icon'           => 'dashicons-clipboard',
			'supports'            => array( 'title', 'editor', 'thumbnail' ),
			'capability_type'     => 'post',
			'map_meta_cap'        => true,
		)
	);
}

add_action( 'init', 'cb_register_post_types' );
