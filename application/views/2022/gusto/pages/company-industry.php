<!--  -->
<div class="container-fluid">
    <div class="csPageWrap">
        <div class="row">
            <!-- left sidebar -->
            <?php $this->load->view('payroll/pages/sidebar', ['mainIndex'=> "company", "subIndex" =>"industry"]);?>
            <!-- Main Content -->
            <div class="col-md-9 col-sm-12">
                <!-- Heading -->
                <div class="row">
                    <div class="col-sm-12">
                        <h1 class="csF18 csB7">
                            Industry
                        </h1>
                    </div>
                </div>
                <!-- Body -->
                <div class="row">
                    <div class="col-sm-12">
                        <h1 class="csF18 csB7">
                            Select your company's industry
                        </h1>
                        <p class="csF16">
                            Select the industry that most closely matches your company's. Think you fall between industries? Choose the one that best represents your primary busniss activity (this is usually the activity that generates the most income for your company).
                        </p>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-12 col-xs-12">
                        <select class="form-control jsCompanyIndustry">
                        <option value="0">[Select]</option>
                            <?php foreach($industries as $industry): ?>
                                <option value="<?=$industry['sid'];?>" <?php echo $industry['sid'] === '7' ? 'selected="selected"' : '';?>><?=$industry['industry_name'];?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-sm-12 text-right">
                        <button class="btn btn-orange csF16 csB7 jsSaveCompanyIndustry">
                            <i class="fa fa-save" aria-hidden="true"></i>&nbsp;
                            Save
                        </button>
                        <button class="btn btn-orange csF16 csB7 jsPayrollConfirmContinue" data-id="3">
                            <i class="fa fa-check-circle-o" aria-hidden="true"></i>&nbsp;
                            Confirm
                        </button>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
