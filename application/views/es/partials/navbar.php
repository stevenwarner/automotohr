<?php
//
$navpills[] = [
    'title' => 'Overview',
    'url' => 'overview',
    'slug' => '',
    'icon' => 'pie-chart',
    'segment' => 'employee/surveys/overview'
];

$navpills[] = [
    'title' => 'Surveys',
    'url' => 'surveys',
    'slug' => '',
    'icon' => 'clipboard',
    'segment' => 'employee/surveys/surveys'
];

// $navpills[] = [
//     'title' => 'My Reviews',
//     'url' => '/my_reviews',
//     'slug' => '',
//     'icon' => 'star',
//     'segment' => 'settings'
// ];

// $navpills[] = [
//     'title' => 'Report',
//     'url' => 'report',
//     'slug' => '',
//     'icon' => 'report',
//     'segment' => 'report'
// ];

// $navpills[] = [
//     'title' => 'Settings',
//     'url' => 'settings',
//     'slug' => '',
//     'icon' => 'cogs',
//     'segment' => 'settings'
// ];

//
$lis = '';
//
$baseURL = base_url('employee/surveys/');
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
        $lis .= '<li><a ' . (isset($tab['props']) ? $tab['props'] : "") . ' class="_csF16 ' . (isset($tab['class']) ? $tab['class'] : '') . ' ' . ($tab['segment'] == '' || strpos($this->uri->uri_string(), $tab['segment']) !== FALSE  ?  'active' : '') . '" href="' . ($tab['url'] == 'javascript:void(0)' ? $tab['url'] : $baseURL . $tab['url']) . '" ><i class="fa fa-' . ($tab['icon']) . '"></i> ' . ($tab['title']) . '</a></li>';
    }
}
?>
<div class="_csPageNavBarRow">
    <nav class="_csPageNavBar">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <!-- Web -->
                    <ul class="_csPageNavBarWeb text-left">
                        <li class="_csP0">
                            <a href="<?= base_url('dashboard'); ?>" class="btn _csB4 _csF2 _csF14 _csR5">
                                <i class="fa fa-th _csF2" aria-hidden="true"></i>Go To Dashboard
                            </a>
                        </li>
                        <?= $lis; ?>
                    </ul>
                </div>
            </div>
        </div>
        <!-- Mobile -->
        <div class="csMobile hidden">
            <a href="<?= base_url('dashboard'); ?>" class="csBack"><i class="fa fa-th" aria-hidden="true"></i>Go To
                Dashboard</a>
            <span class="pull-right"><i class="fa fa-bars" aria-hidden="true"></i></span>
            <ul class="csVertical"><?= $lis; ?></ul>
        </div>
    </nav>
</div>