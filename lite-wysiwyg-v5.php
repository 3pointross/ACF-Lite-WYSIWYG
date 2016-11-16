<?php
class acf_field_lite_wysiwyg extends acf_field {

	/*
	*  __construct
	*
	*  This function will setup the field type data
	*
	*  @type	function
	*  @date	5/03/2014
	*  @since	5.0.0
	*
	*  @param	n/a
	*  @return	n/a
	*/

	function __construct() {

		$this->name 					= 'lite_wysiwyg';
		$this->label 					= __('Lite WYSIWYG', 'acf-num_slider');
		$this->category 				= 'basic';
		$this->settings[ 'dir' ] 		= plugin_dir_url( __FILE__ );
		$this->settings[ 'version' ] 	= '1.0';

		$this->defaults = array(
			'media_buttons' => 1,
			'teeny' 		=> 0,
			'dfw' 			=> 1,
			'default_value' => '',
		);

		// do not delete!
    	parent::__construct();

	}


	/*
	*  render_field_settings()
	*
	*  Create extra settings for your field. These are visible when editing a field
	*
	*  @type	action
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	$field (array) the $field being edited
	*  @return	n/a
	*/

	function render_field_settings( $field ) {

		/*
		*  acf_render_field_setting
		*
		*  This function will create a setting for your field. Simply pass the $field parameter and an array of field settings.
		*  The array of settings does not require a `value` or `prefix`; These settings are found from the $field array.
		*
		*  More than one setting can be added by copy/paste the above code.
		*  Please note that you must also have a matching $defaults value for the field name (font_size)
		*/


		acf_render_field_setting( $field, array(
			'label'			=> __('Default Value','acf'),
			'type'			=> 'textarea',
			'name'			=> 'default_value'
		));

		acf_render_field_setting( $field, array(
			'label'			=> __('Teeny Mode','acf'),
			'instructions'	=> __('Whether to output the minimal editor configuration used in PressThis','acf'),
			'type'			=> 'radio',
			'name'			=> 'teeny',
			'choices'		=>	array(
									1	=>	__("Yes",'acf'),
									0	=>	__("No",'acf'),
								)
		));

		acf_render_field_setting( $field, array(
			'label'			=> __('Media Buttons','acf'),
			'instructions'	=> __('Show Media Upload Buttons?','acf'),
			'type'			=> 'number',
			'name'			=> 'media_buttons',
			'choices'		=>	array(
									1	=>	__("Yes",'acf'),
									0	=>	__("No",'acf'),
								)
		));

		acf_render_field_setting( $field, array(
			'label'			=> __('Distraction Free Writing','acf'),
			'instructions'	=> __('Whether to replace the default fullscreen editor with DFW','acf'),
			'type'			=> 'radio',
			'name'			=> 'dfw',
			'choices'		=>	array(
									1	=>	__("Yes",'acf'),
									0	=>	__("No",'acf'),
								)
		));

	}

	/*
	*  render_field()
	*
	*  Create the HTML interface for your field
	*
	*  @param	$field (array) the $field being rendered
	*
	*  @type	action
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	$field (array) the $field being edited
	*  @return	n/a
	*/

	function render_field( $field ) {


		/*
		*  Review the data of $field.
		*  This will show what data is available
		*/

		$uid = uniqid();
		$field = array_merge($this->defaults, $field);
		$field['textarea_name'] = $field['name'];

		$messages = array(
			'error'		=>	__( 'Error initalizing editor, please ensure you still have internet connectivity and are still logged in.', 'acf' ),
			'success'	=>	__( 'Content saved.' )
		);

		$settings = array(
			'teeny'	=>	$field[ 'teeny' ],
			'dfw'	=>	$field[ 'dfw' ],
			'toolbar'	=>	$field[ 'toolbar' ],
			'media_buttons'	=>	$field[ 'media_buttons' ],
			'media_upload'	=>	$field[ 'media_upload' ],
			'nonce'			=>	wp_create_nonce( 'acf-lite-wysiwyg_' . $field['id'] . '-' . $uid ),
			'id'			=>	$field['id'] . '-' . $uid
		);
		?>
		<div>

			<div
				class="acf-lite-wysiwyg-content <?php echo esc_attr( $field[ 'class' ] ); ?>"
				id="<?php echo esc_attr( $field['id'] . '-' . $uid ); ?>"
				<?php foreach( $settings as $setting => $value ): ?>
					data-<?php echo $setting; ?>="<?php echo esc_attr( $value ); ?>"
				<?php endforeach; ?>
			>

				<div class="acf-lite-wysiwyg-error acf-lite-wysiwyg-hide notice notice-error"><?php echo wpautop( esc_html( $messages[ 'error' ] ) ); ?></div>

				<div class="acf-lite-wysiwyg-content-body uninitalized mce-content-body mceContentBody wp-editor">

					<?php echo $field[ 'value' ]; ?>

				</div>

				<div class="acf-lite-wysiwyg-wpeditor"></div>

				<p><a href="#" class="button button-primary acf-lite-wysiwyg-hide acf-lite-wysiwyg-save"><?php esc_html_e( 'Done', 'psp_projects' ); ?></a></p>

				<textarea class="acf-wysiwyg-lite-content acf-lite-wysiwyg-hide" name="<?php echo $field[ 'name' ]; ?>"><?php echo $field[ 'value' ]; ?></textarea>

			</div>


		</div>

		<?php

	}


	/*
	*  input_admin_enqueue_scripts()
	*
	*  This action is called in the admin_enqueue_scripts action on the edit screen where your field is created.
	*  Use this action to add CSS + JavaScript to assist your render_field() action.
	*
	*  @type	action (admin_enqueue_scripts)
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	n/a
	*  @return	n/a
	*/
	function input_admin_enqueue_scripts() {

		wp_register_script( 'acf-lite-wysiwyg', $this->settings[ 'dir' ] . 'assets/js/acf-lite-wysiwyg-admin.js', array( 'acf-input' ), $this->settings[ 'version' ] );
		wp_register_style( 'acf-lite-wysiwyg', $this->settings[ 'dir' ] . 'assets/css/acf-lite-wysiwyg-admin.css', array( 'acf-input' ), $this->settings[ 'version' ] );

		wp_enqueue_script( 'acf-lite-wysiwyg' );
		wp_enqueue_style( 'acf-lite-wysiwyg' );

	}

}

// create field
new acf_field_lite_wysiwyg();
