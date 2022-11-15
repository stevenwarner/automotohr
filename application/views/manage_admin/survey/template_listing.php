<style>
    .iTngJm.iTngJm.iTngJm.iTngJm {
        font-size: 12px;
        line-height: 1.33;
        font-weight: 400;
        text-transform: none;
        letter-spacing: normal;
        color: rgb(153, 153, 153);
        margin-bottom: 8px;
    }

    .engagement-app-style-root *,
    #pendo-base * {
        box-sizing: border-box;
    }


    .hwufzW.hwufzW.hwufzW.hwufzW {
        display: flex;
    }


    .bBHhrE.bBHhrE.bBHhrE.bBHhrE {
        cursor: pointer;
    }



    .uDWQG.uDWQG.uDWQG.uDWQG {
        margin-left: 8px;
        margin-right: 8px;
        display: flex;
        border-left-color: inherit;
        border-left-width: 1px;
        border-left-style: solid;
    }
    .dash-box{
        min-height: 230px !important;
    }
    .post-title{
        min-height: 50px !important;
    }
</style>


<div class="main">
    <div class="container-fluid">
        <div class="row">
            <div class="inner-content">
                <?php $this->load->view('templates/_parts/admin_column_left_view'); ?>
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-9 no-padding">
                    <div class="dashboard-content">
                        <div class="dash-inner-block">

                       
                            <div class="row">
                                <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                    <div class="heading-title page-title">
                                        <h1 class="page-title"><i class="fa "></i>Select a Survey Template</h1>
                                    </div>
                                    <div class="hr-box">
                                        <div class="hr-box-header bg-header-green">
                                            <span class="pull-left">
                                                <h1 class="hr-registered">Templates</h1>
                                            </span>
                                        </div>

                         
                                        <div class="hr-innerpadding">

                                            <div class="row">
                                                <div class="col-xs-12">
                                                    <div class="table-responsive">

                                                        <?php if (!empty($surveytemplates)) {
                                                            foreach ($surveytemplates as $templetRow) { ?>
                                                                <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">
                                                                    <div class="dash-box">
                                                                        <div class="dashboard-widget-box">
                                                                            <h2 class="post-title" style="margin: 2px 0;">
                                                                                <a href="<?php echo base_url('survey'); ?>"><?php echo $templetRow['title']?></a>
                                                                            </h2>
                                                                            <hr>

                                                                            <div class="sc-jWBwVP iTngJm">
                                                                                <div class="sc-hMqMXs cPxEnv sc-kEYyzF hwufzW">Length <div data-component="Templates_TagSeparator" class="sc-hMqMXs cPxEnv sc-kEYyzF uDWQG"></div> Suggested frequency</div>
                                                                            </div>

                                                                            <div class="sc-jWBwVP iTngJm">
                                                                                <div class="sc-hMqMXs cPxEnv sc-kEYyzF hwufzW" style="color: #000;"><?php echo $templetRow['questions_count']?> Questions <div data-component="Templates_TagSeparator" class="sc-hMqMXs cPxEnv sc-kEYyzF uDWQG"></div> <?php echo $templetRow['frequency']?></div>
                                                                            </div>
                                                                            <hr>

                                                                            <div>
                                                                                <a class="btn btn-success btn-block" href="#"><i class="fa fa-eye" aria-hidden="true"></i> Preview</a>
                                                                            </div>

                                                                        </div>
                                                                    </div>
                                                                </div>

                                                        <?php }
                                                        } ?>

                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
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

<script>
    /*
    var surveyUrl = 'http://localhost:3000/employee_survey/templates';
    $.ajax({
        url: surveyUrl,
        type: 'get',
        crossDomain: true,
         beforeSend: function() {
            $('#loader_text_div').text('Processing');
            $('#document_loader').show();
        },
        success: function(xhr,resp) {
            alert('sdfdddddd');
            $('#loader_text_div').text('');
            $('#document_loader').hide();
            // alertify.alert('SUCCESS!', 'Saved Successfully', function(){
            //  var document_url_view = '<? //= base_url('hr_documents_management/sign_hr_document/d') 
                                            ?>/' + resp;
            //window.location.href = document_url_view;
            //  });

        },
        error: function(xhr,status) {
            alert('Error');
        }
    });


    $(function() {
        if ($('.js-uncompleted-docs tbody tr').length == 0) {
            $('.js-uncompleted-docs').html('<h1 class="section-ttile text-center"> No Document Assigned! </h1>');
        }
    });

    */
</script>