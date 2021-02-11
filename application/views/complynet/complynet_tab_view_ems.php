<div class="panel panel-default lc-tabs-panel">
    <div class="panel-heading">
        <ul class="nav nav-tabs nav-justified">
            <li class="active"><a href="#dashboard_frame" data-toggle="tab">Dashboard</a></li>
            <li><a href="#login_frame" data-toggle="tab">Login</a></li>
        </ul>
    </div>
    <div class="panel-body">
        <div class="tab-content">
            <div class="tab-pane fade in active" id="dashboard_frame">
                <div class="panel-body">
                    <div class="box-wrapper">
                        <div class="row">
                            <div class="col-lg-12">
                                <?php $company_comply_dashboard_link = $session['company_detail']['complynet_dashboard_link'];?>
                                <?php echo '<iframe src="'.$company_comply_dashboard_link.'" sandbox="allow-forms allow-same-origin	allow-scripts" frameborder="0" style="width: 100%; height: 1000px;"></iframe>';?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="login_frame">
                <div class="panel-body">
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
                                <iframe src="<?= COMPLYNET_URL;?>" sandbox="allow-forms allow-same-origin	allow-scripts" frameborder="0" style="width: 100%; height: 1000px;"></iframe>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>







