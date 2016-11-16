<?php 
class Stewardship_CPT {
	
	/**
	 * Construct.
	 */
	public function __construct() {
		// Register the People post type
		add_action( 'init', array($this,'steward_people_post_type'), 0 );
		// Create the Member User Role
		//add_action('init', array($this,'add_member_role'));
		// Small Group taxonomy
		add_action( 'init', array($this,'steward_groups_taxonomy'), 5 );
		// Member Status taxonomy
		add_action( 'init', array($this,'steward_member_status_taxonomy'), 0 );
		// Create default Member Status
		add_action( 'init', array($this,'create_member_statuses'), 0 );
		// Automatically add Unassigned to New People
		add_action('save_post_steward_people', array($this,'add_unassigned_status'));
		// Change 'Title' to 'Name'
		add_filter( 'enter_title_here', array($this,'steward_person_title_text') );
		add_filter('manage_edit-steward_people_columns', array($this,'steward_column_title'));
		// Remove Date column
		add_filter( 'manage_edit-steward_people_columns', array($this,'remove_date_column'), 10, 2 );
	
	}
	
	// Register People Post Type
	function steward_people_post_type() {
	
		$labels = array(
			'name'                  => _x( 'People', 'Post Type General Name', 'steward' ),
			'singular_name'         => _x( 'Person', 'Post Type Singular Name', 'steward' ),
			'menu_name'             => __( 'People', 'steward' ),
			'name_admin_bar'        => __( 'People', 'steward' ),
			'archives'              => __( 'People Archives', 'steward' ),
			'parent_item_colon'     => __( 'Parent People:', 'steward' ),
			'all_items'             => __( 'All People', 'steward' ),
			'add_new_item'          => __( 'Add New Person', 'steward' ),
			'add_new'               => __( 'Add New', 'steward' ),
			'new_item'              => __( 'New Person', 'steward' ),
			'edit_item'             => __( 'Edit Person', 'steward' ),
			'update_item'           => __( 'Update Person', 'steward' ),
			'view_item'             => __( 'View Person', 'steward' ),
			'search_items'          => __( 'Search People', 'steward' ),
			'not_found'             => __( 'Person not found', 'steward' ),
			'not_found_in_trash'    => __( 'Person not found in Trash', 'steward' ),
			'featured_image'        => __( 'Person\'s Image', 'steward' ),
			'set_featured_image'    => __( 'Add image', 'steward' ),
			'remove_featured_image' => __( 'Remove image', 'steward' ),
			'use_featured_image'    => __( 'Use as image', 'steward' ),
			'insert_into_item'      => __( 'Insert into Person', 'steward' ),
			'uploaded_to_this_item' => __( 'Uploaded to this Person', 'steward' ),
			'items_list'            => __( 'People list', 'steward' ),
			'items_list_navigation' => __( 'People list navigation', 'steward' ),
			'filter_items_list'     => __( 'Filter People list', 'steward' ),
		);
		$rewrite = array(
			'slug'                  => 'people',
			'with_front'            => true,
			'pages'                 => true,
			'feeds'                 => true,
		);
		$args = array(
			'label'                 => __( 'Person', 'steward' ),
			'description'           => __( 'People Management', 'steward' ),
			'labels'                => $labels,
			'supports'              => array( 'title', 'thumbnail', ),
			'taxonomies'            => array( 'steward_groups' ),
			'hierarchical'          => false,
			'public'                => true,
			'show_ui'               => true,
			'show_in_menu'          => true,
			'menu_position'         => 20,
			'menu_icon'             => 'dashicons-admin-users',
			'show_in_admin_bar'     => true,
			'show_in_nav_menus'     => true,
			'can_export'            => true,
			'has_archive'           => 'people',
			'exclude_from_search'   => false,
			'publicly_queryable'    => true,
			'rewrite'               => $rewrite,
			'capability_type'       => 'page',
		);
		register_post_type( 'steward_people', $args );
	
	}
	
	// Add a custom user role
	function add_member_role() {
	    add_role( 'member', 'Member', array(
			'read' => true, // true allows this capability
			'edit_posts' => false, // Allows user to edit their own posts
			'edit_pages' => false, // Allows user to edit pages
			'edit_others_posts' => false, // Allows user to edit others posts not just their own
			'create_posts' => false, // Allows user to create new posts
			'manage_categories' => false, // Allows user to manage post categories
			'publish_posts' => false, // Allows the user to publish, otherwise posts stays in draft mode
			'edit_themes' => false, // false denies this capability. User can’t edit your theme
			'install_plugins' => false, // User cant add new plugins
			'update_plugin' => false, // User can’t update any plugins
			'update_core' => false // user cant perform core updates
			)
	
		);
	}
	
