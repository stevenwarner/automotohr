<div class="row">
    <div class="col-xs-12">
        <div class="table-responsive">
            <table class="table table-bordered table-hover table-striped">
                <thead>
                    <tr>
                        <th class="col-xs-2">Link Title</th>
                        <th class="col-xs-3">Link Description</th>
                        <th class="col-xs-5">Link Url</th>
                        <th class="col-xs-2 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(!empty($useful_links)){ ?>
                        <?php foreach($useful_links  as $useful_link) { ?>
                            <tr>
                                <td>
                                    <?php echo $useful_link['link_title']; ?>
                                </td>
                                <td>
                                    <?php echo $useful_link['link_description']; ?>
                                </td>
                                <td>
                                    <?php echo $useful_link['link_url']; ?>
                                </td>
                                <td>
                                    <a title="" data-toggle="tooltip" data-placement="bottom" class="btn btn-info btn-sm pencil_useful_link" data-original-title="Edit Link" onclick="func_edit_useful_link(<?php echo $useful_link['sid']; ?>, <?php echo $company_sid; ?>);">
                                        <i class="fa fa-pencil"></i>
                                    </a>
                                    <div class="trash_useful_link">
                                        <form id="form_delete_useful_link_<?php echo $useful_link['sid']; ?>" enctype="multipart/form-data" method="post" action="<?php echo current_url(); ?>">
                                            <input type="hidden" id="perform_action" name="perform_action" value="delete_useful_link" />
                                            <input type="hidden" id="company_sid" name="company_sid" value="<?php echo $company_sid; ?>" />
                                            <input type="hidden" id="useful_link_sid" name="useful_link_sid" value="<?php echo $useful_link['sid']; ?>" />
                                            <a title="" data-toggle="tooltip" data-placement="bottom" class="btn btn-danger btn-sm" data-original-title="Delete Link" onclick="func_delete_useful_link(<?php echo $useful_link['sid']; ?>);"><i class="fa fa-trash"></i></a>
                                        </form>
                                    </div>
                                    
                                </td>
                            </tr>
                        <?php } ?>
                    <?php } else { ?>
                        <tr>
                            <td colspan="5">
                                <span class="no-data">No Links</span>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<hr />
<div class="row" id="add_new_useful_link_form">
    <div class="col-xs-12">
        <div class="hr-box">
            <div class="hr-box-header">
                Add New Link
            </div>
            <div class="hr-innerpadding">
                <form id="func_add_new_useful_link" enctype="multipart/form-data" method="post" action="<?php echo current_url(); ?>">
                    <input type="hidden" id="perform_action" name="perform_action" value="insert_new_useful_link" />
                    <input type="hidden" id="company_sid" name="company_sid" value="<?php echo $company_sid; ?>" />
                    <div class="universal-form-style-v2">
                        <ul>
                            <?php echo form_error('custom_category'); ?>
                            <li class="form-col-100">
                                <?php $field_id = 'link_title'; ?>
                                <?php echo form_label('Link Title:<span class="staric">*</span>', $field_id); ?>
                                <?php echo form_input($field_id, '', 'class="invoice-fields" id="' . $field_id . '"'); ?>
                                <?php echo form_error($field_id); ?>
                                <span id="link_title_error" class="text-danger"></span>
                            </li>
                            <li class="form-col-100 autoheight">
                                <?php $field_id = 'linkDescription'; ?>
                                <?php echo form_label('Link Description:', $field_id); ?>
                                <?php echo form_textarea($field_id, '', 'class="invoice-fields autoheight" id="' . $field_id . '"'); ?>
                                <?php echo form_error($field_id); ?>
                            </li>
                            <li class="form-col-100">
                                <?php $field_id = 'link_url'; ?>
                                <?php echo form_label('Link Url:', $field_id); ?>
                                <?php echo form_input($field_id, '', 'class="invoice-fields" id="' . $field_id . '"'); ?>
                                <span id="link_url_error" class="text-danger"></span>
                            </li>
                        </ul>
                        <button type="button" class="btn btn-success" id="add_useful_link_button">Add New Link</button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>

<!-- Modal -->
<div id="myLinkModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Modal Header</h4>
            </div>
            <div class="modal-body">
                <input type="hidden" id="edit_link_sid" name="edit_link_sid" value="" />
                <div class="universal-form-style-v2">
                    <ul>
                        <?php echo form_error('custom_category'); ?>
                        <li class="form-col-100">
                            <label>Link Title:<span class="staric">*</span></label>
                            <input type="text" name="edit_link_title" id="edit_link_title" class="invoice-fields">
                            <span id="edit_link_title_error" class="text-danger"></span>
                        </li>
                        <li class="form-col-100 autoheight">
                            <label>Link Description:</label>
                            <textarea name="edit_link_description" class="invoice-fields autoheight" cols="40" rows="10" id="edit_link_description"></textarea>
                        </li>
                        <li class="form-col-100">
                            <label>Link Url:<span class="staric">*</span></label>
                            <input type="text" name="edit_link_url" id="edit_link_url" class="invoice-fields">
                            <span id="edit_link_url_error" class="text-danger"></span>
                        </li>
                    </ul>
                    <button type="button" class="btn btn-success float-right" id="edit_useful_link_button">Edit Link</button>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function(){

        $('#link_title').keyup(function ()  {
               $('#link_title_error').html('');   
        });

        $('#link_url').keyup(function ()  {
               $('#link_url_error').html('');   
        });

        $('#edit_link_title').keyup(function ()  {
               $('#edit_link_title_error').html('');   
        });

        $('#edit_link_url').keyup(function ()  {
               $('#edit_link_url_error').html('');   
        });
    });

    function func_delete_useful_link(useful_link_sid){
        alertify.confirm(
            'Are you sure?',
            'Are you sure you want to delete this Office Timings?',
            function () {
                $('#form_delete_useful_link_' + useful_link_sid).submit();
            },
            function () {
                alertify.error('Cancelled!');
            });
    }

    function func_edit_useful_link(useful_link_sid, company_sid){
        var myurl = "<?= base_url() ?>onboarding/getUsefulLink/"+useful_link_sid+"/"+company_sid;
        
        $.ajax({
            type: "GET",
            url: myurl,
            async : false,
            success: function (data) {
                var obj = jQuery.parseJSON(data);
                var link_id = obj.sid;
                var link_url = obj.link_url;
                var link_title= obj.link_title;
                var link_description = obj.link_description;
                $('#edit_link_sid').val(link_id);
                $('#edit_link_title').val(link_title);
                $('#edit_link_description').val(link_description);
                $('#edit_link_url').val(link_url);
            },
            error: function (data) {

            }
        });
        $('#myLinkModal').modal('show');
    }

    $( "#add_useful_link_button" ).click(function() {
        var title = $('#link_title').val();
        var url = $('#link_url').val();

        // if (title === "" && url === "") {
        //     $('#link_title_error').html('<strong>please provide title</strong>');
        //     $('#link_url_error').html('<strong>please provide valid link</strong>'); 
        // } else if (title === "") {
        //     $('#link_title_error').html('<strong>please provide title</strong>');
        //     var regex = /(http|https):\/\/(\w+:{0,1}\w*)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%!\-\/]))?/;
        //     if(!regex .test(url)) {  
        //         $('#link_url_error').html('<strong>Please enter valid Link</strong>'); 
        //     }
        // } else if (url === "") {    
        //     $('#link_url_error').html('<strong>please provide valid link</strong>'); 
        // } else {
        //     var regex = /(http|https):\/\/(\w+:{0,1}\w*)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%!\-\/]))?/;
        //     if(!regex .test(url)) {  
        //         $('#link_url_error').html('<strong>Please enter valid Link</strong>'); 
        //     } else {
        //         document.getElementById("func_add_new_useful_link").submit(); 
        //     } 
        // }

        if (title === "") {
            $('#link_title_error').html('<strong>please provide title</strong>');
        } else {
            if (url === "") {
                document.getElementById("func_add_new_useful_link").submit(); 
            } else {
                var regex = /(http|https):\/\/(\w+:{0,1}\w*)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%!\-\/]))?/;
                if(!regex .test(url)) {  
                    $('#link_url_error').html('<strong>Please enter valid Link</strong>'); 
                } else {
                    document.getElementById("func_add_new_useful_link").submit(); 
                }
            }  
        }
        
    });

    $( "#edit_useful_link_button" ).click(function() {
        var title = $('#edit_link_title').val();
        var url = $('#edit_link_url').val();

        if (title === "" && url === "") {
            $('#edit_link_title_error').html('<strong>please provide title</strong>');
            $('#edit_link_url_error').html('<strong>please provide valid link</strong>'); 
        } else if (title === "") {
            $('#edit_link_title_error').html('<strong>please provide title</strong>');
            var regex = /(http|https):\/\/(\w+:{0,1}\w*)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%!\-\/]))?/;
            if(!regex .test(url)) {  
                $('#edit_link_url_error').html('<strong>Please enter valid Link</strong>'); 
            }
        } else if (url === "") {    
            $('#edit_link_url_error').html('<strong>please provide valid link</strong>'); 
        } else {
            var regex = /(http|https):\/\/(\w+:{0,1}\w*)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%!\-\/]))?/;
            if(!regex .test(url)) {  
                $('#edit_link_url_error').html('<strong>Please enter valid Link</strong>'); 
            } else {
                var id = $('#edit_link_sid').val();
                var title = $('#edit_link_title').val();
                var description = $('#edit_link_description').val();
                var link_url = $('#edit_link_url').val();

                var myurl = "<?= base_url() ?>onboarding/updateUsefulLink";
                $.ajax({
                    type: 'POST',
                    data:{
                        sid:id,
                        link_title: title,
                        link_description: description,
                        useful_link: link_url
                    },
                    url: myurl,
                    success: function(data){
                        console.log(data);
                        location.reload();
                        alertify.success('Link Update  Successfully');
                    },
                    error: function(){

                    }
                });
                 
            } 
        }
        
    });
</script>
