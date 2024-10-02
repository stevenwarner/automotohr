
<?php if($load_view){

$panelHeading='background-color: #3554DC';

}else{
$panelHeading='background-color: #81b431';
}
?>

<div class="main-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-12">
                <?php $this->load->view('Performance_management/theme2//left_menu'); ?>
            </div>
            <div class="col-lg-9 col-md-9 col-xs-12 col-sm-12">
                <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                <div class="dashboard-conetnt-wrp">
                    <div class="page-header-area">
                        <span class="page-heading down-arrow"><?php $this->load->view('manage_employer/company_logo_name'); ?></span>
                    </div>
                    <div style="position: relative;">                       
                    
                    <div class="col-md-6 col-xs-12">
                <p class="csF16 csB7 jsToggleHelpArea ma10 dn" data-help="assigned_reviews"><i class="fa fa-info-circle csF16 csB7" aria-hidden="true"></i>&nbsp;<em style="color: #cc0000;">The provided feedback will be shared with the reporting manager(s).</em></p>
            </div>
            <div class="col-md-6 col-xs-12">
                <span class="pull-right dn">
                    <a href="" class="btn btn-black" title="Send a reminder email to the reviewer." placement="top">
                        <i class="fa fa-bell-o" aria-hidden="true"></i>&nbsp; Send A Reminder Email
                    </a>
                    <a href="" class="btn btn-orange"><i class="fa fa-print" aria-hidden="true"></i>&nbsp; Print</a>
                    <a href="" class="btn btn-orange"><i class="fa fa-download" aria-hidden="true"></i>&nbsp; Download</a>
                </span>
            </div>
            <div class="clearfix"></div>
            <br />
                    <?php $this->load->view("{$pp}reviews_list_blue",['panelHeading'=>$panelHeading]); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

