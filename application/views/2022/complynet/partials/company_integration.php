<!-- AutomotoHR's Company -->
<div class="row">
    <div class="col-sm-12">
        <label class=""><strong>Company Name</strong></label>
        <select id="jsCICompanyName" disabled style="width: 100%;">
            <option value="0"><?=$companyName;?></option>
        </select>
    </div>
</div>
<br>
<!-- ComplyNet Companies -->
<div class="row">
    <div class="col-sm-12">
        <label class=""><strong>ComplyNet Companies</strong></label>
        <select id="jsCIComplyNetCompanies" style="width: 100%;">
            <option value="0">[Select ComplyNet Company]</option>
            <?php foreach ($complyCompanies as $company): ?>
                <option value="<?=$company['Id'];?>"><?=$company['Name'];?></option>
            <?php endforeach ; ?>
        </select>
    </div>
</div>
<br>
<!-- ComplyNet Locations -->
<div class="row hidden">
    <div class="col-sm-12">
        <label class=""><strong>ComplyNet Company Locations</strong></label>
        <select id="jsCIComplyNetLocations" style="width: 100%;"></select>
    </div>
</div>
<!--  -->
<div class="row hidden jsCIBTNBox">
    <hr>
    <div class="col-sm-12 text-right">
        <button class="btn btn-black jsModalCancel">Cancel</button>
        <button class="btn btn-success jsCISubmit">Submit</button>
    </div>
</div>