<?php
class Stewardship_Meta {

	/**
	 * Construct.
	 */
	public function __construct() {
		// Create the Metaboxes
		add_action( 'cmb2_admin_init', array($this,'steward_people_metaboxes') );
		//Add Info to Admin Column
		add_filter('manage_steward_people_posts_columns', array($this, 'steward_info_columns'), 5);
		add_action('manage_steward_people_posts_custom_column', array($this, 'steward_posts_info_columns'), 5, 2);
		// Add Household to Admin Column (leaving possibility for users)
		//add_filter('manage_steward_people_posts_columns', array($this, 'steward_house_columns'), 5);
		//add_action('manage_steward_people_posts_custom_column', array($this, 'steward_posts_house_columns'), 5, 2);
		// Add Birthday to Admin Column
		add_filter('manage_steward_people_posts_columns', array($this, 'steward_birthday_columns'), 5);
		add_action('manage_steward_people_posts_custom_column', array($this, 'steward_posts_birthday_columns'), 5, 2);
		// Add Person's Image to Admin Column
		add_filter('manage_steward_people_posts_columns', array($this, 'steward_posts_columns'), 10);
		add_action('manage_steward_people_posts_custom_column', array($this, 'steward_posts_custom_columns'), 10, 2);
		// Move Image metabox
		add_action('do_meta_boxes', array($this,'be_rotator_image_metabox') );
		// Date Fixing
		add_filter( 'cmb2_localized_data', array($this,'update_date_picker_defaults') );
	}

