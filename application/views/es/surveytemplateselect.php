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
                        </span>
                    </div>

                    <div class="panel panel-default _csMt10">
                        <div class="panel-body">
                            <!-- Basic -->

                            <div class="row">

                                <div class="col-xs-12">
                                    <p class="_csF14 _csB2"><b>Title</b></p>
                                    <input type="text" class="form-control jsReviewText" id="templatetitle" />
                                </div>

                            </div>

                            <div class="row _csMt10">
                                <div class="col-xs-12">
                                    <p class="_csF14 _csB2"><b>Description</b></p>
                                    <textarea rows="5" class="form-control jsReviewText" id="templatedescription"></textarea>
                                </div>

                            </div>

                            <div class="row _csMt10">

                                <div class="col-sm-3 col-xs-12">
                                    <label>Start Date <span class="cs-required">*</span></label>
                                    <div class="">
                                        <input type="text" class="form-control " id="jsfromdate" readonly="true">
                                    </div>
                                </div>

                                <div class="col-sm-3 col-xs-12">
                                    <label>End Date <span class="cs-required">*</span></label>
                                    <div class="">
                                        <input type="text" class="form-control" id="jstodate" readonly="true">
                                    </div>
                                </div>

                            </div>


                            <div class="row _csMt10">
                                <div class="col-xs-12">
                                    <span class="pull-right">
                                        <button class="btn _csB4 _csF2 _csR5  _csF16" id="btnsave">&nbsp; Save </button>
                                    </span>
                                </div>
                                <div class="clearfix"></div>
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
<link href="https://code.jquery.com/ui/1.10.4/themes/ui-lightness/jquery-ui.css" rel="stylesheet">


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
                   
                })

                $("#templatetitle").val(templateTitle);
                $("#templatedescription").val(templateDetails);

                $('#loader_text_div').text('');
                $('#document_loader').hide();

            },
            error: function() {

            }
        });
    });



    $(function() {
        $("#jsfromdate").datepicker({
            showWeek: true,
            yearSuffix: "-CE",
        });
        $("#jstodate").datepicker({
            showWeek: true,
            yearSuffix: "-CE",
        });


    });


    $("#btnsave").click(function() {
        var templateTitle = $("#templatetitle").val();
        var templateDetails = $("#templatedescription").val();
        var templateStartDate = $("#jsfromdate").val();
        var templateEndDate = $("#jstodate").val();

        if (templateTitle == '') {
            alert("Please Enter Template Title");
            return false;
        }
        if (templateDetails == '') {
            alert("Please Enter Template Details");
            return false;
        }
        if (templateStartDate == '') {
            alert("Please Enter Start Date");
            return false;

        }
        if (templateEndDate == '') {
            alert("Please Enter End Date");
            return false;
        }

        var employee_code = '<?php echo $employer_id; ?>';
        var template_code = '<?php echo $templateId; ?>';



        var templetdata = {
            'title': templateTitle,
            'start_date': moment(templateStartDate).format('YYYY-MM-DD'),
            'end_date': moment(templateEndDate).format('YYYY-MM-DD'),
            'description': templateDetails,
            'employee_code': employee_code,
            'template_code': template_code
        };


        $.ajax({
            type: 'POST',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            url: 'http://localhost:3000/employee_survey/<?= $company_id; ?>/survey',
            data: JSON.stringify(templetdata),
            dataType: 'json',
            beforeSend: function() {
                $('#loader_text_div').text('Processing');
                $('#document_loader').show();
            },
            success: function(res) {
                if (res.id != '') {
                    alertify.success('Survey Template Saved Sucessfully.');
                }

                $('#loader_text_div').text('');
                $('#document_loader').hide();

            },
            error: function() {

            }
        });





    });
</script>