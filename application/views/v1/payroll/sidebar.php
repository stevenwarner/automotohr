<?php
$sideBarUrls = [
    'admins' => [
        'payrolls/admins',
        'payrolls/admins/add',
    ],
    'signatories' => [
        'payrolls/signatories',
        'payrolls/signatories/add',
    ],
    'employees' => [
        'payrolls/employees',
    ],
    'contractors' => [
        'payrolls/contractors',
    ],
    'earnings' => [
        'payrolls/earnings/types',
    ],
    'external_payrolls' => [
        'payrolls/external',
        'payrolls/external/add',
        'payrolls/external/(:num)',
    ],
    'regular_payrolls' => [
        'payrolls/regular',
        'payrolls/regular/add',
        'payrolls/regular/(:num)',
    ],
    'payrolls_history' => [
        'payrolls/history',
        'payrolls/history/(:num)',
    ],
    'settings' => [
        'payrolls/settings',
    ],
    'benefits' => [
        'benefits',
    ],
    'paystubs' => [
        'payrolls/pay-stubs',
    ],
]
?>

<div class="sidebar-nav csAdminSideBar">
    <div class="navbar navbar-default" role="navigation">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".admin-sidebar-navbar-collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <span class="visible-xs navbar-brand">Employee payroll menu</span>
        </div>
        <div class="navbar-collapse collapse admin-sidebar-navbar-collapse">
            <ul class="nav navbar-nav">
                <li>
                    <a href="<?php echo base_url('dashboard'); ?>">
                        <figure><i class="fa fa-th"></i></figure>Dashboard
                    </a>
                </li>
                <?php if (isPayrollOrPlus()) : ?>
                    <!-- Payrolls -->
                    <li>
                        <a <?php if (uri_string() === 'payrolls/dashboard') {
                                echo 'class="active"';
                            } ?> href="<?php echo base_url('payrolls/dashboard'); ?>">
                            <figure><i class="fa fa-home"></i></figure>Payroll Dashboard
                        </a>
                    </li>
                    <!-- Manage Admins -->
                    <li>
                        <a <?php if (in_array(uri_string(), $sideBarUrls['admins'])) {
                                echo 'class="active"';
                            } ?> href="<?php echo base_url('payrolls/admins'); ?>">
                            <figure><i class="fa fa-users"></i></figure>Manage Admins
                        </a>
                    </li>
                    <!-- Manage Signatories -->
                    <li>
                        <a <?php if (in_array(uri_string(), $sideBarUrls['signatories'])) {
                                echo 'class="active"';
                            } ?> href="<?php echo base_url('payrolls/signatories'); ?>">
                            <figure><i class="fa fa-pencil-square"></i></figure>Manage Signatories
                        </a>
                    </li>
                    <!-- Manage custom earnings -->
                    <li>
                        <a <?php if (in_array(uri_string(), $sideBarUrls['earnings'])) {
                                echo 'class="active"';
                            } ?> href="<?php echo base_url('payrolls/earnings/types'); ?>">
                            <figure><i class="fa fa-list"></i></figure>Manage Earnings Types
                        </a>
                    </li>

                    <!-- Manage Employees -->
                    <li>
                        <a <?php if (in_array(uri_string(), $sideBarUrls['employees'])) {
                                echo 'class="active"';
                            } ?> href="<?php echo base_url('payrolls/employees'); ?>">
                            <figure><i class="fa fa-users"></i></figure>Manage Employees
                        </a>
                    </li>

                    <!-- Manage custom/earnings -->
                    <li>
                        <a <?php if (in_array(uri_string(), $sideBarUrls['contractors'])) {
                                echo 'class="active"';
                            } ?> href="<?php echo base_url('payrolls/contractors'); ?>">
                            <figure><i class="fa fa-user-plus"></i></figure>Manage Contractors
                        </a>
                    </li>

                    <?php if (isCompanyApprovedForPayroll()) : ?>
                        <!-- Manage historical payrolls -->
                        <li>
                            <a <?php if (in_array(uri_string(), $sideBarUrls['external_payrolls']) || preg_match('/payrolls\/external/im', uri_string())) {
                                    echo 'class="active"';
                                } ?> href="<?php echo base_url('payrolls/external'); ?>">
                                <figure><i class="fa fa-external-link-square"></i></figure>External Payroll
                            </a>
                        </li>

                        <!-- Regular -->
                        <li>
                            <a <?php if (in_array(uri_string(), $sideBarUrls['regular_payroll']) || preg_match('/payrolls\/regular/im', uri_string())) {
                                    echo 'class="active"';
                                } ?> href="<?php echo base_url('payrolls/regular'); ?>">
                                <figure><i class="fa fa-calendar"></i></figure>Regular Payroll
                            </a>
                        </li>

                        <!-- Off cycle -->
                        <!-- <li>
                            <a <?php if (in_array(uri_string(), $sideBarUrls['offcycle_payrolls'])) {
                                    echo 'class="active"';
                                } ?> href="<?php echo base_url('payrolls/offcycle'); ?>">
                                <figure><i class="fa fa-calculator"></i></figure>Off-Cycle Payroll
                            </a>
                        </li> -->

                        <!-- Payroll history -->
                        <li>
                            <a <?php if (in_array(uri_string(), $sideBarUrls['payrolls_history']) || preg_match('/payrolls\/history/im', uri_string())) {
                                    echo 'class="active"';
                                } ?> href="<?php echo base_url('payrolls/history'); ?>">
                                <figure><i class="fa fa-history"></i></figure>Payroll History
                            </a>
                        </li>


                    <?php endif; ?>

                    <!-- Manage custom/earnings -->
                    <li>
                        <a <?php if (in_array(uri_string(), $sideBarUrls['benefits'])) {
                                echo 'class="active"';
                            } ?> href="<?php echo base_url('benefits'); ?>">
                            <figure><i class="fa fa-book"></i></figure>Benefits
                        </a>
                    </li>

                    <!-- Manage custom/earnings -->
                    <li>
                        <a <?php if (in_array(uri_string(), $sideBarUrls['settings'])) {
                                echo 'class="active"';
                            } ?> href="<?php echo base_url('payrolls/settings'); ?>">
                            <figure><i class="fa fa-cogs"></i></figure>Settings
                        </a>
                    </li>
                <?php endif; ?>
                <!-- Pay stubs -->
                <li>
                    <a <?php if (in_array(uri_string(), $sideBarUrls['paystubs'])) {
                            echo 'class="active"';
                        } ?> href="<?php echo base_url('payrolls/pay-stubs'); ?>">
                        <figure><i class="fa fa-money"></i></figure>My Pay Stubs
                    </a>
                </li>
                <li>
                    <a class="bg-danger" href="<?php echo base_url('payrolls/start_fresh'); ?>">
                        <figure><i class="fa fa-times-circle"></i></figure>Start fresh
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>

