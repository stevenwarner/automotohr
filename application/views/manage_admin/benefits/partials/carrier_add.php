<br>
<br>
<div class="container">
    <div class="panel panel-default">
        <div class="panel-heading">
            <div class="row">
                <div class="col-sm-6 col-xs-12">
                    <h1 class="csF16 m0" style="padding-top: 10px;">
                        <i class="fa fa-briefcase" aria-hidden="true"></i>&nbsp;
                        <strong>
                            We just need a few details...
                        </strong>
                    </h1>
                </div>
            </div>
        </div>
        <div class="panel-body">
            <p>The simplest way to verify who you are working with is to collect your carrier's Employer Identification Number. EINs can be found on your contract document.</p>
            <form action="">
                <!--  -->
                <div class="form-group">
                    <label class="csF16">Carrier EIN <strong class="text-danger">*</strong></label>
                    <p class="text-danger">
                        <em>
                            <strong>
                                Your carrier's Employer Identification # (EIN) helps us verify who they are so we can:
                                <ul>
                                    <li class="text-danger ml24">Prevent duplicate carriers within BambooHR</li>
                                    <li class="text-danger ml24">Potentially create future partnerships to automate plan creation and enrollment</li>
                                </ul>
                            </strong>
                        </em>
                    </p>
                    <input type="text" class="form-control jsCarrierEin" />
                </div>

                <!--  -->
                <div class="form-group">
                    <label class="csF16">What do you want to refer to this carrier as? <strong class="text-danger">*</strong></label>
                    <input type="text" class="form-control jsCarrierName" />
                </div>

                <!--  -->
                <div class="form-group">
                    <label class="csF16">Group Number <strong class="text-danger">*</strong></label>
                    <p class="text-danger"><em><strong>This number is provided by your carrier and can be found in your account structure document.</strong></em></p>
                    <input type="text" class="form-control jsCarrierNumber" />
                </div>

                <div class="form-group">
                    <label class="csF16">Carrier Logo <strong class="text-danger">*</strong></label>
                    <input type="file" name="attachment" id="jsCarrierAttachmentUpload" class="hidden" />
                </div>
            </form>
        </div>
        <div class="panel-footer text-right">
            <button class="btn csW csBG4 csF16 jsModalCancel">
                <i class="fa fa-times-circle csF16" aria-hidden="true"></i>
                &nbsp;Cancel
            </button>
            <button class="btn csW csBG3 csF16 jsBenefitCarrierSave">
                <i class="fa fa-save csF16" aria-hidden="true"></i>
                &nbsp;Save
            </button>
        </div>
    </div>
</div>