<?php
$logo_status = 0;
$logo_location = 'left';
$logo_aspect_ratio = 'none';
$logo_image = '';
$logo_class = '';
$logo_location_class = '';

if (!empty($logo_details)) {
    $logo_status = $logo_details['logo_status'];
    $logo_location = $logo_details['logo_location'];
    $logo_aspect_ratio = $logo_details['logo_aspect_ratio'];
    $logo_image = $logo_details['logo_image'];
    $logo_class = '';
    $logo_location_class = '';

    switch ($logo_aspect_ratio) {
        case 'square':
            $logo_class = 'logo-aspect-square';
            break;
        case 'horizontal':
            $logo_class = 'logo-aspect-horizontal';
            break;
        case 'vertical':
            $logo_class = 'logo-aspect-vertical';
            break;
        default:
            $logo_class = 'logo-aspect-none';
            break;
    }

    switch ($logo_location) {
        case 'left':
            $logo_location_class = 'logo-location-left';
            break;
        case 'center':
            $logo_location_class = 'logo-location-center';
            break;
        case 'right':
            $logo_location_class = 'logo-location-right';
            break;
    }
}
if(!($customize_career_site['status'] == 1 && $customize_career_site['menu'] == 0)){ 
    if($theme_name == 'theme-4') { 
        if($logo_location == 'left') { ?>
            <header id="masthead" class="masthead navbar navbar-default navbar-fixed-top <?php echo ( $pageName == 'testimonial' ? 'header-testimonial' : ''); ?> <?php echo $logo_class . ' ' . $logo_location_class; ?>">
                <div class="container-fluid">
                    <!-- <div class="hr-lanugages">
                        <div id="google_translate_element"></div>
                    </div> -->
                    
                    <div class="navbar-header"><!-- Brand and toggle get grouped for better mobile display -->
                        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#main-menu">
                            <i class="fa fa-bars"></i>
                        </button>
                        <?php if($logo_status == 1 && $logo_image != '') { ?>
                            <a class="navbar-brand" href="<?php echo base_url('/'); ?>">
                                <img src="<?php echo AWS_S3_BUCKET_URL . $logo_image; ?>">
                            </a>
                        <?php } ?>
                    </div>
                    
                    <nav id="main-menu" class="collapse navbar-collapse pull-right navigation"><!-- Collect the nav links, forms, and other content for toggling -->
                        <ul class="nav navbar-nav">
                        <?php $this->load->view('common/menu_li'); ?>
                        </ul>
                    </nav>
                </div>
            </header>

        <?php } else if ( $logo_location == 'center') { ?>

            <header id="masthead" class="masthead navbar navbar-default navbar-fixed-top <?php echo ( $pageName == 'testimonial' ? 'header-testimonial' : ''); ?> <?php echo $logo_class . ' ' . $logo_location_class; ?>">
                <div class="container-fluid">
                    <!-- <div class="hr-lanugages">
                        <div id="google_translate_element"></div>
                    </div> -->
                    
                    <!-- Collect the nav links, forms, and other content for toggling -->
                    
                    <nav class="navbar main-navigation" role="navigation">
                        <div class="navbar-header">
                            <?php if($logo_status == 1 && $logo_image != '') { ?>
                                <a class="navbar-brand" href="<?php echo base_url('/'); ?>">
                                    <img src="<?php echo AWS_S3_BUCKET_URL . $logo_image; ?>">
                                </a>
                            <?php } ?>
                        </div>
                        <div class="dropdown main-menu" id="main-menu">
                          <button class="btn dropdown-toggle" type="button" data-toggle="dropdown">Menu
                          <i class="fa fa-navicon"></i></button>
                          <ul class="dropdown-menu">
                          <?php $this->load->view('common/menu_li'); ?>
                              </ul>
                        </div> 
                    </nav>
                </div>
            </header>

        <?php } else if( $logo_location == 'right') { ?>

            <header id="masthead" class="masthead navbar navbar-default navbar-fixed-top <?php echo ( $pageName == 'testimonial' ? 'header-testimonial' : ''); ?> <?php echo $logo_class . ' ' . $logo_location_class; ?>">
                <div class="container-fluid">
                    <!-- <div class="hr-lanugages">
                        <div id="google_translate_element"></div>
                    </div> -->
                    
                    <!-- Brand and toggle get grouped for better mobile display -->
                    <div class="navbar-header">
                        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#main-menu">
                            <i class="fa fa-bars"></i>
                        </button>
                        <?php if($logo_status == 1 && $logo_image != '') { ?>
                            <a class="navbar-brand" href="<?php echo base_url('/'); ?>">
                                <img src="<?php echo AWS_S3_BUCKET_URL . $logo_image; ?>">
                            </a>
                        <?php } ?>
                    </div>
                    <!-- Collect the nav links, forms, and other content for toggling -->
                    <nav id="main-menu" class="collapse navbar-collapse pull-right navigation">
                        <ul class="nav navbar-nav">
                        <?php $this->load->view('common/menu_li'); ?>
                        </ul>
                    </nav>
                </div>
            </header>

        <?php } ?>
    <?php } else if ($theme_name == 'theme-3') { ?>

        <?php if($logo_location == 'left') { ?>

            <header class="header job_preview_hideit <?php echo $logo_class . ' ' . $logo_location_class; ?>">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                            <?php if ($logo_status == 1 && !empty($logo_image)) { ?>
                                <div class="logo">
                                    <a href="<?php echo base_url('/'); ?>">
                                        <img src="<?php echo AWS_S3_BUCKET_URL . $logo_image; ?>">
                                    </a>
                                </div>
                            <?php } ?>
                        </div>
                        <div class="col-lg-10 col-md-10 col-sm-10 col-xs-12">
                            <div class="hr-lanugages">
                                <div id="google_translate_element"></div>
                            </div>
                            <nav class="navigation">
                                <ul id="menus">
                                <?php $this->load->view('common/menu_li'); ?>
                                </ul>
                            </nav>
                        </div>
                    </div>
                </div>
            </header>

        <?php } else if ( $logo_location == 'center') { ?>

            <header class="header job_preview_hideit <?php echo $logo_class . ' ' . $logo_location_class; ?>">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12 hidden-md visible-md visible-sm visible-xs">
                            <?php if ($logo_status == 1 && !empty($logo_image)) { ?>
                                <div class="logo">
                                    <a href="<?php echo base_url('/'); ?>">
                                        <img src="<?php echo AWS_S3_BUCKET_URL . $logo_image; ?>">
                                    </a>
                                </div>
                            <?php } ?>
                        </div>
                        <div class="col-lg-5 col-md-5 col-sm-3 col-xs-6">
                            <div class="hr-lanugages">
                                <div id="google_translate_element"></div>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12 visible-lg visible-md">
                            <?php if ($logo_status == 1 && !empty($logo_image)) { ?>
                                <div class="logo">
                                    <a href="<?php echo base_url('/'); ?>">
                                        <img src="<?php echo AWS_S3_BUCKET_URL . $logo_image; ?>">
                                    </a>
                                </div>
                            <?php } ?>
                        </div>
                        <div class="col-lg-5 col-md-5 col-sm-9 col-xs-6">                            
                            <nav class="navigation">
                                <ul id="menus">
                                <?php $this->load->view('common/menu_li'); ?>
                                </ul>
                            </nav>
                        </div>
                    </div>
                </div>
            </header>

        <?php } else if( $logo_location == 'right') { ?>

            <header class="header job_preview_hideit <?php echo $logo_class . ' ' . $logo_location_class; ?>">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-10 col-md-10 col-sm-10 col-xs-12">
                            <div class="hr-lanugages">
                                <div id="google_translate_element"></div>
                            </div>
                            <nav class="navigation">
                                <ul id="menus">
                                <?php $this->load->view('common/menu_li'); ?>
                                </ul>
                            </nav>
                        </div>
                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                            <?php if ($logo_status == 1 && !empty($logo_image)) { ?>
                                <div class="logo">
                                    <a href="<?php echo base_url('/'); ?>">
                                        <img src="<?php echo AWS_S3_BUCKET_URL . $logo_image; ?>">
                                    </a>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </header>

        <?php } ?>

    <?php } else if ($theme_name == 'theme-2') { ?>

        <?php if($logo_location == 'left') { ?>

            <header class="header job_preview_hideit custom-theme <?php echo $logo_class . ' ' . $logo_location_class; ?>">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-6 hidden-md visible-md visible-xs">
                            <?php if($logo_status == 1 && !empty($logo_image)) { ?>
                                <div class="logo hide-logo">
                                    <a href="<?php echo base_url('/'); ?>">
                                        <figure>
                                            <img src="<?php echo AWS_S3_BUCKET_URL . $logo_image; ?>">
                                        </figure>
                                    </a>
                                </div>
                            <?php } ?>
                        </div>
                        <div class="col-lg-2 col-md-3 col-sm-3 col-xs-6">
                            <div class="hr-lanugages">
                                <div id="google_translate_element"></div>
                            </div>
                        </div>
                        <div class="col-lg-10 col-md-9 col-sm-9 col-xs-12">
                            <nav class="navigation">
                                <ul id="menus">
                                <?php $this->load->view('common/menu_li'); ?>
                                </ul>
                            </nav>
                        </div>
                    </div>
                </div>
            </header>
            <div class="page-banner">
                <figure>
                    <img src="<?php echo AWS_S3_BUCKET_URL . $pictures ?>" alt="Banner Image">
                    <div class="banner-caption-div <?php echo $logo_class . ' ' . $logo_location_class; ?>">
                        <div class="container">
                            <div class="row">                   
                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                    <?php if($logo_status == 1 && !empty($logo_image)) { ?>
                                        <div class="logo">
                                            <a href="<?php echo base_url('/'); ?>">
                                                <figure>
                                                    <img src="<?php echo AWS_S3_BUCKET_URL . $logo_image; ?>">
                                                </figure>
                                            </a>
                                        </div>  
                                    <?php } ?>
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                    <?php if (!empty($company_details['YouTubeVideo'])) { ?>
                                        <div class="header-video">
                                            <!--<iframe src="//www.youtube.com/embed/<?php /*echo $company_details['YouTubeVideo']; */?>"></iframe>-->
                                            <?php $this->load->view('common/video_player_company_partial'); ?>
                                        </div>
                                    <?php } ?>

                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12"></div>
                            </div>
                        </div>
                    </div>                                        
                </figure>
            </div>

        <?php } else if ( $logo_location == 'center') { ?>

            <header class="header job_preview_hideit custom-theme <?php echo $logo_class . ' ' . $logo_location_class; ?>">
                <div class="container">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="hr-lanugages">
                                <div id="google_translate_element"></div>
                            </div>
                            <?php if($logo_status == 1 && !empty($logo_image)) { ?>
                                <div class="logo hide-logo">
                                    <a href="<?php echo base_url('/'); ?>">
                                        <figure>
                                            <img src="<?php echo AWS_S3_BUCKET_URL . $logo_image; ?>">
                                        </figure>
                                    </a>
                                </div>
                            <?php } ?>
                            <nav class="navigation">
                                <ul id="menus">
                                <?php $this->load->view('common/menu_li'); ?>
                                </ul>
                            </nav>
                        </div>
                    </div>
                </div>
            </header>
            <div class="page-banner">
                <figure>
                    <img src="<?php echo AWS_S3_BUCKET_URL . $pictures ?>" alt="Banner Image">
                    <div class="banner-caption-div <?php echo $logo_class . ' ' . $logo_location_class; ?>">
                        <div class="container">
                            <div class="row">
                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 pull-left">
                                    <?php if (!empty($company_details['YouTubeVideo'])) { ?>
                                        <div class="header-video">
                                            <!--<iframe src="//www.youtube.com/embed/<?php /*echo $company_details['YouTubeVideo']; */?>"></iframe>-->
                                            <?php $this->load->view('common/video_player_company_partial'); ?>
                                        </div>
                                    <?php } ?>

                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                    <?php if($logo_status == 1 && !empty($logo_image)) { ?>
                                        <div class="logo">
                                            <a href="<?php echo base_url('/'); ?>">
                                                <figure>
                                                    <img src="<?php echo AWS_S3_BUCKET_URL . $logo_image; ?>">
                                                </figure>
                                            </a>
                                        </div>  
                                    <?php } ?>
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12"></div>
                            </div>
                        </div>
                    </div>                                        
                </figure>
            </div>

        <?php } else if( $logo_location == 'right') { ?>

            <header class="header job_preview_hideit custom-theme <?php echo $logo_class . ' ' . $logo_location_class; ?>">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-2 col-md-3 col-sm-3 col-xs-6">
                            <div class="hr-lanugages">
                                <div id="google_translate_element"></div>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-6 hidden-md visible-md visible-xs">
                            <?php if($logo_status == 1 && !empty($logo_image)) { ?>
                                <div class="logo hide-logo">
                                    <a href="<?php echo base_url('/'); ?>">
                                        <figure>
                                            <img src="<?php echo AWS_S3_BUCKET_URL . $logo_image; ?>">
                                        </figure>
                                    </a>
                                </div>
                            <?php } ?>
                        </div>
                        <div class="col-lg-8 col-md-9 col-sm-9 col-xs-12">
                            <nav class="navigation">
                                <ul id="menus">
                                <?php $this->load->view('common/menu_li'); ?>
                                </ul>
                            </nav>
                        </div>
                    </div>
                </div>
            </header>
            <div class="page-banner">
                <figure>
                    <img src="<?php echo AWS_S3_BUCKET_URL . $pictures ?>" alt="Banner Image">
                    <div class="banner-caption-div <?php echo $logo_class . ' ' . $logo_location_class; ?>">
                        <div class="container">
                            <div class="row">                   
                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 pull-right">
                                    <?php if($logo_status == 1 && !empty($logo_image)) { ?>
                                        <div class="logo">
                                            <a href="<?php echo base_url('/'); ?>">
                                                <figure>
                                                     <img src="<?php echo AWS_S3_BUCKET_URL . $logo_image; ?>">
                                                </figure>
                                            </a>
                                        </div>  
                                    <?php } ?>
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12"></div>
                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                    <?php if (!empty($company_details['YouTubeVideo'])) { ?>
                                        <div class="header-video">
                                            <!--<iframe src="//www.youtube.com/embed/<?php /*echo $company_details['YouTubeVideo']; */?>"></iframe>-->
                                            <?php $this->load->view('common/video_player_company_partial'); ?>
                                        </div>
                                    <?php } ?>
                                </div>                                
                            </div>
                        </div>
                    </div>                                        
                </figure>
            </div>

        <?php } ?>

    <?php } else if ($theme_name == 'theme-1') { ?>

        <?php if($logo_location == 'left') { ?>

            <header class="header job_preview_hideit <?php echo $logo_class . ' ' . $logo_location_class; ?>">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                            <?php if ($logo_status == 1 && !empty($logo_image)) { ?>
                                <div class="logo">
                                    <a href="<?php echo base_url('/'); ?>">
                                        <figure>
                                            <img src="<?php echo base_url('assets/theme-1/images/bg-logo.png'); ?>">
                                            <figcaption>
                                                <img src="<?php echo AWS_S3_BUCKET_URL . $logo_image; ?>">
                                            </figcaption>
                                        </figure>
                                    </a>
                                </div>
                            <?php } ?>
                        </div>
                        <div class="col-lg-9 col-md-9 col-sm-9 col-xs-12">
                            <nav class="navigation">
                                <ul id="menus">
                                <?php $this->load->view('common/menu_li'); ?>
                                </ul>
                            </nav>
                            <div class="hr-lanugages">
                                <div id="google_translate_element"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

        <?php } else if ( $logo_location == 'center') { ?>

            <header class="header job_preview_hideit <?php echo $logo_class . ' ' . $logo_location_class; ?>">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-5 col-md-5 col-sm-5 col-xs-5">
                            <nav class="navigation">
                                <ul id="menus">
                                <?php $this->load->view('common/menu_li'); ?>
                                </ul>
                            </nav>
                        </div>
                        <div class="col-lg-5 col-md-5 col-sm-5 col-xs-5 pull-right">
                            <div class="hr-lanugages">
                                <div id="google_translate_element"></div>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                            <?php if ($logo_status == 1 && !empty($logo_image)) { ?>
                                <div class="logo">
                                    <a href="<?php echo base_url('/'); ?>">
                                        <figure>
                                            <img src="<?php echo base_url('assets/theme-1/images/bg-logo.png'); ?>">
                                            <figcaption>
                                                <img src="<?php echo AWS_S3_BUCKET_URL . $logo_image; ?>">
                                            </figcaption>
                                        </figure>
                                    </a>
                                </div>
                            <?php } ?>
                        </div>                        
                    </div>
                </div>
            </header>

        <?php } else if( $logo_location == 'right') { ?>

            <header class="header job_preview_hideit <?php echo $logo_class . ' ' . $logo_location_class; ?>">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 pull-right">
                            <?php if ($logo_status == 1 && !empty($logo_image)) { ?>
                                <div class="logo">
                                    <a href="<?php echo base_url('/'); ?>">
                                        <figure>
                                            <img src="<?php echo base_url('assets/theme-1/images/bg-logo.png'); ?>">
                                            <figcaption>
                                                <img src="<?php echo AWS_S3_BUCKET_URL . $logo_image; ?>">
                                            </figcaption>
                                        </figure>
                                    </a>
                                </div>
                            <?php } ?>
                        </div>
                        <div class="col-lg-9 col-md-9 col-sm-9 col-xs-12">
                            <nav class="navigation">
                                <ul id="menus">
                                <?php $this->load->view('common/menu_li'); ?>
                                </ul>
                            </nav>
                            <div class="hr-lanugages">
                                <div id="google_translate_element"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

        <?php } ?>

    <?php } ?>
<?php } ?>
