
<!-- Main -->
<div class="mainContent">
    <div class="csPR">
        <?php $this->load->view('loader_new', ['id' => 'company_pay_period']); ?>
        <!--  -->
        <div class="row">
            <div class="col-md-12 col-xs-12 text-right">
                <button class="btn btn-success csF16 csB7 jsAddPayPeriod"><i class="fa fa-eye csF16" aria-hidden="true"></i>&nbsp;Add A Pay Pariod</button>
            </div>
        </div>
        <!--  -->
        <div class="row">
            <div class="col-md-12 col-xs-12">
                <p class=" csF16"><i class="fa fa-info-circle csF16" aria-hidden="true"></i>&nbsp;Be sure to check <a href="https://www.dol.gov/agencies/whd/state/payday" target="_blank" class="csInfo csB7">state laws</a> to know what schedule is right for your employees.</p>
            </div>
        </div>
        <!-- -->
        <div class="row">
            <div class="col-md-12">
                <table class="table table-striped">
                    <caption></caption>
                    <thead>
                        <tr>
                            <th class="csBG1 csF16 csB7" scope="col">Frequency&nbsp;<i class="fa fa-info-circle csCP" aria-hidden="true" title="The frequency that employees on this pay schedule are paid." placement="top"></i></th>
                            <th class="csBG1 csF16 csB7 text-right" scope="col">Anchor Pay Date&nbsp;<i class="fa fa-info-circle csCP" aria-hidden="true" title="The first date that employees on this pay schedule are paid." placement="top"></i></th>
                            <th class="csBG1 csF16 csB7 text-right" scope="col">Anchor Pay End Date&nbsp;<i class="fa fa-info-circle csCP" aria-hidden="true" title="The last date of the first pay period. This can be the same date as the anchor pay date.." placement="top"></i></th>
                            <th class="csBG1 csF16 csB7 text-right" scope="col">Day 1&nbsp;<i class="fa fa-info-circle csCP" aria-hidden="true" title="An integer between 1 and 31 indicating the first day of the month that employees are paid. This field is only relevant for pay schedules with the “Twice per month” and “Monthly” frequencies. It will be null for pay schedules with other frequencies." placement="top"></i></th>
                            <th class="csBG1 csF16 csB7 text-right" scope="col">Day 2&nbsp;<i class="fa fa-info-circle csCP" aria-hidden="true" title="An integer between 1 and 31 indicating the second day of the month that employees are paid. This field is the second pay date for pay schedules with the “Twice per month” frequency. It will be null for pay schedules with other frequencies." placement="top"></i></th>
                            <th class="csBG1 csF16 csB7 text-right" scope="col">Status</th>
                            <th class="csBG1 csF16 csB7 text-right" scope="col">Id / Version</th>
                            <th class="csBG1 csF16 csB7 text-right" scope="col">Last Modified</th>
                        </tr>
                    </thead>
                    <tbody id="jsPayPeriodBody"></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    window.API_URL = "<?=getAPIUrl('pay_period');?>"; 
</script>