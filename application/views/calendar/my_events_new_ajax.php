<style>
.csCalendarTypes{
    margin-top: 30px;
    list-style: none;
}
.csCalendarTypes li{
    display: inline-block;
    margin-right: 20px;
}
</style>

<?php if (!$load_view) { ?>
<?php 
    $is_regex = 0;
    $input_group_start = $input_group_end = '';
    if(isset($phone_pattern_enable) && $phone_pattern_enable == 1) {
        $is_regex = 1;
        $input_group_start = '<div class="input-group"><div class="input-group-addon"><span class="input-group-text" id="basic-addon1">+1</span></div>';
        $input_group_end   = '</div>';
    }
?>
<div class="main-content">
    <div class="dashboard-wrp">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="dashboard-conetnt-wrp">
                        <div class="page-header-area">
                            <div class="page-heading down-arrow js-fix-btn">
                            <?php $this->load->view('manage_employer/company_logo_name'); ?>
                                <a href="<?=base_url('dashboard');?>" class="dashboard-link-btn"><i class="fa fa-long-arrow-left"></i>&nbsp;Dashboard</a>
                                <p style="margin-top: 5px;">Calendar / Events</p>
                            </div>
                        </div><?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                        <div class="job-feature-main m_job">
                            <div class="portalmid">
                                <div id='calendar'></div>
                                <div id="my_loader" class="text-center my_loader">
                                    <div id="file_loader" class="file_loader" style="display:block; height:1353px;"></div>
                                    <div class="loader-icon-box">
                                        <i class="fa fa-refresh fa-spin my_spinner" style="visibility: visible;"></i>
                                        <div class="loader-text" style="display:block; margin-top: 35px;">Please wait while we generate a preview...
                                        </div>
                                    </div>
                                </div>
                                <?php $this->load->view('calendar/popup_modal_partial_ajax'); ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-sm-12">
                    <!--  -->
                    <div class="ma10">
                        <ul class="text-center csCalendarTypes">
                            <?php if(checkIfAppIsEnabled('performance_management')) {?>
                                <li>
                                    <label class="control control--checkbox">
                                        <input type="checkbox" name="goals" class="jsCalendarTypes" checked="true" /> Goals
                                        <div class="control__indicator"></div>
                                    </label>
                                </li>
                            <?php } ?>
                            <?php if(checkIfAppIsEnabled('timeoff')) {?>
                                <li>
                                    <label class="control control--checkbox">
                                        <input type="checkbox" name="timeoff" class="jsCalendarTypes" checked="true" /> Time Off
                                        <div class="control__indicator"></div>
                                    </label>
                                </li>
                            <?php } ?>

                            <?php if (checkIfAppIsEnabled(SCHEDULE_MODULE)){ ?>
                                <li>
                                    <label class="control control--checkbox">
                                        <input type="checkbox" name="shifts" class="jsCalendarTypes" checked="true" /> Shifts
                                        <div class="control__indicator"></div>
                                    </label>
                                </li>
                            <?php } ?>
                        </ul>
                    </div>
                </div>
                <div class="col-sm-12">
                    <br />
                    <br />

                    <div class="alert alert-info">
                        <strong>Note:</strong> Below tables represent the meaning of the colors and event status on the calendar.
                    </div>

                    <div class="row">
                        <div class="col-sm-6">
                            <!--  -->
                            <div class="responsive-table">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Event Color</th>
                                            <th>Event Type</th>
                                        </tr>
                                    </thead>

                                    <tbody id="js-event-colors"></tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <!--  -->
                            <div class="responsive-table">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th style="width: 30%;">Event Status Color</th>
                                            <th>Event Status</th>
                                        </tr>
                                    </thead>

                                    <tbody id="js-event-status"></tbody>
                                </table>
                            </div>
                            <!-- Tab hint -->
                            <div class="row">
                                <div class="col-sm-4 col-xs-8 col-sm-offset-1 col-xs-offset-2">
                                    <div class="cs-pill-wrap">
                                        <a 
                                        class="fc-popover"
                                            href="javascript:void(0)" 
                                            data-placement="top" 
                                            data-content="Event Status"
                                            style="
                                            border-left: 20px solid #5cb85c; 
                                            position: absolute;
                                            top: 60px;
                                            left: 0;
                                            z-index: 1;
                                            height: 25px;
                                            margin-right: 0;
                                            border-radius: 3px 0 0 3px;
                                        "></a>
                                        <a 
                                        class="fc-day-grid-event fc-h-event fc-event fc-start fc-end fc-event-cc fc-event-cc-confirmed fc-popover" 
                                        style="background-color:#0000ff;border-color:#0000ff; border-width: 0"  
                                        data-placement="bottom" 
                                        data-content="Event Type"
                                        >
                                            <div class="fc-content">
                                                <span class="fc-time">12:</span> 
                                                <span class="fc-title">John Doe, In-Person Interview, Interviewer(s):...</span>
                                            </div>
                                        </a>
                                    </div>
                                </div>

                                <div class="col-sm-4 col-xs-8 col-sm-offset-1 col-xs-offset-2">
                                    <div class="cs-pill-wrap js-event-tab-blinker">
                                        <a 
                                            href="javascript:void(0)" 
                                            style="
                                            border-left: 20px solid #f0ad4e; 
                                            position: absolute;
                                            top: 60px;
                                            left: 0;
                                            z-index: 1;
                                            height: 25px;
                                            margin-right: 0;
                                            border-radius: 3px 0 0 3px;
                                        "></a>
                                        <a 
                                        class="fc-day-grid-event fc-h-event fc-event fc-start fc-end fc-event-cc fc-event-cc-confirmed fc-popover" 
                                        style="background-color:#b910ff;border-color:#b910ff; border-width: 0"  
                                        data-placement="bottom" 
                                        data-content="New Reschedule/Cancellation requests"
                                        >
                                            <div class="fc-content">
                                                <span class="fc-time">11:</span> 
                                                <span class="fc-title">John Doe, Email Interview, Interviewer(s):...</span>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* button in page heading*/
    .js-fix-btn > a{ position: absolute; top: 13px; left: 10px; }
    #js-event-colors td{ text-transform: capitalize; }
    .cs-pill-wrap{ margin-top: 60px; }
    .cs-pill-wrap .popover-content{ text-align: center; }
</style>


<script>
    $(function(){
        $('.fc-popover').popover('show');
        $('.fc-popover').on('hide.bs.popover', function(){ return false; });

        setInterval(function(){
            $('.js-event-tab-blinker a').css('opacity', $('.js-event-tab-blinker a').css('opacity') != 1 ? 1 : .5);
        }, 600);
    })
</script>

<?php $this->load->view('calendar/scripts_partial_ajax', $employees); ?>
<?php } else { ?>
    <?php $this->load->view('onboarding/calendar_ajax'); ?>
<?php } ?>