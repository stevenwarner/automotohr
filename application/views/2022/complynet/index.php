<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="main">
    <div class="container-fluid">
        <div class="row">
            <div class="inner-content">
                <?php $this->load->view('templates/_parts/admin_column_left_view'); ?>
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-9 no-padding">
                    <div class="dashboard-content">
                        <div class="dash-inner-block">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                    <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                                    <div class="heading-title page-title">
                                        <h1 class="page-title">User Name:  <?php echo $employee[0]['username']; ?></h1>
                                    </div>
                                    <br />
                                    <br />
                                  
                                    <div class="box-wrapper">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 text-center" style="margin-bottom: 20px;">
                                <img class="img-responsive img-inlineblock" src="<?=base_url('assets/images/complynet_full_logo.png');?>" alt="ComplyNet Image">
                            </div>
                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                <?php if(!empty($session['company_detail']['complynet_dashboard_link']) && $session['company_detail']['complynet_dashboard_link'] != NULL && $session['employer_detail']['access_level'] != 'Employee'){
                                    $this->load->view('complynet/complynet_tab_view');
                                } else{?>
                                    <div class="box-wrapper">
                                        <div class="row">
                                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                <?php
                                                $comploynet = $session['employer_detail']['complynet_credentials'];
                                                if(!empty($comploynet) && $comploynet != NULL){
                                                    $comploynet = unserialize($comploynet);
                                                    echo '<b><h3 class="text-primary">Please Use Following Credentials To Login</h3></b><br>';
                                                    echo '<b>Username: </b>'.$comploynet['username'].'<br>';
                                                    echo '<b>Password: </b>'.$comploynet['password'];
                                                }
                                                ?>
                                            </div>
                                            <div class="col-sm-12">
                                                <iframe src="<?=COMPLYNET_URL;?>" sandbox="allow-forms allow-same-origin	allow-scripts" frameborder="0" style="width: 100%; height: 1000px;"></iframe>
                                            </div>
                                        </div>
                                    </div>
                                <?php }?>
                            </div>
                        </div>
                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function reset_iframe(){
        document.getElementsByTagName('iframe')[0].src = "<?=COMPLYNET_URL;?>";
    }
    window.onbeforeunload = function(e){
        reset_iframe();
    };

    window.onpopstate = function(){
        console.log('Pinged');
    };
</script>