<!-- Employee Performance Evaluation Group -->
<div class="col-md-12">
    <div class="row">
        <div class="col-xs-12">
            <div class="panel panel-default ems-documents">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapse_<?= EMPLOYEE_PERFORMANCE_EVALUATION_MODULE; ?>">
                            <span class="glyphicon glyphicon-plus"></span>
                            Employee Performance Evaluation
                            <div class="btn btn-xs btn-success">Fillable</div>
                            <div class="pull-right total-records">
                                <strong>
                                    Total: 1
                                </strong>
                            </div>
                        </a>
                    </h4>
                </div>
                <div id="collapse_<?= EMPLOYEE_PERFORMANCE_EVALUATION_MODULE; ?>" class="panel-collapse collapse">
                    <div class="table-responsive">
                        <table class="table table-plane">
                            <thead>
                                <tr>
                                    <th class="col-xs-6">
                                        Document Name
                                    </th>
                                    <th class="col-xs-6 text-right">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        Employee Performance Evaluation
                                    </td>
                                    <td class="text-right">
                                        <button class="btn btn-primary btn-sm jsBulkAssignEPE">
                                            Bulk Assign
                                        </button>
                                        <button class="btn btn-info btn-sm">
                                            Preview
                                        </button>
                                        <button class="btn btn-success btn-sm">
                                            View Employee(s)
                                        </button>
                                        <button class="btn btn-success btn-sm">
                                            Schedule Document
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
    $(function EmployeePerformanceEvaluation() {
        //
        $(".jsBulkAssignEPE").click(startBulkAssignProcess);
        /**
         * handles bulk assign
         */
        function startBulkAssignProcess(event) {
            event.preventDefault();
        }

        $(".jsBulkAssignEPE").trigger("click")
    });
</script>