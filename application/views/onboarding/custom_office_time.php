<!-- Modal -->
<div id="custom_office_hours_model" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title" id="custom-hour-model-title">Add Custom Office Hours</h4>
            </div>
            <div class="modal-body">
                <div class="universal-form-style-v2">
                    <input type="hidden" name="custom_record_hours_sid" id="custom_record_hours_sid" value="">
                    <ul>
                        <li class="form-col-100">
                            <label>Title:<span class="staric">*</span></label>
                            <input type="text" name="hour_title" id="hours_title" class="invoice-fields" autocomplete="off">
                            <span id="hours_title_error" class="text-danger"></span>
                        </li>
                        <li class="form-col-100 autoheight">
                            <label>Start:<span class="staric">*</span></label>
                            <input type="text" name="hour_start_time" id="start_time" class="invoice-fields" autocomplete="off">
                            <span id="start_time_error" class="text-danger"></span>
                        </li>
                        <li class="form-col-100">
                            <label>End:<span class="staric">*</span></label>
                            <input type="text" name="hour_end_time" id="end_time" class="invoice-fields" autocomplete="off">
                            <span id="end_time_error" class="text-danger"></span>
                        </li>
                    </ul>    
                    <button class="btn btn-success" id="add_custom_office_hours_button">Add Custom Office Hours</button>
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
        $('#hours_title').keyup(function ()  {
               $('#hours_title_error').html('');   
        });

        $('#start_time').keyup(function ()  {
               $('#start_time_error').html('');   
        });

        $('#end_time').keyup(function ()  {
               $('#end_time_error').html('');   
        });

        $('#start_time').datetimepicker({
            datepicker: false,
            format: 'g:iA',
            formatTime: 'g:iA',
            step: 15,
            onChangeDateTime: function (dp, $input) {
                $('#end_time').datetimepicker({
                    minTime: $input.val()
                });
            }
        });

        $('#end_time').datetimepicker({
            datepicker: false,
            format: 'g:iA',
            formatTime: 'g:iA',
            step: 15,
            onChangeDateTime: function (dp, $input) {
                $('#start_time').datetimepicker({
                    maxTime: $input.val()
                });
            }
        });

        $( "#add_custom_office_hour_dtn" ).click(function() {
            $('#custom_record_hours_sid').val('');
            $('#hours_title').val('');
            $('#start_time').val('');
            $('#end_time').val('');
            $('#custom-hour-model-title').html('Add Custom Office Hours');
            $("#add_custom_office_hours_button").html('Add Custom Office Hours');
            $('#custom_office_hours_model').modal('show');
        });

    });

    $("#add_custom_office_hours_button" ).click(function() {
        var record_sid = $('#custom_record_hours_sid').val();
        var hours_title = $('#hours_title').val();
        var hours_start = $('#start_time').val();
        var hours_end = $('#end_time').val();

        if (hours_title === "" && hours_start === "" && hours_end === "") {
            $('#hours_title_error').html('<strong>Title is required.</strong>');
            $('#start_time_error').html('<strong>Select Start Time.</strong>'); 
            $('#end_time_error').html('<strong>Select End Time.</strong>');
            return false;
        } else if (hours_title === "" || hours_start === "" || hours_end === "") {
            if (hours_title === "") {$('#hours_title_error').html('<strong>Title is required.</strong>');}
            if (hours_start === "") {$('#start_time_error').html('<strong>Select Start Time.</strong>'); }
            if (hours_end === "") {$('#end_time_error').html('<strong>Select End Time.</strong>');} 
            return false;   
        } else if (hours_title.length > 0 && hours_start.length > 0 && hours_end.length > 0) {
            var id = $('#company_sid').val();
            var u_sid = $('#user_sid').val();
            var u_type = $('#user_type').val();
            var title = $('#hours_title').val();
            var start = $('#start_time').val();
            var end = $('#end_time').val();
            
            if (record_sid === "") {
                var myurl = "<?= base_url() ?>onboarding/customOfficeHours";
                $.ajax({
                    type: 'POST',
                    data:{
                        company_sid:id,
                        user_sid:u_sid,
                        user_type:u_type,
                        hour_title: title,
                        hour_start_time: start,
                        hour_end_time: end
                    },
                    url: myurl,
                    success: function(data){
                        $('#custom_office_hours_model').modal('hide');
                        var new_timing = '<div class="col-xs-12 col-md-4 col-sm-6 col-lg-3"><label class="package_label" for="custom_timing_'+data+'"><div class="img-thumbnail text-center package-info-box selected-package"><figure><i class="fa fa-clock-o"></i></figure><div class="caption"><h3><strong id="custom_hours_title_'+data+'">'+title+'</strong><br /><small id="custom_hours_time_'+data+'">'+start+' - ' +end+'</small></h3><div class="btn-preview full-width"><button onclick="func_get_custom_timimg('+data+');" type="button" class="btn btn-default btn-sm btn-block">Update Hours</button></div><hr/></div><input class="select-package change_custom_record_status" data-type="time" id="custom_timing_'+data+'" name="" type="checkbox" value="'+data+'"></div></label></div>';
                        $("#custom_office_timing_section").append(new_timing);
                        alertify.success('Custom Hours Insert Successfully');
                    },
                    error: function(){

                    }
                });
            } else {
                var update_custom_url = "<?= base_url() ?>onboarding/updateCustomOfficeHours";
                $.ajax({
                    type: 'POST',
                    data:{
                        sid:record_sid,
                        hour_title: title,
                        hour_start_time: start,
                        hour_end_time: end
                    },
                    url: update_custom_url,
                    success: function(data) {
                        $('#custom_office_hours_model').modal('hide');
                        $("#custom_hours_title_"+record_sid).html(title);
                        $("#custom_hours_time_"+record_sid).html(start+' - '+end);
                        alertify.success('Update Custom Hours Successfully');
                    },
                    error: function(){

                    }
                });
            }
        }
    });

    function func_get_custom_timimg (sid) {
        var myurl = "<?= base_url() ?>onboarding/getCustomRecord/"+sid;
        
        $.ajax({
            type: "GET",
            url: myurl,
            async : false,
            success: function (data) {
                var obj = jQuery.parseJSON(data);
                var hour_sid = obj.sid;
                var hour_title = obj.hour_title;
                var hour_start_time = obj.hour_start_time;
                var hour_end_time = obj.hour_end_time;
                $('#custom_record_hours_sid').val(hour_sid);
                $('#hours_title').val(hour_title);
                $('#start_time').val(hour_start_time);
                $('#end_time').val(hour_end_time);
                $('#custom-hour-model-title').html('Update Hours');
                $("#add_custom_office_hours_button").html('Update Custom Hours');
            },
            error: function (data) {

            }
        });
        
        $('#custom_office_hours_model').modal('show');
    }
</script>

