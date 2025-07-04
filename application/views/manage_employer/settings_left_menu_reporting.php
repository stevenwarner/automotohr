<div class="dashboard-menu">
    <ul>
        <li>
            <a <?php if (base_url(uri_string()) == site_url('dashboard')) { echo 'class="active"'; } ?> href="<?php echo base_url('dashboard'); ?>">
            <figure><i class="fa fa-th"></i></figure>Dashboard</a>
        </li>
        <li>
            <a <?php if (base_url(uri_string()) == site_url('my_settings')) { echo 'class="active"'; } ?> href="<?php echo base_url('my_settings') ?>">
            <figure><i class="fa fa-sliders"></i></figure>Settings</a>
        </li>
        <?php   if(check_access_permissions_for_view($security_details, 'eeo')) { ?>
                    <li>
                        <a <?php if (base_url(uri_string()) == site_url('eeo') || in_array('eeo', $this->uri->segment_array())) { echo 'class="active"'; } ?> href="<?php echo base_url('eeo'); ?>">
                        <figure><i class="fa fa-th-list"></i></figure>EEO Report</a>
                    </li>
        <?php   } ?>
        <?php   if(check_access_permissions_for_view($security_details, 'accurate_background')) { ?>
                    <li>
                        <a <?php if (in_array('accurate_background', $this->uri->segment_array())) { echo 'class="active"'; } ?> href="<?php echo base_url('accurate_background'); ?>">
                        <figure><i class="fa fa-th-list"></i></figure>Accurate Background Report</a>
                    </li>
        <?php   } ?>
        <?php   if(check_access_permissions_for_view($security_details, 'reports')) { ?>
                    <li>
                        <a <?php if ($this->uri->segment(1) == 'reports') { echo 'class="active"'; } ?> href="<?php echo base_url('reports'); ?>">
                        <figure><i class="fa fa-th-list"></i></figure>Advanced Reports</a>
                    </li>
        <?php   } ?>
    </ul>
</div>