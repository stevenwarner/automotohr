<div class="main jsmaincontent" style="position:relative">
    <?php $this->load->view('loader_new', ['id' => 'jsPageLoader']); ?>

    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12 text-right">
                <a href="<?= base_url('csp/overview/') ?>" class="btn btn-blue">
                    <i class="fa fa-pie-chart"></i>
                    Compliance Safety Reporting
                </a>

            </div>
            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                <div class="page-header">
                    <h2 class="section-ttile">
                        <?= $report["compliance_report_name"]; ?>
                    </h2>
                </div>
                <!--  -->
                <div class="alert alert-info">
                    <div class="row">
                        <div class="col-sm-12 text-left">
                            Last modified by <strong><?= $report['last_modified_by'] ?></strong> at <strong><?= formatDateToDB($report['updated_at'], DB_DATE_WITH_TIME, DATE_WITH_TIME); ?></strong>.
                        </div>
                    </div>
                </div>
                <!--  -->
                <form method="post" enctype="multipart/form-data" autocomplete="off" id="jsAddReportForm">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                            <div class="form-group">
                                <label for="report_title">Report Title <strong class="text-danger">*</strong></label>
                                <input type="text" class="form-control" id="report_title" name="report_title" value="<?= $report['title'] ?>" />
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-4 col-md-4 col-xs-4 col-sm-4">
                            <div class="form-group">
                                <label for="report_date">Report Date <strong class="text-danger">*</strong></label>
                                <input type="text" class="form-control" id="report_date" name="report_date" value="<?= formatDateToDB($report['report_date'], DB_DATE, "m/d/Y"); ?>" />
                            </div>
                        </div>

                        <div class="col-lg-4 col-md-4 col-xs-4 col-sm-4">
                            <div class="form-group">
                                <label for="report_completion_date">Completion Date</label>
                                <input type="text" class="form-control" id="report_completion_date" name="report_completion_date" value="<?= $report['completion_date'] ? formatDateToDB($report['completion_date'], DB_DATE, "m/d/Y") : ""; ?>" />
                            </div>
                        </div>

                        <div class="col-lg-4 col-md-4 col-xs-4 col-sm-4">
                            <div class="form-group">
                                <label for="report_status">Status</label>
                                <select name="report_status" id="report_status" style="width: 100%;">
                                    <option <?= $report["status"] === "pending" ? "selected" : ""; ?> value="pending">Pending</option>
                                    <option <?= $report["status"] === "on_hold" ? "selected" : ""; ?> value="on_hold">On Hold</option>
                                    <option <?= $report["status"] === "completed" ? "selected" : ""; ?> value="completed">Completed</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <?php if ($report["question_answers"]) : ?>
                        <?php $this->load->view("compliance_safety_reporting/partials/incidents/answers"); ?>
                    <?php endif; ?>
                    <?php $this->load->view("compliance_safety_reporting/partials/incidents/listing"); ?>
                    <?php $this->load->view("compliance_safety_reporting/partials/files/documents"); ?>
                    <?php $this->load->view("compliance_safety_reporting/partials/files/audio"); ?>
                    <?php $this->load->view("compliance_safety_reporting/partials/files/emails_public_view"); ?>

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
                                        <textarea class="form-control" id="report_note" name="report_note" rows="5"></textarea>
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
                                <?php if (!empty($report['notes'])) : ?>
                                    <?php foreach ($report['notes'] as $note) : ?>
                                        <article>
                                            <figure>
                                                <img class="img-responsive" src="<?= getImageURL($note["profile_picture"]) ?>">
                                            </figure>
                                            <div class="text">
                                                <div class="message-header">
                                                    <div class="message-title">
                                                        <h2>
                                                        <h2>
                                                            <?php
                                                                if ($note['manual_email']) {
                                                                    echo getManualUserNameByEmailId($reportId, $incidentId, $note['manual_email']);
                                                                } else {
                                                                    echo remakeEmployeeName($note);
                                                                }
                                                            ?>    
                                                        </h2>
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
                                <?php else : ?>
                                    <div class="alert alert-info text-center">
                                        No notes found.
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Employees -->
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h1 class="panel-heading-text text-medium">
                                <strong>Internal Employees</strong>
                            </h1>
                        </div>
                        <div class="panel-body">
                            <?php if ($employees) :
                                $selectedEmployees = array_column($report["internal_employees"], "employee_sid"); ?>
                                <div class="row">
                                    <?php foreach ($employees as $employee) : ?>
                                        <div class="col-lg-4">
                                            <label class="control control--checkbox">
                                                <input type="checkbox" name="report_employees[]" value="<?= $employee["sid"]; ?>" <?= in_array($employee["sid"], $selectedEmployees) ? "checked" : ""; ?> />
                                                <div class="control__indicator"></div>
                                                <span><?= remakeEmployeeName($employee); ?></span>
                                            </label>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php else : ?>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <p class="text-danger">No employees found.</p>
                                    </div>
                                </div>
                            <?php endif; ?>
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
                            <?php if ($report["external_employees"]) : ?>
                                <?php foreach ($report["external_employees"] as $key => $item) : ?>
                                    <div class="row jsEER" data-external="<?= $key; ?>" data-id="<?= $item["sid"]; ?>">
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <label for="external_employee_name">Name</label>
                                                <input type="text" name="external_employees_names[<?= $key; ?>]['name']" class="form-control" value="<?= $item["external_name"]; ?>" required>
                                            </div>
                                        </div>
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <label for="external_employee_email">Email</label>
                                                <input type="email" name="external_employees_emails[<?= $key; ?>]['email']" class="form-control" value="<?= $item["external_email"]; ?>" required>
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
                            <?php else : ?>
                                <div class="alert alert-info text-center">
                                    No External employees found
                                </div>
                            <?php endif; ?>

                        </div>
                    </div>

                    <!--  -->
                    <div class="row">
                        <div class="col-sm-12 text-right">
                            <button class="btn btn-orange">
                                <i class="fa fa-save jsCreateReportBtn"></i>
                                Update Report
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>


    </div>
</div>


<script>
    const segments = <?= json_encode($segments); ?>;
</script>