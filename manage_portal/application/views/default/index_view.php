<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="container">
    <div class="row">                   
        <div class="col-md-12">
            <div class="info_text"><?php echo $heading_title;?></div>
        </div>
    </div>
</div>
<div class="demo-btn-wrp">
    <div class="container-fluid">
        <div class="col-lg-3 col-md-3 col-xs-12 col-sm-12">
            <div class="demo-btn-box">
                <h2>Schedule Your Free Demo</h2>
                <p>Give it a Test Drive and Kick the Tires Before You Commit to Driving It Everyday.</p>
                <div class="text">
                    <div class="demo-link-wrp">
                        <a class="demo-link" href="<?php echo STORE_FULL_URL.'schedule_your_free_demo'; ?>"><i class="fa fa-calendar"></i>Schedule Your FREE DEMO</a>
                    </div>
                </div>              
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-xs-12 col-sm-12">
            <div class="address-panel">
                <div class="demo-btn-box">
                    <h2>Contact one of our Talent Network Partners at</h2>
                </div>
                <div class="row">
                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                        <h5>Sales Support</h5>
                        <ul>
                            
                            <li><i class="fa fa-phone"></i><?php echo TALENT_NETWORK_SALE_CONTACTNO; ?></li>
                            <li><a href="mailto:<?php echo TALENT_NETWORK_SALES_EMAIL; ?>"><i class="fa fa-envelope"></i> <?php echo TALENT_NETWORK_SALES_EMAIL; ?></a></li>
                        </ul>
                    </div>
                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                        <h5>Technical Support</h5>
                        <ul>
                            
                            <li><i class="fa fa-phone"></i><?php echo TALENT_NETWORK_SUPPORT_CONTACTNO; ?></li>
                            <li><a href="mailto:<?php echo TALENT_NETOWRK_SUPPORT_EMAIL; ?>"><i class="fa fa-envelope"></i> <?php echo TALENT_NETOWRK_SUPPORT_EMAIL; ?></a></li>
                        </ul>
                    </div>
                </div>
            </div> 
        </div>
        <div class="col-lg-3 col-md-3 col-xs-12 col-sm-12">
            <?php $class_name = $this->router->fetch_class(); ?>
            <?php //echo $class_name; ?>
            <?php if ( in_array($class_name, array('home','users','affiliates', 'demo' ))) { ?>
                <div class="demo-btn-box">
                    <div class="text">
                        <figure><a href="<?php base_url('schedule_your_free_demo') ?>"><img class="img-responsive" src="<?php base_url() ?>/assets/images/img-demo-btn-new.png" alt=""/></a>
                        </figure>
                    </div>              
                </div>
            <?php } ?>
        </div>        
    </div>
</div>