	// Small Groups
	function steward_groups_taxonomy() {
	
		$labels = array(
			'name'                       => _x( 'Small Groups', 'Taxonomy General Name', 'steward' ),
			'singular_name'              => _x( 'Small Group', 'Taxonomy Singular Name', 'steward' ),
			'menu_name'                  => __( 'Small Group', 'steward' ),
			'all_items'                  => __( 'All Small Groups', 'steward' ),
			'parent_item'                => __( 'Parent Small Group', 'steward' ),
			'parent_item_colon'          => __( 'Parent Small Group:', 'steward' ),
			'new_item_name'              => __( 'New Small Group Name', 'steward' ),
			'add_new_item'               => __( 'Add New Small Group', 'steward' ),
			'edit_item'                  => __( 'Edit Small Group', 'steward' ),
			'update_item'                => __( 'Update Small Group', 'steward' ),
			'view_item'                  => __( 'View Small Group', 'steward' ),
			'separate_items_with_commas' => __( 'Separate Small Groups with commas', 'steward' ),
			'add_or_remove_items'        => __( 'Add or remove Small Groups', 'steward' ),
			'choose_from_most_used'      => __( 'Choose from the most used', 'steward' ),
			'popular_items'              => __( 'Popular Small Groups', 'steward' ),
			'search_items'               => __( 'Search Small Groups', 'steward' ),
			'not_found'                  => __( 'Not Found', 'steward' ),
			'no_terms'                   => __( 'No Small Groups', 'steward' ),
			'items_list'                 => __( 'Small Groups list', 'steward' ),
			'items_list_navigation'      => __( 'Small Groups list navigation', 'steward' ),
		);
		$rewrite = array(
			'slug'                       => 'small_groups',
			'with_front'                 => true,
			'hierarchical'               => false,
		);
		$args = array(
			'labels'                     => $labels,
			'hierarchical'               => true,
			'public'                     => true,
			'show_ui'                    => true,
			'show_admin_column'          => true,
			'show_in_nav_menus'          => true,
			'show_tagcloud'              => true,
			'rewrite'                    => $rewrite,
		);
		register_taxonomy( 'steward_groups', array( 'steward_people' ), $args );
	
	}
	
	// Member Status
	function steward_member_status_taxonomy() {
	
		$labels = array(
			'name'                       => _x( 'Member Status', 'Taxonomy General Name', 'steward' ),
			'singular_name'              => _x( 'Member Status', 'Taxonomy Singular Name', 'steward' ),
			'menu_name'                  => __( 'Member Status', 'steward' ),
			'all_items'                  => __( 'All Member Statuses', 'steward' ),
			'parent_item'                => __( 'Parent Member Status', 'steward' ),
			'parent_item_colon'          => __( 'Parent Member Status:', 'steward' ),
			'new_item_name'              => __( 'New Member Status', 'steward' ),
			'add_new_item'               => __( 'Add New Member Status', 'steward' ),
			'edit_item'                  => __( 'Edit Member Status', 'steward' ),
			'update_item'                => __( 'Update Member Status', 'steward' ),
			'view_item'                  => __( 'View Member Status', 'steward' ),
			'separate_items_with_commas' => __( 'Separate Member Statuses with commas', 'steward' ),
			'add_or_remove_items'        => __( 'Add or remove Member Statuses', 'steward' ),
			'choose_from_most_used'      => __( 'Choose from the most used', 'steward' ),
			'popular_items'              => __( 'Popular Member Statuses', 'steward' ),
			'search_items'               => __( 'Search Member Statuses', 'steward' ),
			'not_found'                  => __( 'Not Found', 'steward' ),
			'no_terms'                   => __( 'No Member Statuses', 'steward' ),
			'items_list'                 => __( 'Member Statuses list', 'steward' ),
			'items_list_navigation'      => __( 'Member Statuses list navigation', 'steward' ),
		);
		$rewrite = array(
			'slug'                       => 'member_status',
			'with_front'                 => true,
			'hierarchical'               => false,
		);
		$args = array(
			'labels'                     => $labels,
			'hierarchical'               => true,
			'public'                     => true,
			'show_ui'                    => true,
			'show_admin_column'          => true,
			'show_in_nav_menus'          => true,
			'show_tagcloud'              => true,
			'rewrite'                    => $rewrite,
		);
		register_taxonomy( 'steward_member_status', array( 'steward_people' ), $args );
	
	}
	
	// Add Member Statuses
	function create_member_statuses(){
		if( !term_exists( 'Member', 'steward_member_status' ) ) {
		       wp_insert_term(
		           'Member',
		           'steward_member_status',
		           array(
		             'description' => 'This person is a member of your congregation.',
		             'slug'        => 'Member'
		           )
		       );
		 }
		 elseif( !term_exists( 'Visitor', 'steward_member_status' ) ) {
		        wp_insert_term(
		            'Visitor',
		            'steward_member_status',
		            array(
		              'description' => 'This person is visiting or has visited your church.',
		              'slug'        => 'visitor'
		            )
		        );
		  }
		  elseif( !term_exists( 'In Progress', 'steward_member_status' ) ) {
		         wp_insert_term(
		             'In Progress',
		             'steward_member_status',
		             array(
		               'description' => 'This person is in the process of joining your congregation.',
		               'slug'        => 'in-progress'
		             )
		         );
		   }
		   elseif( !term_exists( 'Unassigned', 'steward_member_status' ) ) {
		          wp_insert_term(
		              'Unassigned',
		              'steward_member_status',
		              array(
		                'description' => 'This person has not yet been assigned a status.',
		                'slug'        => 'unassigned'
		              )
		          );
		    }
	}
	
	// Automatically Assign Unassigned to Member Status
	function add_unassigned_status($post_id) {
	    if(!has_term('','steward_member_status',$post_id)){
	        wp_set_object_terms($post_id, 'unassigned', 'steward_member_status', true);
	    }
	}
	
	// Change 'Title' to 'Name'
	function steward_person_title_text( $title ) {
		$screen = get_current_screen();
		if  ( 'steward_people' == $screen->post_type ) {
			$title = esc_html__( 'Enter name here', 'steward' );
		}
		return $title;
	}
	
	// Change 'Title' to 'Name'
	function steward_column_title( $posts_columns ) {
	    //print_r($posts_columns);
	    $posts_columns[ 'title' ] = 'Name';
	    return $posts_columns;
	}
	
	// Remove 'Date' column
	function remove_date_column( $columns, $post_type ) {
	    unset(
	      $columns['date']
	    );
	  return $columns;
	}

}
$Stewardship_CPT = new Stewardship_CPT();