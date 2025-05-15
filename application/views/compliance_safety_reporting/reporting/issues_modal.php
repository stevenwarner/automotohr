<ul class="nav nav-tabs nav-justified">
    <li role="presentation" class="jsIssueSection active" data-section="detail"><a href="javascript:;">Issue Detail</a></li>
    <li role="presentation" class="jsIssueSection" data-section="question"><a href="javascript:;">Add Question</a></li>
    <li role="presentation" class="jsIssueSection" data-section="file"><a href="javascript:;">Add Files</a></li>
    <li role="presentation" class="jsIssueSection" data-section="note"><a href="javascript:;">Add Notes</a></li>
</ul>

<br>

<div id="jsDetailSection">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h1 class="panel-heading-text text-medium">
                <strong>Add Issue Detail</strong>
            </h1>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-lg-4">
                    <div class="form-group autoheight">
                        <label class="control control--checkbox">
                            Default
                            <input type="radio" name="issue_type" value="default" class="jsIssueType" checked="checked">
                            <div class="control__indicator"></div>
                        </label>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="form-group autoheight">
                        <label class="control control--checkbox">
                            Manual
                            <input type="radio" name="issue_type" value="manual" class="jsIssueType">
                            <div class="control__indicator"></div>
                        </label>
                    </div>
                </div>
            </div>

            <div id="jsDefaultIssue">
                <div class="form-group">
                    <label class="text-medium">
                        Select Issue
                        <strong class="text-danger">*</strong>
                    </label>
                    <select id="jsNewItemSelect" class="form-control">
                        <?php if (isset($records) && !empty($records)): ?>
                            <?php foreach ($records as $k0 => $record): ?>
                                <optgroup label="<?= $record["title"]; ?>">
                                    <?php foreach ($record["issues"] as $k1 => $issue): ?>
                                        <option value="<?= $issue["id"]; ?>" data-incidentId="<?= $issue["incident_id"]; ?>"
                                            data-issueId="<?= $issue["id"]; ?>" data-level="<?= $issue["level"]; ?>"
                                            data-bg="<?= $issue["bg_color"]; ?>" data-txt="<?= $issue["txt_color"]; ?>">
                                            <?= $issue["name"]; ?>
                                            (Severity Level <?= $issue["level"]; ?>)
                                        </option>
                                    <?php endforeach; ?>
                                </optgroup>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>

                <br>

                <div id="jsAddIssueBox"></div>
            </div>

            <div id="jsManualIssue" class="hidden">

                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                    <div class="form-group">
                        <label>Issue Title </label>
                        <input class="form-control"
                            type="text"
                            name="manual_issue_title"
                            id="jsManualIssueTitle"
                            value=""/>
                    </div>
                </div>
                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                    <div class="form-group autoheight">
                        <label>Issue Description </label>
                        <textarea class="ckeditor" name="manual_issue_description" id="jsManualIssueDescription" cols="60" rows="10"></textarea>
                    </div>
                </div>
                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                    <div class="form-group autoheight">
                        <label>Severity Level </label>
                        <div class="row">
                            <div class="col-xs-11 jsSelectedPill">
                                <div data-id="<?= $severity_status[1]["sid"]; ?>"
                                    class="csLabelPill jsSelectedLabelPill text-center jsManualSeverityLevel"
                                    style="background-color: <?= $severity_status[1]["bg_color"]; ?>; color: <?= $severity_status[1]["txt_color"]; ?>;">
                                    Severity Level <?= $severity_status[1]["level"]; ?></div>
                            </div>
                            <div class="col-xs-1 text-left">
                                <div class="btn btn-orange show-status-box">
                                    <i class="fa fa-pencil"></i>
                                </div>
                            </div>
                        </div>
                        <!-- Selected one -->
                        <div class="lable-wrapper">
                            <div style="height:20px;">
                                <i class="fa fa-times cross"></i>
                            </div>

                            <?php if ($severity_status): ?>
                                <?php foreach ($severity_status as $v1): ?>
                                    <div class="row">
                                        <div data-id="<?= $v1["sid"]; ?>" class="col-sm-12 label jsSelectManualSeverityLevel csLabelPill"
                                            style="background-color:<?= $v1["bg_color"]; ?>; color:<?= $v1["txt_color"]; ?>;">
                                            <div class="jsSeverityLevelText">Severity Level <?= $v1["level"]; ?></div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="jsQuestionSection" class="hidden wrapper-outer">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h1 class="panel-heading-text text-medium">
                <strong>Add Question</strong>
            </h1>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-lg-12">
                    <div class="form-group autoheight">
                        <label>Report to Dashboard : <span class="required" aria-required="true"></span></label>
                        <div class="row">
                            <div class="col-lg-3 col-md-6 col-xs-12 col-sm-6">
                                <label class="control control--radio">
                                    Yes<input type="radio" class="jsReportToDashboard" name="report_to_dashboard" value="yes" style="position: relative;" >
                                    <div class="control__indicator"></div>
                                </label>
                            </div>
                            <div class="col-lg-3 col-md-6 col-xs-12 col-sm-6">
                                <label class="control control--radio">
                                    No<input type="radio" class="jsReportToDashboard" name="report_to_dashboard" value="no" style="position: relative;" checked="checked">
                                    <div class="control__indicator"></div>
                                </label>
                            </div>
                        </div>
                    </div> 
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="form-group autoheight">
                        <label>Is this a Repeat or Ongoing Issue? <span class="required" aria-required="true"></span></label>
                        <div class="row">
                            <div class="col-lg-3 col-md-6 col-xs-12 col-sm-6">
                                <label class="control control--radio">
                                    Yes<input type="radio" class="jsOngoingIssue" name="ongoing_issue" value="yes" style="position: relative;">
                                    <div class="control__indicator"></div>
                                </label>
                            </div>
                            <div class="col-lg-3 col-md-6 col-xs-12 col-sm-6">
                                <label class="control control--radio">
                                    No<input type="radio" class="jsOngoingIssue" name="ongoing_issue" value="no" style="position: relative;" checked="checked">
                                    <div class="control__indicator"></div>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="form-group autoheight">
                        <label>Was this reported by an employee?: <span class="required" aria-required="true"></span></label>
                        <div class="row">
                            <div class="col-lg-3 col-md-6 col-xs-12 col-sm-6">
                                <label class="control control--radio">
                                    Yes<input type="radio" class="jsReportedBy" name="reported_by" value="yes" style="position: relative;">
                                    <div class="control__indicator"></div>
                                </label>
                            </div>
                            <div class="col-lg-3 col-md-6 col-xs-12 col-sm-6">
                                <label class="control control--radio">
                                    No<input type="radio" class="jsReportedBy" name="reported_by" value="no" style="position: relative;" checked="checked">
                                    <div class="control__indicator"></div>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="form-group autoheight">
                        <label>Category of issue: <span class="required" aria-required="true"></span></label>
                        <input id="jsCategoryOfIssue" type="text" name="category_of_issue" value=""  class="form-control">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="jsFileSection" class="hidden wrapper-outer">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h1 class="panel-heading-text text-medium">
                <strong>Add File</strong>
            </h1>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-lg-12">
                    <div class="form-group">
                        <label for="jsAddIssueFileUploadTitle">Title <strong class="text-danger">*</strong></label>
                        <input type="text" class="form-control" id="jsAddIssueFileUploadTitle" name="jsAddIssueFileUploadTitle" />
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="form-group">
                        <input type="file" class="hidden" id="jsAddIssueFileUploadFile" name="jsAddIssueFileUploadFile" />
                    </div>
                </div>
            </div>
        </div>
        <div class="panel-footer text-right">
            <button class="btn btn-orange" id="jsAddIssueFileBtn">
                <i class="fa fa-plus-circle"></i>
                Add File
            </button>
        </div>
    </div>

    <div class="panel panel-default hidden" id="jsAttachedFileListingSection">
        <div class="panel-heading">
            <h1 class="panel-heading-text text-medium">
                <strong>Attached File</strong>
            </h1>
        </div>
        <div class="panel-body">
            <div class="table-responsive table-outer full-width" style="margin-top: 20px;">
                <div class="table-wrp data-table">
                    <table class="table table-bordered table-hover table-stripped">
                        <thead>
                            <tr>
                                <th class="text-center">Attachment Title</th>
                                <th class="text-center">Attachment Type</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody id="jsAttachedFileListing">

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="jsNoteSection" class="hidden wrapper-outer">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h1 class="panel-heading-text text-medium">
                <strong>Add Note</strong>
            </h1>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-lg-12">
                    <div class="form-group">
                        <label for="report_note_type">Type <strong
                                class="text-danger">*</strong></label>
                        <select name="report_note_type" id="jsAddIssueNoteType">
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
                        <textarea class="form-control" id="jsAddIssueNote" name="report_note"
                            rows="5"></textarea>
                    </div>
                </div>
            </div>
        </div>
        <div class="panel-footer text-right">
            <button class="btn btn-orange" id="jsAddIssueNoteBtn">
                <i class="fa fa-plus-circle"></i>
                Add Note
            </button>
        </div>
    </div>

    <div class="panel panel-default hidden" id="jsAttachedNoteListingSection">
        <div class="panel-heading">
            <h1 class="panel-heading-text text-medium">
                <strong>Attached Note(s)</strong>
            </h1>
        </div>
        <div class="panel-body">
            <div class="table-responsive table-outer full-width" style="margin-top: 20px;">
                <div class="table-wrp data-table">
                    <table class="table table-bordered table-hover table-stripped">
                        <thead>
                            <tr>
                                <th class="text-center">Note Type</th>
                                <th class="text-center">Note</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody id="jsAttachedNoteListing">

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

