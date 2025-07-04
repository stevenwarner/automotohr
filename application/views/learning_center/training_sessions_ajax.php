<!-- momentjs -->
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
                                <span class="page-heading down-arrow"><?php $this->load->view('manage_employer/company_logo_name'); ?>
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
                    <!-- Tabs -->
                    <div id="HorizontalTab" class="HorizontalTab">
                        <ul class="resp-tabs-list">
                            <li id="tab1_nav" class="js-status resp-tab-item <?=$activeTab == 'pending' ? 'resp-tab-active' : '';?>" aria-controls="hor_1_tab_item-0" role="tab" style="background-color: rgb(37, 37, 36); border-color: rgb(193, 193, 193);"><a href="javascript:void(0);" data-value="pending">Scheduled (<span class="js-pending-count">0</span>)</a></li>
                            <li id="tab2_nav" class="js-status resp-tab-item <?=$activeTab == 'confirmed' ? 'resp-tab-active' : '';?>" aria-controls="hor_1_tab_item-1" role="tab" style="background-color: rgb(70, 107, 29);"><a href="javascript:void(0);" data-value="confirmed">Confirmed (<span class="js-confirmed-count">0</span>)</a></li>
                            <li id="tab3_nav" class="js-status resp-tab-item <?=$activeTab == 'cancelled' ? 'resp-tab-active' : '';?>" aria-controls="hor_1_tab_item-2" role="tab" style="background-color: rgb(70, 107, 29);"><a href="javascript:void(0);" data-value="cancelled">Cancelled (<span class="js-cancelled-count">0</span>)</a></li>
                            <li id="tab4_nav" class="js-status resp-tab-item <?=$activeTab == 'completed' ? 'resp-tab-active' : '';?>" aria-controls="hor_1_tab_item-3" role="tab" style="background-color: rgb(70, 107, 29);"><a href="javascript:void(0);" data-value="completed">Completed (<span class="js-completed-count">0</span>)</a></li>
                            <!-- <li id="tab4_nav" class="js-status resp-tab-item <=//$activeTab == 'expired' ? 'resp-tab-active' : '';?>" aria-controls="hor_1_tab_item-3" role="tab" style="background-color: rgb(70, 107, 29);"><a href="javascript:void(0);" data-value="expired">Expired (<span class="js-expired-count">0</span>)</a></li> -->
                        </ul>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="js-pagination-area pull-right"></div>
                        </div>
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
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="js-pagination-area pull-right"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


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
        site2_date_format = 'YYYY-MM-DD',
        pagination_settings = {},
        ts_status = "<?=$activeTab;?>";
        //
        pagination_settings.first = '.js-pagination-first';
        pagination_settings.last  = '.js-pagination-last';
        pagination_settings.next  = '.js-pagination-next';
        pagination_settings.prev  = '.js-pagination-prev';
        pagination_settings.shift = '.js-pagination-shift';
        pagination_settings.list_size = 5;
        pagination_settings.area      = $('.js-pagination');
        pagination_settings.target    = $('.js-pagination-area');
        pagination_settings.total = 100;
        pagination_settings.cb    = fetch_training_sessions;
        pagination_settings.current_page = 1;
        pagination_settings.loader = loader;

        history.pushState({}, "", "<?=base_url('learning_center/training_sessions');?>/"+(ts_status == 'pending' ? 'scheduled' : ts_status)+"");

        fetch_training_sessions();

        $(document).on('click', '.js-reschedule', function(e) {
            e.preventDefault();
            lcid = $(this).data('id');
            $('#js-reschedule-modal').modal();
        });

        //
        $(document).on('change', '.js-ts-status-change', function(e) {
            e.preventDefault();
            func_update_session_status($(this));  
        });

        //
        $(document).on('click', '.js-ts-delete', function(e) {
            e.preventDefault();
            func_delete_training_session($(this), $(this).data('id'));   
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

        //
        $('.js-status').click(function(e) {
            e.preventDefault();
            ts_status = $(this).find('a').data('value');
            $('.js-status').removeClass('resp-tab-active');
            $(this).addClass('resp-tab-active');
            $('.js-pagination-area').html('');
            pagination_settings.current_page = 1;
            fetch_training_sessions();
            history.pushState({}, "", "<?=base_url('learning_center/training_sessions');?>/"+(ts_status == 'pending' ? 'scheduled' : ts_status)+"");
        });

        function func_delete_training_session(source, session_sid) {
            alertify.confirm(
                    'Are you Sure?',
                    'Are you sure you want to delete this training Session?',
                    function () {
                        // $('#form_delete_training_session_' + session_sid).submit();
                        my_request = $.ajax({
                            url: '<?php echo base_url('learning_center/ajax_responder'); ?>',
                            type: 'POST',
                            data: {
                                'perform_action': 'delete_training_session',
                                'session_sid': session_sid
                            }
                        });

                        my_request.done(function (response) {
                            $('select').prop('disabled', false);
                            if (response == 'success') {
                                //
                                if(pagination_settings.current_page != 1 && pagination_settings.Records.length == 1){
                                    pagination_settings.current_page = --pagination_settings.current_page;
                                    pagination_settings.Total = --pagination_settings.Total;
                                }
                                fetch_training_sessions();
                                source.closest('tr').remove();
                                alertify.success('Session successfully deleted!');
                                // alertify.alert('Session successfully deleted!', function(){ return;});
                            } else {
                                alertify.error('Could not update session status!');
                            }
                        });
                    },
                    function () {
                        alertify.error('Cancelled!');
                    }
            );
        }

        function func_update_session_status(source) {
            var session_sid = source.attr('data-session_sid');
            var session_status = source.val();

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
                    //
                    if(pagination_settings.current_page != 1 && pagination_settings.Records.length == 1){
                        pagination_settings.current_page = --pagination_settings.current_page;
                        pagination_settings.Total = --pagination_settings.Total;

                        $('span.js-'+session_status.toLowerCase()+'-count').text(
                            +$('span.js-'+session_status.toLowerCase()+'-count').text() + 1
                        );
                    }
                    if(pagination_settings.current_page != 1 && pagination_settings.Records.length == 0){
                        pagination_settings.Total = --pagination_settings.Total;
                    }
                    fetch_training_sessions();
                    source.closest('tr').remove();
                    alertify.success('Session Status Successfully Updated!');
                } else {
                    alertify.error('Could Not Update Session Status!');
                }
            });
        }

        function reschedule_event(){
            $.post("<?=base_url('calendar/reschedule-training-session');?>", { 
                    lcid: lcid,
                    event_date: $('#js-reschedule-event-date').val(),
                    event_start_time: $('#js-reschedule-event-start-time').val(),
                    event_end_time: $('#js-reschedule-event-end-time').val()
                }, 
                function(resp) {
                    if(resp.Status === false && res.Redirect === true) window.location.reload();
                    $('#my_loader').hide();
                    $('#js-reschedule-modal').modal('hide');
                    altertify.alert(resp.Response, function(){ return; }); return false;
            });
        }

        // Fetch TS
        function fetch_training_sessions(){
            loader();
            //
            $.get("<?=base_url('learning_center/get_training_sessions');?>/"+( pagination_settings.current_page )+"/"+( ts_status )+"/1", 
                function(resp) {
                    load_ts(resp);
            });
        }

        function load_ts(resp){
            var base_url = "<?=base_url('learning_center/edit_training_session');?>";
            pagination_settings.Records = [];
            pagination_settings.target.html('');
            //
            if(pagination_settings.current_page == 1){
                $('span.js-expired-count').text(resp.Expired);    
                $('span.js-cancelled-count').text(resp.Cancelled);    
                $('span.js-confirmed-count').text(resp.Confirmed);    
                $('span.js-pending-count').text(resp.Pending);    
                $('span.js-completed-count').text(resp.Completed);    
            }
            if(resp.Status === false || resp.Records.length === 0){
                loader(false);
                $('tbody').html('<tr><td colspan="6"><span class="no-data">No Sessions</span></td></tr>');
                return;
            }

            var rows = '';
            $.each(resp.Records, function(i, v) {
                var is_expired = v.is_expired;
                var cl = moment(v.session_date_db, site2_date_format).format('YYYY-MM') == moment().format('YYYY-MM') && is_expired == 0 ? 'style="background-color: rgb(129, 180, 49, .2);font-weight: 600;" title="Training session is occuring this month."' : '';
                // var is_expired = ts_status == 'expired' ? true : false;
                rows += '<tr '+cl+' '+( is_expired == 1 ? ' title="Training session is expired."' : '' )+'>';
                rows += '   <td>'+ v.session_topic +'</td>';
                rows += '   <td class="text-center">'+ v.session_date +'</td>';
                rows += '   <td class="text-center">'+ v.session_start_time +'</td>';
                rows += '   <td class="text-center">'+ v.session_end_time +'</td>';
                <?php if (check_access_permissions_for_view($security_details, 'status_ajax_responder')) { ?>
                rows += '   <td>';
                rows += '       <div class="form-group-sm">';
                if(is_expired == 1) {
                rows += '           <select class="form-control" disabled="true" data-e="'+v.id+'">';
                rows += '               <option>'+(v.session_status == 'pending' ? 'Scheduled' : v.session_status)+'</option>';
                }else{
                rows += '           <select data-session_sid="'+v.id+'" id="session_status" name="session_status" class="form-control js-ts-status-change">';
                rows += '               <option '+( v.session_status == 'pending' ? 'selected="selected"' : '' )+' value="pending">Scheduled</option>';
                rows += '               <option '+( v.session_status == 'cancelled' ? 'selected="selected"' : '' )+' value="cancelled">Cancelled</option>';
                rows += '               <option '+( v.session_status == 'completed' ? 'selected="selected"' : '' )+' value="completed">Completed</option>';
                rows += '               <option '+( v.session_status == 'confirmed' ? 'selected="selected"' : '' )+'  value="confirmed">Confirmed</option>';
                }
                rows += '           </select>';
                rows += '       </div>';
                rows += '   </td>';
                if(is_expired == 1) {
                rows += '<td>';
                rows += '   <a href="javascript:void(0)" class="btn btn-block btn-success btn-sm disabled" title="Training session is expired and can not be edited.">Edit</a>';
                rows += '</td>';
                rows += '<td>';
                rows += '   <a href="javascript:void(0)" class="btn btn-block btn-danger btn-sm disabled" title="Training session is expired and can not be deleted.">Delete</a>';
                rows += '</td>';
                }
                else{
                <?php if (check_access_permissions_for_view($security_details, 'edit_training_session')) { ?>
                rows += '<td>';
                rows += '   <a href="'+base_url+'/'+v.id+'" class="btn btn-block btn-success btn-sm">Edit</a>';
                rows += '</td>';
                <?php } ?>
                <?php if (check_access_permissions_for_view($security_details, 'delete_training_sessions')) { ?>
                rows += '<td>';
                rows += '    <form id="form_delete_training_session_'+v.id+'" method="post" enctype="multipart/form-data" action="<?=current_url();?>">';
                rows += '        <input type="hidden" id="perform_action" name="perform_action" value="delete_training_session" />';
                rows += '        <input type="hidden" id="session_sid" name="session_sid" value="'+v.id+'" />';
                rows += '    </form>';
                rows += '    <button data-id="'+v.id+'"" class="btn btn-block btn-danger btn-sm js-ts-delete">Delete</button>';
                rows += '</td>';
                <?php } ?>
                <?php } ?>
                }
                rows += '</tr>';
            });

            // Hide loader
            loader(true);
            // Set recors count for cache
            pagination_settings.Total = pagination_settings.current_page == 1 ? resp.Total : pagination_settings.Total;
            pagination_settings.Limit = resp.Limit;
            pagination_settings.ListSize = resp.ListSize;
            pagination_settings.Records = resp.Records;
           
            $('span.js-'+ts_status.toLowerCase()+'-count').text(pagination_settings.Total);
            // Load pagination
            load_pagination( resp.Records.length );

            $('tbody').html(rows);
        }

        window.onpopstate = function(){ 
            // window.history.back(); 
            window.location.href = "<?=base_url('learning_center');?>";
        }
        
        // loader
        function loader(hide){
            if(hide === undefined) $('#my_loader').show();
            else $('#my_loader').fadeOut(200);
        }

        // Pagination code starts
        // Get previous page
        $(document).on('click', pagination_settings.prev, function(e) {
            e.preventDefault();
            pagination_settings.loader();
            pagination_settings.current_page--;
            pagination_settings.cb();
        });

        // Get next page
        $(document).on('click', pagination_settings.next, function(e) {
            e.preventDefault();
            pagination_settings.loader();
            pagination_settings.current_page++;
            pagination_settings.cb();
        });

        // Get first page
        $(document).on('click', pagination_settings.first, function(e) {
            e.preventDefault();
            pagination_settings.loader();
            pagination_settings.current_page = 1;
            pagination_settings.cb();
        });

        // Get last page
        $(document).on('click', pagination_settings.last, function(e) {
            e.preventDefault();
            pagination_settings.loader();
            pagination_settings.current_page = pagination_settings.total;
            pagination_settings.cb();
        });

        // Get selected page
        $(document).on('click', pagination_settings.shift, function(e) {
            e.preventDefault();
            pagination_settings.loader();
            pagination_settings.current_page = $(this).data('page');
            pagination_settings.cb();
        });

        // Pagination
        // TODO convert it into a plugin
        function load_pagination(record_length){
            // get paginate array
            var page_array = paginate(
                pagination_settings.Total, 
                pagination_settings.current_page, 
                pagination_settings.Limit, 
                pagination_settings.ListSize
            );
            // append the target ul
            // to top and bottom of table
            pagination_settings.target.html('<ul class="pagination js-pagination pull-left"></ul>');
            // set rows append table
            var target = $('.js-pagination');
            // get total items number
            total_records = page_array.total_pages;
            // load pagination only there
            // are more than one page
            if(pagination_settings.Total >= pagination_settings.Limit) {
                // generate li for
                // pagination
                var rows = '';
                // move to one step back
                rows += '<li><a href="javascript:void(0)" class="'+(pagination_settings.current_page == 1 ? '' : 'js-pagination-first')+'">First</a></li>';
                rows += '<li><a href="javascript:void(0)" class="'+(pagination_settings.current_page == 1 ? '' : 'js-pagination-prev')+'">&laquo;</a></li>';
                // generate 5 li
                $.each(page_array.pages, function(index, val) {
                    rows += '<li '+(val == pagination_settings.current_page ?  'class="active"' : '')+'><a href="javascript:void(0)" data-page="'+(val)+'" class="'+(pagination_settings.current_page != val ? 'js-pagination-shift' : '')+'">'+(val)+'</a></li>';
                });
                // move to one step forward
                rows += '<li><a href="javascript:void(0)" class="'+(pagination_settings.current_page == page_array.total_pages ? '' : 'js-pagination-next')+'">&raquo;</a></li>';
                rows += '<li><a href="javascript:void(0)" class="'+(pagination_settings.current_page == page_array.total_pages ? '' : 'js-pagination-last')+'">Last</a></li>';
                // append to ul
                target.html(rows);
            }
            // remove showing
            $('.js-show-record').remove();
            // append showing of records
            target.before('<span class="pull-left js-show-record" style="margin-top: 27px; padding-right: 10px;">Showing '+(page_array.start_index + 1)+' - '+(page_array.end_index + 1)+' of '+(pagination_settings.Total)+'</span>');
        }

        // Paginate logic
        function paginate(total_items, current_page, page_size, max_pages) {
            // calculate total pages
            var total_pages = Math.ceil(total_items / page_size);

            // ensure current page isn't out of range
            if (current_page < 1) current_page = 1;
            else if (current_page > total_pages) current_page = total_pages;

            var start_page, end_page;
            if (total_pages <= max_pages) {
                // total pages less than max so show all pages
                start_page = 1;
                end_page = total_pages;
            } else {
                // total pages more than max so calculate start and end pages
                var max_pagesBeforecurrent_page = Math.floor(max_pages / 2);
                var max_pagesAftercurrent_page = Math.ceil(max_pages / 2) - 1;
                if (current_page <= max_pagesBeforecurrent_page) {
                    // current page near the start
                    start_page = 1;
                    end_page = max_pages;
                } else if (current_page + max_pagesAftercurrent_page >= total_pages) {
                    // current page near the end
                    start_page = total_pages - max_pages + 1;
                    end_page = total_pages;
                } else {
                    // current page somewhere in the middle
                    start_page = current_page - max_pagesBeforecurrent_page;
                    end_page = current_page + max_pagesAftercurrent_page;
                }
            }

            // calculate start and end item indexes
            var start_index = (current_page - 1) * page_size;
            var end_index = Math.min(start_index + page_size - 1, total_items - 1);

            // create an array of pages to ng-repeat in the pager control
            var pages = Array.from(Array((end_page + 1) - start_page).keys()).map(i => start_page + i);

            // return object with all pager properties required by the view
            return {
                total_items: total_items,
                // current_page: current_page,
                // page_size: page_size,
                total_pages: total_pages,
                start_page: start_page,
                end_page: end_page,
                start_index: start_index,
                end_index: end_index,
                pages: pages
            };
        }
        // Pagination code ends
    })
</script>


<style>
    .resp-tabs-list li{ width: 25% !important; }
    /*PAgination custome css*/
    .js-pagination-area li a{ color: #81b431; }
    .js-pagination-area li a:hover,
    .js-pagination-area li.active > a,
    .js-pagination-area li.active > a:hover{ border-color: #81b431; background-color: #81b431; color: #ffffff; }
</style>