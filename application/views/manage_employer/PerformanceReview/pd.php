<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Performance Review - Print / Download</title>
    <link rel="stylesheet" type="text/css" href="<?= base_url() ?>assets/css/bootstrap.css">
    <link rel="stylesheet" href="<?php echo base_url('assets/css/i9-style.css') ?>">
</head>
<style>
    .cs-question-row {
        margin-bottom: 10px;
    }

    .cs-rating-box,
    .cs-rating-text-box {
        border: 1px solid #ccc;
        margin-right: 5px;
        text-align: center;
        height: 80px;
        cursor: pointer;
        background-color: #eee;
    }
    
    .cs-rating-box {
        line-height: 80px;
    }
    
    .cs-rating-btn.active {
        background-color: #81b431;
        color: #fff;
    }

    
    .main .cs-rating-btn.active {
        background-color: #3554dc;
        color: #fff;
    }
    
    .cs-rating-box p {
        font-weight: bold;
    }
    
    .cs-rating-box p:nth-child(1) {
        font-size: 25px;
    }
    
    .cs-rating-text-box p:nth-child(1) {
        margin-top: 10px;
        font-size: 22px;
        font-weight: bold;
    }
    
    .cs-heading {
        margin-top: 20px;
        font-size: 16px;
    }
    
    .cs-question-row h5:nth-child(1) {
        font-size: 16px;
    }
    
    body {
        -webkit-print-color-adjust: exact !important;
    }
    </style>
<!-- Set "A5", "A4" or "A3" for class name -->
<!-- Set also "landscape" if you need -->

<body>
    <div class="A4">
        <div class="container">
            <div class="row">
                <div class="col-sm-12" id="js-export-pdf">
                    <!--  -->
                    <div class="row">
                        <div class="col-sm-12 cs-heading">
                            <h3><?= $review['main']['title']; ?></h3>
                        </div>
                    </div>
                    <hr />
                    <?php 
                        $RevieweeName = getReviewName($review['reviewees'], $employeeSid, 'reviewee'); 
                        $ReviewerName =getReviewName($review['reviewees'], $conductorSid, 'reviewer');
                    ?>
                    <div>
                        <?php $answers = []; ?>
                        <?php foreach ($review['questions'] as $k => $question) { ?>
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="cs-question-row">
                                        <h5><strong>Q <?= $k + 1 ?>: <?= stripslashes($question['question']); ?></strong></h5>
                                        <h5><?= stripslashes($question['description']); ?></h5>
                                        <?php echo getQuestion($question, $answers); ?>
                                        <div class="clearfix"></div>
                                        <div>
                                            <br />
                                            <h5><?=$ReviewerName;?> -> <?=$RevieweeName;?></h5>
                                        </div>
                                        <hr />
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="<?= base_url('assets/js/moment.min.js'); ?>"></script>
    <script type="text/javascript" src="<?php echo base_url('assets/js/jquery-1.11.3.min.js'); ?>"></script>
    <script type="text/javascript" src="<?php echo base_url('assets/employee_panel/js/kendoUI.min.js'); ?>"></script>
    <script type="text/javascript">
        function exportPDF(target) {
            var draw = kendo.drawing;
            draw.drawDOM(target, {
                    avoidLinks: false,
                    paperSize: "A4",
                    multiPage: true,
                    margin: {
                        bottom: "1cm"
                    },
                    scale: 0.8
                })
                .then(function(root) {
                    return draw.exportPDF(root);
                })
                .done(function(data) {
                    // $('#myiframe').attr("src", data);
                    kendo.saveAs({
                        dataURI: data,
                        fileName: 'performance_review_<?=str_replace('/[^a-Z]/', '_', $RevieweeName) . '_' . str_replace('/[^a-Z]/', '_', $ReviewerName).'_'. date('Y_m_d_H_i_s');?>.pdf',
                    });
                });
        }

        if ("<?= $action; ?>" == 'download') {
            //
            $(window).load(() => {
                exportPDF($('#js-export-pdf'));
            });
        } else {
            window.print();
        }
    </script>

</body>

</html>