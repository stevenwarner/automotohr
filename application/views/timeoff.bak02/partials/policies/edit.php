<div class="js-page p10" id="js-page-edit" style="display: none;">
    <div class="js-top-bar">
        <div class="row mg-lr-0">
            <div class="border-top-section border-bottom">
                <div class="col-xs-6 col-lg-6">
                    <div class="pto-top-heading-left">
                        <p>Edit Policy<strong id="jsPolicyTitleEdit"></strong></p>
                    </div>
                </div>
                <div class="col-xs-6 col-lg-6">
                    <div class="pto-top-heading-right text-right pr0">
                        <div class="">
                            <button type="button" class="dashboard-link-btn2 cs-btn-add jsViewPoliciesBtn"><span><i
                                        class="fa fa-arrow-circle-left"></i></span>&nbsp;VIEW POLICIES</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="js-content-area">
        <div class="row" style="background-color: #eee; margin: 0 0 10px 0; padding: 5px;">
            <div class="col-sm-6 pl0">
                <p style="margin-top: 5px; font-weight: 700;" class="jsEditResetText">The policy for current year.</p>
            </div>
            <div class="col-sm-6 pr0">
                <span class="pull-right">
                    <button class="btn btn-success jsEditResetCheckbox" data-type="cp">Current Policy</button>
                    <button class="btn btn-default jsEditResetCheckbox" data-type="rp">On Reset Policy</button>
                    <!-- <input type="checkbox" class="jsEditResetCheckbox" checked data-toggle="toggle" data-on="Current" data-off="On Reset" -->
                        <!-- data-onstyle="success" data-offstyle="danger" /> -->
                </span>
            </div>
        </div>
        <?php $this->load->view('timeoff/policy_edit'); ?>
    </div>
</div>