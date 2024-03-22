<form action="javascript:void(0)" id="jsPageCreateUnavailabilityForm" autocomplete="off">
    <div class="container">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h2 class="text-medium panel-heading-text weight-6">
                    <i class="fa fa-save text-orange" aria-hidden="true"></i>
                    Create Unavailability
                </h2>
            </div>
            <div class="panel-body">
                <!--  -->
                <div class="form-group">
                    <label class="control control--checkbox">
                        <input type="checkbox" class="jsUnavailableAllDay" checked />
                        Unavailable all day
                        <div class="control__indicator"></div>
                    </label>
                </div>

                <!--  -->
                <div class="form-group">
                    <label class="text-medium">
                        Date
                        <strong class="text-red">*</strong>
                    </label>
                    <input type="text" class="form-control jsUnavailableDate" readonly />
                </div>

                <!--  -->
                <div class="row hidden" id="jsAddUnavailableTime">
                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 jsHoursContainer">
                        <div class="row jsHoursRow">
                            <div class="col-lg-1 col-md-1 col-xs-12 col-sm-1">
                                <label class="text-medium">
                                    From
                                    <strong class="text-red">*</strong>
                                </label>
                            </div>
                            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                                <div class="form-group">
                                    <input type="text" class="form-control jsTimeField jsStartTime" placeholder="HH:MM" aria-invalid="false">
                                </div>
                            </div>
                            <div class="col-lg-1 col-md-1 col-xs-12 col-sm-1">
                                <label class="text-medium">
                                    To
                                    <strong class="text-red">*</strong>
                                </label>
                            </div>
                            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                                <div class="form-group">
                                    <input type="text" class="form-control jsTimeField jsEndTime" placeholder="HH:MM">
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4">
                                <button class="btn btn-orange jsAddHours">
                                    <i class="fa fa-plus-circle" aria-hidden="true"></i>
                                    Add Hours
                                </button>
                            </div>    
                        </div>
                    </div>
                </div>        

                <!--  -->
                <div class="form-group">
                    <label class="text-medium">
                        Note
                    </label>
                    <textarea name="notes" rows="5" class="form-control jsNote"></textarea>
                </div>

                <!--  -->
                <hr>

                <!--  -->
                <div class="form-group">
                    <label class="control control--checkbox">
                        <input type="checkbox" class="jsRepeat" />
                        Repeat
                        <div class="control__indicator"></div>
                    </label>
                </div>

                <div class="panel panel-default hidden jsRepeatSection">
                    <div class="panel-heading">
                        <h3 class="csF16 m0">
                            <strong>
                                Starting from <span id="jsSelectedDate"></span>
                            </strong>
                        </h3>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4">
                                <div class="form-group">
                                    <div class="hr-select-dropdown">
                                        <select class="form-control" id="jsRepeatType">
                                            <option value="daily">Daily</option>
                                            <option value="weekly">Weekly</option>
                                            <option value="monthly">Monthly</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4 jsDailyRepeat jsRepeatType">
                                <div class="input-group">
                                    <span class="input-group-btn">
                                        <button class="btn btn-default" type="button">Every</button>
                                    </span>
                                    <div class="hr-select-dropdown">
                                        <select class="form-control" name="type" id="jsEveryDaily">
                                            <option value="1">1</option>
                                            <option value="2">2</option>
                                            <option value="3">3</option>
                                            <option value="4">4</option>
                                            <option value="5">5</option>
                                            <option value="6">6</option>
                                            <option value="7">7</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4 jsDailyRepeat jsRepeatType">
                                <div class="form-group">
                                    <label for="Days">Days</label>
                                </div>
                            </div>

                            <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4 jsWeeklyRepeat jsRepeatType hidden">
                                <div class="input-group">
                                    <span class="input-group-btn">
                                        <button class="btn btn-default" type="button">Every</button>
                                    </span>
                                    <div class="hr-select-dropdown">
                                        <select class="form-control" name="type" id="jsEveryWeekly">
                                            <option value="1">1</option>
                                            <option value="2">2</option>
                                            <option value="3">3</option>
                                            <option value="4">4</option>
                                            <option value="5">5</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4 jsWeeklyRepeat jsRepeatType hidden">
                                <div class="form-group">
                                    <label for="Weeks">Weeks</label>
                                </div>
                            </div>

                            <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4 jsMonthlyRepeat jsRepeatType hidden">
                                <div class="input-group">
                                    <span class="input-group-btn">
                                        <button class="btn btn-default" type="button">Every</button>
                                    </span>
                                    <div class="hr-select-dropdown">
                                        <select class="form-control" name="type" id="jsEveryMonthly">
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
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4 jsMonthlyRepeat jsRepeatType hidden">
                                <div class="form-group">
                                    <label for="Months">Months</label>
                                </div>
                            </div>
                        </div>

                        <hr class="jsWeeklyMonthlySectionSeparator hidden">

                        <div class="row jsWeeklyMonthlySection jsWeeklySection hidden">
                            <div class="col-lg-1 col-md-1 col-xs-12 col-sm-1">
                                <div class="form-group">
                                    <label for="On">On</label>
                                </div>
                            </div>
                            <div class="col-lg-11 col-md-11 col-xs-12 col-sm-11">
                                <label class="control control--checkbox">
                                    <input type="checkbox" class="jsUnavailableWeekDay" value="7" />
                                    Sunday
                                    <div class="control__indicator"></div>
                                </label>
                                <label class="control control--checkbox">
                                    <input type="checkbox" class="jsUnavailableWeekDay" value="1" checked />
                                    Monday
                                    <div class="control__indicator"></div>
                                </label>
                                <label class="control control--checkbox">
                                    <input type="checkbox" class="jsUnavailableWeekDay" value="2" checked />
                                    Tuesday
                                    <div class="control__indicator"></div>
                                </label>
                                <label class="control control--checkbox">
                                    <input type="checkbox" class="jsUnavailableWeekDay" value="3" checked />
                                    Wednesday
                                    <div class="control__indicator"></div>
                                </label>
                                <label class="control control--checkbox">
                                    <input type="checkbox" class="jsUnavailableWeekDay" value="4" checked />
                                    Thursday
                                    <div class="control__indicator"></div>
                                </label>
                                <label class="control control--checkbox">
                                    <input type="checkbox" class="jsUnavailableWeekDay" value="5" checked />
                                    Friday
                                    <div class="control__indicator"></div>
                                </label>
                                <label class="control control--checkbox">
                                    <input type="checkbox" class="jsUnavailableWeekDay" value="6" />
                                    Saturday
                                    <div class="control__indicator"></div>
                                </label>
                            </div>
                        </div>
                        <div class="row jsWeeklyMonthlySection jsMonthlySection hidden">
                            <div class="col-lg-2 col-md-2 col-xs-12 col-sm-2">
                                <div class="form-group">
                                    <label for="On">Repeats on</label>
                                </div>
                            </div>
                            <div class="col-lg-10 col-md-10 col-xs-12 col-sm-10">
                                <div class="form-group">
                                    <div class="hr-select-dropdown">
                                        <select class="form-control multipleSelect" name="type[]" id="jsMonthlyFrequency" multiple>
                                            <option value="First Sunday">First Sunday</option>
                                            <option value="First Monday">First Monday</option>
                                            <option value="First Tuesday">First Tuesday</option>
                                            <option value="First Wednesday">First Wednesday</option>
                                            <option value="First Thursday">First Thursday</option>
                                            <option value="First Friday">First Friday</option>
                                            <option value="First Saturday">First Saturday</option>
                                            <option value="Second Sunday">Second Sunday</option>
                                            <option value="Second Monday">Second Monday</option>
                                            <option value="Second Tuesday">Second Tuesday</option>
                                            <option value="Second Wednesday">Second Wednesday</option>
                                            <option value="Second Thursday">Second Thursday</option>
                                            <option value="Second Friday">Second Friday</option>
                                            <option value="Second Saturday">Second Saturday</option>
                                            <option value="Third Sunday">Third Sunday</option>
                                            <option value="Third Monday">Third Monday</option>
                                            <option value="Third Tuesday">Third Tuesday</option>
                                            <option value="Third Wednesday">Third Wednesday</option>
                                            <option value="Third Thursday">Third Thursday</option>
                                            <option value="Third Friday">Third Friday</option>
                                            <option value="Third Saturday">Third Saturday</option>
                                            <option value="Last Sunday">Last Sunday</option>
                                            <option value="Last Monday">Last Monday</option>
                                            <option value="Last Tuesday">Last Tuesday</option>
                                            <option value="Last Wednesday">Last Wednesday</option>
                                            <option value="Last Thursday">Last Thursday</option>
                                            <option value="Last Friday">Last Friday</option>
                                            <option value="Last Saturday">Last Saturday</option>
                                            <option value="Last Day">End Of Month</option>
                                            <option value="1">1st</option>
                                            <option value="2">2nd</option>
                                            <option value="3">3rd</option>
                                            <option value="4">4th</option>
                                            <option value="5">5th</option>
                                            <option value="6">6th</option>
                                            <option value="7">7th</option>
                                            <option value="8">8th</option>
                                            <option value="9">9th</option>
                                            <option value="10">10th</option>
                                            <option value="11">11th</option>
                                            <option value="12">12th</option>
                                            <option value="13">13th</option>
                                            <option value="14">14th</option>
                                            <option value="15">15th</option>
                                            <option value="16">16th</option>
                                            <option value="17">17th</option>
                                            <option value="18">18th</option>
                                            <option value="19">19th</option>
                                            <option value="20">20th</option>
                                            <option value="21">21st</option>
                                            <option value="22">22nd</option>
                                            <option value="23">23rd</option>
                                            <option value="24">24th</option>
                                            <option value="25">25th</option>
                                            <option value="26">26th</option>
                                            <option value="27">27th</option>
                                            <option value="28">28th</option>
                                            <option value="29">29th</option>
                                            <option value="30">30th</option>
                                            <option value="31">31st</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr>

                        <div class="row">
                            <div class="col-lg-2 col-md-2 col-xs-12 col-sm-2">
                                <div class="form-group">
                                    <label for="">End repeat</label>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                                <div class="form-group">
                                    <div class="hr-select-dropdown">
                                        <select class="form-control" name="type" id="jsOccurrencesType">
                                            <option value="after">After</option>
                                            <option value="on">On</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-2 col-xs-12 col-sm-2">
                                <div class="form-group">
                                    <input type="number" class="form-control" id="jsOccurrences" value="1">
                                </div>
                            </div>
                            <div class="col-lg-5 col-md-5 col-xs-12 col-sm-5">
                                <div class="form-group">
                                    <label for="occurrences">occurrences</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--  -->
            <div class="panel-footer text-right">
                <button class="btn btn-orange jsPageCreateUnavailabilityBtn">
                    <i class="fa fa-save" aria-hidden="true"></i>
                    &nbsp;Save Unavailability
                </button>
                <button class="btn btn-black jsModalCancel" type="button">
                    <i class="fa fa-times-circle" aria-hidden="true"></i>
                    &nbsp;Cancel
                </button>
            </div>
        </div>
    </div>
</form>