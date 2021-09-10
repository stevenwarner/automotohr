<?php 
    $PM_PERMISSION = $session['employer_detail']['access_level_plus']
    ? 1 : GetPMPermissions(
        $companyId,
        $employerId,
        $employee['access_level'],
        $this
    );
    //
    $tabs = [];
    $tabs[] =     [
        'title' => 'Overview',
        'url' => '/dashboard',
        'slug' => '',
        'icon' => 'dashboard',
        'segment' => 'dashboard'
    ];
    //
    if($PM_PERMISSION){
        $tabs[] =     [
            'title' => 'Template(s)',
            'url' => '/templates',
            'slug' => 'templates',
            'icon' => 'plus-square',
            'segment' => 'templates'
        ];
        $tabs[] =     [
            'title' => 'Create a Review',
            'url' => '/review/create',
            'slug' => 'create',
            'icon' => 'plus-square',
            'segment' => 'review/create'
        ];
    }
    $tabs[] = [
        'title' => 'Reviews',
        'submenu' => [ 
        [
            'title' => 'All Reviews',
            'url' => '/reviews',
            'slug' => 'reviews',
            'icon' => '',
            'segment' => 'reviews'
        ],
        [
            'title' => 'Assigned Reviews - Reviewer',
            'url' => '/reviews/all',
            'slug' => 'assigned-reviews',
            'icon' => '',
            'segment' => 'reviews/all'
        ],
        [
            'title' => 'Assigned Reviews - Reporting Manager',
            'url' => '/feedbacks/all',
            'slug' => 'assigned-reviews',
            'icon' => '',
            'segment' => 'reviews/all'
        ],
        [
            'title' => 'Reviews Completed Against Me',
            'url' => '/my-reviews',
            'slug' => 'my-reviews',
            'icon' => '',
            'segment' => 'my-reviews'
        ]]
    ];
    
    $tabs[] =     [
        'title' => 'Create a Goal',
        'url' => 'javascript:void(0)',
        'slug' => 'goal/create',
        'icon' => 'plus-square',
        'segment' => 'goal/create',
        'class' => 'jsCreateGoal'
    ];
    
    
    $tabs[] =     [
        'title' => 'Goals',
        'url' => '/goals',
        'slug' => 'goals',
        'icon' => 'bullseye',
        'segment' => 'goals'
    ];
    $sub[] =     [
        'title' => 'Calendar',
        'url' => 'javascript:void(0)',
        'slug' => 'calendar',
        'icon' => 'calendar',
        'segment' => 'calendar',
        'class' => 'jsCalendarView'
    ];
    if($PM_PERMISSION){
        
        $sub[] =     [
            'title' => 'Report',
            'url' => '/report',
            'slug' => 'report',
            'icon' => 'pie-chart',
            'segment' => 'report'
        ];
        $sub[] =     [
            'title' => 'Settings',
            'url' => '/settings',
            'slug' => 'settings',
            'icon' => 'pie-chart',
            'segment' => 'settings'
        ];
    }
    $tabs[] = [
        'title' => 'More',
        'submenu' => $sub
    ];
    //
    $lis = '';
    //
    $baseURL = purl();
    //
    foreach($tabs as $tab){
        //
        if(isset($tab['submenu'])){
            $tmp = '';
            foreach($tab['submenu'] as $item){
                $tmp .= '<li><a href="'.( $item["url"] == "javascript:void(0)" ? $item['url'] : $baseURL.$item['url'] ).'" '.( isset($item['class']) ? 'class="'.($item['class']).'"' : '' ).'><i class="fa fa-'.( $item['icon'] ).'"></i> '.( $item['title'] ).'</a></li>';
            }
            //
            $lis .= '<li class="has">';
            $lis .= '   <a href="javascript:void(0)"><i class="fa fa-list"></i>'.($tab['title']).' &nbsp;&nbsp;<i class="fa fa-caret-down"></i></a>';
            $lis .= '   <ul>';
            $lis .=       $tmp;
            $lis .= '   </ul>';
            $lis .= '</li>';
        } else{

            //
            $lis .= '<li><a '.( isset($tab['props']) ? $tab['props'] : "").' class="csF16 '.(isset($tab['class']) ? $tab['class'] : '').' '.( $tab['segment'] == '' || strpos($this->uri->uri_string(), $tab['segment']) !== FALSE  ?  'active' : '' ).'" href="'.( $tab['url'] == 'javascript:void(0)' ? $tab['url'] : $baseURL.$tab['url'] ).'" ><i class="fa fa-'.( $tab['icon'] ).'"></i> '.( $tab['title'] ).'</a></li>';
        }
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
                                <span class="csF18 csB9 jsIncreaseSize" title="Increase the font size"
                                    placement="bottom">A</span>&nbsp;
                                <span class="csF16 jsDecreaseSize" title="Decrease the font size"
                                    placement="bottom">A</span>&nbsp;&nbsp;
                                <span class="csF16 jsResetSize" title="Reset the font size to default"
                                    placement="bottom"><i class="fa fa-refresh" aria-hidden="true"></i></span>
                            </li>
                        </ul>
                        <!-- Mobile -->
                        <div class="csMobile hidden-sm">
                            <a href="<?=base_url('dashboard');?>" class="csBack"><i class="fa fa-th"
                                    aria-hidden="true"></i>Go To
                                Dashboard</a>
                            <span class="pull-right"><i class="fa fa-bars" aria-hidden="true"></i></span>
                            <ul class="csVertical"><?= $lis; ?></ul>
                        </div>
                    </div>
                </div>
            </div>
        </nav>
    </div>
