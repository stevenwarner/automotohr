<style>
    label.control{ font-size: 12px !important;}
    .csHeading{
        padding: 10px;
        background-color: #eee;
        border-top: 1px solid #ddd;
        border-bottom: 1px solid #ddd;
    }
    .nav-tabs.nav-justified li.active a{
        background-color: #81b431 !important;
        color: #fff !important;
    }
    .js-hint{ font-size: 11px; margin-top: 5px; }
</style>

<?php $this->load->view('timeoff/includes/header'); ?>
<div class="main-content">
    <div class="dashboard-wrp">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                    <div class="csPIPage">
                        <!-- Loader -->
                        <div class="csIPLoader jsIPLoader" data-page="balance"><i class="fa fa-circle-o-notch fa-spin"></i></div>

                        <!-- View  -->
                        <?php $this->load->view('timeoff/partials/balances/view'); ?>
                        
                        <!-- Employee on off for mobile -->
                        <div id="js-employee-off-box-mobile"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $this->load->view('timeoff/popups/policies'); ?>
<?php $this->load->view('timeoff/popups/balance'); ?>