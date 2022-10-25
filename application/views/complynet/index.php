<?php if (!$load_view) { ?>
<div class="main-content">
    <div class="dashboard-wrp">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-4">
                    <?php //$this->load->view('complynet/complynet_left_view'); ?>
                    <?php $this->load->view('main/employer_column_left_view'); ?>
                </div>
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-8">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                            <div class="page-header-area">
                                <span class="page-heading down-arrow">ComplyNet</span>
                                <?php if ($session['employer_detail']['access_level_plus']) { ?>
                                    <a href="<?php echo base_url('complynet_company_setting'); ?>" class="dashboard-link-btn-right">
                                        <i class="fa fa-cog"></i> ComplyNet Setting
                                    </a>
                                <?php } ?>
                            </div>
                        </div>
                    </div>

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
<?php } else {

    $this->load->view('complynet/index-ems'); ?>
<?php } ?>