	// Person metaboxes
	function steward_people_metaboxes($post_id) {

	    // Start with an underscore to hide fields from custom fields list
	    $prefix = '_steward_';

	    // Personal Details metabox
	    $cmb = new_cmb2_box( array(
	        'id'            => $prefix . 'steward_person_details',
	        'title'         => __( 'Personal Details', 'steward' ),
	        'object_types'  => array( 'steward_people', ), // Post type
	        'context'       => 'normal',
	        'priority'      => 'high',
	        'show_names'    => true, // Show field names on the left
		    ) );

			// Birthday
		    $cmb->add_field( array(
		        'name' => __( 'Birthday', 'steward' ),
        		'id' => $prefix . 'person_birthday',
        		'type' => 'text_date',
        		'date_format' => 'M j, Y',
		    ) );

		    // Gender
		    $cmb->add_field( array(
	    		'name' => __( 'Gender', 'steward' ),
	    		'id' => $prefix . 'person_gender',
	    		'type' => 'select',
	    		'options' => array(
	    			'male' => __( 'Male', 'steward' ),
	    			'female' => __( 'Female', 'steward' ),
	    		),
	    	) );

		    // Phone Number
		    $cmb->add_field( array(
		        'name' => __( 'Phone', 'steward' ),
        		'id' => $prefix . 'person_phone',
        		'type' => 'text_medium',
        		'options' => array(
    		        'add_row_text' => __( 'Add Number', 'steward' ),
    		    ),
        		'repeatable' => true,
		        //'column' => array(
		        //	'name'     => esc_html__( 'Phone Number', 'steward' ), // Set the admin column title
		        //	'position' => 2, // Set as the second column.
		        //),
		    ) );

		    // Email
		    $cmb->add_field( array(
		    	'name' => __( 'Email', 'steward' ),
		    	'id' => $prefix . 'person_email',
		    	'type' => 'text_email',
		    	'options' => array(
		    	    'add_row_text' => __( 'Add Email', 'steward' ),
		    	),
		    	'repeatable' => true,
		    ) );

		    // Address
		    $cmb->add_field( array(
		    		'name' => esc_html__( 'Address', 'steward' ),
		    		'id'   => $prefix . 'person_address',
		    		'type' => 'address',
		    		'options' => array(
		    		    'add_row_text' => __( 'Add Address', 'steward' ),
		    		),
		    		'repeatable' => true,
		    	) );

	    // Household metabox
	    $cmb = new_cmb2_box( array(
	    		'id'           => 'steward_person_household',
	    		'title'        => __( 'Household', 'steward' ),
	    		'object_types' => array( 'steward_people' ), // Post type
	    		'context'      => 'normal',
	    		'priority'     => 'high',
	    		'show_names'   => false, // Show field names on the left
	    		'closed'     => true,
	    	) );

	    	// Marital Status
	    	$cmb->add_field( array(
	    		'name' => __( 'Marital Status', 'steward' ),
	    		'id' => $prefix . 'steward_person_marital_status',
	    		'type' => 'select',
	    		'default' => 'single',
	    		'show_names'   => true,
	    		'options' => array(
	    			'single' => __( 'Single', 'steward' ),
	    			'married' => __( 'Married', 'steward' ),
	    			'widowed' => __( 'Widowed', 'steward' ),
	    		),
	    	) );

	    	// Household instructions
	    	$cmb->add_field( array(
    			//'name' => esc_html__( 'Instructions', 'cmb2' ),
    			'desc' => __( 'Drag people from the left column to the right column to add them to this Household.<br />You may rearrange the order of the family members in the right column by dragging and dropping.', 'steward' ),
    			'id'   => $prefix . 'household_desc',
    			'type' => 'title',
	    	) );

	    	// Household
	    	$cmb->add_field( array(
	    		'name'    => __( 'Household', 'steward' ),
	    		'id'      => $prefix . 'household',
	    		'type'    => 'custom_attached_posts',
	    		'options' => array(
	    			'show_thumbnails' => true, // Show thumbnails on the left
	    			'filter_boxes'    => true, // Show a text box for filtering the results
	    			'query_args'      => array( 'posts_per_page' => 10, 'post_type' => 'steward_people', ), // override the get_posts args
	    		),
	    	) );

	    // Additional Notes metabix
	    $cmb = new_cmb2_box( array(
	    		'id'           => 'steward_person_notes',
	    		'title'        => __( 'Additional Notes', 'steward' ),
	    		'object_types' => array( 'steward_people' ), // Post type
	    		'context'      => 'normal',
	    		'priority'     => 'core',
	    		'show_names'   => false, // Show field names on the left
	    		//'closed'     => false,
	    	) );

	    	// Notes
	    	$cmb->add_field( array(
	    	    'name'    => 'Additional Notes',
	    	    'id'      => $prefix . 'person_notes',
	    	    'type'    => 'wysiwyg',
	    	    'options' => array(
    	            'wpautop' => true, // use wpautop?
    	            'media_buttons' => false, // show insert/upload button(s)
    	            'textarea_rows' => get_option('default_post_edit_rows', 10), // rows="..."
    	            'teeny' => true, // output the minimal editor config used in Press This
    	        ),
	    	) );

	    // School metabox
	    $cmb = new_cmb2_box( array(
	    		'id'           => $prefix . 'steward_people_school',
	    		'title'        => __( 'School', 'steward' ),
	    		'object_types' => array( 'steward_people' ),
	    		'context'      => 'side',
	    		'priority'     => 'core',
	    		'closed'     => true,
	    	) );

	    	$cmb->add_field( array(
    			'id' => $prefix . 'steward_person_school_type',
    			'type' => 'select',
    			'default' => 'unassigned',
    			'options' => array(
    				'unassigned' => __( 'Unassigned', 'steward' ),
    				'elementary' => __( 'Elementary', 'steward' ),
    				'middle-school' => __( 'Middle School', 'steward' ),
    				'high-school' => __( 'High School', 'steward' ),
    				'college' => __( 'College', 'steward' ),
    				'other' => __( 'Other', 'steward' ),
    			),
    		) );

	    	$cmb->add_field( array(
	    		'name' => __( 'Name', 'steward' ),
	    		'id' => $prefix . 'steward_person_school_name',
	    		'type' => 'text',
	    	) );

	    	$cmb->add_field( array(
	    		'name' => __( 'Grade', 'steward' ),
	    		'id' => $prefix . 'steward_person_school_grade',
	    		'type' => 'select',
	    		'options' => array(
	    			'unassigned' => __( 'unassigned', 'steward' ),
	    			'pre-k' => __( 'Pre-K', 'steward' ),
	    			'k5' => __( 'K5', 'steward' ),
	    			'1' => __( '1', 'steward' ),
	    			'2' => __( '2', 'steward' ),
	    			'3' => __( '3', 'steward' ),
	    			'4' => __( '4', 'steward' ),
	    			'5' => __( '5', 'steward' ),
	    			'6' => __( '6', 'steward' ),
	    			'7' => __( '7', 'steward' ),
	    			'8' => __( '8', 'steward' ),
	    			'9' => __( '9', 'steward' ),
	    			'10' => __( '10', 'steward' ),
	    			'11' => __( '11', 'steward' ),
	    			'12' => __( '12', 'steward' ),
	    		),
	    	) );

	    	$cmb->add_field( array(
	    		'name' => __( 'Medical Notes', 'steward' ),
	    		'id' => $prefix . 'steward_person_school_notes',
	    		'type' => 'textarea_small',
	    	) );

    	// Social Profiles metabox
    	$cmb = new_cmb2_box( array(
    			'id'           => $prefix . 'steward_people_social',
    			'title'        => __( 'Social Profiles', 'steward' ),
    			'object_types' => array( 'steward_people' ),
    			'context'      => 'side',
    			'priority'     => 'low',
    			'closed'     => true,
    		) );

    		$cmb->add_field( array(
    			'name' => __( 'Twitter', 'steward' ),
    			'id' => $prefix . 'steward_person_social_twitter',
    			'type' => 'text_medium',
    		) );

    		$cmb->add_field( array(
    			'name' => __( 'Facebook URL', 'steward' ),
    			'id' => $prefix . 'steward_person_social_facebook',
    			'type' => 'text_url',
    		) );

    		$cmb->add_field( array(
    			'name' => __( 'Instagram', 'steward' ),
    			'id' => $prefix . 'steward_person_social_instagram',
    			'type' => 'text_medium',
    		) );

	}

