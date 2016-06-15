jQuery(function ($) {

    /**
     * Add Gallery Images
     */
    var portfolio_gallery_frame;
    var $image_gallery_ids = $('#ng_portfolio_gallery');
    var $portfolio_images = $('#portfolio-images');

    jQuery('#add-portfolio-images').on('click', 'a', function (event) {
        console.log('test');
        var $el = $(this);

        event.preventDefault();

        // If the media frame already exists, reopen it.
        if (portfolio_gallery_frame) {
            portfolio_gallery_frame.open();
            return;
        }

        // Create the media frame.
        portfolio_gallery_frame = wp.media.frames.product_gallery = wp.media({
            // Set the title of the modal.
            title: $el.data('choose'),
            button: {
                text: $el.data('update')
            },
            states: [
                new wp.media.controller.Library({
                    title: $el.data('choose'),
                    filterable: 'all',
                    multiple: true
                })
            ]
        });

        // When an image is selected, run a callback.
        portfolio_gallery_frame.on('select', function () {
            var selection = portfolio_gallery_frame.state().get('selection');
            var attachment_ids = $image_gallery_ids.val();

            selection.map(function (attachment) {
                attachment = attachment.toJSON();

                if (attachment.id) {
                    attachment_ids = attachment_ids ? attachment_ids + ',' + attachment.id : attachment.id;
                    var attachment_image = attachment.sizes && attachment.sizes.thumbnail ? attachment.sizes.thumbnail.url : attachment.url;

                    $portfolio_images.append('<li class="image" data-attachment_id="' + attachment.id + '"><img src="' + attachment_image + '"><div class="actions"><a href="#" class="delete" title="' + $el.data('delete') + '">' + $el.data('text') + '</a></div></li>');
                }
            });

            $image_gallery_ids.val(attachment_ids);
        });

        // Finally, open the modal.
        portfolio_gallery_frame.open();
    });

    /**
     * Image Sorting
     */
    $portfolio_images.sortable({
        items: 'li.image',
        cursor: 'move',
        scrollSensitivity: 40,
        forcePlaceholderSize: true,
        forceHelperSize: false,
        helper: 'clone',
        opacity: 0.65,
        placeholder: 'ng-portfolio-sortable-placeholder',
        start: function( event, ui ) {
            ui.item.css( 'background-color', '#f6f6f6' );
        },
        stop: function( event, ui ) {
            ui.item.removeAttr( 'style' );
        },
        update: function() {
            var attachment_ids = '';

            $( '#portfolio-gallery-container' ).find( 'ul li.image' ).each( function() {
                var attachment_id = jQuery( this ).attr( 'data-attachment_id' );
                attachment_ids = attachment_ids + attachment_id + ',';
            });

            $image_gallery_ids.val( attachment_ids );
        }
    });

    /**
     * Remove Images
     */
    $( '#portfolio-gallery-container' ).on( 'click', 'a.delete', function() {
        $( this ).closest( 'li.image' ).remove();

        var attachment_ids = '';

        $( '#portfolio-gallery-container' ).find( 'ul li.image' ).css( 'cursor', 'default' ).each( function() {
            var attachment_id = jQuery( this ).attr( 'data-attachment_id' );
            attachment_ids = attachment_ids + attachment_id + ',';
        });

        $image_gallery_ids.val( attachment_ids );

        return false;
    });

});