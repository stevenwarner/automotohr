<div class="main-content">
    <div class="dashboard-wrp">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-8">
                    
                    <!-- Google chart script -->
                    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>



                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                            <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                            <?php $this->load->view('manage_employer/employee_management/employee_profile_ats_view_top'); ?>
                            <div class="page-header-area margin-top">
                                <span class="page-heading down-arrow">
                                    <a class="dashboard-link-btn" href="<?php echo $top_bar_link_url; ?>"><i class="fa fa-chevron-left"></i><?php echo $top_bar_link_title; ?></a>
                                    <?php echo $title; ?></span>
                            </div>
                        </div>
                    
                        <!-- Graphs -->
                        <div>
                            <!-- Titles Graph -->
                            <div class="col-sm-4">
                                <div id="jsTitleChart"></div>
                            </div>
                            <!-- Performance Graph -->
                            <div class="col-sm-8">
                                <div id="jsPerformanceChart"></div>
                            </div>
                        </div>

                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                            <div class="panel panel-success">
                                <div class="panel-heading" style="background-color: #81b431; color: #ffffff;"><strong>Search Performance Review</strong></div>
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6 custom-col">
                                            <div class="form-group full-width">
                                                <input type="text" placeholder="Search Review by Title" name="keyword" class="invoice-fields" id="search_review" value="">
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6 custom-col">
                                            <div class="form-group full-width">
                                                <div >
                                                    <select class="invoice-fields" name="emp_sid" id="emp_sid">
                                                        <option value="0">Select Reviewer</option>
                                                        <?php if (!empty($active_employees)) { ?>
                                                            <?php foreach ($active_employees as $active_employee) { ?>
                                                                <option value="<?php echo $active_employee['sid']; ?>"><?php echo $active_employee['first_name'].' '.$active_employee['last_name'] ?></option>
                                                            <?php } ?>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-6 custom-col">
                                            <span class="pull-right">
                                                <a class="btn btn-success" href="javascript:;" id="filter-btn" name="filter-btn">Filter</a>
                                                <a class="btn btn-success" href="<?php echo base_url('performance_review').'/'.$employer['sid']; ?>">Clear</a>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                         
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                            <div class="table-responsive table-outer">
                                <table class="table table-striped table-bordered">
                                    <thead>
                                        <th>Reviewee Title</th>
                                        <th>Reviewer(s)</th>
                                        <th>Status</th>
                                        <th>Date</th>
                                        <th class="col-sm-1">Actions</th>
                                    </thead>
                                    <tbody id='fill_by_ajax'>
                                        <?php if (!empty($employee_reviews)) { ?>
                                            <?php foreach ($employee_reviews as $key => $employee_review) {?>
                                                <tr>
                                                    <td><?php echo $employee_review['title']; ?></td>
                                                    <td><?php echo $employee_review['reviewer_count']; ?></td>
                                                    <td><?php echo ucwords($employee_review['status']); ?></td>
                                                    <td><?php echo  date("d/m/Y", strtotime($employee_review['start_date'])); ?></td>
                                                    <td class="text-center">
                                                        <a href="<?php echo base_url('performance_review/review_detail/').$employee_review['portal_review_sid'].'/'.$employee_review['sid'].'/'.$employer['sid']; ?>" class="btn btn-success btn-xs" title="View Review"><i class="fa fa-eye"></i></a>
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                        <?php } else { ?> 
                                            <tr>
                                                <td colspan="5">
                                                    <p class="alert alert-info text-center">No records found.</p>
                                                </td>
                                            </tr>
                                        <?php } ?>  
                                    </tbody>
                                </table>
                            </div>
                        </div>    
                    </div>
                </div>
                <?php $this->load->view($left_navigation); ?>
            </div>
        </div>
    </div>
</div>
<script src="<?= base_url('assets/js/moment.min.js'); ?>"></script>
<script>
    function capitalizeFirstLetter(string) {
        return string.charAt(0).toUpperCase() + string.slice(1);
    }

    $('#emp_sid').select2();

    $('#filter-btn').on('click',function(){
        $('#filter-btn').prop('disabled', true);
        var title = $('#search_review').val();
        var con_sid = $("#emp_sid option:selected").val();
        var emp_sid = <?php echo $employer['sid']; ?>;
        var form_data = new FormData();
        form_data.append('title', title);
        form_data.append('conductor_sid', con_sid);
        form_data.append('employee_sid', emp_sid);

        var base_url = '<?php echo base_url('performance_review/review_detail/'); ?>';
        
        $.ajax({
            url: '<?= base_url('performance_review/ajax_handler') ?>',
            cache: false,
            contentType: false,
            processData: false,
            type: 'post',
            data: form_data,
            success: function (data) {
                if (data != "not_found") {
                    $('#fill_by_ajax').html("");
                    var obj = jQuery.parseJSON(data);
                    $.each(obj, function(i,v){
                        var review_url = base_url+v.reviews_sid+"/"+v.sid+"/"+emp_sid;
                        var row = '';
                        row += "<tr>";

                        row += "<td>"+v.title+"</td>";
                        row += "<td>"+v.reviewer_count+"</td>";
                        row += "<td>"+capitalizeFirstLetter(v.status)+"</td>";
                        row += "<td>"+moment(v.start_date, "YYYY-MM-DD HH:mm:ss").format("MM/DD/YYYY")+"</td>";
                        row += "<td class='col-sm-3 text-center'>";
                        row += "<a href='"+review_url+"' class='btn btn-default' title='View Review'>View Detail</a>";
                        row += "</td>";
                        row += "</tr>";
                        $('#fill_by_ajax').append(row);
                    })
                }  else {
                    alertify.alert('Notice!', 'No Record Found');
                }
            },
            error: function () {
            }
        });
    })

    // Google chart load
    google.load("visualization", "1", {packages: ["corechart"]});
    google.setOnLoadCallback(drawTitleChart);

    function drawTitleChart() {
        let titles = <?=json_encode($report['Title']);?>;
        data = google.visualization.arrayToDataTable(titles);
        let options = {
            title: 'Review Assigned',
            is3D: true,
            legend: {position: 'top', maxLines: 3}
        };
        let chart = new google.visualization.PieChart(document.getElementById('jsTitleChart'));
        chart.draw(data, options);
    }

    google.setOnLoadCallback(drawPerformanceChart);

    function drawPerformanceChart() {
        let titles = <?=json_encode($report['Rating']);?>;
        data = google.visualization.arrayToDataTable(titles);
        let options = {
            title: 'Score',
            is3D: true,
            legend: {position: 'top', maxLines: 3}
        };
        let chart = new google.visualization.BarChart(document.getElementById('jsPerformanceChart'));
        chart.draw(data, options);
    }
</script>
