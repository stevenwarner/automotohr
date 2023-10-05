<div class="container">
    <br />
    <div class="panel panel-success">
        <div class="panel-heading">
            <h1 class="csF16 csW m0">
                <strong>
                    Add a garnishment
                </strong>
            </h1>
        </div>
        <div class="panel-body">
            <!--  -->
            <div class="row">
                <div class="col-sm-12 text-right">
                    <button class="btn csW csBG4 csF16 jsViewGarnishment">
                        <i class="fa fa-long-arrow-left csF16" aria-hidden="true"></i>
                        &nbsp;Back to garnishments
                    </button>
                </div>
            </div>

            <br />

            <form action="">
                <!--  -->
                <div class="form-group">
                    <label class="csF16">
                        Amount&nbsp;
                        <strong class="text-danger">
                            *
                        </strong>
                    </label>
                    <p class="csF12 text-danger">
                        <strong>
                            The amount of the garnishment. Either a percentage or a fixed dollar amount. Represented as a float, e.g. "8.00".
                        </strong>
                    </p>
                    <div class="input-group">
                        <div class="input-group-addon jsGarnishmentAmountSymbol">$</div>
                        <input type="number" class="form-control jsGarnishmentAmount" placeholder="0.00">
                    </div>
                </div>

                <!--  -->
                <div class="form-group">
                    <label class="csF16">
                        Description&nbsp;
                        <strong class="text-danger">
                            *
                        </strong>
                    </label>
                    <p class="csF12 text-danger">
                        <strong>
                            The description of the garnishment.
                        </strong>
                    </p>
                    <input type="text" class="form-control jsGarnishmentDescription" placeholder="Back taxes">
                </div>

                <!--  -->
                <div class="form-group">
                    <label class="csF16">
                        Court ordered&nbsp;
                        <strong class="text-danger">
                            *
                        </strong>
                    </label>
                    <p class="csF12 text-danger">
                        <strong>
                            Whether the garnishment is court ordered.
                        </strong>
                    </p>
                    <select class="form-control jsGarnishmentCourtOrdered">
                        <option value="no">No</option>
                        <option value="yes">Yes</option>
                    </select>
                </div>

                <!--  -->
                <div class="form-group">
                    <label class="csF16">
                        Times
                    </label>
                    <p class="csF12 text-danger">
                        <strong>
                            The number of times to apply the garnishment. Ignored if recurring is true.
                        </strong>
                    </p>
                    <input type="number" class="form-control jsGarnishmentTimes" placeholder="1">
                </div>

                <!--  -->
                <div class="form-group">
                    <label class="csF16">
                        Recurring
                    </label>
                    <p class="csF12 text-danger">
                        <strong>
                            Whether the garnishment should recur indefinitely.
                        </strong>
                    </p>
                    <select class="form-control jsGarnishmentRecurring">
                        <option value="no">No</option>
                        <option value="yes">Yes</option>
                    </select>
                </div>

                <!--  -->
                <div class="form-group">
                    <label class="csF16">
                        Annual maximum
                    </label>
                    <p class="csF12 text-danger">
                        <strong>
                            The maximum deduction per annum. A null value indicates no maximum. Represented as a float, e.g. "200.00".
                        </strong>
                    </p>
                    <input type="number" class="form-control jsGarnishmentAnnualMaximum" placeholder="200.00">
                </div>

                <!--  -->
                <div class="form-group">
                    <label class="csF16">
                        Pay period maximum
                    </label>
                    <p class="csF12 text-danger">
                        <strong>
                            The maximum deduction per pay period. A null value indicates no maximum. Represented as a float, e.g. "16.00".
                        </strong>
                    </p>
                    <input type="number" class="form-control jsGarnishmentPayPeriodMaximum" placeholder="16.00">
                </div>

                <!--  -->
                <div class="form-group">
                    <label class="csF16">
                        Deduct as percentage
                    </label>
                    <p class="csF12 text-danger">
                        <strong>
                            Whether the amount should be treated as a percentage to be deducted per pay period.
                        </strong>
                    </p>
                    <select class="form-control jsGarnishmentDeductAsPercentage">
                        <option value="no">No</option>
                        <option value="yes">Yes</option>
                    </select>
                </div>

                <!--  -->
                <div class="form-group">
                    <label class="csF16">
                        Active
                    </label>
                    <p class="csF12 text-danger">
                        <strong>
                            Whether or not this garnishment is currently active.
                        </strong>
                    </p>
                    <select class="form-control jsGarnishmentActive">
                        <option value="yes">Yes</option>
                        <option value="no">No</option>
                    </select>
                </div>

                <!--  -->
                <div class="form-group text-right">
                    <button type="button" class="btn csW csBG4 csF16 jsViewGarnishment">
                        <i class="fa fa-long-arrow-left csF16" aria-hidden="true"></i>
                        &nbsp;Back to garnishments
                    </button>
                    <button type="button" class="btn csW csBG3 csF16 jsSaveGarnishment">
                        <i class="fa fa-save csF16" aria-hidden="true"></i>
                        &nbsp;Save
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>