<script src="<?=base_url();?>assets/calendar/moment.min.js"></script>


<?php 
    $this->config->load('calendar_config');
    $calendar_opt = $this->config->item('calendar_opt'); 
?>


<div class="main-content">
    <div class="dashboard-wrp">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-4">
                    <?php $this->load->view('main/manage_ems_left_view'); ?>
                </div>
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-8">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                            <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                            <div class="page-header-area">
                                <span class="page-heading down-arrow">
                                    <a class="dashboard-link-btn" href="<?php echo base_url('learning_center'); ?>"><i class="fa fa-chevron-left"></i>Learning Center</a>
                                    <?php echo $title; ?>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <?php if (check_access_permissions_for_view($security_details, 'add_training_session')) { ?>
                            <div class="col-lg-3 col-md-3 col-xs-3 col-sm-3">
                                <a href="<?php echo base_url('learning_center/add_training_session'); ?>" class="btn btn-success btn-block">Add New Session</a>
                            </div>
                        <?php } ?>
                        <div class="col-lg-3 col-md-3 col-xs-3 col-sm-3"></div>
                        <div class="col-lg-3 col-md-3 col-xs-3 col-sm-3"></div>
                        <div class="col-lg-3 col-md-3 col-xs-3 col-sm-3"></div>
                    </div>
                    <hr />
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover table-striped">
                                    <thead>
                                        <tr>
                                            <th rowspan="2" class="col-xs-5 valign-middle">Topic</th>
                                            <th rowspan="2" class="col-xs-1 text-center valign-middle">Date</th>
                                            <th colspan="2" class="col-xs-1 text-center valign-middle">Time</th>
                                            <?php if (check_access_permissions_for_view($security_details, 'status_ajax_responder')) { ?>
                                                <th rowspan="2" class="col-xs-2 text-center valign-middle">Status</th>
                                            <?php } ?>
                                            <?php if (check_access_permissions_for_view($security_details, 'edit_training_session') || check_access_permissions_for_view($security_details, 'delete_training_sessions')) { ?>
                                                <th rowspan="2" class="col-xs-2 text-center valign-middle" colspan="2">Actions</th>
                                            <?php } ?>
                                        </tr>
                                        <tr>
                                            <th class="col-xs-1 text-center valign-middle">Start</th>
                                            <th class="col-xs-1 text-center valign-middle">End</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (!empty($sessions)) { ?>
                                            <?php foreach ($sessions as $session) {
                                                $is_expired = $session['session_date'] >= date('Y-m-d') ? true : false;
                                                ?>
                                                <tr>
                                                    <td><?php echo $session['session_topic']; ?></td>
                                                    <td class="text-center"><?php echo date('m-d-Y', strtotime($session['session_date'])); ?></td>
                                                    <td class="text-center"><?php echo date('H:i', strtotime($session['session_start_time'])); ?></td>
                                                    <td class="text-center"><?php echo date('H:i', strtotime($session['session_end_time'])); ?></td>
                                                    <?php if (check_access_permissions_for_view($security_details, 'status_ajax_responder')) { ?>
                                                        <td>
                                                            <div class="form-group-sm">
                                                                <?php $session_status = $session['session_status']; ?>
                                                                <?php if($is_expired) { ?>
                                                                    <select onchange="func_update_session_status(this);" data-session_sid="<?php echo $session['sid']; ?>" id="session_status" name="session_status" class="form-control">
                                                                    <?php $default_selected = $session_status == 'pending' ? true : false; ?>
                                                                    <option <?php echo set_select('session_status', 'pending', $default_selected); ?> value="pending">Scheduled</option>
                                                                    <?php $default_selected = $session_status == 'cancelled' ? true : false; ?>
                                                                    <option <?php echo set_select('session_status', 'cancelled', $default_selected); ?> value="cancelled">Cancelled</option>
                                                                    <?php $default_selected = $session_status == 'completed' ? true : false; ?>
                                                                    <option <?php echo set_select('session_status', 'completed', $default_selected); ?>  value="completed">Completed</option>
                                                                    <?php $default_selected = $session_status == 'confirmed' ? true : false; ?>
                                                                    <option <?php echo set_select('session_status', 'confirmed', $default_selected); ?>  value="confirmed">Confirmed</option>
                                                                <?php }else{ ?>
                                                                <select class="form-control" disabled="true">
                                                                    <option><?=($session['session_status'] == 'pending' ? 'Scheduled' : ucfirst($session['session_status']));?></option>
                                                                <?php } ?>
                                                                </select>
                                                            </div>
                                                        </td>
                                                    <?php } ?>
                                                    <?php if($is_expired) { ?>
                                                        <?php if (check_access_permissions_for_view($security_details, 'edit_training_session')) { ?>
                                                        <td>
                                                            <a href="<?php echo base_url('learning_center/edit_training_session/' . $session['sid']); ?>" class="btn btn-block btn-success btn-sm">Edit</a>
                                                        </td>
                                                        <?php } if (check_access_permissions_for_view($security_details, 'delete_training_sessions')) { ?>
                                                        <td>
                                                            <form id="form_delete_training_session_<?php echo $session['sid']; ?>" method="post" enctype="multipart/form-data" action="<?php echo current_url(); ?>">
                                                                <input type="hidden" id="perform_action" name="perform_action" value="delete_training_session" />
                                                                <input type="hidden" id="session_sid" name="session_sid" value="<?php echo $session['sid']; ?>" />
                                                            </form>
                                                            <button onclick="func_delete_training_session(<?php echo $session['sid']; ?>);" class="btn btn-block btn-danger btn-sm">Delete</button>
                                                        </td>
                                                        <?php } ?>
                                                    <?php } else { ?>
                                                        <td colspan="2">
                                                            <button class="btn btn-block btn-success btn-sm js-reschedule" data-id="<?=$session['sid'];?>">Reschedule</button>
                                                        </td>
                                                    <?php } ?>
                                                </tr>
                                            <?php } ?>
                                        <?php } else { ?>
                                            <tr>
                                                <td colspan="6">
                                                    <span class="no-data">No Sessions</span>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    function func_delete_training_session(session_sid) {
        alertify.confirm(
                'Are you Sure?',
                'Are you sure you want to delete this training Session?',
                function () {
                    $('#form_delete_training_session_' + session_sid).submit();
                },
                function () {
                    alertify.error('Cancelled!');
                }
        );
    }

    function func_update_session_status(source) {
        var session_sid = $(source).attr('data-session_sid');
        var session_status = $(source).val();

        var my_request;

        $('select').prop('disabled', true);
        my_request = $.ajax({
            url: '<?php echo base_url('learning_center/ajax_responder'); ?>',
            type: 'POST',
            data: {
                'perform_action': 'update_session_status',
                'session_sid': session_sid,
                'session_status': session_status
            }
        });

        my_request.done(function (response) {
            $('select').prop('disabled', false);
            if (response == 'success') {
                alertify.success('Session Status Successfully Updated!');
            } else {
                alertify.error('Could Not Update Session Status!');
            }
        });
    }
</script>

<!-- modal -->
<div class="modal fade" id="js-reschedule-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="javascript:void(0)" id="js-reschedule-form">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">Reschedule Training Session</h4>
                </div>
                <div class="modal-body">
                     <!-- Expired Reschedule box -->
                    <div class="js-reschedule-page" style="padding: 10px;">
                        <div class="row">
                            <div class="col-sm-12">
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-sm-4">
                                                <label>Event Date</label>
                                                <input type="text" class="form-control" readonly="true" id="js-reschedule-event-date" value="<?=date('Y-m-d');?>"/>
                                            </div>
                                            <div class="col-sm-4">
                                                <label>Event Start Time</label>
                                                <input type="text" class="form-control" readonly="true"  id="js-reschedule-event-start-time" value="<?=$calendar_opt['default_start_time'];?>"/>
                                            </div>
                                            <div class="col-sm-4">
                                                <label>Event End Time</label>
                                                <input type="text" class="form-control" readonly="true"  id="js-reschedule-event-end-time" value="<?=$calendar_opt['default_end_time'];?>"/>
                                            </div>
                                        </div>
                                    </div>
                                    <!--  -->
                                    <div class="form-group  pull-right">
                                        
                                    </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="submit" class="btn btn-success" value="Reschedule" readonly="true" />
                    <input type="button" class="btn btn-default" data-dismiss="modal" value="Cancel" readonly="true" />
                </div>
            </form>
        </div>
    </div>
</div>

<div id="my_loader" class="text-center my_loader" style=" display:none; background-color: rgba(0,0,0,.8); z-index: 1099;">
    <div id="file_loader" class="file_loader" style="display: none; height: 1353px;"></div>
    <div class="loader-icon-box">
        <i class="fa fa-refresh fa-spin my_spinner" style="visibility: visible;"></i>
    </div>
</div>

<!-- server -->

<script>

    $(function(){

        var lcid,
        site_date_format = 'MM-DD-YYYY',
        site2_date_format = 'YYYY-MM-DD';

        $('.js-reschedule').click(function(e) {
            e.preventDefault();
            lcid = $(this).data('id');
            $('#js-reschedule-modal').modal();
        });
        //
        $('#js-reschedule-form').submit(function(e) {
            e.preventDefault();
            $('#my_loader').show();
            reschedule_event();
        });

        // Set datepicker for 
        // expired reschedule
        $('#js-reschedule-event-date').datepicker({
            minDate: 0, 
            format: site_date_format,
            onSelect: function(d){
                $('#js-reschedule-event-date').val(moment(d, site_date_format).format(site_date_format));
                // $('#js-reschedule-event-date').datepicker('setDate', moment(d, site_date_format).format(site_date_format));
            }
        }).val(moment().format(site_date_format));
        
        // Loads time plugin for start time field
        // for expired reschedule
        $('#js-reschedule-event-start-time').datetimepicker({
            datepicker: false,
            format: 'g:iA',
            formatTime: 'g:iA',
            step: <?=$calendar_opt['time_duration'];?>,
            onShow: function (ct) {
                this.setOptions({
                    maxTime: $('#js-reschedule-event-end-time').val() ? $('#js-reschedule-event-end-time').val() : false
                });
            }
        });
        
        // Loads time plugin for end time field
        // for expired reschedule
        $('#js-reschedule-event-end-time').datetimepicker({
            datepicker: false,
            format: 'g:iA',
            formatTime: 'g:iA',
            step: <?=$calendar_opt['time_duration'];?>,
            onShow: function (ct) {
                time = $('#js-reschedule-event-start-time').val();
                if(time == '') return false;
                timeAr = time.split(":");
                last = parseInt(timeAr[1].substr(0, 2)) + 15;
                if (last == 0)
                    last = "00";
                mm = timeAr[1].substr(2, 2);
                timeFinal = timeAr[0] + ":" + last + mm;
                this.setOptions({
                        minTime: $('#js-reschedule-event-start-time').val() ? timeFinal : false
                    }
                )
            }
        });


        function reschedule_event(){
            $.post("<?=base_url('calendar/reschedule-training-session');?>", 
                { 
                    lcid: lcid,
                    event_date: $('#js-reschedule-event-date').val(),
                    event_start_time: $('#js-reschedule-event-start-time').val(),
                    event_end_time: $('#js-reschedule-event-end-time').val()
                }, 
                function(resp) {
                    if(resp.Status === false && res.Redirect === true) window.location.reload();
                    if(resp.Status === false) {
                        $('#my_loader').show();
                        altertify.alert(resp.Response, function(){ return; }); return false;
                    }
                    $('#my_loader').show();
                    alertify.alert(resp.Response, function(){ window.location.reload(); });
            });
        }
    })
</script>