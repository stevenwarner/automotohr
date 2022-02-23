
<!-- Main -->
<div class="mainContent">
    <div class="csPR">
        <?php $this->load->view('loader_new', ['id' => 'company_tax']); ?>
        <!--  -->
        <div class="row">
            <div class="col-md-12 col-xs-12 text-right">
                <?php if(checkIfAppIsEnabled("payroll")) { ?>
                    <button class="btn btn-success csF16 csB7 jsTaxRefresh"><i class="fa fa-refresh csF16" aria-hidden="true"></i>&nbsp;Refresh Status</button>
                <?php } ?>
                <button class="btn btn-success csF16 csB7 jsTaxHistory"><i class="fa fa-eye csF16" aria-hidden="true"></i>&nbsp;Show Tax History</button>
            </div>
        </div>
        <!--  -->
        <div class="row">
            <div class="col-md-12 col-xs-12 text-left">
                <label class="csF16">EIN Verified: <strong class="jsStatus">NO</strong></label>
                <p class="csF14 jsLastModified dn">Last modified by <strong class="jsLastModifiedPerson"></strong> on <strong class="jsLastModifiedTime"></strong></p>
            </div>
        </div>
        <br>
        <!--  -->
        <div class="row">
            <div class="col-md-12 col-xs-12">
                <p class="csF14 csB7">Fields marked with an asterisk (<span class="csRequired"></span>) are mandatory.</p>
            </div>
        </div>
        <!--  -->
        <div class="row">
            <div class="col-md-12 col-xs-12">
                <label class="csF16 csB7">
                    Legal Name <span class="csRequired"></span>
                </label>
                <p class="csF14 csInfo">The legal name of the company.</p>
                <input type="text" class="form-control jsTaxLegalName" placeholder="AutomotoHR" />
            </div>
        </div>
        <br>
        <!--  -->
        <div class="row">
            <div class="col-md-12 col-xs-12">
                <label class="csF16 csB7">
                    EIN <span class="csRequired"></span>
                </label>
                <p class="csF14 csInfo">The EIN of of the company.</p>
                <input type="text" class="form-control jsTaxEIN" placeholder="123456789" />
            </div>
        </div>
        <br>
        <!--  -->
        <div class="row">
            <div class="col-md-12 col-xs-12">
                <label class="csF16 csB7">
                    Tax Payer Type <span class="csRequired"></span>
                </label>
                <p class="csF14 csInfo">What type of tax entity the company is.</p>
                <select class="form-control jsTaxPayerType">
                    <option value="0">[Select]</option>
                    <option value="S-Corporation">S-Corporation</option>
                    <option value="C-Corporation">C-Corporation</option>
                    <option value="Sole proprietor">Sole Proprietor</option>
                    <option value="LLC">LLC</option>
                    <option value="LLP">LLP</option>
                    <option value="Limited partnership">Limited Partnership</option>
                    <option value="Co-ownership">Co-ownership</option>
                    <option value="Association">Association</option>
                    <option value="Trusteeship">Trusteeship</option>
                    <option value="General partnership">General Partnership</option>
                    <option value="Joint venture">Joint Venture</option>
                    <option value="Non-Profit">Non-Profit</option>
                </select>
            </div>
        </div>
        <br>
        <!--  -->
        <div class="row">
            <div class="col-md-12 col-xs-12">
                <label class="csF16 csB7">
                    Filling Form <span class="csRequired"></span>
                </label>
                <p class="csF14 csInfo">The form used by the company for federal tax filing.</p>
                <select class="form-control jsTaxFillingForm">
                    <option value="0">[Select]</option>
                    <option value="941">941 (Quarterly federal tax return)</option>
                    <option value="944">944 (Annual federal tax return)</option>
                </select>
            </div>
        </div>
        <br>
        <!--  -->
        <div class="row">
            <div class="col-md-12 col-xs-12">
                <label class="control control--checkbox csF16 csB7">Taxable As Scorp 
                    <input type="checkbox" class="jsTaxScorp" name="jsTaxScorp" />
                    <div class="control__indicator"></div>
                </label>
                <p class="csF14 csInfo">Whether the company is taxed as an S-Corporation.</p>
            </div>
        </div>
        <br>
        <!--  -->
        <div class="row">
            <div class="col-sm-12 text-right">
                <button class="btn btn-success csF16 csB7 jsTaxUpdateBtn">
                    <i class="fa fa-edit csF16" aria-hidden="true"></i>&nbsp;Update Company Tax
                </button>
            </div>
        </div>
        
    </div>
</div>

<script>
    window.API_URL = "<?=getAPIUrl('tax');?>"; 
</script>