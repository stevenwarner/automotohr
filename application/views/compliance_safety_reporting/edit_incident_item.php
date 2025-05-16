<div class="main jsmaincontent" style="position:relative">
    <?php $this->load->view('loader_new', ['id' => 'jsPageLoader']); ?>

    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <a href="<?php echo $employee['access_level'] == 'Employee' ? base_url('employee_management_system') : base_url('dashboard'); ?>"
                    class="btn btn-black">
                    <i class="fa fa-arrow-left"></i>
                    Dashboard
                </a>
            </div>
            <div class="col-sm-6 text-right">
                <?php if (isMainAllowedForCSP()): ?>
                    <a href="<?= base_url('compliance_safety_reporting/edit/' . $report_id) ?>" class="btn btn-black">
                        <i class="fa fa-arrow-left"></i>
                        Back To Report
                    </a>
                <?php endif; ?>
                <a href="<?= base_url('compliance_safety_reporting' . (isMainAllowedForCSP() ? "" : "/employee") . '/dashboard') ?>"
                    class="btn btn-blue">
                    <i class="fa fa-pie-chart"></i>
                    Compliance Safety Reporting
                </a>
            </div>

        </div>

        <div class="page-header">
            <h2 class="section-ttile">
                <i class="fa fa-exclamation-triangle"></i>
                <?= $report["title"]; ?>
            </h2>
        </div>

        <div class="row">
            <div class="col-md-6">
                <!-- Issue -->
                <div class="row">
                    <div class="col-sm-12">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h1 class="panel-heading-text text-medium">
                                    <strong>Issue Details</strong>
                                </h1>
                            </div>
                            <div class="panel-body">
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <tbody>
                                            <tr>
                                                <th class="vam" scope="col">
                                                    Report
                                                </th>
                                                <td class="vam">
                                                    <?= $report["report"]; ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th class="vam" scope="col">
                                                    Incident
                                                </th>
                                                <td class="vam">
                                                    <?= $report["incident_name"]; ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th class="vam" scope="col">
                                                    Status
                                                </th>
                                                <td class="vam">
                                                    <?= ucwords(str_replace("_", " ", $report["completion_status"])); ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th class="vam" scope="col">
                                                    Completed By
                                                </th>
                                                <td class="vam">
                                                    <?php if ($report["completion_status"] == "completed"): ?>
                                                        <span class="text-success">
                                                            <?= checkAndShowUser(
                                                                $report["completed_by"],
                                                                $report
                                                            ) ?>
                                                            <br>
                                                            <?= formatDateToDB($report["completion_date"], DB_DATE, DATE); ?>
                                                        </span>
                                                    <?php else: ?>
                                                        -
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th class="vam" scope="col">
                                                    Severity Level
                                                </th>
                                                <td class="vam">
                                                    <button type="button" class="btn"
                                                        style="background: <?= $report["bg_color"]; ?>; color: <?= $report["txt_color"]; ?>; border-radius: 5px;">
                                                        Severity Level <?= $report["level"]; ?>
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
            <div class="col-md-6">
                <!-- Issue -->
                <div class="row">
                    <div class="col-sm-12">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h1 class="panel-heading-text text-medium">
                                    <strong>Progress</strong>
                                </h1>
                            </div>
                            <div class="panel-body">
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <tbody>
                                            <tr>
                                                <th class="vam" scope="col">
                                                    Status
                                                </th>
                                                <td class="vam">
                                                    <select class="form-control" id="jsIssueStatus">
                                                        <option <?= $report["completion_status"] == "pending" ? "selected" : ""; ?> value="pending">Pending</option>
                                                        <option <?= $report["completion_status"] == "on_hold" ? "selected" : ""; ?> value="on_hold">On Hold</option>
                                                        <option <?= $report["completion_status"] == "completed" ? "selected" : ""; ?> value="completed">Completed</option>
                                                    </select>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th class="vam" scope="col">
                                                    Completion Date
                                                </th>
                                                <td class="vam">
                                                    <input type="date" class="form-control"
                                                        <?= $report["completion_status"] == "completed" ? "" : "disabled"; ?> id="jsIssueCompletionDate"
                                                        value="<?= ($report["completion_date"] ?? ""); ?>" />
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="panel-footer text-right">
                                <button class="btn btn-orange jsIssueProgressUpdateBtn">
                                    <i class="fa fa-save"></i>
                                    Update Issue
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--  -->

        <div class="row">
            <div class="col-sm-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h1 class="panel-heading-text text-medium">
                            <strong>Questions</strong>
                        </h1>
                    </div>
                    <div class="panel-body">
                        <?php
                        $decodedJSON = json_decode(
                            $report["question_answer_json"],
                            true
                        );
                        //
                        $report_to_dashboard = empty($decodedJSON['report_to_dashboard']) ? 'no' : $decodedJSON['report_to_dashboard'];
                        $ongoing_issue = empty($decodedJSON['ongoing_issue']) ? 'no' : $decodedJSON['ongoing_issue'];
                        $reported_by = empty($decodedJSON['reported_by']) ? 'no' : $decodedJSON['reported_by'];
                        $category_of_issue = empty($decodedJSON['category_of_issue']) ? '' : $decodedJSON['category_of_issue'];
                        ?>
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                <div class="form-group autoheight">
                                    <label>Report to Dashboard : <span class="required"
                                            aria-required="true"></span></label>
                                    <div class="row">
                                        <div class="col-lg-3 col-md-6 col-xs-12 col-sm-6">
                                            <label class="control control--radio">
                                                Yes<input type="radio" class="jsReportToDashboard"
                                                    name="report_to_dashboard" value="yes" style="position: relative;"
                                                    <?php echo $report_to_dashboard == 'yes' ? 'checked="checked"' : ''; ?>>
                                                <div class="control__indicator"></div>
                                            </label>
                                        </div>
                                        <div class="col-lg-3 col-md-6 col-xs-12 col-sm-6">
                                            <label class="control control--radio">
                                                No<input type="radio" class="jsReportToDashboard"
                                                    name="report_to_dashboard" value="no" style="position: relative;"
                                                    <?php echo $report_to_dashboard == 'no' ? 'checked="checked"' : ''; ?>>
                                                <div class="control__indicator"></div>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                <div class="form-group autoheight">
                                    <label>Is this a Repeat or Ongoing Issue? <span class="required"
                                            aria-required="true"></span></label>
                                    <div class="row">
                                        <div class="col-lg-3 col-md-6 col-xs-12 col-sm-6">
                                            <label class="control control--radio">
                                                Yes<input type="radio" class="jsOngoingIssue" name="ongoing_issue"
                                                    value="yes" style="position: relative;" <?php echo $ongoing_issue == 'yes' ? 'checked="checked"' : ''; ?>>
                                                <div class="control__indicator"></div>
                                            </label>
                                        </div>
                                        <div class="col-lg-3 col-md-6 col-xs-12 col-sm-6">
                                            <label class="control control--radio">
                                                No<input type="radio" class="jsOngoingIssue" name="ongoing_issue"
                                                    value="no" style="position: relative;" <?php echo $ongoing_issue == 'no' ? 'checked="checked"' : ''; ?>>
                                                <div class="control__indicator"></div>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                <div class="form-group autoheight">
                                    <label>Was this reported by an employee?: <span class="required"
                                            aria-required="true"></span></label>
                                    <div class="row">
                                        <div class="col-lg-3 col-md-6 col-xs-12 col-sm-6">
                                            <label class="control control--radio">
                                                Yes<input type="radio" class="jsReportedBy" name="reported_by"
                                                    value="yes" style="position: relative;" <?php echo $reported_by == 'yes' ? 'checked="checked"' : ''; ?>>
                                                <div class="control__indicator"></div>
                                            </label>
                                        </div>
                                        <div class="col-lg-3 col-md-6 col-xs-12 col-sm-6">
                                            <label class="control control--radio">
                                                No<input type="radio" class="jsReportedBy" name="reported_by" value="no"
                                                    style="position: relative;" <?php echo $reported_by == 'no' ? 'checked="checked"' : ''; ?>>
                                                <div class="control__indicator"></div>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                <div class="form-group autoheight">
                                    <label>Category of issue: <span class="required"
                                            aria-required="true"></span></label>
                                    <input id="jsCategoryOfIssue" type="text" name="category_of_issue"
                                        value="<?= $category_of_issue ?>" class="form-control">
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php if (isMainAllowedForCSP()): ?>
                        <div class="panel-footer text-right">
                            <button class="btn btn-orange jsIssueProgressQuestionBtn">
                                <i class="fa fa-save"></i>
                                Save Questions
                            </button>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!--  -->
        <div class="row">

            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                <!--  -->

                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h1 class="panel-heading-text text-medium">
                            <strong>Issue</strong>
                        </h1>
                    </div>
                    <div class="panel-body">
                        <?php
                        $item = $report["description"];
                        //
                        $decodedJSON = json_decode(
                            $report["answers_json"],
                            true
                        );
                        ?>
                        <div class="row">
                            <div class="col-md-12">
                                <?= $report["description"]
                                    ? convertCSPTags($report["description"], $decodedJSON ?? [], false)
                                    : $report["description"];
                                ?>
                            </div>
                            <!--  -->
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <hr />
                                <div class="jsDocumentsArea">
                                    <?php if ($report["documents"]): ?>
                                        <div class="row jsFirst">
                                            <?php foreach ($report["documents"] as $document): ?>
                                                <?php
                                                $style = '';
                                                //
                                                if ($document['file_type'] == 'image') {
                                                    $imageUrl = AWS_S3_BUCKET_URL . $document["s3_file_value"];
                                                    $style = "background-image: url('" . $imageUrl . "'); background-size: cover; background-repeat: no-repeat; background-position: center;";
                                                }

                                                ?>
                                                <div class="col-sm-3">
                                                    <div class="widget-box">
                                                        <div class="attachment-box full-width jsFileBox" style="<?= $style; ?>"
                                                            data-id="<?= $document["sid"]; ?>">
                                                            <h4 style="padding: 5px;" class="text-white">
                                                                <?= $document["title"]; ?>
                                                            </h4>
                                                            <p style=" margin-left: 5px;" class="text-white">
                                                                <small>

                                                                    <?= formatDateToDB($document['created_at'], DB_DATE_WITH_TIME, DATE); ?>
                                                                    <br>

                                                                    <?php
                                                                    if ($document['manual_email']) {
                                                                        echo getManualUserNameByEmailId($reportId, $incidentId, $document['manual_email']);
                                                                    } else {
                                                                        echo remakeEmployeeName($document);
                                                                    }
                                                                    ?>
                                                                </small>
                                                            </p>
                                                            <div class="status-panel">
                                                                <div class="row">
                                                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                                        <button class="btn btn-info jsViewFile">
                                                                            <i class="fa fa-eye"></i>
                                                                        </button>
                                                                        <a target="_blank"
                                                                            href="<?= base_url("compliance_safety_reporting/file/download/" . $document["sid"]); ?>"
                                                                            class="btn btn-info btn-info">
                                                                            <i class="fa fa-download"></i>
                                                                        </a>
                                                                        <button class="btn btn-info jsDeleteFile"
                                                                            data-file_id="<?php echo $document["sid"]; ?>"
                                                                            data-file_type="<?php echo $document['file_type']; ?>">
                                                                            <i class="fa fa-trash"></i>
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <div class="jsAudioArea">
                                    <?php if ($report["audios"]): ?>
                                        <div class="row jsFirst">
                                            <?php foreach ($report["audios"] as $file): ?>
                                                <div class="col-sm-3">
                                                    <div class="widget-box">
                                                        <div class="attachment-box full-width jsFileBox"
                                                            data-id="<?= $file["sid"]; ?>">
                                                            <h4 style="padding: 5px;" class="text-white">
                                                                <?= $file["title"]; ?>
                                                            </h4>
                                                            <p style=" margin-left: 5px;" class="text-white">
                                                                <small>

                                                                    <?= formatDateToDB($file['created_at'], DB_DATE_WITH_TIME, DATE); ?>
                                                                    <br>

                                                                    <?php
                                                                    if ($file['manual_email']) {
                                                                        echo getManualUserNameByEmailId($reportId, $incidentId, $file['manual_email']);
                                                                    } else {
                                                                        echo remakeEmployeeName($file);
                                                                    }
                                                                    ?>
                                                                </small>
                                                            </p>
                                                            <div class="status-panel">
                                                                <div class="row">
                                                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                                        <button class="btn btn-info jsViewFile">
                                                                            <i class="fa fa-eye"></i>
                                                                        </button>
                                                                        <?php
                                                                        if ($file["file_type"] != "link"): ?>
                                                                            <a target="_blank"
                                                                                href="<?= base_url("compliance_safety_reporting/file/download/" . $file["sid"]); ?>"
                                                                                class="btn btn-info btn-info">
                                                                                <i class="fa fa-download"></i>
                                                                            </a>
                                                                        <?php endif; ?>

                                                                        <button class="btn btn-info jsDeleteFile"
                                                                            data-file_id="<?php echo $file["sid"]; ?>"
                                                                            data-file_type="<?php echo $file['file_type']; ?>">
                                                                            <i class="fa fa-trash"></i>
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <!-- end -->
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Documents -->
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h1 class="panel-heading-text text-medium">
                            <strong>Upload Files</strong>
                        </h1>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label for="document_title">Title <strong class="text-danger">*</strong></label>
                                    <input type="text" class="form-control" id="document_title" name="document_title" />
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <input type="file" class="hidden" id="report_documents" name="report_documents" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel-footer text-right">
                        <button class="btn btn-orange jsAddDocument" type="button">
                            <i class="fa fa-plus"></i>
                            Add File
                        </button>
                    </div>
                </div>


                <?php $this->load->view("compliance_safety_reporting/partials/files/emails"); ?>

                <!-- Add Notes -->
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h1 class="panel-heading-text text-medium">
                            <strong>Add New Note</strong>
                        </h1>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label for="report_note_type">Type <strong class="text-danger">*</strong></label>
                                    <select name="report_note_type" id="report_note_type">
                                        <option value="personal">Personal Note</option>
                                        <option value="employee">Employee Note</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label for="report_note">Note <strong class="text-danger">*</strong></label>
                                    <textarea class="form-control" id="report_note" name="report_note"
                                        rows="5"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel-footer text-right">
                        <button class="btn btn-orange jsAddNote">
                            <i class="fa fa-plus-circle"></i>
                            Add Note
                        </button>
                    </div>
                </div>

                <!-- Notes -->
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h1 class="panel-heading-text text-medium">
                            <strong>Notes</strong>
                        </h1>
                    </div>
                    <div class="panel-body">
                        <div class="respond">
                            <?php if (!empty($report['notes'])): ?>
                                <?php foreach ($report['notes'] as $note): ?>
                                    <article>
                                        <figure>
                                            <img class="img-responsive" src="<?= getImageURL($note["profile_picture"]) ?>">
                                        </figure>
                                        <div class="text">
                                            <div class="message-header">
                                                <div class="message-title">
                                                    <h2>
                                                        <?php
                                                        if ($note['manual_email']) {
                                                            echo getManualUserNameByEmailId($reportId, $incidentId, $note['manual_email']);
                                                        } else {
                                                            echo remakeEmployeeName($note);
                                                        }
                                                        ?>
                                                    </h2>
                                                    <p class="text-danger"><?= ucfirst($note['note_type']); ?></p>
                                                </div>
                                                <ul class="message-option">
                                                    <li>
                                                        <time><?= formatDateToDB($note['updated_at'], DB_DATE_WITH_TIME, DATE); ?></time>
                                                    </li>
                                                </ul>
                                            </div>
                                            <p><?= $note["notes"]; ?></p>
                                        </div>
                                    </article>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <div class="alert alert-info text-center">
                                    No notes found.
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <?php if (isMainAllowedForCSP()): ?>
                    <!-- Add Notes -->
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h1 class="panel-heading-text text-medium">
                                <strong>Add Department</strong>
                            </h1>
                        </div>
                        <div class="panel-body">
                            <?php if (empty($departments)) { ?>
                                <div class="row" style="margin: 5px 5px;">
                                    <div class="col-lg-12" style="font-weight: 700; color: red;">Kindly ensure the department is
                                        added to the report before proceeding.</div>
                                </div>
                            <?php } ?>
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                    <div class="form-group autoheight">
                                        <label>Departments</label>
                                        <select name="departments[]" id="jsDepartments" multiple>
                                            <?php
                                            //
                                            $allowedDepartments = empty($report['allowed_departments']) ? [] : $report['allowed_departments'];
                                            //
                                            if (!empty($departments)) {
                                                foreach ($departments as $v) {
                                                    ?>
                                                    <option value="<?= $v['sid']; ?>" <?= in_array($v['sid'], $allowedDepartments) ? 'selected' : ''; ?>><?= $v['name']; ?></option>
                                                    <?php
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <br />

                            <!-- Teams -->
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                    <div class="form-group autoheight">
                                        <label>Teams</label>
                                        <select name="teams[]" id="jsTeams" multiple>
                                            <?php
                                            //
                                            $allowedTeams = empty($report['allowed_teams']) ? [] : $report['allowed_teams'];
                                            //
                                            if (!empty($teams)) {
                                                foreach ($teams as $v) {
                                                    ?>
                                                    <option value="<?= $v['sid']; ?>" <?= in_array($v['sid'], $allowedTeams) ? 'selected' : ''; ?>><?= $v['name']; ?></option>
                                                    <?php
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="panel-footer text-right">
                            <button class="btn btn-orange jsAddDepartmentsAndTeams">
                                <i class="fa fa-plus-circle"></i>
                                Save Changes
                            </button>
                            <?php if (!empty($allowedDepartments) || !empty($allowedTeams)) { ?>
                                <button class="btn btn-red jsRemoveDepartmentsAndTeams">
                                    <i class="fa fa-refresh"></i>
                                    Reset Department & Team
                                </button>
                            <?php } ?>
                        </div>
                    </div>

                    <div class="">
                        <!-- Employees -->
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h1 class="panel-heading-text text-medium">
                                    <strong>Internal Employees</strong>
                                </h1>
                            </div>
                            <div class="panel-body">
                                <?php if ($employees):
                                    $selectedEmployees = array_column($report["internal_employees"], "employee_sid"); ?>
                                    <div class="row">
                                        <?php foreach ($employees as $employee): ?>
                                            <div class="col-lg-4">
                                                <label class="control control--checkbox">
                                                    <input type="checkbox" name="report_employees[]" class="jsInternalEmployees"
                                                        value="<?= $employee["sid"]; ?>" <?= in_array($employee["sid"], $selectedEmployees) ? "checked" : ""; ?> />
                                                    <div class="control__indicator"></div>
                                                    <span><?= remakeEmployeeName($employee); ?></span>
                                                </label>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php else: ?>
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <p class="text-danger">No employees found.</p>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="panel-footer text-right">
                                <button class="btn btn-orange jsUpdateItemBtn" data-type="internal">
                                    <i class="fa fa-save"></i>
                                    Save Changes
                                </button>
                            </div>
                        </div>

                        <!-- Employees -->
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <h1 class="panel-heading-text text-medium">
                                            <strong>
                                                External Employees
                                            </strong>
                                        </h1>

                                    </div>
                                    <div class="col-sm-6 text-right">
                                        <button class="btn btn-orange jsAddExternalEmployee">
                                            <i class="fa fa-plus"></i>
                                            Add External Employee
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="panel-body jsAddExternalBody">
                                <?php if ($report["external_employees"]): ?>
                                    <?php foreach ($report["external_employees"] as $key => $item): ?>
                                        <div class="row jsEER" data-external="<?= $key; ?>" data-id="<?= $item["sid"]; ?>">
                                            <div class="col-md-5">
                                                <div class="form-group">
                                                    <label for="external_employee_name">Name</label>
                                                    <input type="text" name="external_employees_names[<?= $key; ?>]['name']"
                                                        class="form-control jsExternalEmployeeName"
                                                        value="<?= $item["external_name"]; ?>" required>
                                                </div>
                                            </div>
                                            <div class="col-md-5">
                                                <div class="form-group">
                                                    <label for="external_employee_email">Email</label>
                                                    <input type="email" name="external_employees_emails[<?= $key; ?>]['email']"
                                                        class="form-control jsExternalEmployeeEmail"
                                                        value="<?= $item["external_email"]; ?>" required>
                                                </div>
                                            </div>
                                            <div class="col-md-1">
                                                <div class="form-group">
                                                    <label>&nbsp;</label>
                                                    <button type="button" class="btn btn-red btn-block jsRemoveExternalEmployee">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <div class="alert alert-info text-center">
                                        No External employees found
                                    </div>
                                <?php endif; ?>

                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<style>
    .candidate-status {
        width: 100%;
        height: 50px;
    }

    .label-wrapper-outer {
        width: 100%;
        position: relative;
    }

    .candidate-status .lable-wrapper {
        top: 40px;
        border-radius: 5px;
        padding: 20px;
        width: 100%;
    }

    .lable-wrapper {
        display: none;
        background-color: white;
        padding: 20px;
        padding-top: 0;
        box-shadow: 0px 0px 6px #888888;
        right: 0;
        position: absolute;
        top: 30px;
        z-index: 999;
    }

    .label.csLabelPill {
        display: block !important;
    }

    .csLabelPill {
        font-family: arial;
        font-weight: bold;
        padding: 6px;
        font-size: 13px;
        margin-bottom: 3px;
        -moz-border-radius: 5px;
        border-radius: 5px;
    }

    .candidate-status .label {
        height: 30px;
        line-height: 24px;
        font-style: italic;
        font-size: 13px;
        font-weight: 600;
    }

    .candidate-status .fa.fa-times.cross {
        background-color: #000;
        border-radius: 100%;
        color: #fff;
        font-size: 9px;
        height: 20px;
        line-height: 19px;
        padding: 0;
        position: absolute;
        right: 20px;
        text-align: center;
        top: 10px;
        width: 20px;
    }
</style>

<script>
    var reportId = '<?php echo $reportId; ?>';
    var incidentId = '<?php echo $incidentId; ?>';
    var itemId = '<?php echo $itemId; ?>';
</script>