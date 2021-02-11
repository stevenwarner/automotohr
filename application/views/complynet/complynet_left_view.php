<div class="dashboard-menu" style="min-height: auto;">
    <ul>
        <li>
            <a <?php if(strpos(base_url(uri_string()), site_url('dashboard')) !== false) { echo 'class="active"'; } ?>
                href="<?php echo base_url('dashboard'); ?>">
                <figure><i class="fa fa-th"></i></figure>Dashboard
            </a>
        </li>
        <li>
            <a <?php if (strpos(base_url(uri_string()), site_url('complynet')) !== false) { echo 'class="active"'; } ?>
                href="<?php echo base_url("complynet"); ?>">
                <figure><i class="fa fa-book"></i></figure>ComplyNet
            </a>
        </li>
    </ul>
</div>
