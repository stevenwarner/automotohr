<?php

//
$timeOffAccess = get_pto_user_access($session['employer_detail']['parent_sid'], $session['employer_detail']['sid']);
//
$tabs = [
    [
        'title' => 'Create a time-off',
        'url' => 'javascript:void(0)',
        'slug' => 'create_time_off',
        'icon' => 'plus-square',
        'segment' => 'create-time-off',
        'class' => 'jsCreateRequest',
        'props' => 'id="jsCreateTimeOffForEmployee"'
    ],
    [
        'title' => 'Requests',
        'url' => 'requests',
        'slug' => 'time_off_request',
        'icon' => 'tasks',
        'segment' => 'requests'
    ],
    [
        'title' => 'Balances',
        'url' => 'balance',
        'slug' => 'time_off_balance',
        'icon' => 'bar-chart',
        'segment' => 'balance'
    ],
    [
        'title' => 'Approvers',
        'url' => 'approvers',
        'slug' => 'time_off_approver',
        'icon' => 'sitemap',
        'segment' => 'approvers'
    ],
    [
        'title' => 'Policies',
        'url' => 'policies',
        'slug' => 'time_off_policies',
        'icon' => 'file',
        'segment' => 'policies'
    ],
    [
        'title' => 'Types',
        'url' => 'types/view',
        'slug' => 'time_off_type',
        'icon' => 'clock-o',
        'segment' => 'types'
    ],
    [
        'title' => 'Holidays',
        'url' => 'holidays',
        'slug' => 'company_holiday',
        'icon' => 'calendar',
        'segment' => 'holidays'
    ],
    [
        'title' => 'Settings',
        'url' => 'settings',
        'slug' => 'time_off_setting',
        'icon' => 'cogs',
        'segment' => 'settings'
    ],
    [
        'title' => 'More',
        'url' => 'javascript:void(0)',
        'submenu' => [
            [
                'title' => 'Import Historical',
                'url' => 'import/historic',
                'slug' => 'import_historical',
                'icon' => 'upload',
                'segment' => 'import',
            ],
            [
                'title' => 'Import',
                'url' => 'import',
                'slug' => 'import_time_off',
                'icon' => 'upload',
                'segment' => 'import',
            ],
            [
                'title' => 'Export',
                'url' => 'export',
                'slug' => 'export_time_off',
                'icon' => 'download',
                'segment' => 'export',
            ],
            [
                'title' => 'Download Report',
                'url' => 'report',
                'slug' => 'report',
                'icon' => 'area-chart',
                'segment' => 'report',
            ],
            [
                'title' => 'Employee Profile',
                'url' => 'javascript:void(0)',
                'slug' => 'employee_profile',
                'icon' => 'users',
                'segment' => 'employee_profile',
                'class' => 'jsEmployeeQuickProfile'
            ]
        ]
    ]
];
//
$lis = '';
//
$baseURL = rtrim(base_url('timeoff'), '/') . '/';
//
foreach ($tabs as $tab) {
    //
    if (!isset($tab['submenu']) && $timeOffAccess[$tab['slug']] != 1) {
        continue;
    }
    //
    if (isset($tab['submenu'])) {
        //
        $tmp = '';
        foreach ($tab['submenu'] as $item) {
            if ($timeOffAccess[$item['slug']] != 1) {
                continue;
            }
            $tmp .= '<li><a href="' . ($item["url"] == "javascript:void(0)" ? $item['url'] : $baseURL . $item['url']) . '" ' . (isset($item['class']) ? 'class="' . ($item['class']) . '"' : '') . '><i class="fa fa-' . ($item['icon']) . '"></i> ' . ($item['title']) . '</a></li>';
        }
        //
        if ($tmp == '') {
            continue;
        }
        //
        $lis .= '<li class="has">';
        $lis .= '   <a href="javascript:void(0)">More &nbsp;&nbsp;<i class="fa fa-caret-down"></i></a>';
        $lis .= '   <ul>';
        $lis .=       $tmp;
        $lis .= '   </ul>';
        $lis .= '</li>';
    } else {
        //
        $lis .= '<li><a ' . (isset($tab['props']) ? $tab['props'] : "") . ' class="' . (isset($tab['class']) ? $tab['class'] : '') . ' ' . (in_array($tab['segment'], $this->uri->segment_array()) ?  'active' : '') . '" href="' . ($tab['url'] == 'javascript:void(0)' ? $tab['url'] : $baseURL . $tab['url']) . '" title="' . ($tab['title']) . '"><i class="fa fa-' . ($tab['icon']) . '"></i> ' . ($tab['title']) . '</a></li>';
    }
}
?>

<div class="clearfix"></div>
<nav class="csNavBar">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <!-- Web -->
                <ul class="csWeb">
                    <li class="pull-left">
                        <a href="<?= base_url('dashboard'); ?>" class="csBackButton" title="Go to Dashboard"><i class="fa fa-th"></i>Go To Dashboard</a>
                    </li>
                    <li><a href="javascript:void(0)">|</a></li>
                    <?= $lis; ?>

                    <?php if (checkIfAppIsEnabled('attendance') && isPayrollOrPlus()) { ?>

                        <li><a href="<?= base_url('attendance/dashboard'); ?>"> Attendance Management</a></li>
                    <?php } ?>
                    <?php

                    $isCompanyOnPayroll = isCompanyLinkedWithGusto($session['company_detail']['sid']);
                    $isTermsAgreed = hasAcceptedPayrollTerms($session['company_detail']['sid']);
                    if ($isCompanyOnPayroll && $isTermsAgreed && isPayrollOrPlus()) { ?>

                        <li><a href="<?= base_url("payrolls/dashboard"); ?>"> Payroll Dashboard</a></li>
                    <?php } ?>

                </ul>
                <!-- Mobile -->
                <div class="csMobile">
                    <a href="<?= base_url('dashboard'); ?>" class="csBack"><i class="fa fa-th"></i>Go To Dashboard</a>
                    <span class="pull-right"><i class="fa fa-bars"></i></span>
                    <ul class="csVertical"><?= $lis; ?></ul>
                </div>
            </div>
        </div>
    </div>
</nav>



<br>
<div class="row">
    <div class="col-sm-12 text-center">
        <img src="<?php echo 'https://automotohrattachments.s3.amazonaws.com/' . $session['company_detail']['Logo']; ?>" style="width: 75px; height: 75px;" class="img-rounded"><br>
        <?php echo $session['company_detail']['CompanyName']; ?>
    </div>
</div>

<!-- Whois is on off -->
<?php if ($this->agent->is_mobile()) : ?>
    <br>
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <h3><strong>Who is off today?</strong></h3>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="csTopNavOutMobile"></div>
            </div>
        </div>
    </div>
<?php else : ?>
    <div class="csTopNavOut"></div>
<?php endif; ?>