<div class="main-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-12">
                <?php $this->load->view('main/employer_column_left_view'); ?>
            </div>
            <div class="col-lg-9 col-md-9 col-xs-12 col-sm-12">
                <div class="page-header-area">
                    <span class="page-heading down-arrow"><?php $this->load->view('manage_employer/company_logo_name'); ?>
                        <a href="<?php echo base_url('dashboard'); ?>" class="dashboard-link-btn">
                            <i class="fa fa-chevron-left"></i>Back
                        </a>
                        <?php echo $title; ?>
                    </span>
                    <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                </div>

                <!-- main table view start -->
                <div class="dashboard-conetnt-wrp">
                    <div class="">
                        <?php if(check_access_permissions_for_view($security_details, 'list_departments')) { ?>
                        <a class="btn btn-success" href="<?php echo base_url('organizational_hierarchy/departments'); ?>">Manage Departments</a>
                        <?php } ?>
                        <?php if(check_access_permissions_for_view($security_details, 'list_positions')) { ?>
                        <a class="btn btn-success" href="<?php echo base_url('organizational_hierarchy/positions'); ?>">Manage Positions</a>
                        <?php } ?>
                        <?php if(check_access_permissions_for_view($security_details, 'list_vacancies')) { ?>
                        <a class="btn btn-success" href="<?php echo base_url('organizational_hierarchy/vacancies'); ?>">Manage Vacancies</a>
                        <?php } ?>
                    </div>
                    <hr />
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="hr-box">
                                <div class="hr-box-header bg-header-green">
                                    <h1 class="hr-registered pull-left">
                                        <span class=""><?php echo $subtitle; ?></span>
                                    </h1>
                                </div>
                                <div class="clear"></div>
                                <div class="table-responsive">
                                    <div id="chart-container"></div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- main table view end -->
            </div>
        </div>
    </div>
</div>


<script>

    function initOrgchart(rootClass, id = '') {
        $('#chart-container').orgchart({
            'parentNodeSymbol': '',
            'chartClass': rootClass,
            'data' : '<?php echo base_url('organizational_hierarchy/ajax_responder/get_json_data')?>/' + rootClass + '/' + id,
            'nodeContent': 'dept',
            'createNode': function($node, data) {
                if ($node.is('.drill-down')) {
                    var assoClass = data.className.match(/asso-\w+/)[0];
                    var drillDownIcon = $('<i>', {
                        'class': 'fa fa-arrow-circle-down drill-icon',
                        'click': function() {
                            $('#chart-container').find('.orgchart:visible').addClass('hidden');
                            if (!$('#chart-container').find('.orgchart.' + assoClass).length) {
                                initOrgchart(assoClass, data.id);
                            } else {
                                $('#chart-container').find('.orgchart.' + assoClass).removeClass('hidden');
                            }
                        }
                    });
                    $node.append(drillDownIcon);




                } else if ($node.is('.drill-up')) {
                    var assoClass = data.className.match(/asso-\w+/)[0];
                    var drillUpIcon = $('<i>', {
                        'class': 'fa fa-arrow-circle-up drill-icon',
                        'click': function() {
                            $('#chart-container').find('.orgchart:visible').addClass('hidden').end()
                                .find('.drill-down.' + assoClass).closest('.orgchart').removeClass('hidden');
                        }
                    });
                    $node.append(drillUpIcon);
                } else if( $node.is('.org_chart_employee')){
                    var secondMenuIcon = $('<i>', {
                        'class': 'fa fa-info-circle second-menu-icon',
                        click: function() {
                            $(this).siblings('.second-menu').toggle();
                        }
                    });
                    var secondMenu = '<figure class="second-menu"><img class="avatar img-circle" src="' + data.profile_picture + '"></figure>';
                    $node.append(secondMenuIcon).append(secondMenu);
                }
            }
        });
    }



    $(document).ready(function () {

        initOrgchart('root-node');

        /*
        $('#chart-container').orgchart({
            'data' : <?php echo $chart_data; ?>,
            'nodeContent': 'title'
        });
        */

    });


</script>