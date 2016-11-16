jQuery(document).ready(function($) {

    /**
     * Fire slightly differently if this is the admin or front end,
     * support for front end forms.
     */
    if( !$('body').hasClass( 'wp-admin' ) ) {

        $('.acf-form').on( 'click', '.acf-lite-wysiwyg-content-body.uninitalized', function(e) {
            e.preventDefault();
            acf_lite_wysiwyg_init( $(this) );
        });

    } else {

        $('#poststuff').on( 'click', '.acf-lite-wysiwyg-content-body.uninitalized', function(e) {
            e.preventDefault();
            acf_lite_wysiwyg_init( $(this) );
        });

    }

    function acf_lite_wysiwyg_init( elm ) {

        if( $('.acf-lite-wysiwyg-content.initalized').length ) {
            acf_lite_wysiwyg_save();
        }

        // Reset just in case
        $(field).find( '.acf-lite-wysiwyg-error' ).hide();

        // Parent field
        var field = $( elm ).parents( '.acf-lite-wysiwyg-content' );

        // Settings
        var teeny   = $( field ).data( 'teeny' );
        var dfw     = $( field ).data( 'dfw' );
        var toolbar = $( field ).data( 'toolbar' );
        var media_buttons   = $( field ).data( 'media_buttons' );
        var media_upload    = $( field ).data( 'media_upload' );
        var content         = $( field ).find( '.acf-wysiwyg-lite-content' ).val();
        var nonce           = $( field ).data( 'nonce' );
        var id              = $( field ).data( 'id' );
        var hash            = $( field ).attr( 'id' );

        $.ajax({
            url: ajaxurl + "?action=acf_lite_wysiwyg_init",
            type: 'post',
            data: {
                teeny   : teeny,
                dfw     : dfw,
                toolbar : toolbar,
                content : content,
                id      : id,
                nonce   : nonce,
                media_buttons   : media_buttons,
                media_upload    : media_upload,
            },
            success: function( response ) {

                $(field).find( '.acf-lite-wysiwyg-content-body' ).removeClass( 'uninitalized' );
                $(field).find( '.acf-lite-wysiwyg-save' ).show();
                $(field).find( '.acf-lite-wysiwyg-wpeditor' ).html( response.data.data );
                $(field).addClass( 'initalized' );

                tinymce.execCommand( 'mceAddEditor', true, 'acf-lite-wysiwyg-body' );
                quicktags({ id : 'acf-lite-wysiwyg-body' });

                if( $('body').hasClass('wp-admin') ) {
                    window.location.hash = hash;
                }

            }, error: function( data, response ) {

                $(field).find( '.acf-lite-wysiwyg-error' ).show();

            }

        });

    }

    if( $('#psp-projects').length ) {

        $('#poststuff').on('click', '.acf-lite-wysiwyg-save', function(e) {

            e.preventDefault();
            acf_lite_wysiwyg_save();

        });

    } else {

        $('.acf-form').on('click', '.acf-lite-wysiwyg-save', function(e) {

            e.preventDefault();
            acf_lite_wysiwyg_save();

        });

    }

    function acf_lite_wysiwyg_save() {

        if( !$('.acf-lite-wysiwyg-content.initalized').length ) return;

        var field   = $('.acf-lite-wysiwyg-content.initalized');

        var ed      = tinyMCE.get( 'acf-lite-wysiwyg-body' );
        var content = ed.getContent();

        $(field).find('.acf-wysiwyg-lite-content').val( content );
        $(field).find('.acf-lite-wysiwyg-content-body').html( content );

        tinyMCE.execCommand('mceFocus', true, 'acf-lite-wysiwyg-body');
        tinyMCE.execCommand('mceRemoveControl', true, 'acf-lite-wysiwyg-body');
        tinyMCE.get( 'acf-lite-wysiwyg-body' ).remove();

        // Clear out the editor
        $(field).find( '.acf-lite-wysiwyg-wpeditor' ).html('');

        $(field).removeClass( 'initalized' );
        $(field).find( '.acf-lite-wysiwyg-save' ).hide();

        $(field).find('.acf-lite-wysiwyg-content-body').addClass('uninitalized');

    }

    $('#publish').click(function(e) {

        acf_lite_wysiwyg_save();

    });

    $('#save-post').click(function(e) {

        acf_lite_wysiwyg_save();

    });

    $('.acf-form').submit(function(e) {

        acf_lite_wysiwyg_save();

    });

});
