<div class="main-content" xmlns="http://www.w3.org/1999/html" id="mydiv">
    <div class="dashboard-wrp">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-3 col-md-4 col-xs-12 col-sm-5">
                    <?php $this->load->view('resource_center/resource_center_column_left_view'); ?>
                </div>
                <div class="col-lg-9 col-md-8 col-xs-12 col-sm-7">
                    <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                    <div class="page-header-area">
                        <span class="page-heading down-arrow">
                            <?php echo $title;?>
                        </span>
                    </div>
                    <div class="full-width resource-center-content">
                        <div class="full-width intro-main">
                            <h3 style="margin-top: 0;">
                                <span><i class="fa fa-file-word-o"></i></span> Form I-9
                            </h3>
                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut
                                aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.</p>
                            <p>When the user fills out the form above and clicks the submit button, the form data is sent for processing to a PHP file named "welcome.php". The form data is sent with the HTTP POST method.</p>
                        </div>
                        <div class="file-type full-width">
                            <div class="row">
                                <div class="col-lg-2 col-md-2 col-xs-12 col-sm-3"><strong class="form-control">File Type:</strong></div>
                                <div class="col-lg-1 col-md-1 col-xs-12 col-sm-2">
                                    <div class="file-icon form-control"><i class="fa fa-file-pdf-o"></i></div>
                                </div>
                                <div class="col-lg-2 col-md-2 col-xs-12 col-sm-4"><strong class="form-control">File Extension:</strong></div>
                                <div class="col-lg-1 col-md-1 col-xs-12 col-sm-3"><strong class="form-control">pdf</strong></div>
                                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6">
                                    <a href="javascript:;" class="btn btn-success btn-block"><i class="fa fa-download"></i>Download Form</a>
                                </div>
                                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6">
                                    <a href="javascript:;" class="btn btn-success btn-block"><i class="fa fa-search"></i>Preview</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function () {

        $(".btn-show-all").click(function () {
            if ($(this).text() == "Show All") {
                $(this).text("Show Less");
            } else {
                $(this).text("Show All");
            };
            $(this).parent().prev(".attachment-wrp").toggleClass("auto-h");
        });

        $('.collapse').on('shown.bs.collapse', function () {
            $(this).parent().find(".glyphicon-chevron-up").removeClass("glyphicon-chevron-up").addClass("glyphicon-chevron-down");
        }).on('hidden.bs.collapse', function () {
            $(this).parent().find(".glyphicon-chevron-down").removeClass("glyphicon-chevron-down").addClass("glyphicon-chevron-up");
        });
    });
</script>