<!-- AutomotoHR's Company -->
<div class="row">
    <div class="col-sm-12">
        <label class=""><strong>Company Name</strong></label>
        <select id="jsCICompanyName" disabled>
            <option value="0"><?=$companyName;?></option>
        </select>
    </div>
</div>
<!-- ComplyNet Companies -->
<div class="row">
    <div class="col-sm-12">
        <label class=""><strong>ComplyNet Companies</strong></label>
        <select id="jsCIComplyNetCompanies">
            <?php foreach ($complyCompanies as $company): ?>
            <option value="<?=$company['Id'];?>"><?=$company['Name'];?></option>
            <?php endforeach ; ?>
        </select>
    </div>
</div>
<!-- ComplyNet Locations -->
<div class="row">
    <div class="col-sm-12">
        <label class=""><strong>ComplyNet Company Locations</strong></label>
        <select id="jsCIComplyNetLocations"></select>
    </div>
</div>