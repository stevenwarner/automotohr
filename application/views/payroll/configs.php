<div class="container-fluid">
    <div class="csPageWrap">
        <!-- Header -->
        <?php $this->load->view('payroll/navbar'); ?>
        <br>
        <!-- Main Content Area -->
        <div class="row">
            <!--  -->
            <div class="col-sm-12">
                <!--  -->
                <div class="">
                    <h3>Payroll Settings</h3>
                </div>
                <div class="">
                    <hr />
                    <!--  -->
                    <div class="row">
                        <div class="col-xs-12">
                            <label class="csF16">Payment Speed <span class="csRequired"></span></label>
                            <select id="jsPayrollSettingsSpeed">
                                <option <?=$payroll_settings['payment_speed'] == '2-day' ? 'selected' : '';?> value="2-day">2 days</option>
                                <option <?=$payroll_settings['payment_speed'] == '4-day' ? 'selected' : '';?> value="4-day">4 days</option>
                            </select>
                        </div>
                    </div>
                    <!--  -->
                    <!-- <div class="row">
                        <br>
                        <div class="col-xs-12">
                            <label class="csF16">Fast Payment Limit <span class="csRequired jsRequiredLimit <?=$payroll_settings['payment_speed'] == '2-day' ? '' : 'dn';?>"></span></label>
                            <p>Payment limit only applicable for 2-day payroll</p>
                            <input type="text" class="form-control" placeholder="5000" id="jsPayrollSettingsLimit" value="<?=$payroll_settings['fast_payment_limit'];?>"/>
                        </div>
                    </div> -->

                    <!--  -->
                    <div class="row">
                        <br>
                        <div class="col-xs-12 text-right">
                            <button class="btn btn-orange jsPayrollSettingsSaveBtn">
                                <i class="fa fa-edit" aria-hidden="true"></i>&nbsp;Update
                            </button>
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