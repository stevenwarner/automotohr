<style>
.custom-tooltip {
    display: block;
    color: #000;
}
</style>
<div class="main-content">
    <div class="dashboard-wrp">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-4">
                    <?php $this->load->view('manage_employer/PerformanceReview/sidebar'); ?>
                </div>

                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-8">
                    <div class="right-content">
                        <!-- Header -->
                        <?php $this->load->view('manage_employer/PerformanceReview/headerBar', [
                            'Link' => [
                                base_url('dashboard'),
                                'Dashboard',
                            ],
                            'Text' => 'Performance Review - Review(s)'
                        ]); ?>

                        <!-- Search Form -->
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                <div class="panel panel-success">
                                    <div class="panel-heading" style="background-color: #81b431; color: #ffffff;">
                                        <strong>Search Performance Review</strong></div>
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
                                                <div class="field-row">
                                                    <label class="">Title</label>
                                                    <input class="form-control" id="js-title"
                                                        value="<?php echo isset($review_title) ? $review_title : ''; ?>"
                                                        <?php echo isset($review_title) ? 'readonly' : ''; ?> />
                                                </div>
                                            </div>
                                            <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
                                                <div class="field-row">
                                                    <label class="">Reviewee</label>
                                                    <select class="invoice-fields" id="selected_reviewee"
                                                        multiple="multiple">
                                                        <option value="0">Select Reviewee</option>
                                                        <?php if (!empty($active_employees)) { ?>
                                                        <?php foreach ($active_employees as $key => $active_employee) { ?>
                                                        <option value="<?php echo $active_employee['sid']; ?>"><?= remakeEmployeeName([
                                                                        'first_name' => $active_employee['first_name'],
                                                                        'last_name' => $active_employee['last_name'],
                                                                        'pay_plan_flag' => $active_employee['pay_plan_flag'],
                                                                        'access_level' => $active_employee['access_level'],
                                                                        'access_level_plus' => $active_employee['access_level_plus'],
                                                                        'job_title' => $active_employee['job_title'],
                                                                    ], true); ?></option>
                                                        <?php } ?>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
                                                <div class="field-row">
                                                    <label class="">Reviewer</label>
                                                    <select class="invoice-fields" id="selected_reviewer"
                                                        multiple="multiple">
                                                        <option value="0">Select Reviewer</option>
                                                        <?php if (!empty($active_employees)) { ?>
                                                        <?php foreach ($active_employees as $key => $active_employee) { ?>
                                                        <option value="<?php echo $active_employee['sid']; ?>"><?= remakeEmployeeName([
                                                                        'first_name' => $active_employee['first_name'],
                                                                        'last_name' => $active_employee['last_name'],
                                                                        'pay_plan_flag' => $active_employee['pay_plan_flag'],
                                                                        'access_level' => $active_employee['access_level'],
                                                                        'access_level_plus' => $active_employee['access_level_plus'],
                                                                        'job_title' => $active_employee['job_title'],
                                                                    ], true); ?></option>
                                                        <?php } ?>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-xs-12 col-sm-8 col-md-8 col-lg-8">
                                            </div>
                                            <div class="col-xs-12 col-sm-2 col-md-2 col-lg-2">
                                                <div class="field-row">
                                                    <label class="">&nbsp;</label>
                                                    <a class="btn btn-success btn-block js-apply-filter-btn"
                                                        id="filter-btn" href="javascript:;">Search</a>
                                                </div>
                                            </div>
                                            <div class="col-xs-12 col-sm-2 col-md-2 col-lg-2">
                                                <div class="field-row">
                                                    <label class="">&nbsp;</label>
                                                    <a class="btn btn-success btn-block js-reset-filter-btn"
                                                        href="javascript:;">Reset Search</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-xs-12 col-sm-8 col-md-8 col-lg-8">
                            </div>
                            <div class="col-xs-12 col-sm-2 col-md-2 col-lg-2">
                            </div>
                            <div class="col-xs-12 col-sm-2 col-md-2 col-lg-2">
                                <div class="field-row">
                                    <label class="">&nbsp;</label>
                                    <a class="btn btn-success btn-block" id="add_new_reviewer" href="#">Add Reviewee</a>
                                </div>
                            </div>
                        </div>

                        <!-- Table -->

                        <div class="cs-s">
                            <!-- Loader -->
                            <div class="table-responsive table-outer">
                                <table class="table table-striped table-bordered">
                                    <thead>
                                        <th>Reviewe Title</th>
                                        <th>Reviewee Name</th>
                                        <th>Reviewer(s)</th>
                                        <th>Status</th>
                                        <th>Date</th>
                                        <th class="col-sm-4">Actions</th>
                                    </thead>
                                    <tbody id="fill_by_ajax">
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="View_Review_Modal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header modal-header-bg">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title" id="review_title"></h4>
            </div>
            <div class="modal-body">
                <div class="table-responsive table-outer">
                    <table class="table table-striped table-bordered">
                        <thead>
                            <th col-xs-5>Reviewee Name</th>
                            <th col-xs-6>Reviewer Name</th>
                            <th col-xs-1>Status</th>
                        </thead>
                        <tbody id="review_detail_table">
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>

<div id="Edit_Review_Modal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header modal-header-bg">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title" id="edit_review_title">Edit Review</h4>
            </div>
            <div class="modal-body">
                <input type="hidden" id="js-portal-review-employees-id" value="">
                <input type="hidden" id="js-portal-review-id" value="">
                <input type="hidden" id="js-conductors-sids" value="">
                <div class="form-group">
                    <label>Reviewee Name <span class="staric">*</span></label>
                    <div>
                        <input type="text" class="form-control" id="js-reviewee-name" value="" readonly>
                    </div>
                </div>
                <div class="form-group">
                    <label>Select Reviewer(s) <span class="staric">*</span></label>
                    <div>
                        <select id="js-reviewee-specific_emoloyee" multiple="true">
                            <?php if (!empty($active_employees)) { ?>
                            <?php foreach ($active_employees as $key => $active_employee) { ?>
                            <option value="<?php echo $active_employee['sid']; ?>"><?= remakeEmployeeName([
                                                'first_name' => $active_employee['first_name'],
                                                'last_name' => $active_employee['last_name'],
                                                'pay_plan_flag' => $active_employee['pay_plan_flag'],
                                                'access_level' => $active_employee['access_level'],
                                                'access_level_plus' => $active_employee['access_level_plus'],
                                                'job_title' => $active_employee['job_title'],
                                            ], true); ?></option>
                            <?php } ?>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <span id="total_conductors"></span>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" id="add_specific_review_conductor">Save</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>

<div id="Add_Review_Modal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header modal-header-bg">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title" id="edit_review_title">Add Reviewee</h4>
            </div>
            <div class="modal-body">
                <input type="hidden" id="js-portal-review-employees-id" value="">
                <input type="hidden" id="js-conductors-sids" value="">
                <div class="form-group">
                    <label>Review Title <span class="staric">*</span></label>
                    <div>
                        <select class="invoice-fields" id="add_review_title" multiple="multiple">
                            <option value="0">Please Select Title</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label>Select Reviewee<span class="staric">*</span></label>
                    <div id="disabled_reviewee">
                        <select id="js-add-reviewee" multiple="multiple">
                            <?php if (!empty($active_employees)) { ?>
                            <option value="0">Please Select Reviewee</option>
                            <?php foreach ($active_employees as $key => $active_employee) { ?>
                            <option value="<?php echo $active_employee['sid']; ?>"><?= remakeEmployeeName([
                                                'first_name' => $active_employee['first_name'],
                                                'last_name' => $active_employee['last_name'],
                                                'pay_plan_flag' => $active_employee['pay_plan_flag'],
                                                'access_level' => $active_employee['access_level'],
                                                'access_level_plus' => $active_employee['access_level_plus'],
                                                'job_title' => $active_employee['job_title'],
                                            ], true); ?></option>
                            <?php } ?>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label>Select Reviewer(s) <span class="staric">*</span></label>
                    <div>
                        <select id="js-add-conductor" multiple="true">
                            <?php if (!empty($active_employees)) { ?>
                            <?php foreach ($active_employees as $key => $active_employee) { ?>
                            <option value="<?php echo $active_employee['sid']; ?>"><?= remakeEmployeeName([
                                                'first_name' => $active_employee['first_name'],
                                                'last_name' => $active_employee['last_name'],
                                                'pay_plan_flag' => $active_employee['pay_plan_flag'],
                                                'access_level' => $active_employee['access_level'],
                                                'access_level_plus' => $active_employee['access_level_plus'],
                                                'job_title' => $active_employee['job_title'],
                                            ], true); ?></option>
                            <?php } ?>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <span id="add_new_total_conductors"></span>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" id="add_new_review_setting">Save</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>

<!-- performance-review loader -->
<div id="my_loader" class="text-center my_loader" style="display: none;">
    <div id="file_loader" class="file_loader" style="display:block; height:1353px;"></div>
    <div class="loader-icon-box">
        <i class="fa fa-refresh fa-spin my_spinner" style="visibility: visible;"></i>
        <div class="loader-text" id="performance-review-loader-text" style="display:block; margin-top: 35px;">
        </div>
    </div>
</div>

<script src="<?= base_url('assets/js/moment.min.js'); ?>"></script>
<script>
$("#selected_reviewee").select2({
    maximumSelectionLength: 1
});

$("#selected_reviewer").select2({
    maximumSelectionLength: 1
});

$('#filter-btn').on('click', function() {
    refresh_search_data();
});

$('.js-reset-filter-btn').on('click', function() {
    $('#js-title').val('');
    $("#selected_reviewee").select2("val", "");
    $("#selected_reviewer").select2("val", "");
    refresh_search_data();
});

function refresh_search_data() {
    $('#my_loader').show();
    $('#performance-review-loader-text').text('Please wait while we fetch data');
    $('#filter-btn').prop('disabled', true);
    var title = $('#js-title').val();
    var emp_sid = $("#selected_reviewee option:selected").val();
    var con_sid = $("#selected_reviewer option:selected").val();
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
        success: function(data) {
            if (data != "not_found") {
                $('#fill_by_ajax').html("");
                var obj = jQuery.parseJSON(data);
                $.each(obj, function(i, v) {
                    var review_url = base_url + v.reviews_sid + "/" + v.sid + "/" + emp_sid;
                    var row = '';
                    row += `<tr data-eid="${v.employee_sid}" data-rid="${v.reviews_sid}">`;

                    row += "<td>" + v.title + "</td>";
                    row += "<td>" + v.employee_name + "</td>";
                    row += "<td>";
                    row +=
                        "    <a href='javascript:void(0)' title='Reviewer(s) Detail' data-trigger='hover' data-toggle='popover' data-placement='left' data-content='" +
                        v.review_conductors_names +
                        "' class='action-activate custom-tooltip view_review_detail' portal_review_employees_id='" +
                        v.sid + "' employee_name='" + v.employee_name + "'>" + v.reviewer_count +
                        "</a>";
                    row += "</td>";
                    row += "<td>" + capitalizeFirstLetter(v.status) + "</td>";
                    row += "<td>" + moment(v.start_date, "YYYY-MM-DD HH:mm:ss").format(
                        "MM/DD/YYYY") + "</td>";
                    row += "<td class='col-sm-3 text-center'>";

                    row +=
                        "<a href='javascript:;' class='btn btn-success view_review_detail' title='View " +
                        v.title + "' portal_review_employees_id='" + v.sid + "' employee_name='" + v
                        .employee_name + "'>View</a>   &nbsp;"

                    row +=
                        "<a href='javascript:;' class='btn btn-success edit_review' portal_review_employees_id='" +
                        v.sid + "' portal_reviews_id='" + v.reviews_sid + "' employee_sid='" + v
                        .employee_sid + "' employee_name='" + v.employee_name + "' title='Edit " + v
                        .title + "'>Edit</a>   &nbsp;";
                    if (v.is_started == 1) {
                        row +=
                            "<a href='javascript:;' class='btn btn-danger set_reviewee_status' up_row='" +
                            v.sid + "' up_val='0' title='End Review'>End Review</a>";
                    } else if (v.is_started == 0) {
                        row +=
                            "<a href='javascript:;' class='btn btn-success set_reviewee_status' up_row='" +
                            v.sid + "' up_val='1' title='Start Review'>Start Review</a>";
                    }
                    row +=
                        " <a href='javascript:;' class='btn btn-danger jsDeleteReviewee' up_row='" +
                        v.sid + "' up_val='1' title='Delete'>Delete</a>";
                    row += "</td>";
                    row += "</tr>";
                    $('#fill_by_ajax').append(row);
                    $('[data-toggle="popover"]').popover({
                        html: true
                    });
                })
            } else {
                alertify.alert('No Record Found').set({
                    title: "WARNING!"
                });
            }
        },
        error: function() {}
    });
    $('#my_loader').hide();
}

function capitalizeFirstLetter(string) {
    return string.charAt(0).toUpperCase() + string.slice(1);
}

$(document).on('click', '.view_review_detail', function() {

    var review_title = $(this).attr('title');
    var employee_name = $(this).attr('employee_name');
    var review_employees_id = $(this).attr('portal_review_employees_id');

    var form_data = new FormData();
    form_data.append('review_employees_id', review_employees_id);

    $.ajax({
        url: '<?= base_url('performance_review/get_conductors_detail') ?>',
        cache: false,
        contentType: false,
        processData: false,
        type: 'post',
        data: form_data,
        success: function(data) {
            if (data != "not_found") {

                var obj = jQuery.parseJSON(data);;
                $.each(obj, function(i, v) {

                    var row = '';

                    row += "<tr>";
                    row += "    <td>" + employee_name + "</td>";
                    row += "    <td>" + v.conductor_name + "</td>";
                    if (v.is_completed == 0) {
                        row += "    <td class='col-sm-3 text-center'>Pending</td>";
                    } else {
                        row += "    <td class='col-sm-3 text-center'>Completed</td>";
                    }
                    row += "</tr>";

                    $('#review_detail_table').append(row);
                })
            } else {
                alertify.alert('No Record Found');
            }
        },
        error: function() {}
    });

    $('#review_title').text(review_title);
    $('#View_Review_Modal').modal('show');
});

$('#View_Review_Modal').on('hidden.bs.modal', function() {
    $('#review_detail_table').html("");
});

$(document).on('click', '.edit_review', function() {

    var reviews_id = $(this).attr('portal_reviews_id');
    var review_title = $(this).attr('title');
    var employee_sid = $(this).attr('employee_sid');
    var employee_name = $(this).attr('employee_name');
    var review_employees_id = $(this).attr('portal_review_employees_id');

    var form_data = new FormData();
    form_data.append('review_employees_id', review_employees_id);

    $.ajax({
        url: '<?= base_url('performance_review/get_conductors_detail') ?>',
        cache: false,
        contentType: false,
        processData: false,
        type: 'post',
        data: form_data,
        success: function(data) {
            if (data != "not_found") {

                var conductors = [];
                var obj = jQuery.parseJSON(data);

                $.each(obj, function(i, v) {
                    conductors.push(v.conductor_sid);
                });

                $("#js-reviewee-specific_emoloyee").select2('val', conductors);
                $("#js-conductors-sids").val(conductors);
                $('#js-portal-review-employees-id').val(review_employees_id);
                $('#js-portal-review-id').val(reviews_id);
                $('#js-reviewee-name').val(employee_name);
                $('#edit_review_title').text(review_title);
                var total_conductors = $("#js-reviewee-specific_emoloyee").select2('data').length;
                var conductor_info_text = total_conductors + ' Reviewer Selected';

                if (total_conductors > 1) {
                    conductor_info_text = total_conductors + " Reviewer's Selected";
                }

                $('#total_conductors').text(conductor_info_text)
                $('#Edit_Review_Modal').modal('show');
            } else {
                alertify.alert('No Record Found');
            }
        },
        error: function() {}
    });
});

$("#js-reviewee-specific_emoloyee").on('select2:close', function(evt) {

    var total_conductors = $(this).select2('data').length;
    var conductor_info_text = total_conductors + ' Reviewer Selected';

    if (total_conductors > 1) {
        conductor_info_text = total_conductors + " Reviewer's Selected";
    }

    $('#total_conductors').text(conductor_info_text)
});

$("#js-reviewee-specific_emoloyee").select2({
    closeOnSelect: false,
});

$('#add_specific_review_conductor').on('click', function(e) {
    e.preventDefault();
    var conductors_ids = $('#js-reviewee-specific_emoloyee').val();
    var review_employees_id = $('#js-portal-review-employees-id').val();
    var previous_conductors_ids = $("#js-conductors-sids").val();

    if (conductors_ids != '' && conductors_ids != undefined && conductors_ids != null && conductors_ids
        .length != 0) {

        var get_filter_condectors = intersect_arrays(previous_conductors_ids.split(','), conductors_ids);

        var inserts = get_filter_condectors[0].insert;
        var deletes = get_filter_condectors[0].delete;

        if (inserts.length != 0 || deletes.length != 0) {
            $('#Edit_Review_Modal').modal('hide');
            $('#my_loader').show();
            $('#performance-review-loader-text').text('Please wait, while we are processing data...');

            var form_data = new FormData();
            form_data.append('review_employees_id', review_employees_id);
            form_data.append('review_id', $('#js-portal-review-id').val());
            form_data.append('insert', inserts);
            form_data.append('delete', deletes);

            $.ajax({
                url: '<?= base_url('performance_review/update_conductors_detail') ?>',
                cache: false,
                contentType: false,
                processData: false,
                type: 'post',
                data: form_data,
                success: function(data) {
                    $('#my_loader').hide();
                    alertify.alert("Reviewers Save Successfully", function() {
                        window.location.reload();
                    }).set({
                        title: "SUCCESS"
                    });
                },
                error: function() {}
            });
        } else {
            alertify.alert("You haven't changed anything").set({
                title: "WARNING!"
            });
        }
    } else {
        alertify.alert('Please select Reviewer').set({
            title: "WARNING!"
        });
    }
})

function intersect_arrays(a, b) {
    var result = [];
    var delete_reviewers = [];
    var insert__reviewers = [];

    var sorted_a = a;
    var sorted_b = b;

    $.each(sorted_b, function(i, v) {
        var is_exist = sorted_a.includes(v);

        if (!is_exist) {
            insert__reviewers.push(v);
        }
    })

    $.each(sorted_a, function(i, v) {
        var is_exist = sorted_b.includes(v);

        if (!is_exist) {
            delete_reviewers.push(v);
        }
    })

    result.push({
        'insert': insert__reviewers,
        'delete': delete_reviewers
    });


    return result;
}

$('#add_new_reviewer').on('click', function() {
    $("#add_review_title").select2("val", "");
    $("#js-add-reviewee").select2("val", "");
    $("#js-add-conductor").select2("val", "");

    var search_title = $('#js-title').val();
    var review_sid = '';
    var selected_title = [];

    var form_data = new FormData();
    form_data.append('company_sid', <?php echo $company_sid; ?>);

    $.ajax({
        url: '<?= base_url('performance_review/get_all_review_title') ?>',
        cache: false,
        contentType: false,
        processData: false,
        type: 'post',
        data: form_data,
        success: function(data) {
            if (data != "not_found") {

                $('#add_review_title').html("");

                var obj = jQuery.parseJSON(data);
                var row = '<option value="0">Please Select Title</option>';

                $.each(obj, function(i, v) {
                    if (v.title == search_title) {
                        review_sid = v.sid;
                        // selected_title = v.title
                        selected_title.push(v.sid);
                    }

                    row += "<option value='" + v.sid + "'>" + v.title + "</option>";

                });

                $('#add_review_title').append(row);

                if (review_sid != '') {
                    update_reviewees(review_sid);
                }

                $('#Add_Review_Modal').modal('show');
                $("#add_review_title").select2('val', selected_title);
            }
        },
        error: function() {}
    });
});

$('#add_review_title').on('change', function() {
    var review_sid = $('#add_review_title').val();
    update_reviewees(review_sid);
});

function update_reviewees(review_sid) {
    var form_data = new FormData();
    form_data.append('review_sid', review_sid);
    $.ajax({
        url: '<?= base_url('performance_review/get_all_reviewee') ?>',
        cache: false,
        contentType: false,
        processData: false,
        type: 'post',
        data: form_data,
        success: function(data) {
            if (data != "not_found") {

                $('#disabled_reviewee').html("");
                $('#disabled_reviewee').append(data);

                $("#js-add-reviewee").select2({
                    maximumSelectionLength: 1
                });
            }
        },
        error: function() {}
    });
}

$("#add_review_title").select2({
    maximumSelectionLength: 1
});

$("#js-add-reviewee").select2({
    maximumSelectionLength: 1
});

$("#js-add-conductor").select2({
    closeOnSelect: false,
});

$("#js-add-conductor").on('select2:close', function(evt) {

    var total_conductors = $(this).select2('data').length;
    var conductor_info_text = total_conductors + ' Reviewer Selected';

    if (total_conductors > 1) {
        conductor_info_text = total_conductors + " Reviewer's Selected";
    }

    $('#add_new_total_conductors').text(conductor_info_text)
});

$('#add_new_review_setting').on('click', function() {
    var review_sid = $('#add_review_title').val();
    var reviewee_sid = $('#js-add-reviewee').val();
    var conductors_sids = $("#js-add-conductor").val();

    if (review_sid != 0 && review_sid != '' && review_sid != null && reviewee_sid != null && conductors_sids !=
        null) {
        $('#Add_Review_Modal').modal('hide');
        $('#my_loader').show();
        $('#performance-review-loader-text').text('Please wait, while we are processing data...');
        var form_data = new FormData();
        form_data.append('review_sid', review_sid);
        form_data.append('reviewee_sid', reviewee_sid);
        form_data.append('conductors_sids', conductors_sids);

        $.ajax({
            url: '<?= base_url('performance_review/add_new_review_detail') ?>',
            cache: false,
            contentType: false,
            processData: false,
            type: 'post',
            data: form_data,
            success: function(data) {
                $('#my_loader').hide();
                alertify.alert("Reviewers Save Successfully", function() {
                    refresh_search_data();
                    window.location.reload();
                });
            },
            error: function() {}
        });
    } else {
        var message = '';
        if (review_sid == 0 && reviewee_sid == null && conductors_sids == null) {
            message = 'Please select Title, Reviewee and Reviewer(s)';
        } else if (review_sid == 0 && reviewee_sid == null) {
            message = 'Please select Title and Reviewee';
        } else if (review_sid == 0 && conductors_sids == null) {
            message = 'Please select Title and Reviewer(s)';
        } else if (reviewee_sid == null && conductors_sids == null) {
            message = 'Please select Reviewee and Reviewer(s)';
        } else if (review_sid == 0 || review_sid == '' || review_sid == null) {
            message = 'Please select Title';
        } else if (reviewee_sid == null) {
            message = 'Please select Reviewee';
        } else if (conductors_sids == null) {
            message = 'Please select Reviewer(s)';
        }
        alertify.alert(message).set({
            title: "WARNING!"
        });
    }
});


$(document).on('click', '.set_reviewee_status', function() {
    $('#my_loader').show();
    $('#performance-review-loader-text').text('Please wait while we chenge review status');
    var update_row = $(this).attr('up_row');
    var reviewee_status = $(this).attr('up_val');
    var form_data = new FormData();
    form_data.append('update_row', update_row);
    form_data.append('reviewee_status', reviewee_status);
    $.ajax({
        url: '<?= base_url('performance_review/change_employee_review_status') ?>',
        cache: false,
        contentType: false,
        processData: false,
        type: 'post',
        data: form_data,
        success: function(data) {
            $('#my_loader').hide(0);
            if (data != "error") {
                alertify.alert("Status Changed Successfully", function() {
                    refresh_search_data();
                });
            }
        },
        error: function() {}
    });
});
// 
$(document).on('click', '.jsDeleteReviewee', function(e) {
    //
    e.preventDefault();
    //
    let obj = {
        reviewSid: 0,
        employeeSid: 0
    };
    //
    obj.reviewSid = $(this).closest('tr').data('rid');
    obj.employeeSid = $(this).closest('tr').data('eid');
    //
    alertify.confirm('Do you really want to delete this Reviewee?', () => {
        goForDelete(obj);
    }).set('labels', {
        ok: 'YES',
        cancel: 'NO'
    }).set('title', 'Confirm!');
});

//
function goForDelete(o){
    //
    o.Action = 'delete_reviewee';
    //
    $('#my_loader').show();
    //
    $.post("<?=base_url('performance/handler');?>", o, (resp) => {
        //
        $('#my_loader').hide();
        //
        alertify.alert('SUCCESS!', 'You have successfully deleted reviewee.', () => {
            window.location.reload();
        });

    });
}
</script>

<?php if (isset($review_title)) { ?>
<script>
$(document).ready(function() {
    $('#filter-btn').click();
})
</script>
<?php } ?>