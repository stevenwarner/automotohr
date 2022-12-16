<div class="main-content">
    <div class="dashboard-wrp">
        <div class="container-fluid">
            <div class="row">
                <!--  -->
                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-12">
                </div>
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-12">
                    <div class="dashboard-conetnt-wrp">
                        <div class="page-header-area">
                            <span class="page-heading down-arrow"><?php echo $title; ?>
                                <a class="dashboard-link-btn-right" href="<?php echo base_url("manage_admin/complynet/report") ?>"><i class="fa fa-bar-chart"></i> Report</a>
                            </span>
                        </div>


                        <hr />

                        <div class="panel panel-success companyBasicInfo">
                            <div class="panel-heading">
                                <h4 style="padding: 0; margin: 0;"><strong>Basic Information</strong></h4>
                            </div>
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-sm-12">

                                        <label>Companies</label>
                                        <select id="automotoHRCompany">
                                            <option value="0">Select Location</option>
                                            <?php foreach ($allcompanies as $companiesRow) { ?>
                                                <option value="<?php echo $companiesRow['sid']?>"><?php echo $companiesRow['CompanyName']?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>


                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="table-responsive">
                                            <label>ComplyNet location</label>
                                            <select id="complyNetLocation">
                                                <option value="0">Select Location</option>
                                                <?php foreach ($locations as $locationRow) { ?>
                                                    <option value="<?php echo $locationRow['Id']; ?>"><?php echo $locationRow['Name']; ?></option>
                                                <?php } ?>
                                            </select>

                                        </div>
                                    </div>
                                </div>


                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="table-responsive">
                                            <div class="col-sm-12 text-right">
                                                <br><button class="btn btn-success jsSaveLinkLocation">Save</button> <button class="btn btn-black jsModalCancel">Cancel</button>
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
    $(document).ready(function() {
        $('#automotoHRCompany').select2();
        $('#complyNetLocation').select2();
    });
</script>


<script>
    $('.jsSaveLinkLocation').on('click', function() {
        var automotocompanySid = $('#automotoHRCompany').val();
        var complyNetLocationId = $('#complyNetLocation').val();
        var complyNetLocationName = $('#complyNetLocation').text();

        var url = '<?= base_url('testing/complynetSync') ?>';

        $.ajax({
            url: url,
            cache: false,
            type: 'post',
            data: {
                automotocompany_sid: automotocompanySid,
                complyNetLocation_Id: complyNetLocationId,
                complyNetLocation_Name: complyNetLocationName

            },
            beforeSend: function() {
                $('#loader_text_div').text('Processing');
                $('#document_loader').show();
            },
            success: function(resp) {
                $('#loader_text_div').text('');
                $('#document_loader').hide();
                alert(resp);
                alertify.alert('SUCCESS!', 'Successfully', function() {});

            },
            error: function() {}
        });

    });


    $(function() {
        if ($('.js-uncompleted-docs tbody tr').length == 0) {
            $('.js-uncompleted-docs').html('<h1 class="section-ttile text-center"> No Document Assigned! </h1>');
        }
    });
</script>