<style>
    /* make sidebar nav vertical */
    .csAdminSideBar {
        border: 1px solid #ababab;
        background-color: #f3f3f3;
        border-radius: 5px;
        margin: 0 0 30px 0;
    }

    .csAdminSideBar .navbar {
        margin-bottom: 0;
    }

    .csAdminSideBar .navbar ul {
        float: none;
    }

    .csAdminSideBar .navbar .navbar-collapse {
        padding: 0;
        max-height: none;
    }

    .csAdminSideBar .navbar li {
        display: block;
        width: 100%;
        overflow: hidden;
        border-bottom: 1px solid #ababab;
    }

    .csAdminSideBar .navbar li a {
        display: block;
        padding: 15px 20px;
        font-size: 15px;
        color: #000;
        font-weight: 600;
        font-style: italic;
        text-transform: capitalize;
    }

    .csAdminSideBar ul li figure {
        display: inline-block;
        width: 35px;
        text-align: center;
        vertical-align: middle;
        margin-right: 10px;
    }

    .csAdminSideBar ul li figure i {
        font-size: 22px;
        color: #4d4d4d;
    }

    .csAdminSideBar .navbar ul:not {
        display: block;
    }

    .csAdminSideBar .navbar li a:hover {
        background-color: #81b431;
        color: #fff;
    }

    .csAdminSideBar .navbar li a:hover figure i {
        color: #fff;
    }

    .csAdminSideBar .navbar-nav a.active,
    .csAdminSideBar .navbar-nav a.active:hover,
    .csAdminSideBar .navbar-nav a.active:focus {
        background-color: #81b431;
        color: #fff;
    }

    .csAdminSideBar .navbar-nav a.active figure i,
    .csAdminSideBar .navbar-nav a.active:hover figure i,
    .csAdminSideBar a.active:focus figure i {
        color: #fff;
    }
</style>