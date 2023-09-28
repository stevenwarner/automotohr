<br>
<br>
<div class="container">
    <div class="panel panel-default">
        <div class="panel-heading">
            <div class="row">
                <div class="col-sm-6 col-xs-12">
                    <h1 class="csF16 m0" style="padding-top: 10px;">
                        <strong>
                            Add a benefit
                        </strong>
                    </h1>
                </div>
            </div>
        </div>
        <div class="panel-body">

            <form action="">

                <!--  -->
                <div class="form-group">
                    <label class="csF16">Benefit Type <strong class="text-danger">*</strong></label>
                    <select class="form-control jsType">
                        <?php foreach ($categories as $category) { ?>
                            <option value="<?= $category['sid']; ?>"><?= $category['name']; ?></option>
                        <?php } ?>
                    </select>
                </div>

                <!--  -->
                <div class="form-group">
                    <label class="csF16">Name <strong class="text-danger">*</strong></label>
                    <input type="text" class="form-control jsName" />
                </div>

                <!--  -->
                <div class="form-group">
                    <label class="csF16">Description</label>
                    <textarea class="form-control jsDescription" rows="10"></textarea>
                </div>

                <!--  -->
                <div class="form-group">
                    <label class="csF16">Pretax <strong class="text-danger">*</strong></label>
                    <select class="form-control jsPretax">
                        <option value="no">No</option>
                        <option value="yes">Yes</option>
                    </select>
                </div>

                <!--  -->
                <div class="form-group">
                    <label class="csF16">Posttax <strong class="text-danger">*</strong></label>
                    <select class="form-control jsPosttax">
                        <option value="no">No</option>
                        <option value="yes">Yes</option>
                    </select>
                </div>

                <!--  -->
                <div class="form-group">
                    <label class="csF16">Imputed <strong class="text-danger">*</strong></label>
                    <select class="form-control jsImputed">
                        <option value="no">No</option>
                        <option value="yes">Yes</option>
                    </select>
                </div>

                <!--  -->
                <div class="form-group">
                    <label class="csF16">Healthcare <strong class="text-danger">*</strong></label>
                    <select class="form-control jsHealthcare">
                        <option value="no">No</option>
                        <option value="yes">Yes</option>
                    </select>
                </div>

                <!--  -->
                <div class="form-group">
                    <label class="csF16">Retirement <strong class="text-danger">*</strong></label>
                    <select class="form-control jsRetirement">
                        <option value="no">No</option>
                        <option value="yes">Yes</option>
                    </select>
                </div>

                <!--  -->
                <div class="form-group">
                    <label class="csF16">Yearly limit <strong class="text-danger">*</strong></label>
                    <select class="form-control jsYearlyLimit">
                        <option value="no">No</option>
                        <option value="yes">Yes</option>
                    </select>
                </div>
            </form>
        </div>
        <div class="panel-footer text-right">
            <button class="btn csW csBG4 csF16 jsModalCancel">
                <i class="fa fa-times-circle csF16" aria-hidden="true"></i>
                &nbsp;Cancel
            </button>
            <button class="btn csW csBG3 csF16 jsBenefitSave">
                <i class="fa fa-save csF16" aria-hidden="true"></i>
                &nbsp;Save
            </button>
        </div>
    </div>
</div>