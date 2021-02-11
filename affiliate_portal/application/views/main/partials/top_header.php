<header class="main-header header-background-color">
    <a href="<?= base_url() ?>dashboard" class="logo header-background-color">
        <span class="logo-mini"><strong>AD</strong></span>
        <span class="logo-lg"><b>Affiliate</b> Dashboard</span>
    </a>
    <nav class="navbar navbar-static-top header-background-color" role="navigation">
        <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
            <span class="sr-only">Toggle navigation</span>
        </a>
        <div class="affiliate-logo">
            <a href="<?php echo base_url();?>">
                <img src="<?php echo base_url('assets/images/Logo-7.png');?>">
            </a>
        </div>
        <div class="quick-menu pull-right">
            <div class="dropdown">
                <a class="guest-links" href="javascript:;" data-toggle="dropdown"><?php echo $name; ?><i class="fa fa-caret-down"></i></a>
                <ul class="dropdown-menu" role="menu" aria-labelledby="menu1">
                    <li><a href="javascript:;">View Website</a></li>
                    <li><a href="<?= base_url() ?>dashboard">Dashboard</a></li>
                    <li><a href="<?php echo base_url('logout');?>">Logout</a></li>
                </ul>
            </div>
        </div>
    </nav>
</header>