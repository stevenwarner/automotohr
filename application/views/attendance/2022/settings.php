<div class="csPageWrap" style="background-color: #f1f1f1;">
    <!-- Nav bar -->
    <div class="container-fluid">
        <?php $this->load->view('attendance/2022/navbar'); ?>
    </div>
    <br>
    <!--  -->
    <div class="row">
        <div class="container-fluid">
            <!-- Side Bar -->
            <?php $this->load->view('employee_info_sidebar_ems'); ?>
            <!-- Main Content Area -->
            <div class="col-md-9">
                <!-- Heading -->
                <div class="row">
                    <div class="col-xs-12 col-md-12">
                        <h1 class="m0 p0 csB7">
                            Settings
                        </h1>
                    </div>
                </div>
                <!--  -->
                <p class="csF14 csB4 pa10">Last modified on <strong><?= formatDateToDB($settings['updated_at'], DB_DATE_WITH_TIME, DATE_WITH_TIME) ?></strong> by <strong><?= $employees[$settings['employer_sid']]['name'] . $employees[$settings['employer_sid']]['role']; ?></strong></p>
                <!--  -->
                <div class="csPageBox csRadius5">
                    <?php $this->load->view('loader_new', ['id' => 'jsAttendanceSettingsLoader']); ?>
                    <div class="csPageBody">
                        <div class="row">
                            <div class="col-sm-12 col-md-12">
                                <h2 class="csF20 csB7 pl10 pr10">Visibility</h2>
                                <p class="csF14 cdB4 pl10 pr10">The selected roles, departments (Supervisors), teams (Team Leads), and employees can manage attendance.</p>
                            </div>
                        </div>
                        <div class="p10">

                            <?php $this->load->view('visibility_section'); ?>
                        </div>
                    </div>
                </div>
                <!-- Buttons -->
                <div class="row">
                    <div class="col-sm-12 text-right p10">
                        <button class="btn btn-orange jsAttendanceSaveSettings"><i class="fa fa-save" aria-hidden="true"></i>&nbsp;Update Settings</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<input type="hidden" id="jsAttendanceLastSlotTime" value="<?php echo $last_time; ?>">