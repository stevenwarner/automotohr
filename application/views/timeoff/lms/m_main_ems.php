<!-- Main area -->
<div class="csContent">
    <div class="col-sm-9">
        <div class="panel panel-success">
            <div class="panel-heading">
                <ul class="csMobileWrap">
                    <li><a href="<?=base_url('employee_management_system');?>" class="btn btn-orange" title="Go to dasboard" placement="top"><i
                                class="fa fa-dashboard"></i> Dashboard</a></li>
                    <li><a href="" class="btn btn-orange jsHolidays" title="Show company holidays" placement="top"><i
                                class="fa fa-calendar-o"></i> Holidays</a></li>
                    <li><a href="" class="btn btn-orange jsCalendarView" title="Show calendar" placement="top"><i
                                class="fa fa-calendar"></i> Calendar</a></li>
                                <?php if($level == 1 || $is_approver) { ?>
                    <li><a href="" class="btn btn-orange jsCreateRequest" id="jsCreateTimeOffForEmployee" title="Create a time off" placement="top"><i
                                class="fa fa-plus-circle"></i> Create a time off</a></li>
                                <?php } ?>
                    <li class="toRight" style="margin-right: 5px;"><a href="" class="csTabAnchor jsTeamShiftTab"
                            title="Show time offs for my team members" placement="top" data-key="0">All Time-off</a></li>
                    <li class="toRight"><a href="" class="csTabAnchor jsTeamShiftTab active" title="Show my time offs"
                            placement="top"  data-key="1">My Time-off</a></li>
                </ul>
                <div class="clearfix"></div>
            </div>
            <div class="panel-body">
                <!-- Policy Box -->
                <div class="csPolicyBox"></div>

                <!-- Graph Box -->
                <?php $this->load->view('timeoff/partials/lms/graph'); ?>

                <!-- Requests Box -->
                <?php $this->load->view('timeoff/partials/lms/'.($this->agent->is_mobile() ?  'm_' : '').'view_new'); ?>
            </div>
        </div>
    </div>
</div>