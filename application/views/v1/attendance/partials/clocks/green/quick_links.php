<?php if (isPayrollOrPlus() && checkIfAppIsEnabled(MODULE_ATTENDANCE)) { ?>
    <li>
        <a <?php if (base_url(uri_string()) == site_url('attendance')) {
                echo 'class="active_header_nav"';
            } ?> href="<?php echo base_url("attendance/dashboard"); ?>">
            <figure><i class="fa fa-calendar"></i></figure>
            Time & Attendance <span class="beta-label">beta</span>
        </a>
    </li>
<?php } ?>