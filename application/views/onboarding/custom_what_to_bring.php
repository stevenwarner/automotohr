<!-- Modal -->
<div id="custom_what_to_bring_model" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title" id="custom-item-modal-title">Add New Custom Item</h4>
            </div>
            <div class="modal-body">
                <div class="universal-form-style-v2">
                    <input type="hidden" name="custom_record_item_sid" id="custom_record_item_sid" value="">
                    <ul>
                        <li class="form-col-100">
                            <label>Item Title:<span class="staric">*</span></label>
                            <input type="text" name="item_title" id="custom_item_title" class="invoice-fields">
                            <span id="custom_item_title_error" class="text-danger"></span>
                        </li>
                        <li class="form-col-100 autoheight">
                            <label>Item Description:</label>
                            <textarea name="item_description" class="invoice-fields autoheight" cols="40" rows="10" id="custom_item_description"></textarea>
                        </li>
                    </ul>    
                    <button class="btn btn-success" id="custom_whatToBring_button">Add Custom Item</button>
                </div>       
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>

<script>

    $(document).ready(function () {
        $('#custom_item_title').keyup(function ()  {
               $('#custom_item_title_error').html('');   
        });

        $( "#add_custom_item_dtn" ).click(function() {
            $('#custom_record_item_sid').val('');
                $('#custom_item_title').val('');
                $('#custom_item_description').val('');
            $('#custom-item-modal-title').html('Add New Custom Item');
            $("#custom_whatToBring_button").html('Add Custom Item');
            $('#custom_what_to_bring_model').modal('show');
        });
    });

    $( "#custom_whatToBring_button" ).click(function() {
        var record_sid = $('#custom_record_item_sid').val();
        var item_title = $('#custom_item_title').val();

        if (item_title === "") {
            $('#custom_item_title_error').html('<strong>Title is required.</strong>');
        } else if (item_title.length > 0){
            
            var id = $('#company_sid').val();
            var u_sid = $('#user_sid').val();
            var u_type = $('#user_type').val();
            var title = $('#custom_item_title').val();
            var description = $('#custom_item_description').val();

            if (record_sid === "") {
                var myurl = "<?= base_url() ?>onboarding/customWhatToBring";
                $.ajax({
                    type: 'POST',
                    data:{
                        company_sid:id,
                        user_sid:u_sid,
                        user_type:u_type,
                        item_title: title,
                        item_description: description
                    },
                    url: myurl,
                    success: function(data){
                        $('#custom_what_to_bring_model').modal('hide');
                        var new_item = '<div class="col-xs-12 col-md-4 col-sm-6 col-lg-3"><label class="package_label" for="custom_item_'+data+'"><div class="img-thumbnail text-center package-info-box selected-package"><figure><i class="fa fa-star"></i></figure><div class="caption"><h3><strong id="custom_item_title_'+data+'">'+title+'</strong><br /><small id="custom_item_description_'+data+'">'+description+'</small></h3><div class="btn-preview full-width"><button onclick="func_get_custom_item('+data+');" type="button" class="btn btn-default btn-sm btn-block">Update Item</button></div></div><input class="select-package change_custom_record_status" data-type="location" id="custom_item_'+data+'" name="" type="checkbox" value="'+data+'"></div></label></div>';
                        $("#custom_office_item_section").append(new_item);
                        alertify.success('New Custom Location Add Successfully');
                    },
                    error: function(){

                    }
                });
            } else {
                var update_custom_url = "<?= base_url() ?>onboarding/updateCustomOfficeItem";
                $.ajax({
                    type: 'POST',
                    data:{
                        sid:record_sid,
                        item_title: title,
                        item_description: description
                    },
                    url: update_custom_url,
                    success: function(data){
                        $('#custom_what_to_bring_model').modal('hide');
                        $("#custom_item_title_"+record_sid).html(title);
                        $("#custom_item_description_"+record_sid).html(description);
                        alertify.success('Update Custom Item Successfully');
                    },
                    error: function(){
                    }
                });
            }    
        }
    });

    function func_get_custom_item (sid) {
        var myurl = "<?= base_url() ?>onboarding/getCustomRecord/"+sid;
        
        $.ajax({
            type: "GET",
            url: myurl,
            async : false,
            success: function (data) {
                var obj = jQuery.parseJSON(data);
                var item_sid = obj.sid;
                var item_title = obj.item_title;
                var item_description = obj.item_description;
                $('#custom_record_item_sid').val(item_sid);
                $('#custom_item_title').val(item_title);
                $('#custom_item_description').val(item_description);
                $('#custom-item-modal-title').html('Update Item');
                $("#custom_whatToBring_button").html('Update Custom Item');
            },
            error: function (data) {

            }
        });
        $('#custom_what_to_bring_model').modal('show');
    }


</script>
