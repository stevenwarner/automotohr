<div class="main pto-main-wrp">
    <div class="container">
        <div class="row">
            <!-- Content Area -->
            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                <a href="<?=base_url('employee_management_system');?>" class="btn btn-info"> <i
                        class="fa fa-arrow-left"></i> &nbsp;Dashboard</a>
                <a href="#" class="btn btn-info jsHolidays"> <i class="fa fa-calendar-check-o"></i> &nbsp;View Holidays</a>
                    <a href="#" class="btn btn-info jsCalendarView" data-id="1"> <i class="fa fa-calendar"></i> &nbsp;View
                    Calendar</a>
                <?php if($level >= 1 || $is_approver >= 1):?>
                <span class="pull-right">
                    <button class="btn btn-black btn-success jsTeamShiftTab" data-key="my" title="Show my time-offs">My Time-offs</button>
                    <button class="btn btn-black jsTeamShiftTab" data-key="all" title="Show all time-offs">All Time-offs</button>
                    <!-- <input type="checkbox" class="jsTeamShiftTab" checked data-toggle="toggle" data-on="My time-offs" -->
                        <!-- data-off="All Time-offs" data-onstyle="info" data-offstyle="danger" /> -->
                </span>
                <?php endif;?>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="csPIPage">
                    <!-- Loader -->
                    <div class="csIPLoader jsIPLoader" data-page="graph"><i class="fa fa-circle-o-notch fa-spin"></i>
                    </div>

                    <div id="jsEmployeeBox">
                        <!-- Policies  -->
                        <?php $this->load->view('timeoff/partials/lms/policies'); ?>
                        <!-- Graph  -->
                        <?php $this->load->view('timeoff/partials/lms/graph'); ?>
                    </div>

                    <!-- Employee on off for mobile -->
                    <div id="js-employee-off-box-mobile"></div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="csPIPage">
                    <!-- Loader -->
                    <div class="csIPLoader jsIPLoader" data-page="requests"><i class="fa fa-circle-o-notch fa-spin"></i>
                    </div>
                    <!--  -->
                    <?php $this->load->view('timeoff/partials/lms/view'); ?>

                    <!-- Employee on off for mobile -->
                    <div id="js-employee-off-box-mobile"></div>
                </div>
            </div>
        </div>

    </div>
</div>