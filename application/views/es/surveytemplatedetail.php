<!-- Main page -->
<div class="csPage">
    <!--  -->
    <?php $this->load->view('es/partials/navbar'); ?>
    <!--  -->
    <div class="_csMt10">
        <div class="container-fluid">
            <!--  -->
            <div class="row">
              
                <!--  -->
                <div class="col-md-12 col-sm-12">
                    <!--  -->
                    <div class="row">
                        <span class="col-sm-12 text-right">
                            <a href="#" target="blank" class="btn _csB4 _csF2 _csR5  _csF16"><i class="fa fa-print" aria-hidden="true"></i>&nbsp; Print</a>
                            <a href="#" target="blank" class="btn _csB4 _csF2 _csR5  _csF16"><i class="fa fa-download" aria-hidden="true"></i>&nbsp; Download</a>
                        </span>
                    </div>

                    <div class="panel panel-default _csMt10">
                        <div class="panel-body">
                            <!-- Basic -->
                            <div class="row">
                                <div class="col-md-12 col-xs-12">
                                    <div>
                                        <p class="_csF16 _csB2" id="templatetitle"></p>
                                        <p class="_csF14" id="templatedetails"></p>
                                    </div>
                                </div>

                            </div>

                        </div>
                    </div>

                    <!-- Question Screen -->
                    <div id="templatequestions"> </div>

                </div>
            </div>
        </div>
    </div>
</div>



<!-- Loader Start -->
<div id="document_loader" class="text-center my_loader" style="display: none; z-index: 1234;">
    <div id="file_loader" class="file_loader" style="display:block; height:1353px;"></div>
    <div class="loader-icon-box">
        <i aria-hidden="true" class="fa fa-refresh fa-spin my_spinner" style="visibility: visible;"></i>
        <div class="loader-text" id="loader_text_div" style="display:block; margin-top: 35px;"></div>
    </div>
</div>
<!-- Loader End -->


<?php $this->load->view('2022/footer_scripts_2022'); ?>



<script type="text/javascript">
    $(document).ready(function() {
        $.ajax({
            type: 'GET',
            url: 'http://localhost:3000/employee_survey/<?= $templateId; ?>/template',
            beforeSend: function() {
                $('#loader_text_div').text('Processing');
                $('#document_loader').show();
            },
            success: function(res) {
                console.log(res)

                let questionBox = '';
                let templateTitle = '';
                let templateDetails = '';

                res.map(function(template) {
                    templateTitle = template.title;
                    templateDetails = template.description;
                    const objquestions = JSON.parse(template.questions);
                    $qi = 1;
                    objquestions.map(function(templatequestions) {

                        questionBox += '<div class="row">';
                        questionBox += '<div class="col-xs-12">';
                        questionBox += '<div class="panel panel-theme"> <!-- feedback -->';
                        questionBox += ' <div class="panel-heading _csB1">';
                        questionBox += ' <p class="_csF14 _csF2">';
                        questionBox += '  <b> QUESTION ' + $qi + '  </b> </p>';
                        questionBox += '         </div>';
                        questionBox += '            <div class="panel-body"><!-- Description -->';
                        questionBox += '   <div class="row">';
                        questionBox += '                    <div class="col-md-8 col-xs-12">';
                        questionBox += '                        <p class="_csF14">';
                        questionBox += '                            ' + templatequestions.text + '</p>';
                        questionBox += '                       </div>';
                        questionBox += '       </div><!-- Rating -->';


                        if (templatequestions.type == 'rating') {

                            questionBox += '        <div class="row"><br>';
                            questionBox += '<ul class="_csRatingBar pl10 pr10">';
                            questionBox += '                       <li data-id="1" class=" active ">';
                            questionBox += '                            <p class="_csF20 _csF2">1</p>';
                            questionBox += '                            <p class="_csF14 _csF2">Strongly Agree</p>';
                            questionBox += '                        </li>';
                            questionBox += '                        <li data-id="2">';
                            questionBox += '                            <p class="_csF20 ">2</p>';
                            questionBox += '                            <p class="_csF14 ">Agree</p>';
                            questionBox += '                      </li>';
                            questionBox += '                        <li data-id="3">';
                            questionBox += '                            <p class="_csF20">3</p>';
                            questionBox += '                            <p class="_csF14 ">Neutral</p>';
                            questionBox += '                        </li>';
                            questionBox += '                        <li data-id="4">';
                            questionBox += '                            <p class="_csF20 ">4</p>';
                            questionBox += '                            <p class="_csF14 ">Disagree</p>';
                            questionBox += '                        </li>';
                            questionBox += '                        <li data-id="5">';
                            questionBox += '                            <p class="_csF20 ">5</p>';
                            questionBox += '                            <p class="_csF14 ">Strongly Disagree</p>';
                            questionBox += '                        </li>';
                            questionBox += '                    </ul>';
                            questionBox += '                </div><!-- Text -->';
                        }

                        if (templatequestions.type == 'text') {
                            questionBox += '                <div class="row"><br>';
                            questionBox += '                    <div class="col-xs-12">';
                            questionBox += '                        <p class="_csF14 _csB2"><b>Feedback (Elaborate)</b></p>';
                            questionBox += '                        <textarea rows="5" class="form-control jsReviewText">' + templatequestions.type + '</textarea>';
                            questionBox += '                   </div></div>';
                        }
                        questionBox += '   </div><!-- End Feedback --></div></div></div>';
                        $qi++;

                    });
                    console.log(objquestions);

                })

                $("#templatetitle").html('<b>' + templateTitle + '<br>');
                $("#templatedetails").html(templateDetails);
                $("#templatequestions").html(questionBox);

                $('#loader_text_div').text('');
                $('#document_loader').hide();

            },
            error: function() {

            }
        });
    });
</script>