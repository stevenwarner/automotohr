
<!-- Modal -->
<div id="custom_office_location_model" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title" id="custom-location-model-title">Add Custom Office Location</h4>
            </div>
            <div class="modal-body">
                <div class="universal-form-style-v2">
                    <input type="hidden" name="custom_record_location_sid" id="custom_record_location_sid" value="">
                    <ul>
                        <li class="form-col-100">
                            <label>Title:<span class="staric">*</span></label>
                            <input type="text" name="location_title" id="custom_location_title" class="invoice-fields">
                            <span id="custom_location_title_error" class="text-danger"></span>
                        </li>
                        <li class="form-col-100 autoheight">
                            <label>Address:<span class="staric">*</span></label>
                            <textarea name="location_address" class="invoice-fields autoheight" cols="40" rows="10" id="custom_location_address"></textarea>
                            <span id="custom_location_address_error" class="text-danger"></span>
                        </li>
                        <li class="form-col-100">
                            <label>Phone:</label>
                            <input type="text" name="location_phone" id="custom_location_telephone" class="invoice-fields">
                            <span id="custom_location_telephone_error" class="text-danger"></span>
                        </li>
                        <li class="form-col-100">
                            <label>Fax:</label>
                            <input type="text" name="location_fax" id="custom_location_fax" class="invoice-fields">
                            <span id="custom_location_fax_error" class="text-danger"></span>
                        </li>
                    </ul>
                    <button class="btn btn-success" id="add_custom_location_button">Add Custom Location</button>
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
        $('#custom_location_title').keyup(function ()  {
               $('#custom_location_title_error').html('');   
        });

        $('#custom_location_address').keyup(function ()  {
               $('#custom_location_address_error').html('');   
        });

        $('#custom_location_telephone').keyup(function ()  {
               $('#custom_location_telephone_error').html('');   
        });

        $('#custom_location_fax').keyup(function ()  {
               $('#custom_location_fax_error').html('');   
        });

        $( "#add_custom_location_dtn" ).click(function() {
            $('#custom_record_location_sid').val('');
            $('#custom_location_title').val('');
            $('#custom_location_address').val('');
            $('#custom_location_telephone').val('');
            $('#custom_location_fax').val('');
            $('#custom-location-model-title').html('Add Custom Office Location');
            $("#add_custom_location_button").html('Add Custom Location');
            $('#custom_office_location_model').modal('show');
        });
    });

    $( "#add_custom_location_button" ).click(function() {
        var record_sid = $('#custom_record_location_sid').val();
        var location_title = $('#custom_location_title').val();
        var location_address = $('#custom_location_address').val();
        var location_telephone = $('#custom_location_telephone').val();
        var location_fax = $('#custom_location_fax').val();

        if (location_title === "" && location_address === "") {
            $('#custom_location_title_error').html('<strong>Title is required.</strong>');
            $('#custom_location_address_error').html('<strong>Address is required.</strong>');  
        } else if (location_title === "" || location_address === "") {
            if (location_title === "") {$('#custom_location_title_error').html('<strong>Title is required.</strong>');}
            if (location_address === "") {$('#custom_location_address_error').html('<strong>Address is required.</strong>');} 
        } else if (location_title.length > 0 && location_address.length > 0) {
            var id = $('#company_sid').val();
            var u_sid = $('#user_sid').val();
            var u_type = $('#user_type').val();
            var title = location_title;
            var address = location_address;
            var phone = location_telephone;
            var fax = location_fax;

            if (record_sid === "") {
                var insert_custom_url = "<?= base_url() ?>onboarding/customOfficeLocation";
                $.ajax({
                    type: 'POST',
                    data:{
                        company_sid:id,
                        user_sid:u_sid,
                        user_type:u_type,
                        location_title: title,
                        location_address: address,
                        location_phone: phone,
                        location_fax: fax
                    },
                    url: insert_custom_url,
                    success: function(data){
                        $('#custom_office_location_model').modal('hide');
                        var new_location = '<div class="col-xs-12 col-md-4 col-sm-6 col-lg-3"><label class="package_label" for="custom_location_'+data+'"><div class="img-thumbnail text-center package-info-box selected-package"><figure><i class="fa fa-map"></i></figure><div class="caption"><h3><strong id="custom_location_title_'+data+'">'+title+'</strong></h3><div class="btn-preview full-width"><button onclick="func_get_custom_location('+data+');" type="button" class="btn btn-default btn-sm btn-block">Update Location</button></div></div><input class="select-package change_custom_record_status" data-type="location" id="custom_location_'+data+'" name="" type="checkbox" value="'+data+'"></div></label></div>';
                        $("#custom_office_location_section").append(new_location);
                        alertify.success('New Custom Location Add Successfully');
                    },
                    error: function(){

                    }
                });
            } else {
                var update_custom_url = "<?= base_url() ?>onboarding/updateCustomOfficeLocation";
                $.ajax({
                    type: 'POST',
                    data:{
                        sid:record_sid,
                        location_title: title,
                        location_address: address,
                        location_phone: phone,
                        location_fax: fax
                    },
                    url: update_custom_url,
                    success: function(data){
                        $('#custom_office_location_model').modal('hide');
                        $("#custom_location_title_"+record_sid).html(title);
                        alertify.success('Update Custom Location Successfully');
                    },
                    error: function(){

                    }
                });
            }          
        }
    });

    function func_get_custom_location (sid) {
        var myurl = "<?= base_url() ?>onboarding/getCustomRecord/"+sid;
        
        $.ajax({
            type: "GET",
            url: myurl,
            async : false,
            success: function (data) {
                var obj = jQuery.parseJSON(data);
                var location_sid = obj.sid;
                var location_title = obj.location_title;
                var location_address= obj.location_address;
                var location_telephone = obj.location_phone;
                var location_fax = obj.location_fax;
                $('#custom_record_location_sid').val(location_sid);
                $('#custom_location_title').val(location_title);
                $('#custom_location_address').val(location_address);
                $('#custom_location_telephone').val(location_telephone);
                $('#custom_location_fax').val(location_fax);
                $('#custom-location-model-title').html('Update Office Location');
                $("#add_custom_location_button").html('Update Custom Location');
            },
            error: function (data) {

            }
        });
        $('#custom_office_location_model').modal('show');
    }
</script>