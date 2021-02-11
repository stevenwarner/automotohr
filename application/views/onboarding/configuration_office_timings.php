<div class="row">
    <div class="col-xs-12">
        <div class="table-responsive">
            <table class="table table-bordered table-hover table-striped">
                <thead>
                    <tr>
                        <th class="col-xs-6">Title</th>
                        <th class="col-xs-2">Start Time</th>
                        <th class="col-xs-2">End Time</th>
                        <th class="col-xs-2 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(!empty($office_timings)){ ?>
                        <?php foreach($office_timings  as $office_timing) { ?>
                            <tr>
                                <td>
                                    <?php echo $office_timing['title']; ?>
                                </td>
                                <td>
                                    <?= reset_datetime(array( 'datetime' => $office_timing['start_time'], '_this' => $this, 'format' => 'h:i A', 'from_format' => 'H:i:s', 'new_zone' => 'PST')); ?>
                                </td>
                                <td>
                                    <?= reset_datetime(array( 'datetime' => $office_timing['end_time'], '_this' => $this, 'format' => 'h:i A', 'from_format' => 'H:i:s', 'new_zone' => 'PST')); ?>
                                </td>
                                <td>
                                    <a title="" data-toggle="tooltip" data-placement="bottom" class="btn btn-info btn-sm pencil_useful_link" data-original-title="Edit Office Hours" onclick="func_edit_offfice_hours(<?php echo $office_timing['sid'];?>, <?php echo $company_sid; ?>);">
                                        <i class="fa fa-pencil"></i>
                                    </a>
                                    <div class="trash_useful_link">
                                        <form id="form_delete_office_timings_<?php echo $office_timing['sid']; ?>" enctype="multipart/form-data" method="post" action="<?php echo current_url(); ?>">
                                            <input type="hidden" id="perform_action" name="perform_action" value="delete_office_timings" />
                                            <input type="hidden" id="company_sid" name="company_sid" value="<?php echo $company_sid; ?>" />
                                            <input type="hidden" id="office_timings_sid" name="office_timings_sid" value="<?php echo $office_timing['sid']; ?>" />
                                            <a title="" data-toggle="tooltip" data-placement="bottom" class="btn btn-danger btn-sm" data-original-title="Delete Office Hours" onclick="func_delete_office_timings(<?php echo $office_timing['sid']; ?>);"><i class="fa fa-trash"></i></a>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php } ?>
                    <?php } else { ?>
                        <tr>
                            <td colspan="5">
                                <span class="no-data">No Office Hours</span>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<hr />
<div class="row" id="add_new_location_form">
    <div class="col-xs-12">
        <div class="hr-box">
            <div class="hr-box-header">
                Add New Office Hours
            </div>
            <div class="hr-innerpadding">
                <form id="func_insert_new_office_timings" enctype="multipart/form-data" method="post" action="<?php echo current_url(); ?>">
                    <input type="hidden" id="perform_action" name="perform_action" value="insert_new_office_timings" />
                    <input type="hidden" id="company_sid" name="company_sid" value="<?php echo $company_sid; ?>" />
                    <div class="universal-form-style-v2">
                        <ul>
                            <li class="form-col-100">
                                <?php $field_id = 'title'; ?>
                                <?php echo form_label('Title: <span class="required">*</span>', $field_id); ?>
                                <?php echo form_input($field_id, '', 'class="invoice-fields" id="' . $field_id . '"'); ?>
                                <?php echo form_error($field_id); ?>
                            </li>
                            <li class="form-col-50-left">
                                <?php $field_id = 'start_time'; ?>
                                <?php echo form_label('Start:', $field_id); ?>
                                <?php echo form_input($field_id, '12:00AM', 'class="invoice-fields" id="' . $field_id . '"'); ?>
                                <?php echo form_error($field_id); ?>
                            </li>
                            <li class="form-col-50-right">
                                <?php $field_id = 'end_time'; ?>
                                <?php echo form_label('End:', $field_id); ?>
                                <?php echo form_input($field_id, '11:00PM', 'class="invoice-fields" id="' . $field_id . '"'); ?>
                                <?php echo form_error($field_id); ?>
                            </li>
                        </ul>
                        <button type="submit" class="btn btn-success">Add New Office Hours</button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>

<!-- Modal -->
<div id="myModalnew" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Edit Office Hours</h4>
            </div>
            <div class="modal-body">
                <input type="hidden" id="edit_hours_sid" name="edit_hours_sid" value="" />
                <div class="universal-form-style-v2">
                    <ul>
                        <li class="form-col-100">
                            <label>Title:<span class="staric">*</span></label>
                            <input type="text" name="edit_hours_title" id="edit_hours_title" class="invoice-fields">
                            <span id="edit_hours_title_error" class="text-danger"></span>
                        </li>
                        <li class="form-col-100 autoheight">
                            <label>Start:</label>
                            <input type="text" name="edit_start_time" id="edit_start_time" class="invoice-fields">
                            <span id="edit_start_time_error" class="text-danger"></span>
                        </li>
                        <li class="form-col-100">
                            <label>End:</label>
                            <input type="text" name="edit_end_time" id="edit_end_time" class="invoice-fields">
                            <span id="edit_end_time_error" class="text-danger"></span>
                        </li>
                    </ul>
                    <button class="btn btn-success" id="edit_office_hours_button">Edit Office Hours</button>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>

<script>
    function func_delete_office_timings(office_timings_sid){
        alertify.confirm(
            'Are you sure?',
            'Are you sure you want to delete this Office Timings?',
            function () {
                $('#form_delete_office_timings_' + office_timings_sid).submit();
            },
            function () {
                alertify.error('Cancelled!');
            });
    }

    $(document).ready(function () {

        $('#edit_hours_title').keyup(function ()  {
               $('#edit_hours_title_error').html('');
        });

        $('#edit_start_time').keyup(function ()  {
               $('#edit_start_time_error').html('');
        });

        $('#edit_end_time').keyup(function ()  {
               $('#edit_end_time_error').html('');
        });

        $('#start_time').datetimepicker({
            datepicker: false,
            format: 'g:iA',
            formatTime: 'g:iA',
            //allowTimes: func_get_allowed_times(),
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
            //allowTimes: func_get_allowed_times(),
            step: 15,
            onChangeDateTime: function (dp, $input) {
                $('#start_time').datetimepicker({
                    maxTime: $input.val()
                });
            }
        });

        $('#edit_start_time').datetimepicker({
            datepicker: false,
            format: 'g:iA',
            formatTime: 'g:iA',
            //allowTimes: func_get_allowed_times(),
            step: 15,
            onChangeDateTime: function (dp, $input) {
                $('#edit_end_time').datetimepicker({
                    minTime: $input.val()
                });
            }
        });

        $('#edit_end_time').datetimepicker({
            datepicker: false,
            format: 'g:iA',
            formatTime: 'g:iA',
            //allowTimes: func_get_allowed_times(),
            step: 15,
            onChangeDateTime: function (dp, $input) {
                $('#edit_start_time').datetimepicker({
                    maxTime: $input.val()
                });
            }
        });

        $("#func_insert_new_office_timings").validate({
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

    function func_edit_offfice_hours(hours_sid, company_sid){
        var myurl = "<?= base_url() ?>onboarding/getOfficeHours/"+hours_sid+"/"+company_sid;

        $.ajax({
            type: "GET",
            url: myurl,
            async : false,
            success: function (data) {
                var obj = jQuery.parseJSON(data);
                var hours_sid = obj.hours_sid;
                var hours_title = obj.hours_title;
                var hours_start = obj.hours_start_time;
                var hours_end = obj.hours_end_time;
                $('#edit_hours_sid').val(hours_sid);
                $('#edit_hours_title').val(hours_title);
                $('#edit_start_time').val(hours_start);
                $('#edit_end_time').val(hours_end);
            },
            error: function (data) {

            }
        });
        $('#myModalnew').modal('show');
    }

    $( "#edit_office_hours_button" ).click(function() {
        var hours_title = $('#edit_hours_title').val();
        var hours_start = $('#edit_start_time').val();
        var hours_end = $('#edit_end_time').val();

        if (hours_title === "" && hours_start === "" && hours_end === "") {
            $('#edit_hours_title_error').html('<strong>Title is required.</strong>');
            $('#edit_start_time_error').html('<strong>Select Start Time.</strong>');
            $('#edit_end_time_error').html('<strong>Select End Time.</strong>');
        } else if (hours_title === "" || hours_start === "" || hours_end === "") {
            if (hours_title === "") {$('#edit_hours_title_error').html('<strong>Title is required.</strong>');}
            if (hours_start === "") {$('#edit_start_time_error').html('<strong>Select Start Time.</strong>'); }
            if (hours_end === "") {$('#edit_end_time_error').html('<strong>Select End Time.</strong>');}
        } else {

            var id = $('#edit_hours_sid').val();
            var title = $('#edit_hours_title').val();
            var start = $('#edit_start_time').val();
            var end = $('#edit_end_time').val();

            var myurl = "<?= base_url() ?>onboarding/updateOfficeHours";
            $.ajax({
                type: 'POST',
                data:{
                    sid:id,
                    hours_title: title,
                    hours_start: start,
                    hours_end: end
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

    function func_get_allowed_times(){
        var return_array = [];
        for(var iHours = 0; iHours < 24; iHours++){
            for(var iMinutes = 0; iMinutes < 60; iMinutes = iMinutes + 15){
                var pad = "00";
                var hour = "" + iHours;

                var hour = pad.substring(0, pad.length - hour.length) + hour;

                var minutes = "" + iMinutes;

                var minutes = pad.substring(0, pad.length - minutes.length) + minutes;

                var time = hour + ':' + minutes;

                return_array.push(time);
            }
        }

        //console.log(return_array);
        return return_array;
    }
</script>
