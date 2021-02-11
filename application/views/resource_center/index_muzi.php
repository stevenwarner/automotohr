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
                        <div class="full-width resource-nav">
                            <div class="dropdown">
                                <button class="btn btn-success dropdown-toggle" type="button" id="toppicsMenu" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                    <i class="fa fa-align-left"></i> Topics
                                    <span class="fa fa-angle-down"></span>
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="toppicsMenu">
                                    <li><a href="javascript:;">Topic 1</a></li>
                                    <li><a href="javascript:;">Topic 2</a></li>
                                    <li><a href="javascript:;">Topic 3</a></li>
                                    <li><a href="javascript:;">Topic 4</a></li>
                                </ul>
                            </div>
                            <div class="dropdown">
                                <button class="btn btn-default dropdown-toggle" type="button" id="lawMenu" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                    <i class="fa fa-gavel"></i> Law
                                    <span class="fa fa-angle-down"></span>
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="lawMenu">
                                    <li><a href="javascript:;">At a Glance</a></li>
                                    <li><a href="javascript:;">Federal Laws</a></li>
                                    <li><a href="javascript:;">Law Finder</a></li>
                                    <li><a href="javascript:;">State Laws</a></li>
                                </ul>
                            </div>
                            <div class="dropdown">
                                <button class="btn btn-default dropdown-toggle" type="button" id="learningMenu" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                    <i class="fa fa-graduation-cap"></i> Learning
                                    <span class="fa fa-angle-down"></span>
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="learningMenu">
                                    <li><a href="javascript:;">At a Glance</a></li>
                                    <li><a href="javascript:;">Federal Laws</a></li>
                                    <li><a href="javascript:;">Law Finder</a></li>
                                    <li><a href="javascript:;">State Laws</a></li>
                                </ul>
                            </div>
                            <div class="dropdown">
                                <button class="btn btn-default dropdown-toggle" type="button" id="toolsMenu" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                    <i class="fa fa-gears"></i> Tools
                                    <span class="fa fa-angle-down"></span>
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="toolsMenu">
                                    <li><a href="javascript:;">At a Glance</a></li>
                                    <li><a href="javascript:;">Federal Laws</a></li>
                                    <li><a href="javascript:;">Law Finder</a></li>
                                    <li><a href="javascript:;">State Laws</a></li>
                                </ul>
                            </div>
                            <div class="dropdown">
                                <button class="btn btn-default dropdown-toggle" type="button" id="documentsMenu" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                    <i class="fa fa-file-text-o"></i> Documents
                                    <span class="fa fa-angle-down"></span>
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="documentsMenu">
                                    <li><a href="javascript:;">At a Glance</a></li>
                                    <li><a href="javascript:;">Federal Laws</a></li>
                                    <li><a href="javascript:;">Law Finder</a></li>
                                    <li><a href="javascript:;">State Laws</a></li>
                                </ul>
                            </div>
                            <div class="dropdown">
                                <button class="btn btn-default dropdown-toggle" type="button" id="on-demandMenu" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                    <i class="fa fa-wechat"></i> HR On-Demand
                                    <span class="fa fa-angle-down"></span>
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="on-demandMenu">
                                    <li><a href="javascript:;">At a Glance</a></li>
                                    <li><a href="javascript:;">Federal Laws</a></li>
                                    <li><a href="javascript:;">Law Finder</a></li>
                                    <li><a href="javascript:;">State Laws</a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="full-width intro-main">
                            <h3>
                                <span><i class="fa fa-gavel"></i></span> At a Glance
                            </h3>
                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut
                                aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.</p>
                        </div>
                        <div class="panel-group-wrp questionaire-area">
                            <div class="panel-group" id="accordion">
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h4 class="panel-title">
                                            <a class="accordion-toggle collapsed" data-toggle="collapse" href="#collapse_1" aria-expanded="false">
                                                <span class="glyphicon glyphicon-chevron-up"></span>
                                                Minimum Wage Laws
                                            </a>
                                        </h4>
                                    </div>
                                    <div id="collapse_1" class="panel-collapse collapse" aria-expanded="false">
                                        <div class="panel-body">
                                            <div class="attachment-wrp full-width">
                                                <?php
                                                    for ($i = 0; $i <= 10; $i++) {
                                                        ?>
                                                            <article class="file-attachment">
                                                                <a href="<?php echo base_url("resource_center/view_detail"); ?>">
                                                                    <figure><i class="fa fa-file-word-o"></i></figure>
                                                                    <div class="text">test counter: Evaluated for each loop iteration. If it evaluates to TRUE, the loop continues. If it evaluates to FALSE, the loop ends.
                                                                        increment counter: Increases the loop counter value</div>
                                                                </a>
                                                                <div class="btn-action">
                                                                    <a href="javascript:;">View</a>
                                                                    <a href="javascript:;">Download</a>
                                                                </div>
                                                            </article>
                                                        <?php
                                                    }
                                                ?>
                                            </div>
                                            <div class="btn-wrp text-left">
                                                <a href="javascript:;" class="btn btn-success btn-show-all">Show All</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h4 class="panel-title">
                                            <a class="accordion-toggle collapsed" data-toggle="collapse" href="#collapse_2" aria-expanded="false">
                                                <span class="glyphicon glyphicon-chevron-up"></span>
                                                Overtime Laws
                                            </a>
                                        </h4>
                                    </div>
                                    <div id="collapse_2" class="panel-collapse collapse" aria-expanded="false">
                                        <div class="panel-body">
                                            <div class="embed-responsive embed-responsive-16by9">
                                                <iframe class="embed-responsive-item" src="//www.youtube.com/embed/zpOULjyy-n8?rel=0" allowfullscreen></iframe>
                                            </div>
                                            <div class="image-wrp well well-sm">
                                                <img class="img-responsive" src="<?= base_url() ?>assets/images/api_integrations_2.png" />
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