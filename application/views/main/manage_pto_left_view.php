<?php
    $pto_user_access = get_pto_user_access($session['employer_detail']['parent_sid'],$session['employer_detail']['sid']);
?>

<div class="dashboard-menu">
    <ul>
        <li>
            <a
                href="<?php echo base_url('dashboard'); ?>">
                <figure><i class="fa fa-th"></i></figure>Dashboard
            </a>
        </li>

        <?php if ($pto_user_access['create_time_off'] == 1) { ?>
            <li>
                <a <?=in_array('create-time-off', $this->uri->segment_array()) ? 'class="active"' : ''; ?>
                    href="<?=base_url("timeoff/create-time-off");?>">
                    <figure><i class="fa fa-plus-square"></i></figure>Create Time Off
                </a>
            </li>
        <?php } ?> 

        <?php if ($pto_user_access['time_off_request'] == 1) { ?>   
            <li>
                <a <?=in_array('requests', $this->uri->segment_array()) ? 'class="active"' : ''; ?>
                    href="<?=base_url("timeoff/requests");?>">
                    <figure><i class="fa fa-tasks"></i></figure>Time Off Requests
                </a>
            </li>
        <?php } ?>

        <?php if ($pto_user_access['time_off_balance'] == 1) { ?>
            <li>
                <a <?=in_array('balance', $this->uri->segment_array()) ? 'class="active"' : ''; ?>
                    href="<?=base_url("timeoff/balance");?>">
                    <figure><i class="fa fa-bar-chart"></i></figure>Time off Balances
                </a>
            </li>
        <?php } ?>

        <?php if ($pto_user_access['time_off_report'] == 1) { ?>
            <li>
                <a <?=in_array('request-report', $this->uri->segment_array()) ? 'class="active"' : ''; ?>
                    href="<?=base_url("timeoff/request-report");?>">
                    <figure><i class="fa fa-pie-chart"></i></figure>Time Off Report
                </a>
            </li>
        <?php } ?>

        <?php if ($pto_user_access['import_time_off'] == 1) { ?>
            <li>
                <a <?=in_array('import', $this->uri->segment_array()) ? 'class="active"' : ''; ?>
                    href="<?=base_url("timeoff/import");?>">
                    <figure><i class="fa fa-upload"></i></figure>Import Time Off
                </a>
            </li>
        <?php } ?>

        <?php if ($pto_user_access['export_time_off'] == 1) { ?>
            <li>
                <a <?=in_array('export_time_off', $this->uri->segment_array()) ? 'class="active"' : ''; ?>
                    href="<?=base_url("export_time_off");?>">
                    <figure><i class="fa fa-download"></i></figure>Export Time Off
                </a>
            </li>
        <?php } ?>

        <?php if ($pto_user_access['time_off_setting'] == 1) { ?>
            <li>
                <a <?=in_array('settings', $this->uri->segment_array()) ? 'class="active"' : ''; ?>
                    href="<?=base_url("timeoff/settings");?>">
                    <figure><i class="fa fa-cogs"></i></figure> Time Off Settings
                </a>
            </li>
        <?php } ?>

        <?php if ($pto_user_access['time_off_approver'] == 1) { ?>
            <li>
                <a <?=in_array('approvers', $this->uri->segment_array()) ? 'class="active"' : ''; ?>
                    href="<?=base_url("timeoff/approvers/view");?>">
                    <figure><i class="fa fa-sitemap"></i></figure>Time Off Approvers
                </a>
            </li>
        <?php } ?>

        <!-- <li>
            <a <?=in_array('plans', $this->uri->segment_array()) ? 'class="active"' : ''; ?>
                href="<?=base_url("timeoff/plans/view");?>">
                <figure><i class="fa fa-file-text-o"></i></figure>Time Off Plans
            </a>
        </li> -->

        <?php if ($pto_user_access['company_holiday'] == 1) { ?>
            <li>
                <a <?=in_array('holidays', $this->uri->segment_array()) ? 'class="active"' : ''; ?>
                    href="<?=base_url("timeoff/holidays/view");?>">
                    <figure><i class="fa fa-calendar"></i></figure>Company Holidays
                </a>
            </li>
        <?php } ?>

        <?php if ($pto_user_access['time_off_type'] == 1) { ?>
            <li>
                <a <?=in_array('types', $this->uri->segment_array()) ? 'class="active"' : ''; ?>
                    href="<?=base_url("timeoff/types/view");?>">
                    <figure><i class="fa fa-clock-o"></i></figure>Time Off Types
                </a>
            </li>
        <?php } ?>

        <?php if ($pto_user_access['time_off_policies'] == 1) { ?>
            <li>
                <a <?=in_array('policies', $this->uri->segment_array()) ? 'class="active"' : ''; ?>
                    href="<?=base_url("timeoff/policies/view");?>">
                    <figure><i class="fa fa-files-o"></i></figure>Time Off Policies
                </a>
            </li>
        <?php } ?>

        <?php if ($pto_user_access['time_off_policy_overwrite'] == 1) { ?>
            <li>
                <a <?=in_array('policy-overwrite', $this->uri->segment_array()) ? 'class="active"' : ''; ?>
                    href="<?=base_url("timeoff/policy-overwrite/view");?>">
                    <figure><i class="fa fa-file-text"></i></figure>Time Off Policy Overwrites
                </a>
            </li>
        <?php } ?>
    </ul>
</div>
