<div class="container-fluid">
    <div class="csPageWrap">
        <!-- Header -->
        <?php $this->load->view('payroll/navbar'); ?>
        <br />
        <div class="row">
            <div class="col-sm-12 text-right" style="padding-right:20px ! important;">
                <button class="btn btn-success jsSyncCompany"><i class="fa fa-refresh"></i> Sync</button>
                <button class="btn btn-success jsSendTestDeposits"><i class="fa fa-bank"></i> Send Test Deposits</button>
                <button class="btn btn-success jsApproveCompany"><i class="fa fa-check"></i> Approve Company</button>
                <button class="btn btn-success jsFinishCompanyOnboard"><i class="fa fa-check-circle"></i> Finish Onboarding</button>
            </div>
        </div>
        <br />
        <!-- Main Content Area -->
        <div class="row">
            <!--  -->
            <div class="col-sm-12">
                <div class="">
                    <!--  -->
                    <div class="row">
                        <div class="col-xs-12">
                            <iframe src="<?=$onboarding_link;?>" style="width: 100%; height: 630px;" frameborder="0" title="Onboarding"></iframe>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add System Model -->
<link rel="stylesheet" href="<?= base_url(_m("assets/css/SystemModel", 'css')); ?>">
<script src="<?= base_url(_m("assets/js/SystemModal")); ?>"></script>