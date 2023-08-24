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
        'payroll/employees',
    ],
    'contractors' => [
        'payrolls/contractors',
    ],
    'earnings' => [
        'payrolls/earnings/types',
    ],
]
?>

<div class="dashboard-menu">
    <ul>
        <li>
            <a href="<?php echo base_url('dashboard'); ?>">
                <figure><i class="fa fa-th"></i></figure>Dashboard
            </a>
        </li>
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
                <figure><i class="fa fa-users"></i></figure>Manage Contractors
            </a>
        </li>

        <!-- Regular -->
        <li>
            <a <?php if (uri_string() === 'payrolls/regular') {
                    echo 'class="active"';
                } ?> href="<?php echo base_url('payrolls/dashboard'); ?>">
                <figure><i class="fa fa-calendar"></i></figure>Regular Payroll
            </a>
        </li>

        <!-- Off cycle -->
        <li>
            <a <?php if (uri_string() === 'payrolls/off_cycle') {
                    echo 'class="active"';
                } ?> href="<?php echo base_url('payrolls/dashboard'); ?>">
                <figure><i class="fa fa-calculator"></i></figure>Off-Cycle Payroll
            </a>
        </li>

        <!-- Pay stubs -->
        <li>
            <a <?php if (uri_string() === 'payrolls/pay_stubs') {
                    echo 'class="active"';
                } ?> href="<?php echo base_url('payrolls/dashboard'); ?>">
                <figure><i class="fa fa-money"></i></figure>My Pay Stubs
            </a>
        </li>
    </ul>
</div>