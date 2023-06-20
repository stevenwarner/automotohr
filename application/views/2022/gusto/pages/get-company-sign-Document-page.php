<!--  -->
<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets') ?>/css/jquery-ui-datepicker-custom.css">
<script src="<?php echo base_url('assets') ?>/js/jquery.datetimepicker.js"></script>
<div class="container-fluid">
    <div class="csPageWrap">
        <div class="row">
            <!-- left sidebar -->
            <?php $this->load->view('payroll/pages/sidebar', ['mainIndex'=> "sign_documents", "subIndex" =>""]);?>
            <!-- Main Content -->
            <div class="col-md-9 col-sm-12">
                <!-- Heading -->
                <div class="row">
                    <div class="col-sm-12">
                        <h1 class="csF18 csB7">
                            Sign Documents
                        </h1>
                    </div>
                </div>
                <!-- Body -->
                <div class="row">
                    <div class="col-sm-12">
                        <h1 class="csF18 csB7">
                            Sign Company Documents
                        </h1>
                        <p class="csF16">
                            please sign the documents below so we can pay your team and your taxes.
                        </p>
                    </div>
                </div>
                <br>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <caption></caption>
                        <thead>
                            <tr>
                                <th scope="col" class="csBG1 csF16 csB7 csW">Name</th>
                                <th scope="col" class="csBG1 csF16 csB7 csW">Description</th>
                                <th scope="col" class="csBG1 csF16 csB7 csW">Action</th>
                            </tr>
                        </thead>
                        <tbody id="jsDataBody">

                        </tbody>
                    </table>
                </div>
                <br>
                <div class="row">
                    <div class="col-sm-12 text-right">
                        <button class="btn btn-black csF16 csB7 jsSignDocumentCancel">
                            <i class="fa fa-arrow-circle-o-left" aria-hidden="true"></i>&nbsp;
                            Back
                        </button>
                        <button class="btn btn-orange csF16 csB7 jsPayrollSaveCompanySignDocuments">
                            <i class="fa fa-save" aria-hidden="true"></i>&nbsp;
                            <span id="jsSaveBtnTxt">Save & continue</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Modal -->
<div id="JsSignaturemodal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header modal-header-bg">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Signature Required</h4>
            </div>
            <div id="document_modal_body" class="modal-body">
                <h4 class="modal-title">Direct Deposit Authorization</h4>
                <br>
                <input type="hidden" value="" id="document_uuid">
                <div class="row">
                    <div class="col-md-12 col-xs-12">
                        <label class="csF16 csB7">
                            Signature <span class="csRequired"></span>
                        </label>
                        <input type="text" class="form-control jsSignature" value="" placeholder="Jone Doe" />
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-12 col-xs-12">
                        <label class="control control--checkbox">
                            <input type="checkbox" class="jsAgreeOnSign" /> I agree to electronically sign this form.
                            <div class="control__indicator"></div>
                        </label>
                    </div>
                </div>
            </div>
            <div id="document_modal_footer" class="modal-footer">
                <button class="btn btn-black csF16 csB7 close" data-dismiss="modal">
                    Cancel
                </button>
                <button class="btn btn-orange csF16 csB7 jsSaveSign">
                    sign
                </button>
            </div>
        </div>
    </div>
</div>


