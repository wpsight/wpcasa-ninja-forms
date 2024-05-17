<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) )
	exit;

/**
 * WPSight_Ninja_Forms_Admin class
 */
class WPSight_Ninja_Forms_Admin {

	/**
	 * Constructor
	 */
	public function __construct() {
		
		// Add add-on options to general plugin settings
		add_filter( 'wpsight_options', array( $this, 'ninja_options' ) );

	}

	/**
	 *	ninja_options()
	 *
	 *	Add add-on options tab to
	 *	general plugin settings.
	 *
	 *	@param	array	Incoming plugin options
	 *	@uses	is_admin()
	 *	@uses	get_editable_roles()
	 *	@uses	get_option()
	 *	@uses	wpsight_date_formats()
	 *	@return array	Updated options array
	 *
	 *	@since 1.0.0
	 */
	public function ninja_options( $options ): array {
		$icon = 'dashicons dashicons-email';
        $name = __( 'Ninja Forms', 'wpcasa-ninja-forms' );
		// Prepare form option
		$forms = array( '' => __( 'None', 'wpcasa-ninja-forms' ) );

		$ninja_forms = Ninja_Forms()->form()->get_forms();

		foreach ( $ninja_forms as $key => $form ) {
			$id = $form->get_id();
			$forms[ $id ] = $form->get_settings( 'title' );
		}

		$options_ninja = array(

			'ninja_listing_form_pageheading' => array(
                'name'		=> $name,
                'desc'		=> '',
                'link'		=> 'https://docs.wpcasa.com/article/wpcasa-ninja-forms/',
                'icon'		=> $icon,
                'id'		=> 'ninja_listing_form_pageheading',
                'type'		=> 'pageheading',
                'position'	=> 10
            ),

			'ninja_listing_form_id' => array(
				'name'		=> __( 'Listing Form', 'wpcasa-ninja-forms' ),
				'desc'		=> __( 'Select the form that you want to use on listing pages.', 'wpcasa-ninja-forms' ),
				'id'		=> 'ninja_listing_form_id',
				'default'	=> '',
				'type'		=> 'select',
				'options'	=> $forms
			)

		);
		
		$form_id = wpsight_get_option( 'ninja_listing_form_id' );
		
		if( $form_id ) {
			
			$form_fields = array( '' => __( 'None', 'wpcasa-ninja-forms' ) );
			
			foreach( Ninja_Forms()->form( absint( $form_id ) )->get_fields() as $key => $field ) {				
				$form_field_id = $field->get_id();
				
				if( '_hidden' == $field->get_type() )
					$form_fields[ $form_field_id ] = $field['data']['label'];
			}
			
			$options_ninja['ninja_listing_field_id'] = array(
				'name'		=> __( 'Agent Email', 'wpcasa-ninja-forms' ),
				'desc'		=> __( 'Select the hidden form field that contains the agent email.', 'wpcasa-ninja-forms' ),
				'id'		=> 'ninja_listing_field_id',
				'default'	=> '',
				'type'		=> 'select',
				'options'	=> $form_fields
			);
		
			$options_ninja['ninja_listing_form_display'] = array(
				'name'		=> __( 'Form Display', 'wpcasa-ninja-forms' ),
				'desc'	=> __( 'Select where to display the listing form or choose to manually add the form via shortcode or function.', 'wpcasa-ninja-forms' ),
				'id'		=> 'ninja_listing_form_display',
				'default'	=> 'listing_after',
				'type'		=> 'select',
				'options'	=> array(
					'wpsight_listing_single_after'				=> __( 'At the end', 'wpcasa-ninja-forms' ),
					'wpsight_listing_single_details_after'		=> __( 'After details', 'wpcasa-ninja-forms' ),
					'wpsight_listing_single_description_after'	=> __( 'After description', 'wpcasa-ninja-forms' ),
					'wpsight_listing_single_features_after'		=> __( 'After features', 'wpcasa-ninja-forms' ),
					'wpsight_listing_single_location_after'		=> __( 'After location', 'wpcasa-ninja-forms' ),
					'wpsight_listing_single_agent_after'		=> __( 'After agent', 'wpcasa-ninja-forms' ),
					''											=> __( 'Do not display', 'wpcasa-ninja-forms' )
				)
			);
			
		}
		
		$options_ninja['ninja_listing_form_css'] = array(
			'name'		=> __( 'Form CSS', 'wpcasa-ninja-forms' ),
			'cb_label'	=> __( 'Please uncheck the box to disable the plugin from outputting CSS.', 'wpcasa-ninja-forms' ),
			'id'		=> 'ninja_listing_form_css',
			'default'	=> '1',
			'type'		=> 'checkbox'
		);

        if ( version_compare( '1.1.0', WPSIGHT_VERSION, '<' ) ) {
            $name = '<span class="' . $icon . '"></span>' . $name;
        }

        $options['ninja_forms'] = array(
            $name,
            apply_filters( 'wpsight_options_ninja', $options_ninja )
        );

        return $options;

	}

}
