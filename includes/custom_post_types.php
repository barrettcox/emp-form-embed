<?php
/**
 * Custom Post Types
 */

add_action( 'init', 'my_cpts' );

function my_cpts() {
	
	function empforwp_register_cpt($single_label, $plural_label, $type, $slug, $cap_type, $icon, $heirarchical, $supports, $public, $public_query, $has_archive) {
	
	$labels = array(
		'name'               => _x( $plural_label, 'post type general name', 'force5' ),
		'singular_name'      => _x( $single_label, 'post type singular name', 'force5' ),
		'menu_name'          => _x( $plural_label, 'admin menu', 'force5' ),
		'name_admin_bar'     => _x( $single_label, 'add new on admin bar', 'force5' ),
		'add_new'            => _x( 'Add New', $slug, 'force5' ),
		'add_new_item'       => __( 'Add New '.$single_label, 'force5' ),
		'new_item'           => __( 'New '.$single_label, 'force5' ),
		'edit_item'          => __( 'Edit '.$single_label, 'force5' ),
		'view_item'          => __( 'View '.$single_label, 'force5' ),
		'all_items'          => __( 'All '.$plural_label, 'force5' ),
		'search_items'       => __( 'Search '.$plural_label, 'force5' ),
		'parent_item_colon'  => __( 'Parent '.$plural_label.':', 'force5' ),
		'not_found'          => __( 'No '.$plural_label.' found.', 'force5' ),
		'not_found_in_trash' => __( 'No '.$plural_label.' found in Trash.', 'force5' )
	);

	$args = array(
		'labels'             => $labels,
		'public'             => $public,
		'publicly_queryable' => $public_query,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => true,
		'rewrite'            => array( 'slug' => $slug ),
		'capability_type'    => $cap_type,
		'has_archive'        => $has_archive,
		'menu_icon'			 => $icon, // http://melchoyce.github.io/dashicons/
		'hierarchical'       => $heirarchical,
		'menu_position'      => null,
		'supports'           => $supports //'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments'
	);

	register_post_type( $type, $args );
	
	}
	
	//Form Post Type
	empforwp_register_cpt('EMP Form', 'EMP Forms', 'empforwp_type_form', 'emp', 'post', 'dashicons-welcome-write-blog', true, array( 'title' ), false, false, false );
	
}