<?php
/**
 * lite-wysiwyg-controller.php
 *
 * The AJAX calls to initlize and return the WYSIWYG editor on click.
 *
 */
add_action( 'wp_ajax_nopriv_acf_lite_wysiwyg_init', 'acf_37_lite_wysiwyg_init' );
add_action( 'wp_ajax_acf_lite_wysiwyg_init', 'acf_37_lite_wysiwyg_init' );
function acf_37_lite_wysiwyg_init() {

    $settings = array(
        'media_buttons' =>  $_POST[ 'media_buttons' ],
        'textarea_name' =>  'acf-lite-wysiwyg-body',
        'teeny'         =>  $_POST[ 'teeny' ],
        'dfw'           =>  $_POST[ 'dfw' ],
        'tinymce'       =>  true,
        'quicktags'     =>  true
    );

    ob_start(); ?>

    <div id="wp-editor-<?php echo esc_attr( $_POST[ 'id' ] ); ?>"></div>

    <?php
    add_filter( 'wp_default_editor', 'acf_37_default_tinymce' );
    wp_editor( stripslashes( $_POST[ 'content' ] ), 'acf-lite-wysiwyg-body', $settings );
    remove_filter( 'wp_default_editor', 'acf_37_default_tinymce' );

    $response = array( 'success', 'data' => ob_get_clean() );

    wp_send_json_success( $response );

    exit();

}

/**
 * Retrun tinymce otherwise editors will always default to text.
 * @return [type] [description]
 */
function acf_37_default_tinymce() {

    return 'tinymce';

}
