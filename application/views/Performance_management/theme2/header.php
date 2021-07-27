<?php 
    //
    $tabs = [
        [
            'title' => 'Overview',
            'url' => '/dashboard',
            'slug' => '',
            'icon' => 'dashboard',
            'segment' => 'dashboard'
        ],
        [
            'title' => 'Create a Review',
            'url' => '/review/create',
            'slug' => 'create',
            'icon' => 'plus-square',
            'segment' => 'review/create'
        ],
        [
            'title' => 'Reviews',
            'url' => '/reviews',
            'slug' => 'reviews',
            'icon' => 'th-list',
            'segment' => 'reviews'
        ],
        [
            'title' => 'My Reviews',
            'url' => '/my-reviews',
            'slug' => 'my-reviews',
            'icon' => 'th-list',
            'segment' => 'my-reviews'
        ],
        [
            'title' => 'Create a Goal',
            'url' => 'javascript:void(0)',
            'slug' => 'goal/create',
            'icon' => 'plus-square',
            'segment' => 'goal/create',
            'class' => 'jsCreateGoal'
        ],
        [
            'title' => 'Goals',
            'url' => '/goals',
            'slug' => 'goals',
            'icon' => 'bullseye',
            'segment' => 'goals'
        ],
        [
            'title' => 'Calendar',
            'url' => 'javascript:void(0)',
            'slug' => 'calendar',
            'icon' => 'calendar',
            'segment' => 'calendar',
            'class' => 'jsCalendarView'
        ],
        [
            'title' => 'Report',
            'url' => '/report',
            'slug' => 'report',
            'icon' => 'pie-chart',
            'segment' => 'report'
        ],
        // [
        //     'title' => 'Settings',
        //     'url' => '/settings',
        //     'slug' => 'settings',
        //     'icon' => 'pie-chart',
        //     'segment' => 'settings'
        // ]
    ];
    //
    $lis = '';
    //
    $baseURL = purl();
    //
    foreach($tabs as $tab){
        //
        $lis .= '<li><a '.( isset($tab['props']) ? $tab['props'] : "").' class="csF16 '.(isset($tab['class']) ? $tab['class'] : '').' '.( $tab['segment'] == '' || strpos($this->uri->uri_string(), $tab['segment']) !== FALSE  ?  'active' : '' ).'" href="'.( $tab['url'] == 'javascript:void(0)' ? $tab['url'] : $baseURL.$tab['url'] ).'" ><i class="fa fa-'.( $tab['icon'] ).'"></i> '.( $tab['title'] ).'</a></li>';
    }
?>
<div class="clearfix"></div>
<div class="csPageWrap">
    <div class="csPageNav">
        <nav class="csNavBar ">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-12">
                        <!-- Web -->
                        <ul class="csWeb hidden-xs">
                            <li>
                                <a href="<?=base_url('dashboard');?>" class="csBackButton csRadius100 csF16"><i
                                        class="fa fa-th" aria-hidden="true"></i>Go To Dashboard</a>
                            </li>
                            <li><a href="javascript:void(0)" class="csF14">|</a></li>
                            <?= $lis; ?>
                            <li class="pull-right" style="margin-top: 5px; cursor: pointer;">
                                <span class="csF18 csB9 jsIncreaseSize" title="Increase the font size" placement="bottom">A</span>&nbsp;
                                <span class="csF16 jsDecreaseSize" title="Decrease the font size" placement="bottom">A</span>&nbsp;&nbsp;
                                <span class="csF16 jsResetSize" title="Reset the font size to default" placement="bottom"><i class="fa fa-refresh" aria-hidden="true"></i></span>
                            </li>
                        </ul>
                        <!-- Mobile -->
                        <div class="csMobile hidden-sm">
                            <a href="<?=base_url('dashboard');?>" class="csBack"><i class="fa fa-th" aria-hidden="true"></i>Go To
                                Dashboard</a>
                            <span class="pull-right"><i class="fa fa-bars" aria-hidden="true"></i></span>
                            <ul class="csVertical"><?= $lis; ?></ul>
                        </div>
                    </div>
                </div>
            </div>
        </nav>
    </div>