<?php $activeClass = 'class="active"'; ?>
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
                    <a href="<?php echo base_url('attendance/settings'); ?>" <?= preg_match('/attendance\/settings/im', uri_string()) ? $activeClass : ''; ?>>
                        <figure><i class="fa fa-cogs"></i></figure>Settings
                    </a>
                </li>
                <li>
                    <a href="<?php echo base_url('attendance/report'); ?>" <?= preg_match('/attendance\/report/im', uri_string()) ? $activeClass : ''; ?>>
                        <figure><i class="fa fa-files-o"></i></figure>Report
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>