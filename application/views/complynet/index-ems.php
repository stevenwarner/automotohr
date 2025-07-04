<div class="main">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                <div class="row">
                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                        <a href="<?php echo $employee['access_level'] == 'Employee' ? base_url('dashboard') : base_url('employee_management_system'); ?>" class="btn btn-info btn-block mb-2"><i class="fa fa-arrow-left"></i> Dashboard</a>
                    </div>
                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3"></div>
                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3"></div>
                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3"></div>
                </div>
            </div>
            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                <div class="page-header">
                    <h1 class="section-ttile">ComplyNet</h1>
                </div>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
<!--                        <div class="dashboard-conetnt-wrp">-->
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 text-center" style="margin-bottom: 20px;">
                                <img class="img-responsive img-inlineblock" src="<?=base_url('assets/images/complynet_full_logo.png');?>" alt="ComplyNet Image">
                            </div>
                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                <?php if(!empty($session['company_detail']['complynet_dashboard_link']) && $session['company_detail']['complynet_dashboard_link'] != NULL && $session['employer_detail']['access_level'] != 'Employee'){
                                    $this->load->view('complynet/complynet_tab_view_ems');
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