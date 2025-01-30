<?php if ($userCompletedLMSCourses) {
     ?>
    <div class="row">
        <div class="col-xs-12">
            <br />
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#user_completed_lms_course">
                            <span class="glyphicon glyphicon-minus" aria-hidden="true"></span>
                            LMS Courses
                            <div class="pull-right total-records"><b>&nbsp;Total: <?php echo count($userCompletedLMSCourses); ?></b></div>
                        </a>
                    </h4>
                </div>

                <!--user_not_completed_state_forms -->
                <div id="user_completed_lms_course" class="panel-collapse collapse in">
                    <div class="table-responsive full-width">
                        <table class="table table-plane">
                            <caption></caption>
                            <thead>
                                <tr>
                                    <th class="col-lg-8">Course Name</th>
                                    <th class="col-lg-4 text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($userCompletedLMSCourses as $v0) { ?>
                                    <tr data-id="<?= $v0["sid"]; ?>">
                                        <td class="vam">
                                            <p><?= $v0["course_title"]; ?></p>
                                       
                                            <p>Completed on: <?= formatDateToDB(
                                                                $v0["completed_at"],
                                                                DB_DATE_WITH_TIME,
                                                                DATE_WITH_TIME
                                                            ); ?></p>
                                        </td>
                                        <td class="vam text-right">                                         
                                                <button class="btn btn-success jsPreviewlmsCertificate" course_sid="<?php echo $v0['course_sid']; ?>">
                                                    View Completed
                                                </button>
                                                <a class="btn btn-success" target="_blank" href="<?php echo base_url("hr_documents_management/print_lms_completed_course_certificate/".$user_sid.'/'.$v0['course_sid'].'/'.'print'); ?>">
                                                    Print
                                                </a>
                                                <a class="btn btn-success" target="_blank" href="<?php echo base_url("hr_documents_management/print_lms_completed_course_certificate/".$user_sid.'/'.$v0['course_sid'].'/'.'download'); ?>">
                                                    Download
                                                </a>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php } ?>

<script>
$('.jsPreviewlmsCertificate').on('click', function() {
            var courseSid = $(this).attr('course_sid');
            XHR = null;
            //
            $.ajax({
                    url: '<?php echo base_url('hr_documents_management/get_lms_completed_course_certificate/' . $user_sid ); ?>' + '/' + courseSid,
                    method: "GET",
                })
                .success(function(response) {
                    $('#jsStateFormEmployerView').modal('show');
                    $("#jsStateEmployerSection").html(response.view);
                });
        });
</script>