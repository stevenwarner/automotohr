<!-- Basic -->
<div class="tab-pane <?= $this->input->get("tab", true) == "overview" || !$this->input->get("tab", true) ? "active" : ""; ?>"
    id="tab-overview" role="tabpanel">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h1 class="panel-heading-text text-medium">
                        <strong>
                            <i class="fa fa-info-circle text-orange"></i>
                            Report
                        </strong>
                    </h1>
                </div>
                <form action="">
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <tbody>
                                    <tr>
                                        <th class="vam" scope="col">
                                            Report Title
                                        </th>
                                        <td class="vam">
                                            <input type="text" class="form-control report_title" name="report_title"
                                                value="<?= $report["title"]; ?>" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="vam" scope="col">
                                            Report Type
                                        </th>
                                        <td class="vam">
                                            <input type="text" disabled class="form-control report_type"
                                                name="report_type" value="<?= $report["compliance_report_name"]; ?>" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="vam" scope="col">
                                            Report Date
                                        </th>
                                        <td class="vam">
                                            <input type="date" class="form-control report_date" name="report_date"
                                                value="<?= $report["report_date"]; ?>" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="vam" scope="col">
                                            Report Completion Date
                                        </th>
                                        <td class="vam">
                                            <input type="date" class="form-control report_completion_date"
                                                name="report_completion_date"
                                                value="<?= $report["completion_date"]; ?>" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="vam" scope="col">
                                            Report Status
                                        </th>
                                        <td class="vam">
                                            <select name="report_status" id="" class="form-control report_status">
                                                <option <?= $report["status"] == "pending" ? "selected" : ""; ?>
                                                    value="pending">In Progress</option>
                                                <option <?= $report["status"] == "on_hold" ? "selected" : ""; ?>
                                                    value="on_hold">On Hold</option>
                                                <option <?= $report["status"] == "completed" ? "selected" : ""; ?>
                                                    value="completed">Completed</option>
                                            </select>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="panel-footer text-right">
                        <button type="submit" class="btn btn-orange jsReportBasicUpdateBtn">
                            <i class="fa fa-save"></i>
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
        <div class="col-sm-4">
            <div id="jsProgressGraphs"></div>
        </div>
        <div class="col-sm-4">
            <div id="jsSeverityGraphs"></div>
        </div>
    </div>
</div>