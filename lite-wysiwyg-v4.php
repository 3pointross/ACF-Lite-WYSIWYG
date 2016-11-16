<?php
class acf_field_lite_wysiwyg extends acf_field
{
	// vars
	var $settings, // will hold info such as dir / path
		$defaults; // will hold default field options


	/*
	*  __construct
	*
	*  Set name / label needed for actions / filters
	*
	*  @since	3.6
	*  @date	23/01/13
	*/

	function __construct()
	{
		// vars
		$this->name 	= 'lite_wysiwyg';
		$this->label 	= __( 'Lite WYSIWYG' );
		$this->category = __( "Basic",'acf' ); // Basic, Content, Choice, etc
		$this->defaults = array(
			'media_buttons' => 1,
			'teeny' => 0,
			'dfw' => 1,
			'default_value' => '',
		);


		// do not delete!
    parent::__construct();


    // settings
		$this->settings = array(
			'path' 		=> apply_filters('acf/helpers/get_path', __FILE__),
			'dir' 		=> apply_filters('acf/helpers/get_dir', __FILE__),
			'version' 	=> '1.0.0'
		);

	}


	/*
	*  create_options()
	*
	*  Create extra options for your field. This is rendered when editing a field.
	*  The value of $field['name'] can be used (like bellow) to save extra data to the $field
	*
	*  @type	action
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	$field	- an array holding all the field's data
	*/

	function create_options($field)
	{
		// defaults?
		/*
		$field = array_merge($this->defaults, $field);
		*/

		// key is needed in the field names to correctly save the data
		$key = $field['name'];

		// Create Field Options HTML
		?>
		<tr class="field_option field_option_<?php echo $this->name; ?>">
			<td class="label">
				<label><?php _e("Default Value",'acf'); ?></label>
			</td>
			<td>
				<?php
				do_action('acf/create_field', array(
					'type'	=>	'textarea',
					'name'	=>	'fields['.$key.'][default_value]',
					'value'	=>	$field['default_value'],
				));
				?>
			</td>
		</tr>
		<tr class="field_option field_option_<?php echo $this->name; ?>">
			<td class="label">
				<label><?php _e("Teeny Mode",'acf'); ?></label>
				<p><?php _e("Whether to output the minimal editor configuration used in PressThis",'acf'); ?></p>
			</td>
			<td>
				<?php

				do_action('acf/create_field', array(
					'type'	=>	'radio',
					'name'	=>	'fields['.$key.'][teeny]',
					'value'	=>	$field['teeny'],
					'layout'	=>	'horizontal',
					'choices' => array(
						1	=>	__("Yes",'acf'),
						0	=>	__("No",'acf'),
					)
				));
				?>
			</td>
		</tr>
		<tr class="field_option field_option_<?php echo $this->name; ?>">
			<td class="label">
				<label><?php _e("Show Media Upload Buttons?",'acf'); ?></label>
			</td>
			<td>
				<?php
				do_action('acf/create_field', array(
					'type'	=>	'radio',
					'name'	=>	'fields['.$key.'][media_buttons]',
					'value'	=>	$field['media_buttons'],
					'layout'	=>	'horizontal',
					'choices' => array(
						1	=>	__("Yes",'acf'),
						0	=>	__("No",'acf'),
					)
				));
				?>
			</td>
		</tr>
		<tr class="field_option field_option_<?php echo $this->name; ?>">
			<td class="label">
				<label><?php _e("Distraction Free Writing",'acf'); ?></label>
				<p><?php _e("Whether to replace the default fullscreen editor with DFW",'acf'); ?></p>
			</td>
			<td>
				<?php
				do_action('acf/create_field', array(
					'type'	=>	'radio',
					'name'	=>	'fields['.$key.'][dfw]',
					'value'	=>	$field['dfw'],
					'layout'	=>	'horizontal',
					'choices' => array(
						1	=>	__("Yes",'acf'),
						0	=>	__("No",'acf'),
					)
				));
				?>
			</td>
		</tr>
		<?php

	}


	/*
	*  create_field()
	*
	*  Create the HTML interface for your field
	*
	*  @param	$field - an array holding all the field's data
	*
	*  @type	action
	*  @since	3.6
	*  @date	23/01/13
	*/

