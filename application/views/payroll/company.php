<div class="container-fluid">
    <div class="csPageWrap">
        <!-- Header -->
        <?php $this->load->view('payroll/navbar'); ?>
        <br />
        <!-- Main Content Area -->
        <div class="row">
            <!--  -->
            <div class="col-sm-12">
                <div class="">
                    <!--  -->
                    <div class="row">
                        <div class="col-xs-12">
                            <iframe src="<?=$onboarding_link;?>" style="width: 100%; height: 600px;" frameborder="0" title="Onboarding"></iframe>
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