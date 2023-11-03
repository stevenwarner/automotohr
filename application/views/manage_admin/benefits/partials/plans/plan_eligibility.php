<div class="panel panel-default" id="jsPlanEligibilitySection">
    <div class="panel-heading">
        <div class="row">
            <div class="col-sm-6 col-xs-12">
                <h1 class="csF16 m0" style="padding-top: 10px;">
                    <i class="fa fa-users" aria-hidden="true"></i>&nbsp;
                    <strong class="jsPlanStepHeading">
                        Add Eligibility and Cost
                    </strong>
                </h1>
            </div>
        </div>
    </div>
    <div class="panel-body">
        <p class="text-danger"><em><strong>Which employees are eligible for this plan, and how much will it cost?</strong></em></p>
        <hr>
        <h1 class="csF18 text-success"><strong><i class="fa fa-users"></i> Eligibility Groups</strong></h1>
        <p>Who is eligible for this plan?</p>

        <div id="jsEligibilityGroupSection">
            <div class="panel panel-default jsEligibleGroup">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-sm-12 col-xs-12">
                            <h1 class="csF16 m0" style="padding-top: 10px;">
                                <i class="fa fa-users" aria-hidden="true"></i>&nbsp;
                                <strong class="jsPlanStepHeading">
                                    Group 1
                                    <a href="#" class="dn jsRemoveGroup pull-right">
                                        <i class="fa fa-trash" aria-hidden="true"></i>
                                    </a>
                                </strong>
                            </h1>
                        </div>
                    </div>
                </div>
                <div class="panel-body">
                    <form action="">
                        <!--  -->
                        <div class="form-group">
                            <label class="csF16">Which employees are eligible? <a href="#">0 selected</a></label>
                        </div>

                        <!--  -->
                        <div class="form-group">
                            <label class="csF16">When do new employees become eligible? <strong class="text-danger">*</strong></label>
                            <select class="form-control jsEligiblity">
                                <option value="">On a manually entered date</option>
                                <option value="">Immediately upon hire</option>
                                <option value="">After a waiting period</option>
                                <option value="">First of the month following period</option>
                            </select>
                        </div>

                        <!--  -->
                        <div class="form-group">
                            <label class="csF16">When do terminated employees lose eligibility?</label>
                            <select class="form-control jsType">
                                <option value="">On a manually entered date</option>
                                <option value="">Day following termination</option>
                                <option value="">1st of the month following terminated</option>
                            </select>
                        </div>

                        <hr>

                        <!--  -->
                        <div class="form-group">
                            <label class="csF16">How much will they pay?</label>
                            <p class="text-danger"><em><strong>This number is provided by your carrier and can be found in your account structure document.</strong></em></p>
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <caption></caption>
                                    <thead>
                                        <tr>
                                            <th scope="col">Coverage Level</th>
                                            <th scope="col">Total Cost</th>
                                            <th scope="col">Employee Pays</th>
                                            <th scope="col">Company Pays</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="vam">
                                                Employee
                                            </td>
                                            <td class="vam">
                                                <input type="text" class="form-control jsTotalCost" />
                                            </td>
                                            <td class="vam">
                                                <nav aria-label="Page navigation example">
                                                    <ul class="pagination">
                                                        <li class="page-item"><a class="page-link" href="#"><i class="fa fa-usd" aria-hidden="true"></i></a></li>
                                                        <li class="page-item"><a class="page-link" href="#"><i class="fa fa-percent" aria-hidden="true"></i></a></li>
                                                    </ul>
                                                </nav>
                                            </td>
                                            <td class="vam jsCompanyShare">
                                                -
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="vam">
                                                Employee + Spouse
                                            </td>
                                            <td class="vam">
                                                <input type="text" class="form-control jsTotalCost" />
                                            </td>
                                            <td class="vam">
                                                <nav aria-label="Page navigation example">
                                                    <ul class="pagination">
                                                        <li class="page-item"><a class="page-link" href="#"><i class="fa fa-usd" aria-hidden="true"></i></a></li>
                                                        <li class="page-item"><a class="page-link" href="#"><i class="fa fa-percent" aria-hidden="true"></i></a></li>
                                                    </ul>
                                                </nav>
                                            </td>
                                            <td class="vam jsCompanyShare">
                                                -
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="vam">
                                                Employee + Children
                                            </td>
                                            <td class="vam">
                                                <input type="text" class="form-control jsTotalCost" />
                                            </td>
                                            <td class="vam">
                                                <nav aria-label="Page navigation example">
                                                    <ul class="pagination">
                                                        <li class="page-item"><a class="page-link" href="#"><i class="fa fa-usd" aria-hidden="true"></i></a></li>
                                                        <li class="page-item"><a class="page-link" href="#"><i class="fa fa-percent" aria-hidden="true"></i></a></li>
                                                    </ul>
                                                </nav>
                                            </td>
                                            <td class="vam jsCompanyShare">
                                                -
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="vam">
                                                Employee + Family
                                            </td>
                                            <td class="vam">
                                                <input type="text" class="form-control jsTotalCost" />
                                            </td>
                                            <td class="vam">
                                                <nav aria-label="Page navigation example">
                                                    <ul class="pagination">
                                                        <li class="page-item"><a class="page-link" href="#"><i class="fa fa-usd" aria-hidden="true"></i></a></li>
                                                        <li class="page-item"><a class="page-link" href="#"><i class="fa fa-percent" aria-hidden="true"></i></a></li>
                                                    </ul>
                                                </nav>
                                            </td>
                                            <td class="vam jsCompanyShare">
                                                -
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="csF16">How much will they pay?</label>
                            <p class="text-danger"><em><strong>As a Variable Rate plan, you will enter costs during each employee's enrollment.</strong></em></p>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <a href="#" id="jsAddAnOtherGroup"><strong><i class="fa fa-plus-circle"></i> Add a Group</strong></a>
        <p>For employees with different eligibility</p>
    </div>
</div>