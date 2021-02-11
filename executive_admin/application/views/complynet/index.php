<div class="main">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">

                <div class="heading-title page-title">
                    <h1 class="page-title"><i class="fa fa-dashboard"></i>ComplyNet</h1>
                    <!--                <div class="heading-title page-title">-->
                    <a class="black-btn pull-right" href="<?php echo base_url('dashboard/manage_admin_companies/'.$cid); ?>"><i class="fa fa-long-arrow-left"></i> Back to Company Dashboard</a>
                    <!--                </div>-->
                </div>
                <div class="hr-box">
                    <div class="hr-box-header bg-header-green">
                        <h1 class="hr-registered text-center">ComplyNet</h1>
                    </div>
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 text-center" style="margin-bottom: 20px;">
                            <img class="img-responsive img-inlineblock" src="<?=base_url('assets/images/complynet_full_logo.png');?>" alt="ComplyNet Image">
                        </div>
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                            <?php
                            $this->load->view('complynet/complynet_tab_view');
//                            if(!empty($executive_user['complynet_dashboard_link']) && $executive_user['complynet_dashboard_link'] != NULL){
//                                $this->load->view('complynet/complynet_tab_view');
//                            } else{ ?>
<!--                                <div class="box-wrapper">-->
<!--                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">-->
<!--                                        --><?php
//                                        $comploynet = $executive_user['complynet_credentials'];
//                                        if(!empty($comploynet) && $comploynet != NULL){
//                                            $comploynet = unserialize($comploynet);
//                                            echo '<b><h3 class="text-primary">Please Use Following Credentials To Login</h3></b><br>';
//                                            echo '<b>Username: </b>'.$comploynet['username'].'<br>';
//                                            echo '<b>Password: </b>'.$comploynet['password'];
//                                        }
//                                        ?>
<!--                                    </div>-->
<!--                                    <div class="col-sm-12">-->
<!--                                        <iframe src="--><?//=COMPLYNET_URL;?><!--" sandbox="allow-forms allow-same-origin	allow-scripts" frameborder="0" style="width: 100%; height: 1000px;"></iframe>-->
<!--                                    </div>-->
<!--                                </div>-->
<!--                            --><?php //}?>
                        </div>
                </div>
            </div>
        </div>
    </div>
</div>	