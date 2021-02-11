<div class="main pto-main-wrp">
    <div class="container">
        <div class="row">
            <div class="col-xs-12">
                <a href="<?=base_url('employee_management_system');?>" class="btn btn-info jsMobileBTN"> <i
                        class="fa fa-arrow-left"></i> &nbsp;Dashboard</a>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12">
                <a href="#" class="btn btn-info jsMobileBTN jsHolidays"> <i class="fa fa-calendar-check-o"></i>
                    &nbsp;View Holidays</a>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12">
                <a href="#" class="btn btn-info jsMobileBTN jsCalendarView" data-id="1"> <i class="fa fa-calendar"></i>
                    &nbsp;View
                    Calendar</a>
            </div>
        </div>
        <!-- Content Area -->
        <?php if($level >= 1 || $is_approver >= 1):?>
        <hr />
        <div class="row">
            <div class="col-xs-6">
                <button class="btn btn-black jsMobileBTN btn-success jsTeamShiftTab" data-key="my"
                    title="Show my time-offs">My Time-offs</button>
            </div>
            <div class="col-xs-6">
                <button class="btn btn-black jsMobileBTN jsTeamShiftTab" data-key="all" title="Show all time-offs">All
                    Time-offs</button>
            </div>
        </div>
        <?php endif;?>
    </div>

    <hr />
    <div class="row">
        <div class="col-sm-12">
            <div class="csPIPage">
                <!-- Loader -->
                <div class="csIPLoader jsIPLoader" data-page="graph"><i class="fa fa-circle-o-notch fa-spin"></i>
                </div>

                <div id="jsEmployeeBox">
                    <!-- Policies  -->
                    <?php $this->load->view('timeoff/partials/lms/m_policies'); ?>
                    <!-- Graph  -->
                    <?php $this->load->view('timeoff/partials/lms/m_graph'); ?>
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
                <?php $this->load->view('timeoff/partials/lms/m_view'); ?>

                <!-- Employee on off for mobile -->
                <div id="js-employee-off-box-mobile"></div>
            </div>
        </div>
    </div>

</div>
</div>