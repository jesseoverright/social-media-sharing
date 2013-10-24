jQuery(document).ready(function($){
 
    var custom_uploader;
 
    $('#social_media_sharing_upload_image_button').click(function(e) {
 
        e.preventDefault();

        console.log('yes');
 
        //If the uploader object has already been created, reopen the dialog
        if (custom_uploader) {
            custom_uploader.open();
            return;
        }
 
        //Extend the wp.media object
        custom_uploader = wp.media.frames.file_frame = wp.media({
            title: 'Choose Image',
            button: {
                text: 'Choose Image'
            },
            multiple: false
        });
 
        //When a file is selected, grab the URL and set it as the text field's value
        custom_uploader.on('select', function() {
            attachment = custom_uploader.state().get('selection').first().toJSON();
            $('#social_media_sharing_default_image_id').val(attachment.id);
            $('#social_media_sharing_image').html('<img src="'+attachment.url+'" height="300" width="300">');
        });
 
        //Open the uploader dialog
        custom_uploader.open();
 
    });

    $('#social_media_sharing_remove_image_button').click(function(e) {
        $('#social_media_sharing_default_image_id').val('');
        $('#social_media_sharing_image').html('');
    });
 
});