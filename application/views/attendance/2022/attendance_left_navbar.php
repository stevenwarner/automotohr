<div class="dashboard-menu">
    <ul>
        <li>
            <a <?php if (base_url(uri_string()) == site_url('dashboard')) {
                    echo 'class="active"';
                } ?> href="<?php echo base_url('dashboard'); ?>">
                <figure><i class="fa fa-th"></i></figure>Dashboard
            </a>
        </li>
        <li>
            <a <?php if (base_url(uri_string()) == site_url('newAttendanceSingle')) {
                    echo 'class="active"';
                } ?> href="<?php echo base_url('newAttendanceSingle'); ?>">
                <figure><i class="fa fa-calendar"></i></figure>Time Sheet
            </a>
        </li>
        <li>
            <a <?php if (base_url(uri_string()) == site_url('newAttendancePeople')) {
                    echo 'class="active"';
                } ?> href="<?php echo base_url('newAttendancePeople'); ?>">
                <figure><i class="fa fa-users"></i></figure>People
            </a>
        </li>
        <li>
            <a <?php if (base_url(uri_string()) == site_url('attendance/report')) {
                    echo 'class="active"';
                } ?> href="<?php echo base_url('attendance/report'); ?>">
                <figure><i class="fa fa-bar-chart"></i></figure>Report
            </a>
        </li>
        <li>
            <a <?php if (base_url(uri_string()) == site_url('attendance/settings')) {
                    echo 'class="active"';
                } ?> href="<?php echo base_url('attendance/settings'); ?>">
                <figure><i class="fa fa-cog"></i></figure>Settings
            </a>
        </li>
    </ul>
</div>