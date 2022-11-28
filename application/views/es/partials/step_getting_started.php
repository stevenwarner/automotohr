<!--  -->
<div class="panel panel-default _csMt20 _csPR _csR5">
    <div class="panel-heading">
        <h3 class="_csF16">Select a Survey Template</h3>
    </div>
    <div class="panel-body">
        <p class="_csF14">Select the best template based on the suggestions below or create your own survey from the ground up.</p>
        <strong class="_csF14 text-danger">Need help getting started your survey? <span class="_csF3">Check out this how-to guide.</span></strong>
        <hr>
        <!--  -->
        <h3 class="_csF14">TEMPLATES</h3>
        <p class="_csF14"><?= STORE_NAME; ?> templates are industry standard and capture the most important aspects of engagement. If you edit any question here, it will impact the comparison capabilities in the future.</p>
        <hr>
        <!--  -->
        <div class="row" id="surveysBoxContainer"></div>

        <hr>
        <div class="row">
            <div class="col-sm-12 _csMt20 _csMb20">
                <label class="control control--checkbox">
                    <input type="checkbox" name="jsCustom" /> Start from scratch
                    <div class="control__indicator"></div>
                </label>
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col-sm-12 text-right">
                <button class="btn _csB1 _csF2 _csR5 _csF14">
                    <i class="fa fa-times-circle _csF14" aria-hidden="true"></i>
                    Cancel
                </button>
                <button class="btn _csB4 _csF2 _csR5 _csF14">
                    <i class="fa fa-long-arrow-right _csF14" aria-hidden="true"></i>
                    Next
                </button>
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


<script type="text/javascript">
            $(document).ready(function() {
                $.ajax({
                    type: 'GET',
                    url: 'http://localhost:3000/employee_survey/templates',
                    beforeSend: function() {
                        $('#loader_text_div').text('Processing');
                        $('#document_loader').show();
                    },
                    success: function(res) {
                        console.log(res)

                        let templateBox = '';

                        res.map(function(template) {

                            templateBox += ' <div class="col-md-4 col-sm-12">';
                            templateBox += ' <div class="csESBox active _csBD _csBD6 _csR5 _csP10 _csMt10">';
                            templateBox += ' <img src="<?= base_url("assets/2022/images/es/enps.svg"); ?>" alt="" />';
                            templateBox += '  <p class="_csF14">' + template.title + '</p><br>';
                            templateBox += '  <dl>';
                            templateBox += '   <dt>Length</dt>';
                            templateBox += '    <dd>' + template.questions_count + ' Questions</dd><br>';
                            templateBox += '   <dt>Suggested frequency</dt>';
                            templateBox += ' <dd>' + template.frequency + '</dd>';
                            templateBox += '</dl><hr>';
                            templateBox += '                    <div class="text-center">';
                            templateBox += '    <a href="<?= base_url("employee/surveys/templatedetail");?>/1" class="btn _csR5 _csB3 _csF2">Preview</a>';
                            templateBox += '     <a href="<?= base_url("employee/surveys/templateselect");?>/1" class="btn _csR5 _csB4 _csF2">Select</a>';
                            templateBox += '</div></div></div>';

                        })

                        $("#surveysBoxContainer").html(templateBox);
                        $('#loader_text_div').text('');
                        $('#document_loader').hide();

                    },
                    error: function() {

                    }
                });
            });
        </script>