	function create_field( $field )
	{
		// defaults?
		/*
		$field = array_merge($this->defaults, $field);
		*/

		// perhaps use $field['preview_size'] to alter the markup?


		// create Field HTML
		//
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
	*  Use this action to add css + javascript to assist your create_field() action.
	*
	*  $info	http://codex.wordpress.org/Plugin_API/Action_Reference/admin_enqueue_scripts
	*  @type	action
	*  @since	3.6
	*  @date	23/01/13
	*/

	function input_admin_enqueue_scripts()
	{

		wp_register_script( 'acf-lite-wysiwyg', $this->settings[ 'dir' ] . 'assets/js/acf-lite-wysiwyg-admin.js', array( 'acf-input' ), $this->settings[ 'version' ] );
		wp_register_style( 'acf-lite-wysiwyg', $this->settings[ 'dir' ] . 'assets/css/acf-lite-wysiwyg-admin.css', array( 'acf-input' ), $this->settings[ 'version' ] );

		wp_enqueue_script( 'acf-lite-wysiwyg' );
		wp_enqueue_style( 'acf-lite-wysiwyg' );

	}


	/*
	*  input_admin_head()
	*
	*  This action is called in the admin_head action on the edit screen where your field is created.
	*  Use this action to add css and javascript to assist your create_field() action.
	*
	*  @info	http://codex.wordpress.org/Plugin_API/Action_Reference/admin_head
	*  @type	action
	*  @since	3.6
	*  @date	23/01/13
	*/

	function input_admin_head()
	{
		// Note: This function can be removed if not used
	}


	/*
	*  field_group_admin_enqueue_scripts()
	*
	*  This action is called in the admin_enqueue_scripts action on the edit screen where your field is edited.
	*  Use this action to add css + javascript to assist your create_field_options() action.
	*
	*  $info	http://codex.wordpress.org/Plugin_API/Action_Reference/admin_enqueue_scripts
	*  @type	action
	*  @since	3.6
	*  @date	23/01/13
	*/

	function field_group_admin_enqueue_scripts()
	{
		// Note: This function can be removed if not used
	}


	/*
	*  field_group_admin_head()
	*
	*  This action is called in the admin_head action on the edit screen where your field is edited.
	*  Use this action to add css and javascript to assist your create_field_options() action.
	*
	*  @info	http://codex.wordpress.org/Plugin_API/Action_Reference/admin_head
	*  @type	action
	*  @since	3.6
	*  @date	23/01/13
	*/

	function field_group_admin_head()
	{
		// Note: This function can be removed if not used
	}


	/*
	*  load_value()
	*
	*  This filter is appied to the $value after it is loaded from the db
	*
	*  @type	filter
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	$value - the value found in the database
	*  @param	$post_id - the $post_id from which the value was loaded from
	*  @param	$field - the field array holding all the field options
	*
	*  @return	$value - the value to be saved in te database
	*/

	function load_value($value, $post_id, $field)
	{
		// Note: This function can be removed if not used
		return $value;
	}


	/*
	*  update_value()
	*
	*  This filter is appied to the $value before it is updated in the db
	*
	*  @type	filter
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	$value - the value which will be saved in the database
	*  @param	$post_id - the $post_id of which the value will be saved
	*  @param	$field - the field array holding all the field options
	*
	*  @return	$value - the modified value
	*/

	function update_value($value, $post_id, $field)
	{
		// Note: This function can be removed if not used
		return $value;
	}


	/*
	*  format_value()
	*
	*  This filter is appied to the $value after it is loaded from the db and before it is passed to the create_field action
	*
	*  @type	filter
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	$value	- the value which was loaded from the database
	*  @param	$post_id - the $post_id from which the value was loaded
	*  @param	$field	- the field array holding all the field options
	*
	*  @return	$value	- the modified value
	*/

	function format_value($value, $post_id, $field)
	{
		// defaults?
		/*
		$field = array_merge($this->defaults, $field);
		*/

		// perhaps use $field['preview_size'] to alter the $value?


		// Note: This function can be removed if not used
		return $value;
	}


	/*
	*  format_value_for_api()
	*
	*  This filter is appied to the $value after it is loaded from the db and before it is passed back to the api functions such as the_field
	*
	*  @type	filter
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	$value	- the value which was loaded from the database
	*  @param	$post_id - the $post_id from which the value was loaded
	*  @param	$field	- the field array holding all the field options
	*
	*  @return	$value	- the modified value
	*/

	function format_value_for_api($value, $post_id, $field)
	{
		// defaults?
		/*
		$field = array_merge($this->defaults, $field);
		*/

		// perhaps use $field['preview_size'] to alter the $value?


		// Note: This function can be removed if not used
		return $value;
	}


	/*
	*  load_field()
	*
	*  This filter is appied to the $field after it is loaded from the database
	*
	*  @type	filter
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	$field - the field array holding all the field options
	*
	*  @return	$field - the field array holding all the field options
	*/

	function load_field($field)
	{
		// Note: This function can be removed if not used
		return $field;
	}


	/*
	*  update_field()
	*
	*  This filter is appied to the $field before it is saved to the database
	*
	*  @type	filter
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	$field - the field array holding all the field options
	*  @param	$post_id - the field group ID (post_type = acf)
	*
	*  @return	$field - the modified field
	*/

	function update_field($field, $post_id)
	{
		// Note: This function can be removed if not used
		return $field;
	}

}
// create field
new acf_field_lite_wysiwyg();
