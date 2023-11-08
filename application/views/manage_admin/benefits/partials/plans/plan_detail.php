<div class="panel panel-default" id="jsPlanDetailSection">
    <div class="panel-heading">
        <div class="row">
            <div class="col-sm-6 col-xs-12">
                <h1 class="csF16 m0" style="padding-top: 10px;">
                    <i class="fa fa-clipboard" aria-hidden="true"></i>&nbsp;
                    <strong class="jsPlanStepHeading">
                        Add a Plan Details
                    </strong>
                </h1>
            </div>
        </div>
    </div>
    <div class="panel-body">
        <p>How will you explain this plan to your employees?.</p>
        <form action="">
            <!--  -->
            <div class="form-group">
                <label class="csF16">Plan Name <strong class="text-danger">*</strong></label>
                <input type="text" class="form-control jsPlanName" />
            </div>

            <!--  -->
            <div class="form-group">
                <label class="csF16">Carriers <strong class="text-danger">*</strong></label>
                <select class="form-control jsCarrier">
                    <option value="">Select</option>
                    <?php foreach ($carriers as $carrier) { ?>
                        <option value="<?= $carrier['sid']; ?>"><?= $carrier['name']; ?></option>
                    <?php } ?>
                </select>
            </div>

            <!--  -->
            <div class="form-group">
                <label class="csF16">Group Number <strong class="text-danger">*</strong></label>
                <p class="text-danger"><em><strong>This number is provided by your carrier and can be found in your account structure document.</strong></em></p>
                <input type="text" class="form-control jsCarrierNumber" readonly />
            </div>

            <!--  -->
            <div class="form-group">
                <label class="csF16">Plan Types <strong class="text-danger">*</strong></label>
                <select class="form-control jsPlanType">
                    <option value="">Select</option>
                    <option value="EPO">EPO</option>
                    <option value="POS">POS</option>
                    <option value="Indemnity">Indemnity</option>
                    <option value="HMO">HMO</option>
                    <option value="PPO">PPO</option>
                </select>
            </div>

            <!--  -->
            <div class="form-group">
                <label class="csF16">Plan Type ID<strong class="text-danger">*</strong></label>
                <p class="text-danger"><em><strong>This number is provided by your carrier and can be found in your account structure document.</strong></em></p>
                <input type="text" class="form-control jsPlanTypeId" />
            </div>

            <!--  -->
            <div class="form-group">
                <label class="csF16">Summary <strong class="text-danger">*</strong></label>
                <input type="text" class="form-control jsPlanSummary" />
            </div>

            <!--  -->
            <div class="form-group">
                <label class="csF16">Description</label>
                <textarea class="form-control jsDescription" rows="10"></textarea>
            </div>

            <!--  -->
            <div class="input-group">
                <div class="row">
                    <div class="col-sm-6 col-md-6 col-xs-12">
                        <label>Plan Start <strong class="text-danger">*</strong></label>
                        <input type="text" class="form-control csRBC" id="jsPlanStartDate"  placeholder="MM/DD/YYYY" readonly />
                    </div>
                    <div class="col-sm-6 col-md-6 col-xs-12">
                        <label>Plan End <strong class="text-danger">*</strong></label>
                        <input type="text" class="form-control csRBC" id="jsPlanEndDate"  placeholder="MM/DD/YYYY" readonly />
                    </div>
                </div>
                <br />
            </div>

            <!--  -->
            <div class="form-group">
                <label class="csF16">Rate <strong class="text-danger">*</strong></label>
                <select class="form-control jsRate">
                    <option value="">Select</option>
                    <option value="standard">Standard (Composite rate)</option>
                    <option value="variable">Variable (e.g. Age-based rate)</option>
                </select>
            </div>

            <hr>

            <h1 class="csF18"><strong>Attachments</strong></h1>

            <!--  -->
            <div class="form-group">
                <label class="csF16">Main Plan URL <strong class="text-danger">*</strong></label>
                <p class="text-danger"><em><strong>We recommend adding the URL that your employees use to access their account information or another page that you think would be most useful.</strong></em></p>
                <input type="text" class="form-control jsMainPlanURL" />
            </div>

            <!--  -->
            <div class="form-group">
                <div class="input-group jsAdditionalLinkSection">
                    
                </div>
                <a href="#" id="jsAddAnOtherlink"><strong><i class="fa fa-plus-circle"></i> Add Another link</strong></a>
            </div>

            <div class="form-group">
                <label class="csF16">Attached File(s) </label>
                <input type="file" name="attachment" id="jsPlanAttachmentUpload" class="hidden" />
            </div>
        </form>
    </div>
</div>

<style>
    #ui-datepicker-div{
        z-index: 1000 !important;
    }
</style>