<div class="row">
    <div class="col-xs-12">
        <ul class="nav nav-tabs nav-justified doc_assign_nav_tab">
            <?php
            $complynet = isset($company_details) ? $company_details : $executive_user;
            if(!empty($complynet['complynet_dashboard_link']) && $complynet['complynet_dashboard_link'] != NULL){?>
                <li class="active doc_assign_nav_li"><a data-toggle="tab" href="#dashboard_frame">ComplyNet Dashboard</a></li>
            <?php }?>
            <li class="<?= empty($complynet['complynet_dashboard_link']) && $complynet['complynet_dashboard_link'] == NULL ? 'active' : '';?> doc_assign_nav_li"><a data-toggle="tab" href="#login_frame">Login</a></li>
        </ul>
        <div class="tab-content hr-documents-tab-content">
            <!-- Login Start -->
            <div id="login_frame" class="tab-pane fade in <?= empty($complynet['complynet_dashboard_link']) && $complynet['complynet_dashboard_link'] == NULL ? 'active' : '';?> hr-innerpadding">
                <div class="panel-body">
                    <div class="box-wrapper">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                <?php
                                $comploynet = $complynet['complynet_credentials'];
                                if(!empty($comploynet) && $comploynet != NULL){
                                    $comploynet = unserialize($comploynet);
                                    echo '<b><h3 class="text-success">Please Use Following Credentials To Login</h3></b><br>';
                                    echo '<b>Username: </b>'.$comploynet['username'].'<br>';
                                    echo '<b>Password: </b>'.$comploynet['password'];
                                }
                                ?>
                            </div>
                            <div class="col-sm-12">
                                <iframe src="<?= COMPLYNET_URL;?>" sandbox="allow-forms allow-same-origin	allow-scripts" frameborder="0" style="width: 100%; height: 1000px;"></iframe>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Login End -->

            <!-- Dashboard Start -->
            <div id="dashboard_frame" class="tab-pane fade in <?= !empty($complynet['complynet_dashboard_link']) && $complynet['complynet_dashboard_link'] != NULL ? 'active' : '';?> hr-innerpadding">
                <div class="panel-body">
                    <div class="box-wrapper">
                        <div class="row">
                            <div class="col-lg-12">
                                <?php $company_comply_dashboard_link = isset($company_details) ? $company_details['complynet_dashboard_link'] : $executive_user['complynet_dashboard_link'];?>
                                <?php echo '<iframe src="'.$company_comply_dashboard_link.'" sandbox="allow-forms allow-same-origin	allow-scripts" frameborder="0" style="width: 100%; height: 1000px;"></iframe>';?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Dashboard End -->
        </div>
    </div>
</div>


