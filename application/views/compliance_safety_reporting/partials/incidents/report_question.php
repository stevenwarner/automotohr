<!-- Manage Documents -->
<div class="panel panel-default">
    <div class="panel-heading">
        <h1 class="panel-heading-text text-medium">
            <strong>Questions</strong>
        </h1>
    </div>
    <div class="panel-body">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                <div class="form-group autoheight">
                    <label class="auto-height">Department Safety Condition exists &nbsp; : <span class="required"
                            aria-required="true"></span></label>
                    <div class="hr-select-dropdown">
                        <select id="jsDepartments" name="departments[]" class="form-control" multiple>
                            <?php foreach ($departments as $v) { ?>
                                <option value="<?= $v['sid']; ?>"><?= $v['name']; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                <div class="form-group autoheight">
                    <label class="auto-height">Original Date of Non-Compliance : <span class="required required_0"
                            aria-required="true"></span></label>
                    <input id="jsComplianceDate" type="text" name="compliance_date" value="" data-require="0"
                        data-attr="0" class="form-control start_date related_0" aria-invalid="false" autocomplete="off"
                        readonly="">
                </div>
            </div>
        </div>
    </div>
</div>