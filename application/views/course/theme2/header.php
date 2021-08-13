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
            'title' => 'Create a Course',
            'url' => '/create',
            'slug' => 'create',
            'icon' => 'plus-square',
            'segment' => 'review/create'
        ];
    }
    $tabs[] =     [
        'title' => 'Courses',
        'url' => '/courses',
        'slug' => 'reviews',
        'icon' => 'th-list',
        'segment' => 'reviews'
    ];
    $tabs[] =     [
        'title' => 'My Courses',
        'url' => '/my-courses',
        'slug' => 'my-reviews',
        'icon' => 'th-list',
        'segment' => 'my-reviews'
    ];
    if($PM_PERMISSION){
        $tabs[] =     [
            'title' => 'Settings',
            'url' => '/settings',
            'slug' => 'settings',
            'icon' => 'pie-chart',
            'segment' => 'settings'
        ];
    }
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
                            <li><a class="csF16" href="<?php echo purl('dashboard'); ?>"><i class="fa fa-dashboard"></i> Overview</a></li>
                            <?php if($PM_PERMISSION) { ?>
                                <li>
                                    <a class="csF16" href="javascript:void(0)" id="add_course_btn">
                                        <i class="fa fa-plus-square"></i> Create a Course
                                    </a>
                                </li> 
                            <?php } ?>
                            <li>
                                <a class="csF16" href="<?php echo purl('courses'); ?>">
                                    <i class="fa fa-th-list"></i> Courses
                                </a>
                            </li>
                            <li>
                                <a class="csF16" href="<?php echo purl('my-courses'); ?>">
                                    <i class="fa fa-th-list"></i> My Courses
                                </a>
                            </li>
                            <?php if($PM_PERMISSION) { ?>
                                <li>
                                    <a class="csF16" href="<?php echo purl('settings'); ?>">
                                        <i class="fa fa-pie-chart"></i> Settings</a>
                                </li>
                            <?php } ?>

                               
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
