<?php
//
$navpills = [];

if (isPayrollOrPlus()) {
    //
    $navpills[] = [
        'title' => 'Overview',
        'url' => '/today_overview',
        'slug' => '',
        'icon' => 'list',
        'segment' => '/today_overview'
    ];
}
$navpills[] = [
    'title' => 'Clock My Day',
    'url' => '/my',
    'slug' => '',
    'icon' => 'clock-o',
    'segment' => 'my'
];
// Dashboard
$navpills[] = [
    'title' => 'My Time sheet',
    'url' => '/my/time-sheet',
    'slug' => '',
    'icon' => 'calendar',
    'segment' => '/my/time-sheet'
];
if (isPayrollOrPlus()) {
    $navpills[] = [
        'title' => 'Time Sheet',
        'url' => '/time-sheet',
        'slug' => '',
        'icon' => 'list',
        'segment' => 'time-sheet'
    ];
    $navpills[] = [
        'title' => 'Overtime Report',
        'url' => '/overtime',
        'slug' => '',
        'icon' => 'pie-chart',
        'segment' => 'overtime'
    ];
    $navpills[] = [
        'title' => 'Report',
        'url' => '/report',
        'slug' => '',
        'icon' => 'bar-chart',
        'segment' => 'report'
    ];

    $navpills[] = [
        'title' => 'Map Location',
        'url' => '/maplocation',
        'slug' => '',
        'icon' => 'map-marker',
        'segment' => 'maplocation'
    ];

    $navpills[] = [
        'title' => 'Settings',
        'url' => '/settings',
        'slug' => '',
        'icon' => 'cogs',
        'segment' => 'settings'
    ];
}


//
$lis = '';
//
$baseURL = base_url('attendance/');
//
foreach ($navpills as $tab) {
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
        $lis .= '<li><a ' . (isset($tab['props']) ? $tab['props'] : "") . ' class="csF16 ' . (isset($tab['class']) ? $tab['class'] : '') . ' ' . ($tab['segment'] == '' || strpos($this->uri->uri_string(), $tab['segment']) !== FALSE  ?  'active' : '') . '" href="' . ($tab['url'] == 'javascript:void(0)' ? $tab['url'] : $baseURL . $tab['url']) . '" ><i class="fa fa-' . ($tab['icon']) . '"></i> ' . ($tab['title']) . '</a></li>';
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