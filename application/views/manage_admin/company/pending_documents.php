<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
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
                                        <h1 class="page-title" style="width: 100%;"><i class="fa fa-file" aria-hidden="true"></i>Pending Documents</h1>
                                    </div>
                                    <hr>
                                    <!-- Main Page -->
                                    <div id="js-main-page" class="js-block">
                                        <div class="row">
                                            <div class="col-sm-10">
                                                <label>Companies</label>
                                                <select id="jsCompanies" style="width: 100%;">
                                                    <option value="0"></option>
                                                    <?php
                                                        if(!empty($companies)):
                                                            foreach($companies as $company):
                                                                ?>
                                                                <option value="<?=$company['sid'];?>"><?=$company['CompanyName'];?></option>
                                                                <?php
                                                            endforeach;
                                                        endif;
                                                    ?>
                                                </select>
                                            </div>
                                            <div class="col-sm-2">
                                                <br>
                                                <button class="btn btn-success form-control jsFetch" style="margin-top: 5px">Fetch </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <hr>

                            <div id="jsBody"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .my_loader{ display: block; position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 99; background-color: rgba(0,0,0,.7); }
    .loader-icon-box{ position: absolute; top: 50%; left: 50%; width: auto; z-index: 9999; -webkit-transform: translate(-50%, -50%); -moz-transform: translate(-50%, -50%); -ms-transform: translate(-50%, -50%); -o-transform: translate(-50%, -50%); transform: translate(-50%, -50%); }
    .loader-icon-box i{ font-size: 14em; color: #81b431; }
    .loader-text{ display: inline-block; padding: 10px; color: #000; background-color: #fff !important; border-radius: 5px; text-align: center; font-weight: 600; }
</style>

<!-- Loader -->
<div id="jsEmployeeEmailLoader" class="text-center my_loader">
    <div id="file_loader" class="file_loader cs-loader-file" style="display: none; height: 1353px;"></div>
    <div class="loader-icon-box cs-loader-box">
        <i class="fa fa-refresh fa-spin my_spinner" aria-hidden="true" style="visibility: visible;"></i>
        <div class="loader-text cs-loader-text jsLoaderText"  id="js-loader-text" style="display:block; margin-top: 35px;">Please wait while we generate a preview...
        </div>
    </div>
</div>

<style>
    #js-job-list-block{ display: none; }
    .cs-required{ font-weight: bolder; color: #cc0000; }
    /* Alertify CSS */
    .ajs-header{ background-color: #81b431 !important; color: #ffffff !important; }
    .ajs-ok{ background-color: #81b431 !important; color: #ffffff !important; }
    .ajs-cancel{ background-color: #81b431 !important; color: #ffffff !important; }
</style>

<script>

    $(function(){
        //
        $('#jsCompanies').select2();
        //
        $('.jsFetch').click(function(event){
            //
            event.preventDefault();
            //
            var companyId = $('#jsCompanies').val();
            //
            if(companyId == 0){
                $('#jsBody').html('');
                console.log('Clear view');
                return;
            }
            //
            loader(true, 'Please wait, while we are fetching employees.');
            //
            $.get("<?=base_url('manage_admin/Pending_documents/get_employees');?>/"+companyId)
            .done(function(resp){
                $('#jsBody').html(resp.view);
                loader(false);
            });
        });





        //
        loader(false);
        //
        function loader(doShow, text){
            //
            if(doShow){
                $('#jsEmployeeEmailLoader').show(0);
                $('#jsEmployeeEmailLoader .jsLoaderText').html(text);
            } else{
                $('#jsEmployeeEmailLoader').hide(0);
                $('#jsEmployeeEmailLoader .jsLoaderText').html('Please wait, while we are processing your request.');
            }
        }
    });

</script>