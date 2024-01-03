<div class="container">
    <form action="javascript:void(0)" id="jsPagePayScheduleForm">
        <!--  -->
        <div class="panel panel-default">
            <div class="panel-heading">
                <h2 class="text-medium panel-heading-text">
                    <i class="fa fa-edit text-orange" aria-hidden="true"></i>
                    Manage Job & Wage
                </h2>
            </div>
            <div class="panel-body">
                <div class="form-group">
                    <label class="text-medium">
                        Employment type
                        <strong class="text-red">
                            *
                        </strong>
                    </label>
                    <select name="employment_type" class="form-control">
                        <option value="fulltime">Full time</option>
                        <option value="parttime">Part time</option>
                        <option value="contractual">Contractual</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="text-medium">
                        FLSA status
                        <strong class="text-red">
                            *
                        </strong>
                        <p class="text-red text-small">

                            The FLSA status for this compensation. <br>
                            Salaried ('Exempt') employees are paid a fixed salary every pay period. <br>
                            Salaried with overtime ('Salaried Nonexempt') employees are paid a fixed salary every pay period, and receive overtime pay when applicable.<br>
                            Hourly ('Nonexempt') employees are paid for the hours they work, and receive overtime pay when applicable.<br>
                            Owners ('Owner') are employees that own at least twenty percent of the company.
                        </p>
                    </label>
                    <select name="flsa_status" class="form-control">
                        <option value="Exempt">Salary/No overtime</option>
                        <option value="Salaried Nonexempt">Salary/Eligible for overtime</option>
                        <option selected="" value="Nonexempt">Paid by the hour</option>
                        <option value="Owner">Owner's draw</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="text-medium">
                        Per
                        <strong class="text-red">
                            *
                        </strong>
                        <p class="text-red text-small">
                            The unit accompanying the compensation rate. If the employee is an owner, rate should be 'Paycheck'.
                        </p>
                    </label>
                    <select name="per" class="form-control">
                        <option value="Hour">Per hour</option>
                        <option value="Week">Per week</option>
                        <option value="Month">Per month</option>
                        <option value="Year">Per year</option>
                        <option value="Paycheck">Per paycheck</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="text-medium">
                        Hire date
                        <strong class="text-red">
                            *
                        </strong>
                    </label>
                    <input type="text" class="form-control readonly" name="hire_date" readonly placeholder="MM/DD/YYYY" />
                </div>

                <div class="form-group">
                    <label class="text-medium">
                        Rate
                        <strong class="text-red">
                            *
                        </strong>
                    </label>
                    <input type="number" class="form-control readonly" name="rate" placeholder="0.00" />
                </div>
                <div class="form-group">
                    <label class="text-medium">
                        Overtime Rule
                        <strong class="text-red">
                            *
                        </strong>
                    </label>
                    <select name="overtime_rule" class="form-control"></select>
                </div>
                <div class="form-group">
                    <label class="text-medium">
                        Minimum wages
                        <strong class="text-red">
                            *
                        </strong>
                    </label>
                    <select name="minimum_wages[]" class="form-control" multiple></select>
                </div>
            </div>
            <div class="panel-footer text-center">
                <button class="btn btn-orange jsPagePayScheduleBtn">
                    <i class="fa fa-edit" aria-hidden="true"></i>
                    Update
                </button>
                <button class="btn btn-black jsModalCancel">
                    <i class="fa fa-times-circle" aria-hidden="true"></i>
                    Cancel
                </button>
            </div>
        </div>
    </form>
</div>