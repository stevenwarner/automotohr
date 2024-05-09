<!-- <html lang="en"> -->

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="shortcut icon" href="<?= base_url() ?>assets/images/favi-icon.png" type="image/x-icon" />
    <link rel="stylesheet" type="text/css" href="<?php echo site_url('assets/manage_admin/css/bootstrap.css'); ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo site_url('assets/manage_admin/css/style.css'); ?>">
    <title>Performance Evaluation Document</title>
    <style>
        .center-col {
            float: left;
            width: 100%;
            text-align: center;
            margin-top: 14px;
        }

        .center-col h2,
        .center-col p {
            margin: 0 0 5px 0;
        }

        .sheet-header {
            float: left;
            width: 100%;
            padding: 0 0 2px 0;
            margin: 0 0 5px 0;
            border-bottom: 5px solid #000;
        }

        .sheet.padding-10mm {
            padding: 10mm
        }

        .header-logo {
            float: left;
            width: 100%;
        }
    </style>
</head>

<body cz-shortcut-listen="true">
    <!-- Wrapper Start -->
    <div class="wrapper">
        <!-- Header Start -->
        <header class="header">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <strong>Performance Evaluation Document</strong>
                            </div>
                            <div class="panel-body">
                                <h3 class="m0">
                                    <strong>
                                        Employee Section 2: The Year in Review
                                    </strong>
                                </h3>
                                <!-- Question Start -->
                                <p>
                                    List 3-4 top accomplishments & add a reflection on overall performance for the year.
                                </p>
                                <table class="table table-bordered">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th scope="col"></th>
                                            <th scope="col">Accomplishment</th>
                                            <th scope="col">Employee Comments/Reflection</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <th scope="row">1</th>
                                            <td>
                                                <input type="text" name="accomplishment_1" readonly class="form-control" value="<?= $section_2['accomplishment_1'] ?? '' ?>">
                                            </td>
                                            <td>
                                                <textarea name="accomplishment_comment_1" rows="4" readonly class="form-control"><?= $section_2['accomplishment_comment_1'] ?? '' ?></textarea>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row">2</th>
                                            <td>
                                                <input type="text" name="accomplishment_2" readonly class="form-control jsAccomplishment" data-key="jsAccomplishment2" value="<?= $section_2['accomplishment_2'] ?? '' ?>">
                                            </td>
                                            <td>
                                                <textarea name="accomplishment_comment_2" readonly rows="4" class="form-control jsAccomplishment2"><?= $section_2['accomplishment_comment_2'] ?? '' ?></textarea>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row">3</th>
                                            <td>
                                                <input type="text" name="accomplishment_3" readonly class="form-control jsAccomplishment" data-key="jsAccomplishment3" value="<?= $section_2['accomplishment_3'] ?? '' ?>">
                                            </td>
                                            <td>
                                                <textarea name="accomplishment_comment_3" readonly rows="4" class="form-control jsAccomplishment3"><?= $section_2['accomplishment_comment_3'] ?? '' ?></textarea>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row">3</th>
                                            <td>
                                                <input type="text" name="accomplishment_4" readonly class="form-control jsAccomplishment" data-key="jsAccomplishment4" value="<?= $section_2['accomplishment_4'] ?? '' ?>">
                                            </td>
                                            <td>
                                                <textarea name="accomplishment_comment_4" readonly rows="4" class="form-control jsAccomplishment4"><?= $section_2['accomplishment_comment_4'] ?? '' ?></textarea>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                <!-- Question End -->
                                <!-- Question Start -->
                                <p>
                                    Opportunities for Improved Performance: List 2-4 areas of improvement & how you will work on these improvements over the coming year.
                                </p>
                                <table class="table table-bordered">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th scope="col"></th>
                                            <th scope="col">Opportunity for Improvement</th>
                                            <th scope="col">Employee Comments</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <th scope="row">1</th>
                                            <td>
                                                <input type="text" name="opportunities_1" readonly class="form-control" value="<?= $section_2['opportunities_1'] ?? '' ?>">
                                            </td>
                                            <td>
                                                <textarea name="opportunities_comment_1" readonly rows="4" class="form-control"><?= $section_2['opportunities_comment_1'] ?? '' ?></textarea>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row">2</th>
                                            <td>
                                                <input type="text" name="opportunities_2" readonly class="form-control jsOpportunities" data-key="jsOpportunities2" value="<?= $section_2['opportunities_2'] ?? '' ?>">
                                            </td>
                                            <td>
                                                <textarea name="opportunities_comment_2" readonly rows="4" class="form-control jsOpportunities2"><?= $section_2['opportunities_comment_2'] ?? '' ?></textarea>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row">3</th>
                                            <td>
                                                <input type="text" name="opportunities_3" readonly class="form-control jsOpportunities" data-key="jsOpportunities3" value="<?= $section_2['opportunities_3'] ?? '' ?>">
                                            </td>
                                            <td>
                                                <textarea name="opportunities_comment_3" readonly rows="4" class="form-control jsOpportunities3"><?= $section_2['opportunities_comment_3'] ?? '' ?></textarea>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row">4</th>
                                            <td>
                                                <input type="text" name="opportunities_4" readonly class="form-control jsOpportunities" data-key="jsOpportunities4" value="<?= $section_2['opportunities_4'] ?? '' ?>">
                                            </td>
                                            <td>
                                                <textarea name="opportunities_comment_4" readonly rows="4" class="form-control jsOpportunities4"><?= $section_2['opportunities_comment_4'] ?? '' ?></textarea>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                <!-- Question End -->
                                <!-- Question Start -->
                                <p>
                                    List 2-3 goals for the upcoming year. One must reflect a personal development goal.
                                </p>
                                <table class="table table-bordered">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th scope="col"></th>
                                            <th scope="col">Goal</th>
                                            <th scope="col">General comments and summary relating to the status of the goal, attainment, difficulty of goal, and impacting factors.</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <th scope="row">1</th>
                                            <td>
                                                <input type="text" name="goal_1" readonly class="form-control" value="<?= $section_2['goal_1'] ?? '' ?>">
                                            </td>
                                            <td>
                                                <textarea name="goal_comment_1" readonly rows="4" class="form-control"><?= $section_2['goal_comment_1'] ?? '' ?></textarea>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row">2</th>
                                            <td>
                                                <input type="text" name="goal_2" readonly class="form-control jsGoal" data-key="jsGoal2" value="<?= $section_2['goal_2'] ?? '' ?>">
                                            </td>
                                            <td>
                                                <textarea name="goal_comment_2" readonly rows="4" class="form-control jsGoal2"><?= $section_2['goal_comment_2'] ?? '' ?></textarea>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row">3</th>
                                            <td>
                                                <input type="text" name="goal_3" readonly class="form-control jsGoal" data-key="jsGoal3" value="<?= $section_2['goal_3'] ?? '' ?>">
                                            </td>
                                            <td>
                                                <textarea name="goal_comment_3" readonly rows="4" class="form-control jsGoal3"><?= $section_2['goal_comment_3'] ?? '' ?></textarea>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                <!-- Question End -->
                                <!-- Question Start -->
                                <h4>
                                    1. Have you and your manager reviewed your job description for this review period?
                                </h4>
                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                    <label class="control control--radio">
                                        <span class="text-large">
                                            Yes
                                        </span>
                                        <input type="radio" name="review_period_radio" disabled value="1" <?php echo $section_2['review_period_radio'] == 1 ? 'checked' : '' ?> />
                                        <div class="control__indicator"></div>
                                    </label>
                                </div>
                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                    <label class="control control--radio">
                                        <span class="text-large">
                                            No
                                        </span>
                                        <input type="radio" name="review_period_radio" disabled value="2" <?php echo $section_2['review_period_radio'] == 2 ? 'checked' : '' ?> />
                                        <div class="control__indicator"></div>
                                    </label>
                                </div>
                                <p>
                                    (Please attach a copy of your job description for review with your manager)
                                </p>
                                <!-- Question End -->
                                <!-- Question Start -->
                                <h4>
                                    2. Do you have access to equipment and resources necessary to perform your job function?
                                </h4>
                                <p>
                                    (If No, please list the equipment you deem necessary subject to Managers approval and budgeting)
                                </p>
                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                    <label class="control control--radio">
                                        <span class="text-large">
                                            Yes
                                        </span>
                                        <input type="radio" name="equipment_resources_radio" disabled value="1" <?php echo $section_2['equipment_resources_radio'] == 1 ? 'checked' : '' ?> />
                                        <div class="control__indicator"></div>
                                    </label>
                                </div>
                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                    <label class="control control--radio">
                                        <span class="text-large">
                                            No
                                        </span>
                                        <input type="radio" name="equipment_resources_radio" disabled value="2" <?php echo $section_2['equipment_resources_radio'] == 2 ? 'checked' : '' ?> />
                                        <div class="control__indicator"></div>
                                    </label>
                                </div>
                                <label class="col-sm-12">
                                    <br>
                                    <span class="text-large">
                                        Necessary Equipment or Resources Needed
                                    </span>
                                    <input type="text" name="equipment_resources_needed" readonly class="form-control" value="<?= $section_2['equipment_resources_needed'] ?? '' ?>">
                                </label>
                                <!-- Question End -->
                                <!-- Question Start -->
                                <label class="col-sm-12">
                                    <br>
                                    <span class="text-large">
                                        3. Is there any additional support or training you feel would be helpful for <?= $companyName ?> to provide for you to help you succeed in your current role?
                                    </span>
                                    <textarea name="additional_support" readonly rows="10" class="form-control"><?= $section_2['additional_support'] ?? '' ?></textarea>
                                </label>
                                <!-- Question End -->
                                <!-- Question Start -->
                                <label class="col-sm-12">
                                    <br>
                                    <span class="text-large">
                                        4. Employee Additional Comments:
                                    </span>
                                    <textarea name="additional_comment" readonly rows="10" class="form-control"><?= $section_2['additional_comment'] ?? '' ?></textarea>
                                </label>
                                <!-- Question End -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.0.487/pdf.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url('assets/employee_panel/js/kendoUI.min.js'); ?>"></script>
    <script>
        $(document).ready(function() {

            <?php if ($document['user_consent'] == 1 && !empty($document['form_input_data'])) { ?>
                var form_input_data = <?php echo $form_input_data; ?>;
                form_input_data = Object.entries(form_input_data);

                $.each(form_input_data, function(key, input_value) {
                    var input_field_id = input_value[0] + '_id';
                    var input_field_val = input_value[1];
                    var input_type = $('#' + input_field_id).attr('data-type');

                    if (input_type == 'text') {
                        $('#' + input_field_id).val(input_field_val);
                        $('#' + input_field_id).prop('disabled', true);
                    } else if (input_type == 'checkbox') {
                        if (input_field_val == 'yes') {
                            $('#' + input_field_id).prop('checked', true);;
                        }
                        $('#' + input_field_id).prop('disabled', true);

                    } else if (input_type == 'textarea') {
                        $('#' + input_field_id).hide();
                        $('#' + input_field_id + '_sec').show();
                        $('#' + input_field_id + '_sec').html(input_field_val);
                    }
                });

            <?php } ?>

            var imgs = $('#download_generated_document').find('img');

            if (imgs.length) {
                $(imgs).each(function(i, v) {
                    var imgSrc = $(this).attr('src');
                    var _this = this;

                    var p = /^(?:http(s)?:\/\/)?[\w.-]+(?:\.[\w\.-]+)+[\w\-\._~:/?#[\]@!\$&'\(\)\*\+,;=.]+$/gm;

                    if (imgSrc.match(p)) {
                        $.ajax({
                            url: '<?= base_url('hr_documents_management/getbase64/') ?>',
                            data: {
                                url: imgSrc.trim()
                            },
                            type: "GET",
                            async: false,
                            success: function(resp) {
                                resp = JSON.parse(resp);
                                $(_this).attr("src", "data:" + resp.type + ";base64," + resp.string);
                                download_document();
                            },
                            error: function() {

                            }
                        });
                    }
                });
            }

            var perform_action = '<?php echo $perform_action; ?>';

            if (perform_action == 'download') {
                var draw = kendo.drawing;
                draw.drawDOM($("#download_generated_document"), {
                        avoidLinks: false,
                        paperSize: "A4",
                        multiPage: true,
                        margin: {
                            bottom: "2cm"
                        },
                        scale: 0.8
                    })
                    .then(function(root) {
                        return draw.exportPDF(root);
                    })
                    .done(function(data) {
                        var pdf;
                        pdf = data;

                        $('#myiframe').attr("src", data);
                        kendo.saveAs({
                            dataURI: pdf,
                            fileName: '<?php echo $file_name . ".pdf"; ?>',
                        });
                        window.close();
                    });
            } else {
                // window.print();
                // $(window).on( "load", function() { 
                //     setTimeout(function(){
                //         window.print();
                //         alert('finish')
                //     }, 2000);  
                // });

                // window.onafterprint = function(){
                //     window.close();
                // }
            }
        });
    </script>
</body>

</html>