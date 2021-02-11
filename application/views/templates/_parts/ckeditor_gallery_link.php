<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<a href="<?php echo base_url('photo_gallery/gallery_view'); ?>" data-target="#photo_gallery_modal" data-toggle="modal" class="ckeditor_custom_link btn btn-default btn-sm" data-preview-url="" data-preview-title="Photo Gallery" >
    Get Photos URL from Photo Gallery
</a>

<button type="button" onclick="func_show_instructions_modal();" class="ckeditor_custom_link btn btn-default btn-sm">
    How to add Image in Editor
</button>

<?php if(!isset($show)){ ?>
<script>
    function func_show_instructions_modal(){
        var myRequest;
        var myData = { 'perform_action' : 'get_instructions' };
        var myUrl = '<?php echo base_url("photo_gallery/ajax_responder"); ?>';



        myRequest = $.ajax({
           url : myUrl,
            type: 'POST',
            data: myData,
            dataType: 'json'
        });

        myRequest.done(function (response) {

            $('#popupmodallabel').html(response.title);
            $('#popupmodalbody').html(response.view);

            $('#popupmodal .modal-dialog').css('width', '75%');
            $('#popupmodal').modal('toggle');
        });
    }
</script>
<?php } ?>