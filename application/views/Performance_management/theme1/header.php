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
        // [
        //     'title' => 'One-on-One',
        //     'url' => '/meeting',
        //     'slug' => 'meeting',
        //     'icon' => 'calendar-plus-o',
        //     'segment' => 'meeting'
        // ],
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
        ]
    ];
    //
    $lis = '';
    //
    $baseURL = purl();
    //
    foreach($tabs as $tab){
    
            //
            $lis .= '<li><a '.( isset($tab['props']) ? $tab['props'] : "").' class="'.(isset($tab['class']) ? $tab['class'] : '').' '.( $tab['segment'] == '' || strpos($this->uri->uri_string(), $tab['segment']) !== FALSE  ?  'active' : '' ).'" href="'.( $tab['url'] == 'javascript:void(0)' ? $tab['url'] : $baseURL.$tab['url'] ).'" ><i class="fa fa-'.( $tab['icon'] ).'"></i> '.( $tab['title'] ).'</a></li>';
    }
?>
<div class="clearfix"></div>
<div class="csPageWrap">
<?php if(!isset($gp)): ?>
    <div class="csPageNav csSticky">
        <nav class="csNavBar ">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-12">
                        <!-- Web -->
                        <ul class="csWeb">
                            <li class="pull-left">
                                <a href="<?=base_url('dashboard');?>" class="csBackButton csRadius100"><i
                                        class="fa fa-th"></i>Go To Dashboard</a>
                            </li>
                            <li><a href="javascript:void(0)">|</a></li>
                            <?= $lis; ?>
                        </ul>
                        <!-- Mobile -->
                        <div class="csMobile">
                            <a href="<?=base_url('dashboard');?>" class="csBack"><i class="fa fa-th"></i>Go To
                                Dashboard</a>
                            <span class="pull-right"><i class="fa fa-bars"></i></span>
                            <ul class="csVertical"><?= $lis; ?></ul>
                        </div>
                    </div>
                </div>
            </div>
        </nav>
    </div>
    <?php endif;?>