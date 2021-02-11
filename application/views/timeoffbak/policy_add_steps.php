<div class="jsPageStep jsBasic" data-step="jsBasic" data-type="add">
    <!--  -->
    <div class="row mb10">
        <div class="col-sm-12">
            <h4 class="timeline-title allowed-time-off-title-custom csHeading">Basic Settings</h4>
        </div>
    </div>

    <!-- Policy Type - Edit -->
    <div class="row mb10" id="js-policy-categories-add">
        <div class="col-md-6 offset-md-3">
            <div class="form-group margin-bottom-custom">
                <label>Type <span class="cs-required">*</span> <i data-toggle="popovers" data-trigger="hover"
                        data-title="Info" data-placement="right" data-html="true"
                        data-content="<?php echo $get_policy_item_info['type_info']; ?>"
                        class="fa fa-question-circle"></i></label>
                <div>
                    <select id="js-category-add"></select>
                </div>
            </div>
        </div>
    </div>

    <!-- Policy Ttile - Edit -->
    <div class="row mb10">
        <div class="col-sm-6 col-xs-12">
            <div class="form-group margin-bottom-custom">
                <div class="form-group margin-bottom-custom">
                    <label class="">Policy Title <span class="cs-required">*</span> <i data-toggle="popovers"
                            data-trigger="hover" data-title="Info" data-placement="right" data-html="true"
                            data-content="<?php echo $get_policy_item_info['policy_title_info']; ?>"
                            class="fa fa-question-circle"></i></label>
                    <div>
                        <input class="invoice-fields" name="policyTitle" id="js-policy-title-add"
                            placeholder="Sick leave" />
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6">
            <p>The name of the policy</p>
        </div>
    </div>
</div>