<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Download</title>
    <link rel="stylesheet" type="text/css" href="<?= base_url() ?>assets/css/bootstrap.css">
    <link rel="stylesheet" href="<?=base_url('assets/css/theme-2021.css?v='.time());?>">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.0.487/pdf.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url('assets/employee_panel/js/kendoUI.min.js'); ?>"></script>
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
            <div class="csPageWrap">
                <div class=" csPageBox csRadius5">
                    <!-- Header -->
                    <div class="csPageBoxHeader pl10">
                        <h4><strong>Review Title: <?=$review['review_title'];?></strong></h4>
                        <h4><strong>Reviewee: <?=$employees[$review['Reviewee'][0]['reviewee_sid']]['name'];?></strong></h4>
                        <h4><strong>Reviewer: <?=$employees[$review['Reviewer'][0]['reviewer_sid']]['name'];?></strong></h4>
                    </div>
                    <!-- Body -->
                    <div class="csPageBoxBody p10">
                        <!-- Loader -->
                        <div class="csIPLoader jsIPLoader dn" data-page="review_listing"><i class="fa fa-circle-o-notch fa-spin"></i></div>
                        <?php 
                            $answers = [];
                            foreach($review['Questions'] as $key => $question):
                            $ques = json_decode($question['question'], true);
                            $answ = json_decode($question['answer'], true);
                            //
                            if(!empty($answ)) {
                                $answers[$question['sid']] = $answ;
                            }
                        ?>
                        <div class="csFeedbackViewBox">
                            <h4 class="pa10 pb10"><strong>Question <?=$key +1;?></strong></h4>
                            
                            <h4><strong><?=$ques['title'];?></strong></h4>
                            <?php if(!empty($ques['description'])): ?>
                            <p><?=$ques['description'];?></p>
                            <?php endif;?>
                            <div class="jsQuestionBox" data-id="<?=$question['sid'];?>">
                                <?php echo getQuestionBody($ques, $answ); ?>
                            </div>
                            <!--  -->
                            <!-- <div class="clearfix"></div> -->
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            </div>
        </div>
    </div>


    <script>
    //
    function generatePDF(target){
        //
        let draw = kendo.drawing;
        //
        draw.drawDOM(target, {
            avoidLinks: true,
            paperSize: "A4",
            margin: { bottom: "1cm", right: "1cm" },
            scale: 0.6
        })
        .then(function(root) {
            return draw.exportPDF(root);
        })
        .done(function(pdfdata) {
            var pdf;
            pdf = pdfdata;
            kendo.saveAs({
                dataURI: pdf,
                fileName: "review.pdf",
            });
                    // window.close();
        });
    }
    // window.print();
    generatePDF($('.container'));
    </script>
</body>
</html>