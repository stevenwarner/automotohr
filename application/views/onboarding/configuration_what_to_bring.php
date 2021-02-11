<div class="row">
    <div class="col-xs-12">
        <div class="table-responsive">
            <table class="table table-bordered table-hover table-striped">
                <thead>
                    <tr>
                        <th class="col-xs-4">Item Title</th>
                        <th class="col-xs-6">Item Description</th>
                        <th class="col-xs-2 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(!empty($what_to_bring_items)){ ?>
                        <?php foreach($what_to_bring_items  as $item) { ?>
                            <tr>
                                <td>
                                    <?php echo $item['item_title']; ?>
                                </td>
                                <td>
                                    <?php echo $item['item_description']; ?>
                                </td>

                                <td>
                                    <a title="" data-toggle="tooltip" data-placement="bottom" class="btn btn-info btn-sm pencil_useful_link" data-original-title="Edit Item" onclick="func_edit_what_to_bring_item(<?php echo $item['sid']; ?>, <?php echo $company_sid; ?>);">
                                        <i class="fa fa-pencil"></i></a>
                                    <div class="trash_useful_link">
                                    <form id="form_delete_what_to_bring_item_<?php echo $item['sid']; ?>" enctype="multipart/form-data" method="post" action="<?php echo current_url(); ?>">
                                        <input type="hidden" id="perform_action" name="perform_action" value="delete_what_to_bring_item" />
                                        <input type="hidden" id="company_sid" name="company_sid" value="<?php echo $company_sid; ?>" />
                                        <input type="hidden" id="what_to_bring_item_sid" name="what_to_bring_item_sid" value="<?php echo $item['sid']; ?>" />
                                        <a title="" data-toggle="tooltip" data-placement="bottom" class="btn btn-danger btn-sm" data-original-title="Delete Item" onclick="func_delete_what_to_bring_item(<?php echo $item['sid']; ?>);"><i class="fa fa-trash"></i></a>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php } ?>
                    <?php } else { ?>
                        <tr>
                            <td colspan="5">
                                <span class="no-data">No Items</span>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<hr />
<div class="row" id="add_new_what_to_bring_item_form">
    <div class="col-xs-12">
        <div class="hr-box">
            <div class="hr-box-header">
                Add New Item
            </div>
            <div class="hr-innerpadding">
                <form id="func_insert_new_what_to_bring_item" enctype="multipart/form-data" method="post" action="<?php echo current_url(); ?>">
                    <input type="hidden" id="perform_action" name="perform_action" value="insert_new_what_to_bring_item" />
                    <input type="hidden" id="company_sid" name="company_sid" value="<?php echo $company_sid; ?>" />
                    <div class="universal-form-style-v2">
                        <ul>
                            <li class="form-col-100">
                                <?php $field_id = 'item_title'; ?>
                                <?php echo form_label('Item Title: <span class="required">*</span>', $field_id); ?>
                                <?php echo form_input($field_id, '', 'class="invoice-fields" id="' . $field_id . '"'); ?>
                                <span id="insert_item_title_error" class="text-danger"></span>
                            </li>
                            <li class="form-col-100 autoheight">
                                <?php $field_id = 'item_description'; ?>
                                <?php echo form_label('Item Description:', $field_id); ?>
                                <?php echo form_textarea($field_id, '', 'class="invoice-fields autoheight" id="' . $field_id . '"'); ?>
                                <?php echo form_error($field_id); ?>
                            </li>
                        </ul>
                        <button type="button" class="btn btn-success" id="insert_new_what_to_bring_item_btn">Add New Item</button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>

<!-- Modal -->
<div id="itemsModel" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Edit New Item</h4>
            </div>
            <div class="modal-body">
                <input type="hidden" id="edit_item_sid" name="edit_item_sid" value="" />
                <div class="universal-form-style-v2">
                    <ul>
                        <li class="form-col-100">
                            <label>Item Title:<span class="staric">*</span></label>
                            <input type="text" name="edit_item_title" id="edit_item_title" class="invoice-fields">
                            <span id="edit_item_title_error" class="text-danger"></span>
                        </li>
                        <li class="form-col-100 autoheight">
                            <label>Item Description:</label>
                            <textarea name="edit_item_description" class="invoice-fields autoheight" cols="40" rows="10" id="edit_item_description"></textarea>
                        </li>
                    </ul>    
                    <button class="btn btn-success" id="edit_whatToBring_button">Edit Item</button>
                </div>       
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>

<script>
    function func_delete_what_to_bring_item(what_to_bring_item_sid){
        alertify.confirm(
            'Are you sure?',
            'Are you sure you want to delete this Office Timings?',
            function () {
                $('#form_delete_what_to_bring_item_' + what_to_bring_item_sid).submit();
            },
            function () {
                alertify.error('Cancelled!');
            });
    }

    $(document).ready(function () {

        $('#edit_item_title').keyup(function ()  {
               $('#edit_item_title_error').html('');   
        });

        $('#item_title').keyup(function ()  {
               $('#insert_item_title_error').html('');   
        });

        $( "#insert_new_what_to_bring_item_btn" ).click(function() {
            var item_title = $('#item_title').val();

            if (item_title === "") {
                $('#insert_item_title_error').html('<strong>Title is required.</strong>');
            }else {
                $('#func_insert_new_what_to_bring_item').submit();
            }
        });

        $("#func_insert_new_what_to_bring_item").validate({
            ignore: [],
            rules: {
                title: {
                    required: true
                }
            },
            messages: {
                title: {
                    required: 'Title is required.'
                }
            },
            submitHandler: function (form) {
                form.submit();
            }
        });
    });

    function func_edit_what_to_bring_item(item_sid, company_sid){
        var myurl = "<?= base_url() ?>onboarding/getBringItem/"+item_sid+"/"+company_sid;
        
        $.ajax({
            type: "GET",
            url: myurl,
            async : false,
            success: function (data) {
                var obj = jQuery.parseJSON(data);
                var item_sid = obj.item_sid;
                var item_title = obj.item_title;
                var item_description = obj.item_description;
                $('#edit_item_sid').val(item_sid);
                $('#edit_item_title').val(item_title);
                $('#edit_item_description').val(item_description);
            },
            error: function (data) {

            }
        });

        $('#itemsModel').modal('show');
    }

    $( "#edit_whatToBring_button" ).click(function() {
        var item_title = $('#edit_item_title').val();

        if (item_title === "") {
            $('#edit_item_title_error').html('<strong>Title is required.</strong>');
        } else {
            
            var id = $('#edit_item_sid').val();
            var title = $('#edit_item_title').val();
            var description = $('#edit_item_description').val();

            var myurl = "<?= base_url() ?>onboarding/updateBringItem";
            $.ajax({
                type: 'POST',
                data:{
                    sid:id,
                    item_title: title,
                    item_description: description
                },
                url: myurl,
                success: function(data){
                    location.reload();
                    alertify.success('Link Update  Successfully');
                },
                error: function(){

                }
            });
        }
    });


</script>