	// Add Person's Image to Admin
	function steward_posts_columns($columns){
	    $columns['people_image'] = __('Image');
	    return $columns;
	}
	function steward_posts_custom_columns($column_name, $id){
	    switch ( $column_name ) {
	        case 'people_image':
	            echo the_post_thumbnail( 'thumbnail' );
	            break;
	    }
	}

	// Move Image metabox
	function be_rotator_image_metabox() {
		remove_meta_box( 'postimagediv', 'steward_people', 'side' );
		add_meta_box('postimagediv', __('Person\'s Image'), 'post_thumbnail_meta_box', 'steward_people', 'side', 'high');
	}

	//Add Household to Admin Column
	function steward_house_columns($columns){
	    $columns['_steward_attached_cmb2_attached_posts'] = __('Household');
	    return $columns;
	}
	function steward_posts_house_columns($column_name, $post_id){
	        switch ( $column_name ) {
	            case '_steward_attached_cmb2_attached_posts':
	                $attached = get_post_meta( $post_id, '_steward_attached_cmb2_attached_posts', true );
	                if (!$attached == ""){
		                foreach ( $attached as $attached_post ) {
		                	$post = get_post( $attached_post ); ?>
		                		<li style="list-style: none;">
		                			<a href="<?php the_permalink($post->ID); ?>"><?php echo $post->post_title; ?>
		                			</a>
		                		</li>
		                <?php }
	                }
	        }
	}

	//Add Info to Admin Column
	function steward_info_columns($columns){
	    $columns['_steward_info'] = __('Contact Info');
	    return $columns;
	}
	function steward_posts_info_columns($column_name, $post_id){
	        switch ( $column_name ) {
	            case '_steward_info':
	                $email = get_post_meta( $post_id, '_steward_person_email', true );
	                $phone = get_post_meta( $post_id, '_steward_person_phone', true );
	                $address = get_post_meta( $post_id, '_steward_person_address', true );
	                if (!$email == ""){
	                    foreach ( $email as $email_post ) { ?>
	                    		<li style="list-style: none;">
	                    			<?php echo is_email( $email_post ); ?>
	                    		</li><br />
	                    <?php }
	                }
	                if (!$phone == ""){
	                    foreach ( $phone as $phone_post ) { ?>
	                    		<li style="list-style: none;">
	                    			<?php echo $phone_post; ?>
	                    		</li>
	                    <?php } ?> <br />
	                <?php }
	                if (!$address == ""){
	                    foreach ( $address as $address_post ) {
	                    $posted = get_post( $address );?>
	                    		<li style="list-style: none;">
	                    			<?php echo $address_post['address-1']; ?>
	                    		</li>
	                    		<?php if (!$address_post['address-2'] ==''){ ?>
	                    			<li style="list-style: none;">
	                    				<?php echo $address_post['address-2']; ?>
	                    			</li>
	                    		<?php } ?>
	                    		<li style="list-style: none;">
	                    			<?php echo $address_post['city']; ?>,&nbsp;
	                    			<?php echo $address_post['state']; ?>&nbsp;
	                    			<?php echo $address_post['zip'];?>
	                    		</li>
	                    <?php }
	                }
	        }
	}

	//Add Birthday to Admin Column
	function steward_birthday_columns($columns){
	    $columns['_steward_birthday'] = __('Birthday');
	    return $columns;
	}
	function steward_posts_birthday_columns($column_name, $post_id){
	        switch ( $column_name ) {
	            case '_steward_birthday':
	                $birthday = get_post_meta( $post_id, '_steward_person_birthday', true );
	                if (!$birthday == ""){ ?>
                		<li style="list-style: none;">
                			<?php echo $birthday; ?>
                		</li>
	                <?php }
	        }
	}

	// Fix Datepicker year range
	function update_date_picker_defaults( $l10n ) {

	    $l10n['defaults']['date_picker']['yearRange'] = '1920:+0';

	    return $l10n;
	}

}
$Stewardship_Meta = new Stewardship_Meta();
