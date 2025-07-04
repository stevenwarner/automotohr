<?php
//
$companySid = $session["company_detail"]["sid"];
$employerEmail = $session['employer_detail']['email'];
$isGustoAdmin = isGustoAdmin($employerEmail, $companySid);
//
$navPills = [];
//
$termsAcceptedByCompany = isCompanyTermsAccpeted();

if ($isGustoAdmin > 0) {

    if ($termsAcceptedByCompany) {
        $navPills[] = [
            'title' => 'Company',
            'url' => '/company',
            'slug' => '',
            'icon' => 'building',
            'segment' => 'company'
        ];

        // Dashboard
        $navPills[] = [
            'title' => 'Employees',
            'url' => '/employees/payroll',
            'slug' => '',
            'icon' => 'users',
            'segment' => 'employees'
        ];
        $navPills[] = [
            'title' => 'Run payroll',
            'url' => '/run',
            'slug' => '',
            'icon' => 'bank',
            'segment' => 'run'
        ];
        $navPills[] = [
            'title' => 'Manage Admins',
            'url' => 'javascript:void(0)',
            'slug' => '',
            'icon' => 'users',
            'segment' => 'manage',
            'class' => 'jsManageGustoAdmins',
            'data-cid' => $companySid
        ];
        $navPills[] = [
            'title' => 'Manage Signatories',
            'url' => 'javascript:void(0)',
            'slug' => '',
            'icon' => '',
            'segment' => 'manage',
            'class' => 'jsManageGustoSignatories',
            'data-cid' => $companySid
        ];
    }


    $navPills[] = [
        'title' => 'Service Terms',
        'url' => '/service-terms',
        'slug' => '',
        'icon' => 'file-pdf-o',
        'segment' => 'service'
    ];
}

$navPills[] = [
    'title' => 'My Pay Stubs',
    'url' => '/my',
    'slug' => '',
    'icon' => 'pie-chart',
    'segment' => 'payroll/my'
];

//
if (isEmployeeOnPayroll($this->session->userdata('logged_in')['employer_detail']['sid'])) {
    $navPills[] = [
        'title' => 'My Payroll Documents',
        'url' => 'my_payroll_documents',
        'slug' => '',
        'icon' => 'file',
        'segment' => 'payroll/my_payroll_documents'
    ];
}

//
$lis = '';
//
$baseURL = base_url('payroll/');
//
foreach ($navPills as $tab) {
    //
    if (isset($tab['submenu'])) {
        $tmp = '';
        foreach ($tab['submenu'] as $item) {
            $tmp .= '<li><a href="' . ($item["url"] == "javascript:void(0)" ? $item['url'] : $baseURL . $item['url']) . '" ' . (isset($item['class']) ? 'class="' . ($item['class']) . '"' : '') . '><i class="fa fa-' . ($item['icon']) . '"></i> ' . ($item['title']) . '</a></li>';
        }
        //
        $lis .= '<li class="has">';
        $lis .= '   <a href="javascript:void(0)"><i class="fa fa-' . ($tab['icon']) . '"></i>&nbsp;&nbsp;' . ($tab['title']) . ' &nbsp;&nbsp;<i class="fa fa-caret-down"></i></a>';
        $lis .= '   <ul>';
        $lis .=       $tmp;
        $lis .= '   </ul>';
        $lis .= '</li>';
    } else {

        //
        $lis .= '<li><a ' . (isset($tab['props']) ? $tab['props'] : "") . ' class="csF16 ' . (isset($tab['class']) ? $tab['class'] : '') . ' ' . ($tab['segment'] == '' || strpos($this->uri->uri_string(), $tab['segment']) !== FALSE  ?  'active' : '') . '" href="' . ($tab['url'] == 'javascript:void(0)' ? $tab['url'] : $baseURL . $tab['url']) . '"  data-cid="' . ($tab['data-cid']  ? $tab['data-cid'] : '') . '"  data-company_sid="' . ($tab['data-cid']  ? $tab['data-cid'] : '') . '" ><i class="fa fa-' . ($tab['icon']) . '"></i> ' . ($tab['title']) . '</a></li>';
    }
}
?>
<div class="clearfix"></div>
<div class="csPageNav">
    <nav class="csNavBar ">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <!-- Web -->
                    <ul class="csWeb hidden-xs">
                        <li>
                            <a href="<?= base_url('dashboard'); ?>" class="csBackButton csRadius100 csF16"><i class="fa fa-th" aria-hidden="true"></i>Go To Dashboard</a>
                        </li>
                        <li><a href="javascript:void(0)" class="csF14">|</a></li>
                        <?= $lis; ?>
                    </ul>
                    <!-- Mobile -->
                    <div class="csMobile hidden-sm">
                        <a href="<?= base_url('dashboard'); ?>" class="csBack"><i class="fa fa-th" aria-hidden="true"></i>Go To
                            Dashboard</a>
                        <span class="pull-right"><i class="fa fa-bars" aria-hidden="true"></i></span>
                        <ul class="csVertical"><?= $lis; ?></ul>
                    </div>
                </div>
            </div>
        </div>
    </nav>
</div>