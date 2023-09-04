<div class="panel panel-success">
    <div class="panel-heading">
        <h3 class="csW" style="margin-top: 0; margin-bottom: 0">
            <strong> Home address</strong>
        </h3>
    </div>
    <div class="panel-body">
        <h4 style="margin-top: 0">
            <strong>Employee home address</strong>
        </h4>
        <p class="text-danger csF16">
            <strong>
                <em>
                    Employee’s home mailing address, within the United States.
                </em>
            </strong>
        </p>

        <br>
        <form action="javascript:void(0)">
            <!--  -->
            <div class="form-group">
                <label class="csF16">Street 1 <strong class="text-danger">*</strong></label>
                <input type="text" class="form-control input-lg jsEmployeeFlowStreet1" placeholder="PO BOX 123" value="<?= $record['Location_Address']; ?>" />
            </div>

            <!--  -->
            <div class="form-group">
                <label class="csF16">Street 2</label>
                <input type="text" class="form-control input-lg jsEmployeeFlowStreet2" placeholder="PO BOX 123" value="<?= $record['Location_Address_2']; ?>" />
            </div>

            <!--  -->
            <div class="form-group">
                <label class="csF16">City <strong class="text-danger">*</strong></label>
                <input type="text" class="form-control input-lg jsEmployeeFlowCity" placeholder="New York" value="<?= $record['Location_City']; ?>" />
            </div>

            <!--  -->
            <div class="form-group">
                <label class="csF16">State <strong class="text-danger">*</strong></label>
                <input type="text" class="form-control input-lg jsEmployeeFlowState" placeholder="NY" value="<?= $record['state_code']; ?>" />
            </div>

            <!--  -->
            <div class="form-group">
                <label class="csF16">Zip <strong class="text-danger">*</strong></label>
                <input type="text" class="form-control input-lg jsEmployeeFlowZip" placeholder="123456" value="<?= $record['Location_ZipCode']; ?>" />
            </div>

        </form>
    </div>
    <div class="panel-footer text-right">
        <button class="btn csBG3 csF16 jsEmployeeFlowSaveHomeAddressBtn">
            <i class="fa fa-save csF16"></i>
            <span>Save & continue</span>
        </button>
    </div>
</div>