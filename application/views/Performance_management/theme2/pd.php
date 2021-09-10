<?php
    $filename = stringToSlug(
        $review['review_title']."_".
        remakeEmployeeName($review['Reviewee']).'_'.
        remakeEmployeeName($review['Reviewer']).'_'.
        formatDate($review['review_start_date'], 'Y-m-d', DATE).'_'.
        formatDate($review['review_end_date'], 'Y-m-d', DATE)
    );
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <link rel="shortcut icon" href="<?= base_url() ?>assets/images/favi-icon.png" type="image/x-icon" />
        <link rel="stylesheet" type="text/css" href="<?php echo site_url('assets/manage_admin/css/bootstrap.css'); ?>">
        <link rel="stylesheet" type="text/css" href="<?php echo site_url('assets/manage_admin/css/style.css'); ?>">
        <title>Download</title>
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

    <section class="sheet padding-10mm" id="js-preview">
        <div class="form-wrp">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-xs-6">
                        <p><strong>Title:</strong><br> <?=$review['review_title'];?></p>
                        <p><strong>Reviewee:</strong><br> <?=remakeEmployeeName($review['Reviewee']);?></p>
                        <p><strong>Reviewer:</strong><br> <?=remakeEmployeeName($review['Reviewer']);?></p>
                    </div>
                    <div class="col-xs-6">
                        <p><strong>Period:</strong><br><?=formatDate($review['review_start_date'], 'Y-m-d', DATE);?> - <?=formatDate($review['review_end_date'], 'Y-m-d', DATE);?></p>
                        <p><strong>Type:</strong><br><?=$isManager ? 'Reporting Manager' : 'Reviewer';?></p>
                    </div>
                </div>
                <?php
                    foreach($review['QA'] as $index => $question){
                        ?>
                        <!--  -->
                        <div class="row">
                            <br>
                            <div class="col-xs-12">
                                <div class="panel panel-success">
                                    <div class="panel-heading" style="padding-bottom: 0">
                                        <p><strong>Q<?=$index;?>: <?=$question['question']['title'];?></strong></p>
                                    </div>
                                    <div class="panel-body">
                                        <p><?=$question['question']['description'];?></p>
                                        <p><strong>Multiple Choice:</strong> <?=!empty($question['answer']['mutiple_choice']) ? $question['answer']['mutiple_choice'] : 'N/A';?></p>
                                        <p><strong>Rating:</strong> <?=!empty($question['answer']['rating']) ? $question['answer']['rating'] : 'N/A';?></p>
                                        <p><strong>Feedback:</strong> <?=!empty($question['answer']['text']) ? $question['answer']['text'] : 'N/A';?></p>
                                        <p><strong>Attachment(s):</strong>  <?=!empty($question['attachments']) ? implode(',', json_decode($question['attachments'], true)) : 'N/A';?></p>
                                        <!--  -->
                                        <?php 
                                            if(!empty($question['other_answers'])){
                                                ?>
                                                <hr>
                                                <p><strong>Reviewer(s) Feedback</strong></p>
                                                <?php
                                                foreach($question['other_answers'] as $other_answer){
                                                    ?>
                                                    <hr>
                                                    <p><strong>Reviewer:</strong> <?=$other_answer['reviewer_sid'];?></p>
                                                    <p><strong>Multiple Choice:</strong> <?=!empty($other_answer['mutiple_choice']) ? $other_answer['mutiple_choice'] : 'N/A';?></p>
                                                    <p><strong>Rating:</strong> <?=!empty($other_answer['rating']) ? $other_answer['rating'] : 'N/A';?></p>
                                                    <p><strong>Feedback:</strong> <?=!empty($other_answer['text']) ? $other_answer['text'] : 'N/A';?></p>
                                                    <p><strong>Attachment(s):</strong>  <?=!empty($other_answer['attachments']) ? implode(',', json_decode($other_answer['attachments'], true)) : 'N/A';?></p>
                                                    <?php
                                                }
                                            }
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                    
                    //
                    if($isManager){
                        ?>
                         <!--  -->
                         <div class="row">
                            <br>
                            <div class="col-xs-12">
                                <div class="panel panel-success">
                                    <div class="panel-heading" style="padding-bottom: 0">
                                        <p><strong>Overall Feedback</strong></p>
                                    </div>
                                    <div class="panel-body">
                                        <p><strong>Multiple Choice:</strong> <?=!empty($review['Feedback']['mutiple_choice']) ? $review['Feedback']['mutiple_choice'] : 'N/A';?></p>
                                        <p><strong>Rating:</strong> <?=!empty($review['Feedback']['rating']) ? $review['Feedback']['rating'] : 'N/A';?></p>
                                        <p><strong>Feedback:</strong> <?=!empty($review['Feedback']['text']) ? $review['Feedback']['text'] : 'N/A';?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                ?>
            </div>
        </div>
    </section>

        <!--  -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.0.487/pdf.min.js"></script>
        <script type="text/javascript" src="<?php echo base_url('assets/employee_panel/js/kendoUI.min.js'); ?>">
        </script>

        <script id="script">
            if("<?=$action;?>" == 'download') {
                var draw = kendo.drawing;
                draw.drawDOM(
                    $("#js-preview"), {
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
        
                        $('#myiframe').attr("src",data);
                        kendo.saveAs({
                            dataURI: pdf,
                            fileName: "<?=$filename;?>.pdf",
                        });
                        window.close();
                    });
            } else{
                
                $(window).on( "load", function() { 
                    setTimeout(function(){
                        window.print();
                    }, 2000);  
                });
                //
                window.onafterprint = function(){
                    window.close();
                }

            }
        </script>
    </body>

</html>
