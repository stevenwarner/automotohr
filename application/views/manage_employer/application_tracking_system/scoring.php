<style>
    .form-title-section {
        margin: 20px 0px 10px 0px;
    }

    .HorizontalTab {
        display: inline-block;
    }

    .submitted_card_wrapper {
        background: #fff;
        border-radius: 2px;
        min-height: 400px;
        padding: 20px;
    }

    .submitted_card_wrapper p {
        font-size: 12px;
        color: #737373;
        margin: 10px 0;
    }

    .score_wrapper h3,
    .score_wrapper span {
        margin: 0px;
        font-weight: 600;
    }

    .submitted_card_wrapper h4 {
        font-size: 16px;
        font-weight: 600;
    }

    .score_wrapper {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
        align-items: center;
    }

    .reasoning ul {
        padding: 0px 20px;
    }

    .reasoning,
    .questions {
        line-height: 1.6;
    }

    .questions span {
        padding: 10px;
        background-color: #eee;
        margin: 10px 0px;
    }

    .skills {
        display: flex;
        flex-wrap: wrap;
        gap: 5px;
    }

    .skills span {
        display: block;
        background-color: #eee;
        padding: 5px 10px;
    }

    .panel-body {
        min-height: max-content;
    }

    .action-btn {
        padding: 4px;
        display: flex;
        align-items: center;
        width: 25px;
        height: 25px;
        justify-content: center;
    }

    .action-btn i {
        margin: 0px;
    }

    .panel-heading {
        display: flex;
        justify-content: space-between;
    }

    textarea,
    input {
        width: 100%;
        border-radius: 3px;
        border: 1px solid #ddd;
        padding: 2px 5px;
    }

    #edit_questionnaire_body_data i.fa-trash {
        font-size: 16px;
        cursor: pointer;
    }

    table {
        line-height: 1.6;
        border: 1px solid #dddddd;
    }

    table tr:nth-of-type(even) {
        background-color: #f5f5f5;
    }

    .custom-tabs {
        display: flex;
        height: 40px;
        margin: 10px 0;
        border-bottom: 1px solid #efefef;
    }

    .custom-tabs span {
        display: block;
        padding: 10px 20px;
        color: #8b8b8b;
        cursor: pointer;
        user-select: none;
        transition: all 0.2s ease-in-out;
    }

    .custom-tabs span:hover {
        background: #f7f7f7;
    }

    .custom-tabs span.active {
        color: #222222;
        border-bottom: 1px solid #222222;
    }

    .donut-chart {
        width: 200px;
        height: 200px;
        border-radius: 50%;
        background: conic-gradient(#252524 0deg,
                #252524 var(--percentage, 216deg),
                #e0e0e0 var(--percentage, 216deg),
                #e0e0e0 360deg);
        position: relative;
        transition: all 0.5s ease;
    }

    .donut-chart::before {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 120px;
        height: 120px;
        background-color: #f5f5f5;
        border-radius: 50%;
        transform: translate(-50%, -50%);
    }

    .donut-text {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        font-size: 32px;
        font-weight: bold;
        color: #333;
        z-index: 1;
    }

    .example-donut {
        text-align: center;
    }

    .example-donut .donut-chart {
        width: 120px;
        height: 120px;
    }

    .example-donut .donut-chart::before {
        width: 70px;
        height: 70px;
    }

    .example-donut .donut-text {
        font-size: 18px;
    }

    .example-title {
        margin-top: 10px;
        font-size: 14px;
        color: #666;
    }
</style>
<?php
$skills = json_decode($submitted_resume_data['skills']);
$education = json_decode($submitted_resume_data['education']);
$work_experience = json_decode($submitted_resume_data['work_experience']);
$certifications = json_decode($submitted_resume_data['certifications']);
$screening_questions = json_decode($submitted_resume_data['screening_questions']);
$extra_content = $submitted_resume_data['extra_content'];
$reports = !empty($interview_logs['reports']) ? json_decode($interview_logs['reports']) : null;
?>

<div class="applicant_scoring">
    <div class="row">
        <div class="col-xs-12">
            <ul class="nav nav-tabs nav-justified">
                <li class="active" style="color: #ffffff;"><a style="color: #ffffff !important" data-toggle="tab"
                        href="#parsed_data">Applicant Data</a></li>
                <?php if ($interview_logs): ?>
                    <li><a data-toggle="tab" href="#interview_data" onclick="activeInterviewData()">Interview Data</a></li>
                <?php endif; ?>
            </ul>
            <div class="tab-content">
                <div id="parsed_data" class="tab-pane fade in active hr-innerpadding">
                    <div class="row">
                        <div class="col-lg-4 col-sm-12">
                            <div class="submitted_card_wrapper hr-widget">
                                <div class="score_wrapper">
                                    <h3>Applicant Score</h3>
                                    <div style="display: flex;align-items: center;">
                                        <span class="score_range">
                                            <?= empty($submitted_resume_data['match_score']) ? '0' : $submitted_resume_data['match_score']; ?>
                                            /
                                            100</span>
                                        <a href="javascript:;" class="action-btn" onclick="displayScoring()">
                                            <i class="fa fa-pencil"></i>
                                            <span class="btn-tooltip">Edit</span>
                                        </a>
                                    </div>
                                </div>
                                <p>Rate the candidate on each criterion below</p>

                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <strong> Reason </strong>
                                    </div>
                                    <div class="panel-body">
                                        <div class="reasoning">
                                            <?php echo $extra_content; ?>
                                        </div>
                                    </div>
                                </div>

                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <strong> Skills </strong>
                                    </div>
                                    <div class="panel-body">
                                        <div class="skills">
                                            <?php foreach ($skills as $skill) {
                                                if (is_array($skill) || is_object($skill)) {
                                                    foreach ($skill as $key => $value) {
                                                        ?>
                                                        <div>
                                                            <span style="text-transform: capitalize;font-weight:600;">
                                                                <?php echo $key; ?> </span>:
                                                            <span> <?php echo $value; ?> </span>
                                                        </div>
                                                        <?php
                                                    }
                                                } else {
                                                    ?>
                                                    <span style="display: block;"> <?php echo ($skill); ?> </span>
                                                    <?php
                                                }
                                            } ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-8 col-sm-12">
                            <div class="submitted_card_wrapper hr-widget">

                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <strong>
                                            Screening Questions
                                        </strong>

                                        <a href="javascript:;" class="action-btn" onclick="displayQuestions()">
                                            <i class="fa fa-pencil"></i>
                                            <span class="btn-tooltip">Edit</span>
                                        </a>
                                    </div>
                                    <div class="panel-body questions">
                                        <?php foreach ($screening_questions as $ques) {
                                            if (is_array($ques) || is_object($ques)) {
                                                foreach ($ques as $key => $value) {
                                                    ?>
                                                    <div>
                                                        <span style="text-transform: capitalize;font-weight:600;">
                                                            <?php echo $key; ?> </span>:
                                                        <span> <?php echo $value; ?> </span>
                                                    </div>
                                                    <?php
                                                }
                                            } else {
                                                ?>
                                                <span style="display: block;"><strong>Q.</strong> &nbsp; <?php echo ($ques); ?>
                                                </span>
                                                <?php
                                            }
                                        } ?>
                                    </div>
                                </div>

                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <strong> Education </strong>
                                    </div>
                                    <div class="panel-body">
                                        <?php foreach ($education as $key => $edu) {
                                            if ($key > 0) {
                                                echo "<hr />";
                                            }
                                            if (is_array($edu) || is_object($edu)) {
                                                foreach ($edu as $key => $value) {
                                                    ?>
                                                    <div>
                                                        <span style="text-transform: capitalize;font-weight:600;">
                                                            <?php echo $key; ?> </span>:
                                                        <span> <?php echo $value; ?> </span>
                                                    </div>
                                                    <?php
                                                }
                                            } else {
                                                ?>
                                                <span style="display: block;"> <?php echo $edu; ?> </span>
                                                <?php
                                            }
                                        } ?>
                                    </div>
                                </div>

                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <strong> Certifications </strong>
                                    </div>
                                    <div class="panel-body">
                                        <?php foreach ($certifications as $certificate) {
                                            if (is_array($certificate) || is_object($certificate)) {
                                                foreach ($certificate as $key => $value) {
                                                    ?>
                                                    <div>
                                                        <span style="text-transform: capitalize;font-weight:600;">
                                                            <?php echo $key; ?> </span>:
                                                        <span> <?php echo $value; ?> </span>
                                                    </div>
                                                    <?php
                                                }
                                            } else {
                                                ?>
                                                <span style="display: block;"> - <?php echo $certificate; ?> </span>
                                                <?php
                                            }
                                        } ?>
                                    </div>
                                </div>

                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <strong> Work Experience </strong>
                                    </div>
                                    <div class="panel-body">
                                        <?php foreach ($work_experience as $key => $exp) {
                                            if ($key > 0) {
                                                echo "<hr />";
                                            }
                                            if (is_array($exp) || is_object($exp)) {
                                                foreach ($exp as $key => $value) {
                                                    ?>
                                                    <div>
                                                        <span style="text-transform: capitalize;font-weight:600;">
                                                            <?php echo $key; ?> </span>:
                                                        <span> <?php echo $value; ?> </span>
                                                    </div>
                                                    <?php
                                                }
                                            } else {
                                                ?>
                                                <span style="display: block;"> <?php echo ($exp); ?> </span>
                                                <?php
                                            }
                                        } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php if ($interview_logs): ?>

                    <div id="interview_data" class="tab-pane fade">
                        <div class="row">
                            <div class="col-md-12 col-xs-12">
                                <div class="form-title-section">
                                    <h2>Audio</h2>
                                </div>
                            </div>

                            <?php $audio_data = json_decode($interview_logs['file_path']); ?>
                            <div class="col-md-12 col-xs-12">
                                <audio controls>
                                    <source src="<?= $audio_data->file_path; ?>" type="audio/wav">
                                </audio>
                            </div>
                        </div>

                        <div class="row">
                            <div id="HorizontalTab" class="col-md-12 col-xs-12">
                                <div class="custom-tabs">
                                    <span target="reports-data" class="active">Reports</span>
                                    <span target="transcript-data">Transcript</span>
                                </div>
                            </div>
                        </div>

                        <div id="reports-data" class="custom-tab" style="display: block;">
                            <table>
                                <tr>
                                    <th style="vertical-align: baseline;width:100px;padding:10px;">Overall Score:</th>
                                    <td style="padding:10px;"><?= $reports->overallScore ?></td>
                                </tr>

                                <tr>
                                    <th style="vertical-align: baseline;width:100px;padding:10px;">Confidence Level:</th>
                                    <td style="padding:10px;"><?= $reports->confidenceLevel ?></td>
                                </tr>

                                <tr>
                                    <th style="vertical-align: baseline;width:100px;padding:10px;">Strengths:</th>
                                    <td style="padding:10px;">
                                        <ul>
                                            <?php foreach ($reports->strengths as $strength): ?>
                                                <li><?= htmlspecialchars($strength) ?></li>
                                            <?php endforeach; ?>
                                        </ul>
                                    </td>
                                </tr>

                                <tr>
                                    <th style="vertical-align: baseline;width:100px;padding:10px;">Areas for Improvement:
                                    </th>
                                    <td style="padding:10px;">
                                        <ul>
                                            <?php foreach ($reports->areasForImprovement as $area): ?>
                                                <li><?= htmlspecialchars($area) ?></li>
                                            <?php endforeach; ?>
                                        </ul>
                                    </td>
                                </tr>

                                <tr>
                                    <th style="vertical-align: baseline;width:100px;padding:10px;">Suitability Summary:</th>
                                    <td style="padding:10px;"><?= $reports->suitabilitySummary ?></td>
                                </tr>

                                <tr>
                                    <th style="vertical-align: baseline;width:100px;padding:10px;">Notable Quotes:</th>
                                    <td style="padding:10px;">
                                        <ul>
                                            <?php foreach ($reports->notableQuotes as $quote): ?>
                                                <li><?= htmlspecialchars($quote) ?></li>
                                            <?php endforeach; ?>
                                        </ul>
                                    </td>
                                </tr>

                                <tr>
                                    <th style="vertical-align: baseline;width:100px;padding:10px;">Recommendation:</th>
                                    <td style="padding:10px;"><?= $reports->recommendation ?></td>
                                </tr>
                            </table>
                        </div>

                        <div id="transcript-data" class="custom-tab" style="display: none;">
                            <table>
                                <?php
                                $transript = json_decode($interview_logs['interview_content']);
                                foreach ($transript as $key => $trans) {
                                    if ($trans->role === "system") {
                                        continue;
                                    }
                                    ?>
                                    <tr>
                                        <th style="vertical-align: baseline;width:100px;padding:10px;">Role:</th>
                                        <td style="padding:10px;">
                                            <?php echo $trans->role === 'assistant' ? 'Michael' : ($applicant_info['first_name'] . ' ' . $applicant_info['last_name']); ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th style="vertical-align: baseline;width:100px;padding:10px;">Content:</th>
                                        <td style="padding:10px;"><?php echo $trans->content; ?></td>
                                    </tr>
                                    <?php
                                }
                                ?>
                            </table>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<!-- Main End -->

<div id="edit_questionnaire_modal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <form method="post">
            <input type="hidden" name="resume_id" value="<?php echo $submitted_resume_data['sid']; ?>" />
            <div class="modal-content">
                <div class="modal-header modal-header-bg">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Edit Questions</h4>
                </div>
                <div class="modal-body" id="edit_questionnaire_body_data">
                    <!-- Questions Input List -->
                    <div id="question_wrapper"></div>
                    <button class="btn btn-success" type="button" onclick="addQuestion()">Add More</button>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-success" type="submit" id="submit_prompt">Save</button>
                </div>
            </div>
        </form>
    </div>
</div>

<div id="edit_scoring_modal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <form method="post">
            <input type="hidden" name="resume_id" value="<?php echo $submitted_resume_data['sid']; ?>" />
            <div class="modal-content">
                <div class="modal-header modal-header-bg">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Edit Applicant Scoring</h4>
                </div>
                <div class="modal-body" id="edit_scoring_body_data">
                    <!-- Scoring Input -->
                    <input type="number" class="invoice-fields" name="score" id="score"
                        value="<?= $submitted_resume_data['match_score']; ?>" min="1" max="100" required />
                    <p>Maximum scoring will be 100.</p>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-success" type="submit" id="submit_prompt">Save</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>

    // --------------------------
    // Parsed Data Script Code
    // --------------------------

    let screening_questions = JSON.parse(`<?php echo $submitted_resume_data['screening_questions'] ?>`);
    const displayQuestions = () => {
        let question_html_data = ``;
        for (let i = 0; i < screening_questions.length; i++) {
            question_html_data += `<div style="margin-bottom: 10px;display:grid;">
                <div style="display:flex;justify-content: space-between;align-items: center;"> <label>Question:</label> <i class="fa fa-trash"></i> </div>
                <textarea name="questions[]" key="${i}" rows="${3}" >${screening_questions[i]}</textarea>
            </div>`;
        }
        $('#edit_questionnaire_body_data #question_wrapper').html(question_html_data);
        $('#edit_questionnaire_modal').modal('show');

        removeQuestionJS();
    }

    const addQuestion = () => {
        let question_html_data = `<div style="margin-bottom: 10px;display:grid;">
            <div style="display:flex;justify-content: space-between;align-items: center;"> <label>Question:</label> <i class="fa fa-trash"></i> </div>
            <textarea name="questions[]" rows="3" >${''}</textarea>
        </div>`;
        $('#edit_questionnaire_body_data #question_wrapper').append(question_html_data);

        removeQuestionJS();
    }

    const removeQuestionJS = () => {
        $('#edit_questionnaire_body_data .fa-trash').click(function (e) {
            e.target.parentElement.parentElement.remove();
        })
    }

    const displayScoring = () => {
        $('#edit_scoring_modal').modal('show');
    }

    // --------------------------
    // Interview Data Script Code
    // --------------------------

    $(document).ready(function () {
        $('#HorizontalTab').easyResponsiveTabs({
            type: 'default', //Types: default, vertical, accordion
            width: 'auto', //auto or any width like 600px
            fit: true, // 100% fit in a container
            tabidentify: 'hor_1', // The tab groups identifier
            activate: function () { }
        });

        let customTabs = document.querySelectorAll('.custom-tabs span');
        let customTabList = document.querySelectorAll('.custom-tab');
        customTabs.forEach(tab => {
            tab.addEventListener('click', (e) => {
                const ref = e.target.getAttribute('target');
                customTabList.forEach(cTab => {
                    cTab.style.display = 'none';
                })

                customTabs.forEach(_tab => {
                    _tab.classList.remove('active');
                })

                e.target.classList.add('active');
                document.querySelector('#' + ref).style.display = 'block';
            })
        })
    });

    function activeInterviewData() {

        document.querySelectorAll('.applicant_scoring .nav-tabs li').forEach(li => {
            li.classList.remove('active');
            if (li.querySelector('a').href === '#interview_data' && !li.classList.contains('active')) {
                li.classList.add('active');
            }
        })

        document.querySelector('#interview_data').classList.add('in');
        document.querySelector('#interview_data').classList.add('active');

        document.querySelector('#parsed_data').classList.remove('in');
        document.querySelector('#parsed_data').classList.remove('active');
    }

    function activeParsedData() {

        document.querySelectorAll('.applicant_scoring .nav-tabs li').forEach(li => {
            li.classList.remove('active');
            if (li.querySelector('a').href === '#parsed_data' && !li.classList.contains('active')) {
                li.classList.add('active');
            }
        })

        document.querySelector('#parsed_data').classList.add('in');
        document.querySelector('#parsed_data').classList.add('active');

        document.querySelector('#interview_data').classList.remove('in');
        document.querySelector('#interview_data').classList.remove('active');
    }
</script>