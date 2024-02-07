<?php $activeClass = 'class="active"'; ?>
<style>
    .navbar-nav li {
        display: block;
        width: 100%;
    }

    .navbar-nav li a {
        display: inline-block;
    }

    .navbar-nav li figure {
        display: inline-block;
        margin-right: 5px;
    }
</style>
<div class="sidebar-nav csAdminSideBar">
    <div class="navbar navbar-default" role="navigation">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".admin-sidebar-navbar-collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <span class="visible-xs navbar-brand">Attendance</span>
        </div>
        <div class="navbar-collapse collapse admin-sidebar-navbar-collapse">
            <ul class="nav navbar-nav">
                <li> 
                    <a href="<?php echo base_url('/dashboard'); ?>">
                        <figure><i class="fa fa-th"></i></figure>Dashboard
                    </a>
                </li>

                <li>
                    <a href="<?php echo base_url('attendance/dashboard'); ?>" <?= preg_match('/attendance\/dashboard/im', uri_string()) ? $activeClass : ''; ?>>
                        <figure><i class="fa fa-dashboard"></i></figure>Overview
                    </a>
                </li>
                <li>
                    <a href="<?php echo base_url('attendance/timesheet'); ?>" <?= preg_match('/attendance\/timesheet/im', uri_string()) ? $activeClass : ''; ?>>
                        <figure><i class="fa fa-file"></i></figure>TimeSheet
                    </a>
                </li>
                <li>
                    <a href="<?php echo base_url('attendance/employees/timesheets'); ?>" <?= preg_match('/attendance\/employees\/timesheets/im', uri_string()) ? $activeClass : ''; ?>>
                        <figure><i class="fa fa-file"></i></figure>Employees timesheets
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>