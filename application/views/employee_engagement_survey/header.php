<?php 
    //
    $tabs = [];
    // Push Dashboard
    $tabs[] = [
        'title' => 'Overview',
        'url' => '/overview',
        'slug' => '',
        'icon' => 'dashboard',
        'segment' => 'overview'
    ];
    // Push Surveys
    $tabs[] = [
        'title' => 'Surveys',
        'url' => '/surveys/pending',
        'slug' => '',
        'icon' => 'list',
        'segment' => 'surveys'
    ];
    // Push Settings
    $tabs[] = [
        'title' => 'Settings',
        'url' => '/settings',
        'slug' => '',
        'icon' => 'cogs',
        'segment' => 'settings'
    ];
    
    //
    $lis = '';
    //
    $baseURL = base_url('employee_survey');
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
