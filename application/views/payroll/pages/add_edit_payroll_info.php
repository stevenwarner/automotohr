<!--  -->
<div class="container-fluid">
    <div class="csPageWrap">
        <div class="row">
            <!-- left sidebar -->
            <?php $this->load->view('payroll/pages/sidebar', ['mainIndex'=> "payroll", "subIndex" =>""]);?>
            <!-- Main Content -->
            <div class="col-md-9 col-sm-12">
                <!-- Heading -->
                <div class="row">
                    <div class="col-sm-12">
                        <h1 class="csF18 csB7">
                            Payroll setup
                        </h1>
                    </div>
                </div>
                <!-- Body -->
                <div class="row">
                    <div class="col-sm-12">
                        <h1 class="csF18 csB7">
                            Pay schedule
                        </h1>
                        <p class="csF16">
                            Enter the pay schedule for your employees. This will be a combination of their pay period and pay date. We use this info to know how much to pay your employees and when.
                        </p>
                        <p class="csF16">
                            Be sure to <a href="https://www.dol.gov/agencies/whd/state/payday" target="_blank"><b>check state laws</b></a> check state laws to know what schedule is right for your customers.
                        </p>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-sm-12">
                        <p class="csF16">
                            Fields marked with an asterisk (<span class="csRequired"></span>) are mandatory.
                        </p>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-12 col-xs-12">
                        <label class="csF16 csB7">
                            Pay frequency <span class="csRequired"></span>
                        </label>
                        <p>
                            How often your employees are paid.
                        </p>
                        <select class="form-control jsPayFrequency">
                            <option value="0">Please select pay frequency</option>
                            <option value="Every week">Every week</option>
                            <option value="Every other week">Every other week</option>
                            <option value="Twice per month">Twice per month</option>
                            <option value="Monthly">Monthly</option>
                            <option value="Quarterly">Quarterly</option>
                        </select>
                    </div>
                </div>
                <br>
                <div class="row jsFrequencyDays">
                    <div class="col-md-12 col-xs-12">
                        <label class="csF16 csB7">
                            Day of week <span class="csRequired"></span>
                        </label>
                        <p>
                            The day they get paid.
                        </p>
                        <select class="form-control jsDayOfWeek">
                            <option value="0">Please select week day</option>
                            <option value="1">Monday</option>
                            <option value="2">Tuesday</option>
                            <option value="3">Wednesday</option>
                            <option value="4">Thursday</option>
                            <option value="5">Friday</option>
                        </select>
                    </div>
                </div>
                <div class="row jsTwicePerMonth">
                    <div class="col-md-12 col-xs-12">
                        <label class="csF16 csB7">
                            Monthly pay days <span class="csRequired"></span>
                        </label>
                        <div class="radio">
                            <label class="gusto-label" for="auto-id-35">
                                <input type="radio" id="jsDefaultSemimonthly" value="default" checked="checked" class="gusto-radio-input" name="default_semimonthly_pay_days">
                                <i class=""></i> 15th and last day of month
                            </label>
                        </div>
                        <div class="radio">
                            <label class="gusto-label" for="auto-id-35">
                                <input type="radio" id="jsOtherSemimonthly" value="other" class="gusto-radio-input" name="default_semimonthly_pay_days">
                                <i class=""></i> Other
                            </label>
                        </div>
                    </div>
                </div>
                <div class="row jsOtherHalfMonthCycle">
                    <div class="col-md-12 col-xs-12">
                        <label class="csF16 csB7">
                            First pay day of month <span class="csRequired"></span>
                        </label>
                        <p>
                            This is the day of the month that you would like your employees to be paid.
                        </p>
                        <select class="form-control jsFirstPayDay jsOthertPayDay">
                            <option value="0">Please select a day</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>
                            <option value="11">11</option>
                            <option value="12">12</option>
                            <option value="13">13</option>
                            <option value="14">14</option>
                            <option value="15">15</option>
                            <option value="16">16</option>
                            <option value="17">17</option>
                        </select>
                    </div>
                </div>  
                <br>
                <div class="row jsOtherHalfMonthCycle">  
                    <div class="col-md-12 col-xs-12">
                        <label class="csF16 csB7">
                            Second pay day of month <span class="csRequired"></span>
                        </label>
                        <select class="form-control jsSecondPayDay jsOthertPayDay">
                        <option value="0">Please select a day</option>
                            <option value="14">14</option>
                            <option value="15">15</option>
                            <option value="16">16</option>
                            <option value="17">17</option>
                            <option value="18">18</option>
                            <option value="19">19</option>
                            <option value="20">20</option>
                            <option value="21">21</option>
                            <option value="22">22</option>
                            <option value="23">23</option>
                            <option value="24">24</option>
                            <option value="25">25</option>
                            <option value="26">26</option>
                            <option value="27">27</option>
                            <option value="28">28</option>
                            <option value="29">29</option>
                            <option value="30">30</option>
                            <option value="31">Last day of month</option>
                        </select>
                    </div> 
                </div>
                <div class="row jsMonthlyCycle">
                    <div class="col-md-12 col-xs-12">
                        <label class="csF16 csB7">
                            Day of month <span class="csRequired"></span>
                        </label>
                        <p>
                            This is the day of the month that you would like your employees to be paid.
                        </p>
                        <select class="form-control jsDayOfMonth">
                            <option value="0">Please select a day</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>
                            <option value="11">11</option>
                            <option value="12">12</option>
                            <option value="13">13</option>
                            <option value="14">14</option>
                            <option value="15">15</option>
                            <option value="16">16</option>
                            <option value="17">17</option>
                            <option value="18">18</option>
                            <option value="19">19</option>
                            <option value="20">20</option>
                            <option value="21">21</option>
                            <option value="22">22</option>
                            <option value="23">23</option>
                            <option value="24">24</option>
                            <option value="25">25</option>
                            <option value="26">26</option>
                            <option value="27">27</option>
                            <option value="28">28</option>
                            <option value="29">29</option>
                            <option value="30">30</option>
                            <option value="last_day_of_month">Last day of month</option>
                        </select>
                    </div>
                </div>
                <div class="row jsQuarterlyCycle">
                    <div class="col-md-12 col-xs-12">
                        <label class="csF16 csB7">
                            First month <span class="csRequired"></span>
                        </label>
                        <p>
                            Select the first month they will get paid. The next pay date will occur 3 months after the first.
                        </p>
                        <select class="form-control jsUpcomingMonth">

                        </select>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-12 col-xs-12">
                        <label class="csF16 csB7">
                            First pay date on new schedule <span class="csRequired"></span>
                        </label>
                        <p>
                            The first paycheck they will receive with Gusto.
                        </p>
                        <select class="form-control jsWeekCalendar">
                        </select>
                    </div>
                </div>
                <br>
                <div id="jsPayrollDeadline" class="form-group">
                    <label class="csF16 csB7" for="payroll-deadline">Deadline to run payroll</label>
                    <div class="controls padding-top-2x jsPayrollDeadlineDate"></div>
                </div>
                <br>
                <div id="jsPayrollPayPeriod" class="row">
                    <div class="col-md-12 col-xs-12">
                        <label class="csF16 csB7">
                            Pay period
                        </label>
                        <p>
                            This is the time period your employees worked.
                        </p>
                        <select class="form-control jsPayPeriods">
                        </select>
                    </div>
                </div>
                <br>
                <div class="row jsPayrollPayPeriodOther">
                    <div class="col-md-12 col-xs-12">
                        <label class="csF16 csB7">
                            First pay period ends on
                        </label>
                        <p>
                            This is the last date of your company's first pay period. For work done during the pay period ending on this date, your employees will be paid on the above pay date.
                        </p>
                        <select class="form-control jsPayPeriodsOther">
                        </select>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-12 col-xs-12">
                        <p class="csF14">By selecting continue I acknowledge I won't be able to run payroll for up to 2 business days until the bank verification completes.</p>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-sm-12 text-right">
                        <button class="btn btn-black csF16 csB7 jsCompanyPayrollCancel">
                            <i class="fa fa-arrow-circle-o-left" aria-hidden="true"></i>&nbsp;
                            Back
                        </button>
                        <button class="btn btn-orange csF16 csB7 jsPayrollSave">
                            <i class="fa fa-save" aria-hidden="true"></i>&nbsp;
                            <span id="jsSaveBtnTxt">Save & continue</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
